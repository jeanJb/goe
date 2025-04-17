const express = require("express");
const router = express.Router();
const db = require("../config/db");

// Obtener todos los observadores
router.get("/", async (req, res) => {
    try {
        const observadores = await db.query("SELECT * FROM observador");
        res.json({ success: true, data: observadores });
    } catch (error) {
        console.error("Error al obtener observadores:", error);
        res.status(500).json({ success: false, message: "Error al obtener observadores", error: error.message });
    }
});

// Obtener observador por ID
router.get("/:idobservador", async (req, res) => {
    try {
        const { idobservador } = req.params;
        const result = await db.query("SELECT * FROM observador WHERE idobservador = ?", [idobservador]);

        if (result.length === 0) {
            return res.status(404).json({ success: false, message: "Observador no encontrado" });
        }

        res.json({ success: true, data: result[0] });
    } catch (error) {
        console.error("Error al obtener observador:", error);
        res.status(500).json({ success: false, message: "Error al obtener observador", error: error.message });
    }
});

// Crear nuevo observador
router.post("/", async (req, res) => {
    try {
        const { documento, fecha, descripcion_falta, compromiso, firma, seguimiento, falta, trimestre } = req.body;

        const result = await db.query(
            `INSERT INTO observador (documento, fecha, descripcion_falta, compromiso, firma, seguimiento, falta, trimestre)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)`,
            [documento, fecha, descripcion_falta, compromiso, firma, seguimiento, falta, trimestre]
        );

        res.status(201).json({ success: true, message: "Observador creado", data: { idobservador: result.insertId } });
    } catch (error) {
        console.error("Error al crear observador:", error);
        res.status(500).json({ success: false, message: "Error al crear observador", error: error.message });
    }
});

// Actualizar observador
router.put("/:idobservador", async (req, res) => {
    try {
        const { idobservador } = req.params;
        const campos = req.body;

        const keys = Object.keys(campos);
        const values = Object.values(campos);

        const updateStr = keys.map(k => `${k} = ?`).join(", ");
        const result = await db.query(
            `UPDATE observador SET ${updateStr} WHERE idobservador = ?`,
            [...values, idobservador]
        );

        if (result.affectedRows === 0) {
            return res.status(404).json({ success: false, message: "Observador no encontrado" });
        }

        res.json({ success: true, message: "Observador actualizado", data: { idobservador } });
    } catch (error) {
        console.error("Error al actualizar observador:", error);
        res.status(500).json({ success: false, message: "Error al actualizar observador", error: error.message });
    }
});

// Eliminar observador
router.delete("/:idobservador", async (req, res) => {
    try {
        const { idobservador } = req.params;
        const result = await db.query("DELETE FROM observador WHERE idobservador = ?", [idobservador]);

        if (result.affectedRows === 0) {
            return res.status(404).json({ success: false, message: "Observador no encontrado" });
        }

        res.json({ success: true, message: "Observador eliminado correctamente" });
    } catch (error) {
        console.error("Error al eliminar observador:", error);
        res.status(500).json({ success: false, message: "Error al eliminar observador", error: error.message });
    }
});

module.exports = router;
