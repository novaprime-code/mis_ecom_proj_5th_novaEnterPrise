<?php
if (!defined('WPINC')) {
    die('Closed');
}
if(defined('REGMAGIC_ADDON')) include_once(RM_ADDON_ADMIN_DIR . 'views/template_rm_formflow_main.php'); else {

$build_page_style = $config_page_style = $publish_page_style = 'style="display:none;"';
$build_step_class = $config_step_class = $publish_step_class = '';

$normalized_form_name = function_exists("mb_strimwidth")? mb_strimwidth($data->form_name, 0, 22, "..."): $data->form_name;

switch($data->active_step) {
    
    case 'config':
        $config_page_style = "";
        $config_step_class = "rm-wizard-activated";
        break;
    
    case 'publish':
        $publish_page_style = "";
        $publish_step_class = "rm-wizard-activated";
        break;
    
    default:
        $build_page_style = "";
        $build_step_class = "rm-wizard-activated";
        break;
}

wp_enqueue_style('rm_form_dashboard_css', RM_BASE_URL . 'admin/css/style_rm_form_dashboard.css');
wp_enqueue_style('rm_formflow_css', RM_BASE_URL . 'admin/css/style_rm_formflow.css');
if(defined('REGMAGIC_ADDON')) {
    wp_enqueue_style('rm_addon_form_dashboard_css', RM_ADDON_BASE_URL . 'admin/css/style_rm_form_dashboard.css');
    wp_enqueue_style('rm_addon_formflow_css', RM_ADDON_BASE_URL . 'admin/css/style_rm_formflow.css');
}
wp_enqueue_script('rm-formflow');
wp_enqueue_style( 'rm_material_icons', RM_BASE_URL . 'admin/css/material-icons.css' );
?>
<div class="rm-formflow-top-bar">
 
     <!-- Step 1 -->
     <div class="rm-formflow-top-section" style="text-align: left">
         <div class="rm-formflow-top-action" >
             <span class="rm-formflow-top-left"><a href="<?php echo admin_url( 'admin.php?page=rm_form_manage'); ?>"><i class="material-icons">keyboard_arrow_left</i><?php _e('All Forms','custom-registration-form-builder-with-submission-manager'); ?></a></span>
         </div>
     </div>
     <!-- Step 1 -->
 
     <!-- Step 2 -->
     <div class="rm-formflow-top-section" style="text-align: center">
         <div class="rm-formflow-top-action rm-form-design-wrap rm-formflow-top-action-center" >
             <ul class="rm-di-flex rm-d-flex-v-center rm-form-design-wrap">
                    <?php
                    $design_link_class = $design_link_tooltip = "";
                    if($data->theme == 'classic') {
                        $design_link_class = "class='rm_deactivated'";
                        $design_link_tooltip = __('Form design customization is not applicable for Classic theme. To enable please change theme in Global Settings >> General Settings.', 'custom-registration-form-builder-with-submission-manager');
                    }
                    ?>
                    <li title="<?php echo esc_attr($design_link_tooltip); ?>"><a <?php echo wp_kses_post($design_link_class); ?> href="?page=rm_form_sett_view&rdrto=rm_field_manage&rm_form_id=<?php echo esc_attr($data->form_id); ?>"><?php _e('Design','custom-registration-form-builder-with-submission-manager'); ?></a></li>
                    <li><a id="rm_form_preview_action" class="thickbox rm_form_preview_btn" href="<?php echo add_query_arg(array('form_prev' => '1','form_id' => $data->form_id), get_permalink($data->prev_page)); ?>&TB_iframe=true&width=900&height=600"><?php _e('Preview','custom-registration-form-builder-with-submission-manager'); ?></a></li>
                </ul>
         </div>
     </div>
     <!-- Step 2 -->
 
     <!-- Step 3 -->
     <div class="rm-formflow-top-section" style="text-align: right">
         <div class="rm-formflow-top-action rm-formflow-top-action-right" >
             
             <span class="rm-formflow-top-right"><a href="<?php echo admin_url( 'admin.php?page=rm_form_sett_manage&rm_form_id='.$data->form_id); ?>"><?php _e('Form Dashboard','custom-registration-form-builder-with-submission-manager'); ?> <i class="material-icons">keyboard_arrow_right</i></a></span>
         </div>
     </div>
 
 </div>

<div id="rm_formflow_build" class="rm_formflow_page" <?php echo wp_kses_post($build_page_style); ?> >
<?php if($data->row_eligible) include RM_ADMIN_DIR."views/template_rm_field_manager_new.php"; else include RM_ADMIN_DIR."views/template_rm_field_manager.php"; ?>
</div>

<div class="rm-formflow-top-bar">

    <!-- Step 1 -->
    <div class="rm-formflow-top-section" style="text-align: left">
        <div class="rm-formflow-top-action" >
            <span class="rm-formflow-top-left"><a href="<?php echo admin_url('admin.php?page=rm_form_manage'); ?>"><i class="material-icons">keyboard_arrow_left</i> <?php _e('All Forms', 'custom-registration-form-builder-with-submission-manager'); ?></a></span>
        </div>
    </div>
    <!-- Step 1 -->

    <!-- Step 2 -->
    <div class="rm-formflow-top-section" style="text-align: center">
        <div class="rm-formflow-top-action  rm-formflow-top-action-center" >

            <span >&nbsp;</span>
        </div>
    </div>
    <!-- Step 2 -->

    <!-- Step 3 -->
    <div class="rm-formflow-top-section" style="text-align: right">
        <div class="rm-formflow-top-action rm-formflow-top-action-right" >

            <span class="rm-formflow-top-right"><a href="<?php echo admin_url('admin.php?page=rm_form_sett_manage&rm_form_id=' . $data->form_id); ?>"><?php _e('Form Dashboard', 'custom-registration-form-builder-with-submission-manager'); ?> <i class="material-icons">keyboard_arrow_right</i></a></span>
        </div>
    </div>

</div>

<?php $current_page= isset($_GET['page']) ? sanitize_text_field($_GET['page']) : ''; ?>
<?php if($current_page!='rm_field_manage') : ?>
    <div id="rm_formflow_publish" class="rm_formflow_page" <?php echo wp_kses_post($publish_page_style); ?> >
    <?php include RM_ADMIN_DIR."views/template_rm_formflow_publish.php"; ?>
    </div>
<?php endif; } ?>


