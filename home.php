<?php

declare(strict_types=1);

require_once 'conexion.php';
require_once 'Sesion.php';
require_once 'Coleccion.php';

Sesion::proteger('login.php');

$usuario_actual = Sesion::usuario();
$id_usuario = (int) ($usuario_actual['id_usuario'] ?? 0);

$pdo = conexion::obtener();
$coleccion = new Coleccion($pdo);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_videojuego'])) {
    $id_videojuego = filter_input(INPUT_POST, 'id_videojuego', FILTER_VALIDATE_INT);
    if ($id_videojuego) {
        $coleccion->agregar($id_usuario, $id_videojuego);
        header('Location: home.php');
        exit;
    }
}

$sql = "SELECT id_videojuego, titulo_videojuego, descripcion_videojuego
        FROM videojuego
        ORDER BY titulo_videojuego";

$stmt = $pdo->prepare($sql);
$stmt->execute();
$videojuegos = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/home.css">
    <link rel="stylesheet" href="font/bootstrap-icons.css">
    <title>Inicio - GameVault</title>
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
        <section class="videojuegos">
            <?php if (!empty($videojuegos)): ?>
            <?php foreach ($videojuegos as $videojuego): ?>
                <?php
                    $id_videojuego = (int) $videojuego['id_videojuego'];
                    $guardado = $coleccion->existe($id_usuario, $id_videojuego);
                    #$nombre_imagen = !empty($videojuego['imagen_videojuego']) ? $videojuego['imagen_videojuego'] : 'default.png';
                ?>
                <div class="card">
                    <div>
                        <!-- <a href="#"><img src="covers/<?php #echo htmlspecialchars($nombre_imagen); ?>" alt="<?php echo htmlspecialchars($videojuego['titulo_videojuego']) ?>"></a> -->
                        <a href="detalle.php?id=<?php echo $videojuego['id_videojuego']; ?>">
                            <h3><?php echo htmlspecialchars($videojuego['titulo_videojuego']); ?></h3>
                        </a>
                        <!-- <p><?php #echo htmlspecialchars(substr($videojuego['descripcion_videojuego'] ?? '', 0, 80)) . '...' ?></p> -->
                    </div>

                    <div class="action">
                        <a href="detalle.php?id=<?php echo $videojuego['id_videojuego']; ?>" class="details">Detalles</a>
                        <?php if ($guardado): ?>
                            <a href="mi_coleccion.php" class="button_agregado"><i class="bi bi-archive-fill"></i></a>
                        <?php else: ?>
                            <form action="home.php" method="POST">
                                <input type="hidden" name="id_videojuego" value="<?php echo $id_videojuego ?>">
                                <button type="submit" class="button_agregar">
                                    <i class="bi bi-archive"></i>
                                </button>
                            </form>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
            <?php else: ?>
                <p>No se encontraron resultados.</p>
            <?php endif; ?>
        </section>
    </main>
</body>
</html>