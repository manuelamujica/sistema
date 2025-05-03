-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 01-05-2025 a las 04:04:03
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
-- Base de datos: `seguridad`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `bitacora`
--

CREATE TABLE `bitacora` (
  `id` int(11) NOT NULL,
  `cod_usuario` int(11) NOT NULL,
  `accion` varchar(255) NOT NULL,
  `fecha` timestamp NOT NULL DEFAULT current_timestamp(),
  `detalles` text DEFAULT NULL,
  `modulo` varchar(220) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `bitacora`
--

INSERT INTO `bitacora` (`id`, `cod_usuario`, `accion`, `fecha`, `detalles`, `modulo`) VALUES
(49, 1, 'Acceso al sistema', '2025-04-18 23:39:42', 'admin', 'Inicio'),
(50, 1, 'Acceso a Ventas', '2025-04-18 23:46:53', '', 'Ventas'),
(51, 1, 'Acceso a Compras', '2025-04-18 23:46:57', '', 'Compras'),
(52, 1, 'Acceso a Ventas', '2025-04-18 23:46:58', '', 'Ventas'),
(53, 1, 'Acceso a Compras', '2025-04-18 23:47:00', '', 'Compras'),
(54, 1, 'Acceso a Ventas', '2025-04-18 23:47:01', '', 'Ventas'),
(55, 1, 'Acceso a Ventas', '2025-04-18 23:48:24', '', 'Ventas'),
(56, 1, 'Acceso a Compras', '2025-04-18 23:48:25', '', 'Compras'),
(57, 1, 'Acceso a Ventas', '2025-04-18 23:48:36', '', 'Ventas'),
(58, 1, 'Acceso a Compras', '2025-04-18 23:50:00', '', 'Compras'),
(59, 1, 'Acceso a Ventas', '2025-04-18 23:50:03', '', 'Ventas'),
(60, 1, 'Acceso a Compras', '2025-04-18 23:50:04', '', 'Compras'),
(61, 1, 'Acceso a Divisas', '2025-04-18 23:50:37', '', 'Divisas'),
(62, 1, 'Acceso a Tipos de pago', '2025-04-19 00:52:29', '', 'Tipos de pago'),
(63, 1, 'Acceso al sistema', '2025-04-25 02:36:12', 'admin', 'Inicio'),
(64, 1, 'Acceso a Ajuste de roles', '2025-04-25 02:36:22', '', 'Ajuste de roles'),
(65, 1, 'Acceso a Compras', '2025-04-25 15:16:46', '', 'Compras'),
(66, 2, 'Acceso a Tipos de pago', '2025-04-26 05:23:22', '', 'Tipos de pago'),
(67, 2, 'Acceso a Ajuste de INventario', '2025-04-26 05:29:18', '', 'Ajuste de INventario'),
(68, 2, 'Acceso a Carga de productos', '2025-04-26 05:29:21', '', 'Carga de productos'),
(69, 2, 'Acceso a Compras', '2025-04-26 05:29:24', '', 'Compras'),
(70, 2, 'Acceso a Compras', '2025-04-26 05:29:27', '', 'Compras'),
(71, 2, 'Acceso a Ventas', '2025-04-26 05:29:28', '', 'Ventas'),
(72, 2, 'Acceso a Reporte De proveedores', '2025-04-26 05:31:57', '', 'Reporte De proveedores'),
(73, 2, 'Acceso a Ajuste general', '2025-04-26 05:32:00', '', 'Ajuste general'),
(74, 2, 'Acceso a Ajuste de roles', '2025-04-26 05:32:14', '', 'Ajuste de roles'),
(75, 2, 'Acceso a Compras', '2025-04-27 00:24:49', '', 'Compras'),
(76, 1, 'Acceso a Compras', '2025-04-28 01:34:14', '', 'Compras'),
(77, 1, 'Acceso al sistema', '2025-04-28 04:21:54', 'admin', 'Inicio'),
(78, 1, 'Acceso a Ajuste general', '2025-04-28 04:22:05', '', 'Ajuste general'),
(79, 1, 'Editar empresa', '2025-04-28 04:22:15', 'Quesera y Charcuteria Don Pedro 24', 'Empresas'),
(80, 1, 'Acceso a Banco', '2025-04-28 04:36:36', '', 'Banco'),
(81, 1, 'Acceso a Ajuste de INventario', '2025-04-28 04:43:45', '', 'Ajuste de INventario'),
(82, 1, 'Acceso a Carga de productos', '2025-04-28 04:43:46', '', 'Carga de productos'),
(83, 1, 'Acceso a Ajuste de INventario', '2025-04-28 04:48:47', '', 'Ajuste de INventario'),
(84, 1, 'Acceso a Categorías', '2025-04-28 05:56:24', '', 'Categorías'),
(85, 1, 'Registro de categoría', '2025-04-28 05:56:34', 'Jamon ', 'Categorias'),
(86, 1, 'Acceso a Compras', '2025-04-28 06:29:30', '', 'Compras'),
(87, 1, 'Acceso a Ajuste de INventario', '2025-04-28 06:30:20', '', 'Ajuste de INventario'),
(88, 1, 'Acceso a Carga de productos', '2025-04-28 06:30:21', '', 'Carga de productos'),
(89, 1, 'Acceso a Categorías', '2025-04-28 06:30:25', '', 'Categorías'),
(90, 1, 'Acceso a Banco', '2025-04-28 06:31:06', '', 'Banco'),
(91, 1, 'Acceso a Compras', '2025-04-28 06:31:10', '', 'Compras'),
(92, 1, 'Acceso a Ajuste de INventario', '2025-04-28 06:41:25', '', 'Ajuste de INventario'),
(93, 1, 'Acceso a Descarga de productos', '2025-04-28 06:41:26', '', 'Descarga de productos'),
(94, 1, 'Acceso a Divisas', '2025-04-28 06:59:24', '', 'Divisas'),
(95, 1, 'Registro de divisa', '2025-04-28 07:01:58', 'Binance', 'Divisas'),
(96, 1, 'Editar divisa', '2025-04-28 07:03:07', 'Binances', 'Divisas'),
(97, 1, 'Acceso a Compras', '2025-04-28 07:23:30', '', 'Compras'),
(98, 1, 'Acceso a Ajuste de INventario', '2025-04-28 07:23:53', '', 'Ajuste de INventario'),
(99, 1, 'Acceso a Carga de productos', '2025-04-28 07:23:54', '', 'Carga de productos'),
(100, 1, 'Acceso al sistema', '2025-04-28 14:25:46', 'admin', 'Inicio'),
(101, 1, 'Acceso a Ajuste de INventario', '2025-04-28 14:25:52', '', 'Ajuste de INventario'),
(102, 1, 'Acceso a Carga de productos', '2025-04-28 14:25:53', '', 'Carga de productos'),
(103, 1, 'Acceso a Ajuste de INventario', '2025-04-28 14:25:54', '', 'Ajuste de INventario'),
(104, 1, 'Acceso a Ajuste de INventario', '2025-04-28 14:25:55', '', 'Ajuste de INventario'),
(105, 1, 'Acceso a Categorías', '2025-04-29 17:10:58', '', 'Categorías'),
(106, 1, 'Acceso a Unidades de medida', '2025-04-29 17:11:09', '', 'Unidades de medida'),
(107, 1, 'Acceso a Banco', '2025-04-29 18:54:46', '', 'Banco'),
(108, 1, 'Acceso a Ajuste general', '2025-04-29 18:55:16', '', 'Ajuste general'),
(109, 1, 'Acceso a Ajuste de roles', '2025-04-29 18:55:35', '', 'Ajuste de roles'),
(110, 1, 'Acceso a Divisas', '2025-04-30 01:28:22', '', 'Divisas'),
(111, 1, 'Acceso a Tipos de pago', '2025-04-30 01:29:58', '', 'Tipos de pago'),
(112, 1, 'Buscar producto', '2025-04-30 02:10:53', 'Jma', 'Productos'),
(113, 1, 'Buscar producto', '2025-04-30 02:10:55', 'Ja,', 'Productos'),
(114, 1, 'Buscar producto', '2025-04-30 02:10:56', 'Jam', 'Productos'),
(115, 1, 'Buscar producto', '2025-04-30 02:10:56', 'Jamo', 'Productos'),
(116, 1, 'Buscar producto', '2025-04-30 02:10:56', 'Jamon', 'Productos'),
(117, 1, 'Buscar producto', '2025-04-30 02:10:56', 'Jamon ', 'Productos'),
(118, 1, 'Buscar producto', '2025-04-30 02:10:56', 'Jamon d', 'Productos'),
(119, 1, 'Buscar producto', '2025-04-30 02:10:56', 'Jamon de', 'Productos'),
(120, 1, 'Buscar producto', '2025-04-30 02:10:57', 'Jamon de ', 'Productos'),
(121, 1, 'Buscar producto', '2025-04-30 02:10:57', 'Jamon de e', 'Productos'),
(122, 1, 'Buscar producto', '2025-04-30 02:10:57', 'Jamon de es', 'Productos'),
(123, 1, 'Buscar producto', '2025-04-30 02:10:58', 'Jamon de esp', 'Productos'),
(124, 1, 'Buscar producto', '2025-04-30 02:10:59', 'Jamon de espa', 'Productos'),
(125, 1, 'Buscar producto', '2025-04-30 02:10:59', 'Jamon de espal', 'Productos'),
(126, 1, 'Buscar producto', '2025-04-30 02:10:59', 'Jamon de espald', 'Productos'),
(127, 1, 'Buscar producto', '2025-04-30 02:10:59', 'Jamon de espalda', 'Productos'),
(128, 1, 'Buscar producto', '2025-04-30 02:18:26', 'Jamon de p', 'Productos'),
(129, 1, 'Buscar producto', '2025-04-30 02:18:27', 'Jamon de pi', 'Productos'),
(130, 1, 'Buscar producto', '2025-04-30 02:18:27', 'Jamon de pie', 'Productos'),
(131, 1, 'Buscar producto', '2025-04-30 02:18:27', 'Jamon de pier', 'Productos'),
(132, 1, 'Buscar producto', '2025-04-30 02:18:27', 'Jamon de piern', 'Productos'),
(133, 1, 'Buscar producto', '2025-04-30 02:18:27', 'Jamon de pierna', 'Productos'),
(134, 1, 'Buscar producto', '2025-04-30 02:19:39', 'jam', 'Productos'),
(135, 1, 'Buscar producto', '2025-04-30 02:19:40', 'jamo', 'Productos'),
(136, 1, 'Buscar producto', '2025-04-30 02:19:40', 'jamon', 'Productos'),
(137, 1, 'Buscar producto', '2025-04-30 02:19:40', 'jamon ', 'Productos'),
(138, 1, 'Buscar producto', '2025-04-30 02:19:40', 'jamon d', 'Productos'),
(139, 1, 'Buscar producto', '2025-04-30 02:19:40', 'jamon de', 'Productos'),
(140, 1, 'Buscar producto', '2025-04-30 02:19:40', 'jamon de ', 'Productos'),
(141, 1, 'Buscar producto', '2025-04-30 02:19:41', 'jamon de p', 'Productos'),
(142, 1, 'Buscar producto', '2025-04-30 02:19:41', 'jamon de pi', 'Productos'),
(143, 1, 'Buscar producto', '2025-04-30 02:19:41', 'jamon de pie', 'Productos'),
(144, 1, 'Buscar producto', '2025-04-30 02:19:41', 'jamon de pier', 'Productos'),
(145, 1, 'Buscar producto', '2025-04-30 02:19:41', 'jamon de piern', 'Productos'),
(146, 1, 'Buscar producto', '2025-04-30 02:19:41', 'jamon de pierna', 'Productos'),
(147, 1, 'Registro de producto', '2025-04-30 02:20:10', 'jamon de pierna', 'Productos'),
(148, 1, 'Acceso a Ajuste de INventario', '2025-04-30 02:20:36', '', 'Ajuste de INventario'),
(149, 1, 'Acceso a Carga de productos', '2025-04-30 02:20:39', '', 'Carga de productos'),
(150, 1, 'Acceso a Ajuste de INventario', '2025-04-30 02:20:41', '', 'Ajuste de INventario'),
(151, 1, 'Registro de carga', '2025-04-30 02:21:21', 'carga prueba', 'Carga'),
(152, 1, 'Acceso a Ajuste de INventario', '2025-04-30 02:21:58', '', 'Ajuste de INventario'),
(153, 1, 'Acceso a Ajuste de INventario', '2025-04-30 02:21:59', '', 'Ajuste de INventario'),
(154, 1, 'Acceso a Ventas', '2025-04-30 02:22:04', '', 'Ventas'),
(155, 1, 'Acceso a Ventas', '2025-04-30 02:32:39', '', 'Ventas'),
(156, 1, 'Registro de venta', '2025-04-30 02:36:52', '4.31', 'Venta'),
(157, 1, 'Acceso a Compras', '2025-04-30 02:40:42', '', 'Compras'),
(158, 1, 'Registro de compra', '2025-04-30 02:42:34', '55.00', 'Compras'),
(159, 1, 'Registro de cliente', '2025-04-30 02:44:23', 'Maribel', 'Clientes'),
(160, 1, 'Editar producto', '2025-04-30 02:45:15', 'jamon de piernaa', 'Productos'),
(161, 1, 'Acceso a Ventas', '2025-04-30 02:45:23', '', 'Ventas'),
(162, 1, 'Eliminar cliente', '2025-04-30 02:45:48', 'Eliminado el cliente con el código 4', 'Clientes'),
(163, 1, 'Registro de proveedor', '2025-04-30 02:47:16', 'ST3M c.a', 'Proveedores'),
(164, 1, 'Registro de representante', '2025-04-30 02:48:31', 'samuel', 'Representantes'),
(165, 1, 'Registro de teléfono', '2025-04-30 02:48:46', '04245645108', 'Teléfonos de proveedores'),
(166, 1, 'Registro de teléfono', '2025-04-30 02:48:59', '12453145213', 'Teléfonos de proveedores'),
(167, 1, 'Acceso a Reporte De proveedores', '2025-04-30 03:33:03', '', 'Reporte De proveedores'),
(168, 1, 'Acceso a Ventas', '2025-04-30 03:34:45', '', 'Ventas'),
(169, 1, 'Acceso a Ajuste general', '2025-04-30 03:36:03', '', 'Ajuste general'),
(170, 1, 'Editar empresa', '2025-04-30 03:36:21', 'Quesera y Charcuteria Don Pedro 24', 'Empresas'),
(171, 1, 'Registro de usuario', '2025-04-30 03:38:20', 'daniel', 'Usuarios'),
(172, 1, 'Editar usuario', '2025-04-30 03:38:40', 'jorge', 'Usuarios'),
(173, 1, 'Acceso a Unidades de medida', '2025-04-30 03:40:14', '', 'Unidades de medida'),
(174, 1, 'Registro de unidad de medida', '2025-04-30 03:40:22', 'UND', 'Unidad de medida'),
(175, 1, 'Editar unidad de medida', '2025-04-30 03:40:41', 'UD', 'Unidad de medida'),
(176, 1, 'Editar unidad de medida', '2025-04-30 03:40:48', 'UND', 'Unidad de medida'),
(177, 1, 'Editar unidad de medida', '2025-04-30 03:41:33', 'UND', 'Unidad de medida'),
(178, 1, 'Eliminar unidad de medida', '2025-04-30 03:41:36', 'Eliminado la unidad de medida con el código 2', 'Unidad de medida'),
(179, 1, 'Acceso a Categorías', '2025-04-30 03:41:43', '', 'Categorías'),
(180, 1, 'Registro de categoría', '2025-04-30 03:41:51', 'Embutidos', 'Categorias'),
(181, 1, 'Editar categoría', '2025-04-30 03:42:21', 'Embutido', 'Categorias'),
(182, 1, 'Eliminar categoría', '2025-04-30 03:42:31', 'Eliminada la categoría con el código 3', 'Categorias'),
(183, 1, 'Acceso a Divisas', '2025-04-30 03:43:04', '', 'Divisas'),
(184, 1, 'Registro de divisa', '2025-04-30 03:45:49', 'libra', 'Divisas'),
(185, 1, 'Acceso a Tipos de pago', '2025-04-30 03:48:47', '', 'Tipos de pago'),
(186, 1, 'Acceso a Ajuste de INventario', '2025-04-30 03:49:14', '', 'Ajuste de INventario'),
(187, 1, 'Acceso a Descarga de productos', '2025-04-30 03:49:14', '', 'Descarga de productos'),
(188, 1, 'Registro de descarga', '2025-04-30 03:49:47', 'ajuste stock prueba', 'Descarga'),
(189, 1, 'Acceso a Ventas', '2025-04-30 04:36:31', '', 'Ventas'),
(190, 1, 'Editar usuario', '2025-04-30 05:32:52', 'daniel', 'Usuarios'),
(191, 3, 'Acceso al sistema', '2025-04-30 06:15:50', 'daniel', 'Inicio'),
(192, 3, 'Acceso al sistema', '2025-04-30 06:20:21', 'daniel', 'Inicio'),
(193, 3, 'Acceso al sistema', '2025-04-30 06:22:00', 'daniel', 'Inicio'),
(194, 3, 'Acceso a Ajuste de INventario', '2025-04-30 07:07:26', '', 'Ajuste de INventario'),
(195, 3, 'Acceso a Carga de productos', '2025-04-30 07:07:27', '', 'Carga de productos'),
(196, 1, 'Acceso al sistema', '2025-04-30 18:38:50', 'admin', 'Inicio'),
(197, 3, 'Acceso al sistema', '2025-04-30 18:48:38', 'daniel', 'Inicio'),
(198, 3, 'Acceso a Ajuste de INventario', '2025-04-30 18:48:50', '', 'Ajuste de INventario'),
(199, 3, 'Acceso a Ajuste de INventario', '2025-04-30 18:48:57', '', 'Ajuste de INventario'),
(200, 3, 'Acceso a Carga de productos', '2025-04-30 18:48:58', '', 'Carga de productos');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `modulos`
--

CREATE TABLE `modulos` (
  `cod_modulo` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

--
-- Volcado de datos para la tabla `modulos`
--

INSERT INTO `modulos` (`cod_modulo`, `nombre`) VALUES
(1, 'producto'),
(2, 'inventario'),
(3, 'categoria'),
(4, 'compra'),
(5, 'venta'),
(6, 'cliente'),
(7, 'proveedor'),
(8, 'usuario'),
(9, 'reporte'),
(10, 'configuracion');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `permisos`
--

CREATE TABLE `permisos` (
  `cod_crud` int(11) NOT NULL,
  `nombre` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish2_ci;

--
-- Volcado de datos para la tabla `permisos`
--

INSERT INTO `permisos` (`cod_crud`, `nombre`) VALUES
(1, 'crear'),
(2, 'actualizar'),
(3, 'eliminar');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipo_usuario`
--

CREATE TABLE `tipo_usuario` (
  `cod_tipo_usuario` int(11) NOT NULL,
  `rol` varchar(50) NOT NULL,
  `status` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

--
-- Volcado de datos para la tabla `tipo_usuario`
--

INSERT INTO `tipo_usuario` (`cod_tipo_usuario`, `rol`, `status`) VALUES
(1, 'Administrador', 1),
(2, 'prueba', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tpu_permisos`
--

CREATE TABLE `tpu_permisos` (
  `cod_tipo_usuario` int(11) NOT NULL,
  `cod_modulo` int(11) NOT NULL,
  `cod_crud` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

--
-- Volcado de datos para la tabla `tpu_permisos`
--

INSERT INTO `tpu_permisos` (`cod_tipo_usuario`, `cod_modulo`, `cod_crud`) VALUES
(1, 1, NULL),
(1, 2, NULL),
(1, 3, NULL),
(1, 4, NULL),
(1, 5, NULL),
(1, 6, NULL),
(1, 7, NULL),
(1, 8, NULL),
(1, 9, NULL),
(1, 10, NULL),
(2, 1, 1),
(2, 1, 2),
(2, 2, 2),
(2, 3, 1),
(2, 3, 2),
(2, 4, 2),
(2, 5, 1),
(2, 5, 2),
(2, 5, 3),
(2, 7, 2),
(2, 9, 2);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `cod_usuario` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `user` varchar(20) NOT NULL,
  `password` varchar(255) NOT NULL,
  `cod_tipo_usuario` int(11) NOT NULL,
  `status` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`cod_usuario`, `nombre`, `user`, `password`, `cod_tipo_usuario`, `status`) VALUES
(1, 'Administrador', 'admin', '$2y$10$.nbh0vwGWNkBgsVzkBSoYurftn9Mg.TLYkxmK32KhMKOzaTjaRS3.', 1, 1),
(2, 'jorges', 'jorge', '$2y$10$wRFU5jEfVEpp/jXR0OQ0YuycA5JvHQilBkXSwfHBds164nz1doz3e', 1, 1),
(3, 'daniel', 'daniel', '$2y$10$ByhGqnqEywZwtEm9PlCJZuWkAvwg4X8keP1XWgLwRMnjPIZG5X3GW', 2, 1);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `bitacora`
--
ALTER TABLE `bitacora`
  ADD PRIMARY KEY (`id`),
  ADD KEY `cod_usuario` (`cod_usuario`);

--
-- Indices de la tabla `modulos`
--
ALTER TABLE `modulos`
  ADD PRIMARY KEY (`cod_modulo`);

--
-- Indices de la tabla `permisos`
--
ALTER TABLE `permisos`
  ADD PRIMARY KEY (`cod_crud`);

--
-- Indices de la tabla `tipo_usuario`
--
ALTER TABLE `tipo_usuario`
  ADD PRIMARY KEY (`cod_tipo_usuario`);

--
-- Indices de la tabla `tpu_permisos`
--
ALTER TABLE `tpu_permisos`
  ADD KEY `cod_tipo_usuario` (`cod_tipo_usuario`),
  ADD KEY `cod_permiso` (`cod_modulo`),
  ADD KEY `tpu-crud` (`cod_crud`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`cod_usuario`),
  ADD UNIQUE KEY `user` (`user`),
  ADD KEY `usuario-tipousuario` (`cod_tipo_usuario`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `bitacora`
--
ALTER TABLE `bitacora`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=201;

--
-- AUTO_INCREMENT de la tabla `modulos`
--
ALTER TABLE `modulos`
  MODIFY `cod_modulo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `permisos`
--
ALTER TABLE `permisos`
  MODIFY `cod_crud` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `tipo_usuario`
--
ALTER TABLE `tipo_usuario`
  MODIFY `cod_tipo_usuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `cod_usuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `bitacora`
--
ALTER TABLE `bitacora`
  ADD CONSTRAINT `bitacora_ibfk_1` FOREIGN KEY (`cod_usuario`) REFERENCES `usuarios` (`cod_usuario`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `tpu_permisos`
--
ALTER TABLE `tpu_permisos`
  ADD CONSTRAINT `tpu-cod` FOREIGN KEY (`cod_tipo_usuario`) REFERENCES `tipo_usuario` (`cod_tipo_usuario`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `tpu-crud` FOREIGN KEY (`cod_crud`) REFERENCES `permisos` (`cod_crud`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `tpu-modulo` FOREIGN KEY (`cod_modulo`) REFERENCES `modulos` (`cod_modulo`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD CONSTRAINT `usuario-tipousuario` FOREIGN KEY (`cod_tipo_usuario`) REFERENCES `tipo_usuario` (`cod_tipo_usuario`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
