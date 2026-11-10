<?php

declare(strict_types=1);

require_once __DIR__ . '/clases/conexion.php';
require_once __DIR__ . '/clases/Sesion.php';
require_once __DIR__ . '/clases/Coleccion.php';
require_once __DIR__ . '/clases/Videojuego.php';

Sesion::proteger('login.php');

$usuario_actual = Sesion::usuario();
$id_usuario = (int) ($usuario_actual['id_usuario'] ?? 0);

$pdo = conexion::obtener();
$coleccion = new Coleccion($pdo);

# Guardar registro
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_videojuego'])) {
    $id_videojuego = filter_input(INPUT_POST, 'id_videojuego', FILTER_VALIDATE_INT);
    if ($id_videojuego) {
        $coleccion->agregarVideojuego($id_usuario, $id_videojuego);
        header('Location: biblioteca.php');
        exit;
    }
}

$sql = "SELECT id_videojuego, titulo_videojuego, descripcion_videojuego, imagen_videojuego
        FROM videojuego
        ORDER BY titulo_videojuego";

$stmt = $pdo->prepare($sql);
$stmt->execute();
$videojuegos = $stmt->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['eliminar_id'])) {
    $id_eliminar = filter_input(INPUT_POST, 'eliminar_id', FILTER_VALIDATE_INT);
    if ($id_eliminar) {
        $videojuegoObj = new Videojuego($pdo);
        $videojuegoObj->eliminarVideojuego($id_eliminar);
        header('Location: biblioteca.php');
        exit;
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/bibliotecas.css">
    <link rel="stylesheet" href="font/bootstrap-icons.css">
    <title>Biblioteca - GameVault</title>
</head>
<body>
    <header>
        <div class="logout">
            <a href="logout.php"><i class="bi bi-door-open" title="Cerrar sesión"></i></a>
        </div>

        <div class="logo">
            <a href="biblioteca.php" title="GameVault">GameVault</a>
        </div>

        <div class="account">
            <a href="agregar_editora.php" title="agregar editora nueva"><i class="bi bi-clipboard-plus"></i></a>
            <a href="agregar_registro.php" title="agregar nuevo registro"><i class="bi bi-patch-plus"></i></a>
            <!--<a href="#" title="Notificaciones"><i class="bi bi-bell"></i></a>-->
            <a href="mi_biblioteca.php" title="Mi biblioteca"><i class="bi bi-folder"></i></a>
            <!--<a href="#" title="Mi Cuenta"><i class="bi bi-person-circle"></i></a>-->
        </div>
    </header>

    <main>
        <section class="library">
            <?php if (!empty($videojuegos)): ?>
                <?php foreach ($videojuegos as $videojuego): ?>
                    <?php
                        $id_videojuego = (int) $videojuego['id_videojuego'];
                        $guardado = $coleccion->existeVideojuego($id_usuario, $id_videojuego);
                    ?>
                    <div class="card">
                        <div>
                            <a href="detalle.php?id=<?php echo $videojuego['id_videojuego']; ?>">
                                <img src="<?php echo htmlspecialchars($videojuego['imagen_videojuego']); ?>" width="100%">
                            
                                <h3 title="<?php echo $videojuego['titulo_videojuego']; ?>">
                                    <?php echo htmlspecialchars($videojuego['titulo_videojuego']); ?>
                                </h3>
                            </a>
                            <!-- <p><?php #echo htmlspecialchars(substr($videojuego['descripcion_videojuego'] ?? '', 0, 80)) . '...' ?></p> -->
                        </div>

                        <div class="action">
                            <a href="modificar_registro.php?id=<?php echo $id_videojuego ?>" class="button_edit" title="Modificar datos">
                                <i class="bi bi-pen"></i>
                            </a>

                            <?php if ($guardado): ?>
                                <a href="mi_biblioteca.php" class="button_added" title="Agregado a mi biblioteca"><i class="bi bi-folder-check"></i></a>
                            <?php else: ?>
                                <form action="biblioteca.php" method="POST">
                                    <input type="hidden" name="id_videojuego" value="<?php echo $id_videojuego ?>">

                                    <button type="submit" class="button_add" title="Agregar a mi biblioteca">
                                        <i class="bi bi-folder-plus"></i>
                                    </button>
                                </form>
                            <?php endif; ?>
                        
                            <form action="biblioteca.php" method="POST" onsubmit="return confirm('¿Seguro que deseas eliminar &quot;<?php echo $videojuego['titulo_videojuego']; ?>&quot; definitivamente?')">
                                <input type="hidden" name="eliminar_id" value="<?php echo $videojuego['id_videojuego'] ?>">

                                <button type="submit" class="button_delete" title="Eliminar registro">
                                    <i class="bi bi-folder-x"></i>
                                </button>
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