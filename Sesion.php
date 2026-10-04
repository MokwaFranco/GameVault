<?php

declare(strict_types=1);

final class Sesion
{
    public static function iniciar(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    public static function login(array $usuario): void
    {
        session_regenerate_id(true);
        $_SESSION['usuario'] = $usuario;
    }

    public static function autenticado(): bool
    {
        self::iniciar();
        return isset($_SESSION['usuario']);
    }

    public static function usuario(): ?array
    {
        self::iniciar();
        return $_SESSION['usuario'] ?? null;
    }

    public static function proteger(string $destino = 'login.php'): void
    {
        if (!self::autenticado()) {
            header('Location: ' . $destino);
            exit;
        }
    }

    public static function cerrar(): void
    {
        self::iniciar();
        $_SESSION = [];
        session_destroy();
    }
}

?>