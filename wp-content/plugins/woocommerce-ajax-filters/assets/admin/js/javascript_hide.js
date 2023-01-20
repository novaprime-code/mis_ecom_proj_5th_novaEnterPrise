var berocket_show_element_hooked_data = [];
function berocket_show_element_callback(show, element, data_string, init) {
    if( show == "1" ) {
        jQuery(element).show();
        jQuery(element).find('input, select, textarea').prop('disabled', false);
    } else {
        jQuery(element).hide();
        jQuery(element).find('input, select, textarea').prop('disabled', true);
    }
    jQuery(document).trigger('berocket_show_element_callback', show, element, data_string, init);
}
var braapf_test_interval = false;
var braapf_test_recount_it = 0;
function berocket_show_element(element, data_string, init, callback) {
    braapf_test_recount_it++;
    if( braapf_test_interval !== false ) {
        clearTimeout(braapf_test_interval);
    }
    braapf_test_interval = setTimeout(function() {
        braapf_test_recount_it = 0;
        braapf_test_interval = false;
    }, 500);
    if( typeof(init) == 'undefined' ) {
        init = true;
    }
    if( typeof(callback) != 'function' ) {
        callback = berocket_show_element_callback;
    }
    $element = jQuery(element);
    if( !$element.length ) {
        return false;
    }
    berocket_show_element_hooked_data = [];
    jQuery(element).data('show_element_data', data_string);
    var show = berocket_check_data(element, data_string);
    callback(show, element, data_string, init);
    if( init ) {
        function onlyUnique(value, index, self) { 
            return self.indexOf(value) === index;
        }
        berocket_show_element_hooked_data.filter( onlyUnique );
        jQuery.each(berocket_show_element_hooked_data, function(i, selector) {
            jQuery(document).on('change', selector, function() {
                berocket_show_element(element, data_string, false, callback);
            });
        });
    }
    return show;
}
function berocket_check_data(element, data_string) {
    var regexp_open = /\([#\w\-\.\s\|\&=!><"'{}\[\]]+\)/g;
    while(regexp_open.exec(data_string) != null) {
        data_string = data_string.replace(regexp_open, function(str) {
            str = str.substr(1, str.length - 2);
            str = berocket_check_data(element, str);
            return str;
        });
    }
    if( data_string.search(/\|\|/) != -1 ) {
        var regexp_or = /[#\w\-\.\s\&=!><"'{}\[\]]+/g;
        data_string = data_string.replace(regexp_or, function(str) {
            str = berocket_check_data(element, str);
            return str;
        });
        var splited_arr = data_string.split('||');
        data_string = "0";
        jQuery.each(splited_arr, function(key, value) {
            value = value.trim()
            if( value == "1" ) {
                data_string = "1";
                return false;
            }
        });
    }
    if( data_string.search(/&&/) != -1 ) {
        var regexp_and = /[#\w\-\.\s=!><"'{}\[\]]+/g;
        data_string = data_string.replace(regexp_and, function(str) {
            str = berocket_check_data(element, str);
            return str;
        });
        var splited_arr = data_string.split('&&');
        data_string = "1";
        jQuery.each(splited_arr, function(key, value) {
            value = value.trim()
            if( value == "0" ) {
                data_string = "0";
                return false;
            }
        });
    }
    var compares = ['==', '>=', '<=', '>', '<', '!='];
    var compare = false;
    var i = -1;
    do {
        i++;
    } while(i < compares.length && data_string.search(compares[i]) == -1 );
    if( i < compares.length ) {
        compare = compares[i];
    }
    if( compare === false ) {
        var splited_arr = [data_string];
    } else {
        var splited_arr = data_string.split(compare);
    }
    
    if( data_string == "0" || data_string == "1" ) {
        return data_string;
    }
    
    jQuery.each(splited_arr, function(key, value) {
        value = value.trim();
        if( value.substr(0,1) == '{' && value.substr(-1,1) == '}' ) {
            value = value.substr(1, value.length - 2);
            value = berocket_get_element_by_selector(element, value);
        } else if( (value.substr(0,1) == '"' && value.substr(-1,1) == '"') || (value.substr(0,1) == "'" && value.substr(-1,1) == "'") ) {
            value = value.substr(1, value.length - 2);
        } else if( value.substr(0,1) == '!' && value.substr(-1,1) == '!' ) {
            value = value.substr(1, value.length - 2);
            if( typeof(window[value]) == 'function' ) {
                value = window[value](splited_arr, data_string);
            } else {
                value = false;
            }
        } else if(value.toLowerCase() == 'false') {
            value = false;
        } else if(value.toLowerCase() == 'true') {
            value = true;
        }
        splited_arr[key] = value;
    });
    while(splited_arr.length < 2) {
        splited_arr.push(true);
    }
    data_string = berocket_check_two_values(splited_arr[0], splited_arr[1], compare);
    
    return data_string;
}
function berocket_get_element_by_selector(element, selector) {
    var $element = jQuery(selector);
    var result = [];
    if( $element.length == 0 ) {
        result.push(false);
    } else {
        berocket_show_element_hooked_data.push(selector);
        $element.each( function() {
            if( jQuery(this).is('[type=checkbox],[type=radio]') ) {
                if( jQuery(this).prop('checked') ) {
                    result.push(jQuery(this).val());
                }
            } else {
                result.push(jQuery(this).val());
            }
        });
    }
    return result;
}
function berocket_check_two_values(value1, value2, check) {
    var to_num = false;
    if( check == '>' || check == '<' || check == '<=' || check == '>=' ) {
        to_num = true;
    }
    if( !Array.isArray(value1) ) {
        value1 = [value1];
    }
    if( !Array.isArray(value2) ) {
        value2 = [value2];
    }
    if( !value1.length ) {
        value1 = [false];
    }
    if( !value2.length ) {
        value2 = [false];
    }
    if( check == '!=' ) {
        var isittrue = "1";
    } else {
        var isittrue = "0";
    }
    jQuery.each(value1, function (key1, el1) {
        jQuery.each(value2, function (key2, el2) {
            if( to_num ) {
                if( 
                    (check == '>'  && parseFloat(el1) >  parseFloat(el2))
                 || (check == '<'  && parseFloat(el1) <  parseFloat(el2))
                 || (check == '>=' && parseFloat(el1) >= parseFloat(el2))
                 || (check == '<=' && parseFloat(el1) <= parseFloat(el2))
                ) {
                    isittrue = "1";
                    return false;
                }
            } else if( check == '!=' ) {
                if( el1 == el2 && el1 !== "0" && el2 !== "0" ) {
                    isittrue = "0";
                    return false;
                }
            } else {
                if( el1 == el2 && el1 !== "0" && el2 !== "0" ) {
                    isittrue = "1";
                    return false;
                }
            }
        });
        if( (check == '!=' && isittrue == "0") || (check != '!=' && isittrue == "1") ) {
            return false;
        }
    });
    return isittrue;
}
/* How to use berocket_show_element function to hide element
 * Parameters:
 * @ selector - Element that must be visible only with some condition
 * @ condition - string with condition that describe when it must be visible
 * 
 * Condition format:
 * EXAMPLE: "({input[name=checkbox1]} == true || {input[name=checkbox2]} == true) && ({input[name=text1]} == 'show' || {select[name=select1]} == 'show')"
 * 
 * {selector} - get value(uses jQuery.val() function) from element(uses jQuery(selector) to get elements), can be used with multiple elements
 * !function_name! - call global function and use value that it is return for compare. Can return: true, false, string, [string, string]
 * true - if element exist and checked(for radio/checkbox) and has value that is not empty string
 * false - if element do not exist or not checked(for radio/checkbox) or has value as empty string
 * "VALUE" or 'VALUE' - search element with value VALUE that is checked(for radio/checkbox)
 * 
 * operators:
 * == - TRUE if at least one element value is equal to one value from another element. work with all type of content
 * != - TRU if all elements value are not equal to all value from another elements. work with all type of content
 * >, >=, <, <= - compare elements value with another elements value(uses function parseFloat() to convert values). Do not work with true and false
 * 
 * 
 * USE EXAMPLE
    berocket_show_element('.test2', '{.test1 input} == true');
    berocket_show_element('.test3', '{.test1 input} == true && {.test2 input} == "2"');
    berocket_show_element('.test4', '{.test1 input} == true && ({.test2 input} == "3" || {.test2 input} == "1") && ({.test3 input} == false || {.test2 input} != "2")');
 */
