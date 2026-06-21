<?php if (!defined('ABSPATH')) exit;
/** @var array $config */
/** @var string $notificacion */

$site_nombre          = $config['site_nombre']          ?? 'GICA Social Analytics';
$site_descripcion     = $config['site_descripcion']     ?? 'Sistema analítico de redes sociales';
$idioma               = $config['idioma']               ?? 'es';
$zona_horaria         = $config['zona_horaria']         ?? 'America/Lima';
$widget_activo        = $config['widget_activo']        ?? '1';
$tracker_activo       = $config['tracker_activo']       ?? '1';

$idiomas = array('es' => 'Español', 'en' => 'English');
$idioma_label = $idiomas[$idioma] ?? 'Español';
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
                <h1><?php echo esc_html($site_nombre); ?></h1>
                <p><?php echo esc_html($site_descripcion); ?></p>
            </div>
        </div>
        <span class="gsa-badge-modulo">
            <span class="dashicons dashicons-admin-settings"></span>
            Configuración
        </span>
    </div>

    <!-- NOTIFICACIÓN -->
    <?php if (!empty($notificacion)) echo $notificacion; ?>

    <form method="POST" action="">
        <?php wp_nonce_field('gsa_nonce_config', 'gsa_nonce_config_campo'); ?>

        <!-- CONFIGURACIÓN GENERAL -->
        <div class="gsa-form-card" style="margin-bottom:20px;">
            <div class="gsa-form-header">
                <div class="gsa-form-header-icon">
                    <span class="dashicons dashicons-admin-generic"></span>
                </div>
                <div>
                    <h2>Configuración general</h2>
                    <span>Nombre y descripción del sistema.</span>
                </div>
            </div>
            <div style="padding:22px;display:grid;grid-template-columns:1fr 1fr;gap:16px;">
                <div class="gsa-field">
                    <label>Nombre del sistema</label>
                    <input type="text" name="site_nombre"
                           style="width:100%;padding:9px 12px;border:1.5px solid var(--gsa-borde);border-radius:7px;font-size:13px;background:var(--gsa-fondo);outline:none;box-sizing:border-box;"
                           value="<?php echo esc_attr($site_nombre); ?>">
                </div>
                <div class="gsa-field">
                    <label>Descripción</label>
                    <input type="text" name="site_descripcion"
                           style="width:100%;padding:9px 12px;border:1.5px solid var(--gsa-borde);border-radius:7px;font-size:13px;background:var(--gsa-fondo);outline:none;box-sizing:border-box;"
                           value="<?php echo esc_attr($site_descripcion); ?>">
                </div>
            </div>
        </div>

        <!-- IDIOMA Y ZONA HORARIA (solo informativo) -->
        <div class="gsa-form-card" style="margin-bottom:20px;">
            <div class="gsa-form-header">
                <div class="gsa-form-header-icon">
                    <span class="dashicons dashicons-admin-site"></span>
                </div>
                <div>
                    <h2>Idioma y zona horaria</h2>
                    <span>Configuración regional actual del sistema.</span>
                </div>
            </div>
            <div style="padding:22px;display:grid;grid-template-columns:1fr 1fr;gap:16px;">
                <div style="background:var(--gsa-fondo);padding:14px 18px;border-radius:8px;border:1px solid var(--gsa-borde);">
                    <div style="font-size:11px;font-weight:700;color:var(--gsa-texto-suave);text-transform:uppercase;letter-spacing:0.04em;margin-bottom:6px;">Idioma actual</div>
                    <div style="display:flex;align-items:center;gap:8px;">
                        <span class="dashicons dashicons-translation" style="color:var(--gsa-azul);"></span>
                        <span style="font-size:14px;font-weight:700;color:var(--gsa-texto);"><?php echo esc_html($idioma_label); ?></span>
                    </div>
                </div>
                <div style="background:var(--gsa-fondo);padding:14px 18px;border-radius:8px;border:1px solid var(--gsa-borde);">
                    <div style="font-size:11px;font-weight:700;color:var(--gsa-texto-suave);text-transform:uppercase;letter-spacing:0.04em;margin-bottom:6px;">Zona horaria actual</div>
                    <div style="display:flex;align-items:center;gap:8px;">
                        <span class="dashicons dashicons-clock" style="color:var(--gsa-naranja);"></span>
                        <span style="font-size:14px;font-weight:700;color:var(--gsa-texto);"><?php echo esc_html($zona_horaria); ?></span>
                    </div>
                </div>
            </div>
            <!-- campos ocultos para que no se pierdan al guardar -->
            <input type="hidden" name="idioma" value="<?php echo esc_attr($idioma); ?>">
            <input type="hidden" name="zona_horaria" value="<?php echo esc_attr($zona_horaria); ?>">
        </div>

        <!-- ACTIVAR/DESACTIVAR MÓDULOS -->
        <div class="gsa-form-card" style="margin-bottom:20px;">
            <div class="gsa-form-header">
                <div class="gsa-form-header-icon">
                    <span class="dashicons dashicons-admin-plugins"></span>
                </div>
                <div>
                    <h2>Activar / Desactivar módulos</h2>
                    <span>Controla qué funcionalidades están activas.</span>
                </div>
            </div>
            <div style="padding:22px;display:flex;flex-direction:column;gap:16px;">

                <div style="display:flex;align-items:center;justify-content:space-between;padding:14px 18px;background:var(--gsa-fondo);border-radius:8px;border:1px solid var(--gsa-borde);">
                    <div style="display:flex;align-items:center;gap:12px;">
                        <div class="gsa-stat-icon azul" style="width:36px;height:36px;border-radius:8px;">
                            <span class="dashicons dashicons-share"></span>
                        </div>
                        <div>
                            <div style="font-size:13px;font-weight:700;color:var(--gsa-texto);">Widget flotante</div>
                            <div style="font-size:12px;color:var(--gsa-texto-suave);">Muestra los botones sociales en el sitio público</div>
                        </div>
                    </div>
                    <label class="gsa-toggle">
                        <input type="checkbox" name="widget_activo" value="1" <?php checked($widget_activo, '1'); ?>>
                        <span class="gsa-toggle-slider"></span>
                    </label>
                </div>

                <div style="display:flex;align-items:center;justify-content:space-between;padding:14px 18px;background:var(--gsa-fondo);border-radius:8px;border:1px solid var(--gsa-borde);">
                    <div style="display:flex;align-items:center;gap:12px;">
                        <div class="gsa-stat-icon naranja" style="width:36px;height:36px;border-radius:8px;">
                            <span class="dashicons dashicons-chart-line"></span>
                        </div>
                        <div>
                            <div style="font-size:13px;font-weight:700;color:var(--gsa-texto);">Tracker de clics</div>
                            <div style="font-size:12px;color:var(--gsa-texto-suave);">Registra los clics de los visitantes en la BD</div>
                        </div>
                    </div>
                    <label class="gsa-toggle">
                        <input type="checkbox" name="tracker_activo" value="1" <?php checked($tracker_activo, '1'); ?>>
                        <span class="gsa-toggle-slider"></span>
                    </label>
                </div>

            </div>
        </div>

        <!-- BOTÓN GUARDAR -->
        <div style="margin-bottom:24px;">
            <button type="submit" name="gsa_guardar_config" class="gsa-btn-primary">
                <span class="dashicons dashicons-saved"></span> Guardar configuración
            </button>
        </div>

    </form>

</div>

<?php include GSA_PATH . 'admin/views/footer.php'; ?>