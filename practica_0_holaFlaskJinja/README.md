# Practica 01 - Hola Mundo con Flask + Jinja

Aplicacion de la practica de Fundamentos de Desarrollo Web. El formulario
envia el nombre, los pasatiempos seleccionados y una descripcion a Flask
mediante `POST`; Jinja muestra los datos en `saludar.html`.

## Estructura

```text
practica_0_holaFlaskJinja/
├── app.py
├── requirements.txt
├── templates/
│   ├── index.html
│   └── saludar.html
└── static/
    └── css/
        └── estilos.css
```

## Ejecutar en Windows

```powershell
python -m venv .venv
.venv\Scripts\activate
pip install -r requirements.txt
python app.py
```

Abre `http://127.0.0.1:5000` en el navegador.

## Subir a GitHub y publicar la web

GitHub guarda el codigo, pero GitHub Pages no ejecuta aplicaciones Flask.
Para publicar esta aplicacion, sube el repositorio a GitHub y conectalo a un
servicio de tipo Web Service, por ejemplo Render.

Desde la carpeta `practica_0_holaFlaskJinja`, ejecuta:

```powershell
git init
git add .
git commit -m "Practica Flask y Jinja"
git branch -M main
git remote add origin https://github.com/TU_USUARIO/practica_0_holaFlaskJinja.git
git push -u origin main
```

En Render crea un **New → Web Service**, conecta tu cuenta de GitHub y elige
este repositorio. Usa estos valores:

- **Build Command:** `pip install -r requirements.txt`
- **Start Command:** `gunicorn app:app`
- **Root Directory:** déjalo vacío si el repositorio contiene directamente `app.py` y `requirements.txt`.

Cuando termine el despliegue, Render entregará una URL pública con dominio
`onrender.com`. Cada nuevo `git push` podrá desplegar automáticamente los
cambios.
