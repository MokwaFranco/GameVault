<?php

declare(strict_types=1);

require_once __DIR__ . '/clases/conexion.php';
require_once __DIR__ . '/clases/Sesion.php';
require_once __DIR__ . '/clases/Editora.php';

Sesion::proteger('login.php');

$pdo = conexion::obtener();

$mensaje = '';
$tipo_mensaje = '';

$editoraObj = new Editora($pdo);

if ($_SERVER['REQUEST_METHOD']  === 'POST') {
    $nombre_editora = trim($_POST['nombre_editora'] ?? '');

    if ($nombre_editora !== '') {
        if ($editoraObj->agregarEditora($nombre_editora)) {
            $mensaje = '¡Editora agregada exitosamente!';
            $tipo_mensaje = 'exito';
        } else {
            $mensaje = 'Editora existente.';
            $tipo_mensaje = 'error';
        }
    } else {
        $mensaje = 'Completar los campos obligatorios.';
        $tipo_mensaje = 'error';
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agregar editora - GameVault</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/abm.css">
    <link rel="stylesheet" href="font/bootstrap-icons.css">
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
            <a href="agregar_editora.php" title="agregar editora nueva"><i class="bi bi-clipboard-plus-fill"></i></a>

            <a href="agregar_registro.php" title="agregar nuevo registro"><i class="bi bi-patch-plus"></i></a>
            <!--<a href="#" title="Notificaciones"><i class="bi bi-bell"></i></a>-->
            <a href="mi_biblioteca.php" title="Mi biblioteca"><i class="bi bi-folder"></i></a>
            <!--<a href="#" title="Mi Cuenta"><i class="bi bi-person-circle"></i></a>-->
        </div>
    </header>

    <main>
        <section class="form-cont">
            <h1>Agregar editora</h1>

            <?php if (!empty($mensaje)): ?>
                <div class="alert-<?php echo $tipo_mensaje ?>">
                    <?php echo htmlspecialchars($mensaje); ?>
                </div>
            <?php endif; ?>

            <form action="agregar_editora.php" method="POST" class="form-videogame">
                <div class="field">
                    <input type="text" name="nombre_editora" id="nombre_editora" placeholder="Nombre">
                </div>

                <button type="submit" class="button-add">Guardar cambios</button>
            </form>
        </section>
    </main>
</body>
</html>