<?php

namespace App\Models;

use App\Core\Database;

class Wedding
{
    private Database $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    public function getAll(): array
    {
        return $this->db->fetchAll("
            SELECT w.*, c.user_id, u.name as client_name, u.email as client_email
            FROM weddings w
            JOIN clients c ON w.client_id = c.id
            JOIN users u ON c.user_id = u.id
            WHERE u.is_active = 1
            ORDER BY w.wedding_date DESC
        ");
    }

    public function getById(int $id): ?array
    {
        return $this->db->fetch("
            SELECT w.*, c.user_id, u.name as client_name, u.email as client_email
            FROM weddings w
            JOIN clients c ON w.client_id = c.id
            JOIN users u ON c.user_id = u.id
            WHERE w.id = ? AND u.is_active = 1
        ", [$id]);
    }

    public function getByClientId(int $clientId): ?array
    {
        return $this->db->fetch("
            SELECT * FROM weddings WHERE client_id = ?
        ", [$clientId]);
    }

    public function getUpcoming(int $limit = 10): array
    {
        return $this->db->fetchAll("
            SELECT w.*, c.user_id, u.name as client_name
            FROM weddings w
            JOIN clients c ON w.client_id = c.id
            JOIN users u ON c.user_id = u.id
            WHERE w.wedding_date >= CURDATE() AND u.is_active = 1
            ORDER BY w.wedding_date ASC
            LIMIT ?
        ", [$limit]);
    }

    public function getStats(): array
    {
        $total = $this->db->fetch("
            SELECT COUNT(*) as count
            FROM weddings w
            JOIN clients c ON w.client_id = c.id
            JOIN users u ON c.user_id = u.id
            WHERE u.is_active = 1
        ")['count'];

        $upcoming = $this->db->fetch("
            SELECT COUNT(*) as count
            FROM weddings w
            JOIN clients c ON w.client_id = c.id
            JOIN users u ON c.user_id = u.id
            WHERE w.wedding_date >= CURDATE() AND u.is_active = 1
        ")['count'];

        $thisMonth = $this->db->fetch("
            SELECT COUNT(*) as count
            FROM weddings w
            JOIN clients c ON w.client_id = c.id
            JOIN users u ON c.user_id = u.id
            WHERE MONTH(w.wedding_date) = MONTH(CURDATE())
            AND YEAR(w.wedding_date) = YEAR(CURDATE())
            AND u.is_active = 1
        ")['count'];

        $avgBudget = $this->db->fetch("
            SELECT AVG(w.budget) as avg_budget
            FROM weddings w
            JOIN clients c ON w.client_id = c.id
            JOIN users u ON c.user_id = u.id
            WHERE w.budget > 0 AND u.is_active = 1
        ")['avg_budget'];

        return [
            'total' => $total,
            'upcoming' => $upcoming,
            'this_month' => $thisMonth,
            'avg_budget' => $avgBudget ? round($avgBudget, 2) : 0
        ];
    }

    public function create(int $clientId, array $data): bool
    {
        try {
            $this->db->query("
                INSERT INTO weddings (client_id, bride_name, groom_name, wedding_date, venue, budget, guest_count, style, notes, created_at)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())
            ", [
                $clientId,
                $data['bride_name'],
                $data['groom_name'],
                $data['wedding_date'],
                $data['venue'] ?? null,
                $data['budget'] ?? null,
                $data['guest_count'] ?? null,
                $data['style'] ?? null,
                $data['notes'] ?? null
            ]);
            return true;
        } catch (\Exception $e) {
            error_log("Error creating wedding: " . $e->getMessage());
            return false;
        }
    }

    public function update(int $id, array $data): bool
    {
        try {
            $fields = [];
            $values = [];

            $allowedFields = ['bride_name', 'groom_name', 'wedding_date', 'venue', 'budget', 'guest_count', 'style', 'notes'];

            foreach ($data as $key => $value) {
                if (in_array($key, $allowedFields)) {
                    $fields[] = "$key = ?";
                    $values[] = $value;
                }
            }

            if (empty($fields)) {
                return false;
            }

            $values[] = $id;

            $this->db->query("
                UPDATE weddings SET " . implode(', ', $fields) . "
                WHERE id = ?
            ", $values);

            return true;
        } catch (\Exception $e) {
            error_log("Error updating wedding: " . $e->getMessage());
            return false;
        }
    }

    public function getDaysUntil(int $weddingId): ?int
    {
        $wedding = $this->getById($weddingId);
        if (!$wedding || !$wedding['wedding_date']) {
            return null;
        }

        $weddingDate = new \DateTime($wedding['wedding_date']);
        $today = new \DateTime();

        $diff = $today->diff($weddingDate);

        return $diff->invert ? -$diff->days : $diff->days;
    }

    public function getGuests(int $weddingId): array
    {
        return $this->db->fetchAll("
            SELECT * FROM guests
            WHERE wedding_id = ?
            ORDER BY name ASC
        ", [$weddingId]);
    }

    public function getGuestStats(int $weddingId): array
    {
        $total = $this->db->fetch("
            SELECT COUNT(*) as count FROM guests WHERE wedding_id = ?
        ", [$weddingId])['count'];

        $confirmed = $this->db->fetch("
            SELECT COUNT(*) as count FROM guests WHERE wedding_id = ? AND status = 'confirmed'
        ", [$weddingId])['count'];

        $pending = $this->db->fetch("
            SELECT COUNT(*) as count FROM guests WHERE wedding_id = ? AND status = 'pending'
        ", [$weddingId])['count'];

        return [
            'total' => $total,
            'confirmed' => $confirmed,
            'pending' => $pending,
            'declined' => $total - $confirmed - $pending
        ];
    }

    public function getProgress(int $weddingId): array
    {
        $totalTasks = $this->db->fetch("
            SELECT COUNT(*) as count FROM wedding_tasks WHERE wedding_id = ?
        ", [$weddingId])['count'];

        $completedTasks = $this->db->fetch("
            SELECT COUNT(*) as count FROM wedding_tasks WHERE wedding_id = ? AND is_completed = 1
        ", [$weddingId])['count'];

        $contractedVendors = $this->db->fetch("
            SELECT COUNT(*) as count FROM contracts c
            JOIN weddings w ON c.client_id = w.client_id
            WHERE w.id = ? AND c.status = 'active'
        ", [$weddingId])['count'];

        return [
            'tasks_total' => $totalTasks,
            'tasks_completed' => $completedTasks,
            'tasks_percentage' => $totalTasks > 0 ? round(($completedTasks / $totalTasks) * 100, 1) : 0,
            'contracted_vendors' => $contractedVendors
        ];
    }
}
?>