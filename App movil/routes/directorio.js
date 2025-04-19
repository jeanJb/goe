const express = require("express");
const router = express.Router();
const db = require("../config/db");

// Obtener todos los registros del directorio
router.get("/", async (req, res) => {
    try {
        const resultados = await db.query("SELECT * FROM directorio");
        res.json({ success: true, data: resultados });
    } catch (error) {
        res.status(500).json({ success: false, message: "Error al obtener el directorio", error: error.message });
    }
});

// Obtener por id_detalle
router.get("/:id_detalle", async (req, res) => {
    try {
        const { id_detalle } = req.params;
        const resultado = await db.query("SELECT * FROM directorio WHERE id_detalle = ?", [id_detalle]);

        if (resultado.length === 0) {
            return res.status(404).json({ success: false, message: "Registro no encontrado" });
        }

        res.json({ success: true, data: resultado[0] });
    } catch (error) {
        res.status(500).json({ success: false, message: "Error al obtener el registro", error: error.message });
    }
});

// Crear nuevo registro
router.post("/", async (req, res) => {
    try {
        const {
            documento, rh_estudiante, eps, fecha_nac,
            enfermedades, nom_acu, telefono_acu, doc_acu, email_acu
        } = req.body;

        const result = await db.query(
            `INSERT INTO directorio 
            (documento, rh_estudiante, eps, fecha_nac, enfermedades, nom_acu, telefono_acu, doc_acu, email_acu)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)`,
            [documento, rh_estudiante, eps, fecha_nac, enfermedades, nom_acu, telefono_acu, doc_acu, email_acu]
        );

        res.status(201).json({ success: true, message: "Registro creado", data: { id_detalle: result.insertId } });
    } catch (error) {
        res.status(500).json({ success: false, message: "Error al crear registro", error: error.message });
    }
});

// Actualizar registro
router.put("/:id_detalle", async (req, res) => {
    try {
        const { id_detalle } = req.params;
        const campos = req.body;

        const keys = Object.keys(campos);
        const values = Object.values(campos);
        const updateStr = keys.map(k => `${k} = ?`).join(", ");

        const result = await db.query(
            `UPDATE directorio SET ${updateStr} WHERE id_detalle = ?`,
            [...values, id_detalle]
        );

        if (result.affectedRows === 0) {
            return res.status(404).json({ success: false, message: "Registro no encontrado" });
        }

        res.json({ success: true, message: "Registro actualizado", data: { id_detalle } });
    } catch (error) {
        res.status(500).json({ success: false, message: "Error al actualizar", error: error.message });
    }
});

// Eliminar registro
router.delete("/:id_detalle", async (req, res) => {
    try {
        const { id_detalle } = req.params;
        const result = await db.query("DELETE FROM directorio WHERE id_detalle = ?", [id_detalle]);

        if (result.affectedRows === 0) {
            return res.status(404).json({ success: false, message: "Registro no encontrado" });
        }

        res.json({ success: true, message: "Registro eliminado correctamente" });
    } catch (error) {
        res.status(500).json({ success: false, message: "Error al eliminar", error: error.message });
    }
});

module.exports = router;
