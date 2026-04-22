<?php
// public/api/crear_pedido.php
header('Content-Type: application/json');

// Ajustamos las rutas para llegar a los archivos desde la carpeta api
require_once '../../config/db.php';
require_once '../../app/models/Pedido.php';

$json = file_get_contents('php://input');
$data = json_decode($json, true);

if ($data) {
    try {
        $pedidoModel = new Pedido();
        
        // CAMBIO AQUÍ: Usamos 'vianda_id' y le pasamos el id que viene del fetch
        $datosParaGuardar = [
            'cliente'   => 'Usuario Web',
            'vianda_id' => $data['id'], // Recibimos el ID numérico
            'total'     => $data['total'] ?? $data['precio']
        ];

        $exito = $pedidoModel->create($datosParaGuardar);
        if ($exito) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'error' => 'Error al ejecutar el modelo']);
        }
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'No se recibieron datos']);
}