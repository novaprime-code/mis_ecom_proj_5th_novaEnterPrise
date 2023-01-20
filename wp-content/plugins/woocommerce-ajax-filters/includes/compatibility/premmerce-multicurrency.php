<?php
if ( ! function_exists( 'wmc_get_default_price' ) && function_exists('premmerce_multicurrency') ) {
	function wmc_get_default_price( $price, $currency_code = false ) {
        $price_multiplier = premmerce_multicurrency()->convertToUserCurrency(1, false);
        $price = $price / $price_multiplier;

		return $price;
	}
}