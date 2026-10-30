<?php
declare(strict_types=1);
date_default_timezone_set('America/Mexico_City');
function db(): PDO {
    static $pdo;
    if (!$pdo) {
        $config = ['host'=>'127.0.0.1','port'=>'3308','name'=>'retrofix','user'=>'root','pass'=>''];
        if (is_file(__DIR__.'/../config/local.php')) $config = array_merge($config, require __DIR__.'/../config/local.php');
        foreach ($config as $k=>$v) if (getenv('RETROFIX_DB_'.strtoupper($k)) !== false) $config[$k]=getenv('RETROFIX_DB_'.strtoupper($k));
        $pdo=new PDO("mysql:host={$config['host']};port={$config['port']};dbname={$config['name']};charset=utf8mb4",$config['user'],$config['pass'],[PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION,PDO::ATTR_DEFAULT_FETCH_MODE=>PDO::FETCH_ASSOC,PDO::ATTR_EMULATE_PREPARES=>false]);
    }
    return $pdo;
}
function consulta(string $sql,array $params=[]): PDOStatement { $q=db()->prepare($sql);$q->execute($params);return $q; }
function fila(string $sql,array $params=[]): ?array { return consulta($sql,$params)->fetch() ?: null; }
function filas(string $sql,array $params=[]): array { return consulta($sql,$params)->fetchAll(); }
function e($value): string { return htmlspecialchars((string)$value,ENT_QUOTES,'UTF-8'); }
function dinero($cents): string { return '$'.number_format((int)$cents/100,2).' MXN'; }
function ruta(string $page='inicio',array $params=[]): string { return 'index.php?'.http_build_query(['p'=>$page]+$params); }
function ir(string $page,array $params=[]): void { header('Location: '.ruta($page,$params),true,303);exit; }
function exigir(bool $ok,string $message): void { if (!$ok) throw new DomainException($message); }
function texto(string $key,int $min=1,int $max=2000): string { $v=trim((string)($_POST[$key]??''));exigir(mb_strlen($v)>=$min && mb_strlen($v)<=$max,"Revisa el campo $key (de $min a $max caracteres).");return $v; }
function numero(string $key,int $min=0,int $max=100000): int { $v=filter_var($_POST[$key]??null,FILTER_VALIDATE_INT);exigir($v!==false && $v!==null && $v>=$min && $v<=$max,"Revisa el campo $key.");return $v; }
function importe(string $key): int { $v=trim((string)($_POST[$key]??''));exigir((bool)preg_match('/^\d{1,6}(\.\d{1,2})?$/D',$v),"Escribe un importe válido en $key.");return (int)round((float)$v*100); }
function fecha_valida(string $date): bool { $d=DateTimeImmutable::createFromFormat('!Y-m-d',$date);return $d && $d->format('Y-m-d')===$date; }
function usuario(): ?array { return empty($_SESSION['uid']) ? null : fila('SELECT id,nombre,correo,telefono,rol,activo FROM rf_users WHERE id=? AND activo=1',[$_SESSION['uid']]); }
function rol(array $permitidos): array { $u=usuario();exigir($u && in_array($u['rol'],$permitidos,true),'No tienes permiso para esta operación.');return $u; }
function csrf(): string { return $_SESSION['csrf']??($_SESSION['csrf']=bin2hex(random_bytes(24))); }
function campo_csrf(): string { return '<input type="hidden" name="csrf" value="'.e(csrf()).'">'; }
function flash(string $message,string $type='success'): void { $_SESSION['flash']=[$type,$message]; }
function auditar(string $tipo,int $id,string $accion,string $detalle=''): void { consulta('INSERT INTO rf_audit(autor_id,tipo,referencia_id,accion,detalle) VALUES(?,?,?,?,?)',[usuario()['id'],$tipo,$id,$accion,$detalle]); }
function historial(int $id,string $antes,string $nuevo,string $comentario): void { consulta('INSERT INTO rf_history(reparacion_id,autor_id,anterior,nuevo,comentario) VALUES(?,?,?,?,?)',[$id,usuario()['id'],$antes,$nuevo,$comentario]);consulta('UPDATE rf_repairs SET estado=?,actualizado=NOW() WHERE id=?',[$nuevo,$id]);auditar('reparacion',$id,$nuevo,$comentario); }
function operativo(string $tipo,int $id,bool $lock=false): array {
    $tabla=['reparacion'=>'rf_repairs','pedido'=>'rf_orders','suscripcion'=>'rf_subscriptions','oferta'=>'rf_offers'][$tipo]??null;
    exigir((bool)$tabla,'Tipo de operación inválido.');$r=fila("SELECT * FROM $tabla WHERE id=?".($lock?' FOR UPDATE':''),[$id]);
    $u=usuario();exigir($r && $u,'Operación no disponible.');
    exigir($u['rol']==='administrador' || ($u['rol']==='cliente' && (int)$r['cliente_id']===(int)$u['id']) || ($tipo==='reparacion' && $u['rol']==='especialista' && (int)$r['tecnico_id']===(int)$u['id']),'No tienes acceso a este registro.');return $r;
}
function cotizacion(int $id): ?array { return fila("SELECT * FROM rf_quotes WHERE reparacion_id=? AND estado='aceptada' ORDER BY version DESC LIMIT 1",[$id]); }
function pago(string $tipo,int $id): ?array { $col=['reparacion'=>'reparacion_id','pedido'=>'pedido_id','suscripcion'=>'suscripcion_id','oferta'=>'oferta_id'][$tipo]??null;exigir((bool)$col,'Operación inválida.');return fila("SELECT p.*,r.id AS recibo_id FROM rf_payments p LEFT JOIN rf_receipts r ON r.pago_id=p.id WHERE p.$col=?",[$id]); }
function total_operacion(string $tipo,array $r): int { return $tipo==='reparacion' ? (int)(cotizacion((int)$r['id'])['total']??0) : (int)($tipo==='oferta'?$r['importe']:$r['total']); }
function pagado(string $tipo,array $r): bool { $p=pago($tipo,(int)$r['id']);return total_operacion($tipo,$r)===0 || ($p && $p['estado']==='pagado' && (int)$p['total']===total_operacion($tipo,$r)); }
function etiqueta(string $s): string { return ['recibida'=>'Solicitud recibida','cotizada'=>'Por autorizar','aprobada'=>'Autorizada','asignada'=>'Asignada','diagnostico'=>'En diagnóstico','reparando'=>'En reparación','pruebas'=>'En pruebas','terminada'=>'Lista para entregar','entregada'=>'Entregada','entregado'=>'Entregado','confirmado'=>'Confirmado','confirmada'=>'Confirmada','lista'=>'Lista para recoger','pausada'=>'Esperando autorización','pendiente'=>'Pendiente','valorada'=>'Oferta disponible','aceptada'=>'Aceptada','rechazada'=>'Rechazada','cancelada'=>'Cancelada','cancelado'=>'Cancelado','sustituida'=>'Versión anterior','pagado'=>'Pago simulado','reembolsado'=>'Reembolso simulado'][$s]??ucfirst($s); }
function foto_subir(): ?string {
    $f=$_FILES['foto']??null;if (!$f || $f['error']===UPLOAD_ERR_NO_FILE) return null;
    exigir($f['error']===UPLOAD_ERR_OK && $f['size']<=3*1024*1024,'La imagen debe pesar menos de 3 MB.');
    $mime=(new finfo(FILEINFO_MIME_TYPE))->file($f['tmp_name']);$ext=['image/jpeg'=>'jpg','image/png'=>'png','image/webp'=>'webp'][$mime]??null;
    exigir($ext && @getimagesize($f['tmp_name']),'Usa una imagen JPG, PNG o WebP válida.');
    $dir=__DIR__.'/../storage/uploads';if (!is_dir($dir)) mkdir($dir,0775,true);
    $name=bin2hex(random_bytes(24)).'.'.$ext;exigir(move_uploaded_file($f['tmp_name'],$dir.'/'.$name),'No fue posible guardar la imagen.');return $name;
}
if (PHP_SAPI!=='cli') {
    $sessionDir=__DIR__.'/../storage/sessions';
    if(!is_dir($sessionDir))mkdir($sessionDir,0775,true);
    session_save_path($sessionDir);
    ini_set('session.use_strict_mode','1');session_name('retrofix_v2');
    session_set_cookie_params(['httponly'=>true,'samesite'=>'Lax','secure'=>!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS']!=='off']);
    session_start();
    header("Content-Security-Policy: default-src 'self'; img-src 'self' data:; style-src 'self'; script-src 'self'; object-src 'none'; base-uri 'self'; frame-ancestors 'none'; form-action 'self'");
    header('X-Content-Type-Options: nosniff');header('Referrer-Policy: same-origin');header('Cache-Control: no-store');
}
