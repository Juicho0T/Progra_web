<?php
require_once __DIR__ . '/includes/funciones.php';
$_SESSION = [];
session_destroy();
session_start();
establecer_mensaje('exito', 'Sesión cerrada correctamente.');
redirigir('index.php');

