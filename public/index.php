<?php
// public/index.php - Front Controller

require_once __DIR__ . '/../includes/auth.php';

// Simple routing
$base_path = dirname($_SERVER['SCRIPT_NAME']); // e.g., /viandalibre_web/public
$base_path = rtrim($base_path, '/');
if ($base_path === '') {
    $base_path = '/';
}
define('BASE_URL', $base_path);

$request = $_SERVER['REQUEST_URI'];
if (strpos($request, '?') !== false) {
    $request = strstr($request, '?', true);
}
if ($base_path !== '/' && strpos($request, $base_path) === 0) {
    $request = substr($request, strlen($base_path));
}
if ($request === false) {
    $request = '';
}

// --- SISTEMA DE RUTAS ---

// 1. Inicio y Catálogo
if ($request === '/' || $request === '/index.php' || $request === '/catalogo' || $request === '') {
    require_once __DIR__ . '/../app/controladores/ViandaController.php';
    $controller = new ViandaController();
    $controller->index();

// 2. Ruta para el Panel de Doña Rosa (INNER JOIN)
} elseif ($request === '/admin/panel') {
    // Cargamos directamente la vista que armamos con el fetch
    require_once __DIR__ . '/../app/views/admin/panel.php';

// 3. Gestión de Viandas (Admin)
} elseif (preg_match('/^\/admin\/viandas$/', $request)) {
    require_once __DIR__ . '/../app/controladores/ViandaController.php';
    $controller = new ViandaController();
    $controller->adminIndex(); 

} elseif (preg_match('/^\/admin\/viandas\/create$/', $request)) {
    require_once __DIR__ . '/../app/controladores/ViandaController.php';
    $controller = new ViandaController();
    $controller->create();

} elseif (preg_match('/^\/admin\/viandas\/update\/(\d+)$/', $request, $matches)) {
    require_once __DIR__ . '/../app/controladores/ViandaController.php';
    $controller = new ViandaController();
    $controller->update($matches[1]);

} elseif (preg_match('/^\/admin\/viandas\/delete\/(\d+)$/', $request, $matches)) {
    require_once __DIR__ . '/../app/controladores/ViandaController.php';
    $controller = new ViandaController();
    $controller->delete($matches[1]);

// 4. Gestión de Pedidos
} elseif (preg_match('/^\/admin\/pedidos$/', $request)) {
    require_once __DIR__ . '/../app/controladores/PedidoController.php';
    $controller = new PedidoController();
    $controller->index();

} elseif (preg_match('/^\/admin\/pedidos\/update\/(\d+)$/', $request, $matches)) {
    require_once __DIR__ . '/../app/controladores/PedidoController.php';
    $controller = new PedidoController();
    $controller->updateStatus($matches[1]);

// 5. Auth y Login
} elseif ($request === '/admin') {
    require_once __DIR__ . '/../app/controladores/ViandaController.php';
    $controller = new ViandaController();
    $controller->adminIndex();

} elseif ($request === '/admin/login') {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $username = $_POST['username'];
        $password = $_POST['password'];
        
        // Si usás la función login() de auth.php
        if (login($username, $password)) {
            // CAMBIO: Al loguear, te manda directo al panel nuevo
            header('Location: ' . BASE_URL . '/admin/panel');
        } else {
            header('Location: ' . BASE_URL . '/admin/login?error=1');
        }
        exit;
    } else {
        require_once __DIR__ . '/../app/views/admin/login.php';
    }

} elseif ($request === '/admin/logout') {
    logout();
    header('Location: ' . BASE_URL . '/admin/login');
    exit;

// 6. Error 404
} else {
    http_response_code(404);
    echo 'Página no encontrada';
}