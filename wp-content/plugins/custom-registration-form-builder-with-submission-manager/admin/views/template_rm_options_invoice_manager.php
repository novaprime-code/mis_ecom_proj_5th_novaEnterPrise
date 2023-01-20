<?php
if (!defined('WPINC')) {
    die('Closed');
}
if(defined('REGMAGIC_ADDON') && class_exists('RM_Payments_Controller_Addon')) include_once(RM_ADDON_ADMIN_DIR . 'views/template_rm_options_invoice_manager.php'); else {
?>
<div class="rmagic">

    <!--Dialogue Box Starts-->
    <div class="rmcontent">

        <?php
        $rm_promo_banner_title = __('Unlock invoice and more by upgrading','custom-registration-form-builder-with-submission-manager');
        include RM_ADMIN_DIR . 'views/template_rm_promo_banner_bottom.php';
        ?>
    </div>
</div>
<?php } ?>