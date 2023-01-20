<?php 
if (!defined('WPINC')) {
    die('Closed');
}
wp_enqueue_style( 'rm_material_icons', RM_BASE_URL . 'admin/css/material-icons.css' );
$checkbox_status = array('1'=>'checked="checked"', '0'=>'');
?>
<div class="rmagic">
    <div class="rmcontent">
    	<?php 
    	if(!empty($data)){

    	$tabs = '<div class="rm-profile-tabs-wrap rm-tabs-sorting-box rm-form-builder-box1">
        	<ul class="rm_sortable_form_rows ui-sortable" id="rm-field-sortable-tabs">';
    		foreach ($data as $key => $tab) {
    			$tabs .= '<li id="'.$key.'" class="rm-fields-row">
    				
                    <div class="rm-profile-tab-slab rm_profile_tab">
                    <div class="rm-field-move rm_sortable_handle ui-sortable-handle"><span class="rm-profile-tab-drag-icon"></span></div>
                        <div class="rm-slab-info">'.$tab["label"].'</div>
                        <div class="rm-slab-buttons"><span class="dashicons dashicons-arrow-down"></span></div>                    
                    </div>
                    <div class="rm_profile_tab-setting" style="display:none;">
                      <div class="rmrow">
                        <div class="rmfield">
                              <label for="'.$tab["id"].'-label">'.__( "Tab Label","custom-registration-form-builder-with-submission-manager" ).'</label>       
                         </div>
                          <div class="rminput">
                               <input type="text" name="rm_profile_tabs_order_status['.$key.'][label]" id="'.$tab["id"].'-label" autocomplete="off" value="'.$tab["label"].'">
                           </div>
                           <div class="rmnote">'.__("The user will see the label on clickable tab.","custom-registration-form-builder-with-submission-manager").'</div>
                         </div>


                         <div class="rmrow">
                        <div class="rmfield">
                            '.__( "Enable Tab", "custom-registration-form-builder-with-submission-manager").'      
                         </div>
                          <div class="rminput">
                          <input name="rm_profile_tabs_order_status['.$key.'][status]" id="'.$tab["id"].'-status" type="checkbox" class="rm_toggle" value="1" '.$checkbox_status[$tab['status']].' />
                           </div>
                           <div class="rmnote">'.__("Disable if you do not wish to show this tab and its contents in the user area.","custom-registration-form-builder-with-submission-manager").'</div>
                         </div>


                        <input type="hidden" name="rm_profile_tabs_order_status['.$key.'][id]" id="'.$tab["id"].'-id" value="'.$tab["id"].'" />
                        <input type="hidden" name="rm_profile_tabs_order_status['.$key.'][icon]" id="'.$tab["id"].'-icon" value="'.$tab["icon"].'" />

                        <input type="hidden" name="rm_profile_tabs_order_status['.$key.'][class]" id="'.$tab["id"].'-class" value="'.$tab["class"].'" />


                    </div>

                </li>';
    		}
    		
	    	$tabs .='</ul>
	    </div>';
    		//echo $tabs;
    	}
    	$form = new RM_PFBC_Form("options_tabs");
        $form->configure(array(
            "prevent" => array("bootstrap", "jQuery"),
            "action" => ""
        ));
    	$form->addElement(new Element_HTML('<div class="rmheader">' . RM_UI_Strings::get('GLOBAL_SETTINGS_TABS') . '</div>'));
    	$form->addElement(new Element_HTML($tabs));
    	$form->addElement(new Element_HTMLL('&#8592; &nbsp; ' . __("Cancel", 'custom-registration-form-builder-with-submission-manager'), '?page=rm_options_manage', array('class' => 'cancel')));
        $form->addElement(new Element_Button(RM_UI_Strings::get('LABEL_SAVE')));
    	$form->render();

    	?>
    </div>
</div>
<script type="text/javascript">
//jQuery('ul#rm-field-sortable-tabs li').click(function(e){
//    jQuery('ul#rm-field-sortable-tabs li').toggleClass('activeTab');
//    jQuery('.rm_profile_tab-setting').toggle();
//    var panel = jQuery(this).closest('li').find('.rm_profile_tab-setting');
//    panel.show();
//    jQuery(this).addClass('activeTab');
//});

jQuery(function($) {
    $( "#rm-field-sortable-tabs" ).sortable();
    $( "#rm-field-sortable-tabs" ).disableSelection();
    $( "#rm-field-sortable-tabs" ).sortable({ axis: 'y' });
});
jQuery('input[type=text]').keyup(function(e){
	var label = jQuery(this).closest('li').find('.rm-slab-info');
    label.text(jQuery(this).val());
});

jQuery(document).ready(function() {
    
  jQuery("ul#rm-field-sortable-tabs li .rm_profile_tab .rm-slab-buttons").click(function(event) {
      //console.log ("yes licked");
    jQuery(this).find('span.dashicons').toggleClass("dashicons-arrow-up")
    //jQuery(this)(".rm-slab-buttons").find('span').toggleClass("dashicons-arrow-up");  
    jQuery(this).parent().siblings(".rm_profile_tab-setting").slideToggle();
    //console.log(  );
    //jQuery(this).next(".rm_profile_tab-setting").slideToggle();
  });
});




</script>