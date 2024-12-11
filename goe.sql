-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 11-12-2024 a las 18:09:42
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `goe2`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `asistencia`
--

CREATE TABLE `asistencia` (
  `idasistencia` int(11) NOT NULL COMMENT 'identificación de las asistencias de un curso',
  `profesor` varchar(50) NOT NULL COMMENT 'Nombre del profesor que llena la asistencia',
  `documento` int(11) NOT NULL COMMENT 'número identificador del usuario',
  `estado_asis` varchar(30) NOT NULL COMMENT 'estado de la asistencia del estudiante',
  `IdMat` int(11) NOT NULL COMMENT 'identificación de la materia	',
  `fecha_asistencia` datetime DEFAULT NULL COMMENT 'fecha de registro de la asistencia	',
  `justificacion_inasistencia` varchar(250) NOT NULL COMMENT 'justificación de la inasistencia del estudiante	'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='donde se registra la asistencia a clase del estudiante';

--
-- Volcado de datos para la tabla `asistencia`
--

INSERT INTO `asistencia` (`idasistencia`, `profesor`, `documento`, `estado_asis`, `IdMat`, `fecha_asistencia`, `justificacion_inasistencia`) VALUES
(1, '', 1020509681, 'Presente', 3, '2024-09-03 00:00:00', 'N/A'),
(2, '', 1020509682, 'Ausente', 3, '2024-09-12 00:00:00', 'N/A'),
(3, '', 1020509683, 'Justificado', 3, '2024-09-28 00:00:00', 'N/A'),
(4, '', 1020509684, 'Presente', 4, '2024-09-11 08:02:10', 'N/A'),
(5, '', 1020509685, 'Ausente', 4, '2024-09-11 08:02:49', 'N/A'),
(6, '', 1020509686, 'Justificado', 4, '2024-09-11 08:03:20', 'N/A'),
(7, '', 1020509687, 'Presente', 4, '2024-09-13 08:03:09', 'N/A'),
(8, '', 1020509688, 'Ausente', 4, '2024-09-04 08:03:38', 'N/A'),
(9, '', 1022222221, 'Justificado', 4, '2024-09-04 08:03:49', 'N/A'),
(10, '', 1022222222, 'Presente', 4, '2024-09-04 08:04:04', 'N/A'),
(11, '', 1022222223, 'Ausente', 3, '2024-09-04 08:04:12', 'N/A'),
(14, 'Farasica', 1029143097, 'Presente', 8, '2024-09-29 16:24:23', 'N/A'),
(15, 'Luis Eduardo', 1029143097, 'Presente', 8, '2024-12-04 21:19:12', 'N/A'),
(16, 'Luis Eduardo', 1023460993, 'Presente', 8, '2024-12-06 17:27:55', 'N/A');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `curso`
--

CREATE TABLE `curso` (
  `grado` int(11) NOT NULL COMMENT 'nombre o número del curso al que será agregado cada estudiante',
  `salon` varchar(30) NOT NULL COMMENT 'salón asignado a cada curso'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='donde se asigna a cada estudiante a un grupo';

--
-- Volcado de datos para la tabla `curso`
--

INSERT INTO `curso` (`grado`, `salon`) VALUES
(1, 'N/A'),
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

--
-- Estructura de tabla para la tabla `directorio`
--

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

--
-- Volcado de datos para la tabla `directorio`
--

INSERT INTO `directorio` (`id_detalle`, `documento`, `rh_estudiante`, `eps`, `fecha_naci`, `enfermedades`, `nom_acu`, `telefono_acu`, `doc_acu`) VALUES
(6, 1020509682, 'O+', 'SALUD TOTAL', '2008-02-02', 'N/A', 'JOHN GOMES', '1023460998', '3103933431'),
(7, 1020509686, 'AB+', 'COLSUBSIDIO', '2008-05-03', 'N/A', 'ERICK TORREZ', '1123460998', '3125433598'),
(8, 1022222222, 'A+', 'SANITAS', '2008-12-04', 'N/A', 'JHONATAN LOPEZ', '1023460998', '3508856383'),
(9, 1020509687, 'A-', 'COMPENSAR', '2008-01-05', 'Alsaimer', 'MONICA MARTÍNEZ', '1023460998', '3156783321'),
(10, 1020509683, 'O-', 'SALUD TOTAL', '2008-10-06', 'N/A', 'CATALINA VARGAS', '1023460998', '3402133244'),
(125478890, 1029143097, 'A+', 'Compensar', '2006-05-18', 'N/A', 'Ledis Hernandez', '3107654567', '123456754'),
(1029143096, 1029143096, '', '', '0000-00-00', NULL, '', '', '');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `horario`
--

CREATE TABLE `horario` (
  `Idhorario` int(11) NOT NULL COMMENT 'identificación del horario de un curso',
  `IdMat` int(11) NOT NULL COMMENT 'identificación de la materia',
  `grado` int(11) NOT NULL COMMENT 'número del grado',
  `dia` varchar(20) NOT NULL COMMENT 'se especifica el dia de la semana que semana es para saber cuando se ve esa materia',
  `Fecha_inicio` time NOT NULL COMMENT 'fecha de inicio del horario del curso',
  `Fecha_fin` time NOT NULL COMMENT 'fecha de fin del horario de un curso'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='donde se lleva un control de las clases que toma un curso durante el día';

--
-- Volcado de datos para la tabla `horario`
--

INSERT INTO `horario` (`Idhorario`, `IdMat`, `grado`, `dia`, `Fecha_inicio`, `Fecha_fin`) VALUES
(1, 1, 1101, 'Semana 1, dia 1', '12:30:00', '14:20:00'),
(2, 2, 808, 'Semana 1, dia 1', '12:30:00', '14:20:00'),
(3, 7, 703, 'Semana 1, dia 1', '12:30:00', '14:20:00'),
(4, 8, 703, 'Semana 1, dia1', '14:20:00', '16:40:00');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `materia`
--

CREATE TABLE `materia` (
  `idmat` int(11) NOT NULL COMMENT 'identificación de la materia',
  `nomb_mat` varchar(35) NOT NULL COMMENT 'nombre de la materia registrada para el curso'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='donde se registran las clases que los estudiantes toman';

--
-- Volcado de datos para la tabla `materia`
--

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

--
-- Estructura de tabla para la tabla `observador`
--

CREATE TABLE `observador` (
  `idobservador` int(11) NOT NULL COMMENT 'Código asignado a cada observador',
  `documento` int(11) NOT NULL COMMENT 'conexión de datos del usuario',
  `fecha` date NOT NULL COMMENT 'fecha en la que se registra la observación',
  `descripcion_falta` varchar(100) NOT NULL COMMENT 'es el compromiso que se hace a cada estudiante cada vez que se le hace una observación',
  `compromiso` varchar(55) NOT NULL COMMENT 'firma del estudiante aprobando la observación',
  `firma` varchar(25) DEFAULT NULL COMMENT 'es el seguimiento o control que se le hace a cada instructor dependiendo si tiene mal comportamiento y muchos llamados de atención',
  `seguimiento` varchar(30) NOT NULL COMMENT 'es el seguimiento o control que se le hace a cada instructor dependiendo si tiene mal comportamiento y muchos llamados de atencion',
  `falta` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='donde se realiza un seguimiento al estudiante';

--
-- Volcado de datos para la tabla `observador`
--

INSERT INTO `observador` (`idobservador`, `documento`, `fecha`, `descripcion_falta`, `compromiso`, `firma`, `seguimiento`, `falta`) VALUES
(1, 1020509681, '0000-00-00', 'NO TRAJO EL UNIFORME malk', 'Nose', 'Leve', 'Genesis Sanabria', 'Leve'),
(2, 1020509682, '2024-01-05', 'NO TRAJO LA TAREA', 'NA', 'NA', 'PEDRO', 'MEDIA'),
(3, 1020509683, '2024-09-06', 'LLEGO TARDE A CLASE', 'NA', 'NA', 'SARA', 'MEDIA'),
(4, 1020509684, '2024-09-06', 'LLEGO TARDE A CLASE', 'NA', 'NA', 'KAOMI', 'MEDIA'),
(5, 1020509685, '2024-09-06', 'LLEGO TARDE A CLASE', 'NA', 'NA', 'SANDRA', 'GRAVE'),
(40, 1020509687, '2024-09-10', 'se porto mal', 'no lo vuelve hacer', '', 'se le hara', 'grave'),
(111, 1029143097, '2024-12-01', 'Irrespeto a sus companeros', 'Manejar un mejor vocabulario', NULL, 'Genesis Sanabria', 'Leve');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rofesor_materia`
--

CREATE TABLE `rofesor_materia` (
  `id_relacion` int(11) NOT NULL,
  `documento` int(11) NOT NULL,
  `idMat` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rol`
--

CREATE TABLE `rol` (
  `id_rol` int(11) NOT NULL COMMENT 'identificador de cada Rol',
  `nom_rol` varchar(25) NOT NULL COMMENT 'nombre asignado a cada rol'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='donde se asigna el rol que tomará el actor para interactuar con el sistema';

--
-- Volcado de datos para la tabla `rol`
--

INSERT INTO `rol` (`id_rol`, `nom_rol`) VALUES
(101, 'ESTUDIANTE'),
(102, 'DOCENTE'),
(103, 'ACUDIENTE'),
(104, 'ADMINISTRADOR');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario`
--

CREATE TABLE `usuario` (
  `documento` int(11) NOT NULL COMMENT 'número identificador del usuario',
  `id_rol` int(11) NOT NULL COMMENT 'rol asignado a cada usuario',
  `email` varchar(40) NOT NULL COMMENT 'correo registrado por cada usuario',
  `clave` varbinary(150) NOT NULL COMMENT 'clave registrada por el usuario',
  `tipo_doc` varchar(10) NOT NULL COMMENT 'tipo de documento del usuario',
  `nombre1` varchar(30) NOT NULL COMMENT 'primer nombre del usuario',
  `nombre2` varchar(30) DEFAULT NULL COMMENT 'segundo nombre del usuario',
  `apellido1` varchar(30) DEFAULT NULL COMMENT 'primer apellido del usuario',
  `apellido2` varchar(30) DEFAULT NULL COMMENT 'segundo apellido del usuario',
  `telefono` varchar(30) NOT NULL COMMENT 'teléfono del usuario',
  `direccion` varchar(30) NOT NULL COMMENT 'dirección del usuario',
  `foto` varchar(255) DEFAULT NULL COMMENT 'foto del usuario',
  `grado` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='donde se registran los datos del usuario que ingresa al sistema';

--
-- Volcado de datos para la tabla `usuario`
--

INSERT INTO `usuario` (`documento`, `id_rol`, `email`, `clave`, `tipo_doc`, `nombre1`, `nombre2`, `apellido1`, `apellido2`, `telefono`, `direccion`, `foto`, `grado`) VALUES
(2345678, 102, 'juan@gmail.com', 0x7ef8ec6b672fd0665dd20e30bdd52c53, 'C.C', 'Juan ', 'Pablo ', 'Paez ', 'Hernandez', '23456789', 'transversal', NULL, NULL),
(1020509681, 101, 'pedro.torrez@example.com', 0x70617373776f7264313233, 'T.I', 'Pedro', '', 'Torrez', '', '3325678998', 'CR 56 DIG 6', NULL, 703),
(1020509682, 101, 'luis.martinez@example.com', 0x70617373776f7264313233, 'T.I', 'Luis', '', 'Martínez', '', '3112345678', 'CLL 3 #10', NULL, NULL),
(1020509683, 101, 'claudia.rodriguez@example', 0x266ae4a5fad5b76152694e199746600c, 'T.I', 'Claudia', '', 'Rodríguez', '', '3147654321', 'AV 7 #40', NULL, NULL),
(1020509684, 101, 'maria.mendoza@example.com', 0x70617373776f7264313233, 'T.I', 'María', '', 'Mendoza', '', '3123451234', 'AV 9 #55', NULL, NULL),
(1020509685, 102, 'vanessa.ramirez@example.c', 0x70617373776f7264313233, 'C.C', 'Vanessa', '', 'Ramírez', '', '3109876543', 'CR 8 #70', NULL, NULL),
(1020509686, 101, 'daniela.suarez@example.co', 0x70617373776f7264313233, 'C.C', 'Daniela', '', 'Suárez', '', '3208901234', 'CLL 12 #120', NULL, 701),
(1020509687, 101, 'alejandro.diaz@example.co', 0x70617373776f7264313233, 'T.I', 'Alejandro', 'Carlos', 'Díaz', 'Gomez', '3207654321', 'CLL 25 #160', '', 703),
(1020509688, 101, 'natalia.gomez@example.com', 0x70617373776f7264313233, 'T.I', 'Natalia', '', 'Gómez', '', '3209876543', 'CLL 30 #200', NULL, NULL),
(1022222221, 101, 'laura.garcia@example.com', 0x70617373776f7264313233, 'T.I', 'Laura', '', 'García', '', '3106543210', 'CR 22 #12', NULL, NULL),
(1022222222, 104, 'hugo.martinez@example.com', 0x266ae4a5fad5b76152694e199746600c, 'C.C', 'Hugo', '', 'Martínez', '', '3103933431', 'CLL 11 #7 CENTRO', NULL, NULL),
(1022222223, 102, 'javier.cruz@example.com', 0x70617373776f7264313233, 'C.C', 'Javier', '', 'Cruz', '', '3134567890', 'CLL 5 #20', NULL, NULL),
(1022222224, 102, 'natalia.perez@example.com', 0x70617373776f7264313233, 'C.C', 'Natalia', '', 'Pérez', '', '3198765432', 'KR 5 #50', NULL, NULL),
(1022222225, 103, 'julian.hurtado@example.co', 0x70617373776f7264313233, 'C.C', 'Julián', '', 'Hurtado', '', '3195432109', 'AV 12 #90', NULL, NULL),
(1022222226, 103, 'ricardo.sanchez@example.c', 0x70617373776f7264313233, 'C.C', 'Ricardo', '', 'Sánchez', '', '3203456789', 'CLL 22 #140', NULL, NULL),
(1022222227, 101, 'andrea.patino@example.com', 0x70617373776f7264313233, 'T.I', 'Andrea', '', 'Patiño', '', '3208765432', 'CLL 28 #180', NULL, NULL),
(1023460992, 103, 'sebastian.gomez@example.c', 0x70617373776f7264313233, 'C.C', 'Sebastián', '', 'Gómez', '', '3109876543', 'CR 50 #190', NULL, NULL),
(1023460993, 101, 'catherine.moreno@example.', 0x70617373776f7264313233, 'T.I', 'Catherine', '', 'Moreno', '', '3106543210', 'CR 40 #150', NULL, 701),
(1029143096, 101, 'santicardenash@gmail.com', 0x9eec238a06e54f7bc63b2a6c05d6b981, 'C.C', 'SANTIAGO', '', 'CARDENAS', 'HERNANDEZ', '3107525662', 'Direccion', NULL, NULL),
(1029143097, 101, 'sebastiancardenash18@gmail.com', 0x5407498f41e1d4c5e6aa7c4a9440abbb, 'C.C', 'Sebastian', '', 'Cardenas', 'Hernandez', '30576454321', 'calle 6', '', 703),
(1032678992, 101, 'jaimebolanos@gmail.com', 0xff3c7d878e10bdf4bab2ded0d2530ad9, '', 'Jaime', 'Jean Pierre', 'Bolaños', 'Bedoya', '3057648201', 'cll 11 carrera 10', NULL, 703),
(1127342346, 104, 'yenesis@gmail.com', 0xac9d03377f1789686810300b62a8eea3, '', 'Genesis', 'Veronica', 'Sanabria', 'Leon', '21345678', 'Direccion', '', 703),
(2147483647, 102, 'juan@gmail.com', 0x7ef8ec6b672fd0665dd20e30bdd52c53, 'C.C', 'juan', 'pablo', 'paez', 'hernandez', '12345678', 'transversal 16 A este #19-43 S', NULL, 703);

--
-- Disparadores `usuario`
--
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

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `asistencia`
--
ALTER TABLE `asistencia`
  ADD PRIMARY KEY (`idasistencia`),
  ADD KEY `FKAsistencia497237` (`documento`),
  ADD KEY `FKasistencia701223` (`IdMat`);

--
-- Indices de la tabla `curso`
--
ALTER TABLE `curso`
  ADD PRIMARY KEY (`grado`);

--
-- Indices de la tabla `directorio`
--
ALTER TABLE `directorio`
  ADD PRIMARY KEY (`id_detalle`),
  ADD KEY `FKdocumento` (`documento`);

--
-- Indices de la tabla `horario`
--
ALTER TABLE `horario`
  ADD PRIMARY KEY (`Idhorario`),
  ADD KEY `FKHorario205184` (`IdMat`),
  ADD KEY `FKHorario52500` (`grado`);

--
-- Indices de la tabla `materia`
--
ALTER TABLE `materia`
  ADD PRIMARY KEY (`idmat`);

--
-- Indices de la tabla `observador`
--
ALTER TABLE `observador`
  ADD PRIMARY KEY (`idobservador`),
  ADD KEY `FKObservador378615` (`documento`);

--
-- Indices de la tabla `rofesor_materia`
--
ALTER TABLE `rofesor_materia`
  ADD PRIMARY KEY (`id_relacion`),
  ADD KEY `documento` (`documento`),
  ADD KEY `idMat` (`idMat`);

--
-- Indices de la tabla `rol`
--
ALTER TABLE `rol`
  ADD PRIMARY KEY (`id_rol`);

--
-- Indices de la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD PRIMARY KEY (`documento`),
  ADD KEY `FKusuario738911` (`id_rol`),
  ADD KEY `fk_usuario_grado` (`grado`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `asistencia`
--
ALTER TABLE `asistencia`
  MODIFY `idasistencia` int(11) NOT NULL AUTO_INCREMENT COMMENT 'identificación de las asistencias de un curso', AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT de la tabla `curso`
--
ALTER TABLE `curso`
  MODIFY `grado` int(11) NOT NULL AUTO_INCREMENT COMMENT 'nombre o número del curso al que será agregado cada estudiante', AUTO_INCREMENT=1104;

--
-- AUTO_INCREMENT de la tabla `directorio`
--
ALTER TABLE `directorio`
  MODIFY `id_detalle` int(11) NOT NULL AUTO_INCREMENT COMMENT 'la identificación asignada al detalle del observador', AUTO_INCREMENT=1029143097;

--
-- AUTO_INCREMENT de la tabla `horario`
--
ALTER TABLE `horario`
  MODIFY `Idhorario` int(11) NOT NULL AUTO_INCREMENT COMMENT 'identificación del horario de un curso', AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `materia`
--
ALTER TABLE `materia`
  MODIFY `idmat` int(11) NOT NULL AUTO_INCREMENT COMMENT 'identificación de la materia', AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `observador`
--
ALTER TABLE `observador`
  MODIFY `idobservador` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Código asignado a cada observador', AUTO_INCREMENT=112;

--
-- AUTO_INCREMENT de la tabla `rofesor_materia`
--
ALTER TABLE `rofesor_materia`
  MODIFY `id_relacion` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `rol`
--
ALTER TABLE `rol`
  MODIFY `id_rol` int(11) NOT NULL AUTO_INCREMENT COMMENT 'identificador de cada Rol', AUTO_INCREMENT=105;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `asistencia`
--
ALTER TABLE `asistencia`
  ADD CONSTRAINT `FKAsistencia497237` FOREIGN KEY (`documento`) REFERENCES `usuario` (`documento`),
  ADD CONSTRAINT `FKasistencia701223` FOREIGN KEY (`IdMat`) REFERENCES `materia` (`idmat`);

--
-- Filtros para la tabla `directorio`
--
ALTER TABLE `directorio`
  ADD CONSTRAINT `FKdocumento` FOREIGN KEY (`documento`) REFERENCES `usuario` (`documento`);

--
-- Filtros para la tabla `horario`
--
ALTER TABLE `horario`
  ADD CONSTRAINT `FKHorario205184` FOREIGN KEY (`IdMat`) REFERENCES `materia` (`idmat`),
  ADD CONSTRAINT `FKHorario52500` FOREIGN KEY (`grado`) REFERENCES `curso` (`grado`);

--
-- Filtros para la tabla `observador`
--
ALTER TABLE `observador`
  ADD CONSTRAINT `FKObservador378615` FOREIGN KEY (`documento`) REFERENCES `usuario` (`documento`);

--
-- Filtros para la tabla `rofesor_materia`
--
ALTER TABLE `rofesor_materia`
  ADD CONSTRAINT `rofesor_materia_ibfk_1` FOREIGN KEY (`documento`) REFERENCES `usuario` (`documento`),
  ADD CONSTRAINT `rofesor_materia_ibfk_2` FOREIGN KEY (`idMat`) REFERENCES `materia` (`idmat`);

--
-- Filtros para la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD CONSTRAINT `FKusuario738911` FOREIGN KEY (`id_rol`) REFERENCES `rol` (`id_rol`),
  ADD CONSTRAINT `fk_usuario_grado` FOREIGN KEY (`grado`) REFERENCES `curso` (`grado`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
