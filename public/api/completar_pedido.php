<?php
// ACTIVAR ERRORES TEMPORALMENTE
error_reporting(E_ALL);
ini_set('display_errors', 1);

header('Content-Type: application/json');


$path = __DIR__ . '/../../config/db.php';

if (!file_exists($path)) {
    echo json_encode(["success" => false, "error" => "El archivo database.php no existe en: " . $path]);
    exit;
}

require_once $path;

// 2. REVISIÓN DE VARIABLE:
// Si en database.php escribiste $conexion, cambia $pdo por $conexion aquí abajo.
if (!isset($pdo)) {
    echo json_encode(["success" => false, "error" => "La variable \$pdo no está definida en database.php"]);
    exit;
}

$res = ["success" => false];

if (isset($_GET['id'])) {
    try {
        $id = intval($_GET['id']);
        $sql = "UPDATE pedidos SET estado = 'completado' WHERE id = ?";
        $stmt = $pdo->prepare($sql);
        $result = $stmt->execute([$id]);

        if ($result) {
            $res["success"] = true;
        }
    } catch (PDOException $e) {
        $res["error"] = "Error de PDO: " . $e->getMessage();
    }
}

echo json_encode($res);