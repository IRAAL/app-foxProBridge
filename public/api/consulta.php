<?php

require_once __DIR__ . '/../../vendor/autoload.php';

use App\Core\Database;

class Consulta
{
    private Database $db;
    private array $input;
    private array $files;

    public function __construct()
    {
        $this->db = new Database();
        $rawInput = $_SERVER['REQUEST_METHOD'] === 'POST' ? $_POST : $_GET;
        $this->input = $rawInput;
        $this->files = $_FILES ?? [];
    }

    public function handle(): void
    {
        $op = trim($this->input['op'] ?? '');
        $retorno = strtolower($this->input['retorno'] ?? 'json');

        try {
            $result = $this->dispatch($op, $retorno);

            switch ($retorno) {
                case 'html':
                    header('Content-Type: text/html; charset=UTF-8');
                    echo $result;
                    break;
                case 'text':
                    header('Content-Type: text/plain; charset=UTF-8');
                    echo $result;
                    break;
                default:
                    header('Content-Type: application/json');
                    echo json_encode($result, JSON_UNESCAPED_UNICODE);
                    break;
            }
        } catch (Throwable $e) {
           \App\Helpers\Logger::log("Error en handle(): " . $e->getMessage());
            header('Content-Type: application/json');
            echo json_encode(['error' => $e->getMessage()]);
        }
    }

    private function dispatch(string $op, string $retorno): array|string
    {
         \App\Helpers\Logger::log("Dispatch recibido: op={$op}, retorno={$retorno}");

        switch ($op) {
            case 'estatus_tramite': {
                $curp = $this->sanitizeInput($this->input['curp'] ?? '');
                //\App\Helpers\Logger::log("Dispatch recibido: $curp={$curp}");
                
                $query = "SELECT 
                            u.id, 
                            u.nombre, 
                            u.curp, 
                            u.telefono, 
                            u.direccion, 
                            t.tipo_tramite, 
                            DATE_FORMAT(t.fecha_creacion, '%d/%m/%Y') AS fecha_creacion,
                            t.estatus, 
                            e.nombre AS entidad
                        FROM usuarios u
                        LEFT JOIN tramites t ON u.id = t.usuario_id
                        LEFT JOIN entidades e ON t.entidad_id = e.id
                        WHERE u.curp = :curp";

                $params = [':curp' => $curp];
                $datos = $this->db->query($query, $params, 'array');

                if ($retorno === 'html') 
                {
                    ob_start();
?>
                    <div class="card shadow-sm">
                        <div class="card-header bg-secondary text-white">
                            Resultado de la consulta
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-hover align-middle">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Nombre</th>
                                            <th>CURP</th>
                                            <th>Teléfono</th>
                                            <th>Dirección</th>
                                            <th>Trámite</th>
                                            <th>Fecha</th>
                                            <th>Estatus</th>
                                            <th>Institución</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($datos as $dato): ?>
                                            <tr>
                                                <td><?= $dato['nombre'] ?></td>
                                                <td><?= $dato['curp'] ?></td>
                                                <td><?= $dato['telefono'] ?></td>
                                                <td><?= $dato['direccion'] ?></td>
                                                <td><?= $dato['tipo_tramite'] ?></td>
                                                <td><?= $dato['fecha_creacion'] ?></td>
                                                <td><?= $dato['estatus'] ?></td>
                                                <td><?= $dato['entidad'] ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
<?php
                    return ob_get_clean();
                }
                return $datos;
            }

            case 'otro_caso':
                return ['info' => 'Otro caso aún no implementado.'];

            default:
                return ['error' => 'Operación no válida.'];
        }
    }

    private function sanitizeInput(mixed $input): mixed
    {
        if (is_array($input)) {
            return array_map([$this, 'sanitizeInput'], $input);
        }
        return addslashes(trim((string)$input));
    }
}

$consulta = new Consulta();
$consulta->handle();
