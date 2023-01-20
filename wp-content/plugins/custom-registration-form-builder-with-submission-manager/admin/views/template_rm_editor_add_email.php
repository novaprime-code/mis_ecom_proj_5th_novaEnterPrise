<?php
if (!defined('WPINC')) {
    die('Closed');
}
if(!empty($data->emails)){
$option_string='';

if(isset($data->editor_control_id) && $data->editor_control_id)
    $select_input_id = $data->editor_control_id;
else
 	$select_input_id = 'rm_editor_add_email';
?>
<select id="<?php echo esc_attr($select_input_id);?>">
    <option value="0"><?php echo wp_kses_post(RM_UI_Strings::get("LABEL_ADD_EMAIL")); ?></option>
    <?php
    foreach($data->emails as $email)
    {
        $opt_value= $email->field_type.'_'.$email->field_id;
        $type= strtolower($email->field_type);
        if($type=='username'){
            $opt_value= 'Username';
        } else if($type=='userpassword'){
            $opt_value= 'UserPassword';
        }
        echo '<option value="'.esc_attr($opt_value).'">'.wp_kses_post($email->field_label).'</option>';
    }
?>
</select>
<?php }