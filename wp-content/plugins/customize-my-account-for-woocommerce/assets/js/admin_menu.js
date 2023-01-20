var $kaz = jQuery.noConflict();
(function( $kaz ) {
    'use strict';

    $kaz(function() {
        
        $kaz("li#toplevel_page_sysbasics a.toplevel_page_sysbasics").removeAttr("href");
        $kaz("li#toplevel_page_sysbasics a.toplevel_page_woomatrix").removeAttr("href");

    });

 
})( jQuery );