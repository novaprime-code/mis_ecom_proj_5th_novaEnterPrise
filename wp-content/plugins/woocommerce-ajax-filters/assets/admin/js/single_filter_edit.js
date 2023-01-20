var braapf_all_sameas_custom_taxonomy,
braapf_all_sameas_attribute,
braapf_current_attribute,
braapf_current_template_styles,
braapf_current_specific_styles,
braapf_current_template,
braapf_current_specific,
braapf_any_style_checked,
braapf_get_style_checked,
braapf_get_current_taxonomy_name,
braapf_load_color_image_pick,
braapf_current_taxonomy_hierarchical,
braapf_any_widget_selected,
braapf_disable_height_control,
braapf_hide_child_attributes_select,
braapf_price_range_changes,
braapf_price_symbol_before_price,
braapf_checked_style_parent;
(function ($){
    function braapf_sbs_numeric_set() {
        $('.berocket_sbs .brsbs_numeric:visible').each(function(i, elem) {
            $(this).text(i+1);
        });
    }
    function braapf_all_sameas_get(sameas) {
        var elements = [];
        $('#braapf_filter_type').find('option[data-optionsameas="'+sameas+'"]').each(function(){
            elements.push($(this).val());
        });
        $('#braapf_filter_type').find('option[data-sameas="'+sameas+'"]').not('option[data-optionsameas="'+sameas+'"]').each(function(){
            elements.push($(this).val());
        });
        return elements;
    }
    braapf_all_sameas_custom_taxonomy = function () {
        return braapf_all_sameas_get('custom_taxonomy');
    }
    braapf_all_sameas_attribute = function () {
        return braapf_all_sameas_get('attribute');
    }
    braapf_current_attribute = function () {
        berocket_show_element_hooked_data.push('#braapf_attribute');
        berocket_show_element_hooked_data.push('#braapf_custom_taxonomy');
        berocket_show_element_hooked_data.push('#braapf_filter_type');
        var result = "";
        var filter_type = $('#braapf_filter_type option:selected');
        if( filter_type.val() == filter_type.data('sameas') ) {
            if( $('#braapf_'+filter_type.data('sameas')).length ) {
                result = $('#braapf_'+filter_type.data('sameas')).val();
            }
        } else {
            if( typeof(filter_type.data('attribute')) != 'undefined' ) {
                result = filter_type.data('attribute');
            }
        }
        return result;
    }
    braapf_current_template_styles = function () {
        berocket_show_element_hooked_data.push('#braapf_attribute');
        berocket_show_element_hooked_data.push('#braapf_custom_taxonomy');
        berocket_show_element_hooked_data.push('#braapf_filter_type');
        berocket_show_element_hooked_data.push('.braapf_widget_type input[type=radio]');
        if( $('.braapf_widget_type input[type=radio]:checked').val() == 'filter' ) {
            var templates = $('#braapf_filter_type option:selected').data('templates');
        } else {
            var templates = $('.braapf_widget_type input[type=radio]:checked').data('templates');
        }
        if( typeof(templates) == 'undefined' || ! templates ) {
            templates = "";
        }
        return templates;
    }
    braapf_current_specific_styles = function () {
        berocket_show_element_hooked_data.push('#braapf_attribute');
        berocket_show_element_hooked_data.push('#braapf_custom_taxonomy');
        berocket_show_element_hooked_data.push('#braapf_filter_type');
        if( $('.braapf_widget_type input[type=radio]:checked').val() == 'filter' ) {
            var specific = jQuery('#braapf_filter_type option:selected').data('specific');
        } else {
            var specific = $('.braapf_widget_type input[type=radio]:checked').data('specific');
        }
        if( typeof(specific) == 'undefined' || ! specific ) {
            specific = "";
        }
        return specific;
    }
    braapf_current_template = function() {
        berocket_show_element_hooked_data.push('.braapf_style input[name="br_product_filter[style]"]');
        var template = "";
        var current_style = $('.braapf_style input[name="br_product_filter[style]"]:enabled:checked');
        if( current_style.length ) {
            template = current_style.data('template');
        }
        return template;
    }
    braapf_current_specific = function() {
        berocket_show_element_hooked_data.push('.braapf_style input[name="br_product_filter[style]"]');
        var specific = "";
        var current_style = $('.braapf_style input[name="br_product_filter[style]"]:enabled:checked');
        if( current_style.length && current_style.data('specific') ) {
            specific = current_style.data('specific');
        }
        return specific;
    }
    braapf_any_style_checked = function() {
        berocket_show_element_hooked_data.push('.braapf_style input[name="br_product_filter[style]"]');
        berocket_show_element_hooked_data.push('.braapf_widget_type input[type=radio]');
        berocket_show_element_hooked_data.push('.brsbs_attribute_setup select');
        return ($('.braapf_style input[type=radio]:enabled:checked + label:visible').length > 0);
    }
    braapf_get_style_checked = function() {
        berocket_show_element_hooked_data.push('.braapf_style input[name="br_product_filter[style]"]');
        if($('.braapf_style input[type=radio]:enabled:checked').length > 0) {
            return $('.braapf_style input[type=radio]:enabled:checked').val();
        }
        return ($('.braapf_style input[type=radio]:enabled:checked').length > 0);
    }
    braapf_get_current_taxonomy_name = function() {
        berocket_show_element_hooked_data.push('#braapf_attribute');
        berocket_show_element_hooked_data.push('#braapf_custom_taxonomy');
        berocket_show_element_hooked_data.push('#braapf_filter_type');
        jQuery(document).trigger('braapf_get_current_taxonomy_name');
        var taxonomy_name = false;
        var filter_type = $('#braapf_filter_type option:selected');
        if( filter_type.val() == filter_type.data('sameas') ) {
            if( $('#braapf_'+filter_type.data('sameas')).length ) {
                taxonomy_name = $('#braapf_'+filter_type.data('sameas')).val();
            }
        } else {
            if( typeof(filter_type.data('attribute')) != 'undefined' ) {
                taxonomy_name = filter_type.data('attribute');
            }
        }
        return taxonomy_name;
    }
    //COLOR/IMAGE SELECT
    braapf_load_color_image_pick = function() {
        var taxonomy_name = braapf_get_current_taxonomy_name();
        var specific = braapf_current_specific();
        var filtertype = jQuery('.braapf_filter_type_data *').serialize();
        filtertype = 'type='+specific+'&'+filtertype;
        var old_filtertype = $('.braapf_widget_color_pick').data('filtertype');
        if( filtertype != old_filtertype ) {
            $('.braapf_widget_color_pick').data('filtertype', filtertype);
            var data = 'action=berocket_aapf_color_listener&tax_color_name='+taxonomy_name+'&type='+specific+'&'+filtertype;
            if ( specific == 'color' || specific == 'image' ) {
                $.post(ajaxurl, data, function(data) {
                    $('.braapf_widget_color_pick').html(data);
                });
                return true;
            } else {
                $('.braapf_widget_color_pick').text("");
                return false;
            }
        }
        return ( specific == 'color' || specific == 'image' );
    }
    braapf_current_taxonomy_hierarchical = function () {
        var taxonoy_name = braapf_get_current_taxonomy_name();
        var hierarchical = false;
        if( taxonoy_name != false && $('#braapf_custom_taxonomy option[value="'+taxonoy_name+'"]').data('hierarchical') ) {
            hierarchical = true;
        }
        return hierarchical;
    }
    braapf_any_widget_selected = function () {
        berocket_show_element_hooked_data.push('.braapf_widget_type input[type=radio]');
        return ($('.braapf_widget_type input[type=radio]:checked').length > 0);
    }
    braapf_sort_styles = function(show, element, data_string, init) {
        berocket_show_element_callback(show, element, data_string, init);
        if( $('.braapf_widget_type input[type=radio]:checked').val() == 'filter' ) {
            sort_styles = true;
            var $element_single = $(element).first();
            var style_template  = $element_single.data('template');
            var style_specific  = $element_single.data('specific');
            
            var templates = $('#braapf_filter_type option:selected').data('templates');
            var position = $('#braapf_filter_type option:selected').data('positions');
            if( ! Array.isArray(position) || ! Array.isArray(templates) ) {
                position = 1000000;
            } else {
                if( templates.indexOf(style_template) != -1 ) {
                    var indexof_template = templates.indexOf(style_template);
                    if( typeof(position[indexof_template]) != 'undefined' ) {
                        position = position[indexof_template];
                    }
                }
            }
            var specific = $('#braapf_filter_type option:selected').data('specific');
            var spec_pos = $('#braapf_filter_type option:selected').data('spec_pos');
            if( ! Array.isArray(specific) || ! Array.isArray(spec_pos) ) {
                spec_pos = 9000;
            } else {
                if( specific.indexOf(style_specific) != -1 ) {
                    var indexof_specific = specific.indexOf(style_specific);
                    if( typeof(spec_pos[indexof_specific]) != 'undefined' ) {
                        spec_pos = spec_pos[indexof_specific];
                    }
                }
            }
            $('.braapf_template_'+style_template+'_'+style_specific).css('order', (parseInt(position) + parseInt(spec_pos)));
            $(element).each(function() {
                var style_pos = $(this).data('sort_pos');
                var sum_pos = parseInt(position) + parseInt(spec_pos) + parseInt(style_pos);
                $(this).css('order', sum_pos);
            });
        }
        braapf_checked_style_parent();
    }
    braapf_hide_child_attributes_select = function(show, element, data_string, init) {
        berocket_show_element_callback(show, element, data_string, init);
        if( braapf_current_template() == "select" ) {
            if( $('#braapf_hide_child_attributes').val() == '1' ) {
                $('#braapf_hide_child_attributes').val("2");
            }
            $('#braapf_hide_child_attributes').find('option[value="1"]').prop('disabled', true);
        } else {
            $('#braapf_hide_child_attributes').find('option[value="1"]').prop('disabled', false);
        }
    }
    braapf_price_symbol_before_price = function(show, element, data_string, init) {
        var changed = $('#braapf_text_before_price').data('price_changed');
        if( typeof(changed) != 'undefined' ) {
            if( show == '0' ) {
                if( changed == 'yes' && $('#braapf_text_before_price').val() == '%cur_symbol%' ) {
                    $('#braapf_text_before_price').val('');
                }
            } else {
                if( changed == 'no' && $('#braapf_text_before_price').val() == '' ) {
                    $('#braapf_text_before_price').val('%cur_symbol%');
                }
            }
        }
        if( show == '0' ) {
            $('#braapf_text_before_price').data('price_changed', 'no');
        } else {
            $('#braapf_text_before_price').data('price_changed', 'yes');
        }
    }
    braapf_price_range_changes = function(show, element, data_string, init) {
        $('.braapf_style > div').each(function() {
            if( show == '0' ) {
                if( typeof($(this).data('image')) != 'undefined' && $(this).data('image') ) {
                    $(this).find('img').attr('src', $(this).data('image'));
                }
                if( typeof($(this).data('name')) != 'undefined' && $(this).data('name') ) {
                    $(this).find('h3').html($(this).data('name'));
                }
            } else {
                if( typeof($(this).data('image_price')) != 'undefined' && $(this).data('image_price') ) {
                    $(this).find('img').attr('src', $(this).data('image_price'));
                }
                if( typeof($(this).data('name_price')) != 'undefined' && $(this).data('name_price') ) {
                    $(this).find('h3').html($(this).data('name_price'));
                }
            }
        });
    }
    braapf_disable_height_control = function() {
        return ['select', 'slider', 'new_slider', 'datepicker'];
    }
    braapf_filter_title_buttons_placeholder = function(show, element, data_string, init) {
        berocket_show_element_callback(show, element, data_string, init);
        if( show == '1') {
            $('#braapf_filter_title').attr('placeholder', $('#braapf_filter_title').data('buttons'));
        }
    }
    braapf_filter_title_filters_placeholder = function(show, element, data_string, init) {
        berocket_show_element_callback(show, element, data_string, init);
        if( show == '1') {
            $('#braapf_filter_title').attr('placeholder', $('#braapf_filter_title').data('filters'));
        }
    }
    /*EXPERIMENTAL STYLE*/
    braapf_checked_style_parent = function() {
        var parent_block = false;
        if( $('.braapf_style input[name="br_product_filter[style]"]:checked').length ) {
            parent_block = $('.braapf_style input[name="br_product_filter[style]"]:checked').closest('.braapf_style').parent();
        }
        if( $('.braapf_checked_style_parent').length ) {
            if( $('.braapf_checked_style_parent').find('.braapf_style').length && (parent_block === false || ! $('.braapf_checked_style_parent').is(parent_block)) ) {
                $('.braapf_checked_style_parent').find('.braapf_style').scrollLeft(0);
            }
            $('.braapf_checked_style_parent').removeClass('braapf_checked_style_parent');
        }
        if( $('.braapf_style input[name="br_product_filter[style]"]:checked').length ) {
            var parent_block = $('.braapf_style input[name="br_product_filter[style]"]:checked').closest('.braapf_style').parent();
            parent_block.addClass('braapf_checked_style_parent');
        }
    }
    $(document).ready(function() {
        braapf_sbs_numeric_set();
        $(document).on('berocket_show_element_callback', braapf_sbs_numeric_set);
        berocket_show_element('.brsbs_style', '!braapf_any_widget_selected! == true');
        $(document).trigger('brsbs_style');
        berocket_show_element('.brsbs_attribute_setup', '{.braapf_widget_type input[type=radio]} == "filter"');
        berocket_show_element('.braapf_filter_title_label', '({.braapf_widget_type input[type=radio]} == "filter" || {.braapf_widget_type input[type=radio]} == "selected_area" || {.braapf_widget_type input[type=radio]} == "search_field" || {.braapf_widget_type input[type=radio]} == "")', true, braapf_filter_title_filters_placeholder);
        berocket_show_element('.braapf_filter_title_button', '({.braapf_widget_type input[type=radio]} == "update_button" || {.braapf_widget_type input[type=radio]} == "reset_button")', true, braapf_filter_title_buttons_placeholder);
        berocket_show_element('.braapf_attribute', '{#braapf_filter_type} == "attribute"');
        berocket_show_element('.braapf_custom_taxonomy', '{#braapf_filter_type} == "custom_taxonomy"');
        berocket_show_element('.braapf_order_values_by, .braapf_order_values_type, .braapf_parent_product_cat', '{#braapf_filter_type} == !braapf_all_sameas_custom_taxonomy! || {#braapf_filter_type} == !braapf_all_sameas_attribute!');
        //REQUIRED
        berocket_show_element('.brsbs_required', '{.braapf_widget_type input[type=radio]} == "filter" && (({#braapf_filter_type} == "price" && (!braapf_current_template! == "select" || !braapf_current_template! == "checkbox")) || !braapf_current_specific! == "color" || !braapf_current_specific! == "image" || ( ({#braapf_filter_type} == !braapf_all_sameas_custom_taxonomy! || {#braapf_filter_type} == !braapf_all_sameas_attribute!) && !braapf_current_template! == "datepicker" ) )');
        $('.braapf_widget_color_pick').data('filtertype', jQuery('.braapf_filter_type_data *').serialize());
        berocket_show_element('.braapf_widget_color_pick', '!braapf_load_color_image_pick! == true');
        //ADDITIONAL
        berocket_show_element('.brsbs_additional', '!braapf_any_style_checked! == true');
        berocket_show_element('.braapf_selected_area_show', '{.braapf_widget_type input[type=radio]} == "selected_area"');
        //FOR ALL FILTERS
        berocket_show_element('.braapf_widget_collapse', '{.braapf_widget_type input[type=radio]} == "filter" || {.braapf_widget_type input[type=radio]} == "selected_area"');
        berocket_show_element('.braapf_widget_is_hide', '{#braapf_widget_collapse} != "" && ({.braapf_widget_type input[type=radio]} == "filter" || {.braapf_widget_type input[type=radio]} == "selected_area")');
        berocket_show_element('.braapf_description', '{.braapf_widget_type input[type=radio]} == "filter" || {.braapf_widget_type input[type=radio]} == "selected_area"');
        berocket_show_element('.braapf_height', '({.braapf_widget_type input[type=radio]} == "filter" && !braapf_current_template! != !braapf_disable_height_control!) || {.braapf_widget_type input[type=radio]} == "selected_area"');
        berocket_show_element('.braapf_scroll_theme', '{#braapf_height} != "" && (({.braapf_widget_type input[type=radio]} == "filter" && !braapf_current_template! != !braapf_disable_height_control!) || {.braapf_widget_type input[type=radio]} == "selected_area")');
        berocket_show_element('.braapf_icon_before_title, .braapf_icon_after_title', '{.braapf_widget_type input[type=radio]} == "filter" || {.braapf_widget_type input[type=radio]} == "selected_area"');
        berocket_show_element('.braapf_icon_before_value, .braapf_icon_after_value', '{.braapf_widget_type input[type=radio]} == "filter" && (!braapf_current_template! != "select" && ((!braapf_current_specific! != "color" && !braapf_current_specific! != "image") || {#braapf_use_value_with_color} != ""))');
        berocket_show_element('.braapf_enable_slider_inputs', '{.braapf_widget_type input[type=radio]} == "filter" && !braapf_current_template! == "slider"');
        //CHECKBOX
        berocket_show_element('.braapf_hide_child_attributes', '{.braapf_widget_type input[type=radio]} == "filter" && (!braapf_current_template! == "checkbox" || !braapf_current_template! == "select") && !braapf_current_taxonomy_hierarchical! == true', true, braapf_hide_child_attributes_select);
        berocket_show_element('.braapf_single_selection', '{.braapf_widget_type input[type=radio]} == "filter" && (!braapf_current_template! == "select" || !braapf_current_template! == "checkbox")');
        berocket_show_element('.braapf_operator', '{.braapf_widget_type input[type=radio]} == "filter" && (!braapf_current_template! == "select" || !braapf_current_template! == "checkbox") && {#braapf_single_selection} == false');
        berocket_show_element('.braapf_select_first_element_text', '{.braapf_widget_type input[type=radio]} == "filter" && !braapf_current_template! == "select" && ({#braapf_single_selection} == true || {.braapf_style input[type=radio]} == "select2")');
        berocket_show_element('.braapf_select_first_element_text_for_single', '{#braapf_single_selection} == true');
        berocket_show_element('.braapf_select_first_element_text_for_multiple', '{#braapf_single_selection} == false');
        berocket_show_element('.braapf_attribute_count, .braapf_attribute_count_show_hide', '{.braapf_widget_type input[type=radio]} == "filter" && !braapf_current_template! == "checkbox"');
        //COLOR/IMAGE
        berocket_show_element('.braapf_use_value_with_color, .braapf_color_image_block_size, .braapf_color_image_checked', '{.braapf_widget_type input[type=radio]} == "filter" && (!braapf_current_specific! == "color" || !braapf_current_specific! == "image")');
        berocket_show_element('.braapf_color_image_block_size_custom', '{#braapf_color_image_block_size} == "hxpx_wxpx"');
        berocket_show_element('.braapf_color_image_checked_custom_css', '{.braapf_widget_type input[type=radio]} == "filter" && (!braapf_current_specific! == "color" || !braapf_current_specific! == "image") && {#braapf_color_image_checked} == "brchecked_custom"');
        //PRICE ATTRIBUTE
        berocket_show_element('.braapf_price_values', '{.braapf_widget_type input[type=radio]} == "filter" && {#braapf_filter_type} == "price" && (!braapf_current_template! == "slider" || !braapf_current_template! == "new_slider")');
        berocket_show_element('.braapf_min_price, .braapf_max_price', '{.braapf_widget_type input[type=radio]} == "filter" && {#braapf_filter_type} == "price" && {#braapf_price_values} == "" && (!braapf_current_template! == "slider" || !braapf_current_template! == "new_slider")');
        berocket_show_element('.braapf_text_before_price, .braapf_text_after_price', '{.braapf_widget_type input[type=radio]} == "filter" && (!braapf_current_template! == "slider" || !braapf_current_template! == "new_slider")');
        berocket_show_element('.braapf_number_style', '{.braapf_widget_type input[type=radio]} == "filter" && {#braapf_filter_type} == "price"');
        berocket_show_element('.braapf_number_style_elements', '{.braapf_widget_type input[type=radio]} == "filter" && {#braapf_filter_type} == "price" && {#braapf_number_style} == true');
        berocket_show_element('#braapf_text_after_price_info, #braapf_text_before_price_info', '{.braapf_widget_type input[type=radio]} == "filter" && {#braapf_filter_type} == "price" && (!braapf_current_template! == "slider" || !braapf_current_template! == "new_slider")');
        //RESET BUTTON
        berocket_show_element('.braapf_reset_hide', '{.braapf_widget_type input[type=radio]} == "reset_button"');
        //SAVE FILTERS
        berocket_show_element('.brsbs_save', '!braapf_any_style_checked! == true');
        //EXECUTE SOME SCRIPT
        berocket_show_element('.brsbs_style', '{.braapf_widget_type input[type=radio]} == "filter" && {#braapf_filter_type} == "price"', true, braapf_price_range_changes);
        berocket_show_element('.brsbs_style', '{.braapf_widget_type input[type=radio]} == "filter" && {#braapf_filter_type} == "price" && (!braapf_current_template! == "slider" || !braapf_current_template! == "new_slider")', true, braapf_price_symbol_before_price);
        /*EXPERIMENTAL STYLE*/
        $(document).on('change', '.braapf_style input[name="br_product_filter[style]"]', function() {
            if( ! $('#braapf_single_selection').data('was_select') && braapf_current_template() == 'select') {
                $('#braapf_single_selection').prop('checked', true).trigger('change');
            } else if( $('#braapf_single_selection').data('was_select') && braapf_current_template() != 'select' ) {
                $('#braapf_single_selection').prop('checked', false).trigger('change');
            }
            $('#braapf_single_selection').data('was_select', braapf_current_template() == 'select');
        });
        $('#braapf_single_selection').data('was_select', braapf_current_template() == 'select');

        $('#post').on('submit', function(event) {
            var copy_val = $('[name="berocket_copy_from_custom_post"]').val();
            if( ! copy_val ) {
                if($('.braapf_widget_type input[type=radio]:enabled:checked').length == 0) {
                    $(document).trigger('braapf_error_select_widget_type');
                    event.preventDefault();
                } else if($('.braapf_style input[name="br_product_filter[style]"]:enabled:checked').length == 0) {
                    $(document).trigger('braapf_error_select_style');
                    event.preventDefault();
                }
            }
        });
        $(window).on('scroll', function() {
            if( $('#submitdiv').length ) {
                var scrollTop = $(window).scrollTop();
                var scrollBottom = scrollTop + $(window).height();
                var submitTop = $('#submitdiv').offset().top;
                var submitBottom = submitTop + $('#submitdiv').height();
                if( scrollTop > submitTop || scrollBottom < submitBottom ) {
                    if( !$('.braapf_fixed_submit').length ) {
                        $('#submitdiv input[type=submit]').addClass('braapf_fixed_submit').appendTo('#post');
                    }
                } else {
                    if( $('.braapf_fixed_submit').length ) {
                        $('input[type=submit].braapf_fixed_submit').removeClass('braapf_fixed_submit').appendTo('#submitdiv #publishing-action');
                    }
                }
            }
        });
        /*EXPERIMENTAL STYLE*/
        function bapf_scroll_to_needed_styles() {
            if( $(this).closest('.braapf_checked_style_parent').length == 0 ) {
                braapf_checked_style_parent();
                var top = $('.braapf_templates_list').offset().top - 100;
                $('html, body').stop().animate({scrollTop:top}, 500, 'swing');
            }
        }
        $(document).on('change', '.braapf_style input[name="br_product_filter[style]"]', bapf_scroll_to_needed_styles);
        $(document).on('change', '.brsbs_widget_type input[name="br_product_filter[widget_type]"]', function() {
            $(document).off('change', '.braapf_style input[name="br_product_filter[style]"]', bapf_scroll_to_needed_styles);
            $('.braapf_style input[name="br_product_filter[style]"]').first().trigger('change');
            $(document).on('change', '.braapf_style input[name="br_product_filter[style]"]', bapf_scroll_to_needed_styles);
        });
        braapf_checked_style_parent();
        setTimeout(function() {
            if( jQuery('.braapf_style > div input:checked').length ) {
                var scroll_from_left = jQuery('.braapf_style > div input:checked + label').position().left + 
                jQuery('.braapf_style > div input:checked + label').width()/3 + 
                jQuery('.braapf_style > div input:checked + label').closest('.braapf_style').scrollLeft() - 
                jQuery('.braapf_style > div input:checked + label').closest('.braapf_style').width()/2;
                jQuery('.braapf_style > div input:checked + label').closest('.braapf_style').stop().animate({scrollLeft:scroll_from_left}, 200, 'swing');
            }
        }, 550);
        $('#braapf_filter_title').data('title', $('#title').val());
        $('#title').on('keyup change', function() {
            if( $('#braapf_filter_title').data('title') == $('#braapf_filter_title').val() ) {
                $('#braapf_filter_title').val($('#title').val());
            }
            $('#braapf_filter_title').data('title', $('#title').val());
        });
        $('.brsbs_save input[type=submit]').click(function(){$(window).off("beforeunload.edit-post")});
    });
})(jQuery);
