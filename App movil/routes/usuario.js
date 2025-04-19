const express = require("express");
const router = express.Router();
const db = require("../config/db");

// Obtener todos los usuarios
router.get("/", async (req, res) => {
    try {
        const usuarios = await db.query("SELECT * FROM usuario");
        res.json({ success: true, data: usuarios });
    } catch (error) {
        console.error("Error al obtener usuarios:", error);
        res.status(500).json({
            success: false,
            message: "Error al obtener usuarios",
            error: error.message,
        });
    }
});

// Obtener un usuario por documento

router.get("/:documento", async (req, res) => {
    try {
        const { documento } = req.params;

        // Consulta con desencriptación en MySQL
        const resultado = await db.query("SELECT *, AES_DECRYPT(clave, 'SENA') AS pass FROM usuario WHERE documento = ?", [documento]);

        if (resultado.length === 0) {
            return res.status(404).json({ success: false, message: "Usuario no encontrado" });
        }

        const usuario = resultado[0];

        // Convertir el buffer a string si pass existe
        if (usuario.pass && Buffer.isBuffer(usuario.pass)) {
            usuario.pass = usuario.pass.toString("utf8");
        }

        res.json({ success: true, data: usuario });
    } catch (error) {
        console.error("Error al obtener usuario:", error);
        res.status(500).json({
            success: false,
            message: "Error al obtener usuario",
            error: error.message,
        });
    }
});

module.exports = router;

// Crear un nuevo usuario
router.post("/", async (req, res) => {
    try {
        const {
            documento, id_rol, email, clave, tipo_doc, nombre1, nombre2,
            apellido1, apellido2, telefono, direccion, foto, grado,
            reset_token, token_expiration, token_sesion,
            activo, token_activacion
        } = req.body;

        if (!documento || !email || !clave || !tipo_doc || !nombre1 || !apellido1) {
            return res.status(400).json({
                success: false,
                message: "Faltan datos obligatorios",
            });
        }

        const result = await db.query(
            `INSERT INTO usuario 
            (documento, id_rol, email, clave, tipo_doc, nombre1, nombre2, apellido1, apellido2, telefono, direccion, foto, grado, reset_token, token_expiration, token_sesion, activo, token_activacion)
            VALUES (?, ?, ?, AES_ENCRYPT(?, 'SENA'), ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)`,
            [
                documento, id_rol, email, clave, tipo_doc, nombre1, nombre2,
                apellido1, apellido2, telefono, direccion, foto, grado,
                reset_token, token_expiration, token_sesion,
                activo, token_activacion,
            ]
        );

        res.status(201).json({
            success: true,
            message: "Usuario creado correctamente",
            data: { documento },
        });
    } catch (error) {
        console.error("Error al crear usuario:", error);
        res.status(500).json({
            success: false,
            message: "Error al crear usuario",
            error: error.message,
        });
    }
});

// Actualizar un usuario
router.put("/:documento", async (req, res) => {
    try {
        const { documento } = req.params;
        const campos = req.body;

        const keys = Object.keys(campos);
        const values = [];

        if (keys.length === 0) {
            return res.status(400).json({
                success: false,
                message: "No se proporcionaron campos para actualizar",
            });
        }

        const updateStr = keys.map(key => {
            if (key === "clave") {
                values.push(campos[key]);
                return `clave = AES_ENCRYPT(?, 'SENA')`;
            } else {
                values.push(campos[key]);
                return `${key} = ?`;
            }
        }).join(", ");

        const result = await db.query(
            `UPDATE usuario SET ${updateStr} WHERE documento = ?`,
            [...values, documento]
        );

        if (result.affectedRows === 0) {
            return res.status(404).json({
                success: false,
                message: "Usuario no encontrado",
            });
        }

        res.json({
            success: true,
            message: "Usuario actualizado correctamente",
            data: { documento },
        });
    } catch (error) {
        console.error("Error al actualizar usuario:", error);
        res.status(500).json({
            success: false,
            message: "Error al actualizar usuario",
            error: error.message,
        });
    }
});

module.exports = router;

// Eliminar un usuario
router.delete("/:documento", async (req, res) => {
    try {
        const { documento } = req.params;

        const result = await db.query("DELETE FROM usuario WHERE documento = ?", [documento]);

        if (result.affectedRows === 0) {
            return res.status(404).json({
                success: false,
                message: "Usuario no encontrado",
            });
        }

        res.json({
            success: true,
            message: "Usuario eliminado correctamente",
        });
    } catch (error) {
        console.error("Error al eliminar usuario:", error);
        res.status(500).json({
            success: false,
            message: "Error al eliminar usuario",
            error: error.message,
        });
    }
});

module.exports = router;
