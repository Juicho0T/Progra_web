<?php
require_once __DIR__.'/../includes/verificar_sesion.php';
$roles_permitidos=['cliente'];
require_once __DIR__.'/../includes/verificar_rol.php';
require_once __DIR__.'/../config/conexion.php';
$usuario_id=(int)$_SESSION['usuario']['id_usuario'];
$q=$pdo->prepare('SELECT nombre,correo,telefono,fecha_registro FROM usuarios WHERE id_usuario=?');
$q->execute([$usuario_id]);
$perfil=$q->fetch();
$titulo_pagina='Mi perfil';$ruta_base='../';require_once __DIR__.'/../includes/encabezado.php';
?>
<div class="cabecera-panel"><div><span class="etiqueta">CUENTA</span><h1>Mi perfil</h1><p>Información de contacto asociada a tus servicios.</p></div></div>
<section class="tarjeta" style="max-width:650px"><div class="fila-detalle"><span>Nombre</span><strong><?= escapar($perfil['nombre']) ?></strong></div><div class="fila-detalle"><span>Correo</span><strong><?= escapar($perfil['correo']) ?></strong></div><div class="fila-detalle"><span>Teléfono</span><strong><?= escapar($perfil['telefono']?:'No indicado') ?></strong></div><div class="fila-detalle"><span>Cliente desde</span><strong><?= date('d/m/Y',strtotime($perfil['fecha_registro'])) ?></strong></div></section>
<?php require_once __DIR__.'/../includes/pie.php'; ?>
