<?php
require 'conexion.php';
header('Content-Type: application/json');

// Validar entrada
$grado = filter_input(INPUT_POST, 'grado', FILTER_VALIDATE_INT);
//$grado = $_GET['grado'];
if (!$grado) {
    echo json_encode([]);
    exit();
}

try {
    $cnn = Conexion::getConexion();
    $stmt = $cnn->prepare("
        SELECT documento, nombre1, nombre2, apellido1, apellido2 
        FROM usuario 
        WHERE id_rol = 101 AND grado = :grado
        ORDER BY apellido1, apellido2, nombre1, nombre2
    ");
    $stmt->bindParam(':grado', $grado, PDO::PARAM_INT);
    $stmt->execute();
    
    $estudiantes = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($estudiantes);
} catch (PDOException $e) {
    error_log("Error cargando estudiantes: " . $e->getMessage());
    echo json_encode([]);
}