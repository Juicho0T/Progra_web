# Práctica 02 - Flask + Jinja + CSS + SQLite

Aplicación Web que captura los datos de un alumno mediante un formulario
HTML, los almacena en una base de datos SQLite y permite consultar los
registros desde una plantilla Jinja.

## Estructura del proyecto

```text
practica_02_FlaskJinjaCSS_BD/
├── app.py
├── README.md
├── requirements.txt
├── .gitignore
├── database/
│   └── practica.db
├── templates/
│   ├── index.html
│   ├── saludar.html
│   └── listar_alumnos.html
└── static/
    └── css/
        └── estilos.css
```

## Ejecución local

```powershell
python -m venv .venv
.venv\Scripts\activate
pip install -r requirements.txt
python app.py
```

Después abre:

```text
http://127.0.0.1:5000
```

Para consultar los alumnos registrados abre:

```text
http://127.0.0.1:5000/alumnos
```

## Rutas

- `/`: muestra el formulario.
- `/saludar`: guarda y confirma el registro mediante `POST`.
- `/alumnos`: consulta y muestra los registros almacenados en SQLite.

La tabla `alumnos` contiene los campos `id`, `nombre`, `pasatiempos` y
`me_gusta`. La base de datos se crea automáticamente al iniciar la
aplicación.
