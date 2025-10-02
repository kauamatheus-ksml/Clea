<?php

/**
 * Helper Functions - Funções auxiliares globais
 */

if (!function_exists('url')) {
    /**
     * Gera uma URL completa para um caminho
     *
     * @param string $path Caminho relativo (ex: '/vendors', 'login')
     * @return string URL completa
     */
    function url($path = '')
    {
        $baseUrl = defined('APP_URL') ? APP_URL : (defined('BASE_URL') ? BASE_URL : '');
        $path = ltrim($path, '/');
        return rtrim($baseUrl, '/') . '/' . $path;
    }
}

if (!function_exists('asset')) {
    /**
     * Gera uma URL de asset (CSS, JS, imagens)
     *
     * @param string $asset Caminho do asset
     * @return string URL completa do asset
     */
    function asset($asset)
    {
        return url($asset);
    }
}

if (!function_exists('redirect')) {
    /**
     * Redireciona para uma URL
     *
     * @param string $path Caminho para redirecionar
     * @param int $code Código HTTP (default: 302)
     */
    function redirect($path, $code = 302)
    {
        header('Location: ' . url($path), true, $code);
        exit;
    }
}

if (!function_exists('back')) {
    /**
     * Redireciona de volta para a página anterior
     */
    function back()
    {
        $referer = $_SERVER['HTTP_REFERER'] ?? url('/');
        header('Location: ' . $referer);
        exit;
    }
}

if (!function_exists('currentUrl')) {
    /**
     * Retorna a URL atual
     *
     * @return string URL atual
     */
    function currentUrl()
    {
        $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
        $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
        $uri = $_SERVER['REQUEST_URI'] ?? '';
        return $protocol . '://' . $host . $uri;
    }
}

if (!function_exists('isUrl')) {
    /**
     * Verifica se a URL atual corresponde a um padrão
     *
     * @param string $pattern Padrão para verificar (ex: '/vendors', '/client/*')
     * @return bool
     */
    function isUrl($pattern)
    {
        $currentPath = parse_url(currentUrl(), PHP_URL_PATH);

        // Remove trailing slash para comparação
        $currentPath = rtrim($currentPath, '/');
        $pattern = rtrim($pattern, '/');

        // Suporta wildcard (*)
        if (strpos($pattern, '*') !== false) {
            $pattern = str_replace('*', '.*', $pattern);
            return (bool) preg_match('#^' . $pattern . '$#', $currentPath);
        }

        return $currentPath === $pattern;
    }
}

if (!function_exists('e')) {
    /**
     * Escapa HTML para prevenir XSS
     *
     * @param string $value Valor para escapar
     * @return string Valor escapado
     */
    function e($value)
    {
        return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
    }
}

if (!function_exists('old')) {
    /**
     * Retorna o valor antigo de um campo de formulário
     *
     * @param string $key Chave do campo
     * @param mixed $default Valor padrão
     * @return mixed
     */
    function old($key, $default = '')
    {
        return $_SESSION['old'][$key] ?? $default;
    }
}

if (!function_exists('session')) {
    /**
     * Retorna ou define um valor de sessão
     *
     * @param string|array $key Chave ou array de chaves/valores
     * @param mixed $default Valor padrão se a chave não existir
     * @return mixed
     */
    function session($key = null, $default = null)
    {
        if (is_null($key)) {
            return $_SESSION;
        }

        if (is_array($key)) {
            foreach ($key as $k => $v) {
                $_SESSION[$k] = $v;
            }
            return null;
        }

        return $_SESSION[$key] ?? $default;
    }
}
