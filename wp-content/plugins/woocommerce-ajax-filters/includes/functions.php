<?php
if( ! function_exists( 'bapf_template_styles_preview' ) ){
    function bapf_template_styles_preview() {
        include_once('template_styles_preview.php');
    }
}
if( ! function_exists( 'br_set_value_to_array' ) ){
    function br_set_value_to_array(&$arr, $index, $value = '') {
        if( ! isset($arr) || ! is_array($arr) ) {
            $arr = array();
        }
        if( ! is_array($index) ) {
            $index = array($index);
        }
        $array = &$arr;
        foreach($index as $i) {
            if( ! isset($array[$i]) ) {
                $array[$i] = array();
            }
            $array2 = &$array[$i];
            unset($array);
            $array = &$array2;
        }
        $array = $value;
        return $arr;
    }
}
if( ! function_exists('braapf_filters_must_be_recounted') ) {
    function braapf_filters_must_be_recounted($type = 'different') {
        $BeRocket_AAPF = BeRocket_AAPF::getInstance();
        $br_options = $BeRocket_AAPF->get_option();
        if( $type === 'different' ) {
            return in_array(br_get_value_from_array($br_options, 'recount_hide'), array('recount', 'removeFirst_recount', 'removeRecount'));
        } elseif( $type === 'first' ) {
            return in_array(br_get_value_from_array($br_options, 'recount_hide'), array('removeFirst', 'removeFirst_recount', 'removeRecount'));
        } elseif( $type === 'none' ) {
            return ( ! br_get_value_from_array($br_options, 'recount_hide') || br_get_value_from_array($br_options, 'recount_hide') !== 'disable' );
        } else {
            return ( br_get_value_from_array($br_options, 'recount_hide') && br_get_value_from_array($br_options, 'recount_hide') !== 'disable' );
        }
    }
}
if( ! function_exists( 'berocket_aapf_insert_to_array' ) ){
    function berocket_aapf_insert_to_array($array, $key_in_array, $array_to_insert, $before = false) {
        $position = array_search($key_in_array, array_keys($array), true);
        if( $position !== FALSE ) {
            if( ! $before ) {
                $position++;
            }
            $array = array_slice($array, 0, $position, true) +
                                $array_to_insert +
                                array_slice($array, $position, NULL, true);
        }
        return $array;
    }
}
if( ! function_exists( 'br_get_current_language_code' ) ){
    /**
     * Permalink block in settings
     *
     */
    function br_get_current_language_code() {
        $language = '';
        if( function_exists( 'qtranxf_getLanguage' ) ) {
            $language = qtranxf_getLanguage();
        }
        if( defined('ICL_LANGUAGE_CODE') ) {
            $language = ICL_LANGUAGE_CODE;
        }
        return $language;
    }
}
if( ! function_exists( 'berocket_wpml_attribute_translate' ) ){
    function berocket_wpml_attribute_translate($slug) {
        $wpml_slug = apply_filters( 'wpml_translate_single_string', $slug, 'WordPress', sprintf( 'URL attribute slug: %s', $slug ) );
        if( $wpml_slug != $slug ) {
            $translations = get_option('berocket_wpml_attribute_slug_untranslate');
            if( ! is_array($translations) ) {
                $translations = array();
            }
            $translations[$wpml_slug] = $slug;
            update_option('berocket_wpml_attribute_slug_untranslate', $translations);
        }
        return $wpml_slug;
    }
}
if( ! function_exists( 'berocket_wpml_attribute_untranslate' ) ){
    function berocket_wpml_attribute_untranslate($slug) {
        $translations = get_option('berocket_wpml_attribute_slug_untranslate');
        if( is_array($translations) && ! empty($translations[$slug]) ) {
            $slug = $translations[$slug];
        }
        return $slug;
    }
}

if( ! function_exists( 'br_get_template_part' ) ){
    /**
     * Public function to get plugin's template
     *
     * @param string $name Template name to search for
     *
     * @return void
     */
    function br_get_template_part( $name = '' ){
        $BeRocket_AAPF = BeRocket_AAPF::getInstance();
        $BeRocket_AAPF->br_get_template_part( $name );
    }
}

if( ! function_exists( 'br_is_filtered' ) ){
    /**
     * Public function to check if filter set
     *
     * @param bool $filters is filter set
     * @param bool $limits is limit set
     * @param bool $price is price set
     *
     * @return bool
     */
    function br_is_filtered(){
        $filtered = false;
        global $berocket_parse_page_obj;
        $data = $berocket_parse_page_obj->get_current();
        if( ! empty($data) && ! empty($data['filters']) && count($data['filters']) > 0 ) {
            $filtered = true;
        }
        return $filtered;
    }
}

if( ! function_exists( 'br_get_cache' ) ){
    /**
     * Get cached object
     *
     * @param string $key Key to find value
     * @param string $group Group with keys
     * @param string $cache_type Type of cache 'wordpress' or 'persistent'
     *
     * @return mixed
     */
    function br_get_cache( $key, $group ){
        return apply_filters('br_get_cache', false, $key, $group);
    }
}

if( ! function_exists( 'br_set_cache' ) ){
    /**
     * Save object to cache
     *
     * @param string $key Key to save value
     * @param mixed $value Value to save
     * @param string $group Group with keys
     * @param int $expire Expiration time in seconds
     * @param string $cache_type Type of cache 'wordpress' or 'persistent'
     *
     * @return void
     */
    function br_set_cache( $key, $value, $group, $expire ){
        return apply_filters('br_set_cache', true, $key, $value, $group, $expire);
    }
}

if ( ! function_exists( 'br_is_term_selected' ) ) {
    /**
     * Public function to check if term is selected
     *
     * @param object $term - Term to check for
     * @param boolean $checked - if TRUE return ' checked="checked"'
     * @param boolean $child_parent - if TRUE search child selected
     * @param integer $depth - current term depth in hierarchy
     *
     * @return string ' selected="selected"' if selected, empty string '' if not selected
     */
    function br_is_term_selected( $term, $checked = FALSE, $child_parent = FALSE, $depth = 0, $additional = array() ) {
        //TODO: Notice: Trying to get property 'taxonomy' of non-object
        $term_taxonomy = apply_filters('br_is_term_selected_taxonomy', $term->taxonomy, $term);
        if( $term_taxonomy == '_rating' ) {
            $term_taxonomy = 'product_visibility';
        }
        $is_checked = apply_filters('br_is_term_selected_checked', false, $term_taxonomy, $term, $checked, $child_parent, $depth);

        if ( ! $is_checked && $child_parent ) {
            $selected_terms = br_get_selected_term( $term_taxonomy, $additional );
            foreach( $selected_terms as $selected_term ) {
                $ancestors = get_ancestors( $selected_term, $term_taxonomy );
                if( count( $ancestors ) > $depth ) {
                    if ( $ancestors[count($ancestors) - ( $depth + 1 )] == $term->term_id ) {
                        $is_checked = true;
                    }
                }
            }
        }
        if( ! $is_checked ) {
            global $berocket_parse_page_obj;
            $filter_data = $berocket_parse_page_obj->get_current();
            if( isset($filter_data['filters']) && is_array($filter_data['filters']) ) {
                foreach($filter_data['filters'] as $filter) {
                    $is_checked_correct = $filter['taxonomy'] == $term_taxonomy && in_array($term->term_id, $filter['val_ids']);
                    if( apply_filters('br_is_term_selected_checked_each', $is_checked_correct, $term_taxonomy, $term, $checked, $child_parent, $depth, $filter, $additional) ) {
                        $is_checked = true;
                    }
                }
            }
        }
        if( $is_checked ) {
            if($checked) return ' checked="checked"';
            else return ' selected="selected"';
        }
        return '';
    }
}

if ( ! function_exists( 'br_get_selected_term' ) ) {
    /**
     * Public function to get all selected terms in taxonomy
     *
     * @param object $taxonomy - Taxonomy name
     *
     * @return array selected terms
     */
    function br_get_selected_term( $taxonomy, $additional = array() ) {
        global $berocket_parse_page_obj;
        $filter_data = $berocket_parse_page_obj->get_current();
        $term_ids = array();
        if( isset($filter_data['filters']) && is_array($filter_data['filters']) ) {
            foreach($filter_data['filters'] as $filter) {
                $is_checked_correct = $filter['taxonomy'] == $taxonomy;
                if( apply_filters('br_get_selected_term_checked_each', $is_checked_correct, $filter, $taxonomy, $additional) ) {
                    $term_ids = array_merge($term_ids, $filter['val_ids']);
                }
            }
        }
        return $term_ids;
    }
}

if( ! function_exists( 'br_aapf_get_attributes' ) ) {
    /**
     * Get all possible woocommerce attribute taxonomies
     *
     * @return mixed|void
     */
    function br_aapf_get_attributes() {
        $attribute_taxonomies = wc_get_attribute_taxonomies();
        $attributes           = array();

        if ( $attribute_taxonomies ) {
            foreach ( $attribute_taxonomies as $tax ) {
                $attributes[ wc_attribute_taxonomy_name( $tax->attribute_name ) ] = $tax->attribute_label;
            }
        }

        return apply_filters( 'berocket_aapf_get_attributes', $attributes );
    }
}

if( ! function_exists( 'br_aapf_parse_order_by' ) ) {
    /**
     * br_aapf_parse_order_by - parsing order by data and saving to $args array that was passed into
     *
     * @param $args
     */
    function br_aapf_parse_order_by( &$args ) {
        $orderby = $_GET['orderby'] = $_POST['orderby'];
        $order   = "ASC";
        if ( preg_match( "/-/", ( empty($orderby) ? '' : $orderby ) ) ) {
            list( $orderby, $order ) = explode( "-", $orderby );
        }
        $order = strtoupper($order);

        // needed for woocommerce sorting funtionality
        if ( ! empty($orderby) and ! empty($order) ) {

            $BeRocket_AAPF = BeRocket_AAPF::getInstance();
            // Get ordering from query string unless defined
            $orderby = strtolower( $orderby );
            $order   = strtoupper( $order );

            // default - menu_order
            $args['orderby']  = 'menu_order title';
            $args['order']    = $order == 'DESC' ? 'DESC' : 'ASC';

            switch ( strtolower($orderby) ) {
                case 'rand' :
                    $args['orderby']  = 'rand';
                    break;
                case 'date' :
                    $args['orderby']  = 'date';
                    $args['order']    = $order == 'ASC' ? 'ASC' : 'DESC';
                    break;
                case 'price' :
                    $args['orderby']  = 'meta_value_num';
                    $args['order']    = $order == 'DESC' ? 'DESC' : 'ASC';
                    $args['meta_key'] = apply_filters('berocket_price_filter_meta_key', '_price', 'functions_280');
                    break;
                case 'popularity' :
                    $args['meta_key'] = 'total_sales';

                    // Sorting handled later though a hook
                    add_filter( 'posts_clauses', array( $BeRocket_AAPF, 'order_by_popularity_post_clauses' ) );
                    break;
                case 'rating' :
                    // Sorting handled later though a hook
                    add_filter( 'posts_clauses', array( $BeRocket_AAPF, 'order_by_rating_post_clauses' ) );
                    break;
                case 'title' :
                    $args['orderby']  = 'title';
                    $args['order']    = $order == 'DESC' ? 'DESC' : 'ASC';
                    break;
                default:
                    break;
            }
        }
    }
}

function br_widget_is_hide( $attribute, $widget_is_hide = false ) {
    if ( $widget_is_hide ) {
        if( $attribute == '_rating' ) {
            $attribute = 'product_visibility';
        }
        global $berocket_parse_page_obj;
        $filter_data = $berocket_parse_page_obj->get_current();
        $term_ids = array();
        if( isset($filter_data['filters']) && is_array($filter_data['filters']) ) {
            foreach($filter_data['filters'] as $filter) {
                if($filter['taxonomy'] == $attribute || $filter['attr'] == $attribute) {
                    return false;
                }
            }
        }
    }

    return $widget_is_hide;
}

if ( ! function_exists( 'br_aapf_get_styled' ) ) {
    function br_aapf_get_styled() {
        return array(
            "title"          => array(
                "name" => __('Widget Title', 'BeRocket_AJAX_domain'),
                "has"  => array(
                    "color"       => true,
                    "bold"        => true,
                    "font_family" => true,
                    "font_size"   => true,
                    "item_size"   => false,
                    "theme"       => false,
                    "image"       => false,
                ),
            ),
            "label"          => array(
                "name" => __('Label(checkbox/radio)', 'BeRocket_AJAX_domain'),
                "has"  => array(
                    "color"       => true,
                    "bold"        => true,
                    "font_family" => true,
                    "font_size"   => true,
                    "item_size"   => false,
                    "theme"       => false,
                    "image"       => false,
                ),
            ),
            "selectbox"      => array(
                "name" => __("Drop-Down", 'BeRocket_AJAX_domain'),
                "has"  => array(
                    "color"       => false,
                    "bold"        => false,
                    "font_family" => false,
                    "font_size"   => false,
                    "item_size"   => false,
                    "theme"       => true,
                    "image"       => false,
                ),
            ),
            "slider_input"   => array(
                "name" => __("Slider Inputs", 'BeRocket_AJAX_domain'),
                "has"  => array(
                    "color"       => true,
                    "bold"        => true,
                    "font_family" => true,
                    "font_size"   => true,
                    "item_size"   => false,
                    "theme"       => false,
                    "image"       => false,
                ),
            ),
            "description"    => array(
                "name" => __("Description Block", 'BeRocket_AJAX_domain'),
                "has"  => array(
                    "color"       => true,
                    "bold"        => false,
                    "font_family" => false,
                    "font_size"   => false,
                    "item_size"   => true,
                    "theme"       => false,
                    "image"       => false,
                ),
            ),
            "description_border"    => array(
                "name" => __("Description Block Border", 'BeRocket_AJAX_domain'),
                "has"  => array(
                    "color"       => true,
                    "bold"        => false,
                    "font_family" => false,
                    "font_size"   => false,
                    "item_size"   => true,
                    "theme"       => false,
                    "image"       => false,
                ),
            ),
            "description_title"    => array(
                "name" => __("Description Block Title", 'BeRocket_AJAX_domain'),
                "has"  => array(
                    "color"       => true,
                    "bold"        => true,
                    "font_family" => true,
                    "font_size"   => true,
                    "item_size"   => false,
                    "theme"       => false,
                    "image"       => false,
                ),
            ),
            "description_text"    => array(
                "name" => __("Description Block Text", 'BeRocket_AJAX_domain'),
                "has"  => array(
                    "color"       => true,
                    "bold"        => true,
                    "font_family" => true,
                    "font_size"   => true,
                    "item_size"   => false,
                    "theme"       => false,
                    "image"       => false,
                ),
            ),
            "selected_area"    => array(
                "name" => __("Selected filters area text", 'BeRocket_AJAX_domain'),
                "has"  => array(
                    "color"       => true,
                    "bold"        => true,
                    "font_family" => true,
                    "font_size"   => true,
                    "item_size"   => false,
                    "theme"       => false,
                    "image"       => false,
                ),
            ),
            "selected_area_hover"    => array(
                "name" => __("Selected filters area mouse over the text", 'BeRocket_AJAX_domain'),
                "has"  => array(
                    "color"       => true,
                    "bold"        => true,
                    "font_family" => true,
                    "font_size"   => true,
                    "item_size"   => false,
                    "theme"       => false,
                    "image"       => false,
                ),
            ),
            "selected_area_block"    => array(
                "name" => __("Selected filters area link background", 'BeRocket_AJAX_domain'),
                "has"  => array(
                    "color"       => true,
                    "bold"        => false,
                    "font_family" => false,
                    "font_size"   => false,
                    "item_size"   => true,
                    "theme"       => false,
                    "image"       => false,
                ),
            ),
            "selected_area_border"    => array(
                "name" => __("Selected filters area link border", 'BeRocket_AJAX_domain'),
                "has"  => array(
                    "color"       => true,
                    "bold"        => false,
                    "font_family" => false,
                    "font_size"   => false,
                    "item_size"   => true,
                    "theme"       => false,
                    "image"       => false,
                ),
            ),
        );
    }
}

if ( ! function_exists( 'br_aapf_converter_styles' ) ) {
    function br_aapf_converter_styles( $user_options = array() ) {
        $converted_styles = $converted_classes = array();
        $styled           = br_aapf_get_styled();
        $included_fonts   = array();
        if ( ! empty($user_options) ) {
            foreach ( $user_options as $element => $style ) {
                if ( ! empty($styled[ $element ]['has']) ) {
                    foreach ( $styled[ $element ]['has'] as $style_name => $use ) {
                        if ( $use ) {
                            if( empty($converted_styles[ $element ]) ) {
                                $converted_styles[ $element ] = '';
                            }
                            if ( $style_name == 'color' && ! empty($style['color']) ) {
                                @ $converted_styles[ $element ] .= "color: #" . ltrim( $style['color'], '#' ) . ";";
                            }
                            if ( $style_name == 'bold' and ! empty($style['bold']) ) {
                                @ $converted_styles[ $element ] .= "font-weight: {$style['bold']};";
                            }
                            if ( $style_name == 'font_size' && ! empty($style['font_size']) ) {
                                @ $converted_styles[ $element ] .= "font-size: " . ( (float) $style['font_size'] ) . "px;";
                            }


                            if ( $style_name == 'theme' ) {
                                if ( empty($style['theme']) ) {
                                    $style['theme'] = 'default';
                                } else {
                                    @ $converted_classes[ $element ] .= " themed";
                                }
                                if( empty($converted_classes[ $element ]) ) {
                                    @ $converted_classes[ $element ] = " " . $style['theme'];
                                } else {
                                    @ $converted_classes[ $element ] .= " " . $style['theme'];
                                }
                            }

                            if ( $style_name == 'font_family' and $style['font_family'] ) {
                                @ $converted_styles[ $element ] .= "font-family: '" . $style['font_family'] . "';";
                                if ( ! in_array( $style['font_family'], $included_fonts ) ) {
                                    $included_fonts[] = $style['font_family'];

                                    $http = ( is_ssl() ? 'https' : 'http' );
                                    wp_register_style( "berocket_aapf_widget-{$element}-font", $http . '://fonts.googleapis.com/css?family=' . urlencode( $style['font_family'] ) );
                                    wp_enqueue_style( "berocket_aapf_widget-{$element}-font" );
                                }
                            }
                        }
                    }
                }
            }
        }

        return array( "style" => $converted_styles, "class" => $converted_classes );
    }
}
if( ! function_exists('berocket_reset_orderby_clauses_popularity') ) {
    function berocket_reset_orderby_clauses_popularity($args) {
        $args['orderby'] = '';
        return $args;
    }
}

if ( ! function_exists( 'g_fonts_list' ) ) {
    function g_fonts_list() {
        return array(
            "ABeeZee",
            "Abel",
            "Abril Fatface",
            "Aclonica",
            "Acme",
            "Actor",
            "Adamina",
            "Advent Pro",
            "Aguafina Script",
            "Akronim",
            "Aladin",
            "Aldrich",
            "Alef",
            "Alegreya",
            "Alegreya SC",
            "Alegreya Sans",
            "Alegreya Sans SC",
            "Alex Brush",
            "Alfa Slab One",
            "Alice",
            "Alike",
            "Alike Angular",
            "Allan",
            "Allerta",
            "Allerta Stencil",
            "Allura",
            "Almendra",
            "Almendra Display",
            "Almendra SC",
            "Amarante",
            "Amaranth",
            "Amatic SC",
            "Amethysta",
            "Amiri",
            "Anaheim",
            "Andada",
            "Andika",
            "Angkor",
            "Annie Use Your Telescope",
            "Anonymous Pro",
            "Antic",
            "Antic Didone",
            "Antic Slab",
            "Anton",
            "Arapey",
            "Arbutus",
            "Arbutus Slab",
            "Architects Daughter",
            "Archivo Black",
            "Archivo Narrow",
            "Arimo",
            "Arizonia",
            "Armata",
            "Artifika",
            "Arvo",
            "Asap",
            "Asset",
            "Astloch",
            "Asul",
            "Atomic Age",
            "Aubrey",
            "Audiowide",
            "Autour One",
            "Average",
            "Average Sans",
            "Averia Gruesa Libre",
            "Averia Libre",
            "Averia Sans Libre",
            "Averia Serif Libre",
            "Bad Script",
            "Balthazar",
            "Bangers",
            "Basic",
            "Battambang",
            "Baumans",
            "Bayon",
            "Belgrano",
            "Belleza",
            "BenchNine",
            "Bentham",
            "Berkshire Swash",
            "Bevan",
            "Bigelow Rules",
            "Bigshot One",
            "Bilbo",
            "Bilbo Swash Caps",
            "Bitter",
            "Black Ops One",
            "Bokor",
            "Bonbon",
            "Boogaloo",
            "Bowlby One",
            "Bowlby One SC",
            "Brawler",
            "Bree Serif",
            "Bubblegum Sans",
            "Bubbler One",
            "Buda",
            "Buenard",
            "Butcherman",
            "Butterfly Kids",
            "Cabin",
            "Cabin Condensed",
            "Cabin Sketch",
            "Caesar Dressing",
            "Cagliostro",
            "Calligraffitti",
            "Cambay",
            "Cambo",
            "Candal",
            "Cantarell",
            "Cantata One",
            "Cantora One",
            "Capriola",
            "Cardo",
            "Carme",
            "Carrois Gothic",
            "Carrois Gothic SC",
            "Carter One",
            "Caudex",
            "Cedarville Cursive",
            "Ceviche One",
            "Changa One",
            "Chango",
            "Chau Philomene One",
            "Chela One",
            "Chelsea Market",
            "Chenla",
            "Cherry Cream Soda",
            "Cherry Swash",
            "Chewy",
            "Chicle",
            "Chivo",
            "Cinzel",
            "Cinzel Decorative",
            "Clicker Script",
            "Coda",
            "Coda Caption",
            "Codystar",
            "Combo",
            "Comfortaa",
            "Coming Soon",
            "Concert One",
            "Condiment",
            "Content",
            "Contrail One",
            "Convergence",
            "Cookie",
            "Copse",
            "Corben",
            "Courgette",
            "Cousine",
            "Coustard",
            "Covered By Your Grace",
            "Crafty Girls",
            "Creepster",
            "Crete Round",
            "Crimson Text",
            "Croissant One",
            "Crushed",
            "Cuprum",
            "Cutive",
            "Cutive Mono",
            "Damion",
            "Dancing Script",
            "Dangrek",
            "Dawning of a New Day",
            "Days One",
            "Dekko",
            "Delius",
            "Delius Swash Caps",
            "Delius Unicase",
            "Della Respira",
            "Denk One",
            "Devonshire",
            "Dhurjati",
            "Didact Gothic",
            "Diplomata",
            "Diplomata SC",
            "Domine",
            "Donegal One",
            "Doppio One",
            "Dorsa",
            "Dosis",
            "Dr Sugiyama",
            "Droid Sans",
            "Droid Sans Mono",
            "Droid Serif",
            "Duru Sans",
            "Dynalight",
            "EB Garamond",
            "Eagle Lake",
            "Eater",
            "Economica",
            "Ek Mukta",
            "Electrolize",
            "Elsie",
            "Elsie Swash Caps",
            "Emblema One",
            "Emilys Candy",
            "Engagement",
            "Englebert",
            "Enriqueta",
            "Erica One",
            "Esteban",
            "Euphoria Script",
            "Ewert",
            "Exo",
            "Exo 2",
            "Expletus Sans",
            "Fanwood Text",
            "Fascinate",
            "Fascinate Inline",
            "Faster One",
            "Fasthand",
            "Fauna One",
            "Federant",
            "Federo",
            "Felipa",
            "Fenix",
            "Finger Paint",
            "Fira Mono",
            "Fira Sans",
            "Fjalla One",
            "Fjord One",
            "Flamenco",
            "Flavors",
            "Fondamento",
            "Fontdiner Swanky",
            "Forum",
            "Francois One",
            "Freckle Face",
            "Fredericka the Great",
            "Fredoka One",
            "Freehand",
            "Fresca",
            "Frijole",
            "Fruktur",
            "Fugaz One",
            "GFS Didot",
            "GFS Neohellenic",
            "Gabriela",
            "Gafata",
            "Galdeano",
            "Galindo",
            "Gentium Basic",
            "Gentium Book Basic",
            "Geo",
            "Geostar",
            "Geostar Fill",
            "Germania One",
            "Gidugu",
            "Gilda Display",
            "Give You Glory",
            "Glass Antiqua",
            "Glegoo",
            "Gloria Hallelujah",
            "Goblin One",
            "Gochi Hand",
            "Gorditas",
            "Goudy Bookletter 1911",
            "Graduate",
            "Grand Hotel",
            "Gravitas One",
            "Great Vibes",
            "Griffy",
            "Gruppo",
            "Gudea",
            "Gurajada",
            "Habibi",
            "Halant",
            "Hammersmith One",
            "Hanalei",
            "Hanalei Fill",
            "Handlee",
            "Hanuman",
            "Happy Monkey",
            "Headland One",
            "Henny Penny",
            "Herr Von Muellerhoff",
            "Hind",
            "Holtwood One SC",
            "Homemade Apple",
            "Homenaje",
            "IM Fell DW Pica",
            "IM Fell DW Pica SC",
            "IM Fell Double Pica",
            "IM Fell Double Pica SC",
            "IM Fell English",
            "IM Fell English SC",
            "IM Fell French Canon",
            "IM Fell French Canon SC",
            "IM Fell Great Primer",
            "IM Fell Great Primer SC",
            "Iceberg",
            "Iceland",
            "Imprima",
            "Inconsolata",
            "Inder",
            "Indie Flower",
            "Inika",
            "Irish Grover",
            "Istok Web",
            "Italiana",
            "Italianno",
            "Jacques Francois",
            "Jacques Francois Shadow",
            "Jim Nightshade",
            "Jockey One",
            "Jolly Lodger",
            "Josefin Sans",
            "Josefin Slab",
            "Joti One",
            "Judson",
            "Julee",
            "Julius Sans One",
            "Junge",
            "Jura",
            "Just Another Hand",
            "Just Me Again Down Here",
            "Kalam",
            "Kameron",
            "Kantumruy",
            "Karla",
            "Karma",
            "Kaushan Script",
            "Kavoon",
            "Kdam Thmor",
            "Keania One",
            "Kelly Slab",
            "Kenia",
            "Khand",
            "Khmer",
            "Khula",
            "Kite One",
            "Knewave",
            "Kotta One",
            "Koulen",
            "Kranky",
            "Kreon",
            "Kristi",
            "Krona One",
            "La Belle Aurore",
            "Laila",
            "Lakki Reddy",
            "Lancelot",
            "Lateef",
            "Lato",
            "League Script",
            "Leckerli One",
            "Ledger",
            "Lekton",
            "Lemon",
            "Libre Baskerville",
            "Life Savers",
            "Lilita One",
            "Lily Script One",
            "Limelight",
            "Linden Hill",
            "Lobster",
            "Lobster Two",
            "Londrina Outline",
            "Londrina Shadow",
            "Londrina Sketch",
            "Londrina Solid",
            "Lora",
            "Love Ya Like A Sister",
            "Loved by the King",
            "Lovers Quarrel",
            "Luckiest Guy",
            "Lusitana",
            "Lustria",
            "Macondo",
            "Macondo Swash Caps",
            "Magra",
            "Maiden Orange",
            "Mako",
            "Mallanna",
            "Mandali",
            "Marcellus",
            "Marcellus SC",
            "Marck Script",
            "Margarine",
            "Marko One",
            "Marmelad",
            "Martel Sans",
            "Marvel",
            "Mate",
            "Mate SC",
            "Maven Pro",
            "McLaren",
            "Meddon",
            "MedievalSharp",
            "Medula One",
            "Megrim",
            "Meie Script",
            "Merienda",
            "Merienda One",
            "Merriweather",
            "Merriweather Sans",
            "Metal",
            "Metal Mania",
            "Metamorphous",
            "Metrophobic",
            "Michroma",
            "Milonga",
            "Miltonian",
            "Miltonian Tattoo",
            "Miniver",
            "Miss Fajardose",
            "Modak",
            "Modern Antiqua",
            "Molengo",
            "Molle",
            "Monda",
            "Monofett",
            "Monoton",
            "Monsieur La Doulaise",
            "Montaga",
            "Montez",
            "Montserrat",
            "Montserrat Alternates",
            "Montserrat Subrayada",
            "Moul",
            "Moulpali",
            "Mountains of Christmas",
            "Mouse Memoirs",
            "Mr Bedfort",
            "Mr Dafoe",
            "Mr De Haviland",
            "Mrs Saint Delafield",
            "Mrs Sheppards",
            "Muli",
            "Mystery Quest",
            "NTR",
            "Neucha",
            "Neuton",
            "New Rocker",
            "News Cycle",
            "Niconne",
            "Nixie One",
            "Nobile",
            "Nokora",
            "Norican",
            "Nosifer",
            "Nothing You Could Do",
            "Noticia Text",
            "Noto Sans",
            "Noto Serif",
            "Nova Cut",
            "Nova Flat",
            "Nova Mono",
            "Nova Oval",
            "Nova Round",
            "Nova Script",
            "Nova Slim",
            "Nova Square",
            "Numans",
            "Nunito",
            "Odor Mean Chey",
            "Offside",
            "Old Standard TT",
            "Oldenburg",
            "Oleo Script",
            "Oleo Script Swash Caps",
            "Open Sans",
            "Open Sans Condensed",
            "Oranienbaum",
            "Orbitron",
            "Oregano",
            "Orienta",
            "Original Surfer",
            "Oswald",
            "Over the Rainbow",
            "Overlock",
            "Overlock SC",
            "Ovo",
            "Oxygen",
            "Oxygen Mono",
            "PT Mono",
            "PT Sans",
            "PT Sans Caption",
            "PT Sans Narrow",
            "PT Serif",
            "PT Serif Caption",
            "Pacifico",
            "Paprika",
            "Parisienne",
            "Passero One",
            "Passion One",
            "Pathway Gothic One",
            "Patrick Hand",
            "Patrick Hand SC",
            "Patua One",
            "Paytone One",
            "Peddana",
            "Peralta",
            "Permanent Marker",
            "Petit Formal Script",
            "Petrona",
            "Philosopher",
            "Piedra",
            "Pinyon Script",
            "Pirata One",
            "Plaster",
            "Play",
            "Playball",
            "Playfair Display",
            "Playfair Display SC",
            "Podkova",
            "Poiret One",
            "Poller One",
            "Poly",
            "Pompiere",
            "Pontano Sans",
            "Port Lligat Sans",
            "Port Lligat Slab",
            "Prata",
            "Preahvihear",
            "Press Start 2P",
            "Princess Sofia",
            "Prociono",
            "Prosto One",
            "Puritan",
            "Purple Purse",
            "Quando",
            "Quantico",
            "Quattrocento",
            "Quattrocento Sans",
            "Questrial",
            "Quicksand",
            "Quintessential",
            "Qwigley",
            "Racing Sans One",
            "Radley",
            "Rajdhani",
            "Raleway",
            "Raleway Dots",
            "Ramabhadra",
            "Ramaraja",
            "Rambla",
            "Rammetto One",
            "Ranchers",
            "Rancho",
            "Ranga",
            "Rationale",
            "Ravi Prakash",
            "Redressed",
            "Reenie Beanie",
            "Revalia",
            "Ribeye",
            "Ribeye Marrow",
            "Righteous",
            "Risque",
            "Roboto",
            "Roboto Condensed",
            "Roboto Slab",
            "Rochester",
            "Rock Salt",
            "Rokkitt",
            "Romanesco",
            "Ropa Sans",
            "Rosario",
            "Rosarivo",
            "Rouge Script",
            "Rozha One",
            "Rubik Mono One",
            "Rubik One",
            "Ruda",
            "Rufina",
            "Ruge Boogie",
            "Ruluko",
            "Rum Raisin",
            "Ruslan Display",
            "Russo One",
            "Ruthie",
            "Rye",
            "Sacramento",
            "Sail",
            "Salsa",
            "Sanchez",
            "Sancreek",
            "Sansita One",
            "Sarina",
            "Sarpanch",
            "Satisfy",
            "Scada",
            "Scheherazade",
            "Schoolbell",
            "Seaweed Script",
            "Sevillana",
            "Seymour One",
            "Shadows Into Light",
            "Shadows Into Light Two",
            "Shanti",
            "Share",
            "Share Tech",
            "Share Tech Mono",
            "Shojumaru",
            "Short Stack",
            "Siemreap",
            "Sigmar One",
            "Signika",
            "Signika Negative",
            "Simonetta",
            "Sintony",
            "Sirin Stencil",
            "Six Caps",
            "Skranji",
            "Slabo 13px",
            "Slabo 27px",
            "Slackey",
            "Smokum",
            "Smythe",
            "Sniglet",
            "Snippet",
            "Snowburst One",
            "Sofadi One",
            "Sofia",
            "Sonsie One",
            "Sorts Mill Goudy",
            "Source Code Pro",
            "Source Sans Pro",
            "Source Serif Pro",
            "Special Elite",
            "Spicy Rice",
            "Spinnaker",
            "Spirax",
            "Squada One",
            "Sree Krushnadevaraya",
            "Stalemate",
            "Stalinist One",
            "Stardos Stencil",
            "Stint Ultra Condensed",
            "Stint Ultra Expanded",
            "Stoke",
            "Strait",
            "Sue Ellen Francisco",
            "Sunshiney",
            "Supermercado One",
            "Suranna",
            "Suravaram",
            "Suwannaphum",
            "Swanky and Moo Moo",
            "Syncopate",
            "Tangerine",
            "Taprom",
            "Tauri",
            "Teko",
            "Telex",
            "Tenali Ramakrishna",
            "Tenor Sans",
            "Text Me One",
            "The Girl Next Door",
            "Tienne",
            "Timmana",
            "Tinos",
            "Titan One",
            "Titillium Web",
            "Trade Winds",
            "Trocchi",
            "Trochut",
            "Trykker",
            "Tulpen One",
            "Ubuntu",
            "Ubuntu Condensed",
            "Ubuntu Mono",
            "Ultra",
            "Uncial Antiqua",
            "Underdog",
            "Unica One",
            "UnifrakturCook",
            "UnifrakturMaguntia",
            "Unkempt",
            "Unlock",
            "Unna",
            "VT323",
            "Vampiro One",
            "Varela",
            "Varela Round",
            "Vast Shadow",
            "Vesper Libre",
            "Vibur",
            "Vidaloka",
            "Viga",
            "Voces",
            "Volkhov",
            "Vollkorn",
            "Voltaire",
            "Waiting for the Sunrise",
            "Wallpoet",
            "Walter Turncoat",
            "Warnes",
            "Wellfleet",
            "Wendy One",
            "Wire One",
            "Yanone Kaffeesatz",
            "Yellowtail",
            "Yeseva One",
            "Yesteryear",
            "Zeyada"
        );
    }
}


if ( ! function_exists( 'br_get_category_id' ) ) {
    /**
     * Public function to get category id by $value in $field
     *
     * @param string $value value for search
     * @param string $field by what field is search
     *
     * @return int category id
     */
    function br_get_category_id( $value, $field = 'slug', $return = 'term_id' ) {
        $term = br_get_cache( $value.$field.$return, 'br_get_category_id' );
        if( $term === false ) {
            $term = _br_get_category_id( $value, $field, $return );
            br_set_cache( $value.$field.$return, $term, 'br_get_category_id', BeRocket_AJAX_cache_expire );
        }
        return $term;
    }
}

if ( ! function_exists( '_br_get_category_id' ) ) {
    /**
     * Public function to get category id by $value in $field
     *
     * @param string $value value for search
     * @param string $field by what field is search
     *
     * @return int category id
     */
    function _br_get_category_id( $value, $field = 'slug', $return = 'term_id' ) {
        global $wpdb;

        if ( 'id' == $field ) {
            return $value;
        } elseif ( 'slug' == $field ) {
            $field = 't.slug';
            $value = sanitize_title( $value );
            if ( empty( $value ) ) {
                return false;
            }
        } elseif ( 'name' == $field ) {
            $value = wp_unslash( $value );
            $field = 't.name';
        } else {
            return false;
        }

        $term = $wpdb->get_row(
            $wpdb->prepare( "SELECT t.term_id, tt.term_taxonomy_id FROM {$wpdb->terms} AS t INNER JOIN {$wpdb->term_taxonomy}
                  AS tt ON t.term_id = tt.term_id WHERE tt.taxonomy = 'product_cat' AND $field = %s LIMIT 1", $value )
        );

        if ( ! $term )
            return false;

        $term = (array)$term;
        return $term[$return];
    }
}

if ( ! function_exists( 'br_get_category' ) ) {
    /**
     * Public function to get category by ID
     *
     * @param int $id category id
     *
     * @return object category
     */
    function br_get_category( $id ) {
        global $wpdb;

        if ( ! $id = (int) $id or ! $term = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM {$wpdb->terms} WHERE term_id = %d", $id ) ) ) {
            return false;
        }

        return $term;
    }
}

if ( ! function_exists( 'br_get_sub_categories' ) ) {
    /**
     * Public function to get sub categories from category
     *
     * @param string $field_value value for search
     * @param string $field_name by what field is search
     * @param array $args 'return' - type of return data, 
     * 'include_parent' = include parent to cate gories list, 'max_depth' - max depth of sub category
     *
     * @return string|array|o category
     */
    function br_get_sub_categories( $field_value, $field_name = 'slug', $args = array(), $return = 'term_id' ) {
        $defaults  = array( 'return' => 'string', 'include_parent' => false, 'max_depth' => 9 );
        $args      = wp_parse_args( $args, $defaults );
        $parent_id = 0;

        if ( $field_value ) {
            $parent_id = br_get_category_id( $field_value, $field_name, $return );
        }

        $categories = br_get_cat_hierarchy( $args, $parent_id );

        if ( $args['include_parent'] ) {
            if ( $args['return'] == 'string' ) {
                if ( $parent_id ) {
                    if ( $categories ) $categories .= ",";
                    $categories .= $parent_id;
                }
            } elseif ( $args['return'] == 'array' ) {
                array_unshift( $cat_hierarchy, $parent_id );
            } elseif ( $args['return'] == 'hierarchy_objects' ) {
                $cat = br_get_category( $parent_id );
                $cat->depth = 0;
                $cat_hierarchy[ $parent_id ] = $cat;
                ksort( $cat_hierarchy );
            }
        }
        return $categories;
    }
}

if ( ! function_exists( 'br_get_cat_hierarchy' ) ) {
    /**
     * Public function to get terms by id and taxonomy
     *
     * @param array $args 'return' - type of return data, 
     * 'include_parent' = include parent to cate gories list, 'max_depth' - max depth of sub category
     * @param int $parent_id category id that will be used as parent
     * @param int $depth sub categories depth
     *
     * @return array terms
     */
    function br_get_cat_hierarchy( $args, $parent_id = 0, $depth = 0 ) {
        $cat_hierarchy = br_get_taxonomy_hierarchy(array(
            'taxonomy'  => 'product_cat',
            'parent'    => $parent_id,
            'depth'     => $depth
        ));

        return $cat_hierarchy;
    }
}

if( ! function_exists('berocket_filter_query_vars_hook') ) {
    function berocket_filter_query_vars_hook($query_vars) {
        $query_vars = apply_filters('bapf_uparse_apply_filters_to_query_vars_save', $query_vars);
        return $query_vars;
    }
}

if( ! function_exists('br_get_taxonomy_hierarchy') ) {
    function br_get_taxonomy_hierarchy($args = array()) {
        global $wpdb;
        $args = array_merge(array(
            'taxonomy' => 'product_cat',
            'return'   => 'taxonomy',
            'parent'   => 0,
            'depth'    => 0
        ), $args);
		$wpdb->query("SET SESSION group_concat_max_len = 1000000");
        $md5 = $wpdb->get_var(
            $wpdb->prepare(
                "SELECT MD5(GROUP_CONCAT(t.slug+t.term_id+tt.parent+tt.count+tt.term_taxonomy_id)) FROM $wpdb->terms AS t 
                INNER JOIN $wpdb->term_taxonomy AS tt ON t.term_id = tt.term_id 
                WHERE tt.taxonomy IN (%s)",
                $args['taxonomy']
            )
        );
        $md5 = apply_filters('BRaapf_cache_check_md5', $md5, 'br_get_taxonomy_hierarchy', $args);
        $hierarchy_data = get_option( apply_filters('br_aapf_md5_cache_text', 'br_get_taxonomy_hierarchy_'.$args['taxonomy']) );
        if( empty($hierarchy_data) || $hierarchy_data['md5'] != $md5 ) {
            $hierarchy = br_generate_taxonomy_hierarchy($args['taxonomy']);
            $hierarchy_data = array(
                'terms'     => $hierarchy,
                'hierarchy' => array(),
                'child'     => array(),
                'md5'       => $md5,
                'time'      => time()
            );
            foreach($hierarchy as $hierarchy_term) {
                $hierarchy_data['hierarchy'][$hierarchy_term->term_id] = array($hierarchy_term->term_id);
                foreach($hierarchy_term->child_list as $child_list_id => $child_list_array) {
                    $hierarchy_data['hierarchy'][$child_list_id] = array_merge(array($hierarchy_term->term_id), $child_list_array);
                }
                foreach($hierarchy_term->parent_list as $parent_list_id => $parent_list_array) {
                    $hierarchy_data['child'][$parent_list_id] = $parent_list_array;
                }
            }
            update_option( apply_filters('br_aapf_md5_cache_text', 'br_get_taxonomy_hierarchy_'.$args['taxonomy']), $hierarchy_data, false );
        }
        if( is_array($hierarchy_data) && isset($hierarchy_data[$args['return']]) ) {
            return $hierarchy_data[$args['return']];
        }
        if( $args['return'] == 'all' ) {
            return $hierarchy_data;
        }
        $terms = $hierarchy_data['terms'];
        if( $args['parent'] != 0 ) {
            if( isset($hierarchy_data['hierarchy'][$args['parent']]) && is_array($hierarchy_data['hierarchy'][$args['parent']]) && count($hierarchy_data['hierarchy'][$args['parent']]) ) {
                foreach($hierarchy_data['hierarchy'][$args['parent']] as $child_id) {
                    $terms = $terms[$child_id]->child;
                }
            }
        }
        if( $args['depth'] > 0 ) {
            foreach($terms as &$term) {
                foreach($term->child_list as $child_list_id => $child_list) {
                    if( count($child_list) == $args['depth'] ) {
                        $child = &$term;
                        $child2 = &$child;
                        foreach($child_list as $child_id) {
                            unset($child2);
                            $child2 = &$child;
                            unset($child);
                            $child = &$child2->child[$child_id];
                        }
                        unset($child2->child[$child_id]);
                    }
                    if( count($child_list) >= $args['depth'] ) {
                        unset($term->child_list[$child_list_id]);
                    }
                }
            }
            if( isset($term) ) {
                unset($term);
            }
        }
        return $terms;
    }
}

if( ! function_exists('br_generate_taxonomy_hierarchy') ) {
    function br_generate_taxonomy_hierarchy($taxonomy, $parent = 0) {
        $terms = get_terms( array(
            'taxonomy'      => $taxonomy,
            'hide_empty'    => false,
            'parent'        => $parent,
            'suppress_filter' => (function_exists('wpm_get_language') ? 0 : 1)
        ) );
        $result_terms = array();
        if( is_array($terms) ) {
            foreach($terms as $term) {
                $child_terms = br_generate_taxonomy_hierarchy($taxonomy, $term->term_id);
                $term->child = array();
                $term->child_list = array();
                $term->parent_list = array($term->term_id => array($term->term_id));
                if( property_exists($term, 'description') ) {
                    unset($term->description);
                }
                if( property_exists($term, 'filter') ) {
                    unset($term->filter);
                }
                if( ! empty($child_terms) && is_array($child_terms) && count($child_terms) ) {
                    foreach($child_terms as $child_term) {
                        $term->child[$child_term->term_id] = $child_term;
                        $term->child_list[$child_term->term_id] = array($child_term->term_id);
                        foreach($child_term->child_list as $child_list_id => $child_list_array) {
                            $term->child_list[$child_list_id] = array_merge(array($child_term->term_id), $child_list_array);
                        }
                        foreach($child_term->parent_list as $parent_list_id => $parent_list_array) {
                            $term->parent_list[$term->term_id] = array_merge(array($parent_list_id), $term->parent_list[$term->term_id]);
                            $term->parent_list[$parent_list_id] = $parent_list_array;
                        }
                    }
                }
                $result_terms[$term->term_id] = $term;
            }
        }
        return $result_terms;
    }
}

if( ! function_exists('br_generate_child_relation') ) {
    function br_generate_child_relation($taxonomy) {
        global $wpdb;
		$wpdb->query("SET SESSION group_concat_max_len = 1000000");
        $newmd5 = $wpdb->get_var(
            $wpdb->prepare(
                "SELECT MD5(GROUP_CONCAT(t.slug+t.term_id+tt.parent+tt.count)) FROM $wpdb->terms AS t 
                INNER JOIN $wpdb->term_taxonomy AS tt ON t.term_id = tt.term_id 
                WHERE tt.taxonomy IN (%s)",
                $taxonomy
            )
        );
        $newmd5 = apply_filters('BRaapf_cache_check_md5', $newmd5, 'br_generate_child_relation', $taxonomy);
        $md5 = get_option(apply_filters('br_aapf_md5_cache_text', 'br_generate_child_relation_'.$taxonomy));
        if($md5 != $newmd5) {
            $terms = get_terms( array(
                'taxonomy'      => $taxonomy,
                'hide_empty'    => false,
                'fields'        => 'ids',
                'suppress_filter' => (function_exists('wpm_get_language') ? 0 : 1)
            ) );
            foreach($terms as $term_id) {
                delete_metadata( 'berocket_term', $term_id, 'child' );
                add_metadata( 'berocket_term', $term_id, 'child', $term_id );
                $child = get_term_children( $term_id, $taxonomy );
                if( ! is_wp_error($child) && is_array($child) && count($child) ) {
                    foreach($child as $child_id) {
                        add_metadata( 'berocket_term', $term_id, 'child', $child_id );
                    }
                }
            }
            update_option(apply_filters('br_aapf_md5_cache_text', 'br_generate_child_relation_'.$taxonomy), $newmd5);
        }
    }
}

if ( ! function_exists('berocket_format_number') ) {
    function berocket_format_number( $number, &$format = false ) {
        if( ! isset($format) || ! is_array($format) ) {
            $format = array(
                'thousand_separate' =>wc_get_price_thousand_separator(), 
                'decimal_separate'  => wc_get_price_decimal_separator(), 
                'decimal_number'    => wc_get_price_decimals()
            );
        }
        return number_format( $number, $format['decimal_number'], $format['decimal_separate'], $format['thousand_separate']);
    }
}
if( ! function_exists('berocket_aapf_get_filter_types') ) {
    function berocket_aapf_get_filter_types ($type = 'widget') {
        $berocket_admin_filter_types = array(
            'tag' => array('checkbox','radio','select','color','image','tag_cloud'),
            'product_cat' => array('checkbox','radio','select','color','image'),
            'sale' => array('checkbox','radio','select'),
            'custom_taxonomy' => array('checkbox','radio','select','color','image'),
            'attribute' => array('checkbox','radio','select','color','image'),
            'price' => array('slider'),
            'filter_by' => array('checkbox','radio','select','color','image'),
        );
        $berocket_admin_filter_types_by_attr = array(
            'checkbox' => array('value' => 'checkbox', 'text' => 'Checkbox'),
            'radio' => array('value' => 'radio', 'text' => 'Radio'),
            'select' => array('value' => 'select', 'text' => 'Select'),
            'color' => array('value' => 'color', 'text' => 'Color'),
            'image' => array('value' => 'image', 'text' => 'Image'),
            'slider' => array('value' => 'slider', 'text' => 'Slider'),
            'tag_cloud' => array('value' => 'tag_cloud', 'text' => 'Tag cloud'),
        );
        return apply_filters( 'berocket_admin_filter_types_by_attr', array($berocket_admin_filter_types, $berocket_admin_filter_types_by_attr), $type );
    }
}
if( ! function_exists('braapf_get_loader_element') ) {
    function braapf_get_loader_element() {
        $loader = array(
            'template' => array(
                'type'          => 'tag',
                'tag'           => 'div',
                'attributes'    => array(
                    'class'     => array(
                        'bapf_loader_page'
                    ),
                ),
                'content' => array(
                    'container' => array(
                        'type'          => 'tag',
                        'tag'           => 'div',
                        'attributes'    => array(
                            'class'     => array(
                                'bapf_lcontainer'
                            ),
                        ),
                        'content' => array(
                            'loader' => array(
                                'type'          => 'tag',
                                'tag'           => 'span',
                                'attributes'    => array(
                                    'class'     => array(
                                        'bapf_loader'
                                    ),
                                ),
                                'content' => array(
                                    'first' => array(
                                        'type'          => 'tag',
                                        'tag'           => 'span',
                                        'attributes'    => array(
                                            'class'     => array(
                                                'bapf_lfirst'
                                            ),
                                        ),
                                    ),
                                    'second' => array(
                                        'type'          => 'tag',
                                        'tag'           => 'span',
                                        'attributes'    => array(
                                            'class'     => array(
                                                'bapf_lsecond'
                                            ),
                                        ),
                                    ),
                                )
                            )
                        )
                    )
                )
            )
        );
        $BeRocket_AAPF = BeRocket_AAPF::getInstance();
        $options = $BeRocket_AAPF->get_option();
        if( ! empty($options['ajax_load_icon']) ) {
            $loader['template']['content']['container']['content']['loader'] = array(
                'type'          => 'tag_open',
                'tag'           => 'img',
                'attributes'    => array(
                    'class'     => array(
                        'bapf_limg'
                    ),
                    'src'       => $options['ajax_load_icon'],
                    'alt'       => __('Loading...', 'BeRocket_AJAX_domain')
                ),
            );
        }
        if( ! empty($options['ajax_load_text']) && is_array($options['ajax_load_text']) ) {
            if( ! empty($options['ajax_load_text']['top']) ) {
                $loader['template']['content']['container']['content']['text_above'] = array(
                    'type'          => 'tag',
                    'tag'           => 'span',
                    'attributes'    => array(
                        'class'     => array(
                            'bapf_labove'
                        ),
                    ),
                    'content' => array($options['ajax_load_text']['top'])
                );
            }
            if( ! empty($options['ajax_load_text']['bottom']) ) {
                $loader['template']['content']['container']['content']['text_below'] = array(
                    'type'          => 'tag',
                    'tag'           => 'span',
                    'attributes'    => array(
                        'class'     => array(
                            'bapf_lbelow'
                        ),
                    ),
                    'content' => array($options['ajax_load_text']['bottom'])
                );
            }
            if( ! empty($options['ajax_load_text']['left']) ) {
                $loader['template']['content']['container']['content']['text_before'] = array(
                    'type'          => 'tag',
                    'tag'           => 'span',
                    'attributes'    => array(
                        'class'     => array(
                            'bapf_lbefore'
                        ),
                    ),
                    'content' => array($options['ajax_load_text']['left'])
                );
            }
            if( ! empty($options['ajax_load_text']['right']) ) {
                $loader['template']['content']['container']['content']['text_after'] = array(
                    'type'          => 'tag',
                    'tag'           => 'span',
                    'attributes'    => array(
                        'class'     => array(
                            'bapf_lafter'
                        ),
                    ),
                    'content' => array($options['ajax_load_text']['right'])
                );
            }
        }
        return BeRocket_AAPF_Template_Build($loader);
    }
}
if( ! function_exists('braapf_is_filters_displayed_debug') ) {
    function braapf_is_filters_displayed_debug($id, $type, $status, $message) {
        if( BeRocket_AAPF::$user_can_manage ) {
            $temp = BeRocket_AAPF::$current_page_filters;
            if( ! in_array($id, $temp['added']) ) {
                $temp['added'][] = $id;
                if( ! isset($temp[$type]) || ! is_array($temp[$type]) ) {
                    $temp[$type] = array();
                }
                if( ! isset($temp[$type][$status]) || ! is_array($temp[$type][$status]) ) {
                    $temp[$type][$status] = array();
                }
                $temp[$type][$status][$id] = $message;
            }
            BeRocket_AAPF::$current_page_filters = $temp;
        }
    }
}
if( ! function_exists('braapf_get_data_taxonomy_from_post') ) {
    function braapf_get_data_taxonomy_from_post($post_data, $return = 'taxonomy') {
        $result = apply_filters('braapf_get_data_taxonomy_from_post_before', null, $post_data);
        if( $result !== null ) {
            if( $return == 'all' ) {
                $result = array(
                    'taxonomy' => $result,
                    'type'     => 'custom'
                );
            }
            return $result;
        }
        $filter_type = br_get_value_from_array($post_data, 'filter_type');
        $attribute   = br_get_value_from_array($post_data, 'attribute');
        $type        = $filter_type;
        $custom_taxonomy   = br_get_value_from_array($post_data, 'custom_taxonomy');
        if ( $filter_type == 'price' ) {
            $filter_type = 'attribute';
            $attribute   = $type = 'price';
        }
        $filter_type_array = array(
            'attribute' => array(
                'name' => __('Attribute', 'BeRocket_AJAX_domain'),
                'sameas' => 'attribute',
            ),
            'tag' => array(
                'name' => __('Tag', 'BeRocket_AJAX_domain'),
                'sameas' => 'tag',
            ),
            'all_product_cat' => array(
                'name' => __('Product Category', 'BeRocket_AJAX_domain'),
                'sameas' => 'custom_taxonomy',
                'attribute' => 'product_cat',
            ),
        );
        $filter_type_array['_rating'] = array(
            'name' => __('Rating', 'BeRocket_AJAX_domain'),
            'sameas' => '_rating',
            'attribute' => '_rating',
        );
        $filter_type_array = apply_filters('berocket_filter_filter_type_array', $filter_type_array, $post_data);
        if( empty($filter_type) || ! array_key_exists($filter_type, $filter_type_array) ) {
            if( $filter_type == 'filter' ) {
                return false;
            }
            foreach($filter_type_array as $filter_type_key => $filter_type_val) {
                $filter_type = $filter_type_key;
                break;
            }
        }
        if( ! empty($filter_type) && ! empty($filter_type_array[$filter_type]) && ! empty($filter_type_array[$filter_type]['sameas']) ) {
            $sameas = $filter_type_array[$filter_type];
            $filter_type = $sameas['sameas'];
            if( ! empty($sameas['attribute']) && $sameas['sameas'] == 'attribute' ) {
                $attribute = $sameas['attribute'];
                $type      = 'attribute';
            } elseif( $sameas['sameas'] == 'custom_taxonomy' ) {
                $type      = 'custom_taxonomy';
                if( ! empty($sameas['attribute']) ) {
                    $attribute = $sameas['attribute'];
                } else {
                    $attribute = $custom_taxonomy;
                }
            } elseif ( ! empty($sameas['slug']) ) {
                $attribute = $sameas['slug'];
            }
        }
        if ( ! empty($filter_type) && ( in_array($filter_type, array('product_cat', '_stock_status', '_sale', '_rating', 'tag')) ) ) {
            switch ($filter_type) {
                case 'tag':
                    $attribute = 'product_tag';
                    break;
                case '_rating':
                    $attribute = 'product_visibility';
                    break;
                default:
                    $attribute   = $filter_type;
            }
        }
        if( $return == 'all' ) {
            $attribute = array(
                'taxonomy' => $attribute,
                'type'     => $type
            );
        }
        return $attribute;
    }
}
if( ! function_exists('berocket_term_get_metadata') ) {
    function berocket_term_get_metadata($term, $color_name) {
        $color_meta = apply_filters('berocket_aapf_color_term_select_metadata', false, $term, $color_name);
        if( $color_meta === false ) {
            $color_meta = get_metadata('berocket_term', $term->term_id, $color_name);
        }
        return $color_meta;
    }
}