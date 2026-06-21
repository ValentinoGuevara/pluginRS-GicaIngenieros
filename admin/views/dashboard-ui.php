<?php if (!defined('ABSPATH')) exit; 
// Suprimir advertencias del IDE — variables inyectadas desde el controlador
/** @var array $stats */
/** @var array $redes_top */
/** @var array $clics_por_dia */
/** @var array $clics_dispositivo */
/** @var array $clics_hora */
/** @var array $clics_por_red */
/** @var string|null $desde */
/** @var string|null $hasta */
/** @var array $clics_navegador */
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
                <h1>Dashboard Analítico</h1>
                <p>Visualiza el rendimiento e interacción de las redes sociales en tiempo real.</p>
            </div>
        </div>
        <span class="gsa-badge-modulo">
            <span class="dashicons dashicons-chart-bar"></span>
            Analíticas
        </span>
    </div>

    <!-- STATS BAR -->
    <div class="gsa-stats-bar" style="grid-template-columns: repeat(4,1fr);">
        <div class="gsa-stat-card">
            <div class="gsa-stat-icon azul">
                <span class="dashicons dashicons-chart-line"></span>
            </div>
            <div>
                <div class="gsa-stat-num"><?php echo $stats['total_clics']; ?></div>
                <div class="gsa-stat-label">Total clics</div>
            </div>
        </div>
        <div class="gsa-stat-card naranja">
            <div class="gsa-stat-icon naranja">
                <span class="dashicons dashicons-clock"></span>
            </div>
            <div>
                <div class="gsa-stat-num"><?php echo $stats['clics_hoy']; ?></div>
                <div class="gsa-stat-label">Clics hoy</div>
            </div>
        </div>
        <div class="gsa-stat-card verde">
            <div class="gsa-stat-icon verde">
                <span class="dashicons dashicons-calendar"></span>
            </div>
            <div>
                <div class="gsa-stat-num"><?php echo $stats['clics_semana']; ?></div>
                <div class="gsa-stat-label">Esta semana</div>
            </div>
        </div>
        <div class="gsa-stat-card rojo">
            <div class="gsa-stat-icon rojo">
                <span class="dashicons dashicons-chart-pie"></span>
            </div>
            <div>
                <div class="gsa-stat-num"><?php echo $stats['clics_mes']; ?></div>
                <div class="gsa-stat-label">Este mes</div>
            </div>
        </div>
    </div>

    <!-- FILTRO POR FECHA -->
    <div class="gsa-form-card" style="margin-bottom:20px;">
        <div class="gsa-form-header">
            <div class="gsa-form-header-icon">
                <span class="dashicons dashicons-filter"></span>
            </div>
            <div>
                <h2>Filtrar por fecha</h2>
                <span>Filtra las estadísticas por rango de fechas.</span>
            </div>
        </div>
        <div style="padding:18px 22px;">
            <form method="GET" action="<?php echo esc_url(admin_url('admin.php')); ?>">
                <input type="hidden" name="page" value="gsa-dashboard">
                <div style="display:flex;gap:14px;align-items:end;flex-wrap:wrap;">
                    <div class="gsa-field">
                        <label>Desde</label>
                        <input type="date" name="desde" class="gsa-input"
                               value="<?php echo esc_attr($desde ?? ''); ?>">
                    </div>
                    <div class="gsa-field">
                        <label>Hasta</label>
                        <input type="date" name="hasta" class="gsa-input"
                               value="<?php echo esc_attr($hasta ?? ''); ?>">
                    </div>
                    <div>
                        <button type="submit" class="gsa-btn-primary">
                            <span class="dashicons dashicons-search"></span> Filtrar
                        </button>
                        <a href="<?php echo esc_url(admin_url('admin.php?page=gsa-dashboard')); ?>"
                           class="gsa-btn-cancel" style="margin-left:8px;">Limpiar</a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- GRÁFICOS ROW 1 -->
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;margin-bottom:20px;">

        <!-- CLICS POR DÍA -->
        <div class="gsa-form-card" style="margin-bottom:0;">
            <div class="gsa-form-header">
                <div class="gsa-form-header-icon">
                    <span class="dashicons dashicons-chart-line"></span>
                </div>
                <div>
                    <h2>Clics por día</h2>
                    <span>Últimos 7 días</span>
                </div>
            </div>
            <div style="padding:18px;">
                <canvas id="gsaChartDia" height="200"></canvas>
            </div>
        </div>

        <!-- CLICS POR RED -->
        <div class="gsa-form-card" style="margin-bottom:0;">
            <div class="gsa-form-header">
                <div class="gsa-form-header-icon">
                    <span class="dashicons dashicons-share"></span>
                </div>
                <div>
                    <h2>Clics por red social</h2>
                    <span><?php echo ($desde && $hasta) ? "Del $desde al $hasta" : 'Todo el tiempo'; ?></span>
                </div>
            </div>
            <div style="padding:18px;">
                <canvas id="gsaChartRed" height="200"></canvas>
            </div>
        </div>

    </div>
    <!-- GRÁFICOS ROW 2 — CÍRCULOS -->
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;margin-bottom:20px;">

        <!-- DISPOSITIVOS -->
        <div class="gsa-form-card" style="margin-bottom:0;">
            <div class="gsa-form-header">
                <div class="gsa-form-header-icon">
                    <span class="dashicons dashicons-smartphone"></span>
                </div>
                <div>
                    <h2>Dispositivos</h2>
                    <span>Móvil vs escritorio</span>
                </div>
            </div>
            <div style="padding:18px;display:flex;justify-content:center;align-items:center;height:220px;">
                <canvas id="gsaChartDispositivo" style="max-width:220px;max-height:220px;"></canvas>
            </div>
        </div>

        <!-- NAVEGADORES -->
        <div class="gsa-form-card" style="margin-bottom:0;">
            <div class="gsa-form-header">
                <div class="gsa-form-header-icon">
                    <span class="dashicons dashicons-admin-site"></span>
                </div>
                <div>
                    <h2>Navegadores</h2>
                    <span>Distribución por navegador</span>
                </div>
            </div>
            <div style="padding:18px;display:flex;justify-content:center;align-items:center;height:220px;">
                <canvas id="gsaChartNavegador" style="max-width:220px;max-height:220px;"></canvas>
            </div>
        </div>

    </div>

    <!-- GRÁFICO ROW 3 — HORARIOS ANCHO COMPLETO -->
    <div style="margin-bottom:20px;">
        <div class="gsa-form-card" style="margin-bottom:0;">
            <div class="gsa-form-header">
                <div class="gsa-form-header-icon">
                    <span class="dashicons dashicons-clock"></span>
                </div>
                <div>
                    <h2>Horarios de interacción</h2>
                    <span>Clics por hora del día</span>
                </div>
            </div>
            <div style="padding:18px;">
                <canvas id="gsaChartHora" height="100"></canvas>
            </div>
        </div>
    </div>


    <!-- TOP REDES -->
    <div class="gsa-form-card">
        <div class="gsa-form-header">
            <div class="gsa-form-header-icon">
                <span class="dashicons dashicons-awards"></span>
            </div>
            <div>
                <h2>Redes más utilizadas</h2>
                <span>Top 5 con mayor interacción</span>
            </div>
        </div>
        <div style="padding:18px 22px;">
            <?php if (empty($redes_top)): ?>
                <p style="color:var(--gsa-texto-suave);font-size:13px;">Aún no hay datos de clics registrados.</p>
            <?php else: ?>
                <?php foreach ($redes_top as $red): ?>
                    <div style="display:flex;align-items:center;gap:12px;margin-bottom:12px;">
                        <div style="width:36px;height:36px;border-radius:50%;background:<?php echo esc_attr($red->color); ?>;display:flex;align-items:center;justify-content:center;">
                            <i class="<?php echo esc_attr($red->icono); ?>" style="color:#fff;font-size:16px;"></i>
                        </div>
                        <div style="flex:1;">
                            <div style="font-size:13px;font-weight:700;color:var(--gsa-texto);"><?php echo esc_html($red->nombre); ?></div>
                            <div style="height:6px;background:var(--gsa-borde);border-radius:3px;margin-top:4px;">
                                <div style="height:6px;background:<?php echo esc_attr($red->color); ?>;border-radius:3px;width:<?php
                                    $max = max(array_column($redes_top, 'total'));
                                    echo $max > 0 ? round(($red->total / $max) * 100) : 0;
                                ?>%;"></div>
                            </div>
                        </div>
                        <div style="font-size:13px;font-weight:800;color:var(--gsa-azul);min-width:40px;text-align:right;">
                            <?php echo intval($red->total); ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>

</div>

<?php include GSA_PATH . 'admin/views/footer.php'; ?>

<!-- DATOS PARA CHART.JS -->
<script>
const gsaDatos = {
    dias: {
        labels: <?php echo json_encode(array_map(fn($r) => $r->dia, $clics_por_dia)); ?>,
        data:   <?php echo json_encode(array_map(fn($r) => intval($r->total), $clics_por_dia)); ?>
    },
    redes: {
        labels: <?php echo json_encode(array_map(fn($r) => $r->nombre, $clics_por_red)); ?>,
        data:   <?php echo json_encode(array_map(fn($r) => intval($r->total), $clics_por_red)); ?>,
        colors: <?php echo json_encode(array_map(fn($r) => $r->color, $clics_por_red)); ?>
    },
    dispositivos: {
        labels: <?php echo json_encode(array_map(fn($r) => ucfirst($r->dispositivo), $clics_dispositivo)); ?>,
        data:   <?php echo json_encode(array_map(fn($r) => intval($r->total), $clics_dispositivo)); ?>
    },
    horas: {
        labels: <?php echo json_encode(array_map(fn($r) => $r->hora . ':00', $clics_hora)); ?>,
        data:   <?php echo json_encode(array_map(fn($r) => intval($r->total), $clics_hora)); ?>
    },
    navegadores: {
        labels: <?php echo json_encode(array_map(fn($r) => $r->navegador, $clics_navegador)); ?>,
        data:   <?php echo json_encode(array_map(fn($r) => intval($r->total), $clics_navegador)); ?>
    }
    
};
</script>