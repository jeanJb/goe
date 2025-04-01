<?php
class PromatDto{
    private $id_relacion = "";
    private $documento = 0;
    private $idMat = "";

// GET
function getIDRelacion(){
    return $this->id_relacion;
}
function getDocumento(){
    return $this->documento;
}
function getIDMat(){
    return $this->idMat;
}

//set
function setIDRelacion($id_relacion){
    $this->id_relacion=$id_relacion;
}
function setDocumento($documento){
    $this->documento=$documento;
}
function setIDMat($idMat){
    $this->idMat=$idMat;
}
}
?>