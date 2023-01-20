<?php
if (!defined('WPINC')) {
    die('Closed');
}
wp_enqueue_script('chart_js');
wp_enqueue_style( 'rm_material_icons', RM_BASE_URL . 'admin/css/material-icons.css' );
if(defined('REGMAGIC_ADDON')) {
    include_once(RM_ADDON_ADMIN_DIR . 'views/template_rm_reports_dashboard.php'); 
}
else{
?>
<div class="rmagic">
    <div class="rmagic-reports rm-box-white-bg rm-box-border">
        <div class="rm-box-wrap rm-reports-title rm-box-mb-25">
                <div class="rm-box-row ">
                    <div class="rm-box-col-9">
                        <div class="rm-reports-page-title">
                            <?php _e('RegistrationMagic Reports', 'custom-registration-form-builder-with-submission-manager'); ?>
                        </div>
                    </div>

                    <div class="rm-box-col-3 rm-box-text-right">
                        
                        <div class="rm-reports-card email-report-schedule-wrap rm-locked-section" data-target="rm-notification-form"> 
                                <div class="rm-reports-card-overlay" data-target="rm-notification-form" style="display: none;"></div>
                                <div id="rm-notification-form" class="rm-submission-value rm-add-custom-status-value rm-report-premium-card rm-premium-card-right" style="display:none">                        
                                    <span class="rm-custom-status-box-nub"></span>
                                    <span class="rm_buy_pro_inline"><?php printf(__('Schedule reports and have them delivered to your email inbox! To use this feature, please upgrade. <a href="%s" target="blank">Click here</a>', 'custom-registration-form-builder-with-submission-manager'), RM_Utilities::comparison_page_link()); ?> </span>

                                </div>
                          
                                <div class="rm-reports-card-content rm-email-report-action rm-box-border rm-box-white-bg">
                                    <div class="rm-box-premium" style="visibility: hidden; display:none" ><span class="material-icons"> workspace_premium </span> Premium</div>
                                    <?php _e('Schedule Email Reports', 'custom-registration-form-builder-with-submission-manager'); ?> <span class="material-icons"> mail_outline </span>
                                </div>
                        </div>    
                        
                        
                        
                        
                        
                        
                        
                        
                        
                        
<!--                        <a href="<?php echo admin_url('admin.php?page=rm_reports_notifications'); ?>" class="rm-email-report-action rm-box-border rm-box-white-bg"><?php _e('Schedule Email Reports','custom-registration-form-builder-with-submission-manager');?> <span class="material-icons"> mail_outline </span></a>-->
                    </div>
                </div> 
            </div>


        <div class="rm-reports-list rm-box-wrap rm-box-mb-25">
            <div class="rm-reports-card rm-box-white-bg rm-box-border">
                <a href="<?php echo admin_url('?page=rm_reports_submissions'); ?>">
                    <div class="rm-reports-card-icon rm-box-ptb"><img src="<?php echo RM_IMG_URL.'svg/inbox.svg'?>"></div>
                    <div class="rm-reports-card-content">
                        <div class="rm-box-premium" style="visibility: hidden;"><span class="material-icons"> workspace_premium </span> Premium</div>
                            <h3 class=""><?php _e('Form Submissions','custom-registration-form-builder-with-submission-manager');?></h3>
                            <p class=""><?php _e('Generates a report for submissions recorded within the selected time period. All field values, along with meta data like submission time are available.','custom-registration-form-builder-with-submission-manager');?></p>
                    </div>
                </a>
            </div>
            
            <div class="rm-reports-card rm-box-white-bg rm-box-border">
                <a href="<?php echo admin_url('?page=rm_reports_login'); ?>">
                    <div class="rm-reports-card-icon rm-box-ptb"><img src="<?php echo RM_IMG_URL.'svg/login.svg'?>"></div>
                    <div class="rm-reports-card-content">
                        <div class="rm-box-premium" style="visibility: hidden;"><span class="material-icons"> workspace_premium </span> Premium</div>
                        <h3 class=""><?php _e('Login Records','custom-registration-form-builder-with-submission-manager');?></h3>
                        <p class=""><?php _e('Generates a report with login records for the selected time period. Both successful and failed attempts are available as filters. Data is recorded through RegistrationMagic login form.','custom-registration-form-builder-with-submission-manager');?></p>
                    </div>
                </a>
            </div>
            
            
                <div class="rm-reports-card rm-box-white-bg rm-box-border rm-locked-section" data-target="rm-attachment-report">
                    <div class="rm-reports-card-overlay"  data-target="rm-attachment-report" style="display: none;"></div>
                    <div id="rm-attachment-report" class="rm-submission-value rm-add-custom-status-value rm-report-premium-card" style="display:none">
                        <span class="rm-custom-status-box-nub"></span>
                        <span class="rm_buy_pro_inline"><?php printf(__('To unlock Attachments Report (and many more), please upgrade <a href="%s" target="blank">Click here</a>', 'custom-registration-form-builder-with-submission-manager'), RM_Utilities::comparison_page_link()); ?> </span>
                  
                    </div>
                    <div class="rm-reports-card-icon rm-box-ptb"><img src="<?php echo RM_IMG_URL . 'svg/attachment.svg' ?>"></div>
                    <div class="rm-reports-card-content">
                        <div class="rm-box-premium" style="visibility: hidden;"><span class="material-icons"> workspace_premium </span> Premium</div>
                        <h3 class=""><?php _e('Attachments', 'custom-registration-form-builder-with-submission-manager'); ?></h3>
                        <p class=""><?php _e('Displays breakdown of file types received. An option to download all files attached to a form during selected time period, as a single zip is also available.', 'custom-registration-form-builder-with-submission-manager'); ?></p>
                    </div>
                </div>
                <div class="rm-reports-card rm-box-white-bg rm-box-border rm-locked-section" data-target="rm-payment-report">
                    <div class="rm-reports-card-overlay " data-target="rm-payment-report" style="display: none;"></div>
                    <div id="rm-payment-report" class="rm-submission-value rm-add-custom-status-value rm-report-premium-card" style="display:none">
                        <span class="rm-custom-status-box-nub"></span>
                        <span class="rm_buy_pro_inline"><?php printf(__('To unlock Payments Report (and many more), please upgrade  <a href="%s" target="blank">Click here</a>', 'custom-registration-form-builder-with-submission-manager'), RM_Utilities::comparison_page_link()); ?> </span>

                    </div>
                    <div class="rm-reports-card-icon rm-box-ptb"><img src="<?php echo RM_IMG_URL . 'svg/payment.svg' ?>"></div>
                    <div class="rm-reports-card-content">
                        <div class="rm-box-premium" style="visibility: hidden;"><span class="material-icons"> workspace_premium </span> Premium</div>
                        <h3 class=""><?php _e('Payments', 'custom-registration-form-builder-with-submission-manager'); ?></h3>
                        <p class=""><?php _e('Compiles payment records for all payments made from the selected form within selected time period. Includes additional filter for payment status.', 'custom-registration-form-builder-with-submission-manager'); ?></p>
                    </div>
                </div>
                <div class="rm-reports-card rm-box-white-bg rm-box-border rm-locked-section" data-target="rm-compare-form"> 
                    <div class="rm-reports-card-overlay" data-target="rm-compare-form" style="display: none;"></div>
                    <div id="rm-compare-form" class="rm-submission-value rm-add-custom-status-value rm-report-premium-card rm-premium-card-right" style="display:none">                        
                        <span class="rm-custom-status-box-nub"></span>
                        <span class="rm_buy_pro_inline"><?php printf(__('To unlock Form Comparison Report (and many more), please upgrade <a href="%s" target="blank">Click here</a>', 'custom-registration-form-builder-with-submission-manager'), RM_Utilities::comparison_page_link()); ?> </span>

                    </div>
                    <div class="rm-reports-card-icon rm-box-ptb"><img src="<?php echo RM_IMG_URL . 'svg/compare-forms.svg' ?>"></div>
                    <div class="rm-reports-card-content">
                        <div class="rm-box-premium" style="visibility: hidden;"><span class="material-icons"> workspace_premium </span> Premium</div>
                        <h3 class=""><?php _e('Form Comparison', 'custom-registration-form-builder-with-submission-manager'); ?></h3>
                        <p class=""><?php _e('A side-by-side comparison table of two selected forms based on their different performance parameters.', 'custom-registration-form-builder-with-submission-manager'); ?></p>
                    </div>

                </div>
 
        </div>
    </div>
</div>
<?php } ?>


<script>
    
jQuery(document).ready(function(){
//    jQuery('.rm-reports-card.rm-locked-section').on('click', function(e) {
//        jQuery('.rm-add-custom-status-value').toggle();
//    });
    
  jQuery(".rm-reports-card.rm-locked-section").click(function(){
  jQuery('#' +  jQuery(this).data('target')).toggle();
  jQuery(this).toggleClass('rm-reports-open');

});




   
});

</script>
