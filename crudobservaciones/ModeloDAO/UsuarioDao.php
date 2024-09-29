<?php

class UsuarioDao {

    public function registrarUsuario(UsuarioDto $usuarioDto) {
        $cnn = Conexion::getConexion();
        $mensaje = "";
        try {
            $query = $cnn->prepare("INSERT INTO observador VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
            $query->bindParam(1, $usuarioDto->getidobservador());
            $query->bindParam(2, $usuarioDto->getdocumento());
            $query->bindParam(3, $usuarioDto->getfecha());
            $query->bindParam(4, $usuarioDto->getdescripcion_falta());
            $query->bindParam(5, $usuarioDto->getcompromiso());
            $query->bindParam(6, $usuarioDto->getfirma());
            $query->bindParam(7, $usuarioDto->getseguimiento());
            $query->bindParam(8, $usuarioDto->getfalta());
            $query->execute();
            $mensaje = "Registro exitoso";
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
        
 catch (Exception $ex) {
            $mensaje = $ex->getMessage();
        }
        $cnn = null;
        return $mensaje;
    }

    // Modificar usuario
    public function modificarUsuario(UsuarioDto $usuarioDto) {
        $cnn = Conexion::getConexion();
        $mensaje = "";
        try {
            $query = $cnn->prepare("UPDATE observador SET documento = ?, fecha = ?, descripcion_falta = ?, compromiso = ?, firma = ?, seguimiento = ?, falta = ? WHERE idobservador = ?");
            $query->bindParam(1, $usuarioDto->getdocumento());
            $query->bindParam(2, $usuarioDto->getfecha());
            $query->bindParam(3, $usuarioDto->getdescripcion_falta());
            $query->bindParam(4, $usuarioDto->getcompromiso());
            $query->bindParam(5, $usuarioDto->getfirma());
            $query->bindParam(6, $usuarioDto->getseguimiento());
            $query->bindParam(7, $usuarioDto->getfalta());
            $query->bindParam(8, $usuarioDto->getidobservador());
            $query->execute();
            $mensaje = "Registro actualizado";
        } catch (Exception $ex) {
            $mensaje = $ex->getMessage();
        }
        $cnn = null;
        return $mensaje;
    }

    // Obtener usuario
    public function obtenerUsuario($idUsuario) {
        $cnn = Conexion::getConexion();
        $mensaje = "";
        try {
            $query = $cnn->prepare('SELECT * FROM observador WHERE documento = ?');
            $query->bindParam(1, $idUsuario);
            $query->execute();
            // Aquí puedes agregar el código para manejar el resultado de la consulta
        } catch (Exception $ex) {
            $mensaje = $ex->getMessage();
        }
        $cnn = null;
        return $mensaje;
    }

//eliminar Usuario
public function eliminarUsuario($idUsuario){
    $cnn = Conexion::getConexion();
    $mensaje = "";
    try {
        $query = $cnn->prepare('DELETE FROM observador WHERE idobservador= ?');
        $query->bindParam(1, $idUsuario);
        $query->execute();
        $mensaje= "Registro eliminado";
    } catch (Exception  $ex) {
        $mensaje= $ex->getMessage();
    }
    return $mensaje;
}

    //Listar

    public function listarTodos(){
        $cnn = Conexion::getConexion();

        try {
            $listarUsuarios = 'SELECT * from observador';
            $query = $cnn->prepare($listarUsuarios);
            $query->execute();
            return $query->fetchAll();
        } catch (Exception  $ex) {
            echo 'Error'. $ex->getMessage();
        }
    }
}

?>