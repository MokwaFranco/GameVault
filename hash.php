<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Hash de Contraseña</title>
</head>
<body>
    <h2>Hash de Contraseña</h2>
    
    <form method="POST">
        <input type="text" id="password" name="password" required>
        <button type="submit">Hashear</button>
    </form>

    <?php

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['password'])) {
        $contrasena = $_POST['password'];
        
        $contrasena_hasheada = password_hash($contrasena, PASSWORD_DEFAULT);
        
        // Lo mostramos en pantalla listo para copiar
        echo '<div class="resultado">';
        echo '<strong>Contraseña original:</strong> ' . htmlspecialchars($contrasena) . '<br><br>';
        echo '<strong>Hash generado (Copiá todo esto):</strong><br>';
        echo '<code>' . $contrasena_hasheada . '</code>';
        echo '</div>';
    }

    ?>
</body>
</html>