from django.test import TestCase


class UsuariosSmokeTest(TestCase):
    def test_inicio_redirige_a_login_si_no_hay_sesion(self):
        respuesta = self.client.get("/")
        self.assertEqual(respuesta.status_code, 200)
        self.assertContains(respuesta, "Control de Usuarios")
