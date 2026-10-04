<?php

declare(strict_types=1);

require_once __DIR__ . "/conexion.php";
require_once __DIR__ . "/Usuario.php";
require_once __DIR__ . "/Sesion.php";

Sesion::iniciar();

if (Sesion::autenticado()) {
    header('Location: home.php');
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre_usuario = trim($_POST['nombre_usuario'] ?? '');
    $contrasena_usuario = trim($_POST['contrasena_usuario'] ?? '');

    if ($nombre_usuario === '' || $contrasena_usuario === '') {
        $error = 'Completá los campos.';
    } else {
        $usuario = new Usuario(Conexion::obtener());
        $datos = $usuario->autenticar($nombre_usuario, $contrasena_usuario);

        if ($datos !== null) {
            Sesion::login($datos);
            header('Location: home.php');
            exit;
        }

        $error = 'Nombre de usuario o contraseña incorrectos.';
    }
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/login.css">
    <title>Iniciar sesión - GameVault</title>
</head>
<body>
    <form action="login.php" method="POST">
        <div class="logo">
            <a href="login.php">GameVault</a>
        </div>

        <?php if ($error !== ''): ?>
            <div class="alerta">
                <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>

        <div class="campo">
            <!-- <label for="nombre_usuario">Nombre de usuario</label> -->
            <input type="text" name="nombre_usuario" id="nombre_usuario" placeholder="Nombre de usuario" required>
        </div>

        <div class="campo">
            <!-- <label for="contrasena_usuario">Contraseña</label> -->
            <input type="password" name="contrasena_usuario" id="contrasena_usuario" placeholder="Contraseña" required>
        </div>

        <button type="submit">Iniciar sesión</button>
    </form>
</body>
</html>