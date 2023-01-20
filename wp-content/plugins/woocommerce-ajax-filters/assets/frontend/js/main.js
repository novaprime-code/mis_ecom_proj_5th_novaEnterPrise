//Filter functions
var berocket_filters = {};
function berocket_make_args_array(args) {
    var args_array = [];
    for(var i = 0; i < args.length; i++) {
        args_array.push(args[i]);
    }
    return args_array;
}
function berocket_apply_filters(filter_name, filter_element) {
    if( typeof(berocket_filters[filter_name]) !== 'undefined' ) {
        var array_args = berocket_make_args_array(arguments);
        array_args.splice(0, 1);
        jQuery.each(berocket_filters[filter_name], function(i, callback_data) {
            filter_element = callback_data.callback.apply(null, array_args);
            array_args[0] = filter_element;
        });
    }
    return filter_element;
}
function berocket_do_action(filter_name) {
    if( typeof(berocket_filters[filter_name]) !== 'undefined' ) {
        var array_args = berocket_make_args_array(arguments);
        array_args.splice(0, 1);
        jQuery.each(berocket_filters[filter_name], function(i, callback_data) {
            callback_data.callback.apply(null, array_args);
        });
    }
}
function berocket_throw_error(error_name) {
    var filter_name = 'berocket_throw_error';
    error_exist = true;
    if( typeof(berocket_filters[filter_name]) !== 'undefined' ) {
        var array_args = [error_exist];
        for(var i = 0; i < arguments.length; i++) {
            array_args.push(arguments[i]);
        }
        jQuery.each(berocket_filters[filter_name], function(i, callback_data) {
            error_exist = callback_data.callback.apply(null, array_args);
            array_args[0] = error_exist;
            if( ! error_exist ) {
                return false;
            }
        });
    }
    return error_exist;
}
function berocket_add_filter(filter_name, callback, priority) {
    if( typeof(priority) == 'undefined' ) {
        priority = 10;
    }
    if( typeof(berocket_filters[filter_name]) === 'undefined' ) {
        berocket_filters[filter_name] = [];
    }
    var isExist = false;
    jQuery.each(berocket_filters[filter_name], function(i, callback_data) {
        if( callback_data.priority === priority && callback_data.callback === callback ) {
            isExist = true;
        }
    });
    if( ! isExist ) {
        berocket_filters[filter_name].push({callback:callback, priority:priority});
        berocket_filters[filter_name] = berocket_sort_by_priority(berocket_filters[filter_name]);
    }
}
function berocket_remove_filter(filter_name, callback, priority) {
    if( typeof(priority) == 'undefined' ) {
        priority = 10;
    }
    if( typeof(berocket_filters[filter_name]) != 'undefined' ) {
        var new_array = [];
        jQuery.each(berocket_filters[filter_name], function(i, callback_data) {
            if( callback_data.priority !== priority || callback_data.callback !== callback ) {
                new_array.push(callback_data);
            }
        });
        berocket_filters[filter_name] = berocket_sort_by_priority(new_array);
    }
}
function berocket_sort_by_priority(sorting_array) {
    if( Array.isArray(sorting_array) ) {
        sorting_array.sort(function(a, b) {
            if( a.priority > b.priority ) {
                return 1;
            } else if( a.priority < b.priority ) {
                return -1;
            }
            return 0;
        });
    }
    return sorting_array;
}
berocket_add_filter('compat_filters_result_single', function(val) {
    if( typeof( val ) == 'object' && typeof(val.taxonomy) == 'string' && val.taxonomy.substr(0, 3) == 'pa_' ) {
        val.taxonomy = val.taxonomy.substr(3);
    }
    return val;
});


//NEW TEMPLATE 

function berocket_format_number (number, number_style) {
    if( typeof number_style == 'undefined' ) {
        number_style = the_ajax_script.number_style;
    }
    var num = number.toFixed(number_style[2]);
    num = num.toString();
    var decimal = num.split('.');
    var new_number = decimal[0];
    if(num.indexOf('.') != -1)
    {
        decimal = decimal[1];
    }
    new_number = new_number.replace(/\d(?=(?:\d{3})+(?:$))/g, function($0, i){
        return $0+number_style[0];
    });
    if(num.indexOf('.') != -1)
    {
        new_number = new_number+number_style[1]+decimal;
    }
    return new_number;
}
jQuery(document).trigger('berocket_hooks_ready');
var braapf_filter_blocked = false,
//object{taxonomy:"", values:[value:"",html:"name/colorBlock/imgBlock/text"], glue:""}
braapf_selected_filters = [],
braapf_filtered_filters = [],
braapf_filters_var,
braapf_ajax_request;
//FUNCTIONS
var braapf_checkbox_same,
braapf_update_products,
braapf_grab_all,
braapf_grab_single,
braapf_compact_filters,
braapf_compat_filters_to_string,
braapf_build_url_from_urldata,
braapf_apply_additional_filter_data,
braapf_glue_by_operator,
braapf_ajax_load_from_url,
braapf_ajax_load_from_url_request,
braapf_init_load,
braapf_remove_pages_from_url_data,
braapf_filter_products_by_url,
braapf_get_current_url_data,
braapf_reset_buttons_hide,
bapf_universal_theme_compatibility,
braapf_disable_ajax_loading,
braapf_close_tippy,
braapf_context_is_update;
function braapf_grab_all_init() {braapf_grab_all();}
function braapf_selected_filters_area_set_init() {braapf_selected_filters_area_set();}
function braapf_filtered_filters_set() {
    braapf_filtered_filters = braapf_selected_filters.slice();
    berocket_do_action('braapf_init_filtered_set', braapf_filtered_filters);
}
(function ($){
    //Main part
    //Checkbox change script
    $(document).on("change", ".bapf_sfilter.bapf_ckbox input[type=checkbox]", function(){
        var value = $(this).val();
        var taxonomy = $(this).parents('.bapf_sfilter').data('taxonomy');
        var checked = $(this).prop('checked');
        braapf_checkbox_same(taxonomy, value, checked);
        berocket_apply_filters('input_ckbox_changed', this, taxonomy, value, checked);
        var filter_changed_element = {
            element:'#'+$(this).attr('id'),
            parent: 1,
            find: false
        };
        berocket_apply_filters('filter_changed_element', filter_changed_element, $(this));
        berocket_do_action('update_products', 'filter', $(this));
    });
    braapf_checkbox_same = function (taxonomy, value, checked) {
        $('.bapf_sfilter[data-taxonomy="'+taxonomy+'"] input[value="'+value+'"]').prop('checked', checked);
        if( $('.bapf_sfilter[data-taxonomy="'+taxonomy+'"]').length > $('.bapf_sfilter.bapf_ckbox[data-taxonomy="'+taxonomy+'"]').length ) {
            berocket_throw_error('multiple_filters_for_same_taxonomy', taxonomy);
        }
    }
    $(document).on('braapf_unselect', '.bapf_ckbox', function(event, data) {
        if( typeof(data) == 'undefined' ) {
            data = false;
        }
        if( data == false ) {
            var $this = $(this).find('input[type=checkbox]');
        } else {
            var $this = $(this).find('input[value="'+data.value+'"]');
        }
        if( $this.length > 0 ) {
            var value = $this.val();
            var taxonomy = $(this).data('taxonomy');
            $this.prop('checked', false);
            braapf_checkbox_same(taxonomy, value, false);
        }
    });
    $(document).on('braapf_unselect_all', '.bapf_ckbox', function(event, data) {
        $(this).trigger('braapf_unselect', false);
    });
    $(document).on('berocket_filters_document_ready', function() {
        $('.bapf_ckbox input[type=checkbox]:checked').closest('li').parents('li').trigger('bapf_ochild');
    });
    //update/reset butons
    $(document).on('click', '.berocket_aapf_widget_update_button, .bapf_update', function(event) {
        event.preventDefault();
        berocket_do_action('update_products', 'update', $(this));
    });
    $(document).on('click', '.berocket_aapf_reset_button, .bapf_reset', function(event) {
        event.preventDefault();
        braapf_unselect_all();
        berocket_do_action('update_products', 'reset_all', $(this));
    });
    braapf_reset_buttons_hide = function() {
        if( berocket_apply_filters('bapf_rst_nofltr_hide', (typeof(braapf_filtered_filters) == 'undefined' || braapf_filtered_filters.length <= 0)) ) {
            $('.bapf_rst_nofltr').hide();
        } else {
            $('.bapf_rst_nofltr').show();
        }
        var selected = false;
        if( typeof(braapf_filtered_filters) != 'undefined' && braapf_filtered_filters.length > 0 ) {
            $.each(braapf_filtered_filters, function(i, taxonomy) {
                if( taxonomy.values.length > 0 ) {
                    selected = true;
                    return false;
                }
            });
        }
        if( berocket_apply_filters('bapf_rst_sel_show', selected) ) {
            $('.bapf_rst_sel').show();
        } else {
            $('.bapf_rst_sel').hide();
        }
    }
    berocket_add_filter('braapf_init', braapf_reset_buttons_hide, 1500);
    berocket_add_filter('braapf_init_filtered_set', braapf_reset_buttons_hide, 1500);
    //Pagination page
    jQuery(document).ready( function() {
        var pagination_links = the_ajax_script.pagination_class;
        pagination_links = pagination_links.replace(',', ' a,');
        pagination_links = berocket_apply_filters('pagination_links_a_tags', pagination_links+' a', the_ajax_script.pagination_class);
        if( !the_ajax_script.disable_ajax_loading && the_ajax_script.pagination_ajax ) {
            $(document).on('click', pagination_links, function(event) {
                event.preventDefault();
                var href = $(this).attr('href');
                href = berocket_apply_filters('pagination_href_from_clicked_a', decodeURI(href), $(this));
                braapf_change_url_history_api(href, {replace:the_ajax_script.seo_friendly_urls});
                berocket_add_filter('ajax_load_from_url_beforeSend', braapf_pagination_prevent_filters_load);
                braapf_ajax_load_from_url(href, {}, berocket_apply_filters('ajax_load_from_pagination', {done:[braapf_replace_products, braapf_replace_pagination, braapf_replace_result_count, braapf_init_load, braapf_update_data_from_current]}, href));
            });
        }
    });
    function braapf_pagination_prevent_filters_load(xhr) {
        berocket_remove_filter('ajax_load_from_url_beforeSend', braapf_pagination_prevent_filters_load);
        xhr.setRequestHeader('X-Braapfdisable', '1');
        return xhr;
    }
    //Order By override
    jQuery(document).ready( function() {
        if( !the_ajax_script.disable_ajax_loading && the_ajax_script.control_sorting ) {
            $(document).on('submit', the_ajax_script.ordering_class, function(event) {
                event.preventDefault();
                var current_url_data = braapf_get_current_url_data();
                current_url_data = braapf_remove_pages_from_url_data(current_url_data);
                if( Array.isArray(current_url_data.queryargs) ) {
                    var newqueryargs = [];
                    $.each(current_url_data.queryargs, function(i, val) {
                        if(val.name != 'orderby') {
                            newqueryargs.push(val);
                        }
                    });
                    current_url_data.queryargs = newqueryargs;
                } else {
                    current_url_data.queryargs = [];
                }
                var form_data = $(this).serializeArray();
                if( Array.isArray(form_data) ) {
                    $.each(form_data, function(i, val) {
                        if(val.name == 'paged') {
                            current_url_data.page = parseInt(val.value);
                        } else if( val.name == 'orderby' ) {
                            if( the_ajax_script.default_sorting != val.value ) {
                                current_url_data.queryargs.push(val);
                            }
                        } else {
                            current_url_data.queryargs.push(val);
                        }
                    });
                }
                var url_filtered = braapf_build_url_from_urldata(current_url_data);
                braapf_filter_products_by_url(url_filtered);
            });
        }
    });
    
    //default update products
    braapf_get_url_with_filters_selected = function() {
        braapf_grab_all();
        var compat_filters = braapf_compact_filters();
        var filter_mask = berocket_apply_filters('braapf_filters_mask', the_ajax_script.url_mask);
        var filter_string = braapf_compat_filters_to_string(compat_filters, filter_mask, the_ajax_script.url_split);
        var current_url_data = braapf_get_current_url_data();
        current_url_data.filter = filter_string;
        current_url_data = braapf_remove_pages_from_url_data(current_url_data);
        current_url_data = braapf_apply_additional_filter_data(current_url_data);
        var url_filtered = braapf_build_url_from_urldata(current_url_data);
        return url_filtered;
    }
    braapf_update_products = function (context, element) {
        if( typeof(context) == 'undefined' ) {
            context = 'filter';
        }
        if( typeof(element) == 'undefined' ) {
            element = false;
        }
        context = berocket_apply_filters('before_update_products_context', context, element);
        var url_filtered = berocket_apply_filters('before_update_products_context_url_filtered', braapf_get_url_with_filters_selected(), context, element);
        if( berocket_apply_filters('apply_filters_to_page', ($('.berocket_aapf_widget_update_button:visible, .bapf_update:visible').length == 0 || braapf_context_is_update(context)), context, element, url_filtered) ) {
            braapf_selected_filters_area_set();
            braapf_filter_products_by_url(url_filtered);
        } else if( berocket_apply_filters('apply_filters_to_page_partial', false, context, element, url_filtered) ) {
            braapf_ajax_load_from_url(berocket_apply_filters('before_update_products_context_url_filtered_partial', url_filtered, context, element), {}, berocket_apply_filters('ajax_load_from_filters_partial', {done:[braapf_replace_each_filter, braapf_init_load]}, url_filtered, 'partial'), 'partial');
        }
    }
    
    braapf_context_is_update = function(context) {
        return berocket_apply_filters('context_is_update', (context == 'update' || context == 'reset_all' || context == 'reset_single'), context);
    }
    braapf_filter_products_by_url = function(url) {
        if( berocket_apply_filters('page_has_products_holder', (! $(the_ajax_script.products_holder_id).length), url) ) {
            location.href = url;
        } else {
            braapf_change_url_history_api(url, {replace:the_ajax_script.seo_friendly_urls});
            braapf_ajax_load_from_url(url, {}, berocket_apply_filters('ajax_load_from_filters', {done:[braapf_replace_products, braapf_replace_pagination, braapf_replace_result_count, braapf_replace_orderby, braapf_replace_each_filter, braapf_init_load, braapf_filtered_filters_set, braapf_update_url_history_api_from_current]}, url, 'default'));
        }
    }
    braapf_update_data_from_current = function() {
        braapf_grab_all();
        var compat_filters = braapf_compact_filters();
    }
    var braapf_update_url_history_api_from_current_action = false;
    braapf_update_url_history_api_from_current = function(data, textStatus, jqXHR, url, type) {
        if( the_ajax_script.seo_friendly_urls ) {
            url_filtered = braapf_get_url_with_filters_selected();
            history.replaceState(history.state, "BeRocket Rules", url_filtered);
            if( url != url_filtered && the_ajax_script.reload_changed_filters ) {
                setTimeout(function() {
                    braapf_filter_products_by_url(url_filtered);
                }, 5);
            }
        }
    }
    //Grab filters from page
    braapf_grab_all = function ($parent) {
        if( typeof($parent) == 'undefined' ) { $parent = false; }
        var selected_filters = berocket_apply_filters('before_grab_all_filters', [], $parent);
        if( $parent === false ) {
            var all_filters = $('.bapf_sfilter');
        } else {
            var all_filters = $($parent).find('.bapf_sfilter');
        }
        all_filters.each(function() {
            var single_data = braapf_grab_single(this, selected_filters);
            if( single_data !== false ) {
                selected_filters.push(single_data);
            }
        });
        selected_filters = berocket_apply_filters('grab_all_filters', selected_filters, $parent);
        if( $parent === false ) {
            braapf_selected_filters = selected_filters;
        }
        return selected_filters;
    }
    braapf_grab_single = function(element, selected_filters, grab_single) {
        if( typeof(grab_single) == 'undefined' ) {
            grab_single = false;
        }
        element = $(element);
        var single_data = false;
        var exist = false;
        var taxonomy = element.data('taxonomy');
        if( typeof(taxonomy) == 'undefined' || ! taxonomy ) {
            return berocket_apply_filters('grab_single_filter_taxonomy_undefined', single_data, element, selected_filters);
        }
        $.each(selected_filters, function(i, val) {
            if(val.taxonomy == taxonomy ) {
                exist = true;
            }
        });
        if( ! berocket_apply_filters('grab_single_filter_exist', exist, element, selected_filters) ) {
            single_data = berocket_apply_filters('grab_single_filter', single_data, element, selected_filters);
            if( single_data !== false ) return single_data;
            var operator = element.data('op');
            var tax_name = element.data('name');
            if( typeof(tax_name) == 'undefined' ) {
                tax_name = '';
            }
            var glue = braapf_glue_by_operator(operator);
            var values = [];
            var values_find = [];
            if( grab_single ) {
                var $elements = element;
            } else {
                var $elements = $('.bapf_sfilter[data-taxonomy="'+taxonomy+'"]');
            }
            $elements.find('input:checked:not(:disabled)').each(function(i, val) {
                if( values_find.indexOf($(this).val()) == -1 ) {
                    values.push({value:$(this).val(), html:$(this).data('name')});
                    values_find.push($(this).val());
                }
            });
            single_data = {name:tax_name, taxonomy:taxonomy, values:values, glue:glue, operator:operator};
        }
        return berocket_apply_filters('grab_single_filter_default', single_data, element, selected_filters);
    }
    braapf_glue_by_operator = function(operator) {
        if( typeof(operator) != 'string' ) {
            operator = '';
        }
        var glue = '-';
        if( operator.toLowerCase() == 'and' ) {
            glue = '+';
        }
        return berocket_apply_filters('glue_by_operator', glue, operator);
    }
    //compact filters to {taxonomy,values} object
    braapf_compact_filters = function(filters_start) {
        if( typeof(filters_start) == 'undefined' ) {
            filters_start = braapf_selected_filters;
        }
        var filters = [];
        $.each(filters_start, function(i, val) {
            var values_str = '';
            if( typeof(val.customValuesLine) != 'undefined' ) {
                values_str = val.customValuesLine;
            } else {
                $.each(val.values, function(i2, val2) {
                    if( values_str.length ) {
                        values_str += val.glue;
                    }
                    values_str += val2.value;
                });
            }
            if( values_str.length ) {
                filters.push(berocket_apply_filters('compat_filters_result_single', {taxonomy:val.taxonomy, values:values_str}, val));
            }
        });
        return berocket_apply_filters('compat_filters_result', filters, filters_start);
    }
    //Compact all filters to single string
    braapf_compat_filters_to_string = function (compat_filters, filter_mask, glue_between_taxonomy) {
        var filters_string = '';
        $.each(compat_filters, function(i, val) {
            if( filters_string.length ) {
                filters_string += glue_between_taxonomy;
            }
            var single_string = filter_mask;
            single_string = single_string.replace('%t%', val.taxonomy);
            single_string = single_string.replace('%v%', val.values);
            filters_string += berocket_apply_filters('compat_filters_to_string_single', single_string, val, compat_filters, filter_mask, glue_between_taxonomy);
        });
        return berocket_apply_filters('compat_filters_to_string', filters_string, compat_filters, filter_mask, glue_between_taxonomy);
    }
    //get object with information about currentUrl/sendedUrl
    braapf_get_current_url_data = function(url) {
        if( typeof(url) == 'undefined' ) {
            var link = location.href.split('#')[0].split('?')[0],
                query = location.search.substring(1);
        } else {
            var link = url.split('#')[0].split('?')[0];
            if( url.split('#')[0].split('?').length > 1 ) {
                var query = url.split('#')[0].split('?')[1];
            } else {
                var query = '';
            }
        }
        var query_arr = [],
            page = 1,
            search_page = link.match(/\/page\/(\d+)/);
        if( search_page && typeof(search_page[1]) == 'string' ) {
            page = parseInt(search_page[1]);
            link = link.replace(/\/page\/(\d+)/, '');
        }
        query = query.split('&');
        $.each(query, function(i, val) {
            if( val.length ) {
                query[i] = val.split('=');
                if( query[i][0] == 'paged' ) {
                    page = parseInt(query[i][1]);
                } else {
                    query_arr.push({name:query[i][0], value: query[i][1]});
                }
            }
        });
        return berocket_apply_filters('get_current_url_data', {baselink:link, queryargs:query_arr, filter:"", page:page});
    }
    braapf_remove_pages_from_url_data = function(url_data) {
        url_data.page = 1;
        if( Array.isArray(url_data.queryargs) ) {
            var newqueryargs = [];
            $.each(url_data.queryargs, function(i, val) {
                if(val.name != 'product-page') {
                    newqueryargs.push(val);
                }
            });
            url_data.queryargs = newqueryargs;
        }
        url_data = berocket_apply_filters('braapf_remove_pages_from_url_data', url_data);
        return url_data;
    }
    braapf_apply_additional_filter_data = function (url_data, filters_start) {
        if( typeof(filters_start) == 'undefined' ) {
            filters_start = braapf_selected_filters;
        }
        $.each(filters_start, function(i, val) {
            url_data = berocket_apply_filters('apply_additional_filter_data', url_data, val);
        });
        return url_data;
    }
    //Build url from url data object
    braapf_build_url_from_urldata = function (url_data, parameters) {
        if( typeof(parameters) == 'undefined' ) {
            parameters = braapf_build_url_parameters_default();
        }
        var url = berocket_apply_filters('url_from_urldata_baselink', url_data.baselink, url_data, parameters);
        var query_get = '';
        if( url_data.queryargs.length ) {
            $.each(url_data.queryargs, function(i, val) {
                if( query_get.length ) {
                    query_get += '&';
                }
                if( typeof(val.name) != 'undefined' && val.name.length && typeof(val.value) != 'undefined' ) {
                    query_get += val.name + '=' + val.value;
                }
            });
        }
        if( url_data.page && url_data.page > 1 ) {
            if( query_get.length ) {
                query_get += '&';
            }
            query_get += 'paged=' + url_data.page;
        }
        if( query_get.length ) {
            query_get = '?' + query_get;
        }
        url = berocket_apply_filters('url_from_urldata_linkget', (url+query_get), url_data, parameters, url, query_get);
        return url;
    }
    braapf_build_url_parameters_default = function () {
        return berocket_apply_filters('build_url_parameters_default', {});
    }
    //REPLACE ANY ELEMENT ON PAGE
    braapf_replace_current_with_new = function(html, selector, argsin) {
        if( typeof(argsin) == 'undefined' ) {
            argsin = {};
        }
        args = {replace:false};
        jQuery.extend(args, argsin);
        args = berocket_apply_filters('replace_current_with_new_args', args, html, selector);
        var $html = $('<div><div>'+html+'</div></div>');
        var $new = $html.find(selector);
        var $current = $(selector);
        if( $current.length != 0 ) {
            if( $new.length != $current.length && $new.length != 0 ) {
                berocket_throw_error('error_notsame_block_qty', selector, $new.length, $current.length);
            }
            if( $new.length == 0 ) {
                $current.html('').addClass('braapfNotReplaced');
                $current.hide();
            } else {
                $current.each(function(i, el) {
                    if( typeof($new[i]) != 'undefined' ) {
                        if( args.replace ) {
                            $(el).replaceWith($($new[i]));
                        } else {
                            $(el).html($($new[i]).html()).removeClass('braapfNotReplaced');
                        }
                    } else {
                        $(el).html('').addClass('braapfNotReplaced');
                    }
                });
                $current.show();
            }
        }
    }
    braapf_replace_products = function (data) {
        var products_selector = the_ajax_script.products_holder_id;
        var $html = $('<div><div>'+data+'</div></div>');
        $('.bapf_no_products').remove();
        braapf_replace_current_with_new(data, products_selector);
        if( $('.braapfNotReplaced').filter(products_selector).length ) {
            $('.braapfNotReplaced').filter(products_selector).before($(the_ajax_script.no_products));
        }
    }
    braapf_replace_pagination = function (data) {
        var pagination_selector = the_ajax_script.pagination_class;
        if( $(pagination_selector).length == 0 ) {
            var products_selector = the_ajax_script.products_holder_id;
            var $html = $('<div><div>'+data+'</div></div>');
            var pagination = $html.find(pagination_selector).last();
            if( pagination.length ) {
                $(products_selector).last().after(pagination);
            }
        } else {
            braapf_replace_current_with_new(data, pagination_selector, {type:'pagination'});
        }
    }
    braapf_replace_result_count = function (data) {
        var result_count_selector = the_ajax_script.result_count_class;
        if( $(result_count_selector).length == 0 ) {
            var products_selector = the_ajax_script.products_holder_id;
            var $html = $('<div><div>'+data+'</div></div>');
            var count_selector = $html.find(result_count_selector).last();
            if( count_selector.length ) {
                $(products_selector).last().before(count_selector);
            }
        } else {
            braapf_replace_current_with_new(data, result_count_selector);
        }
    }
    braapf_replace_orderby = function (data) {
        var orderby_selector = the_ajax_script.ordering_class;
        if( $(orderby_selector).length == 0 ) {
            var products_selector = the_ajax_script.products_holder_id;
            var $html = $('<div><div>'+data+'</div></div>');
            var orderby = $html.find(orderby_selector).last();
            if( orderby.length ) {
                $(products_selector).last().before(orderby);
            }
        } else {
            braapf_replace_current_with_new(data, orderby_selector);
        }
    }
    braapf_replace_each_filter = function(html) {
        var $html = $('<div><div>'+html+'</div></div>');
        $html = braapf_sngl_hd_loaded_filters($html);
        $('.berocket_single_filter_widget').each(function() {
            var data_id = $(this).data('id');
            $('.berocket_single_filter_widget_'+data_id).html($html.find('.berocket_single_filter_widget_'+data_id).last().html());
            if( $html.find('.berocket_single_filter_widget_'+data_id).last().is('.bapf_mt_none') ) {
                $('.berocket_single_filter_widget_'+data_id).addClass('bapf_mt_none');
            } else {
                $('.berocket_single_filter_widget_'+data_id).removeClass('bapf_mt_none');
            }
        });
    }
    //Add url HTML5
    braapf_change_url_history_api = function(new_url, data) {
        if( typeof(data) != 'undefined' && data.replace ) {
            var stateParameters = { BeRocket: "Rules" };
            history.replaceState(stateParameters, "");
            history.pushState(stateParameters, "", new_url);
            history.pathname = new_url;
        }
    }
    if( berocket_apply_filters('load_products_ajax_on_popstate', true) ) {
        window.addEventListener("popstate", function(event) {
            if ( event.state != null && event.state.BeRocket == 'Rules' ) {
                var url = location.href;
                if( berocket_apply_filters('page_has_products_holder', (! $(the_ajax_script.products_holder_id).length), url) ) {
                    location.href = url;
                } else {
                    braapf_ajax_load_from_url(url, {}, berocket_apply_filters('ajax_load_from_filters', {done:[braapf_replace_products, braapf_replace_pagination, braapf_replace_result_count, braapf_replace_orderby, braapf_replace_each_filter, braapf_init_load, braapf_filtered_filters_set, braapf_update_data_from_current]}, url, 'default'));
                }
            }
        });
    }
    //Load data from URL
    braapf_ajax_load_from_url = function(url, send_data, callback_func, type) {
        if( typeof(type) == 'undefined' ) {
            type = 'default';
        }
        if( typeof(send_data) != 'object' ) {
            send_data = {};
        }
        if( typeof(callback_func) != 'object' ) {
            callback_func = {done:[], fail:[], always:[]};
        }
        if( typeof(callback_func.done) == 'undefined' || ! Array.isArray(callback_func.done) ) {
            callback_func.done = [];
        }
        if( typeof(callback_func.fail) == 'undefined' || ! Array.isArray(callback_func.fail) ) {
            callback_func.fail = [];
        }
        if( typeof(callback_func.always) == 'undefined' || ! Array.isArray(callback_func.always) ) {
            callback_func.always = [];
        }
        url = berocket_apply_filters('ajax_load_from_url_url', url, send_data, callback_func, type);
        send_data = berocket_apply_filters('ajax_load_from_url_data', send_data, url, callback_func, type);
        callback_func = berocket_apply_filters('ajax_load_from_url_callback', callback_func, url, send_data, type);
        braapf_ajax_load_from_url_request(url, send_data, callback_func, type);
    }
    braapf_ajax_load_from_url_request = function(url, send_data, callback_func, type) {
        $(document).trigger('berocket_ajax_filtering_start');
        if( typeof(braapf_ajax_request) == 'object' && typeof(braapf_ajax_request.abort) != 'undefined' ) {
            braapf_ajax_request.abort();
        }
        braapf_ajax_request = $.ajax({method:"GET", url: url, data: send_data, beforeSend: function(xhr) {
                xhr = berocket_apply_filters('ajax_load_from_url_beforeSend', xhr, url, send_data, callback_func, type);
            }
        })
            .done(function(data, textStatus, jqXHR) {
                $(document).trigger('berocket_ajax_filtering_on_update');
                data = berocket_apply_filters('ajax_load_from_url_done', data, url, send_data, callback_func, type);
                $.each(callback_func.done, function(i, val) {
                    val(data, textStatus, jqXHR, url, type);
                });
                data = berocket_apply_filters('ajax_load_from_url_done_after', data, url, send_data, callback_func, type);
                $(document).trigger('berocket_ajax_products_loaded');
            })
            .fail(function(jqXHR, textStatus, errorThrown) {
                if( textStatus == 'abort' ) {
                    return false;
                }
                $(document).trigger('berocket_ajax_products_not_loaded');
                jqXHR = berocket_apply_filters('ajax_load_from_url_fail', jqXHR, url, send_data, callback_func, type);
                $.each(callback_func.fail, function(i, val) {
                    val(jqXHR, textStatus, errorThrown, url, type);
                });
                jqXHR = berocket_apply_filters('ajax_load_from_url_fail_after', jqXHR, url, send_data, callback_func, type);
                if( type == 'default' ) {
                    var query_send_data = jQuery.param(send_data);
                    if( query_send_data ) {
                        if( url.split('?').length > 1 ) {
                            url = url + "&" + query_send_data;
                        } else {
                            url = url + "?" + query_send_data;
                        }
                    }
                    location.href = url;
                }
            })
            .always(function(data, textStatus, jqXHR) {
                data = berocket_apply_filters('ajax_load_from_url_always', data, url, send_data, callback_func, type);
                $.each(callback_func.always, function(i, val) {
                    val(data, textStatus, jqXHR, url, type);
                });
                data = berocket_apply_filters('ajax_load_from_url_always_after', data, url, send_data, callback_func, type);
                $(document).trigger('berocket_ajax_filtering_end');
            });
    }
    function braapf_additional_header(xhr) {
        xhr.setRequestHeader('X-Braapf', '1');
        return xhr;
    }
    function braapf_sngl_hd_loaded_filters($elements) {
        var mobile_width = berocket_apply_filters('filter_mobile_width', 767); 
        var tablet_width = berocket_apply_filters('filter_tablet_width', 1024); 
        if( $(window).width() <= mobile_width ) {
            $elements.find('.bapf_sngl_hd_mobile').remove();
        }
        if( $(window).width() > mobile_width && $(window).width() <= tablet_width ) {
            $elements.find('.bapf_sngl_hd_tablet').remove();
        }
        if( $(window).width() > tablet_width ) {
            $elements.find('.bapf_sngl_hd_desktop').remove();
        }
        return $elements;
    }
    //INIT filters
    braapf_init_load = function() {
        var mobile_width = berocket_apply_filters('filter_mobile_width', 767); 
        var tablet_width = berocket_apply_filters('filter_tablet_width', 1024); 
        if( $(window).width() <= mobile_width ) {
            $('.bapf_sngl_hd_mobile').remove();
        }
        if( $(window).width() > mobile_width && $(window).width() <= tablet_width ) {
            $('.bapf_sngl_hd_tablet').remove();
        }
        if( $(window).width() > tablet_width ) {
            $('.bapf_sngl_hd_desktop').remove();
        }
        $('.bapf_sfilter .bapf_body.bapf_mcs:not(.bapf_mcs_ready)').each(function() {
            $(this).css('max-height', $(this).data('mcs-h')).mCustomScrollbar({scrollInertia: 300}).addClass('bapf_mcs_ready');
        });
        $('.berocket_single_filter_widget').each(function() {
            var data_id = $(this).data('id');
            if( berocket_apply_filters('remove_same_filters_to_prevent_errors', ($('.berocket_single_filter_widget_'+data_id).length > 1), $(this), data_id ) ) {
                $('.berocket_single_filter_widget_'+data_id).not($('.berocket_single_filter_widget_'+data_id).first()).remove();
                berocket_throw_error('same_filters_multiple_times', data_id);
            }
        });
        $('body').on('click', '.bapf_desci', function(e) {
            e.stopPropagation();
        });
        $('body').on('hover', '.bapf_desci', function(e) {
            e.stopPropagation();
        });
        berocket_do_action('braapf_init', braapf_selected_filters);
        $(document).trigger('berocket_filters_first_load');
    }
    braapf_update_page_on_error = function() {
        if( berocket_apply_filters('update_page_on_error', true) ) {
            location.reload();
        }
    }
    //Selected filters area
    braapf_selected_filters_area_set = function() {
        if( typeof(braapf_filtered_filters) != 'undefined' && braapf_filtered_filters.length > 0 ) {
            var html = '';
            $.each(braapf_filtered_filters, function(i, taxonomy) {
                if( taxonomy.values.length > 0 ) {
                    var html2 = '<div class="bapf_sfa_taxonomy"><span>' + taxonomy.name + '</span>';
                    html2 += '<ul>';
                    var html2_elements = '';
                    $.each(taxonomy.values, function(i2, val) {
                        html2_elements += berocket_apply_filters('default_selected_filters_area_single', '<li><a href="#unselect_'+val.value+'" class="braapf_unselect" data-taxonomy="'+taxonomy.taxonomy+'" data-value="'+val.value+'"><i class="fa fa-times"></i>'+val.html+'</a></li>', val);
                    });
                    html2 += html2_elements;
                    html2 += '</ul></div>';
                    html += berocket_apply_filters('default_selected_filters_area_single_taxonomy', html2, taxonomy, html2_elements);
                }
            });
            $('.berocket_aapf_widget_selected_area').parents('.berocket_single_filter_widget').removeClass('bapf_mt_none');
            if( html ) {
                $('.bapf_sfa_mt_hide').show().parent().removeClass('bapf_mt_none');
                html = berocket_apply_filters('default_selected_filters_area_full_exist', '<div class="berocket_aapf_widget_selected_filter">' + html + '<ul class="bapf_sfa_unall"><li><a href="#Unselect_all" class="braapf_unselect_all"><i class="fa fa-times"></i> '+the_ajax_script.translate.unselect_all+'</a></li></ul>', html, braapf_filtered_filters);
            } else {
                html = berocket_apply_filters('default_selected_filters_area_full_notexist', the_ajax_script.translate.nothing_selected, html, braapf_filtered_filters);
                $('.bapf_sfa_mt_hide').hide().parent().addClass('bapf_mt_none');
            }
            $('.berocket_aapf_widget_selected_area').html(html);
        }
    }
    
    $(document).on('click', '.berocket_aapf_widget_selected_area .braapf_unselect', function(event) {
        event.preventDefault();
        $('.bapf_sfilter[data-taxonomy="'+$(this).data('taxonomy')+'"]').trigger('braapf_unselect', $(this).data());
        berocket_do_action('update_products', 'reset_single', $(this));
    });
    braapf_unselect_all = function() {
        braapf_grab_all();
        if( typeof(braapf_selected_filters) != 'undefined' && braapf_selected_filters.length > 0 ) {
            $.each(braapf_selected_filters, function(i, taxonomy) {
                if( taxonomy.values.length > 0 ) {
                    $('.bapf_sfilter[data-taxonomy="'+taxonomy.taxonomy+'"]').trigger('braapf_unselect_all', taxonomy);
                }
            });
        }
    }
    $(document).on('click', '.berocket_aapf_widget_selected_area .braapf_unselect_all', function(event) {
        event.preventDefault();
        braapf_unselect_all();
        berocket_do_action('update_products', 'reset_all', $(this));
    });
    berocket_add_filter('braapf_init', braapf_grab_all_init, 1000);
    berocket_add_filter('braapf_init', braapf_selected_filters_area_set_init, 1100);
    berocket_add_filter('braapf_init_filtered_set', braapf_selected_filters_area_set_init, 1100);
    //Error catch
    braapf_reload_page_for_products_error = function(error_exist, selector) {
        if( selector == the_ajax_script.products_holder_id) {
            braapf_update_page_on_error();
        }
        return error_exist;
    }
    //Additional part
    berocket_add_filter('ajax_load_from_url_beforeSend', braapf_additional_header);
    berocket_add_filter('update_products', braapf_update_products);
    berocket_add_filter('error_notsame_block_qty', braapf_reload_page_for_products_error);
    bapf_universal_theme_compatibility = function(data) {
        if( berocket_apply_filters('universal_theme_compatibility', true) ) {
            $(window).trigger('resize');
            //UNCODE theme
            try {if( berocket_apply_filters('uncode_theme_compatibility', (typeof(UNCODE) == 'object' && typeof(UNCODE.init) == 'function' ) ) ) {
                UNCODE.init();
            }} catch (e) {berocket_throw_error('uncode_theme_compatibility', e);}
            //Flatsome theme
            try {if( berocket_apply_filters('flatsome_theme_compatibility', (typeof(Flatsome) == 'object' && typeof(Flatsome.attach) == 'function' && jQuery(the_ajax_script.products_holder_id).length ) ) ) {
                Flatsome.attach(jQuery(the_ajax_script.products_holder_id));
            }} catch (e) {berocket_throw_error('flatsome_theme_compatibility', e);}
            //Woodmart theme
            try {if( berocket_apply_filters('woodmart_theme_compatibility', (typeof(woodmartThemeModule) == 'object' && typeof(woodmartThemeModule.init) == 'function' ) ) ) {
                woodmartThemeModule.wooInit();
                woodmartThemeModule.lazyLoading();
                woodmartThemeModule.productsLoadMore();
            }} catch (e) {berocket_throw_error('woodmart_theme_compatibility', e);}
            //Divi theme
            try {if( berocket_apply_filters('divi_theme_compatibility', (typeof(et_reinit_waypoint_modules) == 'function' ) ) ) {
                et_reinit_waypoint_modules();
            }} catch (e) {berocket_throw_error('divi_theme_compatibility', e);}
            //reyTheme theme
            try {if( berocket_apply_filters('rey_theme_compatibility', (typeof(jQuery.reyTheme) == 'object' && typeof(jQuery.reyTheme.init) == 'function' ) ) ) {
                jQuery.reyTheme.init();
            }} catch (e) {berocket_throw_error('rey_theme_compatibility', e);}
            //layzyLoadImage script
            try {if( berocket_apply_filters('layzyloadimage_script_compatibility', (typeof(layzyLoadImage) == 'function' ) ) ) {
                layzyLoadImage();
            }} catch (e) {berocket_throw_error('layzyloadimage_script_compatibility', e);}
            //jetpackLazyImagesModule script
            try {if( berocket_apply_filters('jetpacklazyimages_script_compatibility', (typeof(jetpackLazyImagesModule) == 'function' ) ) ) {
                jetpackLazyImagesModule();
            }} catch (e) {berocket_throw_error('jetpacklazyimages_script_compatibility', e);}
            try {
            jQuery('img.jetpack-lazy-image').each(function() {
                jQuery(this).removeClass('jetpack-lazy-image').attr('src', jQuery(this).data('lazy-src'));
                jQuery(this).removeClass('jetpack-lazy-image').attr('srcset', '');
            });
            } catch (e) {berocket_throw_error('jetpacklazyimages_script_compatibility', e);}
            //SWIFT script
            try {if( berocket_apply_filters('swift_script_compatibility', (typeof(SWIFT) == 'object' && typeof(SWIFT.woocommerce) == 'object' && typeof(SWIFT.woocommerce.init) == 'function' ) ) ) {
                SWIFT.woocommerce.init();
            }} catch (e) {berocket_throw_error('swift_script_compatibility', e);}
            try {if( typeof(baapfGet_wprocketInstance) != 'undefined' ) {
                baapfGet_wprocketInstance.update();
            }} catch (e) {berocket_throw_error('wprocket_script_compatibility', e);}
            try {
                jQuery(document).trigger('facetwp-loaded');
            } catch (e) {berocket_throw_error('facetwp_script_compatibility', e);}
            try {if( berocket_apply_filters('etTheme_compatibility', (typeof(etTheme) == 'object' && typeof(etTheme.global_image_lazy) == 'function' ) ) ) {
                etTheme.global_image_lazy();
            }} catch (e) {berocket_throw_error('etTheme_compatibility', e);}
            try {if( berocket_apply_filters('the7_compatibility', ($(the_ajax_script.products_holder_id+' .iso-lazy-load').length > 0) ) ) {
                $(the_ajax_script.products_holder_id+' .iso-lazy-load').attr('src', function(){
                    if(typeof($(this).data('src')) != 'undefined' && $(this).data('src')) {
                        return $(this).data('src');
                    }
                    return $(this).attr('src');
                }).attr('srcset', function() {
                    if(typeof($(this).data('srcset')) != 'undefined' && $(this).data('srcset')) {
                        return $(this).data('srcset');
                    }
                    return $(this).attr('srcset');
                }).removeClass('iso-lazy-load');
            }} catch (e) {berocket_throw_error('etTheme_compatibility', e);}
            try { if( typeof(InfiniteScroll) == 'function' ) {
                var infScroll = InfiniteScroll.data( '.shop-container .products' );
                if( typeof(infScroll) == 'object' && infScroll.options ) {
                    var infOptionsStore = infScroll.options;
                    infScroll.destroy();
                    jQuery('.shop-container .products').data('infiniteScroll', '').infiniteScroll(infOptionsStore);
                }
            }} catch (e) {berocket_throw_error('flatsome_infinitescroll', e);}
            try { if( jQuery('.shop-container .products').length && typeof(jQuery('.shop-container .products').data('packery')) == 'object' ) {
                jQuery('.shop-container .products').packery('reloadItems').packery('layout');
            }} catch (e) {berocket_throw_error('flatsome_packery', e);}
            try {if( berocket_apply_filters('JetSmartFilters_compatibility', (typeof(window.JetSmartFilters) == 'object' && typeof(window.JetSmartFilters.initializeFilters) == 'function' ) ) ) {
                window.JetSmartFilters.initializeFilters()
            }} catch (e) {berocket_throw_error('JetSmartFilters_compatibility', e);}
            $(window).trigger('resize');
            $(window).trigger('scroll');
        }
        return data;
    }
    berocket_add_filter('ajax_load_from_url_always_after', bapf_universal_theme_compatibility, 2500);
    $(document).ready(function(){
        if( berocket_apply_filters('remove_shortcode_fix_filters', $('.berocket_wc_shortcode_fix').length) ) {
            braapf_replace_each_filter($('.berocket_wc_shortcode_fix').html());
            $('.berocket_wc_shortcode_fix').html('');
        }
        braapf_init_load();
        braapf_filtered_filters_set();
        $(document).trigger('berocket_filters_document_ready');
    });
    braapf_disable_ajax_loading = function($has_product) {
        return true;
    }
    $(document).ready(function() {
        if( the_ajax_script.disable_ajax_loading) {
            berocket_add_filter('page_has_products_holder', braapf_disable_ajax_loading);
        }
    });
    braapf_close_tippy = function () {
        try{
            if( $('.tippy-box').length ) {
                $('.tippy-box').each(function() {
                    if( typeof($(this).parent()[0]._tippy) != 'undefined' ) {
                        $(this).parent()[0]._tippy.hide();
                    }
                });
            }
        } catch(e){}
    }
    berocket_add_filter('update_products', braapf_close_tippy);
})(jQuery);
var braapf_get_current_filters,
braapf_filters_url_decode,
braapf_scroll_shop_to_top,
braapf_set_filters_to_link,
braapf_convert_ckbox_to_radio,
braapf_hierarhical_save,
braapf_hierarhical_set,
braapf_collapse_status_save,
braapf_collapse_status_set,
braapf_show_hide_values_save,
braapf_show_hide_values_set;
(function ($){
    //default filters
    braapf_get_current_filters = function (url_data) {
        if( url_data.queryargs.length ) {
            var newqueryargs = [];
            $.each(url_data.queryargs, function (i, val) {
                if( val.name == the_ajax_script.url_variable || decodeURI(val.name) == the_ajax_script.url_variable ) {
                    url_data.filter = val.value;
                } else {
                    newqueryargs.push(val);
                }
            });
            url_data.queryargs = newqueryargs;
        }
        return url_data;
    }
    braapf_set_filters_to_link = function(url, url_data, parameters, url_without_query, query_get) {
        if( typeof(url_data.filter) == 'string' && url_data.filter.length ) {
            if( query_get.length ) {
                url += '&';
            } else {
                url += '?';
            }
            url += the_ajax_script.url_variable + '=' + url_data.filter;
        }
        return url;
    }
    //SINGLE ELEMENT / RADIOBUTTONS
    braapf_convert_ckbox_to_radio = function(thisel, taxonomy, value, checked){
        var $this = $(thisel);
        var parent = $(thisel).closest('.bapf_sfilter.bapf_ckbox');
        if( parent.is('.bapf_asradio') ) {
            parent.find('input[type=checkbox]:checked:not(:disabled)').each(function() {
                var val = $(this).val();
                if( val != value ) {
                    braapf_checkbox_same(taxonomy, val, false);
                }
            });
        }
    }
    berocket_add_filter('input_ckbox_changed', braapf_convert_ckbox_to_radio);
    //CHILD HIERARCHICAL
    $(document).on('click', '.bapf_ochild, .bapf_cchild', function(e) {
        e.preventDefault();
        e.stopPropagation();
        if( $(this).is('.bapf_ochild' ) ) {
            $(this).trigger('bapf_ochild');
        } else {
            $(this).trigger('bapf_cchild');
        }
    });
    $(document).on('bapf_ochild', '.bapf_sfilter.bapf_ckbox ul li', function(e) {
        e.stopPropagation();
        if( berocket_apply_filters('colaps_child_open_apply', true, $(this)) ) {
            $(this).find('.bapf_ochild, .bapf_cchild').first().removeClass('bapf_ochild').removeClass('fa-plus').addClass('bapf_cchild').addClass('fa-minus');
            $(this).find('ul').first().show();
        }
    });
    $(document).on('bapf_cchild', '.bapf_sfilter.bapf_ckbox ul li', function(e) {
        e.stopPropagation();
        if( berocket_apply_filters('colaps_child_close_apply', true, $(this)) ) {
            $(this).find('.bapf_ochild, .bapf_cchild').first().addClass('bapf_ochild').addClass('fa-plus').removeClass('bapf_cchild').removeClass('fa-minus');
            $(this).find('ul').first().hide();
        }
    });
    var braapf_hierarhical_values = [];
    braapf_hierarhical_save = function(data) {
        braapf_hierarhical_values = [];
        $('.bapf_cchild').each(function() {
            braapf_hierarhical_values.push($(this).parent().children('input[type=checkbox]').attr('id'));
        });
        return data;
    }
    braapf_hierarhical_set = function(data) {
        $.each(braapf_hierarhical_values, function(i, val) {
            $('#'+val).trigger('bapf_ochild');
        });
        return data;
    }
    berocket_add_filter('ajax_load_from_url_done', braapf_hierarhical_save, 1);
    berocket_add_filter('ajax_load_from_url_done_after', braapf_hierarhical_set, 1000);
    //SHOW/HIDE FILTERS
    $(document).on('click', '.bapf_ocolaps .bapf_colaps_togl, .bapf_ccolaps .bapf_colaps_togl', function(e) {
        e.preventDefault;
        if( $(this).closest('.bapf_ocolaps, .bapf_ccolaps').is('.bapf_ocolaps' ) ) {
            $(this).closest('.bapf_ocolaps, .bapf_ccolaps').trigger('bapf_ocolaps');
        } else {
            $(this).closest('.bapf_ocolaps, .bapf_ccolaps').trigger('bapf_ccolaps');
        }
    });
    $(document).on('bapf_ocolaps', '.bapf_sfilter.bapf_ocolaps, .bapf_sfilter.bapf_ccolaps', function(e) {
        $(this).removeClass('bapf_ocolaps').addClass('bapf_ccolaps');
        if( berocket_apply_filters('colaps_smb_open_apply', true, $(this)) ) {
            $(this).find('.bapf_body').first().show();
            if( $(this).find('.bapf_colaps_smb').length ) {
                $(this).find('.bapf_colaps_smb').removeClass('fa-chevron-down').addClass('fa-chevron-up');
            }
        }
    });
    $(document).on('bapf_ccolaps', '.bapf_sfilter.bapf_ocolaps, .bapf_sfilter.bapf_ccolaps', function(e) {
        $(this).addClass('bapf_ocolaps').removeClass('bapf_ccolaps');
        if( berocket_apply_filters('colaps_smb_close_apply', true, $(this)) ) {
            $(this).find('.bapf_body').first().hide();
            if( $(this).find('.bapf_colaps_smb').length ) {
                $(this).find('.bapf_colaps_smb').addClass('fa-chevron-down').removeClass('fa-chevron-up');
            }
        }
    });
    var braapf_collapse_status = {open:[], close:[]};
    braapf_collapse_status_save = function(data) {
        braapf_collapse_status = {open:[], close:[]};
        $('.bapf_ocolaps, .bapf_ccolaps').each(function() {
            if( $(this).is('.bapf_ocolaps') ) {
                braapf_collapse_status.open.push($(this).attr('id'));
            } else {
                braapf_collapse_status.close.push($(this).attr('id'));
            }
        });
        return data;
    }
    braapf_collapse_status_set = function(data) {
        $.each(braapf_collapse_status.open, function(i, val) {
            $('#'+val).trigger('bapf_ccolaps');
        });
        $.each(braapf_collapse_status.close, function(i, val) {
            $('#'+val).trigger('bapf_ocolaps');
        });
        return data;
    }
    berocket_add_filter('ajax_load_from_url_done', braapf_collapse_status_save, 1);
    berocket_add_filter('ajax_load_from_url_done_after', braapf_collapse_status_set, 1000);
    //Show/Hide values button
    $(document).on('click', '.bapf_show_hide', function() {
        $(this).closest('.bapf_sfilter').toggleClass('bapf_fhide');
        if( $(this).closest('.bapf_sfilter').is('.bapf_fhide') ) {
            $(this).text($(this).data('show'));
        } else {
            $(this).text($(this).data('hide'));
        }
    });
    var braapf_show_hide_values_status = {open:[], close:[]};
    braapf_show_hide_values_save = function(data) {
        braapf_show_hide_values_status = {open:[], close:[]};
        $('.bapf_sfilter').each(function() {
            if( $(this).is('.bapf_fhide') ) {
                braapf_show_hide_values_status.close.push($(this).attr('id'));
            } else {
                braapf_show_hide_values_status.open.push($(this).attr('id'));
            }
        });
        return data;
    }
    braapf_show_hide_values_set = function(data) {
        $.each(braapf_show_hide_values_status.open, function(i, val) {
            $('#'+val).removeClass('bapf_fhide');
            $('#'+val).find('.bapf_show_hide').text($('#'+val).find('.bapf_show_hide').data('hide'));
        });
        $.each(braapf_show_hide_values_status.close, function(i, val) {
            $('#'+val).addClass('bapf_fhide');
            $('#'+val).find('.bapf_show_hide').text($('#'+val).find('.bapf_show_hide').data('show'));
        });
        return data;
    }
    berocket_add_filter('ajax_load_from_url_done', braapf_show_hide_values_save, 1);
    berocket_add_filter('ajax_load_from_url_done_after', braapf_show_hide_values_set, 1000);
    //Scroll page to the top
    braapf_scroll_shop_to_top = function(data, url, send_data, callback_func, type) {
        var mobile_width = berocket_apply_filters('filter_mobile_width', 767); 
        if( berocket_apply_filters('scroll_shop_to_top', (type == 'default' && ( the_ajax_script.scroll_shop_top == 1 
            || (the_ajax_script.scroll_shop_top == 2 && $(window).width() < mobile_width)
            || (the_ajax_script.scroll_shop_top == 3 && $(window).width() >= mobile_width) )
        ) ) ) {
            var top_scroll_offset = 0;
            if( $( the_ajax_script.products_holder_id ).length ) {
                top_scroll_offset = $( the_ajax_script.products_holder_id ).offset().top + parseInt(the_ajax_script.scroll_shop_top_px);
                if(top_scroll_offset < 0) top_scroll_offset = 0;
            } else if( $( '.bapf_no_products' ).length ) {
                top_scroll_offset = $( '.bapf_no_products' ).offset().top + parseInt(the_ajax_script.scroll_shop_top_px);
                if(top_scroll_offset < 0) top_scroll_offset = 0;
            }
            $("html, body").animate({ scrollTop: top_scroll_offset }, "slow");
        }
        return data;
    }
    berocket_add_filter('ajax_load_from_url_beforeSend', braapf_scroll_shop_to_top);
    //Sidebar
    $(document).on('mousedown', '.wc-product-table-reset a', function() {
        $(this).remove();
        br_reset_all_filters();
    });
    $(document).on('click', '.berocket_element_above_products_is_hide_toggle', function (e){
        e.preventDefault();
        $(this).toggleClass( "active" ).next().slideToggle(200, 'linear');
        var is_active = 'inactive';
        if( $(this).is('.active') ) {
            is_active = 'active';
        }
        $(document).trigger('berocket_element_above_products_'+is_active);
    });
    //Compatibility scripts

    function berocket_ajax_load_product_table_compat() {
        if( jQuery('.berocket_product_table_compat .dataTables_length select').length ) {
            jQuery('.berocket_product_table_compat .wc-product-table').dataTable()._fnSaveState();
        }
        var tableid = jQuery('.berocket_product_table_compat .wc-product-table').attr('id');
        if( typeof(window['config_'+tableid]) != 'undefined' && window['config_'+tableid].serverSide ) {
            jQuery('.berocket_product_table_compat .wc-product-table').DataTable().destroy();
            var table_html = jQuery('.berocket_product_table_compat').html();
            jQuery('.berocket_product_table_compat').html('');
            jQuery('.berocket_product_table_compat').html(table_html);
            jQuery('.berocket_product_table_compat .blockUI.blockOverlay').remove();
            jQuery('.berocket_product_table_compat .wc-product-table').productTable();
        }
    }
    $(document).on('berocket_ajax_filtering_start', function() {
        if( jQuery('.berocket_product_table_compat').length ) {
            berocket_ajax_load_product_table_compat();
        }
    });
    braapf_filters_url_decode = function(filter) {
        if( the_ajax_script.seo_uri_decode ) {
            filter = encodeURIComponent(filter);
            if( the_ajax_script.nice_urls ) {
                filter = filter.replace(/%2F/g, '/');
            }
        }
        return filter;
    }
	jQuery( document ).on( 'elementor/popup/show', function() {
        try{
            braapf_init_load();
        } catch (e) {berocket_throw_error('wprocketInstance_get', e);}
	} );
})(jQuery);
berocket_add_filter('get_current_url_data', braapf_get_current_filters);
berocket_add_filter('compat_filters_to_string', braapf_filters_url_decode, 900);
berocket_add_filter('url_from_urldata_linkget', braapf_set_filters_to_link);
var braapf_child_parent_grab_single,
braapf_child_parent_fix_selected,
braapf_child_parent_load_with_update_button,
braapf_check_partial_load_filters,
braapf_add_loader_element,
braapf_remove_loader_element,
braapf_elementor_sticky_fix,
baapfGet_wprocketInstance,
brapf_jet_smart_filters_add,
brapf_jet_smart_filters_remove_pages,
brapf_jet_smart_filters;
(function ($){
    //CHILD/PARENT FEATURE
    braapf_child_parent_grab_single = function(single_data, element, selected_filters) {
        var child_position = element.data('child');
        if( typeof(child_position) != 'undefined' && child_position > 0 
         && typeof(single_data) == 'object' && typeof(single_data.values) != 'undefined' && Array.isArray(single_data.values) && single_data.values.length > 0 ) {
            child_position++;
            var taxonomy = element.data('taxonomy');
            var next_child = $('.bapf_sfilter.bapf_child_'+child_position+'[data-taxonomy="'+taxonomy+'"]');
            if( next_child.length ) {
                var new_single_data = braapf_grab_single (next_child, [], true);
                if( typeof(new_single_data) == 'object' && typeof(new_single_data.values) != 'undefined' && Array.isArray(new_single_data.values) && new_single_data.values.length > 0 ) {
                    single_data = new_single_data;
                }
            }
        }
        return single_data;
    }
    braapf_child_parent_fix_selected = function(context, element) {
        if( element != false) {
            var $filter = element.closest('.bapf_sfilter');
            if( $filter.length ) {
                var child_position = $filter.data('child');
                var taxonomy = $filter.data('taxonomy');
                if( typeof(child_position) != 'undefined' && child_position > 0 ) {
                    for(i = child_position + 1; $('.bapf_sfilter.bapf_child_'+i+'[data-taxonomy="'+taxonomy+'"]').length; i++) {
                        $('.bapf_sfilter.bapf_child_'+i+'[data-taxonomy="'+taxonomy+'"]').trigger('braapf_unselect', false);
                    }
                }
            }
        }
        return context;
    }
    braapf_child_parent_load_with_update_button = function(issend, context, element) {
        if( element != false) {
            var $filter = element.closest('.bapf_sfilter');
            if( $filter.length ) {
                var child_position = $filter.data('child');
                var taxonomy = $filter.data('taxonomy');
                if( typeof(child_position) != 'undefined' && child_position > 0 ) {
                    issend = true;
                }
            }
        }
        return issend;
    }
    braapf_check_partial_load_filters = function(issend, context, element) {
        if( $('.berocket_single_filter_widget.bapf_partload').length > 0 ) {
            issend = true;
        }
        return issend;
    }
    berocket_add_filter( 'grab_single_filter_default', braapf_child_parent_grab_single, 9000000 );
    berocket_add_filter( 'before_update_products_context', braapf_child_parent_fix_selected );
    berocket_add_filter( 'apply_filters_to_page_partial', braapf_child_parent_load_with_update_button );
    berocket_add_filter( 'apply_filters_to_page_partial', braapf_check_partial_load_filters );
    
    //LOADER OVER PAGE
    var braapf_loader_element;
    braapf_add_loader_element = function (data, url, send_data, callback_func, type) {
        if( type == 'default' ) {
            if( typeof(braapf_loader_element) != 'undefined' && typeof(braapf_loader_element.remove) == 'function' ) {
                braapf_loader_element.remove();
            }
            braapf_loader_element = $(the_ajax_script.load_image);
            $('body').append(braapf_loader_element);
        }
        return data;
    }
    braapf_remove_loader_element = function (data) {
        if( typeof(braapf_loader_element) != 'undefined' && typeof(braapf_loader_element.remove) == 'function' ) {
            braapf_loader_element.remove();
        }
        return data;
    }
    berocket_add_filter( 'ajax_load_from_url_beforeSend', braapf_add_loader_element );
    berocket_add_filter( 'ajax_load_from_url_always_after', braapf_remove_loader_element );
    //FILTER LINKS
    $(document).on('click', '.bapf_sfilter.bapf_ckbox .bapf_body li label a', function(event){event.preventDefault();$(this).parent().trigger('click')});
    //ELEMENTOR PRO
    if( jQuery('.elementor-widget').length ) {
        jQuery(window).on('scroll', function() {
            jQuery('.elementor-sticky__spacer .bapf_sfilter').remove();
        });
    }
    //WP-Rocket
    try {
        window.addEventListener('LazyLoad::Initialized',function(e){
            baapfGet_wprocketInstance = e.detail.instance;
        });
    } catch (e) {berocket_throw_error('wprocketInstance_get', e);}
    //WooCommerce quantity fix
    $(document).on("change", "form.cart input.qty", function() {
        try {
            $(this.form).find("[data-quantity]").attr("data-quantity", this.value);
        } catch (e) {berocket_throw_error('wc_quantity_change', e);}
    });
    //JetWooBuilder
    brapf_jet_smart_filters = function(data) {
        var $html = $('<div><div>'+data+'</div></div>');
        if( jQuery('#jet-smart-filters-js-extra').length > 0 && $html.find('#jet-smart-filters-js-extra').length > 0 ) {
            jQuery('#jet-smart-filters-js-extra').replaceWith($html.find('#jet-smart-filters-js-extra'));
        }
    }
    brapf_jet_smart_filters_remove_pages = function(url_data) {
        if( Array.isArray(url_data.queryargs) ) {
            var newqueryargs = [];
            $.each(url_data.queryargs, function(i, val) {
                if(val.name != 'pagenum') {
                    newqueryargs.push(val);
                }
            });
            url_data.queryargs = newqueryargs;
        }
        return url_data;
    }
    brapf_jet_smart_filters_add = function(elem) {
        elem.done.push(brapf_jet_smart_filters);
        return elem;
    }
    brapf_JetSmartFilter_extra_props = function() {
        var compat_filters = braapf_compact_filters();
        var filter_mask = berocket_apply_filters('braapf_filters_mask', the_ajax_script.url_mask);
        var filter_string = braapf_compat_filters_to_string(compat_filters, filter_mask, the_ajax_script.url_split);
        JetSmartFilterSettings.extra_props.brfilters = filter_string;
    }
    jQuery(document).ready(function() {
        if( berocket_apply_filters('JetSmartFilter_compatibility', (jQuery('#jet-smart-filters-js-extra').length > 0)) ) {
            jQuery(document).on('berocket_ajax_products_loaded', brapf_JetSmartFilter_extra_props);
            brapf_JetSmartFilter_extra_props();
            berocket_add_filter( 'ajax_load_from_filters', brapf_jet_smart_filters_add );
            berocket_add_filter( 'braapf_remove_pages_from_url_data', brapf_jet_smart_filters_remove_pages );
        }
    });
})(jQuery);
jQuery(document).trigger('bapf_js_loaded');
var braapf_init_ion_slidr,
braapf_ion_slidr_same,
braapf_jqrui_slidr_ion_value_wc_price,
braapf_jqrui_slidr_ion_value_arr_attr,
braapf_init_ion_slidr_for_parent,
braapf_grab_single_ion,
braapf_jqrui_slidr_ion_values_link_arr_attr;
(function ($){
    braapf_init_ion_slidr = function () {
        braapf_init_ion_slidr_for_parent($(document));
    }
    braapf_init_ion_slidr_for_parent = function($parent) {
        $parent.find(".bapf_slidr_ion:not(.bapf_slidr_ready)").each(function() {
            var $this = $(this).find('.bapf_slidr_all .bapf_slidr_main');
            var update_function = function(data) {
                if( !$this.is('.bapf_ion_blocked') ) {
                    $this.addClass('bapf_ion_blocked');
                    var taxonomy = $this.closest('.bapf_sfilter').data('taxonomy');
                    braapf_ion_slidr_same(taxonomy, data);
                    var filter_changed_element = {
                        element:'#'+$this.closest('.bapf_sfilter').attr('id'),
                        parent: 0,
                        find: '.bapf_body'
                    };
                    berocket_apply_filters('filter_changed_element', filter_changed_element, $this);
                    berocket_do_action('update_products', 'filter', $this);
                    $this.removeClass('bapf_ion_blocked');
                }
            }
            var ionRangeData = berocket_apply_filters('jqrui_data_slidr_ion', {
                type: "double",
                from: $this.data('start'),
                to: $this.data('end'),
                grid: false,
                force_edges: true,
                onFinish: update_function,
                onUpdate: update_function,
                prettify: function(value) {
                    value = berocket_apply_filters('jqrui_slidr_ion_'+$this.data('display'), value, $this);
                    return value;
                }
            }, $this);
            $this.ionRangeSlider(ionRangeData);
            $(this).addClass('bapf_slidr_ready');
        });
    }
    braapf_ion_slidr_same = function (taxonomy, data) {
        $('.bapf_slidr_ion.bapf_slidr_ready[data-taxonomy="'+taxonomy+'"]').each(function() {
            var $slider = $(this).find('.bapf_slidr_main');
            $slider.addClass('bapf_ion_blocked');
            var slider_data = $slider.data("ionRangeSlider");
            slider_data.update({from:data.from, to:data.to});
            $slider.removeClass('bapf_ion_blocked');
        });
    }
    braapf_jqrui_slidr_ion_value_wc_price = function (value, $element) {
        var number_style = $element.data('number_style');
        if( ! number_style ) {
            number_style = the_ajax_script.number_style;
        }
        value = berocket_format_number (parseFloat(value), number_style );
        return value;
    }
    braapf_jqrui_slidr_ion_value_arr_attr = function(value, $element) {
        var attr = $element.data('attr');
        value = attr[value].n;
        return value;
    }
    braapf_grab_single_ion = function(single_data, element) {
        if( element.is('.bapf_slidr_ion.bapf_slidr_ready') && single_data != false ) {
            var data = element.find(".bapf_slidr_main").data('ionRangeSlider');
            var $slider = element.find('.bapf_slidr_main');
            var values = [data.options.from, data.options.to];
            var input_values = [berocket_apply_filters('jqrui_slidr_ion_'+$slider.data('display'), data.options.from, $slider), berocket_apply_filters('jqrui_slidr_ion_'+$slider.data('display'), data.options.to, $slider)];
            var prefix = $slider.data('prefix');
            if( typeof(prefix) == 'undefined' ) {
                prefix = '';
            }
            var postfix = $slider.data('postfix');
            if( typeof(postfix) == 'undefined' ) {
                postfix = '';
            }
            input_values[0] = prefix + input_values[0] + postfix;
            input_values[1] = prefix + input_values[1] + postfix;
            if( values[0] != $slider.data('min') || values[1] != $slider.data('max') ) {
                var value_ready = {value:values[0]+'_'+values[1], html:input_values[0]+' - '+input_values[1]};
                value_ready = berocket_apply_filters('jqrui_slidr_ion_link_'+$slider.data('display'), value_ready, values, input_values, $slider, single_data);
                single_data.values = [value_ready];
            }
        }
        return single_data;
    }
    braapf_jqrui_slidr_ion_values_link_arr_attr = function(value_ready, values, input_values, $slider, single_data) {
        var attr = $slider.data('attr');
        value_ready.value = attr[values[0]].v+'_'+attr[values[1]].v;
        return value_ready;
    }
    $(document).on('braapf_unselect braapf_unselect_all', '.bapf_slidr_ion', function(event, data) {
        var $slider = $(this).find('.bapf_slidr_main');
        var slider_data = $slider.data("ionRangeSlider");
        $slider.addClass('bapf_ion_blocked');
        slider_data.update({from:slider_data.options.min, to:slider_data.options.max});
        $slider.removeClass('bapf_ion_blocked');
    });
    function braapf_jqrui_slidr_ion_berocket_add_filter() {
        berocket_add_filter('braapf_init', braapf_init_ion_slidr);
        berocket_add_filter('braapf_init_for_parent', braapf_init_ion_slidr_for_parent);
        berocket_add_filter('grab_single_filter_default', braapf_grab_single_ion);
        berocket_add_filter('jqrui_slidr_ion_link_arr_attr', braapf_jqrui_slidr_ion_values_link_arr_attr);
        berocket_add_filter('jqrui_slidr_ion_link_arr_attr_price', braapf_jqrui_slidr_ion_values_link_arr_attr);
        berocket_add_filter('jqrui_slidr_ion_wc_price', braapf_jqrui_slidr_ion_value_wc_price);
        berocket_add_filter('jqrui_slidr_ion_arr_attr', braapf_jqrui_slidr_ion_value_arr_attr);
        berocket_add_filter('jqrui_slidr_ion_arr_attr_price', braapf_jqrui_slidr_ion_value_arr_attr, 10);
        berocket_add_filter('jqrui_slidr_ion_arr_attr_price', braapf_jqrui_slidr_ion_value_wc_price, 20);
    }
    if ( typeof(berocket_add_filter) == 'function' ) {
        braapf_jqrui_slidr_ion_berocket_add_filter();
    } else {
        jQuery(document).on('berocket_hooks_ready', function() {
            braapf_jqrui_slidr_ion_berocket_add_filter();
        });
    }
})(jQuery);

var braapf_grab_single_select;
(function ($){
    $(document).on('change', '.bapf_slct .bapf_body select', function() {
        var filter_changed_element = {
            element:'#'+$(this).closest('.bapf_sfilter').attr('id'),
            parent: 0,
            find: '.bapf_body'
        };
        berocket_apply_filters('filter_changed_element', filter_changed_element, $(this));
        berocket_do_action('update_products', 'filter', $(this));
    });
    braapf_grab_single_select = function(single_data, element) {
        if( element.is('.bapf_slct') && single_data != false ) {
            var $select = $('.bapf_slct[data-taxonomy="'+single_data.taxonomy+'"] .bapf_body select:not(:disabled)');
            var added_values = [];
            $select.find('option:selected:not(:disabled)').each(function() {
                var value = $(this).val();
                if( value && added_values.indexOf(value) === -1 ) {
                    added_values.push(value);
                    single_data.values.push({value: value, html: $(this).data('name')})
                }
            });
        }
        return single_data;
    }
    $(document).on('braapf_unselect braapf_unselect_all', '.bapf_slct', function(event, data) {
        $(this).find('.bapf_body select:not(:disabled) option:selected:not(:disabled)').each(function() {
            if( typeof(data) == 'undefined' || typeof(data.value) == 'undefined' || data.value == $(this).val() ) {
                $(this).prop('selected', false);
            }
        });
    });
    if ( typeof(berocket_add_filter) == 'function' ) {
        berocket_add_filter('grab_single_filter_default', braapf_grab_single_select);
    } else {
        jQuery(document).on('berocket_hooks_ready', function() {
            berocket_add_filter('grab_single_filter_default', braapf_grab_single_select);
        });
    }
})(jQuery);

var bapf_select2_init,
bapf_select2_init_for_parent,
bapf_select2_disable_for_parent;
jQuery(document).ready(function() {
    bapf_select2_init = function() {
        bapf_select2_init_for_parent(jQuery(document));
    }
    bapf_select2_init_for_parent = function($parent) {
        if( $parent.find('.bapf_select2').length && typeof($parent.find('.bapf_select2').select2) != 'undefined' ) {
            $parent.find('.bapf_select2').each(function() {
                if ( ! jQuery(this).data('select2') && ! jQuery(this).is('.select2-hidden-accessible') ) { 
                    var select2data = {width:'100%', theme:'default'};
                    if (jQuery(this).prop('multiple') ) {
                        select2data.placeholder = jQuery(this).data('placeholder');
                    }
                    if( jQuery(this).parents('#berocket-ajax-filters-sidebar').length ) {
                        if( jQuery('#bapf-select2-high-zindex').length == 0 ) {
                            jQuery('body').append('<div id="bapf-select2-high-zindex"></div>');
                        }
                        select2data.dropdownParent = jQuery('#bapf-select2-high-zindex');
                    }
                    select2data = berocket_apply_filters('jqrui_data_select2', select2data, jQuery(this));
                    jQuery(this).select2(select2data);
                }
            });
        }
    }
    bapf_select2_disable_for_parent = function($parent) {
        if( $parent.find('.bapf_select2').length && typeof($parent.find('.bapf_select2').select2) != 'undefined' ) {
            $parent.find('.bapf_select2').each(function() {
                if ( jQuery(this).data('select2') ) {
                    jQuery(this).select2('destroy');
                }
            });
        }
    }
    jQuery(document).on('berocket_ajax_filtering_on_update', function() {
        bapf_select2_disable_for_parent(jQuery(document));
    });
    function bapf_select2_berocket_add_filter() {
        bapf_select2_init();
        berocket_add_filter('braapf_init', bapf_select2_init, 2000);
        berocket_add_filter('braapf_init_for_parent', bapf_select2_init_for_parent);
    }
    if ( typeof(berocket_add_filter) == 'function' ) {
        bapf_select2_berocket_add_filter();
    } else {
        jQuery(document).on('berocket_hooks_ready', function() {
            bapf_select2_berocket_add_filter();
        });
    }
});
var braapf_init_jqrui_slidr,
braapf_jqrui_slidr_same,
braapf_jqrui_slidr_values_wc_price,
braapf_init_jqrui_slidr_for_parent,
braapf_grab_single_jqrui,
braapf_jqrui_slidr_values_arr_attr,
braapf_jqrui_slidr_values_link_arr_attr;
(function ($){
    function braapf_slider_input_focusin(input, position) {
        var $slider = $(input).closest('.bapf_slidr_jqrui.bapf_slidr_ready').find('.bapf_slidr_main');
        var values = $slider.slider('values');
        $(input).val(values[position]);
        $(input).data('val', values[position]);
    }
    function braapf_slider_input_focusout_change(input, position, trigger) {
        var $slider = $(input).closest('.bapf_slidr_jqrui.bapf_slidr_ready').find('.bapf_slidr_main');
        if( trigger == 'focusout' ) {
            if( $(input).val() == $(input).data('val') ) {
                var values = $slider.slider('values');
                $slider.trigger('braapf_change_jqrui_slidr', [values]);
            }
        } else {
            var val = parseInt($(input).val());
            $slider.slider('values', position, val);
        }
    }
    $.each([{position:0, className:'bapf_from'}, {position:1, className:'bapf_to'}], function(i, val) {
        $(document).on('focusin', '.bapf_slidr_jqrui.bapf_slidr_ready .'+val.className+' input[type=text]', function() {
            braapf_slider_input_focusin(this, val.position);
        });
        $(document).on('change focusout', '.bapf_slidr_jqrui.bapf_slidr_ready .'+val.className+' input[type=text]', function(event) {
            braapf_slider_input_focusout_change(this, val.position, event.type);
        });
        $(document).on('change', '.bapf_slidr_jqrui.bapf_slidr_ready .'+val.className+' select', function(event) {
            braapf_slider_input_focusout_change(this, val.position, event.type);
        });
    });
    //SPAN CHANGED TEXT
    $(document).on('braapf_change_jqrui_slidr', '.bapf_slidr_jqrui .bapf_slidr_main', function(event, values) {
        var $element = $(this);
        var input_values = [values[0], values[1]];
        input_values = berocket_apply_filters('jqrui_slidr_'+$element.data('display'), input_values, $element);
        if( $element.closest('.bapf_slidr_jqrui').find('.bapf_from span.bapf_val').length ) {
            $element.closest('.bapf_slidr_jqrui').find('.bapf_from span.bapf_val').text(input_values[0]);
        }
        if( $element.closest('.bapf_slidr_jqrui').find('.bapf_to span.bapf_val').length ) {
            $element.closest('.bapf_slidr_jqrui').find('.bapf_to span.bapf_val').text(input_values[1]);
        }
    });
    //INPUT CHANGED TEXT
    $(document).on('braapf_change_jqrui_slidr', '.bapf_slidr_jqrui .bapf_slidr_main', function(event, values) {
        var $element = $(this);
        var input_values = [values[0], values[1]];
        input_values = berocket_apply_filters('jqrui_slidr_'+$element.data('display'), input_values, $element);
        if( $element.closest('.bapf_slidr_jqrui').find('.bapf_from input[type=text]').length ) {
            $element.closest('.bapf_slidr_jqrui').find('.bapf_from input[type=text]').val(input_values[0]);
        }
        if( $element.closest('.bapf_slidr_jqrui').find('.bapf_to input[type=text]').length ) {
            $element.closest('.bapf_slidr_jqrui').find('.bapf_to input[type=text]').val(input_values[1]);
        }
    });
    //SELECT CHANGED
    $(document).on('braapf_change_jqrui_slidr', '.bapf_slidr_jqrui .bapf_slidr_main', function(event, values) {
        var $element        = $(this);
        var attr            = $element.data('attr');
        if( $element.closest('.bapf_slidr_jqrui').find('.bapf_from select').length || $element.closest('.bapf_slidr_jqrui').find('.bapf_to select').length ) {
            var attr = $element.data('attr');
            var from_options    = [];
            var to_options      = [];
            var from_end = false, to_start = false;
            $.each(attr, function(i, val) {
                if( i == values[0] ) to_start = true;
                if( ! from_end ) {
                    from_options.push({v:val.v, n:val.n, ov:i});
                }
                if( to_start ) {
                    to_options.push({v:val.v, n:val.n, ov:i});
                }
                if( i == values[1] ) from_end = true;
            });
        }
        if( $element.closest('.bapf_slidr_jqrui').find('.bapf_from select').length ) {
            $element.closest('.bapf_slidr_jqrui').find('.bapf_from select option').remove();
            $.each(from_options, function(i, val) {
                var selected = '';
                if( val.ov == values[0] ) {
                    selected = ' selected';
                }
                $element.closest('.bapf_slidr_jqrui').find('.bapf_from select').append($('<option value="'+val.ov+'"'+selected+'>'+val.n+'</option>'));
            });
        }
        if( $element.closest('.bapf_slidr_jqrui').find('.bapf_to select').length ) {
            $element.closest('.bapf_slidr_jqrui').find('.bapf_to select option').remove();
            $.each(to_options, function(i, val) {
                var selected = '';
                if( val.ov == values[1] ) {
                    selected = ' selected';
                }
                $element.closest('.bapf_slidr_jqrui').find('.bapf_to select').append($('<option value="'+val.ov+'"'+selected+'>'+val.n+'</option>'));
            });
        }
    });
    braapf_init_jqrui_slidr = function() {
        braapf_init_jqrui_slidr_for_parent($(document));
    }
    braapf_init_jqrui_slidr_for_parent = function($parent) {
        $parent.find( ".bapf_slidr_jqrui:not(.bapf_slidr_ready)" ).each(function() {
            var $slider = $(this).find('.bapf_slidr_main');
            var slider_data = berocket_apply_filters('jqrui_data_slidr_jqrui', {
                range: true,
                min: $slider.data('min'),
                max: $slider.data('max'),
                values: [ $slider.data('start'), $slider.data('end') ],
                step: $slider.data('step'),
                create:function(event, ui) {
                    var values = $(this).slider('values');
                    $(this).trigger('braapf_change_jqrui_slidr', [values]);
                },
                slide:function(event, ui) {
                    $(this).trigger('braapf_change_jqrui_slidr', [ui.values]);
                },
                change:function(event, ui) {
                    var values = $(this).slider('values');
                    $(this).trigger('braapf_change_jqrui_slidr', [values]);
                    if( !$(this).is('.bapf_jqrui_blocked') ) {
                        var values = $(this).slider('values');
                        var taxonomy = $(this).parents('.bapf_sfilter').data('taxonomy');
                        braapf_jqrui_slidr_same(taxonomy, values);
                        var filter_changed_element = {
                            element:'#'+$(this).closest('.bapf_sfilter').attr('id'),
                            parent: 0,
                            find: '.bapf_body'
                        };
                        berocket_apply_filters('filter_changed_element', filter_changed_element, $(this));
                        berocket_do_action('update_products', 'filter', $(this));
                    }
                },
            }, $slider);
            $slider.slider(slider_data);
            $(this).addClass('bapf_slidr_ready');
        });
    }
    braapf_jqrui_slidr_same = function (taxonomy, values) {
        $('.bapf_slidr_jqrui.bapf_slidr_ready[data-taxonomy="'+taxonomy+'"]').each(function() {
            var $slider = $(this).find('.bapf_slidr_main');
            $slider.addClass('bapf_jqrui_blocked');
            $slider.slider('values', values);
            $slider.removeClass('bapf_jqrui_blocked');
        });
    }
    braapf_jqrui_slidr_values_wc_price = function(values, $element) {
        var number_style = $element.data('number_style');
        if( ! number_style ) {
            number_style = the_ajax_script.number_style;
        }
        values[0] = berocket_format_number (values[0], number_style );
        values[1] = berocket_format_number (values[1], number_style );
        return values;
    }
    braapf_grab_single_jqrui = function(single_data, element) {
        if( element.is('.bapf_slidr_jqrui.bapf_slidr_ready') && single_data != false ) {
            var $slider = element.find('.bapf_slidr_main');
            var values = $slider.slider('values');
            var input_values = $slider.slider('values');
            var prefix = '';
            if( element.find('.bapf_tbprice').length ) {
                prefix = element.find('.bapf_tbprice').first().text();
            }
            var postfix = '';
            if( element.find('.bapf_taprice').length ) {
                postfix = element.find('.bapf_taprice').first().text();
            }
            if( values[0] != $slider.data('min') || values[1] != $slider.data('max') ) {
                input_values = berocket_apply_filters('jqrui_slidr_'+$slider.data('display'), input_values, $slider);
                input_values[0] = prefix + input_values[0] + postfix;
                input_values[1] = prefix + input_values[1] + postfix;
                var value_ready = {value:values[0]+'_'+values[1], html:input_values[0]+' - '+input_values[1]};
                value_ready = berocket_apply_filters('jqrui_slidr_link_'+$slider.data('display'), value_ready, values, input_values, $slider, single_data);
                single_data.values = [value_ready];
            }
        }
        return single_data;
    }
    braapf_jqrui_slidr_values_arr_attr = function(values, $element) {
        var attr = $element.data('attr');
        if( Array.isArray(values) && values.length == 2 ) {
            values[0] = attr[values[0]].n;
            values[1] = attr[values[1]].n;
        } else {
            values = ['', ''];
            values[0] = attr[0].n;
            values[1] = attr[attr.length - 1].n;
        }
        return values;
    }
    braapf_jqrui_slidr_values_link_arr_attr = function(value_ready, values, input_values, $slider, single_data) {
        var attr = $slider.data('attr');
        value_ready.value = attr[values[0]].v+'_'+attr[values[1]].v;
        return value_ready;
    }
    $(document).on('braapf_unselect braapf_unselect_all', '.bapf_slidr_jqrui', function(event, data) {
        var $slider = $(this).find('.bapf_slidr_main');
        var min = $slider.data('min');
        var max = $slider.data('max');
        $slider.addClass('bapf_jqrui_blocked');
        $slider.slider('values', [min, max]);
        $slider.removeClass('bapf_jqrui_blocked');
    });
    function braapf_jqrui_slidr_berocket_add_filter() {
        berocket_add_filter('jqrui_slidr_wc_price', braapf_jqrui_slidr_values_wc_price);
        berocket_add_filter('jqrui_slidr_arr_attr', braapf_jqrui_slidr_values_arr_attr);
        berocket_add_filter('jqrui_slidr_arr_attr_price', braapf_jqrui_slidr_values_arr_attr, 10);
        berocket_add_filter('jqrui_slidr_arr_attr_price', braapf_jqrui_slidr_values_wc_price, 20);
        berocket_add_filter('jqrui_slidr_link_arr_attr', braapf_jqrui_slidr_values_link_arr_attr);
        berocket_add_filter('jqrui_slidr_link_arr_attr_price', braapf_jqrui_slidr_values_link_arr_attr);
        berocket_add_filter('grab_single_filter_default', braapf_grab_single_jqrui);
        berocket_add_filter('braapf_init', braapf_init_jqrui_slidr);
        berocket_add_filter('braapf_init_for_parent', braapf_init_jqrui_slidr_for_parent);
    }
    if ( typeof(berocket_add_filter) == 'function' ) {
        braapf_jqrui_slidr_berocket_add_filter();
    } else {
        jQuery(document).on('berocket_hooks_ready', function() {
            braapf_jqrui_slidr_berocket_add_filter();
        });
    }
})(jQuery);
