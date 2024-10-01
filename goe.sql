-----------------------------------------------------
Create database `goe`;
Use `goe`

-- Base de datos: `goe`


-- tabla asistencia


CREATE TABLE `asistencia` (
  `idasistencia` int(11) NOT NULL COMMENT 'identificación de las asistencias de un curso',
  `profesor` varchar(50) NOT NULL COMMENT 'Nombre del profesor que llena la asistencia',
  `documento` int(11) NOT NULL COMMENT 'número identificador del usuario',
  `estado_asis` varchar(30) NOT NULL COMMENT 'estado de la asistencia del estudiante',
  `IdMat` int(11) NOT NULL COMMENT 'identificación de la materia	',
  `fecha_asistencia` datetime DEFAULT NULL COMMENT 'fecha de registro de la asistencia	',
  `justificacion_inasistencia` varchar(250) NOT NULL COMMENT 'justificación de la inasistencia del estudiante	'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='donde se registra la asistencia a clase del estudiante';


-- datos para la tabla asistencia


INSERT INTO `asistencia` (`idasistencia`, `profesor`, `documento`, `estado_asis`, `IdMat`, `fecha_asistencia`, `justificacion_inasistencia`) VALUES
(1, 'Juan', 1020509681, 'Presente', 3, '2024-09-03 00:00:00', 'N/A'),
(2, 'Camilo', 1020509682, 'Ausente', 3, '2024-09-12 00:00:00', 'N/A'),
(3, 'Lucia', 1020509683, 'Justificado', 3, '2024-09-28 00:00:00', 'N/A'),
(4, 'Juan', 1020509684, 'Presente', 4, '2024-09-11 08:02:10', 'N/A'),
(5, 'Farasica', 1020509685, 'Ausente', 4, '2024-09-11 08:02:49', 'N/A'),
(6, 'Nelson', 1020509686, 'Justificado', 4, '2024-09-11 08:03:20', 'N/A'),
(7, 'Carlos', 1020509687, 'Presente', 4, '2024-09-13 08:03:09', 'N/A'),
(8, 'Nelson', 1020509688, 'Ausente', 4, '2024-09-04 08:03:38', 'N/A'),
(9, 'Juan', 1022222221, 'Justificado', 4, '2024-09-04 08:03:49', 'N/A'),
(10, 'Camilo', 1022222222, 'Presente', 4, '2024-09-04 08:04:04', 'N/A'),
(11, 'Lucia', 1022222223, 'Ausente', 3, '2024-09-04 08:04:12', 'N/A'),
(12, 'Juan', 1022222224, 'Justificado', 3, '2024-09-04 08:04:22', 'N/A'),
(14, 'Farasica', 1029143097, 'Presente', 8, '2024-09-29 16:24:23', 'N/A');

-- --------------------------------------------------------


-- tabla curso


CREATE TABLE `curso` (
  `grado` int(11) NOT NULL COMMENT 'nombre o número del curso al que será agregado cada estudiante',
  `salon` varchar(30) NOT NULL COMMENT 'salón asignado a cada curso'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='donde se asigna a cada estudiante a un grupo';


-- datos para la tabla curso


INSERT INTO `curso` (`grado`, `salon`) VALUES
(701, '201'),
(702, '201'),
(703, '201'),
(808, '101'),
(1001, '202'),
(1002, '202'),
(1101, '101'),
(1102, '101'),
(1103, '101');

-- --------------------------------------------------------


-- tabla directorio


CREATE TABLE `directorio` (
  `id_detalle` int(11) NOT NULL COMMENT 'la identificación asignada al detalle del observador',
  `documento` int(11) NOT NULL COMMENT 'Código del estudiante al que le pertenece la información',
  `rh_estudiante` varchar(10) NOT NULL COMMENT 'se registra el tipo de RH o sangre de un estudiante',
  `eps` varchar(30) NOT NULL COMMENT 'se registra la EPS del estudiante',
  `fecha_naci` date NOT NULL COMMENT 'se registra la fecha de nacimiento del estudiante',
  `enfermedades` varchar(50) DEFAULT NULL COMMENT 'se registra si el estudiante tiene alguna enfermedad o no tiene ninguna',
  `nom_acu` varchar(30) NOT NULL COMMENT 'se registra el nombre del acudiente del estudiante',
  `telefono_acu` varchar(30) NOT NULL COMMENT 'se registra el teléfono del acudiente del estudiante',
  `doc_acu` varchar(20) NOT NULL COMMENT 'se registra el número de documento del acudiente'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='donde se registran los detalles específicos del estudiante';


-- datos para la tabla directorio


INSERT INTO `directorio` (`id_detalle`, `documento`, `rh_estudiante`, `eps`, `fecha_naci`, `enfermedades`, `nom_acu`, `telefono_acu`, `doc_acu`) VALUES
(6, 1020509682, 'O+', 'SALUD TOTAL', '2008-02-02', 'N/A', 'JOHN GOMES', '1023460998', '3103933431'),
(7, 1020509686, 'AB+', 'COLSUBSIDIO', '2008-05-03', 'N/A', 'ERICK TORREZ', '1123460998', '3125433598'),
(8, 1022222222, 'A+', 'SANITAS', '2008-12-04', 'N/A', 'JHONATAN LOPEZ', '1023460998', '3508856383'),
(9, 1020509687, 'A-', 'COMPENSAR', '2008-01-05', 'Alsaimer', 'MONICA MARTÍNEZ', '1023460998', '3156783321'),
(10, 1020509683, 'O-', 'SALUD TOTAL', '2008-10-06', 'N/A', 'CATALINA VARGAS', '1023460998', '3402133244'),
(125478889, 1020509686, 'A+', 'Compensar', '2024-09-04', 'no tiene', 'sonia', '11111111', '1234568'),
(125478890, 1029143097, 'A+', 'Compensar', '2006-05-18', 'Alsaimer', 'Ledis Hernandez', '3107654567', '123456754');

-- --------------------------------------------------------

--
--  tabla horario
--

CREATE TABLE `horario` (
  `Idhorario` int(11) NOT NULL COMMENT 'identificación del horario de un curso',
  `IdMat` int(11) NOT NULL COMMENT 'identificación de la materia',
  `grado` int(11) NOT NULL COMMENT 'número del grado',
  `dia` varchar(20) NOT NULL COMMENT 'se especifica el dia de la semana que semana es para saber cuando se ve esa materia',
  `Fecha_inicio` time NOT NULL COMMENT 'fecha de inicio del horario del curso',
  `Fecha_fin` time NOT NULL COMMENT 'fecha de fin del horario de un curso'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='donde se lleva un control de las clases que toma un curso durante el día';


-- datos para la tabla horario


INSERT INTO `horario` (`Idhorario`, `IdMat`, `grado`, `dia`, `Fecha_inicio`, `Fecha_fin`) VALUES
(1, 1, 1101, 'Semana 1, dia 1', '12:30:00', '14:20:00'),
(2, 2, 808, 'Semana 1, dia 1', '12:30:00', '14:20:00'),
(3, 7, 703, 'Semana 1, dia 1', '12:30:00', '14:20:00'),
(4, 8, 703, 'Semana 1, dia1', '14:20:00', '16:40:00');

-- --------------------------------------------------------


-- tabla materia


CREATE TABLE `materia` (
  `idmat` int(11) NOT NULL COMMENT 'identificación de la materia',
  `nomb_mat` varchar(35) NOT NULL COMMENT 'nombre de la materia registrada para el curso'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='donde se registran las clases que los estudiantes toman';


-- tabla materia


INSERT INTO `materia` (`idmat`, `nomb_mat`) VALUES
(1, 'CASTELLANO'),
(2, 'INFORMATICA'),
(3, 'INGLES'),
(4, 'RELIGION'),
(5, 'MATEMATICAS'),
(6, 'FISICA'),
(7, 'EDU.FISICA'),
(8, 'FILOSOFIA'),
(9, 'SOCIALES'),
(10, 'QUIMICA');

-- --------------------------------------------------------


-- tabla observador


CREATE TABLE `observador` (
  `idobservador` int(11) NOT NULL COMMENT 'Código asignado a cada observador',
  `documento` int(11) NOT NULL COMMENT 'conexión de datos del usuario',
  `fecha` date NOT NULL COMMENT 'fecha en la que se registra la observación',
  `descripcion_falta` varchar(100) NOT NULL COMMENT 'es el compromiso que se hace a cada estudiante cada vez que se le hace una observación',
  `compromiso` varchar(55) NOT NULL COMMENT 'firma del estudiante aprobando la observación',
  `firma` varchar(25) DEFAULT NULL COMMENT 'es el seguimiento o control que se le hace a cada instructor dependiendo si tiene mal comportamiento y muchos llamados de atención',
  `seguimiento` varchar(10) NOT NULL COMMENT 'es el seguimiento o control que se le hace a cada instructor dependiendo si tiene mal comportamiento y muchos llamados de atencion',
  `falta` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='donde se realiza un seguimiento al estudiante';


-- datos para la tabla observador


INSERT INTO `observador` (`idobservador`, `documento`, `fecha`, `descripcion_falta`, `compromiso`, `firma`, `seguimiento`, `falta`) VALUES
(1, 1020509681, '2044-06-02', 'NO TRAJO EL UNIFORME', 'NA', 'NA', 'JUAN', 'LEVE'),
(2, 1020509682, '2024-01-05', 'NO TRAJO LA TAREA', 'NA', 'NA', 'PEDRO', 'MEDIA'),
(3, 1020509683, '2024-09-06', 'LLEGO TARDE A CLASE', 'NA', 'NA', 'SARA', 'MEDIA'),
(4, 1020509684, '2024-09-06', 'LLEGO TARDE A CLASE', 'NA', 'NA', 'KAOMI', 'MEDIA'),
(5, 1020509685, '2024-09-06', 'LLEGO TARDE A CLASE', 'NA', 'NA', 'SANDRA', 'GRAVE'),
(40, 1020509687, '2024-09-10', 'se porto mal', 'no lo vuelve hacer', '', 'se le hara', 'grave'),
(101, 1020509687, '2024-09-17', 'Utilizo el celular inadecuadamente', 'NA', NULL, 'sii', 'leve');

-- --------------------------------------------------------


-- tabla rol


CREATE TABLE `rol` (
  `id_rol` int(11) NOT NULL COMMENT 'identificador de cada Rol',
  `nom_rol` varchar(25) NOT NULL COMMENT 'nombre asignado a cada rol'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='donde se asigna el rol que tomará el actor para interactuar con el sistema';


-- datos para la tabla rol


INSERT INTO `rol` (`id_rol`, `nom_rol`) VALUES
(101, 'ESTUDIANTE'),
(102, 'DOCENTE'),
(103, 'ACUDIENTE'),
(104, 'ADMINISTRADOR');

-- --------------------------------------------------------


-- tabla usuario


CREATE TABLE `usuario` (
  `documento` int(11) NOT NULL COMMENT 'número identificador del usuario',
  `id_rol` int(11) NOT NULL COMMENT 'rol asignado a cada usuario',
  `email` varchar(40) NOT NULL COMMENT 'correo registrado por cada usuario',
  `clave` varchar(30) NOT NULL COMMENT 'clave registrada por el usuario',
  `tipo_doc` varchar(10) NOT NULL COMMENT 'tipo de documento del usuario',
  `nombre1` varchar(30) NOT NULL COMMENT 'primer nombre del usuario',
  `nombre2` varchar(30) NOT NULL COMMENT 'segundo nombre del usuario',
  `apellido1` varchar(30) DEFAULT NULL COMMENT 'primer apellido del usuario',
  `apellido2` varchar(30) NOT NULL COMMENT 'segundo apellido del usuario',
  `telefono` varchar(30) NOT NULL COMMENT 'teléfono del usuario',
  `direccion` varchar(30) NOT NULL COMMENT 'dirección del usuario',
  `foto` varchar(255) DEFAULT NULL COMMENT 'foto del usuario',
  `grado` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='donde se registran los datos del usuario que ingresa al sistema';


-- datos para la tabla usuario


INSERT INTO `usuario` (`documento`, `id_rol`, `email`, `clave`, `tipo_doc`, `nombre1`, `nombre2`, `apellido1`, `apellido2`, `telefono`, `direccion`, `foto`, `grado`) VALUES
(1020509681, 101, 'pedro.torrez@example.com', 'password123', 'T.I', 'Pedro', '', 'Torrez', '', '3325678998', 'CR 56 DIG 6', NULL, 703),
(1020509682, 101, 'luis.martinez@example.com', 'password123', 'T.I', 'Luis', '', 'Martínez', '', '3112345678', 'CLL 3 #10', NULL, 1001),
(1020509683, 101, 'claudia.rodriguez@example', 'password123', 'T.I', 'Claudia', '', 'Rodríguez', '', '3147654321', 'AV 7 #40', NULL, 1101),
(1020509684, 101, 'maria.mendoza@example.com', 'password123', 'T.I', 'María', '', 'Mendoza', '', '3123451234', 'AV 9 #55', NULL, 1102),
(1020509685, 102, 'vanessa.ramirez@example.c', 'password123', 'C.C', 'Vanessa', '', 'Ramírez', '', '3109876543', 'CR 8 #70', NULL, 808),
(1020509686, 102, 'daniela.suarez@example.co', 'password123', 'C.C', 'Daniela', '', 'Suárez', '', '3208901234', 'CLL 12 #120', NULL, 1101),
(1020509687, 101, 'alejandro.diaz@example.co', 'password123', 'T.I', 'Alejandro', 'Carlos', 'Díaz', 'Gomez', '3207654321', 'CLL 25 #160', '', 703),
(1020509688, 101, 'natalia.gomez@example.com', 'password123', 'T.I', 'Natalia', '', 'Gómez', '', '3209876543', 'CLL 30 #200', NULL, 1002),
(1022222221, 101, 'laura.garcia@example.com', 'password123', 'T.I', 'Laura', '', 'García', '', '3106543210', 'CR 22 #12', NULL, 1103),
(1022222222, 104, 'hugo.martinez@example.com', 'password123', 'C.C', 'Hugo', '', 'Martínez', '', '3103933431', 'CLL 11 #7 CENTRO', NULL, 1002),
(1022222223, 102, 'javier.cruz@example.com', 'password123', 'C.C', 'Javier', '', 'Cruz', '', '3134567890', 'CLL 5 #20', NULL, 703),
(1022222224, 102, 'natalia.perez@example.com', 'password123', 'C.C', 'Natalia', '', 'Pérez', '', '3198765432', 'KR 5 #50', NULL, 702),
(1022222225, 103, 'julian.hurtado@example.co', 'password123', 'C.C', 'Julián', '', 'Hurtado', '', '3195432109', 'AV 12 #90', NULL, 1001),
(1022222226, 103, 'ricardo.sanchez@example.c', 'password123', 'C.C', 'Ricardo', '', 'Sánchez', '', '3203456789', 'CLL 22 #140', NULL, 702),
(1022222227, 101, 'andrea.patino@example.com', 'password123', 'T.I', 'Andrea', '', 'Patiño', '', '3208765432', 'CLL 28 #180', NULL, 703),
(1023460992, 103, 'sebastian.gomez@example.c', 'password123', 'C.C', 'Sebastián', '', 'Gómez', '', '3109876543', 'CR 50 #190', NULL, 703),
(1023460993, 101, 'catherine.moreno@example.', 'password123', 'T.I', 'Catherine', '', 'Moreno', '', '3106543210', 'CR 40 #150', NULL, 808),
(1029143097, 101, 'sebastiancardenash18@gmail.com', 'Sebas2006', 'C.C', 'Sebastian', '', 'Cardenas', 'Hernandez', '30576454321', 'calle 6', '', 703);


-- TRIGGERS usuario

DELIMITER $$
CREATE TRIGGER `trg_actualizar_rol_documento` AFTER UPDATE ON `usuario` FOR EACH ROW BEGIN
    IF OLD.documento <> NEW.documento THEN
        UPDATE goe_rol
        SET nom_rol = 'Estudiante'
        WHERE id_rol = NEW.id_rol;
    END IF;
END
$$
DELIMITER ;


-- Llave primaria

-- de la tabla asistencia

ALTER TABLE `asistencia`
  ADD PRIMARY KEY (`idasistencia`),
  ADD KEY `FKAsistencia497237` (`documento`),
  ADD KEY `FKasistencia701223` (`IdMat`);

-- Llave primaria

-- de la tabla  curso

ALTER TABLE `curso`
  ADD PRIMARY KEY (`grado`);

-- Llave primaria

-- de la tabla directorio

ALTER TABLE `directorio`
  ADD PRIMARY KEY (`id_detalle`),
  ADD KEY `FKdocumento` (`documento`);

-- Llave primaria y foranea

-- de la tabla horario

ALTER TABLE `horario`
  ADD PRIMARY KEY (`Idhorario`),
  ADD KEY `FKHorario` (`IdMat`),
  ADD KEY `FKHorario` (`grado`);

-- Llave primaria

-- de la tabla materia

ALTER TABLE `materia`
  ADD PRIMARY KEY (`idmat`);

-- Llave primaria y foranea

-- de la tabla observador

ALTER TABLE `observador`
  ADD PRIMARY KEY (`idobservador`),
  ADD KEY `FKObservador` (`documento`);

-- Llave primaria

-- de la tabla rol

ALTER TABLE `rol`
  ADD PRIMARY KEY (`id_rol`);

-- Llave primaria y foranea

-- de la tabla usuario

ALTER TABLE `usuario`
  ADD PRIMARY KEY (`documento`),
  ADD KEY `FKusuario` (`id_rol`),
  ADD KEY `fk_usuario_grado` (`grado`);


-- Descripcion 
-- de la tabla asistencia

ALTER TABLE `asistencia`
  MODIFY `idasistencia` int(11) NOT NULL AUTO_INCREMENT COMMENT 'identificación de las asistencias de un curso', AUTO_INCREMENT=15;

-- Descripcion 
-- de la tabla curso

ALTER TABLE `curso`
  MODIFY `grado` int(11) NOT NULL AUTO_INCREMENT COMMENT 'nombre o número del curso al que será agregado cada estudiante', AUTO_INCREMENT=1104;

-- Descripcion
-- de la tabla directorio

ALTER TABLE `directorio`
  MODIFY `id_detalle` int(11) NOT NULL AUTO_INCREMENT COMMENT 'la identificación asignada al detalle del observador', AUTO_INCREMENT=125478891;

-- Descripcion
-- de la tabla horario

ALTER TABLE `horario`
  MODIFY `Idhorario` int(11) NOT NULL AUTO_INCREMENT COMMENT 'identificación del horario de un curso', AUTO_INCREMENT=5;

-- Descripcion
-- de la tabla materia

ALTER TABLE `materia`
  MODIFY `idmat` int(11) NOT NULL AUTO_INCREMENT COMMENT 'identificación de la materia', AUTO_INCREMENT=11;

-- Descripcion
-- de la tabla observador

ALTER TABLE `observador`
  MODIFY `idobservador` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Código asignado a cada observador', AUTO_INCREMENT=1112;

-- Descripcion
-- de la tabla rol

ALTER TABLE `rol`
  MODIFY `id_rol` int(11) NOT NULL AUTO_INCREMENT COMMENT 'identificador de cada Rol', AUTO_INCREMENT=105;


-- Llaves foraneas

-- para la tabla asistencia

ALTER TABLE `asistencia`
  ADD CONSTRAINT `FKAsistencia` FOREIGN KEY (`documento`) REFERENCES `usuario` (`documento`),
  ADD CONSTRAINT `FKasistencia` FOREIGN KEY (`IdMat`) REFERENCES `materia` (`idmat`);

-- Llaves foraneas
-- para la tabla directorio

ALTER TABLE `directorio`
  ADD CONSTRAINT `FKdocumento` FOREIGN KEY (`documento`) REFERENCES `usuario` (`documento`);

-- Llaves foraneas
-- para la tabla horario

ALTER TABLE `horario`
  ADD CONSTRAINT `FKHorario` FOREIGN KEY (`IdMat`) REFERENCES `materia` (`idmat`),
  ADD CONSTRAINT `FKHorario` FOREIGN KEY (`grado`) REFERENCES `curso` (`grado`);

-- Llaves foraneas
-- para la tabla observador

ALTER TABLE `observador`
  ADD CONSTRAINT `FKObservador` FOREIGN KEY (`documento`) REFERENCES `usuario` (`documento`);

-- Llaves foraneas
-- para la tabla usuario

ALTER TABLE `usuario`
  ADD CONSTRAINT `FKusuario` FOREIGN KEY (`id_rol`) REFERENCES `rol` (`id_rol`),
  ADD CONSTRAINT `fk_usuario_grado` FOREIGN KEY (`grado`) REFERENCES `curso` (`grado`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

---------------------------------------------------------------------------------------------
DELIMITER $$

-- PROCEDIMIENTOS:
-- actualiza contacto del acudiente

CREATE PROCEDURE `actualizar_contacto_acudiente` (IN `p_idDetalle` INT, IN `p_telefono_acu` VARCHAR(30))   BEGIN
    UPDATE directorio
    SET telefono_acu = p_telefono_acu
    WHERE id_detalle = p_idDetalle;
END$$

-- insertar curso

CREATE PROCEDURE `insertar_curso` (IN `p_grado` INT, IN `p_documento` INT, IN `p_salon` VARCHAR(30))   BEGIN
    INSERT INTO curso (grado, documento, salon)
    VALUES (p_grado, p_documento, p_salon);
END$$

-- insertar falta

CREATE PROCEDURE `insertar_falta` (IN `p_documento` INT, IN `p_descripcion` VARCHAR(100), IN `p_compromiso` VARCHAR(55), IN `p_firma` VARCHAR(25), IN `p_seguimiento` VARCHAR(10), IN `p_falta` VARCHAR(255))   BEGIN
    INSERT INTO observador (documento, fecha, descripcion_falta, compromiso, firma, seguimiento, falta)
    VALUES (p_documento, NOW(), p_descripcion, p_compromiso, p_firma, p_seguimiento, p_falta);
END$$

-- registrar asistencia

CREATE PROCEDURE `registrar_asistencia` (IN `p_documento` INT, IN `p_estado_asistencia` VARCHAR(30))   BEGIN
    INSERT INTO asistencia (documento, estado_asis)
    VALUES (p_documento, p_estado_asistencia);
END$$

-- registrar el dellate de asistencia

CREATE PROCEDURE `registrar_detalle_asistencia` (IN `p_idAsistencia` INT, IN `p_idMat` INT, IN `p_fecha_asistencia` DATE, IN `p_justificacion` VARCHAR(35))   BEGIN
    INSERT INTO detalle_asistencia (idAsistencia, idMat, Fecha_asistencia, Justificcion_inasistencia)
    VALUES (p_idAsistencia, p_idMat, p_fecha_asistencia, p_justificacion);
END$$


-- FUNCIONES:

-- Cuenta las faltas de los estudiantes 

CREATE FUNCTION `contar_faltas_estudiante` (`doc` INT) RETURNS INT(11) DETERMINISTIC BEGIN
    SELECT COUNT(documento=doc)
    INTO doc
    FROM observador
    WHERE documento = doc;
    RETURN doc;
END$$

-- muestra el rol de un usuario

CREATE FUNCTION `obtener_rol_usuario` (`documento` INT) RETURNS VARCHAR(25) CHARSET utf8mb4 COLLATE utf8mb4_general_ci  BEGIN
    DECLARE rol VARCHAR(25);
    
    SELECT rol.nom_rol
    INTO rol
    FROM usuario
    JOIN rol ON usuario.id_rol = rol.id_rol
    WHERE usuario.documento = documento;
    RETURN rol;
END$$

-- muestra las enfermedades de un estudiante

CREATE FUNCTION `obtener_enfermedades_estudiante` (`p_doc` INT) RETURNS VARCHAR(50) CHARSET utf8mb4 COLLATE utf8mb4_general_ci DETERMINISTIC BEGIN
    DECLARE e VARCHAR(50);
    
    SELECT directorio.enfermedades
    INTO e
    FROM directorio
    WHERE directorio.documento = p_doc;
    RETURN e;
END$$

-- muestra el nombre completo de un usuario

CREATE FUNCTION `obtener_nombre_completo` (`p_doc` INT) RETURNS VARCHAR(60) CHARSET utf8mb4 COLLATE utf8mb4_general_ci  BEGIN
   DECLARE nombre_completo VARCHAR(60);
    SELECT CONCAT(nombres, ' ', apellidos)
    INTO nombre_completo
    FROM usuario
    WHERE documento = p_doc; 
    RETURN nombre_completo;
END$$

-- cuenta el total de las asistencias de cada materia

CREATE FUNCTION `total_asistencias_materia` (`p_documento` INT, `p_idMat` INT) RETURNS INT(11) DETERMINISTIC BEGIN
    DECLARE total_asistencias INT;
    
    SELECT COUNT(*)
    INTO total_asistencias
    FROM detalle_asistencia
    JOIN asistencia ON detalle_asistencia.idasistencia = asistencia.idasistencia
    WHERE asistencia.documento = p_documento AND detalle_asistencia.idMat = p_idMat;
    
    RETURN total_asistencias;
END$$

DELIMITER ;
