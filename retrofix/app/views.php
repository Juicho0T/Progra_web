<?php
require __DIR__.'/ui.php';
try{$u=usuario();}catch(Throwable $ex){$u=null;}
$publicas=['inicio','tienda','producto','suscripciones','login','registro','servicios'];
if(!$u&&!in_array($pagina,$publicas,true)&&!$error)ir('login');
if($pagina==='inicio'&&$u&&!$error)ir('panel');
$admins=['inventario','producto_editar','planes_admin','plan_editar','usuarios','reportes','pagos','auditoria','solicitudes','cotizaciones'];
if(in_array($pagina,$admins,true)&&(!$u||$u['rol']!=='administrador')){$pagina='denegado';http_response_code(403);}
if(in_array($pagina,['nueva_reparacion','vender','carrito','plan'],true)&&$u&&$u['rol']!=='cliente'){$pagina='denegado';http_response_code(403);}
if($u&&$u['rol']==='especialista'&&in_array($pagina,['pedidos','ofertas','mis_suscripciones','historial'],true)){$pagina='denegado';http_response_code(403);}
?>
<!doctype html><html lang="es"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><meta name="theme-color" content="#000000"><title>RetroFix · Juega. Otra vez.</title><meta name="description" content="Reparación de consolas de todas las generaciones, videojuegos y suscripciones. Tu siguiente partida empieza aquí."><link rel="stylesheet" href="assets/css/retrofix.css"><link rel="stylesheet" href="assets/css/admin.css"><link rel="stylesheet" href="assets/css/cinematic.css?v=1"><script src="assets/js/retrofix.js" defer></script></head><body>
<a class="skip" href="#contenido">Saltar al contenido</a><header class="global-header"><div class="nav-inner"><a class="brand" href="<?=e(ruta($u?'panel':'inicio'))?>" aria-label="RetroFix, inicio"><span class="brand-mark">r<span>f</span></span>RetroFix</a><button class="menu-toggle" aria-expanded="false" aria-controls="navegacion" aria-label="Abrir navegación"><span></span><span></span></button><nav id="navegacion">
<?php if(!$u):?><a href="<?=e(ruta('servicios'))?>">Reparaciones</a><a href="<?=e(ruta('tienda'))?>">Videojuegos</a><a href="<?=e(ruta('suscripciones'))?>">Suscripciones</a><a href="<?=e(ruta('inicio'))?>#como-funciona">Cómo funciona</a><a href="<?=e(ruta('login'))?>">Mi cuenta ↗</a>
<?php else:?><a href="<?=e(ruta('panel'))?>">Mi espacio</a><a href="<?=e(ruta('reparaciones'))?>">Reparaciones</a><?php if($u['rol']!=='especialista'):?><a href="<?=e(ruta('tienda'))?>">Videojuegos</a><a href="<?=e(ruta('suscripciones'))?>">Suscripciones</a><a href="<?=e(ruta($u['rol']==='administrador'?'reportes':'historial'))?>"><?=$u['rol']==='administrador'?'Reportes':'Historial'?></a><?php endif;?><a href="<?=e(ruta('perfil'))?>">Mi cuenta</a><?php if($u['rol']==='cliente'):?><a class="bag-link" href="<?=e(ruta('carrito'))?>">Bolsa <span><?=array_sum($_SESSION['cart']??[])?></span></a><?php endif;?><?php endif;?></nav></div></header>
<div class="notice-bar">Un espacio para todas tus generaciones de juego. <span>Proyecto académico · pagos y entregas digitales simulados.</span></div>
<main id="contenido" class="<?=$pagina==='inicio'?'':'page-shell'?>">
<?php if($u&&$u['rol']==='administrador'):?><nav class="admin-navigation" aria-label="Herramientas de administración"><?php foreach(['panel'=>'Panel de administración','solicitudes'=>'Solicitudes','cotizaciones'=>'Cotizaciones','reparaciones'=>'Reparaciones','citas'=>'Agenda','pedidos'=>'Pedidos','inventario'=>'Inventario','ofertas'=>'Compra de usados','mis_suscripciones'=>'Entregas digitales','planes_admin'=>'Planes','pagos'=>'Pagos','usuarios'=>'Usuarios','reportes'=>'Reportes','auditoria'=>'Actividad'] as $link=>$label):?><a href="<?=e(ruta($link))?>" <?=$pagina===$link?'aria-current="page"':''?>><?=e($label)?></a><?php endforeach;?></nav><?php endif;?>
<?php if($error):?><div class="alert error" role="alert"><?=e($error)?></div><?php endif;?>
<?php if(isset($_SESSION['flash'])):[$type,$msg]=$_SESSION['flash'];unset($_SESSION['flash']);?><div class="alert <?=e($type)?>" role="status"><?=e($msg)?></div><?php endif;?>
<?php try {
 if(in_array($pagina,['inicio','servicios','login','registro','tienda','producto','carrito','suscripciones','plan'],true))require __DIR__.'/public_views.php';
 elseif(in_array($pagina,['inventario','producto_editar','planes_admin','plan_editar','usuarios','reportes','pagos','auditoria','comprobante'],true))require __DIR__.'/admin_views.php';
 elseif($pagina==='detalle')require __DIR__.'/detail.php';
 else require __DIR__.'/account_views.php';
}catch(DomainException $ex){http_response_code(403);echo '<div class="alert error" role="alert">'.e($ex->getMessage()).'</div>'.boton('Volver a mi espacio',ruta($u?'panel':'inicio'),'ghost');}catch(Throwable $ex){error_log('RetroFix vista: '.$ex->getMessage());echo '<div class="alert error">No se pudo cargar este contenido. Comprueba la instalación y vuelve a intentar.</div>';}?>
</main><footer><div class="footer-inner"><div><a class="brand" href="<?=e(ruta('inicio'))?>">RetroFix</a><p>Para seguir jugando.</p></div><div><a href="<?=e(ruta('servicios'))?>">Reparaciones</a><a href="<?=e(ruta('tienda'))?>">Videojuegos</a><a href="<?=e(ruta('suscripciones'))?>">Suscripciones</a></div><p class="fineprint">Proyecto académico · Grupo 8Y<br>Pagos simulados. Comprobantes no fiscales.<br>Las marcas mencionadas pertenecen a sus respectivos titulares.</p></div><div class="footer-bottom">RetroFix · Todas las generaciones, la misma pasión.<span>México · MXN</span></div></footer></body></html>
