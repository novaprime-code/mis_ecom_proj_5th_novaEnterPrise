<script type="text/javascript">
    jQuery(document).ready(function(){
        jQuery('#the-list').find('[data-slug="custom-registration-form-builder-with-submission-manager"] span.deactivate a').click(function(event){
            alert('<?php _e('Please deactivate RegistrationMagic Premium before deactivating RegistrationMagic.', 'custom-registration-form-builder-with-submission-manager'); ?>');
            event.preventDefault();
        });
    });
</script>