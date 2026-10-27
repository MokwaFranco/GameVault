<?php

declare(strict_types=1);

require_once __DIR__ . '/clases/conexion.php';
require_once __DIR__ . '/clases/Sesion.php';
require_once __DIR__ . '/clases/Videojuego.php';

Sesion::proteger('login.php');

$pdo = conexion::obtener();
$videojuegoObj = new Videojuego($pdo);

$id_videojuego = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$id_videojuego) {
    header('Location: Biblioteca.php');
    exit;
}

$videojuego = $videojuegoObj->obtenerPorId($id_videojuego);
if (!$videojuego) {
    header('Location: biblioteca.php');
    exit;
}

$mensaje = '';
$tipo_mensaje = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titulo = trim($_POST['titulo_videojuego']);
    $descripcion = trim($_POST['descripcion_videojuego']);
    $desarrolladora = trim($_POST['desarrolladora_videojuego']);
    $editora = trim($_POST['editora_videojuego']);

    if ($titulo !== "") {
        if ($videojuegoObj->actualizar($id_videojuego, $titulo, $descripcion, $desarrolladora, $editora)) {
            $mensaje = "¡Registro modificado correctamente!";
            $tipo_mensaje = "exito";
            $videojuego = $videojuegoObj->obtenerPorId($id_videojuego);
        } else {
            $mensaje = "No se pudo modificar el registro.";
            $tipo_mensaje = "error";
        }
    } else {
        $mensaje = "Completar los campos obligatorios.";
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
    <title>Editar VideoJuego - GameVault</title>
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
            <a href="agregar_registro.php" title="Agregar videojuego"><i class="bi bi-patch-plus"></i></a>
            <a href="mi_biblioteca.php" title="Mi biblioteca"><i class="bi bi-folder"></i></a>
        </div>
    </header>

    <main>
        <section class="form-cont">
            <h1>Modificar VideoJuego</h1>

            <?php if (!empty($mensaje)): ?>
                <div class="alert-<?php echo $tipo_mensaje ?>">
                    <?php echo htmlspecialchars($mensaje); ?>
                </div>
            <?php endif; ?>

            <form action="modificar_registro.php?id=<?php echo $id_videojuego; ?>" method="POST" class="form-videogame">
                <div class="field">
                    <input type="text" name="titulo_videojuego" id="titulo_videojuego" value="<?php echo htmlspecialchars($videojuego['titulo_videojuego']); ?>" placeholder="Título">
                </div>

                <div class="field">
                    <input type="text" name="descripcion_videojuego" id="descripcion_videojuego" value="<?php echo htmlspecialchars($videojuego['descripcion_videojuego'] ?? ''); ?>" placeholder="Descripción">
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
                    <input type="text" name="desarrolladora_videojuego" id="desarrolladora_videojuego" value="<?php echo htmlspecialchars($videojuego['desarrolladora_videojuego'] ?? ''); ?>" placeholder="Desarrolladora">
                </div>

                <div class="field">
                    <input type="text" name="editora_videojuego" id="editora_videojuego" value="<?php echo htmlspecialchars($videojuego['editora_videojuego'] ?? ''); ?>" placeholder="Editora">
                </div>

                <button type="submit" class="button-add">Guardar cambios</button>
            </form>
        </section>
    </main>
</body>
</html>