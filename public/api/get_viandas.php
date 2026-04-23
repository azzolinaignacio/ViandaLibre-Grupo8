<?php
/**
 * DEMO TÉCNICA: API REST - Menú del Día
 * Objetivo: Conectar el Frontend con la Base de Datos y servir JSON.
 */

// PASO 1: Establecer el encabezado JSON (Requerimiento obligatorio del PDF)
header('Content-Type: application/json; charset=utf-8');

// Importamos la configuración centralizada de la base de datos
require_once '../../config/db.php'; 

// Verificamos la conexión (viene de db.php usando MySQLi)
if (!$conexion) {
    http_response_code(500);
    echo json_encode([
        "status" => 500,
        "error" => "Fallo crítico: No se pudo conectar con el Backend."
    ]);
    exit;
}

/**
 * Consulta SQL: Traer todos los platos reales (Menú del Día)
 * Como pide el PDF: SELECT * FROM viandas
 */
$query = "SELECT * FROM viandas";
$resultado = mysqli_query($conexion, $query);

if ($resultado) {
    // PASO 2: Convertir resultado de DB a Array Asociativo (Requerimiento del PDF)
    // Usamos mysqli_fetch_all para que sea compatible con json_encode
    $viandas = mysqli_fetch_all($resultado, MYSQLI_ASSOC);
    
    // Verificamos si hay datos
    if (empty($viandas)) {
        http_response_code(404);
        echo json_encode([
            "status" => 404,
            "error" => "Error 404: No se encontraron platos cargados."
        ]);
    } else {
        // Todo OK: Enviamos el JSON que el index.php va a "dibujar" con el bucle JS
        echo json_encode($viandas);
    }
} else {
    // Error en la sintaxis SQL o tabla inexistente
    http_response_code(500);
    echo json_encode([
        "status" => 500,
        "error" => "Error interno en la consulta a la base de datos."
    ]);
}

// Liberamos la memoria del resultado y cerramos la conexión
mysqli_free_result($resultado);
mysqli_close($conexion);
?>