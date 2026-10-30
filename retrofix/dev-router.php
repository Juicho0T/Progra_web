<?php
// Servidor local: php -S 127.0.0.1:8080 -t . dev-router.php
$path = rawurldecode(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ?: '/');
if (preg_match('~(?:^|/)(?:\.|app(?:/|$)|config(?:/|$)|includes(?:/|$)|storage(?:/|$))~i', $path) || str_contains($path,'..')) {
    http_response_code(404); exit('No disponible.');
}
if (preg_match('~^/assets/[a-zA-Z0-9_./-]+\.(?:css|js|png|jpg|jpeg|webp|svg|ico|woff2)$~D', $path) && is_file(__DIR__.$path)) return false;
if ($path==='/' || $path==='/index.php') { require __DIR__.'/index.php'; return true; }
if (preg_match('~^/(?:admin/|cliente/|especialista/)?[a-z_]+\.php$~D', $path) && is_file(__DIR__.$path) && $path!=='/dev-router.php') { require __DIR__.'/app/legacy.php'; return true; }
http_response_code(404); echo 'No disponible.';
