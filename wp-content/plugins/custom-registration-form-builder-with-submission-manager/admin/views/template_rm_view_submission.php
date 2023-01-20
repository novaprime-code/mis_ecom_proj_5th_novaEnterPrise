<?php
if (!defined('WPINC')) {
    die('Closed');
}
if(defined('REGMAGIC_ADDON')) include_once(RM_ADDON_ADMIN_DIR . 'views/template_rm_view_submission.php'); else {
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
wp_enqueue_style( 'rm_material_icons', RM_BASE_URL . 'admin/css/material-icons.css' );
?>
<div class="rmagic">
    <?php 
    ?>
    <!-----Operations bar Start-->

    <div class="operationsbar">
        <div class="rmtitle"><span class="rmtitle-from"><?php echo wp_kses_post(RM_UI_Strings::get('TEXT_FROM')).': </span>'. $data->submission->get_user_email(); ?></div>
        <div class="icons">
            <a href="?page=rm_options_manage"><img alt="" src="<?php echo esc_url(plugin_dir_url(dirname(dirname(__FILE__))) . 'images/global-settings.png'); ?>"></a>

        </div>
        <div class="nav">
            <ul>
                <li><a href="?page=rm_submission_manage&rm_form_id=<?php echo esc_attr($data->submission->get_form_id()); ?>"><?php echo wp_kses_post(RM_UI_Strings::get("LABEL_BACK")); ?></a></li>
                <li><a class="rm_deactivated" href="javascript:void(0)"><?php echo wp_kses_post(RM_UI_Strings::get("LABEL_ADD_NOTE")); ?></a></li>
                <li><a class="rm_deactivated" href="javascript:void(0)"><?php echo wp_kses_post(RM_UI_Strings::get("LABEL_PRINT")); ?></a></li>
                <li><a href="?page=rm_submission_view&rm_submission_id=<?php echo esc_attr($data->submission->get_submission_id()); ?>&rm_action=delete"><?php echo wp_kses_post(RM_UI_Strings::get("LABEL_DELETE")); ?></a></li>
                 <li><a class="rm_deactivated" href="javascript:void(0)"><?php echo wp_kses_post(RM_UI_Strings::get("LABEL_BLOCK_EMAIL")); ?></a></li>
                 <li><a class="rm_deactivated" href="javascript:void(0)"><?php echo wp_kses_post(RM_UI_Strings::get("LABEL_BLOCK_IP")); ?></a></li>
                <li><a class="rm_deactivated" href="javascript:void(0)"><?php echo wp_kses_post(RM_UI_Strings::get("LABEL_SEND_MESSAGE")); ?></a></li>
                 <?php if($data->related > 0){ ?>
               <li><a href="?page=rm_submission_related&rm_user_email=<?php echo esc_attr($data->submission->get_user_email()); ?>&rm_submission_id=<?php echo esc_attr($data->submission->get_submission_id()); ?>"><?php echo wp_kses_post(RM_UI_Strings::get("LABEL_RELATED")).' ('.esc_html($data->related).')'; ?></a></li>
                <?php } else { ?>
                <li><a class="rm_deactivated" href="javascript:void(0)"><?php echo RM_UI_Strings::get("LABEL_NO_RELATED"); ?></a></li>
                <?php } ?>
            </ul>
        </div>

    </div>
    <!--****Operations bar Ends**-->

    <!--**Content area Starts**-->
    <div class="rm-submission rm-veiw-submission"> 
        
               <div class="rm-submission-field-row rm-submission-status-row">
                <div class="rm-submission-label rm-custom_status-wrap">
                    <div class="rm-custom-status-lf"><div class="rm-no-status-assigned"> <?php _e('No Status Assigned','custom-registration-form-builder-with-submission-manager') ?> </div></div>
                    <div class="rm-submission-value rm-custom-status-lr">
                        <span class="rm-add-custom-status"><span class="dashicons dashicons-plus-alt"></span></span>
                        <div class="rm-submission-value rm-add-custom-status-value" style="display:none">
                            <span class="rm-custom-status-box-nub"></span>
                           <span class="rm_buy_pro_inline"><?php printf(__('To unlock Custom Statuses (and many more), please upgrade <a href="%s" target="blank">Click here</a>','custom-registration-form-builder-with-submission-manager'),RM_Utilities::comparison_page_link()); ?> </span>
                           <div class="rm-custom-status-ext-link"><a href="https://registrationmagic.com/wordpress-user-registration-status-guide/" target="blank"><?php _e('Learn how to boost your productivity with Custom Statuses!','custom-registration-form-builder-with-submission-manager') ?></a></div>
                            </div>
                    </div>
                </div>
             
            </div>
        
        

        <form method="post" action="" name="rm_view_submission" id="rm_view_submission_page_form">
            <input type="hidden" name="rm_slug" value="" id="rm_slug_input_field">

            <?php
            if ($data->form_is_unique_token)
            {
                ?>
                <div class="rm-submission-field-row">
                    <div class="rm-submission-label"><?php echo wp_kses_post(RM_UI_Strings::get('LABEL_UNIQUE_TOKEN_SHORT')); ?> :</div>
                    <div class="rm-submission-value rm-submission-metavalue"><?php echo esc_html($data->submission->get_unique_token()); ?></div>
                </div>
                <?php
            }
            ?>

            <div class="rm-submission-field-row">
                <div class="rm-submission-label"><?php echo wp_kses_post(RM_UI_Strings::get('LABEL_ENTRY_ID')); ?></div>
                <div class="rm-submission-value rm-submission-metavalue"><?php echo esc_html($data->submission->get_submission_id()); ?></div>
            </div>

            <div class="rm-submission-field-row">
                <div class="rm-submission-label"><?php echo wp_kses_post(RM_UI_Strings::get('LABEL_ENTRY_TYPE')); ?></div>
                <div class="rm-submission-value rm-submission-metavalue"><?php echo esc_html($data->form_type); ?></div>
            </div>
            
            <div class="rm-submission-field-row">
                <div class="rm-submission-label"><?php echo wp_kses_post(RM_UI_Strings::get('LABEL_SUBMITTED_ON')); ?></div>
                <div class="rm-submission-value rm-submission-metavalue"><?php echo esc_html(RM_Utilities::localize_time($data->submission->get_submitted_on())); ?></div>
            </div>
            
            <?php
            if ($data->form_type_status == "1" && !empty($data->user))
            {
                $user_roles_dd = RM_Utilities::user_role_dropdown();
                ?>
                <div class="rm-submission-field-row">
                    <div class="rm-submission-label"><?php echo wp_kses_post(RM_UI_Strings::get('LABEL_DISPLAY_NAME')); ?></div>
                    <div class="rm-submission-value"><?php echo esc_html($data->user->display_name); ?></div>
                </div>

                <div class="rm-submission-field-row">
                    <div class="rm-submission-label"><?php echo wp_kses_post(RM_UI_Strings::get('LABEL_USER_ROLES')); ?></div>
                    <div class="rm-submission-value">
                        <?php
                        if(isset($data->user->roles[0],$user_roles_dd[$data->user->roles[0]]))
                            echo esc_html($user_roles_dd[$data->user->roles[0]]);
                        else
                            echo "<em>".wp_kses_post(RM_UI_Strings::get('MSG_USER_ROLE_NOT_ASSIGNED'))."</em>";
                        ?>
                    </div>
                </div>

                <?php
            }
            ?>
            <?php
            $submission_data = $data->submission->get_data();
         
            if (is_array($submission_data) || $submission_data)
                foreach ($submission_data as $field_id => $sub):

                    $sub_key = $sub->label;
                    $sub_data = $sub->value;
                    if(!isset($sub->type)){
                                $sub->type = '';
                            }
                    ?>

                    <!--submission row block-->
                    <?php if(!is_null($sub_data) && $sub_data != ''){ ?>
                    <div class="rm-submission-field-row">
                        <div class="rm-submission-label"><?php echo esc_html($sub_key); ?></div>
                        <div class="rm-submission-value">
                            <?php
                            //if submitted data is array print it in more than one row.
                            
                            if (is_array($sub_data)) {

                                //If submitted data is a file.

                                if (isset($sub_data['rm_field_type']) && $sub_data['rm_field_type'] == 'File') {
                                    unset($sub_data['rm_field_type']);

                                    foreach ($sub_data as $sub) {

                                        $att_path = get_attached_file($sub);
                                        $att_url = wp_get_attachment_url($sub);
                                        ?>
                                        <div class="rm-submission-attachment">
                                            <?php echo wp_get_attachment_link($sub, 'thumbnail', false, true, false); ?>
                                            <div class="rm-submission-attachment-field"><?php echo esc_html(basename($att_path)); ?></div>
                                            <div class="rm-submission-attachment-field"><a href="<?php echo esc_url($att_url); ?>"><?php echo wp_kses_post(RM_UI_Strings::get('LABEL_DOWNLOAD')); ?></a></div>
                                        </div>

                                        <?php
                                    }
                                } elseif (isset($sub_data['rm_field_type']) && $sub_data['rm_field_type'] == 'Address') {
                                    $sub = $sub_data['original'] . '<br/>';
                                    if (count($sub_data) === 8) {
                                        $sub .= '<b>'.__('Street Address','custom-registration-form-builder-with-submission-manager').'</b> : ' . $sub_data['st_number'] . ', ' . $sub_data['st_route'] . '<br/>';
                                        $sub .= '<b>'.__('City','custom-registration-form-builder-with-submission-manager').'</b> : ' . $sub_data['city'] . '<br/>';
                                        $sub .= '<b>'.__('State','custom-registration-form-builder-with-submission-manager').'</b> : ' . $sub_data['state'] . '<br/>';
                                        $sub .= '<b>'.__('Zip Code','custom-registration-form-builder-with-submission-manager').'</b> : ' . $sub_data['zip'] . '<br/>';
                                        $sub .= '<b>'.__('Country','custom-registration-form-builder-with-submission-manager').'</b> : ' . $sub_data['country'];
                                    }
                                    echo wp_kses_post($sub);
                                }  elseif ($sub->type == 'Time') {                                  
                                    echo esc_html($sub_data['time']).", ".__("Timezone",'custom-registration-form-builder-with-submission-manager').": ".esc_html($sub_data['timezone']);
                                } elseif ($sub->type == 'Checkbox') {   
                                    echo esc_html(implode(', ',RM_Utilities::get_lable_for_option($field_id, $sub_data)));
                                }
                                //If submitted data is a Star Rating.
                                
                                
                                
                                else {
                                    $field_data = implode(', ', $sub_data);
                                    if($sub->type=="Repeatable"):
                                        $field_data = '<pre>'.implode('<hr> ', $sub_data).'</pre>';
                                    endif;
                                    
                                    echo wp_kses_post($field_data);
                                }
                            } else {
                                
                                if($sub->type == 'Rating')
                                {
                                    echo '<div class="rateit" id="rateit5" data-rateit-min="0" data-rateit-max="5" data-rateit-value="'.esc_attr($sub->value).'" data-rateit-ispreset="true" data-rateit-readonly="true"></div>';
                                }
                                elseif ($sub->type == 'Radio' || $sub->type == 'Select') {   
                                    echo esc_html(RM_Utilities::get_lable_for_option($field_id, $sub_data));
                                }
                                else
                                {
                                echo wp_kses_post(nl2br($sub_data));
                                }
                            }
                            ?>
                        </div>
                    </div>  <!-- End of one submission block-->
                    <?php
                    }
                endforeach;
            if ($data->payment)
            {
                switch(ucfirst($data->payment->status)) {
                    case 'Pending':
                        $display_status = __( 'Pending', 'custom-registration-form-builder-with-submission-manager' );
                        break;
                    case 'Completed':
                    case 'Succeeded':
                        $display_status = __( 'Completed', 'custom-registration-form-builder-with-submission-manager' );
                        break;
                    case 'Canceled':
                        $display_status = __( 'Canceled', 'custom-registration-form-builder-with-submission-manager' );
                        break;
                    case 'Refunded':
                        $display_status = __( 'Refunded', 'custom-registration-form-builder-with-submission-manager' );
                        break;
                    default:
                        $display_status = ucfirst($data->payment->status);
                        break;
                }
                if ($data->payment->log):
                    ?>
                    <div class="rm-submission-field-row">
                        <div class="rm-submission-label"><?php echo wp_kses_post(RM_UI_Strings::get('LABEL_PAYER_NAME')); ?></div>
                        <div class="rm-submission-value"><?php if (isset($data->payment->log['first_name'])) echo esc_html($data->payment->log['first_name']);
            if (isset($data->payment->log['last_name'])) echo ' ' . esc_html($data->payment->log['last_name']); ?></div>
                    </div>
                    <div class="rm-submission-field-row">
                        <div class="rm-submission-label"><?php echo wp_kses_post(RM_UI_Strings::get('LABEL_PAYER_EMAIL')); ?></div>
                        <div class="rm-submission-value"><?php if (isset($data->payment->log['payer_email'])) echo esc_html($data->payment->log['payer_email']); ?></div>
                    </div>
                    <?php
                endif;
                ?>
                <div class="rm-submission-field-row">
                    <div class="rm-submission-label"><?php echo wp_kses_post(RM_UI_Strings::get('LABEL_INVOICE')); ?></div>
                    <div class="rm-submission-value"><?php if (isset($data->payment->invoice)) echo esc_html($data->payment->invoice); ?></div>
                </div>
                <div class="rm-submission-field-row">
                    <div class="rm-submission-label"><?php echo wp_kses_post(RM_UI_Strings::get('LABEL_TAXATION_ID')); ?></div>
                    <div class="rm-submission-value"><?php if (isset($data->payment->txn_id)) echo esc_html($data->payment->txn_id); ?></div>
                </div>
                <div class="rm-submission-field-row">
                    <div class="rm-submission-label"><?php echo wp_kses_post(RM_UI_Strings::get('LABEL_STATUS_PAYMENT')); ?></div>
                    <div class="rm-submission-value">
                        <?php if (isset($data->payment->status)) echo esc_html($display_status); ?>
                        <?php if (isset($data->payment->log) && $data->payment->log):?>
                        <a href="javascript:void(0)" onclick="rm_toggle_pp_log_box()"><?php echo wp_kses_post(RM_UI_Strings::get('LABEL_PAYPAL_TRANSACTION_LOG')); ?></a>
                        <div id="rm_sub_pp_log_detail" style="display:none;
                                                              height: 200px;
                                                              border: #dcdbdb 1px solid;
                                                              overflow-y: auto;
                                                              overflow-x: auto;">
                            <?php echo wp_kses_post(RM_Utilities::var_to_html($data->payment->log)); ?>
                        </div>
                        <?php endif; ?> 
                    </div>
                </div>
                <div class="rm-submission-field-row">
                    <div class="rm-submission-label"><?php echo wp_kses_post(RM_UI_Strings::get('LABEL_PAID_AMOUNT')); ?></div>
                    <div class="rm-submission-value"><?php if (isset($data->payment->total_amount)) echo esc_html($data->payment->total_amount); ?></div>
                </div>
                <div class="rm-submission-field-row">
                    <div class="rm-submission-label"><?php echo wp_kses_post(RM_UI_Strings::get('LABEL_PAID_TAX')); ?></div>
                    <div class="rm-submission-value"><?php if (isset($data->payment->tax)) echo esc_html($data->payment->tax); ?></div>
                </div>
                <div class="rm-submission-field-row">
                    <div class="rm-submission-label"><?php echo wp_kses_post(RM_UI_Strings::get('LABEL_DATE_OF_PAYMENT')); ?></div>
                    <div class="rm-submission-value"><?php if (isset($data->payment->posted_date)) echo esc_html(RM_Utilities::localize_time($data->payment->posted_date, get_option('date_format'))); ?></div>
                </div>
                <?php
            }
            ?>


        </form>
    </div>
    <?php
    if ($data->notes && (is_object($data->notes) || is_array($data->notes)))
    {
        foreach ($data->notes as $note)
        {
            ?>
            <div class="rm-submission-note" style="border-left: 4px solid #<?php echo wp_kses_post(maybe_unserialize($note->note_options)->bg_color); ?>">
                <div class="rm-submission-note-text"><?php echo wp_kses_post($note->notes); ?></div>
                <div class="rm-submission-note-attribute">

                    <?php
                    echo wp_kses_post(RM_UI_Strings::get('LABEL_CREATED_BY')) . " <b>" . esc_html($note->author) . "</b> <em>" . esc_html(RM_Utilities::localize_time($note->publication_date)) . "</em>";
                    if ($note->editor)
                        echo " (" . wp_kses_post(RM_UI_Strings::get('LABEL_EDITED_BY')) . " <b>" . esc_html($note->editor) . "</b> <em>" . esc_html(RM_Utilities::localize_time($note->last_edit_date)) . "</em>";
                    ?>
                </div>

                <div class="rm-submission-note-attribute"><a href="?page=rm_note_add&rm_submission_id=<?php echo esc_attr($data->submission->get_submission_id()); ?>&rm_note_id=<?php echo esc_attr($note->note_id); ?>"><?php echo wp_kses_post(RM_UI_Strings::get('LABEL_EDIT')); ?></a>
                    <a href="javascript:void(0)" onclick="document.getElementById('rmnotesectionform<?php echo esc_attr($note->note_id); ?>').submit()"><?php echo wp_kses_post(RM_UI_Strings::get('LABEL_DELETE')); ?></a>
                </div>
                <form method="post" id="rmnotesectionform<?php echo esc_attr($note->note_id); ?>">
                    <input type="hidden" name="rm_slug" value="rm_note_delete">
                    <input type="hidden" name="rm_note_id" value="<?php echo esc_attr($note->note_id); ?>">
                </form>
            </div>
            <?php
        }
    }
    ?>
    <?php     
    $rm_promo_banner_title = __('Unlock Custom Statuses, Note, Print, Block Email/IP and Send Message options by upgrading','custom-registration-form-builder-with-submission-manager');
    include RM_ADMIN_DIR.'views/template_rm_promo_banner_bottom.php';
    ?>
</div>


<pre class="rm-pre-wrapper-for-script-tags"><script type="text/javascript">
 
 function rm_toggle_pp_log_box(){
     var is_log_visible = jQuery('#rm_sub_pp_log_detail').is(":visible");
     
     if(is_log_visible){
         jQuery('#rm_sub_pp_log_detail').slideUp();
     }
     else{
         jQuery('#rm_sub_pp_log_detail').slideDown();
     }
     
 }
 
/* jQuery(document).mouseup(function (e) {debugger;
        var container = jQuery("#rm_sub_pp_log_detail");
        if (!container.is(e.target) // if the target of the click isn't the container... 
                && container.has(e.target).length === 0) // ... nor a descendant of the container 
        {
            container.hide();
        }
    });
*/

jQuery(document).ready(function(){
    jQuery('.rm-add-custom-status').on('click', function(e) {
        jQuery('.rm-add-custom-status-value').toggle();
    });
   
});

</script></pre>
<?php } ?>