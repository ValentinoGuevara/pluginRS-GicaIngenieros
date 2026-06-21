<?php
if (!defined('ABSPATH')) exit;

class GSA_Settings_Model {
    private $tabla;

    public function __construct() {
        global $wpdb;
        $this->tabla = $wpdb->prefix . 'gsa_settings';
    }

    // OBTENER UN VALOR
    public function obtener($clave, $default = '') {
        global $wpdb;
        $resultado = $wpdb->get_var($wpdb->prepare(
            "SELECT valor FROM {$this->tabla} WHERE clave = %s", $clave
        ));
        return $resultado !== null ? $resultado : $default;
    }

    // GUARDAR O ACTUALIZAR UN VALOR
    public function guardar($clave, $valor) {
        global $wpdb;
        $existe = $wpdb->get_var($wpdb->prepare(
            "SELECT id FROM {$this->tabla} WHERE clave = %s", $clave
        ));

        if ($existe) {
            return $wpdb->update(
                $this->tabla,
                array('valor' => $valor),
                array('clave' => $clave),
                array('%s'),
                array('%s')
            );
        } else {
            return $wpdb->insert(
                $this->tabla,
                array('clave' => $clave, 'valor' => $valor),
                array('%s', '%s')
            );
        }
    }

    // OBTENER TODAS LAS CONFIGURACIONES
    public function obtener_todas() {
        global $wpdb;
        $resultados = $wpdb->get_results("SELECT clave, valor FROM {$this->tabla}");
        $config = array();
        foreach ($resultados as $row) {
            $config[$row->clave] = $row->valor;
        }
        return $config;
    }
}