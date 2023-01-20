<?php
if (!defined('WPINC')) {
    die('Closed');
}
if(defined('REGMAGIC_ADDON')) include_once(RM_ADDON_ADMIN_DIR . 'views/template_rm_field_conditions.php'); else {
$excluded_fields= array('HTMLP','HTMLH','File','Image','Repeatable','Price','Multi-Dropdown','Time','Divider','Spacing','Shortcode','Rating','Map','Address','RichText','Timer',"Link","YouTubeV","Facebook","Twitter","Instagram","Linked","VKontacte","Skype","SoundCloud","Language");
//$fields_dd_options = addslashes(RM_Utilities::get_fields_dropdown(array('form_id' => $data->form_id,'ex_by_type'=>$excluded_fields)));
wp_enqueue_style('rm_jquery_ui_css', RM_BASE_URL . 'admin/css/jquery-ui.min.css'); 
?>
<script type="text/javascript">
    /************* Conditional fields related *******************/
    function fieldChanged(obj){
        var element= jQuery(obj);
        var selectedField= element.val(); 
        var data = {
            action: 'rm_fields_conditions_check',
            'rm_slug': 'rm_field_conditions_check',
            'rm_sec_nonce': rm_admin_vars.nonce,
            field_id: selectedField,
            type: 'fieldType'
        };
        var conditionElement= element.closest(".rm-field-condition-row").find("select[name='op[]']");
        var fieldInput = element.closest(".rm-field-condition-row").find("input[name='values[]']");
        
        conditionElement.addClass('rm-block-input');
        var loader = {
            'Loading...':''
        };
        conditionElement.empty();
        Object.entries(loader).map(function (entry) {
            var opt = document.createElement('option');
            opt.value = entry[1];
            opt.innerHTML = entry[0];
            conditionElement.append(opt);
            
        });
        jQuery.post(ajaxurl, data, function (response) {
            changeCondition(obj, response.data);
            //console.log(response.data.fieldType);
            conditionElement.removeClass('rm-block-input');
            fieldInput.removeClass('rm-block-input');
        });
        
       //console.log(selectedField);
    }
    function changeCondition(obj, data){
        //console.log(fieldType);
        var field_type = data.htmlFieldType;
        var element= jQuery(obj);
        var options = data.conditionOption;
        var conditionElement= element.closest(".rm-field-condition-row").find("select[name='op[]']");
        
        conditionElement.empty();
        Object.entries(data.conditionOption).map(function (entry) {
            var opt = document.createElement('option');
            opt.value = entry[1];
            opt.innerHTML = entry[0];
            conditionElement.append(opt);
          
        });
        if(field_type == 'date'){
            var dateFormat = data.dateFormat;
            var valueElement= element.closest(".rm-field-condition-row").find("input[name='values[]']");
            valueElement.attr('text',field_type );
            var value = valueElement.val();
            console.log(value);
            var datepi = valueElement.datepicker({ dateFormat: dateFormat, setDate: value });
            //datepi.datepicker( "setDate", value );
        }else{
            var valueElement= element.closest(".rm-field-condition-row").find("input[name='values[]']");
            valueElement.datepicker( "destroy" );
            valueElement.attr('type',field_type );
        }
        
    }
    function initialiseCalender(activeclass, dateFormat, value){
        jQuery('.active_calender.'+activeclass).datepicker({ dateFormat: dateFormat, setDate: value });
    }
    function setfieldType(fieldType){
        var urlFields = ['Website', 'Facebook', 'Instagram'];
        var dateFields = ['jQueryUIDate', 'Bdate'];
        
        if(jQuery.inArray(fieldType, urlFields) != -1){
            return 'url';
        }else if(jQuery.inArray(fieldType, dateFields) != -1){
            return 'date';
        }else{
            return 'text';
        }
    }
    function opChanged(obj){
        var element= jQuery(obj);
        var selectedVal= element.val();
        var valueElement= element.closest(".rm-field-condition-row").find("input[name='values[]']");
        if(selectedVal=="_blank" || selectedVal=="_not_blank"){
            valueElement.val('');
            valueElement.addClass('rm-block-input');
            valueElement.removeAttr('required');
        } else{
            valueElement.removeClass('rm-block-input');
            valueElement.attr('required');
        }
    }
    function delete_dependency(element)
    {
        jQuery(element).closest(".rm-field-condition-row").remove();
        if(jQuery(element).closest(".rm-field-condition-row").siblings().length<2)
        { 
            jQuery(".rm-match-condition").hide();
        }
    }
    
    function delete_all(id){
        jQuery("#rm_condition_" + id + " .rm-field-condition-row").remove();
       // jQuery("#rm_condition_" + id).hide();
        jQuery("#rm-cond-form-"+id).submit();
    }
    function showConditionFormModal(fid)
    {
        if (fid === void 0) {fid = 0;}
        jQuery("#rm-conditional-modal.rm-modal-view, .rm-modal-overlay").toggle();
         jQuery('.rmagic .rm_field_row_setting_wrap.rm-select-row-setting').removeClass('rm-field-popup-out');
        jQuery('.rmagic .rm_field_row_setting_wrap.rm-select-row-setting').addClass('rm-field-popup-in');

        jQuery('.rm-modal-overlay').removeClass('rm-field-popup-overlay-fade-out');
        jQuery('.rm-modal-overlay').addClass('rm-field-popup-overlay-fade-in');
        
        if(fid>0)
        {
           jQuery(".rm-condition").hide(); 
           addField(fid);
        } 
    }
    function addConditionForm(fid)
    { 
        var select_options= '<?php addslashes(RM_Utilities::get_fields_dropdown(array('form_id' => $data->form_id,'ex_by_type'=>$excluded_fields))); ?>';
        // Removing select field option from dropdown to avoid self condition
        var current_option = new RegExp('<option value="' + fid + '">(.*?)<\/option>');
        var html = '<div class="rm-field-condition-row rm-box-row rm-box-center"><div class="rm-box-col-11"><div class="rm-box-col-wrap rm-di-flex"><div class="rm-controlling-atr"><div class="rminput"><select name="cfields[]" onchange="fieldChanged(this)"><option><?php _e('Select Field','custom-registration-form-builder-with-submission-manager'); ?></option><?php addslashes(RM_Utilities::get_fields_dropdown(array('form_id' => $data->form_id,'ex_by_type'=>$excluded_fields))); ?></select></div></div><div class="rm-controlling-atr"><div class="rminput"><select onchange="opChanged(this)" name="op[]"><?php addslashes(RM_Utilities::get_cond_op_dd()); ?></select></div></div><div class="rm-controlling-atr"><div class="rminput"><input type="text" name="values[]" placeholder="Value" maxlength="50" required></div></div></div></div><div class="rm-box-col-1"><div class="rm-controlling-atr rm-controlling-btn"> <div class="rminput"><a onclick="delete_dependency(this)" href="javascript:void(0)"><span class="material-icons">delete</span></a></div></div></div></div>';
        html= html.replace(current_option,'');
        jQuery("#rm-container-field-"+fid).append(html);
        show_combinator(fid);
    }
    
    function show_combinator(fid)
    {   
        if(jQuery("#rm_condition_"+fid+" .rm-field-condition-row").length>1){
            jQuery(".rm-match-condition").show();
        }
        else{
            jQuery(".rm-match-condition").hide();
        }
    }
    
    
    function addField(fid){
        if(fid===undefined)
            fid= jQuery("#new_field").val();
        jQuery("#rm_condition_"+fid).show();
        jQuery('.rm-modal-wrap').animate({scrollTop: jQuery("#rm_condition_"+fid).offset().top},'slow');
        jQuery("#selected_field").html(jQuery("#rm_condition_"+fid).data('field-name'));
        show_combinator(fid);
    }
    /************* Conditional fields logic ends here *******************/
</script>
<style>
div#ui-datepicker-div {
    z-index: 99999 !important;
}
</style>
<!----Slab View---->
<div class="rm-modal-view" id="rm-conditional-modal" style="display:none">
    <div class="rm-modal-overlay rm-field-popup-overlay-fade-in" style="display:none" onClick="showConditionFormModal()"></div>
        <div class="rm_field_row_setting_wrap rm-select-row-setting rm-field-popup-out">
            <div class="rm-modal-titlebar">
                <div class="rm-modal-title" style="display:none;">  <?php echo wp_kses_post(RM_UI_Strings::get('LABEL_CONDITIONS')); ?> for <span id="selected_field"></span></div>
                <div class="rm-modal-title"><?php _e('Field Conditions','custom-registration-form-builder-with-submission-manager');?></div>
                <span class="rm-modal-close" onClick="showConditionFormModal()">&times;</span>
              
                
            </div>
            <div class="rm-conditional">
                <div class="rm-conditions">
                    <div class="rm-add-field" style="display:none">
                        <a href="javascript:void(0)" onclick="addField()"><?php _e('Add','custom-registration-form-builder-with-submission-manager'); ?></a>
                     <?php RM_Utilities::get_fields_dropdown(array('form_id' => $data->form_id, 'name' => 'new_field','inc_by_type'=>  RM_Utilities::get_allowed_conditional_fields(),'ex_by_type'=>$excluded_fields)); ?>
                    </div>

                    <?php
                    foreach ($data->fields_data as $field):
                        $options = maybe_unserialize($field->field_options);
                        if(empty($options->conditions))
                        {
                            $options = new StdClass();
                            $options->conditions= array("settings"=>array('combinator'=>'OR'));
                        }
                        $display= empty($options->conditions['rules'])?'display:none':'';
                        ?>   
                    <form method="post" id="rm-cond-form-<?php echo esc_attr($field->field_id); ?>">
                        <div style="<?php echo esc_attr($display); ?>" class="rm-condition" id="rm_condition_<?php echo esc_attr($field->field_id); ?>" data-field-name="<?php echo ucwords(esc_attr($field->field_label)); ?>"> 
                                <input type="hidden" name="dfield" value="<?php echo esc_attr($field->field_id); ?>" />
                                
                                <div class="rm-conditions-field-container">
                                <div class="rm-field-conditions-wrap">
                                    <div class="rm-field-conditions-title">
                                        <span class="rm-conditions-field-label">
                                        <?php echo esc_html($field->field_label); ?>
                                        </span>
                                        <div class="rm-field-conditions-delete" style="display:none;"><a href="javascript:void(0)" onclick="delete_all(<?php echo esc_attr($field->field_id); ?>)"><?php _e('Delete','custom-registration-form-builder-with-submission-manager'); ?></a> </div>
                                         
                                    </div> 
                                    <div class="rm-fields-conditions-actions">
                                        <div class="rm-box-border rm-box-white-bg rm-box-mb-25 rm-box-ptb">
                                            <div class="rm-box-row">
                                            <div class="rm-box-col-12">
                                            <ul class="rm-conditions-actions">
                                                <div class="rm-conditions-box-title rm-card-mb-16"><?php _e('Action','custom-registration-form-builder-with-submission-manager'); ?></div>
                                                   <?php RM_Utilities::get_cond_action_dd(array('def' => isset($options->conditions['action']) ? $options->conditions['action'] : 'show')); ?>
                                            </ul>
                                            </div>
                                            </div>
                                        </div>  
                                    </div>
                                    <div class="rm-field-condition-sec-container">
                                        <div  class="rm-field-condition-container rm-combinator-container rm-box-border rm-box-white-bg rm-box-mb-25 rm-box-ptb" id="rm-container-field-<?php echo esc_attr($field->field_id); ?>">
                                            <div class="rm-match-condition-row">
                                                 <div class="rm-conditions-box-title rm-card-mb-16"><?php _e('Conditions','custom-registration-form-builder-with-submission-manager'); ?></div>
                                                 <div class="rm-match-condition"><input type="radio" id="rm-match-all-condition" name="combinator" value="OR" <?php echo @$options->conditions['settings']['combinator'] != 'AND' ? 'checked' : '' ?>><label for="rm-match-all-condition" ><?php _e('OR ','custom-registration-form-builder-with-submission-manager'); ?> <span><?php _e('(when one of these conditions are true) ','custom-registration-form-builder-with-submission-manager'); ?></span></label></div>     
                                                <div class="rm-match-condition"><input  type="radio" id="rm-match-one-condition" name="combinator" value="AND" <?php echo @$options->conditions['settings']['combinator'] == 'AND' ? 'checked' : '' ?>><label for="rm-match-one-condition" ><?php _e('AND ','custom-registration-form-builder-with-submission-manager'); ?> <span><?php _e('(when all of these conditions are true) ','custom-registration-form-builder-with-submission-manager'); ?></span></label></div> 
                                            </div>

                                        <?php
                                        if(!empty($options->conditions['rules'])):
                                        $rule_count =1;
                                        foreach ($options->conditions['rules'] as $key => $condition):
                                            $cfield = new RM_Fields();
                                            $cfield->load_from_db($condition['controlling_field']);
                                            $conditional_settings = json_encode($condition);
                                            ?>   
                                                <div class="rm-field-condition-row rm-box-row rm-box-center">
                                                    <div class="rm-box-col-11">
                                                    <div class="rm-box-col-wrap rm-di-flex">
                                                    <div class="rm-controlling-atr">
                                                        <div class="rminput">
                                                                <?php RM_Utilities::get_fields_dropdown(array('form_id' => $data->form_id,'full' => true, 'change'=>'fieldChanged', 'name' => 'cfields[]','def'=>$condition['controlling_field'],'exclude'=>array($field->field_id),'ex_by_type'=>$excluded_fields)); ?>
                                                        </div>
                                                    </div>
                                                    <div class="rm-controlling-atr">
                                                        <div class="rminput">
                                                            <select name="op[]" onchange="opChanged(this)">
                                                            <?php //RM_Utilities::get_cond_op_dd(array('def' => $condition['op'])); ?>
                                                                <?php RM_Utilities::get_condition_dropdown_option_html(array('def' => $condition['op'],'field_id'=>$condition['controlling_field']));?>
                                                            </select>    
                                                        </div>

                                                    </div>
                                                    <div class="rm-controlling-atr">
                                                        <div class="rminput">
                                                            <?php 
                                                                $values='';
                                                                if(is_array($condition['values']) && !empty($condition['values'])){
                                                                    $values= implode(',', $condition['values']);
                                                                }
                                                                $sel_field_type = RM_Utilities::get_condition_values_fields(array('field_id'=>$condition['controlling_field']));
                                                                $calender_class = '';
                                                                $cl_active_class = '';
                                                                $date_format = 'mm/dd/yy';
                                                                if($sel_field_type == 'date') {
                                                                    $field_type = 'text';
                                                                    $calender_class= "active_calender rule_".$rule_count. ' ';
                                                                    $cl_active_class = "rule_".$rule_count;
                                                                    $valid_options = maybe_unserialize($cfield->get_field_options());
                                                                    $date_format = isset($valid_options->date_format) ? $valid_options->date_format : 'mm/dd/yy';

                                                                }else{
                                                                    $field_type = $sel_field_type;
                                                                }
                                                                $rule_count++;
                                                            ?>
                                                            <input type="<?php echo esc_attr($field_type);?>" class="<?php echo esc_attr($calender_class);?><?php echo ($condition['op']=='_blank' || $condition['op']=='_not_blank')?'rm-block-input':''; ?>" value="<?php echo htmlspecialchars($values); ?>" name="values[]" placeholder="Value"  maxlength="50" />
                                                            <?php if($sel_field_type == 'date'):?>
                                                            <script>initialiseCalender('<?php echo esc_attr($cl_active_class);?>', '<?php echo esc_html($date_format);?>', '<?php $values;?>');</script>
                                                            <?php endif;?>
                                                        </div>
                                                    </div></div></div>
                                                    <div class="rm-box-col-1">
                                                    <div class="rm-controlling-atr rm-controlling-btn"> 
                                                        <div class="rminput"><a onclick='delete_dependency(this)' href="javascript:void(0)"> <span class="material-icons">delete</span></a></div>
                                                    </div>
                                                        </div>
                                                </div>



                                        <?php endforeach;
                                            else:
                                                echo '<script>addConditionForm('.esc_html($field->field_id).')</script>';
                                            endif;
                                        ?> 

                                        </div>
                                        <div class="rm-add-condition-row">  <a onclick="addConditionForm(<?php echo esc_html($field->field_id); ?>)" href="javascript:void(0)" class="rm-add-condition-bt"><span class="material-icons"> add </span></a></div> 
                                   
                                    </div>
                                </div> 
                                </div>
                                
                                <div class="rm-form-builder-modal-footer rm-save-condition-row">
                                            <div class="rm-cancel-row-setting"><a href="javascript:void(0)" onclick="showConditionFormModal()" class="rm-modal-close">← &nbsp;<?php _e('Cancel', 'custom-registration-form-builder-with-submission-manager'); ?></a></div>
                                            <div class="rm-save-row-setting"><input type="submit" value="<?php _e('Save', 'custom-registration-form-builder-with-submission-manager'); ?>" /></div>
                                        </div>

                            </div>
                        </form>
                        <?php
                    endforeach;
                    ?>
                </div>    
            </div> 
        </div>
</form>
</div>

<?php 
if(isset($data->show_conditions) && $data->show_conditions)
{
   // echo '<script>window.onload= showConditionFormModal();</script>';
}
}
?>
