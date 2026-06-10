<?php if (!defined('ABSPATH')) exit; 
// Si el controlador por alguna razón no definió la variable, la creamos vacía aquí
if (!isset($red_a_editar)) {
    $red_a_editar = null;
}
?>
<div class="wrap">
    <h1>📦 GICA Social Analytics — Módulo 1</h1>
    
    <!-- Renderizado de alertas informativas -->
    <?php if (!empty($notificacion)) echo $notificacion; ?>
    
    <div style="display: flex; gap: 20px; margin-top: 20px;">
        
        <!-- FORMULARIO DINÁMICO (Izquierda) -->
        <div style="flex: 1; background: #fff; padding: 20px; border: 1px solid #ccd0d4; border-radius: 4px; height: fit-content;">
            <h2><?php echo $red_a_editar ? '📝 Editar Red Social' : '➕ Agregar Red Social'; ?></h2>
            
            <form method="POST" action="<?php echo esc_url(admin_url('admin.php?page=gsa-panel')); ?>">
                <?php wp_nonce_field('gsa_nonce_formulario', 'gsa_nonce_campo'); ?>
                
                <?php if ($red_a_editar): ?>
                    <!-- Campo oculto necesario para saber qué ID actualizar -->
                    <input type="hidden" name="red_id" value="<?php echo intval($red_a_editar->id); ?>">
                <?php endif; ?>

                <table class="form-table" style="width: 100%;">
                    <tr>
                        <th style="width: 30%;"><label>Nombre</label></th>
                        <td>
                            <input type="text" name="nombre" required class="regular-text" placeholder="Ej. WhatsApp"
                                   value="<?php echo $red_a_editar ? esc_attr($red_a_editar->nombre) : ''; ?>">
                        </td>
                    </tr>
                    <tr>
                        <th><label>URL Destino</label></th>
                        <td>
                            <input type="url" name="url" required class="regular-text" placeholder="https://wa.me/..."
                                   value="<?php echo $red_a_editar ? esc_url($red_a_editar->url) : ''; ?>">
                        </td>
                    </tr>
                    <tr>
                        <th><label>Dashicon</label></th>
                        <td>
                            <input type="text" name="icono" required class="regular-text" placeholder="dashicons-whatsapp"
                                   value="<?php echo $red_a_editar ? esc_attr($red_a_editar->icono) : ''; ?>">
                        </td>
                    </tr>
                    <tr>
                        <th><label>Color Hex</label></th>
                        <td>
                            <input type="color" name="color" 
                                   value="<?php echo $red_a_editar ? esc_attr($red_a_editar->color) : '#25D366'; ?>">
                        </td>
                    </tr>
                </table>
                
                <div style="margin-top: 15px; display: flex; gap: 10px;">
                    <?php 
                    $texto_boton = $red_a_editar ? 'Actualizar Red' : 'Registrar Red';
                    $clase_boton = $red_a_editar ? 'button-secondary' : 'button-primary';
                    submit_button($texto_boton, $clase_boton, 'gsa_guardar_red_submit', false); 
                    ?>
                    
                    <?php if ($red_a_editar): ?>
                        <a href="<?php echo esc_url(admin_url('admin.php?page=gsa-panel')); ?>" class="button">Cancelar Edición</a>
                    <?php endif; ?>
                </div>
            </form>
        </div>

        <!-- TABLA DE CONTROL CON ACCIONES CRUD (Derecha) -->
        <div style="flex: 1.5; background: #fff; padding: 20px; border: 1px solid #ccd0d4; border-radius: 4px;">
            <h2>Redes Activas en Sistema</h2>
            <table class="wp-list-table widefat fixed striped">
                <thead>
                    <tr>
                        <th style="width: 20%;">Nombre</th>
                        <th style="width: 35%;">URL Destino</th>
                        <th style="width: 15%;">Color</th>
                        <th style="width: 15%;">Estado</th>
                        <th style="width: 15%;">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($redes_registradas)): ?>
                        <tr><td colspan="5">No hay registros almacenados.</td></tr>
                    <?php else: foreach ($redes_registradas as $red): ?>
                        <tr>
                            <td><strong><?php echo esc_html($red->nombre); ?></strong></td>
                            <td><code style="font-size: 11px;"><?php echo esc_url($red->url); ?></code></td>
                            <td>
                                <span style="display:inline-block; width:12px; height:12px; background:<?php echo esc_attr($red->color); ?>; border-radius:50%; vertical-align:middle; margin-right:5px;"></span> 
                                <?php echo esc_html($red->color); ?>
                            </td>
                            <td>
                                <!-- Enlace Seguro para Alternar Estado -->
                                <?php 
                                $url_toggle = wp_nonce_url(admin_url('admin.php?page=gsa-panel&action=toggle&id=' . $red->id), 'gsa_toggle_' . $red->id); 
                                ?>
                                <a href="<?php echo esc_url($url_toggle); ?>" style="text-decoration: none;">
                                    <?php echo $red->estado ? '<span style="color:green; font-weight:bold;">🟢 Activo</span>' : '<span style="color:red; font-weight:bold;">🔴 Inactivo</span>'; ?>
                                </a>
                            </td>
                            <td>
                                <!-- Enlace Seguro para Editar -->
                                <?php 
                                $url_edit = wp_nonce_url(admin_url('admin.php?page=gsa-panel&action=edit&id=' . $red->id), 'gsa_edit_' . $red->id); 
                                ?>
                                <a href="<?php echo esc_url($url_edit); ?>" class="button button-small" style="margin-right:3px;">Editar</a>
                                
                                <!-- Enlace Seguro para Eliminar -->
                                <?php 
                                $url_delete = wp_nonce_url(admin_url('admin.php?page=gsa-panel&action=delete&id=' . $red->id), 'gsa_delete_' . $red->id); 
                                ?>
                                <a href="<?php echo esc_url($url_delete); ?>" class="button button-small button-link-delete" 
                                   style="color:red;" onclick="return confirm('¿Seguro que deseas eliminar la red <?php echo esc_js($red->nombre); ?>?');">
                                    Borrar
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>