<?php

declare(strict_types=1);

final class Coleccion
{
    public function __construct(private PDO $pdo) {}

    public function obtenerVideojuegos(int $id_usuario): array
    {
        $sql = "
            SELECT v.id_videojuego, v.titulo_videojuego, c.fecha_agregado_coleccion
            FROM coleccion c
            INNER JOIN videojuego v ON c.id_videojuego = v.id_videojuego
            WHERE c.id_usuario = :id_usuario
            ORDER BY c.fecha_agregado_coleccion DESC
        ";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':id_usuario' => $id_usuario]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function agregar(int $id_usuario, int $id_videojuego): bool
    {
        if ($this->existe($id_usuario, $id_videojuego)) {
            return false;
        }

        $sql = "
            INSERT INTO coleccion (id_usuario, id_videojuego, fecha_agregado_coleccion)
            VALUES (:id_usuario, :id_videojuego, NOW());
        ";

        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            ':id_usuario' => $id_usuario,
            ':id_videojuego' => $id_videojuego
        ]);
    }

    public function eliminar(int $id_usuario, int $id_videojuego): bool
    {
        $sql = "DELETE FROM coleccion
                WHERE id_usuario = :id_usuario
                AND id_videojuego = :id_videojuego";
        
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            ':id_usuario' => $id_usuario,
            ':id_videojuego' => $id_videojuego
        ]);
    }

    public function existe(int $id_usuario, int $id_videojuego): bool
    {
        $sql = "SELECT COUNT(*)
                FROM coleccion
                WHERE id_usuario = :id_usuario
                AND id_videojuego = :id_videojuego";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':id_usuario' => $id_usuario,
            ':id_videojuego' => $id_videojuego
        ]);

        return (int) $stmt->fetchColumn() > 0;
    }
}

?>