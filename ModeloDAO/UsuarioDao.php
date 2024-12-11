<?php

class UsuarioDao{

    public function registrarUsuario(UsuarioDto $usuarioDto){
    $cnn = Conexion::getConexion();
    $mensaje = "";

    try {
        $query = $cnn->prepare("INSERT INTO usuario values(?,?,?, aes_encrypt(?,'SENA'),?,?,?,?,?,?,?,?,?)");
        $query->bindParam(1, $usuarioDto->getDocumento());
        $query->bindParam(2, $usuarioDto->getRol());
        $query->bindParam(3, $usuarioDto->getEmail());
        $query->bindParam(4, $usuarioDto->getClave());
        $query->bindParam(5, $usuarioDto->getTD());
        $query->bindParam(6, $usuarioDto->getNombre1());
        $query->bindParam(7, $usuarioDto->getNombre2());
        $query->bindParam(8, $usuarioDto->getApellido1());
        $query->bindParam(9, $usuarioDto->getApellido2());
        $query->bindParam(10, $usuarioDto->getTelefono());
        $query->bindParam(11, $usuarioDto->getDireccion());
        $query->bindParam(12, $usuarioDto->getFoto()); 
        $queryUsuario->bindParam(13, $grado, PDO::PARAM_NULL);
        $query->execute();
        
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
            $queryUsuario = $cnn->prepare("INSERT INTO usuario values(?,?,?, aes_encrypt(?,'SENA'),?,?,?,?,?,?,?,?,?)");
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

    public function registrarAsistencia($datos) {
        $cnn = Conexion::getConexion();
        $mensaje = "";
    
        try {
            $sql = "INSERT INTO asistencias (profesor, documento, estado_asis, idMat, fecha_asistencia, justification_asistencia) 
            VALUES (:profesor, :documento, :estado, :materia, :fecha, :justificacion)";
            

            $stmt = $cnn->prepare($sql);
            $stmt->bindParam(':profesor', $datos['profesor']);
            $stmt->bindParam(':documento', $datos['documento']);
            $stmt->bindParam(':estado', $datos['estado']);
            $stmt->bindParam(':materia', $datos['materia']);  // idMat
            $stmt->bindParam(':fecha', $datos['fecha']);      // fecha_asistencia
            $stmt->bindParam(':justificacion', $datos['justificacion']);

            return $stmt->execute();
            /* $resultado = $stmt->execute();
            if (!$resultado) {
                print_r($stmt->errorInfo());
                exit;
            } */
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }
    

    public function registrarObservador(ObservadorDto $usuarioDto){
        $cnn = Conexion::getConexion();
        $mensaje = "";
    
        try {
            $query = $cnn->prepare("INSERT INTO observador values(?,?,?,?,?,?,?,?)");
            //$query->bindParam(1, $usuarioDto->getIDObservador());
            $query->bindParam(1, $usuarioDto->getIDObservador());
            $query->bindParam(2, $usuarioDto->getDocumento());
            $query->bindParam(3, $usuarioDto->getFecha());
            $query->bindParam(4, $usuarioDto->getDescripcion_falta());
            $query->bindParam(5, $usuarioDto->getCompromiso());
            $query->bindParam(6, $usuarioDto->getFirma());
            $query->bindParam(7, $usuarioDto->getSeguimiento());
            $query->bindParam(8, $usuarioDto->getFalta());
            $query->execute();
            
            $mensaje= "Registro exitoso";
        } catch (Exception  $ex) {
            $mensaje= $ex->getMessage();
        }
        $cnn= null;
        return $mensaje;
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

    public function modificarAsistencia(AsistenciaDto $asistenciaDto) {
        $cnn = Conexion::getConexion();
        $mensaje = "";
        try {
            $query = $cnn->prepare("UPDATE asistencia SET profesor=?, documento=?, estado_asis=?, idmat=?, fecha_asistencia=?, justificacion_inasistencia=? WHERE idasistencia=?");
    
            $query->bindParam(1, $asistenciaDto->getProfesor());
            $query->bindParam(2, $asistenciaDto->getDocumento());
            $query->bindParam(3, $asistenciaDto->getEstadoAsis());
            $query->bindParam(4, $asistenciaDto->getIdMat());
            $query->bindParam(5, $asistenciaDto->getFechaAsistencia());
            $query->bindParam(6, $asistenciaDto->getJustificacionInasistencia());
            $query->bindParam(7, $asistenciaDto->getIdAsistencia());
    
            $query->execute();
            $mensaje = "Registro actualizado";
        } catch (Exception $ex) {
            $mensaje = $ex->getMessage();
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
        $query = $cnn->prepare("UPDATE directorio SET rh_estudiante=?, eps=?, fecha_naci=?, enfermedades=?, nom_acu=?, telefono_acu=?, doc_acu=? WHERE documento=?");

        $query->bindParam(1, $directorioDto->getRh_estudiante());
        $query->bindParam(2, $directorioDto->getEps());
        $query->bindParam(3, $directorioDto->getFecha_naci());
        $query->bindParam(4, $directorioDto->getEnfermedades());
        $query->bindParam(5, $directorioDto->getNom_acu());
        $query->bindParam(6, $directorioDto->getTelefono_acu());
        $query->bindParam(7, $directorioDto->getDoc_acu());


        $query->bindParam(8, $directorioDto->getDocumento());

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
public function eliminarUsuario($idUsuario){
    $cnn = Conexion::getConexion();
    $mensaje = "";
    try {
        $query = $cnn->prepare('DELETE FROM usuarios WHERE idUsuario= ?');
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

    //Listar modulo de estudiantes

    public function listarTodos(){
        $cnn = Conexion::getConexion();

        try {
            $listarUsuarios = 'SELECT * from usuario';
            $query = $cnn->prepare($listarUsuarios);
            $query->execute();
            return $query->fetchAll();
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
            $listarDir = 'SELECT usuario.nombre1, usuario.apellido1, usuario.apellido2, observador.* 
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

    public function listarAsistenciasPorDocumento($fecha = null, $materia = null, $documento) {
        $cnn = Conexion::getConexion();

        try {
            /* $listarObs = 'SELECT asistencia.*, materia.nomb_mat FROM asistencia
            INNER JOIN materia ON materia.idMat=asistencia.idMat WHERE  documento = :documento';
            $query = $cnn->prepare($listarObs); */

            $listarObs = 'SELECT asistencia.*, materia.nomb_mat
                FROM asistencia
                INNER JOIN usuario ON usuario.documento = asistencia.documento
                INNER JOIN curso ON curso.grado = usuario.grado
                INNER JOIN materia ON materia.idMat = asistencia.idMat
                WHERE  DATE(asistencia.fecha_asistencia) = :fecha OR asistencia.IdMat = :materia AND asistencia.documento = :documento';
            $query = $cnn->prepare($listarObs);
            
            // Vinculamos el valor del documento
            $query->bindParam(':documento', $documento, PDO::PARAM_STR); // Cambia a PDO::PARAM_INT si el documento es numérico.
            $query->bindParam(':fecha', $fecha, PDO::PARAM_STR);
            $query->bindParam(':materia', $materia, PDO::PARAM_INT);


            $query->execute();
            return $query->fetchAll();
        } catch (Exception  $ex) {
            echo 'Error: ' . $ex->getMessage();
        }
    }

    public function listarAsis($fecha = null, $curso = null, $materia = null) {
        $cnn = Conexion::getConexion();
    
        try {
            // Consulta base
            $listarObs = '
                SELECT asistencia.*, materia.nomb_mat
                FROM asistencia
                INNER JOIN usuario ON usuario.documento = asistencia.documento
                INNER JOIN curso ON curso.grado = usuario.grado
                INNER JOIN materia ON materia.idMat = asistencia.idMat
                WHERE 1=1';
    
            // Array para almacenar condiciones dinámicas
            $conditions = [];
            $params = [];
    
            // Agregar condiciones según los parámetros
            if ($fecha) {
                $conditions[] = 'DATE(asistencia.fecha_asistencia) = :fecha';
                $params[':fecha'] = $fecha;
            }
            if ($curso) {
                $conditions[] = 'usuario.grado = :curso';
                $params[':curso'] = $curso;
            }
            if ($materia) {
                $conditions[] = 'asistencia.IdMat = :materia';
                $params[':materia'] = $materia;
            }
    
            // Concatenar las condiciones dinámicas a la consulta
            if (!empty($conditions)) {
                $listarObs .= ' AND ' . implode(' AND ', $conditions);
            }
    
            $query = $cnn->prepare($listarObs);
    
            // Pasar parámetros dinámicos a la consulta preparada
            foreach ($params as $key => $value) {
                $query->bindValue($key, $value, is_int($value) ? PDO::PARAM_INT : PDO::PARAM_STR);
            }
    
            $query->execute();
            return $query->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $ex) {
            echo 'Error: ' . $ex->getMessage();
            return [];
        }
    }

    public function listarAsistencias($fecha = null, $curso = null, $materia = null) {
        $cnn = Conexion::getConexion();
    
        try {
            // Consulta base
            $listarObs = '
                SELECT asistencia.*, materia.nomb_mat 
                FROM asistencia
                INNER JOIN materia ON materia.idMat = asistencia.idMat
                WHERE 1=1'; // La condición 1=1 facilita agregar filtros dinámicos
    
            // Array para almacenar condiciones dinámicas
            $conditions = [];
            $params = [];
    
            // Agregar filtros según los parámetros proporcionados
            if ($fecha) {
                // Asegúrate de que la fecha esté en formato correcto (si es necesario)
                $conditions[] = 'asistencia.fecha_asistencia = :fecha';
                $params[':fecha'] = $fecha;
            }
            if ($curso) {
                $conditions[] = 'asistencia.grado = :curso';
                $params[':curso'] = $curso;
            }
            if ($materia) {
                $conditions[] = 'materia.nomb_mat = :materia';
                $params[':materia'] = $materia;
            }
    
            // Agregar condiciones a la consulta base
            if (!empty($conditions)) {
                $listarObs .= ' AND ' . implode(' AND ', $conditions);
            }
    
            // Preparar y ejecutar la consulta
            $query = $cnn->prepare($listarObs);
    
            // Pasar los parámetros al query
            foreach ($params as $key => $value) {
                $query->bindValue($key, $value);
            }
    
            $query->execute();
    
            // Retornar los resultados
            return $query->fetchAll(PDO::FETCH_ASSOC); // Retorna un array asociativo
        } catch (Exception $ex) {
            // Manejar el error, por ejemplo, registrarlo
            error_log('Error al listar asistencias: ' . $ex->getMessage());
            return []; // Devuelve un array vacío en caso de error
        }
    }

    public function listarUsuariosper($documento) {
        $cnn = Conexion::getConexion();

        try {
            $listarObs = 'SELECT rol.nom_rol, usuario.*, directorio.rh_estudiante, directorio.eps, directorio.fecha_naci, directorio.enfermedades, directorio.nom_acu, directorio.telefono_acu, directorio.doc_acu from usuario INNER JOIN 
            directorio on usuario.documento=directorio.documento INNER JOIN
            rol ON rol.id_rol=usuario.id_rol 
            WHERE usuario.id_rol=101 AND usuario.documento = :documento';
            $query = $cnn->prepare($listarObs);
            
            // Vinculamos el valor del documento
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
    


}
?>