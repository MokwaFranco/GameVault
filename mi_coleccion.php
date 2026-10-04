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

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['eliminar_id'])) {
    $id_eliminar = filter_input(INPUT_POST, 'eliminar_id', FILTER_VALIDATE_INT);
    if ($id_eliminar) {
        $coleccion->eliminar($id_usuario, $id_eliminar);
        header('Location: mi_coleccion.php');
        exit;
    }
}

$mis_videojuegos = $coleccion->obtenerVideojuegos($id_usuario);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/mi_coleccion.css">
    <link rel="stylesheet" href="font/bootstrap-icons.css">
    <title>Mi coleccion - GameVault</title>
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
        <section class="coleccion">
            <?php if (!empty($mis_videojuegos)): ?>
                <?php foreach ($mis_videojuegos as $mi_videojuego): ?>
                    <?php
                        $fecha = new DateTime($mi_videojuego['fecha_agregado_coleccion']);  
                        $fecha_formateada = $fecha->format('d-m-Y H:i') . 'hs';
                    ?>
                    <div class="card">
                        <div>
                            <!-- <a href="#"><img src="covers/<?php #echo htmlspecialchars($nombre_imagen); ?>" alt="<?php echo htmlspecialchars($mi_videojuego['titulo_videojuego']) ?>"></a> -->
                            <a href="detalle.php?id=<?php echo $mi_videojuego['id_videojuego']; ?>">
                                <h3><?php echo htmlspecialchars($mi_videojuego['titulo_videojuego']); ?></h3>
                            </a>
                            <!-- <p><?php #echo htmlspecialchars(substr($videojuego['descripcion_videojuego'] ?? '', 0, 80)) . '...' ?></p> -->
                            <p class="fecha_agregado"><?php echo $fecha_formateada; ?></p>
                        </div>
                        
                        <div class="action">
                            <a href="detalle.php?id=<?php echo $mi_videojuego['id_videojuego']; ?>" class="details">Detalles</a>

                            <form action="mi_coleccion.php" method="POST">
                                <input type="hidden" name="eliminar_id" value="<?php echo $mi_videojuego['id_videojuego'] ?>">
                                <button type="submit" class="button_eliminar"><i class="bi bi-trash2-fill"></i></button>
                            </form>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p class="sin_resultados">No se encontraron resultados.</p>
            <?php endif; ?>
        </section>
    </main>
</body>
</html>