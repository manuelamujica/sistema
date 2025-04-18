-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 05-04-2025 a las 07:31:11
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `nomina`
--

DELIMITER $$
--
-- Procedimientos
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `buscarformula` (IN `_expresion` VARCHAR(15))   BEGIN
SELECT * FROM formula WHERE expresion = _expresion;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `buscarvariable` (IN `nombre_var` VARCHAR(15))   BEGIN
    SELECT * FROM variables WHERE nombre = nombre_var;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `consultaroperador` ()   BEGIN
SELECT * FROM operadores;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `consultarvar` ()   BEGIN
SELECT cod_var,nombre_var FROM variables WHERE status = 1;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `registrarformula` (IN `_nombre` VARCHAR(15), IN `_expresion` VARCHAR(15), IN `_cod_var` INT, IN `_cod_operador` INT)   BEGIN
INSERT INTO formula(nombre,expresion,cod_var,cod_operador, status) VALUES(_nombre,_expresion,_cod_var,_cod_operador,"1");
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `registrarvar` (IN `_nombre_var` VARCHAR(15))   BEGIN
INSERT INTO variables(nombre_var,status)VALUES(_nombre_var,"1");
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `agenda`
--

CREATE TABLE `agenda` (
  `cod_agenda` int(11) NOT NULL,
  `festivo` tinyint(1) NOT NULL,
  `status` int(3) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `beneficio`
--

CREATE TABLE `beneficio` (
  `cod_beneficio` int(11) NOT NULL,
  `nombre_beneficio` varchar(15) NOT NULL,
  `impacto_salarial` tinyint(1) NOT NULL,
  `descripcion` varchar(50) NOT NULL,
  `monto` float(30,0) NOT NULL,
  `status` int(3) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `bono`
--

CREATE TABLE `bono` (
  `cod_bono` int(11) NOT NULL,
  `descripcion` varchar(30) NOT NULL,
  `monto` float NOT NULL,
  `status` int(3) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cargo`
--

CREATE TABLE `cargo` (
  `cod_cargo` int(11) NOT NULL,
  `nombre_cargo` varchar(15) NOT NULL,
  `status` int(3) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `comprobante`
--

CREATE TABLE `comprobante` (
  `cod_comprobante` int(11) NOT NULL,
  `cod_det_nomina` int(11) NOT NULL,
  `documento` varchar(50) NOT NULL,
  `fecha_creacion` date NOT NULL,
  `descripcion` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `deduccion`
--

CREATE TABLE `deduccion` (
  `cod_deduccion` int(11) NOT NULL,
  `nombre_deduccion` varchar(15) NOT NULL,
  `monto` float(30,0) NOT NULL,
  `descripcion` varchar(50) NOT NULL,
  `fecha` date NOT NULL,
  `status` int(3) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalle_agenda`
--

CREATE TABLE `detalle_agenda` (
  `cod_det_agenda` int(11) NOT NULL,
  `cod_agenda` int(11) NOT NULL,
  `cedula` varchar(20) NOT NULL,
  `cod_turnos` int(11) NOT NULL,
  `fecha` date NOT NULL,
  `horas_trabajadas` int(15) NOT NULL,
  `ausencias` int(3) NOT NULL,
  `permisos` int(3) NOT NULL,
  `descripcion` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalle_nomina`
--

CREATE TABLE `detalle_nomina` (
  `cod_det_nomina` int(11) NOT NULL,
  `cod_nomina` int(11) NOT NULL,
  `cod_salario` int(11) NOT NULL,
  `cedula` varchar(20) NOT NULL,
  `salario_neto` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `formula`
--

CREATE TABLE `formula` (
  `cod_formula` int(11) NOT NULL,
  `cod_var` int(11) NOT NULL,
  `cod_operador` int(11) NOT NULL,
  `nombre` varchar(15) NOT NULL,
  `expresion` varchar(15) NOT NULL,
  `status` int(3) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `nomina`
--

CREATE TABLE `nomina` (
  `cod_nomina` int(11) NOT NULL,
  `cod_tipo_nomina` int(11) NOT NULL,
  `fecha_pago` date NOT NULL,
  `descripcion` varchar(30) NOT NULL,
  `monto_total` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `operadores`
--

CREATE TABLE `operadores` (
  `cod_operador` int(11) NOT NULL,
  `operador` varchar(3) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

--
-- Volcado de datos para la tabla `operadores`
--

INSERT INTO `operadores` (`cod_operador`, `operador`) VALUES
(1, '+'),
(2, '-'),
(3, '*'),
(4, '/'),
(5, '%');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `personal`
--

CREATE TABLE `personal` (
  `cedula` varchar(20) NOT NULL,
  `cod_deduccion` int(11) NOT NULL,
  `cod_cargo` int(11) NOT NULL,
  `cod_beneficio` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `apellido` varchar(50) NOT NULL,
  `correo` varchar(25) NOT NULL,
  `fecha_ingreso` date NOT NULL,
  `status` int(3) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `salario`
--

CREATE TABLE `salario` (
  `cod_salario` int(11) NOT NULL,
  `cedula` varchar(20) NOT NULL,
  `salario_base` float NOT NULL,
  `salario_neto` float NOT NULL,
  `cod_bono` int(3) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `solicitud_contable`
--

CREATE TABLE `solicitud_contable` (
  `cod_solicitud_nomina` int(11) NOT NULL,
  `cod_nomina` int(11) NOT NULL,
  `cod_usuario` int(11) NOT NULL,
  `fecha_creacion` date NOT NULL,
  `status` int(3) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `solicitud_empleado`
--

CREATE TABLE `solicitud_empleado` (
  `cod_solicitud` int(11) NOT NULL,
  `cedula` varchar(20) NOT NULL,
  `descripcion` varchar(50) NOT NULL,
  `fecha_solicitud` date NOT NULL,
  `fecha_respuesta` date NOT NULL,
  `tipo` varchar(20) NOT NULL,
  `status` int(3) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipo_cargo`
--

CREATE TABLE `tipo_cargo` (
  `cod_tipo_cargo` int(11) NOT NULL,
  `cod_cargo` int(11) NOT NULL,
  `nombre_tipo` varchar(15) NOT NULL,
  `status` int(3) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipo_nomina`
--

CREATE TABLE `tipo_nomina` (
  `cod_tipo_nomina` int(11) NOT NULL,
  `cod_formula` int(11) NOT NULL,
  `nombre_t_nomina` varchar(15) NOT NULL,
  `descripcion` varchar(30) NOT NULL,
  `status` int(3) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `turnos`
--

CREATE TABLE `turnos` (
  `cod_turnos` int(11) NOT NULL,
  `hora_entrada` time NOT NULL,
  `hora_salida` time NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `variables`
--

CREATE TABLE `variables` (
  `cod_var` int(11) NOT NULL,
  `nombre_var` varchar(15) NOT NULL,
  `status` int(3) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `agenda`
--
ALTER TABLE `agenda`
  ADD PRIMARY KEY (`cod_agenda`);

--
-- Indices de la tabla `beneficio`
--
ALTER TABLE `beneficio`
  ADD PRIMARY KEY (`cod_beneficio`);

--
-- Indices de la tabla `bono`
--
ALTER TABLE `bono`
  ADD PRIMARY KEY (`cod_bono`);

--
-- Indices de la tabla `cargo`
--
ALTER TABLE `cargo`
  ADD PRIMARY KEY (`cod_cargo`);

--
-- Indices de la tabla `comprobante`
--
ALTER TABLE `comprobante`
  ADD PRIMARY KEY (`cod_comprobante`),
  ADD KEY `cod_det_nomina` (`cod_det_nomina`);

--
-- Indices de la tabla `deduccion`
--
ALTER TABLE `deduccion`
  ADD PRIMARY KEY (`cod_deduccion`);

--
-- Indices de la tabla `detalle_agenda`
--
ALTER TABLE `detalle_agenda`
  ADD PRIMARY KEY (`cod_det_agenda`),
  ADD KEY `cedula` (`cedula`),
  ADD KEY `cod_agenda` (`cod_agenda`),
  ADD KEY `cod_turnos` (`cod_turnos`);

--
-- Indices de la tabla `detalle_nomina`
--
ALTER TABLE `detalle_nomina`
  ADD PRIMARY KEY (`cod_det_nomina`),
  ADD KEY `cedula` (`cedula`),
  ADD KEY `cod_nomina` (`cod_nomina`),
  ADD KEY `cod_salario` (`cod_salario`);

--
-- Indices de la tabla `formula`
--
ALTER TABLE `formula`
  ADD PRIMARY KEY (`cod_formula`),
  ADD KEY `cod_var` (`cod_var`),
  ADD KEY `cod_operador` (`cod_operador`);

--
-- Indices de la tabla `nomina`
--
ALTER TABLE `nomina`
  ADD PRIMARY KEY (`cod_nomina`),
  ADD KEY `cod_tipo_nomina` (`cod_tipo_nomina`);

--
-- Indices de la tabla `operadores`
--
ALTER TABLE `operadores`
  ADD PRIMARY KEY (`cod_operador`);

--
-- Indices de la tabla `personal`
--
ALTER TABLE `personal`
  ADD PRIMARY KEY (`cedula`),
  ADD KEY `cod_beneficio` (`cod_beneficio`),
  ADD KEY `cod_cargo` (`cod_cargo`),
  ADD KEY `cod_deduccion` (`cod_deduccion`);

--
-- Indices de la tabla `salario`
--
ALTER TABLE `salario`
  ADD PRIMARY KEY (`cod_salario`),
  ADD KEY `cod_bono` (`cod_bono`),
  ADD KEY `cedula` (`cedula`);

--
-- Indices de la tabla `solicitud_contable`
--
ALTER TABLE `solicitud_contable`
  ADD PRIMARY KEY (`cod_solicitud_nomina`),
  ADD KEY `cod_nomina` (`cod_nomina`),
  ADD KEY `cod_usuario` (`cod_usuario`);

--
-- Indices de la tabla `solicitud_empleado`
--
ALTER TABLE `solicitud_empleado`
  ADD PRIMARY KEY (`cod_solicitud`),
  ADD KEY `cedula` (`cedula`);

--
-- Indices de la tabla `tipo_cargo`
--
ALTER TABLE `tipo_cargo`
  ADD PRIMARY KEY (`cod_tipo_cargo`),
  ADD KEY `cod_cargo` (`cod_cargo`);

--
-- Indices de la tabla `tipo_nomina`
--
ALTER TABLE `tipo_nomina`
  ADD PRIMARY KEY (`cod_tipo_nomina`),
  ADD KEY `cod_formula` (`cod_formula`);

--
-- Indices de la tabla `turnos`
--
ALTER TABLE `turnos`
  ADD PRIMARY KEY (`cod_turnos`);

--
-- Indices de la tabla `variables`
--
ALTER TABLE `variables`
  ADD PRIMARY KEY (`cod_var`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `agenda`
--
ALTER TABLE `agenda`
  MODIFY `cod_agenda` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `beneficio`
--
ALTER TABLE `beneficio`
  MODIFY `cod_beneficio` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `bono`
--
ALTER TABLE `bono`
  MODIFY `cod_bono` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `cargo`
--
ALTER TABLE `cargo`
  MODIFY `cod_cargo` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `comprobante`
--
ALTER TABLE `comprobante`
  MODIFY `cod_comprobante` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `deduccion`
--
ALTER TABLE `deduccion`
  MODIFY `cod_deduccion` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `detalle_agenda`
--
ALTER TABLE `detalle_agenda`
  MODIFY `cod_det_agenda` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `detalle_nomina`
--
ALTER TABLE `detalle_nomina`
  MODIFY `cod_det_nomina` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `formula`
--
ALTER TABLE `formula`
  MODIFY `cod_formula` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `nomina`
--
ALTER TABLE `nomina`
  MODIFY `cod_nomina` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `operadores`
--
ALTER TABLE `operadores`
  MODIFY `cod_operador` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `salario`
--
ALTER TABLE `salario`
  MODIFY `cod_salario` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `solicitud_contable`
--
ALTER TABLE `solicitud_contable`
  MODIFY `cod_solicitud_nomina` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `solicitud_empleado`
--
ALTER TABLE `solicitud_empleado`
  MODIFY `cod_solicitud` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `tipo_cargo`
--
ALTER TABLE `tipo_cargo`
  MODIFY `cod_tipo_cargo` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `tipo_nomina`
--
ALTER TABLE `tipo_nomina`
  MODIFY `cod_tipo_nomina` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `turnos`
--
ALTER TABLE `turnos`
  MODIFY `cod_turnos` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `variables`
--
ALTER TABLE `variables`
  MODIFY `cod_var` int(11) NOT NULL AUTO_INCREMENT;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `comprobante`
--
ALTER TABLE `comprobante`
  ADD CONSTRAINT `comprobante_ibfk_1` FOREIGN KEY (`cod_det_nomina`) REFERENCES `detalle_nomina` (`cod_det_nomina`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `detalle_agenda`
--
ALTER TABLE `detalle_agenda`
  ADD CONSTRAINT `detalle_agenda_ibfk_1` FOREIGN KEY (`cedula`) REFERENCES `personal` (`cedula`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `detalle_agenda_ibfk_2` FOREIGN KEY (`cod_agenda`) REFERENCES `agenda` (`cod_agenda`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `detalle_agenda_ibfk_3` FOREIGN KEY (`cod_turnos`) REFERENCES `turnos` (`cod_turnos`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `detalle_nomina`
--
ALTER TABLE `detalle_nomina`
  ADD CONSTRAINT `detalle_nomina_ibfk_2` FOREIGN KEY (`cod_nomina`) REFERENCES `nomina` (`cod_nomina`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `detalle_nomina_ibfk_4` FOREIGN KEY (`cod_salario`) REFERENCES `salario` (`cod_salario`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `formula`
--
ALTER TABLE `formula`
  ADD CONSTRAINT `formula_ibfk_1` FOREIGN KEY (`cod_var`) REFERENCES `variables` (`cod_var`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `formula_ibfk_2` FOREIGN KEY (`cod_operador`) REFERENCES `operadores` (`cod_operador`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `nomina`
--
ALTER TABLE `nomina`
  ADD CONSTRAINT `nomina_ibfk_1` FOREIGN KEY (`cod_tipo_nomina`) REFERENCES `tipo_nomina` (`cod_tipo_nomina`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `personal`
--
ALTER TABLE `personal`
  ADD CONSTRAINT `personal_ibfk_2` FOREIGN KEY (`cod_beneficio`) REFERENCES `beneficio` (`cod_beneficio`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `personal_ibfk_3` FOREIGN KEY (`cod_cargo`) REFERENCES `cargo` (`cod_cargo`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `personal_ibfk_4` FOREIGN KEY (`cod_deduccion`) REFERENCES `deduccion` (`cod_deduccion`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `salario`
--
ALTER TABLE `salario`
  ADD CONSTRAINT `salario_ibfk_1` FOREIGN KEY (`cod_bono`) REFERENCES `bono` (`cod_bono`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `salario_ibfk_2` FOREIGN KEY (`cedula`) REFERENCES `personal` (`cedula`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `solicitud_contable`
--
ALTER TABLE `solicitud_contable`
  ADD CONSTRAINT `solicitud_contable_ibfk_1` FOREIGN KEY (`cod_nomina`) REFERENCES `nomina` (`cod_nomina`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `solicitud_contable_ibfk_2` FOREIGN KEY (`cod_usuario`) REFERENCES `savyc4`.`usuarios` (`cod_usuario`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `solicitud_empleado`
--
ALTER TABLE `solicitud_empleado`
  ADD CONSTRAINT `solicitud_empleado_ibfk_1` FOREIGN KEY (`cedula`) REFERENCES `personal` (`cedula`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `tipo_cargo`
--
ALTER TABLE `tipo_cargo`
  ADD CONSTRAINT `tipo_cargo_ibfk_1` FOREIGN KEY (`cod_cargo`) REFERENCES `cargo` (`cod_cargo`);

--
-- Filtros para la tabla `tipo_nomina`
--
ALTER TABLE `tipo_nomina`
  ADD CONSTRAINT `tipo_nomina_ibfk_2` FOREIGN KEY (`cod_formula`) REFERENCES `formula` (`cod_formula`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
