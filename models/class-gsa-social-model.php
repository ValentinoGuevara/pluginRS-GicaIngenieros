<?php
if (!defined('ABSPATH')) exit;

class GSA_Social_Model {
    private $tabla;

    public function __construct() {
        global $wpdb;
        $this->tabla = $wpdb->prefix . 'gsa_socials';
    }

    // OBTENER TODAS LAS REDES
    public function obtener_todas() {
        global $wpdb;
        return $wpdb->get_results("SELECT * FROM {$this->tabla} ORDER BY orden ASC");
    }

    // OBTENER UNA RED POR ID
    public function obtener_por_id($id) {
        global $wpdb;
        return $wpdb->get_row($wpdb->prepare("SELECT * FROM {$this->tabla} WHERE id = %d", intval($id)));
    }

    // CREAR RED NUEVA
        public function crear($datos) {
            global $wpdb;

            $ultimo_orden = $wpdb->get_var(
                "SELECT MAX(orden) FROM {$this->tabla}"
            );

            $nuevo_orden = ($ultimo_orden !== null)
                ? intval($ultimo_orden) + 1
                : 1;

            return $wpdb->insert(
                $this->tabla,
                array(
                    'nombre' => $datos['nombre'],
                    'url'    => $datos['url'],
                    'icono'  => $datos['icono'],
                    'color'  => $datos['color'],
                    'orden'  => $nuevo_orden,
                    'estado' => 1
                ),
                array('%s', '%s', '%s', '%s', '%d', '%d')
            );
        }

    // ACTUALIZAR RED EXISTENTE
    public function actualizar($id, $datos) {
        global $wpdb;
        return $wpdb->update(
            $this->tabla,
            array(
                'nombre' => $datos['nombre'],
                'url'    => $datos['url'],
                'icono'  => $datos['icono'],
                'color'  => $datos['color']
            ),
            array('id' => intval($id)),
            array('%s', '%s', '%s', '%s'),
            array('%d')
        );
    }

    // ELIMINAR RED
    public function eliminar($id) {
        global $wpdb;
        return $wpdb->delete(
            $this->tabla,
            array('id' => intval($id)),
            array('%d')
        );
    }

    // ALTERNAR ESTADO ACTIVO/INACTIVO
    public function alternar_estado($id) {
        global $wpdb;
        $estado_actual = $wpdb->get_var(
            $wpdb->prepare("SELECT estado FROM {$this->tabla} WHERE id = %d", intval($id))
        );
        $nuevo_estado = ($estado_actual == 1) ? 0 : 1;
        return $wpdb->update(
            $this->tabla,
            array('estado' => $nuevo_estado),
            array('id'     => intval($id)),
            array('%d'),
            array('%d')
        );
    }

    // OBTENER SOLO REDES ACTIVAS (para el frontend)
    public function obtener_activas() {
        global $wpdb;
        return $wpdb->get_results("SELECT * FROM {$this->tabla} WHERE estado = 1 ORDER BY orden ASC");
    }

    // ACTUALIZAR ORDEN DE REDES
    public function actualizar_orden($orden) {
        global $wpdb;
        foreach ($orden as $posicion => $id) {
            $wpdb->query($wpdb->prepare(
                "UPDATE {$this->tabla} SET orden = %d WHERE id = %d",
                intval($posicion) + 1,
                intval($id)
            ));
        }
        return true;
    }
}