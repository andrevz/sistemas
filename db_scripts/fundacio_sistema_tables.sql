-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Oct 23, 2025 at 08:24 PM
-- Server version: 5.7.23-23
-- PHP Version: 8.1.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `fundacio_sistema`
--

-- --------------------------------------------------------

--
-- Table structure for table `actividad`
--

CREATE TABLE `actividad` (
  `idActividad` int(11) NOT NULL,
  `Nombre` varchar(45) COLLATE utf8_bin DEFAULT NULL,
  `idModulo` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Table structure for table `actividades`
--

CREATE TABLE `actividades` (
  `idActividades` int(11) NOT NULL,
  `Nombre` varchar(150) COLLATE utf8_bin DEFAULT NULL,
  `Version` tinyint(1) DEFAULT NULL,
  `Materia` tinyint(1) DEFAULT NULL,
  `orden` int(11) NOT NULL,
  `tipo` varchar(25) COLLATE utf8_bin NOT NULL,
  `responsable` varchar(120) COLLATE utf8_bin NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Table structure for table `aplicacion`
--

CREATE TABLE `aplicacion` (
  `idAplicacion` tinyint(1) NOT NULL,
  `Descripcion` varchar(50) COLLATE utf8_bin NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Table structure for table `bitacora`
--

CREATE TABLE `bitacora` (
  `id_transaccion` int(11) NOT NULL,
  `fecha_hora` datetime NOT NULL,
  `id_empresa` int(11) NOT NULL,
  `evento` varchar(50) COLLATE utf8_bin NOT NULL,
  `detalles` varchar(255) COLLATE utf8_bin NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Table structure for table `categoria`
--

CREATE TABLE `categoria` (
  `idCategoria` int(11) NOT NULL,
  `Nombre` varchar(15) COLLATE utf8_bin DEFAULT NULL,
  `HonorariosHora` varchar(45) COLLATE utf8_bin DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Table structure for table `checklist`
--

CREATE TABLE `checklist` (
  `idChecklist` int(11) NOT NULL,
  `idVersion` int(11) NOT NULL,
  `idEjecucionMateriaVersionDocente` int(11) NOT NULL,
  `idActividades` int(11) NOT NULL,
  `ejecutado` tinyint(1) DEFAULT NULL,
  `fecha` date NOT NULL,
  `comentarios` varchar(200) COLLATE utf8_bin NOT NULL,
  `responsable` varchar(150) COLLATE utf8_bin NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Table structure for table `ciudad`
--

CREATE TABLE `ciudad` (
  `idciudad` int(11) NOT NULL,
  `nombre` varchar(80) CHARACTER SET latin1 NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Table structure for table `clasificaciongastos`
--

CREATE TABLE `clasificaciongastos` (
  `idclasificacion` int(11) NOT NULL,
  `nombre` varchar(150) COLLATE utf8_bin NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Table structure for table `documentos`
--

CREATE TABLE `documentos` (
  `idDocumentos` int(11) NOT NULL,
  `NombreArchivo` varchar(250) COLLATE utf8_bin DEFAULT NULL,
  `Descripcion` longblob,
  `Tipo` varchar(150) COLLATE utf8_bin DEFAULT NULL,
  `Contenido` longblob,
  `idRecursoHumano` int(10) UNSIGNED DEFAULT NULL,
  `Fecha` datetime NOT NULL,
  `titulo` varchar(250) COLLATE utf8_bin NOT NULL,
  `tamano` int(11) NOT NULL,
  `idejecucionmateriaversiondocente` int(11) NOT NULL,
  `idversion` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Table structure for table `ejecuciongastos`
--

CREATE TABLE `ejecuciongastos` (
  `idEjecucionGastos` int(11) NOT NULL,
  `NroMes` int(11) DEFAULT NULL,
  `Monto` double DEFAULT NULL,
  `idTiposGastos` int(11) NOT NULL,
  `idVersion` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Table structure for table `ejecucioningresos`
--

CREATE TABLE `ejecucioningresos` (
  `idEjecucionIngresos` int(11) NOT NULL,
  `NroMes` int(11) DEFAULT NULL,
  `Monto` double DEFAULT NULL,
  `idTiposIngresos` int(11) NOT NULL,
  `idVersion` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Table structure for table `ejecucionmateriaversiondocente`
--

CREATE TABLE `ejecucionmateriaversiondocente` (
  `idEjecucionMateriaVersionDocente` int(11) NOT NULL,
  `Horas` double DEFAULT NULL,
  `HonorariosHora` double DEFAULT NULL,
  `Pasajes` double DEFAULT NULL,
  `ViaticosDia` double DEFAULT NULL,
  `Dias` int(11) DEFAULT NULL,
  `HospedajeDia` double DEFAULT NULL,
  `EvaluacionDocente` varchar(45) COLLATE utf8_bin DEFAULT NULL,
  `FechaContrato` date DEFAULT NULL,
  `Monto` double DEFAULT NULL,
  `FechaSolicitudPago` date DEFAULT NULL,
  `NroSolicitud` int(11) DEFAULT NULL,
  `Estado` varchar(45) COLLATE utf8_bin DEFAULT NULL,
  `idMateriaVersion` int(11) NOT NULL,
  `idRecursoHumano` int(11) NOT NULL,
  `idCategoria` int(11) NOT NULL,
  `idplanmateriaversiondocente` int(11) NOT NULL DEFAULT '0',
  `Inicio` date NOT NULL,
  `Fin` date NOT NULL,
  `procedencia` varchar(75) COLLATE utf8_bin NOT NULL,
  `observaciones` text COLLATE utf8_bin NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Table structure for table `escuela`
--

CREATE TABLE `escuela` (
  `idescuela` int(11) NOT NULL,
  `nombre` varchar(150) CHARACTER SET latin1 NOT NULL,
  `sigla` varchar(10) CHARACTER SET latin1 NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Table structure for table `estudiantes`
--

CREATE TABLE `estudiantes` (
  `idEstudiantes` int(11) NOT NULL,
  `NroMes` int(11) NOT NULL,
  `Vigentes` int(11) NOT NULL,
  `EnMora` int(11) NOT NULL,
  `idversion` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Table structure for table `materia`
--

CREATE TABLE `materia` (
  `idMateria` int(11) NOT NULL,
  `Nombre` varchar(150) COLLATE utf8_bin DEFAULT NULL,
  `Horas` double DEFAULT NULL,
  `codigo` varchar(15) COLLATE utf8_bin NOT NULL,
  `nombre_oficial` varchar(200) COLLATE utf8_bin NOT NULL,
  `idprograma` int(11) NOT NULL,
  `cod_dti` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Table structure for table `materiaversion`
--

CREATE TABLE `materiaversion` (
  `idMateriaVersion` int(11) NOT NULL,
  `Universidad` varchar(45) COLLATE utf8_bin DEFAULT NULL,
  `idVersion` int(11) NOT NULL,
  `idTipoMateria` int(11) NOT NULL,
  `idMateria` int(11) NOT NULL,
  `orden` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Table structure for table `medio`
--

CREATE TABLE `medio` (
  `idmedio` int(11) NOT NULL,
  `nombre` varchar(150) CHARACTER SET latin1 NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Table structure for table `modulo`
--

CREATE TABLE `modulo` (
  `idModulo` int(11) NOT NULL,
  `Nombre` varchar(45) COLLATE utf8_bin DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Table structure for table `nivelesacceso`
--

CREATE TABLE `nivelesacceso` (
  `idnivel` int(11) NOT NULL,
  `nombre` varchar(50) COLLATE utf8_bin NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Table structure for table `planmateriaversiondocente`
--

CREATE TABLE `planmateriaversiondocente` (
  `idPlanMateriaVersionDocente` int(11) NOT NULL,
  `Horas` double DEFAULT NULL,
  `HonorariosHora` double DEFAULT NULL,
  `Pasajes` double DEFAULT NULL,
  `ViaticosDia` double DEFAULT NULL,
  `Dias` int(11) DEFAULT NULL,
  `HospedajeDia` double DEFAULT NULL,
  `idCategoria` int(11) NOT NULL,
  `idRecursoHumano` int(11) NOT NULL,
  `idMateriaVersion` int(11) NOT NULL,
  `Inicio` date NOT NULL,
  `Fin` date NOT NULL,
  `procedencia` varchar(75) COLLATE utf8_bin NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Table structure for table `planmedios`
--

CREATE TABLE `planmedios` (
  `idPlanMedios` int(11) NOT NULL,
  `Fecha` date DEFAULT NULL,
  `idVersion` int(11) NOT NULL,
  `idmedio` int(11) DEFAULT NULL,
  `Especificaciones` varchar(45) COLLATE utf8_bin DEFAULT NULL,
  `Precio` double DEFAULT NULL,
  `Observaciones` varchar(200) COLLATE utf8_bin DEFAULT NULL,
  `ejecutado` tinyint(1) NOT NULL,
  `fechaejecutado` date NOT NULL,
  `montoejecutado` double NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Table structure for table `presupuestogastos`
--

CREATE TABLE `presupuestogastos` (
  `idPresupuestoGastos` int(11) NOT NULL,
  `Valor` double DEFAULT NULL,
  `idTiposGastos` int(11) NOT NULL,
  `idVersion` int(11) NOT NULL,
  `idaplicacion` int(11) NOT NULL,
  `idclasificacion` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Table structure for table `presupuestoingresos`
--

CREATE TABLE `presupuestoingresos` (
  `idPresupuestoIngresos` int(11) NOT NULL,
  `Descuento` double DEFAULT NULL,
  `Alumnos` int(11) DEFAULT NULL,
  `idVersion` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Table structure for table `pres_detalle`
--

CREATE TABLE `pres_detalle` (
  `idpres_detalle` int(11) NOT NULL,
  `Item` varchar(150) COLLATE utf8_bin DEFAULT NULL,
  `Monto` double DEFAULT NULL,
  `idVersion` int(11) NOT NULL,
  `tipo` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Table structure for table `pres_version`
--

CREATE TABLE `pres_version` (
  `idVersion` int(11) NOT NULL,
  `NroAlumnos` int(11) DEFAULT NULL,
  `NroMaterias` int(11) DEFAULT NULL,
  `Dias` int(11) DEFAULT NULL,
  `Matricula` int(11) DEFAULT NULL,
  `Colegiatura` int(11) DEFAULT NULL,
  `NroMeses` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Table structure for table `programa`
--

CREATE TABLE `programa` (
  `idPrograma` int(11) NOT NULL,
  `Nombre` varchar(150) COLLATE utf8_bin DEFAULT NULL,
  `sigla` varchar(15) COLLATE utf8_bin DEFAULT NULL,
  `idtipoprograma` int(11) NOT NULL,
  `idescuela` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Table structure for table `reclutamiento`
--

CREATE TABLE `reclutamiento` (
  `idReclutamiento` int(11) NOT NULL,
  `idVersion` int(11) NOT NULL,
  `Fecha` date DEFAULT NULL,
  `Meta` int(11) DEFAULT NULL,
  `Interesados` int(11) DEFAULT NULL,
  `Solicitud` int(11) DEFAULT NULL,
  `Seguros` int(11) DEFAULT NULL,
  `Matriculados` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Table structure for table `recursohumano`
--

CREATE TABLE `recursohumano` (
  `idRecursoHumano` int(11) NOT NULL,
  `CI` varchar(45) COLLATE utf8_bin DEFAULT NULL,
  `Nombres` varchar(45) COLLATE utf8_bin DEFAULT NULL,
  `Apellidos` varchar(45) COLLATE utf8_bin DEFAULT NULL,
  `Telefono` varchar(45) COLLATE utf8_bin DEFAULT NULL,
  `Celular` varchar(45) COLLATE utf8_bin DEFAULT NULL,
  `Direccion` varchar(45) COLLATE utf8_bin DEFAULT NULL,
  `Ciudad` varchar(45) COLLATE utf8_bin DEFAULT NULL,
  `email` varchar(45) COLLATE utf8_bin DEFAULT NULL,
  `login` varchar(45) COLLATE utf8_bin DEFAULT NULL,
  `contrasena` varchar(45) COLLATE utf8_bin DEFAULT NULL,
  `RecursoActivo` tinyint(1) DEFAULT NULL,
  `idRol` int(11) NOT NULL DEFAULT '2',
  `UsuarioActivo` tinyint(1) DEFAULT NULL,
  `factura` tinyint(1) NOT NULL,
  `NIT` int(11) NOT NULL,
  `codigo_UPB` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Table structure for table `rol`
--

CREATE TABLE `rol` (
  `idRol` int(11) NOT NULL,
  `Nombre` varchar(45) COLLATE utf8_bin DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Table structure for table `rolciudad`
--

CREATE TABLE `rolciudad` (
  `idrolciudad` int(11) NOT NULL,
  `idrol` int(11) NOT NULL,
  `idciudad` int(11) NOT NULL,
  `idnivel` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Table structure for table `rolesactividades`
--

CREATE TABLE `rolesactividades` (
  `idRolesActividades` int(11) NOT NULL,
  `idRol` int(11) NOT NULL,
  `idActividad` int(11) NOT NULL,
  `NivelAcceso` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Table structure for table `rolescuela`
--

CREATE TABLE `rolescuela` (
  `idrolescuela` int(11) NOT NULL,
  `idrol` int(11) NOT NULL,
  `idescuela` int(11) NOT NULL,
  `idnivel` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `rolesmodulos`
--

CREATE TABLE `rolesmodulos` (
  `idRolesModulos` int(11) NOT NULL,
  `idRol` int(11) NOT NULL,
  `idModulo` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Table structure for table `roltipoprograma`
--

CREATE TABLE `roltipoprograma` (
  `idroltipoprograma` int(11) NOT NULL,
  `idrol` int(11) NOT NULL,
  `idtipoprograma` int(11) NOT NULL,
  `idnivel` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Table structure for table `rolversion`
--

CREATE TABLE `rolversion` (
  `idrolversion` int(11) NOT NULL,
  `idrol` int(11) NOT NULL,
  `idversion` int(11) NOT NULL,
  `idnivel` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Table structure for table `tipomateria`
--

CREATE TABLE `tipomateria` (
  `idTipoMateria` int(11) NOT NULL,
  `Nombre` varchar(45) COLLATE utf8_bin DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Table structure for table `tipoprograma`
--

CREATE TABLE `tipoprograma` (
  `idtipoprograma` int(11) NOT NULL,
  `Nombre` varchar(50) CHARACTER SET latin1 NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Table structure for table `tiposgastos`
--

CREATE TABLE `tiposgastos` (
  `idTiposGastos` int(11) NOT NULL,
  `Nombre` varchar(45) COLLATE utf8_bin DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Table structure for table `tiposingresos`
--

CREATE TABLE `tiposingresos` (
  `idTiposIngresos` int(11) NOT NULL,
  `Descripcion` varchar(45) COLLATE utf8_bin NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Table structure for table `version`
--

CREATE TABLE `version` (
  `idVersion` int(11) NOT NULL,
  `Gestion` char(7) COLLATE utf8_bin DEFAULT NULL,
  `Ciudad` int(11) DEFAULT NULL,
  `Colegiatura` double DEFAULT NULL,
  `Overhead` double DEFAULT NULL,
  `idPrograma` int(11) NOT NULL,
  `idRecursoHumano` int(11) NOT NULL,
  `NroDias` int(11) DEFAULT NULL,
  `NroMeses` int(11) DEFAULT NULL,
  `InicioProgramado` date DEFAULT NULL,
  `FinProgramado` date DEFAULT NULL,
  `InicioEjecutado` date DEFAULT NULL,
  `FinEjecutado` date DEFAULT NULL,
  `Matricula` double NOT NULL,
  `activo` tinyint(1) NOT NULL DEFAULT '1',
  `NroDiasEjecutado` int(11) NOT NULL DEFAULT '0',
  `NroMesesEjecutado` int(11) NOT NULL DEFAULT '0',
  `NroMaterias` int(11) NOT NULL,
  `estado` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `actividad`
--
ALTER TABLE `actividad`
  ADD PRIMARY KEY (`idActividad`),
  ADD KEY `fk_Actividad_Modulo1` (`idModulo`);

--
-- Indexes for table `actividades`
--
ALTER TABLE `actividades`
  ADD PRIMARY KEY (`idActividades`);

--
-- Indexes for table `aplicacion`
--
ALTER TABLE `aplicacion`
  ADD PRIMARY KEY (`idAplicacion`);

--
-- Indexes for table `bitacora`
--
ALTER TABLE `bitacora`
  ADD PRIMARY KEY (`id_transaccion`);

--
-- Indexes for table `categoria`
--
ALTER TABLE `categoria`
  ADD PRIMARY KEY (`idCategoria`);

--
-- Indexes for table `checklist`
--
ALTER TABLE `checklist`
  ADD PRIMARY KEY (`idChecklist`);

--
-- Indexes for table `ciudad`
--
ALTER TABLE `ciudad`
  ADD PRIMARY KEY (`idciudad`);

--
-- Indexes for table `clasificaciongastos`
--
ALTER TABLE `clasificaciongastos`
  ADD PRIMARY KEY (`idclasificacion`);

--
-- Indexes for table `documentos`
--
ALTER TABLE `documentos`
  ADD PRIMARY KEY (`idDocumentos`);

--
-- Indexes for table `ejecuciongastos`
--
ALTER TABLE `ejecuciongastos`
  ADD PRIMARY KEY (`idEjecucionGastos`);

--
-- Indexes for table `ejecucioningresos`
--
ALTER TABLE `ejecucioningresos`
  ADD PRIMARY KEY (`idEjecucionIngresos`);

--
-- Indexes for table `ejecucionmateriaversiondocente`
--
ALTER TABLE `ejecucionmateriaversiondocente`
  ADD PRIMARY KEY (`idEjecucionMateriaVersionDocente`);

--
-- Indexes for table `escuela`
--
ALTER TABLE `escuela`
  ADD PRIMARY KEY (`idescuela`);

--
-- Indexes for table `estudiantes`
--
ALTER TABLE `estudiantes`
  ADD PRIMARY KEY (`idEstudiantes`);

--
-- Indexes for table `materia`
--
ALTER TABLE `materia`
  ADD PRIMARY KEY (`idMateria`);

--
-- Indexes for table `materiaversion`
--
ALTER TABLE `materiaversion`
  ADD PRIMARY KEY (`idMateriaVersion`);

--
-- Indexes for table `medio`
--
ALTER TABLE `medio`
  ADD PRIMARY KEY (`idmedio`);

--
-- Indexes for table `modulo`
--
ALTER TABLE `modulo`
  ADD PRIMARY KEY (`idModulo`);

--
-- Indexes for table `nivelesacceso`
--
ALTER TABLE `nivelesacceso`
  ADD PRIMARY KEY (`idnivel`);

--
-- Indexes for table `planmateriaversiondocente`
--
ALTER TABLE `planmateriaversiondocente`
  ADD PRIMARY KEY (`idPlanMateriaVersionDocente`);

--
-- Indexes for table `planmedios`
--
ALTER TABLE `planmedios`
  ADD PRIMARY KEY (`idPlanMedios`);

--
-- Indexes for table `presupuestogastos`
--
ALTER TABLE `presupuestogastos`
  ADD PRIMARY KEY (`idPresupuestoGastos`);

--
-- Indexes for table `presupuestoingresos`
--
ALTER TABLE `presupuestoingresos`
  ADD PRIMARY KEY (`idPresupuestoIngresos`);

--
-- Indexes for table `pres_detalle`
--
ALTER TABLE `pres_detalle`
  ADD PRIMARY KEY (`idpres_detalle`);

--
-- Indexes for table `pres_version`
--
ALTER TABLE `pres_version`
  ADD PRIMARY KEY (`idVersion`),
  ADD KEY `fk_pres_version_Version1` (`idVersion`);

--
-- Indexes for table `programa`
--
ALTER TABLE `programa`
  ADD PRIMARY KEY (`idPrograma`);

--
-- Indexes for table `reclutamiento`
--
ALTER TABLE `reclutamiento`
  ADD PRIMARY KEY (`idReclutamiento`);

--
-- Indexes for table `recursohumano`
--
ALTER TABLE `recursohumano`
  ADD PRIMARY KEY (`idRecursoHumano`),
  ADD KEY `fk_RecursoHumano_Rol1` (`idRol`);

--
-- Indexes for table `rol`
--
ALTER TABLE `rol`
  ADD PRIMARY KEY (`idRol`);

--
-- Indexes for table `rolciudad`
--
ALTER TABLE `rolciudad`
  ADD PRIMARY KEY (`idrolciudad`);

--
-- Indexes for table `rolesactividades`
--
ALTER TABLE `rolesactividades`
  ADD PRIMARY KEY (`idRolesActividades`);

--
-- Indexes for table `rolescuela`
--
ALTER TABLE `rolescuela`
  ADD PRIMARY KEY (`idrolescuela`);

--
-- Indexes for table `rolesmodulos`
--
ALTER TABLE `rolesmodulos`
  ADD PRIMARY KEY (`idRolesModulos`);

--
-- Indexes for table `roltipoprograma`
--
ALTER TABLE `roltipoprograma`
  ADD PRIMARY KEY (`idroltipoprograma`);

--
-- Indexes for table `rolversion`
--
ALTER TABLE `rolversion`
  ADD PRIMARY KEY (`idrolversion`);

--
-- Indexes for table `tipomateria`
--
ALTER TABLE `tipomateria`
  ADD PRIMARY KEY (`idTipoMateria`);

--
-- Indexes for table `tipoprograma`
--
ALTER TABLE `tipoprograma`
  ADD PRIMARY KEY (`idtipoprograma`);

--
-- Indexes for table `tiposgastos`
--
ALTER TABLE `tiposgastos`
  ADD PRIMARY KEY (`idTiposGastos`);

--
-- Indexes for table `tiposingresos`
--
ALTER TABLE `tiposingresos`
  ADD PRIMARY KEY (`idTiposIngresos`);

--
-- Indexes for table `version`
--
ALTER TABLE `version`
  ADD PRIMARY KEY (`idVersion`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `actividad`
--
ALTER TABLE `actividad`
  MODIFY `idActividad` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `actividades`
--
ALTER TABLE `actividades`
  MODIFY `idActividades` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `aplicacion`
--
ALTER TABLE `aplicacion`
  MODIFY `idAplicacion` tinyint(1) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `bitacora`
--
ALTER TABLE `bitacora`
  MODIFY `id_transaccion` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `categoria`
--
ALTER TABLE `categoria`
  MODIFY `idCategoria` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `checklist`
--
ALTER TABLE `checklist`
  MODIFY `idChecklist` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ciudad`
--
ALTER TABLE `ciudad`
  MODIFY `idciudad` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `clasificaciongastos`
--
ALTER TABLE `clasificaciongastos`
  MODIFY `idclasificacion` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `documentos`
--
ALTER TABLE `documentos`
  MODIFY `idDocumentos` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ejecuciongastos`
--
ALTER TABLE `ejecuciongastos`
  MODIFY `idEjecucionGastos` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ejecucioningresos`
--
ALTER TABLE `ejecucioningresos`
  MODIFY `idEjecucionIngresos` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ejecucionmateriaversiondocente`
--
ALTER TABLE `ejecucionmateriaversiondocente`
  MODIFY `idEjecucionMateriaVersionDocente` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `escuela`
--
ALTER TABLE `escuela`
  MODIFY `idescuela` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `estudiantes`
--
ALTER TABLE `estudiantes`
  MODIFY `idEstudiantes` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `materia`
--
ALTER TABLE `materia`
  MODIFY `idMateria` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `materiaversion`
--
ALTER TABLE `materiaversion`
  MODIFY `idMateriaVersion` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `medio`
--
ALTER TABLE `medio`
  MODIFY `idmedio` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `modulo`
--
ALTER TABLE `modulo`
  MODIFY `idModulo` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `nivelesacceso`
--
ALTER TABLE `nivelesacceso`
  MODIFY `idnivel` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `planmateriaversiondocente`
--
ALTER TABLE `planmateriaversiondocente`
  MODIFY `idPlanMateriaVersionDocente` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `planmedios`
--
ALTER TABLE `planmedios`
  MODIFY `idPlanMedios` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `presupuestogastos`
--
ALTER TABLE `presupuestogastos`
  MODIFY `idPresupuestoGastos` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `presupuestoingresos`
--
ALTER TABLE `presupuestoingresos`
  MODIFY `idPresupuestoIngresos` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pres_detalle`
--
ALTER TABLE `pres_detalle`
  MODIFY `idpres_detalle` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `programa`
--
ALTER TABLE `programa`
  MODIFY `idPrograma` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `reclutamiento`
--
ALTER TABLE `reclutamiento`
  MODIFY `idReclutamiento` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `recursohumano`
--
ALTER TABLE `recursohumano`
  MODIFY `idRecursoHumano` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `rol`
--
ALTER TABLE `rol`
  MODIFY `idRol` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `rolciudad`
--
ALTER TABLE `rolciudad`
  MODIFY `idrolciudad` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `rolesactividades`
--
ALTER TABLE `rolesactividades`
  MODIFY `idRolesActividades` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `rolescuela`
--
ALTER TABLE `rolescuela`
  MODIFY `idrolescuela` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `rolesmodulos`
--
ALTER TABLE `rolesmodulos`
  MODIFY `idRolesModulos` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `roltipoprograma`
--
ALTER TABLE `roltipoprograma`
  MODIFY `idroltipoprograma` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `rolversion`
--
ALTER TABLE `rolversion`
  MODIFY `idrolversion` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tipomateria`
--
ALTER TABLE `tipomateria`
  MODIFY `idTipoMateria` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tipoprograma`
--
ALTER TABLE `tipoprograma`
  MODIFY `idtipoprograma` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tiposgastos`
--
ALTER TABLE `tiposgastos`
  MODIFY `idTiposGastos` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tiposingresos`
--
ALTER TABLE `tiposingresos`
  MODIFY `idTiposIngresos` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `version`
--
ALTER TABLE `version`
  MODIFY `idVersion` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
