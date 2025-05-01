-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 30, 2025 at 10:52 PM
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
-- Database: `savyc`
--

DELIMITER $$
--
-- Procedures
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `consultar_cuentas_contables` ()   BEGIN
WITH RECURSIVE CuentasRecursivas AS (
    -- Caso Base: Selecciona las cuentas de nivel 1 (que no tienen padre)
    SELECT 
        cod_cuenta, 
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
        c.cod_cuenta, 
        c.codigo_contable, 
        c.nombre_cuenta,
    	c.naturaleza,  
        c.cuenta_padreid, 
        c.nivel,
        cr.profundidad + 1 AS profundidad
    FROM cuentas_contables c
    INNER JOIN CuentasRecursivas cr ON c.cuenta_padreid = cr.cod_cuenta
)
SELECT * FROM CuentasRecursivas ORDER BY codigo_contable;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `generar_stock_mensual` ()   BEGIN
    DECLARE mes_actual VARCHAR(20);
    DECLARE anio_actual VARCHAR(20);
    
    -- Get current month and year
    SET mes_actual = MONTH(CURRENT_DATE());
    SET anio_actual = YEAR(CURRENT_DATE());
    
    -- Insert or update stock mensual records
    INSERT INTO stock_mensual (
        cod_detallep,
        mes,
        ano,
        stock_inicial,
        stock_final,
        ventas_cantidad,
        rotacion,
        dias_rotacion
    )
    SELECT 
        dp.cod_detallep,
        mes_actual,
        anio_actual,
        -- Stock inicial (stock from previous month or current stock if no previous record)
        COALESCE(
            (SELECT stock_final 
             FROM stock_mensual 
             WHERE cod_detallep = dp.cod_detallep 
             AND mes = IF(mes_actual = 1, 12, mes_actual - 1)
             AND ano = IF(mes_actual = 1, anio_actual - 1, anio_actual)
             ORDER BY cod_stock DESC LIMIT 1),
            dp.stock
        ) as stock_inicial,
        -- Stock final (current stock)
        dp.stock as stock_final,
        -- Ventas cantidad (sum of sales for the month)
        COALESCE(
            (SELECT SUM(dv.cantidad)
             FROM detalle_ventas dv
             INNER JOIN ventas v ON dv.cod_venta = v.cod_venta
             WHERE dv.cod_detallep = dp.cod_detallep
             AND MONTH(v.fecha) = mes_actual
             AND YEAR(v.fecha) = anio_actual),
            0
        ) as ventas_cantidad,
        -- Rotación (ventas / promedio de inventario)
        CASE 
            WHEN COALESCE(
                (SELECT stock_final 
                 FROM stock_mensual 
                 WHERE cod_detallep = dp.cod_detallep 
                 AND mes = IF(mes_actual = 1, 12, mes_actual - 1)
                 AND ano = IF(mes_actual = 1, anio_actual - 1, anio_actual)
                 ORDER BY cod_stock DESC LIMIT 1),
                dp.stock
            ) + dp.stock = 0 THEN 0
            ELSE (COALESCE(
                (SELECT SUM(dv.cantidad)
                 FROM detalle_ventas dv
                 INNER JOIN ventas v ON dv.cod_venta = v.cod_venta
                 WHERE dv.cod_detallep = dp.cod_detallep
                 AND MONTH(v.fecha) = mes_actual
                 AND YEAR(v.fecha) = anio_actual),
                0
            ) * 2) / (
                COALESCE(
                    (SELECT stock_final 
                     FROM stock_mensual 
                     WHERE cod_detallep = dp.cod_detallep 
                     AND mes = IF(mes_actual = 1, 12, mes_actual - 1)
                     AND ano = IF(mes_actual = 1, anio_actual - 1, anio_actual)
                     ORDER BY cod_stock DESC LIMIT 1),
                    dp.stock
                ) + dp.stock
            )
        END as rotacion,
        -- Días de rotación (30 / rotación)
        CASE 
            WHEN COALESCE(
                (SELECT stock_final 
                 FROM stock_mensual 
                 WHERE cod_detallep = dp.cod_detallep 
                 AND mes = IF(mes_actual = 1, 12, mes_actual - 1)
                 AND ano = IF(mes_actual = 1, anio_actual - 1, anio_actual)
                 ORDER BY cod_stock DESC LIMIT 1),
                dp.stock
            ) + dp.stock = 0 THEN 0
            ELSE 30 / (
                (COALESCE(
                    (SELECT SUM(dv.cantidad)
                     FROM detalle_ventas dv
                     INNER JOIN ventas v ON dv.cod_venta = v.cod_venta
                     WHERE dv.cod_detallep = dp.cod_detallep
                     AND MONTH(v.fecha) = mes_actual
                     AND YEAR(v.fecha) = anio_actual),
                    0
                ) * 2) / (
                    COALESCE(
                        (SELECT stock_final 
                         FROM stock_mensual 
                         WHERE cod_detallep = dp.cod_detallep 
                         AND mes = IF(mes_actual = 1, 12, mes_actual - 1)
                         AND ano = IF(mes_actual = 1, anio_actual - 1, anio_actual)
                         ORDER BY cod_stock DESC LIMIT 1),
                        dp.stock
                    ) + dp.stock
                )
            )
        END as dias_rotacion
    FROM detalle_productos dp
    ON DUPLICATE KEY UPDATE
        stock_inicial = VALUES(stock_inicial),
        stock_final = VALUES(stock_final),
        ventas_cantidad = VALUES(ventas_cantidad),
        rotacion = VALUES(rotacion),
        dias_rotacion = VALUES(dias_rotacion);
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
-- Table structure for table `analisis_rentabilidad`
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
-- Table structure for table `asientos_contables`
--

CREATE TABLE `asientos_contables` (
  `cod_asiento` int(11) NOT NULL,
  `fecha` date NOT NULL,
  `descripcion` varchar(50) NOT NULL,
  `total_debe` decimal(18,2) NOT NULL,
  `total_haber` decimal(18,2) NOT NULL,
  `status` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish2_ci;

-- --------------------------------------------------------

--
-- Table structure for table `banco`
--

CREATE TABLE `banco` (
  `cod_banco` int(11) NOT NULL,
  `nombre_banco` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `bitacora`
--

CREATE TABLE `bitacora` (
  `id` int(11) NOT NULL,
  `cod_usuario` int(11) NOT NULL,
  `accion` varchar(255) NOT NULL,
  `fecha` timestamp NOT NULL DEFAULT current_timestamp(),
  `detalles` text DEFAULT NULL,
  `modulo` varchar(220) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- -- Dumping data for table `bitacora`
--

INSERT INTO `bitacora` (`id`, `cod_usuario`, `accion`, `fecha`, `detalles`, `modulo`) VALUES
(49, 1, 'Acceso al sistema', '2025-04-23 00:00:22', 'admin', 'Inicio'),
(50, 1, 'Buscar producto', '2025-04-23 00:17:23', 'Brian', 'Productos'),
(51, 1, 'Buscar producto', '2025-04-23 00:17:30', 'Brian', 'Productos'),
(52, 1, 'Registro de producto', '2025-04-23 00:17:34', 'Brian', 'Productos'),
(53, 1, 'Buscar producto', '2025-04-23 00:17:37', 'Brian', 'Productos'),
(54, 1, 'Registro de producto', '2025-04-23 00:17:47', 'Brian', 'Productos'),
(55, 1, 'Editar producto', '2025-04-23 00:18:30', 'Brian', 'Productos'),
(56, 1, 'Editar producto', '2025-04-23 00:18:50', 'Brian', 'Productos'),
(57, 1, 'Eliminar producto', '2025-04-23 00:18:54', 'Eliminado el producto con el código 3', 'Productos'),
(58, 1, 'Buscar producto', '2025-04-23 00:38:51', 'Brian', 'Productos'),
(59, 1, 'Buscar producto', '2025-04-23 00:38:58', 'Brian', 'Productos'),
(60, 1, 'Registro de producto', '2025-04-23 00:40:30', 'Brian', 'Productos'),
(61, 1, 'Editar producto', '2025-04-23 00:41:00', 'Brian', 'Productos'),
(62, 1, 'Editar producto', '2025-04-23 00:42:44', 'Brian', 'Productos'),
(63, 1, 'Editar producto', '2025-04-23 00:42:53', 'Brian', 'Productos'),
(64, 1, 'Editar producto', '2025-04-23 00:43:00', 'Brian', 'Productos'),
(65, 1, 'Eliminar producto', '2025-04-23 00:43:04', 'Eliminado el producto con el código 4', 'Productos'),
(66, 1, 'Buscar producto', '2025-04-23 00:43:10', '!!!', 'Productos'),
(67, 1, 'Buscar producto', '2025-04-23 00:43:10', '!!!!', 'Productos'),
(68, 1, 'Buscar producto', '2025-04-23 00:43:10', '!!!!!', 'Productos'),
(69, 1, 'Buscar producto', '2025-04-23 00:43:10', '!!!!!!', 'Productos'),
(70, 1, 'Buscar producto', '2025-04-23 00:43:11', '!!!!!!!', 'Productos'),
(71, 1, 'Registro de categoría', '2025-04-23 00:45:06', 'aasdasdsad', 'Categorias'),
(72, 1, 'Editar producto', '2025-04-23 00:45:12', 'Brian', 'Productos'),
(73, 1, 'Acceso al sistema', '2025-04-25 10:24:22', 'admin', 'Inicio'),
(74, 1, 'Acceso al sistema', '2025-04-25 10:25:41', 'admin', 'Inicio'),
(75, 1, 'Eliminar producto', '2025-04-25 10:35:22', 'Eliminado el producto con el código 2', 'Productos'),
(76, 1, 'Buscar producto', '2025-04-30 12:05:38', 'Brian', 'Productos'),
(77, 1, 'Registro de producto', '2025-04-30 12:05:46', 'Brian', 'Productos'),
(78, 1, 'Acceso a Ajuste de INventario', '2025-04-30 12:05:54', '', 'Ajuste de INventario'),
(79, 1, 'Acceso a Carga de productos', '2025-04-30 12:05:55', '', 'Carga de productos'),
(80, 1, 'Acceso al sistema', '2025-04-30 20:39:11', 'admin', 'Inicio');

-- --------------------------------------------------------

--
-- Table structure for table `caja`
--

CREATE TABLE `caja` (
  `cod_caja` int(11) NOT NULL,
  `nombre` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cambio_divisa`
--

CREATE TABLE `cambio_divisa` (
  `cod_cambio` int(11) NOT NULL,
  `cod_divisa` int(11) NOT NULL,
  `tasa` decimal(10,2) NOT NULL,
  `fecha` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

-- -- Dumping data for table `cambio_divisa`
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
(11, 3, 10.00, '2025-04-07');

-- --------------------------------------------------------

--
-- Table structure for table `carga`
--

CREATE TABLE `carga` (
  `cod_carga` int(11) NOT NULL,
  `fecha` datetime NOT NULL,
  `descripcion` varchar(100) NOT NULL,
  `status` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

-- --------------------------------------------------------

--
-- Table structure for table `categorias`
--

CREATE TABLE `categorias` (
  `cod_categoria` int(11) NOT NULL,
  `nombre` varchar(30) NOT NULL,
  `status` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

-- -- Dumping data for table `categorias`
--

INSERT INTO `categorias` (`cod_categoria`, `nombre`, `status`) VALUES
(1, 'queso', 1),
(2, 'aasdasdsad', 1);

-- --------------------------------------------------------

--
-- Table structure for table `categoria_gasto`
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
-- Table structure for table `categoria_movimiento`
--

CREATE TABLE `categoria_movimiento` (
  `cod_categoria` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `status` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `clientes`
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

-- -- Dumping data for table `clientes`
--

INSERT INTO `clientes` (`cod_cliente`, `nombre`, `apellido`, `cedula_rif`, `telefono`, `email`, `direccion`, `status`) VALUES
(1, 'generico', 'perez', '12345678', '', '', '', 1),
(4, 'adgakj', 'gjfgjgl', '27759223', '04245568013', 'briangonzalez.9406@gmail.com', 'sfsfh', 1),
(5, 'adgakj', 'gjfgjgl', '37759223', '04245568013', 'briangonzalez.9406@gmail.com', 'afadeag', 1),
(6, 'adgakj', 'gjfgjgl', '184215013', '04245568013', 'briangonzalez.9406@gmail.com', 'sfsfh', 1);

-- --------------------------------------------------------

--
-- Table structure for table `compras`
--

CREATE TABLE `compras` (
  `cod_compra` int(11) NOT NULL,
  `cod_prov` int(11) NOT NULL,
  `codicion_pago` enum('contado','credito') NOT NULL,
  `fecha_vencimiento` date DEFAULT NULL,
  `subtotal` decimal(10,2) NOT NULL,
  `total` decimal(10,2) NOT NULL,
  `impuesto_total` decimal(10,2) NOT NULL,
  `fecha` date NOT NULL,
  `descuento` int(11) DEFAULT NULL,
  `status` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

-- --------------------------------------------------------

--
-- Table structure for table `conciliacion`
--

CREATE TABLE `conciliacion` (
  `cod_conciliacion` int(11) NOT NULL,
  `url` varchar(200) NOT NULL,
  `fecha` datetime NOT NULL,
  `cod_cuenta_bancaria` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cuentas_contables`
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
-- Table structure for table `cuenta_bancaria`
--

CREATE TABLE `cuenta_bancaria` (
  `cod_cuenta_bancaria` int(11) NOT NULL,
  `cod_banco` int(11) NOT NULL,
  `cod_tipo_cuenta` int(11) NOT NULL,
  `numero_cuenta` int(20) NOT NULL,
  `saldo` float NOT NULL,
  `cod_divisa` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `descarga`
--

CREATE TABLE `descarga` (
  `cod_descarga` int(11) NOT NULL,
  `fecha` datetime NOT NULL,
  `descripcion` varchar(100) NOT NULL,
  `status` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

-- --------------------------------------------------------

--
-- Table structure for table `detalle_asientos`
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
-- Table structure for table `detalle_caja`
--

CREATE TABLE `detalle_caja` (
  `cod_detalle_caja` int(11) NOT NULL,
  `cod_caja` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `saldo` float NOT NULL,
  `cod_divisas` int(11) NOT NULL,
  `fecha` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `detalle_carga`
--

CREATE TABLE `detalle_carga` (
  `cod_det_carga` int(11) NOT NULL,
  `cod_detallep` int(11) NOT NULL,
  `cod_carga` int(11) NOT NULL,
  `cantidad` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

-- --------------------------------------------------------

--
-- Table structure for table `detalle_compras`
--

CREATE TABLE `detalle_compras` (
  `cod_detallec` int(11) NOT NULL,
  `cod_compra` int(11) NOT NULL,
  `cod_detallep` int(11) NOT NULL,
  `cantidad` decimal(10,2) NOT NULL,
  `monto` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

-- --------------------------------------------------------

--
-- Table structure for table `detalle_descarga`
--

CREATE TABLE `detalle_descarga` (
  `cod_det_descarga` int(11) NOT NULL,
  `cod_detallep` int(11) NOT NULL,
  `cod_descarga` int(11) NOT NULL,
  `cantidad` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

-- --------------------------------------------------------

--
-- Table structure for table `detalle_pago_emitido`
--

CREATE TABLE `detalle_pago_emitido` (
  `cod_detallepagoe` int(11) NOT NULL,
  `cod_pago_emitido` int(11) NOT NULL,
  `cod_tipo_pagoe` int(11) NOT NULL,
  `monto` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish2_ci;

-- --------------------------------------------------------

--
-- Table structure for table `detalle_pago_recibido`
--

CREATE TABLE `detalle_pago_recibido` (
  `cod_detallepago` int(11) NOT NULL,
  `cod_pago` int(11) NOT NULL,
  `cod_tipo_pago` int(11) NOT NULL,
  `monto` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

-- --------------------------------------------------------

--
-- Table structure for table `detalle_productos`
--

CREATE TABLE `detalle_productos` (
  `cod_detallep` int(11) NOT NULL,
  `cod_presentacion` int(11) NOT NULL,
  `stock` float NOT NULL,
  `fecha_vencimiento` date DEFAULT NULL,
  `lote` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

-- -- Dumping data for table `detalle_productos`
--

INSERT INTO `detalle_productos` (`cod_detallep`, `cod_presentacion`, `stock`, `fecha_vencimiento`, `lote`) VALUES
(1, 1, 0, '0000-00-00', ''),
(2, 1, 8, '0000-00-00', ''),
(3, 1, 67, '0000-00-00', ''),
(4, 1, 23.5, '0000-00-00', '');

-- --------------------------------------------------------

--
-- Table structure for table `detalle_tipo_pago`
--

CREATE TABLE `detalle_tipo_pago` (
  `cod_tipo_pago` int(11) NOT NULL,
  `cod_metodo` int(11) NOT NULL,
  `tipo_moneda` int(11) NOT NULL,
  `cod_cuenta_bancaria` int(11) NOT NULL,
  `cod_detalle_caja` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish2_ci;

-- --------------------------------------------------------

--
-- Table structure for table `detalle_ventas`
--

CREATE TABLE `detalle_ventas` (
  `cod_detallev` int(11) NOT NULL,
  `cod_venta` int(11) NOT NULL,
  `cod_detallep` int(11) NOT NULL,
  `importe` decimal(10,2) NOT NULL,
  `cantidad` float(10,3) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

-- -- Dumping data for table `detalle_ventas`
--

INSERT INTO `detalle_ventas` (`cod_detallev`, `cod_venta`, `cod_detallep`, `importe`, `cantidad`) VALUES
(1, 7, 1, 70.00, 10.000),
(2, 7, 2, 28.00, 4.000);

-- --------------------------------------------------------

--
-- Table structure for table `detalle_vueltoe`
--

CREATE TABLE `detalle_vueltoe` (
  `cod_detallev` int(11) NOT NULL,
  `cod_vuelto` int(11) NOT NULL,
  `cod_tipo_pago` int(11) NOT NULL,
  `monto` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish2_ci;

-- --------------------------------------------------------

--
-- Table structure for table `detalle_vueltor`
--

CREATE TABLE `detalle_vueltor` (
  `cod_detallev_r` int(11) NOT NULL,
  `cod_vuelto_r` int(11) NOT NULL,
  `cod_tipo_pago` int(11) NOT NULL,
  `monto` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `divisas`
--

CREATE TABLE `divisas` (
  `cod_divisa` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `abreviatura` varchar(5) NOT NULL,
  `status` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

-- -- Dumping data for table `divisas`
--

INSERT INTO `divisas` (`cod_divisa`, `nombre`, `abreviatura`, `status`) VALUES
(1, 'Bolívares', 'Bs', 1),
(2, 'Dolares', '$', 1),
(3, '', 'EUR', 1);

-- --------------------------------------------------------

--
-- Table structure for table `empresa`
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

-- -- Dumping data for table `empresa`
--

INSERT INTO `empresa` (`rif`, `nombre`, `direccion`, `telefono`, `email`, `descripcion`, `logo`) VALUES
('J505284797', 'Quesera y Charcuteria Don Pedro 24', 'calle 60 entre carreras 12 y 13', '04245645108', 'queseradonpedro24@gmail.com', 'charcuteria', 'vista/dist/img/logo-icono2.png');

-- --------------------------------------------------------

--
-- Table structure for table `frecuencia_gasto`
--

CREATE TABLE `frecuencia_gasto` (
  `cod_frecuencia` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `dias` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `gasto`
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
-- Table structure for table `marcas`
--

CREATE TABLE `marcas` (
  `cod_marca` int(11) NOT NULL,
  `nombre` varchar(255) CHARACTER SET utf8 COLLATE utf8_spanish2_ci NOT NULL,
  `status` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- -- Dumping data for table `marcas`
--

INSERT INTO `marcas` (`cod_marca`, `nombre`, `status`) VALUES
(6, 'Polar', 0),
(7, 'Polara', 1);

-- --------------------------------------------------------

--
-- Table structure for table `movimientos`
--

CREATE TABLE `movimientos` (
  `cod_mov` int(11) NOT NULL,
  `cod_asiento` int(11) DEFAULT NULL,
  `tipo` int(11) NOT NULL,
  `descripcion` varchar(50) NOT NULL,
  `monto` decimal(18,2) DEFAULT NULL,
  `fecha` date NOT NULL,
  `status` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish2_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pago_emitido`
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
-- Table structure for table `pago_recibido`
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
-- Table structure for table `permisos`
--

CREATE TABLE `permisos` (
  `cod_permiso` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

-- -- Dumping data for table `permisos`
--

INSERT INTO `permisos` (`cod_permiso`, `nombre`) VALUES
(1, 'producto'),
(2, 'inventario'),
(3, 'categoria'),
(4, 'compra'),
(5, 'venta'),
(6, 'cliente'),
(7, 'proveedor'),
(8, 'usuario'),
(9, 'reporte'),
(10, 'configuracion'),
(11, 'marca'),
(12, 'finanzas');

-- --------------------------------------------------------

--
-- Table structure for table `presentacion_producto`
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

-- -- Dumping data for table `presentacion_producto`
--

INSERT INTO `presentacion_producto` (`cod_presentacion`, `cod_unidad`, `cod_producto`, `presentacion`, `cantidad_presentacion`, `costo`, `porcen_venta`, `excento`) VALUES
(1, 1, 1, 'pieza', '10', 7.00, 0, 1),
(5, 1, 5, 'pieza', '1kg', 11111111.00, 10, 1);

-- --------------------------------------------------------

--
-- Table structure for table `presupuestos`
--

CREATE TABLE `presupuestos` (
  `cod_presupuesto` int(11) NOT NULL,
  `categoria` int(11) DEFAULT NULL,
  `monto` decimal(10,2) NOT NULL,
  `mes` int(11) NOT NULL,
  `anio` int(11) NOT NULL,
  `notas` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `productos`
--

CREATE TABLE `productos` (
  `cod_producto` int(11) NOT NULL,
  `cod_categoria` int(11) NOT NULL,
  `cod_marca` int(11) DEFAULT NULL,
  `nombre` varchar(40) NOT NULL,
  `imagen` varchar(250) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

-- -- Dumping data for table `productos`
--

INSERT INTO `productos` (`cod_producto`, `cod_categoria`, `cod_marca`, `nombre`, `imagen`) VALUES
(1, 1, NULL, 'Queso Duro', NULL),
(5, 1, 7, 'Brian', 'vista/dist/img/productos/default.png');

-- --------------------------------------------------------

--
-- Table structure for table `proveedores`
--

CREATE TABLE `proveedores` (
  `cod_prov` int(11) NOT NULL,
  `rif` varchar(15) NOT NULL,
  `razon_social` varchar(50) NOT NULL,
  `email` varchar(50) DEFAULT NULL,
  `direccion` varchar(250) DEFAULT NULL,
  `status` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

-- -- Dumping data for table `proveedores`
--

INSERT INTO `proveedores` (`cod_prov`, `rif`, `razon_social`, `email`, `direccion`, `status`) VALUES
(1, 'J505284788', 'generico', '', '', 1);

-- --------------------------------------------------------

--
-- Table structure for table `prov_representantes`
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

-- --------------------------------------------------------

--
-- Table structure for table `proyecciones_futuras`
--

CREATE TABLE `proyecciones_futuras` (
  `cod_proyeccion` int(11) NOT NULL,
  `cod_producto` int(11) NOT NULL,
  `fecha_proyeccion` date NOT NULL,
  `periodo_inicio` date NOT NULL,
  `periodo_fin` date NOT NULL,
  `mes` date NOT NULL,
  `valor_proyectado` decimal(12,2) NOT NULL,
  `ventana_ma` int(11) NOT NULL COMMENT 'Tamaño de la ventana del promedio móvil en meses',
  `status` int(11) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

-- --------------------------------------------------------

--
-- Table structure for table `proyecciones_historicas`
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
  `ventana_ma` int(11) NOT NULL COMMENT 'Tamaño de la ventana del promedio móvil en meses',
  `status` int(11) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

-- --------------------------------------------------------

--
-- Table structure for table `stock_mensual`
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

-- -- Dumping data for table `stock_mensual`
--

INSERT INTO `stock_mensual` (`cod_stock`, `cod_detallep`, `mes`, `ano`, `stock_inicial`, `stock_final`, `ventas_cantidad`, `rotacion`, `dias_rotacion`) VALUES
(1, 1, '4', '2025', 0.00, 0.00, 10.00, 0.00, 0.00),
(2, 2, '4', '2025', 8.00, 8.00, 4.00, 0.50, 60.00),
(3, 3, '4', '2025', 67.00, 67.00, 0.00, 0.00, NULL),
(4, 4, '4', '2025', 23.50, 23.50, 0.00, 0.00, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tipo_cuenta`
--

CREATE TABLE `tipo_cuenta` (
  `cod_tipo_cuenta` int(11) NOT NULL,
  `nombre` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- -- Dumping data for table `tipo_cuenta`
--

INSERT INTO `tipo_cuenta` (`cod_tipo_cuenta`, `nombre`) VALUES
(1, 'AHORRO'),
(2, 'CORRIENTE');

-- --------------------------------------------------------

--
-- Table structure for table `tipo_gasto`
--

CREATE TABLE `tipo_gasto` (
  `cod_tipo_gasto` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tipo_pago`
--

CREATE TABLE `tipo_pago` (
  `cod_metodo` int(11) NOT NULL,
  `medio_pago` varchar(50) NOT NULL,
  `status` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

-- -- Dumping data for table `tipo_pago`
--

INSERT INTO `tipo_pago` (`cod_metodo`, `medio_pago`, `status`) VALUES
(1, 'Efectivo en Bs', 1),
(2, 'Efectivo USD', 1);

-- --------------------------------------------------------

--
-- Table structure for table `tipo_usuario`
--

CREATE TABLE `tipo_usuario` (
  `cod_tipo_usuario` int(11) NOT NULL,
  `rol` varchar(50) NOT NULL,
  `status` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

-- -- Dumping data for table `tipo_usuario`
--

INSERT INTO `tipo_usuario` (`cod_tipo_usuario`, `rol`, `status`) VALUES
(1, 'Administrador', 1);

-- --------------------------------------------------------

--
-- Table structure for table `tlf_proveedores`
--

CREATE TABLE `tlf_proveedores` (
  `cod_tlf` int(11) NOT NULL,
  `cod_prov` int(11) NOT NULL,
  `telefono` varchar(12) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tpu_permisos`
--

CREATE TABLE `tpu_permisos` (
  `cod_tipo_usuario` int(11) NOT NULL,
  `cod_permiso` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

-- -- Dumping data for table `tpu_permisos`
--

INSERT INTO `tpu_permisos` (`cod_tipo_usuario`, `cod_permiso`) VALUES
(1, 1),
(1, 2),
(1, 3),
(1, 4),
(1, 5),
(1, 6),
(1, 7),
(1, 8),
(1, 9),
(1, 10),
(1, 1),
(1, 2),
(1, 3),
(1, 4),
(1, 5),
(1, 6),
(1, 7),
(1, 8),
(1, 9),
(1, 10),
(1, 11),
(1, 12);

-- --------------------------------------------------------

--
-- Table structure for table `unidades_medida`
--

CREATE TABLE `unidades_medida` (
  `cod_unidad` int(11) NOT NULL,
  `tipo_medida` char(10) NOT NULL,
  `status` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

-- -- Dumping data for table `unidades_medida`
--

INSERT INTO `unidades_medida` (`cod_unidad`, `tipo_medida`, `status`) VALUES
(1, 'kg', 1);

-- --------------------------------------------------------

--
-- Table structure for table `usuarios`
--

CREATE TABLE `usuarios` (
  `cod_usuario` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `user` varchar(20) NOT NULL,
  `password` varchar(255) NOT NULL,
  `cod_tipo_usuario` int(11) NOT NULL,
  `status` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

-- -- Dumping data for table `usuarios`
--

INSERT INTO `usuarios` (`cod_usuario`, `nombre`, `user`, `password`, `cod_tipo_usuario`, `status`) VALUES
(1, 'Administrador', 'admin', '$2y$10$.nbh0vwGWNkBgsVzkBSoYurftn9Mg.TLYkxmK32KhMKOzaTjaRS3.', 1, 1),
(2, 'daniel rojas', 'daniel', '$2y$10$e/.eV/tI1okLcangCrPa3ujb56rWAu0VhiD9MPF5ZkLOuWRi2dY2e', 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `ventas`
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

-- -- Dumping data for table `ventas`
--

INSERT INTO `ventas` (`cod_venta`, `cod_cliente`, `condicion_pago`, `fecha_vencimiento`, `total`, `fecha`, `status`) VALUES
(7, 1, 'contado', NULL, 98.00, '2025-04-08 20:03:36', 3);

-- --------------------------------------------------------

--
-- Table structure for table `vuelto_emitido`
--

CREATE TABLE `vuelto_emitido` (
  `cod_vuelto` int(11) NOT NULL,
  `vuelto_total` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish2_ci;

-- --------------------------------------------------------

--
-- Table structure for table `vuelto_recibido`
--

CREATE TABLE `vuelto_recibido` (
  `cod_vuelto_r` int(11) NOT NULL,
  `vuelto_total` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `analisis_rentabilidad`
--
ALTER TABLE `analisis_rentabilidad`
  ADD PRIMARY KEY (`cod_analisis`),
  ADD KEY `cod_detallep` (`cod_detallep`);

--
-- Indexes for table `asientos_contables`
--
ALTER TABLE `asientos_contables`
  ADD PRIMARY KEY (`cod_asiento`);

--
-- Indexes for table `banco`
--
ALTER TABLE `banco`
  ADD PRIMARY KEY (`cod_banco`);

--
-- Indexes for table `bitacora`
--
ALTER TABLE `bitacora`
  ADD PRIMARY KEY (`id`),
  ADD KEY `cod_usuario` (`cod_usuario`);

--
-- Indexes for table `caja`
--
ALTER TABLE `caja`
  ADD PRIMARY KEY (`cod_caja`);

--
-- Indexes for table `cambio_divisa`
--
ALTER TABLE `cambio_divisa`
  ADD PRIMARY KEY (`cod_cambio`),
  ADD KEY `cambiodivisa-divisa` (`cod_divisa`);

--
-- Indexes for table `carga`
--
ALTER TABLE `carga`
  ADD PRIMARY KEY (`cod_carga`);

--
-- Indexes for table `categorias`
--
ALTER TABLE `categorias`
  ADD PRIMARY KEY (`cod_categoria`);

--
-- Indexes for table `categoria_gasto`
--
ALTER TABLE `categoria_gasto`
  ADD PRIMARY KEY (`cod_cat_gasto`),
  ADD KEY `cod_tipo_gasto` (`cod_tipo_gasto`),
  ADD KEY `cod_frecuencia` (`cod_frecuencia`);

--
-- Indexes for table `categoria_movimiento`
--
ALTER TABLE `categoria_movimiento`
  ADD PRIMARY KEY (`cod_categoria`);

--
-- Indexes for table `clientes`
--
ALTER TABLE `clientes`
  ADD PRIMARY KEY (`cod_cliente`);

--
-- Indexes for table `compras`
--
ALTER TABLE `compras`
  ADD PRIMARY KEY (`cod_compra`),
  ADD KEY `compras-proveedores` (`cod_prov`);

--
-- Indexes for table `conciliacion`
--
ALTER TABLE `conciliacion`
  ADD KEY `cod_cuenta_bancaria` (`cod_cuenta_bancaria`);

--
-- Indexes for table `cuentas_contables`
--
ALTER TABLE `cuentas_contables`
  ADD PRIMARY KEY (`cod_cuenta`),
  ADD UNIQUE KEY `codigo_contable` (`codigo_contable`),
  ADD KEY `cuenta_padreid` (`cuenta_padreid`);

--
-- Indexes for table `cuenta_bancaria`
--
ALTER TABLE `cuenta_bancaria`
  ADD PRIMARY KEY (`cod_cuenta_bancaria`),
  ADD KEY `cod_banco` (`cod_banco`),
  ADD KEY `cod_tipo_cuenta` (`cod_tipo_cuenta`),
  ADD KEY `cod_divisa` (`cod_divisa`);

--
-- Indexes for table `descarga`
--
ALTER TABLE `descarga`
  ADD PRIMARY KEY (`cod_descarga`);

--
-- Indexes for table `detalle_asientos`
--
ALTER TABLE `detalle_asientos`
  ADD PRIMARY KEY (`cod_det_asiento`),
  ADD KEY `asiento_id` (`cod_asiento`),
  ADD KEY `cuenta_id` (`cod_cuenta`);

--
-- Indexes for table `detalle_caja`
--
ALTER TABLE `detalle_caja`
  ADD PRIMARY KEY (`cod_detalle_caja`),
  ADD KEY `cod_caja` (`cod_caja`),
  ADD KEY `cod_divisas` (`cod_divisas`);

--
-- Indexes for table `detalle_carga`
--
ALTER TABLE `detalle_carga`
  ADD PRIMARY KEY (`cod_det_carga`),
  ADD KEY `detalle_carga-carga` (`cod_carga`),
  ADD KEY `detalle_carga-detallep` (`cod_detallep`);

--
-- Indexes for table `detalle_compras`
--
ALTER TABLE `detalle_compras`
  ADD PRIMARY KEY (`cod_detallec`),
  ADD KEY `detalle_compras-compras` (`cod_compra`),
  ADD KEY `detalle_compras-detalle_productos` (`cod_detallep`);

--
-- Indexes for table `detalle_descarga`
--
ALTER TABLE `detalle_descarga`
  ADD PRIMARY KEY (`cod_det_descarga`),
  ADD KEY `detalle_descarga-detallep` (`cod_detallep`),
  ADD KEY `detalle_descarga-descarga` (`cod_descarga`);

--
-- Indexes for table `detalle_pago_emitido`
--
ALTER TABLE `detalle_pago_emitido`
  ADD PRIMARY KEY (`cod_detallepagoe`),
  ADD KEY `pagoe-dtpagoe` (`cod_pago_emitido`),
  ADD KEY `dtpagoe-tipopagoe` (`cod_tipo_pagoe`);

--
-- Indexes for table `detalle_pago_recibido`
--
ALTER TABLE `detalle_pago_recibido`
  ADD PRIMARY KEY (`cod_detallepago`),
  ADD KEY `detalle_pago-pago` (`cod_pago`),
  ADD KEY `tipo_pago-detalle_pago` (`cod_tipo_pago`);

--
-- Indexes for table `detalle_productos`
--
ALTER TABLE `detalle_productos`
  ADD PRIMARY KEY (`cod_detallep`),
  ADD KEY `detalle_producto-productos` (`cod_presentacion`);

--
-- Indexes for table `detalle_tipo_pago`
--
ALTER TABLE `detalle_tipo_pago`
  ADD PRIMARY KEY (`cod_tipo_pago`),
  ADD KEY `cod_cuenta_bancaria` (`cod_cuenta_bancaria`),
  ADD KEY `cod_detalle_caja` (`cod_detalle_caja`),
  ADD KEY `cod_metodo` (`cod_metodo`);

--
-- Indexes for table `detalle_ventas`
--
ALTER TABLE `detalle_ventas`
  ADD PRIMARY KEY (`cod_detallev`),
  ADD KEY `cod_venta` (`cod_venta`),
  ADD KEY `detalle_ventas-detalle_productos` (`cod_detallep`);

--
-- Indexes for table `detalle_vueltoe`
--
ALTER TABLE `detalle_vueltoe`
  ADD PRIMARY KEY (`cod_detallev`),
  ADD KEY `cod_vuelto` (`cod_vuelto`),
  ADD KEY `cod_tipo_pago` (`cod_tipo_pago`);

--
-- Indexes for table `detalle_vueltor`
--
ALTER TABLE `detalle_vueltor`
  ADD PRIMARY KEY (`cod_detallev_r`),
  ADD KEY `cod_vuelto_r` (`cod_vuelto_r`),
  ADD KEY `cod_tipo_pago` (`cod_tipo_pago`);

--
-- Indexes for table `divisas`
--
ALTER TABLE `divisas`
  ADD PRIMARY KEY (`cod_divisa`);

--
-- Indexes for table `empresa`
--
ALTER TABLE `empresa`
  ADD PRIMARY KEY (`rif`);

--
-- Indexes for table `frecuencia_gasto`
--
ALTER TABLE `frecuencia_gasto`
  ADD PRIMARY KEY (`cod_frecuencia`);

--
-- Indexes for table `gasto`
--
ALTER TABLE `gasto`
  ADD PRIMARY KEY (`cod_gasto`),
  ADD KEY `cod_cat_gasto` (`cod_cat_gasto`);

--
-- Indexes for table `marcas`
--
ALTER TABLE `marcas`
  ADD PRIMARY KEY (`cod_marca`),
  ADD UNIQUE KEY `marca_unica` (`nombre`);

--
-- Indexes for table `movimientos`
--
ALTER TABLE `movimientos`
  ADD PRIMARY KEY (`cod_mov`),
  ADD KEY `asiento_id` (`cod_asiento`);

--
-- Indexes for table `pago_emitido`
--
ALTER TABLE `pago_emitido`
  ADD PRIMARY KEY (`cod_pago_emitido`),
  ADD KEY `compra-pago` (`cod_compra`),
  ADD KEY `cod_gasto` (`cod_gasto`),
  ADD KEY `cod_vuelto_r` (`cod_vuelto_r`);

--
-- Indexes for table `pago_recibido`
--
ALTER TABLE `pago_recibido`
  ADD PRIMARY KEY (`cod_pago`),
  ADD KEY `pagos-ventas` (`cod_venta`),
  ADD KEY `cod_vuelto` (`cod_vuelto`);

--
-- Indexes for table `permisos`
--
ALTER TABLE `permisos`
  ADD PRIMARY KEY (`cod_permiso`);

--
-- Indexes for table `presentacion_producto`
--
ALTER TABLE `presentacion_producto`
  ADD PRIMARY KEY (`cod_presentacion`),
  ADD KEY `cod_producto` (`cod_producto`),
  ADD KEY `cod_unidad` (`cod_unidad`);

--
-- Indexes for table `presupuestos`
--
ALTER TABLE `presupuestos`
  ADD PRIMARY KEY (`cod_presupuesto`),
  ADD KEY `categoria` (`categoria`);

--
-- Indexes for table `productos`
--
ALTER TABLE `productos`
  ADD PRIMARY KEY (`cod_producto`),
  ADD KEY `productos-categorias` (`cod_categoria`),
  ADD KEY `cod_marca` (`cod_marca`);

--
-- Indexes for table `proveedores`
--
ALTER TABLE `proveedores`
  ADD PRIMARY KEY (`cod_prov`);

--
-- Indexes for table `prov_representantes`
--
ALTER TABLE `prov_representantes`
  ADD PRIMARY KEY (`cod_representante`),
  ADD KEY `prov_representantes_ibfk_1` (`cod_prov`);

--
-- Indexes for table `proyecciones_futuras`
--
ALTER TABLE `proyecciones_futuras`
  ADD PRIMARY KEY (`cod_proyeccion`),
  ADD KEY `fk_proyeccion_producto` (`cod_producto`);

--
-- Indexes for table `proyecciones_historicas`
--
ALTER TABLE `proyecciones_historicas`
  ADD PRIMARY KEY (`cod_historico`),
  ADD KEY `fk_historico_producto` (`cod_producto`);

--
-- Indexes for table `stock_mensual`
--
ALTER TABLE `stock_mensual`
  ADD PRIMARY KEY (`cod_stock`),
  ADD UNIQUE KEY `unique_stock_mensual` (`cod_detallep`,`mes`,`ano`),
  ADD KEY `cod_detallep` (`cod_detallep`);

--
-- Indexes for table `tipo_cuenta`
--
ALTER TABLE `tipo_cuenta`
  ADD PRIMARY KEY (`cod_tipo_cuenta`);

--
-- Indexes for table `tipo_gasto`
--
ALTER TABLE `tipo_gasto`
  ADD PRIMARY KEY (`cod_tipo_gasto`);

--
-- Indexes for table `tipo_pago`
--
ALTER TABLE `tipo_pago`
  ADD PRIMARY KEY (`cod_metodo`);

--
-- Indexes for table `tipo_usuario`
--
ALTER TABLE `tipo_usuario`
  ADD PRIMARY KEY (`cod_tipo_usuario`);

--
-- Indexes for table `tlf_proveedores`
--
ALTER TABLE `tlf_proveedores`
  ADD PRIMARY KEY (`cod_tlf`),
  ADD KEY `cod_prov` (`cod_prov`);

--
-- Indexes for table `tpu_permisos`
--
ALTER TABLE `tpu_permisos`
  ADD KEY `cod_tipo_usuario` (`cod_tipo_usuario`),
  ADD KEY `cod_permiso` (`cod_permiso`);

--
-- Indexes for table `unidades_medida`
--
ALTER TABLE `unidades_medida`
  ADD PRIMARY KEY (`cod_unidad`);

--
-- Indexes for table `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`cod_usuario`),
  ADD UNIQUE KEY `user` (`user`),
  ADD KEY `usuario-tipousuario` (`cod_tipo_usuario`);

--
-- Indexes for table `ventas`
--
ALTER TABLE `ventas`
  ADD PRIMARY KEY (`cod_venta`),
  ADD KEY `ventas-clientes` (`cod_cliente`);

--
-- Indexes for table `vuelto_emitido`
--
ALTER TABLE `vuelto_emitido`
  ADD PRIMARY KEY (`cod_vuelto`);

--
-- Indexes for table `vuelto_recibido`
--
ALTER TABLE `vuelto_recibido`
  ADD PRIMARY KEY (`cod_vuelto_r`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `analisis_rentabilidad`
--
ALTER TABLE `analisis_rentabilidad`
  MODIFY `cod_analisis` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `asientos_contables`
--
ALTER TABLE `asientos_contables`
  MODIFY `cod_asiento` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `banco`
--
ALTER TABLE `banco`
  MODIFY `cod_banco` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `bitacora`
--
ALTER TABLE `bitacora`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=81;

--
-- AUTO_INCREMENT for table `caja`
--
ALTER TABLE `caja`
  MODIFY `cod_caja` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `cambio_divisa`
--
ALTER TABLE `cambio_divisa`
  MODIFY `cod_cambio` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `carga`
--
ALTER TABLE `carga`
  MODIFY `cod_carga` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `categorias`
--
ALTER TABLE `categorias`
  MODIFY `cod_categoria` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `categoria_gasto`
--
ALTER TABLE `categoria_gasto`
  MODIFY `cod_cat_gasto` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `categoria_movimiento`
--
ALTER TABLE `categoria_movimiento`
  MODIFY `cod_categoria` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `clientes`
--
ALTER TABLE `clientes`
  MODIFY `cod_cliente` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `compras`
--
ALTER TABLE `compras`
  MODIFY `cod_compra` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `cuentas_contables`
--
ALTER TABLE `cuentas_contables`
  MODIFY `cod_cuenta` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `cuenta_bancaria`
--
ALTER TABLE `cuenta_bancaria`
  MODIFY `cod_cuenta_bancaria` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `descarga`
--
ALTER TABLE `descarga`
  MODIFY `cod_descarga` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `detalle_asientos`
--
ALTER TABLE `detalle_asientos`
  MODIFY `cod_det_asiento` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `detalle_caja`
--
ALTER TABLE `detalle_caja`
  MODIFY `cod_detalle_caja` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `detalle_carga`
--
ALTER TABLE `detalle_carga`
  MODIFY `cod_det_carga` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `detalle_compras`
--
ALTER TABLE `detalle_compras`
  MODIFY `cod_detallec` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `detalle_descarga`
--
ALTER TABLE `detalle_descarga`
  MODIFY `cod_det_descarga` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `detalle_pago_emitido`
--
ALTER TABLE `detalle_pago_emitido`
  MODIFY `cod_detallepagoe` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `detalle_pago_recibido`
--
ALTER TABLE `detalle_pago_recibido`
  MODIFY `cod_detallepago` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `detalle_productos`
--
ALTER TABLE `detalle_productos`
  MODIFY `cod_detallep` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `detalle_ventas`
--
ALTER TABLE `detalle_ventas`
  MODIFY `cod_detallev` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `detalle_vueltoe`
--
ALTER TABLE `detalle_vueltoe`
  MODIFY `cod_detallev` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `detalle_vueltor`
--
ALTER TABLE `detalle_vueltor`
  MODIFY `cod_detallev_r` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `divisas`
--
ALTER TABLE `divisas`
  MODIFY `cod_divisa` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `frecuencia_gasto`
--
ALTER TABLE `frecuencia_gasto`
  MODIFY `cod_frecuencia` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `gasto`
--
ALTER TABLE `gasto`
  MODIFY `cod_gasto` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `marcas`
--
ALTER TABLE `marcas`
  MODIFY `cod_marca` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `movimientos`
--
ALTER TABLE `movimientos`
  MODIFY `cod_mov` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pago_emitido`
--
ALTER TABLE `pago_emitido`
  MODIFY `cod_pago_emitido` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pago_recibido`
--
ALTER TABLE `pago_recibido`
  MODIFY `cod_pago` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `permisos`
--
ALTER TABLE `permisos`
  MODIFY `cod_permiso` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `presentacion_producto`
--
ALTER TABLE `presentacion_producto`
  MODIFY `cod_presentacion` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `presupuestos`
--
ALTER TABLE `presupuestos`
  MODIFY `cod_presupuesto` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `productos`
--
ALTER TABLE `productos`
  MODIFY `cod_producto` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `proveedores`
--
ALTER TABLE `proveedores`
  MODIFY `cod_prov` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `prov_representantes`
--
ALTER TABLE `prov_representantes`
  MODIFY `cod_representante` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `proyecciones_futuras`
--
ALTER TABLE `proyecciones_futuras`
  MODIFY `cod_proyeccion` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `proyecciones_historicas`
--
ALTER TABLE `proyecciones_historicas`
  MODIFY `cod_historico` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `stock_mensual`
--
ALTER TABLE `stock_mensual`
  MODIFY `cod_stock` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `tipo_gasto`
--
ALTER TABLE `tipo_gasto`
  MODIFY `cod_tipo_gasto` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tipo_pago`
--
ALTER TABLE `tipo_pago`
  MODIFY `cod_metodo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `tipo_usuario`
--
ALTER TABLE `tipo_usuario`
  MODIFY `cod_tipo_usuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tlf_proveedores`
--
ALTER TABLE `tlf_proveedores`
  MODIFY `cod_tlf` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `unidades_medida`
--
ALTER TABLE `unidades_medida`
  MODIFY `cod_unidad` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `cod_usuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `ventas`
--
ALTER TABLE `ventas`
  MODIFY `cod_venta` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `vuelto_emitido`
--
ALTER TABLE `vuelto_emitido`
  MODIFY `cod_vuelto` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `vuelto_recibido`
--
ALTER TABLE `vuelto_recibido`
  MODIFY `cod_vuelto_r` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `analisis_rentabilidad`
--
ALTER TABLE `analisis_rentabilidad`
  ADD CONSTRAINT `analisis_rentabilidad_ibfk_1` FOREIGN KEY (`cod_detallep`) REFERENCES `detalle_productos` (`cod_detallep`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `bitacora`
--
ALTER TABLE `bitacora`
  ADD CONSTRAINT `bitacora_ibfk_1` FOREIGN KEY (`cod_usuario`) REFERENCES `usuarios` (`cod_usuario`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `cambio_divisa`
--
ALTER TABLE `cambio_divisa`
  ADD CONSTRAINT `cambiodivisa-divisa` FOREIGN KEY (`cod_divisa`) REFERENCES `divisas` (`cod_divisa`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `categoria_gasto`
--
ALTER TABLE `categoria_gasto`
  ADD CONSTRAINT `categoria_gasto_ibfk_1` FOREIGN KEY (`cod_tipo_gasto`) REFERENCES `tipo_gasto` (`cod_tipo_gasto`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `categoria_gasto_ibfk_2` FOREIGN KEY (`cod_frecuencia`) REFERENCES `frecuencia_gasto` (`cod_frecuencia`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `compras`
--
ALTER TABLE `compras`
  ADD CONSTRAINT `compras-proveedores` FOREIGN KEY (`cod_prov`) REFERENCES `proveedores` (`cod_prov`) ON UPDATE CASCADE;

--
-- Constraints for table `conciliacion`
--
ALTER TABLE `conciliacion`
  ADD CONSTRAINT `conciliacion_ibfk_1` FOREIGN KEY (`cod_cuenta_bancaria`) REFERENCES `cuenta_bancaria` (`cod_cuenta_bancaria`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `cuentas_contables`
--
ALTER TABLE `cuentas_contables`
  ADD CONSTRAINT `cuentas_contables_ibfk_1` FOREIGN KEY (`cuenta_padreid`) REFERENCES `cuentas_contables` (`cod_cuenta`);

--
-- Constraints for table `cuenta_bancaria`
--
ALTER TABLE `cuenta_bancaria`
  ADD CONSTRAINT `cuenta_bancaria_ibfk_1` FOREIGN KEY (`cod_banco`) REFERENCES `banco` (`cod_banco`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `cuenta_bancaria_ibfk_2` FOREIGN KEY (`cod_tipo_cuenta`) REFERENCES `tipo_cuenta` (`cod_tipo_cuenta`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `cuenta_bancaria_ibfk_3` FOREIGN KEY (`cod_divisa`) REFERENCES `cambio_divisa` (`cod_cambio`);

--
-- Constraints for table `detalle_asientos`
--
ALTER TABLE `detalle_asientos`
  ADD CONSTRAINT `detalle_asientos_ibfk_1` FOREIGN KEY (`cod_asiento`) REFERENCES `asientos_contables` (`cod_asiento`) ON DELETE CASCADE,
  ADD CONSTRAINT `detalle_asientos_ibfk_2` FOREIGN KEY (`cod_cuenta`) REFERENCES `cuentas_contables` (`cod_cuenta`) ON DELETE CASCADE;

--
-- Constraints for table `detalle_caja`
--
ALTER TABLE `detalle_caja`
  ADD CONSTRAINT `detalle_caja_ibfk_1` FOREIGN KEY (`cod_caja`) REFERENCES `caja` (`cod_caja`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `detalle_caja_ibfk_2` FOREIGN KEY (`cod_divisas`) REFERENCES `cambio_divisa` (`cod_cambio`);

--
-- Constraints for table `detalle_carga`
--
ALTER TABLE `detalle_carga`
  ADD CONSTRAINT `detalle_carga-carga` FOREIGN KEY (`cod_carga`) REFERENCES `carga` (`cod_carga`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `detalle_carga-detallep` FOREIGN KEY (`cod_detallep`) REFERENCES `detalle_productos` (`cod_detallep`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `detalle_compras`
--
ALTER TABLE `detalle_compras`
  ADD CONSTRAINT `detalle_compras-compras` FOREIGN KEY (`cod_compra`) REFERENCES `compras` (`cod_compra`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `detalle_compras-detalle_productos` FOREIGN KEY (`cod_detallep`) REFERENCES `detalle_productos` (`cod_detallep`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `detalle_descarga`
--
ALTER TABLE `detalle_descarga`
  ADD CONSTRAINT `detalle_descarga-descarga` FOREIGN KEY (`cod_descarga`) REFERENCES `descarga` (`cod_descarga`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `detalle_descarga-detallep` FOREIGN KEY (`cod_detallep`) REFERENCES `detalle_productos` (`cod_detallep`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `detalle_pago_emitido`
--
ALTER TABLE `detalle_pago_emitido`
  ADD CONSTRAINT `detalle_pago_emitido_ibfk_1` FOREIGN KEY (`cod_pago_emitido`) REFERENCES `pago_emitido` (`cod_pago_emitido`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `detalle_pago_emitido_ibfk_2` FOREIGN KEY (`cod_tipo_pagoe`) REFERENCES `detalle_tipo_pago` (`cod_tipo_pago`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `detalle_pago_recibido`
--
ALTER TABLE `detalle_pago_recibido`
  ADD CONSTRAINT `detalle_pago_recibido_ibfk_1` FOREIGN KEY (`cod_pago`) REFERENCES `pago_recibido` (`cod_pago`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `detalle_pago_recibido_ibfk_2` FOREIGN KEY (`cod_tipo_pago`) REFERENCES `detalle_tipo_pago` (`cod_tipo_pago`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `detalle_productos`
--
ALTER TABLE `detalle_productos`
  ADD CONSTRAINT `detalle_productos_ibfk_1` FOREIGN KEY (`cod_presentacion`) REFERENCES `presentacion_producto` (`cod_presentacion`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `detalle_tipo_pago`
--
ALTER TABLE `detalle_tipo_pago`
  ADD CONSTRAINT `detalle_tipo_pago_ibfk_1` FOREIGN KEY (`cod_cuenta_bancaria`) REFERENCES `cuenta_bancaria` (`cod_cuenta_bancaria`),
  ADD CONSTRAINT `detalle_tipo_pago_ibfk_2` FOREIGN KEY (`cod_detalle_caja`) REFERENCES `detalle_caja` (`cod_detalle_caja`),
  ADD CONSTRAINT `detalle_tipo_pago_ibfk_3` FOREIGN KEY (`cod_metodo`) REFERENCES `tipo_pago` (`cod_metodo`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `detalle_ventas`
--
ALTER TABLE `detalle_ventas`
  ADD CONSTRAINT `detalle_ventas-detalle_productos` FOREIGN KEY (`cod_detallep`) REFERENCES `detalle_productos` (`cod_detallep`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `detalle_ventas_ibfk_1` FOREIGN KEY (`cod_venta`) REFERENCES `ventas` (`cod_venta`);

--
-- Constraints for table `detalle_vueltoe`
--
ALTER TABLE `detalle_vueltoe`
  ADD CONSTRAINT `detalle_vueltoe_ibfk_1` FOREIGN KEY (`cod_vuelto`) REFERENCES `vuelto_emitido` (`cod_vuelto`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `detalle_vueltoe_ibfk_2` FOREIGN KEY (`cod_tipo_pago`) REFERENCES `detalle_tipo_pago` (`cod_tipo_pago`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `detalle_vueltor`
--
ALTER TABLE `detalle_vueltor`
  ADD CONSTRAINT `detalle_vueltor_ibfk_1` FOREIGN KEY (`cod_vuelto_r`) REFERENCES `vuelto_recibido` (`cod_vuelto_r`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `detalle_vueltor_ibfk_2` FOREIGN KEY (`cod_tipo_pago`) REFERENCES `detalle_tipo_pago` (`cod_tipo_pago`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `gasto`
--
ALTER TABLE `gasto`
  ADD CONSTRAINT `gasto_ibfk_1` FOREIGN KEY (`cod_cat_gasto`) REFERENCES `categoria_gasto` (`cod_cat_gasto`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `movimientos`
--
ALTER TABLE `movimientos`
  ADD CONSTRAINT `movimientos_ibfk_1` FOREIGN KEY (`cod_asiento`) REFERENCES `asientos_contables` (`cod_asiento`);

--
-- Constraints for table `pago_emitido`
--
ALTER TABLE `pago_emitido`
  ADD CONSTRAINT `pago_emitido_ibfk_1` FOREIGN KEY (`cod_compra`) REFERENCES `compras` (`cod_compra`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `pago_emitido_ibfk_2` FOREIGN KEY (`cod_gasto`) REFERENCES `gasto` (`cod_gasto`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `pago_emitido_ibfk_3` FOREIGN KEY (`cod_vuelto_r`) REFERENCES `vuelto_recibido` (`cod_vuelto_r`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `pago_recibido`
--
ALTER TABLE `pago_recibido`
  ADD CONSTRAINT `pago_recibido_ibfk_1` FOREIGN KEY (`cod_venta`) REFERENCES `ventas` (`cod_venta`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `pago_recibido_ibfk_2` FOREIGN KEY (`cod_vuelto`) REFERENCES `vuelto_emitido` (`cod_vuelto`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `presentacion_producto`
--
ALTER TABLE `presentacion_producto`
  ADD CONSTRAINT `presentacion_producto_ibfk_1` FOREIGN KEY (`cod_producto`) REFERENCES `productos` (`cod_producto`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `presentacion_producto_ibfk_2` FOREIGN KEY (`cod_unidad`) REFERENCES `unidades_medida` (`cod_unidad`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `presupuestos`
--
ALTER TABLE `presupuestos`
  ADD CONSTRAINT `presupuestos_ibfk_1` FOREIGN KEY (`categoria`) REFERENCES `categoria_movimiento` (`cod_categoria`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `productos`
--
ALTER TABLE `productos`
  ADD CONSTRAINT `productos-categorias` FOREIGN KEY (`cod_categoria`) REFERENCES `categorias` (`cod_categoria`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `productos_ibfk_1` FOREIGN KEY (`cod_marca`) REFERENCES `marcas` (`cod_marca`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `prov_representantes`
--
ALTER TABLE `prov_representantes`
  ADD CONSTRAINT `prov_representantes_ibfk_1` FOREIGN KEY (`cod_prov`) REFERENCES `proveedores` (`cod_prov`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `proyecciones_futuras`
--
ALTER TABLE `proyecciones_futuras`
  ADD CONSTRAINT `fk_proyeccion_producto` FOREIGN KEY (`cod_producto`) REFERENCES `productos` (`cod_producto`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `proyecciones_historicas`
--
ALTER TABLE `proyecciones_historicas`
  ADD CONSTRAINT `fk_historico_producto` FOREIGN KEY (`cod_producto`) REFERENCES `productos` (`cod_producto`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `stock_mensual`
--
ALTER TABLE `stock_mensual`
  ADD CONSTRAINT `stock_mensual_ibfk_1` FOREIGN KEY (`cod_detallep`) REFERENCES `detalle_productos` (`cod_detallep`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `tlf_proveedores`
--
ALTER TABLE `tlf_proveedores`
  ADD CONSTRAINT `tlf_proveedores_ibfk_1` FOREIGN KEY (`cod_prov`) REFERENCES `proveedores` (`cod_prov`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `tpu_permisos`
--
ALTER TABLE `tpu_permisos`
  ADD CONSTRAINT `tpu_permisos_ibfk_1` FOREIGN KEY (`cod_tipo_usuario`) REFERENCES `tipo_usuario` (`cod_tipo_usuario`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `tpu_permisos_ibfk_2` FOREIGN KEY (`cod_permiso`) REFERENCES `permisos` (`cod_permiso`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `usuarios`
--
ALTER TABLE `usuarios`
  ADD CONSTRAINT `usuario-tipousuario` FOREIGN KEY (`cod_tipo_usuario`) REFERENCES `tipo_usuario` (`cod_tipo_usuario`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `ventas`
--
ALTER TABLE `ventas`
  ADD CONSTRAINT `ventas-clientes` FOREIGN KEY (`cod_cliente`) REFERENCES `clientes` (`cod_cliente`) ON DELETE CASCADE ON UPDATE CASCADE;

DELIMITER $$
--
-- Events
--
CREATE DEFINER=`root`@`localhost` EVENT `generar_stock_mensual_event` ON SCHEDULE EVERY 1 MONTH STARTS '2025-05-29 23:59:00' ON COMPLETION NOT PRESERVE ENABLE DO CALL generar_stock_mensual()$$

DELIMITER ;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
