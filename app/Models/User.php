<?php

namespace App\Models;

use App\Core\Database;

class User
{
    private Database $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    public function getAll(): array
    {
        return $this->db->fetchAll("
            SELECT id, name, email, role, created_at, is_active,
                   last_login, phone, avatar_url
            FROM users
            WHERE is_active = 1
            ORDER BY created_at DESC
        ");
    }

    public function getById(int $id): ?array
    {
        return $this->db->fetch("
            SELECT * FROM users WHERE id = ? AND is_active = 1
        ", [$id]);
    }

    public function getByEmail(string $email): ?array
    {
        return $this->db->fetch("
            SELECT * FROM users WHERE email = ? AND is_active = 1
        ", [$email]);
    }

    public function getStats(): array
    {
        $totalUsers = $this->db->fetch("
            SELECT COUNT(*) as count FROM users WHERE is_active = 1
        ")['count'];

        $newThisMonth = $this->db->fetch("
            SELECT COUNT(*) as count FROM users
            WHERE is_active = 1 AND created_at >= DATE_SUB(NOW(), INTERVAL 1 MONTH)
        ")['count'];

        $byRole = $this->db->fetchAll("
            SELECT role, COUNT(*) as count
            FROM users
            WHERE is_active = 1
            GROUP BY role
        ");

        return [
            'total' => $totalUsers,
            'new_month' => $newThisMonth,
            'by_role' => $byRole
        ];
    }

    public function create(array $data): bool
    {
        try {
            $this->db->query("
                INSERT INTO users (name, email, password_hash, role, phone, created_at, is_active)
                VALUES (?, ?, ?, ?, ?, NOW(), 1)
            ", [
                $data['name'],
                $data['email'],
                password_hash($data['password'], PASSWORD_DEFAULT),
                $data['role'],
                $data['phone'] ?? null
            ]);
            return true;
        } catch (\Exception $e) {
            error_log("Error creating user: " . $e->getMessage());
            return false;
        }
    }

    public function update(int $id, array $data): bool
    {
        try {
            $fields = [];
            $values = [];

            foreach ($data as $key => $value) {
                if (in_array($key, ['name', 'email', 'phone', 'role'])) {
                    $fields[] = "$key = ?";
                    $values[] = $value;
                }
            }

            if (empty($fields)) {
                return false;
            }

            $values[] = $id;

            $this->db->query("
                UPDATE users SET " . implode(', ', $fields) . "
                WHERE id = ? AND is_active = 1
            ", $values);

            return true;
        } catch (\Exception $e) {
            error_log("Error updating user: " . $e->getMessage());
            return false;
        }
    }

    public function deactivate(int $id): bool
    {
        try {
            $this->db->query("
                UPDATE users SET is_active = 0 WHERE id = ?
            ", [$id]);
            return true;
        } catch (\Exception $e) {
            error_log("Error deactivating user: " . $e->getMessage());
            return false;
        }
    }

    public function updateLastLogin(int $id): bool
    {
        try {
            $this->db->query("
                UPDATE users SET last_login = NOW() WHERE id = ?
            ", [$id]);
            return true;
        } catch (\Exception $e) {
            error_log("Error updating last login: " . $e->getMessage());
            return false;
        }
    }
}
?>