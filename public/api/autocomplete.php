<?php

require_once __DIR__ . '/../../vendor/autoload.php';

use App\Core\Database;

header('Content-Type: application/json');

$type = $_GET['type'] ?? '';
$query = trim($_GET['q'] ?? '');

if ($query === '') {
    echo json_encode([]);
    exit;
}

try {
    $db = new Database();

    $results = [];

    switch ($type) {
        case 'usuarios':
            $sql = "
                SELECT CONCAT(nombre, ' (', curp, ')') AS label
                FROM usuarios
                WHERE nombre LIKE :query OR curp LIKE :query
                LIMIT 10
            ";
            $results = $db->query($sql, [':query' => "%$query%"], 'array');
            break;

        case 'curps':
            $sql = "
                SELECT curp AS label
                FROM usuarios
                WHERE curp LIKE :query
                LIMIT 10
            ";
            $results = $db->query($sql, [':query' => "%$query%"], 'array');
            break;

        case 'tramites':
            $sql = "
                SELECT DISTINCT tipo_tramite AS label
                FROM tramites
                WHERE tipo_tramite LIKE :query
                LIMIT 10
            ";
            $results = $db->query($sql, [':query' => "%$query%"], 'array');
            break;

        default:
            echo json_encode(['error' => 'Tipo de búsqueda inválido']);
            exit;
    }

    echo json_encode($results, JSON_UNESCAPED_UNICODE);

} catch (\Throwable $e) {
    echo json_encode(['error' => $e->getMessage()]);
}