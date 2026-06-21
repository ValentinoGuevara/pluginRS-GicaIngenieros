<?php
/*
Plugin Name: GICA Social Analytics
Description: Sistema modular analítico de redes sociales para WordPress desarrollado para GICA Ingenieros.
Version: 1.0.0
Author: Valentino Guevara
*/

if (!defined('ABSPATH')) exit;
// Autoloader de Composer

// Definición de constantes globales de ruta
define('GSA_PATH', plugin_dir_path(__FILE__));
define('GSA_URL', plugin_dir_url(__FILE__));
define('GSA_VERSION', '1.0.0');
require_once GSA_PATH . 'vendor/autoload.php';

// 1. Capa Core: Configuración e instalación de tablas
require_once GSA_PATH . 'core/class-gsa-activator.php';
register_activation_hook(__FILE__, array('GSA_Activator', 'activar'));


// 2. Capa Includes: Carga de Controladores (Módulos 1 y 2)
require_once GSA_PATH . 'models/class-gsa-social-model.php';
require_once GSA_PATH . 'models/class-gsa-analytics-model.php';
require_once GSA_PATH . 'includes/class-gsa-admin.php';
require_once GSA_PATH . 'includes/class-gsa-frontend.php';
require_once GSA_PATH . 'includes/class-gsa-dashboard.php';
require_once GSA_PATH . 'includes/class-gsa-tracker.php';
require_once GSA_PATH . 'models/class-gsa-reporter-model.php';
require_once GSA_PATH . 'includes/class-gsa-reporter.php';
require_once GSA_PATH . 'models/class-gsa-settings-model.php';

// Inicializar el sistema
add_action('plugins_loaded', 'gsa_arrancar_plugin');
function gsa_arrancar_plugin() {
    if (is_admin()) {
        new GSA_Admin();
    }
    new GSA_Frontend();
    new GSA_Tracker();
}

add_action('admin_enqueue_scripts', function () {
    wp_enqueue_style(
        'fontawesome',
        'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css',
        [],
        '6.5.2'
    );
});