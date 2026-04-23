<?php
// app/controladores/PedidoController.php - Pedido Controller

require_once __DIR__ . '/../models/Pedido.php';

class PedidoController {
    private $pedidoModel;

    public function __construct() {
        $this->pedidoModel = new Pedido();
    }

    public function index() {
        $pedidos = $this->pedidoModel->getAll();
        require_once __DIR__ . '/../views/admin/pedidos.php';
    }

    public function show($id) {
        $pedido = $this->pedidoModel->getById($id);
        // Render view for single pedido
    }

    public function create() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'cliente' => $_POST['cliente'],
                'viandas' => $_POST['viandas'], // Assume array
                'total' => $_POST['total']
            ];
            $this->pedidoModel->create($data);
            header('Location: ' . BASE_URL . '/pedidos');
        } else {
            // Show order form
        }
    }

    public function updateStatus($id) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $estado = $_POST['estado'];
            $this->pedidoModel->updateStatus($id, $estado);
            header('Location: ' . BASE_URL . '/admin/pedidos');
        }
    }

    public function delete($id) {
        $this->pedidoModel->delete($id);
        header('Location: ' . BASE_URL . '/admin/pedidos');
    }
}
?>