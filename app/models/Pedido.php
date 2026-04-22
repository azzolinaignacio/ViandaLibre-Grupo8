<?php
// app/models/Pedido.php - Pedido Model

require_once __DIR__ . '/../../config/db.php';

class Pedido {
    private $pdo;

    public function __construct() {
        // Traemos la conexión desde el archivo db.php
        global $pdo;
        $this->pdo = $pdo;
    }

    public function getAll() {
        $stmt = $this->pdo->query("SELECT * FROM pedidos");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM pedidos WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($data) {
        // CORRECCIÓN: Usamos $this->pdo en lugar de $this->db
        $sql = "INSERT INTO pedidos (cliente, vianda_id, total, status) 
                VALUES (:cliente, :vianda_id, :total, 'pendiente')";
        
        $stmt = $this->pdo->prepare($sql);
        
        return $stmt->execute([
            ':cliente'   => $data['cliente'],
            ':vianda_id' => $data['vianda_id'], 
            ':total'     => $data['total']
        ]);
    }

    public function updateStatus($id, $status) {
        $stmt = $this->pdo->prepare("UPDATE pedidos SET status = ? WHERE id = ?");
        return $stmt->execute([$status, $id]);
    }

    public function delete($id) {
        $stmt = $this->pdo->prepare("DELETE FROM pedidos WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
?>