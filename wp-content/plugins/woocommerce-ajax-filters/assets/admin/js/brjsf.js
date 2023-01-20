var brjsf, brjsf_reload, brjsf_accordion;
var brjsf_care_mode = false;
(function ($){
    /* 
     * Add brjsf class to input(radio,checkbox) and select to convert this to styling block.
     * To recreate block use brjsf(block) function with block id or class.
     * To create styling block from any input or select use brjsf(block) function with block class or id.
     * To create accordion use bluck with <h3> and <div> inside and call brjsf_accordion(block) function with block id or class.
     * */
    brjsf = function ( block ) {
        if( ! brjsf_care_mode ) {
            if ( typeof(block) != 'undefined' ) {
                brjsf_reload(block);
            }
            $('.brjsf').each( function( i, o ) {
                if ( $(o).is('[type=checkbox]') || $(o).is('[type=radio]') ) {
                    $(o).after('<span class="brjsf_toggle"><span class="brjsf_back"></span><span class="brjsf_toggler"></span></span>');
                    $(o).removeClass('brjsf').addClass('brjsf_ready');
                } else if ( $(o).is('select') ) {
                    var width = 200;
                    if ( $(o).data('width') ) {
                        width = $(o).data('width');
                    } 
                    var html = '<div class="brjsf_select"><span class="brjsf_text">'+$(o).find('option:selected').text()+'</span> <i class="fa fa-caret-down"></i><ul>';
                    if( $(o).is('.brjsf_search') ) {
                        html += '<li class="brjsf_select_search"><input type="text" class="brjsf_select_search_input"></li>';
                    }
                    $(o).find('option').each( function ( i_option, o_option ) {
                        html += '<li data-value="'+$(o_option).val()+'">'+$(o_option).text()+'</li>';
                    });
                    html += '</ul></div>';
                    $(o).after( html );
                    $(o).removeClass('brjsf').addClass('brjsf_ready');
                }
            });
        }
    };
    function brjsf_reload ( input ) {
        if( ! brjsf_care_mode ) {
            if ( $(input).is('.brjsf_ready') ) {
                $(input).removeClass('brjsf_ready').addClass('brjsf').next('.brjsf_toggle, .brjsf_select').remove();
            } else if ( !$(input).is('.brjsf') ) {
                $(input).addClass('brjsf');
            }
        }
    }
    if( ! brjsf_care_mode ) {
        $(document).on( 'click', '.brjsf_select', function (event) {
            if( ! jQuery(event.target).is('.brjsf_select_search, .brjsf_select_search_input') ) {
                event.preventDefault();
                if ( $(this).is('.brjsf_show') ) {
                    $(this).removeClass('brjsf_show');
                    $(this).find('.fa').removeClass('fa-caret-up').addClass('fa-caret-down');
                } else {
                    $('.brjsf_show').removeClass('brjsf_show').find('.fa').removeClass('fa-caret-up').addClass('fa-caret-down');
                    $(this).addClass('brjsf_show');
                    $(this).find('.fa').removeClass('fa-caret-down').addClass('fa-caret-up');
                }
            }
        });
        $(document).on( 'click', '.brjsf_toggle', function(event) {
            if ( $(this).parents('label').length > 0 ) {
                event.preventDefault();
            }
            $(this).prev().click();
        });
        $(document).on( 'click', '.brjsf_select ul li', function(event) {
            if( ! jQuery(this).is('.brjsf_select_search, .brjsf_select_search_input') ) {
                var $select = $(this).parents('.brjsf_select');
                $select.find('span.brjsf_text').text($(this).text());
                $select.prev().val($(this).data('value')).trigger('change');
            }
        });
        $(document).on( 'mousedown', '.brjsf_select ul li, .brjsf_select', function(event) {
            if( ! jQuery(event.target).is('.brjsf_select_search, .brjsf_select_search_input') ) {
                event.preventDefault();
            }
            event.stopPropagation();
        });
        $(document).on( 'mousedown', function(event) {
            $('.brjsf_show').removeClass('brjsf_show').find('.fa').removeClass('fa-caret-up').addClass('fa-caret-down');
        });
        $(document).on('keyup', '.brjsf_select_search_input', function(event) {
            var $parent = $(this).parents('ul').first();
            var value = $(this).val();
            value = value.replace(/\s+/g, '');
            if( value.length >=1 ) {
                $parent.find('li:not(.brjsf_select_search)').hide();
                $parent.find('li:not(.brjsf_select_search):contains("'+value+'")').show();
            } else {
                $parent.find('li:not(.brjsf_select_search)').show();
            }
        });
    }
    brjsf_accordion = function ( input ) {
            $(input).addClass('brjsf_accord');
            $(input).children('h3').children('.fa').remove();
            $(input).children('h3').addClass('brjsf_accord_head').prepend('<i class="fa fa-caret-right"></i>');
            $(input).children('div').addClass('brjsf_accord_block').hide();
            $(input).filter('.brjsf_accord_show').children('div').show();
    }
    $(document).on( 'click', '.brjsf_accord .brjsf_accord_head', function (event) {
        if ( $(this).parent('.brjsf_accord').is('.brjsf_accord_show') ) {
            $(this).find('.fa').removeClass('fa-caret-down').addClass('fa-caret-right');
            $(this).parent('.brjsf_accord').children('.brjsf_accord_block').css('overflow', 'hidden').slideUp( 400, function() { $(this).parent('.brjsf_accord').removeClass('brjsf_accord_show'); });
        } else {
            $(this).find('.fa').removeClass('fa-caret-right').addClass('fa-caret-down');
            $(this).parent('.brjsf_accord').addClass('brjsf_accord_show');
            $(this).parent('.brjsf_accord').children('.brjsf_accord_block').slideDown( 400, function() { $(this).parent('.brjsf_accord').children('.brjsf_accord_block').css('overflow', 'visible'); } );
        }
    });
    $(document).ready( function () {
        brjsf();
    });
})(jQuery);
