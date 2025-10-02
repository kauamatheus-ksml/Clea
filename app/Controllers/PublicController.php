<?php

namespace App\Controllers;

use App\Core\Database;

class PublicController
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    // Landing page principal
    public function home()
    {
        $data = [
            'title' => 'Clea Casamentos - Planejamento de Casamentos Elegante',
            'totalVendors' => $this->getTotalVendors(),
            'categories' => $this->getCategories(),
            'featuredVendors' => $this->getFeaturedVendors(6)
        ];

        require_once dirname(__DIR__) . '/Views/public/home.php';
    }

    // Galeria de fornecedores
    public function vendors()
    {
        $category = $_GET['category'] ?? null;
        $city = $_GET['city'] ?? null;
        $search = $_GET['search'] ?? null;

        $data = [
            'title' => 'Fornecedores - Clea Casamentos',
            'vendors' => $this->searchVendors($category, $city, $search),
            'categories' => $this->getCategories(),
            'cities' => $this->getCities()
        ];

        require_once dirname(__DIR__) . '/Views/public/vendors.php';
    }

    // Página de detalhes do fornecedor
    public function vendorDetail($id)
    {
        $vendor = $this->getVendorById($id);

        if (!$vendor) {
            header('Location: /vendors');
            exit;
        }

        $data = [
            'title' => $vendor['business_name'] . ' - Clea Casamentos',
            'vendor' => $vendor
        ];

        require_once dirname(__DIR__) . '/Views/public/vendor-detail.php';
    }

    // Página sobre
    public function about()
    {
        $data = [
            'title' => 'Sobre - Clea Casamentos'
        ];

        require_once dirname(__DIR__) . '/Views/public/about.php';
    }

    // Página de contato
    public function contact()
    {
        $data = [
            'title' => 'Contato - Clea Casamentos'
        ];

        require_once dirname(__DIR__) . '/Views/public/contact.php';
    }

    // Processar formulário de contato
    public function submitContact()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = $_POST['name'] ?? '';
            $email = $_POST['email'] ?? '';
            $phone = $_POST['phone'] ?? '';
            $message = $_POST['message'] ?? '';

            // Inserir lead no banco
            $stmt = $this->db->prepare("
                INSERT INTO leads (name, email, phone, message, created_at)
                VALUES (?, ?, ?, ?, NOW())
            ");

            if ($stmt->execute([$name, $email, $phone, $message])) {
                $_SESSION['success'] = 'Mensagem enviada com sucesso! Entraremos em contato em breve.';
            } else {
                $_SESSION['error'] = 'Erro ao enviar mensagem. Tente novamente.';
            }

            header('Location: /contact');
            exit;
        }
    }

    // Página de registro
    public function register()
    {
        $data = [
            'title' => 'Criar Conta - Clea Casamentos'
        ];

        require_once dirname(__DIR__) . '/Views/public/register.php';
    }

    // Processar registro
    public function submitRegister()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = $_POST['name'] ?? '';
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';
            $role = $_POST['role'] ?? 'client'; // client ou vendor

            // Validar dados
            if (empty($name) || empty($email) || empty($password)) {
                $_SESSION['error'] = 'Preencha todos os campos obrigatórios.';
                header('Location: /register');
                exit;
            }

            // Verificar se email já existe
            $stmt = $this->db->prepare("SELECT id FROM users WHERE email = ?");
            $stmt->execute([$email]);
            if ($stmt->fetch()) {
                $_SESSION['error'] = 'Este email já está cadastrado.';
                header('Location: /register');
                exit;
            }

            // Criar usuário
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $this->db->prepare("
                INSERT INTO users (name, email, password, role, is_active, created_at)
                VALUES (?, ?, ?, ?, 1, NOW())
            ");

            if ($stmt->execute([$name, $email, $hashedPassword, $role])) {
                $userId = $this->db->lastInsertId();

                // Se for fornecedor, criar perfil
                if ($role === 'vendor') {
                    $businessName = $_POST['business_name'] ?? $name;
                    $category = $_POST['category'] ?? 'outros';
                    $city = $_POST['city'] ?? '';

                    $stmt = $this->db->prepare("
                        INSERT INTO vendor_profiles (user_id, business_name, category, city, is_approved, created_at)
                        VALUES (?, ?, ?, ?, 0, NOW())
                    ");
                    $stmt->execute([$userId, $businessName, $category, $city]);
                }

                $_SESSION['success'] = 'Conta criada com sucesso! Faça login para continuar.';
                header('Location: /login');
                exit;
            } else {
                $_SESSION['error'] = 'Erro ao criar conta. Tente novamente.';
                header('Location: /register');
                exit;
            }
        }
    }

    // === Métodos privados ===

    private function getTotalVendors()
    {
        $stmt = $this->db->query("
            SELECT COUNT(*) as total
            FROM vendor_profiles
            WHERE is_approved = 1
        ");
        $result = $stmt->fetch();
        return $result['total'] ?? 0;
    }

    private function getCategories()
    {
        $stmt = $this->db->query("
            SELECT DISTINCT category, COUNT(*) as count
            FROM vendor_profiles
            WHERE is_approved = 1
            GROUP BY category
            ORDER BY category
        ");
        return $stmt->fetchAll();
    }

    private function getCities()
    {
        $stmt = $this->db->query("
            SELECT DISTINCT city, COUNT(*) as count
            FROM vendor_profiles
            WHERE is_approved = 1 AND city IS NOT NULL AND city != ''
            GROUP BY city
            ORDER BY city
        ");
        return $stmt->fetchAll();
    }

    private function getFeaturedVendors($limit = 6)
    {
        $stmt = $this->db->prepare("
            SELECT vp.*, u.name as owner_name
            FROM vendor_profiles vp
            JOIN users u ON vp.user_id = u.id
            WHERE vp.is_approved = 1
            ORDER BY RAND()
            LIMIT ?
        ");
        $stmt->execute([$limit]);
        return $stmt->fetchAll();
    }

    private function searchVendors($category = null, $city = null, $search = null)
    {
        $sql = "
            SELECT vp.*, u.name as owner_name
            FROM vendor_profiles vp
            JOIN users u ON vp.user_id = u.id
            WHERE vp.is_approved = 1
        ";

        $params = [];

        if ($category) {
            $sql .= " AND vp.category = ?";
            $params[] = $category;
        }

        if ($city) {
            $sql .= " AND vp.city = ?";
            $params[] = $city;
        }

        if ($search) {
            $sql .= " AND (vp.business_name LIKE ? OR vp.description LIKE ?)";
            $params[] = "%$search%";
            $params[] = "%$search%";
        }

        $sql .= " ORDER BY vp.business_name";

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    private function getVendorById($id)
    {
        $stmt = $this->db->prepare("
            SELECT vp.*, u.name as owner_name, u.email
            FROM vendor_profiles vp
            JOIN users u ON vp.user_id = u.id
            WHERE vp.id = ? AND vp.is_approved = 1
        ");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }
}
