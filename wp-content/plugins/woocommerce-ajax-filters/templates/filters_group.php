<div class="berocket_filter_groups">
    <table>
        <tr>
            <th><?php _e('Custom CSS class', 'BeRocket_AJAX_domain'); ?></th>
            <td>
                <input type="text" name="<?php echo $post_name; ?>[custom_class]" value="<?php echo br_get_value_from_array($filters, 'custom_class'); ?>">
                <small><?php _e('use white space for multiple classes', 'BeRocket_AJAX_domain');?></small>
            </td>
        </tr>
        <?php do_action('berocket_aapf_filters_group_settings', $filters, $post_name, $post); ?>
    </table>
    <h3><?php _e('Filters In Group', 'BeRocket_AJAX_domain'); ?></h3>
    <?php
    $query = new WP_Query(array('post_type' => 'br_product_filter', 'nopaging' => true));
    if ( $query->have_posts() ) {
        echo '<select class="berocket_filter_list">';
        while ( $query->have_posts() ) {
            $query->the_post();
            echo '<option data-name="' . get_the_title() . '" value="' . get_the_id() . '">' . get_the_title() . ' (ID:' . get_the_id() . ')</option>';
        }
        echo '</select>';
        echo ' <a class="button berocket_add_filter_to_group" href="#add_filter">' . __('Add filter', 'BeRocket_AJAX_domain') . '</a>';
        echo ' <a href="' . admin_url('edit.php?post_type=br_product_filter') . '">' . __('Manage filters', 'BeRocket_AJAX_domain') . '</a>';
        wp_reset_postdata();
    }
    $filters_correct = 0;
    $errors = array();
    if( isset($filters['filters']) && is_array($filters['filters']) ) {
        echo '<ul class="berocket_filter_added_list" data-name="' . $post_name . '[filters][]" data-url="' . admin_url('post.php') . '">';
        foreach($filters['filters'] as $filter) {
            $filter_id = $filter;
            $filter_post = get_post($filter_id);
            if( ! empty($filter_post) ) {
                echo '<li class="berocket_filter_added_' . $filter_id . '"><fa class="fa fa-bars"></fa>
                    <input type="hidden" name="'.$post_name.'[filters][]" value="' . $filter_id . '">
                    ' . $filter_post->post_title . ' <small>ID:' . $filter_id . '</small>
                    <i class="fa fa-times"></i>
                    <a class="berocket_edit_filter fas fa-pencil-alt" target="_blank" href="' . get_edit_post_link($filter_id) . '"></a>
                    <div class="berocket_hidden_clickable_options">
                        ' . __('Width', 'BeRocket_AJAX_domain') . '<input type="text" name="'.$post_name.'[filters_data][' . $filter_id . '][width]" value="' . br_get_value_from_array($filters, array('filters_data', $filter_id, 'width')) . '" placeholder="100%">
                    </div>
                </li>';
                $filters_correct++;
            } else {
                $errors[] = $filter_id;
            }
        }
        echo '</ul>';
    }
    if( count($errors) > 0 ) {
        BeRocket_error_notices::add_plugin_error(1, 'Filter was removed, but it was added to group', array(
            'filter_ids'   => $errors
        ));
    }
    if($filters_correct == 0) {
        echo '<p>' . __('No one filters was created. Please create filters first', 'BeRocket_AJAX_domain')
        . ' <a href="' . admin_url('edit.php?post_type=br_product_filter') . '">' . __('FILTERS PAGE', 'BeRocket_AJAX_domain') . '</a></p>';
    }
    $popup_text = '<p style="font-size:24px;">'
    . __('Group do not have filters. Please add filters before save it.', 'BeRocket_AJAX_domain') 
    . '</p>'
    . '<p style="font-size:24px;">' . __('You can create new filters or edit it on', 'BeRocket_AJAX_domain')
    . ' <a href="' . admin_url('edit.php?post_type=br_product_filter') . '">' . __('FILTERS PAGE', 'BeRocket_AJAX_domain') . '</a></p>';
    BeRocket_popup_display::add_popup(
        array(
            'height'        => '250px',
            'width'         => '700px',
        ),  
        $popup_text, 
        array('event_new' => array('type' => 'event', 'event' => 'braapf_group_required_filters'))
    );
    ?>
</div>
