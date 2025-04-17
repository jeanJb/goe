const express = require("express");
const router = express.Router();
const db = require("../config/db");

// Obtener todas las asistencias
router.get("/", async (req, res) => {

    try {
        const asistencias = await db.query("SELECT * FROM asistencia");
        res.json({ success: true, data: asistencias });
    } catch (error) {
        console.error("Error al obtener asistencias:", error);
        res.status(500).json({
            success: false,
            message: "Error al obtener asistencias",
            error: error.message,
        });
    }
});

// Obtener una asistencia por ID
router.get("/:idasistencia", async (req, res) => {
    try {
        const { idasistencia } = req.params;
        const asistencia = await db.query("SELECT * FROM asistencia WHERE idasistencia = ?", [idasistencia]);

        if (asistencia.length === 0) {
            return res.status(404).json({ success: false, message: "Asistencia no encontrada" });
        }

        res.json({ success: true, data: asistencia[0] });
    } catch (error) {
        console.error("Error al obtener asistencia:", error);
        res.status(500).json({
            success: false,
            message: "Error al obtener asistencia",
            error: error.message,
        });
    }
});

// Crear una nueva asistencia
router.post("/", async (req, res) => {
    try {
        const {
            profesor, documento, estado_asis,
            idMat, fecha_asistencia, justificacion_inasistencia, idlistado
        } = req.body;

        if (!profesor || !documento || !estado_asis || !idMat || !fecha_asistencia || !idlistado) {
            return res.status(400).json({
                success: false,
                message: "Faltan datos obligatorios",
            });
        }

        const result = await db.query(
            `INSERT INTO asistencia 
        (profesor, documento, estado_asis, idMat, fecha_asistencia, justificacion_inasistencia, idlistado)
        VALUES (?, ?, ?, ?, ?, ?, ?)`,
            [profesor, documento, estado_asis, idMat, fecha_asistencia, justificacion_inasistencia, idlistado]
        );

        res.status(201).json({
            success: true,
            message: "Asistencia creada correctamente",
            data: { idasistencia: result.insertId },
        });
    } catch (error) {
        console.error("Error al crear asistencia:", error);
        res.status(500).json({
            success: false,
            message: "Error al crear asistencia",
            error: error.message,
        });
    }
});

// Actualizar una asistencia
router.put("/:idasistencia", async (req, res) => {
    try {
        const { idasistencia } = req.params;
        const campos = req.body;

        const keys = Object.keys(campos);
        const values = Object.values(campos);

        if (keys.length === 0) {
            return res.status(400).json({
                success: false,
                message: "No se proporcionaron campos para actualizar",
            });
        }

        const updateStr = keys.map(key => `${key} = ?`).join(", ");
        const result = await db.query(
            `UPDATE asistencia SET ${updateStr} WHERE idasistencia = ?`,
            [...values, idasistencia]
        );

        if (result.affectedRows === 0) {
            return res.status(404).json({
                success: false,
                message: "Asistencia no encontrada",
            });
        }

        res.json({
            success: true,
            message: "Asistencia actualizada correctamente",
            data: { idasistencia },
        });
    } catch (error) {
        console.error("Error al actualizar asistencia:", error);
        res.status(500).json({
            success: false,
            message: "Error al actualizar asistencia",
            error: error.message,
        });
    }
});

// Eliminar una asistencia
router.delete("/:idasistencia", async (req, res) => {
    try {
        const { idasistencia } = req.params;

        const result = await db.query("DELETE FROM asistencia WHERE idasistencia = ?", [idasistencia]);

        if (result.affectedRows === 0) {
            return res.status(404).json({
                success: false,
                message: "Asistencia no encontrada",
            });
        }

        res.json({
            success: true,
            message: "Asistencia eliminada correctamente",
        });
    } catch (error) {
        console.error("Error al eliminar asistencia:", error);
        res.status(500).json({
            success: false,
            message: "Error al eliminar asistencia",
            error: error.message,
        });
    }
});

module.exports = router;
