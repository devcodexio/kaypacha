-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 21-04-2026 a las 06:03:57
-- Versión del servidor: 8.4.6
-- Versión de PHP: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `restaurante`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categorias_platos`
--

CREATE TABLE `categorias_platos` (
  `id` int NOT NULL,
  `nombre` varchar(150) COLLATE utf8mb4_general_ci NOT NULL,
  `descripcion` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `categorias_platos`
--

INSERT INTO `categorias_platos` (`id`, `nombre`, `descripcion`) VALUES
(1, 'Entradas', 'Causas \r\nSopas \r\nAnticuchos \r\nTablitas mixtas \r\nTiraditos/Ceviches pequeños'),
(2, 'Platos Marinos', 'Ceviches\r\n\r\nJaleas\r\n\r\nSudados\r\n\r\nParrilla marina\r\n\r\nArroz con mariscos'),
(3, 'Carnes / Parrillas', 'Bife,\r\nLomo fino,\r\nCostillas BBQ,\r\nParrilla mixta,\r\nPollo a la parrilla.'),
(4, 'Pastas y Arroces', 'Lasagna\r\nFettuccine\r\nRisotto\r\nArroz chaufa\r\nTallarines saltados');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `configuracion_sitio`
--

CREATE TABLE `configuracion_sitio` (
  `id` int NOT NULL,
  `nombre_restaurante` varchar(150) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `direccion` varchar(200) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `telefono` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `whatsapp` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `correo_contacto` varchar(150) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `horario` varchar(200) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `facebook` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `instagram` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `tiktok` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `logs_admin`
--

CREATE TABLE `logs_admin` (
  `id` int NOT NULL,
  `usuario_id` int NOT NULL,
  `accion` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `fecha` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `mesas`
--

CREATE TABLE `mesas` (
  `id` int NOT NULL,
  `numero_mesa` int NOT NULL,
  `capacidad` int NOT NULL,
  `estado` varchar(20) COLLATE utf8mb4_general_ci DEFAULT 'disponible',
  `pos_top` varchar(20) COLLATE utf8mb4_general_ci DEFAULT '100px',
  `pos_left` varchar(20) COLLATE utf8mb4_general_ci DEFAULT '100px',
  `tipo_forma` enum('circular','rectangular','grande') COLLATE utf8mb4_general_ci DEFAULT 'circular',
  `zona` varchar(50) COLLATE utf8mb4_general_ci DEFAULT 'general',
  `activo` tinyint(1) DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `mesas`
--

INSERT INTO `mesas` (`id`, `numero_mesa`, `capacidad`, `estado`, `pos_top`, `pos_left`, `tipo_forma`, `zona`, `activo`) VALUES
(1, 1, 10, 'disponible', '25px', '475px', 'grande', 'general', 1),
(3, 2, 10, 'disponible', '30px', '30px', 'circular', 'general', 0),
(4, 3, 12, 'disponible', '350px', '30px', 'circular', 'general', 0),
(5, 4, 6, 'disponible', '450px', '30px', 'circular', 'general', 0),
(6, 5, 8, 'libre', '550px', '30px', 'circular', 'general', 0),
(7, 6, 10, 'libre', '175px', '75px', 'circular', 'general', 1),
(9, 7, 4, 'disponible', '100px', '100px', 'circular', 'general', 0),
(10, 8, 4, 'libre', '825px', '350px', 'circular', 'general', 1),
(11, 9, 8, 'disponible', '15px', '750px', 'grande', 'general', 1),
(12, 10, 2, 'disponible', '125px', '725px', 'circular', 'general', 0),
(13, 11, 2, 'libre', '200px', '725px', 'circular', 'general', 0),
(14, 12, 2, 'libre', '275px', '725px', 'circular', 'general', 1),
(15, 13, 2, 'libre', '350px', '725px', 'circular', 'general', 1),
(16, 14, 2, 'libre', '500px', '225px', 'circular', 'general', 1),
(17, 15, 6, 'reservada', '175px', '325px', 'rectangular', 'general', 1),
(18, 16, 4, 'ocupada', '175px', '475px', 'rectangular', 'general', 1),
(19, 17, 4, 'libre', '600px', '175px', 'rectangular', 'general', 1),
(20, 18, 2, 'disponible', '125px', '600px', 'circular', 'general', 1),
(21, 19, 2, 'libre', '225px', '600px', 'circular', 'general', 1),
(22, 20, 4, 'libre', '350px', '575px', 'rectangular', 'general', 1),
(23, 21, 4, 'libre', '475px', '600px', 'rectangular', 'general', 1),
(24, 22, 4, 'libre', '575px', '600px', 'rectangular', 'general', 1),
(25, 23, 4, 'libre', '400px', '200px', 'rectangular', 'general', 1),
(26, 24, 4, 'libre', '500px', '725px', 'rectangular', 'general', 1),
(27, 25, 4, 'disponible', '150px', '200px', 'rectangular', 'general', 1),
(28, 26, 4, 'disponible', '325px', '200px', 'rectangular', 'general', 1),
(29, 27, 8, 'reservada', '50px', '275px', 'grande', 'general', 1),
(30, 28, 4, 'disponible', '225px', '200px', 'rectangular', 'general', 1),
(31, 29, 2, 'libre', '300px', '20px', 'circular', 'general', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `nosotros`
--

CREATE TABLE `nosotros` (
  `id` int NOT NULL,
  `tipo` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `titulo` varchar(150) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `descripcion` text COLLATE utf8mb4_general_ci NOT NULL,
  `imagen` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `creado_en` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `nosotros`
--

INSERT INTO `nosotros` (`id`, `tipo`, `titulo`, `descripcion`, `imagen`, `creado_en`) VALUES
(2, 'mision', 'MISIÓN – RESTAURANTE KAYPACHA', 'Nuestra misión es ofrecer experiencias gastronómicas auténticas que celebren los sabores del Perú, combinando tradición y creatividad en cada plato.\r\nBuscamos brindar un servicio cálido, cercano y memorable, donde cada cliente se sienta parte de nuestra familia.\r\nTrabajamos con ingredientes frescos y de calidad, apoyando a productores locales y promoviendo el respeto por nuestra cultura y raíces.\r\nNos comprometemos a crear un espacio acogedor, elegante y lleno de buen ambiente, donde compartir momentos especiales sea tan importante como disfrutar de una buena comida.', '1763906964_anticucho.jpeg', '2025-11-23 09:11:30'),
(3, 'vision', 'VISIÓN – RESTAURANTE KAYPACHA', 'Ser un referente gastronómico reconocido por elevar la cocina peruana a una experiencia única, fusionando tradición, innovación y cultura.\r\nAspiramos a consolidarnos como el restaurante preferido por quienes buscan calidad, autenticidad y un ambiente excepcional.\r\nQueremos crecer de manera sostenible, impulsando el talento local, promoviendo el respeto por nuestra identidad culinaria y expandiendo nuestra propuesta hacia nuevos espacios donde podamos compartir la esencia de nuestra cocina con el mundo.', '1763906964_anticucho.jpeg', '2025-11-23 09:11:30');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pagos_paypal`
--

CREATE TABLE `pagos_paypal` (
  `id` int NOT NULL,
  `reserva_id` int NOT NULL,
  `paypal_order_id` varchar(100) NOT NULL,
  `paypal_capture_id` varchar(100) DEFAULT NULL,
  `monto` decimal(10,2) NOT NULL,
  `moneda` varchar(10) DEFAULT 'USD',
  `estado` varchar(50) DEFAULT 'pendiente',
  `fecha_pago` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `pagos_paypal`
--

INSERT INTO `pagos_paypal` (`id`, `reserva_id`, `paypal_order_id`, `paypal_capture_id`, `monto`, `moneda`, `estado`, `fecha_pago`) VALUES
(1, 9, '8G970606HY763213C', '5B929357G19911924', 10.00, 'USD', 'COMPLETED', '2026-04-02 00:22:28'),
(2, 10, '50T75056HE4221626', '3AJ86290CE700245Y', 10.00, 'USD', 'COMPLETED', '2026-04-05 23:24:41'),
(3, 11, '78301091YY700074W', '7SX79166TF066351M', 10.00, 'USD', 'COMPLETED', '2026-04-05 23:53:43'),
(4, 12, '4P195977BB9782842', '3BC40501E18167309', 10.00, 'USD', 'COMPLETED', '2026-04-06 00:02:30'),
(5, 19, '19R35745R10372150', '2UJ02609XE150854W', 10.00, 'USD', 'COMPLETED', '2026-04-06 00:28:31'),
(6, 20, '55607344XS8886153', '6VS97891DL991841F', 10.00, 'USD', 'COMPLETED', '2026-04-06 00:43:05'),
(7, 21, '7S510241T3512632H', '84L92587VG9756334', 10.00, 'USD', 'COMPLETED', '2026-04-06 00:43:46'),
(8, 22, '36269517P4159782J', '5DC108832E104154L', 10.00, 'USD', 'COMPLETED', '2026-04-06 00:46:26'),
(9, 23, '72816924EG064943A', '0G105356UE3245525', 10.00, 'USD', 'COMPLETED', '2026-04-06 00:50:50'),
(10, 26, '0HY28143DJ970064V', '49W23362F1670791T', 10.00, 'USD', 'COMPLETED', '2026-04-06 00:59:28'),
(11, 30, '466112569C428071G', '5EK97916CG060211L', 10.00, 'USD', 'COMPLETED', '2026-04-06 01:02:10'),
(12, 34, '51708972PX113243B', '3VV483139F5091628', 10.00, 'USD', 'COMPLETED', '2026-04-06 21:04:56'),
(13, 43, '25T360180S6080255', '65P880574E824712K', 10.00, 'USD', 'COMPLETED', '2026-04-11 00:39:07');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pagos_yape`
--

CREATE TABLE `pagos_yape` (
  `id` int NOT NULL,
  `reserva_id` int NOT NULL,
  `imagen` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `monto` decimal(10,2) NOT NULL,
  `verificado` tinyint DEFAULT '0',
  `fecha` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `pagos_yape`
--

INSERT INTO `pagos_yape` (`id`, `reserva_id`, `imagen`, `monto`, `verificado`, `fecha`) VALUES
(2, 3, '1765495387_WhatsApp Image 2025-12-11 at 6.21.46 PM.jpeg', 40.00, 0, '2025-12-11 18:23:07'),
(3, 4, '1765504873_WhatsApp Image 2025-12-11 at 6.21.46 PM (1).jpeg', 100.00, 0, '2025-12-11 21:01:13'),
(4, 5, '1765505091_WhatsApp Image 2025-12-11 at 6.21.46 PM (1).jpeg', 100.00, 0, '2025-12-11 21:04:51'),
(5, 7, '1766126354_WhatsApp Image 2025-12-11 at 6.21.46 PM (1).jpeg', 100.00, 1, '2025-12-19 01:39:14');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `platos`
--

CREATE TABLE `platos` (
  `id` int NOT NULL,
  `categoria_id` int NOT NULL,
  `nombre` varchar(150) COLLATE utf8mb4_general_ci NOT NULL,
  `descripcion` varchar(500) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `precio` decimal(10,2) NOT NULL,
  `imagen` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `disponible` tinyint DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `platos`
--

INSERT INTO `platos` (`id`, `categoria_id`, `nombre`, `descripcion`, `precio`, `imagen`, `disponible`) VALUES
(1, 2, 'mostritos', 'rico', 12.00, '1763935180_causalimeniaweb.jpg', 1),
(2, 1, 'Ceviche Clásico Peruano', 'Pescado fresco marinado en jugo de limón, acompañado de cebolla morada, camote, cancha y choclo. Un clásico lleno de frescura.', 12.00, '1763906964_anticucho.jpeg', 1),
(3, 1, 'Tiradito de Ají Amarillo', 'Finas láminas de pescado bañadas en una crema suave de ají amarillo, limón y especias. Fresco, cítrico y elegante.', 12.00, '1763935190_ceviceh.jpg', 1),
(4, 1, 'Arroz con Mariscos', 'Arroz cremoso mezclado con calamares, conchas, camarones y un toque de vino blanco. Sabor intenso y marino.', 12.00, '1763934342_gala.jpeg', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `reservas`
--

CREATE TABLE `reservas` (
  `id` int NOT NULL,
  `usuario_id` int NOT NULL,
  `mesa_id` int NOT NULL,
  `fecha` date NOT NULL,
  `hora` time NOT NULL,
  `cantidad_personas` int NOT NULL,
  `estado` varchar(20) COLLATE utf8mb4_general_ci DEFAULT 'pendiente',
  `notas` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `comprobante_yape` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `total` decimal(10,2) DEFAULT NULL,
  `creado_en` datetime DEFAULT CURRENT_TIMESTAMP,
  `metodo_pago` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `pagado` tinyint(1) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `reservas`
--

INSERT INTO `reservas` (`id`, `usuario_id`, `mesa_id`, `fecha`, `hora`, `cantidad_personas`, `estado`, `notas`, `comprobante_yape`, `total`, `creado_en`, `metodo_pago`, `pagado`) VALUES
(1, 2, 1, '2025-11-06', '12:02:00', 12, 'finalizada', '', '1763907219_444.png', NULL, '2025-11-23 09:13:15', NULL, 0),
(2, 2, 1, '2025-12-12', '12:02:00', 25, 'finalizada', '', NULL, NULL, '2025-11-23 12:06:56', NULL, 0),
(3, 2, 3, '2025-11-06', '16:09:00', 0, 'finalizada', '', '1765495387_WhatsApp Image 2025-12-11 at 6.21.46 PM.jpeg', NULL, '2025-11-23 15:09:48', NULL, 0),
(4, 2, 4, '2025-12-23', '13:52:00', 0, 'finalizada', 'celebrar un cumpleaños ', '1765504873_WhatsApp Image 2025-12-11 at 6.21.46 PM (1).jpeg', NULL, '2025-12-11 13:53:04', NULL, 0),
(5, 2, 4, '2025-12-15', '21:05:00', 0, 'finalizada', '', '1765505091_WhatsApp Image 2025-12-11 at 6.21.46 PM (1).jpeg', NULL, '2025-12-11 21:04:26', NULL, 0),
(6, 2, 5, '2025-12-02', '01:16:00', 0, 'finalizada', 'g', NULL, NULL, '2025-12-19 01:14:08', NULL, 0),
(7, 2, 6, '2025-12-25', '10:00:00', 0, 'finalizada', 'cumpleaños', '1766126354_WhatsApp Image 2025-12-11 at 6.21.46 PM (1).jpeg', NULL, '2025-12-19 01:38:36', NULL, 0),
(8, 5, 7, '2026-04-05', '05:35:00', 0, 'finalizada', '', NULL, NULL, '2026-03-31 23:35:41', NULL, 0),
(9, 5, 7, '2026-04-27', '04:09:00', 0, 'cancelado', '', NULL, NULL, '2026-04-01 00:09:35', 'paypal', 1),
(10, 5, 5, '2026-04-06', '03:23:00', 0, 'finalizada', '', NULL, NULL, '2026-04-05 23:24:03', 'paypal', 1),
(11, 5, 6, '2026-04-30', '03:52:00', 7, 'confirmado', '', NULL, NULL, '2026-04-05 23:53:25', 'paypal', 1),
(12, 5, 6, '2026-04-10', '03:02:00', 10, 'finalizada', '', NULL, NULL, '2026-04-06 00:02:20', 'paypal', 1),
(13, 5, 6, '2026-04-10', '03:03:00', 10, 'finalizada', '', NULL, NULL, '2026-04-06 00:03:13', NULL, 0),
(14, 5, 1, '2026-04-10', '17:00:00', 1, 'finalizada', '', NULL, NULL, '2026-04-06 00:24:22', NULL, 0),
(15, 5, 1, '2026-04-06', '17:00:00', 1, 'finalizada', '', NULL, NULL, '2026-04-06 00:24:56', NULL, 0),
(16, 5, 3, '2026-04-06', '17:00:00', 1, 'finalizada', '', NULL, NULL, '2026-04-06 00:25:21', NULL, 0),
(17, 5, 4, '2026-04-06', '17:00:00', 1, 'finalizada', '', NULL, NULL, '2026-04-06 00:25:26', NULL, 0),
(18, 5, 5, '2026-04-06', '17:00:00', 1, 'finalizada', '', NULL, NULL, '2026-04-06 00:25:31', NULL, 0),
(19, 5, 1, '2026-04-20', '17:00:00', 1, 'finalizada', '', NULL, NULL, '2026-04-06 00:28:16', 'paypal', 1),
(20, 5, 5, '2026-04-06', '18:00:00', 2, 'finalizada', '', NULL, NULL, '2026-04-06 00:42:53', 'paypal', 1),
(21, 5, 5, '2026-04-10', '16:00:00', 2, 'finalizada', '', NULL, NULL, '2026-04-06 00:43:36', 'paypal', 1),
(22, 5, 5, '2026-04-10', '16:00:00', 2, 'finalizada', '', NULL, NULL, '2026-04-06 00:46:11', 'paypal', 1),
(23, 5, 5, '2026-04-11', '07:00:00', 4, 'finalizada', '', NULL, NULL, '2026-04-06 00:50:39', 'paypal', 1),
(24, 5, 5, '2026-04-11', '07:00:00', 4, 'finalizada', '', NULL, NULL, '2026-04-06 00:51:20', NULL, 0),
(25, 5, 5, '2026-04-11', '09:00:00', 4, 'finalizada', '', NULL, NULL, '2026-04-06 00:52:27', NULL, 0),
(26, 6, 5, '2026-04-11', '07:00:00', 2, 'finalizada', '', NULL, NULL, '2026-04-06 00:59:18', 'paypal', 1),
(27, 6, 5, '2026-04-11', '07:00:00', 2, 'finalizada', '', NULL, NULL, '2026-04-06 01:00:43', NULL, 0),
(28, 6, 5, '2026-04-06', '07:00:00', 2, 'finalizada', '', NULL, NULL, '2026-04-06 01:00:48', NULL, 0),
(29, 5, 6, '2026-04-11', '07:00:00', 2, 'finalizada', '', NULL, NULL, '2026-04-06 01:01:41', NULL, 0),
(30, 5, 6, '2026-04-06', '07:00:00', 2, 'finalizada', '', NULL, NULL, '2026-04-06 01:02:00', 'paypal', 1),
(31, 6, 5, '2026-04-12', '07:00:00', 2, 'finalizada', '', NULL, NULL, '2026-04-06 01:06:48', NULL, 0),
(32, 6, 5, '2026-04-11', '11:00:00', 2, 'finalizada', '', NULL, NULL, '2026-04-06 01:14:04', NULL, 0),
(33, 5, 5, '2026-04-10', '10:00:00', 5, 'finalizada', '', NULL, NULL, '2026-04-06 21:04:16', NULL, 0),
(34, 5, 6, '2026-04-10', '08:00:00', 5, 'finalizada', '', NULL, NULL, '2026-04-06 21:04:33', 'paypal', 1),
(35, 5, 1, '2026-04-11', '09:00:00', 10, 'finalizada', '', NULL, NULL, '2026-04-06 21:07:03', NULL, 0),
(36, 5, 1, '2026-04-10', '13:00:00', 10, 'finalizada', '', NULL, NULL, '2026-04-06 21:12:20', NULL, 0),
(37, 5, 6, '2026-04-23', '07:00:00', 8, 'pendiente', '', NULL, NULL, '2026-04-10 00:52:51', NULL, 0),
(38, 5, 1, '2026-04-11', '11:00:00', 2, 'finalizada', '', NULL, NULL, '2026-04-10 00:55:00', NULL, 0),
(39, 5, 12, '2026-04-15', '03:11:00', 2, 'finalizada', '', NULL, NULL, '2026-04-10 01:11:54', NULL, 0),
(40, 5, 3, '2026-04-10', '18:00:00', 10, 'finalizada', '', NULL, NULL, '2026-04-10 01:15:46', NULL, 0),
(41, 5, 5, '2026-04-10', '19:22:00', 8, 'finalizada', '', NULL, NULL, '2026-04-10 01:21:10', NULL, 0),
(42, 5, 12, '2026-04-11', '19:30:00', 0, 'finalizada', '', NULL, NULL, '2026-04-10 01:33:08', NULL, 0),
(43, 5, 12, '2026-04-20', '19:30:00', 0, 'finalizada', '', NULL, NULL, '2026-04-11 00:38:49', 'paypal', 1),
(44, 5, 11, '2026-04-20', '22:45:00', 0, 'finalizada', '', NULL, NULL, '2026-04-11 19:45:47', NULL, 0),
(45, 5, 1, '2026-04-24', '22:30:00', 0, 'pendiente', '', NULL, NULL, '2026-04-15 20:30:04', NULL, 0),
(46, 5, 29, '2026-04-24', '10:12:00', 0, 'pendiente', '', NULL, NULL, '2026-04-16 00:13:12', NULL, 0),
(47, 5, 11, '2026-04-17', '13:19:00', 0, 'finalizada', '', NULL, NULL, '2026-04-17 00:19:42', NULL, 0),
(48, 5, 11, '2026-04-17', '10:21:00', 0, 'finalizada', '', NULL, NULL, '2026-04-17 00:19:59', NULL, 0),
(49, 5, 27, '2026-04-18', '12:50:00', 0, 'finalizada', '', NULL, NULL, '2026-04-17 19:51:01', NULL, 0),
(50, 5, 3, '2026-04-18', '15:51:00', 0, 'finalizada', '', NULL, NULL, '2026-04-17 19:51:40', NULL, 0),
(51, 5, 4, '2026-04-19', '09:22:00', 0, 'finalizada', '', NULL, NULL, '2026-04-19 10:22:49', NULL, 0),
(52, 5, 30, '2026-04-19', '09:25:00', 0, 'finalizada', '', NULL, NULL, '2026-04-19 10:25:13', NULL, 0),
(53, 6, 9, '2026-04-20', '14:41:00', 0, 'finalizada', '', NULL, NULL, '2026-04-19 11:41:07', NULL, 0),
(54, 6, 20, '2026-04-20', '13:41:00', 0, 'finalizada', '', NULL, NULL, '2026-04-19 11:41:29', NULL, 0),
(55, 6, 27, '2026-04-20', '14:42:00', 0, 'finalizada', '', NULL, NULL, '2026-04-19 11:42:06', NULL, 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `roles`
--

CREATE TABLE `roles` (
  `id` int NOT NULL,
  `nombre` varchar(50) COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `roles`
--

INSERT INTO `roles` (`id`, `nombre`) VALUES
(1, 'admin'),
(2, 'empleado'),
(3, 'cliente'),
(4, 'recepcionista');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `testimonios`
--

CREATE TABLE `testimonios` (
  `id` int NOT NULL,
  `usuario_id` int NOT NULL,
  `mensaje` varchar(500) COLLATE utf8mb4_general_ci NOT NULL,
  `calificacion` int DEFAULT NULL,
  `creado_en` datetime DEFAULT CURRENT_TIMESTAMP,
  `estado` varchar(20) COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'activo'
) ;

--
-- Volcado de datos para la tabla `testimonios`
--

INSERT INTO `testimonios` (`id`, `usuario_id`, `mensaje`, `calificacion`, `creado_en`, `estado`) VALUES
(1, 2, 'Kaypacha es un lugar agradable con una decoración bonita y un ambiente tranquilo. La comida estuvo buena, aunque algunos platos tardaron un poco más de lo esperado en llegar. El sabor es auténtico, pero siento que podrían mejorar la presentación y la rapidez del servicio. Aun así, el personal fue amable y el lugar tiene potencial para ofrecer una experiencia aún mejor. Volvería para probar más opciones del menú.', 3, '2025-11-23 09:16:52', 'activo'),
(2, 2, 'Kaypacha es una experiencia que va más allá de una simple comida. Desde el primer momento te envuelve con su calidez, su decoración inspirada en lo andino y la amabilidad de su personal. Los platos son una verdadera obra de arte: frescos, llenos de sabor y perfectamente presentados. Probé su especialidad de la casa y quedé impresionado; cada bocado tenía ese toque único que te conecta con la gastronomía peruana. Sin duda, Kaypacha es un lugar al que siempre quieres volver, ya sea para disfrutar', 5, '2025-11-23 09:16:52', 'activo');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int NOT NULL,
  `rol_id` int NOT NULL,
  `nombre` varchar(150) COLLATE utf8mb4_general_ci NOT NULL,
  `correo` varchar(150) COLLATE utf8mb4_general_ci NOT NULL,
  `contraseña` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `telefono` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `direccion` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `creado_en` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `rol_id`, `nombre`, `correo`, `contraseña`, `telefono`, `direccion`, `creado_en`) VALUES
(2, 3, 'juan', 'juan@gmail.com', '$2y$10$1xmbPjR7d5slOl0GEwDjZ.AVAFJLVqHagjtoQD.YlHUpOp/gvgV1i', '985632047', 'jr andahuaylas', '2025-11-23 08:42:49'),
(3, 1, 'Administrador', 'admin@correo.com', '$2y$10$APGpHzwWQO1qaJn8gJAFIe1bEorKEeXWCu2ytPBa/LhDZuyffb0Vy', '999999999', 'Oficina principal', '2025-11-23 08:50:47'),
(4, 2, 'samuel', 'samuel@gmail.com', '$2y$10$h3BQyW2AQ8GRVIvAZikZg.Fe8DixGlD65JaZKx/Iwz0EYicIoYhj.', '988888888', 'jr inca', '2025-11-23 09:01:23'),
(5, 3, 'brayham garcia', 'brayham.2503@gmail.com', '$2y$10$OTYpVoWueb.gwLPzn9UqK.nfyqbT5lKCBrbGpJXzsuCUJ6UTmiOLK', '910844065', 'Jr.Salvador Cavero680', '2026-03-31 23:35:03'),
(6, 3, 'estefany', 'estefany@gmail.com', '$2y$10$SJzukQAJa7knxdAhRik/Wu6BkFdE5GxiPd5hwMTrfw02ufSxf4MVK', '999999999', 'Jr.Salvador Cavero681', '2026-04-06 00:56:04'),
(7, 2, 'jei garcia', 'jei@gmail.com', '$2y$10$CybB7CSZbFHyS3D0H/GXluorcG/MY25qeq5bvMRhJgud1MMrm4Ona', '910844065', 'Jr.Salvador Cavero680', '2026-04-16 23:04:32');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `categorias_platos`
--
ALTER TABLE `categorias_platos`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `configuracion_sitio`
--
ALTER TABLE `configuracion_sitio`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `logs_admin`
--
ALTER TABLE `logs_admin`
  ADD PRIMARY KEY (`id`),
  ADD KEY `usuario_id` (`usuario_id`);

--
-- Indices de la tabla `mesas`
--
ALTER TABLE `mesas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_zona_activo` (`zona`,`activo`);

--
-- Indices de la tabla `nosotros`
--
ALTER TABLE `nosotros`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `pagos_paypal`
--
ALTER TABLE `pagos_paypal`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_pago_reserva_paypal` (`reserva_id`);

--
-- Indices de la tabla `pagos_yape`
--
ALTER TABLE `pagos_yape`
  ADD PRIMARY KEY (`id`),
  ADD KEY `reserva_id` (`reserva_id`);

--
-- Indices de la tabla `platos`
--
ALTER TABLE `platos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `categoria_id` (`categoria_id`);

--
-- Indices de la tabla `reservas`
--
ALTER TABLE `reservas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `usuario_id` (`usuario_id`),
  ADD KEY `mesa_id` (`mesa_id`);

--
-- Indices de la tabla `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `testimonios`
--
ALTER TABLE `testimonios`
  ADD PRIMARY KEY (`id`),
  ADD KEY `usuario_id` (`usuario_id`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `correo` (`correo`),
  ADD KEY `rol_id` (`rol_id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `categorias_platos`
--
ALTER TABLE `categorias_platos`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `configuracion_sitio`
--
ALTER TABLE `configuracion_sitio`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `logs_admin`
--
ALTER TABLE `logs_admin`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `mesas`
--
ALTER TABLE `mesas`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT de la tabla `nosotros`
--
ALTER TABLE `nosotros`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `pagos_paypal`
--
ALTER TABLE `pagos_paypal`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT de la tabla `pagos_yape`
--
ALTER TABLE `pagos_yape`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `platos`
--
ALTER TABLE `platos`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `reservas`
--
ALTER TABLE `reservas`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=56;

--
-- AUTO_INCREMENT de la tabla `roles`
--
ALTER TABLE `roles`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `testimonios`
--
ALTER TABLE `testimonios`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `logs_admin`
--
ALTER TABLE `logs_admin`
  ADD CONSTRAINT `logs_admin_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`);

--
-- Filtros para la tabla `pagos_paypal`
--
ALTER TABLE `pagos_paypal`
  ADD CONSTRAINT `fk_pago_reserva_paypal` FOREIGN KEY (`reserva_id`) REFERENCES `reservas` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `pagos_yape`
--
ALTER TABLE `pagos_yape`
  ADD CONSTRAINT `pagos_yape_ibfk_1` FOREIGN KEY (`reserva_id`) REFERENCES `reservas` (`id`);

--
-- Filtros para la tabla `platos`
--
ALTER TABLE `platos`
  ADD CONSTRAINT `platos_ibfk_1` FOREIGN KEY (`categoria_id`) REFERENCES `categorias_platos` (`id`);

--
-- Filtros para la tabla `reservas`
--
ALTER TABLE `reservas`
  ADD CONSTRAINT `reservas_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`),
  ADD CONSTRAINT `reservas_ibfk_2` FOREIGN KEY (`mesa_id`) REFERENCES `mesas` (`id`);

--
-- Filtros para la tabla `testimonios`
--
ALTER TABLE `testimonios`
  ADD CONSTRAINT `testimonios_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`);

--
-- Filtros para la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD CONSTRAINT `usuarios_ibfk_1` FOREIGN KEY (`rol_id`) REFERENCES `roles` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
