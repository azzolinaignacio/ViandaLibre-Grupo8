<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['admin_logeado']) || $_SESSION['admin_logeado'] !== true) {
    header("Location: " . BASE_URL . "/admin/login");
    exit();
}

require_once __DIR__ . '/../../../includes/header.php';
?>

<div class="d-flex flex-column min-vh-100">
    <main class="flex-grow-1">
        <div class="container mt-4">
            <div class="d-flex justify-content-between align-items-center border-bottom pb-3 mb-4">
                <div>
                    <h2 class="fw-bold">Panel de Comandas - Doña Rosa</h2>
                    <p class="text-muted mb-0">Gestión de cocina en tiempo real</p>
                </div>
                <a href="<?php echo BASE_URL; ?>/admin/logout" class="btn btn-danger shadow-sm">
                    <i class="bi bi-box-arrow-right"></i> Cerrar Sesión
                </a>
            </div>

            <div class="card shadow-sm mb-5">
                <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Pedidos a Preparar</h5>
                    <span class="badge bg-primary px-3" id="contador-pedidos">0 Pendientes</span>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Cliente</th>
                                    <th>Vianda</th>
                                    <th>Fecha</th>
                                    <th class="text-center">Estado</th>
                                </tr>
                            </thead>
                            <tbody id="tabla-comandas">
                                <tr>
                                    <td colspan="4" class="text-center py-5">
                                        <div class="spinner-border text-primary" role="status"></div>
                                        <p class="mt-2 text-muted">Cargando pedidos de hoy...</p>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <?php require_once __DIR__ . '/../../../includes/footer.php'; ?>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const tabla = document.getElementById('tabla-comandas');
        const contador = document.getElementById('contador-pedidos');

        // Actualiza el número del badge azul contando las filas presentes
        function actualizarContador() {
            const filasActivas = tabla.querySelectorAll('tr[id^="pedido-"]').length;
            contador.innerText = `${filasActivas} Pendientes`;

            if (filasActivas === 0) {
                tabla.innerHTML = '<tr><td colspan="4" class="text-center py-4 text-muted">No hay pedidos pendientes para mostrar.</td></tr>';
            }
        }

        // Carga inicial de datos filtrados (solo pendientes)
        function cargarPedidos() {
            fetch('<?php echo BASE_URL; ?>/api/get_pedidos_admin.php')
                .then(response => response.json())
                .then(data => {
                    tabla.innerHTML = '';

                    if (!data || data.length === 0) {
                        actualizarContador();
                        return;
                    }

                    data.forEach(pedido => {
                        const fila = document.createElement('tr');
                        fila.id = `pedido-${pedido.id}`;
                        fila.innerHTML = `
                            <td class="align-middle">${pedido.cliente}</td>
                            <td class="align-middle">
                                <span class="badge bg-success" style="font-size: 0.9rem;">
                                    ${pedido.vianda_nombre}
                                </span>
                            </td>
                            <td class="align-middle text-muted small">${pedido.fecha}</td>
                            <td class="text-center align-middle">
                                <button onclick="marcarCompletado(${pedido.id})" class="btn btn-sm btn-primary shadow-sm">
                                    <i class="bi bi-check-lg"></i> Completado
                                </button>
                            </td>
                        `;
                        tabla.appendChild(fila);
                    });
                    actualizarContador();
                })
                .catch(err => {
                    tabla.innerHTML = '<tr><td colspan="4" class="text-danger text-center py-4">Error al conectar con la base de datos.</td></tr>';
                });
        }

        // Función para cambiar estado sin borrar de la DB
        window.marcarCompletado = (id) => {
            if (confirm('¿Confirmas que esta vianda está lista?')) {
                fetch('<?php echo BASE_URL; ?>/api/completar_pedido.php?id=' + id)
                    .then(response => {
                        // Si el servidor da error 500, lo capturamos aquí
                        if (!response.ok) {
                            throw new Error('Error interno del servidor (500). Revisa database.php');
                        }
                        return response.json();
                    })
                    .then(data => {
                        if (data.success) {
                            const elemento = document.getElementById(`pedido-${id}`);
                            elemento.style.opacity = '0';
                            elemento.style.transform = 'translateX(20px)';
                            setTimeout(() => {
                                elemento.remove();
                                actualizarContador();
                            }, 300);
                        } else {
                            alert('Error en la base de datos: ' + data.error);
                        }
                    })
                    .catch(err => {
                        console.error('Detalle del error:', err);
                        alert('No se pudo completar: ' + err.message);
                    });
            }
        }

        cargarPedidos();
    });
</script>

<style>
    /* Estilos para fijar el footer y mejorar la tabla */
    body {
        min-height: 100vh;
        display: flex;
        flex-direction: column;
    }

    .table td {
        vertical-align: middle;
        transition: all 0.3s;
    }

    .badge {
        letter-spacing: 0.5px;
    }
</style>