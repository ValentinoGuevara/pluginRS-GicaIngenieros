<?php
if (!defined('ABSPATH')) exit;

class GSA_Analytics_Model {
    private $tabla_clicks;
    private $tabla_socials;

    public function __construct() {
        global $wpdb;
        $this->tabla_clicks  = $wpdb->prefix . 'gsa_clicks';
        $this->tabla_socials = $wpdb->prefix . 'gsa_socials';
    }

    // TOTAL DE CLICS
    public function total_clics() {
        global $wpdb;
        return intval($wpdb->get_var("SELECT COUNT(*) FROM {$this->tabla_clicks}"));
    }

    // CLICS HOY
    public function clics_hoy() {
        global $wpdb;
        $hoy = current_time('Y-m-d');
        return intval($wpdb->get_var($wpdb->prepare(
            "SELECT COUNT(*) FROM {$this->tabla_clicks} WHERE DATE(fecha_click) = %s", $hoy
        )));
    }

    // CLICS ESTA SEMANA
    public function clics_semana() {
        global $wpdb;
        return intval($wpdb->get_var(
            "SELECT COUNT(*) FROM {$this->tabla_clicks} WHERE fecha_click >= DATE_SUB(NOW(), INTERVAL 7 DAY)"
        ));
    }

    // CLICS ESTE MES
    public function clics_mes() {
        global $wpdb;
        return intval($wpdb->get_var(
            "SELECT COUNT(*) FROM {$this->tabla_clicks} WHERE fecha_click >= DATE_SUB(NOW(), INTERVAL 30 DAY)"
        ));
    }

    // REDES MÁS UTILIZADAS
    public function redes_mas_utilizadas($limite = 5) {
        global $wpdb;
        return $wpdb->get_results($wpdb->prepare(
            "SELECT s.nombre, s.color, s.icono, COUNT(c.id) as total
             FROM {$this->tabla_clicks} c
             JOIN {$this->tabla_socials} s ON c.social_id = s.id
             GROUP BY c.social_id
             ORDER BY total DESC
             LIMIT %d",
            $limite
        ));
    }

    // CLICS POR DÍA (últimos 7 días)
    public function clics_por_dia() {
        global $wpdb;
        return $wpdb->get_results(
            "SELECT DATE(fecha_click) as dia, COUNT(*) as total
             FROM {$this->tabla_clicks}
             WHERE fecha_click >= DATE_SUB(NOW(), INTERVAL 7 DAY)
             GROUP BY DATE(fecha_click)
             ORDER BY dia ASC"
        );
    }

    // CLICS POR DISPOSITIVO
    public function clics_por_dispositivo() {
        global $wpdb;
        return $wpdb->get_results(
            "SELECT dispositivo, COUNT(*) as total
             FROM {$this->tabla_clicks}
             GROUP BY dispositivo
             ORDER BY total DESC"
        );
    }

    // CLICS POR HORA
public function clics_por_hora() {
    global $wpdb;
    return $wpdb->get_results(
        "SELECT HOUR(fecha_click) as hora, COUNT(*) as total
         FROM {$this->tabla_clicks}
         GROUP BY HOUR(fecha_click)
         ORDER BY hora ASC"
    );
}

    // CLICS POR RED FILTRADO POR FECHA
    public function clics_por_red($desde = null, $hasta = null) {
        global $wpdb;
        $where = '';
        if ($desde && $hasta) {
            $where = $wpdb->prepare(
                "WHERE DATE(c.fecha_click) BETWEEN %s AND %s",
                $desde, $hasta
            );
        }
        return $wpdb->get_results(
            "SELECT s.nombre, s.color, COUNT(c.id) as total
             FROM {$this->tabla_clicks} c
             JOIN {$this->tabla_socials} s ON c.social_id = s.id
             {$where}
             GROUP BY c.social_id
             ORDER BY total DESC"
        );
    }

        // CLICS POR NAVEGADOR
    public function clics_por_navegador() {
        global $wpdb;
        return $wpdb->get_results(
            "SELECT navegador, COUNT(*) as total
            FROM {$this->tabla_clicks}
            GROUP BY navegador
            ORDER BY total DESC"
        );
    }
}