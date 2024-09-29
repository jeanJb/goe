<?php

class UsuarioDto {
    private $idobservador = 0;
    private $documento = "";
    private $fecha = "";
    private $compromiso = "";
    private $firma = "";
    private $seguimiento = "";
    private $falta = "";
    private $descripcionFalta = "";

    // GET
    public function getIdobservador() {
        return $this->idobservador;
    }

    public function getDocumento() {
        return $this->documento;
    }

    public function getFecha() {
        return $this->fecha;
    }

    public function getCompromiso() {
        return $this->compromiso;
    }

    public function getFirma() {
        return $this->firma;
    }

    public function getSeguimiento() {
        return $this->seguimiento;
    }

    public function getFalta() {
        return $this->falta;
    }

    public function getDescripcion_Falta() {
        return $this->descripcion_Falta;
    }

    // SET
    public function setIdobservador($idobservador) {
        $this->idobservador = $idobservador;
    }

    public function setDocumento($documento) {
        $this->documento = $documento;
    }

    public function setFecha($fecha) {
        $this->fecha = $fecha;
    }

    public function setCompromiso($compromiso) {
        $this->compromiso = $compromiso;
    }

    public function setFirma($firma) {
        $this->firma = $firma;
    }

    public function setSeguimiento($seguimiento) {
        $this->seguimiento = $seguimiento;
    }

    public function setFalta($falta) {
        $this->falta = $falta;
    }

    public function setDescripcionFalta($descripcion_Falta) {
        $this->descripcion_Falta = $descripcion_Falta;
    }
}
?>