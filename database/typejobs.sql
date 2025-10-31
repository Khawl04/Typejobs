-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 30-10-2025 a las 15:45:53
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
-- Base de datos: `typejobs`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `administra`
--

CREATE TABLE `administra` (
  `id_administrador` int(11) NOT NULL,
  `id_mantenimiento` int(11) NOT NULL,
  `fecha_inicio` date DEFAULT NULL,
  `estado_mantenimiento` enum('programado','en proceso','completado','cancelado') DEFAULT NULL,
  `fecha_completado` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `administrador`
--

CREATE TABLE `administrador` (
  `id_usuario` int(11) NOT NULL,
  `ultima_actividad` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `asistencia`
--

CREATE TABLE `asistencia` (
  `id_asistencia` int(11) NOT NULL,
  `id_soporte` int(11) NOT NULL,
  `titulo` varchar(100) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `fecha_creacion` timestamp NOT NULL DEFAULT current_timestamp(),
  `fecha_resolucion` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `busca`
--

CREATE TABLE `busca` (
  `id_servicio` int(11) NOT NULL,
  `id_cliente` int(11) NOT NULL,
  `fecha_busqueda` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `calificacion`
--

CREATE TABLE `calificacion` (
  `id_calificacion` int(11) NOT NULL,
  `id_cliente` int(11) NOT NULL,
  `id_servicio` int(11) NOT NULL,
  `fecha_calificacion` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categoria`
--

CREATE TABLE `categoria` (
  `id_categoria` int(11) NOT NULL,
  `nombre_categoria` varchar(100) NOT NULL,
  `descripcion` varchar(255) DEFAULT NULL,
  `fecha_creacion` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `categoria`
--

INSERT INTO `categoria` (`id_categoria`, `nombre_categoria`, `descripcion`, `fecha_creacion`) VALUES
(1, 'Prueba', 'Categoría de prueba para testear servicios', '2025-10-29 00:35:31'),
(2, 'asasdd', '', '2025-10-29 04:46:59');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cliente`
--

CREATE TABLE `cliente` (
  `id_usuario` int(11) NOT NULL,
  `fecha_ultimo_acceso` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `cliente`
--

INSERT INTO `cliente` (`id_usuario`, `fecha_ultimo_acceso`) VALUES
(16, '2025-10-27 05:29:42');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `envia`
--

CREATE TABLE `envia` (
  `id_usuario` int(11) NOT NULL,
  `id_notificacion` int(11) NOT NULL,
  `fecha_envio` date DEFAULT NULL,
  `tipo_notificacion` enum('reserva','pago','mensaje') NOT NULL,
  `leida` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `imagen_servicio`
--

CREATE TABLE `imagen_servicio` (
  `id_imagen` int(11) NOT NULL,
  `id_servicio` int(11) NOT NULL,
  `imagen` varchar(255) NOT NULL,
  `principal` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `informa`
--

CREATE TABLE `informa` (
  `id_notificacion` int(11) NOT NULL,
  `id_reserva` int(11) NOT NULL,
  `fecha_informe` date DEFAULT NULL,
  `estado_reserva` enum('pendiente','confirmada','en proceso','completada','cancelada') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `mantenimiento`
--

CREATE TABLE `mantenimiento` (
  `id_mantenimiento` int(11) NOT NULL,
  `id_servicio` int(11) NOT NULL,
  `id_administrador` int(11) NOT NULL,
  `titulo` varchar(100) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `fecha_programada` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `mensaje`
--

CREATE TABLE `mensaje` (
  `id_mensaje` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `id_usuario_dest` int(11) NOT NULL,
  `contenido` text NOT NULL,
  `leido` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `notificacion`
--

CREATE TABLE `notificacion` (
  `id_notificacion` int(11) NOT NULL,
  `id_usuario` int(11) DEFAULT NULL,
  `titulo` varchar(100) NOT NULL,
  `mensaje` text NOT NULL,
  `fecha_creacion` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `obtiene`
--

CREATE TABLE `obtiene` (
  `id_asistencia` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `estado_asistencia` enum('abierto','en proceso','resuelto') DEFAULT NULL,
  `prioridad` enum('baja','media','alta') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `otorga`
--

CREATE TABLE `otorga` (
  `id_calificacion` int(11) NOT NULL,
  `id_cliente` int(11) NOT NULL,
  `id_servicio` int(11) DEFAULT NULL,
  `fecha_otorga` date NOT NULL,
  `resenia` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pago`
--

CREATE TABLE `pago` (
  `id_pago` int(11) NOT NULL,
  `id_reserva` int(11) NOT NULL,
  `id_cliente` int(11) DEFAULT NULL,
  `monto` decimal(10,2) NOT NULL,
  `metodo_pago` enum('tarjeta','transferencia','efectivo') NOT NULL,
  `fecha_pago` datetime DEFAULT NULL,
  `estado` enum('pendiente','completado','reembolsado') DEFAULT 'pendiente',
  `transaccion_id` varchar(190) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `pago`
--

INSERT INTO `pago` (`id_pago`, `id_reserva`, `id_cliente`, `monto`, `metodo_pago`, `fecha_pago`, `estado`, `transaccion_id`) VALUES
(1, 1, NULL, 21.00, '', NULL, 'pendiente', NULL),
(2, 2, NULL, 21.00, '', NULL, 'pendiente', NULL),
(3, 3, NULL, 21.00, '', NULL, 'pendiente', NULL),
(4, 4, NULL, 21.00, '', NULL, 'pendiente', NULL),
(5, 5, NULL, 21.00, 'efectivo', '2025-10-30 02:20:26', 'completado', 'TXN-1761801626-6039'),
(6, 6, NULL, 21.00, 'tarjeta', '2025-10-30 02:45:40', 'completado', 'TXN-1761803140-8588'),
(7, 7, NULL, 21.00, '', NULL, 'pendiente', NULL),
(8, 8, NULL, 21.00, '', NULL, 'pendiente', NULL),
(9, 9, NULL, 21.00, '', NULL, 'pendiente', NULL),
(10, 10, NULL, 21.00, '', NULL, 'pendiente', NULL),
(11, 11, NULL, 21.00, '', NULL, 'pendiente', NULL),
(12, 12, NULL, 21.00, '', NULL, 'pendiente', NULL),
(13, 13, NULL, 21.00, '', NULL, 'pendiente', NULL),
(14, 14, NULL, 21.00, '', NULL, 'pendiente', NULL),
(15, 15, NULL, 21.00, '', NULL, 'pendiente', NULL),
(16, 16, NULL, 21.00, '', NULL, 'pendiente', NULL),
(17, 17, NULL, 21.00, 'tarjeta', '2025-10-30 05:01:51', 'completado', 'TXN-1761811311-9943'),
(18, 18, NULL, 21.00, 'efectivo', '2025-10-30 05:03:09', 'completado', 'TXN-1761811389-4456'),
(19, 19, NULL, 21.00, 'efectivo', '2025-10-30 05:03:36', 'completado', 'TXN-1761811416-6399'),
(20, 20, NULL, 21.00, '', NULL, 'pendiente', NULL),
(21, 21, NULL, 16.00, 'tarjeta', '2025-10-30 05:51:18', 'completado', 'TXN-1761814278-4232'),
(22, 22, 16, 21.00, 'tarjeta', '2025-10-30 05:55:32', 'completado', 'TXN-1761814532-1794'),
(24, 23, 16, 21.00, '', NULL, 'pendiente', NULL),
(25, 24, 16, 21.00, '', NULL, 'pendiente', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pertenece`
--

CREATE TABLE `pertenece` (
  `id_servicio` int(11) NOT NULL,
  `id_categoria` int(11) NOT NULL,
  `estado_categoria` enum('activa','inactiva') DEFAULT 'activa'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `provee`
--

CREATE TABLE `provee` (
  `id_servicio` int(11) NOT NULL,
  `id_proveedor` int(11) NOT NULL,
  `precio` decimal(10,2) NOT NULL,
  `duracion_estimada` int(11) DEFAULT NULL,
  `fecha_creacion` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `proveedor`
--

CREATE TABLE `proveedor` (
  `id_usuario` int(11) NOT NULL,
  `calificacion_promedio` decimal(3,2) DEFAULT 0.00,
  `servicios_activos` int(11) DEFAULT 0,
  `fecha_verificacion` date DEFAULT NULL,
  `descripcion` text DEFAULT NULL,
  `direccion` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `proveedor`
--

INSERT INTO `proveedor` (`id_usuario`, `calificacion_promedio`, `servicios_activos`, `fecha_verificacion`, `descripcion`, `direccion`) VALUES
(18, 3.50, 1, NULL, 'sdaasas', 'vssd'),
(19, 5.00, 2, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `recibe`
--

CREATE TABLE `recibe` (
  `id_usuario` int(11) NOT NULL,
  `id_mensaje` int(11) NOT NULL,
  `fecha_recepcion` date DEFAULT NULL,
  `leido` tinyint(1) DEFAULT 0,
  `tipo_mensaje` enum('texto','imagen') DEFAULT 'texto'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `requiere`
--

CREATE TABLE `requiere` (
  `id_mantenimiento` int(11) NOT NULL,
  `id_servicio` int(11) NOT NULL,
  `estado_mantenimiento` enum('programado','en proceso','completado','cancelado') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `resena`
--

CREATE TABLE `resena` (
  `id_resena` int(11) NOT NULL,
  `id_servicio` int(11) NOT NULL,
  `id_cliente` int(11) NOT NULL,
  `calificacion` decimal(3,2) NOT NULL,
  `texto` varchar(450) NOT NULL,
  `fecha` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `resena`
--

INSERT INTO `resena` (`id_resena`, `id_servicio`, `id_cliente`, `calificacion`, `texto`, `fecha`) VALUES
(2, 24, 16, 5.00, 'Excelente trabajo, muy profesional...', '2025-10-29 01:57:03');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `reserva`
--

CREATE TABLE `reserva` (
  `id_reserva` int(11) NOT NULL,
  `id_cliente` int(11) NOT NULL,
  `id_servicio` int(11) NOT NULL,
  `id_notificacion` int(11) DEFAULT NULL,
  `fecha_reserva` datetime DEFAULT NULL,
  `hora_inicio` time DEFAULT NULL,
  `hora_fin` time DEFAULT NULL,
  `fecha_creacion` timestamp NOT NULL DEFAULT current_timestamp(),
  `observaciones` varchar(255) DEFAULT NULL,
  `estado` varchar(32) NOT NULL DEFAULT 'pendiente',
  `notas` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `reserva`
--

INSERT INTO `reserva` (`id_reserva`, `id_cliente`, `id_servicio`, `id_notificacion`, `fecha_reserva`, `hora_inicio`, `hora_fin`, `fecha_creacion`, `observaciones`, `estado`, `notas`) VALUES
(1, 16, 24, NULL, '2025-10-29 00:00:00', '21:35:00', '21:38:00', '2025-10-30 00:36:12', NULL, 'pendiente', 'saas'),
(2, 16, 24, NULL, '2025-10-30 00:00:00', '01:03:00', '01:05:00', '2025-10-30 04:00:33', NULL, 'pendiente', 'fdfdfd'),
(3, 16, 24, NULL, '2025-10-30 00:00:00', '03:21:00', '05:21:00', '2025-10-30 04:21:20', NULL, 'pendiente', 'jj'),
(4, 16, 24, NULL, '2025-10-30 00:00:00', '03:39:00', '05:39:00', '2025-10-30 04:39:32', NULL, 'pendiente', 'ffgfg'),
(5, 16, 24, NULL, '2025-10-30 00:00:00', '06:20:00', '02:20:00', '2025-10-30 05:15:09', NULL, 'confirmada', 'dasdsda'),
(6, 16, 24, NULL, '2025-10-30 00:00:00', '06:29:00', '06:28:00', '2025-10-30 05:25:00', NULL, 'confirmada', 'w'),
(7, 16, 24, NULL, '2025-10-30 00:00:00', '02:50:00', '02:53:00', '2025-10-30 05:47:27', NULL, 'pendiente', 'asass'),
(8, 16, 24, NULL, '2025-10-30 00:00:00', '07:16:00', '07:16:00', '2025-10-30 06:12:45', NULL, 'pendiente', 'ssd'),
(9, 16, 24, NULL, '2025-10-30 00:00:00', '03:34:00', '03:39:00', '2025-10-30 06:30:04', NULL, 'pendiente', 'ghghf'),
(10, 16, 24, NULL, '2025-10-30 00:00:00', '08:30:00', '03:35:00', '2025-10-30 06:30:41', NULL, 'pendiente', 'frf'),
(11, 16, 24, NULL, '2025-10-30 00:00:00', '09:23:00', '08:23:00', '2025-10-30 07:23:39', NULL, 'pendiente', 'vbvb'),
(12, 16, 24, NULL, '2025-10-30 00:00:00', '07:28:00', '04:33:00', '2025-10-30 07:28:52', NULL, 'pendiente', 'sasadads'),
(13, 16, 24, NULL, '2025-10-30 00:00:00', '04:46:00', '04:45:00', '2025-10-30 07:41:54', NULL, 'pendiente', 'dsffdsfds'),
(14, 16, 24, NULL, '2025-10-30 00:00:00', '08:43:00', '21:43:00', '2025-10-30 07:43:56', NULL, 'pendiente', 'sddd'),
(15, 16, 24, NULL, '2025-10-30 00:00:00', '04:53:00', '09:47:00', '2025-10-30 07:47:19', NULL, 'pendiente', 'ddsa'),
(16, 16, 24, NULL, '2025-10-30 00:00:00', '09:48:00', '04:54:00', '2025-10-30 07:48:49', NULL, 'pendiente', 'adsas'),
(17, 16, 24, NULL, '2025-10-16 00:00:00', '04:01:00', '10:01:00', '2025-10-30 07:57:06', NULL, 'confirmada', 'fvff'),
(18, 16, 24, NULL, '2025-10-30 00:00:00', '10:02:00', '05:06:00', '2025-10-30 08:02:03', NULL, 'confirmada', 'ssadsad'),
(19, 16, 24, NULL, '2025-10-30 00:00:00', '08:03:00', '09:03:00', '2025-10-30 08:03:26', NULL, 'pendiente', 'dwwd'),
(20, 16, 24, NULL, '2025-11-01 00:00:00', '08:28:00', '10:28:00', '2025-10-30 08:29:00', NULL, 'pendiente', 'gfgf'),
(21, 16, 24, NULL, '2025-10-30 00:00:00', '05:55:00', '09:54:00', '2025-10-30 08:51:01', NULL, 'confirmada', 'gbfgf'),
(22, 16, 24, NULL, '2025-10-30 00:00:00', '05:00:00', '05:59:00', '2025-10-30 08:55:06', NULL, 'confirmada', 'fvf'),
(23, 16, 24, NULL, '2025-10-30 00:00:00', '09:04:00', '05:04:00', '2025-10-30 08:59:54', NULL, 'pendiente', 'ssadasd'),
(24, 16, 24, NULL, '2025-10-30 00:00:00', '06:10:00', '11:05:00', '2025-10-30 09:05:08', NULL, 'pendiente', 'fdfd');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `servicio`
--

CREATE TABLE `servicio` (
  `id_servicio` int(11) NOT NULL,
  `id_proveedor` int(11) NOT NULL,
  `id_categoria` int(11) NOT NULL,
  `titulo` varchar(100) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `ubicacion` varchar(255) DEFAULT NULL,
  `estado` varchar(20) DEFAULT 'disponible',
  `precio` decimal(10,2) NOT NULL DEFAULT 0.00,
  `duracion_estimada` int(11) DEFAULT NULL,
  `imagen_servicio` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `servicio`
--

INSERT INTO `servicio` (`id_servicio`, `id_proveedor`, `id_categoria`, `titulo`, `descripcion`, `ubicacion`, `estado`, `precio`, `duracion_estimada`, `imagen_servicio`) VALUES
(21, 18, 1, 'fdsfsd', 'sffsd', NULL, 'disponible', 21.00, 23, 'uploads/servicios/servicio_21_1761709029.jpg'),
(23, 18, 1, 'asasdsd', 'ads', NULL, 'disponible', 123.00, 211, 'uploads/servicios/servicio_23_1761711299.jpg'),
(24, 18, 2, 'asas', 'asdas', NULL, 'disponible', 21.00, 12, 'uploads/servicios/servicio_24_1761713219.jpg');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `soporte`
--

CREATE TABLE `soporte` (
  `id_usuario` int(11) NOT NULL,
  `casos_asignados` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `superadmin`
--

CREATE TABLE `superadmin` (
  `id_superadmin` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario`
--

CREATE TABLE `usuario` (
  `id_usuario` int(11) NOT NULL,
  `email` varchar(50) NOT NULL,
  `contrasena` varchar(255) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `apellido` varchar(50) NOT NULL,
  `nomusuario` varchar(40) DEFAULT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `fecha_registro` timestamp NOT NULL DEFAULT current_timestamp(),
  `estado` enum('activo','inactivo','suspendido') DEFAULT 'activo',
  `tipo_usuario` enum('cliente','proveedor','soporte','administrador') NOT NULL,
  `foto_perfil` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuario`
--

INSERT INTO `usuario` (`id_usuario`, `email`, `contrasena`, `nombre`, `apellido`, `nomusuario`, `telefono`, `fecha_registro`, `estado`, `tipo_usuario`, `foto_perfil`) VALUES
(16, 'juano5@gmail.com', '$2y$10$Tsv7lwEROA8hVgdYFBQMX.UfcM07MkGx2HZxWylZOEYT9zHLRclb2', 'Juan', 'Alvarez', 'Juano8', '2234', '2025-10-27 05:29:42', 'activo', 'cliente', 'perfil_16_1761767428.jpg'),
(18, 'Luano2@gmail.com', '$2y$10$e.w6y0kPA3leSsnuSexdaO/W/kb/lV4YiegjZwigUbpJVern33RU2', 'Luan', 'Gomez', 'Luano2', '2322', '2025-10-27 23:42:16', 'activo', 'proveedor', 'perfil_18_1761768206.jpg'),
(19, 'maria@example.com', 'contraseñaEncriptada', 'Maria', 'Perez', 'mariap', NULL, '2025-10-27 23:43:42', 'activo', 'proveedor', NULL);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `administra`
--
ALTER TABLE `administra`
  ADD PRIMARY KEY (`id_administrador`,`id_mantenimiento`),
  ADD KEY `id_mantenimiento` (`id_mantenimiento`);

--
-- Indices de la tabla `administrador`
--
ALTER TABLE `administrador`
  ADD PRIMARY KEY (`id_usuario`);

--
-- Indices de la tabla `asistencia`
--
ALTER TABLE `asistencia`
  ADD PRIMARY KEY (`id_asistencia`),
  ADD KEY `id_soporte` (`id_soporte`);

--
-- Indices de la tabla `busca`
--
ALTER TABLE `busca`
  ADD PRIMARY KEY (`id_servicio`,`id_cliente`),
  ADD KEY `id_cliente` (`id_cliente`);

--
-- Indices de la tabla `calificacion`
--
ALTER TABLE `calificacion`
  ADD PRIMARY KEY (`id_calificacion`),
  ADD KEY `id_cliente` (`id_cliente`),
  ADD KEY `id_servicio` (`id_servicio`);

--
-- Indices de la tabla `categoria`
--
ALTER TABLE `categoria`
  ADD PRIMARY KEY (`id_categoria`);

--
-- Indices de la tabla `cliente`
--
ALTER TABLE `cliente`
  ADD PRIMARY KEY (`id_usuario`);

--
-- Indices de la tabla `envia`
--
ALTER TABLE `envia`
  ADD PRIMARY KEY (`id_notificacion`,`id_usuario`),
  ADD KEY `id_usuario` (`id_usuario`);

--
-- Indices de la tabla `imagen_servicio`
--
ALTER TABLE `imagen_servicio`
  ADD PRIMARY KEY (`id_imagen`),
  ADD KEY `id_servicio` (`id_servicio`);

--
-- Indices de la tabla `informa`
--
ALTER TABLE `informa`
  ADD PRIMARY KEY (`id_notificacion`,`id_reserva`),
  ADD KEY `id_reserva` (`id_reserva`);

--
-- Indices de la tabla `mantenimiento`
--
ALTER TABLE `mantenimiento`
  ADD PRIMARY KEY (`id_mantenimiento`),
  ADD KEY `id_servicio` (`id_servicio`),
  ADD KEY `id_administrador` (`id_administrador`);

--
-- Indices de la tabla `mensaje`
--
ALTER TABLE `mensaje`
  ADD PRIMARY KEY (`id_mensaje`),
  ADD KEY `id_usuario` (`id_usuario`);

--
-- Indices de la tabla `notificacion`
--
ALTER TABLE `notificacion`
  ADD PRIMARY KEY (`id_notificacion`),
  ADD KEY `id_usuario` (`id_usuario`);

--
-- Indices de la tabla `obtiene`
--
ALTER TABLE `obtiene`
  ADD PRIMARY KEY (`id_asistencia`,`id_usuario`),
  ADD KEY `id_usuario` (`id_usuario`);

--
-- Indices de la tabla `otorga`
--
ALTER TABLE `otorga`
  ADD PRIMARY KEY (`id_calificacion`,`id_cliente`,`fecha_otorga`),
  ADD KEY `id_cliente` (`id_cliente`);

--
-- Indices de la tabla `pago`
--
ALTER TABLE `pago`
  ADD PRIMARY KEY (`id_pago`),
  ADD KEY `id_reserva` (`id_reserva`);

--
-- Indices de la tabla `pertenece`
--
ALTER TABLE `pertenece`
  ADD PRIMARY KEY (`id_servicio`,`id_categoria`),
  ADD KEY `id_categoria` (`id_categoria`);

--
-- Indices de la tabla `provee`
--
ALTER TABLE `provee`
  ADD PRIMARY KEY (`id_servicio`,`id_proveedor`),
  ADD KEY `id_proveedor` (`id_proveedor`);

--
-- Indices de la tabla `proveedor`
--
ALTER TABLE `proveedor`
  ADD PRIMARY KEY (`id_usuario`);

--
-- Indices de la tabla `recibe`
--
ALTER TABLE `recibe`
  ADD PRIMARY KEY (`id_mensaje`,`id_usuario`),
  ADD KEY `id_usuario` (`id_usuario`);

--
-- Indices de la tabla `requiere`
--
ALTER TABLE `requiere`
  ADD PRIMARY KEY (`id_mantenimiento`,`id_servicio`),
  ADD KEY `id_servicio` (`id_servicio`);

--
-- Indices de la tabla `resena`
--
ALTER TABLE `resena`
  ADD PRIMARY KEY (`id_resena`),
  ADD KEY `id_servicio` (`id_servicio`),
  ADD KEY `id_cliente` (`id_cliente`);

--
-- Indices de la tabla `reserva`
--
ALTER TABLE `reserva`
  ADD PRIMARY KEY (`id_reserva`),
  ADD KEY `id_cliente` (`id_cliente`),
  ADD KEY `id_servicio` (`id_servicio`),
  ADD KEY `id_notificacion` (`id_notificacion`);

--
-- Indices de la tabla `servicio`
--
ALTER TABLE `servicio`
  ADD PRIMARY KEY (`id_servicio`),
  ADD KEY `id_proveedor` (`id_proveedor`),
  ADD KEY `id_categoria` (`id_categoria`);

--
-- Indices de la tabla `soporte`
--
ALTER TABLE `soporte`
  ADD PRIMARY KEY (`id_usuario`);

--
-- Indices de la tabla `superadmin`
--
ALTER TABLE `superadmin`
  ADD PRIMARY KEY (`id_superadmin`);

--
-- Indices de la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD PRIMARY KEY (`id_usuario`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `nomusuario` (`nomusuario`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `asistencia`
--
ALTER TABLE `asistencia`
  MODIFY `id_asistencia` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `calificacion`
--
ALTER TABLE `calificacion`
  MODIFY `id_calificacion` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `categoria`
--
ALTER TABLE `categoria`
  MODIFY `id_categoria` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `imagen_servicio`
--
ALTER TABLE `imagen_servicio`
  MODIFY `id_imagen` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `mantenimiento`
--
ALTER TABLE `mantenimiento`
  MODIFY `id_mantenimiento` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `mensaje`
--
ALTER TABLE `mensaje`
  MODIFY `id_mensaje` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `notificacion`
--
ALTER TABLE `notificacion`
  MODIFY `id_notificacion` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `pago`
--
ALTER TABLE `pago`
  MODIFY `id_pago` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT de la tabla `resena`
--
ALTER TABLE `resena`
  MODIFY `id_resena` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `reserva`
--
ALTER TABLE `reserva`
  MODIFY `id_reserva` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT de la tabla `servicio`
--
ALTER TABLE `servicio`
  MODIFY `id_servicio` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT de la tabla `superadmin`
--
ALTER TABLE `superadmin`
  MODIFY `id_superadmin` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `usuario`
--
ALTER TABLE `usuario`
  MODIFY `id_usuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `administra`
--
ALTER TABLE `administra`
  ADD CONSTRAINT `administra_ibfk_1` FOREIGN KEY (`id_administrador`) REFERENCES `administrador` (`id_usuario`),
  ADD CONSTRAINT `administra_ibfk_2` FOREIGN KEY (`id_mantenimiento`) REFERENCES `mantenimiento` (`id_mantenimiento`);

--
-- Filtros para la tabla `administrador`
--
ALTER TABLE `administrador`
  ADD CONSTRAINT `administrador_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id_usuario`);

--
-- Filtros para la tabla `asistencia`
--
ALTER TABLE `asistencia`
  ADD CONSTRAINT `asistencia_ibfk_1` FOREIGN KEY (`id_soporte`) REFERENCES `soporte` (`id_usuario`);

--
-- Filtros para la tabla `busca`
--
ALTER TABLE `busca`
  ADD CONSTRAINT `busca_ibfk_1` FOREIGN KEY (`id_servicio`) REFERENCES `servicio` (`id_servicio`),
  ADD CONSTRAINT `busca_ibfk_2` FOREIGN KEY (`id_cliente`) REFERENCES `cliente` (`id_usuario`);

--
-- Filtros para la tabla `calificacion`
--
ALTER TABLE `calificacion`
  ADD CONSTRAINT `calificacion_ibfk_1` FOREIGN KEY (`id_cliente`) REFERENCES `cliente` (`id_usuario`),
  ADD CONSTRAINT `calificacion_ibfk_2` FOREIGN KEY (`id_servicio`) REFERENCES `servicio` (`id_servicio`);

--
-- Filtros para la tabla `cliente`
--
ALTER TABLE `cliente`
  ADD CONSTRAINT `cliente_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id_usuario`);

--
-- Filtros para la tabla `envia`
--
ALTER TABLE `envia`
  ADD CONSTRAINT `envia_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id_usuario`),
  ADD CONSTRAINT `envia_ibfk_2` FOREIGN KEY (`id_notificacion`) REFERENCES `notificacion` (`id_notificacion`);

--
-- Filtros para la tabla `imagen_servicio`
--
ALTER TABLE `imagen_servicio`
  ADD CONSTRAINT `imagen_servicio_ibfk_1` FOREIGN KEY (`id_servicio`) REFERENCES `servicio` (`id_servicio`);

--
-- Filtros para la tabla `informa`
--
ALTER TABLE `informa`
  ADD CONSTRAINT `informa_ibfk_1` FOREIGN KEY (`id_notificacion`) REFERENCES `notificacion` (`id_notificacion`),
  ADD CONSTRAINT `informa_ibfk_2` FOREIGN KEY (`id_reserva`) REFERENCES `reserva` (`id_reserva`);

--
-- Filtros para la tabla `mantenimiento`
--
ALTER TABLE `mantenimiento`
  ADD CONSTRAINT `mantenimiento_ibfk_1` FOREIGN KEY (`id_servicio`) REFERENCES `servicio` (`id_servicio`),
  ADD CONSTRAINT `mantenimiento_ibfk_2` FOREIGN KEY (`id_administrador`) REFERENCES `administrador` (`id_usuario`);

--
-- Filtros para la tabla `mensaje`
--
ALTER TABLE `mensaje`
  ADD CONSTRAINT `mensaje_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id_usuario`);

--
-- Filtros para la tabla `notificacion`
--
ALTER TABLE `notificacion`
  ADD CONSTRAINT `notificacion_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id_usuario`);

--
-- Filtros para la tabla `obtiene`
--
ALTER TABLE `obtiene`
  ADD CONSTRAINT `obtiene_ibfk_1` FOREIGN KEY (`id_asistencia`) REFERENCES `asistencia` (`id_asistencia`),
  ADD CONSTRAINT `obtiene_ibfk_2` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id_usuario`);

--
-- Filtros para la tabla `otorga`
--
ALTER TABLE `otorga`
  ADD CONSTRAINT `otorga_ibfk_1` FOREIGN KEY (`id_calificacion`) REFERENCES `calificacion` (`id_calificacion`),
  ADD CONSTRAINT `otorga_ibfk_2` FOREIGN KEY (`id_cliente`) REFERENCES `cliente` (`id_usuario`);

--
-- Filtros para la tabla `pago`
--
ALTER TABLE `pago`
  ADD CONSTRAINT `pago_ibfk_1` FOREIGN KEY (`id_reserva`) REFERENCES `reserva` (`id_reserva`);

--
-- Filtros para la tabla `pertenece`
--
ALTER TABLE `pertenece`
  ADD CONSTRAINT `pertenece_ibfk_1` FOREIGN KEY (`id_servicio`) REFERENCES `servicio` (`id_servicio`),
  ADD CONSTRAINT `pertenece_ibfk_2` FOREIGN KEY (`id_categoria`) REFERENCES `categoria` (`id_categoria`);

--
-- Filtros para la tabla `provee`
--
ALTER TABLE `provee`
  ADD CONSTRAINT `provee_ibfk_1` FOREIGN KEY (`id_servicio`) REFERENCES `servicio` (`id_servicio`),
  ADD CONSTRAINT `provee_ibfk_2` FOREIGN KEY (`id_proveedor`) REFERENCES `proveedor` (`id_usuario`);

--
-- Filtros para la tabla `proveedor`
--
ALTER TABLE `proveedor`
  ADD CONSTRAINT `proveedor_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id_usuario`);

--
-- Filtros para la tabla `recibe`
--
ALTER TABLE `recibe`
  ADD CONSTRAINT `recibe_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id_usuario`),
  ADD CONSTRAINT `recibe_ibfk_2` FOREIGN KEY (`id_mensaje`) REFERENCES `mensaje` (`id_mensaje`);

--
-- Filtros para la tabla `requiere`
--
ALTER TABLE `requiere`
  ADD CONSTRAINT `requiere_ibfk_1` FOREIGN KEY (`id_mantenimiento`) REFERENCES `mantenimiento` (`id_mantenimiento`),
  ADD CONSTRAINT `requiere_ibfk_2` FOREIGN KEY (`id_servicio`) REFERENCES `servicio` (`id_servicio`);

--
-- Filtros para la tabla `resena`
--
ALTER TABLE `resena`
  ADD CONSTRAINT `resena_ibfk_1` FOREIGN KEY (`id_servicio`) REFERENCES `servicio` (`id_servicio`) ON DELETE CASCADE,
  ADD CONSTRAINT `resena_ibfk_2` FOREIGN KEY (`id_cliente`) REFERENCES `cliente` (`id_usuario`) ON DELETE CASCADE;

--
-- Filtros para la tabla `reserva`
--
ALTER TABLE `reserva`
  ADD CONSTRAINT `reserva_ibfk_1` FOREIGN KEY (`id_cliente`) REFERENCES `cliente` (`id_usuario`),
  ADD CONSTRAINT `reserva_ibfk_2` FOREIGN KEY (`id_servicio`) REFERENCES `servicio` (`id_servicio`),
  ADD CONSTRAINT `reserva_ibfk_3` FOREIGN KEY (`id_notificacion`) REFERENCES `notificacion` (`id_notificacion`);

--
-- Filtros para la tabla `servicio`
--
ALTER TABLE `servicio`
  ADD CONSTRAINT `servicio_ibfk_2` FOREIGN KEY (`id_categoria`) REFERENCES `categoria` (`id_categoria`);

--
-- Filtros para la tabla `soporte`
--
ALTER TABLE `soporte`
  ADD CONSTRAINT `soporte_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id_usuario`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
