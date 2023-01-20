<?php
$fonts_list = g_fonts_list();
?>
<table class="wp-list-table widefat fixed posts design_show_title_only_styles">
    <thead>
        <tr><th colspan="7" style="text-align: center; font-size: 2em;"><?php _e('Show title only Styles', 'BeRocket_AJAX_domain') ?>
            <span id="braapf_design_title_styles" class="dashicons dashicons-editor-help"></span></th></tr>
        <tr>
            <th class="manage-column admin-column-font-size" scope="col"><?php _e('Element', 'BeRocket_AJAX_domain') ?></th>
            <th class="manage-column admin-column-color" scope="col"><?php _e('Border color', 'BeRocket_AJAX_domain') ?></th>
            <th class="manage-column admin-column-font-size" scope="col"><?php _e('Border width', 'BeRocket_AJAX_domain') ?></th>
            <th class="manage-column admin-column-font-size" scope="col"><?php _e('Border radius', 'BeRocket_AJAX_domain') ?></th>
            <th class="manage-column admin-column-font-size" scope="col"><?php _e('Size', 'BeRocket_AJAX_domain') ?></th>
            <th class="manage-column admin-column-color" scope="col"><?php _e('Font color', 'BeRocket_AJAX_domain') ?></th>
            <th class="manage-column admin-column-color" scope="col"><?php _e('Background', 'BeRocket_AJAX_domain') ?></th>
        </tr>
    </thead>
    <tbody>
        <tr class="br_onlyTitle_title_radio_settings">
            <td><?php _e('Title', 'BeRocket_AJAX_domain') ?></td>
            <td class="admin-column-color">
                <div class="br_colorpicker_field" data-color="<?php echo br_get_value_from_array($options, array('styles_input', 'onlyTitle_title', 'bcolor'), '000000') ?>"></div>
                <input class="br_border_color_set" type="hidden" value="<?php echo br_get_value_from_array($options, array('styles_input', 'onlyTitle_title', 'bcolor')) ?>" name="br_filters_options[styles_input][onlyTitle_title][bcolor]" />
                <input type="button" value="<?php _e('Default', 'BeRocket_AJAX_domain') ?>" class="theme_default button tiny-button">
            </td>
            <td class="admin-column-font-size">
                <input class="br_border_width_set" type="text" placeholder="<?php _e('Theme Default', 'BeRocket_AJAX_domain') ?>" name="br_filters_options[styles_input][onlyTitle_title][bwidth]" value="<?php echo br_get_value_from_array($options, array('styles_input', 'onlyTitle_title', 'bwidth')); ?>" />
            </td>
            <td class="admin-column-font-size">
                <input class="br_border_radius_set" type="text" placeholder="<?php _e('Theme Default', 'BeRocket_AJAX_domain') ?>" name="br_filters_options[styles_input][onlyTitle_title][bradius]" value="<?php echo br_get_value_from_array($options, array('styles_input', 'onlyTitle_title', 'bradius')); ?>" />
            </td>
            <td class="admin-column-font-size">
                <input class="br_size_set" type="text" placeholder="<?php _e('Theme Default', 'BeRocket_AJAX_domain') ?>" name="br_filters_options[styles_input][onlyTitle_title][fontsize]" value="<?php echo br_get_value_from_array($options, array('styles_input', 'onlyTitle_title', 'fontsize')); ?>" />
            </td>
            <td class="admin-column-color">
                <div class="br_colorpicker_field" data-color="<?php echo br_get_value_from_array($options, array('styles_input', 'onlyTitle_title', 'fcolor'), '000000') ?>"></div>
                <input class="br_font_color_set" type="hidden" value="<?php echo br_get_value_from_array($options, array('styles_input', 'onlyTitle_title', 'fcolor')) ?>" name="br_filters_options[styles_input][onlyTitle_title][fcolor]" />
                <input type="button" value="<?php _e('Default', 'BeRocket_AJAX_domain') ?>" class="theme_default button tiny-button">
            </td>
            <td class="admin-column-color">
                <div class="br_colorpicker_field" data-color="<?php echo br_get_value_from_array($options, array('styles_input', 'onlyTitle_title', 'backcolor'), '000000') ?>"></div>
                <input class="br_background_set" type="hidden" value="<?php echo br_get_value_from_array($options, array('styles_input', 'onlyTitle_title', 'backcolor')) ?>" name="br_filters_options[styles_input][onlyTitle_title][backcolor]" />
                <input type="button" value="<?php _e('Default', 'BeRocket_AJAX_domain') ?>" class="theme_default button tiny-button">
            </td>
        </tr>
        <tr class="br_onlyTitle_title_radio_settings">
            <td><?php _e('Title when opened', 'BeRocket_AJAX_domain') ?></td>
            <td class="admin-column-color">
                <div class="br_colorpicker_field" data-color="<?php echo br_get_value_from_array($options, array('styles_input', 'onlyTitle_titleopened', 'bcolor'), '000000') ?>"></div>
                <input class="br_border_color_set" type="hidden" value="<?php echo br_get_value_from_array($options, array('styles_input', 'onlyTitle_titleopened', 'bcolor')) ?>" name="br_filters_options[styles_input][onlyTitle_titleopened][bcolor]" />
                <input type="button" value="<?php _e('Default', 'BeRocket_AJAX_domain') ?>" class="theme_default button tiny-button">
            </td>
            <td class="admin-column-font-size">
                <input class="br_border_width_set" type="text" placeholder="<?php _e('Theme Default', 'BeRocket_AJAX_domain') ?>" name="br_filters_options[styles_input][onlyTitle_titleopened][bwidth]" value="<?php echo br_get_value_from_array($options, array('styles_input', 'onlyTitle_titleopened', 'bwidth')); ?>" />
            </td>
            <td class="admin-column-font-size">
                <input class="br_border_radius_set" type="text" placeholder="<?php _e('Theme Default', 'BeRocket_AJAX_domain') ?>" name="br_filters_options[styles_input][onlyTitle_titleopened][bradius]" value="<?php echo br_get_value_from_array($options, array('styles_input', 'onlyTitle_titleopened', 'bradius')); ?>" />
            </td>
            <td class="admin-column-font-size">
                <input class="br_size_set" type="text" placeholder="<?php _e('Theme Default', 'BeRocket_AJAX_domain') ?>" name="br_filters_options[styles_input][onlyTitle_titleopened][fontsize]" value="<?php echo br_get_value_from_array($options, array('styles_input', 'onlyTitle_titleopened', 'fontsize')); ?>" />
            </td>
            <td class="admin-column-color">
                <div class="br_colorpicker_field" data-color="<?php echo br_get_value_from_array($options, array('styles_input', 'onlyTitle_titleopened', 'fcolor'), '000000') ?>"></div>
                <input class="br_font_color_set" type="hidden" value="<?php echo br_get_value_from_array($options, array('styles_input', 'onlyTitle_titleopened', 'fcolor')) ?>" name="br_filters_options[styles_input][onlyTitle_titleopened][fcolor]" />
                <input type="button" value="<?php _e('Default', 'BeRocket_AJAX_domain') ?>" class="theme_default button tiny-button">
            </td>
            <td class="admin-column-color">
                <div class="br_colorpicker_field" data-color="<?php echo br_get_value_from_array($options, array('styles_input', 'onlyTitle_titleopened', 'backcolor'), '000000') ?>"></div>
                <input class="br_background_set" type="hidden" value="<?php echo br_get_value_from_array($options, array('styles_input', 'onlyTitle_titleopened', 'backcolor')) ?>" name="br_filters_options[styles_input][onlyTitle_titleopened][backcolor]" />
                <input type="button" value="<?php _e('Default', 'BeRocket_AJAX_domain') ?>" class="theme_default button tiny-button">
            </td>
        </tr>
        <tr class="br_onlyTitle_filter_radio_settings">
            <td><?php _e('Filter', 'BeRocket_AJAX_domain') ?></td>
            <td class="admin-column-color">
                <div class="br_colorpicker_field" data-color="<?php echo br_get_value_from_array($options, array('styles_input', 'onlyTitle_filter', 'bcolor'), '000000') ?>"></div>
                <input class="br_border_color_set" type="hidden" value="<?php echo br_get_value_from_array($options, array('styles_input', 'onlyTitle_filter', 'bcolor')) ?>" name="br_filters_options[styles_input][onlyTitle_filter][bcolor]" />
                <input type="button" value="<?php _e('Default', 'BeRocket_AJAX_domain') ?>" class="theme_default button tiny-button">
            </td>
            <td class="admin-column-font-size">
                <input class="br_border_width_set" type="text" placeholder="<?php _e('Theme Default', 'BeRocket_AJAX_domain') ?>" name="br_filters_options[styles_input][onlyTitle_filter][bwidth]" value="<?php echo br_get_value_from_array($options, array('styles_input', 'onlyTitle_filter', 'bwidth')) ?>" />
            </td>
            <td class="admin-column-font-size">
                <input class="br_border_radius_set" type="text" placeholder="<?php _e('Theme Default', 'BeRocket_AJAX_domain') ?>" name="br_filters_options[styles_input][onlyTitle_filter][bradius]" value="<?php echo br_get_value_from_array($options, array('styles_input', 'onlyTitle_filter', 'bradius')) ?>" />
            </td>
            <td class="admin-column-font-size">
                <input class="br_size_set" type="text" placeholder="<?php _e('Theme Default', 'BeRocket_AJAX_domain') ?>" name="br_filters_options[styles_input][onlyTitle_filter][fontsize]" value="<?php echo br_get_value_from_array($options, array('styles_input', 'onlyTitle_filter', 'fontsize')) ?>" />
            </td>
            <td class="admin-column-color">
                <div class="br_colorpicker_field" data-color="<?php echo br_get_value_from_array($options, array('styles_input', 'onlyTitle_filter', 'fcolor'), '000000') ?>"></div>
                <input class="br_font_color_set" type="hidden" value="<?php echo br_get_value_from_array($options, array('styles_input', 'onlyTitle_filter', 'fcolor')) ?>" name="br_filters_options[styles_input][onlyTitle_filter][fcolor]" />
                <input type="button" value="<?php _e('Default', 'BeRocket_AJAX_domain') ?>" class="theme_default button tiny-button">
            </td>
            <td class="admin-column-color">
                <div class="br_colorpicker_field" data-color="<?php echo br_get_value_from_array($options, array('styles_input', 'onlyTitle_filter', 'backcolor'), '000000') ?>"></div>
                <input class="br_background_set" type="hidden" value="<?php echo br_get_value_from_array($options, array('styles_input', 'onlyTitle_filter', 'backcolor')) ?>" name="br_filters_options[styles_input][onlyTitle_filter][backcolor]" />
                <input type="button" value="<?php _e('Default', 'BeRocket_AJAX_domain') ?>" class="theme_default button tiny-button">
            </td>
        </tr>
    </tbody>
    <tfoot>
        <tr>
            <th class="manage-column admin-column-theme" scope="col" colspan="7">
                <input type="button" value="<?php _e('Set all to theme default', 'BeRocket_AJAX_domain') ?>" class="all_theme_default button">
                <div style="clear:both;"></div>
            </th>
        </tr>
    </tfoot>
</table>