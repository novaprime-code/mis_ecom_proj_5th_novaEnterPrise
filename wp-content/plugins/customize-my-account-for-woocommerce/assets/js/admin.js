var $var = jQuery.noConflict();
(function( $var ) {
    'use strict';


    ( function( $var, undefined ) {

        $var.widget( "ab.accordion", $var.ui.accordion, {

        options: {
            sortable: false,
            handle:"",
            connectWith:""
        },

        _create: function () {

            this._super( "_create" );

            if ( !this.options.sortable ) {
                return;
            }

            if ( !this.options.handle ) {
                return;
            }

            this.headers.each( function() {
                $var( this ).next()
                     .addBack()
                     .wrapAll( "<div/>" );


            });

            this.element.sortable({
                handle: this.options.handle,
                connectWith: this.options.connectWith,
                cursor: "move",
                placeholder: "dashed-placeholder",
                stop: function( event, ui ) {

                    var $element = ui.item;
                    var $new_parent = $element.parents('li');
                    

                    if (($element.hasClass("group")) && ($new_parent.hasClass("group"))) {
                        alert(wcmamtxadmin.group_mixing_text);
                        

                        return false;
                    }
                     

                    if ($new_parent.hasClass('group')) {
                        $element.find('.wcmamtx_parent_field').val($new_parent.attr("keyvalue"));
                    } else {
                        $element.find('.wcmamtx_parent_field').val("none");
                    }



                    ui.item.children( this.options.handle )
                       .triggerHandler( "focusout" );
                }
            });  

            this.element.accordion({
                 collapsible:true,
                 active:false,
                 clearStyle: true,
                 heightStyle: "content"
                
            }).show();

                  

        },

        _destroy: function () {

            if ( !this.options.sortable ) {
                this._super( "_destroy" );
                return;
            }

            this.element.sortable( "destroy" );

            this.headers.each( function () {
                $var( this ).unwrap( "<div/>" );
            });

        this._super( "_destroy" );

        }

      });

})( jQuery );

$var( function() {



    $var(".wcmamtx-accordion").accordion( { 
        sortable: true, 
        handle:"h3",
        connectWith:".wcmamtx_group_items"
       
    });

    


    $var(".wcmamtx_group_items").accordion( { 
        sortable: true, 
        handle:"h3",
        connectWith:".wcmamtx-accordion"
       
    });


    $var(".wcmamtx-accordion").find('.wcmamtx_accordion_onoff,.wcmamtx_accordion_remove').on('click', function(e) {
        e.preventDefault();
        e.stopPropagation();
        setTimeout(function() {
          this.checked = !this.checked;
      }.bind(this), 100);
    });

    
    $var(".wcmamtx_accordion_remove").on('click',function() {
        var parentkey = $var(this).attr("parentkey");
        if (!confirm(wcmamtxadmin.endpoint_remove_alert)){
          return false;
        }

        var parentitem = "li."+parentkey;
        var litype     = $var(parentitem).attr("litype");

        
        if (litype == "group") {

            var coreli = $var(parentitem).find("li.wcmamtx_endpoint.core").length;

            if (coreli == 0) {
                $var(parentitem).fadeOut('slow').remove();
            } else {
                alert(wcmamtxadmin.core_remove_alert);
            }
            
        } else {
            $var(parentitem).fadeOut('slow').remove();
        }

        
        
    });

    $var('.wcmamtx_accordion_onoff').click(function() {

        var parentkey = $var(this).attr("parentkey");
        
        if ($var(this).is(":checked")) {
            $var(this).parents("li."+ parentkey +"").removeClass('wcmamtx_disabled');
            $var("."+ parentkey +"_hidden_checkbox").val("yes");

        } else {
            
            $var(this).parents("li."+ parentkey +"").addClass('wcmamtx_disabled');
            $var("."+ parentkey +"_hidden_checkbox").val("no");
            
        }
    });

    
    


});

$var( function() {


    $var(".wcmamtx_class_input").tagEditor({
      delimiter: ', ', /* space and comma */
      placeholder: wcmamtxadmin.classplaceholder
    });

    $var("#wcmamtx_reset_tabs_button").on('click',function() {
        var result = confirm(wcmamtxadmin.restorealert);
        
        if (result == true) {
     
            $var.ajax({
                data: {action: "restore_my_account_tabs" },
                type: 'POST',
                url: ajaxurl,
                success: function( response ) { 
                     window.location.reload();
                }
            });
        }
    });

    
    $var('.wcmamtx_show_avatar_checkbox').on("change",function() {
               
        if($var(this).prop("checked")) {
            $var(".wcmamtx_avatar_size_tr").show();
        } else {
            $var(".wcmamtx_avatar_size_tr").hide();
        }
    });


    $var('.show_hide_next_tr_checkbox').on("change",function() {
               
        if($var(this).prop("checked")) {
            $var(this).closest('tr').next('tr').show();
        } else {
            $var(this).closest('tr').next('tr').hide();
        }
    });


    $var('.wcmamtx_disabled').on("click",function() {
        alert(wcmamtxadmin.pro_notice);
        return false;
    });


    function wcmamtx_sanitize(string) {
        const map = {
          '&': '&amp;',
          '<': '&lt;',
          '>': '&gt;',
          '"': '&quot;',
          "'": '&#x27;',
          "/": '&#x2F;',
        };
        const reg = /[&<>"'/]/ig;
        return string.replace(reg, (match)=>(map[match]));
    }


    $var(".wcmamtx_new_end_point").on("click",function(event) {
               
        event.preventDefault();

        var evalue = $var("#wcmamtx_modal_label").val();

        if (evalue == "") {
            
            $var('.wcmamtx_enter_label_alert').html('');
            $var('<p>'+wcmamtxadmin.empty_label_notice+'</p>').appendTo('.wcmamtx_enter_label_alert');
            $var('.wcmamtx_enter_label_alert').show();
            setTimeout(function() {
                $var('.wcmamtx_enter_label_alert').hide();
                $var('.wcmamtx_enter_label_alert').html('');
            }, 2000);
        
        } else {

            var etype = $var('#wcmamtx_hidden_endpoint_type').val();
            var replacetxt = ''+wcmamtxadmin.wait_text+'';
            $var('.wcmamtx_new_end_point').text(replacetxt);
            

            $var.ajax({
                data: {
                    action    : "wcmamtxadmin_add_new_value",
                    row_type  : wcmamtx_sanitize(etype),
                    new_row   : wcmamtx_sanitize(evalue),
                    security  : wcmamtxadmin.nonce,
                    nonce     : $var('#wcmamtx_hidden_endpoint_type').attr("nonce")
                    
                },
                type: 'POST',
                url: wcmamtxadmin.ajax_url,
                success: function( response ) { 
                    window.location.reload();
                }
            });
    
               
            

        }

        return false;

    });


    const capitalize = (s) => {
      if (typeof s !== 'string') return ''
          return s.charAt(0).toUpperCase() + s.slice(1)
    }


    $var('#wcmamtx_example_modal').on('show.bs.modal', function (event) {
        var button = $var(event.relatedTarget) // Button that triggered the modal
        var etype = button.data('etype') // Extract info from data-* attributes
        // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
        // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
        var modal = $var(this)
        modal.find('.wcmamtx_new_end_point').text("Add New "+ capitalize(etype) +"");
        modal.find('#wcmamtx_hidden_endpoint_type').val(etype);
        
        
    });
    



    $var(".wcmamtx_icon_source_radio").on("click", function (event) {
        
        var checkvalue = $var(this).val();
        
        if (checkvalue == "custom") {
            $var(this).parents('tr').next('tr').show();
        } else {
            $var(this).parents('tr').next('tr').hide();
        }
        
    });
    


    $var('.wcmamtxvisibleto').on('change',function(){
        $var(this).closest('tr').next('.wcmamtxroles').toggle();
    });


    $var('.wcmamtx_roleselect').select2({
        width:"400px",
        minimumResultsForSearch: -1
    });


    var icons = [{ icon: 'fa fa-glass' }, { icon: 'fa fa-music' }, { icon: 'fa fa-search' }, { icon: 'fa fa-envelope-o' }, { icon: 'fa fa-heart' }, { icon: 'fa fa-star' }, { icon: 'fa fa-star-o' }, { icon: 'fa fa-user' }, { icon: 'fa fa-film' }, { icon: 'fa fa-th-large' }, { icon: 'fa fa-th' }, { icon: 'fa fa-th-list' }, { icon: 'fa fa-check' }, { icon: 'fa fa-times' }, { icon: 'fa fa-search-plus' }, { icon: 'fa fa-search-minus' }, { icon: 'fa fa-power-off' }, { icon: 'fa fa-signal' }, { icon: 'fa fa-cog' }, { icon: 'fa fa-trash-o' }, { icon: 'fa fa-home' }, { icon: 'fa fa-file-o' }, { icon: 'fa fa-clock-o' }, { icon: 'fa fa-road' }, { icon: 'fa fa-download' }, { icon: 'fa fa-arrow-circle-o-down' }, { icon: 'fa fa-arrow-circle-o-up' }, { icon: 'fa fa-inbox' }, { icon: 'fa fa-play-circle-o' }, { icon: 'fa fa-repeat' }, { icon: 'fa fa-refresh' }, { icon: 'fa fa-list-alt' }, { icon: 'fa fa-lock' }, { icon: 'fa fa-flag' }, { icon: 'fa fa-headphones' }, { icon: 'fa fa-volume-off' }, { icon: 'fa fa-volume-down' }, { icon: 'fa fa-volume-up' }, { icon: 'fa fa-qrcode' }, { icon: 'fa fa-barcode' }, { icon: 'fa fa-tag' }, { icon: 'fa fa-tags' }, { icon: 'fa fa-book' }, { icon: 'fa fa-bookmark' }, { icon: 'fa fa-print' }, { icon: 'fa fa-camera' }, { icon: 'fa fa-font' }, { icon: 'fa fa-bold' }, { icon: 'fa fa-italic' }, { icon: 'fa fa-text-height' }, { icon: 'fa fa-text-width' }, { icon: 'fa fa-align-left' }, { icon: 'fa fa-align-center' }, { icon: 'fa fa-align-right' }, { icon: 'fa fa-align-justify' }, { icon: 'fa fa-list' }, { icon: 'fa fa-outdent' }, { icon: 'fa fa-indent' }, { icon: 'fa fa-video-camera' }, { icon: 'fa fa-picture-o' }, { icon: 'fa fa-pencil' }, { icon: 'fa fa-map-marker' }, { icon: 'fa fa-adjust' }, { icon: 'fa fa-tint' }, { icon: 'fa fa-pencil-square-o' }, { icon: 'fa fa-share-square-o' }, { icon: 'fa fa-check-square-o' }, { icon: 'fa fa-arrows' }, { icon: 'fa fa-step-backward' }, { icon: 'fa fa-fast-backward' }, { icon: 'fa fa-backward' }, { icon: 'fa fa-play' }, { icon: 'fa fa-pause' }, { icon: 'fa fa-stop' }, { icon: 'fa fa-forward' }, { icon: 'fa fa-fast-forward' }, { icon: 'fa fa-step-forward' }, { icon: 'fa fa-eject' }, { icon: 'fa fa-chevron-left' }, { icon: 'fa fa-chevron-right' }, { icon: 'fa fa-plus-circle' }, { icon: 'fa fa-minus-circle' }, { icon: 'fa fa-times-circle' }, { icon: 'fa fa-check-circle' }, { icon: 'fa fa-question-circle' }, { icon: 'fa fa-info-circle' }, { icon: 'fa fa-crosshairs' }, { icon: 'fa fa-times-circle-o' }, { icon: 'fa fa-check-circle-o' }, { icon: 'fa fa-ban' }, { icon: 'fa fa-arrow-left' }, { icon: 'fa fa-arrow-right' }, { icon: 'fa fa-arrow-up' }, { icon: 'fa fa-arrow-down' }, { icon: 'fa fa-share' }, { icon: 'fa fa-expand' }, { icon: 'fa fa-compress' }, { icon: 'fa fa-plus' }, { icon: 'fa fa-minus' }, { icon: 'fa fa-asterisk' }, { icon: 'fa fa-exclamation-circle' }, { icon: 'fa fa-gift' }, { icon: 'fa fa-leaf' }, { icon: 'fa fa-fire' }, { icon: 'fa fa-eye' }, { icon: 'fa fa-eye-slash' }, { icon: 'fa fa-exclamation-triangle' }, { icon: 'fa fa-plane' }, { icon: 'fa fa-calendar' }, { icon: 'fa fa-random' }, { icon: 'fa fa-comment' }, { icon: 'fa fa-magnet' }, { icon: 'fa fa-chevron-up' }, { icon: 'fa fa-chevron-down' }, { icon: 'fa fa-retweet' }, { icon: 'fa fa-shopping-cart' }, { icon: 'fa fa-folder' }, { icon: 'fa fa-folder-open' }, { icon: 'fa fa-arrows-v' }, { icon: 'fa fa-arrows-h' }, { icon: 'fa fa-bar-chart' }, { icon: 'fa fa-twitter-square' }, { icon: 'fa fa-facebook-square' }, { icon: 'fa fa-camera-retro' }, { icon: 'fa fa-key' }, { icon: 'fa fa-cogs' }, { icon: 'fa fa-comments' }, { icon: 'fa fa-thumbs-o-up' }, { icon: 'fa fa-thumbs-o-down' }, { icon: 'fa fa-star-half' }, { icon: 'fa fa-heart-o' }, { icon: 'fa fa-sign-out' }, { icon: 'fa fa-linkedin-square' }, { icon: 'fa fa-thumb-tack' }, { icon: 'fa fa-external-link' }, { icon: 'fa fa-sign-in' }, { icon: 'fa fa-trophy' }, { icon: 'fa fa-github-square' }, { icon: 'fa fa-upload' }, { icon: 'fa fa-lemon-o' }, { icon: 'fa fa-phone' }, { icon: 'fa fa-square-o' }, { icon: 'fa fa-bookmark-o' }, { icon: 'fa fa-phone-square' }, { icon: 'fa fa-twitter' }, { icon: 'fa fa-facebook' }, { icon: 'fa fa-github' }, { icon: 'fa fa-unlock' }, { icon: 'fa fa-credit-card' }, { icon: 'fa fa-rss' }, { icon: 'fa fa-hdd-o' }, { icon: 'fa fa-bullhorn' }, { icon: 'fa fa-bell' }, { icon: 'fa fa-certificate' }, { icon: 'fa fa-hand-o-right' }, { icon: 'fa fa-hand-o-left' }, { icon: 'fa fa-hand-o-up' }, { icon: 'fa fa-hand-o-down' }, { icon: 'fa fa-arrow-circle-left' }, { icon: 'fa fa-arrow-circle-right' }, { icon: 'fa fa-arrow-circle-up' }, { icon: 'fa fa-arrow-circle-down' }, { icon: 'fa fa-globe' }, { icon: 'fa fa-wrench' }, { icon: 'fa fa-tasks' }, { icon: 'fa fa-filter' }, { icon: 'fa fa-briefcase' }, { icon: 'fa fa-arrows-alt' }, { icon: 'fa fa-users' }, { icon: 'fa fa-link' }, { icon: 'fa fa-cloud' }, { icon: 'fa fa-flask' }, { icon: 'fa fa-scissors' }, { icon: 'fa fa-files-o' }, { icon: 'fa fa-paperclip' }, { icon: 'fa fa-floppy-o' }, { icon: 'fa fa-square' }, { icon: 'fa fa-bars' }, { icon: 'fa fa-list-ul' }, { icon: 'fa fa-list-ol' }, { icon: 'fa fa-strikethrough' }, { icon: 'fa fa-underline' }, { icon: 'fa fa-table' }, { icon: 'fa fa-magic' }, { icon: 'fa fa-truck' }, { icon: 'fa fa-pinterest' }, { icon: 'fa fa-pinterest-square' }, { icon: 'fa fa-google-plus-square' }, { icon: 'fa fa-google-plus' }, { icon: 'fa fa-money' }, { icon: 'fa fa-caret-down' }, { icon: 'fa fa-caret-up' }, { icon: 'fa fa-caret-left' }, { icon: 'fa fa-caret-right' }, { icon: 'fa fa-columns' }, { icon: 'fa fa-sort' }, { icon: 'fa fa-sort-desc' }, { icon: 'fa fa-sort-asc' }, { icon: 'fa fa-envelope' }, { icon: 'fa fa-linkedin' }, { icon: 'fa fa-undo' }, { icon: 'fa fa-gavel' }, { icon: 'fa fa-tachometer' }, { icon: 'fa fa-comment-o' }, { icon: 'fa fa-comments-o' }, { icon: 'fa fa-bolt' }, { icon: 'fa fa-sitemap' }, { icon: 'fa fa-umbrella' }, { icon: 'fa fa-clipboard' }, { icon: 'fa fa-lightbulb-o' }, { icon: 'fa fa-exchange' }, { icon: 'fa fa-cloud-download' }, { icon: 'fa fa-cloud-upload' }, { icon: 'fa fa-user-md' }, { icon: 'fa fa-stethoscope' }, { icon: 'fa fa-suitcase' }, { icon: 'fa fa-bell-o' }, { icon: 'fa fa-coffee' }, { icon: 'fa fa-cutlery' }, { icon: 'fa fa-file-text-o' }, { icon: 'fa fa-building-o' }, { icon: 'fa fa-hospital-o' }, { icon: 'fa fa-ambulance' }, { icon: 'fa fa-medkit' }, { icon: 'fa fa-fighter-jet' }, { icon: 'fa fa-beer' }, { icon: 'fa fa-h-square' }, { icon: 'fa fa-plus-square' }, { icon: 'fa fa-angle-double-left' }, { icon: 'fa fa-angle-double-right' }, { icon: 'fa fa-angle-double-up' }, { icon: 'fa fa-angle-double-down' }, { icon: 'fa fa-angle-left' }, { icon: 'fa fa-angle-right' }, { icon: 'fa fa-angle-up' }, { icon: 'fa fa-angle-down' }, { icon: 'fa fa-desktop' }, { icon: 'fa fa-laptop' }, { icon: 'fa fa-tablet' }, { icon: 'fa fa-mobile' }, { icon: 'fa fa-circle-o' }, { icon: 'fa fa-quote-left' }, { icon: 'fa fa-quote-right' }, { icon: 'fa fa-spinner' }, { icon: 'fa fa-circle' }, { icon: 'fa fa-reply' }, { icon: 'fa fa-github-alt' }, { icon: 'fa fa-folder-o' }, { icon: 'fa fa-folder-open-o' }, { icon: 'fa fa-smile-o' }, { icon: 'fa fa-frown-o' }, { icon: 'fa fa-meh-o' }, { icon: 'fa fa-gamepad' }, { icon: 'fa fa-keyboard-o' }, { icon: 'fa fa-flag-o' }, { icon: 'fa fa-flag-checkered' }, { icon: 'fa fa-terminal' }, { icon: 'fa fa-code' }, { icon: 'fa fa-reply-all' }, { icon: 'fa fa-star-half-o' }, { icon: 'fa fa-location-arrow' }, { icon: 'fa fa-crop' }, { icon: 'fa fa-code-fork' }, { icon: 'fa fa-chain-broken' }, { icon: 'fa fa-question' }, { icon: 'fa fa-info' }, { icon: 'fa fa-exclamation' }, { icon: 'fa fa-superscript' }, { icon: 'fa fa-subscript' }, { icon: 'fa fa-eraser' }, { icon: 'fa fa-puzzle-piece' }, { icon: 'fa fa-microphone' }, { icon: 'fa fa-microphone-slash' }, { icon: 'fa fa-shield' }, { icon: 'fa fa-calendar-o' }, { icon: 'fa fa-fire-extinguisher' }, { icon: 'fa fa-rocket' }, { icon: 'fa fa-maxcdn' }, { icon: 'fa fa-chevron-circle-left' }, { icon: 'fa fa-chevron-circle-right' }, { icon: 'fa fa-chevron-circle-up' }, { icon: 'fa fa-chevron-circle-down' }, { icon: 'fa fa-html5' }, { icon: 'fa fa-css3' }, { icon: 'fa fa-anchor' }, { icon: 'fa fa-unlock-alt' }, { icon: 'fa fa-bullseye' }, { icon: 'fa fa-ellipsis-h' }, { icon: 'fa fa-ellipsis-v' }, { icon: 'fa fa-rss-square' }, { icon: 'fa fa-play-circle' }, { icon: 'fa fa-ticket' }, { icon: 'fa fa-minus-square' }, { icon: 'fa fa-minus-square-o' }, { icon: 'fa fa-level-up' }, { icon: 'fa fa-level-down' }, { icon: 'fa fa-check-square' }, { icon: 'fa fa-pencil-square' }, { icon: 'fa fa-external-link-square' }, { icon: 'fa fa-share-square' }, { icon: 'fa fa-compass' }, { icon: 'fa fa-caret-square-o-down' }, { icon: 'fa fa-caret-square-o-up' }, { icon: 'fa fa-caret-square-o-right' }, { icon: 'fa fa-eur' }, { icon: 'fa fa-gbp' }, { icon: 'fa fa-usd' }, { icon: 'fa fa-inr' }, { icon: 'fa fa-jpy' }, { icon: 'fa fa-rub' }, { icon: 'fa fa-krw' }, { icon: 'fa fa-btc' }, { icon: 'fa fa-file' }, { icon: 'fa fa-file-text' }, { icon: 'fa fa-sort-alpha-asc' }, { icon: 'fa fa-sort-alpha-desc' }, { icon: 'fa fa-sort-amount-asc' }, { icon: 'fa fa-sort-amount-desc' }, { icon: 'fa fa-sort-numeric-asc' }, { icon: 'fa fa-sort-numeric-desc' }, { icon: 'fa fa-thumbs-up' }, { icon: 'fa fa-thumbs-down' }, { icon: 'fa fa-youtube-square' }, { icon: 'fa fa-youtube' }, { icon: 'fa fa-xing' }, { icon: 'fa fa-xing-square' }, { icon: 'fa fa-youtube-play' }, { icon: 'fa fa-dropbox' }, { icon: 'fa fa-stack-overflow' }, { icon: 'fa fa-instagram' }, { icon: 'fa fa-flickr' }, { icon: 'fa fa-adn' }, { icon: 'fa fa-bitbucket' }, { icon: 'fa fa-bitbucket-square' }, { icon: 'fa fa-tumblr' }, { icon: 'fa fa-tumblr-square' }, { icon: 'fa fa-long-arrow-down' }, { icon: 'fa fa-long-arrow-up' }, { icon: 'fa fa-long-arrow-left' }, { icon: 'fa fa-long-arrow-right' }, { icon: 'fa fa-apple' }, { icon: 'fa fa-windows' }, { icon: 'fa fa-android' }, { icon: 'fa fa-linux' }, { icon: 'fa fa-dribbble' }, { icon: 'fa fa-skype' }, { icon: 'fa fa-foursquare' }, { icon: 'fa fa-trello' }, { icon: 'fa fa-female' }, { icon: 'fa fa-male' }, { icon: 'fa fa-gratipay' }, { icon: 'fa fa-sun-o' }, { icon: 'fa fa-moon-o' }, { icon: 'fa fa-archive' }, { icon: 'fa fa-bug' }, { icon: 'fa fa-vk' }, { icon: 'fa fa-weibo' }, { icon: 'fa fa-renren' }, { icon: 'fa fa-pagelines' }, { icon: 'fa fa-stack-exchange' }, { icon: 'fa fa-arrow-circle-o-right' }, { icon: 'fa fa-arrow-circle-o-left' }, { icon: 'fa fa-caret-square-o-left' }, { icon: 'fa fa-dot-circle-o' }, { icon: 'fa fa-wheelchair' }, { icon: 'fa fa-vimeo-square' }, { icon: 'fa fa-try' }, { icon: 'fa fa-plus-square-o' }, { icon: 'fa fa-space-shuttle' }, { icon: 'fa fa-slack' }, { icon: 'fa fa-envelope-square' }, { icon: 'fa fa-wordpress' }, { icon: 'fa fa-openid' }, { icon: 'fa fa-university' }, { icon: 'fa fa-graduation-cap' }, { icon: 'fa fa-yahoo' }, { icon: 'fa fa-google' }, { icon: 'fa fa-reddit' }, { icon: 'fa fa-reddit-square' }, { icon: 'fa fa-stumbleupon-circle' }, { icon: 'fa fa-stumbleupon' }, { icon: 'fa fa-delicious' }, { icon: 'fa fa-digg' }, { icon: 'fa fa-pied-piper' }, { icon: 'fa fa-pied-piper-alt' }, { icon: 'fa fa-drupal' }, { icon: 'fa fa-joomla' }, { icon: 'fa fa-language' }, { icon: 'fa fa-fax' }, { icon: 'fa fa-building' }, { icon: 'fa fa-child' }, { icon: 'fa fa-paw' }, { icon: 'fa fa-spoon' }, { icon: 'fa fa-cube' }, { icon: 'fa fa-cubes' }, { icon: 'fa fa-behance' }, { icon: 'fa fa-behance-square' }, { icon: 'fa fa-steam' }, { icon: 'fa fa-steam-square' }, { icon: 'fa fa-recycle' }, { icon: 'fa fa-car' }, { icon: 'fa fa-taxi' }, { icon: 'fa fa-tree' }, { icon: 'fa fa-spotify' }, { icon: 'fa fa-deviantart' }, { icon: 'fa fa-soundcloud' }, { icon: 'fa fa-database' }, { icon: 'fa fa-file-pdf-o' }, { icon: 'fa fa-file-word-o' }, { icon: 'fa fa-file-excel-o' }, { icon: 'fa fa-file-powerpoint-o' }, { icon: 'fa fa-file-image-o' }, { icon: 'fa fa-file-archive-o' }, { icon: 'fa fa-file-audio-o' }, { icon: 'fa fa-file-video-o' }, { icon: 'fa fa-file-code-o' }, { icon: 'fa fa-vine' }, { icon: 'fa fa-codepen' }, { icon: 'fa fa-jsfiddle' }, { icon: 'fa fa-life-ring' }, { icon: 'fa fa-circle-o-notch' }, { icon: 'fa fa-rebel' }, { icon: 'fa fa-empire' }, { icon: 'fa fa-git-square' }, { icon: 'fa fa-git' }, { icon: 'fa fa-hacker-news' }, { icon: 'fa fa-tencent-weibo' }, { icon: 'fa fa-qq' }, { icon: 'fa fa-weixin' }, { icon: 'fa fa-paper-plane' }, { icon: 'fa fa-paper-plane-o' }, { icon: 'fa fa-history' }, { icon: 'fa fa-circle-thin' }, { icon: 'fa fa-header' }, { icon: 'fa fa-paragraph' }, { icon: 'fa fa-sliders' }, { icon: 'fa fa-share-alt' }, { icon: 'fa fa-share-alt-square' }, { icon: 'fa fa-bomb' }, { icon: 'fa fa-futbol-o' }, { icon: 'fa fa-tty' }, { icon: 'fa fa-binoculars' }, { icon: 'fa fa-plug' }, { icon: 'fa fa-slideshare' }, { icon: 'fa fa-twitch' }, { icon: 'fa fa-yelp' }, { icon: 'fa fa-newspaper-o' }, { icon: 'fa fa-wifi' }, { icon: 'fa fa-calculator' }, { icon: 'fa fa-paypal' }, { icon: 'fa fa-google-wallet' }, { icon: 'fa fa-cc-visa' }, { icon: 'fa fa-cc-mastercard' }, { icon: 'fa fa-cc-discover' }, { icon: 'fa fa-cc-amex' }, { icon: 'fa fa-cc-paypal' }, { icon: 'fa fa-cc-stripe' }, { icon: 'fa fa-bell-slash' }, { icon: 'fa fa-bell-slash-o' }, { icon: 'fa fa-trash' }, { icon: 'fa fa-copyright' }, { icon: 'fa fa-at' }, { icon: 'fa fa-eyedropper' }, { icon: 'fa fa-paint-brush' }, { icon: 'fa fa-birthday-cake' }, { icon: 'fa fa-area-chart' }, { icon: 'fa fa-pie-chart' }, { icon: 'fa fa-line-chart' }, { icon: 'fa fa-lastfm' }, { icon: 'fa fa-lastfm-square' }, { icon: 'fa fa-toggle-off' }, { icon: 'fa fa-toggle-on' }, { icon: 'fa fa-bicycle' }, { icon: 'fa fa-bus' }, { icon: 'fa fa-ioxhost' }, { icon: 'fa fa-angellist' }, { icon: 'fa fa-cc' }, { icon: 'fa fa-ils' }, { icon: 'fa fa-meanpath' }, { icon: 'fa fa-buysellads' }, { icon: 'fa fa-connectdevelop' }, { icon: 'fa fa-dashcube' }, { icon: 'fa fa-forumbee' }, { icon: 'fa fa-leanpub' }, { icon: 'fa fa-sellsy' }, { icon: 'fa fa-shirtsinbulk' }, { icon: 'fa fa-simplybuilt' }, { icon: 'fa fa-skyatlas' }, { icon: 'fa fa-cart-plus' }, { icon: 'fa fa-cart-arrow-down' }, { icon: 'fa fa-diamond' }, { icon: 'fa fa-ship' }, { icon: 'fa fa-user-secret' }, { icon: 'fa fa-motorcycle' }, { icon: 'fa fa-street-view' }, { icon: 'fa fa-heartbeat' }, { icon: 'fa fa-venus' }, { icon: 'fa fa-mars' }, { icon: 'fa fa-mercury' }, { icon: 'fa fa-transgender' }, { icon: 'fa fa-transgender-alt' }, { icon: 'fa fa-venus-double' }, { icon: 'fa fa-mars-double' }, { icon: 'fa fa-venus-mars' }, { icon: 'fa fa-mars-stroke' }, { icon: 'fa fa-mars-stroke-v' }, { icon: 'fa fa-mars-stroke-h' }, { icon: 'fa fa-neuter' }, { icon: 'fa fa-facebook-official' }, { icon: 'fa fa-pinterest-p' }, { icon: 'fa fa-whatsapp' }, { icon: 'fa fa-server' }, { icon: 'fa fa-user-plus' }, { icon: 'fa fa-user-times' }, { icon: 'fa fa-bed' }, { icon: 'fa fa-viacoin' }, { icon: 'fa fa-train' }, { icon: 'fa fa-subway' }, { icon: 'fa fa-medium' }];


    var itemTemplate = $var('.icon-picker-list').clone(true).html();

    $var('.icon-picker-list').html('');

    // Loop through JSON and appends content to show icons
    $var(icons).each(function(index) {
        var itemtemp = itemTemplate;
        var item = icons[index].icon;

        if (index == selectedIcon) {
            var activeState = 'active'
        } else {
            var activeState = ''
        }

        itemtemp = itemtemp.replace(/{{item}}/g, item).replace(/{{index}}/g, index).replace(/{{activeState}}/g, activeState);
    
        $var('.icon-picker-list').append(itemtemp);
    });

    // Variable that's passed around for active states of icons
    var selectedIcon = null;

    $var('.icon-class-input').each(function() {
        if ($var(this).val() != null) {
            $var(this).siblings('.demo-icon').addClass($var(this).val());
        }
    });

    // To be set to which input needs updating
    var iconInput = null;

    // Click function to set which input is being used
    $var('.picker-button').click(function() {
        // Sets var to which input is being updated
        iconInput = $var(this).siblings('.icon-class-input');
        // Shows Bootstrap Modal
        $var('#iconPicker').modal('show');
        // Sets active state by looping through the list with the previous class from the picker input
        selectedIcon = findInObject(icons, 'icon', $var(this).siblings('.icon-class-input').val());
        // Removes any previous active class
        $var('.icon-picker-list a').removeClass('active');
        // Sets active class
        $var('.icon-picker-list a').eq(selectedIcon).addClass('active');
    });

    // Click function to select icon
    $var(document).on('click', '.icon-picker-list a', function() {
        // Sets selected icon
        selectedIcon = $var(this).data('index');

        // Removes any previous active class
        $var('.icon-picker-list a').removeClass('active');
        // Sets active class
        $var('.icon-picker-list a').eq(selectedIcon).addClass('active');
    });

    // Update icon input
    $var('#change-icon').click(function() {
        iconInput.val(icons[selectedIcon].icon);
        iconInput.siblings('.demo-icon').attr('class', 'demo-icon');
        iconInput.siblings('.demo-icon').addClass(icons[selectedIcon].icon);
        $var('#iconPicker').modal('hide');
        
    });



    function findInObject(object, property, value) {
        for (var i = 0; i < object.length; i += 1) {
            if (object[i][property] === value) {
                return i;
            }
        }
    }


});
 
})( jQuery );