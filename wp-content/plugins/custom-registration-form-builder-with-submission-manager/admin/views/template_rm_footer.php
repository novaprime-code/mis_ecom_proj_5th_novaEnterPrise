<?php
if (!defined('WPINC')) {
    die('Closed');
}

if(!empty($_GET['rm_form_id'])): 
  $form_id= absint(sanitize_text_field($_GET['rm_form_id']));  
  $form= new RM_Forms();
  $form->load_from_db($form_id);
  if(empty($form->form_id))
      return;
?>
<script>
    document.title = "<?php echo esc_html($form->form_name); ?>";
</script>    
<?php endif; ?>