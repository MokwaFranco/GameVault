<?php

declare(strict_types=1);

require_once __DIR__ . '/Sesion.php';

Sesion::cerrar();

header('Location: login.php');
exit;