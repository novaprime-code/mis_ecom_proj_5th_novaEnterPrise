;
var br_saved_timeout;
var br_init_colorpick;
var br_savin_ajax = false;
var br_something_changed = false;
(function ($){
    $(document).ready( function () {
        $(window).on('beforeunload', function() {
            if( br_something_changed ) {
                return 'Something changed and not saved';
            }
        });
        setTimeout(function() {
            $('.br_framework_submit_form *').on('change', function() {
                br_something_changed = true;
            });
        }, 250);
        $(document).on('submit', '.br_framework_submit_form', function(event) {
            event.preventDefault();
            if( !br_savin_ajax ) {
                var br_reload_page = $(this).is('.br_reload_form');
                br_savin_ajax = true;
                var form_data = $(this).serialize();
                var plugin_name = $(this).data('plugin');
                var url = $(this).attr('action');
                clearTimeout(br_saved_timeout);
                destroy_br_saved();
                $('body').append('<span class="br_saved br_saving"><i class="fa fa-refresh fa-spin"></i></span>');
                $.post(url, form_data, function (data) {
                    if($('.br_saved').length > 0) {
                        $('.br_saved').removeClass('br_saving').find('.fa').removeClass('fa-spin').removeClass('fa-refresh').addClass('fa-check');
                    } else {
                        $('body').append('<span class="br_saved"><i class="fa fa-check"></i></span>');
                    }
                    br_saved_timeout = setTimeout( function(){destroy_br_saved();}, 2000 );
                    br_savin_ajax = false;
                    br_something_changed = false;
                    if( br_reload_page ) {
                        location.reload();
                    }
                }).fail(function(data) {
                    if($('.br_saved').length > 0) {
                        $('.br_saved').removeClass('br_saving').addClass('br_not_saved').find('.fa').removeClass('fa-spin').removeClass('fa-refresh').addClass('fa-times');
                    } else {
                        $('body').append('<span class="br_saved br_not_saved"><i class="fa fa-times"></i></span>');
                    }
                    br_saved_timeout = setTimeout( function(){destroy_br_saved();}, 2000 );
                    br_savin_ajax = false;
                });
            }
        });

        function destroy_br_saved() {
            $('.br_saved').addClass('br_saved_remove');
            var $get = $('.br_saved');
            setTimeout( function(){$get.remove();}, 200 );
        }

        $('.br_framework_settings ul.side a').click(function(event) {
            var block = $(this).data('block');
            if( block != 'redirect_link' ) {
                event.preventDefault();
                $(this).trigger('brlinktab_open');
                $('.br_framework_settings ul.side a.active').removeClass('active');
                $('.nav-block-active').removeClass('nav-block-active');
                $(this).addClass('active');
                $('.'+$(this).data('block')+'-block').addClass('nav-block-active');
                $('.br_framework_settings .content .title').html( $(this).html() );
                window.history.replaceState(null, null, $(this).attr('href'));
                $(this).trigger('brlinktab_opened');
                $('.'+$(this).data('block')+'-block').trigger('brtab_opened');
            }
            berocket_save_button_side();
        });

        $(window).on('keydown', function(event) {
            if (event.ctrlKey || event.metaKey) {
                switch (String.fromCharCode(event.which).toLowerCase()) {
                case 's':
                    event.preventDefault();
                    $('.br_framework_submit_form').submit();
                    break;
                }
            }
        });
        br_init_colorpick = function() {
            $('.br_framework_settings .br_colorpicker').each(function (i,o){
                $(o).css('backgroundColor', $(o).data('color'));
                $(o).colpick({
                    layout: 'hex',
                    submit: 0,
                    color: $(o).data('color'),
                    onChange: function(hsb,hex,rgb,el,bySetColor) {
                        if( hex.charAt(0) != '#' ) {
                            hex = '#'+hex;
                        }
                        $(el).css('backgroundColor', hex).parents('.berocket_color').first().find('.br_colorpicker_value').val(hex).trigger('change');
                    }
                })
            });
        }
        br_init_colorpick();
        $(document).on('click', '.br_framework_settings .br_colorpicker_default', function (event) {
            event.preventDefault();
            var $color = $(this).parents('.berocket_color').first();
            var data = $color.find('.br_colorpicker').data('default');
            $color.find('.br_colorpicker').css('backgroundColor', data).colpickSetColor(data);
            $color.find('.br_colorpicker_value').val(data).trigger('change');
        });
        
        $(document).on('click', '.br_framework_settings .berocket_upload_image', function(e) {
            e.preventDefault();
            $p = $(this);
            var custom_uploader = wp.media({
                title: 'Select custom Icon',
                button: {
                    text: 'Set Icon'
                },
                multiple: false 
            }).on('select', function() {
                var attachment = custom_uploader.state().get('selection').first().toJSON();
                $p.prevAll(".berocket_image_value").val(attachment.url).trigger('change');
				if ( attachment.url ) {
					$p.nextAll(".berocket_remove_image").show();
					$p.prevAll(".berocket_selected_image").html('<image src="'+attachment.url+'" alt="">').show();
					var value_size = $p.prevAll(".berocket_image_value_size");
					if(value_size.length == 0) {
						value_size = $p.nextAll(".berocket_image_value_size");
					}
					if(value_size.length > 0 && attachment.width && attachment.height) {
						var value_size_line = attachment.width.toString()+'*'+attachment.height;
						value_size.val(value_size_line).trigger('change');
					}
				} else {
					$p.nextAll(".berocket_remove_image").hide();
					$p.prevAll(".berocket_selected_image").html('').hide();
				}
            }).open();
        });
        $(document).on('click', '.br_framework_settings .berocket_remove_image',function(event) {
            event.preventDefault();
            $(this).prevAll(".berocket_selected_image").html("").hide();
            $(this).prevAll(".berocket_image_value").val("").trigger('change');
			$(this).hide();
        });
        var berocket_fa_select_for = $('.berocket_fa_dark');
        $(document).on('click', '.berocket_select_fontawesome .berocket_select_fa',function(event) {
            event.preventDefault();
            berocket_fa_select_for = $(this);
            $('.berocket_fa_dark').not(':first').remove();
            var $html = $('<div class="berocket_select_fontawesome"></div>');
            $html.append($('.berocket_fa_dark'));
            var $html2 = $('<div class="br_framework_settings br_fontawesome_body"></div>');
            $html2.append($html);
            $('body').children('.br_fontawesome_body').remove();
            $('body').append($html2);
            $('.berocket_fa_dark').show();
        });
        $(document).on('mouseenter', '.berocket_select_fontawesome .berocket_fa_hover', function() {
            var window_width = $(window).width();
            window_width = window_width / 2;
            var $this = $(this).parents('.berocket_fa_icon');
            if( $this.offset().left < window_width ) {
                $this.find('.berocket_fa_preview').css({left: '0', right: 'initial'});
                $this.find('.berocket_fa_preview span').appendTo($this.find('.berocket_fa_preview'));
            } else {
                $this.find('.berocket_fa_preview').css({left: 'initial', right: '0'});
                $this.find('.berocket_fa_preview .fa').appendTo($this.find('.berocket_fa_preview'));
            }
        });
        $(document).on('click', '.berocket_select_fontawesome .berocket_fa_hover',function(event) {
            event.preventDefault();
            var value = $(this).parents('.berocket_fa_icon').first().find('.berocket_fa_preview span').text();
            $(berocket_fa_select_for).parents('.berocket_select_fontawesome').find('.berocket_fa_value').val(value).trigger('change');
            $('.berocket_fa_dark').hide();
			if ( value ) {
				$(berocket_fa_select_for).parents('.berocket_select_fontawesome').find('.berocket_selected_fa').html('<i class="fa '+value+'"></i>').show();
				$(berocket_fa_select_for).parents('.berocket_select_fontawesome').find(".berocket_remove_image").show();
			} else {
				$(berocket_fa_select_for).parents('.berocket_select_fontawesome').find('.berocket_selected_fa').html('').hide();
				$(berocket_fa_select_for).parents('.berocket_select_fontawesome').find(".berocket_remove_image").hide();
			}
        });
        $(document).on('click', '.berocket_select_fontawesome .berocket_remove_fa',function(event) {
            event.preventDefault();
            $(this).parents('.berocket_select_fontawesome').find('.berocket_selected_fa').html('');
            $(this).parents('.berocket_select_fontawesome').find('.berocket_fa_value').val('').trigger('change');
        });
        $(document).on('keyup', '.berocket_select_fontawesome .berocket_fa_search', function() {
            var $parent = $(this).parents('.berocket_select_fontawesome').first();
            var value = $(this).val();
            value = value.replace(/\s+/g, '');
            value = value.toLowerCase();
            if( value.length >=1 ) {
                $parent.find('.berocket_fa_icon').hide();
                $parent.find('.berocket_fa_preview span:contains("'+value+'")').parents('.berocket_fa_icon').show();
            } else {
                $parent.find('.berocket_fa_icon').show();
            }
        });
        $(document).on('click', '.berocket_select_fontawesome .berocket_fa_dark',function(event) {
            event.preventDefault();
            $(this).hide();
        });
        $(document).on('click', '.berocket_select_fontawesome .berocket_fa_dark .berocket_fa_close',function(event) {
            event.preventDefault();
            $(this).parents('.berocket_fa_dark').hide();
        });
        $(document).on('click', '.berocket_select_fontawesome .berocket_fa_popup',function(event) {
            event.preventDefault();
            event.stopPropagation();
        });
        if( location.hash ) {
            $('.br_framework_settings ul.side a[href="'+location.hash+'"]').trigger('click');
        }
        if( typeof wp.codeEditor != 'undefined' && typeof wp.codeEditor.initialize != 'undefined' ) {
            var css_editor = $('.br_framework_settings .css_editor');
            css_editor.each(function() {
                wp.codeEditor.initialize(this, {"codeEditor":{"codemirror":{"indentUnit":4,"indentWithTabs":true,"inputStyle":"contenteditable","lineNumbers":true,"lineWrapping":true,"styleActiveLine":true,"continueComments":true,"extraKeys":{"Ctrl-Space":"autocomplete","Ctrl-\/":"toggleComment","Cmd-\/":"toggleComment","Alt-F":"findPersistent"},"direction":"ltr","gutters":[],"mode":"css"},"csslint":{"errors":true,"box-model":true,"display-property-grouping":true,"duplicate-properties":true,"known-properties":true,"outline-none":true},"jshint":{"boss":true,"curly":true,"eqeqeq":true,"eqnull":true,"es3":true,"expr":true,"immed":true,"noarg":true,"nonbsp":true,"onevar":true,"quotmark":"single","trailing":true,"undef":true,"unused":true,"browser":true,"globals":{"_":false,"Backbone":false,"jQuery":false,"JSON":false,"wp":false}},"htmlhint":{"tagname-lowercase":true,"attr-lowercase":true,"attr-value-double-quotes":true,"doctype-first":false,"tag-pair":true,"spec-char-escape":true,"id-unique":true,"src-not-empty":true,"attr-no-duplication":true,"alt-require":true,"space-tab-mixed-disabled":"tab","attr-unsafe-chars":true}}});
            })
            var js_editor = $('.br_framework_settings .js_editor');
            js_editor.each(function() {
                wp.codeEditor.initialize(this, {"codeEditor":{"codemirror":{"indentUnit":4,"indentWithTabs":true,"inputStyle":"contenteditable","lineNumbers":true,"lineWrapping":true,"styleActiveLine":true,"continueComments":true,"extraKeys":{"Ctrl-Space":"autocomplete","Ctrl-\/":"toggleComment","Cmd-\/":"toggleComment","Alt-F":"findPersistent"},"direction":"ltr","gutters":[],"mode":"javascript"},"csslint":{"errors":true,"box-model":true,"display-property-grouping":true,"duplicate-properties":true,"known-properties":true,"outline-none":true},"jshint":{"boss":true,"curly":true,"eqeqeq":true,"eqnull":true,"es3":true,"expr":true,"immed":true,"noarg":true,"nonbsp":true,"onevar":true,"quotmark":"single","trailing":true,"undef":true,"unused":true,"browser":true,"globals":{"_":false,"Backbone":false,"jQuery":false,"JSON":false,"wp":false}},"htmlhint":{"tagname-lowercase":true,"attr-lowercase":true,"attr-value-double-quotes":true,"doctype-first":false,"tag-pair":true,"spec-char-escape":true,"id-unique":true,"src-not-empty":true,"attr-no-duplication":true,"alt-require":true,"space-tab-mixed-disabled":"tab","attr-unsafe-chars":true}}});
            });
        }
        var berocket_save_button_side_timeout;
        function berocket_save_button_side() {
            var $button = $('.berocket_framework_sidebar_save_button .button-primary.button');
            if( $button.length ) {
                clearTimeout(berocket_save_button_side_timeout);
                berocket_save_button_side_timeout = setTimeout(function() {
                    /*Settings to set*/
                    var bottom = '0px';
                    var position = 'fixed';
                    /*Get parameters*/
                    var $side = $button.parents('ul.side').first();
                    var sideTop = $side.offset().top;
                    var sideHeight = $side.outerHeight();
                    var windowScroll = jQuery(window).scrollTop();
                    var windowHeight = jQuery(window).height();
                    /*Calculate parameters*/
                    var sideBottom = sideTop + sideHeight;
                    var windowBottom = windowScroll + windowHeight;
                    var positionBottom = sideBottom - windowBottom;
                    if( positionBottom > (sideHeight - 50) ) {
                        bottom = '-100px';
                    }
                    if( positionBottom < 0 ) {
                        position = 'absolute';
                    }
                    if( $('.berocket_framework_default_save_button:visible').offset().top > windowBottom ) {
                        $button.css('display', 'block');
                    } else {
                        bottom = '-100px';
                        if( position == 'absolute' ) {
                            $button.css('display', 'none');
                        }
                    }
                    $button
                        .css('width', $button.parent().outerWidth())
                        .css('position', position)
                        .css('bottom', bottom);
                }, 10);
            }
        }
        $('.berocket_framework_sidebar_save_button .button-primary.button').css('display', 'block');
        berocket_save_button_side();
        $(document).scroll(berocket_save_button_side);
        $(window).resize(berocket_save_button_side);
        $(document).on('click', '.berocket_framework_sidebar_save_button .button-primary.button', function(event) {
            event.preventDefault();
            $(this).parents('.br_framework_settings').find('.br_framework_submit_form').submit();
        });
    });
})(jQuery);
/* PRODUCTS SELECTOR */
(function ($){
    $(document).ready( function () {
        var last_search = '';
        var delay_search = false;
        var ajax_request = false;
        var $current_search = $('');
        $(document).on('click', '.berocket_products_search', function() {
            $(this).find('.berocket_search_input').focus();
        });
        $(document).on('dblclick', '.berocket_products_search', function() {
            $(this).find('.berocket_search_input').select();
        });
        $(document).on('click', '.berocket_products_search .button', function(event) {
            event.stopPropagation();
            var $search_block = $(this);
            var $search_box = $search_block.parents('.berocket_search_box').first();
            $(this).remove();
            if( $search_box.is('.single_product') && $search_box.find('.berocket_product_selected').length == 0 ) {
                $search_box.find('.berocket_product_search').show();
            }
        });
        $(document).on('click', '.berocket_search_box', function(event) {
            event.stopPropagation();
            var $current = $(this).find('.berocket_search_result');
            if( $current.length == 0 ) {
                remove_search_result();
            } 
        });
        $(document).on('keyup focus', '.berocket_search_input', function(event) {
            if( delay_search ) {
                clearTimeout(delay_search);
            }
            var $search_block = $(this);
            var $search_box = $search_block.parents('.berocket_search_box').first();
            if( $search_box.is('.single_product') && $search_box.find('.berocket_product_selected').length > 0 ) {
                return false;
            }
            $current_search = $(this).parents('.berocket_search_box').first();
            delay_search = setTimeout( function () {
                if( $search_block.val().length >= 3 && $search_block.val() != last_search ) {
                    $('.berocket_search_result').remove();
                    last_search = $search_block.val();
                    var exists = [];
                    $search_box.find('.berocket_product_selected input').each(function( i, o ) {
                        exists.push($(o).val());
                    });
                    var data = {
                        action: $search_block.data('action'),
                        term: $search_block.val(),
                        security: berocket_framework_admin.security,
                        is_berocket:true
                    };
                    stop_search();
                    $search_box.find('.berocket_product_search').append($('<span class="berocket_loads"><i class="fa fa-spinner fa-spin"></i></span>'));
                    ajax_request = $.get(ajaxurl, data, function (data) {
                        $current_search = $search_box;
                            var count = 0;
                            var html = '<ul class="berocket_search_result">';
                            $.each(data, function(index, value) {
                                if( $.inArray(index, exists) == -1 ) {
                                    html += '<li data-id="'+index+'">'+value+'</li>';
                                    count++;
                                }
                            });
                            html += '</ul>';
                            if( count > 0 ) {
                                $result_block = $(html);
                                $result_block = $('body').append($result_block);
                                $('.berocket_search_result').css('position', 'absolute')
                                .css('top', $search_box.offset().top+$search_box.height())
                                .css('left', $search_box.offset().left)
                                .outerWidth($search_box.outerWidth());
                            }
                            $('.berocket_product_search .berocket_loads').remove();
                        }, 'json');
                } else {
                    stop_search();
                }
            }, 500 );
        });
        $(document).on('click', '.berocket_search_result li', function(event) {
            var html = '<li class="berocket_product_selected button"><input data-name="'+$current_search.data('name')+'" name="'+$current_search.data('name')+'" type="hidden" value="'+$(this).data('id')+'"><i class="fa fa-times"></i> '+$(this).text()+'</li>';
            $current_search.find('.berocket_product_search').last().before($(html));
            $current_search.find('.berocket_search_input').val('').trigger('change');
            if( $current_search.is('.single_product') ) {
                $current_search.find('.berocket_product_search').hide();
            }
            remove_search_result();
        });
        $(document).on('click', function(event) {
            remove_search_result();
            stop_search();
        });
        function remove_search_result() {
            $('.berocket_search_result').remove();
            last_search = '';
            $current_search = $('');
        }
        function stop_search() {
            if ( ajax_request !== false ) {
                ajax_request.abort();
            }
            $('.berocket_product_search .berocket_loads').remove();
        }
    });
})(jQuery);


//SHOW MESSAGE FOR BLOCK
var berocket_block_messages_elements = [];
var berocket_block_messages_element_last = false;
var berocket_block_messages_element_interval = false;
function berocket_blocks_messages(options) {
    if( typeof(options) == 'undefined' ) {
        do {
            var element = berocket_block_messages_elements.shift();
        } while (! berocket_display_block_messages(element, berocket_block_messages_elements) && berocket_block_messages_elements.length);
        return 'next';
    }
    if( ! berocket_block_messages_elements.length && berocket_block_messages_element_last == false ) {
        berocket_block_messages_elements = options;
        berocket_blocks_messages();
        berocket_block_messages_element_interval = setInterval(function() {
            if( berocket_block_messages_element_last !== false ) {
                var $element = jQuery(berocket_block_messages_element_last.selector).first();
                var top = parseInt($element.offset().top);
                var left = parseInt($element.offset().left);
                if( berocket_block_messages_element_last.top != top || berocket_block_messages_element_last.left != left ) {
                    berocket_display_block_message_reload_last();
                }
            }
        }, 2000);
        return true;
    } else {
        return false;
    }
}
function berocket_display_block_messages(element, next_elements) {
    if( typeof(element.selector) != 'undefined' && jQuery(element.selector).length ) {
        berocket_block_messages_element_last = element;
        if( typeof(element.disable_inside) == 'undefined' ) {
            element.disable_inside = true;
        }
        if( typeof(element.execute) == 'function' ) {
            element.execute(element, next_elements);
        }
        var $element = jQuery(element.selector).first();
        var top = parseInt($element.offset().top);
        var left = parseInt($element.offset().left);
        berocket_block_messages_element_last.top = top;
        berocket_block_messages_element_last.left = left;
        var width = parseInt($element.outerWidth());
        var height = parseInt($element.outerHeight());
        var left_end = left + width;
        var top_end = top + height;
        var right_width = (parseInt(jQuery(window).width()) - left_end);
        styles = {
            top:'top:0;left:0;width:100%;height:'+top+'px;',
            bottom:'top:'+top_end+'px;left:0;width:100%;',
            left:'top:'+top+'px;left:0;width:'+left+'px;height:'+height+'px;',
            right:'top:'+top+'px;left:'+left_end+'px;right:0;height:'+height+'px;',
            inside:'top:'+top+'px;left:'+left+'px;width:'+width+'px;height:'+height+'px;'
        };
        if( top < 40 ) {
            styles.top += 'padding:0;';
        }
        if( jQuery('#wpcontent').length ) {
            var bottom_height = (parseInt(jQuery('#wpcontent').outerHeight()) - top_end) + 100;
            styles.bottom += 'height:'+(bottom_height + 40)+'px;';
        } else {
            styles.bottom += "bottom:0;";
        }
        if( left < 40 || height < 40 ) {
            styles.left += 'padding:0;';
        }
        if( right_width < 40 || height < 40 ) {
            styles.right += 'padding:0;';
        }
        //Create hide blocks
        var html = '<div class="berocket_display_block_messages_hide top" style="'+styles.top+'"></div>';
        html += '<div class="berocket_display_block_messages_hide bottom" data-top_end="'+top_end+'" style="'+styles.bottom+'"></div>';
        html += '<div class="berocket_display_block_messages_hide left" style="'+styles.left+'"></div>';
        html += '<div class="berocket_display_block_messages_hide right" style="'+styles.right+'"></div>';
        if( typeof(element.text) == 'undefined' || element.disable_inside ) {
            html += '<div class="berocket_display_block_messages_hide inside" style="'+styles.inside+'"></div>';
        }
        jQuery('body').append(jQuery(html));
        jQuery("html, body").stop().animate({scrollTop:top - 50}, 500, 'swing');
        //Button to next and close
        html = '<a href="#close" class="berocket_display_block_messages_close_button">'+berocket_framework_admin_text.wizard_close+'</a>';
        if( next_elements.length ) {
            html += '<a href="#next" class="berocket_display_block_messages_next_button">'+berocket_framework_admin_text.wizard_next+'</a>';
        }
        if( typeof(element.text) == 'undefined' ) {
            jQuery('.berocket_display_block_messages_hide.inside').last().append(jQuery(html));
        } else {
            berocket_display_tooltip($element, element.text)
            jQuery('.berocket_display_tooltip').last().append(jQuery(html));
        }
        return true;
    } else {
        return false;
    }
}
setInterval(function(){
    if( jQuery('.berocket_display_block_messages_hide.bottom').length && jQuery('#wpcontent').length ) {
        var top_end = parseInt(jQuery('.berocket_display_block_messages_hide.bottom').data('top_end'));
        var bottom_height = (parseInt(jQuery('#wpcontent').outerHeight()) - top_end) + 100;
        jQuery('.berocket_display_block_messages_hide.bottom').css('height', bottom_height+'px');
    }
}, 500);
function berocket_display_block_message_reload_last() {
    berocket_display_block_messages_remove();
    berocket_display_block_messages(berocket_block_messages_element_last, berocket_block_messages_elements);
}
function berocket_display_tooltip(element, text, additional) {
    $element = jQuery(element).first();
    if( typeof(additional) != 'object' ) {
        additional = {};
    }
    var top = parseInt($element.offset().top);
    var left = parseInt($element.offset().left);
    var width = parseInt($element.outerWidth());
    var height = parseInt($element.outerHeight());
    var left_end = left + width;
    var right_width = (parseInt(jQuery(window).width()) - left_end);
    if( left > 200 ) {
        var position = 'left';
    } else if( right_width > 200 ) {
        var position = 'right';
    } else {
        var position = 'element';
    }
    var style="";
    if( position == 'left' || position == 'right' ) {
        style += 'top:'+top+'px;';
    } else if( position == 'element' ) {
        style += 'top:'+(top+40)+'px;';
    }
    if( position == 'left' ) {
        style += 'right:'+(right_width + width + 15)+'px;';
    } else if( position == 'right' ) {
        style += 'left:'+(left_end + 15)+'px;';
    } else if( position == 'element' ) {
        style += 'left:'+left+'px;';
    }
    if( position == 'left' ) {
        if( (left - 30) < 500 ) {
            style += 'max-width:'+(left - 30)+'px;';
        }
    } else if( position == 'right' ) {
        if( (right_width - 30) < 500 ) {
            style += 'max-width:'+(right_width - 30)+'px;';
        }
    } else if( position == 'element' ) {
        style += 'width:'+width+'px;';
    }
    var classes = 'berocket_display_tooltip position_'+position;
    if( typeof(additional.hide_on_click) != 'undefined' && additional.hide_on_click ) {
        classes += ' hide_on_click';
    }
    if( typeof(additional.classes) != 'undefined' ) {
        classes += ' '+additional.classes;
    }
    var html = '<div class="'+classes+'" style="'+style+'">';
    html += '<span class="br_text">'+text+'</span>';
    html += '<span class="br_add1"></span>';
    html += '<span class="br_add2"></span>';
    html += '<span class="br_add3"></span>';
    html += '</div>';
    jQuery('body').append(jQuery(html));
}
function berocket_display_block_messages_remove() {
    if( typeof(berocket_block_messages_element_last.execute_after) == 'function' ) {
        berocket_block_messages_element_last.execute_after(berocket_block_messages_element_last, berocket_block_messages_elements);
    }
    jQuery('.berocket_display_block_messages_hide').remove();
    jQuery('.berocket_display_block_messages_close_button').remove();
    jQuery('.berocket_display_block_messages_next_button').remove();
    jQuery('.berocket_display_tooltip').remove();
}
jQuery(document).on('click', '.berocket_display_block_messages_close_button', function(event) {
    event.preventDefault();
    berocket_display_block_messages_remove();
    berocket_block_messages_element_last = false;
    berocket_block_messages_elements = [];
    clearInterval(berocket_block_messages_element_interval);
});
jQuery(document).on('click', '.berocket_display_block_messages_next_button', function(event) {
    event.preventDefault();
    berocket_display_block_messages_remove();
    berocket_blocks_messages();
});
jQuery(document).on('click', function() {
    jQuery('.berocket_display_tooltip.hide_on_click').remove();
});
//Sortable custom post
(function ($){
    $(document).ready( function () {
        $(document).on('click', '.berocket_post_set_new_sortable, .berocket_post_set_new_sortable_set', function(event) {
            event.preventDefault();
            var $this = $(this);
            var post_id = $(this).data('post_id');
            if( $this.is('.berocket_post_set_new_sortable_set') ) {
                var order = $this.parents('.berocket_post_set_new_sortable_input').first().find('input').val();
            } else {
                var order = $(this).data('order');
            }
            $.post(location.href, {braction:'berocket_custom_post_sortable', BRsortable_id:post_id, BRorder:order}, function(html) {
                var $html = jQuery(html);
                var $tbody = $html.find('.berocket_post_set_new_sortable').first().parents('tbody').first();
                $('.berocket_post_set_new_sortable').first().parents('tbody').first().replaceWith($tbody);
                jQuery(document).trigger('BRsortable_loaded_html');
            });
        });
    });
})(jQuery);
//ADDONS RELOAD
(function ($){
    function berocket_addon_list_resize(element) {
        var addclass = false;
        var width = $(element).width();
        if( width > 1600 ) {
            addclass = 'col8-addons';
        } else if( width > 1400 ) {
            addclass = 'col7-addons';
        } else if( width > 1200 ) {
            addclass = 'col6-addons';
        } else if( width > 1000 ) {
            addclass = 'col5-addons';
        } else if( width > 800 ) {
            addclass = 'col4-addons';
        } else if( width > 600 ) {
            addclass = 'col3-addons';
        } else if( width > 400 ) {
            addclass = 'col2-addons';
        } else if( width > 100 ) {
            addclass = 'col-addons';
        } else {
            addclass = 'col7-addons';
        }
        $(element)
            .removeClass('col8-addons')
            .removeClass('col7-addons')
            .removeClass('col6-addons')
            .removeClass('col5-addons')
            .removeClass('col4-addons')
            .removeClass('col3-addons')
            .removeClass('col2-addons')
            .removeClass('col-addons');
        if( addclass ) {
            $(element).addClass(addclass);
        }
    }
    function berocket_addon_list_resize_all() {
        $('.berocket_addons_list').each(function() {
            berocket_addon_list_resize($(this));
        });
    }
    $(window).on('resize', berocket_addon_list_resize_all);
    $(document).on('brtab_opened', berocket_addon_list_resize_all);
    $(document).ready( function () {
        berocket_addon_list_resize_all();
        $(document).on('change', '.berocket_addons_list input', function() {
            $(this).parents('.br_framework_submit_form').addClass('br_reload_form');
        });
    });
})(jQuery);
//CONDITIONS
jQuery(document).on('change', '.br_cond_type', function(event) {
    var $data_block = jQuery(this).parents('.berocket_conditions_block').first().find('.br_condition_data').first();
    var condition_name = $data_block.data('condition_name');
    var $parent = jQuery(this).parents('.br_cond_select');
    $parent.find('.br_cond').remove();
    var id = $parent.parents('.br_html_condition');
    var current_id = $parent.data('current');
    id = id.data('id');
    var html_need = jQuery('.br_cond_example .br_cond_'+jQuery(this).val()).get(0);
    html_need = html_need.outerHTML;
    html_need = html_need.replace(/%id%/g, id);
    html_need = html_need.replace(/%current_id%/g, current_id);
    html_need = html_need.replace(/%name%/g, condition_name);
    html_need = html_need.replace(/data-name=/g, 'name=');
    $parent.find('.br_current_cond').html(html_need);
});
jQuery(document).on('click', '.berocket_add_condition', function() {
    var id = jQuery(this).parents('.br_html_condition');
    var current_id = id.data('current');
    current_id = current_id + 1;
    id.data('current', current_id);
    id = id.data('id');
    var $html = jQuery(this).parents('.berocket_conditions_block').first().find('.br_condition_example .br_cond_select').html();
    $html = '<div class="br_cond_select" data-current="'+current_id+'">'+$html+'</div>'; 
    $html = $html.replace('%id%', id);
    jQuery(this).before($html);
    $parent = jQuery(this).prev();
    $parent.find('.br_cond_type').trigger('change');
});
jQuery(document).on('click', '.br_add_group', function() {
    var $data_block = jQuery(this).parents('.berocket_conditions_block').first().find('.br_condition_data').first();
    var last_id = $data_block.data('last_id');
    last_id++;
    $data_block.data('last_id', last_id);
    
    var html = jQuery(this).parents('.berocket_conditions_block').first().find('.br_condition_example').html();
    html = html.replace( '%id%', last_id );
    var html = '<div class="br_html_condition" data-id="'+last_id+'" data-current="1"><div class="br_cond_one">'+html+'</div></div>';
    jQuery(this).before(html);
    $parent = jQuery(this).prev();
    $parent.find('.br_cond_type').trigger('change');
});
jQuery(document).on('click', '.berocket_remove_condition', function() {
    $parent = jQuery(this).parents('.br_cond_select');
    $parent.remove();
});
jQuery(document).on('click', '.br_remove_group', function() {
    $parent = jQuery(this).parents('.br_html_condition');
    $parent.remove();
});
jQuery(document).on('change', '.br_cond_attr_select', function() {
    var $attr_block = jQuery(this).parents('.br_cond').first();
    $attr_block.find('.br_attr_values').hide();
    $attr_block.find('.br_attr_value_'+jQuery(this).val()).show();
});
jQuery(document).on('change', '.price_from', function() {
    var val_price_from = jQuery(this).val();
    var val_price_to = jQuery(this).parents('.br_cond').first().find('.price_to').val();
    price_from = parseFloat(val_price_from);
    price_to = parseFloat(val_price_to);
    price_to_int = parseInt(val_price_to);
    if( val_price_from == '' ) {
        jQuery(this).val(0);
        price_from = 0;
    }
    if( price_from > price_to ) {
        jQuery(this).parents('.br_cond').first().find('.price_to').val(jQuery(this).val());
    }
});
jQuery(document).on('change', '.price_to', function() {
    var val_price_from = jQuery(this).parents('.br_cond').first().find('.price_from').val();
    var val_price_to = jQuery(this).val();
    price_from = parseFloat(val_price_from);
    price_from_int = parseInt(val_price_from);
    price_to = parseFloat(val_price_to);
    if( val_price_to == '' ) {
        jQuery(this).val(0);
        price_to = 0;
    }
    if( price_from > price_to ) {
        jQuery(this).parents('.br_cond').first().find('.price_from')(jQuery(this).val());
    }
});
//TEMPLATES
jQuery(document).on('mousedown', '.berocket_templates_list .berocket_template_label', function() {
    var $this = jQuery(this).find('.berocket_template_is_active');
    if( $this.prop('checked') ) {
        $this.addClass('berocket_was_checked');
    } else {
        $this.removeClass('berocket_was_checked');
    }
});
jQuery(document).on('click', '.berocket_templates_list .berocket_template_label', function(event) {
    if( jQuery(this).find('.berocket_template_paid_sign').length ) {
        event.preventDefault()
    } else {
        var $this = jQuery(this).find('.berocket_template_is_active');
        setTimeout(function() {
            if( $this.is('.berocket_was_checked') ) {
                $this.prop('checked', false);
            }
        }, 10);
    }
});
jQuery(document).on('click', '.berocket_templates_list .berocket_template_label .berocket_template_paid_get a', function(event) {
    event.stopPropogation();
});
//ADDONS
jQuery(document).on('click', '.berocket_addons_list .berocket_addon_label .berocket_addon_paid_get a', function(event) {
    event.stopPropogation();
});
