# 🍱 ViandaLibre Web - Grupo N° 7

Sistema integral para la gestión y administración de viandas de "Doña Rosa", desarrollado para la cátedra de Programación. El proyecto utiliza una arquitectura **MVC (Modelo-Vista-Controlador)** para garantizar un código organizado, escalable y fácil de mantener.

## Integrantes
* **Emmanuel Bustos**
* **Ivan Bustos**
* **Jeremías Sosa**

## Objetivo del Proyecto
Implementar una plataforma robusta que permita la visualización de productos y una administración eficiente de pedidos en tiempo real, separando la lógica de negocio de la interfaz de usuario.

---

## Estructura del Proyecto (Patrón MVC)
El sistema se organiza de la siguiente manera para separar responsabilidades:

```text
viandalibre_web/
├── app/
│   ├── controladores/    # Lógica de procesamiento de peticiones
│   ├── models/           # Interacción con la base de datos (PDO)
│   └── views/            # Interfaz de usuario (HTML/PHP)
├── config/               # Configuración de conexión (db.php)
├── public/               # Punto de entrada y activos (CSS, JS, Imágenes)
│   └── api/              # Endpoints para peticiones dinámicas (Fetch)
├── includes/             # Componentes reutilizables (Headers, Footers)
├── sql/                  # Scripts de creación y migración de BD
└── .gitignore            # Archivos excluidos del repositorio