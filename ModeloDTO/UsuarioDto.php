<?php
class UsuarioDto{
    private $documento = 0;
    private $id_rol = "";
    private $email = "";
    private $clave = "";
    private $tipo_doc = "";
    private $nombre1 = "";
    private $nombre2 = "";
    private $apellido1 = "";
    private $apellido2 = "";
    private $telefono = "";
    private $direccion = "";
    private $foto = "";
    private $grado = "";
// GET
function getDocumento(){
    return $this->documento;
}
function getRol(){
    return $this->id_rol;
}
function getEmail(){
    return $this->email;
}
function getClave(){
    return $this->clave;
}
function getTD(){
    return $this->tipo_doc;
}
function getNombre1(){
    return $this->nombre1;
}
function getNombre2(){
    return $this->nombre2;
}
function getApellido1(){
    return $this->apellido1;
}
function getApellido2(){
    return $this->apellido2;
}
function getDireccion(){
    return $this->direccion;
}
function getTelefono(){
    return $this->telefono;
}
function getFoto(){
    return $this->foto;
}
function getGrado(){
    return $this->grado ?: null;
}

//set
function setDocumento($documento){
    $this->documento=$documento;
}
function setRol($id_rol){
    $this->id_rol=$id_rol;
}
function setEmail($email){
    $this->email=$email;
}
function setClave($clave){
    $this->clave=$clave;
}
function setTD($tipo_doc){
    $this->tipo_doc=$tipo_doc;
}
function setNombre1($nombre1){
    $this->nombre1=$nombre1;
}
function setNombre2($nombre2){
    $this->nombre2=$nombre2;
}
function setApellido1($apellido1){
    $this->apellido1=$apellido1;
}
function setApellido2($apellido2){
    $this->apellido2=$apellido2;
}
function setDireccion($direccion){
    $this->direccion=$direccion;
}
function setTelefono($telefono){
    $this->telefono=$telefono;
}
function setFoto($foto){
    $this->foto=$foto;
}
function setGrado($grado){
    $this->grado=$grado;
}
}
?>