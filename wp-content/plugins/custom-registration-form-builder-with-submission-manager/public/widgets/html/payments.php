<?php
if (!defined('WPINC')) {
    die('Closed');
}
if(defined('REGMAGIC_ADDON')) include_once(RM_ADDON_PUBLIC_DIR . 'widgets/html/payments.php'); else {
$i=1;
foreach ($payments as $payment) {
    ?>
<div class="rm-submission-card">
        <div class="rm-transaction-title rm-pad-10 dbfl">
            <div class="rm-transaction-form-name difl"> <?php echo esc_html($payment->form_name); ?></div>
            <span class="rm_txn_status rm-transaction-<?php echo esc_attr($payment->status); ?> difr rm-rounded-corners"><?php echo esc_html($payment->status); ?></span>
        </div>
        <div class="rm-transaction-card-content dbfl">
            <!----<div class="rm-submission-icon difl">
             <img src="<?php echo RM_IMG_URL; ?>submission-clock.png">
             </div>---->
        <div class="rm-transaction-amount dbfl">
                    <div class="difl rm-transaction-payment-info"><?php _e('Amount','custom-registration-form-builder-with-submission-manager');?> <?php echo esc_html($payment->total_amount); ?> </div>
                <div class="difr rm-transaction-info" id="info-<?php echo esc_attr($i) ?>"><?php _e('Details','custom-registration-form-builder-with-submission-manager'); ?>
                    <span class="rm-details-arrow-up difr"><i class="material-icons">&#xE5C7;</i></span>
                    <span class="rm-details-arrow-down difr"><i class="material-icons">&#xE5C5;</i></span>
                 
                </div>
                </div>
            
                 <div class="rm-transaction-details-wrap rm-white-box dbfl" id="rm-detail-info-<?php echo esc_attr($i) ?>" style="display:none">
            <div class="rm-pad-10 dbfl"><b><?php echo RM_UI_Strings::get('LABEL_DATE_OF_PAYMENT'); ?></b> <?php echo esc_html($payment->posted_date); ?></div>
            <div class="rm-pad-10 dbfl"><b><?php echo RM_UI_Strings::get('LABEL_INVOICE'); ?></b> <?php echo esc_html($payment->invoice); ?></div>
            <div class="rm-pad-10 dbfl"><b><?php echo RM_UI_Strings::get('LABEL_TAXATION_ID'); ?></b> <?php echo esc_html($payment->txn_id); ?></div>
            
                 </div>
            
            
            
            
        </div>
    </div>
<?php $i++; } } ?>