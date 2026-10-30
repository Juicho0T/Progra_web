<?php
require_once __DIR__ . '/funciones.php';
if (empty($_SESSION['usuario'])) {
    establecer_mensaje('error', 'Debes iniciar sesión para continuar.');
    redirigir('../login.php');
}

