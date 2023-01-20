<?php
if (!defined('WPINC')) {
    die('Closed');
}
if(defined('REGMAGIC_ADDON')) include_once(RM_ADDON_ADMIN_DIR . 'views/template_rm_payments_manager.php'); else {
?>
<div class="rmagic">
    <?php
    ?>
    <!-----Operations bar Starts-->

    <div class="operationsbar">
        <div class="rmtitle"><?php echo wp_kses_post(RM_UI_Strings::get("TITLE_PAYMENTS_MANAGER")); ?></div>
        <div class="icons">
            <a href="?page=rm_options_manage"><img alt="" src="<?php echo esc_url(plugin_dir_url(dirname(dirname(__FILE__))) . 'images/global-settings.png'); ?>"></a>

        </div>
        <div class="nav">
            <ul>
                <li onclick="window.history.back()"><a href="javascript:void(0)"><?php echo wp_kses_post(RM_UI_Strings::get("LABEL_BACK")); ?></a></li>

                <li class="rm-form-toggle"><?php
                    if (count($data->forms) !== 0) {
                        echo wp_kses_post(RM_UI_Strings::get('LABEL_TOGGLE_FORM'));
                        ?>
                        <select id="rm_form_dropdown" name="form_id" onchange = "rm_load_page(this, 'payments_manage')">
                            <?php
                            foreach ($data->forms as $form_id => $form)
                                if ($data->filter->form_id == $form_id)
                                    echo "<option value=".esc_html($form_id)." selected>".esc_html($form)."</option>";
                                else
                                    echo "<option value=".esc_html($form_id).">".esc_html($form)."</option>";
                            ?>
                        </select>
                        <?php
                    }
                    ?>
                </li>
            </ul>
        </div>

    </div>
    <!--  Operations bar Ends----->


    <!-------Content area Starts----->
    <div class="rmnotice-row">
        <div class="rmnotice">
            You can set logo and text for invoice PDFs in <a target="_blank" href="<?php echo admin_url('admin.php?page=rm_options_manage_invoice'); ?>">Global Settings</a>.            
        </div>
    </div>
    <?php
    if (count($data->forms) === 0) {?>
        <div class="rmnotice-container">
            <div class="rmnotice">
                <?php echo wp_kses_post(RM_UI_Strings::get('MSG_NO_FORM_SUB_MAN')); ?>
            </div>
        </div><?php
    } elseif ($data->payments || $data->filter->filters['rm_interval'] != 'all' || $data->filter->searched) {
        ?>
        <div class="rmagic-table">
            <div class="sidebar">
                <div class="pay-sb-filter-title"><?php echo wp_kses_post(RM_UI_Strings::get("PAYMENT_FILTER_TITLE")); ?></div>
                <div class="sb-filter">
                    <?php echo wp_kses_post(RM_UI_Strings::get("PAYMENT_FILTER_LABEL_TIME")); ?>
                    <div class="filter-row"><input type="radio" onclick='rm_load_page_multiple_vars(this, "payments_manage", "interval",<?php echo wp_kses_post(json_encode(array('form_id' => $data->filter->form_id))); ?>)' name="filter_between" value="all"   <?php if ($data->filter->filters['rm_interval'] == "all") echo "checked"; ?>><?php echo wp_kses_post(RM_UI_Strings::get("LABEL_ALL")); ?> </div>
                    <div class="filter-row"><input type="radio" onclick='rm_load_page_multiple_vars(this, "payments_manage", "interval",<?php echo wp_kses_post(json_encode(array('form_id' => $data->filter->form_id))); ?>)' name="filter_between" value="today" <?php if ($data->filter->filters['rm_interval'] == "today") echo "checked"; ?>><?php echo wp_kses_post(RM_UI_Strings::get("LABEL_TODAY")); ?> </div>
                    <div class="filter-row"><input type="radio" onclick='rm_load_page_multiple_vars(this, "payments_manage", "interval",<?php echo wp_kses_post(json_encode(array('form_id' => $data->filter->form_id))); ?>)' name="filter_between" value="week"  <?php if ($data->filter->filters['rm_interval'] == "week") echo "checked"; ?>><?php echo wp_kses_post(RM_UI_Strings::get("LABEL_THIS_WEEK")); ?></div>
                    <div class="filter-row"><input type="radio" onclick='rm_load_page_multiple_vars(this, "payments_manage", "interval",<?php echo wp_kses_post(json_encode(array('form_id' => $data->filter->form_id))); ?>)' name="filter_between" value="month" <?php if ($data->filter->filters['rm_interval'] == "month") echo "checked"; ?>><?php echo wp_kses_post(RM_UI_Strings::get("LABEL_THIS_MONTH")); ?></div>
                    <div class="filter-row"><input type="radio" onclick='rm_load_page_multiple_vars(this, "payments_manage", "interval",<?php echo wp_kses_post(json_encode(array('form_id' => $data->filter->form_id))); ?>)' name="filter_between" value="year"  <?php if ($data->filter->filters['rm_interval'] == "year") echo "checked"; ?>><?php echo wp_kses_post(RM_UI_Strings::get("LABEL_THIS_YEAR")); ?></div>

                </div>
            </div>

            <!--*******Side Bar Ends*********-->

            <form method="post" action="" name="rm_submission_manage" id="rm_submission_manager_form">
                <input type="hidden" name="rm_slug" value="" id="rm_slug_input_field">
                <input type="hidden" name="rm_form_id" value="<?php echo esc_attr($data->filter->form_id); ?>" id="rm_form_id_input_field" />
                <input type="hidden" name="rm_interval" value="<?php echo esc_attr($data->filter->filters['rm_interval']); ?>">
                <table class="rm_submissions_manager_table">
                <?php
                if ($data->payments) {
                    ?>
                        <tr>
                        
                        <?php
                        $field_names = array();
                        $fields_data = RM_Utilities::payments_table_header_fields($data->fields);
                        $field_names = $fields_data->field_names;
                        $field_labels = $fields_data->field_labels;
                        if(!empty($field_labels)){
                            foreach ($field_labels as $label):
                                echo '<th>'.esc_html($label).'</th>';
                            endforeach;
                        }
                       
                            ?>
                            <th><?php echo wp_kses_post(RM_UI_Strings::get("PAYMENT_TH_ORDER_AMOUNT")); ?></th>
                            <th><?php echo wp_kses_post(RM_UI_Strings::get("PAYMENT_TH_ORDER_STATUS")); ?></th>
                            <th><?php echo wp_kses_post(RM_UI_Strings::get("PAYMENT_TH_ORDER_DATE")); ?></th>
                            <th><?php echo wp_kses_post(RM_UI_Strings::get("ACTION")); ?></th>
                        </tr>

                            <?php
                            if (is_array($data->payments) || is_object($data->payments))
                                foreach ($data->payments as $payment):
                                    $payment->data_us = RM_Utilities::strip_slash_array(maybe_unserialize($payment->data));
                                    $read_status= $payment->is_read==1 ? 'readed': 'unreaded';
                                ?>
                                <tr  class="<?php echo $read_status; ?>">
                                        <?php
                                        if (is_array($payment->data_us) || is_object($payment->data_us)):
                                            foreach ($field_names as $fields):
                                                $value = '';
                                                if(isset($payment->data_us[$fields])):
                                                    
                                                    $sub_data = $payment->data_us[$fields];
                                                    $type = isset($sub_data->type) ? $sub_data->type : '';
                                                        if($type == 'Price'):
                                                            $bill_products = unserialize($payment->bill);
                                                            $value = array();
                                                            if(is_object($bill_products)):
                                                                foreach ($bill_products->billing as $key => $product){
                                                                    $value[] = $product->label;
                                                                    //$value .= $product->label.' ('.RM_Utilities::get_formatted_price($product->price).') X '.$product->qty .'<br>';
                                                                }
                                                            endif;
                                                        elseif($type == 'Address'):
                                                            $value = implode(', ', array_values($sub_data->value));
                                                        else:
                                                            $value = $sub_data->value;
                                                        endif;
                                                endif;
                                                ?>
                                                <td><?php
                                        
                                                    if (is_array($value)){
                                                        echo $value = implode(', ', $value);
                                                    }
                                                    elseif (function_exists('mb_strimwidth')){
                                                        echo wp_kses_post(mb_strimwidth($value, 0, 70, "..."));
                                                    } else{
                                                        echo esc_html($value);
                                                    }
                                                    ?>
                                                </td>
                                                <?php
                                            endforeach;
                                        endif;?>
                                    <td><?php echo RM_Utilities::get_formatted_price($payment->total_amount);?></td>
                                    <td> <?php
                                            $submission_model = new RM_Submissions;
                                            $submission_model->load_from_db($payment->submission_id);
                                            $have_attchment = $submission_model->is_have_attcahment();
                                            $payment_status = $submission_model->get_payment_status();
                                            if (isset($payment_status) && strtolower($payment_status) =='canceled') {?>
                                                <img  class="rm_submission_icon" alt="" src="<?php echo esc_url(plugin_dir_url(dirname(dirname(__FILE__))) . 'images/canceled_payment.png'); ?>">
                                                <?php
                                            }
                                            if (isset($payment_status) && strtolower($payment_status) =='refunded' ) {?>
                                                <img  class="rm_submission_icon" alt="" src="<?php echo esc_url(plugin_dir_url(dirname(dirname(__FILE__))) . 'images/refunded_payment.png'); ?>">
                                                <?php
                                            }
                                            if (isset($payment_status) && strtolower($payment_status) =='pending' ) {?>
                                                <img  class="rm_submission_icon" alt="" src="<?php echo esc_url(plugin_dir_url(dirname(dirname(__FILE__))) . 'images/pending_payment.png'); ?>">
                                                <?php
                                            }
                                            if (isset($payment_status) && ( strtolower($payment_status) =='completed'  || strtolower($payment_status) =='succeeded' )){?>
                                                <img  class="rm_submission_icon" alt="" src="<?php echo esc_url(plugin_dir_url(dirname(dirname(__FILE__))) . 'images/payment_completed.png'); ?>">
                                                <?php
                                            }
                                        ?>
                                    </td>
                                    <td><?php echo esc_html(RM_Utilities::localize_time($payment->posted_date,'j M Y')); ?></td>
                                    <td><a href="?page=rm_payments_view&rm_submission_id=<?php echo esc_attr($payment->submission_id); ?>"><?php echo wp_kses_post(RM_UI_Strings::get("VIEW")); ?></a></td>
                                </tr><?php
                                endforeach;
                                ?>
                                <?php
                            }elseif ($data->filter->searched) {
                                ?>
                                <div class="rmnotice" style="max-width: 80%;">
                            <?php echo wp_kses_post(RM_UI_Strings::get('MSG_NO_SUBMISSION_MATCHED')); ?>
                            </div>
                        <?php
                        } else {
                            ?>
                        <div class="rmnotice" style="max-width: 80%;">
                            <?php echo wp_kses_post(RM_UI_Strings::get('MSG_NO_SUBMISSION_SUB_MAN_INTERVAL')); ?>
                            </div>
                        
                    <?php }
                    ?>
                </table>

            </form>
                    <?php include RM_ADMIN_DIR . 'views/template_rm_payments_legends.php'; ?>
        </div>
                            <?php
                            echo wp_kses_post($data->filter->render_pagination());
                        } else {
                            ?>
                            <div class="rmnotice-container">
                                <div class="rmnotice">
                                    <?php echo wp_kses_post(RM_UI_Strings::get('MSG_NO_SUBMISSION_SUB_MAN')); ?>
                                </div>
                            </div>
                    <?php
                }
                ?>

        <?php
        $rm_promo_banner_title = __('Unlock export submissions and more by upgrading','custom-registration-form-builder-with-submission-manager');
        include RM_ADMIN_DIR . 'views/template_rm_promo_banner_bottom.php';
        ?>
</div>
<pre class='rm-pre-wrapper-for-script-tags'><script>
function rm_on_selected_submissions(){
          var selected_submission = jQuery("input.rm_checkbox_group:checked");
         if(selected_submission.length > 0) {   
             jQuery("#rm-delete-submission").removeClass("rm_deactivated"); 
             } 
             else 
             {
                 jQuery("#rm-delete-submission").addClass("rm_deactivated");
             }                     
         }
function rm_submission_selection_toggle(selector){
        if(jQuery(selector).prop("checked") == true) {
            jQuery("input[name=rm_selected\\[\\]]").prop("checked",true);
            jQuery("#rm-delete-submission").removeClass("rm_deactivated");
        } else {
            jQuery("input[name=rm_selected\\[\\]]").prop("checked",false);
            jQuery("#rm-delete-submission").addClass("rm_deactivated");
        }
    }
       </script></pre>
<?php } ?>