<?php

namespace App\Controllers;

use App\Core\Auth;
use App\Core\Database;
use App\Models\User;
use App\Models\Vendor;
use App\Models\Client;
use App\Models\Wedding;

class AdminController
{
    private $db;

    public function __construct()
    {
        Auth::checkRole(['admin']);
        $this->db = Database::getInstance()->getConnection();
    }

    public function dashboard()
    {
        // FR-5.1 - Gestão de Clientes (CRM): Visão completa de todos os casais cadastrados
        $stats = $this->getDashboardStats();
        $recentClients = $this->getRecentClients();
        $pendingVendors = $this->getPendingVendors();
        $recentContracts = $this->getRecentContracts();

        require_once dirname(__DIR__) . '/Views/admin/dashboard.php';
    }

    public function users()
    {
        // FR-5.1 - Gestão de Clientes (CRM)
        $users = $this->getAllUsers();
        require_once dirname(__DIR__) . '/Views/admin/users.php';
    }

    public function vendors()
    {
        // FR-5.2 - Gestão de Fornecedores: cadastrar, aprovar e gerenciar
        $vendors = $this->getAllVendors();
        require_once dirname(__DIR__) . '/Views/admin/vendors.php';
    }

    public function financial()
    {
        // FR-5.3 - Gestão Financeira Total: receitas, comissões, relatórios
        $financialData = $this->getFinancialData();
        require_once dirname(__DIR__) . '/Views/admin/financial.php';
    }

    public function contracts()
    {
        // FR-5.5 - Supervisão de Contratos: todos os contratos e status
        $contracts = $this->getAllContracts();
        require_once dirname(__DIR__) . '/Views/admin/contracts.php';
    }

    public function messages()
    {
        // FR-5.6 - Mediação de Chat: visualizar e intervir nas conversas
        $messages = $this->getAllMessages();
        require_once dirname(__DIR__) . '/Views/admin/messages.php';
    }

    private function getDashboardStats()
    {
        $stats = [];

        // Total users by role
        $stmt = $this->db->query("
            SELECT role, COUNT(*) as count
            FROM users
            WHERE is_active = 1
            GROUP BY role
        ");
        $userStats = $stmt->fetchAll();
        foreach ($userStats as $stat) {
            $stats['users'][$stat['role']] = $stat['count'];
        }

        // Total weddings
        $stmt = $this->db->query("SELECT COUNT(*) as count FROM weddings");
        $stats['weddings'] = $stmt->fetch()['count'];

        // Active contracts
        $stmt = $this->db->query("SELECT COUNT(*) as count FROM contracts WHERE status = 'active'");
        $stats['active_contracts'] = $stmt->fetch()['count'];

        // Pending vendor approvals
        $stmt = $this->db->query("SELECT COUNT(*) as count FROM vendor_profiles WHERE is_approved = 0");
        $stats['pending_vendors'] = $stmt->fetch()['count'];

        // Total revenue this month
        $stmt = $this->db->query("
            SELECT SUM(total_value * commission_rate) as revenue
            FROM contracts
            WHERE status IN ('active', 'completed')
            AND MONTH(created_at) = MONTH(CURRENT_DATE())
        ");
        $stats['monthly_revenue'] = $stmt->fetch()['revenue'] ?? 0;

        return $stats;
    }

    private function getRecentClients()
    {
        $stmt = $this->db->query("
            SELECT u.*, w.wedding_date, w.partner_name
            FROM users u
            LEFT JOIN weddings w ON u.id = w.client_user_id
            WHERE u.role = 'client'
            ORDER BY u.created_at DESC
            LIMIT 10
        ");
        return $stmt->fetchAll();
    }

    private function getPendingVendors()
    {
        $stmt = $this->db->query("
            SELECT u.*, vp.business_name, vp.category
            FROM users u
            JOIN vendor_profiles vp ON u.id = vp.user_id
            WHERE vp.is_approved = 0
            ORDER BY u.created_at DESC
        ");
        return $stmt->fetchAll();
    }

    private function getRecentContracts()
    {
        $stmt = $this->db->query("
            SELECT c.*, w.wedding_date, uc.name as client_name, uv.name as vendor_name
            FROM contracts c
            JOIN weddings w ON c.wedding_id = w.id
            JOIN users uc ON w.client_user_id = uc.id
            JOIN users uv ON c.vendor_user_id = uv.id
            ORDER BY c.created_at DESC
            LIMIT 10
        ");
        return $stmt->fetchAll();
    }

    private function getAllUsers()
    {
        $stmt = $this->db->query("
            SELECT u.*, vp.business_name, vp.category, vp.is_approved
            FROM users u
            LEFT JOIN vendor_profiles vp ON u.id = vp.user_id
            ORDER BY u.created_at DESC
        ");
        return $stmt->fetchAll();
    }

    private function getAllVendors()
    {
        $stmt = $this->db->query("
            SELECT u.*, vp.*
            FROM users u
            JOIN vendor_profiles vp ON u.id = vp.user_id
            ORDER BY vp.is_approved ASC, u.created_at DESC
        ");
        return $stmt->fetchAll();
    }

    private function getFinancialData()
    {
        $data = [];

        // Revenue by month
        $stmt = $this->db->query("
            SELECT
                MONTH(created_at) as month,
                YEAR(created_at) as year,
                SUM(total_value * commission_rate) as revenue,
                COUNT(*) as contracts
            FROM contracts
            WHERE status IN ('active', 'completed')
            GROUP BY YEAR(created_at), MONTH(created_at)
            ORDER BY year DESC, month DESC
            LIMIT 12
        ");
        $data['monthly_revenue'] = $stmt->fetchAll();

        // Top vendors by revenue
        $stmt = $this->db->query("
            SELECT
                u.name,
                vp.business_name,
                SUM(c.total_value) as total_contracts,
                SUM(c.total_value * c.commission_rate) as commission_earned,
                COUNT(c.id) as contract_count
            FROM contracts c
            JOIN users u ON c.vendor_user_id = u.id
            JOIN vendor_profiles vp ON u.id = vp.user_id
            WHERE c.status IN ('active', 'completed')
            GROUP BY u.id
            ORDER BY commission_earned DESC
            LIMIT 10
        ");
        $data['top_vendors'] = $stmt->fetchAll();

        return $data;
    }

    private function getAllContracts()
    {
        $stmt = $this->db->query("
            SELECT
                c.*,
                w.wedding_date,
                uc.name as client_name,
                uv.name as vendor_name,
                vp.business_name,
                vp.category
            FROM contracts c
            JOIN weddings w ON c.wedding_id = w.id
            JOIN users uc ON w.client_user_id = uc.id
            JOIN users uv ON c.vendor_user_id = uv.id
            LEFT JOIN vendor_profiles vp ON uv.id = vp.user_id
            ORDER BY c.created_at DESC
        ");
        return $stmt->fetchAll();
    }

    private function getAllMessages()
    {
        $stmt = $this->db->query("
            SELECT
                m.*,
                w.wedding_date,
                us.name as sender_name,
                ur.name as receiver_name
            FROM messages m
            JOIN weddings w ON m.wedding_id = w.id
            JOIN users us ON m.sender_user_id = us.id
            JOIN users ur ON m.receiver_user_id = ur.id
            ORDER BY m.sent_at DESC
            LIMIT 100
        ");
        return $stmt->fetchAll();
    }
}
?>