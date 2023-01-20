<?php
class BeRocket_AAPF_get_terms_additionals {
    public $BeRocket_AAPF;
    function __construct() {
        $this->BeRocket_AAPF = BeRocket_AAPF::getInstance();
        add_filter('berocket_aapf_get_terms_filter_after', array($this, 'add_value'));
        add_filter('berocket_aapf_get_terms_filter_after_not_correct', array($this, 'add_value'));
    }
    function add_value($terms) {
        if( ! empty($terms) && is_array($terms) ) {
            $options = $this->BeRocket_AAPF->get_option();
            foreach($terms as &$term) {
                $term->value = ( empty($options['slug_urls']) ? $term->term_id : $term->slug );
            }
            if( isset($term) ) {
                unset($term);
            }
        }
        return $terms;
    }
}
