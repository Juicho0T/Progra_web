from django.contrib import admin

from .models import PerfilUsuario, Rol


admin.site.register(Rol)
admin.site.register(PerfilUsuario)
