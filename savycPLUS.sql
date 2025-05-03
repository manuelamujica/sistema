-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 03-05-2025 a las 03:52:35
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
-- Base de datos: `savyc+v1`
--

DELIMITER $$
--
-- Procedimientos
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `consultar_cuentas_contables` ()   BEGIN
WITH RECURSIVE CuentasRecursivas AS (
    -- Caso Base: Selecciona las cuentas de nivel 1 (que no tienen padre)
    SELECT 
        id_cuenta, 
        codigo_contable, 
        nombre_cuenta, 
        naturaleza, 
        cuenta_padreid, 
        nivel,
        1 AS profundidad -- Indica la profundidad en la jerarquía
    FROM cuentas_contables
    WHERE cuenta_padreid IS NULL

    UNION ALL

    -- Recursión: Une las cuentas hijas con sus padres
    SELECT 
        c.id_cuenta, 
        c.codigo_contable, 
        c.nombre_cuenta,
    	c.naturaleza,  
        c.cuenta_padreid, 
        c.nivel,
        cr.profundidad + 1 AS profundidad
    FROM cuentas_contables c
    INNER JOIN CuentasRecursivas cr ON c.cuenta_padreid = cr.id_cuenta
)
SELECT * FROM CuentasRecursivas ORDER BY codigo_contable;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `insertar_cuenta_contable` (IN `p_codigo_contable` VARCHAR(20), IN `p_nombre_cuenta` VARCHAR(100), IN `p_naturaleza` ENUM('deudora','acreedora'), IN `p_cuenta_padre_id` INT, IN `p_nivel` INT, IN `p_status` INT)   BEGIN
    DECLARE v_padre_nivel INT;
    DECLARE v_padre_naturaleza ENUM('deudora', 'acreedora');

    -- Verificar si la cuenta padre existe y obtener su nivel y naturaleza
    IF p_cuenta_padre_id IS NOT NULL THEN
        SELECT nivel, naturaleza 
        INTO v_padre_nivel, v_padre_naturaleza
        FROM cuentas_contables
        WHERE id_cuenta = p_cuenta_padre_id;

        -- Validar que el nivel de la nueva cuenta sea mayor que el de su padre
        IF p_nivel <= v_padre_nivel THEN
            SIGNAL SQLSTATE '45000'
            SET MESSAGE_TEXT = 'El nivel de la cuenta debe ser mayor que el de la cuenta padre.';
        END IF;

        -- Validar que la naturaleza sea igual a la de la cuenta padre
        IF p_naturaleza != v_padre_naturaleza THEN
            SIGNAL SQLSTATE '45000'
            SET MESSAGE_TEXT = 'La naturaleza de la cuenta hija debe ser igual a la de su cuenta padre.';
        END IF;
    END IF;

    -- Insertar la nueva cuenta contable
    INSERT INTO cuentas_contables (
        codigo_contable, 
        nombre_cuenta, 
        naturaleza, 
        cuenta_padreid, 
        nivel, 
        status
    ) VALUES (
        p_codigo_contable, 
        p_nombre_cuenta, 
        p_naturaleza, 
        p_cuenta_padre_id, 
        p_nivel, 
        p_status
    );
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `analisis_rentabilidad`
--

CREATE TABLE `analisis_rentabilidad` (
  `cod_analisis` int(11) NOT NULL,
  `cod_detallep` int(11) DEFAULT NULL,
  `fecha_inicio` date NOT NULL,
  `fecha_fin` date NOT NULL,
  `ventas_totales` decimal(12,2) NOT NULL,
  `costo_ventas` decimal(12,2) NOT NULL,
  `gastos` decimal(12,2) NOT NULL,
  `margen_bruto` decimal(10,2) DEFAULT NULL,
  `notas` text DEFAULT NULL,
  `cod_usuario` int(11) DEFAULT NULL,
  `fecha_calculo` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `asientos_contables`
--

CREATE TABLE `asientos_contables` (
  `cod_asiento` int(11) NOT NULL,
  `cod_mov` int(11) NOT NULL,
  `fecha` date NOT NULL,
  `descripcion` varchar(50) NOT NULL,
  `total_debe` decimal(18,2) NOT NULL,
  `total_haber` decimal(18,2) NOT NULL,
  `status` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish2_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `banco`
--

CREATE TABLE `banco` (
  `cod_banco` int(11) NOT NULL,
  `nombre_banco` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `banco`
--

INSERT INTO `banco` (`cod_banco`, `nombre_banco`) VALUES
(1, 'Banco Provincial');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `caja`
--

CREATE TABLE `caja` (
  `cod_caja` int(11) NOT NULL,
  `nombre` varchar(20) NOT NULL,
  `saldo` decimal(10,2) NOT NULL,
  `cod_divisas` int(11) NOT NULL,
  `status` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cambio_divisa`
--

CREATE TABLE `cambio_divisa` (
  `cod_cambio` int(11) NOT NULL,
  `cod_divisa` int(11) NOT NULL,
  `tasa` decimal(10,2) NOT NULL,
  `fecha` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

--
-- Volcado de datos para la tabla `cambio_divisa`
--

INSERT INTO `cambio_divisa` (`cod_cambio`, `cod_divisa`, `tasa`, `fecha`) VALUES
(1, 1, 1.00, '0000-00-00'),
(2, 2, 67.10, '2025-03-17'),
(3, 2, 67.10, '2025-03-12'),
(4, 2, 67.10, '2025-03-11'),
(6, 2, 65.64, '2025-03-18'),
(7, 2, 64.80, '2025-03-10'),
(8, 2, 63.43, '2025-03-09'),
(9, 2, 67.63, '2025-03-19'),
(10, 2, 70.59, '2025-04-03'),
(11, 3, 10.00, '2025-04-07'),
(12, 2, 86.11, '2025-04-25'),
(13, 3, 95.00, '2025-04-07'),
(14, 4, 115.00, '2025-04-28'),
(15, 5, 105.00, '2025-04-29'),
(16, 2, 86.85, '2025-04-30'),
(17, 3, 10.00, '2025-04-07'),
(18, 3, 95.00, '2025-04-07'),
(19, 4, 115.00, '2025-04-28'),
(20, 5, 105.00, '2025-04-29');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `carga`
--

CREATE TABLE `carga` (
  `cod_carga` int(11) NOT NULL,
  `fecha` datetime NOT NULL,
  `descripcion` varchar(100) NOT NULL,
  `status` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

--
-- Volcado de datos para la tabla `carga`
--

INSERT INTO `carga` (`cod_carga`, `fecha`, `descripcion`, `status`) VALUES
(1, '2025-04-29 22:20:42', 'carga prueba', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categorias`
--

CREATE TABLE `categorias` (
  `cod_categoria` int(11) NOT NULL,
  `nombre` varchar(30) NOT NULL,
  `status` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

--
-- Volcado de datos para la tabla `categorias`
--

INSERT INTO `categorias` (`cod_categoria`, `nombre`, `status`) VALUES
(1, 'queso', 1),
(2, 'Jamones', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categoria_gasto`
--

CREATE TABLE `categoria_gasto` (
  `cod_cat_gasto` int(11) NOT NULL,
  `cod_tipo_gasto` int(11) NOT NULL,
  `cod_frecuencia` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `fecha` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `clientes`
--

CREATE TABLE `clientes` (
  `cod_cliente` int(11) NOT NULL,
  `nombre` varchar(80) NOT NULL,
  `apellido` varchar(80) NOT NULL,
  `cedula_rif` varchar(12) NOT NULL,
  `telefono` varchar(12) DEFAULT NULL,
  `email` varchar(70) DEFAULT NULL,
  `direccion` varchar(200) DEFAULT NULL,
  `status` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

--
-- Volcado de datos para la tabla `clientes`
--

INSERT INTO `clientes` (`cod_cliente`, `nombre`, `apellido`, `cedula_rif`, `telefono`, `email`, `direccion`, `status`) VALUES
(1, 'generico', 'perez', '12345678', '', '', '', 1),
(2, 'daniel', 'rojas', '26779660', '04245645108', 'danielrojas1901@gmail.com', 'av.florencio jimenez, parque residencial araguaney', 1),
(3, 'Manuela', 'Mujica', '28516209', '12453145', 'manuelaalejandra.mujica@gmail.com', 'asdasda', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `compras`
--

CREATE TABLE `compras` (
  `cod_compra` int(11) NOT NULL,
  `cod_prov` int(11) NOT NULL,
  `condicion_pago` enum('contado','credito') NOT NULL,
  `fecha_vencimiento` date DEFAULT NULL,
  `subtotal` decimal(10,2) NOT NULL,
  `total` decimal(10,2) NOT NULL,
  `impuesto_total` decimal(10,2) NOT NULL,
  `fecha` date NOT NULL,
  `descuento` int(11) DEFAULT NULL,
  `status` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

--
-- Volcado de datos para la tabla `compras`
--

INSERT INTO `compras` (`cod_compra`, `cod_prov`, `condicion_pago`, `fecha_vencimiento`, `subtotal`, `total`, `impuesto_total`, `fecha`, `descuento`, `status`) VALUES
(5, 1, 'contado', NULL, 55.00, 55.00, 0.00, '2025-04-29', NULL, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `conciliacion`
--

CREATE TABLE `conciliacion` (
  `cod_conciliacion` int(11) NOT NULL,
  `url` varchar(200) NOT NULL,
  `fecha` datetime NOT NULL,
  `cod_cuenta_bancaria` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `control`
--

CREATE TABLE `control` (
  `cod_control` int(11) NOT NULL,
  `fecha_apertura` datetime NOT NULL,
  `fecha_cierre` datetime NOT NULL,
  `monto_apertura` decimal(10,2) NOT NULL,
  `monto_cierre` decimal(10,2) NOT NULL,
  `cod_caja` int(11) NOT NULL,
  `status` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cuentas_contables`
--

CREATE TABLE `cuentas_contables` (
  `cod_cuenta` int(11) NOT NULL,
  `codigo_contable` varchar(20) NOT NULL,
  `nombre_cuenta` varchar(100) NOT NULL,
  `naturaleza` enum('deudora','acreedora') NOT NULL,
  `cuenta_padreid` int(11) DEFAULT NULL,
  `nivel` int(11) NOT NULL,
  `status` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish2_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cuenta_bancaria`
--

CREATE TABLE `cuenta_bancaria` (
  `cod_cuenta_bancaria` int(11) NOT NULL,
  `cod_banco` int(11) NOT NULL,
  `cod_tipo_cuenta` int(11) NOT NULL,
  `numero_cuenta` varchar(20) NOT NULL,
  `saldo` decimal(10,2) NOT NULL,
  `cod_divisa` int(11) NOT NULL,
  `status` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `descarga`
--

CREATE TABLE `descarga` (
  `cod_descarga` int(11) NOT NULL,
  `fecha` datetime NOT NULL,
  `descripcion` varchar(100) NOT NULL,
  `status` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

--
-- Volcado de datos para la tabla `descarga`
--

INSERT INTO `descarga` (`cod_descarga`, `fecha`, `descripcion`, `status`) VALUES
(1, '2025-04-29 23:49:15', 'ajuste stock prueba', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalle_asientos`
--

CREATE TABLE `detalle_asientos` (
  `cod_det_asiento` int(11) NOT NULL,
  `cod_asiento` int(11) NOT NULL,
  `cod_cuenta` int(11) NOT NULL,
  `monto` decimal(18,2) NOT NULL,
  `tipo` enum('Debe','Haber') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish2_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalle_carga`
--

CREATE TABLE `detalle_carga` (
  `cod_det_carga` int(11) NOT NULL,
  `cod_detallep` int(11) NOT NULL,
  `cod_carga` int(11) NOT NULL,
  `cantidad` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

--
-- Volcado de datos para la tabla `detalle_carga`
--

INSERT INTO `detalle_carga` (`cod_det_carga`, `cod_detallep`, `cod_carga`, `cantidad`) VALUES
(1, 5, 1, 6);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalle_compras`
--

CREATE TABLE `detalle_compras` (
  `cod_detallec` int(11) NOT NULL,
  `cod_compra` int(11) NOT NULL,
  `cod_detallep` int(11) NOT NULL,
  `cantidad` decimal(10,2) NOT NULL,
  `monto` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

--
-- Volcado de datos para la tabla `detalle_compras`
--

INSERT INTO `detalle_compras` (`cod_detallec`, `cod_compra`, `cod_detallep`, `cantidad`, `monto`) VALUES
(5, 5, 6, 5.00, 11.00);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalle_descarga`
--

CREATE TABLE `detalle_descarga` (
  `cod_det_descarga` int(11) NOT NULL,
  `cod_detallep` int(11) NOT NULL,
  `cod_descarga` int(11) NOT NULL,
  `cantidad` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

--
-- Volcado de datos para la tabla `detalle_descarga`
--

INSERT INTO `detalle_descarga` (`cod_det_descarga`, `cod_detallep`, `cod_descarga`, `cantidad`) VALUES
(1, 5, 1, 0.2);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalle_operacion`
--

CREATE TABLE `detalle_operacion` (
  `cod_detalle_op` int(11) NOT NULL,
  `detalle_operacion` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalle_pago_emitido`
--

CREATE TABLE `detalle_pago_emitido` (
  `cod_detallepagoe` int(11) NOT NULL,
  `cod_pago_emitido` int(11) NOT NULL,
  `cod_tipo_pagoe` int(11) NOT NULL,
  `monto` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish2_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalle_pago_recibido`
--

CREATE TABLE `detalle_pago_recibido` (
  `cod_detallepago` int(11) NOT NULL,
  `cod_pago` int(11) NOT NULL,
  `cod_tipo_pago` int(11) NOT NULL,
  `monto` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalle_productos`
--

CREATE TABLE `detalle_productos` (
  `cod_detallep` int(11) NOT NULL,
  `cod_presentacion` int(11) NOT NULL,
  `stock` float NOT NULL,
  `fecha_vencimiento` date DEFAULT NULL,
  `lote` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

--
-- Volcado de datos para la tabla `detalle_productos`
--

INSERT INTO `detalle_productos` (`cod_detallep`, `cod_presentacion`, `stock`, `fecha_vencimiento`, `lote`) VALUES
(1, 1, 0, '0000-00-00', ''),
(2, 1, 8, '0000-00-00', ''),
(3, 1, 67, '0000-00-00', ''),
(4, 1, 23.5, '0000-00-00', ''),
(5, 2, 5.478, '2025-04-29', '26-12'),
(6, 2, 5, '2025-08-21', '');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalle_tipo_pago`
--

CREATE TABLE `detalle_tipo_pago` (
  `cod_tipo_pago` int(11) NOT NULL,
  `cod_metodo` int(11) NOT NULL,
  `tipo_moneda` enum('efectivo','digital','','') NOT NULL,
  `cod_cuenta_bancaria` int(11) DEFAULT NULL,
  `cod_caja` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish2_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalle_ventas`
--

CREATE TABLE `detalle_ventas` (
  `cod_detallev` int(11) NOT NULL,
  `cod_venta` int(11) NOT NULL,
  `cod_detallep` int(11) NOT NULL,
  `importe` decimal(10,2) NOT NULL,
  `cantidad` float(10,3) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

--
-- Volcado de datos para la tabla `detalle_ventas`
--

INSERT INTO `detalle_ventas` (`cod_detallev`, `cod_venta`, `cod_detallep`, `importe`, `cantidad`) VALUES
(1, 7, 1, 70.00, 10.000),
(2, 7, 2, 28.00, 4.000),
(3, 8, 5, 4.31, 0.322);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalle_vueltoe`
--

CREATE TABLE `detalle_vueltoe` (
  `cod_detallev` int(11) NOT NULL,
  `cod_vuelto` int(11) NOT NULL,
  `cod_tipo_pago` int(11) NOT NULL,
  `monto` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish2_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalle_vueltor`
--

CREATE TABLE `detalle_vueltor` (
  `cod_detallev_r` int(11) NOT NULL,
  `cod_vuelto_r` int(11) NOT NULL,
  `cod_tipo_pago` int(11) NOT NULL,
  `monto` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `divisas`
--

CREATE TABLE `divisas` (
  `cod_divisa` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `abreviatura` varchar(5) NOT NULL,
  `status` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

--
-- Volcado de datos para la tabla `divisas`
--

INSERT INTO `divisas` (`cod_divisa`, `nombre`, `abreviatura`, `status`) VALUES
(1, 'Bolívares', 'Bs', 1),
(2, 'Dolares', '$', 1),
(3, '', 'EUR', 1),
(4, 'Binances', 'USDT', 1),
(5, 'libra', 'Lb', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `empresa`
--

CREATE TABLE `empresa` (
  `rif` varchar(15) CHARACTER SET utf8 COLLATE utf8_spanish2_ci NOT NULL,
  `nombre` varchar(50) CHARACTER SET utf8 COLLATE utf8_spanish2_ci NOT NULL,
  `direccion` varchar(100) CHARACTER SET utf8 COLLATE utf8_spanish2_ci NOT NULL,
  `telefono` varchar(12) CHARACTER SET utf8 COLLATE utf8_spanish2_ci NOT NULL,
  `email` varchar(70) CHARACTER SET utf8 COLLATE utf8_spanish2_ci NOT NULL,
  `descripcion` varchar(100) CHARACTER SET utf8 COLLATE utf8_spanish2_ci NOT NULL,
  `logo` varchar(255) CHARACTER SET utf8 COLLATE utf8_spanish2_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `empresa`
--

INSERT INTO `empresa` (`rif`, `nombre`, `direccion`, `telefono`, `email`, `descripcion`, `logo`) VALUES
('J505284797', 'Quesera y Charcuteria Don Pedro 24', 'calle 60 entre carreras 12 y 13', '04245645108', 'queseradonpedro24@gmail.com', 'venta al por menor de productos alimenticios', 'vista/dist/img/logo-icono.png');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `frecuencia_gasto`
--

CREATE TABLE `frecuencia_gasto` (
  `cod_frecuencia` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `dias` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `gasto`
--

CREATE TABLE `gasto` (
  `cod_gasto` int(11) NOT NULL,
  `cod_cat_gasto` int(11) NOT NULL,
  `descripcion` varchar(100) NOT NULL,
  `monto` decimal(10,2) NOT NULL,
  `status` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `marcas`
--

CREATE TABLE `marcas` (
  `cod_marca` int(11) NOT NULL,
  `nombre` varchar(255) CHARACTER SET utf8 COLLATE utf8_spanish2_ci NOT NULL,
  `status` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `marcas`
--

INSERT INTO `marcas` (`cod_marca`, `nombre`, `status`) VALUES
(6, 'Alimex', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `movimientos`
--

CREATE TABLE `movimientos` (
  `cod_mov` int(11) NOT NULL,
  `cod_operacion` int(11) DEFAULT NULL,
  `cod_tipo_op` int(11) NOT NULL,
  `cod_detalle_op` int(11) NOT NULL,
  `fecha` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish2_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pago_emitido`
--

CREATE TABLE `pago_emitido` (
  `cod_pago_emitido` int(11) NOT NULL,
  `tipo_pago` enum('compra','gasto') NOT NULL,
  `cod_vuelto_r` int(11) DEFAULT NULL,
  `fecha` date NOT NULL,
  `cod_compra` int(11) DEFAULT NULL,
  `cod_gasto` int(11) DEFAULT NULL,
  `monto_total` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish2_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pago_recibido`
--

CREATE TABLE `pago_recibido` (
  `cod_pago` int(11) NOT NULL,
  `cod_venta` int(11) NOT NULL,
  `cod_vuelto` int(11) DEFAULT NULL,
  `fecha` date NOT NULL,
  `monto_total` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `presentacion_producto`
--

CREATE TABLE `presentacion_producto` (
  `cod_presentacion` int(11) NOT NULL,
  `cod_unidad` int(11) NOT NULL,
  `cod_producto` int(11) NOT NULL,
  `presentacion` varchar(30) DEFAULT NULL,
  `cantidad_presentacion` varchar(20) DEFAULT NULL,
  `costo` decimal(10,2) NOT NULL,
  `porcen_venta` int(11) NOT NULL,
  `excento` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

--
-- Volcado de datos para la tabla `presentacion_producto`
--

INSERT INTO `presentacion_producto` (`cod_presentacion`, `cod_unidad`, `cod_producto`, `presentacion`, `cantidad_presentacion`, `costo`, `porcen_venta`, `excento`) VALUES
(1, 1, 1, 'pieza', '10', 7.00, 0, 1),
(2, 1, 2, 'pieza', '4.5', 11.00, 34, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `presupuestos`
--

CREATE TABLE `presupuestos` (
  `cod_presupuesto` int(11) NOT NULL,
  `cod_tipo_op` int(11) DEFAULT NULL,
  `monto` decimal(10,2) NOT NULL,
  `mes` int(11) NOT NULL,
  `anio` int(11) NOT NULL,
  `notas` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productos`
--

CREATE TABLE `productos` (
  `cod_producto` int(11) NOT NULL,
  `cod_categoria` int(11) NOT NULL,
  `cod_marca` int(11) DEFAULT NULL,
  `nombre` varchar(40) NOT NULL,
  `imagen` varchar(250) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

--
-- Volcado de datos para la tabla `productos`
--

INSERT INTO `productos` (`cod_producto`, `cod_categoria`, `cod_marca`, `nombre`, `imagen`) VALUES
(1, 1, NULL, 'Queso Duro', NULL),
(2, 2, 6, 'jamon de piernaa', 'vista/dist/img/productos/ImgThumb2.jpg');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `proveedores`
--

CREATE TABLE `proveedores` (
  `cod_prov` int(11) NOT NULL,
  `rif` varchar(15) NOT NULL,
  `razon_social` varchar(50) NOT NULL,
  `email` varchar(50) DEFAULT NULL,
  `direccion` varchar(250) DEFAULT NULL,
  `status` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

--
-- Volcado de datos para la tabla `proveedores`
--

INSERT INTO `proveedores` (`cod_prov`, `rif`, `razon_social`, `email`, `direccion`, `status`) VALUES
(1, 'J505284788', 'generico', '', '', 1),
(2, 'J28516209', 'ST3M c.a', 'Pedroperez@gmail.com', 'av. libertador', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `prov_representantes`
--

CREATE TABLE `prov_representantes` (
  `cod_representante` int(11) NOT NULL,
  `cod_prov` int(11) NOT NULL,
  `cedula` varchar(12) NOT NULL,
  `nombre` varchar(80) NOT NULL,
  `apellido` varchar(80) DEFAULT NULL,
  `telefono` varchar(12) DEFAULT NULL,
  `status` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

--
-- Volcado de datos para la tabla `prov_representantes`
--

INSERT INTO `prov_representantes` (`cod_representante`, `cod_prov`, `cedula`, `nombre`, `apellido`, `telefono`, `status`) VALUES
(1, 2, '10771716', 'samuel', 'Rojas', '12453145', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `proyecciones_futuras`
--

CREATE TABLE `proyecciones_futuras` (
  `cod_proyeccion` int(11) NOT NULL,
  `cod_producto` int(11) NOT NULL,
  `fecha_proyeccion` date NOT NULL,
  `periodo_inicio` date NOT NULL,
  `periodo_fin` date NOT NULL,
  `mes` date NOT NULL,
  `valor_proyectado` decimal(12,2) NOT NULL,
  `ventana_ma` int(11) NOT NULL,
  `status` int(11) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `proyecciones_historicas`
--

CREATE TABLE `proyecciones_historicas` (
  `cod_historico` int(11) NOT NULL,
  `cod_producto` int(11) NOT NULL,
  `fecha_proyeccion` date NOT NULL,
  `periodo_inicio` date NOT NULL,
  `periodo_fin` date NOT NULL,
  `mes` date NOT NULL,
  `valor_proyectado` decimal(12,2) NOT NULL,
  `valor_real` decimal(12,2) DEFAULT NULL,
  `precision_valor` decimal(8,2) DEFAULT NULL,
  `ventana_ma` int(11) NOT NULL,
  `status` int(11) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `stock_mensual`
--

CREATE TABLE `stock_mensual` (
  `cod_stock` int(11) NOT NULL,
  `cod_detallep` int(11) DEFAULT NULL,
  `mes` varchar(20) DEFAULT NULL,
  `ano` varchar(20) DEFAULT NULL,
  `stock_inicial` decimal(10,2) DEFAULT NULL,
  `stock_final` decimal(10,2) DEFAULT NULL,
  `ventas_cantidad` decimal(10,2) DEFAULT NULL,
  `rotacion` decimal(8,2) DEFAULT NULL,
  `dias_rotacion` decimal(8,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipo_cuenta`
--

CREATE TABLE `tipo_cuenta` (
  `cod_tipo_cuenta` int(11) NOT NULL,
  `nombre` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tipo_cuenta`
--

INSERT INTO `tipo_cuenta` (`cod_tipo_cuenta`, `nombre`) VALUES
(1, 'AHORRO'),
(2, 'CORRIENTE');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipo_gasto`
--

CREATE TABLE `tipo_gasto` (
  `cod_tipo_gasto` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipo_operacion`
--

CREATE TABLE `tipo_operacion` (
  `cod_tipo_op` int(11) NOT NULL,
  `tipo` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipo_pago`
--

CREATE TABLE `tipo_pago` (
  `cod_metodo` int(11) NOT NULL,
  `medio_pago` varchar(50) NOT NULL,
  `status` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

--
-- Volcado de datos para la tabla `tipo_pago`
--

INSERT INTO `tipo_pago` (`cod_metodo`, `medio_pago`, `status`) VALUES
(1, 'Efectivo en Bs', 1),
(2, 'Efectivo USD', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tlf_proveedores`
--

CREATE TABLE `tlf_proveedores` (
  `cod_tlf` int(11) NOT NULL,
  `cod_prov` int(11) NOT NULL,
  `telefono` varchar(12) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

--
-- Volcado de datos para la tabla `tlf_proveedores`
--

INSERT INTO `tlf_proveedores` (`cod_tlf`, `cod_prov`, `telefono`) VALUES
(1, 2, '04245645108'),
(2, 2, '12453145213');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `unidades_medida`
--

CREATE TABLE `unidades_medida` (
  `cod_unidad` int(11) NOT NULL,
  `tipo_medida` char(10) NOT NULL,
  `status` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

--
-- Volcado de datos para la tabla `unidades_medida`
--

INSERT INTO `unidades_medida` (`cod_unidad`, `tipo_medida`, `status`) VALUES
(1, 'kg', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ventas`
--

CREATE TABLE `ventas` (
  `cod_venta` int(11) NOT NULL,
  `cod_cliente` int(11) NOT NULL,
  `condicion_pago` enum('contado','credito') NOT NULL,
  `fecha_vencimiento` date DEFAULT NULL,
  `total` decimal(10,2) NOT NULL,
  `fecha` datetime NOT NULL,
  `status` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

--
-- Volcado de datos para la tabla `ventas`
--

INSERT INTO `ventas` (`cod_venta`, `cod_cliente`, `condicion_pago`, `fecha_vencimiento`, `total`, `fecha`, `status`) VALUES
(7, 1, 'contado', NULL, 98.00, '2025-04-08 20:03:36', 3),
(8, 2, 'contado', NULL, 4.31, '2025-04-29 22:32:39', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `vuelto_emitido`
--

CREATE TABLE `vuelto_emitido` (
  `cod_vuelto` int(11) NOT NULL,
  `vuelto_total` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish2_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `vuelto_recibido`
--

CREATE TABLE `vuelto_recibido` (
  `cod_vuelto_r` int(11) NOT NULL,
  `vuelto_total` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `analisis_rentabilidad`
--
ALTER TABLE `analisis_rentabilidad`
  ADD PRIMARY KEY (`cod_analisis`),
  ADD KEY `cod_detallep` (`cod_detallep`);

--
-- Indices de la tabla `asientos_contables`
--
ALTER TABLE `asientos_contables`
  ADD PRIMARY KEY (`cod_asiento`),
  ADD KEY `cod_mov` (`cod_mov`);

--
-- Indices de la tabla `banco`
--
ALTER TABLE `banco`
  ADD PRIMARY KEY (`cod_banco`);

--
-- Indices de la tabla `caja`
--
ALTER TABLE `caja`
  ADD PRIMARY KEY (`cod_caja`),
  ADD KEY `cod_divisas` (`cod_divisas`);

--
-- Indices de la tabla `cambio_divisa`
--
ALTER TABLE `cambio_divisa`
  ADD PRIMARY KEY (`cod_cambio`),
  ADD KEY `cambiodivisa-divisa` (`cod_divisa`);

--
-- Indices de la tabla `carga`
--
ALTER TABLE `carga`
  ADD PRIMARY KEY (`cod_carga`);

--
-- Indices de la tabla `categorias`
--
ALTER TABLE `categorias`
  ADD PRIMARY KEY (`cod_categoria`);

--
-- Indices de la tabla `categoria_gasto`
--
ALTER TABLE `categoria_gasto`
  ADD PRIMARY KEY (`cod_cat_gasto`),
  ADD KEY `cod_tipo_gasto` (`cod_tipo_gasto`),
  ADD KEY `cod_frecuencia` (`cod_frecuencia`);

--
-- Indices de la tabla `clientes`
--
ALTER TABLE `clientes`
  ADD PRIMARY KEY (`cod_cliente`);

--
-- Indices de la tabla `compras`
--
ALTER TABLE `compras`
  ADD PRIMARY KEY (`cod_compra`),
  ADD KEY `compras-proveedores` (`cod_prov`);

--
-- Indices de la tabla `conciliacion`
--
ALTER TABLE `conciliacion`
  ADD KEY `cod_cuenta_bancaria` (`cod_cuenta_bancaria`);

--
-- Indices de la tabla `control`
--
ALTER TABLE `control`
  ADD PRIMARY KEY (`cod_control`),
  ADD KEY `cod_caja` (`cod_caja`);

--
-- Indices de la tabla `cuentas_contables`
--
ALTER TABLE `cuentas_contables`
  ADD PRIMARY KEY (`cod_cuenta`),
  ADD UNIQUE KEY `codigo_contable` (`codigo_contable`),
  ADD KEY `cuenta_padreid` (`cuenta_padreid`);

--
-- Indices de la tabla `cuenta_bancaria`
--
ALTER TABLE `cuenta_bancaria`
  ADD PRIMARY KEY (`cod_cuenta_bancaria`),
  ADD KEY `cod_banco` (`cod_banco`),
  ADD KEY `cod_tipo_cuenta` (`cod_tipo_cuenta`),
  ADD KEY `cod_divisa` (`cod_divisa`);

--
-- Indices de la tabla `descarga`
--
ALTER TABLE `descarga`
  ADD PRIMARY KEY (`cod_descarga`);

--
-- Indices de la tabla `detalle_asientos`
--
ALTER TABLE `detalle_asientos`
  ADD PRIMARY KEY (`cod_det_asiento`),
  ADD KEY `asiento_id` (`cod_asiento`),
  ADD KEY `cuenta_id` (`cod_cuenta`);

--
-- Indices de la tabla `detalle_carga`
--
ALTER TABLE `detalle_carga`
  ADD PRIMARY KEY (`cod_det_carga`),
  ADD KEY `detalle_carga-carga` (`cod_carga`),
  ADD KEY `detalle_carga-detallep` (`cod_detallep`);

--
-- Indices de la tabla `detalle_compras`
--
ALTER TABLE `detalle_compras`
  ADD PRIMARY KEY (`cod_detallec`),
  ADD KEY `detalle_compras-compras` (`cod_compra`),
  ADD KEY `detalle_compras-detalle_productos` (`cod_detallep`);

--
-- Indices de la tabla `detalle_descarga`
--
ALTER TABLE `detalle_descarga`
  ADD PRIMARY KEY (`cod_det_descarga`),
  ADD KEY `detalle_descarga-detallep` (`cod_detallep`),
  ADD KEY `detalle_descarga-descarga` (`cod_descarga`);

--
-- Indices de la tabla `detalle_operacion`
--
ALTER TABLE `detalle_operacion`
  ADD PRIMARY KEY (`cod_detalle_op`);

--
-- Indices de la tabla `detalle_pago_emitido`
--
ALTER TABLE `detalle_pago_emitido`
  ADD PRIMARY KEY (`cod_detallepagoe`),
  ADD KEY `pagoe-dtpagoe` (`cod_pago_emitido`),
  ADD KEY `dtpagoe-tipopagoe` (`cod_tipo_pagoe`);

--
-- Indices de la tabla `detalle_pago_recibido`
--
ALTER TABLE `detalle_pago_recibido`
  ADD PRIMARY KEY (`cod_detallepago`),
  ADD KEY `detalle_pago-pago` (`cod_pago`),
  ADD KEY `tipo_pago-detalle_pago` (`cod_tipo_pago`);

--
-- Indices de la tabla `detalle_productos`
--
ALTER TABLE `detalle_productos`
  ADD PRIMARY KEY (`cod_detallep`),
  ADD KEY `detalle_producto-productos` (`cod_presentacion`);

--
-- Indices de la tabla `detalle_tipo_pago`
--
ALTER TABLE `detalle_tipo_pago`
  ADD PRIMARY KEY (`cod_tipo_pago`),
  ADD KEY `cod_cuenta_bancaria` (`cod_cuenta_bancaria`),
  ADD KEY `cod_metodo` (`cod_metodo`),
  ADD KEY `cod_caja` (`cod_caja`);

--
-- Indices de la tabla `detalle_ventas`
--
ALTER TABLE `detalle_ventas`
  ADD PRIMARY KEY (`cod_detallev`),
  ADD KEY `cod_venta` (`cod_venta`),
  ADD KEY `detalle_ventas-detalle_productos` (`cod_detallep`);

--
-- Indices de la tabla `detalle_vueltoe`
--
ALTER TABLE `detalle_vueltoe`
  ADD PRIMARY KEY (`cod_detallev`),
  ADD KEY `cod_vuelto` (`cod_vuelto`),
  ADD KEY `cod_tipo_pago` (`cod_tipo_pago`);

--
-- Indices de la tabla `detalle_vueltor`
--
ALTER TABLE `detalle_vueltor`
  ADD PRIMARY KEY (`cod_detallev_r`),
  ADD KEY `cod_vuelto_r` (`cod_vuelto_r`),
  ADD KEY `cod_tipo_pago` (`cod_tipo_pago`);

--
-- Indices de la tabla `divisas`
--
ALTER TABLE `divisas`
  ADD PRIMARY KEY (`cod_divisa`);

--
-- Indices de la tabla `empresa`
--
ALTER TABLE `empresa`
  ADD PRIMARY KEY (`rif`);

--
-- Indices de la tabla `frecuencia_gasto`
--
ALTER TABLE `frecuencia_gasto`
  ADD PRIMARY KEY (`cod_frecuencia`);

--
-- Indices de la tabla `gasto`
--
ALTER TABLE `gasto`
  ADD PRIMARY KEY (`cod_gasto`),
  ADD KEY `cod_cat_gasto` (`cod_cat_gasto`);

--
-- Indices de la tabla `marcas`
--
ALTER TABLE `marcas`
  ADD PRIMARY KEY (`cod_marca`),
  ADD UNIQUE KEY `marca_unica` (`nombre`);

--
-- Indices de la tabla `movimientos`
--
ALTER TABLE `movimientos`
  ADD PRIMARY KEY (`cod_mov`),
  ADD KEY `cod_tipo_op` (`cod_tipo_op`),
  ADD KEY `cod_detalle_op` (`cod_detalle_op`),
  ADD KEY `cod_operacion` (`cod_operacion`);

--
-- Indices de la tabla `pago_emitido`
--
ALTER TABLE `pago_emitido`
  ADD PRIMARY KEY (`cod_pago_emitido`),
  ADD KEY `compra-pago` (`cod_compra`),
  ADD KEY `cod_gasto` (`cod_gasto`),
  ADD KEY `cod_vuelto_r` (`cod_vuelto_r`);

--
-- Indices de la tabla `pago_recibido`
--
ALTER TABLE `pago_recibido`
  ADD PRIMARY KEY (`cod_pago`),
  ADD KEY `pagos-ventas` (`cod_venta`),
  ADD KEY `cod_vuelto` (`cod_vuelto`);

--
-- Indices de la tabla `presentacion_producto`
--
ALTER TABLE `presentacion_producto`
  ADD PRIMARY KEY (`cod_presentacion`),
  ADD KEY `cod_producto` (`cod_producto`),
  ADD KEY `cod_unidad` (`cod_unidad`);

--
-- Indices de la tabla `presupuestos`
--
ALTER TABLE `presupuestos`
  ADD PRIMARY KEY (`cod_presupuesto`),
  ADD KEY `cod_tipo_op` (`cod_tipo_op`);

--
-- Indices de la tabla `productos`
--
ALTER TABLE `productos`
  ADD PRIMARY KEY (`cod_producto`),
  ADD KEY `productos-categorias` (`cod_categoria`),
  ADD KEY `cod_marca` (`cod_marca`);

--
-- Indices de la tabla `proveedores`
--
ALTER TABLE `proveedores`
  ADD PRIMARY KEY (`cod_prov`);

--
-- Indices de la tabla `prov_representantes`
--
ALTER TABLE `prov_representantes`
  ADD PRIMARY KEY (`cod_representante`),
  ADD KEY `prov_representantes_ibfk_1` (`cod_prov`);

--
-- Indices de la tabla `proyecciones_futuras`
--
ALTER TABLE `proyecciones_futuras`
  ADD PRIMARY KEY (`cod_proyeccion`),
  ADD KEY `fk_proyeccion_producto` (`cod_producto`);

--
-- Indices de la tabla `proyecciones_historicas`
--
ALTER TABLE `proyecciones_historicas`
  ADD PRIMARY KEY (`cod_historico`),
  ADD KEY `fk_historico_producto` (`cod_producto`);

--
-- Indices de la tabla `stock_mensual`
--
ALTER TABLE `stock_mensual`
  ADD PRIMARY KEY (`cod_stock`),
  ADD KEY `cod_detallep` (`cod_detallep`);

--
-- Indices de la tabla `tipo_cuenta`
--
ALTER TABLE `tipo_cuenta`
  ADD PRIMARY KEY (`cod_tipo_cuenta`);

--
-- Indices de la tabla `tipo_gasto`
--
ALTER TABLE `tipo_gasto`
  ADD PRIMARY KEY (`cod_tipo_gasto`);

--
-- Indices de la tabla `tipo_operacion`
--
ALTER TABLE `tipo_operacion`
  ADD PRIMARY KEY (`cod_tipo_op`);

--
-- Indices de la tabla `tipo_pago`
--
ALTER TABLE `tipo_pago`
  ADD PRIMARY KEY (`cod_metodo`);

--
-- Indices de la tabla `tlf_proveedores`
--
ALTER TABLE `tlf_proveedores`
  ADD PRIMARY KEY (`cod_tlf`),
  ADD KEY `cod_prov` (`cod_prov`);

--
-- Indices de la tabla `unidades_medida`
--
ALTER TABLE `unidades_medida`
  ADD PRIMARY KEY (`cod_unidad`);

--
-- Indices de la tabla `ventas`
--
ALTER TABLE `ventas`
  ADD PRIMARY KEY (`cod_venta`),
  ADD KEY `ventas-clientes` (`cod_cliente`);

--
-- Indices de la tabla `vuelto_emitido`
--
ALTER TABLE `vuelto_emitido`
  ADD PRIMARY KEY (`cod_vuelto`);

--
-- Indices de la tabla `vuelto_recibido`
--
ALTER TABLE `vuelto_recibido`
  ADD PRIMARY KEY (`cod_vuelto_r`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `analisis_rentabilidad`
--
ALTER TABLE `analisis_rentabilidad`
  MODIFY `cod_analisis` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `asientos_contables`
--
ALTER TABLE `asientos_contables`
  MODIFY `cod_asiento` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `banco`
--
ALTER TABLE `banco`
  MODIFY `cod_banco` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `caja`
--
ALTER TABLE `caja`
  MODIFY `cod_caja` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `cambio_divisa`
--
ALTER TABLE `cambio_divisa`
  MODIFY `cod_cambio` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT de la tabla `carga`
--
ALTER TABLE `carga`
  MODIFY `cod_carga` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `categorias`
--
ALTER TABLE `categorias`
  MODIFY `cod_categoria` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `categoria_gasto`
--
ALTER TABLE `categoria_gasto`
  MODIFY `cod_cat_gasto` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `clientes`
--
ALTER TABLE `clientes`
  MODIFY `cod_cliente` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `compras`
--
ALTER TABLE `compras`
  MODIFY `cod_compra` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `control`
--
ALTER TABLE `control`
  MODIFY `cod_control` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `cuentas_contables`
--
ALTER TABLE `cuentas_contables`
  MODIFY `cod_cuenta` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de la tabla `cuenta_bancaria`
--
ALTER TABLE `cuenta_bancaria`
  MODIFY `cod_cuenta_bancaria` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `descarga`
--
ALTER TABLE `descarga`
  MODIFY `cod_descarga` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `detalle_asientos`
--
ALTER TABLE `detalle_asientos`
  MODIFY `cod_det_asiento` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `detalle_carga`
--
ALTER TABLE `detalle_carga`
  MODIFY `cod_det_carga` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `detalle_compras`
--
ALTER TABLE `detalle_compras`
  MODIFY `cod_detallec` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `detalle_descarga`
--
ALTER TABLE `detalle_descarga`
  MODIFY `cod_det_descarga` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `detalle_operacion`
--
ALTER TABLE `detalle_operacion`
  MODIFY `cod_detalle_op` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `detalle_pago_emitido`
--
ALTER TABLE `detalle_pago_emitido`
  MODIFY `cod_detallepagoe` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `detalle_pago_recibido`
--
ALTER TABLE `detalle_pago_recibido`
  MODIFY `cod_detallepago` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `detalle_productos`
--
ALTER TABLE `detalle_productos`
  MODIFY `cod_detallep` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `detalle_ventas`
--
ALTER TABLE `detalle_ventas`
  MODIFY `cod_detallev` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `detalle_vueltoe`
--
ALTER TABLE `detalle_vueltoe`
  MODIFY `cod_detallev` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `detalle_vueltor`
--
ALTER TABLE `detalle_vueltor`
  MODIFY `cod_detallev_r` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `divisas`
--
ALTER TABLE `divisas`
  MODIFY `cod_divisa` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `frecuencia_gasto`
--
ALTER TABLE `frecuencia_gasto`
  MODIFY `cod_frecuencia` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `gasto`
--
ALTER TABLE `gasto`
  MODIFY `cod_gasto` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `marcas`
--
ALTER TABLE `marcas`
  MODIFY `cod_marca` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `movimientos`
--
ALTER TABLE `movimientos`
  MODIFY `cod_mov` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `pago_emitido`
--
ALTER TABLE `pago_emitido`
  MODIFY `cod_pago_emitido` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `pago_recibido`
--
ALTER TABLE `pago_recibido`
  MODIFY `cod_pago` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `presentacion_producto`
--
ALTER TABLE `presentacion_producto`
  MODIFY `cod_presentacion` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `presupuestos`
--
ALTER TABLE `presupuestos`
  MODIFY `cod_presupuesto` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `productos`
--
ALTER TABLE `productos`
  MODIFY `cod_producto` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `proveedores`
--
ALTER TABLE `proveedores`
  MODIFY `cod_prov` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `prov_representantes`
--
ALTER TABLE `prov_representantes`
  MODIFY `cod_representante` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `stock_mensual`
--
ALTER TABLE `stock_mensual`
  MODIFY `cod_stock` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `tipo_gasto`
--
ALTER TABLE `tipo_gasto`
  MODIFY `cod_tipo_gasto` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `tipo_operacion`
--
ALTER TABLE `tipo_operacion`
  MODIFY `cod_tipo_op` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `tipo_pago`
--
ALTER TABLE `tipo_pago`
  MODIFY `cod_metodo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `tlf_proveedores`
--
ALTER TABLE `tlf_proveedores`
  MODIFY `cod_tlf` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `unidades_medida`
--
ALTER TABLE `unidades_medida`
  MODIFY `cod_unidad` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `ventas`
--
ALTER TABLE `ventas`
  MODIFY `cod_venta` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `vuelto_emitido`
--
ALTER TABLE `vuelto_emitido`
  MODIFY `cod_vuelto` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `vuelto_recibido`
--
ALTER TABLE `vuelto_recibido`
  MODIFY `cod_vuelto_r` int(11) NOT NULL AUTO_INCREMENT;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `analisis_rentabilidad`
--
ALTER TABLE `analisis_rentabilidad`
  ADD CONSTRAINT `analisis_rentabilidad_ibfk_1` FOREIGN KEY (`cod_detallep`) REFERENCES `detalle_productos` (`cod_detallep`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `asientos_contables`
--
ALTER TABLE `asientos_contables`
  ADD CONSTRAINT `asientos_contables_ibfk_1` FOREIGN KEY (`cod_mov`) REFERENCES `movimientos` (`cod_mov`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `caja`
--
ALTER TABLE `caja`
  ADD CONSTRAINT `caja_ibfk_1` FOREIGN KEY (`cod_divisas`) REFERENCES `divisas` (`cod_divisa`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `cambio_divisa`
--
ALTER TABLE `cambio_divisa`
  ADD CONSTRAINT `cambiodivisa-divisa` FOREIGN KEY (`cod_divisa`) REFERENCES `divisas` (`cod_divisa`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `categoria_gasto`
--
ALTER TABLE `categoria_gasto`
  ADD CONSTRAINT `categoria_gasto_ibfk_1` FOREIGN KEY (`cod_tipo_gasto`) REFERENCES `tipo_gasto` (`cod_tipo_gasto`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `categoria_gasto_ibfk_2` FOREIGN KEY (`cod_frecuencia`) REFERENCES `frecuencia_gasto` (`cod_frecuencia`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `compras`
--
ALTER TABLE `compras`
  ADD CONSTRAINT `compras-proveedores` FOREIGN KEY (`cod_prov`) REFERENCES `proveedores` (`cod_prov`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `conciliacion`
--
ALTER TABLE `conciliacion`
  ADD CONSTRAINT `conciliacion_ibfk_1` FOREIGN KEY (`cod_cuenta_bancaria`) REFERENCES `cuenta_bancaria` (`cod_cuenta_bancaria`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `control`
--
ALTER TABLE `control`
  ADD CONSTRAINT `control_ibfk_1` FOREIGN KEY (`cod_caja`) REFERENCES `caja` (`cod_caja`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `cuentas_contables`
--
ALTER TABLE `cuentas_contables`
  ADD CONSTRAINT `cuentas_contables_ibfk_1` FOREIGN KEY (`cuenta_padreid`) REFERENCES `cuentas_contables` (`cod_cuenta`);

--
-- Filtros para la tabla `cuenta_bancaria`
--
ALTER TABLE `cuenta_bancaria`
  ADD CONSTRAINT `cuenta_bancaria_ibfk_1` FOREIGN KEY (`cod_banco`) REFERENCES `banco` (`cod_banco`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `cuenta_bancaria_ibfk_2` FOREIGN KEY (`cod_tipo_cuenta`) REFERENCES `tipo_cuenta` (`cod_tipo_cuenta`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `cuenta_bancaria_ibfk_3` FOREIGN KEY (`cod_divisa`) REFERENCES `divisas` (`cod_divisa`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `detalle_asientos`
--
ALTER TABLE `detalle_asientos`
  ADD CONSTRAINT `detalle_asientos_ibfk_1` FOREIGN KEY (`cod_asiento`) REFERENCES `asientos_contables` (`cod_asiento`) ON DELETE CASCADE,
  ADD CONSTRAINT `detalle_asientos_ibfk_2` FOREIGN KEY (`cod_cuenta`) REFERENCES `cuentas_contables` (`cod_cuenta`) ON DELETE CASCADE;

--
-- Filtros para la tabla `detalle_carga`
--
ALTER TABLE `detalle_carga`
  ADD CONSTRAINT `detalle_carga-carga` FOREIGN KEY (`cod_carga`) REFERENCES `carga` (`cod_carga`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `detalle_carga-detallep` FOREIGN KEY (`cod_detallep`) REFERENCES `detalle_productos` (`cod_detallep`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `detalle_compras`
--
ALTER TABLE `detalle_compras`
  ADD CONSTRAINT `detalle_compras-compras` FOREIGN KEY (`cod_compra`) REFERENCES `compras` (`cod_compra`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `detalle_compras-detalle_productos` FOREIGN KEY (`cod_detallep`) REFERENCES `detalle_productos` (`cod_detallep`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `detalle_descarga`
--
ALTER TABLE `detalle_descarga`
  ADD CONSTRAINT `detalle_descarga-descarga` FOREIGN KEY (`cod_descarga`) REFERENCES `descarga` (`cod_descarga`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `detalle_descarga-detallep` FOREIGN KEY (`cod_detallep`) REFERENCES `detalle_productos` (`cod_detallep`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `detalle_pago_emitido`
--
ALTER TABLE `detalle_pago_emitido`
  ADD CONSTRAINT `detalle_pago_emitido_ibfk_1` FOREIGN KEY (`cod_pago_emitido`) REFERENCES `pago_emitido` (`cod_pago_emitido`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `detalle_pago_emitido_ibfk_2` FOREIGN KEY (`cod_tipo_pagoe`) REFERENCES `detalle_tipo_pago` (`cod_tipo_pago`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `detalle_pago_recibido`
--
ALTER TABLE `detalle_pago_recibido`
  ADD CONSTRAINT `detalle_pago_recibido_ibfk_1` FOREIGN KEY (`cod_pago`) REFERENCES `pago_recibido` (`cod_pago`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `detalle_pago_recibido_ibfk_2` FOREIGN KEY (`cod_tipo_pago`) REFERENCES `detalle_tipo_pago` (`cod_tipo_pago`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `detalle_productos`
--
ALTER TABLE `detalle_productos`
  ADD CONSTRAINT `detalle_productos_ibfk_1` FOREIGN KEY (`cod_presentacion`) REFERENCES `presentacion_producto` (`cod_presentacion`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `detalle_tipo_pago`
--
ALTER TABLE `detalle_tipo_pago`
  ADD CONSTRAINT `detalle_tipo_pago_ibfk_1` FOREIGN KEY (`cod_cuenta_bancaria`) REFERENCES `cuenta_bancaria` (`cod_cuenta_bancaria`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `detalle_tipo_pago_ibfk_3` FOREIGN KEY (`cod_metodo`) REFERENCES `tipo_pago` (`cod_metodo`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `detalle_tipo_pago_ibfk_4` FOREIGN KEY (`cod_caja`) REFERENCES `caja` (`cod_caja`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `detalle_ventas`
--
ALTER TABLE `detalle_ventas`
  ADD CONSTRAINT `detalle_ventas-detalle_productos` FOREIGN KEY (`cod_detallep`) REFERENCES `detalle_productos` (`cod_detallep`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `detalle_ventas_ibfk_1` FOREIGN KEY (`cod_venta`) REFERENCES `ventas` (`cod_venta`);

--
-- Filtros para la tabla `detalle_vueltoe`
--
ALTER TABLE `detalle_vueltoe`
  ADD CONSTRAINT `detalle_vueltoe_ibfk_1` FOREIGN KEY (`cod_vuelto`) REFERENCES `vuelto_emitido` (`cod_vuelto`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `detalle_vueltoe_ibfk_2` FOREIGN KEY (`cod_tipo_pago`) REFERENCES `detalle_tipo_pago` (`cod_tipo_pago`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `detalle_vueltor`
--
ALTER TABLE `detalle_vueltor`
  ADD CONSTRAINT `detalle_vueltor_ibfk_1` FOREIGN KEY (`cod_vuelto_r`) REFERENCES `vuelto_recibido` (`cod_vuelto_r`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `detalle_vueltor_ibfk_2` FOREIGN KEY (`cod_tipo_pago`) REFERENCES `detalle_tipo_pago` (`cod_tipo_pago`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `gasto`
--
ALTER TABLE `gasto`
  ADD CONSTRAINT `gasto_ibfk_1` FOREIGN KEY (`cod_cat_gasto`) REFERENCES `categoria_gasto` (`cod_cat_gasto`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `movimientos`
--
ALTER TABLE `movimientos`
  ADD CONSTRAINT `movimientos_ibfk_1` FOREIGN KEY (`cod_tipo_op`) REFERENCES `tipo_operacion` (`cod_tipo_op`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `movimientos_ibfk_2` FOREIGN KEY (`cod_detalle_op`) REFERENCES `detalle_operacion` (`cod_detalle_op`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `movimientos_ibfk_3` FOREIGN KEY (`cod_operacion`) REFERENCES `ventas` (`cod_venta`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `movimientos_ibfk_4` FOREIGN KEY (`cod_operacion`) REFERENCES `compras` (`cod_compra`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `movimientos_ibfk_5` FOREIGN KEY (`cod_operacion`) REFERENCES `gasto` (`cod_gasto`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `movimientos_ibfk_6` FOREIGN KEY (`cod_operacion`) REFERENCES `carga` (`cod_carga`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `movimientos_ibfk_7` FOREIGN KEY (`cod_operacion`) REFERENCES `descarga` (`cod_descarga`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `pago_emitido`
--
ALTER TABLE `pago_emitido`
  ADD CONSTRAINT `pago_emitido_ibfk_1` FOREIGN KEY (`cod_compra`) REFERENCES `compras` (`cod_compra`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `pago_emitido_ibfk_2` FOREIGN KEY (`cod_gasto`) REFERENCES `gasto` (`cod_gasto`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `pago_emitido_ibfk_3` FOREIGN KEY (`cod_vuelto_r`) REFERENCES `vuelto_recibido` (`cod_vuelto_r`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `pago_recibido`
--
ALTER TABLE `pago_recibido`
  ADD CONSTRAINT `pago_recibido_ibfk_1` FOREIGN KEY (`cod_venta`) REFERENCES `ventas` (`cod_venta`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `pago_recibido_ibfk_2` FOREIGN KEY (`cod_vuelto`) REFERENCES `vuelto_emitido` (`cod_vuelto`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `presentacion_producto`
--
ALTER TABLE `presentacion_producto`
  ADD CONSTRAINT `presentacion_producto_ibfk_1` FOREIGN KEY (`cod_producto`) REFERENCES `productos` (`cod_producto`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `presentacion_producto_ibfk_2` FOREIGN KEY (`cod_unidad`) REFERENCES `unidades_medida` (`cod_unidad`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `presupuestos`
--
ALTER TABLE `presupuestos`
  ADD CONSTRAINT `presupuestos_ibfk_1` FOREIGN KEY (`cod_tipo_op`) REFERENCES `tipo_operacion` (`cod_tipo_op`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `productos`
--
ALTER TABLE `productos`
  ADD CONSTRAINT `productos-categorias` FOREIGN KEY (`cod_categoria`) REFERENCES `categorias` (`cod_categoria`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `productos_ibfk_1` FOREIGN KEY (`cod_marca`) REFERENCES `marcas` (`cod_marca`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `prov_representantes`
--
ALTER TABLE `prov_representantes`
  ADD CONSTRAINT `prov_representantes_ibfk_1` FOREIGN KEY (`cod_prov`) REFERENCES `proveedores` (`cod_prov`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `proyecciones_futuras`
--
ALTER TABLE `proyecciones_futuras`
  ADD CONSTRAINT `proyecciones_futuras_ibfk_1` FOREIGN KEY (`cod_producto`) REFERENCES `productos` (`cod_producto`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `proyecciones_historicas`
--
ALTER TABLE `proyecciones_historicas`
  ADD CONSTRAINT `proyecciones_historicas_ibfk_1` FOREIGN KEY (`cod_producto`) REFERENCES `productos` (`cod_producto`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `stock_mensual`
--
ALTER TABLE `stock_mensual`
  ADD CONSTRAINT `stock_mensual_ibfk_1` FOREIGN KEY (`cod_detallep`) REFERENCES `detalle_productos` (`cod_detallep`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `tlf_proveedores`
--
ALTER TABLE `tlf_proveedores`
  ADD CONSTRAINT `tlf_proveedores_ibfk_1` FOREIGN KEY (`cod_prov`) REFERENCES `proveedores` (`cod_prov`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `ventas`
--
ALTER TABLE `ventas`
  ADD CONSTRAINT `ventas-clientes` FOREIGN KEY (`cod_cliente`) REFERENCES `clientes` (`cod_cliente`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
