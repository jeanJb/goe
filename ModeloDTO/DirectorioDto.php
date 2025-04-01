<?php
class DirectorioDto{
    private $id_detalle = "";
    private $documento = 0;
    private $rh_estudiante = "";
    private $eps = "";
    private $fecha_naci = "";
    private $enfermedades = "";
    private $nom_acu = "";
    private $telefono_acu = "";
    private $doc_acu = "";
    private $email_acu = "";
// GET
function getIDDetalle(){
    return $this->id_detalle;
}
function getDocumento(){
    return $this->documento;
}
function getRh_estudiante(){
    return $this->rh_estudiante;
}
function getEps(){
    return $this->eps;
}
function getFecha_naci(){
    return $this->fecha_naci;
}
function getEnfermedades(){
    return $this->enfermedades;
}
function getNom_acu(){
    return $this->nom_acu;
}
function getTelefono_acu(){
    return $this->telefono_acu;
}
function getDoc_acu(){
    return $this->doc_acu;
}
function getEmail_acu(){
    return $this->email_acu;
}

//set
function setIDDetalle($id_detalle){
    $this->id_detalle=$id_detalle;
}
function setDocumento($documento){
    $this->documento=$documento;
}
function setRh_estudiante($rh_estudiante){
    $this->rh_estudiante=$rh_estudiante;
}
function setEps($eps){
    $this->eps=$eps;
}
function setFecha_naci($fecha_naci){
    $this->fecha_naci=$fecha_naci;
}
function setEnfermedades($enfermedades){
    $this->enfermedades=$enfermedades;
}
function setNom_acu($nom_acu){
    $this->nom_acu=$nom_acu;
}
function setTelefono_acu($telefono_acu){
    $this->telefono_acu=$telefono_acu;
}
function setDoc_acu($doc_acu){
    $this->doc_acu=$doc_acu;
}
function setEmail_acu($email_acu){
    $this->email_acu=$email_acu;
}

}
?>
