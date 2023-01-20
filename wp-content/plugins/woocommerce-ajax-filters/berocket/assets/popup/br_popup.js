;(function ( $ ) {
    $.br_popup_list = [];
    $.fn.br_popup = function( options ) {
        if (this.length > 1){
            this.each(function() { $(this).br_popup(options) });
            return this;
        }
        this.open_popup = function () {
            var popup_data = this.data('br_popup_data');
            if( ! popup_data.opened ) {
                this.reset_option();
                this.create_popup();
                this.add_content();
                this.add_events();
                this.show_popup();
            }
        };
        this.reset_option = function () {
            var popup_data = {timer_interval: undefined, close_delay: undefined, can_close_popup: true, opened:false, can_close_popup_count:0};
            this.data('br_popup_data', popup_data);
            jQuery(this).trigger('br_popup-after_reset_option', this);
        };
        this.create_popup = function() {
            jQuery(this).trigger('br_popup-before_create_popup', this);
            var settings = this.data('br_popup_settings');

            $popup_html  = '<div id="br_popup" class="br_popup animated'+this.builder.popup_class(settings)+'" style="'+this.builder.popup_style(settings)+'">';
            $popup_html += '<div class="br_popup_wrapper'+this.builder.popup_wrapper_class(settings)+'" style="'+this.builder.popup_wrapper_style(settings)+'">';
            $popup_html += '<div class="animated popup_animation'+this.builder.popup_animation_class(settings)+'">';
            
            $popup_html += this.builder.close_button(settings);
            $popup_html += this.builder.close_delay(settings);
            $popup_html += this.builder.header_title(settings);
            
            $popup_html += '<div class="br_popup_inner">';
            if (
                ( settings.yes_no_buttons.show == true
                  && settings.yes_no_buttons.custom == false
                  || settings.print_button == true
                )
                && settings.yes_no_buttons.location == 'content'
            ) {
                $popup_html += '<div class="br_popup_buttons">';
                $popup_html += this.builder.buttons(settings);
                $popup_html += '</div>';
            }
            $popup_html += '</div>';
            
            if (
                ( settings.yes_no_buttons.show == true
                  && settings.yes_no_buttons.custom == false
                  || settings.print_button == true
                )
                && settings.yes_no_buttons.location == 'popup'
            ) {
                $popup_html += '<div class="br_popup_buttons">';
                $popup_html += this.builder.buttons(settings);
                $popup_html += '</div>';
            }
            
            $popup_html += '</div></div>';
            
            if ( settings.no_overlay == false ) {
                $popup_html += '<div class="br_popup_overlay"></div>';
            }
            
            $popup_html += '</div>';
            $popup_html = $($popup_html);
            $popup_html.appendTo('body');
            $popup_html.data('br_popup_main', this);
            this.data('br_popup_object', $popup_html);
            jQuery(this).trigger('br_popup-after_create_popup', this);
		};
        this.builder = {
            popup_class: function(settings) {
                var text = '';
                if ( settings.theme != 'default' && settings.theme != '' ) {
                    text += ' ' + settings.theme;
                }
                return text;
            },
            popup_style: function(settings) {return '';},
            popup_wrapper_class: function(settings) {return '';},
            popup_wrapper_style: function(settings) {
                var text = '';
                if ( settings.width ) {
                    text += 'width: '+settings.width+';';
                }
                if ( settings.height ) {
                    text += 'height: '+settings.height+';';
                }
                return text;
            },
            popup_animation_class: function(settings) {
                var text = '';
                if ( settings.yes_no_buttons.show == true
                     && settings.yes_no_buttons.custom == false
                     && settings.yes_no_buttons.location == 'popup'
                     || settings.print_button == true
                ) {
                    text += ' with_yes_no_buttons';
                }
                text += ' yes_no_buttons_'+settings.yes_no_buttons.align;
                if ( settings.title && settings.title != '' ) {
                    text += ' with_header';
                }
                if ( settings.print_button == true ) {
                    text += ' with_print_button';
                }
                return text;
            },
            close_button: function(settings) {
                var text = '';
                if ( settings.no_x_button == false ) {
                    text += '<a href="#" class="br_popup_close">×</a>';
                }
                return text;
            },
            close_delay: function(settings) {
                var text = '';
                if ( settings.close_delay * 1 > 0 ) {
                    var close_delay_text = '%s second(s) before close';
                    if( settings.close_delay_text && (typeof(settings.close_delay_text) === 'string' || settings.close_delay_text instanceof String) ) {
                        close_delay_text = settings.close_delay_text;
                    }
                    close_delay_text = close_delay_text.replace('%s', '<span>' + ( settings.close_delay * 1 ) + '</span>');
                    text += '<span class="counters after_close">'+close_delay_text+'</span>';
                }
                return text;
            },
            header_title: function(settings) {
                var text = '';
                if ( settings.title && settings.title != '' ) {
                    text += '<div class="br_popup_header popup_header_' + settings.header_align + '">' + settings.title + '</div>';
                }
                return text;
            },
            buttons: function(settings) {
                var text = '';
                if ( settings.yes_no_buttons.show == true && settings.yes_no_buttons.custom == false ) {
                    text += '<a href="' + settings.yes_no_buttons.yes_text + '" '
                        + 'class="br_yes_button ' + settings.yes_no_buttons.yes_classes + '">'
                        + settings.yes_no_buttons.yes_text
                        + '</a>';
                    text += '<a href="' + settings.yes_no_buttons.no_text + '" '
                        + 'class="br_no_button ' +settings.yes_no_buttons.no_classes + '">'
                        + settings.yes_no_buttons.no_text
                        + '</a>';
                }
                if ( settings.print_button == true ) {
                    text += '<a href="Print" class="print_button">Print</a>';
                }
                return text;
            }
        };
		this.add_content = function() {
            var settings = this.data('br_popup_settings');
            if( settings.content ) {
                this.data('br_popup_object').find('.br_popup_inner').prepend( settings.content );
            } else {
                this.data('br_popup_object').find('.br_popup_inner').prepend( this.html() );
            }
            jQuery(this).trigger('br_popup-after_add_content', this);
		};
        this.add_events = function() {
            var settings = this.data('br_popup_settings');
            var $this = this;
            if ( settings.close_with.includes('overlay') ) {
                $(this.data('br_popup_object')).on("click", ".br_popup_overlay", function (event){
                    event.preventDefault();
                    $this.hide_popup();
                });
            }
            
            if ( settings.close_with.includes('x_button') ) {
                $(this.data('br_popup_object')).on("click", ".br_popup_close", function (event){
                    event.preventDefault();
                    $this.hide_popup();
                });
            }

            if ( settings.close_with.includes('esc_button') ) {
                $(document).on("keydown", function (event){
                    if ( event.keyCode === 27 ) {
                        $this.hide_popup();
                    }
                });
            }
            
            if ( settings.yes_no_buttons.show == true && settings.yes_no_buttons.custom == false ) {
                $(this.data('br_popup_object')).on("click", settings.yes_no_buttons.yes_button, function (event){
                    event.preventDefault();
                    var popup_data = $this.data('br_popup_data');
                    if( popup_data.can_close_popup == false ) {
                        return;
                    }
                    if ( settings.close_with.includes('yes_button') ) {
                        $this.hide_popup();
                    }
                    
                    jQuery($this).trigger('br_popup-yes_button', $this);
                    if ( typeof settings.yes_no_buttons.yes_func === 'function' ) {
                        settings.yes_no_buttons.yes_func();
                    } else if( settings.yes_no_buttons.yes_func) {
                        try {
                            eval(settings.yes_no_buttons.yes_func);
                        } catch( error ) {
                            console.log('Incorrect function settings.yes_no_buttons.yes_func');
                        }
                    }
                });
            }
            
            if ( settings.yes_no_buttons.show == true && settings.yes_no_buttons.custom == false ) {
                $(this.data('br_popup_object')).on("click", settings.yes_no_buttons.no_button, function (event){
                    event.preventDefault();
                    var popup_data = $this.data('br_popup_data');
                    if( popup_data.can_close_popup == false ) {
                        return;
                    }
                    if ( settings.close_with.includes('no_button') ) {
                        $this.hide_popup();
                    }
                    
                    jQuery($this).trigger('br_popup-no_button', $this);
                    if ( typeof settings.yes_no_buttons.no_func === 'function' ) {
                        settings.yes_no_buttons.no_func();
                    } else if( settings.yes_no_buttons.no_func) {
                        try {
                            eval(settings.yes_no_buttons.no_func);
                        } catch( error ) {
                            console.log('Incorrect function settings.yes_no_buttons.no_func');
                        }
                    }
                });
            }

            if ( settings.print_button == true ) {
                $(this.data('br_popup_object')).on("click", '.print_button', function (event){
                    event.preventDefault();
                    $this.print();
                });
            }
            jQuery(this).trigger('br_popup-after_add_events', this);
        };
        this.show_popup = function() {
            var settings = this.data('br_popup_settings');
            var popup_data = this.data('br_popup_data');
            if ( this.data('br_popup_object') && this.data('br_popup_object').is(':hidden') ) {
                var $this = this;
                popup_data.opened = true;

                $('body').addClass('br_popup_opened');

                if ( settings.hide_body_scroll ) {
                    $('body').addClass('hide_scroll');
                }

                this.data('br_popup_object').css({display:'block'});
                this.animateCss(this.data('br_popup_object'), 'fadeIn');
                this.animateCss(this.data('br_popup_object').find('.popup_animation'), 'fadeInDown');
                popup_data = this.set_close_delay(popup_data);
                this.data('br_popup_data', popup_data);
                jQuery(this).trigger('br_popup-show_popup', this);
            }
        };
        this.set_close_delay = function (popup_data, close_delay_sec) {
            var settings = this.data('br_popup_settings');
            var $this = this;
            if( typeof(popup_data) == 'undefined' || ! popup_data ) {
                popup_data = this.data('br_popup_data');
            }
            if( typeof(close_delay_sec) == 'undefined' || ! close_delay_sec ) {
                close_delay_sec = settings.close_delay;
            }
            if ( close_delay_sec * 1 > 0 ) {
                this.data('br_popup_object').addClass('counting');
                this.disable_close();
                
                popup_data.close_delay = close_delay_sec * 1 - 1;
                popup_data.timer_interval = setInterval(function (){
                    if ( popup_data.close_delay <= 0 ) {
                        $this.enable_close();
                        clearInterval(popup_data.timer_interval);
                        $this.data('br_popup_object').removeClass('counting');
                    } else {
                        $this.data('br_popup_object').find('.counters span').text(popup_data.close_delay);
                    }
                    popup_data.close_delay--;
                    $this.data('br_popup_data', popup_data);
                }, 1000);
            }
            this.data('br_popup_data', popup_data);
            return popup_data;
        }
        this.disable_close = function (popup_data) {
            if( typeof(popup_data) == 'undefined' || ! popup_data ) {
                popup_data = this.data('br_popup_data');
            }
            popup_data.can_close_popup = false;
            popup_data.can_close_popup_count = popup_data.can_close_popup_count*1 + 1;
            this.data('br_popup_object').addClass('cannot_be_closed');
            this.data('br_popup_data', popup_data);
            return popup_data;
        }
        this.enable_close = function (popup_data) {
            if( typeof(popup_data) == 'undefined' || ! popup_data ) {
                popup_data = this.data('br_popup_data');
            }
            popup_data.can_close_popup_count = popup_data.can_close_popup_count*1 - 1;
            if( popup_data.can_close_popup_count <= 0 ) {
                popup_data.can_close_popup_count = 0;
                popup_data.can_close_popup = true;
                this.data('br_popup_object').removeClass('cannot_be_closed');
            }
            this.data('br_popup_data', popup_data);
            return popup_data;
        }
		this.hide_popup = function () {
            var $this = this;
            var popup_data = this.data('br_popup_data');
			if ( popup_data.can_close_popup == true && this.data('br_popup_object').hasClass('br_popup') && this.data('br_popup_object').is(':visible') ) {
                var $this = this;
                
                jQuery(this).trigger('br_popup-hide_popup', this);
                clearInterval(popup_data.timer_interval);
				this.animateCss(this.data('br_popup_object').find('.popup_animation'), 'fadeOutUp');
				this.animateCss(this.data('br_popup_object'), 'fadeOut', function (){
					$this.data('br_popup_object').remove();
                    popup_data.opened = false;
					popup_data.can_close_popup = true;
                    $('body').removeClass('br_popup_opened hide_scroll');
                    $this.data('br_popup_data', popup_data);
				});
			}
		};
		this.animateCss = function (element, animationName, callback) {
            element = $(element);
            element.addClass('animated').addClass(animationName);

			function handleAnimationEnd() {
				element.removeClass('animated').removeClass(animationName);
				element.off('animationend', handleAnimationEnd);

				if ( typeof callback === 'function' ) callback()
			}

			element.on('animationend', handleAnimationEnd);
            setTimeout(function() {
                element.trigger('animationend');
            }, 1500);
		};
        this.print = function() {
            $('body').addClass('print');
            window.print();
            $('body').removeClass('print');
        };

        var settings = this.data('br_popup_settings');
        if ( settings ) {
            settings = $.extend( true, settings, options );
        } else {
            this.reset_option();
            $.br_popup_list.push(this);
            settings = $.extend( true, {
                title:          '',                 // title for popup header
                content:        '',                 // html from this element will be duplicated to the popup
                height:         '',  	            // popup height ( with px or %)
                width:          '',                 // popup weight ( with px or %)
                no_overlay:     false,              // don't use overlay
                no_x_button:    false,              // don't show x button
                header_align:   'left',             // align header text
                yes_no_buttons: {      				// yes and no buttons to catch the action from user
                    custom: 	false, 				// show own buttons or use default
                    show:   	false,            	// show buttons
                    yes_button: '.br_yes_button',   // class or id for the yes button. Don't change it unless custom is true
                    no_button:  '.br_no_button',    // class or id for the no button. Don't change it unless custom is true
                    yes_func:   '',					// your function to run when yes is clicked
                    no_func:    '',					// your function to run  when no is clicked
                    yes_text:   'Accept',			// text shown on yes button
                    no_text:    'Decline',			// text shown on no button
                    yes_classes:'',					// text shown on yes button
                    no_classes: '',					// text shown on no button
                    location:   'popup',			// where to show buttons: 'content' - under the content, 'popup' - bottom of popup
                    align:      'right' 			// align text: 'right', 'left', 'center'
                },
                print_button: false,                // show print button for popup
                close_with:     [
                    'overlay',         				// popup will be closed if catch click on overlay
                    'x_button', 	   				// popup will be closed if catch click on x button
                    'yes_button',      				// popup will be closed if catch click on yes overlay
                    'no_button',       				// popup will be closed if catch click on no overlay
                    'esc_button'       				// popup will be closed if catch esc mouse down
                ],
                close_delay:    0,					// don't allow popup close for X seconds
                effects: {							// effects list is here - https://github.com/daneden/animate.css
                    open: {			   				// when popup is opening
                        effect: 'fadeInDown'
                    },
                    close: {		   				// when popup is closing
                        effect: 'fadeOutUp'
                    }
                },
                theme: "default",                   // default, sweet-alert, simple-shadow
                themes_folder_url: "./themes/",     // url where themes are located if you want popup to load theme
                hide_body_scroll: false             // if true body will get overflow hidden on popup opened
            }, options );
            settings = $.extend( true, settings, $(this).data());
        }
        this.data('br_popup_settings', settings);
        return this;
    }
	
}( jQuery ));
