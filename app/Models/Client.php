<?php

namespace App\Models;

use App\Core\Database;

class Client
{
    private Database $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    public function getAll(): array
    {
        return $this->db->fetchAll("
            SELECT c.*, u.name, u.email, u.phone, u.created_at, u.is_active
            FROM clients c
            JOIN users u ON c.user_id = u.id
            WHERE u.is_active = 1
            ORDER BY u.created_at DESC
        ");
    }

    public function getById(int $id): ?array
    {
        return $this->db->fetch("
            SELECT c.*, u.name, u.email, u.phone, u.created_at, u.is_active
            FROM clients c
            JOIN users u ON c.user_id = u.id
            WHERE c.id = ? AND u.is_active = 1
        ", [$id]);
    }

    public function getByUserId(int $userId): ?array
    {
        return $this->db->fetch("
            SELECT c.*, u.name, u.email, u.phone
            FROM clients c
            JOIN users u ON c.user_id = u.id
            WHERE c.user_id = ? AND u.is_active = 1
        ", [$userId]);
    }

    public function getStats(): array
    {
        $total = $this->db->fetch("
            SELECT COUNT(*) as count
            FROM clients c
            JOIN users u ON c.user_id = u.id
            WHERE u.is_active = 1
        ")['count'];

        $withWeddings = $this->db->fetch("
            SELECT COUNT(DISTINCT c.id) as count
            FROM clients c
            JOIN users u ON c.user_id = u.id
            JOIN weddings w ON c.id = w.client_id
            WHERE u.is_active = 1
        ")['count'];

        $upcomingWeddings = $this->db->fetch("
            SELECT COUNT(*) as count
            FROM weddings w
            JOIN clients c ON w.client_id = c.id
            JOIN users u ON c.user_id = u.id
            WHERE u.is_active = 1 AND w.wedding_date >= CURDATE()
        ")['count'];

        return [
            'total' => $total,
            'with_weddings' => $withWeddings,
            'upcoming_weddings' => $upcomingWeddings
        ];
    }

    public function create(int $userId, array $data): bool
    {
        try {
            $this->db->query("
                INSERT INTO clients (user_id, partner_name, budget_range, created_at)
                VALUES (?, ?, ?, NOW())
            ", [
                $userId,
                $data['partner_name'] ?? null,
                $data['budget_range'] ?? null
            ]);
            return true;
        } catch (\Exception $e) {
            error_log("Error creating client: " . $e->getMessage());
            return false;
        }
    }

    public function update(int $id, array $data): bool
    {
        try {
            $fields = [];
            $values = [];

            $allowedFields = ['partner_name', 'budget_range'];

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
                UPDATE clients SET " . implode(', ', $fields) . "
                WHERE id = ?
            ", $values);

            return true;
        } catch (\Exception $e) {
            error_log("Error updating client: " . $e->getMessage());
            return false;
        }
    }

    public function getWedding(int $clientId): ?array
    {
        return $this->db->fetch("
            SELECT * FROM weddings WHERE client_id = ?
        ", [$clientId]);
    }

    public function getRecentActivity(int $clientId, int $limit = 10): array
    {
        return $this->db->fetchAll("
            SELECT 'contract' as type, c.created_at, v.business_name as description
            FROM contracts c
            JOIN vendors v ON c.vendor_id = v.id
            WHERE c.client_id = ?

            UNION ALL

            SELECT 'guest' as type, g.created_at, CONCAT(g.name, ' - ', g.status) as description
            FROM guests g
            JOIN weddings w ON g.wedding_id = w.id
            WHERE w.client_id = ?

            ORDER BY created_at DESC
            LIMIT ?
        ", [$clientId, $clientId, $limit]);
    }
}
?>