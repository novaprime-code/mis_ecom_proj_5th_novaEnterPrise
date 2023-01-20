<?php

/**
 *
 *
 * @author CMSHelplive
 */
class RM_Dashboard_Widget_Service extends RM_Services
{
    public function get_count_summary()
    {
        $Q = 'COUNT(#UID#) AS count';
        $WQ_today = "`submitted_on` BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 1 day)";
        $WQ_yesterday = "`submitted_on` BETWEEN (CURDATE() - 1) AND DATE_ADD(CURDATE() - 1, INTERVAL 1 day)";

        //$WQ_week  = "`submitted_on` BETWEEN DATE_ADD(CURDATE(), INTERVAL 1-DAYOFWEEK(CURDATE()) DAY) AND DATE_ADD(CURDATE(), INTERVAL 7-DAYOFWEEK(CURDATE()) DAY)";
        $WQ_week  = "WEEKOFYEAR(submitted_on)=WEEKOFYEAR(CURDATE())";
        
        $WQ_month = "`submitted_on` BETWEEN DATE_SUB(CURDATE(),INTERVAL (DAY(CURDATE())-1) DAY) AND LAST_DAY(NOW())";
        $WQ_last_week = "WEEKOFYEAR(submitted_on)=WEEKOFYEAR(CURDATE())-1";
        $WQ_last_month = "`submitted_on` BETWEEN DATE_ADD(LAST_DAY(DATE_SUB(NOW(), INTERVAL 2 MONTH)), INTERVAL 1 DAY) AND LAST_DAY(DATE_SUB(NOW(), INTERVAL 1 MONTH));";
        $cs1 = RM_DBManager::get_generic('SUBMISSIONS', $Q, $WQ_today);
        $cs2 = RM_DBManager::get_generic('SUBMISSIONS', $Q, $WQ_week);
        $cs3 = RM_DBManager::get_generic('SUBMISSIONS', $Q, $WQ_month);
        $cs4 = RM_DBManager::get_generic('SUBMISSIONS', $Q, $WQ_yesterday);
        $cs5 = RM_DBManager::get_generic('SUBMISSIONS', $Q, $WQ_last_week);
        $cs6 = RM_DBManager::get_generic('SUBMISSIONS', $Q, $WQ_last_month);
        
        $c1 = !$cs1[0]->count ? 0 : $cs1[0]->count;
        $c2 = !$cs2[0]->count ? 0 : $cs2[0]->count;
        $c3 = !$cs3[0]->count ? 0 : $cs3[0]->count;
        $c4 = !$cs4[0]->count ? 0 : $cs4[0]->count;
        $c5 = !$cs5[0]->count ? 0 : $cs5[0]->count;
        $c6 = !$cs6[0]->count ? 0 : $cs6[0]->count;
        if(!$c1 && !$c2 && !$c3 && !$c4 && !$c5 && !$c6){
            $demo = 1;
        }
        else{
            $demo = 0;
        }
        if($demo){
            $chart_1 = array(1, 2, 3);
            $chart_2 = array(1, 2, 3); 
        }
        else{
            $chart_1 = array($c1, $c2, $c3);
            $chart_2 = array($c4, $c5, $c6);
        }
        
        return (object)array('today'=> $c1,'yesterday'=>$c4,'this_week'=> $c2,'last_week'=> $c5,'this_month'=> $c3,'last_month'=> $c6, 'demo'=> $demo, 'chart_1'=>$chart_1, 'chart_2'=>$chart_2);
    }
    public function get_popular_forms(){
        $forms = RM_DBManager::get_all('FORMS');
        $popular_forms = array();
        if(!empty($forms)){
            foreach ($forms as $key => $form) {
                $count = RM_DBManager::count('SUBMISSIONS', array('form_id'=>$form->form_id));
                if($count){
                $popular_forms[] = array('form_id'=>$form->form_id,'form_name'=> $form->form_name, 'count'=>$count,'created_on'=>RM_Utilities::localize_time($form->created_on, 'd M Y'));
                }
            }
        }
        if(!empty($popular_forms)){
            $count = array_column($popular_forms, 'count');
            array_multisort($count, SORT_DESC, $popular_forms);
        }
        return $popular_forms;
        
    }
    public function generate_date_paramter($interval="days"){
        $date = array();
        switch($interval){
            case 'days':
                $day = 6;
                while($day >= 0){
                    $required_date = date('Y-m-d', strtotime('-'.$day.' days'));
                    $date[] = array("date"=> date('M d',strtotime($required_date)), "year" => date("Y",strtotime($required_date)), 'month' => date("m",strtotime($required_date)), 'day'=> date("d",strtotime($required_date)) );
                    $day--;
                }
                break;

            case 'weeks':
                $weeks = 6;
                while($weeks >= 0){
                    $required_date = date('Y-m-d', strtotime('-'.$weeks.' weeks'));
                    $date[] = array("date"=> date('M d',strtotime($required_date)), "after" => date("Y-m-d",strtotime($required_date)), 'before' => date("Y-m-d",strtotime('+7 day',strtotime($required_date))) );
                    $weeks--;
                }
                break;

            case 'months':
                $months = 11;
                while($months >= 0){
                    $required_date = date('Y-m-d', strtotime('-'.$months.' months'));
                    $date[] = array("date"=> date('M',strtotime($required_date)), "after" => date("Y-m-d",strtotime($required_date)), 'before' => date("Y-m-d",strtotime('+1 month',strtotime($required_date))) );
                    $months--;
                }
            break;

            case 'years':
                $users = get_users( array( 'number' => 1, 'order'=>'ASC' ) );
                $site_year = date('Y',strtotime($users['0']->data->user_registered));
                while($site_year <= date('Y')){
                    $required_date = date('Y-m-d', strtotime('01-01-'.$site_year));
                    $date[] = array("date"=> date('Y',strtotime($required_date)), "after" => date("Y-m-d",strtotime($required_date)), 'before' => date("Y-m-d",strtotime('+1 years',strtotime($required_date))) );
                    $site_year++;
                }
            break;
        }
        return $date;
    }
    public function get_user_statics($interval="days"){
        $users = array();
        $args = array();
        $dates = $this->generate_date_paramter($interval);
        foreach($dates as $date){
            $args['date_query'] = array($date);
            $users_count = count(get_users($args));
            $data_date[] = $date['date'];
            $data_count[] = $users_count;
        }
        $users['date'] = $data_date;
        $users['count'] = $data_count;
        switch ($interval) {
            case 'days':
                $users['label'] = RM_UI_Strings::get("DASHBOARD_USERS_DAYS_CHART_TITLE");
                break;

            case 'weeks':
                $users['label'] = RM_UI_Strings::get("DASHBOARD_USERS_WEEKS_CHART_TITLE");
                break;

            case 'months':
                $users['label'] = RM_UI_Strings::get("DASHBOARD_USERS_MONTHS_CHART_TITLE");
                break;

            case 'years':
                $users['label'] = RM_UI_Strings::get("DASHBOARD_USERS_YEARS_CHART_TITLE");
                break;
        }
        return $users;
    }

    public function get_latest_users(){
        $args = array('number'=>5,'order'=>'DESC');
        $users = get_users($args);
        return $users;
    }

    public function get_dashboard_statics(){
        global $wp_roles;
        $statics = array();
        $users = count_users();
        $all_roles = $wp_roles->roles;
        $submissions = RM_DBManager::get_all('SUBMISSIONS');
        $submissions = !empty($submissions) ? count($submissions) : 0;
        $forms = RM_DBManager::get_all('FORMS');
        $forms = !empty($forms) ? count($forms) : 0;
        $all_users = get_users( array( 'number' => 5, 'orderby' => 'ID', 'order'=>'DESC' ) );
        $avatar ='<div class="rm-dash-user-avatar-bulk"><ul class="rm-dash-user-avatar">';
        if($all_users){
            foreach($all_users as $user){
                if(class_exists('Profile_Magic')):
                    $pg_user_avatar_id = get_user_meta( $user->ID, 'pm_user_avatar', true );
                    if($pg_user_avatar_id):
                        $avatar_url = wp_get_attachment_url($pg_user_avatar_id,'thumbnail');
                    else:
                        $avatar_url = get_avatar_url($user->ID);
                    endif;
                else:
                    $avatar_url = get_avatar_url($user->ID);
                endif;
                $avatar .= '<li class="rm-dash-avatar"><img src="'.$avatar_url.'"></li>';
            }
        }
        $avatar .= '</ul></div>';
        $statics[] = array('title'=>RM_UI_Strings::get('DASHBOARD_STATICS_FORMS_TITLE'), 'state'=>$forms, 'link_label'=>'Add New', 'link'=>'rm_form_setup');
        $statics[] = array('title'=>RM_UI_Strings::get('DASHBOARD_STATICS_SUBMISSION_TITLE'), 'state'=>$submissions, 'link_label'=>'Inbox', 'link'=>'rm_submission_manage');
        $statics[] = array('title'=>RM_UI_Strings::get('DASHBOARD_STATICS_USER_TITLE'), 'state'=>$users['total_users'].' '.$avatar, 'link_label'=>'User Manager', 'link'=>'rm_user_manage');
        $statics[] = array('title'=>RM_UI_Strings::get('DASHBOARD_STATICS_USER_ROLES_TITLE'), 'state'=>count($all_roles), 'link_label'=>'Role Manager', 'link'=>'rm_user_role_manage');
        
        return $statics;
    }

    public function get_feature_data(){
        $data = array();
        $data[] = array('label'=>RM_UI_Strings::get('DASHBOARD_SETTINGS_LOGIN_FORM_TITLE'),'slug'=>'rm_login_sett_manage');
        $data[] = array('label'=>RM_UI_Strings::get('DASHBOARD_SETTINGS_GENERAL_TITLE'),'slug'=>'rm_options_general');
        $data[] = array('label'=>RM_UI_Strings::get('DASHBOARD_SETTINGS_PAYMENT_TITLE'),'slug'=>'rm_options_payment');
        $data[] = array('label'=>RM_UI_Strings::get('DASHBOARD_SETTINGS_MEGIC_TITLE'),'slug'=>'rm_options_fab');
        $data[] = array('label'=>RM_UI_Strings::get('DASHBOARD_SETTINGS_MAILCHIMP_TITLE'),'slug'=>'rm_options_thirdparty');
        $data[] = array('label'=>RM_UI_Strings::get('DASHBOARD_SETTINGS_EMAIL_TITLE'),'slug'=>'rm_options_autoresponder');
        $data[] = array('label'=>RM_UI_Strings::get('DASHBOARD_SETTINGS_PRIVACY_TITLE'),'slug'=>'rm_options_privacy');
        $data[] = array('label'=>RM_UI_Strings::get('DASHBOARD_SETTINGS_AUTOMATION_TITLE'),'slug'=>'rm_ex_chronos_manage_tasks');
        $data[] = array('label'=>RM_UI_Strings::get('DASHBOARD_SETTINGS_USER_ROLE_TITLE'),'slug'=>'rm_user_role_manage');
        $data[] = array('label'=>RM_UI_Strings::get('DASHBOARD_SETTINGS_PRODUCT_TITLE'),'slug'=>'rm_paypal_field_manage');
        return $data;
    }
    
    public function get_logins_data($timerange=7){
        
        $request = new stdClass;
        $request->req = array('rm_tr'=>$timerange);
        return $login_logs = RM_DBManager::get_login_log_results($request->req,$offset=0,$limit=5);
        
    }
    
    public function get_login_logs_stats($timerange=7){
        $service = new RM_Analytics_Service();
        return $service->day_wise_login_stats($timerange);
    }
    
    public function get_latest_attachments(){
        if(defined('REGMAGIC_ADDON') && class_exists('RM_Dashboard_Widget_Service_Addon')) {
            $addon_service = new RM_Dashboard_Widget_Service_Addon();
            return $addon_service->get_latest_attachments();
        }
        return array();
    }
    public function get_latest_payments(){
        if(defined('REGMAGIC_ADDON') && class_exists('RM_Dashboard_Widget_Service_Addon')) {
            $addon_service = new RM_Dashboard_Widget_Service_Addon();
            return $addon_service->get_latest_payments();
        }
        return array();
    }
}