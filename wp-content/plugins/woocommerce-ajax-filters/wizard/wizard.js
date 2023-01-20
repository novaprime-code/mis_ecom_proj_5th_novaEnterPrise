var BeRocket_wizard_selectors = {product:'', products:'',pagination:'', pagination_next:'', pagination_prev:'',products_count:''};
var BeRocket_wizard_check = function(category_page_url, input_data,execute_function) {
    var $input_data = jQuery('<div>'+input_data+'</div>');
    function end_selectors(status) {
        if( typeof execute_function == 'function' ) {
            execute_function(status);
        }
    }
    function br_array_intersect(array1, array2) {
        var return_array = [];
        array1.forEach(function callback(cur1, i1, arr1) {
            array2.some(function callback(cur2, i2, arr2) {
                if( cur1 == cur2 ) {
                    return_array.push(cur1);
                    return true;
                }
                return false;
            });
        });
        return return_array;
    }
    function br_remove_empty_classes(array) {
        var return_arr = [];
        array.forEach(function( item, i, arr) {
            if( item ) {
                return_arr.push(item);
            }
        });
        return return_arr;
    }
    function br_remove_products_classes(array) {
        var return_arr = [];
        array.forEach(function( item, i, arr) {
            if( item != 'type-product' && item != 'status-publish' && item != 'product_cat-berocketselectors' && item != 'instock'
                && item != 'taxable' && item != 'shipping-taxable' && item != 'purchasable' && item != 'product-type-simple' ) {
                    return_arr.push(item);
                }
        });
        return return_arr;
    }
    function br_find_like_product(array) {
        var return_arr = [];
        array.forEach(function( item, i, arr) {
            if( item.search('product') != -1 ) {
                return_arr.push(item);
            }
        });
        return return_arr;
    }
    var products = ['BeRocketSelectorsTest10', 'BeRocketSelectorsTest11', 'BeRocketSelectorsTest12', 'BeRocketSelectorsTest13', 'BeRocketSelectorsTest14', 
                    'BeRocketSelectorsTest15', 'BeRocketSelectorsTest16', 'BeRocketSelectorsTest17', 'BeRocketSelectorsTest18', 'BeRocketSelectorsTest19',
                    'BeRocketSelectorsTest20', 'BeRocketSelectorsTest21', 'BeRocketSelectorsTest22', 'BeRocketSelectorsTest23', 'BeRocketSelectorsTest24',
                    'BeRocketSelectorsTest25', 'BeRocketSelectorsTest26', 'BeRocketSelectorsTest27'];
    var search_next_product = '';
    products.forEach(function( item, i, arr) {
        if( $input_data.find(':contains("'+item+'")').length > 0 ) {
            search_next_product += ':contains("'+item+'")';
        }
    });
    var $elem = $input_data.find(search_next_product);
    while($elem.length > 1) {
        $elem = $elem.first().find(search_next_product);
    }
    
    var $elem = $elem.find(':contains("BeRocketSelectorsTest")');
    while($elem.length > 1) {
        $elem = $elem.first().find(':contains("BeRocketSelectorsTest")');
    }
    var current_product = '';
    search_next_product = '';
    products.forEach(function( item, i, arr) {
        if( $elem.is(':contains("'+item+'")') ) {
            current_product = item;
        } else {
            if( search_next_product != '' ) {
                search_next_product += ',';
            }
            search_next_product += ':contains("'+item+'")';
        }
    });
    i = 0;
    while( $elem.next().find(search_next_product).length == 0 
           && $elem.next().next().find(search_next_product).length == 0 
           && $elem.next().next().next().find(search_next_product).length == 0
           && i < 40) {
        $elem = $elem.parent();
        i++;
    }
    i = 0;
    var has_product = 0;
    var $has_elem = $elem;
    do {
        if( $has_elem.is(':contains("BeRocketSelectorsTest")')  ) {
            has_product++;
        }
        $has_elem = $has_elem.next();
        i++;
    } while( i < 10 );
    if( has_product < 3 ) {
        search_next_product = ''
        products.some(function( item, i, arr) {
            if( search_next_product != '' ) {
                search_next_product += ',';
            }
            search_next_product += '[href*="'+item.toLowerCase()+'"]'
        });
        $elem = $input_data.find(search_next_product).first();
        if( $elem.length == 0 ) {
            end_selectors(false);
            return false;
        }
        current_product = '';
        search_next_product = '';
        products.forEach(function( item, i, arr) {
            if( $elem.is('[href*="'+item.toLowerCase()+'"]') ) {
                current_product = item;
            } else {
                if( search_next_product != '' ) {
                    search_next_product += ',';
                }
                search_next_product += '[href*="'+item.toLowerCase()+'"]';
            }
        });
        i = 0;
        while($elem.next().find(search_next_product).length == 0
              && $elem.next().next().find(search_next_product).length == 0
              && $elem.next().next().next().find(search_next_product).length == 0
              && i < 40) {
            $elem = $elem.parent();
            i++;
        }
    }
    if( $elem.length == 0 || $elem.next().length == 0 || $elem.next().next().length == 0 ) {
        end_selectors(false);
        return false;
    }
    if( $elem.hasClass('product')) {
        var result_classes = ['product'];
    } else {
        has_product = 1;
        i = 0;
        $has_elem = $elem;
        var result_classes = $elem.attr('class');
        if( typeof(result_classes) == 'undefined' ) {
            result_classes = '';
        }
        result_classes = result_classes.split(/\s+/);
        while( has_product < 3 && i < 10 ) {
            if( $has_elem.is(':contains("BeRocketSelectorsTest")') ) {
                class1 = $has_elem.attr('class');
                if( typeof(class1) == 'undefined' ) {
                    class1 = '';
                }
                class1 = class1.split(/\s+/);
                result_classes = br_array_intersect(result_classes, class1);
                has_product++;
            }
            $has_elem = $has_elem.next();
            i++;
        }
        result_classes_new = br_find_like_product(result_classes);
        if( result_classes_new.length > 0 ) {
            result_classes = result_classes_new;
        }
        result_classes = result_classes.slice(0, 3);
        result_classes = br_remove_empty_classes(result_classes);
    }
    BeRocket_wizard_selectors.product = $elem.prop("tagName").toLowerCase()+'.'+result_classes.join('.');
    var $products = $elem.parent();
    $products2 = $products;
    class1 = $products2.attr('class');
    if( typeof class1 == 'undefined' ) {
        class1 = '';
    }
    class1 = class1.split(/\s+/);
    class1 = br_remove_empty_classes(class1);
    class1 = br_find_like_product(class1);
    i = 0;
    while( class1.length == 0 && i < 2 ) {
        $products2 = $products.parent();
        class1 = $products2.attr('class');
        if( typeof class1 == 'undefined' ) {
            class1 = '';
        }
        class1 = class1.split(/\s+/);
        class1 = br_remove_empty_classes(class1);
        class1 = br_find_like_product(class1);
        i++;
    }
    if( class1.length == 0 ) {
        $products2 = $products;
        class1 = $products2.attr('class');
        if( typeof class1 == 'undefined' ) {
            class1 = '';
        }
        class1 = class1.split(/\s+/);
        class1 = br_remove_empty_classes(class1);
        i = 0;
        while( class1.length == 0 && i < 4 ) {
            $products2 = $products.parent();
            class1 = $products2.attr('class');
            if( typeof class1 == 'undefined' ) {
                class1 = '';
            }
            class1 = class1.split(/\s+/);
            class1 = br_remove_empty_classes(class1);
            i++;
        }
    }
    i = 0;
    do {
        i++;
        var checkClass = class1.slice(0, i);
        checkClass = $products2.prop("tagName").toLowerCase()+'.'+checkClass.join('.');
    } while( $input_data.find(checkClass).length != 1 && i < 5 );
    BeRocket_wizard_selectors.products = checkClass;
                                                                                                $products2.css('border', '3px solid Blue');
    var pagination = ['.woocommerce-pagination', '.pagination', 'ul.page-numbers', "[class*='pagination']", "nav[class*='page']", "nav[class*='pagi']", 
                    'nav:contains("2")', "[class*='pagi']", "[class*='page']"];
    var is_pagination = false;
    var class_item = '';
    pagination.some(function( item, i, arr) {
        $pagination = $input_data.find(item);
        if( $pagination.length > 0 ) {
            class_item = item;
            is_pagination = true;
            return true;
        }
        return false;
    });
    if( ! is_pagination ) {
        $pagination = $products2.nextAll(':contains("2")');
        if( $pagination.length == 0 ) {
            $pagination = $products2.parent().nextAll(':contains("2")');
        }
        if( $pagination.length > 0 ) {
            is_pagination = true;
        }
    }
    if( is_pagination ) {
        $pagination = $pagination.first();
        checkClass = $pagination.attr('class');
        if( typeof checkClass == 'undefined' ) {
            checkClass = '';
        }
        checkClass = checkClass.split(/\s+/);
        checkClass = br_remove_empty_classes(checkClass);
        if( checkClass.length > 0 ) {
            checkClass = $pagination.prop("tagName").toLowerCase()+'.'+checkClass.join('.');
        } else if( $pagination.attr('id') ) {
            checkClass = $pagination.prop("tagName").toLowerCase()+'#'+$pagination.attr('id');
        } else {
            checkClass = $pagination.prop("tagName").toLowerCase();
        }
        $pagination = $input_data.find(checkClass).first();
        if( $input_data.find(checkClass).length > 0 ) {
            BeRocket_wizard_selectors.pagination = checkClass;
            $pagination.css('border', '3px solid Green');
        } else {
            is_pagination = false;
        }
    }
    if( $input_data.find('.woocommerce-result-count').length ) {
        BeRocket_wizard_selectors.products_count = '.woocommerce-result-count';
    } else {
        var products_count = ['.woocommerce-result-count', ':contains("Showing 1–3 of 18 results")', ':contains("3 results"), :contains("3results")', 
                              ':contains("3 products"), :contains("3products")', ':contains("18 Product"), :contains("18Product")'];
        var is_products_count = false;
        var class_products_count = '';
        products_count.some(function( item, i, arr) {
            $products_count = $input_data.find(item);
            if( $products_count.length > 0 ) {
                class_products_count = item;
                is_products_count = true;
                return true;
            }
            return false;
        });
        if( is_products_count ) {
            while($products_count.length > 1) {
                $products_count = $products_count.find(class_products_count);
            }
            if( $products_count.length > 0 ) {
                checkClass = $products_count.attr('class');
                if( typeof(checkClass) == 'undefined' ) {
                    checkClass = '';
                }
                if( typeof checkClass == 'string' ) {
                    checkClass = checkClass.split(/\s+/);
                    checkClass = br_remove_empty_classes(checkClass);
                    checkClass = $products_count.prop("tagName").toLowerCase()+'.'+checkClass.join('.');
                    BeRocket_wizard_selectors.products_count = checkClass;
                }
            }
        }
    }
    if( is_pagination ) {
        $next = $pagination.find("[class*='next']");
        if( $next.length == 0 ) {
            do {
                $second_page = $pagination.find(':contains("2")');
            } while(! $second_page.is('a') && $second_page.length > 0);
            if( $second_page.length > 0 ) {
                var second_href = $second_page.attr('href');
                $next = $pagination.find('[href*="'+second_href+'"]:not(:contains("2"))');
            }
        }
        if( $next.length == 0 ) {
            var next_page = ['[href*="/page/2"]', '[href*="paged=2"]'];
            next_page.some(function( item, i, arr) {
                $next = $pagination.find(item).last();
                if( $next.length > 0 ) {
                    return true;
                }
                return false;
            });
        }
        if( $next.length > 0 ) {
            checkClass = $next.attr('class');
            if( typeof(checkClass) == 'undefined' ) {
                checkClass = '';
            }
            if( checkClass == '' || typeof checkClass != 'string' ) {
                checkClass = $next.prop("tagName").toLowerCase()+':contains("'+$next.text()+'")';
            } else {
                checkClass = checkClass.split(/\s+/);
                checkClass = br_remove_empty_classes(checkClass);
                checkClass = $next.prop("tagName").toLowerCase()+'.'+checkClass.join('.');
            }
            BeRocket_wizard_selectors.pagination_next = checkClass;
            $next.css('border', '3px solid Yellow');
        }
        var current_url = category_page_url;
        if( current_url.search(/\?/) == -1 ) {
            current_url = current_url+'?paged=6';
        } else {
            current_url = current_url+'&paged=6';
        }
        jQuery.get(current_url, function(data) {
            if( BeRocket_autoselector_stop ) {
                berocket_wizard_autoselector(true);
                return;
            }
            var $data = jQuery('<div>'+data+'</div>');
            var $pagination6 = $data.find(BeRocket_wizard_selectors.pagination);
            if( $pagination6.length > 0 ) {
                $prev = $pagination6.find("[class*='prev']");
                if( $prev.length == 0 ) {
                    $second_page = $pagination6
                    do {
                        $second_page = $second_page.find(':contains("5")').first();
                    } while(! $second_page.is('a') && $second_page.length > 0);
                    if( $second_page.length > 0 ) {
                        var second_href = $second_page.attr('href');
                        $prev = $pagination6.find('[href*="'+second_href+'"]:not(:contains("5"))');
                    }
                }
                if( $prev.length == 0 ) {
                    var prev_page = ['[href*="/page/5"]', '[href*="paged=5"]'];
                    prev_page.some(function( item, i, arr) {
                        $prev = $pagination6.find(item).last();
                        if( $prev.length > 0 ) {
                            return true;
                        }
                        return false;
                    });
                }
                if( $prev.length > 0 ) {
                    checkClass = $prev.attr('class');
                    if( typeof(checkClass) == 'undefined' ) {
                        checkClass = '';
                    }
                    if( checkClass == '' || typeof checkClass != 'string' ) {
                        checkClass = $prev.prop("tagName").toLowerCase()+':contains("'+$prev.text()+'")';
                    } else {
                        checkClass = checkClass.split(/\s+/);
                        checkClass = br_remove_empty_classes(checkClass);
                        checkClass = $prev.prop("tagName").toLowerCase()+'.'+checkClass.join('.');
                    }
                    BeRocket_wizard_selectors.pagination_prev = checkClass;
                    $prev.css('border', '3px solid Orange');
                }
            }
            end_selectors(true);
        }).error(function() {end_selectors(true);});
    } else {
        end_selectors(true);
    }
}
var BeRocket_autoselector_stop = false;
var BeRocket_current_autoselector_block = jQuery('<div></div>');
jQuery(document).on('click', '.berocket_autoselector', function(event) {
    event.preventDefault();
    BeRocket_autoselector_stop = false;
    BeRocket_current_autoselector_block = jQuery(this).parents('.berocket_wizard_autoselectors');
    BeRocket_init_autoselectors();
    jQuery.get(berocket_wizard_autoselect.ajaxurl, {action:"berocket_wizard_selector_start"}, function(url) {
        if( BeRocket_autoselector_stop ) {
            berocket_wizard_autoselector(true);
            return;
        }
        BeRocket_set_autoselector_load_position(45, berocket_wizard_autoselect.getting_selectors, 60);
        jQuery.get(url, function(data) {
            if( BeRocket_autoselector_stop ) {
                berocket_wizard_autoselector(true);
                return;
            }
            BeRocket_set_autoselector_load_position(60, berocket_wizard_autoselect.getting_selectors, 75);
            BeRocket_wizard_check(url, data, berocket_wizard_autoselector);
        }).error(function() {berocket_wizard_autoselector_end(berocket_wizard_autoselector_end_error);});
    }).error(function() {berocket_wizard_autoselector_end(berocket_wizard_autoselector_end_error);});
});
jQuery(document).on('click', '.berocket_autoselector_seo', function(event) {
    event.preventDefault();
    jQuery(this).removeClass('berocket_autoselector_seo').addClass('berocket_autoselector');
    var seoplugins = jQuery(this).data('seoplugins');
    if( confirm("It seems that you have some SEO plugin active. Please disable it before continue.\n\nPlugin(s): "+seoplugins+"\n\nIf it is not SEO plugin, then ignore this message.\nContinue anyway?") ) {
        jQuery(this).trigger('click');
    }
});
jQuery(document).on('click', '.berocket_autoselector_stop', function(event) {
    event.preventDefault();
    BeRocket_autoselector_stop = true;
    if( jQuery('.berocket_selectors_was_runned').length ) {
        jQuery('.berocket_selectors_was_runned').remove();
        berocket_wizard_autoselector(false);
    }
});
function BeRocket_set_autoselector_load_position(position, position_name, next_position) {
    if( jQuery('.berocket_wizard_autoselectors ol li .fa-spin').length ) {
        jQuery('.berocket_wizard_autoselectors ol li .fa-spin').removeClass('fa-spinner').addClass('fa-check').removeClass('fa-spin');
    }
    if( position_name ) {
        jQuery('.berocket_wizard_autoselectors ol li span:contains('+position_name+')').parent().find('.fa').addClass('fa-spinner').addClass('fa-spin');
    }
    var functions = BeRocket_current_autoselector_block.data('functions');
    if( typeof(functions) != 'undefined' && functions.percent != '' && typeof window[functions.percent] == 'function' ) {
        window[functions.percent](position, position_name, next_position);
    }
    var $block = jQuery(BeRocket_current_autoselector_block);
    $block.find('.berocket_autoselector_load .berocket_line').finish().css('width', position+'%');
    if( typeof position_name != 'undefined' ) {
        $block.find('.berocket_autoselector_load .berocket_autoselector_action').text(position_name);
    }
    if( typeof next_position != 'undefined' ) {
        $block.find('.berocket_autoselector_load .berocket_line').animate({width:next_position+'%'}, 30000);
    }
}
function BeRocket_init_autoselectors() {
    var $block = jQuery(BeRocket_current_autoselector_block);
    jQuery('.berocket_wizard_autoselectors .berocket_autoselector').prop('disabled', true);
    $block.find('ol li .fa').removeClass('fa-spin').removeClass('fa-check').removeClass('fa-spinner').removeClass('fa-times');
    $block.find('ol').show();
    BeRocket_set_autoselector_load_position(0, berocket_wizard_autoselect.creating_products, 45);
    $block.find('.berocket_autoselector_load .berocket_line').css('background-color', '');
    $block.find('.berocket_autoselector_load').show();
    $block.find('.berocket_autoselect_spin').show();
    $block.find('.berocket_autoselect_ready').hide();
    $block.find('.berocket_autoselect_error').hide();
}

function berocket_wizard_set_val_to_input(input_selector, input_val) {
    if( input_selector != "" && jQuery(input_selector).length ) {
        jQuery(input_selector).val(input_val);
    }
}
function berocket_wizard_autoselector(status) {
    if( status ) {
        //SELECTORS READY TO USE
        var inputs = BeRocket_current_autoselector_block.data('inputs');
        berocket_wizard_set_val_to_input(inputs.products, BeRocket_wizard_selectors.products);
        berocket_wizard_set_val_to_input(inputs.product, BeRocket_wizard_selectors.product);
        berocket_wizard_set_val_to_input(inputs.pagination, BeRocket_wizard_selectors.pagination);
        berocket_wizard_set_val_to_input(inputs.next_page, BeRocket_wizard_selectors.pagination_next);
        berocket_wizard_set_val_to_input(inputs.prev_page, BeRocket_wizard_selectors.pagination_prev);
        berocket_wizard_set_val_to_input(inputs.result_count, BeRocket_wizard_selectors.products_count);
        BeRocket_set_autoselector_load_position(75, berocket_wizard_autoselect.removing_products, 100);
        berocket_wizard_autoselector_end(berocket_wizard_autoselector_end_success);
    } else {
        BeRocket_set_autoselector_load_position(75);
        berocket_wizard_autoselector_end(berocket_wizard_autoselector_end_error);
    }
}
function berocket_wizard_autoselector_end(function_after_end) {
    jQuery.get(berocket_wizard_autoselect.ajaxurl, {action:"berocket_wizard_selector_end"}, function() {
        function_after_end();
    }).error(function(){
        jQuery.get(berocket_wizard_autoselect.ajaxurl, {action:"berocket_wizard_selector_end"}, function() {
            function_after_end();
        }).error(function(){
            berocket_wizard_autoselector_end_error();
        });
    });
}
function berocket_wizard_autoselector_end_success() {
    var functions = BeRocket_current_autoselector_block.data('functions');
    if( functions.any != '' && typeof window[functions.any] == 'function' ) {
        window[functions.any]();
    }
    if( functions.success != '' && typeof window[functions.success] == 'function' ) {
        window[functions.success]();
    }
    jQuery('.berocket_autoselector').prop('disabled', false);
    BeRocket_set_autoselector_load_position(100, '');
    jQuery('.berocket_autoselect_spin').hide();
    jQuery('.berocket_autoselect_ready').show();
}
function berocket_wizard_autoselector_end_error() {
    var functions = BeRocket_current_autoselector_block.data('functions');
    if( typeof(functions) != 'undefined' && functions.any != '' && typeof window[functions.any] == 'function' ) {
        window[functions.any]();
    }
    if( typeof(functions) != 'undefined' && functions.error != '' && typeof window[functions.error] == 'function' ) {
        window[functions.error]();
    }
    jQuery('.berocket_autoselector').prop('disabled', false);
    BeRocket_set_autoselector_load_position(100);
    jQuery('.berocket_autoselector_load .berocket_autoselector_action').text('Error: '+jQuery('.berocket_autoselector_load .berocket_autoselector_action').text());
    jQuery('.berocket_autoselect_spin').hide();
    jQuery('.berocket_autoselector_load .berocket_line').css('background-color', '#bb3333');
    jQuery('.berocket_autoselect_error').show();
    if( jQuery('.berocket_wizard_autoselectors ol li .fa-check').length ) {
        jQuery('.berocket_wizard_autoselectors ol li .fa-check').last().removeClass('fa-spinner').addClass('fa-times').removeClass('fa-spin').removeClass('fa-check');
    }
}
