<?php
if (!defined('ABSPATH')) exit;

class GSA_Admin {
    private $table_name;

    public function __construct() {
        global $wpdb;
        $this->table_name = $wpdb->prefix . 'gsa_socials';

        add_action('admin_menu', array($this, 'crear_menu_gsa'));
    }

    public function crear_menu_gsa() {
        add_menu_page(
            'GICA Social Analytics',
            'GICA Analytics',
            'manage_options',
            'gsa-panel',
            array($this, 'render_controlador_panel'),
            'dashicons-chart-share',
            25
        );
    }

    public function render_controlador_panel() {
        if (!current_user_can('manage_options')) {
            wp_die(__('Acceso denegado por políticas de seguridad.'));
        }

        global $wpdb;
        $notificacion = '';
        $red_a_editar = null; // Guardará los datos de la red si estamos editando

        // -------------------------------------------------------------------------
        // ACCIÓN 1: PROCESAR REGISTRO O ACTUALIZACIÓN (FORMULARIO POST)
        // -------------------------------------------------------------------------
        if (isset($_POST['gsa_guardar_red_submit']) && check_admin_referer('gsa_nonce_formulario', 'gsa_nonce_campo')) {
            $nombre = sanitize_text_field($_POST['nombre']);
            $url    = esc_url_raw($_POST['url']);
            $icono  = sanitize_text_field($_POST['icono']);
            $color  = sanitize_hex_color($_POST['color']);
            $id     = isset($_POST['red_id']) ? intval($_POST['red_id']) : 0;

            if ($id > 0) {
                // Es una actualización (EDITAR)
                $wpdb->update(
                    $this->table_name,
                    array('nombre' => $nombre, 'url' => $url, 'icono' => $icono, 'color' => $color),
                    array('id' => $id),
                    array('%s', '%s', '%s', '%s'),
                    array('%d')
                );
                $notificacion = '<div class="notice notice-success is-dismissible"><p>¡Red social actualizada con éxito!</p></div>';
            } else {
                // Es un registro nuevo (CREAR)
                $wpdb->insert(
                    $this->table_name,
                    array('nombre' => $nombre, 'url' => $url, 'icono' => $icono, 'color' => $color, 'estado' => 1),
                    array('%s', '%s', '%s', '%s', '%d')
                );
                $notificacion = '<div class="notice notice-success is-dismissible"><p>¡Red social registrada correctamente!</p></div>';
            }
        }

        // -------------------------------------------------------------------------
        // ACCIÓN 2: ALTERNAR ESTADO (ACTIVO/INACTIVO VÍA GET)
        // -------------------------------------------------------------------------
        if (isset($_GET['action']) && $_GET['action'] === 'toggle' && isset($_GET['id'])) {
            $id = intval($_GET['id']);
            if (check_admin_referer('gsa_toggle_' . $id)) {
                $estado_actual = $wpdb->get_var($wpdb->prepare("SELECT estado FROM {$this->table_name} WHERE id = %d", $id));
                $nuevo_estado  = ($estado_actual == 1) ? 0 : 1;

                $wpdb->update($this->table_name, array('estado' => $nuevo_estado), array('id' => $id), array('%d'), array('%d'));
                $notificacion = '<div class="notice notice-success is-dismissible"><p>Estado de la red social actualizado.</p></div>';
            }
        }

        // -------------------------------------------------------------------------
        // ACCIÓN 3: ELIMINAR REGISTRO (VÍA GET)
        // -------------------------------------------------------------------------
        if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['id'])) {
            $id = intval($_GET['id']);
            if (check_admin_referer('gsa_delete_' . $id)) {
                $wpdb->delete($this->table_name, array('id' => $id), array('%d'));
                $notificacion = '<div class="notice notice-warning is-dismissible"><p>Red social eliminada del sistema.</p></div>';
            }
        }

        // -------------------------------------------------------------------------
        // ACCIÓN 4: SELECCIONAR PARA EDICIÓN (VÍA GET)
        // -------------------------------------------------------------------------
        if (isset($_GET['action']) && $_GET['action'] === 'edit' && isset($_GET['id'])) {
            $id = intval($_GET['id']);
            if (check_admin_referer('gsa_edit_' . $id)) {
                $red_a_editar = $wpdb->get_row($wpdb->prepare("SELECT * FROM {$this->table_name} WHERE id = %d", $id));
            }
        }

        // Obtener la colección completa actualizada de redes para la tabla de la vista
        $redes_registradas = $wpdb->get_results("SELECT * FROM {$this->table_name} ORDER BY orden ASC");

        // Llamar a la vista aislada enviándole los datos
        include GSA_PATH . 'admin/views/panel-redes.php';
    }
}