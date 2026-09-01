# PRÁCTICA 03 - Flask CSS MySQL

Aplicación Web con Flask, CSS y MySQL para capturar, guardar y listar
clientes.

## Estructura

```text
practica03_FLASKCSS_BD_MySQL/
├── app.py
├── CMySql.py
├── requirements.txt
├── .gitignore
├── README.md
├── templates/
│   ├── index.html
│   ├── listar_clientes.html
│   └── mostrar_cliente.html
└── static/
    └── css/
        └── estilos.css
```

El archivo `.env.example` muestra las variables de conexión necesarias. El
archivo `.env` no debe subirse a GitHub.

## Base de datos MySQL

En MySQL Workbench o en el cliente MySQL local ejecuta:

```sql
CREATE DATABASE comercio;
USE comercio;

CREATE TABLE clientes (
    id_cliente INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50) NOT NULL,
    apellido_paterno VARCHAR(50) NOT NULL,
    apellido_materno VARCHAR(50),
    fecha_nacimiento DATE,
    genero VARCHAR(15),
    correo VARCHAR(100) NOT NULL,
    telefono VARCHAR(20),
    estado VARCHAR(50),
    ciudad VARCHAR(50),
    codigo_postal VARCHAR(10),
    tipo_cliente VARCHAR(20),
    intereses VARCHAR(200),
    limite_credito DECIMAL(10,2),
    observaciones VARCHAR(250)
);
```

La conexión usa por defecto `localhost`, usuario `root`, contraseña `root` y la
base `comercio`, tal como se solicita en el documento. En línea se reemplazan
esos valores mediante variables de entorno.

## Ejecución

```powershell
python -m venv .venv
.\.venv\Scripts\Activate.ps1
python -m pip install -r requirements.txt
python app.py
```

Si tu contraseña de MySQL no es `root`, configura la variable antes de iniciar
la aplicación:

```powershell
$env:MYSQL_PASSWORD="tu_contrasena_mysql"
python app.py
```

Abre en el navegador:

```text
http://127.0.0.1:5000
```

La lista de clientes se consulta en:

```text
http://127.0.0.1:5000/clientes
```

## Rutas

- `/`: muestra el formulario de registro.
- `/mostrar_cliente`: recibe el formulario por `POST`, guarda el cliente en MySQL y muestra la información registrada.
- `/clientes`: consulta y muestra los clientes almacenados en MySQL.

## Publicar en GitHub y Render

El archivo `render.yaml` ya configura Render para esta práctica. En Render
puedes crear un Blueprint desde este repositorio y completar las variables
`MYSQL_HOST`, `MYSQL_USER` y `MYSQL_PASSWORD`. También puedes crear un Web
Service manualmente con estos valores:

- Root Directory: `practica03_FLASKCSS_BD_MySQL`
- Build Command: `pip install -r requirements.txt`
- Start Command: `gunicorn app:app`

Render no puede usar el MySQL de `localhost` de tu computadora. Para que la
aplicación funcione en línea, crea la base `comercio` y la tabla `clientes` en
un servidor MySQL accesible desde Internet y coloca sus datos en las variables
de Render. No publiques la contraseña en el repositorio.

Una opción compatible para una práctica es TiDB Cloud Starter. Al crear la
instancia, usa su endpoint público y coloca en Render el host, usuario,
contraseña y nombre de base que muestra TiDB. En TiDB Cloud Starter el puerto
suele ser `4000` y debes activar:

```text
MYSQL_SSL=true
MYSQL_SSL_CA=/etc/ssl/certs/ca-certificates.crt
```

Ejecuta el SQL de este README en la base remota antes de abrir la aplicación.
