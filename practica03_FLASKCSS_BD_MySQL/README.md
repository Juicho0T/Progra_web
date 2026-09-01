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

La conexión de la práctica usa `localhost`, usuario `root`, contraseña
`root` y la base `comercio`, tal como se solicita en el documento.

## Ejecución

```powershell
python -m venv .venv
.\.venv\Scripts\Activate.ps1
python -m pip install -r requirements.txt
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
