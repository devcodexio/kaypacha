-- KAY-PACHA RESTAURANTE - SCHEMA PRO ERP
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

-- --------------------------------------------------------
-- 1. ROLES
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `roles` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nombre` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `roles` (`id`, `nombre`) VALUES 
(1, 'admin'), 
(2, 'empleado'), 
(3, 'cliente');

-- --------------------------------------------------------
-- 2. USUARIOS
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `usuarios` (
  `id` int NOT NULL AUTO_INCREMENT,
  `rol_id` int NOT NULL,
  `nombre` varchar(150) NOT NULL,
  `correo` varchar(150) NOT NULL UNIQUE,
  `contraseña` varchar(255) NOT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `direccion` varchar(255) DEFAULT NULL,
  `creado_en` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  CONSTRAINT `fk_usuarios_roles` FOREIGN KEY (`rol_id`) REFERENCES `roles`(`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Admin: admin@correo.com / admin123
INSERT INTO `usuarios` (`rol_id`, `nombre`, `correo`, `contraseña`) VALUES 
(1, 'Administrador Pro', 'admin@correo.com', '$2y$10$APGpHzwWQO1qaJn8gJAFIe1bEorKEeXWCu2ytPBa/LhDZuyffb0Vy');

-- --------------------------------------------------------
-- 3. CATEGORIAS Y PLATOS
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `categorias_platos` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nombre` varchar(150) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `categorias_platos` (`nombre`) VALUES ('Entradas'), ('Platos Marinos'), ('Carnes'), ('Bebidas');

CREATE TABLE IF NOT EXISTS `platos` (
  `id` int NOT NULL AUTO_INCREMENT,
  `categoria_id` int NOT NULL,
  `nombre` varchar(150) NOT NULL,
  `descripcion` text,
  `precio` decimal(10,2) NOT NULL,
  `imagen` varchar(255) DEFAULT NULL,
  `disponible` tinyint DEFAULT '1',
  PRIMARY KEY (`id`),
  CONSTRAINT `fk_platos_categorias` FOREIGN KEY (`categoria_id`) REFERENCES `categorias_platos`(`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------
-- 4. MESAS (SISTEMA DE CROQUIS)
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `mesas` (
  `id` int NOT NULL AUTO_INCREMENT,
  `numero_mesa` int NOT NULL,
  `capacidad` int NOT NULL,
  `estado` varchar(20) DEFAULT 'disponible',
  `pos_top` varchar(20) DEFAULT '100px',
  `pos_left` varchar(20) DEFAULT '100px',
  `activo` tinyint(1) DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `mesas` (`numero_mesa`, `capacidad`, `estado`, `pos_top`, `pos_left`) VALUES 
(1, 4, 'disponible', '50px', '50px'),
(2, 4, 'disponible', '50px', '200px'),
(3, 8, 'disponible', '200px', '125px');

-- --------------------------------------------------------
-- 5. RESERVAS
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `reservas` (
  `id` int NOT NULL AUTO_INCREMENT,
  `usuario_id` int NOT NULL,
  `mesa_id` int NOT NULL,
  `fecha` date NOT NULL,
  `hora` time NOT NULL,
  `cantidad_personas` int NOT NULL,
  `estado` varchar(20) DEFAULT 'pendiente',
  `metodo_pago` varchar(50) DEFAULT NULL,
  `pagado` tinyint(1) DEFAULT '0',
  `creado_en` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  CONSTRAINT `fk_reservas_usuarios` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios`(`id`),
  CONSTRAINT `fk_reservas_mesas` FOREIGN KEY (`mesa_id`) REFERENCES `mesas`(`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------
-- 6. PAGOS STRIPE (PRO)
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `pagos_stripe` (
  `id` int NOT NULL AUTO_INCREMENT,
  `reserva_id` int NOT NULL,
  `stripe_id` varchar(100) NOT NULL,
  `monto` decimal(10,2) NOT NULL,
  `moneda` varchar(10) DEFAULT 'USD',
  `estado` varchar(50) DEFAULT 'succeeded',
  `fecha_pago` timestamp DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  CONSTRAINT `fk_pago_reserva_stripe` FOREIGN KEY (`reserva_id`) REFERENCES `reservas`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------
-- 7. TESTIMONIOS (CON MODERACION)
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `testimonios` (
  `id` int NOT NULL AUTO_INCREMENT,
  `usuario_id` int NOT NULL,
  `mensaje` varchar(500) NOT NULL,
  `calificacion` int DEFAULT 5,
  `estado` varchar(20) DEFAULT 'pendiente',
  `creado_en` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  CONSTRAINT `fk_testimonios_usuarios` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios`(`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------
-- 8. NOSOTROS
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `nosotros` (
  `id` int NOT NULL AUTO_INCREMENT,
  `tipo` varchar(50) NOT NULL,
  `titulo` varchar(150) DEFAULT NULL,
  `descripcion` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

COMMIT;
