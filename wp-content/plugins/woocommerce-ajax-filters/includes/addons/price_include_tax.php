<?php
class BeRocket_AAPF_price_use_tax {
    public $tax_rates;
    function __construct() {
        $tax_display_mode = get_option( 'woocommerce_tax_display_shop' );
        if ( wc_tax_enabled() 
        && ( ( ! wc_prices_include_tax() && 'incl' === $tax_display_mode ) 
        || ( wc_prices_include_tax() && 'excl' === $tax_display_mode ) )
        ) {
            $tax_class = apply_filters( 'woocommerce_price_filter_widget_tax_class', '' );
            $this->tax_rates = WC_Tax::get_rates( $tax_class );
            if( $this->tax_rates ) {
                if( ! wc_prices_include_tax() ) {
                    $convert_class = array(
                        'to'    => 'include_tax',
                        'from'  => 'exclude_min_max_filter'
                    );
                } else {
                    $convert_class = array(
                        'to'    => 'exclude_tax',
                        'from'  => 'include_min_max_filter'
                    );
                }
                $BeRocket_AAPF = BeRocket_AAPF::getInstance();
                $option = $BeRocket_AAPF->get_option();
                if( $option['use_tax_for_price'] == 'var2' ) {
                    add_filter( 'berocket_price_slider_widget_min_amount', array( $this, $convert_class['to'] ), 5 );
                    add_filter( 'berocket_price_slider_widget_max_amount', array( $this, $convert_class['to'] ), 5 );
                    add_filter('berocket_min_max_filter', array( $this, $convert_class['from'] ), 15 );
                    add_filter('berocket_min_max_filter_range', array( $this, $convert_class['from'] ), 15 );
                } else {
                    add_filter( 'berocket_price_filter_widget_min_amount', array( $this, $convert_class['to'] ), 5 );
                    add_filter( 'berocket_price_filter_widget_max_amount', array( $this, $convert_class['to'] ), 5 );
                    add_filter('berocket_min_max_filter', array( $this, $convert_class['from'] ), 15 );
                }
            }
        }
    }
    function include_min_max_filter($price) {
        return $this->min_max_filter($price, 'include_tax');
    }
    function exclude_min_max_filter($price) {
        return $this->min_max_filter($price, 'exclude_tax');
    }
    function min_max_filter($price, $type) {
        if( is_array($price) ) {
            foreach($price as &$prices) {
                $prices = $this->$type($prices);
            }
            if( isset($prices) ) {
                unset($prices);
            }
        } else {
            $price = $this->$type($price);
        }
        return $price;
    }
    function include_tax($price) {
        $price += WC_Tax::get_tax_total( WC_Tax::calc_exclusive_tax( $price, $this->tax_rates ) );
        return $price;
    }
    function exclude_tax($price) {
        $price -= WC_Tax::get_tax_total( WC_Tax::calc_inclusive_tax( $price, $this->tax_rates ) );
        return $price;
    }
}
new BeRocket_AAPF_price_use_tax();
