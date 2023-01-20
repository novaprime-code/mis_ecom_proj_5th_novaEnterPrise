<?php
if (!defined('WPINC')) {
    die('Closed');
}
if(defined('REGMAGIC_ADDON')) include_once(RM_ADDON_ADMIN_DIR . 'views/template_rm_options_fab.php'); else {
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

$image_dir = plugin_dir_url(dirname(dirname(__FILE__))) . "images";
$media_btn_label = RM_UI_Strings::get('LABEL_FAB_ICON');
$media_btn_help_text = RM_UI_Strings::get('TEXT_FAB_ICON_HELP');
$media_btn_val = RM_UI_Strings::get('LABEL_FAB_ICON_BTN');
$remove_btn_val = RM_UI_Strings::get('LABEL_FAB_ICON_BTN_REM');
$fab_icon = $data['fab_icon'];
$icon_src = '';
if(!empty($fab_icon)) {
    $src = wp_get_attachment_image_url($fab_icon);
    if(!empty($src)) {
        $icon_src = $src;
    }
}

?>


<div class="rmagic">

    <!--Dialogue Box Starts-->
    <div class="rmcontent">


        <?php
        $pages = get_pages();
        $wp_pages = RM_Utilities::wp_pages_dropdown();

        $form = new RM_PFBC_Form("options_fab");
        $form->configure(array(
            "prevent" => array("bootstrap", "jQuery"),
            "action" => "",
            "enctype" => "multipart/form-data"
        ));

        $form->addElement(new Element_HTML('<div class="rmheader">' . RM_UI_Strings::get('GLOBAL_SETTINGS_FAB') . '</div>'));
        $form->addElement(new Element_Checkbox(RM_UI_Strings::get('LABEL_SHOW_FLOATING_ICON'), "display_floating_action_btn", array("yes" => ''), $data['display_floating_action_btn'] == 'yes' ? array("value" => "yes", "longDesc" => RM_UI_Strings::get('HELP_SHOW_FLOATING_ICON')) : array("longDesc" => RM_UI_Strings::get('HELP_SHOW_FLOATING_ICON'))));
        $form->addElement(new Element_Checkbox(__('Hide MagicPopup','custom-registration-form-builder-with-submission-manager'), "hide_magic_panel_styler", array("yes" => ''), $data['hide_magic_panel_styler'] == 'yes' ? array("value" => "yes", "longDesc" => RM_UI_Strings::get('HIDE_MAGIC_PANEL_STYLER')) : array("longDesc" => RM_UI_Strings::get('HIDE_MAGIC_PANEL_STYLER'))));
        $form->addElement(new Element_HTML("<div class='rmrow'><div class='rmfield' for='options_fab-element-1'><label>$media_btn_label</label></div><div class='rminput rm_wpmedia_input_cont rm-popup-icon'><div id='rm_fab_icon'><img alt='' src='$icon_src'></div>"));
        $form->addElement(new Element_Hidden("fab_icon", $fab_icon, array("id" => "rm_fab_icon_val")));
        $form->addElement(new Element_Button($media_btn_val, "button", array("id" => "rm_media_btn_fab_icon", "class" => "rm_wpmedia_btn button")));
        $form->addElement(new Element_Button($remove_btn_val, "button", array("id" => "rm_btn_remove_fab_icon", "onClick" => "rm_remove_fab_icon()", "class" => "rm_btn button")));
        $form->addElement(new Element_HTML("</div><div class='rmnote'><div class='rmprenote'></div><div class='rmnotecontent'>$media_btn_help_text</div></div></div>"));
       $form->addElement(new Element_Checkbox(RM_UI_Strings::get('LABEL_SHOW_SUBMISSION_TAB'), "sub_tab", array(1 =>''),array("id" => "id_rm_fabl1", "class" => "fab_l1" , "value" =>'', "disabled" => "disabled",  "longDesc" => RM_UI_Strings::get('MSG_BUY_PRO_BOTH_INLINE'))));
       $form->addElement(new Element_Checkbox(RM_UI_Strings::get('LABEL_SHOW_PAYMENT_TAB'), "pay_tab", array(1 => ''),array("id" => "id_rm_fabl1", "class" => "fab_l1" , "value" =>'', "disabled" => "disabled", "longDesc" => RM_UI_Strings::get('MSG_BUY_PRO_BOTH_INLINE'))));
       $form->addElement(new Element_Checkbox(RM_UI_Strings::get('LABEL_SHOW_DETAILS_TAB'), "det_tab", array(1 => ''),array("id" => "id_rm_fabl1", "class" => "fab_l1" , "value" =>'', "disabled" => "disabled", "longDesc" => RM_UI_Strings::get('MSG_BUY_PRO_BOTH_INLINE'))));
      
         $form->addElement(new Element_Checkbox(RM_UI_Strings::get('LABEL_SHOW_FAB_LINK1'), "fab_link1", array("yes" => ''),array("id" => "id_rm_fabl1", "class" => "fab_l1" , "value" => 'yes',  "disabled" => "disabled", "longDesc" => RM_UI_Strings::get('HELP_SHO_FAB_LINK'))));
     
            $form->addElement(new Element_HTML('<div class="childfieldsrow" id="fab_l1_childfieldsrow">'));
         $form->addElement(new Element_Radio("<b>" . RM_UI_Strings::get('LABEL_FAB_LINK_TYPE') . "</b>", "fab_link_type1", array('page' => __("Page",'custom-registration-form-builder-with-submission-manager'), 'url' => __("URL",'custom-registration-form-builder-with-submission-manager')), array("id" => "rm_", "class" => "fab_link_type1", "disabled" => "disabled", "value" => 'page', "longDesc" => RM_UI_Strings::get('MSG_BUY_PRO_BOTH_INLINE'))));
     
            $form->addElement(new Element_HTML('<div id="fab_link_type1_rm_form_page">'));
        
        $roles=array('everyone'=>__("Everyone",'custom-registration-form-builder-with-submission-manager'),'unreg'=>__("Unregistered",'custom-registration-form-builder-with-submission-manager'),'reg'=>__("All Registered",'custom-registration-form-builder-with-submission-manager'));
        $roles= array_merge($roles,RM_Utilities::user_role_dropdown());
        $pages=  RM_Utilities::wp_pages_dropdown();
        $form->addElement(new Element_Select("<b>" . RM_UI_Strings::get('LABEL_PAGE') . "</b>", "fab_link_page1", $pages, array("id" => "rm_form_type", "value" =>null,"disabled" => "disabled", "longDesc" => RM_UI_Strings::get('MSG_BUY_PRO_BOTH_INLINE'))));
        $form->addElement(new Element_Select("<b>" . RM_UI_Strings::get('LABEL_VISIBILITY') . "</b>", "fab_link_role_page1", $roles, array("id" => "rm_form_type", "value" =>null,"disabled" => "disabled", "longDesc" => RM_UI_Strings::get('MSG_BUY_PRO_BOTH_INLINE'))));
        $form->addElement(new Element_HTML('</div>'));
        $form->addElement(new Element_HTML("</div>"));
        
        //Second link starts
         $form->addElement(new Element_Checkbox(RM_UI_Strings::get('LABEL_SHOW_FAB_LINK2'), "fab_link2", array("yes" => ''),array("id" => "id_rm_fabl2", "class" => "fab_l2" , "value" =>  null, "disabled" => "disabled","longDesc"=>RM_UI_Strings::get('MSG_BUY_PRO_BOTH_INLINE'))));
       
     //Third link starts
        
        
          $form->addElement(new Element_Checkbox(RM_UI_Strings::get('LABEL_SHOW_FAB_LINK3'), "fab_link3", array("yes" => ''),array("id" => "id_rm_fabl3", "class" => "fab_l3" , "value" =>  null,  "disabled" => "disabled","longDesc"=>RM_UI_Strings::get('MSG_BUY_PRO_BOTH_INLINE'))));
       
        $form->addElement(new Element_HTML('<div class="rmnotice">'.RM_UI_Strings::get('NOTE_MAGIC_PANEL_STYLING').'</div>'));
        $form->addElement(new Element_HTMLL('&#8592; &nbsp; '.__("Cancel",'custom-registration-form-builder-with-submission-manager'), '?page=rm_options_manage', array('class' => 'cancel')));
        $form->addElement(new Element_Button(RM_UI_Strings::get('LABEL_SAVE')));
        $form->render();
        ?> 

    </div>
</div>
<?php wp_enqueue_media(); ?> 
<pre class="rm-pre-wrapper-for-script-tags"><script type="text/javascript">
    jQuery(document).ready(function(){
       jQuery('#rm_floating_btn_type_rd-0').click(function(){
           jQuery('#floating_btn_txt_tb').slideUp();
       });
       jQuery('#rm_floating_btn_type_rd-1, #rm_floating_btn_type_rd-2').click(function(){
                      jQuery('#floating_btn_txt_tb').slideDown();
       });
       
       if (jQuery('.rm_wpmedia_btn').length > 0) {
        if ( typeof wp !== 'undefined' && wp.media && wp.media.editor) {
        jQuery('.rm_wpmedia_input_cont').on('click', '.rm_wpmedia_btn', function(e) {
            e.preventDefault();
            var button = jQuery(this);
            var id = jQuery("input#rm_fab_icon_val");
            wp.media.editor.send.attachment = function(props, attachment) {
                id.val(attachment.id);
                jQuery("#rm_fab_icon img").prop('src',attachment.sizes.thumbnail.url);
            };
            wp.media.editor.open();
            return false;
        });
        
    }
};
    });
    
    function rm_remove_fab_icon(){
        jQuery("#rm_fab_icon img").prop('src','');
        jQuery("#rm_fab_icon_val").val('');
    }
</script></pre>

<?php   
}