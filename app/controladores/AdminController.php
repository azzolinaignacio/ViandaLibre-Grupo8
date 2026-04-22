<?php
// Lógica simple para la demo
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user = $_POST['username'] ?? '';
    $pass = $_POST['password'] ?? '';

    // Credenciales fijas para el TP
    if ($user === 'admin' && $pass === '1234') {
        $_SESSION['admin_logeado'] = true;
        // Redirigimos al panel de comandas
        header("Location: " . BASE_URL . "/admin/panel"); 
        exit();
    } else {
        header("Location: " . BASE_URL . "/admin/login?error=1");
        exit();
    }
}