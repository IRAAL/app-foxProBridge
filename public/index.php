<?php

// Carga autoload para dependencias de composer
require_once __DIR__ . '/../vendor/autoload.php';

// Carga logs automaticos
require_once __DIR__ . '/../src/Helpers/Logger.php';
\App\Helpers\Logger::init();

// carga controlador de rutas
require_once __DIR__ . '/../src/Core/Router.php';
// carga convertirdor de urls
require_once __DIR__ . '/../src/Helpers/url.php';


$router = new \App\Core\Router();
$router->handleRequest();
