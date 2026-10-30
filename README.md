<div align="center">

# RetroFix

### Todas las generaciones. La misma pasión por jugar.

**[Abrir aplicación](http://retrofix.site.je/index.php)** · **[Iniciar sesión](http://retrofix.site.je/index.php?p=login)** · **[Manual técnico](retrofix/README.md)** · **[Guía de operación](docs/OPERACION.md)**

PHP 8 · MySQL / MariaDB · HTML · CSS · JavaScript

![Portada de RetroFix](docs/images/retrofix-inicio.jpg)

</div>

Proyecto académico de Desarrollo Web. RetroFix permite gestionar reparaciones de consolas de todas las generaciones, compraventa de videojuegos y solicitudes de suscripciones Xbox y PlayStation.

## Enlaces de la entrega

- [Código y documentación de RetroFix](retrofix/)
- [Manual de instalación, operación y características](retrofix/README.md)
- [Cuentas de demostración y roles](retrofix/USUARIOS_DE_PRUEBA.md)
- **Aplicación pública:** [Abrir RetroFix](http://retrofix.site.je/index.php), alojada en InfinityFree con PHP y MySQL.
- **Acceso:** [Iniciar sesión](http://retrofix.site.je/index.php?p=login). Las claves de evaluación se entregan en privado; no se publican en este repositorio.

## Características

- Portada, servicios, catálogo con filtros y detalle de videojuegos.
- Registro, inicio de sesión y perfiles con roles de cliente, especialista y administrador.
- Solicitudes de reparación, cotizaciones versionadas, autorización, citas, asignación y bitácora técnica.
- Bolsa, pedidos, inventario y compra de juegos usados a clientes.
- Planes de suscripción Xbox y PlayStation con entrega de demostración.
- Pagos simulados, comprobantes internos, reportes y registro de actividad.
- Interfaz adaptable con fondo negro, superficies grises y acciones azules.

## Organización del repositorio

```text
Progra_web/
├── README.md   # Presentación e índice
├── docs/       # Guía de operación e imágenes
└── retrofix/   # Aplicación final PHP + MySQL
```

### Prácticas anteriores

Las prácticas Flask y Django se conservan en la [rama de archivo](https://github.com/Juicho0T/Progra_web/tree/codex/archivo-practicas). La rama principal contiene únicamente RetroFix y su documentación.

## Tecnología e instalación

PHP 8, PDO, MySQL/MariaDB, HTML, CSS y JavaScript. No requiere frameworks.

1. Descarga o clona este repositorio.
2. Inicia MySQL/MariaDB y configura la base de datos siguiendo el [manual](retrofix/README.md#instalación-nueva).
3. Ejecuta el instalador `php app/install.php` desde la carpeta `retrofix`.
4. En Windows con XAMPP, ejecuta `retrofix/Iniciar-RetroFix.cmd` y deja la ventana abierta.
5. Abre `http://127.0.0.1:8080/`.

## Operación por rol

| Rol | Función |
| --- | --- |
| Cliente | Solicitar reparaciones, revisar cotizaciones, consultar avances, comprar juegos y ofrecer usados. |
| Especialista | Consultar reparaciones asignadas y registrar diagnóstico, trabajo, piezas y avances. |
| Administrador | Gestionar solicitudes, presupuestos, agenda, asignaciones, ventas, inventario, planes, pagos y usuarios. |

Las funciones de vendedor del negocio corresponden al administrador.

## Alcance de la demostración

Los pagos, reembolsos y entregas digitales son simulados. No se procesan cobros bancarios ni se activan suscripciones reales. Los comprobantes no son fiscales.

El código se conserva en GitHub y la aplicación se aloja en InfinityFree con su base de datos MySQL. GitHub Pages no ejecuta PHP ni MySQL. Las configuraciones de despliegue de prácticas anteriores están en la rama de archivo.

La carpeta `retrofix` excluye del control de versiones las sesiones, los archivos subidos, respaldos y la configuración local. Las contraseñas no se publican: una instalación nueva genera claves aleatorias y las muestra únicamente en su consola de instalación.

## Equipo

Ana Alvarado, Luis Becerra y Kevin Castorena. Docente: Ruben Pizarro. Grupo: 8Y.

