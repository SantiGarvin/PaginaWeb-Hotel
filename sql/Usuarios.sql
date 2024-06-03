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
-- Estructura de tabla para la tabla `Usuarios`
--

CREATE TABLE `Usuarios` (
  `id_usuario` int NOT NULL,
  `nombre` varchar(50) COLLATE utf8mb4_spanish_ci NOT NULL,
  `apellidos` varchar(50) COLLATE utf8mb4_spanish_ci NOT NULL,
  `dni` varchar(9) COLLATE utf8mb4_spanish_ci NOT NULL,
  `email` varchar(50) COLLATE utf8mb4_spanish_ci NOT NULL,
  `clave` varchar(255) COLLATE utf8mb4_spanish_ci NOT NULL,
  `num_tarjeta_credito` varchar(16) COLLATE utf8mb4_spanish_ci NOT NULL,
  rol ENUM('Administrador', 'Recepcionista', 'Cliente', 'Anonimo') COLLATE utf8mb4_spanish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

--
-- Indices de la tabla `Usuarios`
--
ALTER TABLE `Usuarios`
  ADD PRIMARY KEY (`id_usuario`),
  ADD UNIQUE KEY `dni` (`dni`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT de la tabla `Usuarios`
--
ALTER TABLE `Usuarios`
  MODIFY `id_usuario` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
COMMIT;

--
-- Volcado de datos para la tabla `Usuarios`
--
INSERT INTO `Usuarios` (`id_usuario`, `nombre`, `apellidos`, `dni`, `email`, `clave`, `num_tarjeta_credito`, `rol`) VALUES
(1, 'tia', '', '1', 'tia@void.ugr.es', 'tia', '', 'Administrador'),
(2, 'abuela', '', '2', 'abuela@void.ugr.es', 'abuela', '', 'Administrador'),
(3, 'director', '', '3', 'director@void.ugr.es', 'director', '', 'Recepcionista'),
(4, 'elsuper', '', '4', 'elsuper@void.ugr.es', 'elsuper', '', 'Recepcionista'),
(5, 'mortadelo', '', '5', 'mortadelo@void.ugr.es', 'mortadelo', '', 'Cliente'),
(6, 'filemon', '', '6', 'filemon@void.ugr.es', 'filemon', '', 'Cliente'),
(7, 'bacterio', '', '7', 'bacterio@void.ugr.es', 'bacterio', '', 'Cliente'),
(8, 'ofelia', '', '8', 'ofelia@void.ugr.es', 'ofelia', '', 'Cliente'),
(9, 'irma', '', '9', 'irma@void.ugr.es', 'irma', '', 'Cliente');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
