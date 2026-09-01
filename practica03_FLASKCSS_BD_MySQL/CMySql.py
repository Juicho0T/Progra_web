import os

import mysql.connector


# =============================================================
# CONEXIÓN A MYSQL
# =============================================================


def f_conectar():
    configuracion = {
        "host": os.getenv("MYSQL_HOST", "localhost"),
        "port": int(os.getenv("MYSQL_PORT", "3306")),
        "user": os.getenv("MYSQL_USER", "root"),
        "password": os.getenv("MYSQL_PASSWORD", "root"),
        "database": os.getenv("MYSQL_DATABASE", "comercio")
    }

    # Algunos proveedores de MySQL exigen TLS para conexiones públicas.
    # Se activa desde Render con MYSQL_SSL=true.
    ssl_activo = os.getenv("MYSQL_SSL", "false").lower() in (
        "1", "true", "yes"
    )

    if ssl_activo:
        configuracion["ssl_verify_cert"] = True
        configuracion["ssl_verify_identity"] = True
        ssl_ca = os.getenv("MYSQL_SSL_CA", "")
        if ssl_ca and os.path.isfile(ssl_ca):
            configuracion["ssl_ca"] = ssl_ca

    conexion = mysql.connector.connect(
        **configuracion
    )

    return conexion


# =============================================================
# AGREGAR CLIENTE
# =============================================================


def f_agregar_registro(
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
    intereses,
    limite_credito,
    observaciones
):
    conexion = f_conectar()
    cursor = conexion.cursor()

    sql = """
        INSERT INTO clientes
        (
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
            intereses,
            limite_credito,
            observaciones
        )
        VALUES
        (
            %s, %s, %s, %s, %s, %s,
            %s, %s, %s, %s, %s, %s, %s, %s
        )
    """

    valores = (
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
        intereses,
        limite_credito,
        observaciones
    )

    cursor.execute(sql, valores)
    conexion.commit()
    cursor.close()
    conexion.close()


# =============================================================
# LISTAR CLIENTES
# =============================================================


def f_listar_clientes():
    conexion = f_conectar()

    cursor = conexion.cursor()

    sql = """
        SELECT
            id_cliente,
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
            intereses,
            limite_credito,
            observaciones
        FROM clientes
        ORDER BY id_cliente
    """

    cursor.execute(sql)
    clientes = cursor.fetchall()

    cursor.close()
    conexion.close()

    return clientes
