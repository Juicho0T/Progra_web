<?php
function boton(string $text,string $url,string $class=''): string {return '<a class="button '.e($class).'" href="'.e($url).'">'.e($text).'</a>';}
function titulo(string $eyebrow,string $title,string $description=''): void {echo '<div class="page-heading"><p class="eyebrow">'.e($eyebrow).'</p><h1>'.e($title).'</h1><p>'.e($description).'</p></div>';}
function badge(string $state): string {return '<span class="badge state-'.e($state).'">'.e(etiqueta($state)).'</span>';}
function iniciar_form(string $accion,array $hidden=[],bool $file=false): void {
 echo '<form method="post" class="form"'.($file?' enctype="multipart/form-data"':'').'>'.campo_csrf().'<input type="hidden" name="accion" value="'.e($accion).'">';
 foreach($hidden as $k=>$v)echo '<input type="hidden" name="'.e($k).'" value="'.e($v).'">';
}
function fin_form(string $label,string $class=''): void {echo '<button class="button '.e($class).'" type="submit">'.e($label).'</button></form>';}
function campo(string $name,string $label,$value='',string $type='text',bool $required=true,string $extra=''): void {
 if($_SERVER['REQUEST_METHOD']==='POST'&&!in_array($type,['password','file'],true))$value=$_POST[$name]??$value;
 echo '<label>'.e($label);$req=$required?' required':'';
 if($type==='textarea')echo '<textarea name="'.e($name).'" rows="4"'.$req.' '.$extra.'>'.e($value).'</textarea>';
 else echo '<input name="'.e($name).'" type="'.e($type).'"'.($type!=='file'?' value="'.e($value).'"':'').$req.' '.$extra.'>';
 echo '</label>';
}
function select_campo(string $name,string $label,array $options,$value=''): void {if($_SERVER['REQUEST_METHOD']==='POST')$value=$_POST[$name]??$value;echo '<label>'.e($label).'<select name="'.e($name).'" required>';foreach($options as $k=>$v)echo '<option value="'.e($k).'"'.((string)$k===(string)$value?' selected':'').'>'.e($v).'</option>';echo '</select></label>';}
function check(string $name,string $label,bool $checked): void {echo '<label class="check"><input type="checkbox" name="'.e($name).'" value="1"'.($checked?' checked':'').'>'.e($label).'</label>';}
function vacio(string $title,string $text): void {echo '<div class="empty"><span class="empty-symbol">＋</span><h3>'.e($title).'</h3><p>'.e($text).'</p></div>';}
function folio(string $tipo,int $id): string {return ['reparacion'=>'RF','pedido'=>'PV','suscripcion'=>'SU','oferta'=>'OF'][$tipo].'-'.str_pad((string)$id,5,'0',STR_PAD_LEFT);}
function arte_juego(array $g): string {return '<div class="game-art art-'.e($g['arte']).'" aria-hidden="true"><div class="orbit"></div><div class="landscape"></div><span>'.e($g['plataforma']).'</span><strong>'.e($g['titulo']).'</strong><small>RETROFIX / SELECCIÓN</small></div>';}
function mando(string $class=''): string {return '<div class="controller-scene '.e($class).'" aria-hidden="true"><div class="controller"><div class="controller-top"></div><div class="dpad"></div><div class="stick stick-left"></div><div class="stick stick-right"></div><div class="face-buttons"><i></i><i></i><i></i><i></i></div><div class="controller-light"></div></div><div class="controller-shadow"></div></div>';}
function lista_operaciones(string $tipo,array $rows): void {if(!$rows){vacio('Aquí empieza tu siguiente paso.','Cuando registres una operación, podrás seguirla desde este espacio.');return;}echo '<div class="record-list">';foreach($rows as $r){$title=$r['titulo']??$r['nombre']??$r['servicio']??'Operación';echo '<a class="record" href="'.e(ruta('detalle',['tipo'=>$tipo,'id'=>$r['id']])).'"><div><small>'.e(folio($tipo,(int)$r['id'])).' · '.e(date('d/m/Y',strtotime($r['creado']))).'</small><h3>'.e($title).'</h3></div><div>'.badge($r['estado']).'<span class="chevron">›</span></div></a>';}echo '</div>';}
function listar_usuario(string $tipo): array {$u=usuario();$tabla=['reparacion'=>'rf_repairs','pedido'=>'rf_orders','suscripcion'=>'rf_subscriptions','oferta'=>'rf_offers'][$tipo];$where='1=1';$params=[];if($u['rol']==='cliente'){$where='cliente_id=?';$params=[$u['id']];}elseif($u['rol']==='especialista'){$where='tecnico_id=?';$params=[$u['id']];}return filas("SELECT * FROM $tabla WHERE $where ORDER BY id DESC",$params);}
