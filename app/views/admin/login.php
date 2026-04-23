<?php
// app/views/admin/login.php - Login View

require_once __DIR__ . '/../../../includes/header.php';

if (isset($_GET['error'])) {
    echo '<div class="alert alert-danger">Credenciales incorrectas</div>';
}
?>

<h1>Login Administrador</h1>

<form method="POST" action="<?php echo BASE_URL; ?>/admin/login">
    <div class="mb-3">
        <label for="username" class="form-label">Usuario</label>
        <input type="text" class="form-control" id="username" name="username" required>
    </div>
    <div class="mb-3">
        <label for="password" class="form-label">Contraseña</label>
        <input type="password" class="form-control" id="password" name="password" required>
    </div>
    <button type="submit" class="btn btn-primary">Iniciar Sesión</button>
</form>

<?php
require_once __DIR__ . '/../../../includes/footer.php';
?>