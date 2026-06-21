<?php if (!defined('ABSPATH')) exit; ?>

<div class="wrap">

    <!-- TOP BAR -->
    <div class="gsa-top-bar">
        <div class="gsa-top-bar-left">
            <img src="<?php echo esc_url(GSA_URL . 'admin/images/logo.png'); ?>"
                 alt="Logo GICA Ingenieros"
                 class="gsa-logo-img">
            <div class="gsa-header-divider"></div>
            <div class="gsa-header-info">
                <h1>Reportes</h1>
                <p>Genera y exporta reportes estadísticos de interacción social.</p>
            </div>
        </div>
        <span class="gsa-badge-modulo">
            <span class="dashicons dashicons-media-spreadsheet"></span>
            Reportes
        </span>
    </div>

    <!-- FORM REPORTE -->
    <div class="gsa-form-card">
        <div class="gsa-form-header">
            <div class="gsa-form-header-icon">
                <span class="dashicons dashicons-download"></span>
            </div>
            <div>
                <h2>Generar reporte</h2>
                <span>Selecciona el tipo, período y formato de exportación.</span>
            </div>
        </div>
        <div style="padding:22px;">
            <form method="POST" action="<?php echo esc_url(admin_url('admin-post.php')); ?>">
                <input type="hidden" name="action" value="gsa_exportar_reporte">
                <?php wp_nonce_field('gsa_nonce_reporte', 'gsa_nonce_reporte_campo'); ?>

                <div style="display:grid;grid-template-columns:1fr 1fr 1fr 1fr;gap:16px;align-items:end;">

                    <div class="gsa-field">
                        <label>Tipo de reporte</label>
                        <select name="tipo" class="gsa-input" style="height:38px;border:1.5px solid var(--gsa-borde);border-radius:7px;padding:0 12px;font-size:13px;background:var(--gsa-fondo);color:var(--gsa-texto);outline:none;">
                            <option value="general">General de clics</option>
                            <option value="dispositivos">Dispositivos</option>
                            <option value="horarios">Horarios</option>
                            <option value="navegadores">Navegadores</option>
                            <option value="paginas">Páginas activas</option>
                        </select>
                    </div>

                    <div class="gsa-field">
                        <label>Desde</label>
                        <input type="date" name="desde" class="gsa-input"
                               style="height:38px;border:1.5px solid var(--gsa-borde);border-radius:7px;padding:0 12px;font-size:13px;background:var(--gsa-fondo);color:var(--gsa-texto);outline:none;width:100%;">
                    </div>

                    <div class="gsa-field">
                        <label>Hasta</label>
                        <input type="date" name="hasta" class="gsa-input"
                               style="height:38px;border:1.5px solid var(--gsa-borde);border-radius:7px;padding:0 12px;font-size:13px;background:var(--gsa-fondo);color:var(--gsa-texto);outline:none;width:100%;">
                    </div>

                    <div class="gsa-field">
                        <label>Formato</label>
                        <select name="formato" class="gsa-input" style="height:38px;border:1.5px solid var(--gsa-borde);border-radius:7px;padding:0 12px;font-size:13px;background:var(--gsa-fondo);color:var(--gsa-texto);outline:none;">
                            <option value="pdf">PDF</option>
                            <option value="excel">Excel (.xlsx)</option>
                            <option value="csv">CSV</option>
                        </select>
                    </div>

                </div>

                <div style="margin-top:18px;display:flex;gap:10px;">
                    <button type="submit" class="gsa-btn-primary">
                        <span class="dashicons dashicons-download"></span> Generar y descargar
                    </button>
                </div>

            </form>
        </div>
    </div>

    <!-- TIPOS DE REPORTE INFO -->
    <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:14px;margin-bottom:20px;">

        <div class="gsa-stat-card">
            <div class="gsa-stat-icon azul">
                <span class="dashicons dashicons-chart-bar"></span>
            </div>
            <div>
                <div style="font-size:13px;font-weight:700;color:var(--gsa-texto);">General de clics</div>
                <div class="gsa-stat-label">Por red social</div>
            </div>
        </div>

        <div class="gsa-stat-card verde">
            <div class="gsa-stat-icon verde">
                <span class="dashicons dashicons-smartphone"></span>
            </div>
            <div>
                <div style="font-size:13px;font-weight:700;color:var(--gsa-texto);">Dispositivos</div>
                <div class="gsa-stat-label">Móvil vs escritorio</div>
            </div>
        </div>

        <div class="gsa-stat-card naranja">
            <div class="gsa-stat-icon naranja">
                <span class="dashicons dashicons-clock"></span>
            </div>
            <div>
                <div style="font-size:13px;font-weight:700;color:var(--gsa-texto);">Horarios</div>
                <div class="gsa-stat-label">Picos de interacción</div>
            </div>
        </div>

        <div class="gsa-stat-card">
            <div class="gsa-stat-icon azul">
                <span class="dashicons dashicons-admin-site"></span>
            </div>
            <div>
                <div style="font-size:13px;font-weight:700;color:var(--gsa-texto);">Navegadores</div>
                <div class="gsa-stat-label">Chrome, Firefox, etc.</div>
            </div>
        </div>

        <div class="gsa-stat-card rojo">
            <div class="gsa-stat-icon rojo">
                <span class="dashicons dashicons-admin-page"></span>
            </div>
            <div>
                <div style="font-size:13px;font-weight:700;color:var(--gsa-texto);">Páginas activas</div>
                <div class="gsa-stat-label">Top 10 páginas</div>
            </div>
        </div>

        <div class="gsa-stat-card verde">
            <div class="gsa-stat-icon verde">
                <span class="dashicons dashicons-media-document"></span>
            </div>
            <div>
                <div style="font-size:13px;font-weight:700;color:var(--gsa-texto);">Formatos</div>
                <div class="gsa-stat-label">PDF, Excel, CSV</div>
            </div>
        </div>

    </div>

</div>

<?php include GSA_PATH . 'admin/views/footer.php'; ?>