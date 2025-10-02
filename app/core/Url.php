<?php

namespace App\Core;

class Url
{
    private static $baseUrl = null;

    /**
     * Retorna a URL base do sistema
     */
    public static function base()
    {
        if (self::$baseUrl === null) {
            // Tenta pegar do .env primeiro
            if (defined('APP_URL')) {
                self::$baseUrl = rtrim(APP_URL, '/');
            } else {
                // Fallback: constrói a URL base automaticamente
                $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
                $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
                self::$baseUrl = $protocol . '://' . $host;
            }
        }
        return self::$baseUrl;
    }

    /**
     * Gera uma URL completa para um caminho
     *
     * @param string $path Caminho relativo (ex: '/vendors', 'login')
     * @return string URL completa
     */
    public static function to($path = '')
    {
        $path = ltrim($path, '/');
        return self::base() . '/' . $path;
    }

    /**
     * Gera uma URL de asset (CSS, JS, imagens)
     *
     * @param string $asset Caminho do asset
     * @return string URL completa do asset
     */
    public static function asset($asset)
    {
        $asset = ltrim($asset, '/');
        return self::base() . '/' . $asset;
    }

    /**
     * Redireciona para uma URL
     *
     * @param string $path Caminho para redirecionar
     * @param int $code Código HTTP (default: 302)
     */
    public static function redirect($path, $code = 302)
    {
        header('Location: ' . self::to($path), true, $code);
        exit;
    }

    /**
     * Retorna a URL atual
     *
     * @return string URL atual
     */
    public static function current()
    {
        $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
        $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
        $uri = $_SERVER['REQUEST_URI'] ?? '';
        return $protocol . '://' . $host . $uri;
    }

    /**
     * Verifica se a URL atual corresponde a um padrão
     *
     * @param string $pattern Padrão para verificar (ex: '/vendors', '/client/*')
     * @return bool
     */
    public static function is($pattern)
    {
        $currentPath = parse_url(self::current(), PHP_URL_PATH);

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
