<?php

namespace App\Http\Controllers\Helper;

use App\Http\Controllers\Controller;
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
                    `id_company` int(11) NOT NULL,
                    `api_k` varchar(50) DEFAULT NULL,
                    `api_l` varchar(50) DEFAULT NULL,
                    `mer_id` varchar(20) DEFAULT NULL,
                    `acc_id` varchar(20) DEFAULT NULL,
                    `pay_on_line` tinyint(1) NOT NULL DEFAULT '0'
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
                (4, 'Turnos', 'Administración de turnos', '2020-04-24 05:00:00', '2020-04-24 05:00:00');");

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
                (7, 'Listar turnos', 'Puede listar la información de los turnos.', '/list_turn', '2020-04-24 05:00:00', '2020-04-24 05:00:00', 4);");

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
                (1, 'Administrador', 'Permite administrar todo el sitio web.', '2020-03-23 23:36:30', '2020-03-23 23:36:30', 1);");

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
                (7, 7, 1, '2020-04-24 05:00:00', '2020-04-24 05:00:00');");

                //Estructura de tabla para la tabla `service_list
                $structure = DB::connection('newCompany')->statement("CREATE TABLE `service_list` (
                    `id` int(11) NOT NULL,
                    `name` varchar(120) DEFAULT NULL,
                    `description` text,
                    `time` varchar(15) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
                    `price` varchar(50) NOT NULL
                ) ENGINE=InnoDB DEFAULT CHARSET=latin1;");

                // Volcado de datos para la tabla `service_list`
                $structure = DB::connection('newCompany')->statement("INSERT INTO `service_list` (`id`, `name`, `description`, `time`, `price`) VALUES
                (1, 'General', 'Es un turno para cortarse el cabello.', '45', '10000');");

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
                ADD KEY `employee_id` (`employee_id`);");

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
                ADD CONSTRAINT `client_turn_ibfk_3` FOREIGN KEY (`finished_by_id`) REFERENCES `users` (`id`);");

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
