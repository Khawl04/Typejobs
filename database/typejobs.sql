-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 31-10-2025 a las 12:10:22
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
(2, 'asasdd', '', '2025-10-29 04:46:59'),
(3, 'Bananin', '', '2025-10-30 22:52:21');

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
(16, '2025-10-27 05:29:42'),
(20, '2025-10-30 20:58:29'),
(23, '2025-10-30 21:28:23'),
(24, '2025-10-30 21:30:29'),
(25, '2025-10-30 21:34:18'),
(26, '2025-10-30 21:37:34'),
(27, '2025-10-30 21:44:37'),
(28, '2025-10-30 21:46:27');

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
  `leido` tinyint(1) DEFAULT 0,
  `archivo_adjunto` varchar(255) DEFAULT NULL,
  `fecha_envio` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `mensaje`
--

INSERT INTO `mensaje` (`id_mensaje`, `id_usuario`, `id_usuario_dest`, `contenido`, `leido`, `archivo_adjunto`, `fecha_envio`) VALUES
(1, 16, 18, 'dsa', 1, NULL, '2025-10-30 15:16:51'),
(2, 16, 18, 'dsa', 1, NULL, '2025-10-30 15:16:51'),
(3, 16, 18, 'dsa', 1, NULL, '2025-10-30 15:16:51'),
(4, 16, 18, 'dsa', 1, NULL, '2025-10-30 15:16:51'),
(5, 16, 18, 'sa', 1, NULL, '2025-10-30 15:16:51'),
(6, 18, 16, 'sass', 0, NULL, '2025-10-30 15:16:51'),
(7, 16, 18, 'asasd', 1, NULL, '2025-10-30 15:16:51'),
(8, 16, 18, 'sa', 1, NULL, '2025-10-30 15:16:51'),
(9, 16, 18, 'cdsd', 1, NULL, '2025-10-30 15:16:51'),
(10, 16, 18, 'cdsd', 1, NULL, '2025-10-30 15:16:51'),
(11, 16, 18, 'dssa', 1, NULL, '2025-10-30 15:16:51'),
(12, 16, 18, 'as', 1, NULL, '2025-10-30 15:16:51'),
(13, 16, 18, 'saa', 1, NULL, '2025-10-30 15:16:51'),
(14, 16, 18, 'gg', 1, NULL, '2025-10-30 15:57:22'),
(15, 16, 18, 'fff', 1, NULL, '2025-10-30 15:58:31'),
(16, 16, 18, 'assdadas', 1, NULL, '2025-10-30 16:16:20'),
(17, 18, 16, 'saddsa', 0, NULL, '2025-10-30 16:18:10'),
(18, 16, 18, 'f', 1, NULL, '2025-10-30 17:11:23'),
(19, 16, 18, 'hola', 1, NULL, '2025-10-30 17:16:23'),
(20, 16, 18, 'si', 1, NULL, '2025-10-30 17:16:52'),
(21, 16, 18, '', 1, NULL, '2025-10-30 17:28:23'),
(22, 16, 18, '', 1, NULL, '2025-10-30 17:30:26'),
(23, 16, 18, '', 1, 'uploads/mensaje/6903cbf1119ca_08a3dd841025e3d2b73121cd8d0af545.jpg', '2025-10-30 17:34:57'),
(24, 16, 18, '', 1, 'uploads/mensaje/6903cd2613217_caratula.pdf', '2025-10-30 17:40:06'),
(25, 16, 18, '', 1, 'uploads/mensaje/6903cd3d65490_16865854344306.jpg', '2025-10-30 17:40:29'),
(26, 16, 18, '', 1, 'uploads/mensaje/6903cd4ba391d_requerimientosfuncionales.docx', '2025-10-30 17:40:43'),
(27, 16, 18, '', 1, 'uploads/mensaje/6903ce4864c0b_Literatura escrito.doc', '2025-10-30 17:44:56'),
(28, 16, 18, '', 1, 'uploads/mensaje/6903ce9743d93_holas.txt', '2025-10-30 17:46:15'),
(29, 18, 16, '', 0, 'uploads/mensaje/6903ceb5d7c6a_holas.txt', '2025-10-30 17:46:45'),
(30, 18, 16, '', 0, 'uploads/mensajes/6903cf2c96ee2_holas.txt', '2025-10-30 17:48:44'),
(31, 18, 16, '', 0, 'uploads/mensajes/6903cf7b742aa_holas.txt', '2025-10-30 17:50:03'),
(32, 20, 18, 'sasdasd', 1, NULL, '2025-10-30 18:07:00'),
(33, 16, 18, 'sadasd', 1, NULL, '2025-10-31 00:05:48'),
(34, 16, 18, '', 1, 'uploads/mensajes/690427921b975_dancing-banana.gif', '2025-10-31 00:05:54'),
(35, 16, 18, '', 1, 'uploads/mensajes/69042797a9f8c_requerimientosfuncionales.docx', '2025-10-31 00:05:59');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `notificacion`
--

CREATE TABLE `notificacion` (
  `id_notificacion` int(11) NOT NULL,
  `id_usuario` int(11) DEFAULT NULL,
  `titulo` varchar(100) NOT NULL,
  `mensaje` text NOT NULL,
  `fecha_creacion` timestamp NOT NULL DEFAULT current_timestamp(),
  `tipo` varchar(50) DEFAULT NULL,
  `contenido` text DEFAULT NULL,
  `url_accion` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `notificacion`
--

INSERT INTO `notificacion` (`id_notificacion`, `id_usuario`, `titulo`, `mensaje`, `fecha_creacion`, `tipo`, `contenido`, `url_accion`) VALUES
(1, 18, 'Nuevo mensaje recibido', '', '2025-10-30 17:24:08', NULL, 'Tienes un nuevo mensaje de Juan', '/mensaje/mensaje?chat=16'),
(2, 18, 'Nuevo mensaje recibido', '', '2025-10-30 17:24:12', NULL, 'Tienes un nuevo mensaje de Juan', '/mensaje/mensaje?chat=16'),
(3, 16, 'Nuevo mensaje recibido', '', '2025-10-30 17:24:59', NULL, 'Tienes un nuevo mensaje de Luan', '/mensaje/mensaje?chat=18'),
(4, 18, 'Nuevo mensaje recibido', '', '2025-10-30 17:41:20', NULL, 'Tienes un nuevo mensaje de Juan', '/mensaje/mensaje?chat=16'),
(5, 18, 'Nuevo mensaje recibido', '', '2025-10-30 17:41:31', NULL, 'Tienes un nuevo mensaje de Juan', '/mensaje/mensaje?chat=16'),
(6, 18, 'Nuevo mensaje recibido', '', '2025-10-30 17:41:45', NULL, 'Tienes un nuevo mensaje de Juan', '/mensaje/mensaje?chat=16'),
(7, 18, 'Nuevo mensaje recibido', '', '2025-10-30 17:41:56', NULL, 'Tienes un nuevo mensaje de Juan', '/mensaje/mensaje?chat=16'),
(8, 18, 'Nuevo mensaje recibido', '', '2025-10-30 18:06:24', NULL, NULL, '/mensaje/mensaje?chat=16'),
(9, 18, 'Nuevo mensaje recibido', '', '2025-10-30 18:11:31', NULL, NULL, '/mensaje/mensaje?chat=16'),
(10, 18, 'Nuevo mensaje recibido', '', '2025-10-30 18:13:26', NULL, NULL, '/mensaje/mensaje?chat=16'),
(11, 18, 'Nuevo mensaje recibido', '', '2025-10-30 18:57:22', NULL, NULL, '/mensaje/mensaje?chat=16'),
(12, 18, 'Nuevo mensaje recibido', '', '2025-10-30 18:58:31', NULL, NULL, '/mensaje/mensaje?chat=16'),
(13, 18, 'Nuevo mensaje recibido', '', '2025-10-30 19:16:20', NULL, NULL, '/mensaje?chat=16'),
(14, 16, 'Nuevo mensaje recibido', '', '2025-10-30 19:18:10', NULL, NULL, '/mensaje?chat=18'),
(15, 18, 'Nuevo mensaje recibido', '', '2025-10-30 20:11:23', NULL, NULL, '/mensaje?chat=16'),
(16, 18, 'Nuevo mensaje recibido', '', '2025-10-30 20:16:23', NULL, NULL, '/mensaje?chat=16'),
(17, 18, 'Nuevo mensaje recibido', '', '2025-10-30 20:16:52', NULL, NULL, '/mensaje?chat=16'),
(18, 18, 'Nuevo mensaje recibido', '', '2025-10-30 20:28:23', NULL, NULL, '/mensaje?chat=16'),
(19, 18, 'Nuevo mensaje recibido', '', '2025-10-30 20:30:26', NULL, NULL, '/mensaje?chat=16'),
(20, 18, 'Nuevo mensaje recibido', '', '2025-10-30 20:34:57', NULL, NULL, '/mensaje?chat=16'),
(21, 18, 'Nuevo mensaje recibido', '', '2025-10-30 20:40:06', NULL, NULL, '/mensaje?chat=16'),
(22, 18, 'Nuevo mensaje recibido', '', '2025-10-30 20:40:29', NULL, NULL, '/mensaje?chat=16'),
(23, 18, 'Nuevo mensaje recibido', '', '2025-10-30 20:40:43', NULL, NULL, '/mensaje?chat=16'),
(24, 18, 'Nuevo mensaje recibido', '', '2025-10-30 20:44:56', NULL, NULL, '/mensaje?chat=16'),
(25, 18, 'Nuevo mensaje recibido', '', '2025-10-30 20:46:15', NULL, NULL, '/mensaje?chat=16'),
(26, 16, 'Nuevo mensaje recibido', '', '2025-10-30 20:46:45', NULL, NULL, '/mensaje?chat=18'),
(27, 16, 'Nuevo mensaje recibido', '', '2025-10-30 20:48:44', NULL, NULL, '/mensaje?chat=18'),
(28, 16, 'Nuevo mensaje recibido', '', '2025-10-30 20:50:03', NULL, NULL, '/mensaje?chat=18'),
(29, 18, 'Nuevo mensaje recibido', '', '2025-10-30 21:07:00', NULL, NULL, '/mensaje?chat=20'),
(30, 18, 'Nuevo mensaje recibido', '', '2025-10-31 03:05:48', NULL, NULL, '/mensaje?chat=16'),
(31, 18, 'Nuevo mensaje recibido', '', '2025-10-31 03:05:54', NULL, NULL, '/mensaje?chat=16'),
(32, 18, 'Nuevo mensaje recibido', '', '2025-10-31 03:05:59', NULL, NULL, '/mensaje?chat=16');

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
(19, 5.00, 2, NULL, NULL, NULL),
(21, 0.00, 0, NULL, NULL, NULL);

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
  `fecha` datetime NOT NULL DEFAULT current_timestamp(),
  `likes` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
  `imagen_servicio` varchar(255) DEFAULT NULL,
  `calificacion` decimal(3,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `servicio`
--

INSERT INTO `servicio` (`id_servicio`, `id_proveedor`, `id_categoria`, `titulo`, `descripcion`, `ubicacion`, `estado`, `precio`, `duracion_estimada`, `imagen_servicio`, `calificacion`) VALUES
(25, 18, 3, 'Banana dance', 'soy una banana', NULL, 'disponible', 214.00, 65, 'uploads/servicios/servicio_25_1761864741.gif', NULL),
(31, 18, 2, 'ddsa', 'assad', NULL, 'disponible', 2327.00, 226, 'uploads/servicios/servicio_31_1761905466.jpg', NULL),
(33, 18, 3, 'assww', 'asasdee', NULL, 'disponible', 2112.00, 213, 'uploads/servicios/servicio_33_1761907198.jpg', NULL),
(34, 18, 1, 'fsfdfds', 'fsdsdf', NULL, 'disponible', 332.00, 212, 'uploads/servicios/servicio_34_1761907445.gif', NULL);

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
(19, 'maria@example.com', 'contraseñaEncriptada', 'Maria', 'Perez', 'mariap', NULL, '2025-10-27 23:43:42', 'activo', 'proveedor', NULL),
(20, 'guzito@gmail.com', '$2y$10$cKTtdaP6sDFS9cjDNZYE.uyG77OF9SUKhxNh1nZ47DllbkGOknPci', 'Guzman', 'Viudez', 'Guzi2', '', '2025-10-30 20:58:29', 'activo', 'cliente', NULL),
(21, 'juano@gmail.com', '$2y$10$KNYzYbrpGEQs0tj/yiTXqO0D7yx6uvzmNtAcD0sL0Uf5qb8XQN6Z.', 'dassad', 'asasd', 'Juano', '', '2025-10-30 21:22:45', 'activo', 'proveedor', NULL),
(23, 'Nahu2@gmail.com', '$2y$10$JbqZZ/jDZBpprEEhCzpFOukX27bd2vcQJRTFkhGBZ3uPKcqiv2.L.', 'Nahuel', 'Fernandez', 'Ferna2', '', '2025-10-30 21:28:23', 'activo', 'cliente', NULL),
(24, 'das@gmail.com', '$2y$10$Rh54t91iah2e1cA0vBf/z.rIUQvmS4x1kbK//SRgLXe0YVkpEfgca', 'dasda', 'aas', 'dasa', '', '2025-10-30 21:30:29', 'activo', 'cliente', NULL),
(25, 'Juansito10@gmail.com', '$2y$10$OH1Dn4ryo8UU.7xKsTALG.nElyXpJVAGn.szXdEwPe25JuLKWR15m', 'dassada', 'adsasd', 'ssaas', '', '2025-10-30 21:34:18', 'activo', 'cliente', NULL),
(26, 'Juansito12@gmail.com', '$2y$10$Io8FbLmqChC5UQsPtT/NEOj/VKX6g.qZLdGD1eWwQwDwPuwRE/PBS', 'dassad', 'Gomez', 'Juano11', '', '2025-10-30 21:37:34', 'activo', 'cliente', NULL),
(27, 'Juansito24@gmail.com', '$2y$10$lDPIdDaUi8Q3arH10f57lO94vc7hpJ/h1TbT/367Z3RNmhz3Z5rUC', 'dassad', 'Gomez', 'Juano20', '', '2025-10-30 21:44:37', 'activo', 'cliente', NULL),
(28, 'Juansito643@gmail.com', '$2y$10$xWN4rPHYB/IS.JYq804Une0fbx/PLErh2Ipf1gVbukZOmG1qryqqm', 'aadsasd', 'asdasd', 'Juano92', '', '2025-10-30 21:46:27', 'activo', 'cliente', NULL);

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
  MODIFY `id_categoria` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

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
  MODIFY `id_mensaje` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT de la tabla `notificacion`
--
ALTER TABLE `notificacion`
  MODIFY `id_notificacion` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT de la tabla `pago`
--
ALTER TABLE `pago`
  MODIFY `id_pago` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT de la tabla `resena`
--
ALTER TABLE `resena`
  MODIFY `id_resena` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT de la tabla `reserva`
--
ALTER TABLE `reserva`
  MODIFY `id_reserva` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT de la tabla `servicio`
--
ALTER TABLE `servicio`
  MODIFY `id_servicio` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT de la tabla `superadmin`
--
ALTER TABLE `superadmin`
  MODIFY `id_superadmin` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `usuario`
--
ALTER TABLE `usuario`
  MODIFY `id_usuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

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
