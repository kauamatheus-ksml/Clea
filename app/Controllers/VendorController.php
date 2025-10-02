<?php

namespace App\Controllers;

use App\Core\Auth;
use App\Core\Database;

class VendorController
{
    private $db;
    private $vendorId;

    public function __construct()
    {
        Auth::checkRole(['vendor']);
        $this->db = Database::getInstance()->getConnection();
        $this->vendorId = $_SESSION['user_id'];
    }

    public function dashboard()
    {
        // FR-4.1 - Dashboard: lista de próximos eventos contratados
        $data = [
            'vendorProfile' => $this->getVendorProfile(),
            'upcomingEvents' => $this->getUpcomingEvents(),
            'stats' => $this->getVendorStats(),
            'recentMessages' => $this->getRecentMessages()
        ];

        require_once dirname(__DIR__) . '/Views/vendor/dashboard.php';
    }

    public function events()
    {
        // FR-4.2 - Jornada do Cliente Compartilhada: detalhes dos eventos
        $data = [
            'events' => $this->getAllEvents(),
            'vendorProfile' => $this->getVendorProfile()
        ];
        require_once dirname(__DIR__) . '/Views/vendor/events.php';
    }

    public function financial()
    {
        // FR-4.3 - Módulo Financeiro: valores a receber, cronograma de repasses
        $data = [
            'financialData' => $this->getFinancialData(),
            'vendorProfile' => $this->getVendorProfile()
        ];
        require_once dirname(__DIR__) . '/Views/vendor/financial.php';
    }

    public function messages()
    {
        // FR-4.4 - Chat Integrado: comunicação com noivos e equipe Clea
        $data = [
            'conversations' => $this->getConversations(),
            'vendorProfile' => $this->getVendorProfile()
        ];
        require_once dirname(__DIR__) . '/Views/vendor/messages.php';
    }

    public function profile()
    {
        $data = [
            'vendorProfile' => $this->getVendorProfile()
        ];
        require_once dirname(__DIR__) . '/Views/vendor/profile.php';
    }

    private function getVendorProfile()
    {
        $stmt = $this->db->prepare("
            SELECT u.*, vp.*
            FROM users u
            JOIN vendor_profiles vp ON u.id = vp.user_id
            WHERE u.id = ?
        ");
        $stmt->execute([$this->vendorId]);
        return $stmt->fetch();
    }

    private function getUpcomingEvents()
    {
        $stmt = $this->db->prepare("
            SELECT
                c.*,
                w.wedding_date,
                w.partner_name,
                w.location_details,
                w.estimated_guests,
                u.name as client_name,
                u.email as client_email,
                u.phone as client_phone
            FROM contracts c
            JOIN weddings w ON c.wedding_id = w.id
            JOIN users u ON w.client_user_id = u.id
            WHERE c.vendor_user_id = ?
            AND w.wedding_date >= CURDATE()
            AND c.status IN ('active', 'pending_signature')
            ORDER BY w.wedding_date ASC
            LIMIT 10
        ");
        $stmt->execute([$this->vendorId]);
        return $stmt->fetchAll();
    }

    private function getVendorStats()
    {
        $stats = [];

        // Total contracts
        $stmt = $this->db->prepare("
            SELECT COUNT(*) as count
            FROM contracts
            WHERE vendor_user_id = ?
        ");
        $stmt->execute([$this->vendorId]);
        $stats['total_contracts'] = $stmt->fetch()['count'];

        // Active contracts
        $stmt = $this->db->prepare("
            SELECT COUNT(*) as count
            FROM contracts
            WHERE vendor_user_id = ? AND status = 'active'
        ");
        $stmt->execute([$this->vendorId]);
        $stats['active_contracts'] = $stmt->fetch()['count'];

        // Pending contracts
        $stmt = $this->db->prepare("
            SELECT COUNT(*) as count
            FROM contracts
            WHERE vendor_user_id = ? AND status = 'pending_signature'
        ");
        $stmt->execute([$this->vendorId]);
        $stats['pending_contracts'] = $stmt->fetch()['count'];

        // Total revenue (after commission)
        $stmt = $this->db->prepare("
            SELECT SUM(total_value * (1 - commission_rate)) as revenue
            FROM contracts
            WHERE vendor_user_id = ? AND status IN ('active', 'completed')
        ");
        $stmt->execute([$this->vendorId]);
        $stats['total_revenue'] = $stmt->fetch()['revenue'] ?? 0;

        // Revenue to receive (upcoming events)
        $stmt = $this->db->prepare("
            SELECT SUM(c.total_value * (1 - c.commission_rate)) as revenue
            FROM contracts c
            JOIN weddings w ON c.wedding_id = w.id
            WHERE c.vendor_user_id = ?
            AND c.status = 'active'
            AND w.wedding_date >= CURDATE()
        ");
        $stmt->execute([$this->vendorId]);
        $stats['pending_revenue'] = $stmt->fetch()['revenue'] ?? 0;

        return $stats;
    }

    private function getAllEvents()
    {
        $stmt = $this->db->prepare("
            SELECT
                c.*,
                w.*,
                u.name as client_name,
                u.email as client_email,
                u.phone as client_phone,
                COUNT(other_vendors.id) as other_vendor_count
            FROM contracts c
            JOIN weddings w ON c.wedding_id = w.id
            JOIN users u ON w.client_user_id = u.id
            LEFT JOIN contracts other_vendors ON w.id = other_vendors.wedding_id AND other_vendors.vendor_user_id != ?
            WHERE c.vendor_user_id = ?
            GROUP BY c.id
            ORDER BY w.wedding_date DESC
        ");
        $stmt->execute([$this->vendorId, $this->vendorId]);
        return $stmt->fetchAll();
    }

    private function getFinancialData()
    {
        $data = [];

        // Upcoming payments (events where payment will be made)
        $stmt = $this->db->prepare("
            SELECT
                c.*,
                w.wedding_date,
                u.name as client_name,
                (c.total_value * (1 - c.commission_rate)) as net_amount
            FROM contracts c
            JOIN weddings w ON c.wedding_id = w.id
            JOIN users u ON w.client_user_id = u.id
            WHERE c.vendor_user_id = ?
            AND c.status = 'active'
            AND w.wedding_date >= CURDATE()
            ORDER BY w.wedding_date ASC
        ");
        $stmt->execute([$this->vendorId]);
        $data['upcoming_payments'] = $stmt->fetchAll();

        // Payment history
        $stmt = $this->db->prepare("
            SELECT
                c.*,
                w.wedding_date,
                u.name as client_name,
                (c.total_value * (1 - c.commission_rate)) as net_amount
            FROM contracts c
            JOIN weddings w ON c.wedding_id = w.id
            JOIN users u ON w.client_user_id = u.id
            WHERE c.vendor_user_id = ?
            AND c.status = 'completed'
            ORDER BY w.wedding_date DESC
        ");
        $stmt->execute([$this->vendorId]);
        $data['payment_history'] = $stmt->fetchAll();

        // Monthly revenue chart data
        $stmt = $this->db->prepare("
            SELECT
                MONTH(w.wedding_date) as month,
                YEAR(w.wedding_date) as year,
                SUM(c.total_value * (1 - c.commission_rate)) as revenue
            FROM contracts c
            JOIN weddings w ON c.wedding_id = w.id
            WHERE c.vendor_user_id = ?
            AND c.status IN ('active', 'completed')
            GROUP BY YEAR(w.wedding_date), MONTH(w.wedding_date)
            ORDER BY year DESC, month DESC
            LIMIT 12
        ");
        $stmt->execute([$this->vendorId]);
        $data['monthly_revenue'] = $stmt->fetchAll();

        return $data;
    }

    private function getRecentMessages()
    {
        $stmt = $this->db->prepare("
            SELECT
                m.*,
                u.name as other_user_name,
                w.wedding_date
            FROM messages m
            JOIN weddings w ON m.wedding_id = w.id
            JOIN users u ON (
                CASE
                    WHEN m.sender_user_id = ? THEN m.receiver_user_id
                    ELSE m.sender_user_id
                END
            ) = u.id
            WHERE m.sender_user_id = ? OR m.receiver_user_id = ?
            ORDER BY m.sent_at DESC
            LIMIT 10
        ");
        $stmt->execute([$this->vendorId, $this->vendorId, $this->vendorId]);
        return $stmt->fetchAll();
    }

    private function getConversations()
    {
        // Get all unique conversations this vendor is part of
        $stmt = $this->db->prepare("
            SELECT DISTINCT
                w.id as wedding_id,
                w.wedding_date,
                u.name as client_name,
                COUNT(m.id) as message_count,
                MAX(m.sent_at) as last_message_date
            FROM contracts c
            JOIN weddings w ON c.wedding_id = w.id
            JOIN users u ON w.client_user_id = u.id
            LEFT JOIN messages m ON w.id = m.wedding_id
            WHERE c.vendor_user_id = ?
            GROUP BY w.id
            ORDER BY last_message_date DESC
        ");
        $stmt->execute([$this->vendorId]);
        return $stmt->fetchAll();
    }
}
?>