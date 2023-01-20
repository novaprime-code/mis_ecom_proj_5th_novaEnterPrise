<?php
add_filter('woocommerce_json_search_found_products', 'BR_woocommerce_json_search_found_products');
function BR_woocommerce_json_search_found_products($products) {
    if( ! empty($_GET['is_berocket']) ) {
        $new_products = $products;
        $products = array();
        $current_language = apply_filters( 'wpml_current_language', NULL );
        foreach($new_products as $product_id => $product_name) {
            $product_id = apply_filters( 'wpml_object_id', $product_id, 'product', true, $current_language );
            $products[$product_id] = get_the_title($product_id). ' (#'.$product_id.')';
        }
    }
    return $products;
}
