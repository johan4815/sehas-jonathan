<?php

class Bd
{
    private static ?PDO $pdo = null;

    static function pdo(): PDO
    {
        if (self::$pdo === null) {
            self::$pdo = new PDO(
                // cadena de conexión
                "sqlite:srvbd.db",
                // usuario
                null,
                // contraseña
                null,
                // Opciones: pdos no persistentes y lanza excepciones.
                [PDO::ATTR_PERSISTENT => false, PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
            );

            // Tabla PRODUCTO
            self::$pdo->exec(
                "CREATE TABLE IF NOT EXISTS PRODUCTO (
                    PRO_ID INTEGER PRIMARY KEY,
                    PRO_NOMBRE TEXT NOT NULL, 
                    PRO_PRECIO REAL NOT NULL,
                    PRO_DESCRIPCION TEXT NOT NULL,
                    CONSTRAINT PRO_NOMBRE_UK UNIQUE(PRO_NOMBRE),
                    CONSTRAINT PRO_PRECIO_CK CHECK(PRO_PRECIO > 0)
                )"
            );

            // Tabla CLIENTE
            self::$pdo->exec(
                "CREATE TABLE IF NOT EXISTS CLIENTE (
                    CLI_ID INTEGER PRIMARY KEY,
                    CLI_NOMBRE TEXT NOT NULL,
                    CLI_EMAIL TEXT NOT NULL,
                    CLI_PASSWORD TEXT NOT NULL,
                    CLI_DIRECCION TEXT,
                    CLI_TELEFONO TEXT,
                    CLI_ESTATUS TEXT NOT NULL,
                    CONSTRAINT CLIENTE_EMAIL_UK UNIQUE(CLI_EMAIL)
                )"
            );

            // Tabla MASCOTA
            self::$pdo->exec(
                "CREATE TABLE IF NOT EXISTS MASCOTA (
                    MAS_ID INTEGER PRIMARY KEY,
                    MAS_CLIENTE INTEGER NOT NULL,
                    MAS_NOMBRE TEXT NOT NULL,
                    MAS_ESPECIE TEXT NOT NULL,
                    MAS_RAZA TEXT,
                    MAS_FECHA_NAC DATE,
                    MAS_ESTATUS TEXT NOT NULL,
                    CONSTRAINT MASCOTA_CLIENTE_FK FOREIGN KEY(MAS_CLIENTE) REFERENCES CLIENTE(CLI_ID)
                )"
            );

            // Tabla VETERINARIO
            self::$pdo->exec(
                "CREATE TABLE IF NOT EXISTS VETERINARIO (
                    VET_ID INTEGER PRIMARY KEY,
                    VET_NOMBRE TEXT NOT NULL,
                    VET_EMAIL TEXT NOT NULL,
                    VET_PASSWORD TEXT NOT NULL,
                    VET_TELEFONO TEXT,
                    VET_CEDULA TEXT,
                    VET_ESPECIALIDAD TEXT,
                    VET_HORARIO TEXT,
                    VET_ESTATUS TEXT NOT NULL,
                    CONSTRAINT VETERINARIO_EMAIL_UK UNIQUE(VET_EMAIL)
                )"
            );

            // Tabla PEDIDO
            self::$pdo->exec(
                "CREATE TABLE IF NOT EXISTS PEDIDO (
                    PED_ID INTEGER PRIMARY KEY,
                    PED_CLIENTE INTEGER NOT NULL,
                    PED_PRODUCTO INTEGER NOT NULL,
                    PED_PRECIO REAL NOT NULL,
                    PED_FECHA DATE NOT NULL,
                    PED_ESTATUS TEXT NOT NULL,
                    CONSTRAINT PEDIDO_CLIENTE_FK FOREIGN KEY(PED_CLIENTE) REFERENCES CLIENTE(CLI_ID),
                    CONSTRAINT PEDIDO_PRODUCTO_FK FOREIGN KEY(PED_PRODUCTO) REFERENCES PRODUCTO(PRO_ID)
                )"
            );

            // Tabla CITA
            self::$pdo->exec(
                "CREATE TABLE IF NOT EXISTS CITA (
                    CIT_ID INTEGER PRIMARY KEY,
                    CIT_MASCOTA INTEGER NOT NULL,
                    CIT_TIPO TEXT NOT NULL,
                    CIT_HORARIO TEXT,
                    CIT_FECHA DATE NOT NULL,
                    CIT_VET INTEGER NOT NULL,
                    CIT_DESCRIPCION TEXT,
                    CIT_ESTATUS TEXT NOT NULL,
                    CONSTRAINT CITA_MASCOTA_FK FOREIGN KEY(CIT_MASCOTA) REFERENCES MASCOTA(MAS_ID),
                    CONSTRAINT CITA_VET_FK FOREIGN KEY(CIT_VET) REFERENCES VETERINARIO(VET_ID)
                )"
            );
        }

        return self::$pdo;
    }
}
