-- phpMyAdmin SQL Dump
-- version 5.1.1deb5ubuntu1
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost:3306
-- Tiempo de generación: 30-05-2024 a las 19:47:30
-- Versión del servidor: 8.0.36-0ubuntu0.22.04.1
-- Versión de PHP: 8.1.2-1ubuntu2.17

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

-- Change character set and collation to UTF8MB4
/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `santicolle2324`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `Habitaciones`
--

CREATE TABLE IF NOT EXISTS Habitaciones (
    `id_habitacion` int NOT NULL AUTO_INCREMENT,
    `numero` varchar(20) COLLATE utf8mb4_spanish_ci NOT NULL,
    `capacidad` int NOT NULL,
    `precio_por_noche` decimal(10, 2) NOT NULL,
    `descripcion` text COLLATE utf8mb4_spanish_ci NOT NULL,
    `n-imagenes` int NOT NULL,
    `estado` ENUM('Operativa', 'Pendiente', 'Confirmada', 'Mantenimiento') NOT NULL DEFAULT 'Operativa',
    PRIMARY KEY (`id_habitacion`),
    UNIQUE KEY `numero` (`numero`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

--
-- AUTO_INCREMENT de las tablas volcadas
--
ALTER TABLE `Habitaciones`
  MODIFY `id_habitacion` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=303;
COMMIT;

--
-- Volcado de datos para la tabla `Habitaciones`
--

INSERT INTO `Habitaciones` (`id_habitacion`, `numero`, `capacidad`, `precio_por_noche`, `descripcion`, `n-imagenes`, `estado`) VALUES
(101, '101', 2, 25.5, '', 0, 'Operativa'),
(102, '102', 2, 25.5, '', 0, 'Operativa'),
(103, '103', 2, 25.5, '', 1, 'Operativa'),
(104, '104', 2, 25.5, '', 1, 'Operativa'),
(105, '105', 2, 25.5, '', 2, 'Operativa'),
(201, '201', 3, 25.5, '', 2, 'Operativa'),
(202, '202', 3, 25.5, '', 3, 'Operativa'),
(203, '203', 3, 25.5, '', 3, 'Operativa'),
(204, '204', 3, 25.5, '', 4, 'Operativa'),
(301, '301', 4, 25.5, '', 4, 'Operativa'),
(302, '302', 4, 25.5, '', 0, 'Operativa'),
(1, 'Suite presidencial', 4, 25.5, '', 0, 'Operativa'),
(2, 'Suite nupcial', 2, 25.5, '', 0, 'Operativa');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
