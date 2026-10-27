<?php

declare(strict_types=1);

require_once __DIR__ . '/clases/conexion.php';
require_once __DIR__ . '/clases/Sesion.php';
require_once __DIR__ . '/clases/Coleccion.php';

Sesion::proteger('login.php');

$usuario_actual = Sesion::usuario();
$id_usuario = (int) ($usuario_actual['id_usuario'] ?? 0);

$pdo = conexion::obtener();
$coleccion = new Coleccion($pdo);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['quitar_id'])) {
    $id_quitar = filter_input(INPUT_POST, 'quitar_id', FILTER_VALIDATE_INT);
    if ($id_quitar) {
        $coleccion->eliminar($id_usuario, $id_quitar);
        header('Location: mi_biblioteca.php');
        exit;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['eliminar_id'])) {
    $id_eliminar = filter_input(INPUT_POST, 'eliminar_id', FILTER_VALIDATE_INT);
    if ($id_eliminar) {
        $videojuegoObj = new Videojuego($pdo);
        $videojuegoObj->eliminar($id_eliminar);
        header('Location: biblioteca.php');
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
    <link rel="stylesheet" href="css/bibliotecas.css">
    <link rel="stylesheet" href="font/bootstrap-icons.css">
    <title>Mi coleccion - GameVault</title>
</head>
<body>
    <header>
        <div class="logout">
            <a href="logout.php" title="Cerrar sesión"><i class="bi bi-door-open"></i></a>
        </div>

        <div class="logo">
            <a href="biblioteca.php" title="GameVault">GameVault</a>
        </div>

        <div class="account">
            <a href="agregar_registro.php" title="Agregar registro"><i class="bi bi-patch-plus"></i></a>
            <!--<a href="#" title="Notificaciones"><i class="bi bi-bell"></i></a>-->
            <a href="mi_biblioteca.php" title="Mi biblioteca"><i class="bi bi-folder-fill"></i></a>
            <!--<a href="#" title="Mi Cuenta"><i class="bi bi-person-circle"></i></a>-->
        </div>
    </header>

    <main>
        <section class="library">
            <?php if (!empty($mis_videojuegos)): ?>
                <?php foreach ($mis_videojuegos as $mi_videojuego): ?>
                    <?php
                        $fecha = new DateTime($mi_videojuego['fecha_agregado_coleccion']);  
                        $fecha_formateada = $fecha->format('d-m-Y H:i');
                    ?>
                    <div class="my-card">
                        <div>
                            <!-- <a href="#"><img src="covers/<?php #echo htmlspecialchars($nombre_imagen); ?>" alt="<?php #echo htmlspecialchars($mi_videojuego['titulo_videojuego']) ?>"></a> -->
                            <a href="detelle.php?id=<?php echo $mi_videojuego['id_videojuego']; ?>">
                                <h3 title="<?php echo $mi_videojuego['titulo_videojuego'] ?>"><?php echo htmlspecialchars($mi_videojuego['titulo_videojuego']); ?></h3>
                            </a>
                            <!-- <p><?php #echo htmlspecialchars(substr($videojuego['descripcion_videojuego'] ?? '', 0, 80)) . '...' ?></p> -->
                            <p class="fecha_agregado"><?php echo $fecha_formateada; ?></p>
                        </div>
                        
                        <div class="action">
                            <a href="modificar_registro.php?id=<?php echo $mi_videojuego['id_videojuego'] ?>" class="button_edit" title="Modificar datos">
                                <i class="bi bi-pen"></i>
                            </a>

                            <form action="mi_biblioteca.php" method="POST" onsubmit="return confirm('¿Seguro que deseas quitar &quot;<?php echo $mi_videojuego['titulo_videojuego']; ?>&quot; a mi biblioteca?')">
                                <input type="hidden" name="quitar_id" value="<?php echo $mi_videojuego['id_videojuego'] ?>">
                                <button type="submit" class="button_quit" title="Quitar de mi biblioteca"><i class="bi bi-folder-minus"></i></button>
                            </form>

                            <form action="biblioteca.php" method="POST" onsubmit="return confirm('¿Seguro de que deseas eliminar <?php echo $mi_videojuego['titulo_videojuego'] ?> de la biblioteca?')">
                                <input type="hidden" name="eliminar_id" value="<?php echo $mi_videojuego['id_videojuego'] ?>">
                                <button type="submit" class="button_delete"><i class="bi bi-folder-x" title="Eliminar registro"></i></button>
                            </form>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p class="no_results">No se encontraron resultados.</p>
            <?php endif; ?>
        </section>
    </main>
</body>
</html>