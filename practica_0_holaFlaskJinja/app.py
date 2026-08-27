"""Practica 01 - Hola Mundo con Flask + Jinja.

Aplicacion web sencilla que recibe datos de un formulario HTML y los
renderiza dinamicamente en una segunda plantilla Jinja.
"""

from flask import Flask, render_template, request


app = Flask(__name__)


@app.route("/")
def inicio():
    """Muestra el formulario principal."""
    return render_template("index.html")


@app.route("/saludar", methods=["POST"])
def saludar():
    """Recibe el formulario y envia sus datos a saludar.html."""
    nombre = request.form.get("nombre", "").strip()
    pasatiempos = request.form.getlist("pasatiempos")
    me_gusta = request.form.get("me_gusta", "").strip()

    return render_template(
        "saludar.html",
        nombre=nombre,
        pasatiempos=pasatiempos,
        me_gusta=me_gusta,
    )


if __name__ == "__main__":
    app.run(debug=True)
