<?php
if (!defined('WPINC')) {
    die('Closed');
}
if(defined('REGMAGIC_ADDON') && class_exists('RM_Reports_Controller_Addon') && method_exists('RM_Reports_Controller_Addon', 'notifications')) include_once(RM_ADDON_ADMIN_DIR . 'views/template_rm_reports_notification_add.php'); else {
?>
<div class="rmagic">
    <div class="rmagic-cards"><div class="rm-reports-no-data-found rmnotice rm-box-border rm-box-mb-25"><?php __('Need to update premium plugin to latest version.');?></div></div>
</div>
<?php } ?>