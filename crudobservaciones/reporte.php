<?php
require 'utilidades/conexion.php';
require 'reportes/fpdf.php';
$sql= "SELECT nombre, apellido, direccion, clave FROM usuarios";
$resultado = $con->query($sql);
$pdf = new FPDF("P", "mm", "letter");
$pdf->ADDpage();
$pdf->setFont("Arial", "B", 12);
$pdf->Cell(190,5, "Usuarios registrados en el Sistema", 0, 1, "C");
$pdf->Image("img/logo.png", 10,5,12);
$pdf->Ln(3);
$pdf->Cell(50, 5, "Nombre", 1, 0, "C");
$pdf->Cell(50, 5, "Apellido", 1, 0, "C");
$pdf->Cell(50, 5, "Direccion", 1, 0, "C");
$pdf->Cell(50, 5, "Clave", 1, 1, "C");
$pdf->SetFont("Arial", "", 9);
while($fila= $resultado->fetch()){
    $pdf->Cell(50, 5, utf8_decode($fila['nombre']), 1, 0, "C");
    $pdf->Cell(50, 5, utf8_decode($fila['apellido']), 1, 0, "C");
    $pdf->Cell(50, 5, utf8_decode($fila['direccion']), 1, 0, "C");
    $pdf->Cell(50, 5, utf8_decode($fila['clave']), 1, 1, "C");
}
$pdf->Output();

?>