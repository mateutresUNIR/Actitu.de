-- phpMyAdmin SQL Dump
-- version 4.6.4
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Temps de generació: 18-06-2017 a les 10:56:20
-- Versió del servidor: 5.7.14
-- Versió de PHP: 5.6.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de dades: `tfg`
--

-- --------------------------------------------------------

--
-- Estructura de la taula `alumnos`
--

CREATE TABLE `alumnos` (
  `id` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `apellidos` varchar(150) NOT NULL,
  `grupomateria` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Bolcant dades de la taula `alumnos`
--

INSERT INTO `alumnos` (`id`, `nombre`, `apellidos`, `grupomateria`) VALUES
(1, 'Mateu', 'Tres Bosch', 7),
(3, 'Mateu', 'Tres Bosch (M08UF2)', 7),
(4, 'Mateu', 'Tres Bosch (M02UF3)', 7),
(5, 'Mateu', 'Tres Bosch (M02UF3)', 7),
(6, 'Mateu', 'Tres Bosch (M02UF3)', 7),
(7, 'Mateu', 'Tres Bosch (M02UF3)', 7),
(8, 'Mateu', 'Tres Bosch (M02UF3)', 7),
(9, 'Mateu', 'Tres Bosch (M02UF3)', 7),
(10, 'Mateu', 'Tres Bosch (M02UF3)', 7),
(11, 'Mateu', 'Tres Bosch M2UF3', 1),
(12, 'Mateu', 'Tres Bosch M2UF3', 1),
(13, 'Mateu', 'Tres Bosch M2UF3', 1),
(14, 'Mateu', 'Tres Bosch M2UF3', 1),
(15, 'Mateu', 'Tres Bosch M2UF3', 1),
(18, 'Aaaasdf', 'awefawe', 12),
(29, 'Mateu', 'Tres Bosch', 15),
(20, 'Pepe', 'González López', 13),
(21, 'Ana', 'Fernández Antúnez', 13),
(22, 'Xuanyang', 'Li', 13),
(23, 'Laura', 'Nicolás Pérez', 13),
(24, 'Mateu', 'Tres Bosch', 13),
(31, 'Ana', 'Fernández Antúnez', 15),
(30, 'Pepe', 'González López', 15),
(32, 'Xuanyang', 'Li', 15),
(33, 'Laura', 'Nicolás Pérez', 15);

-- --------------------------------------------------------

--
-- Estructura de la taula `centros`
--

CREATE TABLE `centros` (
  `id` int(11) NOT NULL,
  `nombre` varchar(150) NOT NULL,
  `poblacion` varchar(150) NOT NULL,
  `codigo` varchar(15) NOT NULL,
  `clave` varchar(15) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Bolcant dades de la taula `centros`
--

INSERT INTO `centros` (`id`, `nombre`, `poblacion`, `codigo`, `clave`) VALUES
(1, 'Institut Camps Blancs', 'Sant Boi de Llobregat', '08025605', 'H764T844'),
(2, 'Institut Torre Roja', 'Viladecans', '08031228', '5KPMMUCC'),
(3, 'Sant Gabriel', 'Viladecans', '08031231', 'UBE49VBB'),
(4, 'Salesians Sant Vicenç dels Horts', 'Sant Vicenç dels Horts', '08032063', 'E2Q6OBTT'),
(5, 'Institut de Sales', 'Viladecans', '08034621', 'RZT87U00'),
(6, 'Frandaser', 'Viladecans', '08040904', 'ZJRGT144'),
(7, 'Institut Marianao', 'Sant Boi de Llobregat', '08043681', 'YOQLXM44'),
(8, 'Institut Gabriela Mistral', 'Sant Vicenç dels Horts', '08053340', 'OBKQCCRR');

-- --------------------------------------------------------

--
-- Estructura de la taula `criterios`
--

CREATE TABLE `criterios` (
  `id` int(11) NOT NULL,
  `centro` int(11) NOT NULL,
  `codigo` varchar(5) NOT NULL,
  `nombre` varchar(250) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Bolcant dades de la taula `criterios`
--

INSERT INTO `criterios` (`id`, `centro`, `codigo`, `nombre`) VALUES
(3, 7, 'CA1', 'Respeto a normas y personas'),
(2, 7, 'CA2', 'Trabajo');

-- --------------------------------------------------------

--
-- Estructura de la taula `gruposmaterias`
--

CREATE TABLE `gruposmaterias` (
  `id` int(11) NOT NULL,
  `codigo` varchar(15) NOT NULL,
  `nombre` varchar(150) NOT NULL,
  `grupo` varchar(150) NOT NULL,
  `usuario` int(11) NOT NULL,
  `centro` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Bolcant dades de la taula `gruposmaterias`
--

INSERT INTO `gruposmaterias` (`id`, `codigo`, `nombre`, `grupo`, `usuario`, `centro`) VALUES
(1, 'M02UF3', 'M02 UF3 Programación de PLC', '1º Robótica', 9, 7),
(2, 'M08UF2', 'M08 UF2 Comunicaciones industriales', '1º Robótica', 9, 7),
(3, 'M07 UF1', 'Robótica Industrial - Teoría', '2º robótica', 9, 7),
(4, 'M07 UF1', 'Robótica Industrial - Teoría', '2º robótica', 9, 7),
(5, 'M07 UF1', 'Robótica Industrial - Teoría', '2º robótica', 9, 7),
(15, 'M07 UF2', 'Robótica Industrial - Programación', '1º Robótica G.S.', 8, 7),
(14, 'M07UF1', 'Robótica Industrial - Teoría', '1º Robótica G.S.', 8, 7),
(13, 'M02UF3', 'Programación de PLC', '1º Robótica G.S.', 8, 7),
(16, 'M07 UF3', 'Robótica Industrial - Mantenimiento', '1º Robótica G.S.', 8, 7),
(17, 'M07 UF4', 'Robótica Industrial - Servomotores', '1º Robótica G.S.', 8, 7);

-- --------------------------------------------------------

--
-- Estructura de la taula `indicadores`
--

CREATE TABLE `indicadores` (
  `id` int(11) NOT NULL,
  `centro` int(11) NOT NULL,
  `nombre` varchar(250) NOT NULL,
  `icono` varchar(25) NOT NULL DEFAULT '',
  `factor` float NOT NULL,
  `idCriterio` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Bolcant dades de la taula `indicadores`
--

INSERT INTO `indicadores` (`id`, `centro`, `nombre`, `icono`, `factor`, `idCriterio`) VALUES
(1, 7, 'No es puntual', 'calendar', -1, 3),
(2, 7, 'Uso móvil', 'mobile', -0.5, 3),
(10, 7, 'No habla en clase', 'microphone-slash', 0.4, 3),
(9, 7, 'Habla en clase', 'microphone', -0.2, 3),
(8, 7, 'Realiza ampliaciones de trabajo', 'thumbs-up', 0.6, 2),
(11, 7, 'Dispersión', 'cloud', -0.2, 2),
(12, 7, 'Participación activa en el aula', 'comments', 0.4, 3),
(13, 7, 'Ayuda compañeros y profesor', 'exchange', 0.4, 3),
(14, 7, 'Desafía las indicaciones del profesor', 'fire', -0.9, 3),
(15, 7, 'Pregunta cuando lo necesita', 'question', 0.2, 2),
(16, 7, 'Aplica los aprendizajes para resolver retos', 'refresh', 0.4, 2);

-- --------------------------------------------------------

--
-- Estructura de la taula `notasletras`
--

CREATE TABLE `notasletras` (
  `letra` varchar(1) NOT NULL,
  `centro` int(11) NOT NULL,
  `notaMin` float NOT NULL,
  `notaMax` float NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Bolcant dades de la taula `notasletras`
--

INSERT INTO `notasletras` (`letra`, `centro`, `notaMin`, `notaMax`) VALUES
('A', 7, 8, 10),
('B', 7, 6, 7.9),
('D', 7, 3.1, 4.4),
('E', 7, 0, 3),
('C', 7, 4.5, 5.9);

-- --------------------------------------------------------

--
-- Estructura de la taula `registroindicadores`
--

CREATE TABLE `registroindicadores` (
  `alumno` int(11) NOT NULL,
  `indicador` int(11) NOT NULL,
  `fecha` date NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Bolcant dades de la taula `registroindicadores`
--

INSERT INTO `registroindicadores` (`alumno`, `indicador`, `fecha`) VALUES
(11, 1, '2017-05-29'),
(11, 8, '2017-05-14'),
(11, 8, '2017-05-15'),
(11, 8, '2017-05-16'),
(11, 8, '2017-05-17'),
(11, 8, '2017-05-18'),
(11, 8, '2017-05-29'),
(11, 9, '2017-05-29'),
(11, 10, '2017-05-17'),
(11, 10, '2017-05-18'),
(11, 10, '2017-05-29'),
(11, 13, '2017-05-17'),
(11, 15, '2017-05-15'),
(11, 15, '2017-05-17'),
(11, 15, '2017-05-18'),
(11, 16, '2017-05-15'),
(11, 16, '2017-05-17'),
(11, 16, '2017-05-18'),
(12, 8, '2017-05-16'),
(12, 8, '2017-05-17'),
(12, 8, '2017-05-29'),
(12, 16, '2017-05-29'),
(13, 8, '2017-05-16'),
(13, 8, '2017-05-17'),
(13, 8, '2017-05-30'),
(13, 15, '2017-05-29'),
(14, 8, '2017-05-16'),
(14, 8, '2017-05-17'),
(14, 8, '2017-05-30'),
(14, 11, '2017-05-29'),
(15, 1, '2017-05-29'),
(15, 8, '2017-05-16'),
(15, 8, '2017-05-17'),
(15, 8, '2017-05-30'),
(18, 1, '2017-06-16'),
(18, 8, '2017-06-16'),
(18, 14, '2017-06-16'),
(19, 11, '2017-06-16'),
(20, 2, '2017-06-06'),
(20, 8, '2017-06-05'),
(20, 9, '2017-06-19'),
(20, 10, '2017-06-07'),
(20, 11, '2017-06-16'),
(20, 11, '2017-06-29'),
(21, 8, '2017-06-16'),
(21, 12, '2017-06-27'),
(21, 29, '2017-05-28'),
(21, 29, '2017-05-29'),
(21, 29, '2017-06-16'),
(21, 31, '2017-06-01'),
(22, 16, '2017-06-28'),
(22, 26, '2017-06-16'),
(23, 1, '2017-06-12'),
(23, 13, '2017-06-16'),
(23, 14, '2017-06-11'),
(23, 15, '2017-06-13'),
(24, 12, '2017-06-16'),
(24, 13, '2017-06-08'),
(25, 2, '2017-06-27'),
(25, 10, '2017-06-16'),
(25, 11, '2017-06-20'),
(25, 16, '2017-06-29'),
(27, 9, '2017-06-16'),
(28, 9, '2017-06-16');

-- --------------------------------------------------------

--
-- Estructura de la taula `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `email` varchar(150) NOT NULL,
  `contrasena` varchar(150) NOT NULL,
  `nombre` varchar(150) NOT NULL,
  `superAdmin` tinyint(1) NOT NULL DEFAULT '0',
  `gestorCentro` tinyint(1) NOT NULL DEFAULT '0',
  `idCentro` int(11) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Bolcant dades de la taula `usuarios`
--

INSERT INTO `usuarios` (`id`, `email`, `contrasena`, `nombre`, `superAdmin`, `gestorCentro`, `idCentro`) VALUES
(2, 'admin@actitu.de', '48d144228ac815381e6e0c9fa2221706', 'Administrador plataforma', -1, 0, 0),
(8, 'gestorcentro@actitu.de', '48d144228ac815381e6e0c9fa2221706', 'Gestor centro', 0, -1, 7),
(9, 'profesor@actitu.de', '48d144228ac815381e6e0c9fa2221706', 'Profesor', 0, 0, 7);

--
-- Indexos per taules bolcades
--

--
-- Index de la taula `alumnos`
--
ALTER TABLE `alumnos`
  ADD PRIMARY KEY (`id`);

--
-- Index de la taula `centros`
--
ALTER TABLE `centros`
  ADD PRIMARY KEY (`id`);

--
-- Index de la taula `criterios`
--
ALTER TABLE `criterios`
  ADD PRIMARY KEY (`id`);

--
-- Index de la taula `gruposmaterias`
--
ALTER TABLE `gruposmaterias`
  ADD PRIMARY KEY (`id`);

--
-- Index de la taula `indicadores`
--
ALTER TABLE `indicadores`
  ADD PRIMARY KEY (`id`);

--
-- Index de la taula `notasletras`
--
ALTER TABLE `notasletras`
  ADD PRIMARY KEY (`letra`,`centro`);

--
-- Index de la taula `registroindicadores`
--
ALTER TABLE `registroindicadores`
  ADD PRIMARY KEY (`alumno`,`indicador`,`fecha`);

--
-- Index de la taula `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT per les taules bolcades
--

--
-- AUTO_INCREMENT per la taula `alumnos`
--
ALTER TABLE `alumnos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;
--
-- AUTO_INCREMENT per la taula `centros`
--
ALTER TABLE `centros`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;
--
-- AUTO_INCREMENT per la taula `criterios`
--
ALTER TABLE `criterios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT per la taula `gruposmaterias`
--
ALTER TABLE `gruposmaterias`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;
--
-- AUTO_INCREMENT per la taula `indicadores`
--
ALTER TABLE `indicadores`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;
--
-- AUTO_INCREMENT per la taula `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
