<?php
if (!defined('WPINC')) {
    die('Closed');
}
?>
<h1><?php echo wp_kses_post(RM_UI_Strings::get('CRIT_ERROR_TITLE')); ?></h1>
<h3><?php echo wp_kses_post(RM_UI_Strings::get('CRIT_ERROR_SUBTITLE')); ?></h3>
<ul>
<?php
global $regmagic_errors;
if(is_array($regmagic_errors)){
    foreach($regmagic_errors as $err)
    {
       //Display only fatal errors
       if(!$err->should_cont)
           echo "<li>- ".wp_kses_post($err->msg)."</li>";
    }
}

?>
</ul>