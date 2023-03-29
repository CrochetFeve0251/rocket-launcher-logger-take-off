<?php

defined( 'ABSPATH' ) || exit;

$plugin_name = 'Rocket launcher';

$plugin_launcher_path = dirname(__DIR__) . '/';

return [
    'plugin_name' => $plugin_name,
    'plugin_slug' => sanitize_key( $plugin_name ),
    'plugin_version' => '1.0.0',
    'plugin_launcher_file' => $plugin_launcher_path . '/' . basename($plugin_launcher_path) . '.php',
    'plugin_launcher_path' => $plugin_launcher_path,
    'plugin_inc_path' => realpath( $plugin_launcher_path . 'inc/' ) . '/',
    'prefix' => 'rocket_launcher_',
    'translation_key' => 'rocketlauncher',
    'is_mu_plugin' => false,
    'log_enabled' => false,
    'log_handlers' => [
        \RocketLauncher\Dependencies\RocketLauncherLogger\MonologHandler::class
    ],
    'logger_name' => 'rocketlauncher',
    'log_file_name' => 'rocketlauncher.log',
    'log_path' => '',
    'log_debug_interval' => 0,
];
