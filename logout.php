<?php

declare(strict_types=1);

require_once __DIR__ . '/clases/Sesion.php';

Sesion::cerrar();

header('Location: login.php');
exit;