<?php
if (!defined('ABSPATH')) exit;

class GSA_Reporter_Model {
    private $tabla_clicks;
    private $tabla_socials;

    public function __construct() {
        global $wpdb;
        $this->tabla_clicks  = $wpdb->prefix . 'gsa_clicks';
        $this->tabla_socials = $wpdb->prefix . 'gsa_socials';
    }

    // REPORTE GENERAL DE CLICS
    public function reporte_general($desde = null, $hasta = null) {
        global $wpdb;
        $where = $this->filtro_fecha($desde, $hasta);
        return $wpdb->get_results(
            "SELECT s.nombre, s.color, s.icono, COUNT(c.id) as total
             FROM {$this->tabla_clicks} c
             JOIN {$this->tabla_socials} s ON c.social_id = s.id
             {$where}
             GROUP BY c.social_id
             ORDER BY total DESC"
        );
    }

    // REPORTE POR DISPOSITIVO
    public function reporte_dispositivos($desde = null, $hasta = null) {
        global $wpdb;
        $where = $this->filtro_fecha($desde, $hasta);
        return $wpdb->get_results(
            "SELECT dispositivo, COUNT(*) as total
             FROM {$this->tabla_clicks}
             {$where}
             GROUP BY dispositivo
             ORDER BY total DESC"
        );
    }

    // REPORTE POR HORARIO
    public function reporte_horarios($desde = null, $hasta = null) {
        global $wpdb;
        $where = $this->filtro_fecha($desde, $hasta);
        return $wpdb->get_results(
            "SELECT HOUR(fecha_click) as hora, COUNT(*) as total
             FROM {$this->tabla_clicks}
             {$where}
             GROUP BY HOUR(fecha_click)
             ORDER BY hora ASC"
        );
    }

    // REPORTE POR NAVEGADOR
    public function reporte_navegadores($desde = null, $hasta = null) {
        global $wpdb;
        $where = $this->filtro_fecha($desde, $hasta);
        return $wpdb->get_results(
            "SELECT navegador, COUNT(*) as total
             FROM {$this->tabla_clicks}
             {$where}
             GROUP BY navegador
             ORDER BY total DESC"
        );
    }

    // REPORTE PÁGINAS MÁS ACTIVAS
    public function reporte_paginas($desde = null, $hasta = null) {
        global $wpdb;
        $where = $this->filtro_fecha($desde, $hasta);
        return $wpdb->get_results(
            "SELECT pagina, COUNT(*) as total
             FROM {$this->tabla_clicks}
             {$where}
             GROUP BY pagina
             ORDER BY total DESC
             LIMIT 10"
        );
    }

    // HELPER: FILTRO DE FECHA
    private function filtro_fecha($desde, $hasta) {
        global $wpdb;
        if ($desde && $hasta) {
            return $wpdb->prepare(
                "WHERE DATE(fecha_click) BETWEEN %s AND %s",
                $desde, $hasta
            );
        }
        return '';
    }

    // TOTAL DE CLICS
    public function total_clics($desde = null, $hasta = null) {
        global $wpdb;
        $where = $this->filtro_fecha($desde, $hasta);
        return intval($wpdb->get_var(
            "SELECT COUNT(*) FROM {$this->tabla_clicks} {$where}"
        ));
    }
}