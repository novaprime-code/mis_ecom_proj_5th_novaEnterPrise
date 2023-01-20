<?php extract($berocket_var_exclude_list); ?>
<div class="br_accordion">
    <h3><?php _e('Include / Exclude List', 'BeRocket_AJAX_domain'); ?></h3>
    <div>
<?php if ( is_array(berocket_isset($terms)) ) { ?>
<?php foreach( $terms as $term ) { ?>
    <div class="element-depth-<?php echo (empty($term->depth) ? '0' : $term->depth); ?>">
        <label>
            <input type="checkbox" value="<?php echo berocket_isset($term, 'term_id'); ?>" name="%field_name%[]"<?php if( in_array( $term->term_id, $selected ) ) echo ' checked'; ?>>
            <?php echo berocket_isset($term, 'name') ?>
        </label>
    </div>
<?php } ?>
<?php
}
?>
</div>
</div>
<script>
    if( typeof(brjsf_accordion) == 'function' ) {
        brjsf_accordion(jQuery( ".br_accordion" ));
    }
</script>
