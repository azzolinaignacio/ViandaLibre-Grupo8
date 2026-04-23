<?php
// app/controladores/ViandaController.php - Vianda Controller

require_once __DIR__ . '/../models/Vianda.php';

class ViandaController {
    private $viandaModel;

    public function __construct() {
        $this->viandaModel = new Vianda();
    }

    public function index() {
        $viandas = $this->viandaModel->getAll();
        require_once __DIR__ . '/../views/catalogo.php';
    }

    public function adminIndex() {
        $viandas = $this->viandaModel->getAll();
        require_once __DIR__ . '/../views/admin/viandas.php';
    }

    public function show($id) {
        $vianda = $this->viandaModel->getById($id);
        // Render view for single vianda
    }

    public function create() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'nombre' => $_POST['nombre'],
                'descripcion' => $_POST['descripcion'],
                'precio' => $_POST['precio'],
                'imagen' => $_POST['imagen_url']
            ];
            $this->viandaModel->create($data);
            header('Location: ' . BASE_URL . '/admin/viandas');
        } else {
            require_once __DIR__ . '/../views/admin/vianda_form.php';
        }
    }

    public function update($id) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'nombre' => $_POST['nombre'],
                'descripcion' => $_POST['descripcion'],
                'precio' => $_POST['precio'],
                'imagen' => $_POST['imagen_url']
            ];
            $this->viandaModel->update($id, $data);
            header('Location: ' . BASE_URL . '/admin/viandas');
        } else {
            $vianda = $this->viandaModel->getById($id);
            require_once __DIR__ . '/../views/admin/vianda_form.php';
        }
    }

    public function delete($id) {
        $this->viandaModel->delete($id);
        header('Location: ' . BASE_URL . '/admin/viandas');
    }
}
?>