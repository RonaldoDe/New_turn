-- phpMyAdmin SQL Dump
-- version 4.6.6deb5
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost:3306
-- Tiempo de generación: 22-04-2020 a las 11:11:09
-- Versión del servidor: 5.7.29-0ubuntu0.18.04.1
-- Versión de PHP: 7.2.24-0ubuntu0.18.04.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `new_turn`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `branch_office`
--

CREATE TABLE `branch_office` (
  `id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `nit` int(11) NOT NULL,
  `email` varchar(75) COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `city` varchar(50) COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `longitude` varchar(100) COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `latitude` varchar(199) COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `address` text COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `phone` varchar(12) COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `db_name` varchar(125) COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `close` int(11) NOT NULL,
  `hours_24` int(11) NOT NULL,
  `state_id` int(11) NOT NULL,
  `company_id` int(11) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

--
-- Volcado de datos para la tabla `branch_office`
--

INSERT INTO `branch_office` (`id`, `name`, `description`, `nit`, `email`, `city`, `longitude`, `latitude`, `address`, `phone`, `db_name`, `close`, `hours_24`, `state_id`, `company_id`, `created_at`, `updated_at`) VALUES
(1, 'Master', 'Sucursal para administración master', 123454321, 'admin@gmail.com', 'barranquilla', '111122223', '7776323234', 'cc ll nn', '23132414', 'new_turn', 0, 0, 1, 1, '2020-04-21 21:41:51', '2020-04-22 03:18:39'),
(2, 'La barba 2', 'La barba 49', 123454321, 'labarba@gmail.com', 'barranquilla', '111122223', '7776323234', 'La 49', '23132414', 'la_barba_2_58685', 0, 0, 1, 2, '2020-04-22 03:09:56', '2020-04-22 03:09:56');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `branch_state`
--

CREATE TABLE `branch_state` (
  `id` int(11) NOT NULL,
  `name` varchar(15) COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_520_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

--
-- Volcado de datos para la tabla `branch_state`
--

INSERT INTO `branch_state` (`id`, `name`, `description`) VALUES
(1, 'Activa', 'Estado activa'),
(2, 'Inactiva', 'Estado inactiva');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `branch_user`
--

CREATE TABLE `branch_user` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `branch_id` int(11) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

--
-- Volcado de datos para la tabla `branch_user`
--

INSERT INTO `branch_user` (`id`, `user_id`, `branch_id`, `created_at`, `updated_at`) VALUES
(1, 1, 1, '2020-04-21 22:47:35', '2020-04-21 22:47:35');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `company`
--

CREATE TABLE `company` (
  `id` int(11) NOT NULL,
  `name` varchar(125) COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `nit` varchar(15) COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `email` varchar(125) COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `type_id` int(11) NOT NULL,
  `state_id` int(11) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

--
-- Volcado de datos para la tabla `company`
--

INSERT INTO `company` (`id`, `name`, `description`, `nit`, `email`, `type_id`, `state_id`, `created_at`, `updated_at`) VALUES
(1, 'Master', 'Es la base de datos principal ', '123456789', 'ronaldocamachomeza@hotmail.com', 1, 1, '2020-04-21 21:21:39', '2020-04-21 21:21:39'),
(2, 'BarberShop', 'Es una barbería', '123454321', 'barber@gmail.com', 2, 1, '2020-04-22 02:23:30', '2020-04-22 02:32:50');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `company_state`
--

CREATE TABLE `company_state` (
  `id` int(11) NOT NULL,
  `name` varchar(25) COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_520_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

--
-- Volcado de datos para la tabla `company_state`
--

INSERT INTO `company_state` (`id`, `name`, `description`) VALUES
(1, 'Activa', 'Empresa activa'),
(2, 'Inactiva', 'Empresa inactiva');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `company_type`
--

CREATE TABLE `company_type` (
  `id` int(11) NOT NULL,
  `name` varchar(20) COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_520_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

--
-- Volcado de datos para la tabla `company_type`
--

INSERT INTO `company_type` (`id`, `name`, `description`) VALUES
(1, 'Master', 'La base de datos principal.'),
(2, 'Barbería', 'Empresa de barbería.');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_11_194234_create_user_state_table', 1),
(2, '2014_10_12_000000_create_users_table', 1),
(3, '2014_10_12_100000_create_password_resets_table', 1),
(4, '2016_06_01_000001_create_oauth_auth_codes_table', 1),
(5, '2016_06_01_000002_create_oauth_access_tokens_table', 1),
(6, '2016_06_01_000003_create_oauth_refresh_tokens_table', 1),
(7, '2016_06_01_000004_create_oauth_clients_table', 1),
(8, '2016_06_01_000005_create_oauth_personal_access_clients_table', 1),
(9, '2019_08_19_000000_create_failed_jobs_table', 1),
(10, '2020_02_21_153751_create_role_table', 1),
(11, '2020_02_21_153802_create_permission_table', 1),
(12, '2020_02_21_161059_create_module_table', 1),
(13, '2020_02_21_162003_create_user_has_role_table', 1),
(14, '2020_02_26_164106_create_module_has_module_table', 1),
(15, '2020_02_27_214525_add_module_id_to_permission_table', 1),
(16, '2020_02_27_214820_create_role_has_permission_table', 1),
(17, '2020_03_02_201246_create_state_table', 1),
(18, '2020_03_02_201331_create_banner_table', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `module`
--

CREATE TABLE `module` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `module`
--

INSERT INTO `module` (`id`, `name`, `description`, `created_at`, `updated_at`) VALUES
(1, 'Usuarios', 'Permite administrar los usuarios.', '2020-03-23 23:36:31', '2020-03-23 23:36:31'),
(2, 'Roles', 'Permite administrar los roles.', '2020-03-23 23:36:31', '2020-03-23 23:36:31'),
(3, 'Empresas', 'Administración de empresas', '2020-04-21 05:00:00', '2020-04-21 05:00:00');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `module_has_module`
--

CREATE TABLE `module_has_module` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `parent_module_id` int(10) UNSIGNED NOT NULL,
  `module_id` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `oauth_access_tokens`
--

CREATE TABLE `oauth_access_tokens` (
  `id` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `client_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `scopes` text COLLATE utf8mb4_unicode_ci,
  `revoked` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `expires_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `oauth_access_tokens`
--

INSERT INTO `oauth_access_tokens` (`id`, `user_id`, `client_id`, `name`, `scopes`, `revoked`, `created_at`, `updated_at`, `expires_at`) VALUES
('9a8724865c521d979d2496028cd847f32ab2968c6b4ca0fff9befcfb5dd897755e3093bfe5f3f6c3', 1, 1, NULL, '[\"*\"]', 0, '2020-04-22 07:10:08', '2020-04-22 07:10:08', '2021-04-22 02:10:08');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `oauth_auth_codes`
--

CREATE TABLE `oauth_auth_codes` (
  `id` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `client_id` bigint(20) UNSIGNED NOT NULL,
  `scopes` text COLLATE utf8mb4_unicode_ci,
  `revoked` tinyint(1) NOT NULL,
  `expires_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `oauth_clients`
--

CREATE TABLE `oauth_clients` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `secret` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `redirect` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `personal_access_client` tinyint(1) NOT NULL,
  `password_client` tinyint(1) NOT NULL,
  `revoked` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `oauth_clients`
--

INSERT INTO `oauth_clients` (`id`, `user_id`, `name`, `secret`, `redirect`, `personal_access_client`, `password_client`, `revoked`, `created_at`, `updated_at`) VALUES
(1, NULL, 'Turnos', '18JNxauQmD9pcqr7Uh7iqknZP5VChyw7teT8ZaEW', 'http://localhost', 0, 1, 0, '2020-03-23 23:45:11', '2020-03-23 23:45:11');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `oauth_personal_access_clients`
--

CREATE TABLE `oauth_personal_access_clients` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `client_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `oauth_refresh_tokens`
--

CREATE TABLE `oauth_refresh_tokens` (
  `id` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `access_token_id` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `revoked` tinyint(1) NOT NULL,
  `expires_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `oauth_refresh_tokens`
--

INSERT INTO `oauth_refresh_tokens` (`id`, `access_token_id`, `revoked`, `expires_at`) VALUES
('c5bbcb26d5b4edccfd474f3f430999f2c927ebd8deaa1ad42b7e6afc48872f10c0b8ddfd274dd0f8', '9a8724865c521d979d2496028cd847f32ab2968c6b4ca0fff9befcfb5dd897755e3093bfe5f3f6c3', 0, '2021-04-22 02:10:09'),
('d1db18af0aa6ac70e1dbb24d8f793fd0680f280ea3ccfb67fc380971cabbe47f875b302339c1757d', 'e7290c02d0730a0e814a9f9fe86dce82b5d5279133e1bee05b09e265f404522e433dcb816effa535', 0, '2021-03-23 18:51:07');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `payment_data`
--

CREATE TABLE `payment_data` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `payment_method` int(11) NOT NULL,
  `data` text COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `state` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `payment_method`
--

CREATE TABLE `payment_method` (
  `id` int(11) NOT NULL,
  `name` varchar(50) COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `methods` text COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `state` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

--
-- Volcado de datos para la tabla `payment_method`
--

INSERT INTO `payment_method` (`id`, `name`, `description`, `methods`, `state`) VALUES
(1, 'Tarjeta de credito', 'Pago por medio de tarjetas de crédito', '{ \"VISA\", \"MASTERCARD\", \"AMEX\", \"DINERS\", \"CREDITO FACIL CODENSA\" }', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `permission`
--

CREATE TABLE `permission` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `route` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `module_id` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `permission`
--

INSERT INTO `permission` (`id`, `name`, `description`, `route`, `created_at`, `updated_at`, `module_id`) VALUES
(1, 'Listar usuarios', 'Permite listar usuarios.', '/list_user', '2020-03-23 23:36:36', '2020-03-23 23:36:36', 1),
(2, 'Crear usuarios', 'Permite crear usuarios.', '/create_user', '2020-03-23 23:36:36', '2020-03-23 23:36:36', 1),
(3, 'modificar usuarios', 'Permite actualizar y eliminar usuarios.', '/update_user', '2020-03-23 23:36:36', '2020-03-23 23:36:36', 1),
(4, 'Listar roles', 'Permite listar roles.', '/list_role', '2020-03-23 23:36:36', '2020-03-23 23:36:36', 2),
(5, 'Listar empresas', 'Permite listar las empresas y sucursales', '/list_company', '2020-03-23 23:36:36', '2020-03-23 23:36:36', 3),
(6, 'Crear empresas', 'Puede crear y actualizar empresas y sucursales', '/create_company', '2020-04-21 05:00:00', '2020-04-21 05:00:00', 3);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `role`
--

CREATE TABLE `role` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `role`
--

INSERT INTO `role` (`id`, `name`, `description`, `created_at`, `updated_at`) VALUES
(1, 'Administrador', 'Permite administrar todo el sitio web.', '2020-03-23 23:36:30', '2020-03-23 23:36:30');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `role_has_permission`
--

CREATE TABLE `role_has_permission` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `permission_id` int(10) UNSIGNED NOT NULL,
  `role_id` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `role_has_permission`
--

INSERT INTO `role_has_permission` (`id`, `permission_id`, `role_id`, `created_at`, `updated_at`) VALUES
(1, 1, 1, '2020-03-23 23:36:38', '2020-03-23 23:36:38'),
(2, 2, 1, '2020-03-23 23:36:38', '2020-03-23 23:36:38'),
(3, 3, 1, '2020-03-23 23:36:38', '2020-03-23 23:36:38'),
(4, 4, 1, '2020-03-23 23:36:38', '2020-03-23 23:36:38'),
(5, 5, 1, '2020-03-23 23:36:38', '2020-03-23 23:36:38'),
(6, 5, 1, '2020-04-21 05:00:00', '2020-04-21 05:00:00'),
(7, 6, 1, '2020-04-21 05:00:00', '2020-04-21 05:00:00');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `state`
--

CREATE TABLE `state` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `state`
--

INSERT INTO `state` (`id`, `name`, `description`, `created_at`, `updated_at`) VALUES
(1, 'Activo', 'Estado visible para los usuarios externos.', '2020-03-23 23:36:39', '2020-03-23 23:36:39'),
(2, 'Inactivo', 'Estado que no es visible para los usuarios externos.', '2020-03-23 23:36:39', '2020-03-23 23:36:39');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `transcation_log`
--

CREATE TABLE `transcation_log` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `payment_id` int(11) NOT NULL,
  `branch_id` int(11) NOT NULL,
  `service_id` int(11) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `users`
--

CREATE TABLE `users` (
  `id` int(10) NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `address` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `dni` varchar(15) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `state_id` int(10) UNSIGNED NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `phanton_user` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `users`
--

INSERT INTO `users` (`id`, `name`, `last_name`, `phone`, `address`, `email`, `dni`, `email_verified_at`, `password`, `state_id`, `remember_token`, `created_at`, `updated_at`, `phanton_user`) VALUES
(1, 'Administrator', 'General', '0000000000', 'cll cr nn', ' ronaldocamachomeza@hotmail.com', '1007730321', NULL, '$2y$10$fe.SBIAnguU0UdKx7cn6FOkLAzhkHiC1Raff1bIIv33vn9AA/RjS.', 1, NULL, '2020-03-23 23:36:25', '2020-03-23 23:36:25', 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `user_has_role`
--

CREATE TABLE `user_has_role` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` int(10) NOT NULL,
  `role_id` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `user_has_role`
--

INSERT INTO `user_has_role` (`id`, `user_id`, `role_id`, `created_at`, `updated_at`) VALUES
(1, 1, 1, '2020-03-23 23:36:32', '2020-03-23 23:36:32');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `user_state`
--

CREATE TABLE `user_state` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `user_state`
--

INSERT INTO `user_state` (`id`, `name`, `description`, `created_at`, `updated_at`) VALUES
(1, 'Activo', 'Usuario activo', '2020-03-23 23:36:23', '2020-03-23 23:36:23');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `user_turn`
--

CREATE TABLE `user_turn` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `branch_id` int(11) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `branch_office`
--
ALTER TABLE `branch_office`
  ADD PRIMARY KEY (`id`),
  ADD KEY `state_id` (`state_id`),
  ADD KEY `company_id` (`company_id`);

--
-- Indices de la tabla `branch_state`
--
ALTER TABLE `branch_state`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `branch_user`
--
ALTER TABLE `branch_user`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `branch_id` (`branch_id`);

--
-- Indices de la tabla `company`
--
ALTER TABLE `company`
  ADD PRIMARY KEY (`id`),
  ADD KEY `type_id` (`type_id`),
  ADD KEY `state_id` (`state_id`);

--
-- Indices de la tabla `company_state`
--
ALTER TABLE `company_state`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `company_type`
--
ALTER TABLE `company_type`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `module`
--
ALTER TABLE `module`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `module_has_module`
--
ALTER TABLE `module_has_module`
  ADD PRIMARY KEY (`id`),
  ADD KEY `module_has_module_parent_module_id_foreign` (`parent_module_id`),
  ADD KEY `module_has_module_module_id_foreign` (`module_id`);

--
-- Indices de la tabla `oauth_access_tokens`
--
ALTER TABLE `oauth_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD KEY `oauth_access_tokens_user_id_index` (`user_id`);

--
-- Indices de la tabla `oauth_auth_codes`
--
ALTER TABLE `oauth_auth_codes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `oauth_auth_codes_user_id_index` (`user_id`);

--
-- Indices de la tabla `oauth_clients`
--
ALTER TABLE `oauth_clients`
  ADD PRIMARY KEY (`id`),
  ADD KEY `oauth_clients_user_id_index` (`user_id`);

--
-- Indices de la tabla `oauth_personal_access_clients`
--
ALTER TABLE `oauth_personal_access_clients`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `oauth_refresh_tokens`
--
ALTER TABLE `oauth_refresh_tokens`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `password_resets`
--
ALTER TABLE `password_resets`
  ADD KEY `password_resets_email_index` (`email`);

--
-- Indices de la tabla `payment_data`
--
ALTER TABLE `payment_data`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `payment_method_id` (`payment_method`);

--
-- Indices de la tabla `payment_method`
--
ALTER TABLE `payment_method`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `permission`
--
ALTER TABLE `permission`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `permission_name_unique` (`name`),
  ADD KEY `permission_module_id_foreign` (`module_id`);

--
-- Indices de la tabla `role`
--
ALTER TABLE `role`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `role_has_permission`
--
ALTER TABLE `role_has_permission`
  ADD PRIMARY KEY (`id`),
  ADD KEY `role_has_permission_permission_id_foreign` (`permission_id`),
  ADD KEY `role_has_permission_role_id_foreign` (`role_id`);

--
-- Indices de la tabla `state`
--
ALTER TABLE `state`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `transcation_log`
--
ALTER TABLE `transcation_log`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `payment_id` (`payment_id`),
  ADD KEY `branch_id` (`branch_id`);

--
-- Indices de la tabla `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD KEY `state_id` (`state_id`);

--
-- Indices de la tabla `user_has_role`
--
ALTER TABLE `user_has_role`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_has_role_role_id_foreign` (`role_id`),
  ADD KEY `user_has_role_role_id_foreign1` (`user_id`);

--
-- Indices de la tabla `user_state`
--
ALTER TABLE `user_state`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_state_name_unique` (`name`);

--
-- Indices de la tabla `user_turn`
--
ALTER TABLE `user_turn`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `branch_id` (`branch_id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `branch_office`
--
ALTER TABLE `branch_office`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT de la tabla `branch_state`
--
ALTER TABLE `branch_state`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT de la tabla `branch_user`
--
ALTER TABLE `branch_user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT de la tabla `company`
--
ALTER TABLE `company`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT de la tabla `company_state`
--
ALTER TABLE `company_state`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT de la tabla `company_type`
--
ALTER TABLE `company_type`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT de la tabla `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;
--
-- AUTO_INCREMENT de la tabla `module`
--
ALTER TABLE `module`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT de la tabla `module_has_module`
--
ALTER TABLE `module_has_module`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `oauth_clients`
--
ALTER TABLE `oauth_clients`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT de la tabla `oauth_personal_access_clients`
--
ALTER TABLE `oauth_personal_access_clients`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `payment_data`
--
ALTER TABLE `payment_data`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `payment_method`
--
ALTER TABLE `payment_method`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT de la tabla `permission`
--
ALTER TABLE `permission`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT de la tabla `role`
--
ALTER TABLE `role`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT de la tabla `role_has_permission`
--
ALTER TABLE `role_has_permission`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT de la tabla `state`
--
ALTER TABLE `state`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT de la tabla `transcation_log`
--
ALTER TABLE `transcation_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `users`
--
ALTER TABLE `users`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT de la tabla `user_has_role`
--
ALTER TABLE `user_has_role`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT de la tabla `user_state`
--
ALTER TABLE `user_state`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT de la tabla `user_turn`
--
ALTER TABLE `user_turn`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `branch_office`
--
ALTER TABLE `branch_office`
  ADD CONSTRAINT `branch_office_ibfk_1` FOREIGN KEY (`state_id`) REFERENCES `branch_state` (`id`),
  ADD CONSTRAINT `branch_office_ibfk_2` FOREIGN KEY (`company_id`) REFERENCES `company` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `branch_user`
--
ALTER TABLE `branch_user`
  ADD CONSTRAINT `branch_user_ibfk_1` FOREIGN KEY (`branch_id`) REFERENCES `branch_office` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `branch_user_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `company`
--
ALTER TABLE `company`
  ADD CONSTRAINT `company_ibfk_1` FOREIGN KEY (`type_id`) REFERENCES `company_type` (`id`),
  ADD CONSTRAINT `company_ibfk_2` FOREIGN KEY (`state_id`) REFERENCES `company_state` (`id`);

--
-- Filtros para la tabla `module_has_module`
--
ALTER TABLE `module_has_module`
  ADD CONSTRAINT `module_has_module_module_id_foreign` FOREIGN KEY (`module_id`) REFERENCES `module` (`id`),
  ADD CONSTRAINT `module_has_module_parent_module_id_foreign` FOREIGN KEY (`parent_module_id`) REFERENCES `module` (`id`);

--
-- Filtros para la tabla `payment_data`
--
ALTER TABLE `payment_data`
  ADD CONSTRAINT `payment_data_ibfk_1` FOREIGN KEY (`payment_method`) REFERENCES `payment_method` (`id`),
  ADD CONSTRAINT `payment_data_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `permission`
--
ALTER TABLE `permission`
  ADD CONSTRAINT `permission_module_id_foreign` FOREIGN KEY (`module_id`) REFERENCES `module` (`id`);

--
-- Filtros para la tabla `role_has_permission`
--
ALTER TABLE `role_has_permission`
  ADD CONSTRAINT `role_has_permission_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permission` (`id`),
  ADD CONSTRAINT `role_has_permission_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `role` (`id`);

--
-- Filtros para la tabla `transcation_log`
--
ALTER TABLE `transcation_log`
  ADD CONSTRAINT `transcation_log_ibfk_1` FOREIGN KEY (`payment_id`) REFERENCES `payment_data` (`id`),
  ADD CONSTRAINT `transcation_log_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `transcation_log_ibfk_3` FOREIGN KEY (`branch_id`) REFERENCES `branch_office` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`state_id`) REFERENCES `user_state` (`id`);

--
-- Filtros para la tabla `user_has_role`
--
ALTER TABLE `user_has_role`
  ADD CONSTRAINT `user_has_role_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `role` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `user_has_role_role_id_foreign1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `user_turn`
--
ALTER TABLE `user_turn`
  ADD CONSTRAINT `user_turn_ibfk_1` FOREIGN KEY (`branch_id`) REFERENCES `branch_office` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `user_turn_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
