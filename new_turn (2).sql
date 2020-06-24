-- phpMyAdmin SQL Dump
-- version 4.6.6deb5
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost:3306
-- Tiempo de generación: 23-06-2020 a las 21:48:03
-- Versión del servidor: 5.7.30-0ubuntu0.18.04.1
-- Versión de PHP: 7.2.31-1+ubuntu18.04.1+deb.sury.org+1

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
(3, 'Grooming branch', 'Es una sucursal de aseo', 456132, 'BranchGrooming@mail.com', 'Bquilla', '15025405', '32456013254', 'Calle 245', '11554422', 'db_grooming_branch_23308', 0, 0, 1, 4, '2020-05-06 23:10:13', '2020-05-06 23:10:13'),
(8, 'Barber', 'Es una sucursal de barbería', 456132, 'vip@mail.com', 'Bquilla', '15025405', '32456013254', 'Calle 245', '11554422', 'db_barber_4602', 0, 0, 1, 5, '2020-05-12 22:51:27', '2020-05-12 22:51:27');

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
(1, 1, 1, '2020-04-21 22:47:35', '2020-04-21 22:47:35'),
(3, 10, 3, '2020-05-06 00:00:00', '2020-05-06 00:00:00'),
(20, 28, 8, '2020-05-12 00:00:00', '2020-05-12 00:00:00'),
(21, 29, 8, '2020-05-12 00:00:00', '2020-05-12 00:00:00'),
(22, 30, 8, '2020-05-12 00:00:00', '2020-05-12 00:00:00'),
(23, 31, 8, '2020-05-12 00:00:00', '2020-05-12 00:00:00'),
(24, 11, 3, '2020-05-13 19:08:02', '2020-05-13 19:08:02'),
(25, 32, 3, '2020-05-14 23:53:42', '2020-05-14 23:53:42');

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
(4, 'Grooming', 'Es una empresa de aseo', '12354', 'grooming@gmail.com', 3, 1, '2020-05-06 23:10:08', '2020-05-06 23:10:08'),
(5, 'BarberVip', 'Una barbería', '1236542', 'barber@gmail.com', 2, 1, '2020-05-12 22:35:55', '2020-05-12 22:35:55');

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
(2, 'Barbería', 'Empresa de barbería.'),
(3, 'Aseo', 'Empresa de aseo');

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
('0427a405301f5abd4d5d546cf2b95ef732c0d154e8d79b4ed231fbb9d4e71c14efab87a8039506e1', 28, 1, NULL, '[\"*\"]', 0, '2020-05-29 22:27:45', '2020-05-29 22:27:45', '2021-05-29 17:27:45'),
('1348c70ba6e9ad923c909e2ea822fbf28e6c781cfcfbbfaa3fdd9ff987742aa596e2494d571a448b', 64, 1, NULL, '[\"*\"]', 0, '2020-04-30 06:14:12', '2020-04-30 06:14:12', '2021-04-30 01:14:12'),
('1c8b3415200f747c8ee802cb795510c48c7e222620d8dde1a2daf883087d0688e71265fa7cc8fe39', 1, 1, NULL, '[\"*\"]', 0, '2020-05-14 03:07:37', '2020-05-14 03:07:37', '2021-05-13 22:07:37'),
('301b6c6bc2777b131cbeb4f248b7740a428dbab6e6c6c351391164809526732a0d21fc5ad082eeb3', 29, 1, NULL, '[\"*\"]', 0, '2020-05-14 00:12:22', '2020-05-14 00:12:22', '2021-05-13 19:12:22'),
('3774480f3f8fe7a25637242fdd8009d54d77829272dd56602b5a8f671b73344190cfc21425693dac', 72, 1, NULL, '[\"*\"]', 0, '2020-05-01 22:34:00', '2020-05-01 22:34:00', '2021-05-01 17:34:00'),
('bd0c4bc0295405e51720757a681767457a2ecc8748a3cf0778d04b9dc65c133aefc77878f035771c', 63, 1, NULL, '[\"*\"]', 0, '2020-05-05 02:49:30', '2020-05-05 02:49:30', '2021-05-04 21:49:30'),
('d62841fa195dd06202f5e8c4c9223685f8fbf8a7e900701bea31bb449dc12f12ba740181984b825b', 11, 1, NULL, '[\"*\"]', 0, '2020-05-14 00:06:31', '2020-05-14 00:06:31', '2021-05-13 19:06:31'),
('e0236689ed786e573f2243c60cad59669732bf579ed7d394970ef4d86d919b63e467a5762717b3d5', 10, 1, NULL, '[\"*\"]', 0, '2020-06-12 02:41:26', '2020-06-12 02:41:26', '2021-06-11 21:41:26'),
('e4b63b8e84fcba9119bd533606fef2aec798daaa66f729d01a857775c5a151512ede83adcf30fe2b', 67, 1, NULL, '[\"*\"]', 0, '2020-05-01 22:31:08', '2020-05-01 22:31:08', '2021-05-01 17:31:08');

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
('106f751696e87647dd1b2fce32ced1e319b68605fe2d963f7c9d0f37570303a4e0db9f447dded430', '18629928d1643bfdbeaf732b26877177cdcbbd9cb9c96caea9d90ce941333cedb9b1837b90ebdc60', 0, '2021-05-01 18:53:46'),
('18faffcb0d84bb4d52c3522bce925a4e64a386c5f9a61cd5ad49ee4cff91039c902cbbdcaa1af32f', '2bf4498256a89b76cb3b44a5c0639d938e67a0f16efd37a4d088f7daa3b9a8145bcbdab627fadc66', 0, '2021-05-12 22:55:09'),
('1e70e4f2f9607078a88422572c3246dc261049504fe30ebf63d973b3a76a7604196bb7bb47d3105a', '14282e6b8e5a061263e2681048e637042eb1ca5923fb889045628e69a910c0cccfb264e379dbb6cd', 0, '2021-06-09 20:56:00'),
('22d26375ee6f62f9993a29c9665ead3bdcb3f171af9f314f6f644e5f9e20edd78545afda04bb8010', '301b6c6bc2777b131cbeb4f248b7740a428dbab6e6c6c351391164809526732a0d21fc5ad082eeb3', 0, '2021-05-13 19:12:22'),
('241f632962e3093bc0d471edee2c4600063fe52d0a2841868a6c367ee9e5b335012c6bdcb0d1fd4f', '40f7c3aed14ccdc6a21a8fd2fc83ac88cce30b677da0a1c4f48e8615f1ddeec569c7ad39fcd14125', 0, '2021-05-14 23:24:14'),
('2b33d4d76a9a9bcec1d4be762ed9677e475fda9f1a6aef16f4d880890c8fc3894e7f93eb6c8518c8', '51e43daca8e16e850fe58720ee165e825d33b421c2270ce55d24f033347f6636c896b64620afe77f', 0, '2021-06-04 20:33:35'),
('2c25611f06562e5ed60f9c1de9b00daf3c72878fee29d1fbcbbcdacb8c2f5b411049917ec90f9adf', '44ac529e30eb1db1fd06f8125d52f9a9a4a33a0f562eaf8db9513528949c09a2c9ec5793b5db7dc4', 0, '2021-05-15 19:35:38'),
('311e16cc1943454fc1b6fd45d1d6e42bb28676a143b2128e904bac9b86a9e77eaa520ac8c98dc25d', '58825e3c6bc41efa5a6eb388e16a4eb15ab5a33380616d5dfab03c772ec8151644f721747bf11b38', 0, '2021-06-09 18:07:21'),
('3622a851885a6d06c7344224e6190fefa8af9517b6ad3c8466b85bcce9582c721025bc4c27bfac2a', 'e0236689ed786e573f2243c60cad59669732bf579ed7d394970ef4d86d919b63e467a5762717b3d5', 0, '2021-06-11 21:41:26'),
('382fd72845304b2f09af50efceaaf644b9178770b016c2e67c94b3a34727122a2de63fdfde3499bd', '184e3ba64295899bfe415fc0dc7c7d8ebcfa276dec04ff2a1b9f4934f8eed2ade33e9304a08ea4c6', 0, '2021-05-12 22:34:31'),
('3d580af20d980e599d782b4a71d6c864cd47def8f6d5c317069a8fc0892566f8a5ec3387aa5e7b83', 'bd0c4bc0295405e51720757a681767457a2ecc8748a3cf0778d04b9dc65c133aefc77878f035771c', 0, '2021-05-04 21:49:31'),
('3fa5988d544939d128459febc2e11eae2ceb79681aaeed5a29e89d5cdb99b22ac62ca34445f8df50', '741197f2299a175a86154c85d78e99427b9fc6848e0b7f407b928882f0ccdb00f0c1602fe5f029a1', 0, '2021-05-13 18:06:33'),
('41b0596d73dfc9413eb4d19099fb2627e4784382e945c00b42f5f2d0b56c834459967fffac51a85f', 'fb7126618edf227c0bdd6703c18d75907380884f08eb0895ff36274f76b70913d01919940393b079', 0, '2021-06-03 21:50:05'),
('484e52b7b46013923919c300c5926e4c74e83358247b7d74873f893e3d823542d55f0ecc8f38268d', '967257159d2816285e2d2e586ece2ebebbc4f4a62966d66ff75016c07eb270b8b6c60496d58cd7c4', 0, '2021-06-05 17:18:42'),
('507ae6d8f34ac76d19486dabde2389af8f313c82d470f4ca48f566d8e24332246ff7fa25653671f1', 'a6758beb8ca73b5b0851c7de2892442eab7e4fbb711f1539b1dccd129709942550ea751c2cbc9628', 0, '2021-05-06 22:56:34'),
('5b90a3e1bbb29fa1d7f7062e9a6f9f965118fd69ce4b6509e4a2954ad68e7133088e58029fd5dca9', 'd967cdc917774a84313c7a363d469623b86502b950cbc549c1e90dff1ca5be614b5fa726ca15a33a', 0, '2021-04-27 04:11:13'),
('7472fb31ec9ed8642a3b40d9316e18798f8553085154451cee60c5747d36f7ff140e024f97928685', '533d5b9c44230cc39a8d7d7918d25dd554b075ec2c0522cf945764120c29f6dd4c83273f4d074e21', 0, '2021-05-13 19:02:36'),
('74a0caead557d17d784f469977968f624bcdf98a4811c508cb1b48fc2c0debbc43864ba055adfa74', 'ef358486df1952c0054dd23b8e4cd4621da7a3b5c99c8d6f1079f09a4f9fba36b6e41d6776ceb13a', 0, '2021-04-27 04:18:55'),
('76995d9741fc90c6f97bacaef1732eff810fd6d97df0d10d183ca7299a6cd846077f1be222400a0c', 'd62841fa195dd06202f5e8c4c9223685f8fbf8a7e900701bea31bb449dc12f12ba740181984b825b', 0, '2021-05-13 19:06:31'),
('7aa4e670978e8e56add744750d7062b5bf4ce0d912216389dcc6da8745f9acc100d3a0d2a46947d3', '3774480f3f8fe7a25637242fdd8009d54d77829272dd56602b5a8f671b73344190cfc21425693dac', 0, '2021-05-01 17:34:00'),
('949d06cf5d8cd6723b30b63c324dbf2685ef6f05a7da1e075e0e941d592360ba0727868486be677c', '3e88a217282481dfad22909d1466f0bb11e2ce378c7cefff4221f200b329aa58795da1c7bf4ede00', 0, '2021-05-08 22:42:00'),
('9e4303bb8cd425bc6f852b4d51078de61194477d7507cd82074e305a4b82c2bc7e2414610fa29902', 'e4b63b8e84fcba9119bd533606fef2aec798daaa66f729d01a857775c5a151512ede83adcf30fe2b', 0, '2021-05-01 17:31:08'),
('a007acdfe3c8405010b634bb378c4632c17354dbdc025d8d64ff673e032186722eb45a3a901477d6', '21f099212593c2099c3365d13d614e07d023e1229e61f8bb9d4d7fd041c9bb0a2fe5edb371f05ca3', 0, '2021-05-02 22:23:59'),
('ad5b6c48beb85a937a8a875b520705cddb555f751bac87cabafbdb56c2c8300e8ba817035ea9512e', 'bdbf25a7c53b2d50f1fde910f970e7faefaf8ec1f7d0b5faf1a2eac7b5fca2856cfbe0b7cbf2f120', 0, '2021-05-07 00:15:15'),
('b6b62b64a74224f858d931ff71781c92640854cbab0f7a79763697df52fa543c4656d21c7919f3ce', '1c8b3415200f747c8ee802cb795510c48c7e222620d8dde1a2daf883087d0688e71265fa7cc8fe39', 0, '2021-05-13 22:07:37'),
('b88e592087981fb8e42bf3505c01d744ce139b04a2b334b1e11cb75b431b6bc672026c1c4f0446d3', 'c9e30696d01369cfd74f351bcc47e6af30e5ecfe4196917930366fa8b3e4e0755ecdae025c0d9743', 0, '2021-04-22 16:56:45'),
('c5bbcb26d5b4edccfd474f3f430999f2c927ebd8deaa1ad42b7e6afc48872f10c0b8ddfd274dd0f8', '9a8724865c521d979d2496028cd847f32ab2968c6b4ca0fff9befcfb5dd897755e3093bfe5f3f6c3', 0, '2021-04-22 02:10:09'),
('c6ac783d4fe1712700f316383fb82876761b646f6f4ea4c9051acfdcf7c1fed278342f3ca7e4e8a3', '1348c70ba6e9ad923c909e2ea822fbf28e6c781cfcfbbfaa3fdd9ff987742aa596e2494d571a448b', 0, '2021-04-30 01:14:12'),
('cf7d46780e546de2b2786641797035d5136d4cfae5a387f4414b3f34f57851381145a46ca743f00b', '0427a405301f5abd4d5d546cf2b95ef732c0d154e8d79b4ed231fbb9d4e71c14efab87a8039506e1', 0, '2021-05-29 17:27:45'),
('d1db18af0aa6ac70e1dbb24d8f793fd0680f280ea3ccfb67fc380971cabbe47f875b302339c1757d', 'e7290c02d0730a0e814a9f9fe86dce82b5d5279133e1bee05b09e265f404522e433dcb816effa535', 0, '2021-03-23 18:51:07'),
('d5fbfa224871db04c90eaa27e5e45e6e6a46cdc9346c64db836bba128275a6406259efa8abf95119', '0948b934d58e435544fe4790ca2d635a536e57fbf61eec6cbaa12c1ddb610baf12d3a3dc2d807cf1', 0, '2021-05-13 19:05:23'),
('de197d1ebbc8896dfb35cf40c6bb867bfe35171cf3005b2fd783be533e51d6f1eac541ebfef9aa74', '19e9397312dbb8987651b7bf2770074590005ae86d9b6e9810591c3812652fb82849117c2269537a', 0, '2021-05-01 13:22:23'),
('e9fdc6775f9e5264d760db336f3339c6f95b8a578bb13fd2ef981879a94383542928cf2265564f6a', 'f1c4221f8c740d95c652ffeded4e5559ee8f1346750ddca0022bca948d537444bf9bf1bcad892503', 0, '2021-06-02 23:10:21');

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
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `state` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

--
-- Volcado de datos para la tabla `payment_data`
--

INSERT INTO `payment_data` (`id`, `user_id`, `payment_method`, `data`, `created_at`, `updated_at`, `state`) VALUES
(1, 10, 1, '{ \"full_name\": \"Ronaldo camacho meza\", \"email\": \"ronaldocamachomeza@hotmail.com\", \"phone\": \"3045335043\", \"dni\": \"1007730321\", \"address_1\": \"Calle 115a #13-24\", \"address_2\": \"Los angeles 3\", \"city\": \"Barranquilla\", \"state\": \"Atlantico\", \"postal_code\": \"080015\", \"payment_method\": \"MASTERCARD\" }', '2020-05-18 21:12:24', '2020-05-18 21:53:34', 1),
(2, 10, 1, '{\"full_name\":\"Ronaldo camacho meza\",\"email\":\"ronaldocamachomeza@hotmail.com\",\"phone\":\"3045335043\",\"dni\":\"1007730321\",\"address_1\":\"Calle 115a #13-24\",\"address_2\":\"Los angeles 3\",\"city\":\"Barranquilla\",\"state\":\"Atlantico\",\"postal_code\":\"080015\",\"payment_method\":\"MASTERCARD\"}', '2020-05-18 21:53:38', '2020-05-18 21:53:38', 1);

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
(1, 'Listar usuarios', 'Permite listar usuarios.', '/m_list_user', '2020-03-23 23:36:36', '2020-03-23 23:36:36', 1),
(2, 'Crear usuarios', 'Permite crear usuarios.', '/m_create_user', '2020-03-23 23:36:36', '2020-03-23 23:36:36', 1),
(3, 'modificar usuarios', 'Permite actualizar y eliminar usuarios.', '/m_update_user', '2020-03-23 23:36:36', '2020-03-23 23:36:36', 1),
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
  `updated_at` timestamp NULL DEFAULT NULL,
  `state` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `role`
--

INSERT INTO `role` (`id`, `name`, `description`, `created_at`, `updated_at`, `state`) VALUES
(1, 'Administrador', 'Permite administrar todo el sitio web.', '2020-03-23 23:36:30', '2020-03-23 23:36:30', 1);

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
  `action_id` enum('Barber','Grooming') COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

--
-- Volcado de datos para la tabla `transcation_log`
--

INSERT INTO `transcation_log` (`id`, `user_id`, `payment_id`, `branch_id`, `service_id`, `action_id`, `created_at`, `updated_at`) VALUES
(2, 10, 1, 8, 1, 'Barber', '2020-05-18 21:31:58', '2020-05-18 21:31:58'),
(3, 10, 1, 3, 1, 'Grooming', '2020-06-09 18:14:26', '2020-06-09 18:14:26'),
(4, 10, 1, 3, 1, 'Grooming', '2020-06-09 18:15:13', '2020-06-09 18:15:13');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `users`
--

CREATE TABLE `users` (
  `id` int(10) NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `dni` varchar(15) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `state_id` int(10) UNSIGNED NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `password_verify` int(11) NOT NULL,
  `password_code` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email_verify` int(11) NOT NULL,
  `email_code` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `phanton_user` int(11) NOT NULL,
  `user_type` enum('facebook_login','google_login','registered') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'registered'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `users`
--

INSERT INTO `users` (`id`, `name`, `last_name`, `phone`, `address`, `email`, `dni`, `email_verified_at`, `password`, `state_id`, `remember_token`, `password_verify`, `password_code`, `email_verify`, `email_code`, `created_at`, `updated_at`, `phanton_user`, `user_type`) VALUES
(1, 'Administrator', 'General', '0000000000', 'cll cr nn', 'ronaldocamachomeza@hotmail.com', '1007730321', NULL, '$2y$10$fe.SBIAnguU0UdKx7cn6FOkLAzhkHiC1Raff1bIIv33vn9AA/RjS.', 1, NULL, 0, '7276', 0, NULL, '2020-03-23 23:36:25', '2020-06-12 03:22:46', 0, 'registered'),
(8, 'Ronaldo', 'Camac', '321564215', 'cc ll nn', 'ronal@hotmail.com', '123456789', NULL, '$2y$10$pcwWlAiyjnV0inCHZ5TOGeLXTrnsZTWNVdQYv0tEZwhtwvJt.FWMe', 1, NULL, 0, NULL, 0, NULL, '2020-04-23 01:57:47', '2020-04-23 03:48:12', 0, 'registered'),
(10, 'Grooming branch', 'Grooming branch', '11554422', 'Calle 245', 'BranchGrooming@mail.com', '456132', NULL, '$2y$10$5Jdf34fadPt565pLCxuzyOFg9vGcXeJoJt/H04I6TQGvYvf1c5gxu', 1, NULL, 0, NULL, 0, NULL, '2020-05-07 04:10:13', '2020-05-07 04:10:13', 0, 'registered'),
(11, 'Luis angel', 'Camacho meza', '154848', 'Calle 54566', 'luis@gmail.com', '11225544', NULL, '$2y$10$fe.SBIAnguU0UdKx7cn6FOkLAzhkHiC1Raff1bIIv33vn9AA/RjS.', 1, NULL, 0, NULL, 0, NULL, '2020-05-07 05:00:00', '2020-05-07 05:00:00', 0, 'registered'),
(28, 'Barber', 'Barber', '11554422', 'Calle 245', 'vip@mail.com', '456132', NULL, '$2y$10$vF5jMNWjhNivEfbN7ibTCuWvu9chqJtPMaWG1khhQHQ311SHoLTVK', 1, NULL, 0, NULL, 0, NULL, '2020-05-13 03:51:27', '2020-05-13 03:51:27', 0, 'registered'),
(29, 'First entry', '-', '11554422', 'Calle 245', '1-vip@mail.com', '456132', NULL, '$2y$10$J60xka8EziKOejQJ9kk94ehRYBeIk4Mhmj1TVzzDhfghDYiOWG3BO', 1, NULL, 0, NULL, 0, NULL, '2020-05-13 03:51:27', '2020-05-13 03:51:27', 1, 'registered'),
(30, 'Second entry', '-', '11554422', 'Calle 245', '2-vip@mail.com', '456132', NULL, '$2y$10$42EVmJrLPAU4LNPysTI0u.8pyEJ4qFY7mC4c/i3yEJpH/NcEWNM1q', 1, NULL, 0, NULL, 0, NULL, '2020-05-13 03:51:27', '2020-05-13 03:51:27', 1, 'registered'),
(31, 'Third entry', '-', '11554422', 'Calle 245', '3-vip@mail.com', '456132', NULL, '$2y$10$W7AJ8NmsmEvbMRHmmmO7POm6hlKwn2eODAWjZ9rFvoYtGeK32wFS6', 1, NULL, 0, NULL, 0, NULL, '2020-05-13 03:51:27', '2020-05-13 03:51:27', 1, 'facebook_login'),
(32, 'Ronal2', 'Camacho', '112233', 'CCLL', 'ronal@mail.com', '1234321', NULL, '$2y$10$qH1SHNPZ64HdMO6PbxPU/e/b55FTbAJ84O3JTmj/AXdTx3Ve5K4Dq', 1, NULL, 0, NULL, 0, NULL, '2020-05-15 04:53:42', '2020-05-15 04:55:26', 0, 'facebook_login'),
(33, 'Ronaldo', 'Camacho', '123456', 'Calle', 'ronaldo@mail.com', '1122334455', NULL, '$2y$10$WscyrhEb6cB9KNgMfhHxkOi9S5fmuF3Yle1f4pEkzC66RkGZ53Btq', 1, NULL, 0, NULL, 0, NULL, '2020-05-28 02:29:09', '2020-05-28 02:29:09', 0, 'registered');

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
(1, 'Activo', 'Usuario activo', '2020-03-23 23:36:23', '2020-03-23 23:36:23'),
(2, 'Inactivo', 'Usuario inactivo', '2020-04-22 05:00:00', '2020-04-22 05:00:00');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `user_turn`
--

CREATE TABLE `user_turn` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `branch_id` int(11) NOT NULL,
  `service_type` enum('barber_turn','grooming_contract') COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `state` int(11) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

--
-- Volcado de datos para la tabla `user_turn`
--

INSERT INTO `user_turn` (`id`, `user_id`, `branch_id`, `service_type`, `state`, `created_at`) VALUES
(1, 8, 3, 'grooming_contract', 0, '2020-05-07 00:25:01'),
(85, 10, 3, 'grooming_contract', 0, '2020-05-09 16:58:21'),
(88, 10, 3, 'grooming_contract', 0, '2020-05-09 20:08:28'),
(189, 10, 3, 'grooming_contract', 0, '2020-05-09 23:38:06'),
(266, 10, 8, 'barber_turn', 1, '2020-05-15 19:34:47'),
(267, 10, 8, 'barber_turn', 0, '2020-05-15 19:35:49'),
(271, 10, 3, 'grooming_contract', 1, '2020-06-02 23:04:29'),
(275, 10, 3, 'grooming_contract', 1, '2020-06-09 18:09:02'),
(276, 10, 3, 'grooming_contract', 1, '2020-06-09 18:09:36'),
(279, 10, 3, 'grooming_contract', 1, '2020-06-09 18:14:26'),
(282, 10, 3, 'grooming_contract', 1, '2020-06-09 18:15:13'),
(284, 10, 8, 'barber_turn', 1, '2020-06-10 18:44:58'),
(285, 10, 8, 'barber_turn', 1, '2020-06-10 18:45:12');

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
  ADD KEY `branch_id` (`branch_id`),
  ADD KEY `action_id` (`action_id`);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
--
-- AUTO_INCREMENT de la tabla `branch_state`
--
ALTER TABLE `branch_state`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT de la tabla `branch_user`
--
ALTER TABLE `branch_user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;
--
-- AUTO_INCREMENT de la tabla `company`
--
ALTER TABLE `company`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT de la tabla `company_state`
--
ALTER TABLE `company_state`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT de la tabla `company_type`
--
ALTER TABLE `company_type`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT de la tabla `users`
--
ALTER TABLE `users`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;
--
-- AUTO_INCREMENT de la tabla `user_has_role`
--
ALTER TABLE `user_has_role`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT de la tabla `user_state`
--
ALTER TABLE `user_state`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT de la tabla `user_turn`
--
ALTER TABLE `user_turn`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=286;
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
