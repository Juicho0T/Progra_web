<?php
require_once __DIR__ . '/config/conexion.php'; require_once __DIR__ . '/includes/funciones.php';
$errores = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = trim($_POST['nombre'] ?? ''); $correo = trim($_POST['correo'] ?? ''); $telefono = trim($_POST['telefono'] ?? ''); $contrasena = $_POST['contrasena'] ?? '';
    if ($nombre === '') $errores[] = 'El nombre es obligatorio.';
    if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) $errores[] = 'Ingresa un correo válido.';
    if (strlen($contrasena) < 6) $errores[] = 'La contraseña debe tener al menos 6 caracteres.';
    if (!$errores) { $existe = $pdo->prepare('SELECT id_usuario FROM usuarios WHERE correo = ?'); $existe->execute([$correo]); if ($existe->fetch()) $errores[] = 'Ese correo ya está registrado.'; else { $insertar = $pdo->prepare('INSERT INTO usuarios (nombre, correo, telefono, contrasena, rol) VALUES (?, ?, ?, ?, "cliente")'); $insertar->execute([$nombre, $correo, $telefono, password_hash($contrasena, PASSWORD_DEFAULT)]); establecer_mensaje('exito', 'Cuenta creada correctamente. Ya puedes iniciar sesión.'); redirigir('login.php'); } }
}
$titulo_pagina = 'Crear cuenta'; $ruta_base = ''; require_once __DIR__ . '/includes/encabezado.php';
?><section class="formulario-acceso"><div class="panel-acceso"><span class="etiqueta">ÚNETE A LA COMUNIDAD</span><h1>Crea tu cuenta <span>RetroFix</span></h1><p>Registra tus solicitudes y sigue cada reparación.</p><?php if ($errores): ?><div class="alerta alerta-error"><ul><?php foreach ($errores as $e): ?><li><?= escapar($e) ?></li><?php endforeach; ?></ul></div><?php endif; ?><form method="post" class="formulario"><label>Nombre completo<input type="text" name="nombre" required value="<?= escapar($_POST['nombre'] ?? '') ?>"></label><label>Correo electrónico<input type="email" name="correo" required value="<?= escapar($_POST['correo'] ?? '') ?>"></label><label>Teléfono <small>(opcional)</small><input type="tel" name="telefono" value="<?= escapar($_POST['telefono'] ?? '') ?>"></label><label>Contraseña<input type="password" name="contrasena" required minlength="6"></label><button class="boton ancho-completo" type="submit">Crear mi cuenta →</button></form><p class="texto-centro">¿Ya tienes cuenta? <a href="login.php">Inicia sesión</a></p></div></section><?php require_once __DIR__ . '/includes/pie.php'; ?>

