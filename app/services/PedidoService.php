<?php
// app/services/PedidoService.php

class PedidoService {

    const ENVIO_GRATIS_DESDE = 5000;
    const COSTO_ENVIO        = 500;

    /**
     * Calcula el costo de envío según el total del pedido.
     * Si el total supera $5000, el envío es gratis ($0).
     */
    public function calcularEnvio(float $total): float {
        if ($total > self::ENVIO_GRATIS_DESDE) {
            return 0.0;
        }
        return self::COSTO_ENVIO;
    }

    /**
     * Valida los datos del pedido.
     * Retorna un array con los errores encontrados (vacío = sin errores).
     */
    public function validarPedido(array $data): array {
        $errores = [];

        if (empty($data['telefono'])) {
            $errores[] = 'El campo Teléfono es obligatorio.';
        }

        if (!isset($data['precio']) || (float)$data['precio'] <= 0) {
            $errores[] = 'El precio debe ser mayor a cero.';
        }

        return $errores;
    }

    /**
     * Indica si un pedido es válido (sin errores de validación).
     */
    public function esPedidoValido(array $data): bool {
        return count($this->validarPedido($data)) === 0;
    }
}
