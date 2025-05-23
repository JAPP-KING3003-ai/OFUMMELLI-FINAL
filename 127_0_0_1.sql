-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 22-05-2025 a las 21:27:11
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
-- Base de datos: `ofumelli_pos`
--
CREATE DATABASE IF NOT EXISTS `ofumelli_pos` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `ofumelli_pos`;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `areas`
--
-- Creación: 15-05-2025 a las 22:59:52
--

CREATE TABLE `areas` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `areas`
--

INSERT INTO `areas` (`id`, `nombre`, `created_at`, `updated_at`) VALUES
(1, 'Barra', '2025-05-15 22:59:54', '2025-05-15 22:59:54'),
(2, 'Cocina', '2025-05-15 22:59:54', '2025-05-15 22:59:54'),
(3, 'Carnes', '2025-05-15 22:59:54', '2025-05-15 22:59:54'),
(4, 'Cachapas', '2025-05-15 22:59:54', '2025-05-15 22:59:54');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cache`
--
-- Creación: 05-05-2025 a las 19:10:41
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cache_locks`
--
-- Creación: 05-05-2025 a las 19:10:42
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `clientes`
--
-- Creación: 05-05-2025 a las 19:10:40
--

CREATE TABLE `clientes` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `apellido` varchar(255) NOT NULL,
  `cedula` varchar(255) NOT NULL,
  `telefono` varchar(255) NOT NULL,
  `direccion` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `clientes`
--

INSERT INTO `clientes` (`id`, `nombre`, `apellido`, `cedula`, `telefono`, `direccion`, `email`, `created_at`, `updated_at`) VALUES
(1, 'Karely', 'Chaparro', '1234567', '04168014807', 'Artigas', 'admin@ofummelli.com', '2025-05-06 17:50:40', '2025-05-06 17:50:40');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `config`
--
-- Creación: 09-05-2025 a las 23:37:42
--

CREATE TABLE `config` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `key` varchar(255) NOT NULL,
  `value` text NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `config`
--

INSERT INTO `config` (`id`, `key`, `value`, `created_at`, `updated_at`) VALUES
(1, 'tasa_cambio', '500', NULL, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cuentas`
--
-- Creación: 22-05-2025 a las 16:56:59
-- Última actualización: 22-05-2025 a las 17:15:29
--

CREATE TABLE `cuentas` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `responsable_pedido` varchar(255) DEFAULT NULL,
  `barrero` varchar(255) DEFAULT NULL,
  `cliente_id` bigint(20) UNSIGNED DEFAULT NULL,
  `cliente_nombre` varchar(255) DEFAULT NULL,
  `usuario_id` bigint(20) UNSIGNED NOT NULL,
  `cajera` varchar(255) DEFAULT NULL,
  `productos` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`productos`)),
  `total_estimado` decimal(10,2) NOT NULL DEFAULT 0.00,
  `tasa_dia` decimal(10,2) DEFAULT NULL,
  `estacion` varchar(255) NOT NULL,
  `metodos_pago` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`metodos_pago`)),
  `fecha_apertura` timestamp NOT NULL DEFAULT current_timestamp(),
  `fecha_cierre` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `pagada` tinyint(1) NOT NULL DEFAULT 0,
  `pendiente` tinyint(1) NOT NULL DEFAULT 0,
  `cliente_nombre_manual` varchar(255) DEFAULT NULL,
  `total_pagado` decimal(10,2) NOT NULL DEFAULT 0.00,
  `vuelto` decimal(10,2) NOT NULL DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `cuentas`
--

INSERT INTO `cuentas` (`id`, `responsable_pedido`, `barrero`, `cliente_id`, `cliente_nombre`, `usuario_id`, `cajera`, `productos`, `total_estimado`, `tasa_dia`, `estacion`, `metodos_pago`, `fecha_apertura`, `fecha_cierre`, `created_at`, `updated_at`, `pagada`, `pendiente`, `cliente_nombre_manual`, `total_pagado`, `vuelto`) VALUES
(3, 'Leonel Ramos', NULL, NULL, 'Batman', 1, NULL, '\"[{\\\"producto_id\\\":\\\"40\\\",\\\"cantidad\\\":\\\"4\\\",\\\"precio\\\":5,\\\"subtotal\\\":20},{\\\"producto_id\\\":\\\"68\\\",\\\"cantidad\\\":\\\"3\\\",\\\"precio\\\":4,\\\"subtotal\\\":12}]\"', 32.00, NULL, 'Cachapas', '\"[{\\\"metodo\\\":\\\"divisas\\\",\\\"monto\\\":0,\\\"referencia\\\":null},{\\\"metodo\\\":\\\"divisas\\\",\\\"monto\\\":0,\\\"referencia\\\":null}]\"', '2025-05-05 19:24:00', NULL, '2025-05-05 19:27:58', '2025-05-05 19:27:58', 0, 0, NULL, 0.00, 0.00),
(4, 'Leonel Ramos', NULL, NULL, 'Jose Alejandro', 1, NULL, '\"[{\\\"producto_id\\\":\\\"70\\\",\\\"cantidad\\\":\\\"1\\\",\\\"precio\\\":4,\\\"subtotal\\\":4},{\\\"producto_id\\\":\\\"40\\\",\\\"cantidad\\\":\\\"15\\\",\\\"precio\\\":5,\\\"subtotal\\\":75}]\"', 79.00, NULL, 'Barra', '\"[{\\\"metodo\\\":\\\"divisas\\\",\\\"monto\\\":\\\"70\\\",\\\"referencia\\\":null},{\\\"metodo\\\":\\\"pago_movil\\\",\\\"monto\\\":\\\"900\\\",\\\"referencia\\\":\\\"123456\\\"}]\"', '2025-05-05 19:28:00', NULL, '2025-05-05 19:28:34', '2025-05-05 22:47:21', 0, 0, NULL, 0.00, 0.00),
(5, 'Leonel Ramos', NULL, NULL, 'Gabriel Rojas', 1, NULL, '\"[{\\\"producto_id\\\":\\\"65\\\",\\\"cantidad\\\":\\\"3\\\",\\\"precio\\\":4,\\\"subtotal\\\":12},{\\\"producto_id\\\":\\\"100\\\",\\\"cantidad\\\":\\\"4\\\",\\\"precio\\\":3,\\\"subtotal\\\":12},{\\\"producto_id\\\":\\\"66\\\",\\\"cantidad\\\":\\\"2\\\",\\\"precio\\\":4,\\\"subtotal\\\":8},{\\\"producto_id\\\":\\\"100\\\",\\\"cantidad\\\":\\\"5\\\",\\\"precio\\\":3,\\\"subtotal\\\":15},{\\\"producto_id\\\":\\\"51\\\",\\\"cantidad\\\":\\\"2\\\",\\\"precio\\\":20,\\\"subtotal\\\":40},{\\\"producto_id\\\":\\\"22\\\",\\\"cantidad\\\":\\\"1\\\",\\\"precio\\\":10,\\\"subtotal\\\":10},{\\\"producto_id\\\":\\\"86\\\",\\\"cantidad\\\":\\\"1\\\",\\\"precio\\\":5,\\\"subtotal\\\":5}]\"', 102.00, NULL, 'Barra', '\"[{\\\"metodo\\\":\\\"divisas\\\",\\\"monto\\\":\\\"100\\\",\\\"referencia\\\":null},{\\\"metodo\\\":\\\"pago_movil\\\",\\\"monto\\\":\\\"200\\\",\\\"referencia\\\":\\\"123456\\\"}]\"', '2025-05-05 19:29:00', NULL, '2025-05-05 19:31:14', '2025-05-05 22:37:56', 0, 0, NULL, 0.00, 0.00),
(6, 'Leonel Ramos', NULL, NULL, 'Erika Perez', 1, NULL, '\"[{\\\"producto_id\\\":\\\"59\\\",\\\"cantidad\\\":\\\"2\\\",\\\"precio\\\":10,\\\"subtotal\\\":20},{\\\"producto_id\\\":\\\"1\\\",\\\"cantidad\\\":\\\"1\\\",\\\"precio\\\":35,\\\"subtotal\\\":35}]\"', 55.00, NULL, 'Barra', '\"[{\\\"metodo\\\":\\\"cuenta_casa\\\",\\\"monto\\\":\\\"55\\\",\\\"referencia\\\":null}]\"', '2025-05-05 20:52:00', '2025-05-05 21:04:04', '2025-05-05 20:53:32', '2025-05-05 21:04:04', 1, 0, NULL, 0.00, 0.00),
(7, 'Leonel Ramos', NULL, NULL, 'Karelis Ruiz', 1, NULL, '\"[{\\\"producto_id\\\":\\\"1\\\",\\\"cantidad\\\":\\\"1\\\",\\\"precio\\\":35,\\\"subtotal\\\":35},{\\\"producto_id\\\":\\\"59\\\",\\\"cantidad\\\":\\\"1\\\",\\\"precio\\\":10,\\\"subtotal\\\":10},{\\\"producto_id\\\":\\\"45\\\",\\\"cantidad\\\":\\\"3\\\",\\\"precio\\\":3,\\\"subtotal\\\":9}]\"', 54.00, NULL, 'Carne en Vara', '\"[{\\\"metodo\\\":\\\"divisas\\\",\\\"monto\\\":\\\"0\\\",\\\"referencia\\\":null},{\\\"metodo\\\":\\\"pago_movil\\\",\\\"monto\\\":\\\"0\\\",\\\"referencia\\\":null}]\"', '2025-05-05 22:49:00', NULL, '2025-05-05 22:50:33', '2025-05-05 22:51:04', 0, 0, NULL, 0.00, 0.00),
(8, 'Leonel Ramos', NULL, NULL, 'Jose Alejandro', 1, NULL, '\"[{\\\"producto_id\\\":\\\"59\\\",\\\"cantidad\\\":\\\"1\\\",\\\"precio\\\":10,\\\"subtotal\\\":10},{\\\"producto_id\\\":\\\"1\\\",\\\"cantidad\\\":\\\"1\\\",\\\"precio\\\":35,\\\"subtotal\\\":35}]\"', 45.00, NULL, 'Barra', '\"[{\\\"metodo\\\":\\\"divisas\\\",\\\"monto\\\":\\\"40\\\",\\\"referencia\\\":null},{\\\"metodo\\\":\\\"pago_movil\\\",\\\"monto\\\":\\\"500\\\",\\\"referencia\\\":\\\"123456\\\"},{\\\"metodo\\\":\\\"debito\\\",\\\"monto\\\":\\\"0\\\",\\\"referencia\\\":null}]\"', '2025-05-05 22:58:00', NULL, '2025-05-05 22:59:18', '2025-05-06 00:48:52', 0, 0, NULL, 0.00, 0.00),
(9, 'Leonel', NULL, NULL, 'Jose Peña', 1, NULL, '\"[{\\\"producto_id\\\":\\\"40\\\",\\\"cantidad\\\":\\\"15\\\",\\\"precio\\\":5,\\\"subtotal\\\":75},{\\\"producto_id\\\":\\\"51\\\",\\\"cantidad\\\":\\\"2\\\",\\\"precio\\\":20,\\\"subtotal\\\":40},{\\\"producto_id\\\":\\\"6\\\",\\\"cantidad\\\":\\\"1\\\",\\\"precio\\\":12,\\\"subtotal\\\":12},{\\\"producto_id\\\":\\\"69\\\",\\\"cantidad\\\":\\\"15\\\",\\\"precio\\\":4,\\\"subtotal\\\":60},{\\\"producto_id\\\":\\\"60\\\",\\\"cantidad\\\":\\\"2\\\",\\\"precio\\\":1,\\\"subtotal\\\":2},{\\\"producto_id\\\":\\\"40\\\",\\\"cantidad\\\":\\\"1\\\",\\\"precio\\\":5,\\\"subtotal\\\":5},{\\\"producto_id\\\":\\\"96\\\",\\\"cantidad\\\":\\\"2\\\",\\\"precio\\\":1,\\\"subtotal\\\":2}]\"', 196.00, NULL, 'Barra', '\"[{\\\"metodo\\\":\\\"divisas\\\",\\\"monto\\\":\\\"180.00\\\",\\\"referencia\\\":null},{\\\"metodo\\\":\\\"pago_movil\\\",\\\"monto\\\":\\\"900.00\\\",\\\"referencia\\\":\\\"123456\\\"},{\\\"metodo\\\":\\\"pago_movil\\\",\\\"monto\\\":\\\"700.00\\\",\\\"referencia\\\":\\\"123456\\\"}]\"', '2025-05-06 00:40:00', '2025-05-06 13:48:17', '2025-05-06 00:52:08', '2025-05-06 13:48:17', 1, 0, NULL, 0.00, 0.00),
(10, 'Leonel Ramos', NULL, NULL, 'Katerina Reyes', 1, NULL, '\"[{\\\"producto_id\\\":\\\"37\\\",\\\"cantidad\\\":\\\"1\\\",\\\"precio\\\":10,\\\"subtotal\\\":10},{\\\"producto_id\\\":\\\"59\\\",\\\"cantidad\\\":\\\"1\\\",\\\"precio\\\":10,\\\"subtotal\\\":10},{\\\"producto_id\\\":\\\"100\\\",\\\"cantidad\\\":\\\"1\\\",\\\"precio\\\":3,\\\"subtotal\\\":3}]\"', 23.00, NULL, 'Cocina', '\"[{\\\"metodo\\\":\\\"divisas\\\",\\\"monto\\\":\\\"1.00\\\",\\\"referencia\\\":null}]\"', '2025-05-06 01:02:00', NULL, '2025-05-06 01:03:17', '2025-05-08 21:50:41', 0, 0, NULL, 0.00, 0.00),
(11, 'Leonel Ramos', NULL, NULL, 'Flash', 1, NULL, '\"[{\\\"producto_id\\\":\\\"46\\\",\\\"cantidad\\\":\\\"1\\\",\\\"precio\\\":60,\\\"subtotal\\\":60},{\\\"producto_id\\\":\\\"40\\\",\\\"cantidad\\\":\\\"1\\\",\\\"precio\\\":5,\\\"subtotal\\\":5},{\\\"producto_id\\\":\\\"1\\\",\\\"cantidad\\\":\\\"1\\\",\\\"precio\\\":35,\\\"subtotal\\\":35},{\\\"producto_id\\\":\\\"40\\\",\\\"cantidad\\\":\\\"1\\\",\\\"precio\\\":5,\\\"subtotal\\\":5}]\"', 105.00, NULL, 'Barra', '\"[{\\\"metodo\\\":\\\"divisas\\\",\\\"monto\\\":\\\"50.00\\\",\\\"referencia\\\":null},{\\\"metodo\\\":\\\"pago_movil\\\",\\\"monto\\\":\\\"5500.00\\\",\\\"referencia\\\":\\\"123456\\\"}]\"', '2025-05-06 03:37:00', '2025-05-06 13:49:27', '2025-05-06 03:39:46', '2025-05-06 13:49:27', 1, 0, NULL, 0.00, 0.00),
(12, 'Leonel Ramos', NULL, NULL, 'Superman', 1, NULL, '\"[{\\\"producto_id\\\":\\\"39\\\",\\\"cantidad\\\":\\\"15\\\",\\\"precio\\\":16,\\\"subtotal\\\":240},{\\\"producto_id\\\":\\\"66\\\",\\\"cantidad\\\":\\\"25\\\",\\\"precio\\\":4,\\\"subtotal\\\":100},{\\\"producto_id\\\":\\\"68\\\",\\\"cantidad\\\":\\\"1\\\",\\\"precio\\\":4,\\\"subtotal\\\":4}]\"', 344.00, NULL, 'Cachapas', '\"[{\\\"metodo\\\":\\\"divisas\\\",\\\"monto\\\":\\\"45.00\\\",\\\"referencia\\\":null},{\\\"metodo\\\":\\\"pago_movil\\\",\\\"monto\\\":\\\"12000.00\\\",\\\"referencia\\\":\\\"123456\\\"},{\\\"metodo\\\":\\\"debito\\\",\\\"monto\\\":\\\"12900.00\\\",\\\"referencia\\\":null}]\"', '2025-05-06 04:43:00', NULL, '2025-05-06 04:44:07', '2025-05-09 05:44:29', 0, 0, NULL, 0.00, 0.00),
(13, 'Leonel Ramos', NULL, NULL, 'PRUEBA', 1, NULL, '\"[{\\\"producto_id\\\":\\\"100\\\",\\\"cantidad\\\":\\\"1\\\",\\\"precio\\\":3,\\\"subtotal\\\":3},{\\\"producto_id\\\":\\\"68\\\",\\\"cantidad\\\":\\\"1\\\",\\\"precio\\\":4,\\\"subtotal\\\":4},{\\\"producto_id\\\":\\\"66\\\",\\\"cantidad\\\":\\\"1\\\",\\\"precio\\\":4,\\\"subtotal\\\":4}]\"', 11.00, NULL, 'Carne en Vara', '\"[{\\\"metodo\\\":\\\"pago_movil\\\",\\\"monto\\\":\\\"100.00\\\",\\\"referencia\\\":\\\"123456\\\"},{\\\"metodo\\\":\\\"bs_efectivo\\\",\\\"monto\\\":\\\"200.00\\\",\\\"referencia\\\":null},{\\\"metodo\\\":\\\"debito\\\",\\\"monto\\\":\\\"100.00\\\",\\\"referencia\\\":null},{\\\"metodo\\\":\\\"divisas\\\",\\\"monto\\\":\\\"3.00\\\",\\\"referencia\\\":null},{\\\"metodo\\\":\\\"euros\\\",\\\"monto\\\":\\\"4.00\\\",\\\"referencia\\\":null}]\"', '2025-05-06 07:15:00', '2025-05-06 13:33:38', '2025-05-06 07:16:16', '2025-05-06 13:33:38', 1, 0, NULL, 0.00, 0.00),
(14, 'Karely', NULL, NULL, 'Juan Lopez', 1, NULL, '\"[{\\\"producto_id\\\":\\\"59\\\",\\\"cantidad\\\":\\\"1\\\",\\\"precio\\\":10,\\\"subtotal\\\":10},{\\\"producto_id\\\":\\\"21\\\",\\\"cantidad\\\":\\\"1\\\",\\\"precio\\\":10,\\\"subtotal\\\":10},{\\\"producto_id\\\":\\\"42\\\",\\\"cantidad\\\":\\\"15\\\",\\\"precio\\\":5,\\\"subtotal\\\":75}]\"', 95.00, NULL, 'Cocina', '\"[{\\\"metodo\\\":\\\"divisas\\\",\\\"monto\\\":\\\"50.00\\\",\\\"referencia\\\":null},{\\\"metodo\\\":\\\"pago_movil\\\",\\\"monto\\\":\\\"3200.00\\\",\\\"referencia\\\":\\\"123456\\\"},{\\\"metodo\\\":\\\"debito\\\",\\\"monto\\\":\\\"1000.00\\\",\\\"referencia\\\":null},{\\\"metodo\\\":\\\"bs_efectivo\\\",\\\"monto\\\":\\\"300.00\\\",\\\"referencia\\\":null}]\"', '2025-05-06 17:52:00', '2025-05-06 18:07:48', '2025-05-06 18:00:46', '2025-05-06 18:07:48', 1, 0, NULL, 0.00, 0.00),
(15, 'Leonel Ramos', NULL, NULL, 'Juan Suarez', 1, NULL, '\"[{\\\"producto_id\\\":\\\"59\\\",\\\"cantidad\\\":\\\"2\\\",\\\"precio\\\":10,\\\"subtotal\\\":20},{\\\"producto_id\\\":\\\"1\\\",\\\"cantidad\\\":\\\"1\\\",\\\"precio\\\":35,\\\"subtotal\\\":35},{\\\"producto_id\\\":\\\"42\\\",\\\"cantidad\\\":\\\"15\\\",\\\"precio\\\":5,\\\"subtotal\\\":75}]\"', 130.00, NULL, 'Cocina', '\"[{\\\"metodo\\\":\\\"divisas\\\",\\\"monto\\\":\\\"30.00\\\",\\\"referencia\\\":null},{\\\"metodo\\\":\\\"pago_movil\\\",\\\"monto\\\":\\\"5000.00\\\",\\\"referencia\\\":\\\"123456\\\"},{\\\"metodo\\\":\\\"debito\\\",\\\"monto\\\":\\\"2000.00\\\",\\\"referencia\\\":null},{\\\"metodo\\\":\\\"bs_efectivo\\\",\\\"monto\\\":\\\"3000.00\\\",\\\"referencia\\\":null}]\"', '2025-05-06 19:41:00', '2025-05-06 19:55:44', '2025-05-06 19:45:55', '2025-05-06 19:55:44', 1, 0, NULL, 0.00, 0.00),
(16, 'Leonel Ramos', NULL, NULL, 'Mood Blanco', 1, NULL, '\"[{\\\"producto_id\\\":\\\"96\\\",\\\"cantidad\\\":\\\"2\\\",\\\"precio\\\":1,\\\"subtotal\\\":2}]\"', 2.00, NULL, 'Barra', '\"[{\\\"metodo\\\":\\\"pago_movil\\\",\\\"monto\\\":\\\"200.00\\\",\\\"referencia\\\":\\\"123456\\\"}]\"', '2025-05-09 05:46:00', NULL, '2025-05-09 05:46:59', '2025-05-10 00:46:15', 0, 0, NULL, 0.00, 0.00),
(17, 'Leonel Ramos', NULL, NULL, 'Mood Blanco', 1, NULL, '\"[{\\\"producto_id\\\":\\\"96\\\",\\\"cantidad\\\":\\\"2\\\",\\\"precio\\\":1,\\\"subtotal\\\":2}]\"', 2.00, NULL, 'Barra', '\"[{\\\"metodo\\\":null,\\\"monto\\\":0,\\\"referencia\\\":null}]\"', '2025-05-09 06:15:00', NULL, '2025-05-09 06:15:55', '2025-05-09 06:15:55', 0, 0, NULL, 0.00, 0.00),
(18, 'Leonel Ramos', NULL, NULL, 'COREANO LOCO', 1, NULL, '\"[{\\\"producto_id\\\":\\\"96\\\",\\\"cantidad\\\":\\\"2\\\",\\\"precio\\\":1,\\\"subtotal\\\":2}]\"', 2.00, NULL, 'Barra', '\"[{\\\"metodo\\\":\\\"pago_movil\\\",\\\"monto\\\":\\\"234.00\\\",\\\"referencia\\\":\\\"123456\\\"}]\"', '2025-05-10 00:46:00', NULL, '2025-05-10 00:47:17', '2025-05-10 00:48:11', 0, 0, NULL, 0.00, 0.00),
(19, 'Leonel Ramos', NULL, NULL, 'Angel Miguel', 1, NULL, '\"[{\\\"producto_id\\\":\\\"96\\\",\\\"cantidad\\\":\\\"2\\\",\\\"precio\\\":1,\\\"subtotal\\\":2}]\"', 2.00, 1.00, 'Barra', '\"[{\\\"metodo\\\":\\\"bs_efectivo\\\",\\\"monto\\\":\\\"234.00\\\",\\\"referencia\\\":null}]\"', '2025-05-10 00:56:00', NULL, '2025-05-10 00:57:11', '2025-05-10 01:20:22', 0, 0, NULL, 0.00, 0.00),
(20, 'Leonel Ramos', NULL, NULL, 'Estoy Cansado Jefe', 1, NULL, '\"[{\\\"producto_id\\\":\\\"96\\\",\\\"cantidad\\\":\\\"2\\\",\\\"precio\\\":1,\\\"subtotal\\\":2}]\"', 2.00, 1.00, 'Barra', '\"[{\\\"metodo\\\":\\\"bs_efectivo\\\",\\\"monto\\\":\\\"200.00\\\",\\\"referencia\\\":null}]\"', '2025-05-10 01:20:00', NULL, '2025-05-10 01:21:34', '2025-05-10 01:22:10', 0, 0, NULL, 0.00, 0.00),
(21, 'Leonel Ramos', NULL, NULL, 'sdhdvbhjds', 1, NULL, '\"[{\\\"producto_id\\\":\\\"96\\\",\\\"cantidad\\\":\\\"2\\\",\\\"precio\\\":1,\\\"subtotal\\\":2}]\"', 2.00, 1.00, 'Barra', '\"[{\\\"metodo\\\":\\\"bs_efectivo\\\",\\\"monto\\\":\\\"1000.00\\\",\\\"referencia\\\":null}]\"', '2025-05-10 01:50:00', NULL, '2025-05-10 01:51:19', '2025-05-10 02:59:08', 0, 0, NULL, 0.00, 0.00),
(22, 'Leonel Ramos', NULL, NULL, 'PRUEBA #1', 1, NULL, '\"[{\\\"producto_id\\\":\\\"96\\\",\\\"cantidad\\\":\\\"2\\\",\\\"precio\\\":1,\\\"subtotal\\\":2}]\"', 2.00, 1.00, 'Barra', '\"[{\\\"metodo\\\":\\\"pago_movil\\\",\\\"monto\\\":\\\"1000.00\\\",\\\"referencia\\\":\\\"123456\\\"}]\"', '2025-05-10 20:14:00', NULL, '2025-05-10 20:14:41', '2025-05-10 20:15:35', 0, 0, NULL, 0.00, 0.00),
(23, 'Leonel Ramos', NULL, NULL, 'PRUEBA #2', 1, NULL, '\"[{\\\"producto_id\\\":\\\"96\\\",\\\"cantidad\\\":\\\"2\\\",\\\"precio\\\":1,\\\"subtotal\\\":2}]\"', 2.00, 999.00, 'Barra', '\"[{\\\"metodo\\\":null,\\\"monto\\\":\\\"0.00\\\",\\\"referencia\\\":null}]\"', '2025-05-10 20:34:00', NULL, '2025-05-10 20:34:38', '2025-05-10 21:07:40', 0, 0, NULL, 0.00, 0.00),
(24, 'Leonel Ramos', NULL, NULL, 'PRUEBA #3', 1, NULL, '\"[{\\\"producto_id\\\":\\\"96\\\",\\\"cantidad\\\":\\\"2\\\",\\\"precio\\\":1,\\\"subtotal\\\":2}]\"', 2.00, 100.06, 'Barra', '\"[{\\\"metodo\\\":\\\"pago_movil\\\",\\\"monto\\\":\\\"100.00\\\",\\\"referencia\\\":\\\"123456\\\"},{\\\"metodo\\\":\\\"bs_efectivo\\\",\\\"monto\\\":\\\"100.00\\\",\\\"referencia\\\":null}]\"', '2025-05-10 21:08:00', NULL, '2025-05-10 21:09:17', '2025-05-11 23:38:54', 0, 0, NULL, 0.00, 0.00),
(25, 'Leonel Ramos', NULL, NULL, 'PRUEBA #4', 1, NULL, '\"[{\\\"producto_id\\\":\\\"96\\\",\\\"cantidad\\\":\\\"2\\\",\\\"precio\\\":1,\\\"subtotal\\\":2}]\"', 2.00, NULL, 'Barra', '\"[{\\\"metodo\\\":\\\"pago_movil\\\",\\\"monto\\\":0,\\\"referencia\\\":null}]\"', '2025-05-12 00:21:00', NULL, '2025-05-12 00:22:05', '2025-05-12 00:22:05', 0, 0, NULL, 0.00, 0.00),
(26, 'Leonel Ramos', NULL, NULL, 'PRUEBA #5', 1, NULL, '\"[{\\\"producto_id\\\":\\\"96\\\",\\\"cantidad\\\":\\\"2\\\",\\\"precio\\\":1,\\\"subtotal\\\":2}]\"', 2.00, NULL, 'Barra', '\"[{\\\"metodo\\\":null,\\\"monto\\\":0,\\\"referencia\\\":null}]\"', '2025-05-12 19:28:00', NULL, '2025-05-12 19:28:43', '2025-05-12 19:28:43', 0, 0, NULL, 0.00, 0.00),
(27, 'Leonel Ramos', NULL, NULL, 'PRUEBA #6', 1, NULL, '\"[{\\\"producto_id\\\":\\\"96\\\",\\\"cantidad\\\":\\\"2\\\",\\\"precio\\\":1,\\\"subtotal\\\":2}]\"', 2.00, 500.00, 'Barra', '\"[{\\\"metodo\\\":\\\"pago_movil\\\",\\\"monto\\\":\\\"1000.00\\\",\\\"referencia\\\":\\\"123456\\\"}]\"', '2025-05-13 00:35:00', NULL, '2025-05-13 00:35:24', '2025-05-13 02:58:29', 0, 0, NULL, 0.00, 0.00),
(28, 'Leonel Ramos', NULL, NULL, 'PRUEBA #8', 1, NULL, '\"[{\\\"producto_id\\\":\\\"96\\\",\\\"cantidad\\\":\\\"2\\\",\\\"precio\\\":1,\\\"subtotal\\\":2}]\"', 2.00, NULL, 'Barra', '\"[{\\\"metodo\\\":\\\"pago_movil\\\",\\\"monto\\\":0,\\\"referencia\\\":null},{\\\"metodo\\\":\\\"debito\\\",\\\"monto\\\":0,\\\"referencia\\\":null},{\\\"metodo\\\":\\\"bs_efectivo\\\",\\\"monto\\\":0,\\\"referencia\\\":null}]\"', '2025-05-13 02:13:00', NULL, '2025-05-13 02:14:04', '2025-05-13 02:14:04', 0, 0, NULL, 0.00, 0.00),
(29, 'Leonel Ramos', NULL, NULL, 'PRUEBA #7', 1, NULL, '\"[{\\\"producto_id\\\":\\\"96\\\",\\\"cantidad\\\":\\\"2\\\",\\\"precio\\\":1,\\\"subtotal\\\":2}]\"', 2.00, NULL, 'Barra', '\"[{\\\"metodo\\\":null,\\\"monto\\\":0,\\\"referencia\\\":null}]\"', '2025-05-13 02:44:00', NULL, '2025-05-13 02:45:16', '2025-05-13 02:45:16', 0, 0, NULL, 0.00, 0.00),
(31, 'Leonel Ramos', NULL, NULL, 'PRUEBA #9', 1, NULL, '\"[{\\\"producto_id\\\":\\\"96\\\",\\\"cantidad\\\":\\\"2\\\",\\\"precio\\\":1,\\\"subtotal\\\":2},{\\\"producto_id\\\":\\\"59\\\",\\\"cantidad\\\":\\\"6\\\",\\\"precio\\\":10,\\\"subtotal\\\":60},{\\\"producto_id\\\":\\\"39\\\",\\\"cantidad\\\":\\\"6\\\",\\\"precio\\\":16,\\\"subtotal\\\":96},{\\\"producto_id\\\":\\\"66\\\",\\\"cantidad\\\":\\\"15\\\",\\\"precio\\\":4,\\\"subtotal\\\":60}]\"', 218.00, 100.04, 'Barra', '\"[{\\\"metodo\\\":\\\"divisas\\\",\\\"monto\\\":\\\"28.07\\\",\\\"referencia\\\":null,\\\"cuenta\\\":\\\"esmerley_venezuela\\\",\\\"banco\\\":\\\"venezuela\\\",\\\"autorizado_por\\\":null},{\\\"metodo\\\":\\\"zelle\\\",\\\"monto\\\":\\\"10.00\\\",\\\"referencia\\\":\\\"123456\\\",\\\"cuenta\\\":null,\\\"banco\\\":\\\"venezuela\\\",\\\"autorizado_por\\\":null},{\\\"metodo\\\":\\\"punto_venta\\\",\\\"monto\\\":\\\"1300.00\\\",\\\"referencia\\\":\\\"123456\\\",\\\"cuenta\\\":null,\\\"banco\\\":\\\"venezuela\\\",\\\"autorizado_por\\\":null},{\\\"metodo\\\":\\\"punto_venta\\\",\\\"monto\\\":\\\"500.00\\\",\\\"referencia\\\":\\\"123456\\\",\\\"cuenta\\\":null,\\\"banco\\\":\\\"bancamiga\\\",\\\"autorizado_por\\\":null},{\\\"metodo\\\":\\\"tarjeta_credito_dolares\\\",\\\"monto\\\":\\\"30.00\\\",\\\"referencia\\\":\\\"123456\\\",\\\"cuenta\\\":null,\\\"banco\\\":\\\"venezuela\\\",\\\"autorizado_por\\\":null},{\\\"metodo\\\":\\\"tarjeta_credito_bolivares\\\",\\\"monto\\\":\\\"999.00\\\",\\\"referencia\\\":\\\"123456\\\",\\\"cuenta\\\":null,\\\"banco\\\":\\\"venezuela\\\",\\\"autorizado_por\\\":null},{\\\"metodo\\\":\\\"bs_efectivo\\\",\\\"monto\\\":\\\"200.00\\\",\\\"referencia\\\":null,\\\"cuenta\\\":null,\\\"banco\\\":\\\"venezuela\\\",\\\"autorizado_por\\\":null},{\\\"metodo\\\":\\\"euros\\\",\\\"monto\\\":\\\"5.00\\\",\\\"referencia\\\":null,\\\"cuenta\\\":null,\\\"banco\\\":\\\"venezuela\\\",\\\"autorizado_por\\\":null},{\\\"metodo\\\":\\\"propina\\\",\\\"monto\\\":\\\"999.00\\\",\\\"referencia\\\":null,\\\"cuenta\\\":null,\\\"banco\\\":\\\"venezuela\\\",\\\"autorizado_por\\\":null},{\\\"metodo\\\":\\\"cuenta_casa\\\",\\\"monto\\\":\\\"218.00\\\",\\\"referencia\\\":null,\\\"cuenta\\\":null,\\\"banco\\\":\\\"venezuela\\\",\\\"autorizado_por\\\":\\\"Karelis Chaparro\\\"},{\\\"metodo\\\":\\\"pago_movil\\\",\\\"monto\\\":\\\"12000.00\\\",\\\"referencia\\\":\\\"123456\\\",\\\"cuenta\\\":\\\"esmerley_venezuela\\\",\\\"banco\\\":\\\"venezuela\\\",\\\"autorizado_por\\\":null}]\"', '2025-05-13 03:26:00', NULL, '2025-05-13 03:27:33', '2025-05-14 11:58:33', 0, 0, NULL, 0.00, 16071.07),
(34, 'Leonel Ramos', NULL, NULL, 'PRUEBA #10', 1, NULL, '\"[{\\\"producto_id\\\":\\\"96\\\",\\\"cantidad\\\":\\\"2\\\",\\\"precio\\\":1,\\\"subtotal\\\":2}]\"', 2.00, 100.07, 'Barra', '\"[{\\\"metodo\\\":\\\"pago_movil\\\",\\\"monto\\\":\\\"200.14\\\",\\\"referencia\\\":\\\"123456\\\",\\\"cuenta\\\":\\\"genesis_banesco\\\",\\\"banco\\\":\\\"venezuela\\\",\\\"autorizado_por\\\":null}]\"', '2025-05-13 06:25:00', NULL, '2025-05-13 06:26:23', '2025-05-13 06:27:15', 0, 0, NULL, 0.00, 0.00),
(35, 'Leonel Ramos', NULL, NULL, 'PRUEBA #11', 1, NULL, '\"[{\\\"producto_id\\\":\\\"96\\\",\\\"cantidad\\\":\\\"2\\\",\\\"precio\\\":1,\\\"subtotal\\\":2}]\"', 2.00, NULL, 'Barra', '\"[{\\\"metodo\\\":null,\\\"monto\\\":0,\\\"referencia\\\":null}]\"', '2025-05-13 06:36:00', NULL, '2025-05-13 06:36:32', '2025-05-13 06:36:32', 0, 0, NULL, 0.00, 0.00),
(37, 'Leonel Ramos', NULL, NULL, 'PRUEBA #12', 1, NULL, '\"[{\\\"producto_id\\\":\\\"96\\\",\\\"cantidad\\\":\\\"2\\\",\\\"precio\\\":1,\\\"subtotal\\\":2}]\"', 2.00, 500.00, 'Barra', '\"[{\\\"metodo\\\":\\\"divisas\\\",\\\"monto\\\":\\\"2.00\\\",\\\"referencia\\\":\\\"123456\\\",\\\"cuenta\\\":null,\\\"banco\\\":null,\\\"autorizado_por\\\":null},{\\\"metodo\\\":\\\"propina\\\",\\\"monto\\\":\\\"5.00\\\",\\\"referencia\\\":null,\\\"cuenta\\\":null,\\\"banco\\\":null,\\\"autorizado_por\\\":null}]\"', '2025-05-13 06:41:00', NULL, '2025-05-13 06:42:09', '2025-05-13 06:58:05', 0, 0, NULL, 0.00, 0.00),
(38, 'Leonel Ramos', NULL, NULL, 'PRUEBA #13', 1, NULL, '\"[{\\\"producto_id\\\":\\\"96\\\",\\\"cantidad\\\":\\\"2\\\",\\\"precio\\\":1,\\\"subtotal\\\":2}]\"', 2.00, 100.70, 'Barra', '\"[{\\\"metodo\\\":\\\"pago_movil\\\",\\\"monto\\\":\\\"201.40\\\",\\\"referencia\\\":\\\"123456\\\",\\\"cuenta\\\":\\\"esmerley_venezuela\\\",\\\"banco\\\":\\\"venezuela\\\",\\\"autorizado_por\\\":null}]\"', '2025-05-13 06:48:00', '2025-05-13 06:49:35', '2025-05-13 06:48:32', '2025-05-13 06:49:35', 1, 0, NULL, 0.00, 0.00),
(40, 'Leonel Ramos', NULL, NULL, 'PRUEBA #14', 1, NULL, '\"[{\\\"producto_id\\\":\\\"96\\\",\\\"cantidad\\\":\\\"1\\\",\\\"precio\\\":1,\\\"subtotal\\\":1}]\"', 1.00, 100.40, 'Barra', '\"[{\\\"metodo\\\":\\\"divisas\\\",\\\"monto\\\":\\\"2.00\\\",\\\"referencia\\\":null,\\\"cuenta\\\":\\\"seleccionar_cuenta\\\",\\\"banco\\\":\\\"venezuela\\\",\\\"autorizado_por\\\":null}]\"', '2025-05-13 07:22:00', NULL, '2025-05-13 07:22:44', '2025-05-13 07:23:22', 0, 0, NULL, 0.00, 0.00),
(41, 'Leonel Ramos', NULL, NULL, 'PRUEBA #15', 1, NULL, '\"[{\\\"producto_id\\\":\\\"96\\\",\\\"cantidad\\\":\\\"1\\\",\\\"precio\\\":1,\\\"subtotal\\\":1}]\"', 1.00, 100.40, 'Barra', '\"[{\\\"metodo\\\":\\\"divisas\\\",\\\"monto\\\":\\\"2.00\\\",\\\"referencia\\\":null,\\\"cuenta\\\":\\\"seleccionar_cuenta\\\",\\\"banco\\\":\\\"venezuela\\\",\\\"autorizado_por\\\":null}]\"', '2025-05-13 07:26:00', NULL, '2025-05-13 07:26:45', '2025-05-13 07:27:18', 0, 0, NULL, 0.00, 0.00),
(42, 'Leonel Ramos', NULL, NULL, 'PRUEBA #16', 1, NULL, '\"[{\\\"producto_id\\\":\\\"96\\\",\\\"cantidad\\\":\\\"1\\\",\\\"precio\\\":1,\\\"subtotal\\\":1}]\"', 1.00, 100.40, 'Barra', '\"[{\\\"metodo\\\":\\\"divisas\\\",\\\"monto\\\":\\\"2.00\\\",\\\"referencia\\\":\\\"hola\\\",\\\"cuenta\\\":null,\\\"banco\\\":null,\\\"autorizado_por\\\":null}]\"', '2025-05-13 07:29:00', NULL, '2025-05-13 07:29:59', '2025-05-13 07:32:55', 0, 0, NULL, 0.00, 0.00),
(43, 'Leonel Ramos', NULL, NULL, 'PRUEBA #17', 1, NULL, '\"[{\\\"producto_id\\\":\\\"96\\\",\\\"cantidad\\\":\\\"1\\\",\\\"precio\\\":1,\\\"subtotal\\\":1}]\"', 1.00, 1.00, 'Barra', '\"[{\\\"metodo\\\":\\\"divisas\\\",\\\"monto\\\":\\\"2.00\\\",\\\"referencia\\\":null,\\\"cuenta\\\":\\\"seleccionar_cuenta\\\",\\\"banco\\\":\\\"venezuela\\\",\\\"autorizado_por\\\":null}]\"', '2025-05-13 07:57:00', NULL, '2025-05-13 07:58:06', '2025-05-13 07:58:37', 0, 0, NULL, 0.00, 0.00),
(44, 'Leonel Ramos', NULL, NULL, 'PRUEBA #18', 1, NULL, '\"[{\\\"producto_id\\\":\\\"100\\\",\\\"cantidad\\\":\\\"1\\\",\\\"precio\\\":3,\\\"subtotal\\\":3}]\"', 3.00, 1.00, 'Barra', '\"[{\\\"metodo\\\":\\\"divisas\\\",\\\"monto\\\":\\\"5.00\\\",\\\"referencia\\\":null,\\\"cuenta\\\":null,\\\"banco\\\":null,\\\"autorizado_por\\\":null}]\"', '2025-05-13 17:07:00', NULL, '2025-05-13 17:08:11', '2025-05-13 18:18:00', 0, 0, NULL, 0.00, 0.00),
(45, 'Leonel Ramos', NULL, NULL, 'PRUEBA #19', 1, NULL, '\"[{\\\"producto_id\\\":\\\"100\\\",\\\"cantidad\\\":\\\"1\\\",\\\"precio\\\":3,\\\"subtotal\\\":3}]\"', 3.00, 1.00, 'Barra', '\"[{\\\"metodo\\\":\\\"divisas\\\",\\\"monto\\\":\\\"5.00\\\",\\\"referencia\\\":null,\\\"cuenta\\\":\\\"seleccionar_cuenta\\\",\\\"banco\\\":\\\"venezuela\\\",\\\"autorizado_por\\\":null}]\"', '2025-05-13 18:18:00', NULL, '2025-05-13 18:19:06', '2025-05-13 18:19:32', 0, 0, NULL, 0.00, 0.00),
(46, 'Leonel Ramos', NULL, NULL, 'PRUEBA #20', 1, NULL, '\"[{\\\"producto_id\\\":\\\"100\\\",\\\"cantidad\\\":\\\"1\\\",\\\"precio\\\":3,\\\"subtotal\\\":3}]\"', 3.00, 1.00, 'Barra', '\"[{\\\"metodo\\\":\\\"divisas\\\",\\\"monto\\\":\\\"5.00\\\",\\\"referencia\\\":null,\\\"cuenta\\\":\\\"seleccionar_cuenta\\\",\\\"banco\\\":\\\"venezuela\\\",\\\"autorizado_por\\\":null}]\"', '2025-05-13 18:37:00', NULL, '2025-05-13 18:37:36', '2025-05-13 18:37:48', 0, 0, NULL, 0.00, 0.00),
(47, 'Leonel Ramos', NULL, NULL, 'PRUEBA #21', 1, NULL, '\"[{\\\"producto_id\\\":\\\"100\\\",\\\"cantidad\\\":\\\"1\\\",\\\"precio\\\":3,\\\"subtotal\\\":3}]\"', 3.00, 117.00, 'Barra', '\"[{\\\"metodo\\\":\\\"divisas\\\",\\\"monto\\\":\\\"5.00\\\",\\\"referencia\\\":null,\\\"cuenta\\\":null,\\\"banco\\\":null,\\\"autorizado_por\\\":null}]\"', '2025-05-13 18:47:00', '2025-05-13 23:47:12', '2025-05-13 18:48:24', '2025-05-13 23:47:12', 1, 0, NULL, 0.00, 2.00),
(48, 'Leonel Ramos', NULL, NULL, 'PRUEBA #22', 1, NULL, '\"[{\\\"producto_id\\\":\\\"100\\\",\\\"cantidad\\\":\\\"1\\\",\\\"precio\\\":3,\\\"subtotal\\\":3}]\"', 3.00, 1.00, 'Barra', '\"[{\\\"metodo\\\":\\\"divisas\\\",\\\"monto\\\":\\\"5.00\\\",\\\"referencia\\\":null,\\\"cuenta\\\":\\\"seleccionar_cuenta\\\",\\\"banco\\\":\\\"venezuela\\\",\\\"autorizado_por\\\":null}]\"', '2025-05-13 19:41:00', NULL, '2025-05-13 19:41:44', '2025-05-13 19:42:11', 0, 0, NULL, 0.00, 0.00),
(49, 'Leonel Ramos', NULL, NULL, 'PRUEBA #23', 1, NULL, '\"[{\\\"producto_id\\\":\\\"100\\\",\\\"cantidad\\\":\\\"1\\\",\\\"precio\\\":3,\\\"subtotal\\\":3}]\"', 3.00, 1.00, 'Barra', '\"[{\\\"metodo\\\":\\\"divisas\\\",\\\"monto\\\":\\\"5.00\\\",\\\"referencia\\\":null,\\\"cuenta\\\":\\\"seleccionar_cuenta\\\",\\\"banco\\\":\\\"venezuela\\\",\\\"autorizado_por\\\":null}]\"', '2025-05-13 19:44:00', NULL, '2025-05-13 19:45:06', '2025-05-13 19:45:26', 0, 0, NULL, 0.00, 0.00),
(50, 'Leonel Ramos', NULL, NULL, 'PRUEBA #', 1, NULL, '\"[{\\\"producto_id\\\":\\\"100\\\",\\\"cantidad\\\":\\\"1\\\",\\\"precio\\\":3,\\\"subtotal\\\":3}]\"', 3.00, 1.00, 'Barra', '\"[{\\\"metodo\\\":\\\"divisas\\\",\\\"monto\\\":\\\"5.00\\\",\\\"referencia\\\":null,\\\"cuenta\\\":\\\"seleccionar_cuenta\\\",\\\"banco\\\":\\\"venezuela\\\",\\\"autorizado_por\\\":null}]\"', '2025-05-13 19:50:00', NULL, '2025-05-13 19:50:28', '2025-05-13 19:50:48', 0, 0, NULL, 0.00, 0.00),
(51, 'Leonel Ramos', NULL, NULL, 'PRUEBA #24', 1, NULL, '\"[{\\\"producto_id\\\":\\\"100\\\",\\\"cantidad\\\":\\\"1\\\",\\\"precio\\\":3,\\\"subtotal\\\":3}]\"', 3.00, 1.00, 'Barra', '\"[{\\\"metodo\\\":\\\"divisas\\\",\\\"monto\\\":\\\"5.00\\\",\\\"referencia\\\":null,\\\"cuenta\\\":\\\"seleccionar_cuenta\\\",\\\"banco\\\":\\\"venezuela\\\",\\\"autorizado_por\\\":null}]\"', '2025-05-13 20:05:00', NULL, '2025-05-13 20:06:06', '2025-05-13 20:06:17', 0, 0, NULL, 0.00, 0.00),
(52, 'Leonel Ramos', NULL, NULL, 'PRUEBA #25', 1, NULL, '\"[{\\\"producto_id\\\":\\\"100\\\",\\\"cantidad\\\":\\\"1\\\",\\\"precio\\\":3,\\\"subtotal\\\":3}]\"', 3.00, 1.00, 'Barra', '\"[{\\\"metodo\\\":\\\"divisas\\\",\\\"monto\\\":\\\"5.00\\\",\\\"referencia\\\":null,\\\"cuenta\\\":\\\"seleccionar_cuenta\\\",\\\"banco\\\":\\\"venezuela\\\",\\\"autorizado_por\\\":null}]\"', '2025-05-13 20:20:00', NULL, '2025-05-13 20:20:38', '2025-05-13 20:32:30', 0, 0, NULL, 0.00, 0.00),
(53, 'Leonel Ramos', NULL, NULL, 'PRUEBA #26', 1, NULL, '\"[{\\\"producto_id\\\":\\\"100\\\",\\\"cantidad\\\":\\\"1\\\",\\\"precio\\\":3,\\\"subtotal\\\":3}]\"', 3.00, 1.00, 'Barra', '\"[{\\\"metodo\\\":\\\"divisas\\\",\\\"monto\\\":\\\"5.00\\\",\\\"referencia\\\":null,\\\"cuenta\\\":\\\"seleccionar_cuenta\\\",\\\"banco\\\":\\\"venezuela\\\",\\\"autorizado_por\\\":null}]\"', '2025-05-13 23:24:00', NULL, '2025-05-13 23:24:41', '2025-05-13 23:27:13', 0, 0, NULL, 0.00, 0.00),
(54, 'Leonel Ramos', NULL, NULL, 'PRUEBA #27', 1, NULL, '\"[{\\\"producto_id\\\":\\\"100\\\",\\\"cantidad\\\":\\\"1\\\",\\\"precio\\\":3,\\\"subtotal\\\":3}]\"', 3.00, 1.00, 'Barra', '\"[{\\\"metodo\\\":\\\"divisas\\\",\\\"monto\\\":\\\"5.00\\\",\\\"referencia\\\":null,\\\"cuenta\\\":null,\\\"banco\\\":null,\\\"autorizado_por\\\":null}]\"', '2025-05-13 23:33:00', '2025-05-13 23:36:11', '2025-05-13 23:33:14', '2025-05-13 23:36:11', 1, 0, NULL, 0.00, 2.00),
(55, 'Leonel Ramos', NULL, NULL, 'PRUEBA #30', 1, NULL, '\"[{\\\"producto_id\\\":\\\"100\\\",\\\"cantidad\\\":\\\"1\\\",\\\"precio\\\":3,\\\"subtotal\\\":3}]\"', 3.00, 117.00, 'Barra', '\"[{\\\"metodo\\\":\\\"divisas\\\",\\\"monto\\\":\\\"3.00\\\",\\\"referencia\\\":null,\\\"cuenta\\\":null,\\\"banco\\\":null,\\\"autorizado_por\\\":null}]\"', '2025-05-14 00:31:00', NULL, '2025-05-14 00:31:30', '2025-05-14 00:47:34', 0, 0, NULL, 0.00, 0.00),
(57, 'Leonel Ramos', NULL, NULL, 'PRUEBA #32', 1, NULL, '\"[{\\\"producto_id\\\":\\\"96\\\",\\\"cantidad\\\":\\\"1\\\",\\\"precio\\\":1,\\\"subtotal\\\":1}]\"', 1.00, 106.50, 'Barra', '\"[{\\\"metodo\\\":\\\"pago_movil\\\",\\\"monto\\\":\\\"2000.00\\\",\\\"referencia\\\":\\\"123456\\\",\\\"cuenta\\\":null,\\\"banco\\\":\\\"venezuela\\\",\\\"autorizado_por\\\":null},{\\\"metodo\\\":\\\"zelle\\\",\\\"monto\\\":\\\"2.50\\\",\\\"referencia\\\":\\\"hola\\\",\\\"cuenta\\\":null,\\\"banco\\\":\\\"venezuela\\\",\\\"autorizado_por\\\":null}]\"', '2025-05-14 04:30:00', '2025-05-14 04:40:01', '2025-05-14 04:30:51', '2025-05-14 04:40:01', 1, 0, NULL, 0.00, 2001.50),
(58, 'Leonel Ramos', NULL, NULL, 'Luis', 1, NULL, '\"[{\\\"producto_id\\\":\\\"59\\\",\\\"cantidad\\\":\\\"5\\\",\\\"precio\\\":10,\\\"subtotal\\\":50}]\"', 50.00, 106.50, 'Barra', '\"[{\\\"metodo\\\":\\\"divisas\\\",\\\"monto\\\":\\\"100.00\\\",\\\"referencia\\\":null,\\\"cuenta\\\":\\\"seleccionar_cuenta\\\",\\\"banco\\\":\\\"venezuela\\\",\\\"autorizado_por\\\":null},{\\\"metodo\\\":\\\"punto_venta\\\",\\\"monto\\\":\\\"2662.50\\\",\\\"referencia\\\":\\\"123456\\\",\\\"cuenta\\\":\\\"seleccionar_cuenta\\\",\\\"banco\\\":\\\"bancamiga\\\",\\\"autorizado_por\\\":null},{\\\"metodo\\\":\\\"propina\\\",\\\"monto\\\":\\\"20.00\\\",\\\"referencia\\\":null,\\\"cuenta\\\":\\\"seleccionar_cuenta\\\",\\\"banco\\\":\\\"venezuela\\\",\\\"autorizado_por\\\":null}]\"', '2025-05-14 14:01:00', '2025-05-14 14:06:02', '2025-05-14 14:01:37', '2025-05-14 14:06:02', 1, 0, NULL, 0.00, 2732.50),
(59, 'Leonel Ramos', NULL, NULL, 'PRUEBA #35', 1, NULL, '\"[{\\\"producto_id\\\":\\\"66\\\",\\\"cantidad\\\":\\\"43\\\",\\\"precio\\\":4,\\\"subtotal\\\":172}]\"', 172.00, 1.00, 'Barra', '\"[{\\\"metodo\\\":\\\"divisas\\\",\\\"monto\\\":\\\"172.00\\\",\\\"referencia\\\":null,\\\"cuenta\\\":\\\"seleccionar_cuenta\\\",\\\"banco\\\":\\\"venezuela\\\",\\\"autorizado_por\\\":null}]\"', '2025-05-14 14:50:00', '2025-05-14 18:49:23', '2025-05-14 14:51:22', '2025-05-14 18:49:23', 1, 0, NULL, 0.00, 0.00),
(61, 'Leonel Ramos', NULL, NULL, 'Prueba roles de usuario #1', 10, NULL, '\"[{\\\"producto_id\\\":\\\"96\\\",\\\"cantidad\\\":\\\"1\\\",\\\"precio\\\":1,\\\"subtotal\\\":1}]\"', 1.00, 106.70, 'Barra', '\"[{\\\"metodo\\\":\\\"divisas\\\",\\\"monto\\\":\\\"5.00\\\",\\\"referencia\\\":null,\\\"cuenta\\\":\\\"seleccionar_cuenta\\\",\\\"banco\\\":\\\"venezuela\\\",\\\"autorizado_por\\\":null}]\"', '2025-05-15 03:08:00', '2025-05-15 03:10:56', '2025-05-15 03:08:57', '2025-05-15 03:10:56', 1, 0, NULL, 0.00, 4.00),
(62, 'Leonel Ramos', 'FLAHS', NULL, 'PRUEBA CON ROLES DE USUARIOS# 1', 10, NULL, '\"[{\\\"producto_id\\\":\\\"59\\\",\\\"cantidad\\\":\\\"2\\\",\\\"precio\\\":10,\\\"subtotal\\\":20}]\"', 20.00, 117.00, 'Barra', '\"[{\\\"metodo\\\":\\\"divisas\\\",\\\"monto\\\":\\\"10.00\\\",\\\"referencia\\\":null,\\\"cuenta\\\":null,\\\"banco\\\":\\\"venezuela\\\",\\\"autorizado_por\\\":null},{\\\"metodo\\\":\\\"pago_movil\\\",\\\"monto\\\":\\\"1170.00\\\",\\\"referencia\\\":\\\"123456\\\",\\\"cuenta\\\":\\\"esmerley_venezuela\\\",\\\"banco\\\":\\\"venezuela\\\",\\\"autorizado_por\\\":null}]\"', '2025-05-15 04:05:00', NULL, '2025-05-15 04:06:00', '2025-05-15 04:07:56', 0, 0, NULL, 0.00, 1160.00),
(63, 'Leonel Ramos', 'SuperMan', NULL, 'Prueba de roles #3', 10, 'María Eugenia Girott', '\"[{\\\"producto_id\\\":\\\"72\\\",\\\"cantidad\\\":\\\"1\\\",\\\"precio\\\":4,\\\"subtotal\\\":4},{\\\"producto_id\\\":\\\"1\\\",\\\"cantidad\\\":\\\"1\\\",\\\"precio\\\":35,\\\"subtotal\\\":35}]\"', 39.00, 1.00, 'Barra', '\"[]\"', '2025-05-15 04:08:00', NULL, '2025-05-15 04:09:40', '2025-05-15 04:09:40', 0, 0, NULL, 0.00, 0.00),
(64, 'Leonel Ramos', 'Acuaman', NULL, 'Prueba de roles #4', 10, NULL, '\"[{\\\"producto_id\\\":\\\"96\\\",\\\"cantidad\\\":\\\"1\\\",\\\"precio\\\":1,\\\"subtotal\\\":1}]\"', 1.00, 117.00, 'Cocina', '\"[{\\\"metodo\\\":\\\"divisas\\\",\\\"monto\\\":\\\"5.00\\\",\\\"referencia\\\":null,\\\"cuenta\\\":\\\"seleccionar_cuenta\\\",\\\"banco\\\":\\\"venezuela\\\",\\\"autorizado_por\\\":null}]\"', '2025-05-15 04:16:00', NULL, '2025-05-15 04:16:18', '2025-05-15 04:21:36', 0, 0, NULL, 0.00, 4.00),
(65, 'Leonel Ramos', 'Thor', NULL, 'Prueba de roles #5', 10, NULL, '\"[{\\\"producto_id\\\":\\\"68\\\",\\\"cantidad\\\":\\\"1\\\",\\\"precio\\\":4,\\\"subtotal\\\":4},{\\\"producto_id\\\":\\\"38\\\",\\\"cantidad\\\":\\\"1\\\",\\\"precio\\\":14,\\\"subtotal\\\":14},{\\\"producto_id\\\":\\\"59\\\",\\\"cantidad\\\":\\\"1\\\",\\\"precio\\\":10,\\\"subtotal\\\":10},{\\\"producto_id\\\":\\\"23\\\",\\\"cantidad\\\":\\\"1\\\",\\\"precio\\\":10,\\\"subtotal\\\":10}]\"', 38.00, 117.00, 'Barra', '\"[{\\\"metodo\\\":\\\"divisas\\\",\\\"monto\\\":\\\"30.00\\\",\\\"referencia\\\":null,\\\"cuenta\\\":null,\\\"banco\\\":\\\"venezuela\\\",\\\"autorizado_por\\\":null},{\\\"metodo\\\":\\\"punto_venta\\\",\\\"monto\\\":\\\"936.00\\\",\\\"referencia\\\":\\\"123456\\\",\\\"cuenta\\\":null,\\\"banco\\\":\\\"bancamiga\\\",\\\"autorizado_por\\\":null},{\\\"metodo\\\":\\\"propina_divisas\\\",\\\"monto\\\":\\\"9.00\\\",\\\"referencia\\\":null,\\\"cuenta\\\":null,\\\"banco\\\":\\\"venezuela\\\",\\\"autorizado_por\\\":null}]\"', '2025-05-15 04:40:00', NULL, '2025-05-15 04:41:54', '2025-05-15 05:52:31', 0, 0, NULL, 0.00, 937.00),
(66, 'Leonel Ramos', 'Hulk', NULL, 'Prueba de roles #7', 10, NULL, '\"[{\\\"producto_id\\\":\\\"100\\\",\\\"cantidad\\\":\\\"1\\\",\\\"precio\\\":3,\\\"subtotal\\\":3}]\"', 3.00, 117.00, 'Barra', '\"[{\\\"metodo\\\":\\\"divisas\\\",\\\"monto\\\":\\\"10.00\\\",\\\"referencia\\\":null,\\\"cuenta\\\":\\\"seleccionar_cuenta\\\",\\\"banco\\\":\\\"venezuela\\\",\\\"autorizado_por\\\":null},{\\\"metodo\\\":\\\"propina_divisas\\\",\\\"monto\\\":\\\"5.00\\\",\\\"referencia\\\":null,\\\"cuenta\\\":\\\"seleccionar_cuenta\\\",\\\"banco\\\":\\\"venezuela\\\",\\\"autorizado_por\\\":null}]\"', '2025-05-15 05:53:00', '2025-05-15 05:58:40', '2025-05-15 05:54:09', '2025-05-15 05:58:40', 1, 0, NULL, 0.00, 12.00),
(67, 'Leonel Ramos', 'Calipso', NULL, 'Prueba de Comander #1', 1, NULL, '[\r\n    {\"producto_id\":\"254\",\"cantidad\":\"1\",\"precio\":14,\"subtotal\":14,\"area_id\":1},\r\n    {\"producto_id\":\"275\",\"cantidad\":\"1\",\"precio\":10,\"subtotal\":10,\"area_id\":4},\r\n    {\"producto_id\":\"352\",\"cantidad\":\"2\",\"precio\":2,\"subtotal\":4,\"area_id\":3}\r\n]', 28.00, 117.00, 'Barra', '\"[{\\\"metodo\\\":\\\"divisas\\\",\\\"monto\\\":\\\"28.00\\\",\\\"referencia\\\":null,\\\"cuenta\\\":\\\"seleccionar_cuenta\\\",\\\"banco\\\":\\\"venezuela\\\",\\\"autorizado_por\\\":null}]\"', '2025-05-16 03:07:00', NULL, '2025-05-16 03:10:09', '2025-05-16 03:48:46', 0, 0, NULL, 0.00, 0.00),
(68, 'Leonel Ramos', 'Thor', NULL, 'Prueba de Comandera #2', 1, NULL, '\"[{\\\"producto_id\\\":\\\"303\\\",\\\"cantidad\\\":\\\"1\\\",\\\"precio\\\":2,\\\"subtotal\\\":2}]\"', 2.00, 1.00, 'Barra', '\"[{\\\"metodo\\\":\\\"divisas\\\",\\\"monto\\\":\\\"2.00\\\",\\\"referencia\\\":null,\\\"cuenta\\\":\\\"seleccionar_cuenta\\\",\\\"banco\\\":\\\"venezuela\\\",\\\"autorizado_por\\\":null}]\"', '2025-05-16 05:39:00', NULL, '2025-05-16 05:39:31', '2025-05-16 05:39:46', 0, 0, NULL, 0.00, 0.00),
(69, 'Leonel Ramos', 'Batman', NULL, 'Prueba de Comandera #3', 1, NULL, '\"[{\\\"producto_id\\\":\\\"303\\\",\\\"cantidad\\\":\\\"1\\\",\\\"precio\\\":2,\\\"subtotal\\\":2}]\"', 2.00, 117.00, 'Barra', '\"[{\\\"metodo\\\":\\\"divisas\\\",\\\"monto\\\":\\\"2.00\\\",\\\"referencia\\\":null,\\\"cuenta\\\":\\\"seleccionar_cuenta\\\",\\\"banco\\\":\\\"venezuela\\\",\\\"autorizado_por\\\":null}]\"', '2025-05-16 06:05:00', NULL, '2025-05-16 06:06:13', '2025-05-16 06:06:32', 0, 0, NULL, 0.00, 0.00),
(70, 'Leonel Ramos', 'Thor', NULL, 'Prueba de Comandera #4', 1, NULL, '\"[{\\\"producto_id\\\":\\\"297\\\",\\\"cantidad\\\":\\\"1\\\",\\\"precio\\\":2,\\\"subtotal\\\":2}]\"', 2.00, 1.00, 'Barra', '\"[{\\\"metodo\\\":\\\"divisas\\\",\\\"monto\\\":\\\"2.00\\\",\\\"referencia\\\":null,\\\"cuenta\\\":\\\"seleccionar_cuenta\\\",\\\"banco\\\":\\\"venezuela\\\",\\\"autorizado_por\\\":null}]\"', '2025-05-16 06:11:00', NULL, '2025-05-16 06:12:05', '2025-05-16 06:12:22', 0, 0, NULL, 0.00, 0.00),
(71, 'Leonel', 'Thor', NULL, 'Prueba de Comandera #5', 1, NULL, '\"[{\\\"producto_id\\\":\\\"303\\\",\\\"cantidad\\\":\\\"1\\\",\\\"precio\\\":2,\\\"subtotal\\\":2}]\"', 2.00, 117.00, 'Barra', '\"[{\\\"metodo\\\":\\\"divisas\\\",\\\"monto\\\":\\\"2.00\\\",\\\"referencia\\\":null,\\\"cuenta\\\":\\\"seleccionar_cuenta\\\",\\\"banco\\\":\\\"venezuela\\\",\\\"autorizado_por\\\":null}]\"', '2025-05-16 06:18:00', NULL, '2025-05-16 06:18:34', '2025-05-16 06:18:48', 0, 0, NULL, 0.00, 0.00),
(72, 'Leonel', 'Thor', NULL, 'Prueba de Comandera #6', 1, NULL, '\"[{\\\"producto_id\\\":\\\"297\\\",\\\"cantidad\\\":\\\"1\\\",\\\"precio\\\":2,\\\"subtotal\\\":2}]\"', 2.00, 100.00, 'Barra', '\"[{\\\"metodo\\\":\\\"divisas\\\",\\\"monto\\\":\\\"2.00\\\",\\\"referencia\\\":null,\\\"cuenta\\\":\\\"seleccionar_cuenta\\\",\\\"banco\\\":\\\"venezuela\\\",\\\"autorizado_por\\\":null}]\"', '2025-05-16 06:39:00', NULL, '2025-05-16 06:39:18', '2025-05-16 06:39:44', 0, 0, NULL, 0.00, 0.00),
(73, 'Leonel Ramos', 'FLAHS', NULL, 'Prueba de Comandera #7', 1, NULL, '\"[{\\\"producto_id\\\":\\\"300\\\",\\\"cantidad\\\":\\\"1\\\",\\\"precio\\\":2,\\\"subtotal\\\":2}]\"', 2.00, 117.00, 'Barra', '\"[{\\\"metodo\\\":\\\"pago_movil\\\",\\\"monto\\\":\\\"234.00\\\",\\\"referencia\\\":\\\"123456\\\",\\\"cuenta\\\":\\\"esmerley_banesco\\\",\\\"banco\\\":\\\"venezuela\\\",\\\"autorizado_por\\\":null}]\"', '2025-05-16 06:43:00', NULL, '2025-05-16 06:43:26', '2025-05-16 06:43:44', 0, 0, NULL, 0.00, 232.00),
(74, 'Leonel Ramos', 'Thor', NULL, 'Prueba de Comandera #8', 1, NULL, '[{\"producto_id\":\"297\",\"cantidad\":\"1\",\"precio\":2,\"subtotal\":2,\"area_id\":1}]', 2.00, 1.00, 'Barra', '\"[]\"', '2025-05-16 07:44:00', NULL, '2025-05-16 07:44:37', '2025-05-16 07:44:37', 0, 0, NULL, 0.00, 0.00),
(75, 'Leonel', 'FLAHS', NULL, 'Prueba de Comander #1', 1, NULL, '\"[{\\\"producto_id\\\":\\\"303\\\",\\\"cantidad\\\":\\\"1\\\",\\\"precio\\\":2,\\\"subtotal\\\":2,\\\"area_id\\\":3}]\"', 2.00, 1.00, 'Barra', '\"[]\"', '2025-05-16 08:07:00', NULL, '2025-05-16 08:07:15', '2025-05-16 08:07:15', 0, 0, NULL, 0.00, 0.00),
(76, 'Leonel', 'Batman', NULL, 'Prueba de Comander #1', 1, NULL, '\"[{\\\"producto_id\\\":\\\"303\\\",\\\"cantidad\\\":\\\"1\\\",\\\"precio\\\":2,\\\"subtotal\\\":2}]\"', 2.00, 1.00, 'Carne en Vara', '\"[{\\\"metodo\\\":\\\"divisas\\\",\\\"monto\\\":\\\"2.00\\\",\\\"referencia\\\":null,\\\"cuenta\\\":\\\"seleccionar_cuenta\\\",\\\"banco\\\":\\\"venezuela\\\",\\\"autorizado_por\\\":null}]\"', '2025-05-16 08:20:00', NULL, '2025-05-16 08:21:15', '2025-05-16 08:21:38', 0, 0, NULL, 0.00, 0.00),
(77, 'Leonel Ramos', 'Calipso', NULL, 'Prueba de Comander #1', 1, NULL, '\"[{\\\"producto_id\\\":\\\"303\\\",\\\"cantidad\\\":\\\"1\\\",\\\"precio\\\":2,\\\"subtotal\\\":2}]\"', 2.00, 1.00, 'Barra', '\"[{\\\"metodo\\\":\\\"divisas\\\",\\\"monto\\\":\\\"2.00\\\",\\\"referencia\\\":null,\\\"cuenta\\\":\\\"seleccionar_cuenta\\\",\\\"banco\\\":\\\"venezuela\\\",\\\"autorizado_por\\\":null}]\"', '2025-05-16 08:35:00', NULL, '2025-05-16 08:35:43', '2025-05-16 08:36:19', 0, 0, NULL, 0.00, 0.00),
(78, 'Leonel Ramos', 'Thor', NULL, 'Prueba de Comandera #4', 1, NULL, '\"[{\\\"producto_id\\\":\\\"303\\\",\\\"cantidad\\\":\\\"1\\\",\\\"precio\\\":2,\\\"subtotal\\\":2,\\\"area_id\\\":\\\"3\\\"}]\"', 2.00, 1.00, 'Barra', '\"[]\"', '2025-05-16 08:37:00', NULL, '2025-05-16 08:41:32', '2025-05-16 08:41:32', 0, 0, NULL, 0.00, 0.00),
(79, 'Leonel', 'Thor', NULL, 'Prueba de Comandera #4', 1, NULL, '\"[{\\\"producto_id\\\":\\\"303\\\",\\\"cantidad\\\":\\\"1\\\",\\\"precio\\\":2,\\\"subtotal\\\":2,\\\"area_id\\\":\\\"3\\\"}]\"', 2.00, 1.00, 'Barra', '\"[]\"', '2025-05-16 08:41:00', NULL, '2025-05-16 08:41:43', '2025-05-16 08:41:43', 0, 0, NULL, 0.00, 0.00),
(80, 'Leonel Ramos', 'Batman', NULL, 'Prueba de Comandera #4', 1, NULL, '\"[{\\\"producto_id\\\":\\\"303\\\",\\\"cantidad\\\":\\\"1\\\",\\\"precio\\\":2,\\\"subtotal\\\":2,\\\"area_id\\\":\\\"1\\\"}]\"', 2.00, 1.00, 'Barra', '\"[]\"', '2025-05-16 08:58:00', NULL, '2025-05-16 08:59:04', '2025-05-16 08:59:04', 0, 0, NULL, 0.00, 0.00),
(81, 'Leonel Ramos', 'Batman', NULL, 'Prueba de Comander #1', 1, NULL, '\"[{\\\"producto_id\\\":\\\"217\\\",\\\"cantidad\\\":\\\"1\\\",\\\"precio\\\":18,\\\"subtotal\\\":18},{\\\"producto_id\\\":\\\"299\\\",\\\"cantidad\\\":\\\"1\\\",\\\"precio\\\":2,\\\"subtotal\\\":2}]\"', 20.00, 1.00, 'Barra', '\"[{\\\"metodo\\\":\\\"divisas\\\",\\\"monto\\\":\\\"10.00\\\",\\\"referencia\\\":null,\\\"cuenta\\\":null,\\\"banco\\\":\\\"venezuela\\\",\\\"autorizado_por\\\":null}]\"', '2025-05-16 09:40:00', NULL, '2025-05-16 09:41:14', '2025-05-16 23:31:09', 0, 0, NULL, 0.00, 0.00),
(82, 'Leonel Ramos', 'Thor', NULL, 'Prueba de Comandera #4', 1, NULL, '\"[{\\\"producto_id\\\":\\\"303\\\",\\\"cantidad\\\":\\\"1\\\",\\\"precio\\\":2,\\\"subtotal\\\":2,\\\"area_id\\\":\\\"3\\\"}]\"', 2.00, 1.00, 'Barra', '\"[]\"', '2025-05-16 23:31:00', NULL, '2025-05-16 23:31:42', '2025-05-16 23:31:42', 0, 0, NULL, 0.00, 0.00),
(83, 'Leonel Ramos', 'Calipso', NULL, 'Prueba de roles #7', 1, NULL, '\"[{\\\"producto_id\\\":\\\"337\\\",\\\"cantidad\\\":\\\"99\\\",\\\"precio\\\":2.5,\\\"subtotal\\\":247.5},{\\\"producto_id\\\":\\\"360\\\",\\\"cantidad\\\":\\\"1\\\",\\\"precio\\\":5,\\\"subtotal\\\":5}]\"', 252.50, 1.00, 'Barra', '\"[{\\\"metodo\\\":\\\"divisas\\\",\\\"monto\\\":\\\"2.50\\\",\\\"referencia\\\":null,\\\"cuenta\\\":null,\\\"banco\\\":\\\"venezuela\\\",\\\"autorizado_por\\\":null}]\"', '2025-05-16 23:53:00', NULL, '2025-05-16 23:53:27', '2025-05-17 00:21:32', 0, 0, NULL, 0.00, 0.00),
(84, 'Leonel', 'Thor', NULL, 'Prueba de Comander #1', 1, NULL, '\"[{\\\"producto_id\\\":\\\"225\\\",\\\"cantidad\\\":\\\"1\\\",\\\"precio\\\":8,\\\"subtotal\\\":8},{\\\"producto_id\\\":\\\"303\\\",\\\"cantidad\\\":\\\"1\\\",\\\"precio\\\":2,\\\"subtotal\\\":2}]\"', 10.00, 1.00, 'Barra', '\"[{\\\"metodo\\\":\\\"divisas\\\",\\\"monto\\\":\\\"8.00\\\",\\\"referencia\\\":null,\\\"cuenta\\\":\\\"seleccionar_cuenta\\\",\\\"banco\\\":\\\"venezuela\\\",\\\"autorizado_por\\\":null}]\"', '2025-05-17 00:25:00', NULL, '2025-05-17 00:25:30', '2025-05-17 00:26:50', 0, 0, NULL, 0.00, 0.00),
(85, 'Leonel', 'Batman', NULL, 'Prueba de Comander #1', 1, NULL, '\"[{\\\"producto_id\\\":\\\"278\\\",\\\"cantidad\\\":\\\"8\\\",\\\"precio\\\":1,\\\"subtotal\\\":8}]\"', 8.00, 1.00, 'Carne en Vara', '\"[{\\\"metodo\\\":\\\"divisas\\\",\\\"monto\\\":\\\"8.00\\\",\\\"referencia\\\":null,\\\"cuenta\\\":\\\"seleccionar_cuenta\\\",\\\"banco\\\":\\\"venezuela\\\",\\\"autorizado_por\\\":null}]\"', '2025-05-17 00:29:00', NULL, '2025-05-17 00:29:21', '2025-05-17 00:29:54', 0, 0, NULL, 0.00, 0.00),
(86, 'Leonel Ramos', 'fdgdf', NULL, 'Prueba de Comandera #4', 1, NULL, '\"[{\\\"producto_id\\\":\\\"360\\\",\\\"cantidad\\\":\\\"2\\\",\\\"precio\\\":5,\\\"subtotal\\\":10}]\"', 10.00, 1.00, 'Barra', '\"[{\\\"metodo\\\":\\\"divisas\\\",\\\"monto\\\":\\\"5.00\\\",\\\"referencia\\\":null,\\\"cuenta\\\":\\\"seleccionar_cuenta\\\",\\\"banco\\\":\\\"venezuela\\\",\\\"autorizado_por\\\":null}]\"', '2025-05-17 00:40:00', NULL, '2025-05-17 00:40:17', '2025-05-17 00:47:12', 0, 0, NULL, 0.00, 0.00),
(87, 'Leonel Ramos', 'Calipso', NULL, 'Prueba de Comandera #4', 1, NULL, '\"[{\\\"producto_id\\\":\\\"303\\\",\\\"cantidad\\\":\\\"1\\\",\\\"precio\\\":2,\\\"subtotal\\\":2,\\\"area_id\\\":\\\"3\\\"}]\"', 2.00, 1.00, 'Barra', '\"[]\"', '2025-05-17 00:52:00', NULL, '2025-05-17 00:52:46', '2025-05-17 00:52:46', 0, 0, NULL, 0.00, 0.00),
(88, 'Karely', 'Thor', NULL, 'Prueba de Comandera #4', 1, NULL, '\"[{\\\"producto_id\\\":\\\"303\\\",\\\"cantidad\\\":\\\"1\\\",\\\"precio\\\":2,\\\"subtotal\\\":2,\\\"area_id\\\":\\\"3\\\"}]\"', 2.00, 1.00, 'Barra', '\"[]\"', '2025-05-17 00:53:00', NULL, '2025-05-17 00:53:25', '2025-05-17 00:53:25', 0, 0, NULL, 0.00, 0.00),
(89, 'Leonel', 'Batman', NULL, 'Prueba de Comander #1', 1, NULL, '\"[{\\\"producto_id\\\":\\\"304\\\",\\\"cantidad\\\":\\\"1\\\",\\\"precio\\\":2,\\\"subtotal\\\":2,\\\"area_id\\\":\\\"3\\\"}]\"', 2.00, 1.00, 'Barra', '\"[]\"', '2025-05-17 01:06:00', NULL, '2025-05-17 01:06:33', '2025-05-17 01:06:33', 0, 0, NULL, 0.00, 0.00),
(90, 'Leonel', 'Calipso', NULL, 'Prueba de roles #7', 1, NULL, '\"[{\\\"producto_id\\\":\\\"303\\\",\\\"cantidad\\\":\\\"1\\\",\\\"precio\\\":2,\\\"subtotal\\\":2},{\\\"producto_id\\\":\\\"298\\\",\\\"cantidad\\\":\\\"1\\\",\\\"precio\\\":2,\\\"subtotal\\\":2}]\"', 4.00, 1.00, 'Barra', '\"[{\\\"metodo\\\":\\\"divisas\\\",\\\"monto\\\":\\\"2.00\\\",\\\"referencia\\\":null,\\\"cuenta\\\":\\\"seleccionar_cuenta\\\",\\\"banco\\\":\\\"venezuela\\\",\\\"autorizado_por\\\":null}]\"', '2025-05-17 01:16:00', NULL, '2025-05-17 01:16:59', '2025-05-17 01:18:04', 0, 0, NULL, 0.00, 0.00),
(91, 'Leonel', 'Batman', NULL, 'Prueba de Comander #1', 1, NULL, '\"[{\\\"producto_id\\\":\\\"303\\\",\\\"cantidad\\\":\\\"1\\\",\\\"precio\\\":2,\\\"subtotal\\\":2}]\"', 2.00, 1.00, 'Cocina', '\"[{\\\"metodo\\\":\\\"divisas\\\",\\\"monto\\\":\\\"2.00\\\",\\\"referencia\\\":null,\\\"cuenta\\\":\\\"seleccionar_cuenta\\\",\\\"banco\\\":\\\"venezuela\\\",\\\"autorizado_por\\\":null}]\"', '2025-05-17 01:19:00', NULL, '2025-05-17 01:19:46', '2025-05-17 01:23:06', 0, 0, NULL, 0.00, 0.00),
(92, 'Leonel Ramos', 'Calipso', NULL, 'Prueba de roles #7', 1, NULL, '\"[{\\\"producto_id\\\":\\\"303\\\",\\\"cantidad\\\":\\\"14\\\",\\\"precio\\\":2,\\\"subtotal\\\":28},{\\\"producto_id\\\":\\\"303\\\",\\\"cantidad\\\":\\\"1\\\",\\\"precio\\\":2,\\\"subtotal\\\":2}]\"', 30.00, 1.00, 'Barra', '\"[{\\\"metodo\\\":\\\"divisas\\\",\\\"monto\\\":\\\"28.00\\\",\\\"referencia\\\":null,\\\"cuenta\\\":\\\"seleccionar_cuenta\\\",\\\"banco\\\":\\\"venezuela\\\",\\\"autorizado_por\\\":null}]\"', '2025-05-17 01:23:00', NULL, '2025-05-17 01:23:26', '2025-05-17 02:04:59', 0, 0, NULL, 0.00, 0.00),
(93, 'Leonel Ramos', 'Thor', NULL, 'Prueba de Comandera #4', 1, NULL, '\"[{\\\"producto_id\\\":\\\"297\\\",\\\"cantidad\\\":\\\"1\\\",\\\"precio\\\":2,\\\"subtotal\\\":2},{\\\"producto_id\\\":\\\"297\\\",\\\"cantidad\\\":\\\"1\\\",\\\"precio\\\":2,\\\"subtotal\\\":2}]\"', 4.00, 1.00, 'Barra', '\"[{\\\"metodo\\\":\\\"divisas\\\",\\\"monto\\\":\\\"2.00\\\",\\\"referencia\\\":null,\\\"cuenta\\\":\\\"seleccionar_cuenta\\\",\\\"banco\\\":\\\"venezuela\\\",\\\"autorizado_por\\\":null}]\"', '2025-05-17 02:13:00', NULL, '2025-05-17 02:13:11', '2025-05-17 02:41:01', 0, 0, NULL, 0.00, 0.00),
(94, 'Leonel Ramos', 'Thor', NULL, 'Prueba de Comander #1', 1, NULL, '\"[{\\\"producto_id\\\":\\\"316\\\",\\\"cantidad\\\":\\\"1\\\",\\\"precio\\\":2.5,\\\"subtotal\\\":2.5}]\"', 2.50, 1.00, 'Barra', '\"[{\\\"metodo\\\":\\\"tarjeta_credito_dolares\\\",\\\"monto\\\":\\\"2.00\\\",\\\"referencia\\\":\\\"123456\\\",\\\"cuenta\\\":null,\\\"banco\\\":\\\"venezuela\\\",\\\"autorizado_por\\\":null}]\"', '2025-05-17 03:09:00', NULL, '2025-05-17 03:09:45', '2025-05-17 03:17:13', 0, 0, NULL, 0.00, 0.00),
(95, 'Leonel Ramos', 'Batman', NULL, 'Prueba de roles #3', 1, NULL, '\"[{\\\"producto_id\\\":\\\"303\\\",\\\"cantidad\\\":\\\"1\\\",\\\"precio\\\":2,\\\"subtotal\\\":2},{\\\"producto_id\\\":\\\"316\\\",\\\"cantidad\\\":\\\"1\\\",\\\"precio\\\":2.5,\\\"subtotal\\\":2.5}]\"', 4.50, 1.00, 'Barra', '\"[{\\\"metodo\\\":\\\"divisas\\\",\\\"monto\\\":\\\"2.00\\\",\\\"referencia\\\":null,\\\"cuenta\\\":\\\"seleccionar_cuenta\\\",\\\"banco\\\":\\\"venezuela\\\",\\\"autorizado_por\\\":null}]\"', '2025-05-17 03:17:00', NULL, '2025-05-17 03:18:08', '2025-05-17 03:23:26', 0, 0, NULL, 0.00, 0.00),
(96, 'Leonel Ramos', 'Thor', NULL, 'Prueba de roles #7', 1, NULL, '\"[{\\\"producto_id\\\":\\\"303\\\",\\\"cantidad\\\":\\\"1\\\",\\\"precio\\\":2,\\\"subtotal\\\":2},{\\\"producto_id\\\":\\\"255\\\",\\\"cantidad\\\":\\\"1\\\",\\\"precio\\\":16,\\\"subtotal\\\":16},{\\\"producto_id\\\":\\\"316\\\",\\\"cantidad\\\":\\\"1\\\",\\\"precio\\\":2.5,\\\"subtotal\\\":2.5},{\\\"producto_id\\\":\\\"316\\\",\\\"cantidad\\\":\\\"1\\\",\\\"precio\\\":2.5,\\\"subtotal\\\":2.5}]\"', 23.00, 1.00, 'Barra', '\"[{\\\"metodo\\\":\\\"divisas\\\",\\\"monto\\\":\\\"18.00\\\",\\\"referencia\\\":null,\\\"cuenta\\\":null,\\\"banco\\\":\\\"venezuela\\\",\\\"autorizado_por\\\":null}]\"', '2025-05-17 03:26:00', NULL, '2025-05-17 03:26:40', '2025-05-17 04:44:05', 0, 0, NULL, 0.00, 0.00),
(97, 'Leonel Ramos', 'Thor', NULL, 'Prueba de Comander #1', 1, NULL, '\"[{\\\"producto_id\\\":\\\"297\\\",\\\"cantidad\\\":\\\"1\\\",\\\"precio\\\":2,\\\"subtotal\\\":2,\\\"area_id\\\":\\\"3\\\"},{\\\"producto_id\\\":\\\"254\\\",\\\"cantidad\\\":\\\"1\\\",\\\"precio\\\":14,\\\"subtotal\\\":14,\\\"area_id\\\":\\\"2\\\"},{\\\"producto_id\\\":\\\"275\\\",\\\"cantidad\\\":\\\"4\\\",\\\"precio\\\":10,\\\"subtotal\\\":40,\\\"area_id\\\":\\\"sin-area\\\"},{\\\"producto_id\\\":\\\"297\\\",\\\"cantidad\\\":\\\"1\\\",\\\"precio\\\":2,\\\"subtotal\\\":2,\\\"area_id\\\":\\\"sin-area\\\"},{\\\"producto_id\\\":\\\"258\\\",\\\"cantidad\\\":\\\"1\\\",\\\"precio\\\":5,\\\"subtotal\\\":5,\\\"area_id\\\":\\\"sin-area\\\"}]\"', 63.00, 117.00, 'Barra', '\"[{\\\"metodo\\\":\\\"divisas\\\",\\\"monto\\\":\\\"20.00\\\",\\\"referencia\\\":null,\\\"cuenta\\\":null,\\\"banco\\\":\\\"venezuela\\\",\\\"autorizado_por\\\":null},{\\\"metodo\\\":\\\"pago_movil\\\",\\\"monto\\\":\\\"4446.00\\\",\\\"referencia\\\":\\\"123456\\\",\\\"cuenta\\\":\\\"genesis_venezuela\\\",\\\"banco\\\":\\\"venezuela\\\",\\\"autorizado_por\\\":null},{\\\"metodo\\\":\\\"divisas\\\",\\\"monto\\\":\\\"5.00\\\",\\\"referencia\\\":null,\\\"cuenta\\\":\\\"seleccionar_cuenta\\\",\\\"banco\\\":\\\"venezuela\\\",\\\"autorizado_por\\\":null}]\"', '2025-05-17 05:10:00', '2025-05-17 05:17:50', '2025-05-17 05:10:31', '2025-05-17 05:17:50', 1, 0, NULL, 0.00, 4408.00);
INSERT INTO `cuentas` (`id`, `responsable_pedido`, `barrero`, `cliente_id`, `cliente_nombre`, `usuario_id`, `cajera`, `productos`, `total_estimado`, `tasa_dia`, `estacion`, `metodos_pago`, `fecha_apertura`, `fecha_cierre`, `created_at`, `updated_at`, `pagada`, `pendiente`, `cliente_nombre_manual`, `total_pagado`, `vuelto`) VALUES
(98, 'Leonel Ramos', 'Thor', NULL, 'comandera funciona', 1, NULL, '\"[{\\\"producto_id\\\":\\\"297\\\",\\\"cantidad\\\":\\\"2\\\",\\\"precio\\\":2,\\\"subtotal\\\":4,\\\"area_id\\\":\\\"3\\\"},{\\\"producto_id\\\":\\\"238\\\",\\\"cantidad\\\":\\\"1\\\",\\\"precio\\\":10,\\\"subtotal\\\":10,\\\"area_id\\\":\\\"4\\\"},{\\\"producto_id\\\":\\\"296\\\",\\\"cantidad\\\":\\\"1\\\",\\\"precio\\\":2,\\\"subtotal\\\":2,\\\"area_id\\\":\\\"sin-area\\\"},{\\\"producto_id\\\":\\\"303\\\",\\\"cantidad\\\":\\\"1\\\",\\\"precio\\\":2,\\\"subtotal\\\":2,\\\"area_id\\\":\\\"sin-area\\\"},{\\\"producto_id\\\":\\\"331\\\",\\\"cantidad\\\":\\\"1\\\",\\\"precio\\\":4,\\\"subtotal\\\":4,\\\"area_id\\\":\\\"sin-area\\\"}]\"', 22.00, 117.00, 'Barra', '\"[{\\\"metodo\\\":\\\"divisas\\\",\\\"monto\\\":\\\"17.00\\\",\\\"referencia\\\":null,\\\"cuenta\\\":\\\"seleccionar_cuenta\\\",\\\"banco\\\":\\\"venezuela\\\",\\\"autorizado_por\\\":null},{\\\"metodo\\\":\\\"pago_movil\\\",\\\"monto\\\":\\\"585.00\\\",\\\"referencia\\\":\\\"123456\\\",\\\"cuenta\\\":\\\"esmerley_banesco\\\",\\\"banco\\\":\\\"venezuela\\\",\\\"autorizado_por\\\":null}]\"', '2025-05-17 05:17:00', '2025-05-17 06:01:35', '2025-05-17 05:18:35', '2025-05-17 06:01:35', 1, 0, NULL, 0.00, 580.00),
(99, 'Leonel Ramos', 'Thor', NULL, 'Prueba de Comander #2', 1, NULL, '\"[{\\\"producto_id\\\":\\\"299\\\",\\\"cantidad\\\":\\\"1\\\",\\\"precio\\\":2,\\\"subtotal\\\":2,\\\"area_id\\\":\\\"3\\\"},{\\\"producto_id\\\":\\\"350\\\",\\\"cantidad\\\":\\\"1\\\",\\\"precio\\\":1,\\\"subtotal\\\":1,\\\"area_id\\\":\\\"sin-area\\\"},{\\\"producto_id\\\":\\\"297\\\",\\\"cantidad\\\":\\\"1\\\",\\\"precio\\\":2,\\\"subtotal\\\":2,\\\"area_id\\\":null}]\"', 5.00, 1.00, 'Cocina', NULL, '2025-05-17 06:11:00', NULL, '2025-05-17 06:11:30', '2025-05-17 06:15:04', 0, 0, NULL, 0.00, 0.00),
(100, 'Leonel Ramos', 'Thor', NULL, 'impresion prueba #2', 1, NULL, '\"[{\\\"producto_id\\\":\\\"316\\\",\\\"cantidad\\\":\\\"2\\\",\\\"precio\\\":2.5,\\\"subtotal\\\":5,\\\"area_id\\\":\\\"3\\\"},{\\\"producto_id\\\":\\\"246\\\",\\\"cantidad\\\":\\\"1\\\",\\\"precio\\\":8,\\\"subtotal\\\":8,\\\"area_id\\\":\\\"sin-area\\\"},{\\\"producto_id\\\":\\\"217\\\",\\\"cantidad\\\":\\\"1\\\",\\\"precio\\\":18,\\\"subtotal\\\":18,\\\"area_id\\\":\\\"sin-area\\\"}]\"', 31.00, 117.00, 'Barra', NULL, '2025-05-17 06:16:00', NULL, '2025-05-17 06:17:04', '2025-05-17 06:24:04', 0, 0, NULL, 0.00, 0.00),
(101, 'Leonel Ramos', 'Thor', NULL, 'impresion prueba #3', 1, NULL, '\"[{\\\"producto_id\\\":\\\"303\\\",\\\"cantidad\\\":\\\"2\\\",\\\"precio\\\":2,\\\"subtotal\\\":4,\\\"area_id\\\":\\\"3\\\"},{\\\"producto_id\\\":\\\"255\\\",\\\"cantidad\\\":\\\"1\\\",\\\"precio\\\":16,\\\"subtotal\\\":16,\\\"area_id\\\":\\\"sin-area\\\"},{\\\"producto_id\\\":\\\"300\\\",\\\"cantidad\\\":\\\"1\\\",\\\"precio\\\":2,\\\"subtotal\\\":2,\\\"area_id\\\":\\\"sin-area\\\"}]\"', 22.00, 117.00, 'Barra', NULL, '2025-05-17 06:37:00', NULL, '2025-05-17 06:37:42', '2025-05-17 07:37:21', 0, 0, NULL, 0.00, 0.00),
(102, 'Leonel Ramos', 'Capitan America', NULL, 'Prueba de Comandera #10', 1, NULL, '\"[{\\\"producto_id\\\":\\\"275\\\",\\\"cantidad\\\":\\\"1\\\",\\\"precio\\\":10,\\\"subtotal\\\":10,\\\"area_id\\\":\\\"3\\\"},{\\\"producto_id\\\":\\\"222\\\",\\\"cantidad\\\":\\\"1\\\",\\\"precio\\\":12,\\\"subtotal\\\":12,\\\"area_id\\\":\\\"sin-area\\\"},{\\\"producto_id\\\":\\\"327\\\",\\\"cantidad\\\":\\\"1\\\",\\\"precio\\\":2.5,\\\"subtotal\\\":2.5,\\\"area_id\\\":\\\"sin-area\\\"}]\"', 24.50, 117.00, 'Cocina', NULL, '2025-05-17 07:37:00', NULL, '2025-05-17 07:38:23', '2025-05-17 07:43:58', 0, 0, NULL, 0.00, 0.00),
(103, 'Karely', 'Batman', NULL, 'Prueba de Comandera #11', 1, NULL, '\"[{\\\"producto_id\\\":\\\"316\\\",\\\"cantidad\\\":\\\"1\\\",\\\"precio\\\":2.5,\\\"subtotal\\\":2.5,\\\"area_id\\\":\\\"3\\\"},{\\\"producto_id\\\":\\\"254\\\",\\\"cantidad\\\":\\\"1\\\",\\\"precio\\\":14,\\\"subtotal\\\":14,\\\"area_id\\\":\\\"sin-area\\\"}]\"', 16.50, 117.00, 'Carne en Vara', NULL, '2025-05-17 07:44:00', NULL, '2025-05-17 07:44:22', '2025-05-17 07:45:40', 0, 0, NULL, 0.00, 0.00),
(104, 'Leonel Ramos', 'Thor', NULL, 'Prueba de Comandera #12', 1, NULL, '\"[{\\\"producto_id\\\":\\\"297\\\",\\\"cantidad\\\":\\\"1\\\",\\\"precio\\\":2,\\\"subtotal\\\":2,\\\"area_id\\\":\\\"3\\\"},{\\\"producto_id\\\":\\\"238\\\",\\\"cantidad\\\":\\\"1\\\",\\\"precio\\\":10,\\\"subtotal\\\":10,\\\"area_id\\\":\\\"sin-area\\\"},{\\\"producto_id\\\":\\\"297\\\",\\\"cantidad\\\":\\\"1\\\",\\\"precio\\\":2,\\\"subtotal\\\":2,\\\"area_id\\\":\\\"sin-area\\\"}]\"', 14.00, 117.00, 'Barra', NULL, '2025-05-17 07:47:00', NULL, '2025-05-17 07:48:11', '2025-05-17 07:53:21', 0, 0, NULL, 0.00, 0.00),
(105, 'Leonel Ramos', 'Thor', NULL, 'Prueba de Comandera #13', 1, NULL, '\"[{\\\"producto_id\\\":\\\"303\\\",\\\"cantidad\\\":\\\"1\\\",\\\"precio\\\":2,\\\"subtotal\\\":2,\\\"area_id\\\":\\\"3\\\"},{\\\"producto_id\\\":\\\"297\\\",\\\"cantidad\\\":\\\"1\\\",\\\"precio\\\":2,\\\"subtotal\\\":2,\\\"area_id\\\":\\\"sin-area\\\"},{\\\"producto_id\\\":\\\"303\\\",\\\"cantidad\\\":\\\"1\\\",\\\"precio\\\":2,\\\"subtotal\\\":2,\\\"area_id\\\":\\\"sin-area\\\"}]\"', 6.00, 1.00, 'Barra', NULL, '2025-05-17 08:02:00', NULL, '2025-05-17 08:02:19', '2025-05-17 08:26:31', 0, 0, NULL, 0.00, 0.00),
(106, 'Leonel Ramos', 'Calipso', NULL, 'Prueba de Comandera #14', 1, NULL, '\"[{\\\"producto_id\\\":\\\"300\\\",\\\"cantidad\\\":\\\"1\\\",\\\"precio\\\":2,\\\"subtotal\\\":2,\\\"area_id\\\":\\\"3\\\"},{\\\"producto_id\\\":\\\"316\\\",\\\"cantidad\\\":\\\"1\\\",\\\"precio\\\":2.5,\\\"subtotal\\\":2.5,\\\"area_id\\\":\\\"sin-area\\\"}]\"', 4.50, 117.00, 'Barra', NULL, '2025-05-17 08:32:00', NULL, '2025-05-17 08:32:13', '2025-05-17 08:39:51', 0, 0, NULL, 0.00, 0.00),
(107, 'Leonel Ramos', 'SuperMan', NULL, 'Prueba de Comandera #15', 1, NULL, '\"[{\\\"producto_id\\\":\\\"316\\\",\\\"cantidad\\\":\\\"1\\\",\\\"precio\\\":2.5,\\\"subtotal\\\":2.5,\\\"area_id\\\":\\\"3\\\"},{\\\"producto_id\\\":\\\"254\\\",\\\"cantidad\\\":\\\"1\\\",\\\"precio\\\":14,\\\"subtotal\\\":14,\\\"area_id\\\":\\\"3\\\"},{\\\"producto_id\\\":\\\"221\\\",\\\"cantidad\\\":\\\"1\\\",\\\"precio\\\":9,\\\"subtotal\\\":9,\\\"area_id\\\":\\\"3\\\"}]\"', 25.50, 117.00, 'Carne en Vara', NULL, '2025-05-17 09:03:00', NULL, '2025-05-17 09:03:23', '2025-05-17 09:55:58', 0, 0, NULL, 0.00, 0.00),
(108, 'Leonel Ramos', 'SuperMan', NULL, 'Prueba de Comander #16', 1, NULL, '\"[{\\\"producto_id\\\":\\\"303\\\",\\\"cantidad\\\":\\\"1\\\",\\\"precio\\\":2,\\\"subtotal\\\":2,\\\"area_id\\\":\\\"3\\\"},{\\\"producto_id\\\":\\\"255\\\",\\\"cantidad\\\":\\\"1\\\",\\\"precio\\\":16,\\\"subtotal\\\":16,\\\"area_id\\\":\\\"2\\\"},{\\\"producto_id\\\":\\\"297\\\",\\\"cantidad\\\":\\\"1\\\",\\\"precio\\\":2,\\\"subtotal\\\":2,\\\"area_id\\\":3}]\"', 20.00, 117.00, 'Barra', NULL, '2025-05-17 09:38:00', NULL, '2025-05-17 09:38:16', '2025-05-17 09:39:20', 0, 0, NULL, 0.00, 0.00),
(109, 'Leonel Ramos', 'Batman', NULL, 'Prueba de Comander #17', 1, NULL, '\"[{\\\"producto_id\\\":\\\"297\\\",\\\"cantidad\\\":\\\"1\\\",\\\"precio\\\":2,\\\"subtotal\\\":2,\\\"area_id\\\":\\\"3\\\"},{\\\"producto_id\\\":\\\"212\\\",\\\"cantidad\\\":\\\"1\\\",\\\"precio\\\":35,\\\"subtotal\\\":35,\\\"area_id\\\":\\\"1\\\"},{\\\"producto_id\\\":\\\"288\\\",\\\"cantidad\\\":\\\"1\\\",\\\"precio\\\":4,\\\"subtotal\\\":4,\\\"area_id\\\":\\\"3\\\"}]\"', 41.00, 117.00, 'Cocina', '\"[{\\\"metodo\\\":\\\"divisas\\\",\\\"monto\\\":\\\"41.00\\\",\\\"referencia\\\":null,\\\"cuenta\\\":\\\"seleccionar_cuenta\\\",\\\"banco\\\":\\\"venezuela\\\",\\\"autorizado_por\\\":null}]\"', '2025-05-17 09:40:00', '2025-05-17 09:42:36', '2025-05-17 09:41:07', '2025-05-17 09:42:36', 1, 0, NULL, 0.00, 0.00),
(110, 'Leonel Ramos', 'Capitan America', NULL, 'Prueba de Comander #17', 1, NULL, '\"[{\\\"producto_id\\\":\\\"297\\\",\\\"cantidad\\\":\\\"1\\\",\\\"precio\\\":2,\\\"subtotal\\\":2,\\\"area_id\\\":\\\"3\\\"},{\\\"producto_id\\\":\\\"213\\\",\\\"cantidad\\\":\\\"1\\\",\\\"precio\\\":35,\\\"subtotal\\\":35,\\\"area_id\\\":\\\"1\\\"},{\\\"producto_id\\\":\\\"275\\\",\\\"cantidad\\\":\\\"1\\\",\\\"precio\\\":10,\\\"subtotal\\\":10,\\\"area_id\\\":\\\"3\\\"}]\"', 47.00, 117.00, 'Carne en Vara', '\"[{\\\"metodo\\\":\\\"pago_movil\\\",\\\"monto\\\":\\\"5499.00\\\",\\\"referencia\\\":\\\"123456\\\",\\\"cuenta\\\":\\\"juridica\\\",\\\"banco\\\":\\\"venezuela\\\",\\\"autorizado_por\\\":null}]\"', '2025-05-17 09:43:00', '2025-05-17 09:45:28', '2025-05-17 09:43:55', '2025-05-17 09:45:28', 1, 0, NULL, 0.00, 5452.00),
(111, 'Leonel Ramos', 'Calipso', NULL, 'Prueba de Comander #18', 1, NULL, '\"[{\\\"producto_id\\\":\\\"297\\\",\\\"cantidad\\\":\\\"3\\\",\\\"precio\\\":2,\\\"subtotal\\\":6,\\\"area_id\\\":\\\"3\\\"},{\\\"producto_id\\\":\\\"217\\\",\\\"cantidad\\\":\\\"2\\\",\\\"precio\\\":18,\\\"subtotal\\\":36,\\\"area_id\\\":\\\"1\\\"},{\\\"producto_id\\\":\\\"258\\\",\\\"cantidad\\\":\\\"4\\\",\\\"precio\\\":5,\\\"subtotal\\\":20,\\\"area_id\\\":\\\"3\\\"},{\\\"producto_id\\\":\\\"299\\\",\\\"cantidad\\\":\\\"1\\\",\\\"precio\\\":2,\\\"subtotal\\\":2,\\\"area_id\\\":\\\"3\\\"}]\"', 64.00, 117.00, 'Barra', '\"[{\\\"metodo\\\":\\\"divisas\\\",\\\"monto\\\":\\\"20.00\\\",\\\"referencia\\\":null,\\\"cuenta\\\":\\\"seleccionar_cuenta\\\",\\\"banco\\\":\\\"venezuela\\\",\\\"autorizado_por\\\":null},{\\\"metodo\\\":\\\"punto_venta\\\",\\\"monto\\\":\\\"5148.00\\\",\\\"referencia\\\":\\\"123456\\\",\\\"cuenta\\\":\\\"seleccionar_cuenta\\\",\\\"banco\\\":\\\"bancamiga\\\",\\\"autorizado_por\\\":null}]\"', '2025-05-17 09:46:00', '2025-05-17 09:49:30', '2025-05-17 09:46:40', '2025-05-17 09:49:30', 1, 0, NULL, 0.00, 5104.00),
(112, 'Leonel Ramos', 'Batman', NULL, 'Prueba de Comander #19', 1, NULL, '\"[{\\\"producto_id\\\":\\\"299\\\",\\\"nombre\\\":\\\"1\\\\\\/2 Batido de Durazno\\\",\\\"cantidad\\\":\\\"1\\\",\\\"precio\\\":2,\\\"subtotal\\\":2,\\\"area_id\\\":\\\"3\\\"},{\\\"producto_id\\\":\\\"316\\\",\\\"nombre\\\":\\\"1\\\\\\/2 Batido Berry (Mora y Fresa)\\\",\\\"cantidad\\\":\\\"1\\\",\\\"precio\\\":2.5,\\\"subtotal\\\":2.5,\\\"area_id\\\":3}]\"', 4.50, 117.00, 'Barra', NULL, '2025-05-17 10:35:00', NULL, '2025-05-17 10:35:21', '2025-05-17 10:44:02', 0, 0, NULL, 0.00, 0.00),
(113, 'Leonel Ramos', 'Batman', NULL, 'Prueba de Comandera #20', 1, NULL, '\"[{\\\"producto_id\\\":\\\"218\\\",\\\"nombre\\\":\\\"1\\\\\\/2Kg de Carne Importada\\\",\\\"cantidad\\\":\\\"1\\\",\\\"precio\\\":23,\\\"subtotal\\\":23,\\\"area_id\\\":\\\"1\\\",\\\"printed_at\\\":\\\"2025-05-17 09:07:42\\\"},{\\\"producto_id\\\":\\\"275\\\",\\\"nombre\\\":\\\"Tobo 10 Cervezas Zulia\\\",\\\"cantidad\\\":\\\"4\\\",\\\"precio\\\":10,\\\"subtotal\\\":40,\\\"area_id\\\":\\\"3\\\",\\\"printed_at\\\":\\\"2025-05-17 09:07:46\\\"},{\\\"producto_id\\\":\\\"303\\\",\\\"nombre\\\":\\\"1\\\\\\/2 Batido de Guanabana\\\",\\\"cantidad\\\":\\\"1\\\",\\\"precio\\\":2,\\\"subtotal\\\":2,\\\"area_id\\\":\\\"3\\\",\\\"printed_at\\\":\\\"2025-05-17 09:07:49\\\"}]\"', 65.00, 117.00, 'Carne en Vara', '\"[{\\\"metodo\\\":\\\"divisas\\\",\\\"monto\\\":\\\"65.00\\\",\\\"referencia\\\":null,\\\"cuenta\\\":\\\"seleccionar_cuenta\\\",\\\"banco\\\":\\\"venezuela\\\",\\\"autorizado_por\\\":null}]\"', '2025-05-17 10:45:00', '2025-05-17 13:49:24', '2025-05-17 10:46:15', '2025-05-17 13:49:24', 1, 0, NULL, 0.00, 0.00),
(114, 'Leonel Ramos', 'Thor', NULL, 'Prueba de Comander #21', 1, NULL, '\"[{\\\"producto_id\\\":\\\"316\\\",\\\"nombre\\\":\\\"1\\\\\\/2 Batido Berry (Mora y Fresa)\\\",\\\"cantidad\\\":\\\"1\\\",\\\"precio\\\":2.5,\\\"subtotal\\\":2.5,\\\"area_id\\\":\\\"3\\\",\\\"printed_at\\\":null},{\\\"producto_id\\\":\\\"256\\\",\\\"nombre\\\":\\\"1\\\\\\/2 Cachapa C\\\\\\/ Queso Telita\\\",\\\"cantidad\\\":\\\"1\\\",\\\"precio\\\":5,\\\"subtotal\\\":5,\\\"area_id\\\":\\\"2\\\",\\\"printed_at\\\":\\\"2025-05-17 09:51:59\\\"},{\\\"producto_id\\\":\\\"217\\\",\\\"nombre\\\":\\\"1\\\\\\/2Kg de Carne\\\",\\\"cantidad\\\":\\\"1\\\",\\\"precio\\\":18,\\\"subtotal\\\":18,\\\"area_id\\\":\\\"1\\\",\\\"printed_at\\\":\\\"2025-05-17 09:52:02\\\"},{\\\"producto_id\\\":\\\"299\\\",\\\"nombre\\\":\\\"1\\\\\\/2 Batido de Durazno\\\",\\\"cantidad\\\":\\\"1\\\",\\\"precio\\\":2,\\\"subtotal\\\":2,\\\"area_id\\\":\\\"3\\\",\\\"printed_at\\\":\\\"2025-05-17 09:52:06\\\"},{\\\"producto_id\\\":\\\"316\\\",\\\"nombre\\\":\\\"1\\\\\\/2 Batido Berry (Mora y Fresa)\\\",\\\"cantidad\\\":\\\"1\\\",\\\"precio\\\":2.5,\\\"subtotal\\\":2.5,\\\"area_id\\\":\\\"3\\\",\\\"printed_at\\\":null},{\\\"producto_id\\\":\\\"312\\\",\\\"nombre\\\":\\\"1\\\\\\/2 Batido Potencia (Mora, Fresa y Tomate de \\\\u00c1rbol)\\\",\\\"cantidad\\\":\\\"1\\\",\\\"precio\\\":2.5,\\\"subtotal\\\":2.5,\\\"area_id\\\":\\\"3\\\",\\\"printed_at\\\":\\\"2025-05-17 09:52:12\\\"}]\"', 32.50, 117.00, 'Barra', NULL, '2025-05-17 10:50:00', NULL, '2025-05-17 10:50:39', '2025-05-17 13:52:30', 0, 0, NULL, 0.00, 0.00),
(115, 'Leonel Ramos', 'FLAHS', NULL, 'Prueba de Comandera #23', 1, NULL, '\"[{\\\"producto_id\\\":\\\"302\\\",\\\"nombre\\\":\\\"1\\\\\\/2 Batido de Patilla\\\",\\\"cantidad\\\":\\\"1\\\",\\\"precio\\\":2,\\\"subtotal\\\":2,\\\"area_id\\\":\\\"3\\\"},{\\\"producto_id\\\":\\\"222\\\",\\\"nombre\\\":\\\"1\\\\\\/4Kg de Carne Importada\\\",\\\"cantidad\\\":\\\"8\\\",\\\"precio\\\":12,\\\"subtotal\\\":96,\\\"area_id\\\":\\\"1\\\"},{\\\"producto_id\\\":\\\"360\\\",\\\"nombre\\\":\\\"Cigarrillo Lucky\\\",\\\"cantidad\\\":\\\"17\\\",\\\"precio\\\":5,\\\"subtotal\\\":85,\\\"area_id\\\":\\\"3\\\"},{\\\"producto_id\\\":\\\"297\\\",\\\"nombre\\\":\\\"1\\\\\\/2 Batido de Fresa\\\",\\\"cantidad\\\":\\\"1\\\",\\\"precio\\\":2,\\\"subtotal\\\":2,\\\"area_id\\\":\\\"3\\\"},{\\\"producto_id\\\":\\\"241\\\",\\\"nombre\\\":\\\"Ceviche Mixto\\\",\\\"cantidad\\\":\\\"1\\\",\\\"precio\\\":16,\\\"subtotal\\\":16,\\\"area_id\\\":\\\"4\\\"}]\"', 201.00, 117.00, 'Carne en Vara', '\"[{\\\"metodo\\\":\\\"divisas\\\",\\\"monto\\\":\\\"201.00\\\",\\\"referencia\\\":null,\\\"cuenta\\\":null,\\\"banco\\\":\\\"venezuela\\\",\\\"autorizado_por\\\":null},{\\\"metodo\\\":\\\"propina_bolivares\\\",\\\"monto\\\":\\\"10.00\\\",\\\"referencia\\\":null,\\\"cuenta\\\":\\\"seleccionar_cuenta\\\",\\\"banco\\\":\\\"venezuela\\\",\\\"autorizado_por\\\":null}]\"', '2025-05-17 10:53:00', '2025-05-17 11:35:37', '2025-05-17 10:56:06', '2025-05-17 11:35:37', 1, 0, NULL, 0.00, 10.00),
(117, 'Leonel Ramos', 'Thor', NULL, 'Prueba de Comander #26', 1, NULL, '\"[{\\\"producto_id\\\":\\\"303\\\",\\\"nombre\\\":\\\"1\\\\\\/2 Batido de Guanabana\\\",\\\"cantidad\\\":\\\"1\\\",\\\"precio\\\":2,\\\"subtotal\\\":2,\\\"area_id\\\":\\\"3\\\",\\\"printed_at\\\":\\\"2025-05-17 08:47:41\\\"},{\\\"producto_id\\\":\\\"256\\\",\\\"nombre\\\":\\\"1\\\\\\/2 Cachapa C\\\\\\/ Queso Telita\\\",\\\"cantidad\\\":\\\"1\\\",\\\"precio\\\":5,\\\"subtotal\\\":5,\\\"area_id\\\":\\\"2\\\",\\\"printed_at\\\":\\\"2025-05-17 08:49:25\\\"},{\\\"producto_id\\\":\\\"300\\\",\\\"nombre\\\":\\\"1\\\\\\/2 Batido de Lechoza\\\",\\\"cantidad\\\":\\\"1\\\",\\\"precio\\\":2,\\\"subtotal\\\":2,\\\"area_id\\\":\\\"3\\\",\\\"printed_at\\\":\\\"2025-05-17 08:51:56\\\"}]\"', 9.00, 117.00, 'Barra', '\"[{\\\"metodo\\\":\\\"divisas\\\",\\\"monto\\\":\\\"9.00\\\",\\\"referencia\\\":null,\\\"cuenta\\\":\\\"seleccionar_cuenta\\\",\\\"banco\\\":\\\"venezuela\\\",\\\"autorizado_por\\\":null}]\"', '2025-05-17 12:47:00', '2025-05-17 13:49:59', '2025-05-17 12:47:24', '2025-05-17 13:49:59', 1, 0, NULL, 0.00, 0.00),
(118, 'Leonel Ramos', 'Batman', NULL, 'Prueba de Comander #24', 1, NULL, '\"[{\\\"producto_id\\\":\\\"316\\\",\\\"nombre\\\":\\\"1\\\\\\/2 Batido Berry (Mora y Fresa)\\\",\\\"cantidad\\\":\\\"1\\\",\\\"precio\\\":2.5,\\\"subtotal\\\":2.5,\\\"area_id\\\":\\\"3\\\",\\\"printed_at\\\":\\\"2025-05-17 13:58:05\\\"},{\\\"producto_id\\\":\\\"299\\\",\\\"nombre\\\":\\\"1\\\\\\/2 Batido de Durazno\\\",\\\"cantidad\\\":\\\"1\\\",\\\"precio\\\":2,\\\"subtotal\\\":2,\\\"area_id\\\":\\\"3\\\",\\\"printed_at\\\":\\\"2025-05-17 13:58:22\\\"}]\"', 4.50, 1.00, 'Cocina', NULL, '2025-05-17 14:16:00', NULL, '2025-05-17 14:17:25', '2025-05-17 17:58:22', 0, 0, NULL, 0.00, 0.00),
(119, 'Leonel Ramos', 'Batman', NULL, 'Prueba de Comander #26', 10, NULL, '\"[{\\\"producto_id\\\":\\\"316\\\",\\\"nombre\\\":\\\"1\\\\\\/2 Batido Berry (Mora y Fresa)\\\",\\\"cantidad\\\":\\\"1\\\",\\\"precio\\\":2.5,\\\"subtotal\\\":2.5,\\\"area_id\\\":\\\"3\\\",\\\"printed_at\\\":\\\"2025-05-17 22:05:34\\\"},{\\\"producto_id\\\":\\\"299\\\",\\\"nombre\\\":\\\"1\\\\\\/2 Batido de Durazno\\\",\\\"cantidad\\\":\\\"1\\\",\\\"precio\\\":2,\\\"subtotal\\\":2,\\\"area_id\\\":\\\"3\\\",\\\"printed_at\\\":\\\"2025-05-17 22:09:39\\\"},{\\\"producto_id\\\":\\\"316\\\",\\\"nombre\\\":\\\"1\\\\\\/2 Batido Berry (Mora y Fresa)\\\",\\\"cantidad\\\":\\\"1\\\",\\\"precio\\\":2.5,\\\"subtotal\\\":2.5,\\\"area_id\\\":\\\"3\\\",\\\"printed_at\\\":\\\"2025-05-17 22:05:34\\\"},{\\\"producto_id\\\":\\\"303\\\",\\\"nombre\\\":\\\"1\\\\\\/2 Batido de Guanabana\\\",\\\"cantidad\\\":\\\"1\\\",\\\"precio\\\":2,\\\"subtotal\\\":2,\\\"area_id\\\":\\\"3\\\",\\\"printed_at\\\":\\\"2025-05-17 22:10:41\\\"},{\\\"producto_id\\\":\\\"303\\\",\\\"nombre\\\":\\\"1\\\\\\/2 Batido de Guanabana\\\",\\\"cantidad\\\":\\\"1\\\",\\\"precio\\\":2,\\\"subtotal\\\":2,\\\"area_id\\\":\\\"3\\\",\\\"printed_at\\\":\\\"2025-05-17 22:10:41\\\"}]\"', 11.00, 117.00, 'Barra', NULL, '2025-05-18 02:04:00', NULL, '2025-05-18 02:05:04', '2025-05-18 02:10:50', 0, 0, NULL, 0.00, 0.00),
(120, 'Leonel Ramos', 'Thor', NULL, 'Prueba de Comander #1', 1, NULL, '\"[{\\\"producto_id\\\":\\\"303\\\",\\\"nombre\\\":\\\"1\\\\\\/2 Batido de Guanabana\\\",\\\"cantidad\\\":\\\"1\\\",\\\"precio\\\":2,\\\"subtotal\\\":2,\\\"area_id\\\":\\\"3\\\",\\\"printed_at\\\":\\\"2025-05-19 03:08:22\\\"}]\"', 2.00, 117.00, 'Barra', NULL, '2025-05-19 07:06:00', NULL, '2025-05-19 07:06:29', '2025-05-19 07:08:22', 0, 0, NULL, 0.00, 0.00),
(121, 'Leonel Ramos', 'Batman', NULL, 'Prueba de Comander #1', 1, NULL, '\"[{\\\"producto_id\\\":\\\"303\\\",\\\"nombre\\\":\\\"1\\\\\\/2 Batido de Guanabana\\\",\\\"cantidad\\\":\\\"1\\\",\\\"precio\\\":2,\\\"subtotal\\\":2,\\\"area_id\\\":\\\"3\\\",\\\"printed_at\\\":\\\"2025-05-19 15:51:17\\\"},{\\\"producto_id\\\":\\\"297\\\",\\\"nombre\\\":\\\"1\\\\\\/2 Batido de Fresa\\\",\\\"cantidad\\\":\\\"1\\\",\\\"precio\\\":2,\\\"subtotal\\\":2,\\\"area_id\\\":\\\"3\\\",\\\"printed_at\\\":\\\"2025-05-19 15:52:09\\\"}]\"', 4.00, 117.00, 'Cocina', NULL, '2025-05-19 19:50:00', NULL, '2025-05-19 19:50:58', '2025-05-19 19:52:16', 0, 0, NULL, 0.00, 0.00),
(122, 'Leonel Ramos', 'Thor', NULL, 'Prueba de Comander #1', 1, NULL, '\"[{\\\"producto_id\\\":\\\"299\\\",\\\"nombre\\\":\\\"1\\\\\\/2 Batido de Durazno\\\",\\\"cantidad\\\":\\\"1\\\",\\\"precio\\\":2,\\\"subtotal\\\":2,\\\"area_id\\\":\\\"3\\\",\\\"printed_at\\\":\\\"2025-05-19 15:52:53\\\"}]\"', 2.00, 117.00, 'Cocina', NULL, '2025-05-19 19:52:00', NULL, '2025-05-19 19:52:37', '2025-05-19 19:52:53', 0, 0, NULL, 0.00, 0.00),
(123, 'Leonel', 'Batman', NULL, 'Prueba de Comander #26', 1, NULL, '\"[{\\\"producto_id\\\":\\\"303\\\",\\\"nombre\\\":\\\"1\\\\\\/2 Batido de Guanabana\\\",\\\"cantidad\\\":\\\"1\\\",\\\"precio\\\":2,\\\"subtotal\\\":2,\\\"area_id\\\":\\\"3\\\",\\\"printed_at\\\":\\\"2025-05-19 18:54:18\\\"}]\"', 2.00, 117.00, 'Barra', NULL, '2025-05-19 22:53:00', NULL, '2025-05-19 22:54:07', '2025-05-19 22:54:18', 0, 0, NULL, 0.00, 0.00),
(125, 'Leonel Ramos', 'Thor', NULL, 'Ya estamos por terminar', 1, NULL, '\"[{\\\"producto_id\\\":\\\"297\\\",\\\"nombre\\\":\\\"1\\\\\\/2 Batido de Fresa\\\",\\\"cantidad\\\":\\\"1\\\",\\\"precio\\\":2,\\\"subtotal\\\":2,\\\"area_id\\\":\\\"3\\\",\\\"printed_at\\\":\\\"2025-05-20 06:25:06\\\"}]\"', 2.00, 117.00, 'Barra', '\"[{\\\"metodo\\\":\\\"divisas\\\",\\\"monto\\\":\\\"1.00\\\",\\\"referencia\\\":null,\\\"cuenta\\\":\\\"seleccionar_cuenta\\\",\\\"banco\\\":\\\"venezuela\\\",\\\"autorizado_por\\\":null}]\"', '2025-05-20 10:24:00', NULL, '2025-05-20 10:24:32', '2025-05-20 15:26:45', 0, 0, NULL, 0.00, 0.00),
(126, 'Leonel Ramos', 'Thor', NULL, 'AntMan', 1, NULL, '\"[{\\\"producto_id\\\":\\\"253\\\",\\\"nombre\\\":\\\"Cachapa C\\\\\\/ Queso Telita\\\",\\\"cantidad\\\":\\\"1\\\",\\\"precio\\\":10,\\\"subtotal\\\":10,\\\"area_id\\\":\\\"2\\\",\\\"printed_at\\\":\\\"2025-05-20 10:22:29\\\"},{\\\"producto_id\\\":\\\"322\\\",\\\"nombre\\\":\\\"Merengada de Fresa\\\",\\\"cantidad\\\":\\\"1\\\",\\\"precio\\\":5,\\\"subtotal\\\":5,\\\"area_id\\\":\\\"3\\\",\\\"printed_at\\\":\\\"2025-05-20 10:22:41\\\"}]\"', 15.00, 117.00, 'Churuata', '\"[{\\\"metodo\\\":\\\"pago_movil\\\",\\\"monto\\\":\\\"1755.00\\\",\\\"referencia\\\":\\\"123456\\\",\\\"cuenta\\\":\\\"genesis_venezuela\\\",\\\"banco\\\":\\\"venezuela\\\",\\\"autorizado_por\\\":null}]\"', '2025-05-20 14:19:00', '2025-05-20 14:25:37', '2025-05-20 14:21:44', '2025-05-20 14:25:37', 1, 0, NULL, 0.00, 1740.00),
(127, 'Leonel Ramos', 'Batman', NULL, 'Prueba de roles #7', 1, NULL, '\"[{\\\"producto_id\\\":\\\"303\\\",\\\"nombre\\\":\\\"1\\\\\\/2 Batido de Guanabana\\\",\\\"cantidad\\\":\\\"1\\\",\\\"precio\\\":2,\\\"subtotal\\\":2,\\\"area_id\\\":\\\"3\\\",\\\"printed_at\\\":null}]\"', 2.00, 117.00, 'Churuata', '\"[{\\\"metodo\\\":\\\"divisas\\\",\\\"monto\\\":\\\"2.00\\\",\\\"referencia\\\":null,\\\"cuenta\\\":null,\\\"banco\\\":\\\"venezuela\\\",\\\"autorizado_por\\\":null}]\"', '2025-05-20 15:05:00', '2025-05-21 11:12:08', '2025-05-20 15:05:35', '2025-05-21 11:12:08', 1, 0, NULL, 0.00, 0.00),
(128, 'Leonel', 'Thor', NULL, 'Prueba de roles #7', 1, NULL, '\"[{\\\"producto_id\\\":\\\"300\\\",\\\"nombre\\\":\\\"1\\\\\\/2 Batido de Lechoza\\\",\\\"cantidad\\\":\\\"1\\\",\\\"precio\\\":2,\\\"subtotal\\\":2,\\\"area_id\\\":\\\"3\\\",\\\"printed_at\\\":null}]\"', 2.00, 117.00, 'Churuata', NULL, '2025-05-20 16:19:00', NULL, '2025-05-20 16:19:30', '2025-05-20 16:21:33', 0, 0, NULL, 0.00, 0.00),
(129, 'Leonel Ramos', 'Batman', NULL, 'Prueba de Comandera 80mm #1', 1, NULL, '\"[{\\\"producto_id\\\":\\\"297\\\",\\\"nombre\\\":\\\"1\\\\\\/2 Batido de Fresa\\\",\\\"cantidad\\\":\\\"1\\\",\\\"precio\\\":2,\\\"subtotal\\\":2,\\\"area_id\\\":\\\"3\\\",\\\"printed_at\\\":\\\"2025-05-20 21:23:14\\\"}]\"', 2.00, 117.00, 'Salón Principal', NULL, '2025-05-21 01:21:00', NULL, '2025-05-21 01:22:10', '2025-05-21 01:23:14', 0, 0, NULL, 0.00, 0.00),
(130, 'Leonel', 'Batman', NULL, 'Prueba de Comandera 80mm #2', 1, NULL, '\"[{\\\"producto_id\\\":\\\"297\\\",\\\"nombre\\\":\\\"1\\\\\\/2 Batido de Fresa\\\",\\\"cantidad\\\":\\\"1\\\",\\\"precio\\\":2,\\\"subtotal\\\":2,\\\"area_id\\\":\\\"3\\\",\\\"printed_at\\\":\\\"2025-05-21 01:57:11\\\"},{\\\"producto_id\\\":\\\"316\\\",\\\"nombre\\\":\\\"1\\\\\\/2 Batido Berry (Mora y Fresa)\\\",\\\"cantidad\\\":\\\"1\\\",\\\"precio\\\":2.5,\\\"subtotal\\\":2.5,\\\"area_id\\\":\\\"3\\\",\\\"printed_at\\\":\\\"2025-05-21 01:57:56\\\"},{\\\"producto_id\\\":\\\"300\\\",\\\"nombre\\\":\\\"1\\\\\\/2 Batido de Lechoza\\\",\\\"cantidad\\\":\\\"1\\\",\\\"precio\\\":2,\\\"subtotal\\\":2,\\\"area_id\\\":\\\"3\\\",\\\"printed_at\\\":\\\"2025-05-21 01:59:00\\\"},{\\\"producto_id\\\":\\\"313\\\",\\\"nombre\\\":\\\"1\\\\\\/2 Batido Rez\\\\u00e1gate (Mango, Pi\\\\u00f1a y Maracuya)\\\",\\\"cantidad\\\":\\\"1\\\",\\\"precio\\\":2.5,\\\"subtotal\\\":2.5,\\\"area_id\\\":\\\"3\\\",\\\"printed_at\\\":\\\"2025-05-21 02:07:58\\\"},{\\\"producto_id\\\":\\\"306\\\",\\\"nombre\\\":\\\"1\\\\\\/2 Batido de Limonada\\\",\\\"cantidad\\\":\\\"1\\\",\\\"precio\\\":2,\\\"subtotal\\\":2,\\\"area_id\\\":\\\"3\\\",\\\"printed_at\\\":\\\"2025-05-21 02:42:05\\\"},{\\\"producto_id\\\":\\\"316\\\",\\\"nombre\\\":\\\"1\\\\\\/2 Batido Berry (Mora y Fresa)\\\",\\\"cantidad\\\":\\\"1\\\",\\\"precio\\\":2.5,\\\"subtotal\\\":2.5,\\\"area_id\\\":\\\"3\\\",\\\"printed_at\\\":\\\"2025-05-21 01:57:56\\\"},{\\\"producto_id\\\":\\\"303\\\",\\\"nombre\\\":\\\"1\\\\\\/2 Batido de Guanabana\\\",\\\"cantidad\\\":\\\"1\\\",\\\"precio\\\":2,\\\"subtotal\\\":2,\\\"area_id\\\":\\\"3\\\",\\\"printed_at\\\":\\\"2025-05-21 02:42:53\\\"},{\\\"producto_id\\\":\\\"299\\\",\\\"nombre\\\":\\\"1\\\\\\/2 Batido de Durazno\\\",\\\"cantidad\\\":\\\"1\\\",\\\"precio\\\":2,\\\"subtotal\\\":2,\\\"area_id\\\":\\\"3\\\",\\\"printed_at\\\":\\\"2025-05-21 02:49:55\\\"},{\\\"producto_id\\\":\\\"316\\\",\\\"nombre\\\":\\\"1\\\\\\/2 Batido Berry (Mora y Fresa)\\\",\\\"cantidad\\\":\\\"1\\\",\\\"precio\\\":2.5,\\\"subtotal\\\":2.5,\\\"area_id\\\":\\\"3\\\",\\\"printed_at\\\":\\\"2025-05-21 01:57:56\\\"},{\\\"producto_id\\\":\\\"316\\\",\\\"nombre\\\":\\\"1\\\\\\/2 Batido Berry (Mora y Fresa)\\\",\\\"cantidad\\\":\\\"1\\\",\\\"precio\\\":2.5,\\\"subtotal\\\":2.5,\\\"area_id\\\":\\\"3\\\",\\\"printed_at\\\":\\\"2025-05-21 01:57:56\\\"}]\"', 22.50, 117.00, 'Salón Principal', NULL, '2025-05-21 05:56:00', NULL, '2025-05-21 05:56:50', '2025-05-21 06:50:41', 0, 0, NULL, 0.00, 0.00),
(131, 'Leonel Ramos', 'Thor', NULL, 'Prueba de Comandera de 80mm #3', 1, NULL, '\"[{\\\"producto_id\\\":\\\"299\\\",\\\"nombre\\\":\\\"1\\\\\\/2 Batido de Durazno\\\",\\\"cantidad\\\":\\\"1\\\",\\\"precio\\\":2,\\\"subtotal\\\":2,\\\"area_id\\\":\\\"3\\\",\\\"printed_at\\\":\\\"2025-05-21 02:51:32\\\"},{\\\"producto_id\\\":\\\"297\\\",\\\"nombre\\\":\\\"1\\\\\\/2 Batido de Fresa\\\",\\\"cantidad\\\":\\\"1\\\",\\\"precio\\\":2,\\\"subtotal\\\":2,\\\"area_id\\\":\\\"3\\\",\\\"printed_at\\\":\\\"2025-05-21 02:52:27\\\"},{\\\"producto_id\\\":\\\"300\\\",\\\"nombre\\\":\\\"1\\\\\\/2 Batido de Lechoza\\\",\\\"cantidad\\\":\\\"1\\\",\\\"precio\\\":2,\\\"subtotal\\\":2,\\\"area_id\\\":\\\"3\\\",\\\"printed_at\\\":\\\"2025-05-21 03:01:31\\\"},{\\\"producto_id\\\":\\\"316\\\",\\\"nombre\\\":\\\"1\\\\\\/2 Batido Berry (Mora y Fresa)\\\",\\\"cantidad\\\":\\\"1\\\",\\\"precio\\\":2.5,\\\"subtotal\\\":2.5,\\\"area_id\\\":\\\"3\\\",\\\"printed_at\\\":\\\"2025-05-21 03:38:28\\\"},{\\\"producto_id\\\":\\\"316\\\",\\\"nombre\\\":\\\"1\\\\\\/2 Batido Berry (Mora y Fresa)\\\",\\\"cantidad\\\":\\\"1\\\",\\\"precio\\\":2.5,\\\"subtotal\\\":2.5,\\\"area_id\\\":\\\"3\\\",\\\"printed_at\\\":\\\"2025-05-21 03:38:28\\\"},{\\\"producto_id\\\":\\\"316\\\",\\\"nombre\\\":\\\"1\\\\\\/2 Batido Berry (Mora y Fresa)\\\",\\\"cantidad\\\":\\\"1\\\",\\\"precio\\\":2.5,\\\"subtotal\\\":2.5,\\\"area_id\\\":\\\"3\\\",\\\"printed_at\\\":\\\"2025-05-21 03:38:28\\\"},{\\\"producto_id\\\":\\\"316\\\",\\\"nombre\\\":\\\"1\\\\\\/2 Batido Berry (Mora y Fresa)\\\",\\\"cantidad\\\":\\\"1\\\",\\\"precio\\\":2.5,\\\"subtotal\\\":2.5,\\\"area_id\\\":\\\"3\\\",\\\"printed_at\\\":\\\"2025-05-21 03:38:28\\\"},{\\\"producto_id\\\":\\\"316\\\",\\\"nombre\\\":\\\"1\\\\\\/2 Batido Berry (Mora y Fresa)\\\",\\\"cantidad\\\":\\\"1\\\",\\\"precio\\\":2.5,\\\"subtotal\\\":2.5,\\\"area_id\\\":\\\"3\\\",\\\"printed_at\\\":\\\"2025-05-21 03:38:28\\\"},{\\\"producto_id\\\":\\\"328\\\",\\\"nombre\\\":\\\"1\\\\\\/2 Merengada de Melon\\\",\\\"cantidad\\\":\\\"1\\\",\\\"precio\\\":2.5,\\\"subtotal\\\":2.5,\\\"area_id\\\":\\\"3\\\",\\\"printed_at\\\":\\\"2025-05-21 03:40:12\\\"},{\\\"producto_id\\\":\\\"300\\\",\\\"nombre\\\":\\\"1\\\\\\/2 Batido de Lechoza\\\",\\\"cantidad\\\":\\\"1\\\",\\\"precio\\\":2,\\\"subtotal\\\":2,\\\"area_id\\\":\\\"3\\\",\\\"printed_at\\\":\\\"2025-05-21 03:01:31\\\"},{\\\"producto_id\\\":\\\"218\\\",\\\"nombre\\\":\\\"1\\\\\\/2Kg de Carne Importada\\\",\\\"cantidad\\\":\\\"1\\\",\\\"precio\\\":23,\\\"subtotal\\\":23,\\\"area_id\\\":\\\"1\\\",\\\"printed_at\\\":\\\"2025-05-21 03:41:53\\\"},{\\\"producto_id\\\":\\\"335\\\",\\\"nombre\\\":\\\"1\\\\\\/2 Limonada (Lim\\\\u00f3n y Parchita)\\\",\\\"cantidad\\\":\\\"1\\\",\\\"precio\\\":2.5,\\\"subtotal\\\":2.5,\\\"area_id\\\":\\\"3\\\",\\\"printed_at\\\":\\\"2025-05-21 03:43:08\\\"},{\\\"producto_id\\\":\\\"329\\\",\\\"nombre\\\":\\\"1\\\\\\/2 Merengada de Lechosa\\\",\\\"cantidad\\\":\\\"1\\\",\\\"precio\\\":2.5,\\\"subtotal\\\":2.5,\\\"area_id\\\":3,\\\"printed_at\\\":\\\"2025-05-21 03:49:54\\\"}]\"', 51.00, 117.00, 'Churuata', NULL, '2025-05-21 06:50:00', NULL, '2025-05-21 06:51:12', '2025-05-21 07:49:54', 0, 0, NULL, 0.00, 0.00),
(132, 'Leonel Ramos', 'Thor', NULL, 'Prueba de Comandera de 80mm #4', 10, NULL, '\"[{\\\"producto_id\\\":\\\"297\\\",\\\"nombre\\\":\\\"1\\\\\\/2 Batido de Fresa\\\",\\\"cantidad\\\":\\\"1\\\",\\\"precio\\\":2,\\\"subtotal\\\":2,\\\"area_id\\\":\\\"3\\\",\\\"printed_at\\\":\\\"2025-05-21 04:37:34\\\"}]\"', 2.00, 177.00, 'Churuata', NULL, '2025-05-21 08:35:00', NULL, '2025-05-21 08:36:39', '2025-05-21 08:37:56', 0, 0, NULL, 0.00, 0.00),
(133, 'Leonel Ramos', 'Batman', NULL, 'Prueba de Comandera de 80mm #5', 10, NULL, '\"[{\\\"producto_id\\\":\\\"327\\\",\\\"nombre\\\":\\\"1\\\\\\/2 Merengada de Fresa\\\",\\\"cantidad\\\":\\\"1\\\",\\\"precio\\\":2.5,\\\"subtotal\\\":2.5,\\\"area_id\\\":\\\"3\\\",\\\"printed_at\\\":null}]\"', 2.50, 117.00, 'Churuata', NULL, '2025-05-21 11:14:00', NULL, '2025-05-21 11:15:01', '2025-05-21 11:16:18', 0, 0, NULL, 0.00, 0.00),
(134, 'Leonel Ramos', 'Batman', NULL, 'Prueba de Comandera de 80mm #6', 1, NULL, '\"[{\\\"producto_id\\\":\\\"303\\\",\\\"cantidad\\\":\\\"1\\\",\\\"precio\\\":2,\\\"subtotal\\\":2,\\\"area_id\\\":\\\"3\\\"}]\"', 2.00, 1.00, 'Churuata', '\"[]\"', '2025-05-21 12:38:00', NULL, '2025-05-21 12:38:36', '2025-05-21 12:38:36', 0, 0, NULL, 0.00, 0.00),
(135, 'Leonel Ramos', 'Batman', NULL, 'Prueba de Comandera de 80mm #7', 10, NULL, '\"[{\\\"producto_id\\\":\\\"303\\\",\\\"nombre\\\":\\\"1\\\\\\/2 Batido de Guanabana\\\",\\\"cantidad\\\":\\\"1\\\",\\\"precio\\\":2,\\\"subtotal\\\":2,\\\"area_id\\\":\\\"3\\\",\\\"printed_at\\\":\\\"2025-05-21 19:32:14\\\"}]\"', 2.00, 117.00, 'Churuata', NULL, '2025-05-21 18:39:00', NULL, '2025-05-21 18:39:31', '2025-05-21 23:32:14', 0, 0, NULL, 0.00, 0.00),
(136, 'Leonel', 'Batman', NULL, 'Prueba de Comandera de 80mm #9', 1, NULL, '\"[{\\\"producto_id\\\":\\\"303\\\",\\\"nombre\\\":\\\"1\\\\\\/2 Batido de Guanabana\\\",\\\"cantidad\\\":\\\"1\\\",\\\"precio\\\":2,\\\"subtotal\\\":2,\\\"area_id\\\":\\\"3\\\",\\\"printed_at\\\":null}]\"', 2.00, 117.00, 'Churuata', NULL, '2025-05-21 19:18:00', NULL, '2025-05-21 19:18:20', '2025-05-21 19:18:27', 0, 0, NULL, 0.00, 0.00),
(137, 'Leonel Ramos', 'Thor', NULL, 'Prueba de Comandera de 80mm #10', 1, NULL, '\"[{\\\"producto_id\\\":\\\"297\\\",\\\"nombre\\\":\\\"1\\\\\\/2 Batido de Fresa\\\",\\\"cantidad\\\":\\\"1\\\",\\\"precio\\\":2,\\\"subtotal\\\":2,\\\"area_id\\\":\\\"3\\\",\\\"printed_at\\\":null}]\"', 2.00, 100.00, 'Churuata', NULL, '2025-05-21 19:33:00', NULL, '2025-05-21 19:34:12', '2025-05-21 19:34:25', 0, 0, NULL, 0.00, 0.00),
(138, 'Leonel Ramos', 'Thor', NULL, 'Prueba de Comandera de 80mm #11', 1, NULL, '\"[{\\\"producto_id\\\":\\\"303\\\",\\\"nombre\\\":\\\"1\\\\\\/2 Batido de Guanabana\\\",\\\"cantidad\\\":\\\"1\\\",\\\"precio\\\":2,\\\"subtotal\\\":2,\\\"area_id\\\":\\\"3\\\",\\\"printed_at\\\":null}]\"', 2.00, 117.00, 'Churuata', NULL, '2025-05-21 19:45:00', NULL, '2025-05-21 19:45:32', '2025-05-21 19:46:01', 0, 0, NULL, 0.00, 0.00),
(139, 'Leonel Ramos', 'Thor', NULL, 'Prueba de Comandera de 80mm #11', 1, NULL, '\"[{\\\"producto_id\\\":\\\"306\\\",\\\"nombre\\\":\\\"1\\\\\\/2 Batido de Limonada\\\",\\\"cantidad\\\":\\\"1\\\",\\\"precio\\\":2,\\\"subtotal\\\":2,\\\"area_id\\\":\\\"3\\\",\\\"printed_at\\\":\\\"2025-05-21 20:50:50\\\"}]\"', 2.00, 117.00, 'Churuata', NULL, '2025-05-21 21:27:00', NULL, '2025-05-21 21:27:34', '2025-05-22 00:50:50', 0, 0, NULL, 0.00, 0.00),
(140, 'Leonel Ramos', 'Thor', NULL, 'Prueba de Comandera de 80mm #13', 1, NULL, '\"[{\\\"producto_id\\\":\\\"297\\\",\\\"nombre\\\":\\\"1\\\\\\/2 Batido de Fresa\\\",\\\"cantidad\\\":\\\"1\\\",\\\"precio\\\":2,\\\"subtotal\\\":2,\\\"area_id\\\":\\\"3\\\",\\\"printed_at\\\":\\\"2025-05-21 18:17:53\\\"}]\"', 2.00, 117.00, 'Churuata', NULL, '2025-05-21 22:10:00', NULL, '2025-05-21 22:10:53', '2025-05-21 22:17:53', 0, 0, NULL, 0.00, 0.00),
(141, 'Leonel Ramos', 'Thor', NULL, 'Prueba de Comander #1', 1, NULL, '\"[{\\\"producto_id\\\":\\\"297\\\",\\\"nombre\\\":\\\"1\\\\\\/2 Batido de Fresa\\\",\\\"cantidad\\\":\\\"1\\\",\\\"precio\\\":2,\\\"subtotal\\\":2,\\\"area_id\\\":\\\"3\\\",\\\"printed_at\\\":\\\"2025-05-21 19:08:39\\\"},{\\\"producto_id\\\":\\\"303\\\",\\\"nombre\\\":\\\"1\\\\\\/2 Batido de Guanabana\\\",\\\"cantidad\\\":\\\"1\\\",\\\"precio\\\":2,\\\"subtotal\\\":2,\\\"area_id\\\":\\\"3\\\",\\\"printed_at\\\":\\\"2025-05-21 19:10:39\\\"},{\\\"producto_id\\\":\\\"313\\\",\\\"nombre\\\":\\\"1\\\\\\/2 Batido Rez\\\\u00e1gate (Mango, Pi\\\\u00f1a y Maracuya)\\\",\\\"cantidad\\\":\\\"1\\\",\\\"precio\\\":2.5,\\\"subtotal\\\":2.5,\\\"area_id\\\":\\\"3\\\",\\\"printed_at\\\":\\\"2025-05-21 19:12:19\\\"},{\\\"producto_id\\\":\\\"303\\\",\\\"nombre\\\":\\\"1\\\\\\/2 Batido de Guanabana\\\",\\\"cantidad\\\":\\\"1\\\",\\\"precio\\\":2,\\\"subtotal\\\":2,\\\"area_id\\\":\\\"3\\\",\\\"printed_at\\\":\\\"2025-05-21 19:10:39\\\"},{\\\"producto_id\\\":\\\"280\\\",\\\"nombre\\\":\\\"Cerveza por Unidad Verano\\\",\\\"cantidad\\\":\\\"1\\\",\\\"precio\\\":2,\\\"subtotal\\\":2,\\\"area_id\\\":\\\"3\\\",\\\"printed_at\\\":\\\"2025-05-21 19:14:48\\\"},{\\\"producto_id\\\":\\\"257\\\",\\\"nombre\\\":\\\"Raci\\\\u00f3n de Queso Telita\\\",\\\"cantidad\\\":\\\"1\\\",\\\"precio\\\":6,\\\"subtotal\\\":6,\\\"area_id\\\":\\\"2\\\",\\\"printed_at\\\":\\\"2025-05-21 19:17:04\\\"},{\\\"producto_id\\\":\\\"290\\\",\\\"nombre\\\":\\\"Batido Patilla\\\",\\\"cantidad\\\":\\\"1\\\",\\\"precio\\\":4,\\\"subtotal\\\":4,\\\"area_id\\\":\\\"3\\\",\\\"printed_at\\\":\\\"2025-05-21 19:19:50\\\"},{\\\"producto_id\\\":\\\"311\\\",\\\"nombre\\\":\\\"Batido Berry (Mora y Fresa)\\\",\\\"cantidad\\\":\\\"1\\\",\\\"precio\\\":5,\\\"subtotal\\\":5,\\\"area_id\\\":\\\"3\\\",\\\"printed_at\\\":\\\"2025-05-21 19:22:07\\\"},{\\\"producto_id\\\":\\\"254\\\",\\\"nombre\\\":\\\"Cachapa C\\\\\\/ Cochino\\\",\\\"cantidad\\\":\\\"1\\\",\\\"precio\\\":14,\\\"subtotal\\\":14,\\\"area_id\\\":2,\\\"printed_at\\\":\\\"2025-05-21 19:27:25\\\"}]\"', 39.50, 117.00, 'Churuata', '\"[{\\\"metodo\\\":\\\"divisas\\\",\\\"monto\\\":\\\"0.00\\\",\\\"referencia\\\":null,\\\"cuenta\\\":null,\\\"banco\\\":\\\"venezuela\\\",\\\"autorizado_por\\\":null}]\"', '2025-05-21 22:23:00', NULL, '2025-05-21 22:23:23', '2025-05-21 23:27:25', 0, 0, NULL, 0.00, 0.00),
(142, 'Leonel Ramos', 'Thor', NULL, 'Prueba de Comandera de 80mm #15', 1, NULL, '\"[{\\\"producto_id\\\":\\\"297\\\",\\\"nombre\\\":\\\"1\\\\\\/2 Batido de Fresa\\\",\\\"cantidad\\\":\\\"1\\\",\\\"precio\\\":2,\\\"subtotal\\\":2,\\\"area_id\\\":\\\"3\\\",\\\"printed_at\\\":\\\"2025-05-21 19:43:25\\\"}]\"', 2.00, 117.00, 'Churuata', NULL, '2025-05-21 23:41:00', NULL, '2025-05-21 23:42:09', '2025-05-21 23:43:25', 0, 0, NULL, 0.00, 0.00),
(143, 'Leonel', 'Batman', NULL, 'Prueba de Comandera de 80mm #25', 1, NULL, '\"[{\\\"producto_id\\\":\\\"297\\\",\\\"cantidad\\\":\\\"1\\\",\\\"precio\\\":2,\\\"subtotal\\\":2,\\\"area_id\\\":\\\"3\\\"}]\"', 2.00, 1.00, 'Churuata', '\"[]\"', '2025-05-21 23:44:00', NULL, '2025-05-21 23:44:51', '2025-05-21 23:44:51', 0, 0, NULL, 0.00, 0.00),
(144, 'Leonel', 'Thor', NULL, 'Prueba de Comandera de 80mm #3', 1, NULL, '\"[{\\\"producto_id\\\":\\\"299\\\",\\\"nombre\\\":\\\"1\\\\\\/2 Batido de Durazno\\\",\\\"cantidad\\\":\\\"1\\\",\\\"precio\\\":2,\\\"subtotal\\\":2,\\\"area_id\\\":\\\"3\\\",\\\"printed_at\\\":\\\"2025-05-21 19:54:44\\\"}]\"', 2.00, 117.00, 'Churuata', NULL, '2025-05-21 23:52:00', NULL, '2025-05-21 23:52:12', '2025-05-21 23:54:44', 0, 0, NULL, 0.00, 0.00),
(145, 'Leonel Ramos', 'Thor', NULL, 'Prueba de roles #7', 1, NULL, '\"[{\\\"producto_id\\\":\\\"297\\\",\\\"nombre\\\":\\\"1\\\\\\/2 Batido de Fresa\\\",\\\"cantidad\\\":\\\"1\\\",\\\"precio\\\":2,\\\"subtotal\\\":2,\\\"area_id\\\":\\\"3\\\",\\\"printed_at\\\":\\\"2025-05-21 19:56:43\\\"}]\"', 2.00, 117.00, 'Salón Principal', NULL, '2025-05-21 23:56:00', NULL, '2025-05-21 23:56:26', '2025-05-21 23:56:43', 0, 0, NULL, 0.00, 0.00),
(146, 'dgdfgdf', 'dfgdf', NULL, 'dfdfg', 1, NULL, '\"[{\\\"producto_id\\\":\\\"297\\\",\\\"nombre\\\":\\\"1\\\\\\/2 Batido de Fresa\\\",\\\"cantidad\\\":\\\"1\\\",\\\"precio\\\":2,\\\"subtotal\\\":2,\\\"area_id\\\":\\\"3\\\",\\\"printed_at\\\":\\\"2025-05-21 20:18:17\\\"},{\\\"producto_id\\\":\\\"313\\\",\\\"nombre\\\":\\\"1\\\\\\/2 Batido Rez\\\\u00e1gate (Mango, Pi\\\\u00f1a y Maracuya)\\\",\\\"cantidad\\\":\\\"1\\\",\\\"precio\\\":2.5,\\\"subtotal\\\":2.5,\\\"area_id\\\":\\\"3\\\",\\\"printed_at\\\":\\\"2025-05-21 20:38:34\\\"}]\"', 4.50, 117.00, 'Churuata', NULL, '2025-05-22 00:17:00', NULL, '2025-05-22 00:17:55', '2025-05-22 00:39:27', 0, 0, NULL, 0.00, 0.00),
(147, 'dgdfgdf', 'dfgdf', NULL, 'dfdfg', 1, NULL, '\"[{\\\"producto_id\\\":\\\"303\\\",\\\"nombre\\\":\\\"1\\\\\\/2 Batido de Guanabana\\\",\\\"cantidad\\\":\\\"1\\\",\\\"precio\\\":2,\\\"subtotal\\\":2,\\\"area_id\\\":\\\"3\\\",\\\"printed_at\\\":\\\"2025-05-21 20:40:25\\\"},{\\\"producto_id\\\":\\\"299\\\",\\\"nombre\\\":\\\"1\\\\\\/2 Batido de Durazno\\\",\\\"cantidad\\\":\\\"1\\\",\\\"precio\\\":2,\\\"subtotal\\\":2,\\\"area_id\\\":\\\"3\\\",\\\"printed_at\\\":\\\"2025-05-21 20:42:41\\\"},{\\\"producto_id\\\":\\\"222\\\",\\\"nombre\\\":\\\"1\\\\\\/4Kg de Carne Importada\\\",\\\"cantidad\\\":\\\"1\\\",\\\"precio\\\":12,\\\"subtotal\\\":12,\\\"area_id\\\":\\\"1\\\",\\\"printed_at\\\":\\\"2025-05-21 20:44:35\\\"},{\\\"producto_id\\\":\\\"254\\\",\\\"nombre\\\":\\\"Cachapa C\\\\\\/ Cochino\\\",\\\"cantidad\\\":\\\"1\\\",\\\"precio\\\":14,\\\"subtotal\\\":14,\\\"area_id\\\":\\\"2\\\",\\\"printed_at\\\":\\\"2025-05-21 23:00:30\\\"},{\\\"producto_id\\\":\\\"281\\\",\\\"nombre\\\":\\\"Cerveza por Unidad Breeze Ice\\\",\\\"cantidad\\\":\\\"1\\\",\\\"precio\\\":3,\\\"subtotal\\\":3,\\\"area_id\\\":3,\\\"printed_at\\\":\\\"2025-05-22 00:00:08\\\"}]\"', 33.00, 117.00, 'Churuata', NULL, '2025-05-22 00:39:00', NULL, '2025-05-22 00:39:58', '2025-05-22 04:00:08', 0, 0, NULL, 0.00, 0.00),
(148, 'Leonel Ramos', 'Batman', NULL, 'Prueba de Comander #1', 1, NULL, '\"[{\\\"producto_id\\\":\\\"303\\\",\\\"nombre\\\":\\\"1\\\\\\/2 Batido de Guanabana\\\",\\\"cantidad\\\":\\\"1\\\",\\\"precio\\\":2,\\\"subtotal\\\":2,\\\"area_id\\\":\\\"3\\\",\\\"printed_at\\\":\\\"2025-05-21 20:58:16\\\"},{\\\"producto_id\\\":\\\"314\\\",\\\"nombre\\\":\\\"1\\\\\\/2 Batido Happy (Guanabana y Parchita)\\\",\\\"cantidad\\\":\\\"1\\\",\\\"precio\\\":2.5,\\\"subtotal\\\":2.5,\\\"area_id\\\":\\\"3\\\",\\\"printed_at\\\":\\\"2025-05-21 20:59:21\\\"},{\\\"producto_id\\\":\\\"297\\\",\\\"nombre\\\":\\\"1\\\\\\/2 Batido de Fresa\\\",\\\"cantidad\\\":\\\"1\\\",\\\"precio\\\":2,\\\"subtotal\\\":2,\\\"area_id\\\":\\\"3\\\",\\\"printed_at\\\":\\\"2025-05-21 21:07:07\\\"},{\\\"producto_id\\\":\\\"300\\\",\\\"nombre\\\":\\\"1\\\\\\/2 Batido de Lechoza\\\",\\\"cantidad\\\":\\\"1\\\",\\\"precio\\\":2,\\\"subtotal\\\":2,\\\"area_id\\\":\\\"3\\\",\\\"printed_at\\\":\\\"2025-05-21 21:07:42\\\"},{\\\"producto_id\\\":\\\"300\\\",\\\"nombre\\\":\\\"1\\\\\\/2 Batido de Lechoza\\\",\\\"cantidad\\\":\\\"1\\\",\\\"precio\\\":2,\\\"subtotal\\\":2,\\\"area_id\\\":\\\"3\\\",\\\"printed_at\\\":\\\"2025-05-21 21:07:42\\\"},{\\\"producto_id\\\":\\\"277\\\",\\\"nombre\\\":\\\"Cerveza por Unidad Polar\\\",\\\"cantidad\\\":\\\"1\\\",\\\"precio\\\":1,\\\"subtotal\\\":1,\\\"area_id\\\":\\\"3\\\",\\\"printed_at\\\":\\\"2025-05-21 22:44:50\\\"},{\\\"producto_id\\\":\\\"293\\\",\\\"nombre\\\":\\\"Batido Pi\\\\u00f1a\\\",\\\"cantidad\\\":\\\"1\\\",\\\"precio\\\":4,\\\"subtotal\\\":4,\\\"area_id\\\":\\\"3\\\",\\\"printed_at\\\":\\\"2025-05-22 00:03:47\\\"},{\\\"producto_id\\\":\\\"297\\\",\\\"nombre\\\":\\\"1\\\\\\/2 Batido de Fresa\\\",\\\"cantidad\\\":\\\"1\\\",\\\"precio\\\":2,\\\"subtotal\\\":2,\\\"area_id\\\":3,\\\"printed_at\\\":\\\"2025-05-21 21:07:07\\\"}]\"', 17.50, 117.00, 'Churuata', NULL, '2025-05-22 00:57:00', NULL, '2025-05-22 00:57:56', '2025-05-22 04:13:11', 0, 0, NULL, 0.00, 0.00),
(149, 'dgdfgdf', 'dfgdf', NULL, 'dfdfg', 1, NULL, '\"[{\\\"producto_id\\\":\\\"297\\\",\\\"nombre\\\":\\\"1\\\\\\/2 Batido de Fresa\\\",\\\"cantidad\\\":\\\"1\\\",\\\"precio\\\":2,\\\"subtotal\\\":2,\\\"area_id\\\":\\\"3\\\",\\\"printed_at\\\":\\\"2025-05-22 00:00:53\\\"},{\\\"producto_id\\\":\\\"303\\\",\\\"nombre\\\":\\\"1\\\\\\/2 Batido de Guanabana\\\",\\\"cantidad\\\":\\\"1\\\",\\\"precio\\\":2,\\\"subtotal\\\":2,\\\"area_id\\\":3,\\\"printed_at\\\":\\\"2025-05-22 00:02:40\\\"}]\"', 4.00, 200.00, 'Churuata', NULL, '2025-05-22 04:00:00', NULL, '2025-05-22 04:00:36', '2025-05-22 04:02:40', 0, 0, NULL, 0.00, 0.00),
(150, 'Leonel', 'Batman', NULL, 'Prueba de roles #7', 1, NULL, '\"[{\\\"producto_id\\\":\\\"303\\\",\\\"nombre\\\":\\\"1\\\\\\/2 Batido de Guanabana\\\",\\\"cantidad\\\":\\\"9\\\",\\\"precio\\\":2,\\\"subtotal\\\":18,\\\"area_id\\\":\\\"3\\\",\\\"printed_at\\\":\\\"2025-05-22 01:47:58\\\"},{\\\"producto_id\\\":\\\"316\\\",\\\"nombre\\\":\\\"1\\\\\\/2 Batido Berry (Mora y Fresa)\\\",\\\"cantidad\\\":\\\"1\\\",\\\"precio\\\":2.5,\\\"subtotal\\\":2.5,\\\"area_id\\\":\\\"3\\\",\\\"printed_at\\\":null},{\\\"producto_id\\\":\\\"316\\\",\\\"nombre\\\":\\\"1\\\\\\/2 Batido Berry (Mora y Fresa)\\\",\\\"cantidad\\\":\\\"1\\\",\\\"precio\\\":2.5,\\\"subtotal\\\":2.5,\\\"area_id\\\":\\\"3\\\",\\\"printed_at\\\":null},{\\\"producto_id\\\":\\\"316\\\",\\\"nombre\\\":\\\"1\\\\\\/2 Batido Berry (Mora y Fresa)\\\",\\\"cantidad\\\":\\\"1\\\",\\\"precio\\\":2.5,\\\"subtotal\\\":2.5,\\\"area_id\\\":\\\"3\\\",\\\"printed_at\\\":null},{\\\"producto_id\\\":\\\"316\\\",\\\"nombre\\\":\\\"1\\\\\\/2 Batido Berry (Mora y Fresa)\\\",\\\"cantidad\\\":\\\"1\\\",\\\"precio\\\":2.5,\\\"subtotal\\\":2.5,\\\"area_id\\\":\\\"3\\\",\\\"printed_at\\\":null}]\"', 28.00, 1.00, 'Churuata', '\"[{\\\"metodo\\\":\\\"divisas\\\",\\\"monto\\\":\\\"2.00\\\",\\\"referencia\\\":null,\\\"cuenta\\\":null,\\\"banco\\\":\\\"venezuela\\\",\\\"autorizado_por\\\":null},{\\\"metodo\\\":\\\"pago_movil\\\",\\\"monto\\\":\\\"0.00\\\",\\\"referencia\\\":null,\\\"cuenta\\\":\\\"esmerley_venezuela\\\",\\\"banco\\\":\\\"venezuela\\\",\\\"autorizado_por\\\":null}]\"', '2025-05-22 05:27:00', NULL, '2025-05-22 05:27:41', '2025-05-22 05:52:15', 0, 0, NULL, 0.00, 0.00),
(151, 'Leonel Ramos', 'Thor', NULL, 'Prueba de Comander #1', 1, NULL, '\"[{\\\"producto_id\\\":\\\"303\\\",\\\"nombre\\\":\\\"1\\\\\\/2 Batido de Guanabana\\\",\\\"cantidad\\\":\\\"1\\\",\\\"precio\\\":2,\\\"subtotal\\\":2,\\\"area_id\\\":\\\"3\\\",\\\"printed_at\\\":\\\"2025-05-22 01:58:12\\\"},{\\\"producto_id\\\":\\\"299\\\",\\\"nombre\\\":\\\"1\\\\\\/2 Batido de Durazno\\\",\\\"cantidad\\\":\\\"1\\\",\\\"precio\\\":2,\\\"subtotal\\\":2,\\\"area_id\\\":\\\"3\\\",\\\"printed_at\\\":\\\"2025-05-22 01:59:30\\\"},{\\\"producto_id\\\":\\\"303\\\",\\\"nombre\\\":\\\"1\\\\\\/2 Batido de Guanabana\\\",\\\"cantidad\\\":\\\"1\\\",\\\"precio\\\":2,\\\"subtotal\\\":2,\\\"area_id\\\":\\\"3\\\",\\\"printed_at\\\":\\\"2025-05-22 01:58:12\\\"},{\\\"producto_id\\\":\\\"299\\\",\\\"nombre\\\":\\\"1\\\\\\/2 Batido de Durazno\\\",\\\"cantidad\\\":\\\"1\\\",\\\"precio\\\":2,\\\"subtotal\\\":2,\\\"area_id\\\":3,\\\"printed_at\\\":\\\"2025-05-22 01:59:30\\\"}]\"', 8.00, 117.00, 'Churuata', NULL, '2025-05-22 05:57:00', NULL, '2025-05-22 05:57:45', '2025-05-22 05:59:47', 0, 0, NULL, 0.00, 0.00),
(152, 'Leonel Ramos', 'Thor', NULL, 'Prueba de Comander #1', 1, NULL, '\"[{\\\"producto_id\\\":\\\"303\\\",\\\"nombre\\\":\\\"1\\\\\\/2 Batido de Guanabana\\\",\\\"cantidad\\\":\\\"1\\\",\\\"precio\\\":2,\\\"subtotal\\\":2,\\\"area_id\\\":\\\"3\\\",\\\"printed_at\\\":\\\"2025-05-22 02:20:37\\\"},{\\\"producto_id\\\":\\\"299\\\",\\\"nombre\\\":\\\"1\\\\\\/2 Batido de Durazno\\\",\\\"cantidad\\\":\\\"1\\\",\\\"precio\\\":2,\\\"subtotal\\\":2,\\\"area_id\\\":\\\"3\\\",\\\"printed_at\\\":\\\"2025-05-22 02:21:47\\\"},{\\\"producto_id\\\":\\\"299\\\",\\\"nombre\\\":\\\"1\\\\\\/2 Batido de Durazno\\\",\\\"cantidad\\\":\\\"1\\\",\\\"precio\\\":2,\\\"subtotal\\\":2,\\\"area_id\\\":\\\"3\\\",\\\"printed_at\\\":\\\"2025-05-22 02:21:47\\\"}]\"', 6.00, 1.00, 'Churuata', NULL, '2025-05-22 06:10:00', NULL, '2025-05-22 06:10:30', '2025-05-22 06:21:50', 0, 0, NULL, 0.00, 0.00),
(153, 'Leonel', 'Batman', NULL, 'Prueba de Comander #26', 1, NULL, '\"[{\\\"id_unico\\\":\\\"0\\\",\\\"producto_id\\\":\\\"303\\\",\\\"nombre\\\":\\\"1\\\\\\/2 Batido de Guanabana\\\",\\\"cantidad\\\":\\\"1\\\",\\\"precio\\\":2,\\\"subtotal\\\":2,\\\"area_id\\\":\\\"3\\\",\\\"printed_at\\\":\\\"2025-05-22 02:34:27\\\"},{\\\"id_unico\\\":\\\"1\\\",\\\"producto_id\\\":\\\"297\\\",\\\"nombre\\\":\\\"1\\\\\\/2 Batido de Fresa\\\",\\\"cantidad\\\":\\\"1\\\",\\\"precio\\\":2,\\\"subtotal\\\":2,\\\"area_id\\\":\\\"3\\\",\\\"printed_at\\\":\\\"2025-05-22 02:37:52\\\"},{\\\"id_unico\\\":\\\"2\\\",\\\"producto_id\\\":\\\"256\\\",\\\"nombre\\\":\\\"1\\\\\\/2 Cachapa C\\\\\\/ Queso Telita\\\",\\\"cantidad\\\":\\\"1\\\",\\\"precio\\\":5,\\\"subtotal\\\":5,\\\"area_id\\\":\\\"2\\\",\\\"printed_at\\\":\\\"2025-05-22 02:38:03\\\"},{\\\"id_unico\\\":\\\"prod-nuevo-1747907066744-244\\\",\\\"producto_id\\\":\\\"300\\\",\\\"nombre\\\":\\\"1\\\\\\/2 Batido de Lechoza\\\",\\\"cantidad\\\":\\\"1\\\",\\\"precio\\\":2,\\\"subtotal\\\":2,\\\"area_id\\\":\\\"3\\\",\\\"printed_at\\\":\\\"impreso\\\"},{\\\"id_unico\\\":\\\"prod-nuevo-1747907130616-81\\\",\\\"producto_id\\\":\\\"303\\\",\\\"nombre\\\":\\\"1\\\\\\/2 Batido de Guanabana\\\",\\\"cantidad\\\":\\\"1\\\",\\\"precio\\\":2,\\\"subtotal\\\":2,\\\"area_id\\\":\\\"3\\\",\\\"printed_at\\\":\\\"impreso\\\"},{\\\"id_unico\\\":\\\"prod-nuevo-1747907246927-539\\\",\\\"producto_id\\\":\\\"301\\\",\\\"nombre\\\":\\\"1\\\\\\/2 Batido de Parchita\\\",\\\"cantidad\\\":\\\"1\\\",\\\"precio\\\":2,\\\"subtotal\\\":2,\\\"area_id\\\":\\\"3\\\",\\\"printed_at\\\":\\\"2025-05-22 05:47:40\\\"},{\\\"id_unico\\\":\\\"prod-nuevo-1747912241094-396\\\",\\\"producto_id\\\":\\\"300\\\",\\\"nombre\\\":\\\"1\\\\\\/2 Batido de Lechoza\\\",\\\"cantidad\\\":\\\"1\\\",\\\"precio\\\":2,\\\"subtotal\\\":2,\\\"area_id\\\":\\\"3\\\",\\\"printed_at\\\":\\\"2025-05-22 07:10:49\\\"},{\\\"id_unico\\\":\\\"prod-nuevo-1747914501381-912\\\",\\\"producto_id\\\":\\\"297\\\",\\\"nombre\\\":\\\"1\\\\\\/2 Batido de Fresa\\\",\\\"cantidad\\\":\\\"1\\\",\\\"precio\\\":2,\\\"subtotal\\\":2,\\\"area_id\\\":\\\"3\\\",\\\"printed_at\\\":\\\"impreso\\\"},{\\\"id_unico\\\":\\\"prod-nuevo-1747914563284-505\\\",\\\"producto_id\\\":\\\"300\\\",\\\"nombre\\\":\\\"1\\\\\\/2 Batido de Lechoza\\\",\\\"cantidad\\\":\\\"1\\\",\\\"precio\\\":2,\\\"subtotal\\\":2,\\\"area_id\\\":\\\"3\\\",\\\"printed_at\\\":\\\"2025-05-22 07:49:30\\\"},{\\\"id_unico\\\":\\\"prod-nuevo-1747914581636-181\\\",\\\"producto_id\\\":\\\"301\\\",\\\"nombre\\\":\\\"1\\\\\\/2 Batido de Parchita\\\",\\\"cantidad\\\":\\\"1\\\",\\\"precio\\\":2,\\\"subtotal\\\":2,\\\"area_id\\\":\\\"3\\\",\\\"printed_at\\\":\\\"2025-05-22 07:49:52\\\"},{\\\"id_unico\\\":\\\"prod-nuevo-1747914624077-108\\\",\\\"producto_id\\\":\\\"307\\\",\\\"nombre\\\":\\\"Batido Potencia (Mora, Fresa y Tomate de \\\\u00c1rbol)\\\",\\\"cantidad\\\":\\\"1\\\",\\\"precio\\\":5,\\\"subtotal\\\":5,\\\"area_id\\\":\\\"3\\\",\\\"printed_at\\\":\\\"impreso\\\"},{\\\"id_unico\\\":\\\"prod-nuevo-1747914700239-133\\\",\\\"producto_id\\\":\\\"297\\\",\\\"nombre\\\":\\\"1\\\\\\/2 Batido de Fresa\\\",\\\"cantidad\\\":\\\"1\\\",\\\"precio\\\":2,\\\"subtotal\\\":2,\\\"area_id\\\":\\\"3\\\",\\\"printed_at\\\":\\\"impreso\\\"},{\\\"id_unico\\\":\\\"prod-nuevo-1747914734701-957\\\",\\\"producto_id\\\":\\\"300\\\",\\\"nombre\\\":\\\"1\\\\\\/2 Batido de Lechoza\\\",\\\"cantidad\\\":\\\"1\\\",\\\"precio\\\":2,\\\"subtotal\\\":2,\\\"area_id\\\":\\\"3\\\",\\\"printed_at\\\":\\\"impreso\\\"},{\\\"id_unico\\\":\\\"prod-nuevo-1747914821117-199\\\",\\\"producto_id\\\":\\\"300\\\",\\\"nombre\\\":\\\"1\\\\\\/2 Batido de Lechoza\\\",\\\"cantidad\\\":\\\"1\\\",\\\"precio\\\":2,\\\"subtotal\\\":2,\\\"area_id\\\":\\\"3\\\",\\\"printed_at\\\":\\\"impreso\\\"},{\\\"id_unico\\\":\\\"prod-nuevo-1747914905452-328\\\",\\\"producto_id\\\":\\\"297\\\",\\\"nombre\\\":\\\"1\\\\\\/2 Batido de Fresa\\\",\\\"cantidad\\\":\\\"1\\\",\\\"precio\\\":2,\\\"subtotal\\\":2,\\\"area_id\\\":3,\\\"printed_at\\\":\\\"2025-05-22 07:55:14\\\"}]\"', 36.00, 117.00, 'Churuata', '\"[{\\\"metodo\\\":\\\"divisas\\\",\\\"monto\\\":\\\"15.00\\\",\\\"referencia\\\":null,\\\"cuenta\\\":null,\\\"banco\\\":\\\"venezuela\\\",\\\"autorizado_por\\\":null}]\"', '2025-05-22 06:34:00', NULL, '2025-05-22 06:34:15', '2025-05-22 11:55:14', 0, 0, NULL, 0.00, 0.00);
INSERT INTO `cuentas` (`id`, `responsable_pedido`, `barrero`, `cliente_id`, `cliente_nombre`, `usuario_id`, `cajera`, `productos`, `total_estimado`, `tasa_dia`, `estacion`, `metodos_pago`, `fecha_apertura`, `fecha_cierre`, `created_at`, `updated_at`, `pagada`, `pendiente`, `cliente_nombre_manual`, `total_pagado`, `vuelto`) VALUES
(154, 'Leonel Ramos', 'Thor', NULL, 'Prueba de roles #7', 1, NULL, '\"[{\\\"id_unico\\\":\\\"0\\\",\\\"producto_id\\\":\\\"303\\\",\\\"nombre\\\":\\\"1\\\\\\/2 Batido de Guanabana\\\",\\\"cantidad\\\":\\\"1\\\",\\\"precio\\\":2,\\\"subtotal\\\":2,\\\"area_id\\\":\\\"3\\\",\\\"printed_at\\\":\\\"2025-05-22 02:38:45\\\"},{\\\"id_unico\\\":\\\"1\\\",\\\"producto_id\\\":\\\"303\\\",\\\"nombre\\\":\\\"1\\\\\\/2 Batido de Guanabana\\\",\\\"cantidad\\\":\\\"1\\\",\\\"precio\\\":2,\\\"subtotal\\\":2,\\\"area_id\\\":\\\"3\\\",\\\"printed_at\\\":\\\"2025-05-22 02:38:45\\\"},{\\\"id_unico\\\":\\\"2\\\",\\\"producto_id\\\":\\\"303\\\",\\\"nombre\\\":\\\"1\\\\\\/2 Batido de Guanabana\\\",\\\"cantidad\\\":\\\"1\\\",\\\"precio\\\":2,\\\"subtotal\\\":2,\\\"area_id\\\":\\\"3\\\",\\\"printed_at\\\":\\\"2025-05-22 03:04:24\\\"},{\\\"id_unico\\\":\\\"prod-nuevo-1747899728283-25\\\",\\\"producto_id\\\":\\\"299\\\",\\\"nombre\\\":\\\"1\\\\\\/2 Batido de Durazno\\\",\\\"cantidad\\\":\\\"1\\\",\\\"precio\\\":2,\\\"subtotal\\\":2,\\\"area_id\\\":\\\"3\\\",\\\"printed_at\\\":\\\"impreso\\\"},{\\\"id_unico\\\":\\\"prod-nuevo-1747899746562-892\\\",\\\"producto_id\\\":\\\"299\\\",\\\"nombre\\\":\\\"1\\\\\\/2 Batido de Durazno\\\",\\\"cantidad\\\":\\\"1\\\",\\\"precio\\\":2,\\\"subtotal\\\":2,\\\"area_id\\\":\\\"3\\\",\\\"printed_at\\\":\\\"impreso\\\"},{\\\"id_unico\\\":\\\"prod-nuevo-1747902894592-18\\\",\\\"producto_id\\\":\\\"300\\\",\\\"nombre\\\":\\\"1\\\\\\/2 Batido de Lechoza\\\",\\\"cantidad\\\":\\\"1\\\",\\\"precio\\\":2,\\\"subtotal\\\":2,\\\"area_id\\\":\\\"3\\\",\\\"printed_at\\\":\\\"impreso\\\"},{\\\"id_unico\\\":\\\"prod-nuevo-1747904750402-693\\\",\\\"producto_id\\\":\\\"256\\\",\\\"nombre\\\":\\\"1\\\\\\/2 Cachapa C\\\\\\/ Queso Telita\\\",\\\"cantidad\\\":\\\"1\\\",\\\"precio\\\":5,\\\"subtotal\\\":5,\\\"area_id\\\":\\\"2\\\",\\\"printed_at\\\":\\\"2025-05-22 05:06:02\\\"},{\\\"id_unico\\\":\\\"prod-nuevo-1747905408249-195\\\",\\\"producto_id\\\":\\\"303\\\",\\\"nombre\\\":\\\"1\\\\\\/2 Batido de Guanabana\\\",\\\"cantidad\\\":\\\"1\\\",\\\"precio\\\":2,\\\"subtotal\\\":2,\\\"area_id\\\":\\\"3\\\",\\\"printed_at\\\":\\\"2025-05-22 05:17:06\\\"},{\\\"id_unico\\\":\\\"prod-nuevo-1747905747849-239\\\",\\\"producto_id\\\":\\\"314\\\",\\\"nombre\\\":\\\"1\\\\\\/2 Batido Happy (Guanabana y Parchita)\\\",\\\"cantidad\\\":\\\"1\\\",\\\"precio\\\":2.5,\\\"subtotal\\\":2.5,\\\"area_id\\\":\\\"3\\\",\\\"printed_at\\\":\\\"2025-05-22 05:22:39\\\"},{\\\"id_unico\\\":\\\"prod-nuevo-1747906100521-205\\\",\\\"producto_id\\\":\\\"312\\\",\\\"nombre\\\":\\\"1\\\\\\/2 Batido Potencia (Mora, Fresa y Tomate de \\\\u00c1rbol)\\\",\\\"cantidad\\\":\\\"1\\\",\\\"precio\\\":2.5,\\\"subtotal\\\":2.5,\\\"area_id\\\":\\\"3\\\",\\\"printed_at\\\":\\\"impreso\\\"},{\\\"id_unico\\\":\\\"prod-nuevo-1747906756840-124\\\",\\\"producto_id\\\":\\\"330\\\",\\\"nombre\\\":\\\"1\\\\\\/2 Merengada de Guanabana\\\",\\\"cantidad\\\":\\\"1\\\",\\\"precio\\\":2.5,\\\"subtotal\\\":2.5,\\\"area_id\\\":\\\"3\\\",\\\"printed_at\\\":\\\"impreso\\\"}]\"', 26.50, 117.00, 'Churuata', NULL, '2025-05-22 06:38:00', NULL, '2025-05-22 06:38:32', '2025-05-22 09:44:22', 0, 0, NULL, 0.00, 0.00),
(155, 'Leonel Ramos', 'Thor', NULL, 'Prueba de Comander #1', 1, NULL, '\"[{\\\"id_unico\\\":\\\"0\\\",\\\"producto_id\\\":\\\"297\\\",\\\"nombre\\\":\\\"1\\\\\\/2 Batido de Fresa\\\",\\\"cantidad\\\":\\\"1\\\",\\\"precio\\\":2,\\\"subtotal\\\":2,\\\"area_id\\\":\\\"3\\\",\\\"printed_at\\\":\\\"impreso\\\"},{\\\"id_unico\\\":\\\"1\\\",\\\"producto_id\\\":\\\"299\\\",\\\"nombre\\\":\\\"1\\\\\\/2 Batido de Durazno\\\",\\\"cantidad\\\":\\\"1\\\",\\\"precio\\\":2,\\\"subtotal\\\":2,\\\"area_id\\\":\\\"3\\\",\\\"printed_at\\\":\\\"impreso\\\"},{\\\"id_unico\\\":\\\"prod-nuevo-1747897571027-16\\\",\\\"producto_id\\\":\\\"299\\\",\\\"nombre\\\":\\\"1\\\\\\/2 Batido de Durazno\\\",\\\"cantidad\\\":\\\"1\\\",\\\"precio\\\":2,\\\"subtotal\\\":2,\\\"area_id\\\":\\\"3\\\",\\\"printed_at\\\":\\\"impreso\\\"}]\"', 6.00, 117.00, 'Churuata', '\"[{\\\"metodo\\\":\\\"divisas\\\",\\\"monto\\\":\\\"6.00\\\",\\\"referencia\\\":null,\\\"cuenta\\\":\\\"seleccionar_cuenta\\\",\\\"banco\\\":\\\"venezuela\\\",\\\"autorizado_por\\\":null}]\"', '2025-05-22 07:04:00', '2025-05-22 07:33:01', '2025-05-22 07:04:47', '2025-05-22 07:33:01', 1, 0, NULL, 0.00, 0.00),
(156, 'Leonel Ramos', 'Batman', NULL, 'Prueba de roles #7', 1, NULL, '\"[{\\\"id_unico\\\":\\\"prod-682ed5cd369eb\\\",\\\"producto_id\\\":\\\"303\\\",\\\"nombre\\\":\\\"1\\\\\\/2 Batido de Guanabana\\\",\\\"cantidad\\\":\\\"1\\\",\\\"precio\\\":2,\\\"subtotal\\\":2,\\\"area_id\\\":\\\"3\\\",\\\"printed_at\\\":\\\"impreso\\\"}]\"', 2.00, 117.00, 'Seleccionar Estación', '\"[{\\\"metodo\\\":\\\"divisas\\\",\\\"monto\\\":\\\"2.00\\\",\\\"referencia\\\":null,\\\"cuenta\\\":\\\"seleccionar_cuenta\\\",\\\"banco\\\":\\\"venezuela\\\",\\\"autorizado_por\\\":null}]\"', '2025-05-22 07:44:00', '2025-05-22 07:47:33', '2025-05-22 07:44:13', '2025-05-22 07:47:33', 1, 0, NULL, 0.00, 0.00),
(157, 'Leonel', 'Batman', NULL, 'Prueba de roles #7', 1, NULL, '\"[{\\\"id_unico\\\":\\\"prod-682f5bb1b0e6c\\\",\\\"producto_id\\\":\\\"303\\\",\\\"cantidad\\\":\\\"1\\\",\\\"precio\\\":2,\\\"subtotal\\\":2,\\\"area_id\\\":\\\"3\\\"}]\"', 2.00, 1.00, 'Churuata', '\"[]\"', '2025-05-22 17:15:00', NULL, '2025-05-22 17:15:29', '2025-05-22 17:15:29', 0, 0, NULL, 0.00, 0.00);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cuenta_producto`
--
-- Creación: 05-05-2025 a las 19:10:53
--

CREATE TABLE `cuenta_producto` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `cuenta_id` bigint(20) UNSIGNED NOT NULL,
  `producto_id` bigint(20) UNSIGNED NOT NULL,
  `cantidad` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalle_cuentas`
--
-- Creación: 17-05-2025 a las 00:35:51
--

CREATE TABLE `detalle_cuentas` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `cuenta_id` bigint(20) UNSIGNED NOT NULL,
  `producto_id` bigint(20) UNSIGNED NOT NULL,
  `area_id` bigint(20) UNSIGNED DEFAULT NULL,
  `printed_at` timestamp NULL DEFAULT NULL,
  `cantidad` int(11) NOT NULL DEFAULT 1,
  `precio_unitario` decimal(10,2) NOT NULL,
  `subtotal` decimal(10,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `failed_jobs`
--
-- Creación: 05-05-2025 a las 19:10:40
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `inventarios`
--
-- Creación: 05-05-2025 a las 19:10:47
--

CREATE TABLE `inventarios` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `producto_id` bigint(20) UNSIGNED NOT NULL,
  `cantidad_inicial` int(11) NOT NULL DEFAULT 0,
  `cantidad_actual` int(11) NOT NULL DEFAULT 0,
  `precio_costo` decimal(10,2) NOT NULL DEFAULT 0.00,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `inventario_productos`
--
-- Creación: 20-05-2025 a las 04:58:52
--

CREATE TABLE `inventario_productos` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `producto_id` bigint(20) UNSIGNED DEFAULT NULL,
  `nombre` varchar(255) NOT NULL,
  `codigo` varchar(255) NOT NULL,
  `cantidad_inicial` int(11) NOT NULL DEFAULT 0,
  `cantidad_actual` int(11) NOT NULL DEFAULT 0,
  `precio_costo` decimal(10,2) NOT NULL DEFAULT 0.00,
  `unidad_medida` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `inventario_productos`
--

INSERT INTO `inventario_productos` (`id`, `producto_id`, `nombre`, `codigo`, `cantidad_inicial`, `cantidad_actual`, `precio_costo`, `unidad_medida`, `created_at`, `updated_at`) VALUES
(5, NULL, 'CARNE DE RES', '#CAR-001', 0, 0, 0.00, 'Kg', '2025-05-19 04:15:46', '2025-05-19 04:15:46'),
(7, NULL, 'Café', 'CAF-001', 0, 0, 0.00, 'Kg', '2025-05-19 04:23:25', '2025-05-19 04:23:25'),
(8, NULL, 'Cebolla', '#CEB-001', 0, 0, 0.00, 'Kg', '2025-05-19 05:44:52', '2025-05-19 05:44:52'),
(10, NULL, 'yatusabe', 'YAT-001', 0, 0, 0.00, 'Kg', '2025-05-19 20:39:27', '2025-05-19 20:39:27'),
(15, NULL, 'TOMATE', '#TOM-001', 0, 0, 0.00, 'Kg', '2025-05-19 21:54:08', '2025-05-20 11:23:30'),
(16, NULL, 'CERVEZA', '#CER-001', 0, 0, 0.00, 'Lt', '2025-05-20 04:14:31', '2025-05-20 04:14:31'),
(17, NULL, 'Fresa', '#FRE-001', 0, 0, 0.00, 'Kg', '2025-05-20 04:28:06', '2025-05-20 04:28:06'),
(18, NULL, 'GUANABANA', '#GUA-001', 0, 0, 0.00, 'Kg', '2025-05-20 04:45:48', '2025-05-20 04:45:48'),
(20, NULL, 'MERENGADAS', '#MER-001', 0, 0, 0.00, 'Lt', '2025-05-20 05:03:56', '2025-05-20 05:03:56'),
(21, NULL, 'VODKA', 'VOD-001', 0, 0, 0.00, 'Botella', '2025-05-20 05:08:45', '2025-05-20 05:08:45'),
(22, NULL, 'Limon', '#LIM-001', 0, 0, 0.00, 'Kg', '2025-05-20 05:36:17', '2025-05-20 05:36:17'),
(23, NULL, 'Cambur', '#CAM-001', 0, 0, 0.00, 'Kg', '2025-05-20 11:37:08', '2025-05-20 11:37:08'),
(24, NULL, 'Azucar', '#AZU-001', 0, 0, 0.00, 'kg', '2025-05-20 12:30:21', '2025-05-20 12:31:10'),
(30, NULL, 'Sal', '#SAL-001', 0, 0, 0.00, 'kg', '2025-05-20 13:43:24', '2025-05-20 13:43:24'),
(31, NULL, 'Carne de Cochino', '#CAR-002', 0, 0, 0.00, 'kg', '2025-05-20 14:02:01', '2025-05-20 14:02:01');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `jobs`
--
-- Creación: 05-05-2025 a las 19:10:40
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `job_batches`
--
-- Creación: 05-05-2025 a las 19:10:43
--

CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `lotes`
--
-- Creación: 19-05-2025 a las 03:57:56
-- Última actualización: 22-05-2025 a las 07:48:33
--

CREATE TABLE `lotes` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `producto_id` bigint(20) UNSIGNED NOT NULL,
  `cantidad_inicial` int(11) NOT NULL,
  `cantidad_actual` int(11) NOT NULL,
  `precio_costo` decimal(8,2) NOT NULL,
  `fecha_ingreso` datetime NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `lotes`
--

INSERT INTO `lotes` (`id`, `producto_id`, `cantidad_inicial`, `cantidad_actual`, `precio_costo`, `fecha_ingreso`, `created_at`, `updated_at`) VALUES
(3, 5, 200, 200, 150.00, '2025-05-19 00:00:00', '2025-05-19 04:15:46', '2025-05-19 04:15:46'),
(4, 7, 300, 300, 150.00, '2025-05-19 00:00:00', '2025-05-19 04:23:25', '2025-05-19 04:23:25'),
(5, 8, 100, 0, 50.00, '2025-05-19 00:00:00', '2025-05-19 05:44:52', '2025-05-20 04:13:39'),
(7, 10, 150, 0, 50.00, '2025-05-19 00:00:00', '2025-05-19 20:39:28', '2025-05-20 03:34:53'),
(10, 15, 5728, 0, 80.00, '2025-05-19 00:00:00', '2025-05-19 21:54:08', '2025-05-20 00:34:46'),
(11, 15, 100, 0, 75.00, '2025-05-19 00:00:00', '2025-05-19 23:05:30', '2025-05-20 00:35:18'),
(12, 15, 999, 0, 50.00, '2025-05-19 00:00:00', '2025-05-19 23:27:01', '2025-05-20 00:38:33'),
(13, 15, 777, 0, 85.00, '2025-05-19 00:00:00', '2025-05-19 23:34:30', '2025-05-20 00:55:11'),
(14, 15, 888, 0, 55.00, '2025-05-19 19:57:10', '2025-05-19 23:57:10', '2025-05-20 00:55:51'),
(15, 15, 100, 0, 50.00, '2025-05-19 20:59:38', '2025-05-20 00:59:38', '2025-05-20 01:00:07'),
(16, 15, 100, 0, 100.00, '2025-05-19 21:01:32', '2025-05-20 01:01:32', '2025-05-20 01:13:52'),
(17, 8, 125, 100, 50.00, '2025-05-20 00:13:30', '2025-05-20 04:13:30', '2025-05-20 04:13:40'),
(18, 16, 500, 400, 250.00, '2025-05-20 00:14:31', '2025-05-20 04:14:31', '2025-05-20 04:27:05'),
(19, 17, 500, 450, 150.00, '2025-05-20 00:28:06', '2025-05-20 04:28:06', '2025-05-20 04:28:29'),
(20, 17, 50, 50, 25.00, '2025-05-20 00:28:45', '2025-05-20 04:28:45', '2025-05-20 04:28:45'),
(21, 18, 500, 450, 150.00, '2025-05-20 00:45:48', '2025-05-20 04:45:48', '2025-05-20 04:46:00'),
(23, 20, 600, 550, 777.00, '2025-05-20 01:03:56', '2025-05-20 05:03:56', '2025-05-20 08:47:13'),
(24, 21, 500, 450, 250.00, '2025-05-20 01:08:45', '2025-05-20 05:08:45', '2025-05-20 05:08:54'),
(25, 22, 500, 450, 250.00, '2025-05-20 01:36:17', '2025-05-20 05:36:17', '2025-05-20 05:36:33'),
(26, 22, 50, 50, 75.00, '2025-05-20 02:11:53', '2025-05-20 06:11:53', '2025-05-20 06:11:53'),
(27, 22, 50, 50, 25.00, '2025-05-20 02:20:41', '2025-05-20 06:20:41', '2025-05-20 06:20:41'),
(28, 22, 50, 50, 100.00, '2025-05-20 02:21:17', '2025-05-20 06:21:17', '2025-05-20 06:21:17'),
(29, 21, 100, 100, 50.00, '2025-05-20 02:21:50', '2025-05-20 06:21:50', '2025-05-20 06:21:50'),
(30, 21, 100, 100, 50.00, '2025-05-20 02:22:25', '2025-05-20 06:22:25', '2025-05-20 06:22:25'),
(31, 20, 100, 100, 50.00, '2025-05-20 02:25:25', '2025-05-20 06:25:25', '2025-05-20 06:25:25'),
(32, 8, 100, 100, 0.50, '2025-05-20 07:04:27', '2025-05-20 11:04:27', '2025-05-20 11:04:27'),
(33, 8, 100, 100, 0.50, '2025-05-20 07:04:59', '2025-05-20 11:04:59', '2025-05-20 11:04:59'),
(34, 22, 700, 700, 0.12, '2025-05-20 07:06:25', '2025-05-20 11:06:25', '2025-05-20 11:06:25'),
(35, 15, 100, 100, 0.50, '2025-05-20 07:23:30', '2025-05-20 11:23:30', '2025-05-20 11:23:30'),
(36, 23, 10, 10, 5.00, '2025-05-20 07:37:08', '2025-05-20 11:37:08', '2025-05-20 11:37:08'),
(37, 24, 50, 0, 0.50, '2025-05-20 08:30:21', '2025-05-20 12:30:21', '2025-05-20 12:37:38'),
(38, 24, 100, 25, 0.50, '2025-05-20 08:31:10', '2025-05-20 12:31:10', '2025-05-20 12:32:07'),
(45, 30, 100, 100, 0.50, '2025-05-20 09:43:24', '2025-05-20 13:43:24', '2025-05-20 13:43:24'),
(46, 31, 200, 160, 0.75, '2025-05-20 10:02:01', '2025-05-20 14:02:01', '2025-05-22 07:48:33');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `migrations`
--
-- Creación: 30-04-2025 a las 03:52:33
-- Última actualización: 22-05-2025 a las 16:56:59
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2025_04_26_150000_create_clientes_table', 1),
(5, '2025_04_27_150000_create_cuentas_table', 1),
(6, '2025_04_27_173736_create_productos_table', 1),
(7, '2025_04_27_175349_create_inventarios_table', 1),
(8, '2025_04_27_184640_create_detalle_cuentas_table', 1),
(9, '2025_04_28_013936_create_movimiento_inventarios_table', 1),
(10, '2025_04_28_192049_create_movimientos_table', 1),
(11, '2025_04_30_032241_create_cuenta_producto_table', 1),
(12, '2025_04_30_032312_create_pagos_table', 1),
(13, '2025_05_02_125823_modify_precio_column_in_productos_table', 1),
(14, '2025_05_02_130407_change_precio_to_decimal_in_productos_table', 1),
(15, '2025_05_02_131609_add_precio_to_productos_table', 1),
(16, '2025_05_02_164408_add_pagada_to_cuentas_table', 1),
(17, '2025_05_03_022431_add_cliente_nombre_to_cuentas_table', 1),
(18, '2025_05_03_173356_add_cliente_nombre_manual_to_cuentas_table', 1),
(19, '2025_05_09_000001_add_tasa_dia_to_cuentas_table', 2),
(20, '2025_05_09_000001_create_config_table', 3),
(21, '2025_05_13_000001_add_total_pagado_and_vuelto_to_cuentas_table', 4),
(22, '2025_05_14_150928_create_permission_tables', 5),
(23, '2025_05_14_152951_add_barrero_to_cuentas_table', 6),
(24, '2025_05_14_181219_add_role_to_users_table', 7),
(25, '2025_05_14_235349_add_cajera_and_barrero_to_cuentas_table', 8),
(26, '2025_05_15_000001_create_areas_table', 9),
(27, '2025_05_15_000002_add_area_id_to_productos_table', 9),
(28, '2025_05_16_071611_add_printed_status_to_detalle_cuentas_table', 10),
(29, '2025_05_17_000000_add_area_id_to_detalle_cuentas_table', 11),
(30, '2025_05_18_000001_create_lotes_table', 12),
(31, '2025_05_18_041500_update_precio_venta_in_productos_table', 12),
(32, '2025_05_18_154458_create_inventario_productos_table', 13),
(33, '2025_05_18_161130_add_inventario_productos_table', 14),
(34, '2025_05_18_154458_create_inventario_productos_table', 1),
(35, '2025_05_19_165952_add_cantidad_inicial_to_inventario_productos_table', 15),
(36, '2025_05_19_203740_add_lote_id_to_movimientos_table', 16),
(37, '2025_05_19_232301_add_user_id_to_movimientos_table', 17),
(38, '2025_05_20_005802_add_producto_id_to_inventario_productos_table', 18),
(39, '2024_05_22_000001_add_pendiente_to_cuentas_table', 19);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `model_has_permissions`
--
-- Creación: 14-05-2025 a las 19:09:44
--

CREATE TABLE `model_has_permissions` (
  `permission_id` bigint(20) UNSIGNED NOT NULL,
  `model_type` varchar(255) NOT NULL,
  `model_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `model_has_roles`
--
-- Creación: 14-05-2025 a las 19:09:44
--

CREATE TABLE `model_has_roles` (
  `role_id` bigint(20) UNSIGNED NOT NULL,
  `model_type` varchar(255) NOT NULL,
  `model_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `model_has_roles`
--

INSERT INTO `model_has_roles` (`role_id`, `model_type`, `model_id`) VALUES
(3, 'App\\Models\\User', 2),
(3, 'App\\Models\\User', 3),
(3, 'App\\Models\\User', 4),
(3, 'App\\Models\\User', 5);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `movimientos`
--
-- Creación: 20-05-2025 a las 03:45:58
-- Última actualización: 22-05-2025 a las 07:48:33
--

CREATE TABLE `movimientos` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `inventario_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `lote_id` bigint(20) UNSIGNED DEFAULT NULL,
  `tipo` enum('entrada','salida') NOT NULL,
  `cantidad` int(11) NOT NULL,
  `precio_costo` decimal(8,2) DEFAULT NULL,
  `detalle` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `movimientos`
--

INSERT INTO `movimientos` (`id`, `inventario_id`, `user_id`, `lote_id`, `tipo`, `cantidad`, `precio_costo`, `detalle`, `created_at`, `updated_at`) VALUES
(6, 8, 1, 5, 'salida', 700, NULL, 'Se pudrio', '2025-05-20 03:47:02', '2025-05-20 03:47:02'),
(7, 8, 1, 5, 'salida', 50, NULL, 'Se pudrio', '2025-05-20 04:03:55', '2025-05-20 04:03:55'),
(8, 8, 1, 5, 'salida', 25, NULL, 'Se pudrio', '2025-05-20 04:12:42', '2025-05-20 04:12:42'),
(9, 8, 1, 5, 'salida', 25, NULL, 'Se pudrio', '2025-05-20 04:13:40', '2025-05-20 04:13:40'),
(10, 8, 1, 17, 'salida', 25, NULL, 'Se pudrio', '2025-05-20 04:13:40', '2025-05-20 04:13:40'),
(11, 16, 1, 18, 'salida', 50, NULL, 'Vendidas', '2025-05-20 04:14:53', '2025-05-20 04:14:53'),
(12, 16, 1, 18, 'salida', 50, NULL, 'Vendidas', '2025-05-20 04:27:05', '2025-05-20 04:27:05'),
(13, 17, 1, 19, 'salida', 50, NULL, 'Vendidas', '2025-05-20 04:28:30', '2025-05-20 04:28:30'),
(14, 18, 1, 21, 'salida', 50, NULL, 'Se pudrio', '2025-05-20 04:46:00', '2025-05-20 04:46:00'),
(16, 20, 1, 23, 'salida', 50, NULL, 'Vendidas', '2025-05-20 05:04:03', '2025-05-20 05:04:03'),
(17, 21, 1, 24, 'salida', 50, NULL, 'Vendidas', '2025-05-20 05:08:54', '2025-05-20 05:08:54'),
(18, 22, 1, 25, 'salida', 50, NULL, 'Vendidas', '2025-05-20 05:36:33', '2025-05-20 05:36:33'),
(19, 22, 1, 25, 'entrada', 50, NULL, 'Entrada de inventario', '2025-05-20 06:20:41', '2025-05-20 06:20:41'),
(20, 22, 1, 25, 'entrada', 50, NULL, 'Entrada de inventario', '2025-05-20 06:21:17', '2025-05-20 06:21:17'),
(21, 21, 1, 24, 'entrada', 100, NULL, 'Entrada de inventario', '2025-05-20 06:21:50', '2025-05-20 06:21:50'),
(22, 21, 1, 24, 'entrada', 100, NULL, 'Entrada de inventario', '2025-05-20 06:22:25', '2025-05-20 06:22:25'),
(23, 20, 1, 23, 'entrada', 100, NULL, 'Entrada de inventario', '2025-05-20 06:25:25', '2025-05-20 06:25:25'),
(24, 8, 1, 32, 'entrada', 100, NULL, 'Entrada de inventario', '2025-05-20 11:04:27', '2025-05-20 11:04:27'),
(25, 8, 1, 33, 'entrada', 100, NULL, 'Entrada de inventario', '2025-05-20 11:04:59', '2025-05-20 11:04:59'),
(26, 22, 1, 34, 'entrada', 700, NULL, 'Entrada de inventario', '2025-05-20 11:06:25', '2025-05-20 11:06:25'),
(27, 15, 1, 35, 'entrada', 100, NULL, 'Entrada de inventario', '2025-05-20 11:23:30', '2025-05-20 11:23:30'),
(28, 24, 1, 38, 'entrada', 100, NULL, 'Entrada de inventario', '2025-05-20 12:31:10', '2025-05-20 12:31:10'),
(29, 24, 1, 37, 'salida', 50, NULL, 'Uso Interno', '2025-05-20 12:31:38', '2025-05-20 12:31:38'),
(30, 24, 1, 38, 'salida', 50, NULL, 'Uso Interno', '2025-05-20 12:31:38', '2025-05-20 12:31:38'),
(31, 24, 1, 38, 'salida', 25, NULL, 'Uso Interno', '2025-05-20 12:32:07', '2025-05-20 12:32:07'),
(34, 31, 1, 46, 'salida', 40, NULL, 'Merma', '2025-05-22 07:48:33', '2025-05-22 07:48:33');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pagos`
--
-- Creación: 05-05-2025 a las 19:10:55
--

CREATE TABLE `pagos` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `cuenta_id` bigint(20) UNSIGNED NOT NULL,
  `metodo` varchar(255) NOT NULL,
  `monto` decimal(10,2) NOT NULL,
  `referencia` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `password_reset_tokens`
--
-- Creación: 05-05-2025 a las 19:10:40
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `permissions`
--
-- Creación: 14-05-2025 a las 19:09:44
--

CREATE TABLE `permissions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `guard_name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productos`
--
-- Creación: 15-05-2025 a las 22:59:52
--

CREATE TABLE `productos` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `codigo` varchar(255) NOT NULL,
  `area_id` bigint(20) UNSIGNED DEFAULT NULL,
  `nombre` varchar(255) NOT NULL,
  `precio` decimal(10,2) NOT NULL DEFAULT 0.00,
  `descripcion` text DEFAULT NULL,
  `unidad_medida` varchar(255) NOT NULL,
  `cantidad_inicial` int(11) NOT NULL DEFAULT 0,
  `cantidad_actual` int(11) NOT NULL DEFAULT 0,
  `precio_venta` decimal(8,2) NOT NULL DEFAULT 0.00,
  `costo` decimal(10,2) DEFAULT NULL,
  `categoria` enum('barra','bodegon','cocina') NOT NULL DEFAULT 'barra',
  `observaciones` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `productos`
--

INSERT INTO `productos` (`id`, `codigo`, `area_id`, `nombre`, `precio`, `descripcion`, `unidad_medida`, `cantidad_inicial`, `cantidad_actual`, `precio_venta`, `costo`, `categoria`, `observaciones`, `created_at`, `updated_at`) VALUES
(211, 'CAR-001', 1, '1Kg Surtido', 0.00, NULL, 'kg', 0, 0, 35.00, NULL, 'barra', NULL, '2025-05-16 01:45:24', '2025-05-16 01:45:24'),
(212, 'CAR-002', 1, '1Kg Cochino', 0.00, NULL, 'kg', 0, 0, 35.00, NULL, 'barra', NULL, '2025-05-16 01:45:24', '2025-05-16 01:45:24'),
(213, 'CAR-003', 1, '1Kg de Carne', 0.00, NULL, 'kg', 0, 0, 35.00, NULL, 'barra', NULL, '2025-05-16 01:45:24', '2025-05-16 01:45:24'),
(214, 'CAR-004', 1, '1Kg de Carne Importada', 0.00, NULL, 'kg', 0, 0, 46.00, NULL, 'barra', NULL, '2025-05-16 01:45:24', '2025-05-16 01:45:24'),
(215, 'CAR-005', 1, '1/2Kg Surtido', 0.00, NULL, 'kg', 0, 0, 18.00, NULL, 'barra', NULL, '2025-05-16 01:45:24', '2025-05-16 01:45:24'),
(216, 'CAR-006', 1, '1/2Kg Cochino', 0.00, NULL, 'kg', 0, 0, 18.00, NULL, 'barra', NULL, '2025-05-16 01:45:24', '2025-05-16 01:45:24'),
(217, 'CAR-007', 1, '1/2Kg de Carne', 0.00, NULL, 'kg', 0, 0, 18.00, NULL, 'barra', NULL, '2025-05-16 01:45:24', '2025-05-16 01:45:24'),
(218, 'CAR-008', 1, '1/2Kg de Carne Importada', 0.00, NULL, 'kg', 0, 0, 23.00, NULL, 'barra', NULL, '2025-05-16 01:45:24', '2025-05-16 01:45:24'),
(219, 'CAR-009', 1, '1/4Kg Surtido', 0.00, NULL, 'kg', 0, 0, 9.00, NULL, 'barra', NULL, '2025-05-16 01:45:24', '2025-05-16 01:45:24'),
(220, 'CAR-010', 1, '1/4Kg Cochino', 0.00, NULL, 'kg', 0, 0, 9.00, NULL, 'barra', NULL, '2025-05-16 01:45:24', '2025-05-16 01:45:24'),
(221, 'CAR-011', 1, '1/4Kg de Carne', 0.00, NULL, 'kg', 0, 0, 9.00, NULL, 'barra', NULL, '2025-05-16 01:45:24', '2025-05-16 01:45:24'),
(222, 'CAR-012', 1, '1/4Kg de Carne Importada', 0.00, NULL, 'kg', 0, 0, 12.00, NULL, 'barra', NULL, '2025-05-16 01:45:24', '2025-05-16 01:45:24'),
(223, 'COC-001', 4, 'Ración de Tequeños', 0.00, NULL, 'ración', 0, 0, 8.00, NULL, 'barra', NULL, '2025-05-16 01:45:24', '2025-05-16 01:45:24'),
(224, 'COC-002', 4, 'Ración de Papas', 0.00, NULL, 'ración', 0, 0, 8.00, NULL, 'barra', NULL, '2025-05-16 01:45:25', '2025-05-16 01:45:25'),
(225, 'COC-003', 4, 'Ración de Huevos de Codorniz', 0.00, NULL, 'ración', 0, 0, 8.00, NULL, 'barra', NULL, '2025-05-16 01:45:25', '2025-05-16 01:45:25'),
(226, 'COC-004', 4, 'Ración de Tostones con Atún', 0.00, NULL, 'ración', 0, 0, 8.00, NULL, 'barra', NULL, '2025-05-16 01:45:25', '2025-05-16 01:45:25'),
(227, 'COC-005', 4, 'Ración de Tostones con Queso', 0.00, NULL, 'ración', 0, 0, 8.00, NULL, 'barra', NULL, '2025-05-16 01:45:25', '2025-05-16 01:45:25'),
(228, 'COC-006', 4, 'Ración de Pastelito Carne/Queso', 0.00, NULL, 'ración', 0, 0, 8.00, NULL, 'barra', NULL, '2025-05-16 01:45:25', '2025-05-16 01:45:25'),
(229, 'COC-007', 4, 'Ración de Arepas C/ Queso Fundido', 0.00, NULL, 'ración', 0, 0, 8.00, NULL, 'barra', NULL, '2025-05-16 01:45:25', '2025-05-16 01:45:25'),
(230, 'COC-008', 4, 'Ración de Queso Fundido', 0.00, NULL, 'ración', 0, 0, 8.00, NULL, 'barra', NULL, '2025-05-16 01:45:25', '2025-05-16 01:45:25'),
(231, 'COC-009', 4, 'Ración de Queso Crema C/ Casabe', 0.00, NULL, 'ración', 0, 0, 8.00, NULL, 'barra', NULL, '2025-05-16 01:45:25', '2025-05-16 01:45:25'),
(232, 'COC-010', 4, 'Ración de Alitas a la BBQ', 0.00, NULL, 'ración', 0, 0, 8.00, NULL, 'barra', NULL, '2025-05-16 01:45:25', '2025-05-16 01:45:25'),
(233, 'COC-011', 4, 'Ración Chorizo a la Monserratina', 0.00, NULL, 'ración', 0, 0, 8.00, NULL, 'barra', NULL, '2025-05-16 01:45:25', '2025-05-16 01:45:25'),
(234, 'COC-012', 4, 'Ración de Morcilla', 0.00, NULL, 'ración', 0, 0, 8.00, NULL, 'barra', NULL, '2025-05-16 01:45:25', '2025-05-16 01:45:25'),
(235, 'COC-013', 4, 'Ración Mixta (Morcilla/Chorizo)', 0.00, NULL, 'ración', 0, 0, 8.00, NULL, 'barra', NULL, '2025-05-16 01:45:25', '2025-05-16 01:45:25'),
(236, 'COC-014', 4, 'Ensalada', 0.00, NULL, 'plato', 0, 0, 10.00, NULL, 'barra', NULL, '2025-05-16 01:45:25', '2025-05-16 01:45:25'),
(237, 'COC-015', 4, 'Salchipapa', 0.00, NULL, 'plato', 0, 0, 10.00, NULL, 'barra', NULL, '2025-05-16 01:45:25', '2025-05-16 01:45:25'),
(238, 'COC-016', 4, 'Hamburguesa', 0.00, NULL, 'unidad', 0, 0, 10.00, NULL, 'barra', NULL, '2025-05-16 01:45:25', '2025-05-16 01:45:25'),
(239, 'COC-017', 4, 'Combo Kids', 0.00, NULL, 'combo', 0, 0, 10.00, NULL, 'barra', NULL, '2025-05-16 01:45:25', '2025-05-16 01:45:25'),
(240, 'COC-018', 4, 'Ceviche de Pescado', 0.00, NULL, 'plato', 0, 0, 14.00, NULL, 'barra', NULL, '2025-05-16 01:45:25', '2025-05-16 01:45:25'),
(241, 'COC-019', 4, 'Ceviche Mixto', 0.00, NULL, 'plato', 0, 0, 16.00, NULL, 'barra', NULL, '2025-05-16 01:45:25', '2025-05-16 01:45:25'),
(242, 'COC-020', 4, 'Sopa Costilla Grande', 0.00, NULL, 'plato', 0, 0, 8.00, NULL, 'barra', NULL, '2025-05-16 01:45:25', '2025-05-16 01:45:25'),
(243, 'COC-021', 4, 'Sopa Costilla Pequeña', 0.00, NULL, 'plato', 0, 0, 5.00, NULL, 'barra', NULL, '2025-05-16 01:45:25', '2025-05-16 01:45:25'),
(244, 'COC-022', 4, 'Sopa Mondongo Grande', 0.00, NULL, 'plato', 0, 0, 8.00, NULL, 'barra', NULL, '2025-05-16 01:45:25', '2025-05-16 01:45:25'),
(245, 'COC-023', 4, 'Sopa Mondongo Pequeña', 0.00, NULL, 'plato', 0, 0, 5.00, NULL, 'barra', NULL, '2025-05-16 01:45:25', '2025-05-16 01:45:25'),
(246, 'COC-024', 4, 'Sopa Pollo Grande', 0.00, NULL, 'plato', 0, 0, 8.00, NULL, 'barra', NULL, '2025-05-16 01:45:25', '2025-05-16 01:45:25'),
(247, 'COC-025', 4, 'Sopa Pollo Pequeña', 0.00, NULL, 'plato', 0, 0, 5.00, NULL, 'barra', NULL, '2025-05-16 01:45:25', '2025-05-16 01:45:25'),
(248, 'COC-026', 4, 'Picadillo Llanero Grande', 0.00, NULL, 'plato', 0, 0, 8.00, NULL, 'barra', NULL, '2025-05-16 01:45:25', '2025-05-16 01:45:25'),
(249, 'COC-027', 4, 'Picadillo Llanero Pequeño', 0.00, NULL, 'plato', 0, 0, 5.00, NULL, 'barra', NULL, '2025-05-16 01:45:25', '2025-05-16 01:45:25'),
(250, 'COC-028', 4, 'Mariscos Grande', 0.00, NULL, 'plato', 0, 0, 10.00, NULL, 'barra', NULL, '2025-05-16 01:45:25', '2025-05-16 01:45:25'),
(251, 'COC-029', 4, 'Mariscos Pequeño', 0.00, NULL, 'plato', 0, 0, 6.00, NULL, 'barra', NULL, '2025-05-16 01:45:25', '2025-05-16 01:45:25'),
(252, 'CAC-001', 2, 'Cachapa Sola', 0.00, NULL, 'plato', 0, 0, 6.00, NULL, 'barra', NULL, '2025-05-16 01:45:26', '2025-05-16 01:45:26'),
(253, 'CAC-002', 2, 'Cachapa C/ Queso Telita', 0.00, NULL, 'plato', 0, 0, 10.00, NULL, 'barra', NULL, '2025-05-16 01:45:26', '2025-05-16 01:45:26'),
(254, 'CAC-003', 2, 'Cachapa C/ Cochino', 0.00, NULL, 'plato', 0, 0, 14.00, NULL, 'barra', NULL, '2025-05-16 01:45:26', '2025-05-16 01:45:26'),
(255, 'CAC-004', 2, 'Cachapa C/ Queso Telita y Cochino', 0.00, NULL, 'plato', 0, 0, 16.00, NULL, 'barra', NULL, '2025-05-16 01:45:26', '2025-05-16 01:45:26'),
(256, 'CAC-005', 2, '1/2 Cachapa C/ Queso Telita', 0.00, NULL, 'plato', 0, 0, 5.00, NULL, 'barra', NULL, '2025-05-16 01:45:26', '2025-05-16 01:45:26'),
(257, 'CAC-006', 2, 'Ración de Queso Telita', 0.00, NULL, 'ración', 0, 0, 6.00, NULL, 'barra', NULL, '2025-05-16 01:45:26', '2025-05-16 01:45:26'),
(258, 'POS-001', 3, 'Torta Grande', 0.00, NULL, 'unidad', 0, 0, 5.00, NULL, 'barra', NULL, '2025-05-16 01:45:26', '2025-05-16 01:45:26'),
(259, 'POS-002', 3, 'Marquesa y Quesillo', 0.00, NULL, 'unidad', 0, 0, 4.00, NULL, 'barra', NULL, '2025-05-16 01:45:26', '2025-05-16 01:45:26'),
(260, 'POS-003', 3, 'Torta Pequeña', 0.00, NULL, 'unidad', 0, 0, 3.00, NULL, 'barra', NULL, '2025-05-16 01:45:26', '2025-05-16 01:45:26'),
(261, 'WHI-001', 3, 'Buchanans', 0.00, NULL, 'botella', 0, 0, 60.00, NULL, 'barra', NULL, '2025-05-16 01:45:26', '2025-05-16 01:45:26'),
(262, 'WHI-002', 3, 'Old Par', 0.00, NULL, 'botella', 0, 0, 50.00, NULL, 'barra', NULL, '2025-05-16 01:45:26', '2025-05-16 01:45:26'),
(263, 'WHI-003', 3, 'Descorche', 0.00, NULL, 'servicio', 0, 0, 25.00, NULL, 'barra', NULL, '2025-05-16 01:45:26', '2025-05-16 01:45:26'),
(264, 'SER-001', 3, 'Servicio de Vino', 0.00, NULL, 'servicio', 0, 0, 25.00, NULL, 'barra', NULL, '2025-05-16 01:45:26', '2025-05-16 01:45:26'),
(265, 'SER-002', 3, 'Servicio de Ron Carúpano', 0.00, NULL, 'servicio', 0, 0, 20.00, NULL, 'barra', NULL, '2025-05-16 01:45:26', '2025-05-16 01:45:26'),
(266, 'SER-003', 3, 'Servicio de Ron Cacique', 0.00, NULL, 'servicio', 0, 0, 20.00, NULL, 'barra', NULL, '2025-05-16 01:45:26', '2025-05-16 01:45:26'),
(267, 'SER-004', 3, 'Servicio Cacique 500 Años', 0.00, NULL, 'servicio', 0, 0, 35.00, NULL, 'barra', NULL, '2025-05-16 01:45:26', '2025-05-16 01:45:26'),
(268, 'SER-005', 3, 'Servicio de Santa Teresa 0,75', 0.00, NULL, 'servicio', 0, 0, 25.00, NULL, 'barra', NULL, '2025-05-16 01:45:26', '2025-05-16 01:45:26'),
(269, 'SER-006', 3, 'Servicio Vodka Gordon', 0.00, NULL, 'servicio', 0, 0, 25.00, NULL, 'barra', NULL, '2025-05-16 01:45:26', '2025-05-16 01:45:26'),
(270, 'SER-007', 3, 'Servicio Grey Goose', 0.00, NULL, 'servicio', 0, 0, 60.00, NULL, 'barra', NULL, '2025-05-16 01:45:26', '2025-05-16 01:45:26'),
(271, 'SER-008', 3, 'Servicio de Anís Cartujo', 0.00, NULL, 'servicio', 0, 0, 20.00, NULL, 'barra', NULL, '2025-05-16 01:45:26', '2025-05-16 01:45:26'),
(272, 'SER-009', 3, 'Servicio de Anís El Mono', 0.00, NULL, 'servicio', 0, 0, 40.00, NULL, 'barra', NULL, '2025-05-16 01:45:26', '2025-05-16 01:45:26'),
(273, 'SER-010', 3, 'Servicio de Caroreña', 0.00, NULL, 'servicio', 0, 0, 15.00, NULL, 'barra', NULL, '2025-05-16 01:45:26', '2025-05-16 01:45:26'),
(274, 'CER-001', 3, 'Tobo 10 Cervezas Polar', 0.00, NULL, 'tobo', 0, 0, 10.00, NULL, 'barra', NULL, '2025-05-16 01:45:26', '2025-05-16 01:45:26'),
(275, 'CER-002', 3, 'Tobo 10 Cervezas Zulia', 0.00, NULL, 'tobo', 0, 0, 10.00, NULL, 'barra', NULL, '2025-05-16 01:45:26', '2025-05-16 01:45:26'),
(276, 'CER-003', 3, 'Tobo 10 Cervezas Solera', 0.00, NULL, 'tobo', 0, 0, 10.00, NULL, 'barra', NULL, '2025-05-16 01:45:26', '2025-05-16 01:45:26'),
(277, 'CER-004', 3, 'Cerveza por Unidad Polar', 0.00, NULL, 'unidad', 0, 0, 1.00, NULL, 'barra', NULL, '2025-05-16 01:45:26', '2025-05-16 01:45:26'),
(278, 'CER-005', 3, 'Cerveza por Unidad Zulia', 0.00, NULL, 'unidad', 0, 0, 1.00, NULL, 'barra', NULL, '2025-05-16 01:45:26', '2025-05-16 01:45:26'),
(279, 'CER-006', 3, 'Cerveza por Unidad Solera', 0.00, NULL, 'unidad', 0, 0, 1.00, NULL, 'barra', NULL, '2025-05-16 01:45:27', '2025-05-16 01:45:27'),
(280, 'CER-007', 3, 'Cerveza por Unidad Verano', 0.00, NULL, 'unidad', 0, 0, 2.00, NULL, 'barra', NULL, '2025-05-16 01:45:27', '2025-05-16 01:45:27'),
(281, 'CER-008', 3, 'Cerveza por Unidad Breeze Ice', 0.00, NULL, 'unidad', 0, 0, 3.00, NULL, 'barra', NULL, '2025-05-16 01:45:27', '2025-05-16 01:45:27'),
(282, 'CER-009', 3, 'Cerveza por Unidad Corona', 0.00, NULL, 'unidad', 0, 0, 3.00, NULL, 'barra', NULL, '2025-05-16 01:45:27', '2025-05-16 01:45:27'),
(283, 'CER-010', 3, 'Cerveza por Unidad Presidente', 0.00, NULL, 'unidad', 0, 0, 3.00, NULL, 'barra', NULL, '2025-05-16 01:45:27', '2025-05-16 01:45:27'),
(284, 'BEB-001', 3, 'Batido Mora', 0.00, NULL, 'vaso', 0, 0, 4.00, NULL, 'barra', NULL, '2025-05-16 01:45:27', '2025-05-16 01:45:27'),
(285, 'BEB-002', 3, 'Batido Fresa', 0.00, NULL, 'vaso', 0, 0, 4.00, NULL, 'barra', NULL, '2025-05-16 01:45:27', '2025-05-16 01:45:27'),
(286, 'BEB-003', 3, 'Batido Melón', 0.00, NULL, 'vaso', 0, 0, 4.00, NULL, 'barra', NULL, '2025-05-16 01:45:27', '2025-05-16 01:45:27'),
(287, 'BEB-004', 3, 'Batido Durazno', 0.00, NULL, 'vaso', 0, 0, 4.00, NULL, 'barra', NULL, '2025-05-16 01:45:27', '2025-05-16 01:45:27'),
(288, 'BEB-005', 3, 'Batido Lechoza', 0.00, NULL, 'vaso', 0, 0, 4.00, NULL, 'barra', NULL, '2025-05-16 01:45:27', '2025-05-16 01:45:27'),
(289, 'BEB-006', 3, 'Batido Parchita', 0.00, NULL, 'vaso', 0, 0, 4.00, NULL, 'barra', NULL, '2025-05-16 01:45:27', '2025-05-16 01:45:27'),
(290, 'BEB-007', 3, 'Batido Patilla', 0.00, NULL, 'vaso', 0, 0, 4.00, NULL, 'barra', NULL, '2025-05-16 01:45:27', '2025-05-16 01:45:27'),
(291, 'BEB-008', 3, 'Batido Guanábana', 0.00, NULL, 'vaso', 0, 0, 4.00, NULL, 'barra', NULL, '2025-05-16 01:45:27', '2025-05-16 01:45:27'),
(292, 'BEB-009', 3, 'Batido Mango', 0.00, NULL, 'vaso', 0, 0, 4.00, NULL, 'barra', NULL, '2025-05-16 01:45:27', '2025-05-16 01:45:27'),
(293, 'BEB-010', 3, 'Batido Piña', 0.00, NULL, 'vaso', 0, 0, 4.00, NULL, 'barra', NULL, '2025-05-16 01:45:27', '2025-05-16 01:45:27'),
(294, 'BEB-012', 3, 'Café', 0.00, NULL, 'vaso', 0, 0, 3.00, NULL, 'barra', NULL, '2025-05-16 01:45:27', '2025-05-16 01:45:27'),
(295, 'BEB-013', 3, 'Nestea', 0.00, NULL, 'vaso', 0, 0, 3.00, NULL, 'barra', NULL, '2025-05-16 01:45:27', '2025-05-16 01:45:27'),
(296, 'BAT-001', 3, '1/2 Batido de Mora', 0.00, NULL, 'vaso', 0, 0, 2.00, NULL, 'barra', NULL, '2025-05-16 01:45:27', '2025-05-16 01:45:27'),
(297, 'BAT-002', 3, '1/2 Batido de Fresa', 0.00, NULL, 'vaso', 0, 0, 2.00, NULL, 'barra', NULL, '2025-05-16 01:45:27', '2025-05-16 01:45:27'),
(298, 'BAT-003', 3, '1/2 Batido de Melon', 0.00, NULL, 'vaso', 0, 0, 2.00, NULL, 'barra', NULL, '2025-05-16 01:45:27', '2025-05-16 01:45:27'),
(299, 'BAT-004', 3, '1/2 Batido de Durazno', 0.00, NULL, 'vaso', 0, 0, 2.00, NULL, 'barra', NULL, '2025-05-16 01:45:27', '2025-05-16 01:45:27'),
(300, 'BAT-005', 3, '1/2 Batido de Lechoza', 0.00, NULL, 'vaso', 0, 0, 2.00, NULL, 'barra', NULL, '2025-05-16 01:45:27', '2025-05-16 01:45:27'),
(301, 'BAT-006', 3, '1/2 Batido de Parchita', 0.00, NULL, 'vaso', 0, 0, 2.00, NULL, 'barra', NULL, '2025-05-16 01:45:27', '2025-05-16 01:45:27'),
(302, 'BAT-007', 3, '1/2 Batido de Patilla', 0.00, NULL, 'vaso', 0, 0, 2.00, NULL, 'barra', NULL, '2025-05-16 01:45:27', '2025-05-16 01:45:27'),
(303, 'BAT-008', 3, '1/2 Batido de Guanabana', 0.00, NULL, 'vaso', 0, 0, 2.00, NULL, 'barra', NULL, '2025-05-16 01:45:27', '2025-05-16 01:45:27'),
(304, 'BAT-009', 3, '1/2 Batido de Mango', 0.00, NULL, 'vaso', 0, 0, 2.00, NULL, 'barra', NULL, '2025-05-16 01:45:27', '2025-05-16 01:45:27'),
(305, 'BAT-010', 3, '1/2 Batido de Piña', 0.00, NULL, 'vaso', 0, 0, 2.00, NULL, 'barra', NULL, '2025-05-16 01:45:27', '2025-05-16 01:45:27'),
(306, 'BAT-011', 3, '1/2 Batido de Limonada', 0.00, NULL, 'vaso', 0, 0, 2.00, NULL, 'barra', NULL, '2025-05-16 01:45:27', '2025-05-16 01:45:27'),
(307, 'BAT-012', 3, 'Batido Potencia (Mora, Fresa y Tomate de Árbol)', 0.00, NULL, 'vaso', 0, 0, 5.00, NULL, 'barra', NULL, '2025-05-16 01:45:28', '2025-05-16 01:45:28'),
(308, 'BAT-013', 3, 'Batido Rezágate (Mango, Piña y Maracuya)', 0.00, NULL, 'vaso', 0, 0, 5.00, NULL, 'barra', NULL, '2025-05-16 01:45:28', '2025-05-16 01:45:28'),
(309, 'BAT-014', 3, 'Batido Happy (Guanabana y Parchita)', 0.00, NULL, 'vaso', 0, 0, 5.00, NULL, 'barra', NULL, '2025-05-16 01:45:28', '2025-05-16 01:45:28'),
(310, 'BAT-015', 3, 'Batido Tropical (Fresa, Melon y Piña)', 0.00, NULL, 'vaso', 0, 0, 5.00, NULL, 'barra', NULL, '2025-05-16 01:45:28', '2025-05-16 01:45:28'),
(311, 'BAT-016', 3, 'Batido Berry (Mora y Fresa)', 0.00, NULL, 'vaso', 0, 0, 5.00, NULL, 'barra', NULL, '2025-05-16 01:45:28', '2025-05-16 01:45:28'),
(312, 'BAT-017', 3, '1/2 Batido Potencia (Mora, Fresa y Tomate de Árbol)', 0.00, NULL, 'vaso', 0, 0, 2.50, NULL, 'barra', NULL, '2025-05-16 01:45:28', '2025-05-16 01:45:28'),
(313, 'BAT-018', 3, '1/2 Batido Rezágate (Mango, Piña y Maracuya)', 0.00, NULL, 'vaso', 0, 0, 2.50, NULL, 'barra', NULL, '2025-05-16 01:45:28', '2025-05-16 01:45:28'),
(314, 'BAT-019', 3, '1/2 Batido Happy (Guanabana y Parchita)', 0.00, NULL, 'vaso', 0, 0, 2.50, NULL, 'barra', NULL, '2025-05-16 01:45:28', '2025-05-16 01:45:28'),
(315, 'BAT-020', 3, '1/2 Batido Tropical (Fresa, Melon y Piña)', 0.00, NULL, 'vaso', 0, 0, 2.50, NULL, 'barra', NULL, '2025-05-16 01:45:28', '2025-05-16 01:45:28'),
(316, 'BAT-021', 3, '1/2 Batido Berry (Mora y Fresa)', 0.00, NULL, 'vaso', 0, 0, 2.50, NULL, 'barra', NULL, '2025-05-16 01:45:28', '2025-05-16 01:45:28'),
(317, 'TOD-001', 3, 'TODYY', 0.00, NULL, 'vaso', 0, 0, 5.00, NULL, 'barra', NULL, '2025-05-16 01:45:28', '2025-05-16 01:45:28'),
(318, 'CRL-001', 3, 'Cerelac', 0.00, NULL, 'vaso', 0, 0, 5.00, NULL, 'barra', NULL, '2025-05-16 01:45:28', '2025-05-16 01:45:28'),
(319, 'MAL-001', 3, 'Malteada', 0.00, NULL, 'vaso', 0, 0, 5.00, NULL, 'barra', NULL, '2025-05-16 01:45:28', '2025-05-16 01:45:28'),
(320, 'SAM-001', 3, 'Samba', 0.00, NULL, 'vaso', 0, 0, 5.00, NULL, 'barra', NULL, '2025-05-16 01:45:28', '2025-05-16 01:45:28'),
(321, 'MER-001', 3, 'Merengada de Durazno', 0.00, NULL, 'vaso', 0, 0, 5.00, NULL, 'barra', NULL, '2025-05-16 01:45:28', '2025-05-16 01:45:28'),
(322, 'MER-002', 3, 'Merengada de Fresa', 0.00, NULL, 'vaso', 0, 0, 5.00, NULL, 'barra', NULL, '2025-05-16 01:45:28', '2025-05-16 01:45:28'),
(323, 'MER-003', 3, 'Merengada de Melon', 0.00, NULL, 'vaso', 0, 0, 5.00, NULL, 'barra', NULL, '2025-05-16 01:45:28', '2025-05-16 01:45:28'),
(324, 'MER-004', 3, 'Merengada de Lechosa', 0.00, NULL, 'vaso', 0, 0, 5.00, NULL, 'barra', NULL, '2025-05-16 01:45:28', '2025-05-16 01:45:28'),
(325, 'MER-005', 3, 'Merengada de Guanabana', 0.00, NULL, 'vaso', 0, 0, 5.00, NULL, 'barra', NULL, '2025-05-16 01:45:28', '2025-05-16 01:45:28'),
(326, 'MER-006', 3, '1/2 Merengada de Durazno', 0.00, NULL, 'vaso', 0, 0, 2.50, NULL, 'barra', NULL, '2025-05-16 01:45:28', '2025-05-16 01:45:28'),
(327, 'MER-007', 3, '1/2 Merengada de Fresa', 0.00, NULL, 'vaso', 0, 0, 2.50, NULL, 'barra', NULL, '2025-05-16 01:45:29', '2025-05-16 01:45:29'),
(328, 'MER-008', 3, '1/2 Merengada de Melon', 0.00, NULL, 'vaso', 0, 0, 2.50, NULL, 'barra', NULL, '2025-05-16 01:45:29', '2025-05-16 01:45:29'),
(329, 'MER-009', 3, '1/2 Merengada de Lechosa', 0.00, NULL, 'vaso', 0, 0, 2.50, NULL, 'barra', NULL, '2025-05-16 01:45:29', '2025-05-16 01:45:29'),
(330, 'MER-010', 3, '1/2 Merengada de Guanabana', 0.00, NULL, 'vaso', 0, 0, 2.50, NULL, 'barra', NULL, '2025-05-16 01:45:29', '2025-05-16 01:45:29'),
(331, 'LIM-001', 3, 'Limonada', 0.00, NULL, 'vaso', 0, 0, 4.00, NULL, 'barra', NULL, '2025-05-16 01:45:29', '2025-05-16 01:45:29'),
(332, 'LIM-002', 3, 'Limonada (Limón y Parchita)', 0.00, NULL, 'vaso', 0, 0, 5.00, NULL, 'barra', NULL, '2025-05-16 01:45:29', '2025-05-16 01:45:29'),
(333, 'LIM-003', 3, 'Limonada (Limón y Hierba Buena)', 0.00, NULL, 'vaso', 0, 0, 5.00, NULL, 'barra', NULL, '2025-05-16 01:45:29', '2025-05-16 01:45:29'),
(334, 'LIM-004', 3, 'Limonada (Limón y Granadina de Frambuesa)', 0.00, NULL, 'vaso', 0, 0, 5.00, NULL, 'barra', NULL, '2025-05-16 01:45:29', '2025-05-16 01:45:29'),
(335, 'LIM-005', 3, '1/2 Limonada (Limón y Parchita)', 0.00, NULL, 'vaso', 0, 0, 2.50, NULL, 'barra', NULL, '2025-05-16 01:45:29', '2025-05-16 01:45:29'),
(336, 'LIM-006', 3, '1/2 Limonada (Limón y Hierba Buena)', 0.00, NULL, 'vaso', 0, 0, 2.50, NULL, 'barra', NULL, '2025-05-16 01:45:29', '2025-05-16 01:45:29'),
(337, 'LIM-007', 3, '1/2 Limonada (Limón y Granadina de Frambuesa)', 0.00, NULL, 'vaso', 0, 0, 2.50, NULL, 'barra', NULL, '2025-05-16 01:45:29', '2025-05-16 01:45:29'),
(338, 'TRA-001', 3, 'Trago de Whisky Buchanan', 0.00, NULL, 'vaso', 0, 0, 8.00, NULL, 'barra', NULL, '2025-05-16 01:45:29', '2025-05-16 01:45:29'),
(339, 'TRA-002', 3, 'Trago de Whisky Old Par', 0.00, NULL, 'vaso', 0, 0, 8.00, NULL, 'barra', NULL, '2025-05-16 01:45:29', '2025-05-16 01:45:29'),
(340, 'TRA-003', 3, 'Cuba Libre', 0.00, NULL, 'vaso', 0, 0, 5.00, NULL, 'barra', NULL, '2025-05-16 01:45:29', '2025-05-16 01:45:29'),
(341, 'TRA-004', 3, 'Mojito de Fresa', 0.00, NULL, 'vaso', 0, 0, 5.00, NULL, 'barra', NULL, '2025-05-16 01:45:29', '2025-05-16 01:45:29'),
(342, 'TRA-005', 3, 'Mojito de Parchita', 0.00, NULL, 'vaso', 0, 0, 5.00, NULL, 'barra', NULL, '2025-05-16 01:45:29', '2025-05-16 01:45:29'),
(343, 'TRA-006', 3, 'Mojito de Limón', 0.00, NULL, 'vaso', 0, 0, 5.00, NULL, 'barra', NULL, '2025-05-16 01:45:29', '2025-05-16 01:45:29'),
(344, 'TRA-007', 3, 'Michelada', 0.00, NULL, 'vaso', 0, 0, 5.00, NULL, 'barra', NULL, '2025-05-16 01:45:29', '2025-05-16 01:45:29'),
(345, 'TRA-008', 3, 'Daikiri de Fresa', 0.00, NULL, 'vaso', 0, 0, 5.00, NULL, 'barra', NULL, '2025-05-16 01:45:29', '2025-05-16 01:45:29'),
(346, 'TRA-009', 3, 'Daikiri de Parchita', 0.00, NULL, 'vaso', 0, 0, 5.00, NULL, 'barra', NULL, '2025-05-16 01:45:29', '2025-05-16 01:45:29'),
(347, 'EX-001', 3, 'Jugo Yukery 1,5', 0.00, NULL, 'unidad', 0, 0, 6.00, NULL, 'barra', NULL, '2025-05-16 01:45:29', '2025-05-16 01:45:29'),
(348, 'EX-002', 3, 'Juguito', 0.00, NULL, 'unidad', 0, 0, 1.50, NULL, 'barra', NULL, '2025-05-16 01:45:29', '2025-05-16 01:45:29'),
(349, 'EX-003', 3, 'Refresco', 0.00, NULL, 'unidad', 0, 0, 1.00, NULL, 'barra', NULL, '2025-05-16 01:45:29', '2025-05-16 01:45:29'),
(350, 'EX-004', 3, 'Agua Minalba', 0.00, NULL, 'unidad', 0, 0, 1.00, NULL, 'barra', NULL, '2025-05-16 01:45:29', '2025-05-16 01:45:29'),
(351, 'EX-005', 3, 'Malta', 0.00, NULL, 'unidad', 0, 0, 1.00, NULL, 'barra', NULL, '2025-05-16 01:45:29', '2025-05-16 01:45:29'),
(352, 'EX-006', 3, 'Refresco de Lata', 0.00, NULL, 'unidad', 0, 0, 2.00, NULL, 'barra', NULL, '2025-05-16 01:45:29', '2025-05-16 01:45:29'),
(353, 'EX-007', 3, 'Lipton', 0.00, NULL, 'unidad', 0, 0, 2.00, NULL, 'barra', NULL, '2025-05-16 01:45:29', '2025-05-16 01:45:29'),
(354, 'EX-008', 3, 'Agua Perrier', 0.00, NULL, 'unidad', 0, 0, 3.00, NULL, 'barra', NULL, '2025-05-16 01:45:30', '2025-05-16 01:45:30'),
(355, 'EX-009', 3, 'Gatorade', 0.00, NULL, 'unidad', 0, 0, 3.00, NULL, 'barra', NULL, '2025-05-16 01:45:30', '2025-05-16 01:45:30'),
(356, 'EX-010', 3, 'Red Bull', 0.00, NULL, 'unidad', 0, 0, 4.00, NULL, 'barra', NULL, '2025-05-16 01:45:30', '2025-05-16 01:45:30'),
(357, 'EX-011', 3, 'Monster', 0.00, NULL, 'unidad', 0, 0, 5.00, NULL, 'barra', NULL, '2025-05-16 01:45:30', '2025-05-16 01:45:30'),
(358, 'EX-012', 3, 'Refresco de 2Lt', 0.00, NULL, 'unidad', 0, 0, 4.00, NULL, 'barra', NULL, '2025-05-16 01:45:30', '2025-05-16 01:45:30'),
(359, 'CIG-001', 3, 'Cigarrillo Belmon', 0.00, NULL, 'caja', 0, 0, 4.00, NULL, 'barra', NULL, '2025-05-16 01:45:30', '2025-05-16 01:45:30'),
(360, 'CIG-002', 3, 'Cigarrillo Lucky', 0.00, NULL, 'caja', 0, 0, 5.00, NULL, 'barra', NULL, '2025-05-16 01:45:30', '2025-05-16 01:45:30');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `roles`
--
-- Creación: 14-05-2025 a las 19:09:44
--

CREATE TABLE `roles` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `guard_name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `roles`
--

INSERT INTO `roles` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
(1, 'Administrador', 'web', '2025-05-14 19:13:06', '2025-05-14 19:13:06'),
(2, 'Supervisor', 'web', '2025-05-14 19:13:06', '2025-05-14 19:13:06'),
(3, 'Cajero', 'web', '2025-05-14 19:13:06', '2025-05-14 19:13:06');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `role_has_permissions`
--
-- Creación: 14-05-2025 a las 19:09:47
--

CREATE TABLE `role_has_permissions` (
  `permission_id` bigint(20) UNSIGNED NOT NULL,
  `role_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sessions`
--
-- Creación: 05-05-2025 a las 19:10:40
-- Última actualización: 22-05-2025 a las 19:19:22
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('fgIJ0Plg1yQO8yAeiXXB8DGD499rD8sjIRfwwta3', 1, '192.168.1.102', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36', 'YTo1OntzOjY6Il90b2tlbiI7czo0MDoiSzFtWGhGTmtNaEhGOGEzWkVHRWtoVUh6YXllc1JOZ09WYlhySkxsUyI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Njc6Imh0dHA6Ly8xOTIuMTY4LjEuMTAyL29mdW1tZWxsaS1wb3MtbWFpbi9wdWJsaWMvY3VlbnRhcy9yZXN1bWVuLWFyZWEiO31zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aToxO3M6MTI6ImFyZWFfdHJhYmFqbyI7czo2OiJQT1MtNTgiO30=', 1747935535),
('Hfzu9wiELmfMrtDNqB6cRWG3WcqWFtB0oFqYH36a', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36', 'YTo1OntzOjY6Il90b2tlbiI7czo0MDoiajFZQ3BsbUs5N2hsMkpMMlJlNk1VeUtuNWljeUdwbWdoQ052VUtjeSI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzM6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9pbnZlbnRhcmlvcyI7fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjE7czoxMjoiYXJlYV90cmFiYWpvIjtzOjY6IlBPUy01OCI7fQ==', 1747941562);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `users`
--
-- Creación: 14-05-2025 a las 22:41:45
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `role` varchar(255) NOT NULL DEFAULT 'Cajero'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`, `role`) VALUES
(1, 'Admin OfumMelli', 'admin@ofummelli.com', NULL, '$2y$12$s4xwdEAbnrQvPx8g80IAG.2Y74tBCE9tIX1YF4JxGW6Ak0kIzuu7u', NULL, '2025-05-05 19:12:03', '2025-05-05 19:12:03', 'Admin'),
(10, 'María Eugenia Girott', 'maria-girott@ofummelli.com', NULL, '$2y$12$OekIsAjb0Vz.1U5cAnj8QeMG1C7tzz4/RTO60qKnVR6Wml6eExv2.', NULL, '2025-05-14 22:55:24', '2025-05-14 22:55:24', 'Cajero'),
(11, 'Karla Olivero', 'karla-olivero@ofummelli.com', NULL, '$2y$12$sQaGYPromMlowgi3HvplvOYaBfwfKuqKZSV2fa.a.nbil4BHwrDOO', NULL, '2025-05-14 22:55:24', '2025-05-14 22:55:24', 'Cajero'),
(12, 'Stefhani Montero', 'stefhani-montero@ofummelli.com', NULL, '$2y$12$EUHzrH1zyGsJfQtgbn0LDeFU1JEuv1InkbBkZDh7n/BB8Igc5fh0K', NULL, '2025-05-14 22:55:24', '2025-05-14 22:55:24', 'Cajero'),
(13, 'Andreina Berne', 'andreina-berne@ofummelli.com', NULL, '$2y$12$sz70TQm22ViPwVQu0G2nQe9nU2h3VtAkp4vwbdXcPC8PFSGoYVwKy', NULL, '2025-05-14 22:55:27', '2025-05-14 22:55:27', 'Cajero');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `areas`
--
ALTER TABLE `areas`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- Indices de la tabla `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`);

--
-- Indices de la tabla `clientes`
--
ALTER TABLE `clientes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `clientes_cedula_unique` (`cedula`);

--
-- Indices de la tabla `config`
--
ALTER TABLE `config`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `config_key_unique` (`key`);

--
-- Indices de la tabla `cuentas`
--
ALTER TABLE `cuentas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `cuentas_cliente_id_foreign` (`cliente_id`),
  ADD KEY `cuentas_usuario_id_foreign` (`usuario_id`);

--
-- Indices de la tabla `cuenta_producto`
--
ALTER TABLE `cuenta_producto`
  ADD PRIMARY KEY (`id`),
  ADD KEY `cuenta_producto_cuenta_id_foreign` (`cuenta_id`),
  ADD KEY `cuenta_producto_producto_id_foreign` (`producto_id`);

--
-- Indices de la tabla `detalle_cuentas`
--
ALTER TABLE `detalle_cuentas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `detalle_cuentas_cuenta_id_foreign` (`cuenta_id`),
  ADD KEY `detalle_cuentas_producto_id_foreign` (`producto_id`);

--
-- Indices de la tabla `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indices de la tabla `inventarios`
--
ALTER TABLE `inventarios`
  ADD PRIMARY KEY (`id`),
  ADD KEY `inventarios_producto_id_foreign` (`producto_id`);

--
-- Indices de la tabla `inventario_productos`
--
ALTER TABLE `inventario_productos`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `codigo` (`codigo`),
  ADD KEY `inventario_productos_producto_id_foreign` (`producto_id`);

--
-- Indices de la tabla `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indices de la tabla `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `lotes`
--
ALTER TABLE `lotes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `lotes_producto_id_index` (`producto_id`);

--
-- Indices de la tabla `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `model_has_permissions`
--
ALTER TABLE `model_has_permissions`
  ADD PRIMARY KEY (`permission_id`,`model_id`,`model_type`),
  ADD KEY `model_has_permissions_model_id_model_type_index` (`model_id`,`model_type`);

--
-- Indices de la tabla `model_has_roles`
--
ALTER TABLE `model_has_roles`
  ADD PRIMARY KEY (`role_id`,`model_id`,`model_type`),
  ADD KEY `model_has_roles_model_id_model_type_index` (`model_id`,`model_type`);

--
-- Indices de la tabla `movimientos`
--
ALTER TABLE `movimientos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `movimientos_lote_id_foreign` (`lote_id`),
  ADD KEY `movimientos_user_id_foreign` (`user_id`),
  ADD KEY `movimientos_inventario_id_foreign` (`inventario_id`);

--
-- Indices de la tabla `pagos`
--
ALTER TABLE `pagos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pagos_cuenta_id_foreign` (`cuenta_id`);

--
-- Indices de la tabla `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indices de la tabla `permissions`
--
ALTER TABLE `permissions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `permissions_name_guard_name_unique` (`name`,`guard_name`);

--
-- Indices de la tabla `productos`
--
ALTER TABLE `productos`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `productos_codigo_unique` (`codigo`),
  ADD KEY `productos_area_id_foreign` (`area_id`);

--
-- Indices de la tabla `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `roles_name_guard_name_unique` (`name`,`guard_name`);

--
-- Indices de la tabla `role_has_permissions`
--
ALTER TABLE `role_has_permissions`
  ADD PRIMARY KEY (`permission_id`,`role_id`),
  ADD KEY `role_has_permissions_role_id_foreign` (`role_id`);

--
-- Indices de la tabla `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indices de la tabla `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `areas`
--
ALTER TABLE `areas`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `clientes`
--
ALTER TABLE `clientes`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `config`
--
ALTER TABLE `config`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `cuentas`
--
ALTER TABLE `cuentas`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=158;

--
-- AUTO_INCREMENT de la tabla `cuenta_producto`
--
ALTER TABLE `cuenta_producto`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `detalle_cuentas`
--
ALTER TABLE `detalle_cuentas`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `inventarios`
--
ALTER TABLE `inventarios`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=112;

--
-- AUTO_INCREMENT de la tabla `inventario_productos`
--
ALTER TABLE `inventario_productos`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT de la tabla `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `lotes`
--
ALTER TABLE `lotes`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=47;

--
-- AUTO_INCREMENT de la tabla `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT de la tabla `movimientos`
--
ALTER TABLE `movimientos`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT de la tabla `pagos`
--
ALTER TABLE `pagos`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `productos`
--
ALTER TABLE `productos`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=370;

--
-- AUTO_INCREMENT de la tabla `roles`
--
ALTER TABLE `roles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `cuentas`
--
ALTER TABLE `cuentas`
  ADD CONSTRAINT `cuentas_cliente_id_foreign` FOREIGN KEY (`cliente_id`) REFERENCES `clientes` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `cuentas_usuario_id_foreign` FOREIGN KEY (`usuario_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `cuenta_producto`
--
ALTER TABLE `cuenta_producto`
  ADD CONSTRAINT `cuenta_producto_cuenta_id_foreign` FOREIGN KEY (`cuenta_id`) REFERENCES `cuentas` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `cuenta_producto_producto_id_foreign` FOREIGN KEY (`producto_id`) REFERENCES `productos` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `detalle_cuentas`
--
ALTER TABLE `detalle_cuentas`
  ADD CONSTRAINT `detalle_cuentas_cuenta_id_foreign` FOREIGN KEY (`cuenta_id`) REFERENCES `cuentas` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `detalle_cuentas_producto_id_foreign` FOREIGN KEY (`producto_id`) REFERENCES `productos` (`id`);

--
-- Filtros para la tabla `inventarios`
--
ALTER TABLE `inventarios`
  ADD CONSTRAINT `inventarios_producto_id_foreign` FOREIGN KEY (`producto_id`) REFERENCES `productos` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `inventario_productos`
--
ALTER TABLE `inventario_productos`
  ADD CONSTRAINT `inventario_productos_producto_id_foreign` FOREIGN KEY (`producto_id`) REFERENCES `productos` (`id`) ON DELETE SET NULL;

--
-- Filtros para la tabla `lotes`
--
ALTER TABLE `lotes`
  ADD CONSTRAINT `lotes_producto_id_foreign` FOREIGN KEY (`producto_id`) REFERENCES `inventario_productos` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `model_has_permissions`
--
ALTER TABLE `model_has_permissions`
  ADD CONSTRAINT `model_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `model_has_roles`
--
ALTER TABLE `model_has_roles`
  ADD CONSTRAINT `model_has_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `movimientos`
--
ALTER TABLE `movimientos`
  ADD CONSTRAINT `movimientos_inventario_id_foreign` FOREIGN KEY (`inventario_id`) REFERENCES `inventario_productos` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `movimientos_lote_id_foreign` FOREIGN KEY (`lote_id`) REFERENCES `lotes` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `movimientos_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Filtros para la tabla `pagos`
--
ALTER TABLE `pagos`
  ADD CONSTRAINT `pagos_cuenta_id_foreign` FOREIGN KEY (`cuenta_id`) REFERENCES `cuentas` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `productos`
--
ALTER TABLE `productos`
  ADD CONSTRAINT `productos_area_id_foreign` FOREIGN KEY (`area_id`) REFERENCES `areas` (`id`) ON DELETE SET NULL;

--
-- Filtros para la tabla `role_has_permissions`
--
ALTER TABLE `role_has_permissions`
  ADD CONSTRAINT `role_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `role_has_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
