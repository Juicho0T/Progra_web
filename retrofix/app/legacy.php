<?php
// Las rutas de la primera versión ya no ejecutan operaciones antiguas.
if (PHP_SAPI !== 'cli') {
    $script = str_replace('\\', '/', $_SERVER['SCRIPT_NAME']);
    $base = dirname($script);
    if (in_array(basename($base), ['admin','cliente','especialista'], true)) $base = dirname($base);
    $page = in_array(basename($script), ['login.php','registro.php'], true) ? pathinfo($script, PATHINFO_FILENAME) : 'panel';
    header('Location: '.rtrim($base,'/').'/index.php?p='.$page, true, 303);
    exit;
}
