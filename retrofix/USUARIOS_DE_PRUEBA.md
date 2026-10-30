# RetroFix — Usuarios de prueba

Acceso local: [Iniciar sesión](http://127.0.0.1:8080/index.php?p=login).

Estas son las cuentas de demostración definidas en el instalador del proyecto. Si se modificó una contraseña posteriormente, debe utilizarse la contraseña actual.

| Usuario | Correo | Rol del sistema |
| --- | --- | --- |
| Administración RetroFix | admin@retrofix.com | administrador |
| Alex Técnico | especialista@retrofix.com | especialista |
| María López | cliente1@gmail.com | cliente |
| Carlos Ruiz | cliente2@gmail.com | cliente |

Las contraseñas no se publican. En una instalación nueva, el instalador genera claves aleatorias y las muestra en consola una sola vez. En una instalación existente, utiliza la contraseña actual de cada cuenta.

## Funciones por rol

- **Administrador:** consulta solicitudes, prepara cotizaciones, administra reparaciones y asignaciones, agenda, inventario, pedidos, compra de videojuegos usados, planes, pagos, usuarios y reportes.
- **Especialista / técnico:** consulta las reparaciones asignadas y registra avances técnicos y cambios de estado permitidos.
- **Cliente:** solicita reparaciones, consulta y responde cotizaciones, revisa avances, compra videojuegos, solicita suscripciones y ofrece videojuegos usados al taller.

## Sobre el vendedor

No existe una cuenta ni un rol independiente llamado `vendedor`. La gestión de ventas del negocio corresponde al **administrador**. Un cliente puede ofrecer sus videojuegos usados al taller desde su cuenta de **cliente**.

## Uso de la demostración

1. Mantén encendidos MySQL de XAMPP y el servidor local de RetroFix.
2. Abre el enlace de inicio de sesión e introduce el correo de la tabla y la contraseña privada de esa cuenta.
3. Para probar otro rol, cierra la sesión actual desde **Mi cuenta** y accede con otra cuenta.

Estas credenciales son únicamente para pruebas locales. Los pagos y las entregas digitales son simulados; las suscripciones no activan servicios reales de Xbox o PlayStation.
