jQuery(document).ready(function () {

    'use strict';

    var rmPanelOrigin, rmMenuOrigin, rmPanelWidth, rmDate;

    rmPanelOrigin = jQuery(".rm-floating-page").css("transform");

    rmMenuOrigin = jQuery("#rm-menu").css("transform");

    rmPanelWidth = jQuery("#rm-panel-page").css("width");

    rmPanelWidth = parseInt(rmPanelWidth, 10);

    jQuery("#rm-popup-button .rm-magic-popup-close-button").hide();   

    

    /*Clicking on Magic Button brings popup menu*/

    jQuery("#rm-popup-button").click(function () {
        
      
        var popupmenu_jqel = jQuery("#rm-menu");
        
        if(!popupmenu_jqel.is(":visible")) {
            popupmenu_jqel.show(0);
            //popupmenu_jqel.css('opacity', '1'); 
            popupmenu_jqel.addClass('rm-magic-popup-open');
            
            jQuery("#rm-popup-button img").hide();
            jQuery("#rm-popup-button .rm-magic-popup-close-button").show();
      
        } else {
            //popupmenu_jqel.css('transform', rmMenuOrigin);
            //popupmenu_jqel.css('opacity', '0'); 
            popupmenu_jqel.hide();
             popupmenu_jqel.removeClass('rm-magic-popup-open');
            jQuery("#rm-popup-button img").show();
            jQuery("#rm-popup-button .rm-magic-popup-close-button").hide();
            
        }
       
    });

    

    /*Popup menu returns when cursor leaves*/

    jQuery("#rm-menu").mouseleave(function () {

        jQuery(this).css('transform', rmMenuOrigin);
        setTimeout(function(){jQuery("#rm-menu").hide()},400);
        jQuery("#rm-popup-button img").show();
        jQuery(".rm-magic-popup-close-button").hide();
    });

    

     /*Sets Title of Panel Page same as clicked menu item and opens corresponding panel*/

    jQuery("#rm-menu").children().not('#rm_log_off').click(function () {
        
        
        if(jQuery(this).attr("id") == 'rm_fab_register_redirect_link')
        {
            location.href = jQuery(this).attr("href");
            jQuery('#rm-menu').css('transform', rmMenuOrigin);
            return false;
        }
        
        jQuery(".rm-magic-popup").hide(200);
        
        var rmTitle, rmPanel, rmFormHeight;

        rmTitle = "";

        rmPanel = "";

        rmTitle = jQuery(this).text();

        jQuery(".rm-floating-page-top").text(rmTitle);

        jQuery("#rm-panel-page").css('transform', 'translateX(0%)').show();

        jQuery(".rm-floating-page-content").children().hide();      
        
        
        rmPanel = jQuery(this).attr("id");

        rmPanel = rmPanel.replace('open', 'panel');

        rmPanel = "#" + rmPanel;

        jQuery(rmPanel).show();

        if (rmPanel.indexOf("big") !== -1 && parseInt(window.innerWidth, 10) > 960) {

            jQuery("#rm-panel-page").css('width', rmPanelWidth * 1.5);

            rmFormHeight = jQuery(".regmagic_embed").contents().find(".rmagic").height();

            jQuery(".regmagic_embed").contents().find("body").css("background", "none");

            jQuery(".rm-floating-page-content").css("padding", "0px");

            jQuery(".regmagic_embed").contents().find(".rmagic").css("padding", "15px");

            rmFormHeight = parseInt(rmFormHeight, 10) + "px";

            jQuery("iframe").attr('height', rmFormHeight);

            jQuery("#regmagic_embed").contents().find(".rmnote").css({'position': 'relative', 'left': '0px', 'top': '0px', 'width': '90%', 'margin-left': '5%'});

        } else if (rmPanel.includes("account") === true) {

            jQuery("#rm-panel-page").css('width', rmPanelWidth);

        }

    });

    

    /*Slides in the panel and resets it to original width*/

    jQuery(document).on('click', '#rm-panel-close', function () {

        jQuery("#rm-panel-page").css({ /* 'transform': rmPanelOrigin, */ 'width': rmPanelWidth});
        jQuery("#rm-panel-page").css("transform", "matrix(1, 0, 0, 1, 600, 0)");

        jQuery(".rm-floating-page-content").css("padding", "5% 10%");

        jQuery(".rm-magic-popup").show();

    });

    

    jQuery(".rm-floating-page-top").click(function() {

      jQuery("#rm-panel-page").css({'transform': rmPanelOrigin, 'width': rmPanelWidth});

    jQuery(".rm-floating-page-content").css("padding", "5% 10%");

      jQuery(".rm-magic-popup").show();

    });

    
        /* Payment Info */
    
    jQuery(".rm-details-arrow-up").hide();

    jQuery(".rm-transaction-info").click(function () {
        var id = jQuery(this).attr('id');

        jQuery("#rm-detail-" + id).slideToggle(500);
        jQuery(this).find(".rm-details-arrow-up, .rm-details-arrow-down").toggle();



    });

    

    /*Handles Font Color for accented background and dark theme attributes*/

    jQuery("#rm-color-switch").click(function () {

        rm_color_switch();

    });



    rm_color_switch(rm_fab_color, rm_fab_theme);

    

    /*Sets greetings on user account panel*/

    rmDate = new Date();

    rmDate = rmDate.getHours();

    if (rmDate >= 4 && rmDate < 12) {

        jQuery("#rm-greeting-text").text(floating_js_vars.greetings.morning + ", ");

    } else if (rmDate >= 12 && rmDate < 16) {

        jQuery("#rm-greeting-text").text(floating_js_vars.greetings.afternoon + ", ");

    } else {jQuery("#rm-greeting-text").text(floating_js_vars.greetings.evening + ", ");

           }

});



'use strict';

function rm_color_switch(rmAccent, rmTheme) {

    var rmAccentRed, rmAccentGreen, rmAccentBlue, rmColor, rmRgb;

    if (typeof rmAccent === 'undefined') {

        rmAccent = jQuery("#rm-panel-accent").val();

        if (rmAccent === "FFFFFF") 

        rmAccent = "";

           

        var data = {

                'action' : 'rm_save_fab_settings',
                'rm_sec_nonce': rm_admin_vars.nonce,
                'fab_color' : rmAccent,
                'rm_slug' : 'rm_front_save_fab_settings'

           };

           

           jQuery.post (ajaxurl, data, function(){

               location.reload();

           });

        }

        

        if (typeof rmTheme === 'undefined') {

           rmTheme = jQuery("#rm-panel-theme").val();

           

           var data = {

               'action' : 'rm_save_fab_settings',
               'rm_sec_nonce': rm_admin_vars.nonce,
               'fab_theme' : rmTheme,
               'rm_slug' : 'rm_front_save_fab_settings'

           };

           

           jQuery.post(ajaxurl,data,function(){

               location.reload();

           });

        }

        

        rmAccentRed = rmAccent.slice(0, 2);

        rmAccentRed = parseInt(rmAccentRed, 16);

        rmAccentGreen = rmAccent.slice(2, 4);

        rmAccentGreen = parseInt(rmAccentGreen, 16);

        rmAccentBlue = rmAccent.slice(4, 6);

        rmAccentBlue = parseInt(rmAccentBlue, 16);

        rmRgb = [rmAccentRed, rmAccentGreen, rmAccentBlue];

        var rmRgbMax = Math.max.apply(Math, rmRgb);

        rmRgbMax /= 255;

        var rmRgbMin = Math.min.apply(Math, rmRgb);

        rmRgbMin /= 255;

        var rmLum = rmRgbMax + rmRgbMin;

        rmLum /= 2;

        rmLum *=100

        rmLum = parseInt(rmLum, 10);

        if (rmRgbMax === rmRgbMin) {

            var rmSat = 0;

            } else if (rmRgbMax != rmRgbMin && rmLum < 50) {

            var rmSat = (rmRgbMax - rmRgbMin) / (rmRgbMax + rmRgbMin);

            } else {

            rmSat = (rmRgbMax - rmRgbMin) / (2.0 - rmRgbMax - rmRgbMin);

            }

        rmSat *= 100;

        rmSat = parseInt(rmSat);

        if (rmSat > 50 && rmLum > 40) {

            rmRgb.sort(function (a, b) {return b - a; });

            if (rmRgb[0] > 100 && rmRgb[0] <= 155) {

                rmAccentRed += 100;

                rmAccentGreen += 100;

                rmAccentBlue += 100;

                } else if (rmRgb[0] <= 100 && rmRgb[0] > 55) {

                rmAccentRed += 155;

                rmAccentGreen += 155;

                rmAccentBlue += 155;

                } else if (rmRgb[0] <= 55) {

                rmAccentRed += 200;

                rmAccentGreen += 200;

                rmAccentBlue += 200;

                } else if (rmRgb[0] > 155 && rmRgb[2] >= 120) {

                rmAccentRed -= 100;

                rmAccentGreen -= 100;

                rmAccentBlue -= 100;

                } else if (rmRgb[0] > 155 && rmRgb[2] < 100) {

                rmAccentRed = 255;

                rmAccentGreen = 255;

                rmAccentBlue = 255;

                } else {

                rmAccentRed = rmAccentRed - 120;

                rmAccentGreen = rmAccentGreen - 120;

                rmAccentBlue = rmAccentBlue - 120;

                }

        }    else if (rmSat === 0 && rmLum > 50) {

                rmAccentRed = 50;

                rmAccentBlue = 50;

                rmAccentGreen = 50;

            } else if (rmSat === 100) {

                rmAccentRed = 255;

                rmAccentBlue = 255;

                rmAccentGreen = 255;

            } else if (rmLum < 50 && rmSat >= 50) {

                rmAccentRed -= 100;

                rmAccentBlue -= 100;

                rmAccentGreen -= 100;

            } else if (rmLum >= 60 && rmSat > 50) {

                rmAccentRed = 50;

                rmAccentBlue = 50;

                rmAccentGreen = 50;

            } else {

                rmAccentRed = 255;

                rmAccentBlue = 255;

                rmAccentGreen = 255;

            }

            

        rmColor = "rgb" + "(" + rmAccentRed + ", " + rmAccentGreen + ", " + rmAccentBlue + ")";

        rmAccent = "#" + rmAccent;

        jQuery(".rm-accent-bg").css({'background-color': rmAccent, 'color': rmColor});

        jQuery(".rm-accent-bg").children("a").css('color', rmColor);

        

        

        if (rmTheme === "Dark") {
            
             jQuery(".rm-magic-popup, .rm-floating-page").addClass ('rm-floating-page-dark-theme');
            
            jQuery(".rm-floating-page").addClass ('rm-floating-page-dark');

            jQuery("#rm-menu").css('color', '#e1e1e1');

            jQuery(".rm-white-box").css({'background-color': '#323232', 'border-width': '0px'});

            jQuery(".rm-grey-box").css({'background-color': '#e1e1e1', 'color': '#969696'});

            jQuery(".rm-border").css('border-color', '#646464');

            jQuery("#rm-panel-page").css({'color': '#e1e1e1', 'background-color': 'rgba(0,0,0,0.9)'});

            jQuery(".rm-popup-item").hover(function () {

                jQuery(this).css('color', rmAccent);

            },

                function () {

                    jQuery(this).css('color', '#e1e1e1');

                });
                
                jQuery(".rm-popup-menu").on('mouseleave','.rm-popup-item',function () {
                jQuery(".rm-popup-item-log-off").css('color', '#FF3030');
             

                 });
            
                jQuery(".rm-popup-item-log-off").css('color', '#FF3030');
                
                

        } else {jQuery("#rm-menu").css('color', '#646464');
            
            jQuery(".rm-magic-popup, .rm-floating-page").addClass ('rm-floating-page-light-theme');

            jQuery(".rm-white-box").css('background-color', '#fafafa');

            jQuery(".rm-grey-box").css('background-color', '#f0f0f0');

            jQuery("#rm-panel-page").css({'color': '#646464', 'background-color': 'rgba(255,255,255)'});

            /*Use event delegation instead of .hover(), so that dynamically added menu can use the effect as well (cart for example).               */
            jQuery(".rm-popup-menu").on('mouseenter','.rm-popup-item',function () {
                jQuery(this).css({'background-color': rmAccent, 'color': rmColor});
            });

            jQuery(".rm-popup-menu").on('mouseleave','.rm-popup-item',function () {
                jQuery(this).css({'background-color': '#ffffff', 'color': '#646464'});
                jQuery(".rm-popup-item-log-off").css('color', '#FF3030');
                jQuery(".rm-popup-welcome-box").css('background-color', '#fafafa');
            });
            
            jQuery(".rm-popup-item-log-off").css('color', '#FF3030');

            }

}



var themeBoxFlag="up";

function close_theme_box()

{  

    /*if(themeBoxFlag=="up"){

        jQuery( "#rm-color-switcher" ).animate({marginBottom :"-=35%"},'slow');

        themeBoxFlag="down";

        jQuery("#rm-white-toggle").removeClass("box_up");

        jQuery("#rm-white-toggle").css('color',jQuery(".rm-accent-bg").css('color'));

        jQuery("#rm-white-toggle").addClass("box_down");

    }      

    else{

        jQuery( "#rm-color-switcher" ).animate({marginBottom :"+=35%"},'slow');

        themeBoxFlag="up";

        jQuery("#rm-white-toggle").removeClass("box_down");

        jQuery("#rm-white-toggle").css('color',jQuery(".rm-accent-bg").css('color'));

        jQuery("#rm-white-toggle").addClass("box_up");

    }*/

    jQuery( "#rm-color-switcher" ).fadeToggle();     

}
