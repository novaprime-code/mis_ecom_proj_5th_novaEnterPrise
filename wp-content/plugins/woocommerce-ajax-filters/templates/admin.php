<div style="font-size:1.5em;padding:10px 0;line-height: 1.2em;">
    <?php _e('Widget will be removed in future please use <strong>AAPF Filters Group</strong> instead.', 'BeRocket_AJAX_domain'); ?>
    <div><?php echo sprintf(__('You can add filter to %s that has limitation', 'BeRocket_AJAX_domain'), '<a href="' . admin_url('edit.php?post_type=br_filters_group') . '">' . __('Filters group', 'BeRocket_AJAX_domain') . '</a>'); ?></div>
    <div>Or you can replace deprecated widgets with new automatically in <a href="<?php echo admin_url('admin.php?page=br-product-filters'); ?>">Plugin settings</a>->Advanced tab</div>
</div>
<script>
    if( typeof(br_widget_set) == 'function' )
        br_widget_set();
</script>
