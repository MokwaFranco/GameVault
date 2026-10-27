<?php

declare(strict_types=1);

require_once __DIR__ . "/clases/conexion.php";
require_once __DIR__ . "/clases/Sesion.php";
require_once __DIR__ . "/clases/Videojuego.php";

Sesion::proteger('login.php');

$pdo = conexion::obtener();
$videojuegoObj = new Videojuego(($pdo));

$id_videojuego = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$id_videojuego) {
    header('Location: biblioteca.php');
    exit;
}

$videojuego = $videojuegoObj->obtenerPorId($id_videojuego);
if (!$videojuego) {
    header('Location: biblioteca.php');
    exit;
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/detalle.css">
    <link rel="stylesheet" href="font/bootstrap-icons.css">
    <title><?php echo htmlspecialchars($videojuego['titulo_videojuego']); ?> - GameVault</title>
</head>
<body>
    <header>
        <div class="logout">
            <a href="logout.php" title="Cerrar sesión"><i class="bi bi-door-open"></i></a>
        </div>

        <div class="logo">
            <a href="biblioteca.php">GameVault</a>
        </div>

        <div class="account">
            <a href="agregar_registro.php" title="Agregar nuevo registro"><i class="bi bi-patch-plus"></i></a>
            <a href="mi_biblioteca.php" title="Mi Biblioteca"><i class="bi bi-folder"></i></a>
        </div>
    </header>

    <main>
        <section class="detail-container">
            <h1><?php echo htmlspecialchars($videojuego['titulo_videojuego']); ?></h1>

            <div class="detail-info">
                <div class="detail-item">
                    <strong>Desarrolladora:</strong> 
                    <span><?php echo htmlspecialchars($videojuego['desarrolladora_videojuego'] ?? 'Sin especificar'); ?></span>
                </div>

                <div class="detail-item">
                    <strong>Editora:</strong> 
                    <span><?php echo htmlspecialchars($videojuego['editora_videojuego'] ?? 'Sin especificar'); ?></span>
                </div>

                <div class="detail-item">
                    <p><?php echo htmlspecialchars($videojuego['descripcion_videojuego'] ?? 'Sin descripción disponible.'); ?></p>
                </div>
            </div>
        </section>
    </main>
</body>
</html>