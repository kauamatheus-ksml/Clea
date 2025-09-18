<?php

/**
 * Classe de conexão com o banco de dados usando o padrão Singleton.
 * Garante que apenas uma instância da conexão PDO seja criada.
 */
class Database {
    // Guarda a única instância da classe
    private static ?self $instance = null;
    
    // Guarda o objeto de conexão PDO
    public PDO $conn;

    /**
     * O construtor é privado para prevenir a criação de novas instâncias
     * com o operador 'new'.
     */
    private function __construct() {
        // Inclui as credenciais do banco de dados
        require_once __DIR__ . '/../../config/config.php';

        $dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8mb4';

        // Opções para a conexão PDO para melhor segurança e tratamento de erros
        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,      // Lança exceções em caso de erros
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,          // Retorna resultados como arrays associativos
            PDO::ATTR_EMULATE_PREPARES   => false,                     // Usa prepared statements nativos do MySQL
        ];

        try {
            // Tenta estabelecer a conexão
            $this->conn = new PDO($dsn, DB_USER, DB_PASS, $options);
        } catch (PDOException $e) {
            // Em caso de falha, interrompe a execução e exibe o erro
            // Em um ambiente de produção, o ideal seria logar o erro em um arquivo
            // e mostrar uma mensagem genérica para o usuário.
            http_response_code(500);
            die('ERRO DE CONEXÃO COM O BANCO DE DADOS: ' . $e->getMessage());
        }
    }

    /**
     * Método estático que controla o acesso à instância.
     * @return Database
     */
    public static function getInstance(): self {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Previne a clonagem da instância.
     */
    private function __clone() {}

    /**
     * Previne a desserialização da instância.
     */
    public function __wakeup() {}
}