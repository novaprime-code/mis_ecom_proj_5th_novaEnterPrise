<?php
class BeRocket_AAPF_Template_Builder {
    function __construct() {
        $types = array('tag', 'tag_open');
        foreach($types as $type) {
            add_filter('braapf_template_builder_type_'.$type, array(__CLASS__, 'type_'.$type), 10, 2);
        }
    }
    public static function build($template) {
        $html_elements = array();
        if( ! empty($template) && is_array($template) ) {
            foreach($template as $element) {
                if( is_string($element) || is_numeric($element) ) {
                    $html_elements[] = strval($element);
                } elseif(is_array($element)) {
                    $type = trim(berocket_isset($element['type']));
                    $html_elements[] = apply_filters('braapf_template_builder_type_'.$type, '', $element);
                }
            }
        }
        return trim(implode('', $html_elements));
    }
    public static function attribute_filter($attributes) {
        $atributes_html = array();
        if( ! empty($attributes) && is_array($attributes) ) {
            foreach($attributes as $attribute_name => $value) {
                if( is_array($value) ) {
                    $glue = ' ';
                    if( isset($value['glue']) ) {
                        $glue = $value['glue'];
                        unset($value['glue']);
                    }
                    $value = implode($glue, $value);
                }
                $value = trim(strval($value));
                if( isset($value) && (strlen($value) > 0 || $attribute_name == 'value') ) {
                    $atributes_html[] = $attribute_name . '="' . htmlentities($value , ENT_COMPAT|ENT_HTML401, ini_get("default_charset"), false) . '"';
                }
            }
        }
        $attributes_ready = trim(implode(' ', $atributes_html));
        if( strlen($attributes_ready) > 0 ) {
            $attributes_ready = ' ' . $attributes_ready;
        }
        return $attributes_ready;
    }
    //TYPES FUNCTIONS
    public static function type_tag_open($html, $element) {
        $html = '';
        if( ! empty($element['tag']) ) {
            $html .= '<' . htmlentities(trim($element['tag'])) . self::attribute_filter(berocket_isset($element['attributes'])) . '>';
        }
        return $html;
    }
    public static function type_tag($html, $element) {
        $html = self::type_tag_open($html, $element);
        if( !empty($html) && ! empty($element['tag']) ) {
            if( ! empty($element['content']) ) {
                $html .= self::build($element['content']);
            }
            $html .= '</' . htmlentities(trim($element['tag'])) . '>';
        }
        return $html;
    }
    public static function default_template() {
        $template_content = array(
            'template'=> array(
                'type'          => 'tag',
                'tag'           => 'div',
                'attributes'    => array(
                    'class'         => array(
                        'bapf_sfilter'
                    ),
                    'data-op'       => 'operator',
                    'data-taxonomy' => 'taxonomy_slug',
                    'data-name'     => 'Filter Name'
                ),
                'content'       => array(
                    'header'  => array(
                        'type'          => 'tag',
                        'tag'           => 'div',
                        'attributes'    => array(
                            'class'         => array(
                                'bapf_head'
                            )
                        ),
                        'content'       => array(
                            'title'  => array(
                                'type'          => 'tag',
                                'tag'           => 'h3',
                                'attributes'    => array(),
                                'content'       => array(
                                    'title' => 'Filter Title'
                                ),
                            ),
                        )
                    ),
                    'filter' => array(
                        'type'          => 'tag',
                        'tag'           => 'div',
                        'attributes'    => array(
                            'class'         => array(
                                'bapf_body'
                            )
                        ),
                        'content'       => array(
                        )
                    ),
                )
            ),
        );
        return $template_content;
    }
}
new BeRocket_AAPF_Template_Builder();
function BeRocket_AAPF_Template_Build_default() {
    return BeRocket_AAPF_Template_Builder::default_template();
}
function BeRocket_AAPF_Template_Build($template) {
    return BeRocket_AAPF_Template_Builder::build($template);
}
