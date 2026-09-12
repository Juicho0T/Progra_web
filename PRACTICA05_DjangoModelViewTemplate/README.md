# PRACTICA 05 - Django. Administración de usuarios

Aplicación WEB en Django para administrar usuarios y roles. La práctica usa
el modelo de usuarios nativo de Django, una tabla `Rol`, una tabla
`PerfilUsuario`, SQLite como base de datos y un inicio de sesión con mensaje de
bienvenida según el rol.

## Estructura

```text
PRACTICA05_DjangoModelViewTemplate/
├── manage.py
├── requirements.txt
├── .gitignore
├── .env.example
├── render.yaml
├── practica05_DjangoMVT/
│   ├── __init__.py
│   ├── asgi.py
│   ├── settings.py
│   ├── urls.py
│   └── wsgi.py
└── usuarios/
    ├── __init__.py
    ├── admin.py
    ├── apps.py
    ├── models.py
    ├── tests.py
    ├── urls.py
    ├── views.py
    ├── migrations/
    │   ├── __init__.py
    │   └── 0001_initial.py
    ├── static/usuarios/css/estilos.css
    └── templates/usuarios/
        ├── inicio.html
        └── login.html
```

## Instancia local

Desde PowerShell:

```powershell
cd "C:\Users\luisw\OneDrive\Desktop\pizarro\PRACTICA05_DjangoModelViewTemplate"
python -m venv .venv
.\.venv\Scripts\Activate.ps1
python -m pip install -r requirements.txt
python manage.py makemigrations
python manage.py migrate
python manage.py check
python manage.py runserver
```

Abre:

```text
http://127.0.0.1:8000/
```

El formulario de inicio de sesión está en:

```text
http://127.0.0.1:8000/login/
```

El administrador está en:

```text
http://127.0.0.1:8000/admin/
```

## Crear el administrador

Con el servidor detenido, ejecuta:

```powershell
python manage.py createsuperuser
```

Proporciona el nombre de usuario, correo y contraseña que quieras utilizar.

## Crear roles y perfiles

1. Entra a `/admin/` con el superusuario.
2. En **Roles**, agrega exactamente estos registros:

   - Administrador - Administra usuarios y roles
   - Especialista - Personal especializado
   - Vendedor - Personal de ventas
   - Analista - Personal de análisis
   - Dueño - Propietario del negocio
   - Cliente - Cliente registrado

3. En **Usuarios**, crea los usuarios de prueba de la guía:

   - `rpizarroanalista` con contraseña `probando`
   - `rpizarroduenio` con contraseña `probando`
   - `rpizarrovendedor` con contraseña `probando`
   - `rpizarroespecialista` con contraseña `probando`
   - `rpizarrocliente` con contraseña `probando`

4. En **Perfil usuarios**, relaciona cada usuario con su rol.

Al entrar a `/login/`, la aplicación autentica el usuario, muestra una alerta
JavaScript y enseña el rol asignado.

## Instancia en la nube con Render

El archivo `render.yaml` deja configurado el servicio. En Render selecciona
**New > Blueprint** y el repositorio de GitHub. Si lo haces como Web Service
manual, usa:

- Root Directory: `PRACTICA05_DjangoModelViewTemplate`
- Build Command: `pip install -r requirements.txt && python manage.py collectstatic --no-input && python manage.py migrate`
- Start Command: `gunicorn practica05_DjangoMVT.wsgi:application`
- Plan: Free

Agrega estas variables en Render:

```text
DJANGO_DEBUG=False
DJANGO_SECRET_KEY=una-clave-secreta
DJANGO_ALLOWED_HOSTS=tu-servicio.onrender.com
DJANGO_CSRF_TRUSTED_ORIGINS=https://tu-servicio.onrender.com
```

Después de desplegar, crea un superusuario mediante la Shell de Render:

```text
python manage.py createsuperuser
```

SQLite en el plan gratuito es adecuada para la demostración de esta práctica,
pero sus datos pueden perderse si Render reemplaza la instancia. Para datos
permanentes se debe migrar posteriormente a una base administrada.
