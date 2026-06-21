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
        add_action('wp_head', array($this, 'generar_css_dinamico'));
    }

    public function cargar_assets_publicos() {
        wp_enqueue_style('dashicons');

        wp_enqueue_style(
            'fontawesome',
            'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css',
            array(),
            '6.5.2'
        );

        wp_enqueue_style('gsa-frontend-css', GSA_URL . 'public/css/gsa-frontend.css', array(), GSA_VERSION);

        wp_enqueue_script(
            'gsa-tracker-js',
            GSA_URL . 'public/js/gsa-tracker.js',
            array(),
            GSA_VERSION,
            true
        );

        wp_localize_script('gsa-tracker-js', 'gsaTracker', array(
            'ajaxurl' => admin_url('admin-ajax.php'),
            'nonce'   => wp_create_nonce('gsa_nonce_tracker'),
        ));
    }

public function pintar_botones_flotantes() {
    $settings = new GSA_Settings_Model();
    if ($settings->obtener('widget_activo', '1') !== '1') return;

    global $wpdb;
    $botones = $wpdb->get_results("SELECT * FROM {$this->table_name} WHERE estado = 1 ORDER BY orden ASC");

    if (empty($botones)) return;

    echo '<div class="gsa-floating-wrapper">';
    foreach ($botones as $btn) {

        $url = $btn->url;

        // Si es correo, usar mailto:
        if ($btn->nombre === 'Correo') {
            $url    = 'mailto:' . trim($btn->url);
            $target = '';
        } else {
            $target = 'target="_blank" rel="noopener noreferrer"';
        }

        echo sprintf(
            '<a href="%s" %s
                class="gsa-btn"
                data-id="%d"
                data-tooltip="%s"
                style="background-color: %s;">
                <i class="%s"></i>
             </a>',
            esc_attr($url),
            $target,
            intval($btn->id),
            esc_attr($btn->nombre),
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

    public function generar_css_dinamico() {
        $settings = new GSA_Settings_Model();

        $tamano         = intval($settings->obtener('ap_tamano',         46));
        $tamano_movil   = intval($settings->obtener('ap_tamano_movil',   38));
        $forma          = $settings->obtener('ap_forma',          'circular');
        $sombra         = $settings->obtener('ap_sombra',         'media');
        $espaciado      = intval($settings->obtener('ap_espaciado',      8));
        $animaciones    = $settings->obtener('ap_animaciones',    '1');
        $tipo_animacion = $settings->obtener('ap_tipo_animacion', 'entrada-derecha');
        $hover          = $settings->obtener('ap_hover',          '1');
        $posicion       = $settings->obtener('ap_posicion',       'bottom-right');
        $orientacion    = $settings->obtener('ap_orientacion',    'vertical');
        $distancia      = intval($settings->obtener('ap_distancia', 6));

        $radios = array(
            'circular'   => '50%',
            'redondeado' => '12px',
            'cuadrado'   => '4px',
        );
        $border_radius = $radios[$forma] ?? '50%';

        $sombras = array(
            'ninguna' => 'none',
            'suave'   => '0 2px 6px rgba(0,0,0,0.15)',
            'media'   => '0 3px 10px rgba(26,58,107,0.25)',
            'fuerte'  => '0 6px 20px rgba(26,58,107,0.45)',
        );
        $box_shadow = $sombras[$sombra] ?? '0 3px 10px rgba(26,58,107,0.25)';

        $pos_css = array(
            'top-left'      => "top:{$distancia}px;left:{$distancia}px;bottom:auto;right:auto;",
            'top-center'    => "top:{$distancia}px;left:50%;transform:translateX(-50%);bottom:auto;right:auto;",
            'top-right'     => "top:{$distancia}px;right:{$distancia}px;bottom:auto;left:auto;",
            'middle-left'   => "top:50%;left:{$distancia}px;transform:translateY(-50%);bottom:auto;right:auto;",
            'middle-right'  => "top:50%;right:{$distancia}px;transform:translateY(-50%);bottom:auto;left:auto;",
            'bottom-left'   => "bottom:{$distancia}px;left:{$distancia}px;top:auto;right:auto;",
            'bottom-center' => "bottom:{$distancia}px;left:50%;transform:translateX(-50%);top:auto;right:auto;",
            'bottom-right'  => "bottom:{$distancia}px;right:{$distancia}px;top:auto;left:auto;",
        );
        $posicion_css = $pos_css[$posicion] ?? "bottom:{$distancia}px;right:{$distancia}px;top:auto;left:auto;";

        $flex_dir = $orientacion === 'horizontal' ? 'row' : 'column';

        // Animaciones
        $keyframes = '';
        $anim_css  = 'animation:none;';
        if ($animaciones === '1' && $tipo_animacion !== 'ninguna') {
            switch ($tipo_animacion) {
                case 'entrada-derecha':
                    $keyframes = '@keyframes gsaEntrada{from{opacity:0;transform:translateX(20px) scale(0.8)}to{opacity:1;transform:translateX(0) scale(1)}}';
                    break;
                case 'entrada-abajo':
                    $keyframes = '@keyframes gsaEntrada{from{opacity:0;transform:translateY(20px) scale(0.8)}to{opacity:1;transform:translateY(0) scale(1)}}';
                    break;
                case 'fade':
                    $keyframes = '@keyframes gsaEntrada{from{opacity:0}to{opacity:1}}';
                    break;
                case 'rebote':
                    $keyframes = '@keyframes gsaEntrada{0%{opacity:0;transform:scale(0.3)}50%{transform:scale(1.1)}70%{transform:scale(0.95)}100%{opacity:1;transform:scale(1)}}';
                    break;
            }
            $anim_css = 'animation:gsaEntrada 0.4s ease both;';
        }

        $tema = $settings->obtener('ap_tema', 'original');

        $tema_css = '';
        switch ($tema) {
            case 'oscuro':
                $tema_css = '.gsa-btn { background-color: #2d2d2d !important; } .gsa-btn i { color: #ffffff !important; }';
                break;
            case 'claro':
                $tema_css = '.gsa-btn { background-color: #ffffff !important; box-shadow: 0 2px 8px rgba(0,0,0,0.15) !important; } .gsa-btn i { color: #333333 !important; }';
                break;
            case 'gica':
                $tema_css = '
                    .gsa-btn:nth-child(odd)  { background-color: #1a3a6b !important; }
                    .gsa-btn:nth-child(even) { background-color: #f47920 !important; }
                    .gsa-btn i { color: #ffffff !important; }
                ';
                break;
            default:
                $tema_css = '';
                break;
        }

        $hover_css = $hover === '1'
            ? '.gsa-btn:hover{transform:scale(1.15) translateY(-2px) !important;box-shadow:0 6px 18px rgba(26,58,107,0.45) !important;}'
            : '.gsa-btn:hover{transform:none !important;}';
        $tooltip_css = ($posicion === 'bottom-left' || $posicion === 'top-left' || $posicion === 'middle-left')
            ? '.gsa-btn::before { right: auto; left: calc(100% + 8px); }'
            : ($posicion === 'top-center' || $posicion === 'bottom-center'
                ? '.gsa-btn::before { right: auto; left: 50%; transform: translateX(-50%) translateY(-140%); top: 0; }'
                : '.gsa-btn::before { left: auto; right: calc(100% + 8px); }');
        echo "<style>
            {$keyframes}
            .gsa-floating-wrapper { {$posicion_css} gap:{$espaciado}px; flex-direction:{$flex_dir}; }
            .gsa-btn { width:{$tamano}px; height:{$tamano}px; border-radius:{$border_radius}; box-shadow:{$box_shadow}; {$anim_css} }
            .gsa-btn:nth-child(1){animation-delay:0.05s}
            .gsa-btn:nth-child(2){animation-delay:0.10s}
            .gsa-btn:nth-child(3){animation-delay:0.15s}
            .gsa-btn:nth-child(4){animation-delay:0.20s}
            .gsa-btn:nth-child(5){animation-delay:0.25s}
            {$tema_css}
            {$hover_css}
            {$tooltip_css}
                    @media(max-width:768px){
                        .gsa-btn { width:{$tamano_movil}px; height:{$tamano_movil}px; }
                        .gsa-floating-wrapper { gap:" . max(0, $espaciado - 2) . "px; }
                    }
                </style>";

    }

}