-- MariaDB dump 10.19  Distrib 10.4.32-MariaDB, for Win64 (AMD64)
--
-- Host: localhost    Database: seguridad
-- ------------------------------------------------------
-- Server version	10.4.32-MariaDB

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Current Database: `seguridad`
--

CREATE DATABASE /*!32312 IF NOT EXISTS*/ `seguridad` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_spanish2_ci */;

USE `seguridad`;

--
-- Table structure for table `backup`
--

DROP TABLE IF EXISTS `backup`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `backup` (
  `cod_backup` int(11) NOT NULL AUTO_INCREMENT,
  `cod_config_backup` int(11) DEFAULT NULL,
  `cod_usuario` int(11) NOT NULL,
  `descripcion` varchar(50) NOT NULL,
  `ruta` varchar(255) NOT NULL,
  `fecha` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `tipo` enum('manual','automatico','','') NOT NULL,
  `tamanio` float NOT NULL,
  PRIMARY KEY (`cod_backup`),
  KEY `configbackup` (`cod_config_backup`),
  KEY `usuariobackup` (`cod_usuario`),
  CONSTRAINT `configbackup` FOREIGN KEY (`cod_config_backup`) REFERENCES `config_backup` (`cod_config_backup`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `usuariobackup` FOREIGN KEY (`cod_usuario`) REFERENCES `usuarios` (`cod_usuario`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish2_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `backup`
--

LOCK TABLES `backup` WRITE;
/*!40000 ALTER TABLE `backup` DISABLE KEYS */;
INSERT INTO `backup` VALUES (1,NULL,1,'Respaldo1','respaldos/Respaldo1_2025-05-08_02-02-07.sql','2025-05-08 06:02:08','manual',0.0760946),(2,NULL,1,'Respaldo1','respaldos/Respaldo1_2025-05-08_02-02-20.sql','2025-05-08 06:02:21','manual',0.0762444);
/*!40000 ALTER TABLE `backup` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `bitacora`
--

DROP TABLE IF EXISTS `bitacora`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `bitacora` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cod_usuario` int(11) NOT NULL,
  `accion` varchar(255) NOT NULL,
  `fecha` timestamp NOT NULL DEFAULT current_timestamp(),
  `detalles` text DEFAULT NULL,
  `modulo` varchar(220) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `cod_usuario` (`cod_usuario`),
  CONSTRAINT `bitacora_ibfk_1` FOREIGN KEY (`cod_usuario`) REFERENCES `usuarios` (`cod_usuario`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=221 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `bitacora`
--

LOCK TABLES `bitacora` WRITE;
/*!40000 ALTER TABLE `bitacora` DISABLE KEYS */;
INSERT INTO `bitacora` VALUES (49,1,'Acceso al sistema','2025-04-18 23:39:42','admin','Inicio'),(50,1,'Acceso a Ventas','2025-04-18 23:46:53','','Ventas'),(51,1,'Acceso a Compras','2025-04-18 23:46:57','','Compras'),(52,1,'Acceso a Ventas','2025-04-18 23:46:58','','Ventas'),(53,1,'Acceso a Compras','2025-04-18 23:47:00','','Compras'),(54,1,'Acceso a Ventas','2025-04-18 23:47:01','','Ventas'),(55,1,'Acceso a Ventas','2025-04-18 23:48:24','','Ventas'),(56,1,'Acceso a Compras','2025-04-18 23:48:25','','Compras'),(57,1,'Acceso a Ventas','2025-04-18 23:48:36','','Ventas'),(58,1,'Acceso a Compras','2025-04-18 23:50:00','','Compras'),(59,1,'Acceso a Ventas','2025-04-18 23:50:03','','Ventas'),(60,1,'Acceso a Compras','2025-04-18 23:50:04','','Compras'),(61,1,'Acceso a Divisas','2025-04-18 23:50:37','','Divisas'),(62,1,'Acceso a Tipos de pago','2025-04-19 00:52:29','','Tipos de pago'),(63,1,'Acceso al sistema','2025-04-25 02:36:12','admin','Inicio'),(64,1,'Acceso a Ajuste de roles','2025-04-25 02:36:22','','Ajuste de roles'),(65,1,'Acceso a Compras','2025-04-25 15:16:46','','Compras'),(66,2,'Acceso a Tipos de pago','2025-04-26 05:23:22','','Tipos de pago'),(67,2,'Acceso a Ajuste de INventario','2025-04-26 05:29:18','','Ajuste de INventario'),(68,2,'Acceso a Carga de productos','2025-04-26 05:29:21','','Carga de productos'),(69,2,'Acceso a Compras','2025-04-26 05:29:24','','Compras'),(70,2,'Acceso a Compras','2025-04-26 05:29:27','','Compras'),(71,2,'Acceso a Ventas','2025-04-26 05:29:28','','Ventas'),(72,2,'Acceso a Reporte De proveedores','2025-04-26 05:31:57','','Reporte De proveedores'),(73,2,'Acceso a Ajuste general','2025-04-26 05:32:00','','Ajuste general'),(74,2,'Acceso a Ajuste de roles','2025-04-26 05:32:14','','Ajuste de roles'),(75,2,'Acceso a Compras','2025-04-27 00:24:49','','Compras'),(76,1,'Acceso a Compras','2025-04-28 01:34:14','','Compras'),(77,1,'Acceso al sistema','2025-04-28 04:21:54','admin','Inicio'),(78,1,'Acceso a Ajuste general','2025-04-28 04:22:05','','Ajuste general'),(79,1,'Editar empresa','2025-04-28 04:22:15','Quesera y Charcuteria Don Pedro 24','Empresas'),(80,1,'Acceso a Banco','2025-04-28 04:36:36','','Banco'),(81,1,'Acceso a Ajuste de INventario','2025-04-28 04:43:45','','Ajuste de INventario'),(82,1,'Acceso a Carga de productos','2025-04-28 04:43:46','','Carga de productos'),(83,1,'Acceso a Ajuste de INventario','2025-04-28 04:48:47','','Ajuste de INventario'),(84,1,'Acceso a Categorías','2025-04-28 05:56:24','','Categorías'),(85,1,'Registro de categoría','2025-04-28 05:56:34','Jamon ','Categorias'),(86,1,'Acceso a Compras','2025-04-28 06:29:30','','Compras'),(87,1,'Acceso a Ajuste de INventario','2025-04-28 06:30:20','','Ajuste de INventario'),(88,1,'Acceso a Carga de productos','2025-04-28 06:30:21','','Carga de productos'),(89,1,'Acceso a Categorías','2025-04-28 06:30:25','','Categorías'),(90,1,'Acceso a Banco','2025-04-28 06:31:06','','Banco'),(91,1,'Acceso a Compras','2025-04-28 06:31:10','','Compras'),(92,1,'Acceso a Ajuste de INventario','2025-04-28 06:41:25','','Ajuste de INventario'),(93,1,'Acceso a Descarga de productos','2025-04-28 06:41:26','','Descarga de productos'),(94,1,'Acceso a Divisas','2025-04-28 06:59:24','','Divisas'),(95,1,'Registro de divisa','2025-04-28 07:01:58','Binance','Divisas'),(96,1,'Editar divisa','2025-04-28 07:03:07','Binances','Divisas'),(97,1,'Acceso a Compras','2025-04-28 07:23:30','','Compras'),(98,1,'Acceso a Ajuste de INventario','2025-04-28 07:23:53','','Ajuste de INventario'),(99,1,'Acceso a Carga de productos','2025-04-28 07:23:54','','Carga de productos'),(100,1,'Acceso al sistema','2025-04-28 14:25:46','admin','Inicio'),(101,1,'Acceso a Ajuste de INventario','2025-04-28 14:25:52','','Ajuste de INventario'),(102,1,'Acceso a Carga de productos','2025-04-28 14:25:53','','Carga de productos'),(103,1,'Acceso a Ajuste de INventario','2025-04-28 14:25:54','','Ajuste de INventario'),(104,1,'Acceso a Ajuste de INventario','2025-04-28 14:25:55','','Ajuste de INventario'),(105,1,'Acceso a Categorías','2025-04-29 17:10:58','','Categorías'),(106,1,'Acceso a Unidades de medida','2025-04-29 17:11:09','','Unidades de medida'),(107,1,'Acceso a Banco','2025-04-29 18:54:46','','Banco'),(108,1,'Acceso a Ajuste general','2025-04-29 18:55:16','','Ajuste general'),(109,1,'Acceso a Ajuste de roles','2025-04-29 18:55:35','','Ajuste de roles'),(110,1,'Acceso a Divisas','2025-04-30 01:28:22','','Divisas'),(111,1,'Acceso a Tipos de pago','2025-04-30 01:29:58','','Tipos de pago'),(112,1,'Buscar producto','2025-04-30 02:10:53','Jma','Productos'),(113,1,'Buscar producto','2025-04-30 02:10:55','Ja,','Productos'),(114,1,'Buscar producto','2025-04-30 02:10:56','Jam','Productos'),(115,1,'Buscar producto','2025-04-30 02:10:56','Jamo','Productos'),(116,1,'Buscar producto','2025-04-30 02:10:56','Jamon','Productos'),(117,1,'Buscar producto','2025-04-30 02:10:56','Jamon ','Productos'),(118,1,'Buscar producto','2025-04-30 02:10:56','Jamon d','Productos'),(119,1,'Buscar producto','2025-04-30 02:10:56','Jamon de','Productos'),(120,1,'Buscar producto','2025-04-30 02:10:57','Jamon de ','Productos'),(121,1,'Buscar producto','2025-04-30 02:10:57','Jamon de e','Productos'),(122,1,'Buscar producto','2025-04-30 02:10:57','Jamon de es','Productos'),(123,1,'Buscar producto','2025-04-30 02:10:58','Jamon de esp','Productos'),(124,1,'Buscar producto','2025-04-30 02:10:59','Jamon de espa','Productos'),(125,1,'Buscar producto','2025-04-30 02:10:59','Jamon de espal','Productos'),(126,1,'Buscar producto','2025-04-30 02:10:59','Jamon de espald','Productos'),(127,1,'Buscar producto','2025-04-30 02:10:59','Jamon de espalda','Productos'),(128,1,'Buscar producto','2025-04-30 02:18:26','Jamon de p','Productos'),(129,1,'Buscar producto','2025-04-30 02:18:27','Jamon de pi','Productos'),(130,1,'Buscar producto','2025-04-30 02:18:27','Jamon de pie','Productos'),(131,1,'Buscar producto','2025-04-30 02:18:27','Jamon de pier','Productos'),(132,1,'Buscar producto','2025-04-30 02:18:27','Jamon de piern','Productos'),(133,1,'Buscar producto','2025-04-30 02:18:27','Jamon de pierna','Productos'),(134,1,'Buscar producto','2025-04-30 02:19:39','jam','Productos'),(135,1,'Buscar producto','2025-04-30 02:19:40','jamo','Productos'),(136,1,'Buscar producto','2025-04-30 02:19:40','jamon','Productos'),(137,1,'Buscar producto','2025-04-30 02:19:40','jamon ','Productos'),(138,1,'Buscar producto','2025-04-30 02:19:40','jamon d','Productos'),(139,1,'Buscar producto','2025-04-30 02:19:40','jamon de','Productos'),(140,1,'Buscar producto','2025-04-30 02:19:40','jamon de ','Productos'),(141,1,'Buscar producto','2025-04-30 02:19:41','jamon de p','Productos'),(142,1,'Buscar producto','2025-04-30 02:19:41','jamon de pi','Productos'),(143,1,'Buscar producto','2025-04-30 02:19:41','jamon de pie','Productos'),(144,1,'Buscar producto','2025-04-30 02:19:41','jamon de pier','Productos'),(145,1,'Buscar producto','2025-04-30 02:19:41','jamon de piern','Productos'),(146,1,'Buscar producto','2025-04-30 02:19:41','jamon de pierna','Productos'),(147,1,'Registro de producto','2025-04-30 02:20:10','jamon de pierna','Productos'),(148,1,'Acceso a Ajuste de INventario','2025-04-30 02:20:36','','Ajuste de INventario'),(149,1,'Acceso a Carga de productos','2025-04-30 02:20:39','','Carga de productos'),(150,1,'Acceso a Ajuste de INventario','2025-04-30 02:20:41','','Ajuste de INventario'),(151,1,'Registro de carga','2025-04-30 02:21:21','carga prueba','Carga'),(152,1,'Acceso a Ajuste de INventario','2025-04-30 02:21:58','','Ajuste de INventario'),(153,1,'Acceso a Ajuste de INventario','2025-04-30 02:21:59','','Ajuste de INventario'),(154,1,'Acceso a Ventas','2025-04-30 02:22:04','','Ventas'),(155,1,'Acceso a Ventas','2025-04-30 02:32:39','','Ventas'),(156,1,'Registro de venta','2025-04-30 02:36:52','4.31','Venta'),(157,1,'Acceso a Compras','2025-04-30 02:40:42','','Compras'),(158,1,'Registro de compra','2025-04-30 02:42:34','55.00','Compras'),(159,1,'Registro de cliente','2025-04-30 02:44:23','Maribel','Clientes'),(160,1,'Editar producto','2025-04-30 02:45:15','jamon de piernaa','Productos'),(161,1,'Acceso a Ventas','2025-04-30 02:45:23','','Ventas'),(162,1,'Eliminar cliente','2025-04-30 02:45:48','Eliminado el cliente con el código 4','Clientes'),(163,1,'Registro de proveedor','2025-04-30 02:47:16','ST3M c.a','Proveedores'),(164,1,'Registro de representante','2025-04-30 02:48:31','samuel','Representantes'),(165,1,'Registro de teléfono','2025-04-30 02:48:46','04245645108','Teléfonos de proveedores'),(166,1,'Registro de teléfono','2025-04-30 02:48:59','12453145213','Teléfonos de proveedores'),(167,1,'Acceso a Reporte De proveedores','2025-04-30 03:33:03','','Reporte De proveedores'),(168,1,'Acceso a Ventas','2025-04-30 03:34:45','','Ventas'),(169,1,'Acceso a Ajuste general','2025-04-30 03:36:03','','Ajuste general'),(170,1,'Editar empresa','2025-04-30 03:36:21','Quesera y Charcuteria Don Pedro 24','Empresas'),(171,1,'Registro de usuario','2025-04-30 03:38:20','daniel','Usuarios'),(172,1,'Editar usuario','2025-04-30 03:38:40','jorge','Usuarios'),(173,1,'Acceso a Unidades de medida','2025-04-30 03:40:14','','Unidades de medida'),(174,1,'Registro de unidad de medida','2025-04-30 03:40:22','UND','Unidad de medida'),(175,1,'Editar unidad de medida','2025-04-30 03:40:41','UD','Unidad de medida'),(176,1,'Editar unidad de medida','2025-04-30 03:40:48','UND','Unidad de medida'),(177,1,'Editar unidad de medida','2025-04-30 03:41:33','UND','Unidad de medida'),(178,1,'Eliminar unidad de medida','2025-04-30 03:41:36','Eliminado la unidad de medida con el código 2','Unidad de medida'),(179,1,'Acceso a Categorías','2025-04-30 03:41:43','','Categorías'),(180,1,'Registro de categoría','2025-04-30 03:41:51','Embutidos','Categorias'),(181,1,'Editar categoría','2025-04-30 03:42:21','Embutido','Categorias'),(182,1,'Eliminar categoría','2025-04-30 03:42:31','Eliminada la categoría con el código 3','Categorias'),(183,1,'Acceso a Divisas','2025-04-30 03:43:04','','Divisas'),(184,1,'Registro de divisa','2025-04-30 03:45:49','libra','Divisas'),(185,1,'Acceso a Tipos de pago','2025-04-30 03:48:47','','Tipos de pago'),(186,1,'Acceso a Ajuste de INventario','2025-04-30 03:49:14','','Ajuste de INventario'),(187,1,'Acceso a Descarga de productos','2025-04-30 03:49:14','','Descarga de productos'),(188,1,'Registro de descarga','2025-04-30 03:49:47','ajuste stock prueba','Descarga'),(189,1,'Acceso a Ventas','2025-04-30 04:36:31','','Ventas'),(190,1,'Editar usuario','2025-04-30 05:32:52','daniel','Usuarios'),(191,3,'Acceso al sistema','2025-04-30 06:15:50','daniel','Inicio'),(192,3,'Acceso al sistema','2025-04-30 06:20:21','daniel','Inicio'),(193,3,'Acceso al sistema','2025-04-30 06:22:00','daniel','Inicio'),(194,3,'Acceso a Ajuste de INventario','2025-04-30 07:07:26','','Ajuste de INventario'),(195,3,'Acceso a Carga de productos','2025-04-30 07:07:27','','Carga de productos'),(196,1,'Acceso al sistema','2025-04-30 18:38:50','admin','Inicio'),(197,3,'Acceso al sistema','2025-04-30 18:48:38','daniel','Inicio'),(198,3,'Acceso a Ajuste de INventario','2025-04-30 18:48:50','','Ajuste de INventario'),(199,3,'Acceso a Ajuste de INventario','2025-04-30 18:48:57','','Ajuste de INventario'),(200,3,'Acceso a Carga de productos','2025-04-30 18:48:58','','Carga de productos'),(201,1,'Acceso al sistema','2025-05-03 04:45:14','admin','Inicio'),(202,1,'Acceso al sistema','2025-05-03 04:45:38','admin','Inicio'),(203,1,'Acceso al sistema','2025-05-07 20:46:55','admin','Inicio'),(204,1,'Acceso al sistema','2025-05-07 20:47:41','admin','Inicio'),(205,1,'Acceso al sistema','2025-05-07 21:13:27','admin','Inicio'),(206,1,'Acceso al sistema','2025-05-07 21:13:51','admin','Inicio'),(207,1,'Acceso al sistema','2025-05-07 21:15:44','admin','Inicio'),(208,1,'Acceso al sistema','2025-05-07 21:17:05','admin','Inicio'),(209,1,'Acceso al sistema','2025-05-07 21:43:20','admin','Inicio'),(210,1,'Acceso al sistema','2025-05-07 21:45:13','admin','Inicio'),(211,1,'Acceso al sistema','2025-05-07 21:47:13','admin','Inicio'),(212,1,'Acceso al sistema','2025-05-07 21:49:19','admin','Inicio'),(213,1,'Acceso al sistema','2025-05-07 21:51:20','admin','Inicio'),(214,1,'Acceso al sistema','2025-05-07 21:51:56','admin','Inicio'),(215,1,'Acceso al sistema','2025-05-07 21:54:16','admin','Inicio'),(216,1,'Acceso al sistema','2025-05-08 05:33:13','admin','Inicio'),(217,1,'Registro de Copia de Seguridad','2025-05-08 05:55:46','hola_prueba','Backup'),(218,1,'Registro de Copia de Seguridad','2025-05-08 05:58:49','hola_prueba','Backup'),(219,1,'Registro de categoría','2025-05-08 06:00:19','Euros','Categorias'),(220,1,'Registro de Copia de Seguridad','2025-05-08 06:02:21','Respaldo1','Backup');
/*!40000 ALTER TABLE `bitacora` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `config_backup`
--

DROP TABLE IF EXISTS `config_backup`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `config_backup` (
  `cod_config_backup` int(11) NOT NULL AUTO_INCREMENT,
  `frecuencia` enum('diario','semanal','quincenal','mensual') NOT NULL,
  `retencion` int(11) NOT NULL,
  `hora` time NOT NULL,
  `dia` int(11) NOT NULL,
  `ult_respaldo` datetime DEFAULT NULL,
  `habilitado` int(11) NOT NULL,
  PRIMARY KEY (`cod_config_backup`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish2_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `config_backup`
--

LOCK TABLES `config_backup` WRITE;
/*!40000 ALTER TABLE `config_backup` DISABLE KEYS */;
INSERT INTO `config_backup` VALUES (1,'diario',10,'20:00:00',1,NULL,2);
/*!40000 ALTER TABLE `config_backup` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `modulos`
--

DROP TABLE IF EXISTS `modulos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `modulos` (
  `cod_modulo` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(50) NOT NULL,
  PRIMARY KEY (`cod_modulo`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `modulos`
--

LOCK TABLES `modulos` WRITE;
/*!40000 ALTER TABLE `modulos` DISABLE KEYS */;
INSERT INTO `modulos` VALUES (1,'producto'),(2,'inventario'),(3,'categoria'),(4,'compra'),(5,'venta'),(6,'cliente'),(7,'proveedor'),(8,'usuario'),(9,'reporte'),(10,'configuracion');
/*!40000 ALTER TABLE `modulos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `permisos`
--

DROP TABLE IF EXISTS `permisos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `permisos` (
  `cod_crud` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(10) NOT NULL,
  PRIMARY KEY (`cod_crud`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish2_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `permisos`
--

LOCK TABLES `permisos` WRITE;
/*!40000 ALTER TABLE `permisos` DISABLE KEYS */;
INSERT INTO `permisos` VALUES (1,'crear'),(2,'actualizar'),(3,'eliminar');
/*!40000 ALTER TABLE `permisos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tipo_usuario`
--

DROP TABLE IF EXISTS `tipo_usuario`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tipo_usuario` (
  `cod_tipo_usuario` int(11) NOT NULL AUTO_INCREMENT,
  `rol` varchar(50) NOT NULL,
  `status` int(11) NOT NULL,
  PRIMARY KEY (`cod_tipo_usuario`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tipo_usuario`
--

LOCK TABLES `tipo_usuario` WRITE;
/*!40000 ALTER TABLE `tipo_usuario` DISABLE KEYS */;
INSERT INTO `tipo_usuario` VALUES (1,'Administrador',1),(2,'prueba',1);
/*!40000 ALTER TABLE `tipo_usuario` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tpu_permisos`
--

DROP TABLE IF EXISTS `tpu_permisos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tpu_permisos` (
  `cod_tipo_usuario` int(11) NOT NULL,
  `cod_modulo` int(11) NOT NULL,
  `cod_crud` int(11) DEFAULT NULL,
  KEY `cod_tipo_usuario` (`cod_tipo_usuario`),
  KEY `cod_permiso` (`cod_modulo`),
  KEY `tpu-crud` (`cod_crud`),
  CONSTRAINT `tpu-cod` FOREIGN KEY (`cod_tipo_usuario`) REFERENCES `tipo_usuario` (`cod_tipo_usuario`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `tpu-crud` FOREIGN KEY (`cod_crud`) REFERENCES `permisos` (`cod_crud`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `tpu-modulo` FOREIGN KEY (`cod_modulo`) REFERENCES `modulos` (`cod_modulo`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tpu_permisos`
--

LOCK TABLES `tpu_permisos` WRITE;
/*!40000 ALTER TABLE `tpu_permisos` DISABLE KEYS */;
INSERT INTO `tpu_permisos` VALUES (1,1,NULL),(1,2,NULL),(1,3,NULL),(1,4,NULL),(1,5,NULL),(1,6,NULL),(1,7,NULL),(1,8,NULL),(1,9,NULL),(1,10,NULL),(2,1,1),(2,1,2),(2,2,2),(2,3,1),(2,3,2),(2,4,2),(2,5,1),(2,5,2),(2,5,3),(2,7,2),(2,9,2);
/*!40000 ALTER TABLE `tpu_permisos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `usuarios`
--

DROP TABLE IF EXISTS `usuarios`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `usuarios` (
  `cod_usuario` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(50) NOT NULL,
  `user` varchar(20) NOT NULL,
  `password` varchar(255) NOT NULL,
  `cod_tipo_usuario` int(11) NOT NULL,
  `status` int(11) NOT NULL,
  PRIMARY KEY (`cod_usuario`),
  UNIQUE KEY `user` (`user`),
  KEY `usuario-tipousuario` (`cod_tipo_usuario`),
  CONSTRAINT `usuario-tipousuario` FOREIGN KEY (`cod_tipo_usuario`) REFERENCES `tipo_usuario` (`cod_tipo_usuario`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `usuarios`
--

LOCK TABLES `usuarios` WRITE;
/*!40000 ALTER TABLE `usuarios` DISABLE KEYS */;
INSERT INTO `usuarios` VALUES (1,'Administrador','admin','$2y$10$.nbh0vwGWNkBgsVzkBSoYurftn9Mg.TLYkxmK32KhMKOzaTjaRS3.',1,1),(2,'jorges','jorge','$2y$10$wRFU5jEfVEpp/jXR0OQ0YuycA5JvHQilBkXSwfHBds164nz1doz3e',1,1),(3,'daniel','daniel','$2y$10$ByhGqnqEywZwtEm9PlCJZuWkAvwg4X8keP1XWgLwRMnjPIZG5X3GW',2,1);
/*!40000 ALTER TABLE `usuarios` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Current Database: `savycplus`
--

CREATE DATABASE /*!32312 IF NOT EXISTS*/ `savycplus` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_spanish2_ci */;

USE `savycplus`;

--
-- Table structure for table `analisis_rentabilidad`
--

DROP TABLE IF EXISTS `analisis_rentabilidad`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `analisis_rentabilidad` (
  `cod_analisis` int(11) NOT NULL AUTO_INCREMENT,
  `cod_detallep` int(11) DEFAULT NULL,
  `fecha_inicio` date NOT NULL,
  `fecha_fin` date NOT NULL,
  `ventas_totales` decimal(12,2) NOT NULL,
  `costo_ventas` decimal(12,2) NOT NULL,
  `gastos` decimal(12,2) NOT NULL,
  `margen_bruto` decimal(10,2) DEFAULT NULL,
  `notas` text DEFAULT NULL,
  `cod_usuario` int(11) DEFAULT NULL,
  `fecha_calculo` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`cod_analisis`),
  KEY `cod_detallep` (`cod_detallep`),
  CONSTRAINT `analisis_rentabilidad_ibfk_1` FOREIGN KEY (`cod_detallep`) REFERENCES `detalle_productos` (`cod_detallep`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `analisis_rentabilidad`
--

LOCK TABLES `analisis_rentabilidad` WRITE;
/*!40000 ALTER TABLE `analisis_rentabilidad` DISABLE KEYS */;
/*!40000 ALTER TABLE `analisis_rentabilidad` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `asientos_contables`
--

DROP TABLE IF EXISTS `asientos_contables`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `asientos_contables` (
  `cod_asiento` int(11) NOT NULL AUTO_INCREMENT,
  `cod_mov` int(11) NOT NULL,
  `fecha` date NOT NULL,
  `descripcion` varchar(50) NOT NULL,
  `total_debe` decimal(18,2) NOT NULL,
  `total_haber` decimal(18,2) NOT NULL,
  `status` int(11) NOT NULL,
  PRIMARY KEY (`cod_asiento`),
  KEY `cod_mov` (`cod_mov`),
  CONSTRAINT `asientos_contables_ibfk_1` FOREIGN KEY (`cod_mov`) REFERENCES `movimientos` (`cod_mov`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish2_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `asientos_contables`
--

LOCK TABLES `asientos_contables` WRITE;
/*!40000 ALTER TABLE `asientos_contables` DISABLE KEYS */;
/*!40000 ALTER TABLE `asientos_contables` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `banco`
--

DROP TABLE IF EXISTS `banco`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `banco` (
  `cod_banco` int(11) NOT NULL AUTO_INCREMENT,
  `nombre_banco` varchar(20) NOT NULL,
  PRIMARY KEY (`cod_banco`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `banco`
--

LOCK TABLES `banco` WRITE;
/*!40000 ALTER TABLE `banco` DISABLE KEYS */;
INSERT INTO `banco` VALUES (1,'Banco Provincial');
/*!40000 ALTER TABLE `banco` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `caja`
--

DROP TABLE IF EXISTS `caja`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `caja` (
  `cod_caja` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(20) NOT NULL,
  `saldo` decimal(10,2) NOT NULL,
  `cod_divisas` int(11) NOT NULL,
  `status` int(11) NOT NULL,
  PRIMARY KEY (`cod_caja`),
  KEY `cod_divisas` (`cod_divisas`),
  CONSTRAINT `caja_ibfk_1` FOREIGN KEY (`cod_divisas`) REFERENCES `divisas` (`cod_divisa`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `caja`
--

LOCK TABLES `caja` WRITE;
/*!40000 ALTER TABLE `caja` DISABLE KEYS */;
/*!40000 ALTER TABLE `caja` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cambio_divisa`
--

DROP TABLE IF EXISTS `cambio_divisa`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cambio_divisa` (
  `cod_cambio` int(11) NOT NULL AUTO_INCREMENT,
  `cod_divisa` int(11) NOT NULL,
  `tasa` decimal(10,2) NOT NULL,
  `fecha` date NOT NULL,
  PRIMARY KEY (`cod_cambio`),
  KEY `cambiodivisa-divisa` (`cod_divisa`),
  CONSTRAINT `cambiodivisa-divisa` FOREIGN KEY (`cod_divisa`) REFERENCES `divisas` (`cod_divisa`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cambio_divisa`
--

LOCK TABLES `cambio_divisa` WRITE;
/*!40000 ALTER TABLE `cambio_divisa` DISABLE KEYS */;
INSERT INTO `cambio_divisa` VALUES (1,1,1.00,'0000-00-00'),(2,2,67.10,'2025-03-17'),(3,2,67.10,'2025-03-12'),(4,2,67.10,'2025-03-11'),(6,2,65.64,'2025-03-18'),(7,2,64.80,'2025-03-10'),(8,2,63.43,'2025-03-09'),(9,2,67.63,'2025-03-19'),(10,2,70.59,'2025-04-03'),(11,3,10.00,'2025-04-07'),(12,2,86.11,'2025-04-25'),(13,3,95.00,'2025-04-07'),(14,4,115.00,'2025-04-28'),(15,5,105.00,'2025-04-29'),(16,2,86.85,'2025-04-30'),(17,3,10.00,'2025-04-07'),(18,3,95.00,'2025-04-07'),(19,4,115.00,'2025-04-28'),(20,5,105.00,'2025-04-29');
/*!40000 ALTER TABLE `cambio_divisa` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `carga`
--

DROP TABLE IF EXISTS `carga`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `carga` (
  `cod_carga` int(11) NOT NULL AUTO_INCREMENT,
  `fecha` datetime NOT NULL,
  `descripcion` varchar(100) NOT NULL,
  `status` int(11) NOT NULL,
  PRIMARY KEY (`cod_carga`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `carga`
--

LOCK TABLES `carga` WRITE;
/*!40000 ALTER TABLE `carga` DISABLE KEYS */;
INSERT INTO `carga` VALUES (1,'2025-04-29 22:20:42','carga prueba',1);
/*!40000 ALTER TABLE `carga` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `categoria_gasto`
--

DROP TABLE IF EXISTS `categoria_gasto`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `categoria_gasto` (
  `cod_cat_gasto` int(11) NOT NULL AUTO_INCREMENT,
  `cod_tipo_gasto` int(11) NOT NULL,
  `cod_frecuencia` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `fecha` date NOT NULL,
  PRIMARY KEY (`cod_cat_gasto`),
  KEY `cod_tipo_gasto` (`cod_tipo_gasto`),
  KEY `cod_frecuencia` (`cod_frecuencia`),
  CONSTRAINT `categoria_gasto_ibfk_1` FOREIGN KEY (`cod_tipo_gasto`) REFERENCES `tipo_gasto` (`cod_tipo_gasto`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `categoria_gasto_ibfk_2` FOREIGN KEY (`cod_frecuencia`) REFERENCES `frecuencia_gasto` (`cod_frecuencia`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `categoria_gasto`
--

LOCK TABLES `categoria_gasto` WRITE;
/*!40000 ALTER TABLE `categoria_gasto` DISABLE KEYS */;
/*!40000 ALTER TABLE `categoria_gasto` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `categorias`
--

DROP TABLE IF EXISTS `categorias`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `categorias` (
  `cod_categoria` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(30) NOT NULL,
  `status` int(11) NOT NULL,
  PRIMARY KEY (`cod_categoria`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `categorias`
--

LOCK TABLES `categorias` WRITE;
/*!40000 ALTER TABLE `categorias` DISABLE KEYS */;
INSERT INTO `categorias` VALUES (1,'queso',1),(2,'Jamones',1),(4,'Euros',1);
/*!40000 ALTER TABLE `categorias` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `clientes`
--

DROP TABLE IF EXISTS `clientes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `clientes` (
  `cod_cliente` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(80) NOT NULL,
  `apellido` varchar(80) NOT NULL,
  `cedula_rif` varchar(12) NOT NULL,
  `telefono` varchar(12) DEFAULT NULL,
  `email` varchar(70) DEFAULT NULL,
  `direccion` varchar(200) DEFAULT NULL,
  `status` int(11) NOT NULL,
  PRIMARY KEY (`cod_cliente`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `clientes`
--

LOCK TABLES `clientes` WRITE;
/*!40000 ALTER TABLE `clientes` DISABLE KEYS */;
INSERT INTO `clientes` VALUES (1,'generico','perez','12345678','','','',1),(2,'daniel','rojas','26779660','04245645108','danielrojas1901@gmail.com','av.florencio jimenez, parque residencial araguaney',1),(3,'Manuela','Mujica','28516209','12453145','manuelaalejandra.mujica@gmail.com','asdasda',1);
/*!40000 ALTER TABLE `clientes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `compras`
--

DROP TABLE IF EXISTS `compras`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `compras` (
  `cod_compra` int(11) NOT NULL AUTO_INCREMENT,
  `cod_prov` int(11) NOT NULL,
  `condicion_pago` enum('contado','credito') NOT NULL,
  `fecha_vencimiento` date DEFAULT NULL,
  `subtotal` decimal(10,2) NOT NULL,
  `total` decimal(10,2) NOT NULL,
  `impuesto_total` decimal(10,2) NOT NULL,
  `fecha` date NOT NULL,
  `descuento` int(11) DEFAULT NULL,
  `status` int(11) NOT NULL,
  PRIMARY KEY (`cod_compra`),
  KEY `compras-proveedores` (`cod_prov`),
  CONSTRAINT `compras-proveedores` FOREIGN KEY (`cod_prov`) REFERENCES `proveedores` (`cod_prov`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `compras`
--

LOCK TABLES `compras` WRITE;
/*!40000 ALTER TABLE `compras` DISABLE KEYS */;
INSERT INTO `compras` VALUES (5,1,'contado',NULL,55.00,55.00,0.00,'2025-04-29',NULL,1);
/*!40000 ALTER TABLE `compras` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `conciliacion`
--

DROP TABLE IF EXISTS `conciliacion`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `conciliacion` (
  `cod_conciliacion` int(11) NOT NULL,
  `url` varchar(200) NOT NULL,
  `fecha` datetime NOT NULL,
  `cod_cuenta_bancaria` int(11) NOT NULL,
  KEY `cod_cuenta_bancaria` (`cod_cuenta_bancaria`),
  CONSTRAINT `conciliacion_ibfk_1` FOREIGN KEY (`cod_cuenta_bancaria`) REFERENCES `cuenta_bancaria` (`cod_cuenta_bancaria`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `conciliacion`
--

LOCK TABLES `conciliacion` WRITE;
/*!40000 ALTER TABLE `conciliacion` DISABLE KEYS */;
/*!40000 ALTER TABLE `conciliacion` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `control`
--

DROP TABLE IF EXISTS `control`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `control` (
  `cod_control` int(11) NOT NULL AUTO_INCREMENT,
  `fecha_apertura` datetime NOT NULL,
  `fecha_cierre` datetime NOT NULL,
  `monto_apertura` decimal(10,2) NOT NULL,
  `monto_cierre` decimal(10,2) NOT NULL,
  `cod_caja` int(11) NOT NULL,
  `status` int(11) NOT NULL,
  PRIMARY KEY (`cod_control`),
  KEY `cod_caja` (`cod_caja`),
  CONSTRAINT `control_ibfk_1` FOREIGN KEY (`cod_caja`) REFERENCES `caja` (`cod_caja`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `control`
--

LOCK TABLES `control` WRITE;
/*!40000 ALTER TABLE `control` DISABLE KEYS */;
/*!40000 ALTER TABLE `control` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cuenta_bancaria`
--

DROP TABLE IF EXISTS `cuenta_bancaria`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cuenta_bancaria` (
  `cod_cuenta_bancaria` int(11) NOT NULL AUTO_INCREMENT,
  `cod_banco` int(11) NOT NULL,
  `cod_tipo_cuenta` int(11) NOT NULL,
  `numero_cuenta` varchar(20) NOT NULL,
  `saldo` decimal(10,2) NOT NULL,
  `cod_divisa` int(11) NOT NULL,
  `status` int(11) NOT NULL,
  PRIMARY KEY (`cod_cuenta_bancaria`),
  KEY `cod_banco` (`cod_banco`),
  KEY `cod_tipo_cuenta` (`cod_tipo_cuenta`),
  KEY `cod_divisa` (`cod_divisa`),
  CONSTRAINT `cuenta_bancaria_ibfk_1` FOREIGN KEY (`cod_banco`) REFERENCES `banco` (`cod_banco`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `cuenta_bancaria_ibfk_2` FOREIGN KEY (`cod_tipo_cuenta`) REFERENCES `tipo_cuenta` (`cod_tipo_cuenta`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `cuenta_bancaria_ibfk_3` FOREIGN KEY (`cod_divisa`) REFERENCES `divisas` (`cod_divisa`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cuenta_bancaria`
--

LOCK TABLES `cuenta_bancaria` WRITE;
/*!40000 ALTER TABLE `cuenta_bancaria` DISABLE KEYS */;
/*!40000 ALTER TABLE `cuenta_bancaria` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cuentas_contables`
--

DROP TABLE IF EXISTS `cuentas_contables`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cuentas_contables` (
  `cod_cuenta` int(11) NOT NULL AUTO_INCREMENT,
  `codigo_contable` varchar(20) NOT NULL,
  `nombre_cuenta` varchar(100) NOT NULL,
  `naturaleza` enum('deudora','acreedora') NOT NULL,
  `cuenta_padreid` int(11) DEFAULT NULL,
  `nivel` int(11) NOT NULL,
  `saldo` decimal(10,2) NOT NULL,
  `status` int(11) NOT NULL,
  PRIMARY KEY (`cod_cuenta`),
  UNIQUE KEY `codigo_contable` (`codigo_contable`),
  KEY `cuenta_padreid` (`cuenta_padreid`),
  CONSTRAINT `cuentas_contables_ibfk_1` FOREIGN KEY (`cuenta_padreid`) REFERENCES `cuentas_contables` (`cod_cuenta`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish2_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cuentas_contables`
--

LOCK TABLES `cuentas_contables` WRITE;
/*!40000 ALTER TABLE `cuentas_contables` DISABLE KEYS */;
/*!40000 ALTER TABLE `cuentas_contables` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `descarga`
--

DROP TABLE IF EXISTS `descarga`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `descarga` (
  `cod_descarga` int(11) NOT NULL AUTO_INCREMENT,
  `fecha` datetime NOT NULL,
  `descripcion` varchar(100) NOT NULL,
  `status` int(11) NOT NULL,
  PRIMARY KEY (`cod_descarga`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `descarga`
--

LOCK TABLES `descarga` WRITE;
/*!40000 ALTER TABLE `descarga` DISABLE KEYS */;
INSERT INTO `descarga` VALUES (1,'2025-04-29 23:49:15','ajuste stock prueba',1);
/*!40000 ALTER TABLE `descarga` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `detalle_asientos`
--

DROP TABLE IF EXISTS `detalle_asientos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `detalle_asientos` (
  `cod_det_asiento` int(11) NOT NULL AUTO_INCREMENT,
  `cod_asiento` int(11) NOT NULL,
  `cod_cuenta` int(11) NOT NULL,
  `monto` decimal(18,2) NOT NULL,
  `tipo` enum('Debe','Haber') DEFAULT NULL,
  PRIMARY KEY (`cod_det_asiento`),
  KEY `asiento_id` (`cod_asiento`),
  KEY `cuenta_id` (`cod_cuenta`),
  CONSTRAINT `detalle_asientos_ibfk_1` FOREIGN KEY (`cod_asiento`) REFERENCES `asientos_contables` (`cod_asiento`) ON DELETE CASCADE,
  CONSTRAINT `detalle_asientos_ibfk_2` FOREIGN KEY (`cod_cuenta`) REFERENCES `cuentas_contables` (`cod_cuenta`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish2_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `detalle_asientos`
--

LOCK TABLES `detalle_asientos` WRITE;
/*!40000 ALTER TABLE `detalle_asientos` DISABLE KEYS */;
/*!40000 ALTER TABLE `detalle_asientos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `detalle_carga`
--

DROP TABLE IF EXISTS `detalle_carga`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `detalle_carga` (
  `cod_det_carga` int(11) NOT NULL AUTO_INCREMENT,
  `cod_detallep` int(11) NOT NULL,
  `cod_carga` int(11) NOT NULL,
  `cantidad` float NOT NULL,
  PRIMARY KEY (`cod_det_carga`),
  KEY `detalle_carga-carga` (`cod_carga`),
  KEY `detalle_carga-detallep` (`cod_detallep`),
  CONSTRAINT `detalle_carga-carga` FOREIGN KEY (`cod_carga`) REFERENCES `carga` (`cod_carga`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `detalle_carga-detallep` FOREIGN KEY (`cod_detallep`) REFERENCES `detalle_productos` (`cod_detallep`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `detalle_carga`
--

LOCK TABLES `detalle_carga` WRITE;
/*!40000 ALTER TABLE `detalle_carga` DISABLE KEYS */;
INSERT INTO `detalle_carga` VALUES (1,5,1,6);
/*!40000 ALTER TABLE `detalle_carga` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `detalle_compras`
--

DROP TABLE IF EXISTS `detalle_compras`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `detalle_compras` (
  `cod_detallec` int(11) NOT NULL AUTO_INCREMENT,
  `cod_compra` int(11) NOT NULL,
  `cod_detallep` int(11) NOT NULL,
  `cantidad` decimal(10,2) NOT NULL,
  `monto` decimal(10,2) NOT NULL,
  PRIMARY KEY (`cod_detallec`),
  KEY `detalle_compras-compras` (`cod_compra`),
  KEY `detalle_compras-detalle_productos` (`cod_detallep`),
  CONSTRAINT `detalle_compras-compras` FOREIGN KEY (`cod_compra`) REFERENCES `compras` (`cod_compra`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `detalle_compras-detalle_productos` FOREIGN KEY (`cod_detallep`) REFERENCES `detalle_productos` (`cod_detallep`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `detalle_compras`
--

LOCK TABLES `detalle_compras` WRITE;
/*!40000 ALTER TABLE `detalle_compras` DISABLE KEYS */;
INSERT INTO `detalle_compras` VALUES (5,5,6,5.00,11.00);
/*!40000 ALTER TABLE `detalle_compras` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `detalle_descarga`
--

DROP TABLE IF EXISTS `detalle_descarga`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `detalle_descarga` (
  `cod_det_descarga` int(11) NOT NULL AUTO_INCREMENT,
  `cod_detallep` int(11) NOT NULL,
  `cod_descarga` int(11) NOT NULL,
  `cantidad` float NOT NULL,
  PRIMARY KEY (`cod_det_descarga`),
  KEY `detalle_descarga-detallep` (`cod_detallep`),
  KEY `detalle_descarga-descarga` (`cod_descarga`),
  CONSTRAINT `detalle_descarga-descarga` FOREIGN KEY (`cod_descarga`) REFERENCES `descarga` (`cod_descarga`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `detalle_descarga-detallep` FOREIGN KEY (`cod_detallep`) REFERENCES `detalle_productos` (`cod_detallep`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `detalle_descarga`
--

LOCK TABLES `detalle_descarga` WRITE;
/*!40000 ALTER TABLE `detalle_descarga` DISABLE KEYS */;
INSERT INTO `detalle_descarga` VALUES (1,5,1,0.2);
/*!40000 ALTER TABLE `detalle_descarga` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `detalle_operacion`
--

DROP TABLE IF EXISTS `detalle_operacion`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `detalle_operacion` (
  `cod_detalle_op` int(11) NOT NULL AUTO_INCREMENT,
  `detalle_operacion` varchar(50) NOT NULL,
  PRIMARY KEY (`cod_detalle_op`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `detalle_operacion`
--

LOCK TABLES `detalle_operacion` WRITE;
/*!40000 ALTER TABLE `detalle_operacion` DISABLE KEYS */;
/*!40000 ALTER TABLE `detalle_operacion` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `detalle_pago_emitido`
--

DROP TABLE IF EXISTS `detalle_pago_emitido`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `detalle_pago_emitido` (
  `cod_detallepagoe` int(11) NOT NULL AUTO_INCREMENT,
  `cod_pago_emitido` int(11) NOT NULL,
  `cod_tipo_pagoe` int(11) NOT NULL,
  `monto` decimal(10,2) NOT NULL,
  PRIMARY KEY (`cod_detallepagoe`),
  KEY `pagoe-dtpagoe` (`cod_pago_emitido`),
  KEY `dtpagoe-tipopagoe` (`cod_tipo_pagoe`),
  CONSTRAINT `detalle_pago_emitido_ibfk_1` FOREIGN KEY (`cod_pago_emitido`) REFERENCES `pago_emitido` (`cod_pago_emitido`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `detalle_pago_emitido_ibfk_2` FOREIGN KEY (`cod_tipo_pagoe`) REFERENCES `detalle_tipo_pago` (`cod_tipo_pago`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish2_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `detalle_pago_emitido`
--

LOCK TABLES `detalle_pago_emitido` WRITE;
/*!40000 ALTER TABLE `detalle_pago_emitido` DISABLE KEYS */;
/*!40000 ALTER TABLE `detalle_pago_emitido` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `detalle_pago_recibido`
--

DROP TABLE IF EXISTS `detalle_pago_recibido`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `detalle_pago_recibido` (
  `cod_detallepago` int(11) NOT NULL AUTO_INCREMENT,
  `cod_pago` int(11) NOT NULL,
  `cod_tipo_pago` int(11) NOT NULL,
  `monto` decimal(10,2) NOT NULL,
  PRIMARY KEY (`cod_detallepago`),
  KEY `detalle_pago-pago` (`cod_pago`),
  KEY `tipo_pago-detalle_pago` (`cod_tipo_pago`),
  CONSTRAINT `detalle_pago_recibido_ibfk_1` FOREIGN KEY (`cod_pago`) REFERENCES `pago_recibido` (`cod_pago`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `detalle_pago_recibido_ibfk_2` FOREIGN KEY (`cod_tipo_pago`) REFERENCES `detalle_tipo_pago` (`cod_tipo_pago`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `detalle_pago_recibido`
--

LOCK TABLES `detalle_pago_recibido` WRITE;
/*!40000 ALTER TABLE `detalle_pago_recibido` DISABLE KEYS */;
/*!40000 ALTER TABLE `detalle_pago_recibido` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `detalle_productos`
--

DROP TABLE IF EXISTS `detalle_productos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `detalle_productos` (
  `cod_detallep` int(11) NOT NULL AUTO_INCREMENT,
  `cod_presentacion` int(11) NOT NULL,
  `stock` float NOT NULL,
  `fecha_vencimiento` date DEFAULT NULL,
  `lote` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`cod_detallep`),
  KEY `detalle_producto-productos` (`cod_presentacion`),
  CONSTRAINT `detalle_productos_ibfk_1` FOREIGN KEY (`cod_presentacion`) REFERENCES `presentacion_producto` (`cod_presentacion`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `detalle_productos`
--

LOCK TABLES `detalle_productos` WRITE;
/*!40000 ALTER TABLE `detalle_productos` DISABLE KEYS */;
INSERT INTO `detalle_productos` VALUES (1,1,0,'0000-00-00',''),(2,1,8,'0000-00-00',''),(3,1,67,'0000-00-00',''),(4,1,23.5,'0000-00-00',''),(5,2,5.478,'2025-04-29','26-12'),(6,2,5,'2025-08-21','');
/*!40000 ALTER TABLE `detalle_productos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `detalle_tipo_pago`
--

DROP TABLE IF EXISTS `detalle_tipo_pago`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `detalle_tipo_pago` (
  `cod_tipo_pago` int(11) NOT NULL,
  `cod_metodo` int(11) NOT NULL,
  `tipo_moneda` enum('efectivo','digital') NOT NULL,
  `cod_cuenta_bancaria` int(11) DEFAULT NULL,
  `cod_caja` int(11) DEFAULT NULL,
  PRIMARY KEY (`cod_tipo_pago`),
  KEY `cod_cuenta_bancaria` (`cod_cuenta_bancaria`),
  KEY `cod_metodo` (`cod_metodo`),
  KEY `cod_caja` (`cod_caja`),
  CONSTRAINT `detalle_tipo_pago_ibfk_1` FOREIGN KEY (`cod_cuenta_bancaria`) REFERENCES `cuenta_bancaria` (`cod_cuenta_bancaria`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `detalle_tipo_pago_ibfk_3` FOREIGN KEY (`cod_metodo`) REFERENCES `tipo_pago` (`cod_metodo`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `detalle_tipo_pago_ibfk_4` FOREIGN KEY (`cod_caja`) REFERENCES `caja` (`cod_caja`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish2_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `detalle_tipo_pago`
--

LOCK TABLES `detalle_tipo_pago` WRITE;
/*!40000 ALTER TABLE `detalle_tipo_pago` DISABLE KEYS */;
/*!40000 ALTER TABLE `detalle_tipo_pago` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `detalle_ventas`
--

DROP TABLE IF EXISTS `detalle_ventas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `detalle_ventas` (
  `cod_detallev` int(11) NOT NULL AUTO_INCREMENT,
  `cod_venta` int(11) NOT NULL,
  `cod_detallep` int(11) NOT NULL,
  `importe` decimal(10,2) NOT NULL,
  `cantidad` float(10,3) NOT NULL,
  PRIMARY KEY (`cod_detallev`),
  KEY `cod_venta` (`cod_venta`),
  KEY `detalle_ventas-detalle_productos` (`cod_detallep`),
  CONSTRAINT `detalle_ventas-detalle_productos` FOREIGN KEY (`cod_detallep`) REFERENCES `detalle_productos` (`cod_detallep`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `detalle_ventas_ibfk_1` FOREIGN KEY (`cod_venta`) REFERENCES `ventas` (`cod_venta`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `detalle_ventas`
--

LOCK TABLES `detalle_ventas` WRITE;
/*!40000 ALTER TABLE `detalle_ventas` DISABLE KEYS */;
INSERT INTO `detalle_ventas` VALUES (1,7,1,70.00,10.000),(2,7,2,28.00,4.000),(3,8,5,4.31,0.322);
/*!40000 ALTER TABLE `detalle_ventas` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `detalle_vueltoe`
--

DROP TABLE IF EXISTS `detalle_vueltoe`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `detalle_vueltoe` (
  `cod_detallev` int(11) NOT NULL AUTO_INCREMENT,
  `cod_vuelto` int(11) NOT NULL,
  `cod_tipo_pago` int(11) NOT NULL,
  `monto` decimal(10,2) NOT NULL,
  PRIMARY KEY (`cod_detallev`),
  KEY `cod_vuelto` (`cod_vuelto`),
  KEY `cod_tipo_pago` (`cod_tipo_pago`),
  CONSTRAINT `detalle_vueltoe_ibfk_1` FOREIGN KEY (`cod_vuelto`) REFERENCES `vuelto_emitido` (`cod_vuelto`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `detalle_vueltoe_ibfk_2` FOREIGN KEY (`cod_tipo_pago`) REFERENCES `detalle_tipo_pago` (`cod_tipo_pago`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish2_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `detalle_vueltoe`
--

LOCK TABLES `detalle_vueltoe` WRITE;
/*!40000 ALTER TABLE `detalle_vueltoe` DISABLE KEYS */;
/*!40000 ALTER TABLE `detalle_vueltoe` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `detalle_vueltor`
--

DROP TABLE IF EXISTS `detalle_vueltor`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `detalle_vueltor` (
  `cod_detallev_r` int(11) NOT NULL AUTO_INCREMENT,
  `cod_vuelto_r` int(11) NOT NULL,
  `cod_tipo_pago` int(11) NOT NULL,
  `monto` decimal(10,2) NOT NULL,
  PRIMARY KEY (`cod_detallev_r`),
  KEY `cod_vuelto_r` (`cod_vuelto_r`),
  KEY `cod_tipo_pago` (`cod_tipo_pago`),
  CONSTRAINT `detalle_vueltor_ibfk_1` FOREIGN KEY (`cod_vuelto_r`) REFERENCES `vuelto_recibido` (`cod_vuelto_r`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `detalle_vueltor_ibfk_2` FOREIGN KEY (`cod_tipo_pago`) REFERENCES `detalle_tipo_pago` (`cod_tipo_pago`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `detalle_vueltor`
--

LOCK TABLES `detalle_vueltor` WRITE;
/*!40000 ALTER TABLE `detalle_vueltor` DISABLE KEYS */;
/*!40000 ALTER TABLE `detalle_vueltor` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `divisas`
--

DROP TABLE IF EXISTS `divisas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `divisas` (
  `cod_divisa` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(50) NOT NULL,
  `abreviatura` varchar(5) NOT NULL,
  `status` int(11) NOT NULL,
  PRIMARY KEY (`cod_divisa`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `divisas`
--

LOCK TABLES `divisas` WRITE;
/*!40000 ALTER TABLE `divisas` DISABLE KEYS */;
INSERT INTO `divisas` VALUES (1,'Bolívares','Bs',1),(2,'Dolares','$',1),(3,'','EUR',1),(4,'Binances','USDT',1),(5,'libra','Lb',1);
/*!40000 ALTER TABLE `divisas` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `empresa`
--

DROP TABLE IF EXISTS `empresa`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `empresa` (
  `rif` varchar(15) CHARACTER SET utf8 COLLATE utf8_spanish2_ci NOT NULL,
  `nombre` varchar(50) CHARACTER SET utf8 COLLATE utf8_spanish2_ci NOT NULL,
  `direccion` varchar(100) CHARACTER SET utf8 COLLATE utf8_spanish2_ci NOT NULL,
  `telefono` varchar(12) CHARACTER SET utf8 COLLATE utf8_spanish2_ci NOT NULL,
  `email` varchar(70) CHARACTER SET utf8 COLLATE utf8_spanish2_ci NOT NULL,
  `descripcion` varchar(100) CHARACTER SET utf8 COLLATE utf8_spanish2_ci NOT NULL,
  `logo` varchar(255) CHARACTER SET utf8 COLLATE utf8_spanish2_ci DEFAULT NULL,
  PRIMARY KEY (`rif`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `empresa`
--

LOCK TABLES `empresa` WRITE;
/*!40000 ALTER TABLE `empresa` DISABLE KEYS */;
INSERT INTO `empresa` VALUES ('J505284797','Quesera y Charcuteria Don Pedro 24','calle 60 entre carreras 12 y 13','04245645108','queseradonpedro24@gmail.com','venta al por menor de productos alimenticios','vista/dist/img/logo-icono.png');
/*!40000 ALTER TABLE `empresa` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `frecuencia_gasto`
--

DROP TABLE IF EXISTS `frecuencia_gasto`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `frecuencia_gasto` (
  `cod_frecuencia` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(50) NOT NULL,
  `dias` int(11) NOT NULL,
  PRIMARY KEY (`cod_frecuencia`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `frecuencia_gasto`
--

LOCK TABLES `frecuencia_gasto` WRITE;
/*!40000 ALTER TABLE `frecuencia_gasto` DISABLE KEYS */;
/*!40000 ALTER TABLE `frecuencia_gasto` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `gasto`
--

DROP TABLE IF EXISTS `gasto`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `gasto` (
  `cod_gasto` int(11) NOT NULL AUTO_INCREMENT,
  `cod_cat_gasto` int(11) NOT NULL,
  `descripcion` varchar(100) NOT NULL,
  `monto` decimal(10,2) NOT NULL,
  `status` int(11) NOT NULL,
  PRIMARY KEY (`cod_gasto`),
  KEY `cod_cat_gasto` (`cod_cat_gasto`),
  CONSTRAINT `gasto_ibfk_1` FOREIGN KEY (`cod_cat_gasto`) REFERENCES `categoria_gasto` (`cod_cat_gasto`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `gasto`
--

LOCK TABLES `gasto` WRITE;
/*!40000 ALTER TABLE `gasto` DISABLE KEYS */;
/*!40000 ALTER TABLE `gasto` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `marcas`
--

DROP TABLE IF EXISTS `marcas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `marcas` (
  `cod_marca` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(255) CHARACTER SET utf8 COLLATE utf8_spanish2_ci NOT NULL,
  `status` int(11) NOT NULL,
  PRIMARY KEY (`cod_marca`),
  UNIQUE KEY `marca_unica` (`nombre`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `marcas`
--

LOCK TABLES `marcas` WRITE;
/*!40000 ALTER TABLE `marcas` DISABLE KEYS */;
INSERT INTO `marcas` VALUES (6,'Alimex',1);
/*!40000 ALTER TABLE `marcas` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `movimientos`
--

DROP TABLE IF EXISTS `movimientos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `movimientos` (
  `cod_mov` int(11) NOT NULL AUTO_INCREMENT,
  `cod_operacion` int(11) DEFAULT NULL,
  `cod_tipo_op` int(11) NOT NULL,
  `cod_detalle_op` int(11) NOT NULL,
  `fecha` date NOT NULL,
  PRIMARY KEY (`cod_mov`),
  KEY `cod_tipo_op` (`cod_tipo_op`),
  KEY `cod_detalle_op` (`cod_detalle_op`),
  KEY `cod_operacion` (`cod_operacion`),
  CONSTRAINT `movimientos_ibfk_1` FOREIGN KEY (`cod_tipo_op`) REFERENCES `tipo_operacion` (`cod_tipo_op`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `movimientos_ibfk_2` FOREIGN KEY (`cod_detalle_op`) REFERENCES `detalle_operacion` (`cod_detalle_op`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `movimientos_ibfk_3` FOREIGN KEY (`cod_operacion`) REFERENCES `ventas` (`cod_venta`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `movimientos_ibfk_4` FOREIGN KEY (`cod_operacion`) REFERENCES `compras` (`cod_compra`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `movimientos_ibfk_5` FOREIGN KEY (`cod_operacion`) REFERENCES `gasto` (`cod_gasto`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `movimientos_ibfk_6` FOREIGN KEY (`cod_operacion`) REFERENCES `carga` (`cod_carga`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `movimientos_ibfk_7` FOREIGN KEY (`cod_operacion`) REFERENCES `descarga` (`cod_descarga`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish2_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `movimientos`
--

LOCK TABLES `movimientos` WRITE;
/*!40000 ALTER TABLE `movimientos` DISABLE KEYS */;
/*!40000 ALTER TABLE `movimientos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pago_emitido`
--

DROP TABLE IF EXISTS `pago_emitido`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pago_emitido` (
  `cod_pago_emitido` int(11) NOT NULL AUTO_INCREMENT,
  `tipo_pago` enum('compra','gasto') NOT NULL,
  `cod_vuelto_r` int(11) DEFAULT NULL,
  `fecha` date NOT NULL,
  `cod_compra` int(11) DEFAULT NULL,
  `cod_gasto` int(11) DEFAULT NULL,
  `monto_total` decimal(10,2) NOT NULL,
  PRIMARY KEY (`cod_pago_emitido`),
  KEY `compra-pago` (`cod_compra`),
  KEY `cod_gasto` (`cod_gasto`),
  KEY `cod_vuelto_r` (`cod_vuelto_r`),
  CONSTRAINT `pago_emitido_ibfk_1` FOREIGN KEY (`cod_compra`) REFERENCES `compras` (`cod_compra`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `pago_emitido_ibfk_2` FOREIGN KEY (`cod_gasto`) REFERENCES `gasto` (`cod_gasto`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `pago_emitido_ibfk_3` FOREIGN KEY (`cod_vuelto_r`) REFERENCES `vuelto_recibido` (`cod_vuelto_r`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish2_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pago_emitido`
--

LOCK TABLES `pago_emitido` WRITE;
/*!40000 ALTER TABLE `pago_emitido` DISABLE KEYS */;
/*!40000 ALTER TABLE `pago_emitido` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pago_recibido`
--

DROP TABLE IF EXISTS `pago_recibido`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pago_recibido` (
  `cod_pago` int(11) NOT NULL AUTO_INCREMENT,
  `cod_venta` int(11) NOT NULL,
  `cod_vuelto` int(11) DEFAULT NULL,
  `fecha` date NOT NULL,
  `monto_total` decimal(10,2) NOT NULL,
  PRIMARY KEY (`cod_pago`),
  KEY `pagos-ventas` (`cod_venta`),
  KEY `cod_vuelto` (`cod_vuelto`),
  CONSTRAINT `pago_recibido_ibfk_1` FOREIGN KEY (`cod_venta`) REFERENCES `ventas` (`cod_venta`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `pago_recibido_ibfk_2` FOREIGN KEY (`cod_vuelto`) REFERENCES `vuelto_emitido` (`cod_vuelto`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pago_recibido`
--

LOCK TABLES `pago_recibido` WRITE;
/*!40000 ALTER TABLE `pago_recibido` DISABLE KEYS */;
/*!40000 ALTER TABLE `pago_recibido` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `presentacion_producto`
--

DROP TABLE IF EXISTS `presentacion_producto`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `presentacion_producto` (
  `cod_presentacion` int(11) NOT NULL AUTO_INCREMENT,
  `cod_unidad` int(11) NOT NULL,
  `cod_producto` int(11) NOT NULL,
  `presentacion` varchar(30) DEFAULT NULL,
  `cantidad_presentacion` varchar(20) DEFAULT NULL,
  `costo` decimal(10,2) NOT NULL,
  `porcen_venta` int(11) NOT NULL,
  `excento` int(11) NOT NULL,
  PRIMARY KEY (`cod_presentacion`),
  KEY `cod_producto` (`cod_producto`),
  KEY `cod_unidad` (`cod_unidad`),
  CONSTRAINT `presentacion_producto_ibfk_1` FOREIGN KEY (`cod_producto`) REFERENCES `productos` (`cod_producto`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `presentacion_producto_ibfk_2` FOREIGN KEY (`cod_unidad`) REFERENCES `unidades_medida` (`cod_unidad`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `presentacion_producto`
--

LOCK TABLES `presentacion_producto` WRITE;
/*!40000 ALTER TABLE `presentacion_producto` DISABLE KEYS */;
INSERT INTO `presentacion_producto` VALUES (1,1,1,'pieza','10',7.00,0,1),(2,1,2,'pieza','4.5',11.00,34,1);
/*!40000 ALTER TABLE `presentacion_producto` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `presupuestos`
--

DROP TABLE IF EXISTS `presupuestos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `presupuestos` (
  `cod_presupuesto` int(11) NOT NULL AUTO_INCREMENT,
  `cod_tipo_op` int(11) DEFAULT NULL,
  `monto` decimal(10,2) NOT NULL,
  `mes` int(11) NOT NULL,
  `anio` int(11) NOT NULL,
  `notas` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`cod_presupuesto`),
  KEY `cod_tipo_op` (`cod_tipo_op`),
  CONSTRAINT `presupuestos_ibfk_1` FOREIGN KEY (`cod_tipo_op`) REFERENCES `tipo_operacion` (`cod_tipo_op`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `presupuestos`
--

LOCK TABLES `presupuestos` WRITE;
/*!40000 ALTER TABLE `presupuestos` DISABLE KEYS */;
/*!40000 ALTER TABLE `presupuestos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `productos`
--

DROP TABLE IF EXISTS `productos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `productos` (
  `cod_producto` int(11) NOT NULL AUTO_INCREMENT,
  `cod_categoria` int(11) NOT NULL,
  `cod_marca` int(11) DEFAULT NULL,
  `nombre` varchar(40) NOT NULL,
  `imagen` varchar(250) DEFAULT NULL,
  PRIMARY KEY (`cod_producto`),
  KEY `productos-categorias` (`cod_categoria`),
  KEY `cod_marca` (`cod_marca`),
  CONSTRAINT `productos-categorias` FOREIGN KEY (`cod_categoria`) REFERENCES `categorias` (`cod_categoria`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `productos_ibfk_1` FOREIGN KEY (`cod_marca`) REFERENCES `marcas` (`cod_marca`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `productos`
--

LOCK TABLES `productos` WRITE;
/*!40000 ALTER TABLE `productos` DISABLE KEYS */;
INSERT INTO `productos` VALUES (1,1,NULL,'Queso Duro',NULL),(2,2,6,'jamon de piernaa','vista/dist/img/productos/ImgThumb2.jpg');
/*!40000 ALTER TABLE `productos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `prov_representantes`
--

DROP TABLE IF EXISTS `prov_representantes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `prov_representantes` (
  `cod_representante` int(11) NOT NULL AUTO_INCREMENT,
  `cod_prov` int(11) NOT NULL,
  `cedula` varchar(12) NOT NULL,
  `nombre` varchar(80) NOT NULL,
  `apellido` varchar(80) DEFAULT NULL,
  `telefono` varchar(12) DEFAULT NULL,
  `status` int(11) NOT NULL,
  PRIMARY KEY (`cod_representante`),
  KEY `prov_representantes_ibfk_1` (`cod_prov`),
  CONSTRAINT `prov_representantes_ibfk_1` FOREIGN KEY (`cod_prov`) REFERENCES `proveedores` (`cod_prov`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `prov_representantes`
--

LOCK TABLES `prov_representantes` WRITE;
/*!40000 ALTER TABLE `prov_representantes` DISABLE KEYS */;
INSERT INTO `prov_representantes` VALUES (1,2,'10771716','samuel','Rojas','12453145',1);
/*!40000 ALTER TABLE `prov_representantes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `proveedores`
--

DROP TABLE IF EXISTS `proveedores`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `proveedores` (
  `cod_prov` int(11) NOT NULL AUTO_INCREMENT,
  `rif` varchar(15) NOT NULL,
  `razon_social` varchar(50) NOT NULL,
  `email` varchar(50) DEFAULT NULL,
  `direccion` varchar(250) DEFAULT NULL,
  `status` int(11) NOT NULL,
  PRIMARY KEY (`cod_prov`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `proveedores`
--

LOCK TABLES `proveedores` WRITE;
/*!40000 ALTER TABLE `proveedores` DISABLE KEYS */;
INSERT INTO `proveedores` VALUES (1,'J505284788','generico','','',1),(2,'J28516209','ST3M c.a','Pedroperez@gmail.com','av. libertador',1);
/*!40000 ALTER TABLE `proveedores` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `proyecciones_futuras`
--

DROP TABLE IF EXISTS `proyecciones_futuras`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
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
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`cod_proyeccion`),
  KEY `fk_proyeccion_producto` (`cod_producto`),
  CONSTRAINT `proyecciones_futuras_ibfk_1` FOREIGN KEY (`cod_producto`) REFERENCES `productos` (`cod_producto`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `proyecciones_futuras`
--

LOCK TABLES `proyecciones_futuras` WRITE;
/*!40000 ALTER TABLE `proyecciones_futuras` DISABLE KEYS */;
/*!40000 ALTER TABLE `proyecciones_futuras` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `proyecciones_historicas`
--

DROP TABLE IF EXISTS `proyecciones_historicas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
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
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`cod_historico`),
  KEY `fk_historico_producto` (`cod_producto`),
  CONSTRAINT `proyecciones_historicas_ibfk_1` FOREIGN KEY (`cod_producto`) REFERENCES `productos` (`cod_producto`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `proyecciones_historicas`
--

LOCK TABLES `proyecciones_historicas` WRITE;
/*!40000 ALTER TABLE `proyecciones_historicas` DISABLE KEYS */;
/*!40000 ALTER TABLE `proyecciones_historicas` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `stock_mensual`
--

DROP TABLE IF EXISTS `stock_mensual`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `stock_mensual` (
  `cod_stock` int(11) NOT NULL AUTO_INCREMENT,
  `cod_detallep` int(11) DEFAULT NULL,
  `mes` varchar(20) DEFAULT NULL,
  `ano` varchar(20) DEFAULT NULL,
  `stock_inicial` decimal(10,2) DEFAULT NULL,
  `stock_final` decimal(10,2) DEFAULT NULL,
  `ventas_cantidad` decimal(10,2) DEFAULT NULL,
  `rotacion` decimal(8,2) DEFAULT NULL,
  `dias_rotacion` decimal(8,2) DEFAULT NULL,
  PRIMARY KEY (`cod_stock`),
  KEY `cod_detallep` (`cod_detallep`),
  CONSTRAINT `stock_mensual_ibfk_1` FOREIGN KEY (`cod_detallep`) REFERENCES `detalle_productos` (`cod_detallep`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `stock_mensual`
--

LOCK TABLES `stock_mensual` WRITE;
/*!40000 ALTER TABLE `stock_mensual` DISABLE KEYS */;
/*!40000 ALTER TABLE `stock_mensual` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tipo_cuenta`
--

DROP TABLE IF EXISTS `tipo_cuenta`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tipo_cuenta` (
  `cod_tipo_cuenta` int(11) NOT NULL,
  `nombre` varchar(20) NOT NULL,
  PRIMARY KEY (`cod_tipo_cuenta`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tipo_cuenta`
--

LOCK TABLES `tipo_cuenta` WRITE;
/*!40000 ALTER TABLE `tipo_cuenta` DISABLE KEYS */;
INSERT INTO `tipo_cuenta` VALUES (1,'AHORRO'),(2,'CORRIENTE');
/*!40000 ALTER TABLE `tipo_cuenta` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tipo_gasto`
--

DROP TABLE IF EXISTS `tipo_gasto`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tipo_gasto` (
  `cod_tipo_gasto` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(50) NOT NULL,
  PRIMARY KEY (`cod_tipo_gasto`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tipo_gasto`
--

LOCK TABLES `tipo_gasto` WRITE;
/*!40000 ALTER TABLE `tipo_gasto` DISABLE KEYS */;
/*!40000 ALTER TABLE `tipo_gasto` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tipo_operacion`
--

DROP TABLE IF EXISTS `tipo_operacion`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tipo_operacion` (
  `cod_tipo_op` int(11) NOT NULL AUTO_INCREMENT,
  `tipo` varchar(50) NOT NULL,
  PRIMARY KEY (`cod_tipo_op`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tipo_operacion`
--

LOCK TABLES `tipo_operacion` WRITE;
/*!40000 ALTER TABLE `tipo_operacion` DISABLE KEYS */;
/*!40000 ALTER TABLE `tipo_operacion` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tipo_pago`
--

DROP TABLE IF EXISTS `tipo_pago`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tipo_pago` (
  `cod_metodo` int(11) NOT NULL AUTO_INCREMENT,
  `medio_pago` varchar(50) NOT NULL,
  `status` int(11) NOT NULL,
  PRIMARY KEY (`cod_metodo`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tipo_pago`
--

LOCK TABLES `tipo_pago` WRITE;
/*!40000 ALTER TABLE `tipo_pago` DISABLE KEYS */;
INSERT INTO `tipo_pago` VALUES (1,'Efectivo en Bs',1),(2,'Efectivo USD',1);
/*!40000 ALTER TABLE `tipo_pago` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tlf_proveedores`
--

DROP TABLE IF EXISTS `tlf_proveedores`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tlf_proveedores` (
  `cod_tlf` int(11) NOT NULL AUTO_INCREMENT,
  `cod_prov` int(11) NOT NULL,
  `telefono` varchar(12) NOT NULL,
  PRIMARY KEY (`cod_tlf`),
  KEY `cod_prov` (`cod_prov`),
  CONSTRAINT `tlf_proveedores_ibfk_1` FOREIGN KEY (`cod_prov`) REFERENCES `proveedores` (`cod_prov`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tlf_proveedores`
--

LOCK TABLES `tlf_proveedores` WRITE;
/*!40000 ALTER TABLE `tlf_proveedores` DISABLE KEYS */;
INSERT INTO `tlf_proveedores` VALUES (1,2,'04245645108'),(2,2,'12453145213');
/*!40000 ALTER TABLE `tlf_proveedores` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `unidades_medida`
--

DROP TABLE IF EXISTS `unidades_medida`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `unidades_medida` (
  `cod_unidad` int(11) NOT NULL AUTO_INCREMENT,
  `tipo_medida` char(10) NOT NULL,
  `status` int(11) NOT NULL,
  PRIMARY KEY (`cod_unidad`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `unidades_medida`
--

LOCK TABLES `unidades_medida` WRITE;
/*!40000 ALTER TABLE `unidades_medida` DISABLE KEYS */;
INSERT INTO `unidades_medida` VALUES (1,'kg',1);
/*!40000 ALTER TABLE `unidades_medida` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ventas`
--

DROP TABLE IF EXISTS `ventas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ventas` (
  `cod_venta` int(11) NOT NULL AUTO_INCREMENT,
  `cod_cliente` int(11) NOT NULL,
  `condicion_pago` enum('contado','credito') NOT NULL,
  `fecha_vencimiento` date DEFAULT NULL,
  `total` decimal(10,2) NOT NULL,
  `fecha` datetime NOT NULL,
  `status` int(11) NOT NULL,
  PRIMARY KEY (`cod_venta`),
  KEY `ventas-clientes` (`cod_cliente`),
  CONSTRAINT `ventas-clientes` FOREIGN KEY (`cod_cliente`) REFERENCES `clientes` (`cod_cliente`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ventas`
--

LOCK TABLES `ventas` WRITE;
/*!40000 ALTER TABLE `ventas` DISABLE KEYS */;
INSERT INTO `ventas` VALUES (7,1,'contado',NULL,98.00,'2025-04-08 20:03:36',3),(8,2,'contado',NULL,4.31,'2025-04-29 22:32:39',1);
/*!40000 ALTER TABLE `ventas` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `vuelto_emitido`
--

DROP TABLE IF EXISTS `vuelto_emitido`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `vuelto_emitido` (
  `cod_vuelto` int(11) NOT NULL AUTO_INCREMENT,
  `vuelto_total` decimal(10,2) NOT NULL,
  PRIMARY KEY (`cod_vuelto`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish2_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `vuelto_emitido`
--

LOCK TABLES `vuelto_emitido` WRITE;
/*!40000 ALTER TABLE `vuelto_emitido` DISABLE KEYS */;
/*!40000 ALTER TABLE `vuelto_emitido` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `vuelto_recibido`
--

DROP TABLE IF EXISTS `vuelto_recibido`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `vuelto_recibido` (
  `cod_vuelto_r` int(11) NOT NULL AUTO_INCREMENT,
  `vuelto_total` decimal(10,2) NOT NULL,
  PRIMARY KEY (`cod_vuelto_r`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `vuelto_recibido`
--

LOCK TABLES `vuelto_recibido` WRITE;
/*!40000 ALTER TABLE `vuelto_recibido` DISABLE KEYS */;
/*!40000 ALTER TABLE `vuelto_recibido` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-05-08  2:03:34
