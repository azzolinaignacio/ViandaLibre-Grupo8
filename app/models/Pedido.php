<?php
// app/models/Pedido.php - Pedido Model

require_once __DIR__ . '/../../config/db.php';

class Pedido {
    private $pdo;

    public function __construct() {
        global $pdo;
        $this->pdo = $pdo;
    }

    public function getAll() {
        $stmt = $this->pdo->query("SELECT * FROM pedidos");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM pedidos WHERE id_pedido = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($data) {
        $stmt = $this->pdo->prepare("INSERT INTO pedidos (cliente_nombre, id_pedido, total_pago, fecha_pedido) VALUES (?, ?, ?, NOW())");
        return $stmt->execute([$data['cliente'], json_encode($data['viandas']), $data['total']]);
    }

    public function updateStatus($id, $estado) {
        $stmt = $this->pdo->prepare("UPDATE pedidos SET estado = ? WHERE id_pedido = ?");
        return $stmt->execute([$estado, $id]);
    }

    public function delete($id) {
        $stmt = $this->pdo->prepare("DELETE FROM pedidos WHERE id_pedido = ?");
        return $stmt->execute([$id]);
    }
}
?>