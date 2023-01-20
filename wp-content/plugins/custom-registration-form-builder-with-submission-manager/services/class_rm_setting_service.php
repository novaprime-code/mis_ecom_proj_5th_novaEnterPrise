<?php

class RM_Setting_Service {

    public $model;

    public function set_model($model){
        $this->model= $model;
    }

    public function get_model(){
        return $this->model;
    }

    public function get_options()
    {
        return $this->model->get_all_options();
    }

    public function save_options($options)
    {
        return $this->model->set_values($options);
    }
    public function get_custom_db_tabs(){
        $tabs = array();
        if(defined('REGMAGIC_ADDON') && version_compare(RM_ADDON_PLUGIN_VERSION,'5.1.2.0','>=')){
            
            $ctabs = RM_DBManager_Addon::get_all_tabs();
            if(!empty($ctabs)){
                foreach ($ctabs as $key => $ctab) {
                    $icon='';
                    if($ctab->tab_icon){
                        $icon = $ctab->tab_icon.';';
                    }
                    $ct = array('label'=>$ctab->tab_label,'icon'=>$icon,'id'=>'rm_ctab_'.$ctab->tab_id,'class'=>$ctab->tab_class.'-'.$ctab->tab_id,'status'=> 1);
                    $tabs['rm_ctab_'.$ctab->tab_id] = $ct; 
                }
            }
        }
        return $tabs;
    }
    public function rm_custom_tabs($db_tabs, $tabs){
        $custom_tabs = apply_filters('rm_profile_tabs', array());
        $after_tabs = apply_filters('rm_after_front_tabtitle_listing',array());
        if(!empty($after_tabs)){
            foreach ($after_tabs as $value) {
                $key = $value['id'];
                $custom_tabs[$key] = $value;
            }
        }
        /*if(defined('REGMAGIC_ADDON')){
            $custom_tabs['rm_inbox_tab'] = array('label'=>RM_UI_Strings::get('LABEL_INBOX'),'icon'=>'mail','id'=>'rm_inbox_tab','class'=>'rmtab-inbox','status'=> 1);
        }*/
        if(defined('REGMAGIC_ADDON') && version_compare(RM_ADDON_PLUGIN_VERSION,'5.1.2.0','>=')){
            $ctabs = $this->get_custom_db_tabs();
            foreach ($ctabs as $key => $value) {
                $custom_tabs[$key] = $value;
            }
        }
        foreach ($custom_tabs as $key => $value) {
            $tabs[$key] = $value;
        }
        $default_tabs = array();
        foreach ($tabs as $key => $value) {
            $default_tabs[] = $key;
        }
        if(!empty($db_tabs)){
            foreach ($db_tabs as $key => $value) {
                if(!in_array($key, $default_tabs)){
                    if(!isset($custom_tabs[$key])){
                        unset($db_tabs[$key]);
                    }
                }
            }
        }

        if(!empty($default_tabs) && !empty($db_tabs)){
            foreach ($default_tabs as $key => $value) {
                if(!isset($db_tabs[$value])){
                    $db_tabs[$value] = $custom_tabs[$value];
                }
            }
        }

        if(defined('REGMAGIC_ADDON') && version_compare(RM_ADDON_PLUGIN_VERSION,'5.1.2.0','>=')){
            if(!empty($ctabs) && !empty($db_tabs)){
                foreach ($ctabs as $key => $value) {
                    if(!isset($db_tabs[$key])){
                        $db_tabs[$key] = $ctabs[$key];
                    }
                }
            }
        }
        return !empty($db_tabs) ? $db_tabs : $tabs;
    }
    public function rm_profile_tabs(){
        $db_tabs = array();
        $db_tabs = get_option('rm_profile_tabs_order_status');
        $tabs = array();
        
        $custom_tabs = $this->rm_custom_tabs($db_tabs, $tabs);
        return $rm_profile_tabs_order_status = maybe_unserialize($custom_tabs, $tabs);
        //return apply_filters('rm_profile_tabs',$rm_profile_tabs_order_status);
    }
    public function generate_profile_tab_links()
    {   
        $html='';
        $profile_tabs = $this->rm_profile_tabs();
        if (!empty($profile_tabs)):                  
            foreach($profile_tabs as $key=>$tab):
                if($tab['status']){
                    if($key=='rm_inbox_tab' ){
                        if(defined('REGMAGIC_ADDON')){
                            $html .= '<div class="'.$tab["class"].' rmtabs_head" title="'.$tab["label"].'" data-rmt-tabcontent="#'.$tab["id"].'"><i class="material-icons">'.$tab["icon"].'</i>'.$tab["label"].'</div>';
                        }
                    }else{
                        $html .= '<div class="'.$tab["class"].' rmtabs_head" title="'.$tab["label"].'" data-rmt-tabcontent="#'.$tab["id"].'"><i class="material-icons">'.$tab["icon"].'</i>'.$tab["label"].'</div>';
                    }
                }
            endforeach;
        endif;
        $html .= $this->generate_profile_tab_links_static();
        return $html;
    }

    public function generate_profile_tab_links_static(){
        $html ='';
        if (!is_user_logged_in()) {
            $html .= '<div class="rmtab-log-off rm-form-toggle rmtabs_head" title="'.RM_UI_Strings::get("LABEL_LOG_OFF").'" data-rmt-tabcontent="__rmt_noop" onclick="document.getElementById("rm_front_submissions_nav_form").submit()"><i class="material-icons">vpn_key</i>'.RM_UI_Strings::get("LABEL_LOG_OFF").'</div>';
        } else {
            $html .='<div class="rmtab-reset-pass rm-form-toggle rmtabs_head" title="'.RM_UI_Strings::get("LABEL_RESET_PASS").'" data-rmt-tabcontent="__rmt_noop" onclick="resetpassword();"><i class="material-icons">vpn_key</i>'.RM_UI_Strings::get("LABEL_RESET_PASS").'</div>';
            $html .= '<div class="rmtab-logout rmtabs_head" title="Logout"><i class="material-icons">exit_to_app</i><a href="'. wp_logout_url(get_permalink()).'">'.__("Logout", "custom-registration-form-builder-with-submission-manager").'</a></div>';
            $html .='<form method="post" id="rm_front_submissions_nav_form">
                <input type="hidden" name="rm_slug" value="rm_front_log_off">
            </form>
            <form method="post" id="rm_front_submissions_respas_form">
                <input type="hidden" name="rm_slug" value="rm_front_reset_pass_page">
                <input type="hidden" name="RM_CLEAR_ERROR" value="true">
            </form>';
           
        }

        return $html;
    }

    public function rm_profile_tabs_content($data,$uid){

        do_action('rm_profile_tabs_content',$data, $uid);
        if(defined('REGMAGIC_ADDON') && version_compare(RM_ADDON_PLUGIN_VERSION,'5.1.2.0','>=')){
            $this->rm_profile_custom_tabs_content();
        }
    }

    public function rm_profile_custom_tabs_content(){
        if(defined('REGMAGIC_ADDON') && version_compare(RM_ADDON_PLUGIN_VERSION,'5.1.2.0','>=')){
            $ctabs = RM_DBManager_Addon::get_all_tabs();
            $tabs = $this->rm_profile_tabs();
            $activetabs = array();
            if(!empty($ctabs)){
                foreach ($tabs as $key => $value) {
                    $activetabs[] = $key;
                }
                foreach ($ctabs as $key => $ctab) {
                    if(in_array('rm_ctab_'.$ctab->tab_id, $activetabs)){
                        if($tabs['rm_ctab_'.$ctab->tab_id]['status']==1){?>
                        <div class="<?php echo esc_attr($ctab->tab_class); ?>" id="rm_ctab_<?php echo esc_attr($ctab->tab_id);?>" style="display: none;">
                            <?php 
                            $pattern = get_shortcode_regex();
                            echo preg_replace_callback( "/$pattern/s", 'do_shortcode_tag', $ctab->tab_content ); 
                            ?>
                        </div>
                    <?php
                        }
                    }
                }
            }
        }   
    }
}