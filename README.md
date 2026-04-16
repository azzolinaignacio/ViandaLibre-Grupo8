# ViandaLibre Web - Grupo N° 6

Sistema web para la gestión de viandas de "Doña Rosa", desarrollado para la universidad.

## 👥 Integrantes
* **Delfina Ibañez**
* **Candela Aguilar**
* **Carolina Fetta**

## 🎯 Objetivo del Proyecto
El objetivo principal es implementar un sistema funcional y escalable utilizando el **patrón de diseño MVC (Modelo-Vista-Controlador)**. Se ha puesto especial énfasis en la organización de carpetas para separar la lógica de negocio, el acceso a datos y la interfaz de usuario.

---

## 📂 Estructura del Proyecto (Orden MVC)
Para cumplir con el objetivo de orden, el proyecto se organiza de la siguiente manera:

viandalibre_web/
├── app/
│   ├── controladores/    # (C) Lógica de control y manejo de peticiones
│   ├── models/           # (M) Conexión a la DB y gestión de datos
│   └── views/            # (V) Archivos HTML y presentación visual
├── config/               # Configuración de base de datos (db.php)
├── public/               # Único punto de acceso público (index.php, CSS, JS)
├── includes/             # Componentes globales (header, footer, auth)
├── sql/                  # Scripts de creación de tablas y base de datos
└── .gitignore            # Archivos excluidos de Git

> Nota: el proyecto actual usa controladores como carpeta de controladores.

## Instalación

1. Copia el proyecto a tu servidor local. Con WAMP, coloca la carpeta en `C:\wamp\www\viandalibre_web`
2. Configura la base de datos en db.php:
   - `DB_HOST`
   - `DB_NAME`
   - `DB_USER`
   - `DB_PASS`
3. Crea la base de datos ejecutando el script schema.sql en phpMyAdmin o MySQL Console.
4. Abre el sitio en el navegador con:
   - `http://localhost/viandalibre_web/public/`

## Credenciales de administrador

Por ahora el login de administrador es estático y funciona con:

- Usuario: `admin`
- Contraseña: `password`

## Uso

- **Catálogo**: `http://localhost/viandalibre_web/public/`
- **Login Admin**: `http://localhost/viandalibre_web/public/admin/login`
- **Panel Admin**: `http://localhost/viandalibre_web/public/admin`

## Funcionalidades actuales

- Catálogo de viandas mostrado desde la base de datos
- Login de administrador
- Panel admin de viandas:
  - listar viandas
  - crear viandas
  - editar viandas
  - eliminar viandas
- Panel admin de pedidos:
  - listar pedidos
  - actualizar estado de pedidos

## Funcionalidades pendientes / sugerencias

Estas son mejoras sugeridas para completar la aplicación:

- Registrar administradores reales en base de datos
- Panel de registro de administrador
- Hash de contraseñas en lugar de credenciales fijas
- Carrito de compras y proceso de pedido desde el catálogo
- Control de stock y categorías de viandas
- Validación de formularios del lado servidor y navegador
- Mejor manejo de errores y mensajes de usuario
- Optimizar rutas con un router más robusto

## Tecnologías

- PHP 7+
- MySQL
- Bootstrap 5
- HTML/CSS/JS

## Pasos recomendados

1. Configurar correctamente la base de datos
2. Probar el catálogo y el login admin
3. Implementar un registro de administradores si quieres varios usuarios
4. Agregar carrito/pedido si quieres vender viandas desde el sitio
