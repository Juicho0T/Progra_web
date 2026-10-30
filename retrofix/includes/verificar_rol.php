<?php
require_once __DIR__ . '/funciones.php';
$roles_permitidos = $roles_permitidos ?? [];
if (empty($_SESSION['usuario']) || !in_array($_SESSION['usuario']['rol'], $roles_permitidos, true)) {
    establecer_mensaje('error', 'No tienes permisos para acceder a esta sección.');
    redirigir('../index.php');
}

