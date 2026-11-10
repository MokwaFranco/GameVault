<?php

declare(strict_types=1);

final class Videojuego
{
    public function __construct(private PDO $pdo) {}

    public function crearVideojuego(
        string $titulo,
        ?string $descripcion,
        ?string $imagen,
        ?string $desarrolladora,
        ?int $id_editora
        ): bool
    {
        if ($this->existePorTitulo($titulo)) {
            return false;
        }

        $sql = "INSERT INTO videojuego (
                    titulo_videojuego,
                    descripcion_videojuego,
                    imagen_videojuego,
                    desarrolladora_videojuego,
                    id_editora
                ) VALUES (
                    :titulo,
                    :descripcion,
                    :imagen,
                    :desarrolladora,
                    :id_editora
                )";

        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            ':titulo' => $titulo,
            ':descripcion' => ($descripcion !== '') ? $descripcion : null,
            ':imagen' => ($imagen !== '') ? $imagen : null,
            ':desarrolladora' => ($desarrolladora !== '') ? $desarrolladora : null,
            ':id_editora' => $id_editora
        ]);
    }

    public function actualizarVideojuego(
        int $id_videojuego,
        string $titulo,
        ?string $descripcion,
        ?string $imagen,
        ?string $desarrolladora,
        ?int $id_editora
    ): bool {
        $sql = "UPDATE videojuego SET
                    titulo_videojuego = :titulo,
                    descripcion_videojuego = :descripcion,
                    imagen_videojuego = :imagen,
                    desarrolladora_videojuego = :desarrolladora,
                    id_editora = :id_editora
                WHERE id_videojuego = :id";
        
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            ':titulo' => $titulo,
            ':descripcion' => ($descripcion !== '') ? $descripcion : null,
            ':imagen' => ($imagen !== '') ? $imagen : null,
            ':desarrolladora' => ($desarrolladora !== '') ? $desarrolladora : null,
            ':id_editora' => $id_editora,
            ':id' => $id_videojuego
        ]);
    }

    public function eliminarVideojuego(int $id_videojuego): bool
    {
        $sql = "DELETE FROM videojuego WHERE id_videojuego = :id_videojuego";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([':id_videojuego' => $id_videojuego]);
    }

    public function obtenerPorId(int $id_videojuego): ?array
    {
        $sql = "SELECT v.*, e.nombre_editora
                FROM videojuego v
                LEFT JOIN editora e ON v.id_editora = e.id_editora
                WHERE v.id_videojuego = :id LIMIT 1";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':id' => $id_videojuego]);
        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
        return $resultado ?: null;
    }

    public function existePorTitulo(string $titulo): bool
    {
        $sql = "SELECT COUNT(*)
                FROM videojuego
                WHERE titulo_videojuego = :titulo";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':titulo' => $titulo]);

        return (int) $stmt->fetchColumn() > 0;
    }
}

?>