<?php
// Instalación exclusivamente por consola: php app/install.php
if (PHP_SAPI!=='cli') {http_response_code(404);exit;}
require __DIR__.'/bootstrap.php';
db()->exec(file_get_contents(__DIR__.'/schema.sql'));
db()->exec("ALTER TABLE rf_subscriptions ALTER COLUMN estado SET DEFAULT 'confirmada'");
db()->exec("UPDATE rf_subscriptions SET estado='confirmada' WHERE estado='confirmado'");
$createdAccounts=[];
db()->beginTransaction();
try {
 if (!fila("SELECT * FROM rf_meta WHERE clave='importacion_v1'")) {
    $old=(bool)fila("SELECT TABLE_NAME FROM information_schema.TABLES WHERE TABLE_SCHEMA=DATABASE() AND TABLE_NAME='usuarios'");
    if ($old) {
        foreach(filas('SELECT * FROM usuarios') as $u) {
            $hash=$u['contrasena'];if (!password_get_info($hash)['algo']) $hash=password_hash($hash,PASSWORD_DEFAULT);
            consulta('INSERT INTO rf_users(id,nombre,correo,telefono,password_hash,rol,activo,creado) VALUES(?,?,?,?,?,?,?,?)',[$u['id_usuario'],$u['nombre'],$u['correo'],$u['telefono']??'',$hash,$u['rol'],$u['estado']==='Activo'?1:0,$u['fecha_registro']]);
        }
        $estados=['Pendiente'=>'recibida','En revisión'=>'recibida','Cotizada'=>'cotizada','Aceptada'=>'aprobada','Rechazada'=>'recibida','En reparación'=>'reparando','Terminada'=>'terminada','Cancelada'=>'cancelada'];
        $tecnicos=['Pendiente'=>'asignada','Diagnóstico'=>'diagnostico','En reparación'=>'reparando','En pruebas'=>'pruebas','Terminada'=>'terminada'];
        foreach(filas('SELECT * FROM solicitudes') as $s) {
            consulta('INSERT INTO rf_consoles(id,cliente_id,tipo,marca,modelo,serie,condicion) VALUES(?,?,?,?,?,?,?)',[$s['id_solicitud'],$s['id_cliente'],$s['tipo_consola'],$s['marca'],$s['modelo'],$s['numero_serie']??'',$s['estado_fisico']??'']);
            $r=fila('SELECT * FROM reparaciones WHERE id_solicitud=?',[$s['id_solicitud']]);
            consulta('INSERT INTO rf_repairs(id,cliente_id,consola_id,tecnico_id,servicio,falla,estado,diagnostico,trabajo,piezas,creado) VALUES(?,?,?,?,?,?,?,?,?,?,?)',[$s['id_solicitud'],$s['id_cliente'],$s['id_solicitud'],$r['id_especialista']??null,$s['problema_reportado'],$s['descripcion'],$r?($tecnicos[$r['estado']]??'asignada'):($estados[$s['estado']]??'recibida'),$r['diagnostico']??null,$r['trabajo_realizado']??null,$r['piezas_utilizadas']??null,$s['fecha_solicitud']]);
            $version=0;foreach(filas('SELECT * FROM cotizaciones WHERE id_solicitud=? ORDER BY id_cotizacion',[$s['id_solicitud']]) as $c) {
                consulta('INSERT INTO rf_quotes(reparacion_id,administrador_id,version,diagnostico,conceptos,mano_obra,refacciones,total,vence,estado,creado) VALUES(?,?,?,?,?,?,?,?,?,?,?)',[$s['id_solicitud'],$c['id_administrador'],++$version,$c['diagnostico_preliminar']??'',$c['descripcion_trabajo'],round($c['costo_reparacion']*100),round($c['costo_refacciones']*100),round($c['costo_total']*100),$c['vigencia']??date('Y-m-d'),strtolower($c['estado']),$c['fecha_cotizacion']]);
            }
            if ($r) foreach(filas('SELECT * FROM historial_reparacion WHERE id_reparacion=?',[$r['id_reparacion']]) as $h) consulta('INSERT INTO rf_history(reparacion_id,autor_id,anterior,nuevo,comentario,creado) VALUES(?,?,?,?,?,?)',[$s['id_solicitud'],$h['usuario_actualiza'],$tecnicos[$h['estado_anterior']]??'recibida',$tecnicos[$h['estado_nuevo']]??'asignada',$h['comentario']??'',$h['fecha_actualizacion']]);
        }
        foreach(filas('SELECT * FROM citas') as $c) consulta('INSERT INTO rf_appointments(reparacion_id,fecha,tipo,notas,estado) VALUES(?,?,?,?,?)',[$c['id_solicitud'],$c['fecha'].' '.$c['hora'],$c['tipo_cita'],$c['observaciones']??'',strtolower($c['estado'])]);
        foreach(filas("SELECT * FROM pagos WHERE estado='Pagado'") as $p) {
            $admin=fila("SELECT id FROM rf_users WHERE rol='administrador' ORDER BY id LIMIT 1")['id'];
            consulta('INSERT INTO rf_payments(reparacion_id,autor_id,total,metodo,referencia,creado) VALUES(?,?,?,?,?,?)',[$p['id_solicitud'],$admin,round($p['monto']*100),$p['metodo_pago'],'Importado: '.$p['referencia'],$p['fecha_pago']??date('Y-m-d H:i:s')]);
            consulta('INSERT INTO rf_receipts(pago_id) VALUES(?)',[db()->lastInsertId()]);
        }
        foreach(filas('SELECT * FROM resenas') as $r) consulta('INSERT INTO rf_reviews(reparacion_id,cliente_id,estrellas,comentario,creado) VALUES(?,?,?,?,?)',[$r['id_solicitud'],$r['id_cliente'],$r['calificacion'],$r['comentario']??'',$r['fecha']]);
    } else {
        foreach([['Administración RetroFix','admin@retrofix.com','administrador'],['Alex Técnico','especialista@retrofix.com','especialista'],['María López','cliente1@gmail.com','cliente'],['Carlos Ruiz','cliente2@gmail.com','cliente']] as $u) {
            $password=bin2hex(random_bytes(12));
            consulta('INSERT INTO rf_users(nombre,correo,password_hash,rol) VALUES(?,?,?,?)',[$u[0],$u[1],password_hash($password,PASSWORD_DEFAULT),$u[2]]);
            $createdAccounts[]=[$u[1],$password];
        }
    }
    consulta("INSERT INTO rf_meta VALUES('importacion_v1',?)",[date('c')]);
 }
 if (!fila("SELECT * FROM rf_meta WHERE clave='catalogo_demo'")) {
    $games=[['RF-001','Horizon Forbidden West','PlayStation 5','Nuevo',89900,5,'horizon','Explora un mundo extraordinario. Edición física para tu próxima aventura.'],['RF-002','Forza Horizon 5','Xbox Series','Usado · revisado',64900,4,'velocity','Carreteras abiertas y un mundo por recorrer. Disco revisado por el taller.'],['RF-003','The Legend of Zelda: Tears of the Kingdom','Nintendo Switch','Nuevo',119900,3,'forest','Una aventura entre el cielo y la tierra. Edición física de demostración.'],['RF-004','Halo Infinite','Xbox Series','Nuevo',69900,5,'orbit','Vuelve a la acción. Juego de ejemplo para el catálogo académico.'],['RF-005','Gran Turismo 7','PlayStation 5','Usado · revisado',74900,2,'velocity','Precisión en cada curva. Incluye caja y disco revisado.'],['RF-006','Super Mario Odyssey','Nintendo Switch','Usado · revisado',79900,3,'sunset','Un viaje que siempre quieres volver a jugar. Cartucho y caja.']];
    foreach($games as $g) consulta('INSERT INTO rf_products(sku,titulo,plataforma,condicion,precio,stock,arte,descripcion) VALUES(?,?,?,?,?,?,?,?)',$g);
    foreach([['Xbox','Acceso multijugador · demo','1 mes',14900],['Xbox','Catálogo ampliado · demo','3 meses',39900],['PlayStation','Juego online · demo','1 mes',17900],['PlayStation','Catálogo de juegos · demo','3 meses',49900]] as $p) consulta('INSERT INTO rf_plans(plataforma,nombre,duracion,precio,region,descripcion) VALUES(?,?,?,?,?,?)',[$p[0],$p[1],$p[2],$p[3],'México','Producto académico configurable. Entrega simulada; no activa un servicio real.']);
    consulta("INSERT INTO rf_meta VALUES('catalogo_demo','1')");
 }
 db()->commit();echo "Instalación lista. Tablas anteriores conservadas; importación y catálogo idempotentes.\n";
 foreach($createdAccounts as [$email,$password]) echo "Cuenta nueva: $email | Contraseña: $password\n";
 if($createdAccounts) echo "Guarda estas claves en privado; sólo se muestran al crear las cuentas.\n";
} catch(Throwable $e) { if(db()->inTransaction())db()->rollBack();fwrite(STDERR,$e->getMessage()."\n");exit(1); }
