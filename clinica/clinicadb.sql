
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


DROP TABLE IF EXISTS `consultas`;
CREATE TABLE IF NOT EXISTS `consultas` (
  `IdConsulta` int NOT NULL AUTO_INCREMENT,
  `IdMedico` int NOT NULL,
  `IdPaciente` int NOT NULL,
  `FechaConsulta` date NOT NULL,
  `Diagnostico` text NOT NULL,
  PRIMARY KEY (`IdConsulta`),
  KEY `IdMedico_FK_idx` (`IdMedico`),
  KEY `IdPaciente_idx` (`IdPaciente`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `consultas`
--

INSERT INTO `consultas` (`IdConsulta`, `IdMedico`, `IdPaciente`, `FechaConsulta`, `Diagnostico`) VALUES
(1, 1, 1, '2023-01-10', 'Presion arterial alta'),
(2, 4, 2, '2023-02-15', 'Papiloma Humano'),
(3, 3, 3, '2023-03-20', 'Problemas cutaneos'),
(4, 4, 4, '2023-04-25', 'Examen ocular'),
(5, 3, 2, '2023-05-30', 'Chequeo ginecologico'),
(6, 3, 2, '2023-11-20', 'Mamografía'),
(7, 2, 2, '2023-11-22', 'Arritmias cardíacas'),
(8, 1, 4, '2023-11-17', 'Hipermetropía'),
(9, 4, 2, '2023-10-10', 'Acné'),
(10, 1, 1, '2023-09-11', 'Glaucoma'),
(11, 1, 4, '2023-08-07', 'Astigmatismo'),
(12, 2, 3, '2023-06-20', 'Hipertensión arterial'),
(13, 3, 3, '2023-11-07', 'Dermatitis atópica'),
(14, 2, 4, '2023-11-03', 'Colesterol Alto'),
(15, 4, 1, '2023-11-09', 'Acné');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `especialidades`
--

DROP TABLE IF EXISTS `especialidades`;
CREATE TABLE IF NOT EXISTS `especialidades` (
  `IdEsp` int NOT NULL AUTO_INCREMENT,
  `Descripcion` varchar(50) NOT NULL,
  `Dias` varchar(45) NOT NULL,
  `Franja_HI` time NOT NULL,
  `Franja_HF` time NOT NULL,
  PRIMARY KEY (`IdEsp`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `especialidades`
--

INSERT INTO `especialidades` (`IdEsp`, `Descripcion`, `Dias`, `Franja_HI`, `Franja_HF`) VALUES
(1, 'Cardiologia', 'MJV', '08:00:00', '12:00:00'),
(2, 'Pediatria', 'LMXJV', '08:00:00', '18:00:00'),
(3, 'Dermatologia', 'MJ', '12:00:00', '18:00:00'),
(4, 'Ginecologia', 'LXV', '08:00:00', '18:00:00'),
(5, 'Oftalmologia', 'LV', '08:00:00', '12:00:00');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `medicos`
--

DROP TABLE IF EXISTS `medicos`;
CREATE TABLE IF NOT EXISTS `medicos` (
  `IdMedico` int NOT NULL AUTO_INCREMENT,
  `Nombre` varchar(50) NOT NULL,
  `Especialidad` int NOT NULL,
  `Telefono` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  PRIMARY KEY (`IdMedico`),
  KEY `FK_Especialidad_idx` (`Especialidad`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `medicos`
--

INSERT INTO `medicos` (`IdMedico`, `Nombre`, `Especialidad`, `Telefono`, `email`) VALUES
(1, 'Oscar Lopez', 5, 09852364121,`mail1@gmail.com`),
(2, 'Juan Garcia', 1, 09852364155,`mail2@gmail.com`),
(3, 'Ana Perez', 4, 09852364127,`mail3@gmail.com`),
(4, 'Luis Quintana', 3, 09852364129,`mail4@gmail.com`),
(5, 'Julieta Armijos', 2, 0985236411,`mail5@gmail.com`);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pacientes`
--

DROP TABLE IF EXISTS `pacientes`;
CREATE TABLE IF NOT EXISTS `pacientes` (
  `IdPaciente` int NOT NULL AUTO_INCREMENT,
  `Nombre` varchar(50) NOT NULL,
  `Apellido` varchar(50) NOT NULL,
  `Direccion` varchar(50) NOT NULL,
  `Telefono` varchar(50) NOT NULL,
  PRIMARY KEY (`IdPaciente`),
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `pacientes`
--

INSERT INTO `pacientes` (`IdPaciente`, `Nombre`, `Apellido`, `Direccion`, `Telefono`) VALUES
(1, 'Luis', 'Torres', 'Ambato1', 0965412784),
(2, 'Ana', 'Gomez', 'Ambato2', 0965412783),
(3, 'Oscar', 'Uribe', 'Ambato3', 0965412782),
(4, 'Alex', 'Marquez', 'Ambato4', 0965412781);
--
-- Filtros para la tabla `consultas`
--
ALTER TABLE `consultas`
  ADD CONSTRAINT `IdMedico_FK` FOREIGN KEY (`IdMedico`) REFERENCES `medicos` (`IdMedico`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `IdPaciente` FOREIGN KEY (`IdPaciente`) REFERENCES `pacientes` (`IdPaciente`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `medicos`
--
ALTER TABLE `medicos`
  ADD CONSTRAINT `FK_Especialidad` FOREIGN KEY (`Especialidad`) REFERENCES `especialidades` (`IdEsp`) ON DELETE CASCADE ON UPDATE CASCADE,


