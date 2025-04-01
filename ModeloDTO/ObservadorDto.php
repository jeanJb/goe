<?php
class ObservadorDto{
    private $idobservador = "";
    private $documento = 0;
    private $fecha = "";
    private $descripcion_falta = "";
    private $compromiso = "";
    private $firma = "";
    private $seguimiento = "";
    private $falta = "";

// GET
function getIDObservador(){
    return $this->idobservador;
}
function getDocumento(){
    return $this->documento;
}
function getFecha(){
    return $this->fecha;
}
function getDescripcion_falta(){
    return $this->descripcion_falta;
}
function getCompromiso(){
    return $this->compromiso;
}
function getFirma(){
    return $this->firma;
}
function getSeguimiento(){
    return $this->seguimiento;
}
function getFalta(){
    return $this->falta;
}

//set
function setIDObservador($idobservador){
    $this->idobservador=$idobservador;
}
function setDocumento($documento){
    $this->documento=$documento;
}
function setFecha($fecha){
    $this->fecha=$fecha;
}
function setDescripcion_falta($descripcion_falta){
    $this->descripcion_falta=$descripcion_falta;
}
function setCompromiso($compromiso){
    $this->compromiso=$compromiso;
}
function setFirma($firma){
    $this->firma=$firma;
}
function setSeguimiento($seguimiento){
    $this->seguimiento=$seguimiento;
}
function setFalta($falta){
    $this->falta=$falta;
}
}
?>
