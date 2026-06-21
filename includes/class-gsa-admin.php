<?php
if (!defined('ABSPATH')) exit;

class GSA_Admin {
    private $model;

    public function __construct() {
        $this->model = new GSA_Social_Model();
        add_action('admin_menu', array($this, 'crear_menu_gsa'));
        add_action('admin_enqueue_scripts', array($this, 'cargar_estilos_admin'));
        add_filter('admin_footer_text', '__return_empty_string');
        add_filter('update_footer', '__return_empty_string', 11);
        add_action('wp_ajax_gsa_actualizar_orden', array($this, 'ajax_actualizar_orden'));
        add_action('admin_post_gsa_exportar_reporte', array($this, 'exportar_reporte'));
    }

    public function cargar_estilos_admin($hook) {
        $paginas_validas = array(
            'toplevel_page_gsa-panel',
            'gica-analytics_page_gsa-dashboard',
            'gica-analytics_page_gsa-reportes',
            'gica-analytics_page_gsa-configuracion',
            'gica-analytics_page_gsa-apariencia',
        );

        if (!in_array($hook, $paginas_validas)) return;

        wp_enqueue_style('gsa-admin-styles', GSA_URL . 'admin/css/gsa-admin-styles.css', array(), GSA_VERSION);
        wp_enqueue_script('sortablejs', 'https://cdn.jsdelivr.net/npm/sortablejs@1.15.2/Sortable.min.js', array(), '1.15.2', true);
        wp_enqueue_script('chartjs', 'https://cdn.jsdelivr.net/npm/chart.js@4.4.2/dist/chart.umd.min.js', array(), '4.4.2', true);
        wp_enqueue_script('gsa-charts', GSA_URL . 'admin/js/gsa-charts.js', array('chartjs'), GSA_VERSION, true);
    }
    
    public function ajax_actualizar_orden() {
        check_ajax_referer('gsa_nonce_orden', 'nonce');

        if (!current_user_can('manage_options')) {
            wp_send_json_error('Sin permisos.');
        }

        $orden = isset($_POST['orden']) ? array_map('intval', $_POST['orden']) : array();

        if (empty($orden)) {
            wp_send_json_error('Orden vacío.');
        }

        $resultado = $this->model->actualizar_orden($orden);
        $resultado ? wp_send_json_success('Orden guardado.') : wp_send_json_error('Error al guardar.');
    }

    public function crear_menu_gsa() {
        // Menú principal
        add_menu_page(
            'GICA Social Analytics',
            'GICA Analytics',
            'manage_options',
            'gsa-panel',
            array($this, 'render_controlador_panel'),
            'dashicons-chart-line',
            25
        );

        // Submenú 1 — Redes Sociales
        add_submenu_page(
            'gsa-panel',
            'Redes Sociales',
            'Redes Sociales',
            'manage_options',
            'gsa-panel',
            array($this, 'render_controlador_panel')
        );

        // Submenú 2 — Dashboard
        add_submenu_page(
            'gsa-panel',
            'Dashboard Analítico',
            'Dashboard',
            'manage_options',
            'gsa-dashboard',
            array($this, 'render_dashboard')
        );

        // Submenú 3 — Reportes
        add_submenu_page(
            'gsa-panel',
            'Reportes',
            'Reportes',
            'manage_options',
            'gsa-reportes',
            array($this, 'render_reportes')
        );

        // Submenú 4 — Apariencia
        add_submenu_page(
            'gsa-panel',
            'Apariencia',
            'Apariencia',
            'manage_options',
            'gsa-apariencia',
            array($this, 'render_apariencia')
        );

        // Submenú 5 — Configuración
        add_submenu_page(
            'gsa-panel',
            'Configuración',
            'Configuración',
            'manage_options',
            'gsa-configuracion',
            array($this, 'render_configuracion')
        );
    }

    public function render_dashboard() {
        $dashboard         = new GSA_Dashboard();
        $stats             = $dashboard->get_stats();
        $redes_top         = $dashboard->get_redes_mas_utilizadas();
        $clics_por_dia     = $dashboard->get_clics_por_dia();
        $clics_dispositivo = $dashboard->get_clics_por_dispositivo();
        $clics_hora        = $dashboard->get_clics_por_hora();

        $desde = isset($_GET['desde']) ? sanitize_text_field($_GET['desde']) : null;
        $hasta = isset($_GET['hasta']) ? sanitize_text_field($_GET['hasta']) : null;
        $clics_por_red = $dashboard->get_clics_por_red($desde, $hasta);
        $clics_navegador = $dashboard->get_clics_por_navegador();

        include GSA_PATH . 'admin/views/dashboard-ui.php';
    }

    public function render_configuracion() {
        $settings_model = new GSA_Settings_Model();
        $config         = $settings_model->obtener_todas();
        $notificacion   = '';

        if (isset($_POST['gsa_guardar_config']) && check_admin_referer('gsa_nonce_config', 'gsa_nonce_config_campo')) {
            $campos = array(
                'site_nombre'          => sanitize_text_field($_POST['site_nombre'] ?? ''),
                'site_descripcion'     => sanitize_text_field($_POST['site_descripcion'] ?? ''),
                'idioma'               => sanitize_text_field($_POST['idioma'] ?? 'es'),
                'zona_horaria'         => sanitize_text_field($_POST['zona_horaria'] ?? 'America/Lima'),
                'widget_activo'        => isset($_POST['widget_activo']) ? '1' : '0',
                'tracker_activo'       => isset($_POST['tracker_activo']) ? '1' : '0',
            );

            foreach ($campos as $clave => $valor) {
                $settings_model->guardar($clave, $valor);
            }

            $config       = $settings_model->obtener_todas();
            $notificacion = '<div class="gsa-notice gsa-notice--success">✔ Configuración guardada correctamente.</div>';
        }

        include GSA_PATH . 'admin/views/configuracion-ui.php';
    }
    public function render_controlador_panel() {
        if (!current_user_can('manage_options')) {
            wp_die(__('Acceso denegado por políticas de seguridad.'));
        }

        $notificacion = '';
        $red_a_editar = null;

        // -------------------------------------------------------------------------
        // ACCIÓN 1: PROCESAR REGISTRO O ACTUALIZACIÓN (FORMULARIO POST)
        // -------------------------------------------------------------------------
        if (isset($_POST['gsa_guardar_red_submit']) && check_admin_referer('gsa_nonce_formulario', 'gsa_nonce_campo')) {
            $url = esc_url_raw($_POST['url']); // por defecto

            // Solo para Correo
            if (isset($_POST['nombre']) && $_POST['nombre'] === 'Correo') {
                $email = sanitize_email($_POST['url']);
                $url   = 'mailto:' . $email;
            }

            $nombre = sanitize_text_field($_POST['nombre']);
            $icono  = sanitize_text_field($_POST['icono']);

            if (empty($nombre) || empty($icono)) {
                $notificacion = '<div class="gsa-notice gsa-notice--warning">⚠ Debe completar el nombre e icono de la red social.</div>';
            } else {

            $datos = array(
                'nombre' => $nombre,
                'url'    => $url,
                'icono'  => $icono,
                'color'  => sanitize_hex_color($_POST['color']),
            );
            $id = isset($_POST['red_id']) ? intval($_POST['red_id']) : 0;

            if ($id > 0) {
                $this->model->actualizar($id, $datos);
                $notificacion = '<div class="gsa-notice gsa-notice--success">✔ Red social actualizada con éxito.</div>';
            } else {
                $this->model->crear($datos);
                $notificacion = '<div class="gsa-notice gsa-notice--success">✔ Red social registrada correctamente.</div>';
            }
        }
    }

        // -------------------------------------------------------------------------
        // ACCIÓN 2: ALTERNAR ESTADO (ACTIVO/INACTIVO VÍA GET)
        // -------------------------------------------------------------------------
        if (isset($_GET['action']) && $_GET['action'] === 'toggle' && isset($_GET['id'])) {
            $id = intval($_GET['id']);
            if (check_admin_referer('gsa_toggle_' . $id)) {
                $this->model->alternar_estado($id);
                $notificacion = '<div class="gsa-notice gsa-notice--success">✔ Estado de la red social actualizado.</div>';
                add_action('admin_footer', function() {
                    echo '<script>
                        if (window.history.replaceState) {
                            window.history.replaceState(null, null, "' . admin_url('admin.php?page=gsa-panel') . '");
                        }
                    </script>';
                });
            }
        }

        // -------------------------------------------------------------------------
        // ACCIÓN 3: ELIMINAR REGISTRO (VÍA GET)
        // -------------------------------------------------------------------------
        if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['id'])) {
            $id = intval($_GET['id']);
            if (check_admin_referer('gsa_delete_' . $id)) {
                $this->model->eliminar($id);
                $notificacion = '<div class="gsa-notice gsa-notice--warning">⚠ Red social eliminada del sistema.</div>';
            }
        }

        // -------------------------------------------------------------------------
        // ACCIÓN 4: SELECCIONAR PARA EDICIÓN (VÍA GET)
        // -------------------------------------------------------------------------
        if (isset($_GET['action']) && $_GET['action'] === 'edit' && isset($_GET['id'])) {
            $id = intval($_GET['id']);
            if (check_admin_referer('gsa_edit_' . $id)) {
                $red_a_editar = $this->model->obtener_por_id($id);
            }
        }

        // Obtener colección completa para la tabla de la vista
        $redes_registradas = $this->model->obtener_todas();

        // Llamar a la vista
        include GSA_PATH . 'admin/views/panel-redes.php';
    }

    public function exportar_reporte() {
        if (!current_user_can('manage_options')) {
            wp_die('Sin permisos.');
        }

        check_admin_referer('gsa_nonce_reporte', 'gsa_nonce_reporte_campo');

        $tipo   = isset($_POST['tipo'])   ? sanitize_text_field($_POST['tipo'])   : 'general';
        $desde  = isset($_POST['desde'])  ? sanitize_text_field($_POST['desde'])  : null;
        $hasta  = isset($_POST['hasta'])  ? sanitize_text_field($_POST['hasta'])  : null;
        $formato = isset($_POST['formato']) ? sanitize_text_field($_POST['formato']) : 'pdf';

        $reporter = new GSA_Reporter();

        switch ($formato) {
            case 'pdf':   $reporter->generar_pdf($tipo, $desde, $hasta);   break;
            case 'excel': $reporter->generar_excel($tipo, $desde, $hasta); break;
            case 'csv':   $reporter->generar_csv($tipo, $desde, $hasta);   break;
        }
    }

    public function render_reportes() {
        include GSA_PATH . 'admin/views/reportes-ui.php';
    }

    public function render_apariencia() {
    $settings_model = new GSA_Settings_Model();
    $config         = $settings_model->obtener_todas();
    $notificacion   = '';

    if (isset($_POST['gsa_guardar_apariencia']) && check_admin_referer('gsa_nonce_apariencia', 'gsa_nonce_apariencia_campo')) {
        $campos = array(
            'ap_posicion'       => sanitize_text_field($_POST['ap_posicion']    ?? 'bottom-right'),
            'ap_tamano'         => intval($_POST['ap_tamano']                   ?? 46),
            'ap_tamano_movil'   => intval($_POST['ap_tamano_movil']             ?? 38),
            'ap_forma'          => sanitize_text_field($_POST['ap_forma']       ?? 'circular'),
            'ap_sombra'         => sanitize_text_field($_POST['ap_sombra']      ?? 'media'),
            'ap_espaciado'      => intval($_POST['ap_espaciado']                ?? 8),
            'ap_animaciones'    => isset($_POST['ap_animaciones'])    ? '1' : '0',
            'ap_tipo_animacion' => sanitize_text_field($_POST['ap_tipo_animacion'] ?? 'entrada-derecha'),
            'ap_hover'          => isset($_POST['ap_hover'])          ? '1' : '0',
            'ap_orientacion'    => sanitize_text_field($_POST['ap_orientacion'] ?? 'vertical'),
            'ap_distancia'      => intval($_POST['ap_distancia']                ?? 6),
            'ap_tema' => sanitize_text_field($_POST['ap_tema'] ?? 'original'),
        );

        foreach ($campos as $clave => $valor) {
            $settings_model->guardar($clave, $valor);
        }

        $config       = $settings_model->obtener_todas();
        $notificacion = '<div class="gsa-notice gsa-notice--success">✔ Apariencia guardada correctamente.</div>';
    }

        include GSA_PATH . 'admin/views/apariencia-ui.php';
    }
    
}