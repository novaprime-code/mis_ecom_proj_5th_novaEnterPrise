function fixWooIsotope() {
	if (dtGlobals.isPhone) {
		jQuery(window).trigger("scroll");
		return;
	}

	var $container = jQuery(the_ajax_script.products_holder_id);
	var $dataAttrContainer = $container,
	i = 0,
	contWidth = parseInt($dataAttrContainer.attr("data-width")),
	contNum = parseInt($dataAttrContainer.attr("data-columns")),
	desktopNum = parseInt($dataAttrContainer.attr("data-desktop-columns-num")),
	tabletHNum = parseInt($dataAttrContainer.attr("data-h-tablet-columns-num")),
	tabletVNum = parseInt($dataAttrContainer.attr("data-v-tablet-columns-num")),
	phoneNum = parseInt($dataAttrContainer.attr("data-phone-columns-num")),
	contPadding = parseInt($dataAttrContainer.attr("data-padding"));

	$container.addClass("cont-id-"+i).attr("data-cont-id", i);
 
	jQuery(window).off("columnsReady");

	$container.off("columnsReady.fixWooIsotope").one("columnsReady.fixWooIsotope.IsoInit", function() {
		$container.addClass("dt-isotope").IsoInitialisation('.iso-item', 'masonry', 400);
		$container.isotope("on", "layoutComplete", function () {
			$container.trigger("IsoReady");
		});
	});
	
	$container.on("columnsReady.fixWooIsotope.IsoLayout", function() {
		$container.isotope("layout");
	});
	
	$container.one("columnsReady.fixWooIsotope", function() {
			jQuery(".preload-me", $container).heightHack();
	});

	$container.one("IsoReady", function() {
		$container.IsoLayzrInitialisation();
	});
	
	jQuery(window).off("debouncedresize.fixWooIsotope").on("debouncedresize.fixWooIsotope", function () {
		$container.calculateColumns(contWidth, contNum, contPadding, desktopNum, tabletHNum, tabletVNum, phoneNum, "px");
		if(contPadding > 10){
			$container.addClass("mobile-paddings");
		}
	}).trigger("debouncedresize.fixWooIsotope");
	jQuery(window).trigger("scroll");
}

function fixWooOrdering() {
	jQuery('.woocommerce-ordering-div select').each(function(){
		jQuery(this).customSelect();
	});
}
jQuery(document).on('berocket_ajax_products_loaded berocket_lmp_end', function() {
    fixWooIsotope();
    fixWooOrdering();
});
