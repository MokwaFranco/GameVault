<?php

declare(strict_types=1);

require_once 'Conexion.php';
require_once 'Sesion.php';
require_once 'Coleccion.php';

Sesion::proteger('login.php');

$usuario_actual = Sesion::usuario();
$id_usuario = (int) ($usuario_actual['id_usuario'] ?? 0);
$id_videojuego = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if (!$id_videojuego) {
    header('Location: home.php');
    exit;
}

$pdo = conexion::obtener();
$coleccion = new Coleccion($pdo);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    if ($_POST['action'] === 'agregar') {
        $coleccion->agregar($id_usuario, $id_videojuego);
    } elseif ($_POST['action'] === 'eliminar') {
        $coleccion->eliminar($id_usuario, $id_videojuego);
    }
    header("Location: detalle.php?id={$id_videojuego}");
    exit;
}

$sql = "SELECT id_videojuego, titulo_videojuego, descripcion_videojuego
        FROM videojuego
        WHERE id_videojuego = :id";

$stmt = $pdo->prepare($sql);
$stmt->execute([':id' => $id_videojuego]);
$videojuego = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$videojuego) {
    header('Location: home.php');
    exit;
}

$guardado =$coleccion->existe($id_usuario, $id_videojuego);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/detalle.css">
    <link rel="stylesheet" href="font/bootstrap-icons.css">
    <title><?php echo htmlspecialchars((string) $videojuego['titulo_videojuego']); ?> - GameVault</title>
</head>
<body>
    <header>
        <div class="logout">
            <a href="logout.php"><i class="bi bi-box-arrow-left"></i></a>
        </div>

        <div class="logo">
            <a href="home.php">GameVault</a>
        </div>

        <div class="account">
            <a href="#"><i class="bi bi-bell"></i></a>
            <a href="mi_coleccion.php"><i class="bi bi-archive"></i></a>
            <a href="#"><i class="bi bi-person-circle"></i></a>
        </div>
    </header>

    <main>
        <article class="detalle">
            <h1><?php echo htmlspecialchars((string) $videojuego['titulo_videojuego']); ?></h1>
            <p><?php echo htmlspecialchars((string) $videojuego['descripcion_videojuego']); ?></p>
        
            <div class="action">
                <?php if ($guardado): ?>
                    <form action="detalle.php?id=<?php echo $id_videojuego; ?>" method="POST">
                        <button type="submit" class="button_eliminar">
                            <i class="bi bi-trash2-fill"></i>
                        </button>
                    </form>
                <?php else: ?>
                    <form action="" method="POST">
                        <button type="submit" class="button_agregar">
                            <i class="bi bi-archive"></i>
                        </button>
                    </form>
                <?php endif; ?>
            </div>
        </article>
    </main>
</body>
</html>