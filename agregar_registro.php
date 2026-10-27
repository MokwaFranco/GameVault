<?php

declare(strict_types=1);

require_once __DIR__ . '/clases/conexion.php';
require_once __DIR__ . '/clases/Sesion.php';
require_once __DIR__ . '/clases/Videojuego.php';

Sesion::proteger('login.php');

$pdo = conexion::obtener();
$mensaje = "";
$tipo_mensaje = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['titulo_videojuego'])) {
    $titulo = trim($_POST['titulo_videojuego']);
    $descripcion = trim($_POST['descripcion_videojuego'] ?? '');
    #$imagen = trim($_POST['imagen_videojuego'] ?? '');
    #$fecha_lanzamiento = trim($_POST['fecha_lanzamiento_videojuego'] ?? '');
    $desarrolladora = trim($_POST['desarrolladora_videojuego'] ?? '');
    $editora = trim($_POST['editora_videojuego'] ?? '');
    
    
    if ($titulo !== "") {
        $videojuegoObj = new Videojuego($pdo);

        if ($videojuegoObj->crear($titulo, $descripcion, $desarrolladora, $editora)) {
            $mensaje = "¡Registro agregado exitosamente!";
            $tipo_mensaje = "exito";
        } else {
            $mensaje = "" . $titulo . " en existencia";
            $tipo_mensaje = "error";
        }
    } else {
        $mensaje = "Completar los campos obligatorios";
        $tipo_mensaje = "error";
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/abm.css">
    <link rel="stylesheet" href="font/bootstrap-icons.css">
    <title>Agregar videojuego - GameVault</title>
</head>
<body>
    <header>
        <div class="logout">
            <a href="logout.php" title="Cerrar sesión"><i class="bi bi-box-arrow-left"></i></a>
        </div>

        <div class="logo">
            <a href="biblioteca.php" title="GameVault">GameVault</a>
        </div>

        <div class="account">
            <a href="agregar_registro.php" title="Agregar registro"><i class="bi bi-patch-plus-fill"></i></a>
            <!--<a href="#" title="Notificaciones"><i class="bi bi-bell"></i></a>-->
            <a href="mi_biblioteca.php" title="Mi biblioteca"><i class="bi bi-folder"></i></a>
            <!--<a href="#" title="Mi Cuenta"><i class="bi bi-person-circle"></i></a>-->
        </div>
    </header>

    <main>
        <section class="form-cont">
            <h1>Agregar VideoJuego</h1>

            <?php if (!empty($mensaje)): ?>
                <div class="alert-<?php echo $tipo_mensaje ?>">
                    <?php echo htmlspecialchars($mensaje); ?>
                </div>
            <?php endif; ?>

            <form action="agregar_registro.php" method="POST" class="form-videogame">
                <div class="field">
                    <input type="text" name="titulo_videojuego" id="titulo_videojuego" placeholder="Título">
                </div>

                <div class="field">
                    <input type="text" name="descripcion_videojuego" id="descripcion_videojuego" placeholder="Descripción">
                </div>

                <!--
                <div class="field">
                    <input type="text" name="imagen_videojuego" id="imagen_videojuego" accept="image/png, image/jpeg, image/webp" placeholder="Imagen URL (png, jpeg, webp)">
                </div>

                <div class="field">
                    <input type="date" name="fecha_lanzamiento_videojuego" id="fecha_lanzamiento_videojuego">
                </div>
                -->

                <div class="field">
                    <input type="text" name="desarrolladora_videojuego" id="desarrolladora_videojuego" placeholder="Desarrolladora">
                </div>

                <div class="field">
                    <input type="text" name="editora_videojuego" id="editora_videojuego" placeholder="Editora">
                </div>

                <button type="submit" class="button-add">Guardar cambios</button>
            </form>
        </section>
    </main>
</body>
</html>