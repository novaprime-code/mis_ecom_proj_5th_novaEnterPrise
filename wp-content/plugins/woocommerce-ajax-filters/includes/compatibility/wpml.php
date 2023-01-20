<?php
if( ! function_exists('br_aapf_md5_cache_text_fix') ) {
    add_filter('br_aapf_md5_cache_text', 'br_aapf_md5_cache_text_fix');
    function br_aapf_md5_cache_text_fix($text) {
        if ( (defined( 'WCML_VERSION' ) || defined('POLYLANG_VERSION')) && defined( 'ICL_LANGUAGE_CODE' ) ) {
            $text = $text.ICL_LANGUAGE_CODE;
        }
        if( function_exists('wpm_get_language') ) {
            $text = $text.'_'.wpm_get_language();
        }
        return $text;
    }
}
