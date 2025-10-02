<?php

namespace App\Controllers;

use App\Core\Auth;
use App\Core\Database;

class ClientController
{
    private $db;
    private $clientId;

    public function __construct()
    {
        Auth::checkRole(['client']);
        $this->db = Database::getInstance()->getConnection();
        $this->clientId = $_SESSION['user_id'];
    }

    public function dashboard()
    {
        // FR-3.1 - Dashboard: resumo do evento, contador regressivo, checklist
        $wedding = $this->getClientWedding();
        $stats = $this->getClientStats($wedding);
        $recentActivity = $this->getRecentActivity($wedding);
        $upcomingTasks = $this->getUpcomingTasks($wedding);

        require_once dirname(__DIR__) . '/Views/client/dashboard.php';
    }

    public function wedding()
    {
        // Gestão do evento principal
        $wedding = $this->getClientWedding();
        require_once dirname(__DIR__) . '/Views/client/wedding.php';
    }

    public function vendors()
    {
        // FR-3.2 - Curadoria de Fornecedores: navegar pelo portfólio curado
        $availableVendors = $this->getAvailableVendors();
        $contractedVendors = $this->getContractedVendors();

        require_once dirname(__DIR__) . '/Views/client/vendors.php';
    }

    public function contracts()
    {
        // FR-3.3 & FR-3.4 - Gestão de Contratações e Contratos Digitais
        $wedding = $this->getClientWedding();
        $contracts = $this->getClientContracts($wedding);

        require_once dirname(__DIR__) . '/Views/client/contracts.php';
    }

    public function financial()
    {
        // FR-3.5 - Módulo Financeiro: valor total, boletos, histórico
        $wedding = $this->getClientWedding();
        $financialData = $this->getFinancialData($wedding);

        require_once dirname(__DIR__) . '/Views/client/financial.php';
    }

    public function guests()
    {
        // FR-3.7 - Gestão de Convidados: lista e mapa de assentos
        $wedding = $this->getClientWedding();
        $guests = $this->getWeddingGuests($wedding);

        require_once dirname(__DIR__) . '/Views/client/guests.php';
    }

    public function messages()
    {
        // FR-3.6 - Chat Integrado: comunicação com fornecedores e Clea
        $wedding = $this->getClientWedding();
        $conversations = $this->getConversations($wedding);

        require_once dirname(__DIR__) . '/Views/client/messages.php';
    }

    private function getClientWedding()
    {
        $stmt = $this->db->prepare("
            SELECT * FROM weddings
            WHERE client_user_id = ?
            ORDER BY created_at DESC
            LIMIT 1
        ");
        $stmt->execute([$this->clientId]);
        return $stmt->fetch();
    }

    private function getClientStats($wedding)
    {
        $stats = [];

        if (!$wedding) {
            return [
                'wedding_exists' => false,
                'days_until' => 0,
                'total_value' => 0,
                'contracted_vendors' => 0,
                'pending_contracts' => 0,
                'estimated_guests' => 0
            ];
        }

        // Days until wedding
        $weddingDate = new \DateTime($wedding['wedding_date']);
        $today = new \DateTime();
        $stats['days_until'] = max(0, $today->diff($weddingDate)->days);
        $stats['wedding_exists'] = true;

        // Contract statistics
        $stmt = $this->db->prepare("
            SELECT
                COUNT(*) as total_contracts,
                SUM(total_value) as total_value,
                SUM(CASE WHEN status = 'pending_signature' THEN 1 ELSE 0 END) as pending_contracts,
                SUM(CASE WHEN status = 'active' THEN 1 ELSE 0 END) as active_contracts
            FROM contracts
            WHERE wedding_id = ?
        ");
        $stmt->execute([$wedding['id']]);
        $contractStats = $stmt->fetch();

        $stats['total_value'] = $contractStats['total_value'] ?? 0;
        $stats['contracted_vendors'] = $contractStats['total_contracts'] ?? 0;
        $stats['pending_contracts'] = $contractStats['pending_contracts'] ?? 0;
        $stats['active_contracts'] = $contractStats['active_contracts'] ?? 0;
        $stats['estimated_guests'] = $wedding['estimated_guests'] ?? 0;

        return $stats;
    }

    private function getAvailableVendors()
    {
        $stmt = $this->db->query("
            SELECT
                u.id,
                u.name,
                u.email,
                vp.business_name,
                vp.category,
                vp.description,
                vp.city,
                vp.state,
                vp.profile_image_url
            FROM users u
            JOIN vendor_profiles vp ON u.id = vp.user_id
            WHERE u.role = 'vendor'
            AND u.is_active = 1
            AND vp.is_approved = 1
            ORDER BY vp.category, vp.business_name
        ");
        return $stmt->fetchAll();
    }

    private function getContractedVendors()
    {
        $wedding = $this->getClientWedding();
        if (!$wedding) return [];

        $stmt = $this->db->prepare("
            SELECT
                c.*,
                u.name as vendor_name,
                vp.business_name,
                vp.category,
                vp.description
            FROM contracts c
            JOIN users u ON c.vendor_user_id = u.id
            JOIN vendor_profiles vp ON u.id = vp.user_id
            WHERE c.wedding_id = ?
            ORDER BY c.created_at DESC
        ");
        $stmt->execute([$wedding['id']]);
        return $stmt->fetchAll();
    }

    private function getClientContracts($wedding)
    {
        if (!$wedding) return [];

        $stmt = $this->db->prepare("
            SELECT
                c.*,
                u.name as vendor_name,
                vp.business_name,
                vp.category
            FROM contracts c
            JOIN users u ON c.vendor_user_id = u.id
            LEFT JOIN vendor_profiles vp ON u.id = vp.user_id
            WHERE c.wedding_id = ?
            ORDER BY c.created_at DESC
        ");
        $stmt->execute([$wedding['id']]);
        return $stmt->fetchAll();
    }

    private function getFinancialData($wedding)
    {
        $data = [];

        if (!$wedding) {
            return [
                'total_value' => 0,
                'paid_value' => 0,
                'pending_value' => 0,
                'contracts' => [],
                'payment_schedule' => []
            ];
        }

        // Contract financial summary
        $stmt = $this->db->prepare("
            SELECT
                SUM(total_value) as total_value,
                SUM(CASE WHEN status IN ('active', 'completed') THEN total_value ELSE 0 END) as committed_value
            FROM contracts
            WHERE wedding_id = ?
        ");
        $stmt->execute([$wedding['id']]);
        $summary = $stmt->fetch();

        $data['total_value'] = $summary['total_value'] ?? 0;
        $data['committed_value'] = $summary['committed_value'] ?? 0;
        $data['pending_value'] = $data['total_value'] - $data['committed_value'];

        // Individual contract details
        $stmt = $this->db->prepare("
            SELECT
                c.*,
                vp.business_name,
                vp.category
            FROM contracts c
            LEFT JOIN vendor_profiles vp ON c.vendor_user_id = vp.user_id
            WHERE c.wedding_id = ?
            ORDER BY c.created_at
        ");
        $stmt->execute([$wedding['id']]);
        $data['contracts'] = $stmt->fetchAll();

        // Payment schedule (simplified - 40% down payment, rest before wedding)
        $data['payment_schedule'] = [];
        foreach ($data['contracts'] as $contract) {
            if ($contract['status'] === 'active') {
                $downPayment = $contract['total_value'] * 0.4;
                $finalPayment = $contract['total_value'] * 0.6;

                $data['payment_schedule'][] = [
                    'contract_id' => $contract['id'],
                    'business_name' => $contract['business_name'],
                    'down_payment' => $downPayment,
                    'final_payment' => $finalPayment,
                    'down_payment_date' => $contract['signed_at'] ?? $contract['created_at'],
                    'final_payment_date' => date('Y-m-d', strtotime($wedding['wedding_date'] . ' -30 days'))
                ];
            }
        }

        return $data;
    }

    private function getWeddingGuests($wedding)
    {
        if (!$wedding) return [];

        $stmt = $this->db->prepare("
            SELECT * FROM guests
            WHERE wedding_id = ?
            ORDER BY name
        ");
        $stmt->execute([$wedding['id']]);
        return $stmt->fetchAll();
    }

    private function getConversations($wedding)
    {
        if (!$wedding) return [];

        // Get conversations with vendors
        $stmt = $this->db->prepare("
            SELECT DISTINCT
                u.id as vendor_id,
                u.name as vendor_name,
                vp.business_name,
                vp.category,
                COUNT(m.id) as message_count,
                MAX(m.sent_at) as last_message_date
            FROM contracts c
            JOIN users u ON c.vendor_user_id = u.id
            LEFT JOIN vendor_profiles vp ON u.id = vp.user_id
            LEFT JOIN messages m ON c.wedding_id = m.wedding_id AND (
                (m.sender_user_id = ? AND m.receiver_user_id = u.id) OR
                (m.sender_user_id = u.id AND m.receiver_user_id = ?)
            )
            WHERE c.wedding_id = ?
            GROUP BY u.id
            ORDER BY last_message_date DESC
        ");
        $stmt->execute([$this->clientId, $this->clientId, $wedding['id']]);
        return $stmt->fetchAll();
    }

    private function getRecentActivity($wedding)
    {
        if (!$wedding) return [];

        // Get recent contracts, messages, and other activities
        $stmt = $this->db->prepare("
            SELECT
                'contract' as activity_type,
                c.created_at as activity_date,
                CONCAT('Contrato com ', vp.business_name, ' criado') as activity_description,
                c.status as activity_status
            FROM contracts c
            LEFT JOIN vendor_profiles vp ON c.vendor_user_id = vp.user_id
            WHERE c.wedding_id = ?

            UNION ALL

            SELECT
                'message' as activity_type,
                m.sent_at as activity_date,
                CONCAT('Nova mensagem de ', u.name) as activity_description,
                '' as activity_status
            FROM messages m
            JOIN users u ON m.sender_user_id = u.id
            WHERE m.wedding_id = ? AND m.receiver_user_id = ?

            ORDER BY activity_date DESC
            LIMIT 10
        ");
        $stmt->execute([$wedding['id'], $wedding['id'], $this->clientId]);
        return $stmt->fetchAll();
    }

    private function getUpcomingTasks($wedding)
    {
        if (!$wedding) return [];

        $weddingDate = new \DateTime($wedding['wedding_date']);
        $today = new \DateTime();
        $daysUntil = $today->diff($weddingDate)->days;

        // Organized timeline phases
        $phases = [];

        // 6+ Months Before (180+ days)
        if ($daysUntil > 150) {
            $tasks = [
                ['id' => 1, 'task' => 'Definir orçamento do casamento', 'priority' => 'high', 'due_date' => date('Y-m-d', strtotime($wedding['wedding_date'] . ' -180 days')), 'completed' => true, 'vendor' => '', 'description' => 'Estabelecer o budget total disponível para todas as despesas'],
                ['id' => 2, 'task' => 'Escolher data do casamento', 'priority' => 'high', 'due_date' => date('Y-m-d', strtotime($wedding['wedding_date'] . ' -180 days')), 'completed' => true, 'vendor' => '', 'description' => ''],
                ['id' => 3, 'task' => 'Elaborar lista preliminar de convidados', 'priority' => 'high', 'due_date' => date('Y-m-d', strtotime($wedding['wedding_date'] . ' -150 days')), 'completed' => false, 'vendor' => '', 'description' => 'Lista inicial para calcular tamanho do evento'],
                ['id' => 4, 'task' => 'Pesquisar e visitar locais para cerimônia', 'priority' => 'high', 'due_date' => date('Y-m-d', strtotime($wedding['wedding_date'] . ' -150 days')), 'completed' => false, 'vendor' => '', 'description' => 'Reservar com antecedência para garantir disponibilidade'],
                ['id' => 5, 'task' => 'Pesquisar e visitar locais para festa', 'priority' => 'high', 'due_date' => date('Y-m-d', strtotime($wedding['wedding_date'] . ' -150 days')), 'completed' => false, 'vendor' => '', 'description' => ''],
            ];
            $completed = count(array_filter($tasks, fn($t) => $t['completed']));
            $phases[] = [
                'period' => '6months',
                'title' => '6+ Meses Antes - Planejamento Inicial',
                'duration' => 'Até ' . date('d/m/Y', strtotime($wedding['wedding_date'] . ' -150 days')),
                'completion' => round(($completed / count($tasks)) * 100),
                'completed_tasks' => $completed,
                'total_tasks' => count($tasks),
                'tasks' => $tasks
            ];
        }

        // 3-6 Months Before (90-150 days)
        if ($daysUntil > 60) {
            $tasks = [
                ['id' => 6, 'task' => 'Contratar fotógrafo e videomaker', 'priority' => 'high', 'due_date' => date('Y-m-d', strtotime($wedding['wedding_date'] . ' -120 days')), 'completed' => true, 'vendor' => 'Foto Studio Premium', 'description' => 'Profissionais mais concorridos precisam ser reservados cedo'],
                ['id' => 7, 'task' => 'Escolher e encomendar vestido de noiva', 'priority' => 'high', 'due_date' => date('Y-m-d', strtotime($wedding['wedding_date'] . ' -120 days')), 'completed' => true, 'vendor' => '', 'description' => 'Tempo necessário para ajustes e alterações'],
                ['id' => 8, 'task' => 'Contratar banda ou DJ', 'priority' => 'medium', 'due_date' => date('Y-m-d', strtotime($wedding['wedding_date'] . ' -100 days')), 'completed' => false, 'vendor' => '', 'description' => ''],
                ['id' => 9, 'task' => 'Definir cardápio e contratar buffet/catering', 'priority' => 'high', 'due_date' => date('Y-m-d', strtotime($wedding['wedding_date'] . ' -90 days')), 'completed' => true, 'vendor' => 'Buffet Elegance', 'description' => 'Inclui degustação e definição de menu'],
                ['id' => 10, 'task' => 'Contratar decoração floral', 'priority' => 'medium', 'due_date' => date('Y-m-d', strtotime($wedding['wedding_date'] . ' -90 days')), 'completed' => false, 'vendor' => '', 'description' => 'Bouquet, arranjos e decoração geral'],
            ];
            $completed = count(array_filter($tasks, fn($t) => $t['completed']));
            $phases[] = [
                'period' => '3months',
                'title' => '3-6 Meses Antes - Fornecedores Principais',
                'duration' => date('d/m/Y', strtotime($wedding['wedding_date'] . ' -150 days')) . ' a ' . date('d/m/Y', strtotime($wedding['wedding_date'] . ' -90 days')),
                'completion' => round(($completed / count($tasks)) * 100),
                'completed_tasks' => $completed,
                'total_tasks' => count($tasks),
                'tasks' => $tasks
            ];
        }

        // 1-3 Months Before (30-90 days)
        if ($daysUntil > 7) {
            $tasks = [
                ['id' => 11, 'task' => 'Finalizar e enviar convites', 'priority' => 'high', 'due_date' => date('Y-m-d', strtotime($wedding['wedding_date'] . ' -60 days')), 'completed' => false, 'vendor' => '', 'description' => 'Save the date + convite formal'],
                ['id' => 12, 'task' => 'Contratar cerimonialista', 'priority' => 'medium', 'due_date' => date('Y-m-d', strtotime($wedding['wedding_date'] . ' -60 days')), 'completed' => false, 'vendor' => '', 'description' => 'Para coordenar o dia do evento'],
                ['id' => 13, 'task' => 'Provar bolo de casamento', 'priority' => 'medium', 'due_date' => date('Y-m-d', strtotime($wedding['wedding_date'] . ' -45 days')), 'completed' => false, 'vendor' => 'Confeitaria Doce Momento', 'description' => 'Degustação e definição de sabores'],
                ['id' => 14, 'task' => 'Primeira prova do vestido', 'priority' => 'high', 'due_date' => date('Y-m-d', strtotime($wedding['wedding_date'] . ' -45 days')), 'completed' => false, 'vendor' => '', 'description' => 'Primeira sessão de ajustes'],
                ['id' => 15, 'task' => 'Definir alianças', 'priority' => 'medium', 'due_date' => date('Y-m-d', strtotime($wedding['wedding_date'] . ' -30 days')), 'completed' => false, 'vendor' => '', 'description' => 'Escolha e gravação das alianças'],
            ];
            $completed = count(array_filter($tasks, fn($t) => $t['completed']));
            $phases[] = [
                'period' => '1month',
                'title' => '1-3 Meses Antes - Detalhes e Confirmações',
                'duration' => date('d/m/Y', strtotime($wedding['wedding_date'] . ' -90 days')) . ' a ' . date('d/m/Y', strtotime($wedding['wedding_date'] . ' -30 days')),
                'completion' => round(($completed / count($tasks)) * 100),
                'completed_tasks' => $completed,
                'total_tasks' => count($tasks),
                'tasks' => $tasks
            ];
        }

        // Final Week (0-7 days)
        $tasks = [
            ['id' => 16, 'task' => 'Confirmar número final de convidados', 'priority' => 'high', 'due_date' => date('Y-m-d', strtotime($wedding['wedding_date'] . ' -7 days')), 'completed' => false, 'vendor' => '', 'description' => 'Para acerto final com buffet'],
            ['id' => 17, 'task' => 'Reunião final com todos os fornecedores', 'priority' => 'high', 'due_date' => date('Y-m-d', strtotime($wedding['wedding_date'] . ' -5 days')), 'completed' => false, 'vendor' => '', 'description' => 'Alinhamento de horários e detalhes'],
            ['id' => 18, 'task' => 'Prova final do vestido', 'priority' => 'high', 'due_date' => date('Y-m-d', strtotime($wedding['wedding_date'] . ' -3 days')), 'completed' => false, 'vendor' => '', 'description' => 'Última sessão de ajustes'],
            ['id' => 19, 'task' => 'Preparar cronograma detalhado do dia', 'priority' => 'high', 'due_date' => date('Y-m-d', strtotime($wedding['wedding_date'] . ' -2 days')), 'completed' => false, 'vendor' => 'Cerimonialista', 'description' => 'Timeline minuto a minuto'],
            ['id' => 20, 'task' => 'Spa day e relaxamento', 'priority' => 'low', 'due_date' => date('Y-m-d', strtotime($wedding['wedding_date'] . ' -1 day')), 'completed' => false, 'vendor' => '', 'description' => 'Cuidar de si antes do grande dia'],
        ];
        $completed = count(array_filter($tasks, fn($t) => $t['completed']));
        $phases[] = [
            'period' => 'final',
            'title' => 'Semana Final - Últimos Preparativos',
            'duration' => date('d/m/Y', strtotime($wedding['wedding_date'] . ' -7 days')) . ' a ' . date('d/m/Y', strtotime($wedding['wedding_date'])),
            'completion' => round(($completed / count($tasks)) * 100),
            'completed_tasks' => $completed,
            'total_tasks' => count($tasks),
            'tasks' => $tasks
        ];

        return $phases;
    }
}
?>