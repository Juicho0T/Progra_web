<?php
declare(strict_types=1);
ob_start();
require __DIR__.'/bootstrap.php';
require __DIR__.'/actions.php';
$pagina=(string)($_GET['p']??'inicio');$error=null;
try {
 exigir((bool)fila("SELECT valor FROM rf_meta WHERE clave='importacion_v1'"),'Ejecuta php app/install.php para preparar RetroFix.');
 if ($_SERVER['REQUEST_METHOD']==='POST') {
  exigir(hash_equals(csrf(),(string)($_POST['csrf']??'')),'La sesión del formulario expiró. Recarga la página.');db()->beginTransaction();
  try {[$destino,$params,$mensaje]=ejecutar_accion((string)($_POST['accion']??''));db()->commit();flash($mensaje);ir($destino,$params);}
  catch(Throwable $ex){if(db()->inTransaction())db()->rollBack();throw $ex;}
 }
 if($pagina==='foto'){
  $r=operativo((string)($_GET['tipo']??''),(int)($_GET['id']??0));$name=$r['foto']??'';
  exigir((bool)preg_match('/^[a-f0-9]{48}\.(jpg|png|webp)$/D',$name),'Imagen no disponible.');$path=__DIR__.'/../storage/uploads/'.$name;exigir(is_file($path),'Imagen no disponible.');header('Content-Type: '.(new finfo(FILEINFO_MIME_TYPE))->file($path));readfile($path);exit;
 }
}catch(DomainException $ex){$error=$ex->getMessage();http_response_code(422);}catch(Throwable $ex){error_log('RetroFix: '.$ex->getMessage());http_response_code(503);$error='No pudimos completar la operación. Comprueba MySQL y la instalación; tus datos no se borraron.';}
require __DIR__.'/views.php';
