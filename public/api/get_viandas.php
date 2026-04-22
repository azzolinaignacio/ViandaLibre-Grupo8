<?php
header('Content-Type: application/json');

// IMPORTANTE: Cambiamos 'localhost' por la IP '127.0.0.1' 
// Esto obliga a PHP a usar el puerto que le digamos.
$host = "127.0.0.1"; 
$user = "root";
$pass = ""; 
$db   = "viandalibre";
$port = 3308; // El puerto que tenés en el my.ini

// Pasamos el puerto como el QUINTO parámetro
$conn = mysqli_connect($host, $user, $pass, $db, $port);

if (!$conn) {
    http_response_code(500);
    echo json_encode(['error' => 'No conecta: ' . mysqli_connect_error()]);
    exit;
}

$query = "SELECT * FROM viandas";
$resultado = mysqli_query($conn, $query);

if ($resultado) {
    $viandas = mysqli_fetch_all($resultado, MYSQLI_ASSOC);
    echo json_encode($viandas);
} else {
    http_response_code(500);
    echo json_encode(['error' => 'Error SQL']);
}

mysqli_close($conn);