<?php
if (!defined('WPINC')) {
    die('Closed');
}

?>
<!-- Legends -->
<div class="rm-sub-legends">    
    <div class="rm-subsection-heading"><?php echo wp_kses_post(RM_UI_Strings::get('LABEL_LEGEND')); ?></div>

    <div class="rm-sub-legends-row">
        <div class="rm-legend-wrap">
            <div class="rm-legend-img">   
                <img  class="rm_submission_icon" alt="" src="<?php echo esc_url(plugin_dir_url(dirname(dirname(__FILE__))) . 'images/pending_payment.png'); ?>"> <span class="rm-legend-text"><?php echo wp_kses_post(RM_UI_Strings::get('LABEL_LEGEND_PAYMENT_PENDING')); ?></span>
            </div>
        </div>

        <div class="rm-legend-wrap">
            <div class="rm-legend-img">
                <img  class="rm_submission_icon" alt="" src="<?php echo esc_url(plugin_dir_url(dirname(dirname(__FILE__))) . 'images/payment_completed.png'); ?>"> <span class="rm-legend-text"><?php echo wp_kses_post(RM_UI_Strings::get('LABEL_LEGEND_PAYMENT_COMPLETED')); ?></span>
            </div>
        </div>
        
        <div class="rm-legend-wrap">
            <div class="rm-legend-img">   
                <img  class="rm_submission_icon" alt="" src="<?php echo esc_url(plugin_dir_url(dirname(dirname(__FILE__))) . 'images/refunded_payment.png'); ?>"> <span class="rm-legend-text"><?php echo wp_kses_post(RM_UI_Strings::get('LABEL_LEGEND_PAYMENT_REFUNDED')); ?></span>
            </div>
        </div>
        
        <div class="rm-legend-wrap">
            <div class="rm-legend-img">   
                <img  class="rm_submission_icon" alt="" src="<?php echo esc_url(plugin_dir_url(dirname(dirname(__FILE__))) . 'images/canceled_payment.png'); ?>"> <span class="rm-legend-text"><?php echo wp_kses_post(RM_UI_Strings::get('LABEL_LEGEND_PAYMENT_CANCELED')); ?></span>
            </div>
        </div>
        
        
    </div>

</div>
<!-- Legends End -->
