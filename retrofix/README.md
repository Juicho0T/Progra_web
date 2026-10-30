# RetroFix · Juega. Otra vez.

Aplicación académica local para reparar consolas de todas las generaciones, comprar y vender videojuegos y gestionar solicitudes de suscripciones Xbox y PlayStation. PHP 8, PDO, MySQL/MariaDB, HTML, CSS y JavaScript; sin frameworks ni dependencias externas.

## Abrir en este equipo

1. Iniciar MySQL desde XAMPP (puerto 3308).
2. Abrir `Iniciar-RetroFix.cmd` y mantener la ventana abierta.
3. Visitar http://127.0.0.1:8080.

Si el servidor ya está iniciado, basta con abrir el enlace. El servidor de desarrollo escucha únicamente en este equipo; no es un despliegue público.

## Cuentas académicas

| Rol | Correo |
| --- | --- |
| Administrador | admin@retrofix.com |
| Especialista | especialista@retrofix.com |
| Cliente | cliente1@gmail.com |
| Otro cliente | cliente2@gmail.com |

En una instalación nueva, el instalador genera una contraseña aleatoria distinta para cada cuenta y la muestra únicamente en la consola al crearlas. Guárdalas en privado. Las instalaciones existentes conservan sus contraseñas y no se modifican al actualizar el código. No publicar contraseñas en GitHub.

## Instalación nueva

1. Importar `database.sql` desde phpMyAdmin. Es aditivo: no contiene DROP TABLE.
2. Revisar conexión: por defecto 127.0.0.1, puerto 3308, base retrofix, root sin contraseña, exclusiva para XAMPP local.
3. Para otra configuración copiar `config/local.example.php` como `config/local.php` y ajustar los datos. También se admiten RETROFIX_DB_HOST, PORT, NAME, USER y PASS.
4. Ejecutar desde esta carpeta: `C:\xampp\php\php.exe app/install.php`. Crea tablas, importa los datos anteriores si existen y agrega catálogo de demostración. Es seguro repetir el instalador: no duplica la importación ni el catálogo.
5. Abrir el iniciador indicado arriba.

Alternativa Apache: colocar el proyecto en htdocs/retrofix y abrir http://localhost/retrofix/ con Apache y MySQL activos. Se necesita AllowOverride para aplicar los archivos .htaccess. No se actualizó la copia antigua de htdocs en esta entrega: el enlace 8080 usa esta carpeta de trabajo.

## Funcionalidades y recorrido

- Público: portada, servicios, catálogo con búsqueda y filtros, detalle de videojuegos, planes, registro e inicio de sesión.
- Cliente: espacio personal sin navegación promocional, perfil, solicitudes con fotografía opcional, cotizaciones, citas, seguimiento, bolsa, pedidos, ofertas de juegos usados, suscripciones, historial y comprobantes.
- Especialista: únicamente reparaciones asignadas, diagnóstico, trabajo realizado, piezas, pruebas y bitácora.
- Administración: cotizaciones versionadas, asignación, agenda, recepción y entregas, inventario, compras a clientes, planes, cuentas y roles, pagos, reportes y auditoría.

### Reparación

Solicitud → cotización → aceptación → asignación → diagnóstico → reparación → pruebas → terminada → pago de prueba → entrega → reseña.

Rechazar una cotización no autoriza el trabajo. Una nueva versión requiere aceptación y pausa los trabajos ya asignados. Las versiones y avances anteriores permanecen en el expediente. Las citas usan intervalos de 30 minutos, con una atención de taller por horario. Una reparación terminada no equivale a una entregada.

### Venta de videojuegos

Bolsa → confirmación con revisión de precio y stock → pedido → pago de prueba → entrega en taller. La confirmación descuenta existencias con una transacción y bloqueos. Los pedidos guardan título, condición y precio históricos. Repetir la confirmación no crea otro pedido. Cancelar antes de entregar restaura stock una sola vez y marca un reembolso simulado si había pago.

### Compra de juegos al cliente

Oferta → valoración del administrador → aceptación del cliente → recepción e inspección → incorporación de una unidad al inventario → pago simulado al vendedor. Una oferta pendiente no crea existencias. RetroFix compra al cliente; no es un mercado de ventas entre particulares.

### Suscripciones

Selección → revisión de plataforma, duración, región e importe → solicitud → pago de prueba → entrega de referencia DEMO. Puede solicitarse renovación de una entrega anterior. Las condiciones confirmadas no cambian con el catálogo. Las fechas de vigencia se capturan expresamente; si no se conocen, quedan sin verificar.

## Seguridad y datos

Contraseñas con password_hash/password_verify, sesiones regeneradas al autenticar, cookies HttpOnly/SameSite, CSRF en formularios, consultas preparadas, validación de propiedad y rol, escape de HTML y cabeceras de seguridad. Imágenes JPG/PNG/WebP de hasta 3 MB, nombres aleatorios y acceso mediante un expediente autorizado. Una imagen por solicitud u oferta.

Montos almacenados como centavos enteros. Transacciones para operaciones relacionadas, pagos únicos por operación, comprobantes no fiscales, bitácora de acciones y movimientos de inventario. Desactivación de cuentas sin borrar sus historiales; no se puede desactivar al especialista con trabajos abiertos ni degradar la propia cuenta administrativa.

`storage`, `config`, `app` e `includes` están bloqueados para acceso web directo. Usar el iniciador o dev-router.php con el servidor PHP: ejecutarlo sin el router expondría archivos internos.

## Estructura

- index.php: entrada única.
- app/bootstrap.php: configuración, sesión, PDO, validación y acceso.
- app/actions.php: reglas del negocio y escrituras.
- app/router.php: transacciones, CSRF, rutas y archivos privados.
- app/*views.php, detail.php, ui.php: pantallas y componentes.
- app/schema.sql y database.sql: esquema aditivo.
- app/install.php: instalación, importación y catálogo inicial.
- assets/css/retrofix.css y assets/js/retrofix.js: diseño adaptable e interacciones.
- dev-router.php: servidor local con rutas internas protegidas.
- Las rutas PHP de la primera versión redirigen a la nueva aplicación; su lógica anterior no se ejecuta.

## Respaldo y migración de esta entrega

Respaldo previo en `../tmp/retrofix-backup/original` y `../tmp/retrofix-backup/retrofix-before-v2.sql`. Contienen datos locales: no publicarlos ni subirlos a repositorios.

Se conservaron las tablas anteriores y se importaron 4 usuarios, 3 solicitudes, 2 cotizaciones y 2 citas. Los pagos liquidados se importan con un comprobante interno nuevo; facturas y pagos pendientes anteriores siguen en las tablas originales. Las fotografías anteriores permanecen en los archivos del respaldo, no se transforman automáticamente al nuevo formato privado. No borrar el respaldo hasta revisar los expedientes.

Para respaldar nuevamente, usar mysqldump --single-transaction sobre retrofix y copiar storage/uploads junto con config/local.php a un sitio privado. Para recuperar, importar el respaldo en una base diferente primero y apuntar una copia de la aplicación a ella; no sobreescribir la base actual sin revisión.

## Verificación realizada

201 comprobaciones HTTP automatizadas del recorrido integral, roles, propiedad, CSRF, transiciones, cita duplicada, pago duplicado, reseña única, cancelaciones, reembolsos, compra de usados, suscripciones y comprobantes. Pruebas adicionales: última unidad, restitución de stock, cambio de precio, producto desactivado, registro y bloqueo de archivos privados. Instalación repetida sin duplicación, sintaxis PHP y conteos de migración verificados. Revisión visual de portada y catálogo en escritorio y móvil.

Scripts reproducibles en `../tmp/retrofix-backup/test_flows.py` y `test_extra.py`, contra la base aislada retrofix_qa_20261007 y puerto 8081. Nunca apuntarlos a la base con datos del usuario. Los registros de QA no están en la base principal.

## Alcance académico

Los pagos, reembolsos, comprobantes y entregas digitales son simulados. No hay pasarela bancaria, activación real de Xbox/PlayStation, correo/SMS automático ni facturación fiscal. Las notificaciones son internas al consultar los expedientes. Precios y planes son ejemplos editables, no ofertas oficiales.

Diseño inspirado en el tratamiento editorial de Apple (espacios, jerarquía tipográfica y tarjetas), con textos, interfaz y dibujos CSS propios. Las portadas son composiciones editoriales de demostración, no carátulas oficiales. Tipografía de sistema sin descargas externas.

Antes de publicar en Internet se requiere endurecimiento adicional: HTTPS, credenciales seguras, permisos mínimos, limitación de acceso robusta fuera de la sesión, revisión de privacidad, copias programadas y pruebas de carga/concurrencia. El servidor PHP integrado no es para producción.

