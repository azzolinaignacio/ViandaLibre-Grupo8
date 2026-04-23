ViandaLibre Web - Grupo N° 7
Sistema web para la gestión de viandas de "Doña Rosa". En esta etapa, el Grupo 7 ha implementado la infraestructura base, la persistencia de datos y la capa de servicios API.

 Integrantes
Emmanuel Bustos

Ivan Bustos

Jeremias Sosa

Objetivo de la Entrega (Grupo 7)
El objetivo de esta fase fue establecer la columna vertebral del sistema:

Implementar el patrón MVC para la gestión de viandas.

Desarrollar una API RESTful que sirve datos en formato JSON.

Configurar la base de datos relacional y demostrar el uso de INNER JOIN para la gestión de comandas.

Estructura del Proyecto
Plaintext
viandas/
├── app/
│   ├── controladores/    # Lógica de control (ViandaController)
│   ├── models/           # Modelos con conexión MySQLi (Vianda.php)
│   └── views/            # Vistas dinámicas (catalogo.php)
├── config/               # Configuración centralizada (db.php)
├── public/               # Punto de entrada
│   └── api/              # Endpoints JSON (get_viandas, get_comanda, crear_pedido)
├── assets/               # CSS, JS e Imágenes de viandas
├── sql/                  # Scripts de creación de tablas y datos de prueba
└── tests/                # Pruebas unitarias iniciales

 Instalación y Configuración
Servidor Local: Colocar la carpeta en C:\xampp\htdocs\viandas.

Base de Datos: * Importar el archivo SQL desde la carpeta /sql en phpMyAdmin.

Base de datos: viandalibre_db.

Configuración de Conexión (config/db.php):

Puerto: Configurado por defecto en 3308 (ajustar si es necesario).

Motor: Se utiliza la extensión MySQLi (Requerimiento de cátedra).

Acceso: * Catálogo: http://localhost/viandas/public/index.php

Funcionalidades Entregadas
1. API de Datos (Backend)
GET api/get_viandas.php: Retorna el menú completo en JSON.

GET api/get_comanda.php: Demostración de INNER JOIN uniendo detalle_pedidos y viandas para mostrar la comanda de Doña Rosa (Pedido #45).

2. Persistencia y Lógica
Modelo Vianda: Operaciones CRUD preparadas con sentencias seguras (Prepared Statements).

Integridad Referencial: Implementación de ON DELETE CASCADE para asegurar la limpieza de datos en pedidos cancelados.

3. Simulación de Envío (POST)
Capacidad de recibir datos en el Body de la petición mediante JSON.stringify, procesados en el backend para futuros módulos de pedidos.