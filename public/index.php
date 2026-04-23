<?php
// public/index.php - Front Controller

require_once __DIR__ . '/../includes/auth.php';


$base_path = dirname($_SERVER['SCRIPT_NAME']); 
$base_path = rtrim($base_path, '/');
if ($base_path === '') {
    $base_path = '/';
}
define('BASE_URL', $base_path);

$request = $_SERVER['REQUEST_URI'];

// Limpiar la query string (ej: ?id=1)
if (strpos($request, '?') !== false) {
    $request = strstr($request, '?', true);
}


if ($base_path !== '/' && strpos($request, $base_path) === 0) {
    $request = substr($request, strlen($base_path));
}

// Normalizar rutas vacías
if ($request === false || $request === '' || $request === '/') {
    $request = '/';
}


if (strpos($request, 'api/get_viandas.php') !== false) {
    require_once __DIR__ . '/api/get_viandas.php';
    exit;

// B. Ruta para el Catálogo (Página Principal)
} elseif ($request === '/' || $request === '/index.php' || $request === '/catalogo') {
    require_once __DIR__ . '/../app/controladores/ViandaController.php';
    $controller = new ViandaController();
    $controller->index();

// C. Rutas de Administración de Viandas
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

// D. Rutas de Administración de Pedidos
} elseif (preg_match('/^\/admin\/pedidos$/', $request)) {
    require_once __DIR__ . '/../app/controladores/PedidoController.php';
    $controller = new PedidoController();
    $controller->index();
} elseif (preg_match('/^\/admin\/pedidos\/update\/(\d+)$/', $request, $matches)) {
    require_once __DIR__ . '/../app/controladores/PedidoController.php';
    $controller = new PedidoController();
    $controller->updateStatus($matches[1]);

// E. Rutas de Autenticación
} elseif ($request === '/admin') {
    header('Location: ' . BASE_URL . '/admin/viandas');
    exit;
} elseif ($request === '/admin/login') {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $username = $_POST['username'] ?? '';
        $password = $_POST['password'] ?? '';
        if (login($username, $password)) {
            header('Location: ' . BASE_URL . '/admin/viandas');
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

// F. Error 404
} else {
    http_response_code(404);
    echo "<h1>404 - No encontrado</h1>";
    echo "Carpeta base: " . BASE_URL . "<br>";
    echo "Ruta procesada: " . htmlspecialchars($request);
}
?>