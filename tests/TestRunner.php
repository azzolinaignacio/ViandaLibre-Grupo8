<?php

// TestRunner: se encarga de correr cada test y mostrar si paso o fallo

class TestRunner {

    private static int $pasaron = 0;
    private static int $fallaron = 0;

    // Corre un test. Si no lanza error, paso. Si lanza error, fallo.
    public static function run(string $nombre, callable $test): void {
        try {
            $test();
            self::$pasaron++;
            echo "  ✓ {$nombre}\n";
        } catch (AssertionError $e) {
            self::$fallaron++;
            echo "  ✗ {$nombre}\n";
            echo "    → " . $e->getMessage() . "\n";
        }
    }

    // Muestra el resumen al final
    public static function resumen(): void {
        $total = self::$pasaron + self::$fallaron;
        echo "\n";
        echo "Tests: {$total}  |  Pasaron: " . self::$pasaron . "  |  Fallaron: " . self::$fallaron . "\n";
    }

    // Compara dos valores, si son distintos lanza error
    public static function assertEquals($esperado, $real, string $msg = ''): void {
        if ($esperado !== $real) {
            throw new AssertionError($msg ?: "Se esperaba [{$esperado}] pero se obtuvo [{$real}]");
        }
    }

    // Verifica que la lista no este vacia
    public static function assertNotEmpty($valor, string $msg = ''): void {
        if (empty($valor)) {
            throw new AssertionError($msg ?: "Se esperaba que no estuviera vacio");
        }
    }
}
