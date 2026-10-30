<?php
require_once __DIR__.'/../app/legacy.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function escapar($valor): string
{
    return htmlspecialchars((string) $valor, ENT_QUOTES, 'UTF-8');
}

function redirigir(string $url): void
{
    header('Location: ' . $url);
    exit;
}

function establecer_mensaje(string $tipo, string $texto): void
{
    $_SESSION['mensaje'] = ['tipo' => $tipo, 'texto' => $texto];
}

function mostrar_mensaje(): void
{
    if (!empty($_SESSION['mensaje'])) {
        $mensaje = $_SESSION['mensaje'];
        unset($_SESSION['mensaje']);
        echo '<div class="alerta alerta-' . escapar($mensaje['tipo']) . '">' . escapar($mensaje['texto']) . '</div>';
    }
}

function usuario_actual(): ?array
{
    return $_SESSION['usuario'] ?? null;
}

function url_dashboard(string $rol): string
{
    return match ($rol) {
        'administrador' => 'admin/dashboard.php',
        'especialista' => 'especialista/dashboard.php',
        default => 'cliente/dashboard.php'
    };
}

function estado_clase(string $estado): string
{
    return 'estado estado-' . strtolower(str_replace([' ', 'ó', 'í'], ['-', 'o', 'i'], $estado));
}

function registrar_historial(PDO $pdo, int $id_reparacion, ?string $estado_anterior, string $estado_nuevo, string $comentario, int $usuario_actualiza): void
{
    $consulta = $pdo->prepare('INSERT INTO historial_reparacion (id_reparacion, estado_anterior, estado_nuevo, comentario, usuario_actualiza) VALUES (?, ?, ?, ?, ?)');
    $consulta->execute([$id_reparacion, $estado_anterior, $estado_nuevo, $comentario, $usuario_actualiza]);
}

