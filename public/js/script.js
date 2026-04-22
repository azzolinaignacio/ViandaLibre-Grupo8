document.addEventListener('DOMContentLoaded', () => {
    const contenedorViandas = document.getElementById('contenedor-viandas');
    
    if (contenedorViandas) {
        fetch('api/get_viandas.php')
            .then(response => response.json())
            .then(data => {
                contenedorViandas.innerHTML = '';

                data.forEach(vianda => {
                    const cardHTML = `
                        <div class="col-md-4 mb-4">
                            <div class="card h-100 shadow-sm border-0">
                                <img src="assets/img/${vianda.imagen}" 
                                     class="card-img-top" 
                                     style="height: 250px; object-fit: cover;" 
                                     alt="${vianda.nombre}">
                                <div class="card-body d-flex flex-column">
                                    <h5 class="card-title fw-bold">${vianda.nombre}</h5>
                                    <p class="card-text text-muted small">${vianda.descripcion}</p>
                                    <p class="card-text text-success fw-bold h5 mt-auto mb-3">$${vianda.precio}</p>
                                    
                                    <button class="btn btn-primary btn-agregar" 
                                            data-id="${vianda.id}" 
                                            data-nombre="${vianda.nombre}"
                                            data-precio="${vianda.precio}">
                                        Agregar al pedido
                                    </button>
                                </div>
                            </div>
                        </div>
                    `;
                    contenedorViandas.innerHTML += cardHTML;
                });
            })
            .catch(error => console.error('Error al cargar las viandas:', error));
    }
});

// Escuchamos el evento click para procesar la compra real
document.addEventListener('click', function (e) {
    if (e.target && e.target.classList.contains('btn-agregar')) {
        
        // Recolectamos los datos del botón
        const idVianda = e.target.dataset.id; // CLAVE: Ahora obtenemos el ID
        const nombreVianda = e.target.dataset.nombre;
        const precioVianda = e.target.dataset.precio;

        // Armamos el objeto para enviar (Ahora con ID para la base de datos relacional)
        const datosParaEnviar = {
            id: idVianda, 
            nombre: nombreVianda,
            precio: precioVianda
        };

        // Desactivamos el botón un segundo
        e.target.disabled = true;
        e.target.innerText = 'Enviando...';

        // FETCH para guardar en la base de datos
        fetch('api/crear_pedido.php', {
            method: 'POST',
            body: JSON.stringify(datosParaEnviar),
            headers: {
                'Content-Type': 'application/json'
            }
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                alert('¡Éxito! Tu pedido de "' + nombreVianda + '" se guardó con el ID: ' + idVianda);
            } else {
                alert('Error al guardar: ' + (data.error || 'No se pudo conectar con la DB.'));
            }
        })
        .catch(err => {
            console.error('Error:', err);
            alert('Hubo un problema de red al intentar comprar.');
        })
        .finally(() => {
            e.target.disabled = false;
            e.target.innerText = 'Agregar al pedido';
        });
    }
});