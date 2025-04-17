-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 19-03-2025 a las 16:41:55
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
-- Base de datos: `goe`
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
  `justificacion_inasistencia` varchar(250) NOT NULL COMMENT 'justificación de la inasistencia del estudiante	',
  `idlistado` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='donde se registra la asistencia a clase del estudiante';

--
-- Volcado de datos para la tabla `asistencia`
--

INSERT INTO `asistencia` (`idasistencia`, `profesor`, `documento`, `estado_asis`, `IdMat`, `fecha_asistencia`, `justificacion_inasistencia`, `idlistado`) VALUES
(21, 'Genesis Sanabria', 1020509681, 'Excusa', 1, '2025-02-07 21:41:00', 'Cita mediaca', 1),
(22, 'Genesis Sanabria', 1020509687, 'Asistio', 1, '2025-02-07 21:41:00', 'N/A', 1),
(23, 'Genesis Sanabria', 1029143097, 'Asistio', 1, '2025-02-07 21:41:00', 'N/A', 1),
(24, 'Genesis Sanabria', 1032678992, 'Asistio', 1, '2025-02-07 21:41:00', 'N/A', 1),
(25, 'Genesis Sanabria', 1020509681, 'Asistio', 4, '2025-02-20 21:14:00', 'N/A', 3),
(26, 'Genesis Sanabria', 1020509687, 'Asistio', 4, '2025-02-20 21:14:00', 'N/A', 3),
(27, 'Genesis Sanabria', 1029143097, 'Asistio', 4, '2025-02-20 21:14:00', 'N/A', 3),
(28, 'Genesis Sanabria', 1032678992, 'Asistio', 4, '2025-02-20 21:14:00', 'N/A', 3),
(29, 'Genesis Sanabria', 1020509686, 'Retardo', 5, '2025-02-24 19:48:00', 'N/A', 4),
(30, 'Genesis Sanabria', 1023460993, 'Asistio', 5, '2025-02-24 19:48:00', 'N/A', 4),
(33, 'Genesis Sanabria', 1020509681, 'Asistio', 2, '2025-02-24 20:02:00', 'N/A', 6),
(34, 'Genesis Sanabria', 1020509687, 'Asistio', 2, '2025-02-24 20:02:00', 'N/A', 6),
(35, 'Genesis Sanabria', 1029143097, 'Asistio', 2, '2025-02-24 20:02:00', 'N/A', 6),
(36, 'Genesis Sanabria', 1032678992, 'Asistio', 2, '2025-02-24 20:02:00', 'N/A', 6),
(37, 'juan paez', 1020509681, 'Retardo', 6, '2025-02-24 21:39:00', 'N/A', 7),
(38, 'juan paez', 1020509687, 'Asistio', 6, '2025-02-24 21:39:00', 'N/A', 7),
(39, 'juan paez', 1029143097, 'Asistio', 6, '2025-02-24 21:39:00', 'N/A', 7),
(40, 'juan paez', 1032678992, 'Asistio', 6, '2025-02-24 21:39:00', 'N/A', 7),
(41, 'juan paez', 1020509681, 'Asistio', 2, '2025-03-09 21:45:00', 'N/A', 8),
(42, 'juan paez', 1020509687, 'Asistio', 2, '2025-03-09 21:45:00', 'N/A', 8),
(43, 'juan paez', 1029143097, 'Asistio', 2, '2025-03-09 21:45:00', 'N/A', 8),
(44, 'juan paez', 1032678992, 'Asistio', 2, '2025-03-09 21:45:00', 'N/A', 8),
(45, 'Genesis Sanabria', 1020509681, 'Asistio', 4, '2025-03-10 07:58:00', 'N/A', 9),
(46, 'Genesis Sanabria', 1020509687, 'Asistio', 4, '2025-03-10 07:58:00', 'N/A', 9),
(47, 'Genesis Sanabria', 1029143097, 'Asistio', 4, '2025-03-10 07:58:00', 'N/A', 9),
(48, 'Genesis Sanabria', 1032678992, 'Asistio', 4, '2025-03-10 07:58:00', 'N/A', 9),
(49, 'juan paez', 1029143098, 'Asistio', 3, '2025-03-18 21:44:00', 'N/A', 10),
(50, 'Genesis Sanabria', 1029143098, 'Falla', 3, '2025-03-18 22:56:00', 'N/A', 11);

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
(601, 'N/A'),
(701, '201'),
(702, '201'),
(703, '201'),
(808, '101'),
(1001, '202'),
(1002, '202'),
(1101, '101'),
(1102, '102'),
(1103, '103');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `curso_materia`
--

CREATE TABLE `curso_materia` (
  `id_relacion` int(11) NOT NULL,
  `grado` int(11) NOT NULL,
  `idmat` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `curso_materia`
--

INSERT INTO `curso_materia` (`id_relacion`, `grado`, `idmat`) VALUES
(1, 1103, 11),
(4, 703, 2);

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
  `doc_acu` varchar(20) NOT NULL COMMENT 'se registra el número de documento del acudiente',
  `email_acu` varchar(50) NOT NULL COMMENT 'Se registra el email del acudiente\r\n'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='donde se registran los detalles específicos del estudiante';

--
-- Volcado de datos para la tabla `directorio`
--

INSERT INTO `directorio` (`id_detalle`, `documento`, `rh_estudiante`, `eps`, `fecha_naci`, `enfermedades`, `nom_acu`, `telefono_acu`, `doc_acu`, `email_acu`) VALUES
(7, 1020509686, 'AB+', 'COLSUBSIDIO', '2008-05-03', 'N/A', 'ERICK TORREZ', '1123460998', '3125433598', ''),
(8, 1022222222, 'A+', 'SANITAS', '2008-12-04', 'N/A', 'JHONATAN LOPEZ', '1023460998', '3508856383', ''),
(9, 1020509687, 'A-', 'COMPENSAR', '2008-01-05', 'Alsaimer', 'MONICA MARTÍNEZ', '1023460998', '3156783321', ''),
(10, 1020509683, 'O-', 'SALUD TOTAL', '2008-10-06', 'N/A', 'CATALINA VARGAS', '1023460998', '3402133244', ''),
(125478890, 1029143097, 'A+', 'Compensar', '2006-05-18', 'N/A', 'Ledis Hernandez', '3107654567', '1234567544', ''),
(1029143096, 1029143096, '', '', '0000-00-00', NULL, '', '', '', ''),
(1029143098, 1029143098, 'A+', 'Compensar', '2006-05-18', '', 'Arfenes Cardenas', '98732732498', '18928460', 'sebastiancawolf@gmail.com'),
(1074088080, 1074088080, '', '', '0000-00-00', NULL, '', '', '', '');

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
-- Estructura de tabla para la tabla `listado`
--

CREATE TABLE `listado` (
  `idlistado` int(11) NOT NULL,
  `trimestre` varchar(4) NOT NULL COMMENT 'Trimestre en el que se registra la asistencia'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `listado`
--

INSERT INTO `listado` (`idlistado`, `trimestre`) VALUES
(1, 'II'),
(3, 'I'),
(4, 'I'),
(6, 'I'),
(7, 'I'),
(8, 'II'),
(9, 'II'),
(10, 'II'),
(11, 'I');

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
(10, 'QUIMICA'),
(11, 'Economia');

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
  `falta` varchar(255) DEFAULT NULL,
  `trimestre` varchar(4) NOT NULL COMMENT 'Trimestre en el que se le registra la observación al estudiante'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='donde se realiza un seguimiento al estudiante';

--
-- Volcado de datos para la tabla `observador`
--

INSERT INTO `observador` (`idobservador`, `documento`, `fecha`, `descripcion_falta`, `compromiso`, `firma`, `seguimiento`, `falta`, `trimestre`) VALUES
(3, 1020509683, '2024-09-06', 'LLEGO TARDE A CLASE', 'NA', 'Regular', 'SARA', 'Regular', 'I'),
(4, 1020509684, '2024-09-06', 'LLEGO TARDE A CLASE', 'NA', 'Regular', 'KAOMI', 'Regular', 'I'),
(5, 1020509685, '2024-09-06', 'LLEGO TARDE A CLASE', 'NA', 'Grave', 'SANDRA', 'Grave', 'I'),
(40, 1020509687, '2024-09-10', 'se porto mal', 'no lo vuelve hacer', 'Grave', 'se le hara', 'Grave', 'I'),
(111, 1029143097, '2024-12-01', 'Irrespeto a sus companeros', 'Manejar un mejor vocabulario', NULL, 'Genesis Sanabria', 'Leve', 'I'),
(115, 1029143098, '2025-03-14', 'Provocó una pelea entre el y su compañero Roger', 'El estudiante se compromete a mejorar su comportamiento', NULL, 'juan paez', 'Regular', 'I');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `profesor_materia`
--

CREATE TABLE `profesor_materia` (
  `id_relacion` int(11) NOT NULL,
  `documento` int(11) NOT NULL,
  `idMat` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `profesor_materia`
--

INSERT INTO `profesor_materia` (`id_relacion`, `documento`, `idMat`) VALUES
(5, 1022222224, 3),
(6, 2147483647, 2),
(7, 2147483647, 3),
(8, 2147483647, 6);

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
  `grado` int(11) DEFAULT NULL,
  `reset_token` varchar(255) DEFAULT NULL,
  `token_expiration` datetime DEFAULT NULL,
  `token_sesion` varchar(255) DEFAULT NULL,
  `activo` tinyint(1) DEFAULT 0,
  `token_activacion` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='donde se registran los datos del usuario que ingresa al sistema';

--
-- Volcado de datos para la tabla `usuario`
--

INSERT INTO `usuario` (`documento`, `id_rol`, `email`, `clave`, `tipo_doc`, `nombre1`, `nombre2`, `apellido1`, `apellido2`, `telefono`, `direccion`, `foto`, `grado`, `reset_token`, `token_expiration`, `token_sesion`, `activo`, `token_activacion`) VALUES
(1020509681, 101, 'pedro.torrez@example.com', 0x70617373776f7264313233, 'T.I', 'Pedro', '', 'Torrez', '', '3325678998', 'CR 56 DIG 6', 'user.png', 703, NULL, NULL, NULL, 0, NULL),
(1020509683, 101, 'claudia.rodriguez@example', 0x266ae4a5fad5b76152694e199746600c, 'T.I', 'Claudia', '', 'Rodríguez', '', '3147654321', 'AV 7 #40', 'user.png', 1001, NULL, NULL, NULL, 0, NULL),
(1020509684, 101, 'maria.mendoza@example.com', 0x70617373776f7264313233, 'T.I', 'María', '', 'Mendoza', '', '3123451234', 'AV 9 #55', 'user.png', NULL, NULL, NULL, NULL, 0, NULL),
(1020509685, 102, 'vanessa.ramirez@example.c', 0x70617373776f7264313233, 'C.C', 'Vanessa', '', 'Ramírez', '', '3109876543', 'CR 8 #70', 'user.png', 601, NULL, NULL, NULL, 0, NULL),
(1020509686, 101, 'daniela.suarez@example.co', 0x70617373776f7264313233, 'C.C', 'Daniela', '', 'Suárez', '', '3208901234', 'CLL 12 #120', 'user.png', 701, NULL, NULL, NULL, 0, NULL),
(1020509687, 101, 'alejandro.diaz@example.co', 0x70617373776f7264313233, 'T.I', 'Alejandro', 'Carlos', 'Díaz', 'Gomez', '3207654321', 'CLL 25 #160', 'user.png', 703, NULL, NULL, NULL, 0, NULL),
(1020509688, 101, 'natalia.gomez@example.com', 0x70617373776f7264313233, 'T.I', 'Natalia', '', 'Gómez', '', '3209876543', 'CLL 30 #200', 'user.png', NULL, NULL, NULL, NULL, 0, NULL),
(1022222221, 101, 'laura.garcia@example.com', 0x70617373776f7264313233, 'T.I', 'Laura', '', 'García', '', '3106543210', 'CR 22 #12', 'user.png', NULL, NULL, NULL, NULL, 0, NULL),
(1022222222, 104, 'sebastiancawolf@gmail.com', 0xb9d27ddf91e62cf9c41fba75629e648d, 'C.C', 'Hugo', '', 'Martínez', '', '3103933431', 'CLL 11 #7 CENTRO', 'user.png', NULL, '0920dd682cbb92d6d4367e764efc8692a31e085e32520ec18db2213b92595c29', '2025-03-14 23:34:07', 'ace7adc3ce24268731f013a4a5adf1da508e1ef27e50f8eda987af8c0e17d156', 1, NULL),
(1022222223, 102, 'javier.cruz@example.com', 0x70617373776f7264313233, 'C.C', 'Javier', '', 'Cruz', '', '3134567890', 'CLL 5 #20', 'user.png', 701, NULL, NULL, NULL, 0, NULL),
(1022222224, 102, 'natalia.perez@example.com', 0x70617373776f7264313233, 'C.C', 'Natalia', '', 'Pérez', '', '3198765432', 'KR 5 #50', 'user.png', 1001, NULL, NULL, NULL, 0, NULL),
(1022222225, 103, 'julian.hurtado@example.co', 0x70617373776f7264313233, 'C.C', 'Julián', '', 'Hurtado', '', '3195432109', 'AV 12 #90', 'user.png', NULL, NULL, NULL, NULL, 0, NULL),
(1022222227, 101, 'andrea.patino@example.com', 0x70617373776f7264313233, 'T.I', 'Andrea', '', 'Patiño', '', '3208765432', 'CLL 28 #180', 'user.png', NULL, NULL, NULL, NULL, 0, NULL),
(1023460992, 103, 'sebastian.gomez@example.c', 0x70617373776f7264313233, 'C.C', 'Sebastián', '', 'Gómez', '', '3109876543', 'CR 50 #190', 'user.png', NULL, NULL, NULL, NULL, 0, NULL),
(1023460993, 101, 'catherine.moreno@example.', 0x70617373776f7264313233, 'T.I', 'Catherine', '', 'Moreno', '', '3106543210', 'CR 40 #150', 'user.png', 701, NULL, NULL, NULL, 0, NULL),
(1029143096, 101, '1029143096@ctjfr.edu.co', 0x9eec238a06e54f7bc63b2a6c05d6b981, 'C.C', 'SANTIAGO', '', 'CARDENAS', 'HERNANDEZ', '3107525662', 'Direccion', 'user.png', 808, NULL, NULL, NULL, 0, 'a7e6824aace4790d0570bde77f267e141de94889e8a5c495782713b6b4d050712a6729d3c69a044ceb54c3bbc6f26783532f'),
(1029143097, 101, 'sebastiancardenash18@gmail.com', 0x5407498f41e1d4c5e6aa7c4a9440abbb, 'C.C', 'Sebastian', '', 'Cardenas', 'Hernandez', '30576454321', 'calle 6', 'sebas_avatar.jpg', 703, 'd3cfe580349075a6a683fffb25da6c616b5a5d7bb41ca9cef529197ec597d51a', '2025-03-15 00:47:34', 'bde60ef86da9b690c0af00cea7d1dac75a7428cdf43a42b216e81c6bab0aeb00', 1, NULL),
(1029143098, 101, '1029143097@ctjfr.edu.co', 0xc55f2b233c52570c2cf7ef30696a5527, 'C.C', 'Santiago', '', 'Cardenas', 'Hernadez', '3107525662', 'Direccion', 'user.png', 1002, NULL, NULL, '54e8cd587abdf0fccc01db08a8a49b2e70df8255c699758eff6c29af063d73b6', 1, NULL),
(1032678992, 101, 'jaimebolanos@gmail.com', 0xff3c7d878e10bdf4bab2ded0d2530ad9, '', 'Jaime', 'Jean Pierre', 'Bolaños', 'Bedoya', '3057648201', 'cll 11 carrera 10', 'user.png', 703, NULL, NULL, NULL, 0, NULL),
(1074088080, 101, '1074088080@ctjfr.edu.co', 0x160106f6c5edfd90025e6725c75dcf36, 'T.I', '', '', '', '', '', '', 'user.png', NULL, NULL, NULL, '5f51ddfe8098ab2a195b9e41be4b22124d4c71122201434b396a8df70179c85e', 1, NULL),
(1127342346, 104, 'yenesis@gmail.com', 0x01219947fb6d0d4a2cce40da60854d64, '', 'Genesis', 'Veronica', 'Sanabria', 'Leon', '21345678', 'Direccion trv', 'user.png', NULL, NULL, NULL, 'c6f2b2565df6f22c9ce14294b8b4460c2e86653c1ba2a447aace986a1a79b1d0', 1, NULL),
(2147483647, 102, 'juan@gmail.com', 0x7ef8ec6b672fd0665dd20e30bdd52c53, 'C.C', 'juan', 'pablo', 'paez', 'hernandez', '12345672', 'transversal 16 A este #19-43 S', 'user.png', 703, NULL, NULL, 'd0dc22ca880f0aab286935e4bb17f445174343cbb61c1688f940c87a2f4d4125', 1, NULL);

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
  ADD KEY `FKasistencia701223` (`IdMat`),
  ADD KEY `idlistado` (`idlistado`);

--
-- Indices de la tabla `curso`
--
ALTER TABLE `curso`
  ADD PRIMARY KEY (`grado`);

--
-- Indices de la tabla `curso_materia`
--
ALTER TABLE `curso_materia`
  ADD PRIMARY KEY (`id_relacion`),
  ADD KEY `id_curso` (`grado`),
  ADD KEY `id_materia` (`idmat`);

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
-- Indices de la tabla `listado`
--
ALTER TABLE `listado`
  ADD PRIMARY KEY (`idlistado`);

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
-- Indices de la tabla `profesor_materia`
--
ALTER TABLE `profesor_materia`
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
  MODIFY `idasistencia` int(11) NOT NULL AUTO_INCREMENT COMMENT 'identificación de las asistencias de un curso', AUTO_INCREMENT=51;

--
-- AUTO_INCREMENT de la tabla `curso`
--
ALTER TABLE `curso`
  MODIFY `grado` int(11) NOT NULL AUTO_INCREMENT COMMENT 'nombre o número del curso al que será agregado cada estudiante', AUTO_INCREMENT=1104;

--
-- AUTO_INCREMENT de la tabla `curso_materia`
--
ALTER TABLE `curso_materia`
  MODIFY `id_relacion` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `directorio`
--
ALTER TABLE `directorio`
  MODIFY `id_detalle` int(11) NOT NULL AUTO_INCREMENT COMMENT 'la identificación asignada al detalle del observador', AUTO_INCREMENT=1074088081;

--
-- AUTO_INCREMENT de la tabla `horario`
--
ALTER TABLE `horario`
  MODIFY `Idhorario` int(11) NOT NULL AUTO_INCREMENT COMMENT 'identificación del horario de un curso', AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `listado`
--
ALTER TABLE `listado`
  MODIFY `idlistado` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT de la tabla `materia`
--
ALTER TABLE `materia`
  MODIFY `idmat` int(11) NOT NULL AUTO_INCREMENT COMMENT 'identificación de la materia', AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT de la tabla `observador`
--
ALTER TABLE `observador`
  MODIFY `idobservador` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Código asignado a cada observador', AUTO_INCREMENT=126;

--
-- AUTO_INCREMENT de la tabla `profesor_materia`
--
ALTER TABLE `profesor_materia`
  MODIFY `id_relacion` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

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
  ADD CONSTRAINT `FKAsistencia497237` FOREIGN KEY (`documento`) REFERENCES `usuario` (`documento`) ON DELETE CASCADE,
  ADD CONSTRAINT `FKasistencia701223` FOREIGN KEY (`IdMat`) REFERENCES `materia` (`idmat`),
  ADD CONSTRAINT `asistencia_ibfk_1` FOREIGN KEY (`idlistado`) REFERENCES `listado` (`idlistado`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `curso_materia`
--
ALTER TABLE `curso_materia`
  ADD CONSTRAINT `curso_materia_ibfk_1` FOREIGN KEY (`grado`) REFERENCES `curso` (`grado`),
  ADD CONSTRAINT `curso_materia_ibfk_2` FOREIGN KEY (`idmat`) REFERENCES `materia` (`idmat`);

--
-- Filtros para la tabla `directorio`
--
ALTER TABLE `directorio`
  ADD CONSTRAINT `FKdocumento` FOREIGN KEY (`documento`) REFERENCES `usuario` (`documento`) ON DELETE CASCADE;

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
  ADD CONSTRAINT `FKObservador378615` FOREIGN KEY (`documento`) REFERENCES `usuario` (`documento`) ON DELETE CASCADE;

--
-- Filtros para la tabla `profesor_materia`
--
ALTER TABLE `profesor_materia`
  ADD CONSTRAINT `profesor_materia_ibfk_1` FOREIGN KEY (`documento`) REFERENCES `usuario` (`documento`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `profesor_materia_ibfk_2` FOREIGN KEY (`idMat`) REFERENCES `materia` (`idmat`) ON DELETE CASCADE ON UPDATE CASCADE;

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
