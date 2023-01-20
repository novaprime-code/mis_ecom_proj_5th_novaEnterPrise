<?php

if ( ! class_exists('BeRocket_AAPF_compat_woojetpack') ) {

    class BeRocket_AAPF_compat_woojetpack {
        function __construct() {
            add_filter('berocket_aapf_get_attribute_values_post__not_in_outside', array($this, 'product_by_user_role'));
        }
        function product_by_user_role( $query_args ) {
            if ( ! function_exists('wcj_get_current_user_all_roles') ) return $query_args;

            $option_to_check        = wcj_get_current_user_all_roles();
            $post__not_in           = ( isset( $query_args['post__not_in'] ) ? $query_args['post__not_in'] : array() );
            $args                   = $query_args;
            $args['fields']         = 'ids';
            $args['posts_per_page'] = -1;
            $args['post_type']      = 'product';
            $loop                   = new WP_Query( $args );
            foreach ( $loop->posts as $product_id ) {
                if ( ! $this->is_product_visible( $product_id, $option_to_check, 'product_by_user_role' ) ) {
                    $post__not_in[] = $product_id;
                }
            }

            return $post__not_in;
        }

        function is_product_visible( $product_id, $option_to_check, $id ) {
            if ( ! function_exists('wcj_maybe_get_product_id_wpml') ) return true;

            if ( 'invisible' != apply_filters( 'booster_option', 'visible', get_option( 'wcj_' . $id . '_visibility_method', 'visible' ) ) ) {
                $visible = get_post_meta( wcj_maybe_get_product_id_wpml( $product_id ), '_' . 'wcj_' . $id . '_visible', true );
                if ( ! empty( $visible ) && is_array( $visible ) ) {
                    if ( is_array( $option_to_check ) ) {
                        $the_intersect = array_intersect( $visible, $option_to_check );
                        if ( empty( $the_intersect ) ) {
                            return false;
                        }
                    } else {
                        if ( ! in_array( $option_to_check, $visible ) ) {
                            return false;
                        }
                    }
                }
            }
            if ( 'visible' != apply_filters( 'booster_option', 'visible', get_option( 'wcj_' . $id . '_visibility_method', 'visible' ) ) ) {
                $invisible = get_post_meta( wcj_maybe_get_product_id_wpml( $product_id ), '_' . 'wcj_' . $id . '_invisible', true );
                if ( ! empty( $invisible ) && is_array( $invisible ) ) {
                    if ( is_array( $option_to_check ) ) {
                        $the_intersect = array_intersect( $invisible, $option_to_check );
                        if ( ! empty( $the_intersect ) ) {
                            return false;
                        }
                    } else {
                        if ( in_array( $option_to_check, $invisible ) ) {
                            return false;
                        }
                    }
                }
            }
            return true;
        }
    }
    new BeRocket_AAPF_compat_woojetpack();
}
