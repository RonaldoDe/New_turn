<?php

namespace App\Http\Controllers\Helper;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class NewDatabaseHelper extends Controller
{
    public static function newBarberShopDatabase($db_name, $data)
    {
        $db_company = DB::statement("CREATE DATABASE $db_name");
        if($db_company){

            $configDb = [
                'driver'      => 'mysql',
                'host'        => env('DB_HOST', '127.0.0.1'),
                'port'        => env('DB_PORT', '3306'),
                'database'    => $db_name,
                'username'    => env('DB_USERNAME', 'forge'),
                'password'    => env('DB_PASSWORD', ''),
                'unix_socket' => env('DB_SOCKET', ''),
                'charset'     => 'utf8',
                'collation'   => 'utf8_unicode_ci',
                'prefix'      => '',
                'strict'      => true,
                'engine'      => null,
            ];

            \Config::set('database.connections.newCompany', $configDb);
            try{
                //Estructura de tabla para la tabla `client_turn
                $structure = DB::connection('newCompany')->statement("CREATE TABLE `client_turn` (
                    `id` int(11) NOT NULL,
                    `employee_id` int(11) DEFAULT NULL,
                    `user_id` int(11) NOT NULL,
                    `user_turn_id` int(11) DEFAULT NULL,
                    `dni` varchar(15) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
                    `date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
                    `start_at` datetime DEFAULT NULL,
                    `started_by` int(11)  DEFAULT NULL,
                    `finished_at` datetime DEFAULT NULL,
                    `finished_by_id` int(11) DEFAULT NULL,
                    `service_id` int(11) NOT NULL,
                    `today` date NOT NULL,
                    `turn_number` int(11) NOT NULL,
                    `c_return` int(11) NOT NULL,
                    `paid_out` tinyint(1) NOT NULL DEFAULT '0',
                    `state_id` int(11) NOT NULL
                ) ENGINE=InnoDB DEFAULT CHARSET=latin1;");

                //Estructura de tabla para la tabla `data_company
                $structure = DB::connection('newCompany')->statement("CREATE TABLE `company_data` (
                    `id` int(11) NOT NULL,
                    `turns_number` int(11) NOT NULL,
                    `min_turns` int(11) DEFAULT NULL,
                    `current_return` int(11) NOT NULL,
                    `company_id` int(11) NOT NULL,
                    `api_k` varchar(50) DEFAULT NULL,
                    `api_l` varchar(50) DEFAULT NULL,
                    `mer_id` varchar(20) DEFAULT NULL,
                    `acc_id` varchar(20) DEFAULT NULL,
                    `pay_on_line` tinyint(1) NOT NULL DEFAULT '0',
                    `created_at` timestamp NULL DEFAULT NULL,
                    `updated_at` timestamp NULL DEFAULT NULL
                ) ENGINE=InnoDB DEFAULT CHARSET=latin1;");

                //Estructura de tabla para la tabla `module
                $structure = DB::connection('newCompany')->statement("CREATE TABLE `module` (
                    `id` int(10) NOT NULL,
                    `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
                    `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
                    `created_at` timestamp NULL DEFAULT NULL,
                    `updated_at` timestamp NULL DEFAULT NULL
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;");

                // Volcado de datos para la tabla `module`
                $structure = DB::connection('newCompany')->statement("INSERT INTO `module` (`id`, `name`, `description`, `created_at`, `updated_at`) VALUES
                (1, 'Usuarios', 'Permite administrar los usuarios.', '2020-03-23 23:36:31', '2020-03-23 23:36:31'),
                (2, 'Roles', 'Permite administrar los roles.', '2020-03-23 23:36:31', '2020-03-23 23:36:31'),
                (3, 'Sucursal', 'Administración de la sucursal', '2020-04-24 05:00:00', '2020-04-24 05:00:00'),
                (4, 'Turnos', 'Administración de turnos', '2020-04-24 05:00:00', '2020-04-24 05:00:00'),
                (5, 'Servicios', 'Administración de servicios.', '2020-04-24 05:00:00', '2020-04-24 05:00:00');");

                //Estructura de tabla para la tabla `permission
                $structure = DB::connection('newCompany')->statement("CREATE TABLE `permission` (
                    `id` int(10) UNSIGNED NOT NULL,
                    `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
                    `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
                    `route` text COLLATE utf8mb4_unicode_ci NOT NULL,
                    `created_at` timestamp NULL DEFAULT NULL,
                    `updated_at` timestamp NULL DEFAULT NULL,
                    `module_id` int(10) NOT NULL
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;");

                // Volcado de datos para la tabla `permission`
                $structure = DB::connection('newCompany')->statement("INSERT INTO `permission` (`id`, `name`, `description`, `route`, `created_at`, `updated_at`, `module_id`) VALUES
                (1, 'Listar usuarios', 'Permite listar usuarios.', '/list_user', '2020-03-23 23:36:36', '2020-03-23 23:36:36', 1),
                (2, 'Crear usuarios', 'Permite crear usuarios.', '/create_user', '2020-03-23 23:36:36', '2020-03-23 23:36:36', 1),
                (3, 'modificar usuarios', 'Permite actualizar y eliminar usuarios.', '/update_user', '2020-03-23 23:36:36', '2020-03-23 23:36:36', 1),
                (4, 'Listar roles', 'Permite listar roles.', '/list_role', '2020-03-23 23:36:36', '2020-03-23 23:36:36', 2),
                (5, 'Datos de la sucursal', 'Ver información de la sucursal', '/show_branch', '2020-04-24 05:00:00', '2020-04-24 05:00:00', 3),
                (6, 'Editar datos de la sucursal', 'Puede editar datos de la sucursal.', '/update_branch', '2020-04-24 05:00:00', '2020-04-24 05:00:00', 3),
                (7, 'Listar turnos', 'Puede listar la información de los turnos.', '/list_turns', '2020-04-24 05:00:00', '2020-04-24 05:00:00', 4),
                (8, 'Iniciar turno', 'Empezar un turno.', '/start_turn', '2020-04-24 05:00:00', '2020-04-24 05:00:00', 4),
                (9, 'Listar configuración de los servicios', 'Lista la configuración de los servicios que ofrece la empresa.', '/list_c_service', '2020-05-08 05:00:00', '2020-05-08 05:00:00', 5),
                (10, 'Crear y modificar la configuración de los servicios', 'Crear y modificar la configuración de los servicios que ofrece la empresa.', '/create_c_service', '2020-05-08 05:00:00', '2020-05-08 05:00:00', 5);");

                //Estructura de tabla para la tabla `role`
                $structure = DB::connection('newCompany')->statement("CREATE TABLE `role` (
                    `id` int(10) UNSIGNED NOT NULL,
                    `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
                    `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
                    `created_at` timestamp NULL DEFAULT NULL,
                    `updated_at` timestamp NULL DEFAULT NULL,
                    `state` int(11) NOT NULL
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;");

                // Volcado de datos para la tabla `role`
                $structure = DB::connection('newCompany')->statement("INSERT INTO `role` (`id`, `name`, `description`, `created_at`, `updated_at`, `state`) VALUES
                (1, 'Administrador', 'Permite administrar todo el sitio web.', '2020-03-23 23:36:30', '2020-03-23 23:36:30', 1),
                (2, 'Barbero', 'Es un barbero', '2020-05-04 05:00:00', '2020-05-04 05:00:00', 1);");

                //Estructura de tabla para la tabla `role_has_permission
                $structure = DB::connection('newCompany')->statement("CREATE TABLE `role_has_permission` (
                    `id` bigint(20) UNSIGNED NOT NULL,
                    `permission_id` int(10) UNSIGNED NOT NULL,
                    `role_id` int(10) UNSIGNED NOT NULL,
                    `created_at` timestamp NULL DEFAULT NULL,
                    `updated_at` timestamp NULL DEFAULT NULL
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;");

                // Volcado de datos para la tabla `role_has_permission`
                $structure = DB::connection('newCompany')->statement("INSERT INTO `role_has_permission` (`id`, `permission_id`, `role_id`, `created_at`, `updated_at`) VALUES
                (1, 1, 1, '2020-03-23 23:36:38', '2020-03-23 23:36:38'),
                (2, 2, 1, '2020-03-23 23:36:38', '2020-03-23 23:36:38'),
                (3, 3, 1, '2020-03-23 23:36:38', '2020-03-23 23:36:38'),
                (4, 4, 1, '2020-03-23 23:36:38', '2020-03-23 23:36:38'),
                (5, 5, 1, '2020-04-24 05:00:00', '2020-04-24 05:00:00'),
                (6, 6, 1, '2020-04-24 05:00:00', '2020-04-24 05:00:00'),
                (7, 7, 1, '2020-04-24 05:00:00', '2020-04-24 05:00:00'),
                (8, 8, 2, '2020-04-24 05:00:00', '2020-04-24 05:00:00'),
                (11, 9, 2, '2020-04-24 05:00:00', '2020-04-24 05:00:00'),
                (9, 9, 1, '2020-04-24 05:00:00', '2020-04-24 05:00:00'),
                (10, 10, 1, '2020-04-24 05:00:00', '2020-04-24 05:00:00');");

                //Estructura de tabla para la tabla `service_list
                $structure = DB::connection('newCompany')->statement("CREATE TABLE `service_list` (
                    `id` int(11) NOT NULL,
                    `name` varchar(120) DEFAULT NULL,
                    `description` text,
                    `time` varchar(15) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
                    `price` varchar(50) NOT NULL,
                    `opening_hours` text,
                    `state` int(11) NOT NULL,
                    `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
                    `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
                ) ENGINE=InnoDB DEFAULT CHARSET=latin1;");

                # Basic opening hours
                $opening = '[{ "monday": [{ "date_start": "08:00:00", "date_end": "12:30:00" }, { "date_start": "14:00:00", "date_end": "17:30:00" }] }, { "tuesday": [{ "date_start": "08:00:00", "date_end": "12:30:00" }, { "date_start": "14:00:00", "date_end": "17:30:00" }] }, { "wednesday": [{ "date_start": "08:00:00", "date_end": "12:30:00" }, { "date_start": "14:00:00", "date_end": "17:30:00" }] }, { "thursday": [{ "date_start": "08:00:00", "date_end": "12:30:00" }, { "date_start": "14:00:00", "date_end": "17:30:00" }] }, { "friday": [{ "date_start": "08:00:00", "date_end": "12:30:00" }, { "date_start": "14:00:00", "date_end": "17:30:00" }] }, { "saturday": [{ "date_start": "08:00:00", "date_end": "12:30:00" }, { "date_start": "14:00:00", "date_end": "16:30:00" }] }, { "holidays": [{ "date_start": "08:00:00", "date_end": "12:30:00" }] }]';

                // Volcado de datos para la tabla `service_list`
                $structure = DB::connection('newCompany')->statement("INSERT INTO `service_list` (`id`, `name`, `description`, `time`, `price`, `opening_hours`, `state`) VALUES
                (1, 'General', 'Es un turno para cortarse el cabello.', '45', '10000', '$opening', 1);");

                //Estructura de tabla para la tabla `turn_state
                $structure = DB::connection('newCompany')->statement("CREATE TABLE `turn_state` (
                    `id` int(11) NOT NULL,
                    `name` varchar(100) NOT NULL,
                    `description` text
                ) ENGINE=InnoDB DEFAULT CHARSET=latin1;");

                // Volcado de datos para la tabla `turn_state`
                $structure = DB::connection('newCompany')->statement("INSERT INTO `turn_state` (`id`, `name`, `description`) VALUES
                (1, 'En proceso', 'El turno se está ejecutando.'),
                (2, 'En espera', 'El turno aun no se inicia.'),
                (3, 'Cancelado', 'El turno fue cancelado.'),
                (4, 'Finalizado', 'El turno se terminó exitosamente.');");

                //Estructura de tabla para la tabla `users
                $structure = DB::connection('newCompany')->statement("CREATE TABLE `users` (
                    `id` int(10) NOT NULL,
                    `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
                    `last_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
                    `phone` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
                    `address` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
                    `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
                    `dni` varchar(15) COLLATE utf8mb4_unicode_ci NOT NULL,
                    `state_id` int(10) NOT NULL,
                    `created_at` timestamp NULL DEFAULT NULL,
                    `updated_at` timestamp NULL DEFAULT NULL,
                    `phanton_user` int(11) NOT NULL,
                    `principal_id` int(11) NOT NULL
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;");

                //Estructura de tabla para la tabla `user_has_role
                $structure = DB::connection('newCompany')->statement("CREATE TABLE `user_has_role` (
                    `id` bigint(20) UNSIGNED NOT NULL,
                    `user_id` int(10) NOT NULL,
                    `role_id` int(10) UNSIGNED NOT NULL,
                    `created_at` timestamp NULL DEFAULT NULL,
                    `updated_at` timestamp NULL DEFAULT NULL
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;");

                //Estructura de tabla para la tabla `user_state
                $structure = DB::connection('newCompany')->statement("CREATE TABLE `user_state` (
                    `id` int(10) NOT NULL,
                    `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
                    `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
                    `created_at` timestamp NULL DEFAULT NULL,
                    `updated_at` timestamp NULL DEFAULT NULL
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;");

                // Volcado de datos para la tabla `user_state`
                $structure = DB::connection('newCompany')->statement("INSERT INTO `user_state` (`id`, `name`, `description`, `created_at`, `updated_at`) VALUES
                (1, 'Activo', 'Usuario activo', '2020-04-22 05:00:00', '2020-04-22 05:00:00'),
                (2, 'Inactivo', 'Usuario inactivo', '2020-04-22 05:00:00', '2020-04-22 05:00:00');");


                // ################ -- Relaciones -- ##############################

                //Indices de la tabla `client_turn`
                $relations = DB::connection('newCompany')->statement("ALTER TABLE `client_turn`
                ADD PRIMARY KEY (`id`),
                ADD KEY `state_id` (`state_id`),
                ADD KEY `service_id` (`service_id`),
                ADD KEY `finished_by_id` (`finished_by_id`),
                ADD KEY `employee_id` (`employee_id`),
                ADD KEY `started_by` (`started_by`);");

                //Indices de la tabla `company_data`
                $relations = DB::connection('newCompany')->statement("ALTER TABLE `company_data`
                ADD PRIMARY KEY (`id`);");

                //Indices de la tabla `module`
                $relations = DB::connection('newCompany')->statement("ALTER TABLE `module`
                ADD PRIMARY KEY (`id`);");

                //Indices de la tabla `permission`
                $relations = DB::connection('newCompany')->statement("ALTER TABLE `permission`
                ADD PRIMARY KEY (`id`),
                ADD UNIQUE KEY `permission_name_unique` (`name`),
                ADD KEY `permission_module_id_foreign` (`module_id`);");

                //Indices de la tabla `role_has_permission`
                $relations = DB::connection('newCompany')->statement("ALTER TABLE `role_has_permission`
                ADD PRIMARY KEY (`id`),
                ADD KEY `role_has_permission_permission_id_foreign` (`permission_id`),
                ADD KEY `role_has_permission_role_id_foreign` (`role_id`);");

                //Indices de la tabla `service_list`
                $relations = DB::connection('newCompany')->statement("ALTER TABLE `service_list`
                ADD PRIMARY KEY (`id`);");

                //Indices de la tabla `turn_state`
                $relations = DB::connection('newCompany')->statement("ALTER TABLE `turn_state`
                ADD PRIMARY KEY (`id`);");

                //Indices de la tabla `role`
                $relations = DB::connection('newCompany')->statement("ALTER TABLE `role`
                ADD PRIMARY KEY (`id`);");

                //Indices de la tabla `users`
                $relations = DB::connection('newCompany')->statement("ALTER TABLE `users`
                ADD PRIMARY KEY (`id`),
                ADD UNIQUE KEY `users_email_unique` (`email`),
                ADD KEY `state_id` (`state_id`);");

                //Indices de la tabla `user_has_role`
                $relations = DB::connection('newCompany')->statement("ALTER TABLE `user_has_role`
                ADD PRIMARY KEY (`id`),
                ADD KEY `user_has_role_role_id_foreign` (`role_id`),
                ADD KEY `user_has_role_role_id_foreign1` (`user_id`);");

                //Indices de la tabla `user_state`
                $relations = DB::connection('newCompany')->statement("ALTER TABLE `user_state`
                ADD PRIMARY KEY (`id`);");

                // ################ -- AUTO_INCREMENT -- ##############################

                // AUTO_INCREMENT de la tabla `client_turn`
                $auto_increment = DB::connection('newCompany')->statement("ALTER TABLE `client_turn`
                MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;");

                // AUTO_INCREMENT de la tabla `company_data`
                $auto_increment = DB::connection('newCompany')->statement("ALTER TABLE `company_data`
                MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;");

                // AUTO_INCREMENT de la tabla `module`
                $auto_increment = DB::connection('newCompany')->statement("ALTER TABLE `module`
                MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;");

                // AUTO_INCREMENT de la tabla `permission`
                $auto_increment = DB::connection('newCompany')->statement("ALTER TABLE `permission`
                MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;");

                // AUTO_INCREMENT de la tabla `role`
                $auto_increment = DB::connection('newCompany')->statement("ALTER TABLE `role`
                MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;");

                // AUTO_INCREMENT de la tabla `role_has_permission`
                $auto_increment = DB::connection('newCompany')->statement("ALTER TABLE `role_has_permission`
                MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;");

                // AUTO_INCREMENT de la tabla `service_list`
                $auto_increment = DB::connection('newCompany')->statement("ALTER TABLE `service_list`
                MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;");

                // AUTO_INCREMENT de la tabla `turn_state`
                $auto_increment = DB::connection('newCompany')->statement("ALTER TABLE `turn_state`
                MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;");

                // AUTO_INCREMENT de la tabla `users`
                $auto_increment = DB::connection('newCompany')->statement("ALTER TABLE `users`
                MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;");

                // AUTO_INCREMENT de la tabla `user_has_role`
                $auto_increment = DB::connection('newCompany')->statement("ALTER TABLE `user_has_role`
                MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;");

                // AUTO_INCREMENT de la tabla `user_state`
                $auto_increment = DB::connection('newCompany')->statement("ALTER TABLE `user_state`
                MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;");

                // ################ -- Filtros para tablas  -- ##############################

                //Filtros para la tabla `client_turn`
                $filters = DB::connection('newCompany')->statement("ALTER TABLE `client_turn`
                ADD CONSTRAINT `client_turn_ibfk_1` FOREIGN KEY (`service_id`) REFERENCES `service_list` (`id`),
                ADD CONSTRAINT `client_turn_ibfk_2` FOREIGN KEY (`employee_id`) REFERENCES `users` (`id`),
                ADD CONSTRAINT `client_turn_ibfk_3` FOREIGN KEY (`finished_by_id`) REFERENCES `users` (`id`),
                ADD CONSTRAINT `client_turn_ibfk_4` FOREIGN KEY (`state_id`) REFERENCES `turn_state` (`id`),
                ADD CONSTRAINT `client_turn_ibfk_5` FOREIGN KEY (`started_by`) REFERENCES `users` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;");

                //Filtros para la tabla `permission`
                $filters = DB::connection('newCompany')->statement("ALTER TABLE `permission`
                ADD CONSTRAINT `permission_ibfk_1` FOREIGN KEY (`module_id`) REFERENCES `module` (`id`);");

                //Filtros para la tabla `role_has_permission`
                $filters = DB::connection('newCompany')->statement("ALTER TABLE `role_has_permission`
                ADD CONSTRAINT `role_has_permission_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permission` (`id`),
                ADD CONSTRAINT `role_has_permission_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `role` (`id`);");

                //Filtros para la tabla `users`
                $filters = DB::connection('newCompany')->statement("ALTER TABLE `users`
                ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`state_id`) REFERENCES `user_state` (`id`);");

                //Filtros para la tabla `user_has_role`
                $filters = DB::connection('newCompany')->statement("ALTER TABLE `user_has_role`
                ADD CONSTRAINT `user_has_role_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `role` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
                ADD CONSTRAINT `user_has_role_role_id_foreign1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;");

            }catch(Exception $e){
                $structure = DB::connection('newCompany')->statement("DROP DATABASE $db_name;");
                return $e->getMessage();
            }
            return 1;
        }else{

            return 'Error al crear la base de datos de la empresa';
        }

    }

    public static function newGroomingDatabase($db_name, $data)
    {
        $db_company = DB::statement("CREATE DATABASE $db_name");
        if($db_company){

            $configDb = [
                'driver'      => 'mysql',
                'host'        => env('DB_HOST', '127.0.0.1'),
                'port'        => env('DB_PORT', '3306'),
                'database'    => $db_name,
                'username'    => env('DB_USERNAME', 'forge'),
                'password'    => env('DB_PASSWORD', ''),
                'unix_socket' => env('DB_SOCKET', ''),
                'charset'     => 'utf8',
                'collation'   => 'utf8_unicode_ci',
                'prefix'      => '',
                'strict'      => true,
                'engine'      => null,
            ];

            \Config::set('database.connections.newCompany', $configDb);
            try{
                //Estructura de tabla para la tabla `client_service`
                $structure = DB::connection('newCompany')->statement("CREATE TABLE `client_service` (
                    `id` int(11) NOT NULL,
                    `employee_id` int(11) DEFAULT NULL,
                    `user_id` int(11) NOT NULL,
                    `user_service_id` int(11) NOT NULL,
                    `dni` varchar(15) COLLATE utf8mb4_unicode_ci NOT NULL,
                    `start_at` datetime DEFAULT NULL,
                    `acepted_by` int(11) DEFAULT NULL,
                    `service_id` int(11) NOT NULL,
                    `paid_out` int(11) NOT NULL,
                    `hours` int(11) DEFAULT NULL,
                    `date_start` datetime DEFAULT NULL,
                    `date_end` datetime DEFAULT NULL,
                    `state_id` int(11) NOT NULL,
                    `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
                    `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
                  ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;");

                //Estructura de tabla para la tabla `company_data`
                $structure = DB::connection('newCompany')->statement("CREATE TABLE `company_data` (
                    `id` int(11) NOT NULL,
                    `opening_hours` text,
                    `company_id` int(11) NOT NULL,
                    `api_k` varchar(50) DEFAULT NULL,
                    `api_l` varchar(50) DEFAULT NULL,
                    `mer_id` varchar(20) DEFAULT NULL,
                    `acc_id` varchar(20) DEFAULT NULL,
                    `pay_on_line` tinyint(1) NOT NULL DEFAULT '0',
                    `created_at` timestamp NULL DEFAULT NULL,
                    `updated_at` timestamp NULL DEFAULT NULL
                  ) ENGINE=InnoDB DEFAULT CHARSET=latin1;");

                //Estructura de tabla para la tabla `module`
                $structure = DB::connection('newCompany')->statement("CREATE TABLE `module` (
                    `id` int(10) NOT NULL,
                    `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
                    `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
                    `created_at` timestamp NULL DEFAULT NULL,
                    `updated_at` timestamp NULL DEFAULT NULL
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;");

                // Volcado de datos para la tabla `module`
                $structure = DB::connection('newCompany')->statement("INSERT INTO `module` (`id`, `name`, `description`, `created_at`, `updated_at`) VALUES
                (1, 'Usuarios', 'Permite administrar los usuarios.', '2020-03-24 04:36:31', '2020-03-24 04:36:31'),
                (2, 'Roles', 'Permite administrar los roles.', '2020-03-24 04:36:31', '2020-03-24 04:36:31'),
                (3, 'Sucursal', 'Administración de la sucursal', '2020-04-24 10:00:00', '2020-04-24 10:00:00'),
                (4, 'Servicios', 'Administración de lo sservicios', '2020-04-24 10:00:00', '2020-04-24 10:00:00');");

                //Estructura de tabla para la tabla `permission`
                $structure = DB::connection('newCompany')->statement("CREATE TABLE `permission` (
                    `id` int(10) UNSIGNED NOT NULL,
                    `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
                    `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
                    `route` text COLLATE utf8mb4_unicode_ci NOT NULL,
                    `created_at` timestamp NULL DEFAULT NULL,
                    `updated_at` timestamp NULL DEFAULT NULL,
                    `module_id` int(10) NOT NULL
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;");

                // Volcado de datos para la tabla `permission`
                $structure = DB::connection('newCompany')->statement("INSERT INTO `permission` (`id`, `name`, `description`, `route`, `created_at`, `updated_at`, `module_id`) VALUES
                (1, 'Listar usuarios', 'Permite listar usuarios.', '/list_user', '2020-05-06 05:00:00', '2020-05-06 05:00:00', 1),
                (2, 'Crear usuarios', 'Permite crear usuarios.', '/create_user', '2020-05-06 05:00:00', '2020-05-06 05:00:00', 1),
                (3, 'modificar usuarios', 'Permite actualizar y eliminar usuarios.', '/update_user', '2020-05-06 05:00:00', '2020-05-06 05:00:00', 1),
                (4, 'Listar roles', 'Permite listar roles.', '/list_role', '2020-05-06 05:00:00', '2020-05-06 05:00:00', 2),
                (5, 'Datos de la sucursal', 'Ver información de la sucursal', '/show_branch', '2020-05-06 05:00:00', '2020-05-06 05:00:00', 3),
                (6, 'Editar datos de la sucursal', 'Puede editar datos de la sucursal.', '/update_branch', '2020-05-06 05:00:00', '2020-05-06 05:00:00', 3),
                (7, 'Listar servicios', 'Listar y administrar los servicios solicitados', '/list_services', '2020-05-06 05:00:00', '2020-05-06 05:00:00', 4),
                (8, 'Modificar servicios al clinte', 'Puede cambiar estado de los servicios brindados', '/update_services', '2020-05-06 05:00:00', '2020-05-06 05:00:00', 4),
                (9, 'Listar configuración de los servicios', 'Lista la configuración de los servicios que ofrece la empresa.', '/list_c_service', '2020-05-08 05:00:00', '2020-05-08 05:00:00', 4),
                (10, 'Crear y modificar la configuración de los servicios', 'Crear y modificar la configuración de los servicios que ofrece la empresa.', '/create_c_service', '2020-05-08 05:00:00', '2020-05-08 05:00:00', 4);");

                //Estructura de tabla para la tabla `role`
                $structure = DB::connection('newCompany')->statement("CREATE TABLE `role` (
                    `id` int(10) UNSIGNED NOT NULL,
                    `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
                    `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
                    `created_at` timestamp NULL DEFAULT NULL,
                    `updated_at` timestamp NULL DEFAULT NULL,
                    `state` int(11) NOT NULL
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;");

                // Volcado de datos para la tabla `role`
                $structure = DB::connection('newCompany')->statement("INSERT INTO `role` (`id`, `name`, `description`, `created_at`, `updated_at`, `state`) VALUES
                (1, 'Administrador', 'Permite administrar todo el sitio web.', '2020-03-23 23:36:30', '2020-03-23 23:36:30', 1),
                (2, 'Empleado', 'Empleado aseador', '2020-05-04 05:00:00', '2020-05-04 05:00:00', 1);");

                //Estructura de tabla para la tabla `role_has_permission
                $structure = DB::connection('newCompany')->statement("CREATE TABLE `role_has_permission` (
                    `id` bigint(20) UNSIGNED NOT NULL,
                    `permission_id` int(10) UNSIGNED NOT NULL,
                    `role_id` int(10) UNSIGNED NOT NULL,
                    `created_at` timestamp NULL DEFAULT NULL,
                    `updated_at` timestamp NULL DEFAULT NULL
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;");

                // Volcado de datos para la tabla `role_has_permission`
                $structure = DB::connection('newCompany')->statement("INSERT INTO `role_has_permission` (`id`, `permission_id`, `role_id`, `created_at`, `updated_at`) VALUES
                (1, 1, 1, '2020-05-07 03:11:13', '2020-05-07 03:11:13'),
                (2, 2, 1, '2020-05-07 03:11:13', '2020-05-07 03:11:13'),
                (3, 3, 1, '2020-05-07 03:11:19', '2020-05-07 03:11:19'),
                (4, 4, 1, '2020-05-07 03:11:19', '2020-05-07 03:11:19'),
                (5, 5, 1, '2020-05-07 03:11:28', '2020-05-07 03:11:28'),
                (6, 6, 1, '2020-05-07 03:11:28', '2020-05-07 03:11:28'),
                (7, 7, 1, '2020-05-07 03:11:37', '2020-05-07 03:11:37'),
                (8, 8, 1, '2020-05-07 03:11:37', '2020-05-07 03:11:37'),
                (9, 7, 2, '2020-05-07 03:11:41', '2020-05-07 03:11:41');");

                //Estructura de tabla para la tabla `service_list`
                $structure = DB::connection('newCompany')->statement("CREATE TABLE `service_list` (
                    `id` int(11) NOT NULL,
                    `name` varchar(120) DEFAULT NULL,
                    `description` text,
                    `price_per_hour` varchar(50) NOT NULL,
                    `unit_per_hour` int(11) NOT NULL,
                    `hours_max` int(11) NOT NULL,
                    `wait_time` int(11) NOT NULL,
                    `opening_hours` text,
                    `state` int(11) NOT NULL,
                    `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
                    `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
                  ) ENGINE=InnoDB DEFAULT CHARSET=latin1;");

                   # Basic opening hours
                $opening = '[{ "monday": [{ "date_start": "08:00:00", "date_end": "12:30:00" }, { "date_start": "14:00:00", "date_end": "17:30:00" }] }, { "tuesday": [{ "date_start": "08:00:00", "date_end": "12:30:00" }, { "date_start": "14:00:00", "date_end": "17:30:00" }] }, { "wednesday": [{ "date_start": "08:00:00", "date_end": "12:30:00" }, { "date_start": "14:00:00", "date_end": "17:30:00" }] }, { "thursday": [{ "date_start": "08:00:00", "date_end": "12:30:00" }, { "date_start": "14:00:00", "date_end": "17:30:00" }] }, { "friday": [{ "date_start": "08:00:00", "date_end": "12:30:00" }, { "date_start": "14:00:00", "date_end": "17:30:00" }] }, { "saturday": [{ "date_start": "08:00:00", "date_end": "12:30:00" }, { "date_start": "14:00:00", "date_end": "16:30:00" }] }, { "holidays": [{ "date_start": "08:00:00", "date_end": "12:30:00" }] }]';

                // Volcado de datos para la tabla `service_list`
                $structure = DB::connection('newCompany')->statement("INSERT INTO `service_list` (`id`, `name`, `description`, `price_per_hour`, `unit_per_hour`, `hours_max`, `wait_time`, `opening_hours`, `state`) VALUES
                (1, 'General', 'Aseo general.', '20000', 30, 12, 30, '$opening', 1);");

                //Estructura de tabla para la tabla `service_state
                $structure = DB::connection('newCompany')->statement("CREATE TABLE `service_state` (
                    `id` int(11) NOT NULL,
                    `name` varchar(100) NOT NULL,
                    `description` text
                  ) ENGINE=InnoDB DEFAULT CHARSET=latin1;");

                // Volcado de datos para la tabla `service_state`
                $structure = DB::connection('newCompany')->statement("INSERT INTO `service_state` (`id`, `name`, `description`) VALUES
                (1, 'Servicio solicitado', 'Se solicitó un servicio.'),
                (2, 'Servicio aceptado', 'Ser aceptó un servicio.'),
                (3, 'Servicio cancelado', 'Se canceló el servicio.'),
                (4, 'En proceso de revisión', 'El estado fue recibido y se está analizando.'),
                (5, 'Servicio en proceso.', 'El servicio se está empleado por el empleado.'),
                (6, 'Servicio finalizado', 'El servicio se finalizó.');");

                //Estructura de tabla para la tabla `users
                $structure = DB::connection('newCompany')->statement("CREATE TABLE `users` (
                    `id` int(10) NOT NULL,
                    `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
                    `last_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
                    `phone` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
                    `address` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
                    `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
                    `dni` varchar(15) COLLATE utf8mb4_unicode_ci NOT NULL,
                    `state_id` int(10) NOT NULL,
                    `created_at` timestamp NULL DEFAULT NULL,
                    `updated_at` timestamp NULL DEFAULT NULL,
                    `phanton_user` int(11) NOT NULL,
                    `principal_id` int(11) NOT NULL
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;");

                //Estructura de tabla para la tabla `user_has_role
                $structure = DB::connection('newCompany')->statement("CREATE TABLE `user_has_role` (
                    `id` bigint(20) UNSIGNED NOT NULL,
                    `user_id` int(10) NOT NULL,
                    `role_id` int(10) UNSIGNED NOT NULL,
                    `created_at` timestamp NULL DEFAULT NULL,
                    `updated_at` timestamp NULL DEFAULT NULL
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;");

                //Estructura de tabla para la tabla `user_state
                $structure = DB::connection('newCompany')->statement("CREATE TABLE `user_state` (
                    `id` int(10) NOT NULL,
                    `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
                    `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
                    `created_at` timestamp NULL DEFAULT NULL,
                    `updated_at` timestamp NULL DEFAULT NULL
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;");

                // Volcado de datos para la tabla `user_state`
                $structure = DB::connection('newCompany')->statement("INSERT INTO `user_state` (`id`, `name`, `description`, `created_at`, `updated_at`) VALUES
                (1, 'Activo', 'Usuario activo', '2020-04-22 05:00:00', '2020-04-22 05:00:00'),
                (2, 'Inactivo', 'Usuario inactivo', '2020-04-22 05:00:00', '2020-04-22 05:00:00');");


                // ################ -- Relaciones -- ##############################

                //Indices de la tabla `client_turn`
                $relations = DB::connection('newCompany')->statement("ALTER TABLE `client_service`
                ADD PRIMARY KEY (`id`),
                ADD KEY `employee_id` (`employee_id`),
                ADD KEY `user_service_id` (`user_service_id`),
                ADD KEY `acepted_by` (`acepted_by`),
                ADD KEY `service_id` (`service_id`),
                ADD KEY `state_id` (`state_id`);");

                //Indices de la tabla `company_data`
                $relations = DB::connection('newCompany')->statement("ALTER TABLE `company_data`
                ADD PRIMARY KEY (`id`);");

                //Indices de la tabla `module`
                $relations = DB::connection('newCompany')->statement("ALTER TABLE `module`
                ADD PRIMARY KEY (`id`);");

                //Indices de la tabla `permission`
                $relations = DB::connection('newCompany')->statement("ALTER TABLE `permission`
                ADD PRIMARY KEY (`id`),
                ADD UNIQUE KEY `permission_name_unique` (`name`),
                ADD KEY `permission_module_id_foreign` (`module_id`);");

                //Indices de la tabla `role_has_permission`
                $relations = DB::connection('newCompany')->statement("ALTER TABLE `role_has_permission`
                ADD PRIMARY KEY (`id`),
                ADD KEY `role_has_permission_permission_id_foreign` (`permission_id`),
                ADD KEY `role_has_permission_role_id_foreign` (`role_id`);");

                //Indices de la tabla `service_list`
                $relations = DB::connection('newCompany')->statement("ALTER TABLE `service_list`
                ADD PRIMARY KEY (`id`);");

                //Indices de la tabla `service_state`
                $relations = DB::connection('newCompany')->statement("ALTER TABLE `service_state`
                ADD PRIMARY KEY (`id`);");

                //Indices de la tabla `role`
                $relations = DB::connection('newCompany')->statement("ALTER TABLE `role`
                ADD PRIMARY KEY (`id`);");

                //Indices de la tabla `users`
                $relations = DB::connection('newCompany')->statement("ALTER TABLE `users`
                ADD PRIMARY KEY (`id`),
                ADD UNIQUE KEY `users_email_unique` (`email`),
                ADD KEY `state_id` (`state_id`);");

                //Indices de la tabla `user_has_role`
                $relations = DB::connection('newCompany')->statement("ALTER TABLE `user_has_role`
                ADD PRIMARY KEY (`id`),
                ADD KEY `user_has_role_role_id_foreign` (`role_id`),
                ADD KEY `user_has_role_role_id_foreign1` (`user_id`);");

                //Indices de la tabla `user_state`
                $relations = DB::connection('newCompany')->statement("ALTER TABLE `user_state`
                ADD PRIMARY KEY (`id`);");

                // ################ -- AUTO_INCREMENT -- ##############################

                // AUTO_INCREMENT de la tabla `client_service`
                $auto_increment = DB::connection('newCompany')->statement("ALTER TABLE `client_service`
                MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;");

                // AUTO_INCREMENT de la tabla `company_data`
                $auto_increment = DB::connection('newCompany')->statement("ALTER TABLE `company_data`
                MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;");

                // AUTO_INCREMENT de la tabla `module`
                $auto_increment = DB::connection('newCompany')->statement("ALTER TABLE `module`
                MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;");

                // AUTO_INCREMENT de la tabla `permission`
                $auto_increment = DB::connection('newCompany')->statement("ALTER TABLE `permission`
                MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;");

                // AUTO_INCREMENT de la tabla `role`
                $auto_increment = DB::connection('newCompany')->statement("ALTER TABLE `role`
                MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;");

                // AUTO_INCREMENT de la tabla `role_has_permission`
                $auto_increment = DB::connection('newCompany')->statement("ALTER TABLE `role_has_permission`
                MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;");

                // AUTO_INCREMENT de la tabla `service_list`
                $auto_increment = DB::connection('newCompany')->statement("ALTER TABLE `service_list`
                MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;");

                // AUTO_INCREMENT de la tabla `service_state`
                $auto_increment = DB::connection('newCompany')->statement("ALTER TABLE `service_state`
                MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;");

                // AUTO_INCREMENT de la tabla `users`
                $auto_increment = DB::connection('newCompany')->statement("ALTER TABLE `users`
                MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;");

                // AUTO_INCREMENT de la tabla `user_has_role`
                $auto_increment = DB::connection('newCompany')->statement("ALTER TABLE `user_has_role`
                MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;");

                // AUTO_INCREMENT de la tabla `user_state`
                $auto_increment = DB::connection('newCompany')->statement("ALTER TABLE `user_state`
                MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;");

                // ################ -- Filtros para tablas  -- ##############################

                //Filtros para la tabla `client_service`
                $filters = DB::connection('newCompany')->statement("ALTER TABLE `client_service`
                ADD CONSTRAINT `client_service_ibfk_1` FOREIGN KEY (`employee_id`) REFERENCES `users` (`id`),
                ADD CONSTRAINT `client_service_ibfk_3` FOREIGN KEY (`acepted_by`) REFERENCES `users` (`id`),
                ADD CONSTRAINT `client_service_ibfk_4` FOREIGN KEY (`service_id`) REFERENCES `service_list` (`id`),
                ADD CONSTRAINT `client_service_ibfk_5` FOREIGN KEY (`state_id`) REFERENCES `service_state` (`id`);");

                //Filtros para la tabla `permission`
                $filters = DB::connection('newCompany')->statement("ALTER TABLE `permission`
                ADD CONSTRAINT `permission_ibfk_1` FOREIGN KEY (`module_id`) REFERENCES `module` (`id`);");

                //Filtros para la tabla `role_has_permission`
                $filters = DB::connection('newCompany')->statement("ALTER TABLE `role_has_permission`
                ADD CONSTRAINT `role_has_permission_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permission` (`id`),
                ADD CONSTRAINT `role_has_permission_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `role` (`id`);");

                //Filtros para la tabla `users`
                $filters = DB::connection('newCompany')->statement("ALTER TABLE `users`
                ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`state_id`) REFERENCES `user_state` (`id`);");

                //Filtros para la tabla `user_has_role`
                $filters = DB::connection('newCompany')->statement("ALTER TABLE `user_has_role`
                ADD CONSTRAINT `user_has_role_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `role` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
                ADD CONSTRAINT `user_has_role_role_id_foreign1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;");

            }catch(Exception $e){
                $structure = DB::connection('newCompany')->statement("DROP DATABASE $db_name;");
                return $e->getMessage();
            }
            return 1;
        }else{

            return 'Error al crear la base de datos de la empresa';
        }

    }
}
