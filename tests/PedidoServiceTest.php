<?php
// tests/PedidoServiceTest.php

require_once __DIR__ . '/TestRunner.php';
require_once __DIR__ . '/../app/services/PedidoService.php';

$service = new PedidoService();

echo "\n\033[1m══════════════════════════════════════════════\033[0m\n";
echo "\033[1m  Demo Técnica: «El Test que Falla» — Grupo 8  \033[0m\n";
echo "\033[1m══════════════════════════════════════════════\033[0m\n\n";

// ─────────────────────────────────────────────────────────────
// BLOQUE 1: Envío gratis cuando el pedido supera $5000
// ─────────────────────────────────────────────────────────────
echo "\033[1m[1] Costo de envío según total del pedido\033[0m\n";

TestRunner::run('Pedido de $5001 → envío gratis ($0)', function () use ($service) {
    $envio = $service->calcularEnvio(5001.00);
    TestRunner::assertEquals(0.0, $envio, "El envío debería ser $0 para pedidos > $5000");
});

TestRunner::run('Pedido exactamente de $5000 → envío con costo', function () use ($service) {
    $envio = $service->calcularEnvio(5000.00);
    TestRunner::assertEquals(500.0, $envio, "El límite es MAYOR a $5000, no mayor o igual");
});

TestRunner::run('Pedido de $10000 → envío gratis ($0)', function () use ($service) {
    $envio = $service->calcularEnvio(10000.00);
    TestRunner::assertEquals(0.0, $envio);
});

TestRunner::run('Pedido de $1500 → envío con costo ($500)', function () use ($service) {
    $envio = $service->calcularEnvio(1500.00);
    TestRunner::assertEquals(500.0, $envio);
});

// ─────────────────────────────────────────────────────────────
// BLOQUE 2: Validaciones — Teléfono vacío y precio cero
// ─────────────────────────────────────────────────────────────
echo "\n\033[1m[2] Validaciones de pedido\033[0m\n";

TestRunner::run('Pedido válido → sin errores', function () use ($service) {
    $errores = $service->validarPedido(['telefono' => '2614001234', 'precio' => 1500.00]);
    TestRunner::assertEmpty($errores, "Un pedido con datos completos no debe tener errores");
});

TestRunner::run('Teléfono vacío → rechazado', function () use ($service) {
    $errores = $service->validarPedido(['telefono' => '', 'precio' => 1500.00]);
    TestRunner::assertNotEmpty($errores, "Debe rechazar pedidos sin teléfono");
    TestRunner::assertContains('El campo Teléfono es obligatorio.', $errores);
});

TestRunner::run('Teléfono ausente (null) → rechazado', function () use ($service) {
    $errores = $service->validarPedido(['telefono' => null, 'precio' => 1500.00]);
    TestRunner::assertNotEmpty($errores);
});

TestRunner::run('Precio cero → rechazado', function () use ($service) {
    $errores = $service->validarPedido(['telefono' => '2614001234', 'precio' => 0]);
    TestRunner::assertNotEmpty($errores, "Debe rechazar pedidos con precio $0");
    TestRunner::assertContains('El precio debe ser mayor a cero.', $errores);
});

TestRunner::run('Precio negativo → rechazado', function () use ($service) {
    $errores = $service->validarPedido(['telefono' => '2614001234', 'precio' => -50]);
    TestRunner::assertNotEmpty($errores);
});

TestRunner::run('Teléfono vacío Y precio cero → dos errores', function () use ($service) {
    $errores = $service->validarPedido(['telefono' => '', 'precio' => 0]);
    TestRunner::assertEquals(2, count($errores), "Deben detectarse ambos errores a la vez");
});

// ─────────────────────────────────────────────────────────────
// BLOQUE 3: Test que falla a propósito → luego se corrige
// ─────────────────────────────────────────────────────────────
echo "\n\033[1m[3] Test que falla a propósito (bug en calcularEnvio)\033[0m\n";
echo "\033[33m    ↓ El siguiente test ESTÁ DISEÑADO PARA FALLAR y mostrar el bug\033[0m\n";

TestRunner::run('[BUG INTENCIONAL] Pedido de $5000 debería tener envío GRATIS (código incorrecto)', function () use ($service) {
    // BUG: el código usa > (estricto) pero este test asume >= (mayor o igual)
    // Esto expone que el umbral no incluye el valor exacto de $5000
    $envio = $service->calcularEnvio(5000.00);
    // Aserción incorrecta a propósito → FALLA para demostrar el bug
    TestRunner::assertEquals(
        0.0,
        $envio,
        "BUG DETECTADO: calcularEnvio(5000) retorna {$envio} en lugar de 0. " .
        "La condición usa '>' pero debería usar '>=' para incluir $5000 exactos."
    );
});

echo "\n\033[33m    ↑ Bug detectado. Corrigiendo la lógica en PedidoService...\033[0m\n";

// CORRECCIÓN: cambiamos la condición de > a >=
// (simulamos el fix sin tocar el archivo original, sobreescribiendo el método en runtime)
$serviceParcheado = new class extends PedidoService {
    public function calcularEnvio(float $total): float {
        // FIX: >= en lugar de > para que $5000 exactos también sean gratis
        if ($total >= PedidoService::ENVIO_GRATIS_DESDE) {
            return 0.0;
        }
        return PedidoService::COSTO_ENVIO;
    }
};

echo "\n\033[1m[3b] Mismos tests con el código corregido\033[0m\n";

TestRunner::run('[CORREGIDO] Pedido de $5000 → envío gratis ($0)', function () use ($serviceParcheado) {
    $envio = $serviceParcheado->calcularEnvio(5000.00);
    TestRunner::assertEquals(0.0, $envio, "Con el fix, $5000 exactos también deben tener envío gratis");
});

TestRunner::run('[CORREGIDO] Pedido de $4999 → envío con costo', function () use ($serviceParcheado) {
    $envio = $serviceParcheado->calcularEnvio(4999.99);
    TestRunner::assertEquals(500.0, $envio);
});

// ─────────────────────────────────────────────────────────────
// BLOQUE 4: Integridad Referencial — al cancelar un pedido
//           sus detalles deben eliminarse
// ─────────────────────────────────────────────────────────────
echo "\n\033[1m[4] Integridad referencial — cancelación de pedido\033[0m\n";

/**
 * Simulamos la base de datos en memoria para no requerir MySQL.
 * Esto replica exactamente el comportamiento de ON DELETE CASCADE.
 */
class BaseDatosSimulada {
    public array $pedidos  = [];
    public array $detalles = [];
    private int $nextPedidoId  = 1;
    private int $nextDetalleId = 1;

    public function crearPedido(array $datos): int {
        $id = $this->nextPedidoId++;
        $this->pedidos[$id] = array_merge(['id' => $id], $datos);
        return $id;
    }

    public function agregarDetalle(int $pedidoId, array $datos): int {
        if (!isset($this->pedidos[$pedidoId])) {
            throw new RuntimeException("El pedido #{$pedidoId} no existe.");
        }
        $id = $this->nextDetalleId++;
        $this->detalles[$id] = array_merge(['id' => $id, 'pedido_id' => $pedidoId], $datos);
        return $id;
    }

    public function eliminarPedido(int $pedidoId): void {
        if (!isset($this->pedidos[$pedidoId])) {
            throw new RuntimeException("El pedido #{$pedidoId} no existe.");
        }
        // ON DELETE CASCADE: borra todos los detalles del pedido primero
        $this->detalles = array_filter(
            $this->detalles,
            fn($d) => $d['pedido_id'] !== $pedidoId
        );
        unset($this->pedidos[$pedidoId]);
    }

    public function obtenerDetallesPorPedido(int $pedidoId): array {
        return array_values(array_filter(
            $this->detalles,
            fn($d) => $d['pedido_id'] === $pedidoId
        ));
    }
}

TestRunner::run('Al eliminar un pedido, sus detalles se borran (CASCADE)', function () {
    $db = new BaseDatosSimulada();

    $idPedido = $db->crearPedido(['cliente' => 'Juan Pérez', 'telefono' => '2614001234', 'total' => 1200]);
    $db->agregarDetalle($idPedido, ['vianda_id' => 1, 'cantidad' => 2, 'precio_unitario' => 600]);
    $db->agregarDetalle($idPedido, ['vianda_id' => 2, 'cantidad' => 1, 'precio_unitario' => 0]);

    // Verificamos que los detalles existen antes de borrar
    TestRunner::assertEquals(2, count($db->obtenerDetallesPorPedido($idPedido)));

    // Cancelamos (eliminamos) el pedido
    $db->eliminarPedido($idPedido);

    // El pedido ya no debe existir
    TestRunner::assertFalse(isset($db->pedidos[$idPedido]), "El pedido debe haber sido eliminado");

    // Los detalles tampoco deben existir
    $detallesRestantes = $db->obtenerDetallesPorPedido($idPedido);
    TestRunner::assertEmpty($detallesRestantes, "Los detalles deben borrarse junto con el pedido (CASCADE)");
});

TestRunner::run('Los detalles de otros pedidos NO se ven afectados', function () {
    $db = new BaseDatosSimulada();

    $pedido1 = $db->crearPedido(['cliente' => 'Ana García', 'telefono' => '2614111111', 'total' => 800]);
    $pedido2 = $db->crearPedido(['cliente' => 'Luis Rodríguez', 'telefono' => '2614222222', 'total' => 1500]);

    $db->agregarDetalle($pedido1, ['vianda_id' => 1, 'cantidad' => 1, 'precio_unitario' => 800]);
    $db->agregarDetalle($pedido2, ['vianda_id' => 2, 'cantidad' => 2, 'precio_unitario' => 750]);

    // Eliminamos solo el pedido 1
    $db->eliminarPedido($pedido1);

    // Los detalles del pedido 2 deben seguir intactos
    $detallesPedido2 = $db->obtenerDetallesPorPedido($pedido2);
    TestRunner::assertEquals(1, count($detallesPedido2), "Los detalles del pedido 2 no deben verse afectados");
});

TestRunner::run('Eliminar pedido inexistente lanza excepción', function () {
    $db = new BaseDatosSimulada();
    $lanzaExcepcion = false;
    try {
        $db->eliminarPedido(999);
    } catch (RuntimeException $e) {
        $lanzaExcepcion = true;
    }
    TestRunner::assertTrue($lanzaExcepcion, "Debe lanzar excepción al eliminar un pedido que no existe");
});

// ─────────────────────────────────────────────────────────────
// Resumen final
// ─────────────────────────────────────────────────────────────
TestRunner::resumen();
