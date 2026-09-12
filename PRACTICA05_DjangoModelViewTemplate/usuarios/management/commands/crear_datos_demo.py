import os

from django.contrib.auth import get_user_model
from django.contrib.auth.hashers import make_password
from django.core.management.base import BaseCommand

from usuarios.models import PerfilUsuario, Rol


ROLES = (
    ("Administrador", "Administra usuarios y roles"),
    ("Especialista", "Personal especializado"),
    ("Vendedor", "Personal de ventas"),
    ("Analista", "Personal de análisis"),
    ("Dueño", "Propietario del negocio"),
    ("Cliente", "Cliente registrado"),
)

USUARIOS_DEMO = (
    ("rpizarroanalista", "Analista"),
    ("rpizarroduenio", "Dueño"),
    ("rpizarrovendedor", "Vendedor"),
    ("rpizarroespecialista", "Especialista"),
    ("rpizarrocliente", "Cliente"),
)


class Command(BaseCommand):
    help = "Crea roles, usuarios demo y perfiles para la práctica."

    def handle(self, *args, **options):
        User = get_user_model()
        roles = {}

        for nombre, descripcion in ROLES:
            rol, _ = Rol.objects.update_or_create(
                nombre=nombre,
                defaults={"descripcion": descripcion, "activo": True},
            )
            roles[nombre] = rol

        demo_password = os.environ.get("DJANGO_DEMO_PASSWORD")
        if demo_password:
            for username, nombre_rol in USUARIOS_DEMO:
                usuario, creado = User.objects.get_or_create(
                    username=username,
                    defaults={"is_active": True},
                )
                if creado:
                    usuario.password = make_password(demo_password)
                    usuario.save(update_fields=["password"])
                else:
                    # Los usuarios demo usan la contraseña privada configurada
                    # en Render para que puedan probarse sin utilizar la Shell.
                    usuario.password = make_password(demo_password)
                    usuario.is_active = True
                    usuario.save(update_fields=["password", "is_active"])

                perfil, _ = PerfilUsuario.objects.get_or_create(
                    usuario=usuario,
                    defaults={"rol": roles[nombre_rol]},
                )
                if perfil.rol_id != roles[nombre_rol].pk:
                    perfil.rol = roles[nombre_rol]
                    perfil.save(update_fields=["rol"])

            self.stdout.write(
                self.style.SUCCESS("Usuarios demo y perfiles creados o verificados.")
            )
        else:
            self.stdout.write(
                self.style.WARNING(
                    "No se creó usuarios demo: falta DJANGO_DEMO_PASSWORD."
                )
            )

        admin_username = os.environ.get("DJANGO_ADMIN_USERNAME")
        admin_password = os.environ.get("DJANGO_ADMIN_PASSWORD")
        if admin_username and admin_password:
            admin, creado = User.objects.get_or_create(
                username=admin_username,
                defaults={
                    "is_active": True,
                    "is_staff": True,
                    "is_superuser": True,
                },
            )
            if creado:
                admin.password = make_password(admin_password)
                admin.save(update_fields=["password"])
            elif not (admin.is_staff and admin.is_superuser):
                admin.is_staff = True
                admin.is_superuser = True
                admin.save(update_fields=["is_staff", "is_superuser"])

            self.stdout.write(
                self.style.SUCCESS(f"Superusuario verificado: {admin_username}")
            )
