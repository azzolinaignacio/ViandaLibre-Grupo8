<?php 
// app/views/admin/login.php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Si ya está logueado, lo mandamos directo al panel
if (isset($_SESSION['admin_logeado']) && $_SESSION['admin_logeado'] === true) {
    header("Location: panel");
    exit();
}

require_once __DIR__ . '/../../../includes/header.php'; 
?>

<div class="d-flex flex-column min-vh-100 bg-light">
    
    <main class="flex-grow-1 d-flex align-items-center justify-content-center">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-5 col-lg-4">
                    
                    <div class="card shadow-lg border-0 rounded-4">
                        <div class="card-body p-5">
                            <div class="text-center mb-4">
                                <h3 class="fw-bold">Acceso Admin</h3>
                                <p class="text-muted">Ingresá tus credenciales</p>
                            </div>

                            <?php if (isset($_GET['error'])): ?>
                                <div class="alert alert-danger py-2 text-center" style="font-size: 0.9rem;">
                                    Usuario o contraseña incorrectos
                                </div>
                            <?php endif; ?>

                            <form action="<?php echo BASE_URL; ?>/api/login_proceso.php" method="POST">
                                <div class="mb-3">
                                    <label for="username" class="form-label small fw-bold">Usuario</label>
                                    <input type="text" name="username" id="username" class="form-control" placeholder="admin" required>
                                </div>

                                <div class="mb-4">
                                    <label for="password" class="form-label small fw-bold">Contraseña</label>
                                    <input type="password" name="password" id="password" class="form-control" placeholder="••••••••" required>
                                </div>

                                <button type="submit" class="btn btn-primary w-100 py-2 fw-bold shadow-sm">
                                    Iniciar Sesión
                                </button>
                            </form>
                        </div>
                    </div>

                    <div class="text-center mt-3">
                        <a href="<?php echo BASE_URL; ?>" class="text-decoration-none text-muted small">
                            <i class="bi bi-arrow-left"></i> Volver al Catálogo
                        </a>
                    </div>

                </div>
            </div>
        </div>
    </main>

    <?php require_once __DIR__ . '/../../../includes/footer.php'; ?>
</div>

<style>
    /* Estética extra para el login */
    .form-control:focus {
        border-color: #0d6efd;
        box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.1);
    }
    .card {
        background-color: #ffffff;
    }
</style>