<?php
class Element_Username extends Element_Textbox {
    public function getJSFiles() {}
	
    public function render() {
        parent::render();              
	}
        
    public function jQueryDocumentReady() {
        $form_id_array= explode('_', $this->_form->form_slug);
        // Form int ID will always be on scond index eg: form_52_1
        $form_id= (int) $form_id_array[1];
        $validation_msg= RM_UI_Strings::get("USERNAME_EXISTS");
        $nonce = wp_create_nonce('rm_ajax_secure');
        echo "jQuery('#" . esc_attr($this->_attributes['id']) . "').change(function(){
                var data = {
                    'action': 'rm_user_exists',
                    'rm_sec_nonce': '" . esc_attr($nonce) . "',
                    'rm_slug': 'rm_user_exists',
                    'username': jQuery(this).val(),
                    'attr': 'data-rm-valid-username',
                    'form_id': '" . esc_attr($form_id) . "'
                };
                rm_user_exists(this,rm_ajax_url,data,'" . wp_kses_post($validation_msg) . "');
                });
            ";
        
        if(is_user_logged_in()){
            echo "jQuery('#" . esc_attr($this->_attributes['id']) . "').prop('disabled', true);
                jQuery('#" . esc_attr($this->_attributes['id']) . "').removeAttr('required');
                jQuery('#" . esc_attr($this->_attributes['id']) . "').removeAttr('initial-state');
            ";
        }
    }
       
}