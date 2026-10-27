<?php

declare(strict_types=1);

final class conexion
{
    private const HOST = 'localhost';
    private const PUERTO = '3306';
    private const BASE = 'game_vault_database';
    private const USUARIO = 'root';
    private const CONTRASENA = '';
    private const CHARSET = 'utf8mb4';

    private static ?PDO $pdo = null;

    private function __construct() {}

    public static function obtener(): PDO
    {
        if (self::$pdo === null) {
            $dsn = sprintf(
                'mysql:host=%s;port=%s;dbname=%s;charset=%s',
                self::HOST,
                self::PUERTO,
                self::BASE,
                self::CHARSET
            );

            $opciones = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false
            ];

            try {
                self::$pdo = new PDO($dsn, self::USUARIO, self::CONTRASENA, $opciones);
            } catch (PDOException $e) {
                exit('Error de conexión: ' . $e->getMessage());
            }
        }

        return self::$pdo;
    }
}

?>