<?php

declare(strict_types=1);

require_once 'Conexion.php';
require_once 'Sesion.php';
require_once 'Coleccion.php';

Sesion::proteger('login.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_videojuego'])) {
    $usuario_actual = Sesion::usuario();
    $id_usuario = (int) ($usuario_actual['id_usuario'] ?? 0);
    $id_videojuego = filter_input(INPUT_POST, 'id_videojuego', FILTER_VALIDATE_INT);

    if ($id_usuario && $id_videojuego) {
        $pdo = conexion::obtener();

        $coleccion = new coleccion($pdo);
        $coleccion->agregar($id_usuario, $id_videojuego);
    }
}

header('Location: mi_coleccion.php');
exit;

?>