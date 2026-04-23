<?php

require_once __DIR__ . '/TestRunner.php';
require_once __DIR__ . '/../app/services/PedidoService.php';

$service = new PedidoService();

echo "\n=== Demo: El Test que Falla - Grupo 8 ===\n\n";

// TEST 1: un pedido barato paga envio
// Le pasamos $1500, esperamos que devuelva $500 de envio
TestRunner::run('Pedido de $1500 paga envio ($500)', function () use ($service) {
    $resultado = $service->calcularEnvio(1500);
    TestRunner::assertEquals(500.0, $resultado);
});

// TEST 2: un pedido caro tiene envio gratis
// Le pasamos $6000, esperamos que devuelva $0
TestRunner::run('Pedido de $6000 tiene envio gratis ($0)', function () use ($service) {
    $resultado = $service->calcularEnvio(6000);
    TestRunner::assertEquals(0.0, $resultado);
});

// TEST 3: pedido sin telefono no deberia aceptarse
// validarPedido devuelve una lista de errores, si hay errores el pedido no es valido
TestRunner::run('Pedido sin telefono es rechazado', function () use ($service) {
    $errores = $service->validarPedido(['telefono' => '', 'precio' => 1500]);
    TestRunner::assertNotEmpty($errores);
});

// TEST 4: EL TEST QUE FALLA A PROPOSITO
// La regla dice: envio gratis si el pedido supera $5000
// Entonces $5000 exactos deberian tener envio gratis, pero el codigo tiene un bug:
// usa ">" en lugar de ">=" entonces $5000 exactos NO son gratis
// Este test falla para mostrar ese bug
TestRunner::run('[FALLA] Pedido de $5000 exactos deberia tener envio gratis', function () use ($service) {
    $resultado = $service->calcularEnvio(5000);
    TestRunner::assertEquals(0.0, $resultado, "BUG: el codigo usa > en lugar de >= entonces $5000 exactos cobran envio");
});

TestRunner::resumen();
