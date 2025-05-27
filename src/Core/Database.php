<?php

namespace App\Core;

use PDO;
use PDOException;
use InvalidArgumentException;

class Database
{
    private ?PDO $connection = null;

    public function __construct()
    {
        $config = require __DIR__ . '/../../config/config.php';
        $db = $config['db'];

        $driver = $db['driver'] ?? 'mysql';
        $host = $db['host'] ?? 'localhost';
        $dbname = $db['dbname'] ?? '';
        $user = $db['user'] ?? '';
        $password = $db['password'] ?? '';
        $port = $db['port'] ?? '3306';
        $charset = $db['charset'] ?? 'utf8mb4';

        $dsn = match ($driver) {
            'mysql' => "mysql:host=$host;port=$port;dbname=$dbname;charset=$charset",
            default => throw new InvalidArgumentException("Driver de base de datos no soportado: $driver"),
        };

        try {
            $this->connection = new PDO($dsn, $user, $password, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ]);
        } catch (PDOException $e) {
            die("Error de conexión: " . $e->getMessage());
        }
    }

    public function query(string $sql, array $params = [], string $returnType = 'array'): mixed
    {
        $stmt = $this->connection->prepare($sql);
        $stmt->execute($params);

        return match ($returnType) {
            'record' => $stmt->fetch(),
            'array' => $stmt->fetchAll(),
            'json' => json_encode($stmt->fetchAll(), JSON_UNESCAPED_UNICODE),
            'recordset' => $stmt,
            default => throw new InvalidArgumentException("Tipo de retorno inválido: $returnType"),
        };
    }

    public function multiQuery(array $queries): array
    {
        $results = [];
        foreach ($queries as $item) {
            $sql = $item['sql'];
            $params = $item['params'] ?? [];
            $type = $item['type'] ?? 'array';
            $results[] = $this->query($sql, $params, $type);
        }
        return $results;
    }

    public function disconnect(): void
    {
        $this->connection = null;
    }

    public function getConnection(): ?PDO
    {
        return $this->connection;
    }
}
