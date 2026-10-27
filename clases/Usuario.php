<?php

declare(strict_types=1);

final class Usuario
{
    public function __construct(private PDO $pdo) {}

    public function autenticar(string $nombre_usuario, string $contrasena_usuario): ?array
    {
        $sql = 'SELECT id_usuario, nombre_usuario, email_usuario, contrasena_usuario
                FROM usuario
                WHERE nombre_usuario = :nombre_usuario
                LIMIT 1';
        
        $consulta = $this->pdo->prepare($sql);
        $consulta->execute([':nombre_usuario' => $nombre_usuario]);
        $usuario = $consulta->fetch();

        if ($usuario && password_verify($contrasena_usuario, $usuario['contrasena_usuario'])) {
            unset($usuario['contrasena_usuario']);
            return $usuario;
        }

        return null;
    }
}

?>