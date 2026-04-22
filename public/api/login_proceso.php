<?php
// public/api/login_proceso.php
session_start();

// Definimos la base URL manualmente para evitar errores de archivos no encontrados
// Esto apunta a la raíz de tu carpeta public en XAMPP
if (!defined('BASE_URL')) {
    define('BASE_URL', '/proyectoViandasUniversidad/public');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user = $_POST['username'] ?? '';
    $pass = $_POST['password'] ?? '';

    // Credenciales para la universidad
    if ($user === 'admin' && $pass === '1234') {
        $_SESSION['admin_logeado'] = true;
        
        // Ahora redirigimos usando la ruta que el index.php sí entiende
        header("Location: " . BASE_URL . "/admin/panel"); 
        exit();
    } else {
        // Si falla, volvemos al login con el error
        header("Location: " . BASE_URL . "/admin/login?error=1");
        exit();
    }
}