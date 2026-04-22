<?php
header('Content-Type: application/json');
require_once '../../config/db.php'; // Asegurate que esta ruta llegue a tu db.php

try {
    // EL INNER JOIN: Traemos datos de pedidos y el nombre desde la tabla viandas
    // Cambiá tu consulta anterior por esta:
    $sql = "SELECT p.id, p.cliente, v.nombre as vianda_nombre, p.fecha 
        FROM pedidos p 
        INNER JOIN viandas v ON p.vianda_id = v.id 
        WHERE p.estado = 'pendiente' 
        ORDER BY p.fecha DESC";

    $stmt = $pdo->query($sql);
    $pedidos = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($pedidos);
} catch (Exception $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
