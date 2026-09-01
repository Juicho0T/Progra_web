from flask import Flask, render_template, request


# Importamos las funciones que trabajan con MySQL
from CMySql import f_agregar_registro, f_listar_clientes


# =============================================================
# CREAR APLICACIÓN FLASK
# =============================================================


app = Flask(__name__)


# =============================================================
# RUTA PRINCIPAL
# =============================================================


@app.route("/")
def inicio():
    return render_template("index.html")


# =============================================================
# RECIBIR DATOS DEL FORMULARIO Y GUARDAR EN MYSQL
# =============================================================


@app.route("/mostrar_cliente", methods=["POST"])
def mostrar_cliente():
    # ---------------------------------------------------------
    # DATOS PERSONALES
    # ---------------------------------------------------------

    nombre = request.form["nombre"]
    apellido_paterno = request.form["apellido_paterno"]
    apellido_materno = request.form["apellido_materno"]
    fecha_nacimiento = request.form["fecha_nacimiento"]
    genero = request.form.get("genero", "")

    # ---------------------------------------------------------
    # DATOS DE CONTACTO
    # ---------------------------------------------------------

    correo = request.form["correo"]
    telefono = request.form["telefono"]

    # ---------------------------------------------------------
    # UBICACIÓN
    # ---------------------------------------------------------

    estado = request.form["estado"]
    ciudad = request.form["ciudad"]
    codigo_postal = request.form["codigo_postal"]

    # ---------------------------------------------------------
    # INFORMACIÓN COMERCIAL
    # ---------------------------------------------------------

    tipo_cliente = request.form["tipo_cliente"]

    # Los checkbox llegan como una lista
    intereses = request.form.getlist("intereses")
    # Convertimos la lista en texto para almacenarla en MySQL
    intereses_texto = ", ".join(intereses)
    limite_credito = request.form["limite_credito"]

    observaciones = request.form["observaciones"]

    # =========================================================
    # GUARDAR EN MYSQL
    # =========================================================

    f_agregar_registro(
        nombre,
        apellido_paterno,
        apellido_materno,
        fecha_nacimiento,
        genero,
        correo,
        telefono,
        estado,
        ciudad,
        codigo_postal,
        tipo_cliente,
        intereses_texto,
        limite_credito,
        observaciones
    )

    # =========================================================
    # MOSTRAR LOS DATOS REGISTRADOS
    # =========================================================

    return render_template(
        "mostrar_cliente.html",
        nombre=nombre,
        apellido_paterno=apellido_paterno,
        apellido_materno=apellido_materno,
        fecha_nacimiento=fecha_nacimiento,
        genero=genero,
        correo=correo,
        telefono=telefono,
        estado=estado,
        ciudad=ciudad,
        codigo_postal=codigo_postal,
        tipo_cliente=tipo_cliente,
        intereses=intereses,
        limite_credito=limite_credito,
        observaciones=observaciones
    )


# =============================================================
# LISTAR CLIENTES
# =============================================================


@app.route("/clientes")
def listar_clientes():
    # Obtener los clientes desde MySQL
    clientes = f_listar_clientes()
    # Enviar los registros a Jinja
    return render_template(
        "listar_clientes.html",
        clientes=clientes
    )


# =============================================================
# EJECUTAR APLICACIÓN
# =============================================================


if __name__ == "__main__":
    app.run(debug=True)
