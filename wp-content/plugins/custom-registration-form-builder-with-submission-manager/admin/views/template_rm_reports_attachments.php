<?php
if (!defined('WPINC')) {
    die('Closed');
}
wp_enqueue_script('chart_js');
wp_enqueue_script('script_rm_moment');
wp_enqueue_script('script_rm_daterangepicker');
wp_enqueue_style('style_rm_daterangepicker');
if(defined('REGMAGIC_ADDON')) {
    include_once(RM_ADDON_ADMIN_DIR . 'views/template_rm_reports_attachments.php');
}
else{
?>
    <div class="rmagic">
        <div class="rmagic-reports">
            <div class="rm-reports-dashboard">
                <h3><?php _e('Attachments Reports','custom-registration-form-builder-with-submission-manager');?></h3>
            </div>
            <div class="rm-locked-section">
                <div class="rmagic-cards"><div class="rm-reports-no-data-found rmnotice rm-box-border rm-box-mb-25"><?php _e('Buy premium to unlock this feature.','custom-registration-form-builder-with-submission-manager');?></div></div>
            </div>
        </div>
    </div>
<?php } ?>
