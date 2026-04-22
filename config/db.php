<?php
// config/db.php - Database configuration corregido para puerto 3308

// Usamos 127.0.0.1 para asegurar que tome el puerto en Windows
define('DB_HOST', '127.0.0.1'); 
define('DB_PORT', '3308'); // <--- El puerto que te funciona
define('DB_NAME', 'viandalibre');
define('DB_USER', 'root');
define('DB_PASS', '');

try {
    // Agregamos port al DSN de PDO
    $dsn = "mysql:host=" . DB_HOST . ";port=" . DB_PORT . ";dbname=" . DB_NAME;
    $pdo = new PDO($dsn, DB_USER, DB_PASS);
    
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
   
    die("Error de conexión: " . $e->getMessage());
}
?>