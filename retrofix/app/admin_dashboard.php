<?php
rol(['administrador']);
$pendientes=(int)consulta("SELECT COUNT(*) FROM rf_repairs WHERE estado='recibida'")->fetchColumn();
$porAsignar=(int)consulta("SELECT COUNT(*) FROM rf_repairs WHERE estado='aprobada'")->fetchColumn();
$porEntregar=(int)consulta("SELECT COUNT(*) FROM rf_repairs WHERE estado='terminada'")->fetchColumn();
$esperando=(int)consulta("SELECT COUNT(*) FROM rf_repairs WHERE estado IN ('cotizada','pausada')")->fetchColumn();
if($pagina==='panel'):
titulo('ADMINISTRACIÓN','Tu centro de control.','Revisa lo pendiente y entra directamente a cada área del negocio.');
?>
<div class="admin-priorities">
<?php foreach([['solicitudes','recibida',$pendientes,'Solicitudes por revisar','Revisar y cotizar'],['cotizaciones','',$esperando,'Esperando autorización','Ver cotizaciones'],['solicitudes','aprobada',$porAsignar,'Listas para asignar','Asignar especialista'],['reparaciones','terminada',$porEntregar,'Reparaciones terminadas','Gestionar entrega']] as [$page,$state,$count,$label,$action]):?>
<a class="priority-card" href="<?=e(ruta($page,['estado'=>$state]))?>"><span><?=e($label)?></span><strong><?=$count?></strong><span class="priority-action"><?=e($action)?> <span aria-hidden="true">→</span></span></a>
<?php endforeach;?></div>
<div class="admin-section-heading"><h2>Todas tus herramientas</h2><p>Organizadas por lo que necesitas hacer.</p></div>
<?php
$groups=[
 'Taller y atención'=>[
 ['solicitudes','Solicitudes de reparación','Consulta cliente, consola y falla. Revisa solicitudes nuevas y prepara su cotización.','01'],
 ['cotizaciones','Cotizaciones','Envía presupuestos, consulta autorizaciones y crea nuevas versiones.','02'],
 ['reparaciones','Reparaciones y entregas','Asigna especialistas, consulta avances y confirma entregas.','03'],
 ['citas','Agenda de citas','Consulta horarios de recepción, diagnóstico y entrega.','04']],
 'Ventas y catálogo'=>[
 ['pedidos','Pedidos de videojuegos','Revisa compras, pagos y entregas pendientes en taller.','05'],
 ['inventario','Inventario de videojuegos','Agrega juegos, cambia precios y controla las existencias.','06'],
 ['ofertas','Compra de juegos usados','Valora ofertas de clientes y registra la recepción de sus juegos.','07'],
 ['mis_suscripciones','Solicitudes de suscripción','Revisa pagos y registra entregas digitales de demostración.','08'],
 ['planes_admin','Planes Xbox y PlayStation','Configura precios, duración y disponibilidad de cada plan.','09']],
 'Administración del negocio'=>[
 ['pagos','Pagos y comprobantes','Consulta los importes registrados y abre sus comprobantes.','10'],
 ['usuarios','Usuarios y permisos','Administra clientes, especialistas y administradores.','11'],
 ['reportes','Reportes del negocio','Consulta ingresos simulados, actividad del taller y reseñas.','12'],
 ['auditoria','Registro de actividad','Revisa qué se modificó, cuándo y quién lo hizo.','13']]];
foreach($groups as $group=>$items):?><section class="admin-tool-group"><h3><?=e($group)?></h3><div class="admin-tools"><?php foreach($items as [$page,$title,$description,$number]):?><a class="admin-tool" href="<?=e(ruta($page))?>"><span class="tool-number"><?=$number?></span><div><h4><?=e($title)?></h4><p><?=e($description)?></p><span class="text-link">Abrir sección →</span></div></a><?php endforeach;?></div></section><?php endforeach;?>
<?php return;endif;
$esCotizacion=$pagina==='cotizaciones';
titulo('TALLER · ADMINISTRACIÓN',$esCotizacion?'Cotizaciones':($pagina==='solicitudes'?'Solicitudes de reparación':'Reparaciones y entregas'),$esCotizacion?'Consulta la última versión de cada presupuesto. Abre el expediente para ver su historial o enviar una nueva cotización.':'Revisa la información del cliente y su consola. Cada solicitud tiene un acceso directo a su expediente y presupuesto.');
$estado=(string)($_GET['estado']??'');$busqueda=trim((string)($_GET['q']??''));
$states=['recibida'=>'Por revisar / cotizar','cotizada'=>'Esperando autorización','aprobada'=>'Autorizadas / por asignar','asignada'=>'Asignadas','diagnostico'=>'En diagnóstico','reparando'=>'En reparación','pruebas'=>'En pruebas','pausada'=>'Esperando nueva autorización','terminada'=>'Listas para entregar','entregada'=>'Entregadas','cancelada'=>'Canceladas'];
$sql='SELECT r.*,u.nombre cliente,u.correo,c.marca,c.modelo,q.id quote_id,q.version,q.total quote_total,q.estado quote_estado,q.vence FROM rf_repairs r JOIN rf_users u ON u.id=r.cliente_id JOIN rf_consoles c ON c.id=r.consola_id LEFT JOIN rf_quotes q ON q.id=(SELECT qq.id FROM rf_quotes qq WHERE qq.reparacion_id=r.id ORDER BY qq.version DESC LIMIT 1) WHERE 1=1';$params=[];
if($esCotizacion)$sql.=' AND q.id IS NOT NULL';
if(isset($states[$estado])){$sql.=' AND r.estado=?';$params[]=$estado;}
if($busqueda!==''){$sql.=' AND (u.nombre LIKE ? OR u.correo LIKE ? OR c.modelo LIKE ? OR c.marca LIKE ? OR r.id=?)';$params=array_merge($params,array_fill(0,4,'%'.$busqueda.'%'),[(int)preg_replace('/^RF-0*/i','',$busqueda)]);}
$rows=filas($sql.' ORDER BY r.id DESC',$params);
?>
<div class="admin-workflow"><strong>Así se atiende una solicitud</strong><p>1. Revisa la falla <span>→</span> 2. Envía la cotización <span>→</span> 3. El cliente autoriza <span>→</span> 4. Asigna un especialista</p></div>
<?php if($esCotizacion):?><p><?=boton('Cotizar una solicitud',ruta('solicitudes',['estado'=>'recibida']))?></p><?php endif;?>
<form method="get" class="filters"><input type="hidden" name="p" value="<?=e($pagina)?>"><label class="search-field">Buscar solicitud<input name="q" value="<?=e($busqueda)?>" placeholder="Cliente, consola o folio RF-00001"></label><label>Estado<select name="estado"><option value="">Todos los estados</option><?php foreach($states as $value=>$label):?><option value="<?=e($value)?>" <?=$estado===$value?'selected':''?>><?=e($label)?></option><?php endforeach;?></select></label><button class="button dark">Filtrar</button><a class="text-link" href="<?=e(ruta($pagina))?>">Limpiar</a></form>
<p class="small"><?=count($rows)?> <?=count($rows)===1?'registro encontrado':'registros encontrados'?></p>
<div class="admin-request-list"><?php foreach($rows as $r):$canQuote=!in_array($r['estado'],['entregada','cancelada','terminada'],true)&&!pago('reparacion',(int)$r['id']);$url=ruta('detalle',['tipo'=>'reparacion','id'=>$r['id']]);?>
<article class="admin-request card"><div class="request-top"><div><p class="eyebrow"><?=e(folio('reparacion',(int)$r['id']))?> · <?=e(date('d/m/Y',strtotime($r['creado'])))?></p><h2><?=e($r['marca'].' '.$r['modelo'])?></h2><p class="request-customer"><?=e($r['cliente'])?> · <?=e($r['correo'])?></p></div><?=badge($r['estado'])?></div>
<div class="request-info"><div><span class="small muted">Servicio y falla reportada</span><h3><?=e($r['servicio'])?></h3><p><?=e(mb_strimwidth($r['falla'],0,240,'…'))?></p></div><div class="quote-summary"><span class="small muted">Última cotización</span><?php if($r['quote_id']):?><strong><?=dinero($r['quote_total'])?></strong><p>Versión <?=$r['version']?> · <?=badge($r['quote_estado'])?><br>Vigente hasta <?=e($r['vence'])?><?=$r['quote_estado']==='pendiente'&&$r['vence']<date('Y-m-d')?' · Vencida':''?></p><?php else:?><strong>Sin cotización</strong><p>Revisa la solicitud y envía un presupuesto.</p><?php endif;?></div></div>
<div class="actions"><?=boton('Revisar solicitud',$url,'ghost')?><?php if($canQuote):echo boton($r['quote_id']?'Nueva versión de cotización':'Crear cotización',$url.'&cotizar=1#cotizar');endif;?><?php if($r['estado']==='aprobada')echo boton('Asignar especialista',$url.'#asignar','dark');?></div></article>
<?php endforeach;?></div><?php if(!$rows)vacio('No hay solicitudes con estos filtros.','Prueba otro estado o limpia la búsqueda. Las nuevas solicitudes aparecerán aquí.');?>
