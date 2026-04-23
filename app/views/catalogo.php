<?php
/**
 * Vista del Catálogo - Demo Técnica
 * Conecta el navegador con el Backend para traer el "Menú del Día"
 */
require_once __DIR__ . '/../../includes/header.php';
?>

<h1 class="text-center my-4">Catálogo de Viandas</h1>

<div class="container">
    <div id="contenedor-viandas" class="row">
        <div class="text-center" id="loader">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Cargando...</span>
            </div>
            <p>Buscando las mejores viandas para vos...</p>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const contenedor = document.getElementById('contenedor-viandas');
        
        // Definimos la URL de nuestra API interna (Ruta absoluta desde BASE_URL)
        const apiUrl = '<?php echo BASE_URL; ?>/api/get_viandas.php';

        // PASO 3: El fetch pide los datos y los dibuja sin recargar la página
        fetch(apiUrl)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Error 500: Falló la conexión con el servidor');
                }
                return response.json();
            })
            .then(viandas => {
                // Limpiamos el contenedor (quitamos el spinner de carga)
                contenedor.innerHTML = '';

                // Si la API devuelve un error 404 simulado (tabla vacía)
                if (viandas.error) {
                    contenedor.innerHTML = `<p class="text-center alert alert-warning">${viandas.error}</p>`;
                    return;
                }

                // Bucle .forEach para crear las cards de Bootstrap dinámicamente
                viandas.forEach(vianda => {
                    // Concatenamos la base del proyecto + la carpeta de imágenes assets/img/
                    const rutaImagen = '<?php echo BASE_URL; ?>/assets/img/' + vianda.imagen_url;

                    // Inyectamos el HTML de la tarjeta (Card)
                    contenedor.innerHTML += `
                    <div class="col-md-4 mb-4">
                        <div class="card h-100 shadow-sm border-0">
                            <img src="${rutaImagen}" 
                                 class="card-img-top" 
                                 alt="${vianda.nombre}" 
                                 style="height: 250px; width: 100%; object-fit: cover; object-position: center;"
                                 onerror="this.onerror=null; this.src='https://via.placeholder.com/300x200?text=Imagen+Próximamente'">
                            
                            <div class="card-body d-flex flex-column">
                                <h5 class="card-title text-dark">${vianda.nombre}</h5>
                                <p class="card-text text-muted small">${vianda.descripcion}</p>
                                <div class="mt-auto">
                                    <p class="card-text h5 text-primary mb-3"><strong>$${vianda.precio}</strong></p>
                                    <button class="btn btn-primary w-100 shadow-sm">Agregar al pedido</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    `;
                });
            })
            .catch(error => {
                // Manejo de error de conexión (Backend caído o error de red)
                contenedor.innerHTML = `
                    <div class="alert alert-danger text-center">
                        <h5>Error de conexión con la API</h5>
                        <p>No pudimos traer el menú. Por favor, verifica la conexión a la base de datos.</p>
                    </div>`;
                console.error('Error REST API:', error);
            });
    });
</script>

<?php
require_once __DIR__ . '/../../includes/footer.php';
?>