<?php

declare(strict_types=1);

final class Editora
{
    public function __construct(private PDO $pdo) {}

    public function getEditoras(): array
    {
        $sql = "SELECT *
                FROM editora
                ORDER BY nombre_editora ASC";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function agregarEditora(string $nombre): bool
    {
        if ($this->existePorNombre($nombre)) {
            return false;
        }

        $sql = "INSERT INTO editora (nombre_editora)
                VALUES (:nombre)";
        
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([':nombre' => $nombre]);
    }
    
    public function existePorNombre(string $nombre): bool
    {
        $sql = "SELECT COUNT(*)
                FROM editora
                WHERE nombre_editora = :nombre";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':nombre' => $nombre]);
        return (int) $stmt->fetchColumn() > 0;
    }
}

?>