var $vas = jQuery.noConflict();
(function( $vas ) {
    'use strict';

    $vas(function() {
        
      $vas(".wcmamtx_group").on('click',function(event) {
      	  event.preventDefault();

      	  var parentli = $vas(this).parents("li");

      	  if (parentli.hasClass("open")) {
      	  	 parentli.find("ul.wcmamtx_sub_level").hide();
      	  	 parentli.removeClass("open");
      	  	 parentli.addClass("closed");

      	  } else if (parentli.hasClass("closed")) {
      	  	 parentli.find("ul.wcmamtx_sub_level").show();
      	  	 parentli.removeClass("closed");
      	  	 parentli.addClass("open");
      	  }

          return false;
          
      });
        
    });

 
})( jQuery );
