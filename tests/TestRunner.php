<?php
// tests/TestRunner.php - Framework de tests liviano sin dependencias

class TestRunner {
    private static int $passed  = 0;
    private static int $failed  = 0;
    private static array $failures = [];

    public static function run(string $nombre, callable $test): void {
        try {
            $test();
            self::$passed++;
            echo "\033[32m  ✓ {$nombre}\033[0m\n";
        } catch (AssertionError $e) {
            self::$failed++;
            self::$failures[] = "  ✗ {$nombre}\n    → " . $e->getMessage();
            echo "\033[31m  ✗ {$nombre}\033[0m\n";
            echo "\033[33m    → " . $e->getMessage() . "\033[0m\n";
        }
    }

    public static function resumen(): void {
        $total = self::$passed + self::$failed;
        echo "\n";
        echo "─────────────────────────────────────────────\n";
        echo "Tests: {$total}  |  ";
        echo "\033[32mPasaron: " . self::$passed . "\033[0m  |  ";
        echo "\033[31mFallaron: " . self::$failed . "\033[0m\n";
        echo "─────────────────────────────────────────────\n";
        if (self::$failed > 0) exit(1);
    }

    // — Aserciones ——————————————————————————————————————————————

    public static function assertEquals(mixed $esperado, mixed $real, string $msg = ''): void {
        if ($esperado !== $real) {
            $e = $esperado;
            $r = $real;
            throw new AssertionError(
                $msg ?: "Se esperaba [{$e}] pero se obtuvo [{$r}]"
            );
        }
    }

    public static function assertTrue(bool $condicion, string $msg = ''): void {
        if (!$condicion) {
            throw new AssertionError($msg ?: "Se esperaba TRUE pero fue FALSE");
        }
    }

    public static function assertFalse(bool $condicion, string $msg = ''): void {
        if ($condicion) {
            throw new AssertionError($msg ?: "Se esperaba FALSE pero fue TRUE");
        }
    }

    public static function assertNotEmpty(mixed $valor, string $msg = ''): void {
        if (empty($valor)) {
            throw new AssertionError($msg ?: "Se esperaba un valor no vacío");
        }
    }

    public static function assertEmpty(mixed $valor, string $msg = ''): void {
        if (!empty($valor)) {
            throw new AssertionError($msg ?: "Se esperaba un valor vacío");
        }
    }

    public static function assertContains(string $aguja, array $pajar, string $msg = ''): void {
        if (!in_array($aguja, $pajar, true)) {
            throw new AssertionError($msg ?: "'{$aguja}' no está en el array");
        }
    }
}
