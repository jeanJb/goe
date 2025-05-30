<?php

class UsuarioDao{

    public function registrarUsuario(UsuarioDto $usuarioDto){
    $cnn = Conexion::getConexion();
    $mensaje = "";

    try {
        $queryUsuario = $cnn->prepare("INSERT INTO usuario values(?,?,?, aes_encrypt(?,'SENA'),?,?,?,?,?,?,?,?,?,?,?,?,?,?)");
        $queryUsuario->bindParam(1, $usuarioDto->getDocumento());
        $queryUsuario->bindParam(2, $usuarioDto->getRol());
        $queryUsuario->bindParam(3, $usuarioDto->getEmail());            
        $queryUsuario->bindParam(4, $usuarioDto->getClave());
        $queryUsuario->bindParam(5, $usuarioDto->getTD());
        $queryUsuario->bindParam(6, $usuarioDto->getNombre1());
        $queryUsuario->bindParam(7, $usuarioDto->getNombre2());            
        $queryUsuario->bindParam(8, $usuarioDto->getApellido1());
        $queryUsuario->bindParam(9, $usuarioDto->getApellido2());
        $queryUsuario->bindParam(10, $usuarioDto->getTelefono());
        $queryUsuario->bindParam(11, $usuarioDto->getDireccion());            
        $queryUsuario->bindParam(12, $usuarioDto->getFoto()); 
        $queryUsuario->bindParam(13, $grado, PDO::PARAM_NULL); 
        $queryUsuario->bindParam(14, $reset_token, PDO::PARAM_NULL);
        $queryUsuario->bindParam(15, $token_expiration, PDO::PARAM_NULL);
        $queryUsuario->bindParam(16, $token_sesion, PDO::PARAM_NULL);            
        $queryUsuario->bindParam(17, $usuarioDto->getActivo());
        $queryUsuario->bindParam(18, $usuarioDto->getTokenActivacion());
        $queryUsuario->execute();
        
        $mensaje= "Registro exitoso";
    } catch (Exception  $ex) {
        $mensaje= $ex->getMessage();
    }
    $cnn= null; 
    return $mensaje;
    }

    public function registrarUsuarioDir(UsuarioDto $usuarioDto) {
        $cnn = Conexion::getConexion();
        $mensaje = "";
    
        try {
            // Iniciar una transacción
            $cnn->beginTransaction();
    
            // Insertar en la tabla usuario
            $queryUsuario = $cnn->prepare("INSERT INTO usuario values(?,?,?, aes_encrypt(?,'SENA'),?,?,?,?,?,?,?,?,?,?,?,?,?,?)");
            $queryUsuario->bindParam(1, $usuarioDto->getDocumento());
            $queryUsuario->bindParam(2, $usuarioDto->getRol());
            $queryUsuario->bindParam(3, $usuarioDto->getEmail());
            $queryUsuario->bindParam(4, $usuarioDto->getClave());
            $queryUsuario->bindParam(5, $usuarioDto->getTD());
            $queryUsuario->bindParam(6, $usuarioDto->getNombre1());
            $queryUsuario->bindParam(7, $usuarioDto->getNombre2());
            $queryUsuario->bindParam(8, $usuarioDto->getApellido1());
            $queryUsuario->bindParam(9, $usuarioDto->getApellido2());
            $queryUsuario->bindParam(10, $usuarioDto->getTelefono());
            $queryUsuario->bindParam(11, $usuarioDto->getDireccion());
            $queryUsuario->bindParam(12, $usuarioDto->getFoto()); 
            $queryUsuario->bindParam(13, $grado, PDO::PARAM_NULL); 
            $queryUsuario->bindParam(14, $reset_token, PDO::PARAM_NULL);
            $queryUsuario->bindParam(15, $token_expiration, PDO::PARAM_NULL);
            $queryUsuario->bindParam(16, $token_sesion, PDO::PARAM_NULL);
            $queryUsuario->bindParam(17, $usuarioDto->getActivo());
            $queryUsuario->bindParam(18, $usuarioDto->getTokenActivacion());
            $queryUsuario->execute();
    
            // Insertar en la tabla directorio
            $queryDirectorio = $cnn->prepare("INSERT INTO directorio (id_detalle, documento) VALUES (?, ?)");
            $documento = $usuarioDto->getDocumento(); // Obtenemos el documento del usuario
            $queryDirectorio->bindParam(1, $documento);
            $queryDirectorio->bindParam(2, $documento);
            $queryDirectorio->execute();
    
            // Confirmar la transacción
            $cnn->commit();
    
            $mensaje = "Registro exitoso";
        } catch (Exception $ex) {
            // Revertir los cambios si ocurre un error
            $cnn->rollBack();
            $mensaje = $ex->getMessage();
        }
    
        $cnn = null;
        return $mensaje;
    }

    public function registrarAsistencias($trimestre, $asistencias) {
        $cnn = Conexion::getConexion();
    
        try {
            $cnn->beginTransaction(); // Iniciar una transacción para evitar errores de inserción parcial
    
            // Insertar un solo registro en la tabla listado
            $querylistado = $cnn->prepare("INSERT INTO listado (idlistado, trimestre) VALUES (?, ?)");
            $querylistado->bindParam(1, $idlistado);
            $querylistado->bindParam(2, $trimestre);
            $querylistado->execute();
    
            // Obtener el ID generado para listado
            $idlistado = $cnn->lastInsertId();  
    
            // Preparar la consulta para insertar asistencia (sin ejecutarla aún)
            $sql = "INSERT INTO asistencia (profesor, documento, estado_asis, idMat, fecha_asistencia, justificacion_inasistencia, idlistado) 
                    VALUES (:profesor, :documento, :estado, :materia, :fecha, :justificacion, :idlistado)";
            $stmt = $cnn->prepare($sql);
    
            // Insertar cada asistencia con el mismo idlistado
            foreach ($asistencias as $datos) {
                $stmt->bindParam(':profesor', $datos['profesor']);
                $stmt->bindParam(':documento', $datos['documento']);
                $stmt->bindParam(':estado', $datos['estado']);
                $stmt->bindParam(':materia', $datos['materia']);
                $stmt->bindParam(':fecha', $datos['fecha']);
                $stmt->bindParam(':justificacion', $datos['justificacion']);
                $stmt->bindParam(':idlistado', $idlistado);
    
                $stmt->execute();
            }
    
            $cnn->commit(); // Confirmar la transacción
            return true;
    
        } catch (PDOException $e) {
            $cnn->rollBack(); // Deshacer la transacción en caso de error
            echo "Error: " . $e->getMessage();
            return false;
        }
    }
    
    
    

    public function registrarObservador(ObservadorDto $usuarioDto,$trimestre){
        $cnn = Conexion::getConexion();
        $mensaje = "";
    
        try {
            $query = $cnn->prepare("INSERT INTO observador values(?,?,?,?,?,?,?,?,?)");
            //$query->bindParam(1, $usuarioDto->getIDObservador());
            $query->bindParam(1, $usuarioDto->getIDObservador());
            $query->bindParam(2, $usuarioDto->getDocumento());
            $query->bindParam(3, $usuarioDto->getFecha());
            $query->bindParam(4, $usuarioDto->getDescripcion_falta());
            $query->bindParam(5, $usuarioDto->getCompromiso());
            $query->bindParam(6, $usuarioDto->getFirma());
            $query->bindParam(7, $usuarioDto->getSeguimiento());
            $query->bindParam(8, $usuarioDto->getFalta());
            $query->bindParam(9, $trimestre);
            $query->execute();
            
            $mensaje= "Registro exitoso";
        } catch (Exception  $ex) {
            $mensaje= $ex->getMessage();
        }
        $cnn= null;
        return $mensaje;
        }

        public function registrarPromat(PromatDto $usuarioDto){
            $cnn = Conexion::getConexion();
            $mensaje = "";
        
            try {
                $query = $cnn->prepare("INSERT INTO profesor_materia values(?,?,?)");
                
                $query->bindParam(1, $usuarioDto->getIDRelacion());
                $query->bindParam(2, $usuarioDto->getDocumento());
                $query->bindParam(3, $usuarioDto->getIDMat());
                
                $query->execute();
                
                $mensaje= "Registro exitoso";
            } catch (Exception  $ex) {
                $mensaje= $ex->getMessage();
            }
            $cnn= null;
            return $mensaje;
        }

        public function registrarCurMat($grado, $materia){
            $cnn = Conexion::getConexion();
            $mensaje = "";
        
            try {
                $query = $cnn->prepare("INSERT INTO curso_materia values(?,?,?)");
                
                $query->bindParam(1, $idrelacion);
                $query->bindParam(2, $grado);
                $query->bindParam(3, $materia);
                
                $query->execute();
                
                $mensaje= "Registro exitoso";
            } catch (Exception  $ex) {
                $mensaje= $ex->getMessage();
            }
            $cnn= null;
            return $mensaje;
        }

        public function registrarMateria($asignatura){
            $cnn = Conexion::getConexion();
            $mensaje = "";
        
            try {
                $query = $cnn->prepare("INSERT INTO materia values(?,?)");
                
                $query->bindParam(1, $idmat);
                $query->bindParam(2, $asignatura);
                
                
                $query->execute();
                    
                $mensaje= "Registro exitoso";
            } catch (Exception  $ex) {
                $mensaje= $ex->getMessage();
            }
            $cnn= null;                return $mensaje;
        }

        public function RegistrarCurso($grado, $salon){
            $cnn = Conexion::getConexion();
            $mensaje = "";
        
            try {
                $query = $cnn->prepare("INSERT INTO curso values(?,?)");
                
                $query->bindParam(1, $grado);
                $query->bindParam(2, $salon);
                
                
                $query->execute();
                    
                $mensaje= "Registro exitoso";
            } catch (Exception  $ex) {
                $mensaje= $ex->getMessage();
            }
            $cnn= null;                return $mensaje;
        }

//modificar usuario 
public function modificarUsuario(UsuarioDto $usuarioDto){
    $cnn = Conexion::getConexion();
    $mensaje = "";
    try {
        $query = $cnn->prepare("UPDATE usuario SET email=?, clave=aes_encrypt(?,'SENA'), nombre1=?, nombre2=?, apellido1=?, apellido2=?, telefono=?, direccion=?, foto=?, grado=? WHERE documento=?");

        $query->bindParam(1, $usuarioDto->getEmail());
        $query->bindParam(2, $usuarioDto->getClave());
        $query->bindParam(3, $usuarioDto->getNombre1());
        $query->bindParam(4, $usuarioDto->getNombre2());
        $query->bindParam(5, $usuarioDto->getApellido1());
        $query->bindParam(6, $usuarioDto->getApellido2());
        $query->bindParam(7, $usuarioDto->getTelefono());
        $query->bindParam(8, $usuarioDto->getDireccion());
        $query->bindParam(9, $usuarioDto->getFoto()); 
        $query->bindParam(10, $usuarioDto->getGrado()); 

        $query->bindParam(11, $usuarioDto->getDocumento());

        $query->execute();
        $mensaje= "Registro actualizado";
    } catch (Exception  $ex) {
        $mensaje= $ex->getMessage();
    }
    $cnn= null;
    return $mensaje;
    }

    public function modificarCurso($grado, $salon){
        $cnn = Conexion::getConexion();
        $mensaje = "";
        try {
            $query = $cnn->prepare("UPDATE curso SET  salon=? WHERE grado=?");
    
            
            $query->bindParam(1, $salon);
            $query->bindParam(2, $grado);
    
            $query->execute();
            $mensaje= "Registro actualizado";
        } catch (Exception  $ex) {
            $mensaje= $ex->getMessage();
        }
        $cnn= null;
        return $mensaje;
    }

    public function modificarGrado(UsuarioDto $usuarioDto){
        $cnn = Conexion::getConexion();
        $mensaje = "";
        try {
            $query = $cnn->prepare("UPDATE usuario SET  grado=? WHERE documento=?");
    
            
            $query->bindParam(1, $usuarioDto->getGrado()); 
    
            $query->bindParam(2, $usuarioDto->getDocumento());
    
            $query->execute();
            $mensaje= "Registro actualizado";
        } catch (Exception  $ex) {
            $mensaje= $ex->getMessage();
        }
        $cnn= null;
        return $mensaje;
    }

    public function modificarGradoAll($gradoNew, $gradoOld) {
        $cnn = Conexion::getConexion();
        $mensaje = "";
        try {
            $query = $cnn->prepare("UPDATE usuario SET grado = ? WHERE grado = ?");
            
            $query->bindParam(1, $gradoNew); 
            $query->bindParam(2, $gradoOld);
            
            $query->execute();
    
            $mensaje = "Registro actualizado correctamente.";
        } catch (Exception $ex) {
            $mensaje = "Error al actualizar: " . $ex->getMessage();
        }
        $cnn = null;
        return $mensaje;
    }

    public function actualizarAsistencia($id, $estado, $justificacion) {
        $cnn = Conexion::getConexion();
        $mensaje = "";
        try {
            $query = $cnn->prepare("UPDATE asistencia SET estado_asis=?, justificacion_inasistencia = ? WHERE idasistencia = ?");
            
            $query->bindParam(1, $estado);
            $query->bindParam(2, $justificacion);
            $query->bindParam(3, $id); 
            
            $query->execute();

            $mensaje = "Registro actualizado correctamente.";
        } catch (Exception $ex) {
            $mensaje = "Error al actualizar: " . $ex->getMessage();
        }
        $cnn = null;
        return $mensaje;
    }

    public function modificarObservador(ObservadorDto $observadorDto) {
        $cnn = Conexion::getConexion();
        $mensaje = "";
        try {
            $query = $cnn->prepare("
                UPDATE observador 
                SET  
                    
                    fecha = ?, 
                    descripcion_falta = ?, 
                    compromiso = ?,
                    firma = ?, 
                    seguimiento = ?, 
                    falta = ?
                WHERE idobservador = ?
            ");

            //$query->bindParam(2, $observadorDto->getDocumento());
            $query->bindParam(1, $observadorDto->getFecha());
            $query->bindParam(2, $observadorDto->getDescripcion_falta());
            $query->bindParam(3, $observadorDto->getCompromiso());
            $query->bindParam(4, $observadorDto->getFalta());
            $query->bindParam(5, $observadorDto->getSeguimiento());
            $query->bindParam(6, $observadorDto->getFalta());
            $query->bindParam(7, $observadorDto->getIDObservador());
    
            $query->execute();
            $mensaje = "Registro de observador actualizado";
        } catch (Exception $ex) {
            $mensaje = $ex->getMessage();
        }
        $cnn = null;
        return $mensaje;
    }
    

    //modificar directorio
public function modificarDirectorio(DirectorioDto $directorioDto){
    $cnn = Conexion::getConexion();
    $mensaje = "";
    try {
        $query = $cnn->prepare("UPDATE directorio SET rh_estudiante=?, eps=?, fecha_naci=?, enfermedades=?, nom_acu=?, telefono_acu=?, doc_acu=?, email_acu=? WHERE documento=?");

        $query->bindParam(1, $directorioDto->getRh_estudiante());
        $query->bindParam(2, $directorioDto->getEps());
        $query->bindParam(3, $directorioDto->getFecha_naci());
        $query->bindParam(4, $directorioDto->getEnfermedades());
        $query->bindParam(5, $directorioDto->getNom_acu());
        $query->bindParam(6, $directorioDto->getTelefono_acu());
        $query->bindParam(7, $directorioDto->getDoc_acu());
        $query->bindParam(8, $directorioDto->getEmail_acu());

        $query->bindParam(9, $directorioDto->getDocumento());

        $query->execute();
        $mensaje= "Registro actualizado";
    } catch (Exception  $ex) {
        $mensaje= $ex->getMessage();
    }
    $cnn= null;
    return $mensaje;
    }
    
    // obtener usuario y directorio
    public function obtenerUsuario($documento) {
        $cnn = Conexion::getConexion();
        try {
            $query = $cnn->prepare('SELECT rol.nom_rol, AES_DECRYPT(usuario.clave, :clave_secreta) as clave, usuario.documento, usuario.id_rol, usuario.email, 
            usuario.tipo_doc, usuario.nombre1, usuario.nombre2, usuario.apellido1, usuario.apellido2, usuario.telefono, usuario.direccion, usuario.foto, usuario.grado 
            FROM usuario
            INNER JOIN rol ON rol.id_rol = usuario.id_rol 
            WHERE usuario.documento = :documento;');
            
            $claveSecreta = 'SENA';
            $query->bindParam(':clave_secreta', $claveSecreta);
            $query->bindParam(':documento', $documento, PDO::PARAM_STR);
            $query->execute();
            
            $resultado = $query->fetch(PDO::FETCH_ASSOC);
            return $resultado ? $resultado : [];
        } catch (Exception $ex) {
            error_log("Error en obtenerUsuario: " . $ex->getMessage());
            return [];
        } finally {
            $cnn = null;
        }
    }
    

 // obtener usuario
public function user($documento){
    $cnn = Conexion::getConexion();
    $mensaje = "";
    try {
        $query = $cnn->prepare('SELECT * FROM usuario WHERE documento=?');
        $query->bindParam(1, $documento);
        $query->execute();
        return $query->fetch();

    } catch (Exception  $ex) {
        $mensaje= $ex->getMessage();
    }
    $cnn= null;
    return $mensaje;
}

//eliminar Usuario
public function eliminarUsuario($documento){
    $cnn = Conexion::getConexion();
    $mensaje = "";
    try {
        $query = $cnn->prepare('DELETE FROM usuario WHERE documento= ?');
        $query->bindParam(1, $documento);
        $query->execute();
        $mensaje= "Registro eliminado";
    } catch (Exception  $ex) {
        $mensaje= $ex->getMessage();
    }
    return $mensaje;
}

public function eliminarMateria($idUsuario){
    $cnn = Conexion::getConexion();
    $mensaje = "";
    try {
        $query = $cnn->prepare('DELETE FROM materia WHERE idmat= ?');
        $query->bindParam(1, $idUsuario);
        $query->execute();
        $mensaje= "Registro eliminado";
    } catch (Exception  $ex) {
        $mensaje= $ex->getMessage();
    }
    return $mensaje;
}

public function eliminarObservador($idUsuario){
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

public function eliminarAsistencia($idUsuario){
    $cnn = Conexion::getConexion();
    $mensaje = "";
    try {
        $query = $cnn->prepare('DELETE FROM listado WHERE idlistado= ?');
        $query->bindParam(1, $idUsuario);
        $query->execute();
        $mensaje= "Registro eliminado";
    } catch (Exception  $ex) {
        $mensaje= $ex->getMessage();
    }
    return $mensaje;
}

public function eliminarPromat($idUsuario){
    $cnn = Conexion::getConexion();
    $mensaje = "";
    try {
        $query = $cnn->prepare('DELETE FROM profesor_materia WHERE id_relacion= ?');
        $query->bindParam(1, $idUsuario);
        $query->execute();
        $mensaje= "Registro eliminado";
    } catch (Exception  $ex) {
        $mensaje= $ex->getMessage();
    }
    return $mensaje;
}

public function eliminarCurso($grado){
    $cnn = Conexion::getConexion();
    $mensaje = "";
    try {
        $query = $cnn->prepare('DELETE FROM curso WHERE grado= ?');
        $query->bindParam(1, $grado);
        $query->execute();
        $mensaje= "Registro eliminado";
    } catch (Exception  $ex) {
        $mensaje= $ex->getMessage();
    }
    return $mensaje;
}

public function eliminarCurMat($id){
    $cnn = Conexion::getConexion();
    $mensaje = "";
    try {
        $query = $cnn->prepare('DELETE FROM curso_materia WHERE id_relacion= ?');
        $query->bindParam(1, $id);
        $query->execute();
        $mensaje= "Registro eliminado";
    } catch (Exception  $ex) {
        $mensaje= $ex->getMessage();
    }
    return $mensaje;
}

    //Listar modulo de estudiantes

    public function listarTodos(){
        $cnn = Conexion::getConexion();

        try {
            $listarUsuarios = 'SELECT usuario.*, rol.nom_rol
            FROM usuario INNER JOIN rol ON rol.id_rol=usuario.id_rol
            WHERE usuario.id_rol != 104;';
            $query = $cnn->prepare($listarUsuarios);
            $query->execute();
            return $query->fetchAll();
        } catch (Exception  $ex) {
            echo 'Error'. $ex->getMessage();
        }
    }

    public function EstudiantesCurso($grado){
        $cnn = Conexion::getConexion();

        try {
            $query = $cnn->prepare('SELECT * FROM usuario WHERE id_rol=101 AND grado= :grado');
            $query->bindParam(':grado', $grado, PDO::PARAM_STR);
            $query->execute();
            return $query->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception  $ex) {
            echo 'Error'. $ex->getMessage();
        }
    }

    public function listarDirectorios(){
        $cnn = Conexion::getConexion();

        try {
            $listarDir = 'SELECT * from directorio';
            $query = $cnn->prepare($listarDir);
            $query->execute();
            return $query->fetchAll();
        } catch (Exception  $ex) {
            echo 'Error'. $ex->getMessage();
        }
    }

    public function listarObservadores(){
        $cnn = Conexion::getConexion();

        try {
            $listarDir = 'SELECT usuario.nombre1, usuario.nombre2, usuario.apellido1, usuario.apellido2, observador.* 
            FROM observador
            INNER JOIN usuario ON usuario.documento=observador.documento';
            $query = $cnn->prepare($listarDir);
            $query->execute();
            return $query->fetchAll();
        } catch (Exception  $ex) {
            echo 'Error'. $ex->getMessage();
        }
    }

    public function listarEstudiantesPorCurso($grado) {
        $cnn = Conexion::getConexion();
    
        try {
            $query = 'SELECT * FROM usuario WHERE usuario.id_rol=101 AND grado = :grado';
            $stmt = $cnn->prepare($query);
            $stmt->bindParam(':grado', $grado, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $ex) {
            echo 'Error: ' . $ex->getMessage();
            return [];
        }
    }

    public function listarDirectoriosper($documento){
        $cnn = Conexion::getConexion();

        try {
            $listarDir = 'SELECT * from directorio WHERE documento= :documento';
            $query = $cnn->prepare($listarDir);

            $query->bindParam(':documento', $documento, PDO::PARAM_STR);

            $query->execute();
            return $query->fetchAll();
        } catch (Exception  $ex) {
            echo 'Error'. $ex->getMessage();
        }
    }

    public function listarObservadoresper($documento) {
        $cnn = Conexion::getConexion();

        try {
            $listarObs = 'SELECT * FROM observador WHERE documento = :documento';
            $query = $cnn->prepare($listarObs);
            
            // Vinculamos el valor del documento
            $query->bindParam(':documento', $documento, PDO::PARAM_STR); // Cambia a PDO::PARAM_INT si el documento es numérico.

            $query->execute();
            return $query->fetchAll();
        } catch (Exception  $ex) {
            echo 'Error: ' . $ex->getMessage();
        }
    }

    public function TomaAsistenciaCurso($grado) {
        $cnn = Conexion::getConexion();

        try {
            $listarObs = 'SELECT * FROM usuario WHERE usuario.id_rol=101 AND usuario.grado = :grado';
            $query = $cnn->prepare($listarObs);
            
            // Vinculamos el valor del documento
            $query->bindParam(':grado', $grado, PDO::PARAM_INT); // Cambia a PDO::PARAM_INT si el documento es numérico.

            $query->execute();
            return $query->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $ex) {
            // Muestra el error si ocurre
            echo 'Error en TomaAsistenciaCurso: ' . $ex->getMessage();
            return [];
        }
    }

    public function listarAsistenciasPorDocumento($fecha = null, $materia = null, $trimestre = null, $documento) {
        $cnn = Conexion::getConexion();
    
        try {
            // Construimos la base de la consulta
            $listarObs = '
                SELECT listado.*, asistencia.*, materia.nomb_mat
                FROM asistencia
                INNER JOIN listado ON listado.idlistado = asistencia.idlistado
                INNER JOIN usuario ON usuario.documento = asistencia.documento
                INNER JOIN curso ON curso.grado = usuario.grado
                INNER JOIN materia ON materia.idMat = asistencia.idMat
                WHERE asistencia.documento = :documento';
    
            // Agregamos filtros solo si tienen valor
            if (!empty($materia)) {
                $listarObs .= ' AND asistencia.idMat = :materia';
            }
    
            if (!empty($fecha)) {
                $listarObs .= ' AND DATE(asistencia.fecha_asistencia) = :fecha';
            }

            if (!empty($trimestre)) {
                $listarObs .= ' AND listado.trimestre = :trimestre';
            }
    
            $query = $cnn->prepare($listarObs);
    
            // Vinculamos siempre el documento
            $query->bindParam(':documento', $documento, PDO::PARAM_STR);
    
            // Vinculamos parámetros solo si tienen valor
            if (!empty($materia)) {
                $query->bindParam(':materia', $materia, PDO::PARAM_INT);
            }
    
            if (!empty($fecha)) {
                $query->bindParam(':fecha', $fecha, PDO::PARAM_STR);
            }

            if (!empty($trimestre)) {
                $query->bindParam(':trimestre', $trimestre, PDO::PARAM_STR);
            }
    
            $query->execute();
            return $query->fetchAll();
    
        } catch (Exception $ex) {
            echo 'Error: ' . $ex->getMessage();
        }
    }
    

    public function listarAsistencias($fecha = null, $curso = null, $materia = null, $trimestre = null) {
        $cnn = Conexion::getConexion();
        try {
            $sql = 'SELECT listado.*, asistencia.*, materia.nomb_mat, usuario.grado AS curso
                    FROM asistencia
                    INNER JOIN listado ON listado.idlistado = asistencia.idlistado
                    INNER JOIN usuario ON usuario.documento = asistencia.documento
                    INNER JOIN materia ON materia.idMat = asistencia.idMat';
            
            $conditions = [];
            $params = [];
    
            if (!empty($fecha)) {
                $conditions[] = "DATE(asistencia.fecha_asistencia) = :fecha";
                $params[':fecha'] = $fecha;
            }
    
            if (!empty($curso)) {
                $conditions[] = "usuario.grado = :curso";
                $params[':curso'] = $curso;
            }
    
            if (!empty($materia)) {
                $conditions[] = "asistencia.idMat = :materia";
                $params[':materia'] = $materia;
            }
            
            if (!empty($trimestre)) {
                $conditions[] = "listado.trimestre = :trimestre";
                $params[':trimestre'] = $trimestre;
            }
    
            // Agregar condiciones si existen
            if (!empty($conditions)) {
                $sql .= " AND " . implode(" AND ", $conditions);
            }
    
            $sql .= " GROUP BY asistencia.idlistado"; // Mantenemos el GROUP BY si es necesario
    
            $stmt = $cnn->prepare($sql);
    
            // Vincula los parámetros
            foreach ($params as $key => $value) {
                $stmt->bindValue($key, $value, is_int($value) ? PDO::PARAM_INT : PDO::PARAM_STR);
            }
    
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $ex) {
            error_log("Error al listar asistencias: " . $ex->getMessage());
            return [];
        }
    }

    public function listarAsistenciasDoc($fecha, $curso, $materia, $trimestre, $docente) {
        $cnn = Conexion::getConexion();
        try {
            $sql = "SELECT listado.*, asistencia.*, materia.nomb_mat, usuario.grado AS curso
                    FROM asistencia
                    INNER JOIN listado ON listado.idlistado = asistencia.idlistado
                    INNER JOIN usuario ON usuario.documento = asistencia.documento
                    INNER JOIN materia ON materia.idMat = asistencia.idMat
                    WHERE asistencia.profesor = :docente"; // Filtro por docente
    
            $conditions = [];
            $params = [':docente' => $docente]; // Parámetro base para el docente
    
            if (!empty($fecha)) {
                $conditions[] = "DATE(asistencia.fecha_asistencia) = :fecha";
                $params[':fecha'] = $fecha;
            }
    
            if (!empty($curso)) {
                $conditions[] = "usuario.grado = :curso";
                $params[':curso'] = $curso;
            }
    
            if (!empty($materia)) {
                $conditions[] = "asistencia.idMat = :materia";
                $params[':materia'] = $materia;
            }
            
            if (!empty($trimestre)) {
                $conditions[] = "listado.trimestre = :trimestre";
                $params[':trimestre'] = $trimestre;
            }
    
            // Agregar condiciones si existen
            if (!empty($conditions)) {
                $sql .= " AND " . implode(" AND ", $conditions);
            }
    
            $sql .= " GROUP BY asistencia.idlistado"; // Mantenemos el GROUP BY si es necesario
    
            $stmt = $cnn->prepare($sql);
    
            // Vincula los parámetros
            foreach ($params as $key => $value) {
                $stmt->bindValue($key, $value, is_int($value) ? PDO::PARAM_INT : PDO::PARAM_STR);
            }
    
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $ex) {
            error_log("Error al listar asistencias: " . $ex->getMessage());
            return [];
        }
    }  

    public function listadoAsis($listado) {
        $cnn = Conexion::getConexion();

        try {
            $listarObs = 'SELECT asistencia.*, materia.nomb_mat, usuario.grado AS curso, usuario.nombre1, 
                    usuario.nombre2, usuario.apellido1, usuario.apellido2
                    FROM asistencia
                    INNER JOIN usuario ON usuario.documento=asistencia.documento
                    INNER JOIN materia ON materia.idMat = asistencia.idMat WHERE asistencia.idlistado = :listado';
            $query = $cnn->prepare($listarObs);
            
            // Vinculamos el valor del documento
            $query->bindParam(':listado', $listado, PDO::PARAM_INT); // Cambia a PDO::PARAM_INT si el documento es numérico.

            $query->execute();
            return $query->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $ex) {
            // Muestra el error si ocurre
            echo 'Error en TomaAsistenciaCurso: ' . $ex->getMessage();
            return [];
        }
    }

    public function listarUsuariosper($documento) {
        $cnn = Conexion::getConexion();

        try {
            $listarObs = 'SELECT rol.nom_rol, usuario.*, AES_DECRYPT(usuario.clave, :clave_secreta) as pass, directorio.rh_estudiante, directorio.eps, directorio.fecha_naci, directorio.enfermedades, directorio.nom_acu, directorio.telefono_acu, directorio.doc_acu, directorio.email_acu from usuario INNER JOIN 
            directorio on usuario.documento=directorio.documento INNER JOIN
            rol ON rol.id_rol=usuario.id_rol 
            WHERE usuario.id_rol=101 AND usuario.documento = :documento';
            $query = $cnn->prepare($listarObs);
            
            // Vinculamos el valor del documento
            $claveSecreta = 'SENA';
            $query->bindParam(':clave_secreta', $claveSecreta);
            $query->bindParam(':documento', $documento, PDO::PARAM_STR); // Cambia a PDO::PARAM_INT si el documento es numérico.

            $query->execute();
            return $query->fetchAll();
        } catch (Exception  $ex) {
            echo 'Error: ' . $ex->getMessage();
        }
    }

    public function Cursoper($documento) {
        $cnn = Conexion::getConexion();

        try {
            $listarCurso = 'SELECT usuario.documento, curso.grado, curso.salon, materia.idMat, materia.nomb_mat, horario.dia, horario.Fecha_inicio, horario.Fecha_fin 
            FROM curso
            INNER JOIN horario ON curso.grado=horario.grado
            INNER JOIN materia ON horario.IdMat=materia.idmat
            INNER JOIN usuario ON usuario.grado=curso.grado

            WHERE usuario.documento = :documento ORDER BY horario.Fecha_inicio ASC';
            $query = $cnn->prepare($listarCurso);
            
            // Vinculamos el valor del documento
            $query->bindParam(':documento', $documento, PDO::PARAM_STR); // Cambia a PDO::PARAM_INT si el documento es numérico.

            $query->execute();
            return $query->fetchAll();
        } catch (Exception  $ex) {
            echo 'Error: ' . $ex->getMessage();
        }
    }

    public function Curso() {
        $cnn = Conexion::getConexion();

        try {
            $listarCurso = 'SELECT usuario.documento, curso.grado, curso.salon, materia.idMat, materia.nomb_mat, horario.dia, horario.Fecha_inicio, horario.Fecha_fin 
            FROM curso
            INNER JOIN horario ON curso.grado=horario.grado
            INNER JOIN materia ON horario.IdMat=materia.idmat
            INNER JOIN usuario ON usuario.grado=curso.grado

            ORDER BY horario.Fecha_inicio ASC';
            $query = $cnn->prepare($listarCurso);
            
            // Vinculamos el valor del documento
            $query->bindParam(':documento', $documento, PDO::PARAM_STR); // Cambia a PDO::PARAM_INT si el documento es numérico.

            $query->execute();
            return $query->fetchAll();
        } catch (Exception  $ex) {
            echo 'Error: ' . $ex->getMessage();
        }
    }

    public function ListarCursos() {
        $cnn = Conexion::getConexion();

        try {
            $sql = "SELECT * FROM curso";
            $stmt = $cnn->prepare($sql);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
            if (empty($result)) {
                error_log("No se encontraron cursos.");
                return [];
            }
    
            return $result;
        } catch (PDOException $e) {
            error_log("Error en Curso(): " . $e->getMessage());
            return [];
        }
    }

    public function ListarMaterias() {
        $cnn = Conexion::getConexion();
    
        try {
            $listarCurso = 'SELECT * FROM materia';
            $query = $cnn->prepare($listarCurso);
            
            $query->execute();
            $result= $query->fetchAll(PDO::FETCH_ASSOC);
    
            return $result;
        } catch (Exception  $ex) {
            echo 'Error: ' . $ex->getMessage();
        }
    }

    public function ListarMateriasdoc($docente) {
        $cnn = Conexion::getConexion();
    
        try {
            $listarCurso = '
                SELECT materia.nomb_mat, profesor_materia.* 
                FROM materia
                INNER JOIN profesor_materia ON materia.idMat = profesor_materia.idMat
                WHERE profesor_materia.documento = :documento';
            
            $query = $cnn->prepare($listarCurso);
            $query->bindParam(':documento', $docente, PDO::PARAM_STR);
    
            $query->execute();
            return $query->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $ex) {
            error_log('Error al listar materias del docente: ' . $ex->getMessage());
            return [];
        }
    }
    
    
    public function ListarDocentes() {
        $cnn = Conexion::getConexion();
    
        try {
            $listarCurso = 'SELECT * FROM usuario WHERE id_rol=102';
            $query = $cnn->prepare($listarCurso);
            
            $query->execute();
            $result= $query->fetchAll(PDO::FETCH_ASSOC);
    
            return $result;
        } catch (Exception  $ex) {
            echo 'Error: ' . $ex->getMessage();
        }
    }

    public function ListarEstudiantes() {
        $cnn = Conexion::getConexion();
    
        try {
            $listarCurso = 'SELECT * FROM usuario WHERE id_rol=101';
            $query = $cnn->prepare($listarCurso);
            
            $query->execute();
            $result= $query->fetchAll(PDO::FETCH_ASSOC);
    
            return $result;
        } catch (Exception  $ex) {
            echo 'Error: ' . $ex->getMessage();
        }
    }

    public function MateriasAsignadas() {
        $cnn = Conexion::getConexion();
    
        try {
            $listarCurso = 'SELECT usuario.*, profesor_materia.id_relacion, profesor_materia.idMat, materia.nomb_mat FROM profesor_materia 
            INNER JOIN usuario ON profesor_materia.documento=usuario.documento 
            INNER JOIN materia ON profesor_materia.idMat=materia.idmat WHERE usuario.id_rol=102;';
            $query = $cnn->prepare($listarCurso);
            
            $query->execute();
            $result= $query->fetchAll(PDO::FETCH_ASSOC);
    
            return $result;
        } catch (Exception  $ex) {
            echo 'Error: '. $ex->getMessage();
        }
    }

    public function MateriasAsignadasDocente($docente) {
        $cnn = Conexion::getConexion();
    
        try {
            $query = $cnn->prepare('SELECT usuario.*, profesor_materia.id_relacion, profesor_materia.idMat, materia.nomb_mat FROM profesor_materia 
            INNER JOIN usuario ON profesor_materia.documento=usuario.documento 
            INNER JOIN materia ON profesor_materia.idMat=materia.idmat WHERE usuario.id_rol=102 AND usuario.documento= :documento');
            
            $query->bindParam(':documento', $docente, PDO::PARAM_STR); // Cambia a PDO::PARAM_INT si el documento es numérico.
            
            $query->execute();
            $result= $query->fetchAll(PDO::FETCH_ASSOC);
    
            return $result;
        } catch (Exception  $ex) {
            echo 'Error: '. $ex->getMessage();
        }
    }

    public function MateriasAsignadasCurso() {
        $cnn = Conexion::getConexion();
    
        try {
            $listarCurso = 'SELECT curso_materia.*, materia.nomb_mat FROM curso_materia
            INNER JOIN materia ON materia.idmat=curso_materia.idmat;';
            $query = $cnn->prepare($listarCurso);
            
            $query->execute();
            $result= $query->fetchAll(PDO::FETCH_ASSOC);
    
            return $result;
        } catch (Exception  $ex) {
            echo 'Error: '. $ex->getMessage();
        }
    }

}
?>