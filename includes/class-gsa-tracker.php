<?php
if (!defined('ABSPATH')) exit;

class GSA_Tracker {

    public function __construct() {
        add_action('wp_ajax_gsa_registrar_clic',        array($this, 'registrar_clic'));
        add_action('wp_ajax_nopriv_gsa_registrar_clic', array($this, 'registrar_clic'));
    }

    public function registrar_clic() {
        $settings = new GSA_Settings_Model();
        if ($settings->obtener('tracker_activo', '1') !== '1') {
            wp_send_json_error('Tracker desactivado.');
        }
        
        check_ajax_referer('gsa_nonce_tracker', 'nonce');

        $social_id  = isset($_POST['social_id'])  ? intval($_POST['social_id'])             : 0;
        $pagina     = isset($_POST['pagina'])      ? sanitize_text_field($_POST['pagina'])   : '';
        $dispositivo = $this->detectar_dispositivo();
        $navegador   = $this->detectar_navegador();

        if ($social_id <= 0) {
            wp_send_json_error('ID inválido.');
        }

        global $wpdb;
        $wpdb->insert(
            $wpdb->prefix . 'gsa_clicks',
            array(
                'social_id'   => $social_id,
                'pagina'      => $pagina,
                'dispositivo' => $dispositivo,
                'navegador'   => $navegador,
                'fecha_click' => current_time('mysql'),
            ),
            array('%d', '%s', '%s', '%s', '%s')
        );

        wp_send_json_success('Clic registrado.');
    }

    private function detectar_dispositivo() {
        $ua = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '';
        if (preg_match('/Mobile|Android|iPhone|iPad/i', $ua)) {
            return 'movil';
        }
        return 'escritorio';
    }

    private function detectar_navegador() {
        $ua = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '';
        if (strpos($ua, 'Chrome') !== false)  return 'Chrome';
        if (strpos($ua, 'Firefox') !== false) return 'Firefox';
        if (strpos($ua, 'Safari') !== false)  return 'Safari';
        if (strpos($ua, 'Edge') !== false)    return 'Edge';
        if (strpos($ua, 'Opera') !== false)   return 'Opera';
        return 'Otro';
    }
}