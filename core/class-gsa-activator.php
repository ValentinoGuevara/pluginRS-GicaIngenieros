<?php
if (!defined('ABSPATH')) exit;

class GSA_Activator {

    public static function activar() {
        global $wpdb;
        $charset_collate = $wpdb->get_charset_collate();

        // Tabla 1: Redes Sociales
        $t_socials = $wpdb->prefix . 'gsa_socials';
        $sql_socials = "CREATE TABLE $t_socials (
            id INT AUTO_INCREMENT,
            nombre VARCHAR(50) NOT NULL,
            url VARCHAR(255) NOT NULL,
            icono VARCHAR(100) NOT NULL,
            color VARCHAR(20) DEFAULT '#000000',
            orden INT DEFAULT 0,
            estado TINYINT(1) DEFAULT 1,
            PRIMARY KEY  (id)
        ) $charset_collate;";

        // Tabla 2: Clics (Métricas)
        $t_clicks = $wpdb->prefix . 'gsa_clicks';
        $sql_clicks = "CREATE TABLE $t_clicks (
            id BIGINT AUTO_INCREMENT,
            social_id INT NOT NULL,
            pagina VARCHAR(255) NOT NULL,
            dispositivo VARCHAR(50) NOT NULL,
            navegador VARCHAR(50) NOT NULL,
            fecha_click DATETIME DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY  (id),
            KEY social_id_idx (social_id),
            KEY fecha_click_idx (fecha_click)
        ) $charset_collate;";

        // Tabla 4: Ajustes Generales
        $t_settings = $wpdb->prefix . 'gsa_settings';
        $sql_settings = "CREATE TABLE $t_settings (
            id INT AUTO_INCREMENT,
            clave VARCHAR(100) NOT NULL,
            valor LONGTEXT NOT NULL,
            PRIMARY KEY  (id),
            UNIQUE KEY clave_unique (clave)
        ) $charset_collate;";

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql_socials);
        dbDelta($sql_clicks);
        dbDelta($sql_settings);
    }
}