-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: May 21, 2025 at 05:06 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `institucion_educativa`
--

DELIMITER $$
--
-- Functions
--
CREATE DEFINER=`root`@`localhost` FUNCTION `nombre_completo` (`uid` INT) RETURNS VARCHAR(150) CHARSET utf8mb4 COLLATE utf8mb4_general_ci DETERMINISTIC BEGIN
    DECLARE completo VARCHAR(150);
    SELECT CONCAT(nombre, ' ', apellido) INTO completo FROM Usuarios WHERE id_usuario = uid;
    RETURN completo;
END$$

CREATE DEFINER=`root`@`localhost` FUNCTION `obtener_nombre_completo` (`id` INT) RETURNS VARCHAR(511) CHARSET utf8mb4 COLLATE utf8mb4_general_ci DETERMINISTIC BEGIN
  DECLARE nombre_completo VARCHAR(511);
  SELECT CONCAT(nombre, ' ', apellido) INTO nombre_completo
  FROM Usuarios
  WHERE id_usuario = id;
  RETURN nombre_completo;
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `areasinstitucion`
--

CREATE TABLE `areasinstitucion` (
  `id_area` int(11) NOT NULL,
  `nombre_area` varchar(100) DEFAULT NULL,
  `descripcion` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `auditoria`
--

CREATE TABLE `auditoria` (
  `id_auditoria` int(11) NOT NULL,
  `usuario_afectado` int(11) DEFAULT NULL,
  `operacion` enum('INSERT','UPDATE','DELETE') DEFAULT NULL,
  `descripcion` text DEFAULT NULL,
  `fecha_operacion` timestamp NOT NULL DEFAULT current_timestamp(),
  `usuario_realiza_operacion` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `auditoria`
--

INSERT INTO `auditoria` (`id_auditoria`, `usuario_afectado`, `operacion`, `descripcion`, `fecha_operacion`, `usuario_realiza_operacion`) VALUES
(1, 1, 'INSERT', 'Nuevo usuario registrado', '2025-05-19 05:41:17', 1);

-- --------------------------------------------------------

--
-- Table structure for table `cursos`
--

CREATE TABLE `cursos` (
  `id_curso` int(11) NOT NULL,
  `nombre_curso` varchar(100) DEFAULT NULL,
  `id_programa` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `docentecurso`
--

CREATE TABLE `docentecurso` (
  `id_docente_curso` int(11) NOT NULL,
  `id_usuario` int(11) DEFAULT NULL,
  `id_curso` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `equipostecnologicos`
--

CREATE TABLE `equipostecnologicos` (
  `id_equipo` int(11) NOT NULL,
  `nombre_equipo` varchar(100) DEFAULT NULL,
  `marca` varchar(50) DEFAULT NULL,
  `modelo` varchar(50) DEFAULT NULL,
  `numero_serie` varchar(100) DEFAULT NULL,
  `id_area` int(11) DEFAULT NULL,
  `estado` enum('activo','inactivo','dañado') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `eventos`
--

CREATE TABLE `eventos` (
  `id_evento` int(11) NOT NULL,
  `titulo_evento` varchar(100) DEFAULT NULL,
  `descripcion_evento` text DEFAULT NULL,
  `fecha_evento` date DEFAULT NULL,
  `lugar_evento` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `inscripciones`
--

CREATE TABLE `inscripciones` (
  `id_inscripcion` int(11) NOT NULL,
  `id_usuario` int(11) DEFAULT NULL,
  `id_curso` int(11) DEFAULT NULL,
  `fecha_inscripcion` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `notas`
--

CREATE TABLE `notas` (
  `id_nota` int(11) NOT NULL,
  `id_usuario` int(11) DEFAULT NULL,
  `id_curso` int(11) DEFAULT NULL,
  `nota` decimal(5,2) DEFAULT NULL,
  `periodo` enum('I','II','III','IV') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `personaladministrativo`
--

CREATE TABLE `personaladministrativo` (
  `id_personal` int(11) NOT NULL,
  `id_usuario` int(11) DEFAULT NULL,
  `cargo` varchar(100) DEFAULT NULL,
  `dependencia` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `programas`
--

CREATE TABLE `programas` (
  `id_programa` int(11) NOT NULL,
  `nombre_programa` varchar(100) DEFAULT NULL,
  `descripcion` text DEFAULT NULL,
  `nivel` enum('preescolar','primaria','secundaria','media','formacion_complementaria') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `usuarios`
--

CREATE TABLE `usuarios` (
  `id_usuario` int(11) NOT NULL,
  `nombre` varchar(50) DEFAULT NULL,
  `apellido` varchar(50) DEFAULT NULL,
  `documento_identidad` varchar(20) DEFAULT NULL,
  `tipo_usuario` enum('administrador','docente','estudiante') DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `contrasena` varchar(255) DEFAULT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `direccion` varchar(100) DEFAULT NULL,
  `estado` enum('activo','bloqueado') DEFAULT 'activo',
  `intentos_fallidos` int(11) DEFAULT 0,
  `tiempo_bloqueo` datetime DEFAULT NULL,
  `fecha_registro` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `usuarios`
--

INSERT INTO `usuarios` (`id_usuario`, `nombre`, `apellido`, `documento_identidad`, `tipo_usuario`, `email`, `contrasena`, `telefono`, `direccion`, `estado`, `intentos_fallidos`, `tiempo_bloqueo`, `fecha_registro`) VALUES
(1, 'adrian stiven ', 'murillo palacios', '1078457695', 'administrador', 'adrianstivenm@admin.com', '$2y$10$7Of6.poggzdEwRMpv1r9Z.px7UTgfz0yQfphD/PxsunEfteFXiqNa', '3126650806', 'jardin x la 18', 'activo', 0, NULL, '2025-05-19 05:41:17'),
(5, 'Deiner David', 'Bello González', '1077430750', 'estudiante', 'deinerb2.0.0.5@gmail.com', '$2y$10$Gd2Z/T7qBr1.VroxzUzAEOJx3G0e.gCt9r4GO0sn0JdSE3nD1eMiS', '3147813608', 'Carrera 8', 'activo', 0, NULL, '2025-05-21 02:37:25'),
(6, 'Adrian', 'Pancho', '1078457693', 'estudiante', 'pedro@gmail.com', '$2y$10$1vd.puLwWs5fvwIxxd0DI.qPxXBfXQR.Tz./LPNOnA3vBSp1E0Kkq', '', '', 'activo', 0, NULL, '2025-05-21 02:48:24'),
(10, 'Alcachofas', 'News', '1077430759', 'estudiante', 'deinerbello@gmail.com', '$2y$10$CA/9eYG0tH9siNx.5aXg6uApwFpKy53rM/kYkU4CNISazbQLxXeIa', '', '', 'activo', 0, NULL, '2025-05-21 03:01:48'),
(11, 'oswall', 'murillo', '1180370422', 'docente', 'oswallyassir@docente.com', '$2y$10$858cp6TxYJlCOR8c.rqS4uVy1IE0FrNRZnwMT640xuv1qsiHI55Jy', '312654789', 'calle22 mentira18', 'activo', 0, NULL, '2025-05-21 03:04:24');

-- --------------------------------------------------------

--
-- Table structure for table `usuariosbloqueados`
--

CREATE TABLE `usuariosbloqueados` (
  `id_bloqueo` int(11) NOT NULL,
  `id_usuario` int(11) DEFAULT NULL,
  `razon_bloqueo` text DEFAULT NULL,
  `fecha_bloqueo` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Stand-in structure for view `vista_auditoria`
-- (See below for the actual view)
--
CREATE TABLE `vista_auditoria` (
`id_auditoria` int(11)
,`usuario_afectado` int(11)
,`operacion` enum('INSERT','UPDATE','DELETE')
,`descripcion` text
,`fecha_operacion` timestamp
,`usuario_realiza_operacion` int(11)
,`operador` varchar(150)
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `vista_equipos_por_area`
-- (See below for the actual view)
--
CREATE TABLE `vista_equipos_por_area` (
`nombre_area` varchar(100)
,`nombre_equipo` varchar(100)
,`marca` varchar(50)
,`modelo` varchar(50)
,`estado` enum('activo','inactivo','dañado')
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `vista_estudiantes_activos`
-- (See below for the actual view)
--
CREATE TABLE `vista_estudiantes_activos` (
`id_usuario` int(11)
,`nombre` varchar(50)
,`apellido` varchar(50)
,`documento_identidad` varchar(20)
,`tipo_usuario` enum('administrador','docente','estudiante')
,`email` varchar(100)
,`contrasena` varchar(255)
,`telefono` varchar(20)
,`direccion` varchar(100)
,`estado` enum('activo','bloqueado')
,`intentos_fallidos` int(11)
,`tiempo_bloqueo` datetime
,`fecha_registro` timestamp
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `vista_inscripciones`
-- (See below for the actual view)
--
CREATE TABLE `vista_inscripciones` (
`nombre` varchar(50)
,`apellido` varchar(50)
,`nombre_curso` varchar(100)
,`fecha_inscripcion` date
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `vista_usuarios_bloqueados`
-- (See below for the actual view)
--
CREATE TABLE `vista_usuarios_bloqueados` (
`id_bloqueo` int(11)
,`id_usuario` int(11)
,`razon_bloqueo` text
,`fecha_bloqueo` timestamp
,`nombre` varchar(50)
,`apellido` varchar(50)
);

-- --------------------------------------------------------

--
-- Structure for view `vista_auditoria`
--
DROP TABLE IF EXISTS `vista_auditoria`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vista_auditoria`  AS SELECT `a`.`id_auditoria` AS `id_auditoria`, `a`.`usuario_afectado` AS `usuario_afectado`, `a`.`operacion` AS `operacion`, `a`.`descripcion` AS `descripcion`, `a`.`fecha_operacion` AS `fecha_operacion`, `a`.`usuario_realiza_operacion` AS `usuario_realiza_operacion`, `nombre_completo`(`a`.`usuario_realiza_operacion`) AS `operador` FROM `auditoria` AS `a` ;

-- --------------------------------------------------------

--
-- Structure for view `vista_equipos_por_area`
--
DROP TABLE IF EXISTS `vista_equipos_por_area`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vista_equipos_por_area`  AS SELECT `a`.`nombre_area` AS `nombre_area`, `e`.`nombre_equipo` AS `nombre_equipo`, `e`.`marca` AS `marca`, `e`.`modelo` AS `modelo`, `e`.`estado` AS `estado` FROM (`equipostecnologicos` `e` join `areasinstitucion` `a` on(`e`.`id_area` = `a`.`id_area`)) ;

-- --------------------------------------------------------

--
-- Structure for view `vista_estudiantes_activos`
--
DROP TABLE IF EXISTS `vista_estudiantes_activos`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vista_estudiantes_activos`  AS SELECT `usuarios`.`id_usuario` AS `id_usuario`, `usuarios`.`nombre` AS `nombre`, `usuarios`.`apellido` AS `apellido`, `usuarios`.`documento_identidad` AS `documento_identidad`, `usuarios`.`tipo_usuario` AS `tipo_usuario`, `usuarios`.`email` AS `email`, `usuarios`.`contrasena` AS `contrasena`, `usuarios`.`telefono` AS `telefono`, `usuarios`.`direccion` AS `direccion`, `usuarios`.`estado` AS `estado`, `usuarios`.`intentos_fallidos` AS `intentos_fallidos`, `usuarios`.`tiempo_bloqueo` AS `tiempo_bloqueo`, `usuarios`.`fecha_registro` AS `fecha_registro` FROM `usuarios` WHERE `usuarios`.`tipo_usuario` = 'estudiante' AND `usuarios`.`estado` = 'activo' ;

-- --------------------------------------------------------

--
-- Structure for view `vista_inscripciones`
--
DROP TABLE IF EXISTS `vista_inscripciones`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vista_inscripciones`  AS SELECT `u`.`nombre` AS `nombre`, `u`.`apellido` AS `apellido`, `c`.`nombre_curso` AS `nombre_curso`, `i`.`fecha_inscripcion` AS `fecha_inscripcion` FROM ((`inscripciones` `i` join `usuarios` `u` on(`i`.`id_usuario` = `u`.`id_usuario`)) join `cursos` `c` on(`i`.`id_curso` = `c`.`id_curso`)) ;

-- --------------------------------------------------------

--
-- Structure for view `vista_usuarios_bloqueados`
--
DROP TABLE IF EXISTS `vista_usuarios_bloqueados`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vista_usuarios_bloqueados`  AS SELECT `ub`.`id_bloqueo` AS `id_bloqueo`, `ub`.`id_usuario` AS `id_usuario`, `ub`.`razon_bloqueo` AS `razon_bloqueo`, `ub`.`fecha_bloqueo` AS `fecha_bloqueo`, `u`.`nombre` AS `nombre`, `u`.`apellido` AS `apellido` FROM (`usuariosbloqueados` `ub` join `usuarios` `u` on(`ub`.`id_usuario` = `u`.`id_usuario`)) ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `areasinstitucion`
--
ALTER TABLE `areasinstitucion`
  ADD PRIMARY KEY (`id_area`);

--
-- Indexes for table `auditoria`
--
ALTER TABLE `auditoria`
  ADD PRIMARY KEY (`id_auditoria`),
  ADD KEY `usuario_afectado` (`usuario_afectado`),
  ADD KEY `usuario_realiza_operacion` (`usuario_realiza_operacion`);

--
-- Indexes for table `cursos`
--
ALTER TABLE `cursos`
  ADD PRIMARY KEY (`id_curso`),
  ADD KEY `id_programa` (`id_programa`);

--
-- Indexes for table `docentecurso`
--
ALTER TABLE `docentecurso`
  ADD PRIMARY KEY (`id_docente_curso`),
  ADD KEY `id_usuario` (`id_usuario`),
  ADD KEY `id_curso` (`id_curso`);

--
-- Indexes for table `equipostecnologicos`
--
ALTER TABLE `equipostecnologicos`
  ADD PRIMARY KEY (`id_equipo`),
  ADD KEY `id_area` (`id_area`);

--
-- Indexes for table `eventos`
--
ALTER TABLE `eventos`
  ADD PRIMARY KEY (`id_evento`);

--
-- Indexes for table `inscripciones`
--
ALTER TABLE `inscripciones`
  ADD PRIMARY KEY (`id_inscripcion`),
  ADD KEY `id_usuario` (`id_usuario`),
  ADD KEY `id_curso` (`id_curso`);

--
-- Indexes for table `notas`
--
ALTER TABLE `notas`
  ADD PRIMARY KEY (`id_nota`),
  ADD KEY `id_usuario` (`id_usuario`),
  ADD KEY `id_curso` (`id_curso`);

--
-- Indexes for table `personaladministrativo`
--
ALTER TABLE `personaladministrativo`
  ADD PRIMARY KEY (`id_personal`),
  ADD KEY `id_usuario` (`id_usuario`);

--
-- Indexes for table `programas`
--
ALTER TABLE `programas`
  ADD PRIMARY KEY (`id_programa`);

--
-- Indexes for table `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id_usuario`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `usuariosbloqueados`
--
ALTER TABLE `usuariosbloqueados`
  ADD PRIMARY KEY (`id_bloqueo`),
  ADD KEY `id_usuario` (`id_usuario`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `areasinstitucion`
--
ALTER TABLE `areasinstitucion`
  MODIFY `id_area` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `auditoria`
--
ALTER TABLE `auditoria`
  MODIFY `id_auditoria` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `cursos`
--
ALTER TABLE `cursos`
  MODIFY `id_curso` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `docentecurso`
--
ALTER TABLE `docentecurso`
  MODIFY `id_docente_curso` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `equipostecnologicos`
--
ALTER TABLE `equipostecnologicos`
  MODIFY `id_equipo` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `eventos`
--
ALTER TABLE `eventos`
  MODIFY `id_evento` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `inscripciones`
--
ALTER TABLE `inscripciones`
  MODIFY `id_inscripcion` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `notas`
--
ALTER TABLE `notas`
  MODIFY `id_nota` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `personaladministrativo`
--
ALTER TABLE `personaladministrativo`
  MODIFY `id_personal` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `programas`
--
ALTER TABLE `programas`
  MODIFY `id_programa` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id_usuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `usuariosbloqueados`
--
ALTER TABLE `usuariosbloqueados`
  MODIFY `id_bloqueo` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `auditoria`
--
ALTER TABLE `auditoria`
  ADD CONSTRAINT `auditoria_ibfk_1` FOREIGN KEY (`usuario_afectado`) REFERENCES `usuarios` (`id_usuario`),
  ADD CONSTRAINT `auditoria_ibfk_2` FOREIGN KEY (`usuario_realiza_operacion`) REFERENCES `usuarios` (`id_usuario`);

--
-- Constraints for table `cursos`
--
ALTER TABLE `cursos`
  ADD CONSTRAINT `cursos_ibfk_1` FOREIGN KEY (`id_programa`) REFERENCES `programas` (`id_programa`);

--
-- Constraints for table `docentecurso`
--
ALTER TABLE `docentecurso`
  ADD CONSTRAINT `docentecurso_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`),
  ADD CONSTRAINT `docentecurso_ibfk_2` FOREIGN KEY (`id_curso`) REFERENCES `cursos` (`id_curso`);

--
-- Constraints for table `equipostecnologicos`
--
ALTER TABLE `equipostecnologicos`
  ADD CONSTRAINT `equipostecnologicos_ibfk_1` FOREIGN KEY (`id_area`) REFERENCES `areasinstitucion` (`id_area`);

--
-- Constraints for table `inscripciones`
--
ALTER TABLE `inscripciones`
  ADD CONSTRAINT `inscripciones_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`),
  ADD CONSTRAINT `inscripciones_ibfk_2` FOREIGN KEY (`id_curso`) REFERENCES `cursos` (`id_curso`);

--
-- Constraints for table `notas`
--
ALTER TABLE `notas`
  ADD CONSTRAINT `notas_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`),
  ADD CONSTRAINT `notas_ibfk_2` FOREIGN KEY (`id_curso`) REFERENCES `cursos` (`id_curso`);

--
-- Constraints for table `personaladministrativo`
--
ALTER TABLE `personaladministrativo`
  ADD CONSTRAINT `personaladministrativo_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`);

--
-- Constraints for table `usuariosbloqueados`
--
ALTER TABLE `usuariosbloqueados`
  ADD CONSTRAINT `usuariosbloqueados_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`);

DELIMITER $$
--
-- Events
--
CREATE DEFINER=`root`@`localhost` EVENT `desbloquear_usuarios` ON SCHEDULE EVERY 1 MINUTE STARTS '2025-05-05 09:43:24' ON COMPLETION NOT PRESERVE ENABLE DO DELETE FROM UsuariosBloqueados
  WHERE TIMESTAMPDIFF(MINUTE, fecha_bloqueo, NOW()) >= 3$$

DELIMITER ;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
