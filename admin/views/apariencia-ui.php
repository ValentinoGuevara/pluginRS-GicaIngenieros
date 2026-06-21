<?php if (!defined('ABSPATH')) exit;
/** @var array $config */
/** @var string $notificacion */

$ap_posicion     = $config['ap_posicion']     ?? 'bottom-right';
$ap_tamano       = $config['ap_tamano']       ?? 46;
$ap_tamano_movil = $config['ap_tamano_movil'] ?? 38;
$ap_forma        = $config['ap_forma']        ?? 'circular';
$ap_sombra       = $config['ap_sombra']       ?? 'media';
$ap_espaciado    = $config['ap_espaciado']    ?? 8;
$ap_animaciones  = $config['ap_animaciones']  ?? '1';
$ap_hover        = $config['ap_hover']        ?? '1';
$ap_tipo_animacion = $config['ap_tipo_animacion'] ?? 'entrada-derecha';
$ap_orientacion    = $config['ap_orientacion']    ?? 'vertical';
$ap_distancia      = $config['ap_distancia']      ?? 6;
?>

<div class="wrap">

    <!-- TOP BAR -->
    <div class="gsa-top-bar">
        <div class="gsa-top-bar-left">
            <img src="<?php echo esc_url(GSA_URL . 'admin/images/logo.png'); ?>"
                 alt="Logo GICA Ingenieros"
                 class="gsa-logo-img">
            <div class="gsa-header-divider"></div>
            <div class="gsa-header-info">
                <h1>Apariencia del Widget</h1>
                <p>Personaliza el diseño visual de los botones sociales flotantes.</p>
            </div>
        </div>
        <span class="gsa-badge-modulo">
            <span class="dashicons dashicons-art"></span>
            Apariencia
        </span>
    </div>

    <?php if (!empty($notificacion)) echo $notificacion; ?>

    <div style="display:grid;grid-template-columns:1fr 340px;gap:20px;align-items:start;">

        <!-- COLUMNA IZQUIERDA: CONTROLES -->
        <div>
            <form method="POST" action="">
                <?php wp_nonce_field('gsa_nonce_apariencia', 'gsa_nonce_apariencia_campo'); ?>

                <!-- POSICIÓN -->
                <div class="gsa-form-card" style="margin-bottom:20px;">
                    <div class="gsa-form-header">
                        <div class="gsa-form-header-icon">
                            <span class="dashicons dashicons-move"></span>
                        </div>
                        <div>
                            <h2>Posición del widget</h2>
                            <span>Dónde aparecen los botones en el sitio.</span>
                        </div>
                    </div>
                    <div style="padding:22px;">
                        <div class="gsa-posicion-grid" style="grid-template-columns:repeat(3,1fr);">
                            <?php
                            $posiciones = array(
                                'top-left'      => 'Superior izq.',
                                'top-center'    => 'Superior centro',
                                'top-right'     => 'Superior der.',
                                'middle-left'   => 'Medio izq.',
                                'middle-right'  => 'Medio der.',
                                'bottom-left'   => 'Inferior izq.',
                                'bottom-center' => 'Inferior centro',
                                'bottom-right'  => 'Inferior der.',
                            );
                            foreach ($posiciones as $val => $label):
                            ?>
                            <label class="gsa-posicion-item <?php echo $ap_posicion === $val ? 'selected' : ''; ?>">
                                <input type="radio" name="ap_posicion" value="<?php echo $val; ?>"
                                    <?php checked($ap_posicion, $val); ?> style="display:none;">
                                <span class="gsa-posicion-label" style="font-size:11px;"><?php echo $label; ?></span>
                            </label>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>

                <!-- TAMAÑO -->
                <div class="gsa-form-card" style="margin-bottom:20px;">
                    <div class="gsa-form-header">
                        <div class="gsa-form-header-icon">
                            <span class="dashicons dashicons-editor-expand"></span>
                        </div>
                        <div>
                            <h2>Tamaño de botones</h2>
                            <span>Tamaño en píxeles para desktop y móvil.</span>
                        </div>
                    </div>
                    <div style="padding:22px;display:grid;grid-template-columns:1fr 1fr;gap:16px;">
                        <div class="gsa-field">
                            <label>Desktop — <span id="labelTamano"><?php echo $ap_tamano; ?></span>px</label>
                            <input type="range" name="ap_tamano" id="sliderTamano"
                                   min="32" max="72" value="<?php echo intval($ap_tamano); ?>"
                                   style="width:100%;accent-color:var(--gsa-azul);">
                        </div>
                        <div class="gsa-field">
                            <label>Móvil — <span id="labelTamanoMovil"><?php echo $ap_tamano_movil; ?></span>px</label>
                            <input type="range" name="ap_tamano_movil" id="sliderTamanoMovil"
                                   min="28" max="56" value="<?php echo intval($ap_tamano_movil); ?>"
                                   style="width:100%;accent-color:var(--gsa-azul);">
                        </div>
                    </div>
                </div>

                <!-- FORMA -->
                <div class="gsa-form-card" style="margin-bottom:20px;">
                    <div class="gsa-form-header">
                        <div class="gsa-form-header-icon">
                            <span class="dashicons dashicons-marker"></span>
                        </div>
                        <div>
                            <h2>Forma de botones</h2>
                            <span>Define la forma del borde de los botones.</span>
                        </div>
                    </div>
                    <div style="padding:22px;display:flex;gap:12px;">
                        <?php
                        $formas = array(
                            'circular'   => array('label' => 'Circular',   'radius' => '50%'),
                            'redondeado' => array('label' => 'Redondeado', 'radius' => '12px'),
                            'cuadrado'   => array('label' => 'Cuadrado',   'radius' => '4px'),
                        );
                        foreach ($formas as $val => $f):
                        ?>
                        <label class="gsa-forma-item <?php echo $ap_forma === $val ? 'selected' : ''; ?>">
                            <input type="radio" name="ap_forma" value="<?php echo $val; ?>"
                                   <?php checked($ap_forma, $val); ?> style="display:none;">
                            <div class="gsa-forma-preview" style="border-radius:<?php echo $f['radius']; ?>;background:var(--gsa-azul);"></div>
                            <span><?php echo $f['label']; ?></span>
                        </label>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- SOMBRA Y ESPACIADO -->
                <div class="gsa-form-card" style="margin-bottom:20px;">
                    <div class="gsa-form-header">
                        <div class="gsa-form-header-icon">
                            <span class="dashicons dashicons-admin-appearance"></span>
                        </div>
                        <div>
                            <h2>Sombra y espaciado</h2>
                            <span>Ajusta la sombra y separación entre botones.</span>
                        </div>
                    </div>
                    <div style="padding:22px;display:grid;grid-template-columns:1fr 1fr;gap:16px;">
                        <div class="gsa-field">
                            <label>Intensidad de sombra</label>
                            <select name="ap_sombra"
                                    style="width:100%;height:38px;padding:0 12px;border:1.5px solid var(--gsa-borde);border-radius:7px;font-size:13px;background:var(--gsa-fondo);color:var(--gsa-texto);outline:none;">
                                <option value="ninguna"  <?php selected($ap_sombra, 'ninguna'); ?>>Sin sombra</option>
                                <option value="suave"    <?php selected($ap_sombra, 'suave'); ?>>Suave</option>
                                <option value="media"    <?php selected($ap_sombra, 'media'); ?>>Media</option>
                                <option value="fuerte"   <?php selected($ap_sombra, 'fuerte'); ?>>Fuerte</option>
                            </select>
                        </div>
                        <div class="gsa-field">
                            <label>Espaciado — <span id="labelEspaciado"><?php echo $ap_espaciado; ?></span>px</label>
                            <input type="range" name="ap_espaciado" id="sliderEspaciado"
                                   min="0" max="20" value="<?php echo intval($ap_espaciado); ?>"
                                   style="width:100%;accent-color:var(--gsa-azul);">
                        </div>
                    </div>
                </div>

                <!-- ANIMACIONES -->
                <div class="gsa-form-card" style="margin-bottom:20px;">
                    <div class="gsa-form-header">
                        <div class="gsa-form-header-icon">
                            <span class="dashicons dashicons-controls-play"></span>
                        </div>
                        <div>
                            <h2>Animaciones</h2>
                            <span>Controla las animaciones del widget.</span>
                        </div>
                    </div>
                    <div style="padding:22px;display:flex;flex-direction:column;gap:14px;">

                        <div style="display:flex;align-items:center;justify-content:space-between;padding:12px 16px;background:var(--gsa-fondo);border-radius:8px;border:1px solid var(--gsa-borde);">
                            <div>
                                <div style="font-size:13px;font-weight:700;color:var(--gsa-texto);">Animación de entrada</div>
                                <div style="font-size:12px;color:var(--gsa-texto-suave);">Los botones aparecen con animación al cargar</div>
                            </div>
                            <label class="gsa-toggle">
                                <input type="checkbox" name="ap_animaciones" value="1" <?php checked($ap_animaciones, '1'); ?>>
                                <span class="gsa-toggle-slider"></span>
                            </label>
                        </div>

                        <div class="gsa-field">
                            <label>Tipo de animación</label>
                            <select name="ap_tipo_animacion"
                                    id="selectAnimacion"
                                    style="width:100%;height:38px;padding:0 12px;border:1.5px solid var(--gsa-borde);border-radius:7px;font-size:13px;background:var(--gsa-fondo);color:var(--gsa-texto);outline:none;">
                                <option value="entrada-derecha" <?php selected($ap_tipo_animacion, 'entrada-derecha'); ?>>Entrada desde la derecha</option>
                                <option value="entrada-abajo"   <?php selected($ap_tipo_animacion, 'entrada-abajo'); ?>>Entrada desde abajo</option>
                                <option value="fade"            <?php selected($ap_tipo_animacion, 'fade'); ?>>Fade (aparecer suave)</option>
                                <option value="rebote"          <?php selected($ap_tipo_animacion, 'rebote'); ?>>Rebote</option>
                                <option value="ninguna"         <?php selected($ap_tipo_animacion, 'ninguna'); ?>>Sin animación</option>
                            </select>
                        </div>

                        <div style="display:flex;align-items:center;justify-content:space-between;padding:12px 16px;background:var(--gsa-fondo);border-radius:8px;border:1px solid var(--gsa-borde);">
                            <div>
                                <div style="font-size:13px;font-weight:700;color:var(--gsa-texto);">Efecto hover</div>
                                <div style="font-size:12px;color:var(--gsa-texto-suave);">Los botones se agrandan al pasar el cursor</div>
                            </div>
                            <label class="gsa-toggle">
                                <input type="checkbox" name="ap_hover" value="1" <?php checked($ap_hover, '1'); ?>>
                                <span class="gsa-toggle-slider"></span>
                            </label>
                        </div>

                    </div>
                </div>

                <!-- ORIENTACIÓN Y DISTANCIA -->
                <div class="gsa-form-card" style="margin-bottom:20px;">
                    <div class="gsa-form-header">
                        <div class="gsa-form-header-icon">
                            <span class="dashicons dashicons-editor-justify"></span>
                        </div>
                        <div>
                            <h2>Orientación y distancia</h2>
                            <span>Disposición de los botones y distancia al borde.</span>
                        </div>
                    </div>
                    <div style="padding:22px;display:grid;grid-template-columns:1fr 1fr;gap:16px;">

                        <div class="gsa-field">
                            <label>Orientación</label>
                            <div style="display:flex;gap:10px;margin-top:6px;">
                                <label class="gsa-posicion-item <?php echo $ap_orientacion === 'vertical' ? 'selected' : ''; ?>" style="flex:1;padding:10px;">
                                    <input type="radio" name="ap_orientacion" value="vertical" <?php checked($ap_orientacion, 'vertical'); ?> style="display:none;">
                                    <div style="display:flex;flex-direction:column;align-items:center;gap:4px;">
                                        <div style="display:flex;flex-direction:column;gap:3px;">
                                            <div style="width:16px;height:16px;background:var(--gsa-azul);border-radius:50%;"></div>
                                            <div style="width:16px;height:16px;background:var(--gsa-naranja);border-radius:50%;"></div>
                                            <div style="width:16px;height:16px;background:#25D366;border-radius:50%;"></div>
                                        </div>
                                        <span class="gsa-posicion-label" style="font-size:11px;">Vertical</span>
                                    </div>
                                </label>
                                <label class="gsa-posicion-item <?php echo $ap_orientacion === 'horizontal' ? 'selected' : ''; ?>" style="flex:1;padding:10px;">
                                    <input type="radio" name="ap_orientacion" value="horizontal" <?php checked($ap_orientacion, 'horizontal'); ?> style="display:none;">
                                    <div style="display:flex;flex-direction:column;align-items:center;gap:4px;">
                                        <div style="display:flex;flex-direction:row;gap:3px;">
                                            <div style="width:16px;height:16px;background:var(--gsa-azul);border-radius:50%;"></div>
                                            <div style="width:16px;height:16px;background:var(--gsa-naranja);border-radius:50%;"></div>
                                            <div style="width:16px;height:16px;background:#25D366;border-radius:50%;"></div>
                                        </div>
                                        <span class="gsa-posicion-label" style="font-size:11px;">Horizontal</span>
                                    </div>
                                </label>
                            </div>
                        </div>

                        <div class="gsa-field">
                            <label>Distancia al borde — <span id="labelDistancia"><?php echo $ap_distancia; ?></span>px</label>
                            <input type="range" name="ap_distancia" id="sliderDistancia"
                                min="0" max="60" value="<?php echo intval($ap_distancia); ?>"
                                style="width:100%;accent-color:var(--gsa-azul);margin-top:10px;">
                            <div style="display:flex;justify-content:space-between;font-size:11px;color:var(--gsa-texto-suave);margin-top:4px;">
                                <span>Pegado</span>
                                <span>Separado</span>
                            </div>
                        </div>

                    </div>
                </div>
                <!-- TEMA DE COLORES -->
                <div class="gsa-form-card" style="margin-bottom:20px;">
                    <div class="gsa-form-header">
                        <div class="gsa-form-header-icon">
                            <span class="dashicons dashicons-color-picker"></span>
                        </div>
                        <div>
                            <h2>Tema de colores</h2>
                            <span>Define el estilo de color de los botones flotantes.</span>
                        </div>
                    </div>
                    <div style="padding:22px;">
                        <?php
                        $ap_tema = $config['ap_tema'] ?? 'original';
                        $temas = array(
                            'original' => array(
                                'label' => 'Colores originales',
                                'desc'  => 'Cada red con su color propio',
                                'btns'  => array('#25D366', '#1877F2', '#E1306C'),
                                'default' => true,
                            ),
                            'oscuro' => array(
                                'label' => 'Modo oscuro',
                                'desc'  => 'Botones gris oscuro con íconos blancos',
                                'btns'  => array('#2d2d2d', '#2d2d2d', '#2d2d2d'),
                            ),
                            'claro' => array(
                                'label' => 'Modo claro',
                                'desc'  => 'Botones blancos con íconos grises',
                                'btns'  => array('#ffffff', '#ffffff', '#ffffff'),
                                'border' => true,
                            ),
                            'gica' => array(
                                'label' => 'Colores GICA',
                                'desc'  => 'Azul y naranja de GICA Ingenieros',
                                'btns'  => array('#1a3a6b', '#f47920', '#1a3a6b'),
                            ),
                        );
                        ?>
                        <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:12px;">
                            <?php foreach ($temas as $val => $tema): ?>
                            <label class="gsa-tema-item <?php echo $ap_tema === $val ? 'selected' : ''; ?>">
                                <input type="radio" name="ap_tema" value="<?php echo $val; ?>"
                                    <?php checked($ap_tema, $val); ?> style="display:none;">
                                <?php if (!empty($tema['default'])): ?>
                                    <span class="gsa-tema-badge">Por defecto</span>
                                <?php endif; ?>
                                <div style="display:flex;gap:4px;justify-content:center;margin-bottom:8px;">
                                    <?php foreach ($tema['btns'] as $color): ?>
                                    <div style="width:28px;height:28px;border-radius:50%;background:<?php echo $color; ?>;<?php echo !empty($tema['border']) ? 'border:1.5px solid #d0d7e3;' : ''; ?>"></div>
                                    <?php endforeach; ?>
                                </div>
                                <div style="font-size:12px;font-weight:700;color:var(--gsa-texto);text-align:center;"><?php echo $tema['label']; ?></div>
                                <div style="font-size:11px;color:var(--gsa-texto-suave);text-align:center;margin-top:2px;"><?php echo $tema['desc']; ?></div>
                            </label>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
                <div style="margin-bottom:24px;">
                    <button type="submit" name="gsa_guardar_apariencia" class="gsa-btn-primary">
                        <span class="dashicons dashicons-saved"></span> Guardar apariencia
                    </button>
                </div>

            </form>
        </div>

        <!-- COLUMNA DERECHA: PREVIEW -->
        <div style="position:sticky;top:32px;">
            <div class="gsa-form-card">
                <div class="gsa-form-header">
                    <div class="gsa-form-header-icon">
                        <span class="dashicons dashicons-visibility"></span>
                    </div>
                    <div>
                        <h2>Preview</h2>
                        <span>Vista previa en tiempo real.</span>
                    </div>
                </div>
                <div id="gsaPreviewBox" style="padding:18px;background:#f0f2f5;border-radius:0 0 10px 10px;min-height:300px;position:relative;overflow:hidden;">
                    <div style="font-size:12px;color:#999;text-align:center;margin-bottom:10px;">Vista previa del widget</div>
                    <div id="gsaPreviewWidget" style="position:absolute;display:flex;flex-direction:column;gap:8px;">
                        <div class="gsa-preview-btn" style="width:46px;height:46px;background:#25D366;display:flex;align-items:center;justify-content:center;color:#fff;border-radius:50%;box-shadow:0 3px 10px rgba(0,0,0,0.2);">
                            <i class="fa-brands fa-whatsapp" style="font-size:20px;"></i>
                        </div>
                        <div class="gsa-preview-btn" style="width:46px;height:46px;background:#1877F2;display:flex;align-items:center;justify-content:center;color:#fff;border-radius:50%;box-shadow:0 3px 10px rgba(0,0,0,0.2);">
                            <i class="fa-brands fa-facebook-f" style="font-size:20px;"></i>
                        </div>
                        <div class="gsa-preview-btn" style="width:46px;height:46px;background:#E1306C;display:flex;align-items:center;justify-content:center;color:#fff;border-radius:50%;box-shadow:0 3px 10px rgba(0,0,0,0.2);">
                            <i class="fa-brands fa-instagram" style="font-size:20px;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

</div>

<?php include GSA_PATH . 'admin/views/footer.php'; ?>

<script>
document.addEventListener('DOMContentLoaded', function () {

    const preview    = document.getElementById('gsaPreviewWidget');
    const btns       = preview.querySelectorAll('.gsa-preview-btn');

    const initialPos       = '<?php echo esc_js($ap_posicion); ?>';
    const initialTamano    = <?php echo intval($ap_tamano); ?>;
    const initialGap       = <?php echo intval($ap_espaciado); ?>;
    const initialForma     = '<?php echo esc_js($ap_forma); ?>';
    const initialSombra    = '<?php echo esc_js($ap_sombra); ?>';
    const initialOrient    = '<?php echo esc_js($ap_orientacion); ?>';
    const initialDistancia = <?php echo intval($ap_distancia); ?>;

    const radios  = { circular: '50%', redondeado: '12px', cuadrado: '4px' };
    const sombras = {
        ninguna: 'none',
        suave:   '0 2px 6px rgba(0,0,0,0.15)',
        media:   '0 3px 10px rgba(0,0,0,0.25)',
        fuerte:  '0 6px 20px rgba(0,0,0,0.45)'
    };

    function aplicarInicial() {
        btns.forEach(b => {
            b.style.width        = initialTamano + 'px';
            b.style.height       = initialTamano + 'px';
            b.style.borderRadius = radios[initialForma] || '50%';
            b.style.boxShadow    = sombras[initialSombra] || sombras.media;
        });
        preview.style.gap            = initialGap + 'px';
        preview.style.flexDirection  = initialOrient === 'horizontal' ? 'row' : 'column';
        actualizarPosicion(initialPos, initialDistancia);
    }

    aplicarInicial();

    // Tamaño desktop — móvil se adapta automáticamente al 80%
    const sliderTamano     = document.getElementById('sliderTamano');
    const labelTamano      = document.getElementById('labelTamano');
    const sliderMovil      = document.getElementById('sliderTamanoMovil');
    const labelMovil       = document.getElementById('labelTamanoMovil');

    sliderTamano.addEventListener('input', function () {
        const val       = parseInt(this.value);
        const movilVal  = Math.round(val * 0.8);
        labelTamano.textContent = val;
        sliderMovil.value       = movilVal;
        labelMovil.textContent  = movilVal;
        btns.forEach(b => { b.style.width = val + 'px'; b.style.height = val + 'px'; });
    });

    sliderMovil.addEventListener('input', function () {
        labelMovil.textContent = this.value;
    });

    // Espaciado
    const sliderEspaciado = document.getElementById('sliderEspaciado');
    const labelEspaciado  = document.getElementById('labelEspaciado');
    sliderEspaciado.addEventListener('input', function () {
        labelEspaciado.textContent = this.value;
        preview.style.gap = this.value + 'px';
    });

    // Distancia al borde
    const sliderDistancia = document.getElementById('sliderDistancia');
    const labelDistancia  = document.getElementById('labelDistancia');
    sliderDistancia.addEventListener('input', function () {
        labelDistancia.textContent = this.value;
        const pos = document.querySelector('input[name="ap_posicion"]:checked').value;
        actualizarPosicion(pos, parseInt(this.value));
    });

    // Forma
    document.querySelectorAll('input[name="ap_forma"]').forEach(function (radio) {
        radio.addEventListener('change', function () {
            document.querySelectorAll('.gsa-forma-item').forEach(i => i.classList.remove('selected'));
            this.closest('.gsa-forma-item').classList.add('selected');
            btns.forEach(b => b.style.borderRadius = radios[this.value] || '50%');
        });
    });

    // Posición
    document.querySelectorAll('input[name="ap_posicion"]').forEach(function (radio) {
        radio.addEventListener('change', function () {
            document.querySelectorAll('.gsa-posicion-item').forEach(i => i.classList.remove('selected'));
            this.closest('.gsa-posicion-item').classList.add('selected');
            const dist = parseInt(document.getElementById('sliderDistancia').value);
            actualizarPosicion(this.value, dist);
        });
    });

    function actualizarPosicion(pos, dist) {
        dist = dist || 6;
        preview.style.top       = 'auto';
        preview.style.bottom    = 'auto';
        preview.style.left      = 'auto';
        preview.style.right     = 'auto';
        preview.style.transform = '';

        if (pos === 'top-left')      { preview.style.top = dist+'px'; preview.style.left = dist+'px'; }
        if (pos === 'top-center')    { preview.style.top = dist+'px'; preview.style.left = '50%'; preview.style.transform = 'translateX(-50%)'; }
        if (pos === 'top-right')     { preview.style.top = dist+'px'; preview.style.right = dist+'px'; }
        if (pos === 'middle-left')   { preview.style.top = '50%'; preview.style.left = dist+'px'; preview.style.transform = 'translateY(-50%)'; }
        if (pos === 'middle-right')  { preview.style.top = '50%'; preview.style.right = dist+'px'; preview.style.transform = 'translateY(-50%)'; }
        if (pos === 'bottom-left')   { preview.style.bottom = dist+'px'; preview.style.left = dist+'px'; }
        if (pos === 'bottom-center') { preview.style.bottom = dist+'px'; preview.style.left = '50%'; preview.style.transform = 'translateX(-50%)'; }
        if (pos === 'bottom-right')  { preview.style.bottom = dist+'px'; preview.style.right = dist+'px'; }
    }

    // Sombra
    document.querySelector('select[name="ap_sombra"]').addEventListener('change', function () {
        btns.forEach(b => b.style.boxShadow = sombras[this.value] || sombras.media);
    });

    // Orientación
    document.querySelectorAll('input[name="ap_orientacion"]').forEach(function (radio) {
        radio.addEventListener('change', function () {
            document.querySelectorAll('.gsa-posicion-item').forEach(i => i.classList.remove('selected'));
            this.closest('.gsa-posicion-item').classList.add('selected');
            preview.style.flexDirection = this.value === 'horizontal' ? 'row' : 'column';
        });
    });

    // Hover preview
    btns.forEach(function(btn) {
        btn.addEventListener('mouseenter', function() {
            if (document.querySelector('input[name="ap_hover"]').checked) {
                this.style.transform = 'scale(1.15) translateY(-2px)';
            }
        });
        btn.addEventListener('mouseleave', function() {
            this.style.transform = '';
        });
    });

     // Tema de colores
    const temasColores = {
        original: ['#25D366', '#1877F2', '#E1306C'],
        oscuro:   ['#2d2d2d', '#2d2d2d', '#2d2d2d'],
        claro:    ['#ffffff', '#ffffff', '#ffffff'],
        gica:     ['#1a3a6b', '#f47920', '#1a3a6b'],
    };

    const temasIconos = {
        original: '#ffffff',
        oscuro:   '#ffffff',
        claro:    '#333333',
        gica:     '#ffffff',
    };

    // Aplicar tema inicial al preview
    const temaInicial = '<?php echo esc_js($ap_tema ?? "original"); ?>';
    aplicarTema(temaInicial);

    document.querySelectorAll('input[name="ap_tema"]').forEach(function(radio) {
        radio.addEventListener('change', function() {
            document.querySelectorAll('.gsa-tema-item').forEach(i => i.classList.remove('selected'));
            this.closest('.gsa-tema-item').classList.add('selected');
            aplicarTema(this.value);
        });
    });

    function aplicarTema(tema) {
        const colores = temasColores[tema] || temasColores.original;
        const iconColor = temasIconos[tema] || '#ffffff';
        const btnsList = preview.querySelectorAll('.gsa-preview-btn');
        btnsList.forEach(function(btn, i) {
            btn.style.backgroundColor = colores[i % colores.length];
            btn.style.border = tema === 'claro' ? '1.5px solid #d0d7e3' : 'none';
            const icon = btn.querySelector('i');
            if (icon) icon.style.color = iconColor;
        });
    }

});
</script>