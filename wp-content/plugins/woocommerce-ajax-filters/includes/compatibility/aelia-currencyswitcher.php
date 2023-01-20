<?php
if ( ! function_exists( 'wmc_get_default_price' ) ) {
	function wmc_get_default_price( $price ) {
        $price_multiplier = $GLOBALS['woocommerce-aelia-currencyswitcher']->current_exchange_rate();
        $price = $price / $price_multiplier;

		return $price;
	}
}