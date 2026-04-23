<?php
/**
 * Modelo Vianda - Adaptado para MySQLi (Demo Técnica)
 */

require_once __DIR__ . '/../../config/db.php';

class Vianda {
    private $db;

    public function __construct() {
        // Usamos la variable global $conexion definida en config/db.php
        global $conexion;
        $this->db = $conexion;

        // Verificamos que la conexión no sea nula para evitar el Fatal Error
        if (!$this->db) {
            die("Error crítico: El modelo Vianda no pudo acceder a la conexión de la base de datos.");
        }
    }

    public function getAll() {
        // Paso 2 del PDF: Usar mysqli_query y mysqli_fetch_all
        $sql = "SELECT * FROM viandas";
        $resultado = mysqli_query($this->db, $sql);
        return mysqli_fetch_all($resultado, MYSQLI_ASSOC);
    }

    public function getById($id) {
        // Usamos sentencias preparadas de MySQLi para seguridad
        $stmt = mysqli_prepare($this->db, "SELECT * FROM viandas WHERE id_vianda = ?");
        mysqli_stmt_bind_param($stmt, "i", $id); // "i" de integer
        mysqli_stmt_execute($stmt);
        $resultado = mysqli_stmt_get_result($stmt);
        return mysqli_fetch_assoc($resultado);
    }

    public function create($data) {
        $stmt = mysqli_prepare($this->db, "INSERT INTO viandas (nombre, descripcion, precio, imagen_url) VALUES (?, ?, ?, ?)");
        mysqli_stmt_bind_param($stmt, "ssds", $data['nombre'], $data['descripcion'], $data['precio'], $data['imagen']);
        return mysqli_stmt_execute($stmt);
    }

    public function update($id, $data) {
        $stmt = mysqli_prepare($this->db, "UPDATE viandas SET nombre = ?, descripcion = ?, precio = ?, imagen_url = ? WHERE id_vianda = ?");
        mysqli_stmt_bind_param($stmt, "ssdsi", $data['nombre'], $data['descripcion'], $data['precio'], $data['imagen'], $id);
        return mysqli_stmt_execute($stmt);
    }

    public function delete($id) {
        $stmt = mysqli_prepare($this->db, "DELETE FROM viandas WHERE id_vianda = ?");
        mysqli_stmt_bind_param($stmt, "i", $id);
        return mysqli_stmt_execute($stmt);
    }
}
?>