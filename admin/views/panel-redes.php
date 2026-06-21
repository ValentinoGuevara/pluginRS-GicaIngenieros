<?php 
if (!defined('ABSPATH')) exit;
if (!isset($red_a_editar)) $red_a_editar = null;

// Validamos si la variable existe y es un array, si no, la inicializamos vacía
if (!isset($redes_registradas) || !is_array($redes_registradas)) {
    $redes_registradas = [];
}

$total     = count($redes_registradas);
$activas   = count(array_filter($redes_registradas, fn($r) => isset($r->estado) && $r->estado == 1));
$inactivas = $total - $activas;
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
                <h1>Gestión de Redes Sociales</h1>
                <p>Administra, activa y personaliza los canales sociales que aparecen en el sitio web de GICA Ingenieros.</p>
            </div>
        </div>
        <span class="gsa-badge-modulo">
            <span class="dashicons dashicons-share"></span>
            Gestión de redes sociales
        </span>
    </div>

    <!-- NOTIFICACIÓN -->
    <?php if (!empty($notificacion)) echo $notificacion; ?>

    <!-- STATS BAR -->
    <div class="gsa-stats-bar">
        <div class="gsa-stat-card">
            <div class="gsa-stat-icon azul">
                <span class="dashicons dashicons-share"></span>
            </div>
            <div>
                <div class="gsa-stat-num"><?php echo $total; ?></div>
                <div class="gsa-stat-label">Total registradas</div>
            </div>
        </div>
        <div class="gsa-stat-card verde">
            <div class="gsa-stat-icon verde">
                <span class="dashicons dashicons-yes-alt"></span>
            </div>
            <div>
                <div class="gsa-stat-num"><?php echo $activas; ?></div>
                <div class="gsa-stat-label">Activas</div>
            </div>
        </div>
        <div class="gsa-stat-card rojo">
            <div class="gsa-stat-icon rojo">
                <span class="dashicons dashicons-dismiss"></span>
            </div>
            <div>
                <div class="gsa-stat-num"><?php echo $inactivas; ?></div>
                <div class="gsa-stat-label">Inactivas</div>
            </div>
        </div>
    </div>

    <!-- FORMULARIO -->
    <div class="gsa-form-card">
        <div class="gsa-form-header">
            <div class="gsa-form-header-icon">
                <span class="dashicons <?php echo $red_a_editar ? 'dashicons-edit' : 'dashicons-plus'; ?>"></span>
            </div>
            <div>
                <h2><?php echo $red_a_editar ? 'Editar red social' : 'Agregar red social'; ?></h2>
                <span><?php echo $red_a_editar ? 'Modifica los datos y guarda los cambios.' : 'Selecciona una red o agrega una personalizada.'; ?></span>
            </div>
        </div>
        <div style="padding: 22px;">
            <form method="POST" action="<?php echo esc_url(admin_url('admin.php?page=gsa-panel')); ?>">
                <?php wp_nonce_field('gsa_nonce_formulario', 'gsa_nonce_campo'); ?>
                <?php if ($red_a_editar): ?>
                    <input type="hidden" name="red_id" value="<?php echo intval($red_a_editar->id); ?>">
                <?php endif; ?>

                <!-- HIDDEN FIELDS que se llenan automáticamente -->
                <input type="hidden" name="nombre" id="gsa_nombre_hidden" value="<?php echo $red_a_editar ? esc_attr($red_a_editar->nombre) : ''; ?>">
                <input type="hidden" name="icono"  id="gsa_icono_hidden"  value="<?php echo $red_a_editar ? esc_attr($red_a_editar->icono)  : ''; ?>">

                <?php if (!$red_a_editar): ?>
                <!-- GRILLA DE REDES PREDEFINIDAS -->
                <div class="gsa-redes-selector">
                    <p class="gsa-selector-label">Selecciona una red social:</p>
                    <div class="gsa-redes-picker" id="gsaRedesPicker">

                        <div class="gsa-picker-item" data-nombre="WhatsApp" data-icono="fa-brands fa-whatsapp" data-color="#25D366">
                            <span class="gsa-picker-icon" style="background:#25D366">
                                <i class="fa-brands fa-whatsapp"></i>
                            </span>
                            <span class="gsa-picker-name">WhatsApp</span>
                        </div>

                        <div class="gsa-picker-item" data-nombre="Facebook" data-icono="fa-brands fa-facebook-f" data-color="#1877F2">
                            <span class="gsa-picker-icon" style="background:#1877F2">
                                <i class="fa-brands fa-facebook-f"></i>
                            </span>
                            <span class="gsa-picker-name">Facebook</span>
                        </div>

                        <div class="gsa-picker-item" data-nombre="Instagram" data-icono="fa-brands fa-instagram" data-color="#E1306C">
                            <span class="gsa-picker-icon" style="background:#E1306C">
                                <i class="fa-brands fa-instagram"></i>
                            </span>
                            <span class="gsa-picker-name">Instagram</span>
                        </div>

                        <div class="gsa-picker-item" data-nombre="Twitter / X" data-icono="fa-brands fa-x-twitter" data-color="#000000">
                            <span class="gsa-picker-icon" style="background:#000000">
                                <i class="fa-brands fa-x-twitter"></i>
                            </span>
                            <span class="gsa-picker-name">Twitter / X</span>
                        </div>

                        <div class="gsa-picker-item" data-nombre="LinkedIn" data-icono="fa-brands fa-linkedin-in" data-color="#0A66C2">
                            <span class="gsa-picker-icon" style="background:#0A66C2">
                                <i class="fa-brands fa-linkedin-in"></i>
                            </span>
                            <span class="gsa-picker-name">LinkedIn</span>
                        </div>

                        <div class="gsa-picker-item" data-nombre="YouTube" data-icono="fa-brands fa-youtube" data-color="#FF0000">
                            <span class="gsa-picker-icon" style="background:#FF0000">
                                <i class="fa-brands fa-youtube"></i>
                            </span>
                            <span class="gsa-picker-name">YouTube</span>
                        </div>

                        <div class="gsa-picker-item" data-nombre="TikTok" data-icono="fa-brands fa-tiktok" data-color="#010101">
                            <span class="gsa-picker-icon" style="background:#010101">
                                <i class="fa-brands fa-tiktok"></i>
                            </span>
                            <span class="gsa-picker-name">TikTok</span>
                        </div>

                        <div class="gsa-picker-item" data-nombre="Messenger" data-icono="fa-brands fa-facebook-messenger" data-color="#0084FF">
                            <span class="gsa-picker-icon" style="background:#0084FF">
                                <i class="fa-brands fa-facebook-messenger"></i>
                            </span>
                            <span class="gsa-picker-name">Messenger</span>
                        </div>

                        <div class="gsa-picker-item" data-nombre="Telegram" data-icono="fa-brands fa-telegram" data-color="#229ED9">
                            <span class="gsa-picker-icon" style="background:#229ED9">
                                <i class="fa-brands fa-telegram"></i>
                            </span>
                            <span class="gsa-picker-name">Telegram</span>
                        </div>

                        <div class="gsa-picker-item" data-nombre="Correo" data-icono="fa-solid fa-envelope" data-color="#EA4335">
                            <span class="gsa-picker-icon" style="background:#EA4335">
                                <i class="fa-solid fa-envelope"></i>
                            </span>
                            <span class="gsa-picker-name">Correo</span>
                        </div>

                        <div class="gsa-picker-item" data-nombre="Pinterest" data-icono="fa-brands fa-pinterest-p" data-color="#E60023">
                            <span class="gsa-picker-icon" style="background:#E60023">
                                <i class="fa-brands fa-pinterest-p"></i>
                            </span>
                            <span class="gsa-picker-name">Pinterest</span>
                        </div>

                        <div class="gsa-picker-item" data-nombre="Snapchat" data-icono="fa-brands fa-snapchat" data-color="#FFFC00">
                            <span class="gsa-picker-icon" style="background:#FFFC00">
                                <i class="fa-brands fa-snapchat"></i>
                            </span>
                            <span class="gsa-picker-name">Snapchat</span>
                        </div>

                        <div class="gsa-picker-item" data-nombre="Discord" data-icono="fa-brands fa-discord" data-color="#5865F2">
                            <span class="gsa-picker-icon" style="background:#5865F2">
                                <i class="fa-brands fa-discord"></i>
                            </span>
                            <span class="gsa-picker-name">Discord</span>
                        </div>

                        <div class="gsa-picker-item" data-nombre="Twitch" data-icono="fa-brands fa-twitch" data-color="#9146FF">
                            <span class="gsa-picker-icon" style="background:#9146FF">
                                <i class="fa-brands fa-twitch"></i>
                            </span>
                            <span class="gsa-picker-name">Twitch</span>
                        </div>

                        <div class="gsa-picker-item" data-nombre="GitHub" data-icono="fa-brands fa-github" data-color="#181717">
                            <span class="gsa-picker-icon" style="background:#181717">
                                <i class="fa-brands fa-github"></i>
                            </span>
                            <span class="gsa-picker-name">GitHub</span>
                        </div>

                        <div class="gsa-picker-item" data-nombre="Spotify" data-icono="fa-brands fa-spotify" data-color="#1DB954">
                            <span class="gsa-picker-icon" style="background:#1DB954">
                                <i class="fa-brands fa-spotify"></i>
                            </span>
                            <span class="gsa-picker-name">Spotify</span>
                        </div>

                        <div class="gsa-picker-item gsa-picker-custom" id="gsaPickerCustom">
                            <span class="gsa-picker-icon" style="background:var(--gsa-azul)">
                                <span class="dashicons dashicons-plus-alt"></span>
                            </span>
                            <span class="gsa-picker-name">Personalizada</span>
                        </div>

                    </div>
                </div>

            <!-- CAMPOS MANUALES (solo para personalizada, ocultos por defecto) -->
            <div class="gsa-custom-fields" id="gsaCustomFields" style="display:none;">
                <div class="gsa-form-body" style="padding:0; grid-template-columns: 1fr 1fr; margin-bottom:16px;">
                    <div class="gsa-field">
                        <label for="gsa_nombre_custom">Nombre</label>
                        <input type="text" id="gsa_nombre_custom" placeholder="Ej. Mi Red" maxlength="50">
                    </div>
                    <div class="gsa-field">
                        <label>Color</label>
                        <div class="gsa-color-field">
                            <input type="color" id="gsa_color_custom" value="#000000">
                            <span class="gsa-color-preview" id="gsa_color_custom_hex">#000000</span>
                        </div>
                    </div>
                </div>

                <!-- SELECTOR DE ICONOS -->
                <div class="gsa-field" style="margin-bottom:0;">
                    <label>Ícono <span id="gsaIconoSeleccionado" style="color:var(--gsa-azul);font-weight:700;margin-left:8px;"></span></label>
                    <input type="hidden" id="gsa_icono_custom">

                    <!-- Buscador -->
                    <input type="text" id="gsaIconoSearch" placeholder="Buscar ícono... ej: phone, star, home"
                        style="margin-bottom:10px;width:100%;padding:8px 12px;border:1.5px solid var(--gsa-borde);border-radius:7px;font-size:13px;outline:none;">

                    <!-- Grilla de iconos -->
                    <div class="gsa-iconos-grid" id="gsaIconosGrid"></div>
                </div>
            </div>
                <?php endif; ?>

                <!-- FILA INFERIOR: URL + COLOR + BOTÓN -->
                <div class="gsa-form-body" style="padding:0; margin-top: 18px; grid-template-columns: 1fr auto auto auto;">

                    <div class="gsa-field">
                        <label for="gsa_url">URL destino</label>
                            <?php 
                                $val  = $red_a_editar ? $red_a_editar->url : '';
                                $es_correo = strpos($val, 'mailto:') === 0;
                                if ($es_correo) $val = str_replace('mailto:', '', $val);
                            ?>
                            <input type="<?php echo $es_correo ? 'email' : 'url'; ?>" 
                                id="gsa_url" name="url" required
                                placeholder="<?php echo $es_correo ? 'contacto@gica.com' : 'https://...'; ?>"
                                value="<?php echo esc_attr($val); ?>">
                    </div>

                    <div class="gsa-field">
                        <label>Color</label>
                        <div class="gsa-color-field">
                            <input type="color" name="color" id="gsa_color"
                                value="<?php echo $red_a_editar ? esc_attr($red_a_editar->color) : '#25D366'; ?>">
                            <span class="gsa-color-preview" id="gsa_color_hex">
                                <?php echo $red_a_editar ? esc_attr($red_a_editar->color) : '#25D366'; ?>
                            </span>
                        </div>
                    </div>

                    <div class="gsa-form-actions">
                        <?php if ($red_a_editar): ?>
                            <button type="submit" name="gsa_guardar_red_submit" class="gsa-btn-secondary">
                                <span class="dashicons dashicons-yes"></span> Actualizar
                            </button>
                            <a href="<?php echo esc_url(admin_url('admin.php?page=gsa-panel')); ?>" class="gsa-btn-cancel">Cancelar</a>
                        <?php else: ?>
                            <button type="submit" name="gsa_guardar_red_submit" class="gsa-btn-primary" id="gsaBtnRegistrar" disabled>
                                <span class="dashicons dashicons-saved"></span> Registrar
                            </button>
                        <?php endif; ?>
                    </div>
                </div>

            </form>
        </div>
    </div>
    <!-- BOTÓN GUARDAR ORDEN -->
    <div id="gsaOrdenBar" style="display:none; margin-bottom:14px;">
        <button type="button" id="gsaBtnGuardarOrden" class="gsa-btn-primary">
            <span class="dashicons dashicons-saved"></span> Guardar orden
        </button>
        <span style="font-size:12px; color:var(--gsa-texto-suave); margin-left:10px;">Arrastra las tarjetas y presiona guardar.</span>
    </div>
    <!-- DIVISOR -->
    <div class="gsa-divider">
        <div class="gsa-divider-line"></div>
        <span class="gsa-divider-label">Redes registradas</span>
        <span class="gsa-divider-count"><?php echo $total; ?></span>
        <div class="gsa-divider-line"></div>
    </div>

    <!-- GRID DE REDES -->
    <div class="gsa-redes-grid">
        <?php if (empty($redes_registradas)): ?>
            <div class="gsa-empty">
                <span class="dashicons dashicons-share" style="font-size:36px;width:36px;height:36px;color:#d0d7e3;display:block;margin:0 auto 10px;"></span>
                No hay redes registradas aún. ¡Agrega la primera arriba!
            </div>
        <?php else: foreach ($redes_registradas as $red): ?>
            <div class="gsa-red-card" data-id="<?php echo intval($red->id); ?>">
                <div class="gsa-red-card-top" style="background:<?php echo esc_attr($red->color); ?>;"></div>
                <div class="gsa-red-card-body">
                    <div class="gsa-red-card-icon-row">
                        <div class="gsa-red-icon" style="background:<?php echo esc_attr($red->color); ?>;">
                            <i class="<?php echo esc_attr($red->icono); ?>"></i>
                        </div>
                        <?php $url_toggle = wp_nonce_url(admin_url('admin.php?page=gsa-panel&action=toggle&id=' . $red->id), 'gsa_toggle_' . $red->id); ?>
                        <a href="<?php echo esc_url($url_toggle); ?>"
                           class="gsa-red-estado <?php echo $red->estado ? 'gsa-estado-activo' : 'gsa-estado-inactivo'; ?>">
                            <?php echo $red->estado ? '● Activo' : '● Inactivo'; ?>
                        </a>
                    </div>
                    <div class="gsa-red-nombre"><?php echo esc_html($red->nombre); ?></div>
                    <code class="gsa-red-url"><?php 
                    $url_display = $red->url;
                    if (strpos($red->url, 'mailto:') === 0) {
                        $url_display = str_replace('mailto:', '', $red->url);
                    }
                    echo esc_html($url_display); 
                ?></code>
                </div>
                <div class="gsa-red-card-footer">
                    <?php $url_edit = wp_nonce_url(admin_url('admin.php?page=gsa-panel&action=edit&id=' . $red->id), 'gsa_edit_' . $red->id); ?>
                    <a href="<?php echo esc_url($url_edit); ?>" class="gsa-action-btn gsa-action-btn--edit">
                        <span class="dashicons dashicons-edit" style="font-size:13px;width:13px;height:13px;"></span> Editar
                    </a>
                    <?php $url_delete = wp_nonce_url(admin_url('admin.php?page=gsa-panel&action=delete&id=' . $red->id), 'gsa_delete_' . $red->id); ?>
                    <a href="<?php echo esc_url($url_delete); ?>"
                       class="gsa-action-btn gsa-action-btn--delete"
                       onclick="return confirm('¿Eliminar la red <?php echo esc_js($red->nombre); ?>?');">
                        <span class="dashicons dashicons-trash" style="font-size:13px;width:13px;height:13px;"></span> Borrar
                    </a>
                </div>
            </div>
        <?php endforeach; endif; ?>
    </div>

</div>
<div class="gsa-toast" id="gsaToast"></div>

<script>
const gsaNonceOrden = '<?php echo wp_create_nonce('gsa_nonce_orden'); ?>';
const gsaAjaxUrl    = '<?php echo admin_url('admin-ajax.php'); ?>';
document.addEventListener('DOMContentLoaded', function () {

    const picker      = document.getElementById('gsaRedesPicker');
    const btnRegistrar = document.getElementById('gsaBtnRegistrar');
    const customFields = document.getElementById('gsaCustomFields');
    const pickerCustom = document.getElementById('gsaPickerCustom');

    const hiddenNombre = document.getElementById('gsa_nombre_hidden');
    const hiddenIcono  = document.getElementById('gsa_icono_hidden');
    const colorPicker  = document.getElementById('gsa_color');
    const colorHex     = document.getElementById('gsa_color_hex');

    // Campos personalizados
    const customNombre = document.getElementById('gsa_nombre_custom');
    const customIcono  = document.getElementById('gsa_icono_custom');
    const customColor  = document.getElementById('gsa_color_custom');
    const customColorHex = document.getElementById('gsa_color_custom_hex');

    if (!picker) return;

    // Clic en una red predefinida
    picker.querySelectorAll('.gsa-picker-item:not(#gsaPickerCustom)').forEach(function (item) {
        item.addEventListener('click', function () {
            // Deseleccionar todos
            picker.querySelectorAll('.gsa-picker-item').forEach(i => i.classList.remove('selected'));
            this.classList.add('selected');

            // Ocultar campos personalizados
            customFields.style.display = 'none';

            // Llenar campos hidden
            hiddenNombre.value = this.dataset.nombre;
            hiddenIcono.value  = this.dataset.icono;

            const urlField = document.getElementById('gsa_url');

            if (this.dataset.nombre === 'Correo') {
                urlField.type = 'email';
                urlField.placeholder = 'contacto@gicaingenieros.com';
            } else {
                urlField.type = 'url';
                urlField.placeholder = 'https://...';
            }
            // Actualizar color
            colorPicker.value = this.dataset.color;
            colorHex.textContent = this.dataset.color;

            // Habilitar botón
            btnRegistrar.disabled = false;
        });
    });

    // Clic en Personalizada
    pickerCustom.addEventListener('click', function () {
        picker.querySelectorAll('.gsa-picker-item').forEach(i => i.classList.remove('selected'));
        this.classList.add('selected');
        customFields.style.display = 'block';
        btnRegistrar.disabled = false;

        // Sincronizar campos manuales con los hidden
        syncCustomFields();
    });

    // Sincronizar campos personalizados en tiempo real
    function syncCustomFields() {
        if (customNombre) customNombre.addEventListener('input', function () {
            this.value = this.value.replace(/[^a-zA-Z0-9áéíóúÁÉÍÓÚüÜñÑ\s]/g, '');
            hiddenNombre.value = this.value;
        });
        if (customIcono) customIcono.addEventListener('input', function () {
            hiddenIcono.value = this.value;
        });
        if (customColor) customColor.addEventListener('input', function () {
            colorPicker.value    = this.value;
            colorHex.textContent = this.value;
            customColorHex.textContent = this.value;
        });
    }
    syncCustomFields();
    
    // Color picker principal
    if (colorPicker) {
        colorPicker.addEventListener('input', function () {
            colorHex.textContent = this.value;
        });
    }
    // ── SORTABLE (Drag & Drop) ──
    const grid = document.querySelector('.gsa-redes-grid');

    if (grid && typeof Sortable !== 'undefined') {
    Sortable.create(grid, {
        animation: 150,
        ghostClass: 'gsa-card-ghost',
        chosenClass: 'gsa-card-chosen',
        dragClass: 'gsa-card-drag',
        onEnd: function () {
            document.getElementById('gsaOrdenBar').style.display = 'block';
        }
    });

    const btnGuardar = document.getElementById('gsaBtnGuardarOrden');


if (grid && typeof Sortable !== 'undefined') {
    Sortable.create(grid, {
        animation: 150,
        ghostClass: 'gsa-card-ghost',
        chosenClass: 'gsa-card-chosen',
        dragClass: 'gsa-card-drag',
        onEnd: function () {
            document.getElementById('gsaOrdenBar').style.display = 'block';
        }
    });

    const btnGuardar = document.getElementById('gsaBtnGuardarOrden');
        if (btnGuardar) {
            btnGuardar.addEventListener('click', function () {
                const cards = grid.querySelectorAll('.gsa-red-card');
                const orden = [];
                cards.forEach(function (card) {
                    orden.push(card.dataset.id);
                });

                const formData = new FormData();
                formData.append('action', 'gsa_actualizar_orden');
                formData.append('nonce', gsaNonceOrden);
                orden.forEach(function(id) {
                    formData.append('orden[]', id);
                });

                fetch(gsaAjaxUrl, {
                    method: 'POST',
                    body: formData
                })
                .then(r => r.json())
                .then(data => {
                    const toast = document.getElementById('gsaToast');
                    if (toast) {
                        toast.textContent = data.success ? '✔ Orden guardado correctamente' : '✖ Error al guardar';
                        toast.className   = 'gsa-toast ' + (data.success ? 'gsa-toast--ok' : 'gsa-toast--error');
                        toast.style.opacity = '1';
                        setTimeout(() => { toast.style.opacity = '0'; }, 2500);
                    }
                    if (data.success) {
                        document.getElementById('gsaOrdenBar').style.display = 'none';
                    }
                });
            });
        }
    }


        // -------------------------------------
        // ── SELECTOR DE ICONOS PERSONALIZADA ──
    const iconos = [
        // Redes sociales
        'fa-brands fa-whatsapp', 'fa-brands fa-facebook-f', 'fa-brands fa-instagram',
        'fa-brands fa-x-twitter', 'fa-brands fa-linkedin-in', 'fa-brands fa-youtube',
        'fa-brands fa-tiktok', 'fa-brands fa-telegram', 'fa-brands fa-discord',
        'fa-brands fa-twitch', 'fa-brands fa-github', 'fa-brands fa-spotify',
        'fa-brands fa-pinterest-p', 'fa-brands fa-snapchat', 'fa-brands fa-reddit',
        'fa-brands fa-twitter', 'fa-brands fa-facebook-messenger', 'fa-brands fa-skype',
        'fa-brands fa-slack', 'fa-brands fa-medium', 'fa-brands fa-behance',
        'fa-brands fa-dribbble', 'fa-brands fa-vimeo-v', 'fa-brands fa-soundcloud',
        'fa-brands fa-tumblr', 'fa-brands fa-google', 'fa-brands fa-apple',
        'fa-brands fa-android', 'fa-brands fa-windows',
        // Comunicación
        'fa-solid fa-envelope', 'fa-solid fa-phone', 'fa-solid fa-mobile-screen',
        'fa-solid fa-comment', 'fa-solid fa-comments', 'fa-solid fa-message',
        'fa-solid fa-paper-plane', 'fa-solid fa-headset', 'fa-solid fa-video',
        // Negocios
        'fa-solid fa-briefcase', 'fa-solid fa-building', 'fa-solid fa-store',
        'fa-solid fa-handshake', 'fa-solid fa-chart-line', 'fa-solid fa-chart-bar',
        'fa-solid fa-chart-pie', 'fa-solid fa-dollar-sign', 'fa-solid fa-coins',
        'fa-solid fa-credit-card', 'fa-solid fa-receipt', 'fa-solid fa-file-invoice',
        // Generales
        'fa-solid fa-star', 'fa-solid fa-heart', 'fa-solid fa-house',
        'fa-solid fa-location-dot', 'fa-solid fa-map', 'fa-solid fa-globe',
        'fa-solid fa-link', 'fa-solid fa-share-nodes', 'fa-solid fa-bell',
        'fa-solid fa-bookmark', 'fa-solid fa-tag', 'fa-solid fa-tags',
        'fa-solid fa-user', 'fa-solid fa-users', 'fa-solid fa-calendar',
        'fa-solid fa-clock', 'fa-solid fa-image', 'fa-solid fa-camera',
        'fa-solid fa-music', 'fa-solid fa-play', 'fa-solid fa-podcast',
        'fa-solid fa-rss', 'fa-solid fa-wifi', 'fa-solid fa-qrcode',
        'fa-solid fa-shield', 'fa-solid fa-lock', 'fa-solid fa-key',
        'fa-solid fa-gear', 'fa-solid fa-wrench', 'fa-solid fa-code',
        'fa-solid fa-laptop', 'fa-solid fa-desktop', 'fa-solid fa-cloud',
        'fa-solid fa-download', 'fa-solid fa-upload', 'fa-solid fa-print',
        'fa-solid fa-truck', 'fa-solid fa-box', 'fa-solid fa-gift',
        'fa-solid fa-graduation-cap', 'fa-solid fa-book', 'fa-solid fa-pen',
        'fa-solid fa-newspaper', 'fa-solid fa-magnifying-glass', 'fa-solid fa-info',
        'fa-solid fa-circle-question', 'fa-solid fa-fire', 'fa-solid fa-bolt',
        'fa-solid fa-leaf', 'fa-solid fa-sun', 'fa-solid fa-moon',
    ];

    const grid2     = document.getElementById('gsaIconosGrid');
    const search    = document.getElementById('gsaIconoSearch');
    const hidIcono  = document.getElementById('gsa_icono_custom');
    const labelSel  = document.getElementById('gsaIconoSeleccionado');

    function renderIconos(filtro) {
        if (!grid2) return;
        grid2.innerHTML = '';
        const lista = filtro
            ? iconos.filter(i => i.includes(filtro.toLowerCase()))
            : iconos;
        lista.forEach(function(icono) {
            const div = document.createElement('div');
            div.className = 'gsa-icono-item';
            div.title = icono;
            div.innerHTML = '<i class="' + icono + '"></i>';
            div.addEventListener('click', function() {
                grid2.querySelectorAll('.gsa-icono-item').forEach(i => i.classList.remove('selected'));
                this.classList.add('selected');
                hidIcono.value     = icono;
                hiddenIcono.value  = icono;
                labelSel.innerHTML = '<i class="' + icono + '"></i> ' + icono.split(' ').pop().replace('fa-','');
            });
            grid2.appendChild(div);
        });
    }

    if (grid2) renderIconos('');

    if (search) {
        search.addEventListener('input', function() {
            renderIconos(this.value);
        });
    }
}

});
</script>