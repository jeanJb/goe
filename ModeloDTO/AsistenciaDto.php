<?php
class AsistenciaDto {
    private $idasistencia = 0;
    private $profesor = "";
    private $documento = "";
    private $estado_asis = "";
    private $idmat = "";
    private $fecha_asistencia = "";
    private $justificacion_inasistencia = "";

    // GETTERS
    function getIdAsistencia() {
        return $this->idasistencia;
    }
    function getProfesor() {
        return $this->profesor;
    }
    function getDocumento() {
        return $this->documento;
    }
    function getEstadoAsis() {
        return $this->estado_asis;
    }
    function getIdMat() {
        return $this->idmat;
    }
    function getFechaAsistencia() {
        return $this->fecha_asistencia;
    }
    function getJustificacionInasistencia() {
        return $this->justificacion_inasistencia;
    }

    // SETTERS
    function setIdAsistencia($idasistencia) {
        $this->idasistencia = $idasistencia;
    }
    function setProfesor($profesor) {
        $this->profesor = $profesor;
    }
    function setDocumento($documento) {
        $this->documento = $documento;
    }
    function setEstadoAsis($estado_asis) {
        $this->estado_asis = $estado_asis;
    }
    function setIdMat($idmat) {
        $this->idmat = $idmat;
    }
    function setFechaAsistencia($fecha_asistencia) {
        $this->fecha_asistencia = $fecha_asistencia;
    }
    function setJustificacionInasistencia($justificacion_inasistencia) {
        $this->justificacion_inasistencia = $justificacion_inasistencia;
    }
}
?>

