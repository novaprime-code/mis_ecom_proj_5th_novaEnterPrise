<?php
if (!defined('WPINC')) {
    die('Closed');
}
//Front-end form template
wp_enqueue_style( 'rm_material_icons', RM_BASE_URL . 'admin/css/material-icons.css' );

if(isset($data->banned) && $data->banned == true)
    echo "<div class=rm-notice-banned>".RM_UI_Strings::get('MSG_BANNED')."</div>";
else
    $data->fe_form->render(array('stat_id' => $data->stat_id, 'submission_id' => isset($data->submission_id)?$data->submission_id:null));