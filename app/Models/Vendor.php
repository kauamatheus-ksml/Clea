<?php

namespace App\Models;

use App\Core\Database;

class Vendor
{
    private Database $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    public function getAll(): array
    {
        return $this->db->fetchAll("
            SELECT v.*, u.name, u.email, u.phone, u.created_at, u.is_active
            FROM vendors v
            JOIN users u ON v.user_id = u.id
            WHERE u.is_active = 1
            ORDER BY u.created_at DESC
        ");
    }

    public function getById(int $id): ?array
    {
        return $this->db->fetch("
            SELECT v.*, u.name, u.email, u.phone, u.created_at, u.is_active
            FROM vendors v
            JOIN users u ON v.user_id = u.id
            WHERE v.id = ? AND u.is_active = 1
        ", [$id]);
    }

    public function getByUserId(int $userId): ?array
    {
        return $this->db->fetch("
            SELECT v.*, u.name, u.email, u.phone
            FROM vendors v
            JOIN users u ON v.user_id = u.id
            WHERE v.user_id = ? AND u.is_active = 1
        ", [$userId]);
    }

    public function getByCategory(string $category): array
    {
        return $this->db->fetchAll("
            SELECT v.*, u.name, u.email, u.phone, u.created_at
            FROM vendors v
            JOIN users u ON v.user_id = u.id
            WHERE v.category = ? AND u.is_active = 1 AND v.is_verified = 1
            ORDER BY v.rating DESC
        ", [$category]);
    }

    public function getStats(): array
    {
        $total = $this->db->fetch("
            SELECT COUNT(*) as count
            FROM vendors v
            JOIN users u ON v.user_id = u.id
            WHERE u.is_active = 1
        ")['count'];

        $verified = $this->db->fetch("
            SELECT COUNT(*) as count
            FROM vendors v
            JOIN users u ON v.user_id = u.id
            WHERE u.is_active = 1 AND v.is_verified = 1
        ")['count'];

        $byCategory = $this->db->fetchAll("
            SELECT v.category, COUNT(*) as count
            FROM vendors v
            JOIN users u ON v.user_id = u.id
            WHERE u.is_active = 1
            GROUP BY v.category
            ORDER BY count DESC
        ");

        $avgRating = $this->db->fetch("
            SELECT AVG(rating) as avg_rating
            FROM vendors v
            JOIN users u ON v.user_id = u.id
            WHERE u.is_active = 1 AND v.rating > 0
        ")['avg_rating'];

        return [
            'total' => $total,
            'verified' => $verified,
            'by_category' => $byCategory,
            'avg_rating' => round($avgRating, 1)
        ];
    }

    public function create(int $userId, array $data): bool
    {
        try {
            $this->db->query("
                INSERT INTO vendors (user_id, business_name, category, description, website, instagram, facebook, address, city, state, zip_code, price_range, portfolio_images, created_at, is_verified)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), 0)
            ", [
                $userId,
                $data['business_name'],
                $data['category'],
                $data['description'] ?? null,
                $data['website'] ?? null,
                $data['instagram'] ?? null,
                $data['facebook'] ?? null,
                $data['address'] ?? null,
                $data['city'] ?? null,
                $data['state'] ?? null,
                $data['zip_code'] ?? null,
                $data['price_range'] ?? null,
                $data['portfolio_images'] ?? null
            ]);
            return true;
        } catch (\Exception $e) {
            error_log("Error creating vendor: " . $e->getMessage());
            return false;
        }
    }

    public function update(int $id, array $data): bool
    {
        try {
            $fields = [];
            $values = [];

            $allowedFields = ['business_name', 'category', 'description', 'website', 'instagram', 'facebook', 'address', 'city', 'state', 'zip_code', 'price_range', 'portfolio_images'];

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
                UPDATE vendors SET " . implode(', ', $fields) . "
                WHERE id = ?
            ", $values);

            return true;
        } catch (\Exception $e) {
            error_log("Error updating vendor: " . $e->getMessage());
            return false;
        }
    }

    public function verify(int $id): bool
    {
        try {
            $this->db->query("
                UPDATE vendors SET is_verified = 1 WHERE id = ?
            ", [$id]);
            return true;
        } catch (\Exception $e) {
            error_log("Error verifying vendor: " . $e->getMessage());
            return false;
        }
    }

    public function updateRating(int $id, float $rating): bool
    {
        try {
            $this->db->query("
                UPDATE vendors SET rating = ? WHERE id = ?
            ", [$rating, $id]);
            return true;
        } catch (\Exception $e) {
            error_log("Error updating vendor rating: " . $e->getMessage());
            return false;
        }
    }

    public function getRecentEvents(int $vendorId, int $limit = 5): array
    {
        return $this->db->fetchAll("
            SELECT e.*, w.bride_name, w.groom_name, w.wedding_date
            FROM events e
            JOIN weddings w ON e.wedding_id = w.id
            WHERE e.vendor_id = ?
            ORDER BY e.created_at DESC
            LIMIT ?
        ", [$vendorId, $limit]);
    }
}
?>