<?php
// app/views/catalogo.php - Catálogo View
require_once __DIR__ . '/../../includes/header.php';
?>

<div class="container my-5">
    <div class="row mb-4">
        <div class="col text-center">
            <h1 class="display-4 fw-bold">Catálogo de Viandas</h1>
            <p class="lead text-muted">Elegí tu plato favorito y recibilo en la puerta de tu casa.</p>
            <hr class="w-25 mx-auto">
        </div>
    </div>

    <div id="contenedor-viandas" class="row">
        <div class="col-12 text-center py-5">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Cargando viandas...</span>
            </div>
            <p class="mt-2">Cargando nuestro menú...</p>
        </div>
    </div>
</div>

<?php
require_once __DIR__ . '/../../includes/footer.php';
?>