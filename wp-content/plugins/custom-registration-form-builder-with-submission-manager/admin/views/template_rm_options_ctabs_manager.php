<?php
if (!defined('WPINC')) {
    die('Closed');
}
if(defined('REGMAGIC_ADDON') && version_compare(RM_ADDON_PLUGIN_VERSION,'5.1.2.0','>=')) {
	include_once(RM_ADDON_ADMIN_DIR . 'views/template_rm_options_ctabs_manager.php');
}
else{
	?>
	<?php
if (!defined('WPINC')) {
    die('Closed');
}
wp_enqueue_style( 'rm_material_icons', RM_BASE_URL . 'admin/css/material-icons.css' );
?>
<div class="rmagic">

    <!-----Operations bar Starts----->

    <div class="operationsbar">
        <div class="rmtitle"><?php _e('Customize User Area Tab','custom-registration-form-builder-with-submission-manager');?></div>
        <div class="icons">
            <a href="?page=rm_options_manage"><img alt="" src="<?php echo RM_IMG_URL . 'global-settings.png'; ?>"></a>
        </div>
        <div class="nav">
            <ul>
                <li onclick="window.history.back()"><a href="javascript:void(0)"><?php echo RM_UI_Strings::get("LABEL_BACK"); ?></a></li>
                
                <li class="rm-form-toggle rm-tabs-reorder"><a href="<?php echo admin_url('admin.php?page=rm_options_tabs');?>"><span class="rm-profile-tab-reorder-info"></span> <?php _e('Reorder Tabs','custom-registration-form-builder-with-submission-manager');?></a></li>
            </ul>
        </div>
        
    </div>

    <?php 
    if(defined('REGMAGIC_ADDON') && version_compare(RM_ADDON_PLUGIN_VERSION,'5.1.2.0','<')){
        ?>
        <div class="rm-upgrade-note-gold rm-notice-to-update-plugin">        
            <div class="rm-banner-title"><?php _e('Update premium plugin to use this feature.','custom-registration-form-builder-with-submission-manager');?></div>
        </div>
        <?php
    }
    else
    {
    $rm_promo_banner_title = __('Unlock Custom User Area Tabs by upgrading to ','custom-registration-form-builder-with-submission-manager');
    include RM_ADMIN_DIR.'views/template_rm_promo_banner_bottom.php';
    }
    ?>
    <style>
        .rm-upgrade-note-gold.rm-notice-to-update-plugin .rm-banner-title:before {
            display: none;
        }
        .rm-upgrade-note-gold.rm-notice-to-update-plugin .rm-banner-title {
            padding: 30px;
            font-weight: 600;
            color: red;
            font-size: 16px;
        }
    </style>
    
</div>
<?php
}
?>