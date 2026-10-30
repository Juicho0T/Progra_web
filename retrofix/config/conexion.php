<?php
require_once __DIR__.'/../app/legacy.php';
// Conexión central a MySQL/MariaDB mediante PDO para XAMPP.
$servidor = 'localhost';
$base_datos = 'retrofix';
$usuario_bd = 'root';
$contrasena_bd = '';
// En esta instalación de XAMPP MySQL usa 3308 porque el puerto 3306 está ocupado.
$puerto = '3308';

try {
    $pdo = new PDO(
        "mysql:host={$servidor};port={$puerto};dbname={$base_datos};charset=utf8mb4",
        $usuario_bd,
        $contrasena_bd,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false
        ]
    );
} catch (PDOException $error) {
    die('No se pudo conectar a la base de datos. Verifica que MySQL esté activo y que hayas importado database.sql. Detalle: ' . htmlspecialchars($error->getMessage()));
}

