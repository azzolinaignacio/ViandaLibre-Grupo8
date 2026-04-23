<?php
// app/views/admin/viandas.php - Admin Viandas List View

require_once __DIR__ . '/../../../includes/header.php';
require_once __DIR__ . '/../../../includes/auth.php';
requireLogin();
?>

<h1 class="mb-4">Administrar Viandas</h1>

<div class="mb-3">
    <a href="<?php echo BASE_URL; ?>/admin/viandas/create" class="btn btn-primary">Nueva Vianda</a>
</div>

<table class="table table-striped">
    <thead>
        <tr>
            <th>ID</th>
            <th>Nombre</th>
            <th>Descripción</th>
            <th>Precio</th>
            <th>Imagen</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($viandas as $vianda): ?>
            <tr>
                <td><?php echo $vianda['id_vianda']; ?></td>
                <td><?php echo $vianda['nombre']; ?></td>
                <td><?php echo substr($vianda['descripcion'], 0, 50) . '...'; ?></td>
                <td>$<?php echo $vianda['precio']; ?></td>
                <td><?php echo $vianda['imagen_url']; ?></td>
                <td>
                    <a href="<?php echo BASE_URL; ?>/admin/viandas/update/<?php echo $vianda['id_vianda']; ?>" class="btn btn-sm btn-warning">Editar</a>
                    <a href="<?php echo BASE_URL; ?>/admin/viandas/delete/<?php echo $vianda['id_vianda']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('¿Estás seguro?')">Eliminar</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?php
require_once __DIR__ . '/../../../includes/footer.php';
?>