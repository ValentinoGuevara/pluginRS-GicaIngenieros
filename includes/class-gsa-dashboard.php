<?php
if (!defined('ABSPATH')) exit;

class GSA_Dashboard {
    private $model;

    public function __construct() {
        $this->model = new GSA_Analytics_Model();
    }

    public function get_stats() {
        return array(
            'total_clics'   => $this->model->total_clics(),
            'clics_hoy'     => $this->model->clics_hoy(),
            'clics_semana'  => $this->model->clics_semana(),
            'clics_mes'     => $this->model->clics_mes(),
        );
    }

    public function get_redes_mas_utilizadas() {
        return $this->model->redes_mas_utilizadas();
    }

    public function get_clics_por_dia() {
        return $this->model->clics_por_dia();
    }

    public function get_clics_por_dispositivo() {
        return $this->model->clics_por_dispositivo();
    }

    public function get_clics_por_hora() {
        return $this->model->clics_por_hora();
    }

    public function get_clics_por_red($desde = null, $hasta = null) {
        return $this->model->clics_por_red($desde, $hasta);
    }
    public function get_clics_por_navegador() {
        return $this->model->clics_por_navegador();
    }
}