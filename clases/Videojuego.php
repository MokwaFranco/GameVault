<?php

declare(strict_types=1);

final class Videojuego
{
    public function __construct(private PDO $pdo) {}

    public function crear(
        string $titulo,
        ?string $descripcion,
        ?string $desarrolladora,
        ?string $editora
        ): bool
    {
        if ($this->existePorTitulo($titulo)) {
            return false;
        }

        $sql = "INSERT INTO videojuego (
                    titulo_videojuego,
                    descripcion_videojuego,
                    desarrolladora_videojuego,
                    editora_videojuego
                )
                VALUES (
                    :titulo,
                    :descripcion,
                    :desarrolladora,
                    :editora
                )";

        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            ':titulo' => $titulo,
            ':descripcion' => ($descripcion !== '') ? $descripcion : null,
            #':fecha_lanzamiento' => ($fecha_lanzamiento !== '') ? $fecha_lanzamiento : null,
            #':imagen' => ($imagen !== '') ? $imagen : null,
            ':desarrolladora' => ($desarrolladora !== '') ? $desarrolladora : null,
            ':editora' => ($editora !== '') ? $editora : null
        ]);
    }

    public function actualizar(
        int $id_videojuego,
        string $titulo,
        ?string $descripcion,
        ?string $desarrolladora,
        ?string $editora
    ): bool {
        $sql = "UPDATE videojuego SET
                    titulo_videojuego = :titulo,
                    descripcion_videojuego = :descripcion,
                    desarrolladora_videojuego = :desarrolladora,
                    editora_videojuego = :editora
                WHERE id_videojuego = :id";
        
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            ':titulo' => $titulo,
            ':descripcion' => $descripcion,
            ':desarrolladora' => $desarrolladora,
            ':editora' => $editora,
            ':id' => $id_videojuego
        ]);
    }

    public function eliminar(int $id_videojuego): bool
    {
        $sql = "DELETE FROM videojuego WHERE id_videojuego = :id_videojuego";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([':id_videojuego' => $id_videojuego]);
    }

    public function obtenerPorId(int $id_videojuego): ?array
    {
        $sql = "SELECT * FROM videojuego WHERE id_videojuego = :id LIMIT 1";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':id' => $id_videojuego]);
        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
        return $resultado ?: null;
    }

    public function existePorTitulo(string $titulo): bool
    {
        $sql = "SELECT COUNT(*) FROM videojuego WHERE titulo_videojuego = :titulo";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':titulo' => $titulo]);

        return (int) $stmt->fetchColumn() > 0;
    }

}

?>