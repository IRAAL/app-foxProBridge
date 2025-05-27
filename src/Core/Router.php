<?php

namespace App\Core;

class Router
{
    // arreglo de paginas dinamicas
    private array $routes = [
        '/' => 'home.php',
        // '/contacto' => 'contacto.php',
    ];

    public function handleRequest(): void
    {
        // Detecta automaticamente basePath (por ejemplo: /FoxProBridge/public)
        $scriptName = $_SERVER['SCRIPT_NAME'];       // /FoxProBridge/public/index.php
        $basePath = str_replace('/index.php', '', $scriptName);

        // Extrae URI real, sin parametros
        $uri = $_SERVER['REQUEST_URI'];
        $uri = parse_url($uri, PHP_URL_PATH); 
        $uri = substr($uri, strlen($basePath));

        $uri = rtrim($uri, '/');
        if ($uri === '') $uri = '/';

        // Determina la vista a cargar
        $view = $this->routes[$uri] ?? 'error404.php';

        // Carga layout + vista
        require_once __DIR__ . '/../../templates/layout/header.php';
        require_once __DIR__ . "/../../templates/{$view}";
        require_once __DIR__ . '/../../templates/layout/footer.php';
    }
}
