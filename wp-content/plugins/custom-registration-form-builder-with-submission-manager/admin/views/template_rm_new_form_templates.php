<?php
if (!defined('WPINC')) {
    die('Closed');
}
if(defined('REGMAGIC_ADDON')) include_once(RM_ADDON_ADMIN_DIR . 'views/template_rm_new_form_templates.php'); else {
?>
<script>
    jQuery(document).ready(function() {
        jQuery(window).keydown(function(event){
          if(event.keyCode == 13) {
            event.preventDefault();
            return false;
          }
        });
        
    });
    jQuery(document).on('click', 'button.rm-form-template-btn',function(event) {
        var temp = jQuery(this).data('tempid');
        var temp_title = jQuery(this).data('title');
        var temp_submit = jQuery(this).data('submit');
        var temp_submitalign = jQuery(this).data('submitalign');
        jQuery('#form_name_template').val(temp_title);
        jQuery('.form_submit_btn_label').val(temp_submit);
        jQuery('.form_btn_align').val(temp_submitalign);
        jQuery('#template_type').val(temp);
        if(temp.includes('r')){
            jQuery('#temp_form_type').val('rm_reg_form');  
        }
        else{
            jQuery('#temp_form_type').val('rm_contact_form');
        }
        jQuery('#rm_submit_btn_template').click();
    });
    jQuery(document).ready(function(){
        jQuery("#rm-input-filterTemplate").on("keyup", function() {
          var value = jQuery(this).val().toLowerCase();
          jQuery(".form_temp li").filter(function() {
            jQuery(this).toggle(jQuery(this).text().toLowerCase().indexOf(value) > -1)
          });
        });
      });
</script>
<form action="" id="rm_form_add_new_form_template" method="post" class="form-horizontal">
    <input type="submit" value="<?php _e("Save",'custom-registration-form-builder-with-submission-manager') ?>" name="submit" id="rm_submit_btn_template" style="display: none;">
    <input type="hidden" name="rm_slug" value="rm_form_add_new_form_template" id='rm_form_template_slug'>
    <input type="hidden" name="form_type" value="rm_reg_form" id="temp_form_type"/>
    <input type="hidden" value="Template" name="form_name" id="form_name_template" />
    <input type="hidden" name="form_submit_btn_label" value="SEND" class="form_submit_btn_label">
    <input type="hidden" name="form_btn_align" value="left" class="form_btn_align">
    <input type="hidden" name="template_type" value="c1" id="template_type">
    <input type="hidden" name="type" value="basic" id="temp_type"/>
    <div class="rm-templates_list">
        <div class="rm-templates-type-tabs">
            <div class="templates-body-panel">
                <div class="rm-template-filter">
                    <div class="rm-temp-filter-input">
                        <input id="rm-input-filterTemplate" type="text" placeholder="Search Templates">
                        <span class="material-icons">search</span>
                    </div>
                    <div class="rm-temp-filter-suggestion">
                        <ul>
                            <li><span class="material-icons">person</span><label>= Creates User Accounts</label></li>
                        </ul>
                    </div>
                </div>
                <div class="template-body tt-panel-active">
                    <ul class="form_temp">
                        <?php foreach($data->templates as $template){
                            if($template['ver']=='standard'){
                            ?>
                            <li class="form-contact form_temp_bullet">
                                <div class="rm-temp-card">
                                    <div class="rm-temp-header">
                                        <h3 class="rm-temp-header-title"><?php echo esc_html($template['title']);?></h3>
                                        <div class="rm-template-label-icon">
                                            <?php 
                                            if($template['create_user']){
                                                echo '<span class="material-icons">person</span>';
                                            }
                                            if($template['multipage']){
                                                echo '<span class="material-icons">auto_stories</span>';
                                            }
                                            ?>
                                        </div>
                                        <p><?php echo wp_kses_post($template['description']);?></p>
                                        <div class="rm-form-template-link"><a href="#" class="rm-form-template-demo">Live Preview<span class="material-icons"> launch </span></a></div>
                                    </div>
                                    <div class="rm-temp-footer">
                                        <button class="rm-form-template-btn" data-tempid="<?php echo esc_attr($template['id']);?>" data-type="basic" data-title="<?php echo esc_attr($template['title']);?>" data-submit="Submit" data-submitalign="center" type="button"><?php _e('Use Template','custom-registration-form-builder-with-submission-manager');?></button>                                
                                    </div>
                                </div>
                            </li>
                            <?php
                            }
                            elseif($template['ver']=='premium'){
                            ?>
                            <li class="form-contact form_temp_bullet pro-template">
                            <div class="rm-temp-card">
                                <div class="rm-temp-header">
                                    <h3 class="rm-temp-header-title"><?php echo esc_html($template['title']);?></h3>
                                    <div class="icon-label pro-label">
                                        <?php 
                                            if($template['create_user']){
                                                echo '<span class="material-icons">person</span>';
                                            }
                                            if($template['multipage']){
                                                echo '<span class="material-icons">auto_stories</span>';
                                            }
                                        ?>
                                        <span class="material-icons"> workspace_premium </span><?php _e('Premium Template','custom-registration-form-builder-with-submission-manager');?></div>
                                   <p><?php echo wp_kses_post($template['description']);?></p>
                                    <div class="rm-form-template-link"><a href="#" class="rm-form-template-demo">Live Preview<span class="material-icons"> launch </span></a></div>
                                </div>
                                <div class="rm-temp-footer">
                                    <a href="https://registrationmagic.com/comparison/?utm_source=rm_plugin&utm_medium=form_template_modal&utm_campaign=form_template_upgrade" target="_blank" class="rm-form-template-btn"><?php _e('Upgrade','custom-registration-form-builder-with-submission-manager');?></a> 
                                </div>
                            </div>
                        </li>
                            <?php
                            }
                        }?>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</form>
<?php } ?>