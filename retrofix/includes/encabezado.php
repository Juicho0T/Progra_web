<?php
require_once __DIR__ . '/funciones.php';
$titulo_pagina = $titulo_pagina ?? 'RetroFix';
$ruta_base = $ruta_base ?? '';
$usuario = usuario_actual();
?>
<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= escapar($titulo_pagina) ?> | RetroFix</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= $ruta_base ?>assets/css/estilos.css">
</head>
<body>
<header class="barra-navegacion">
    <a class="marca" href="<?= $ruta_base ?>index.php"><span class="marca-icono">◆</span> Retro<span>Fix</span></a>
    <nav class="nav-enlaces">
        <?php if (!$usuario): ?>
            <a href="<?= $ruta_base ?>index.php#servicios">Servicios</a>
            <a href="<?= $ruta_base ?>index.php#como-funciona">Cómo funciona</a>
            <a href="<?= $ruta_base ?>index.php#nosotros">Nosotros</a>
        <?php endif; ?>
        <?php if ($usuario): ?>
            <a class="nav-panel" href="<?= $ruta_base . url_dashboard($usuario['rol']) ?>">Mi panel</a>
            <?php if ($usuario['rol'] === 'administrador'): ?><a href="<?= $ruta_base ?>admin/solicitudes.php">Solicitudes</a><a href="<?= $ruta_base ?>admin/cotizaciones.php">Cotizaciones</a><a href="<?= $ruta_base ?>admin/asignaciones.php">Asignaciones</a><a href="<?= $ruta_base ?>admin/citas.php">Citas</a><a href="<?= $ruta_base ?>admin/reparaciones.php">Reparaciones</a><a href="<?= $ruta_base ?>admin/pagos.php">Pagos</a><a href="<?= $ruta_base ?>admin/usuarios.php">Usuarios</a><a href="<?= $ruta_base ?>admin/reportes.php">Reportes</a><?php elseif ($usuario['rol'] === 'especialista'): ?><a href="<?= $ruta_base ?>especialista/mis_reparaciones.php">Reparaciones</a><?php else: ?><a href="<?= $ruta_base ?>cliente/mis_solicitudes.php">Mis solicitudes</a><a href="<?= $ruta_base ?>cliente/mis_citas.php">Mis citas</a><a href="<?= $ruta_base ?>cliente/historial.php">Historial</a><a href="<?= $ruta_base ?>cliente/perfil.php">Perfil</a><?php endif; ?>
            <a href="<?= $ruta_base ?>cerrar_sesion.php">Salir</a>
        <?php else: ?>
            <a href="<?= $ruta_base ?>login.php">Iniciar sesión</a>
            <a class="boton boton-pequeno" href="<?= $ruta_base ?>registro.php">Registrarse</a>
        <?php endif; ?>
    </nav>
    <button class="menu-movil" aria-label="Abrir menú">☰</button>
</header>
<main class="contenedor-principal">
<?php mostrar_mensaje(); ?>

