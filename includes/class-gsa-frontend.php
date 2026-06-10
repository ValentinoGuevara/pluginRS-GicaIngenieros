<?php
if (!defined('ABSPATH')) exit;

class GSA_Frontend {
    private $table_name;

    public function __construct() {
        global $wpdb;
        $this->table_name = $wpdb->prefix . 'gsa_socials';

        add_action('wp_enqueue_scripts', array($this, 'cargar_assets_publicos'));
        add_action('wp_footer', array($this, 'pintar_botones_flotantes'));
        add_shortcode('gsa_botones', array($this, 'render_via_shortcode'));
    }

    public function cargar_assets_publicos() {
        wp_enqueue_style('dashicons');
        // Apuntado exacto a /public/css/gsa-frontend.css
        wp_enqueue_style('gsa-frontend-css', GSA_URL . 'public/css/gsa-frontend.css', array(), '1.0.0');
    }

    public function pintar_botones_flotantes() {
        global $wpdb;
        $botones = $wpdb->get_results("SELECT * FROM {$this->table_name} WHERE estado = 1 ORDER BY orden ASC");

        if (empty($botones)) return;

        echo '<div class="gsa-floating-wrapper">';
        foreach ($botones as $btn) {
            echo sprintf(
                '<a href="%s" target="_blank" rel="noopener noreferrer" class="gsa-btn" data-id="%d" style="background-color: %s;">
                    <span class="dashicons %s"></span>
                 </a>',
                esc_url($btn->url),
                intval($btn->id),
                esc_attr($btn->color),
                esc_attr($btn->icono)
            );
        }
        echo '</div>';
    }

    public function render_via_shortcode() {
        ob_start();
        $this->pintar_botones_flotantes();
        return ob_get_clean();
    }
}