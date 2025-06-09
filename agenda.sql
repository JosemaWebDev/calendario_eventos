-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 09-06-2025 a las 08:59:05
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
-- Base de datos: `calendario_eventos`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `agenda`
--

CREATE TABLE `agenda` (
  `id` int(11) NOT NULL,
  `titulo` varchar(255) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `enlace` varchar(255) DEFAULT NULL,
  `fecha_evento` datetime NOT NULL,
  `imagenes` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `borrado` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

--
-- Volcado de datos para la tabla `agenda`
--

INSERT INTO `agenda` (`id`, `titulo`, `descripcion`, `enlace`, `fecha_evento`, `imagenes`, `created_at`, `updated_at`, `borrado`) VALUES
(1, 'Reunión de planificación', 'Planificación del segundo semestre', 'https://zoom.us/planificacion', '2025-06-03 10:00:00', NULL, '2025-06-09 06:57:45', '2025-06-09 06:57:45', 0),
(2, 'Taller de productividad', 'Aprende técnicas para mejorar tu rendimiento', 'https://meet.google.com/taller-productividad', '2025-06-05 16:30:00', NULL, '2025-06-09 06:57:45', '2025-06-09 06:57:45', 0),
(3, 'Revisión mensual', 'Análisis del progreso del mes anterior', 'https://zoom.us/revision', '2025-06-07 09:00:00', NULL, '2025-06-09 06:57:45', '2025-06-09 06:57:45', 0),
(4, 'Curso de diseño UX', 'Introducción al diseño de experiencia de usuario', 'https://udemy.com/ux', '2025-06-09 14:00:00', NULL, '2025-06-09 06:57:45', '2025-06-09 06:57:45', 0),
(5, 'Sesión informativa', 'Nueva normativa para empleados', 'https://empresa.com/normativa', '2025-06-10 11:15:00', NULL, '2025-06-09 06:57:45', '2025-06-09 06:57:45', 0),
(6, 'Taller de liderazgo', 'Desarrolla tus habilidades como líder', 'https://empresa.com/liderazgo', '2025-06-12 13:00:00', NULL, '2025-06-09 06:57:45', '2025-06-09 06:57:45', 0),
(7, 'Webinar marketing', 'Últimas tendencias en marketing digital', 'https://youtube.com/marketing2025', '2025-06-14 18:00:00', NULL, '2025-06-09 06:57:45', '2025-06-09 06:57:45', 0),
(8, 'Día de puertas abiertas', 'Visita guiada para nuevos candidatos', 'https://empresa.com/open-day', '2025-06-17 12:30:00', NULL, '2025-06-09 06:57:45', '2025-06-09 06:57:45', 0),
(9, 'Reunión de equipo', 'Coordinación de proyectos internos', 'https://teams.microsoft.com/equipo', '2025-06-19 15:45:00', NULL, '2025-06-09 06:57:45', '2025-06-09 06:57:45', 0),
(10, 'Presentación de resultados', 'Resultados del segundo trimestre', 'https://empresa.com/resultados', '2025-06-22 17:00:00', NULL, '2025-06-09 06:57:45', '2025-06-09 06:57:45', 0),
(11, 'Curso de primeros auxilios', 'Formación certificada presencial', 'https://formacion.com/auxilios', '2025-06-25 10:30:00', NULL, '2025-06-09 06:57:45', '2025-06-09 06:57:45', 0),
(12, 'Fiesta de verano', 'Celebración informal de mitad de año', 'https://eventbrite.com/verano2025', '2025-06-29 20:00:00', NULL, '2025-06-09 06:57:45', '2025-06-09 06:57:45', 0),
(13, 'Seminario internacional', 'Conferencias de expertos internacionales', 'https://webinar.com/internacional', '2025-07-03 09:00:00', NULL, '2025-06-09 06:57:45', '2025-06-09 06:57:45', 0),
(14, 'Hackathon interno', 'Desafío de programación para el equipo técnico', 'https://hackathon.com/empresa', '2025-07-07 08:00:00', NULL, '2025-06-09 06:57:45', '2025-06-09 06:57:45', 0),
(15, 'Curso intensivo de inglés', 'Inglés profesional en 3 días', 'https://ingles.com/pro', '2025-07-15 10:00:00', NULL, '2025-06-09 06:57:45', '2025-06-09 06:57:45', 0),
(16, 'Asamblea general', 'Encuentro anual de toda la plantilla', 'https://empresa.com/asamblea', '2025-07-25 13:30:00', NULL, '2025-06-09 06:57:45', '2025-06-09 06:57:45', 0);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `agenda`
--
ALTER TABLE `agenda`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `agenda`
--
ALTER TABLE `agenda`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
