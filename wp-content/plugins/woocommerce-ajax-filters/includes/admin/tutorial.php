<?php
if( ! class_exists('BeRocket_tutorial_tab') ) {
    class BAPF_tutorial_tab {
        function __construct() {
            $element_types = array(
                'youtube',
                'link',
                'html',
            );
            add_filter('berocket_tutorial_tab_section', array(__CLASS__, 'build_section'), 10, 4);
            add_filter('berocket_tutorial_tab_element', array(__CLASS__, 'build_element'), 10, 5);
            foreach($element_types as $element_type) {
                add_filter('berocket_tutorial_tab_element_'.$element_type, array(__CLASS__, 'build_element_'.$element_type), 10, 5);
            }
        }
        public static function add_code() {
            add_action('admin_footer', array(__CLASS__, 'required_code'));
        }
        public static function build($data, $additional = '') {
            $html = '';
            if( is_array($data) ) {
                foreach($data as $section_id => $section) {
                    $html .= apply_filters('berocket_tutorial_tab_section', '', $section, $section_id, $additional);
                }
                self::add_code();
            }
            return $html;
        }
        public static function build_section($html, $section, $section_id, $additional = '') {
            if( is_array($section) && isset($section['elements']) && is_array($section['elements']) && count($section['elements']) ) {
                $html .= '<div class="br_tutorial_section">';
                if( isset($section['title']) ) {
                    $html .= '<h2>' . $section['title'] .'</h2>';
                }
                $html .= '<div class="br_tutorial_section_elements">';
                foreach($section['elements'] as $element_id => $element) {
                    $html .= apply_filters('berocket_tutorial_tab_element', '', $element, $section_id, $element_id, $additional
                    );
                }
                $html .= '</div></div>';
            }
            return $html;
        }
        public static function build_element($html, $element, $section_id, $element_id, $additional = '') {
            $element = array_merge(array('paid' => false), $element);
            $html .= '<div class="br_tutorial_element br_tutorial_element_'.$element['type'].(empty($element['paid']) ? '' : ' br_tutorial_only_paid').'">';
            $html .= apply_filters('berocket_tutorial_tab_element_'.$element['type'], '', $element, $section_id, $element_id, $additional);
            $html .= '<h3>'.$element['title'].'</h3>';
            $html .='</div>';
            return $html;
        }
        public static function build_element_youtube($html, $element, $section_id, $element_id, $additional = '') {
            if( ! empty($element['video']) ) {
                $html .= '<div data-video="'.$element['video'].'" class="br_tutorial_youtube" style="background-image:url(https://img.youtube.com/vi/'.$element['video'].'/sddefault.jpg);">
                    <span class="ytp-large-play-button ytp-button" aria-label="Watch"><svg height="100%" version="1.1" viewBox="0 0 68 48" width="100%"><path class="ytp-large-play-button-bg" d="M66.52,7.74c-0.78-2.93-2.49-5.41-5.42-6.19C55.79,.13,34,0,34,0S12.21,.13,6.9,1.55 C3.97,2.33,2.27,4.81,1.48,7.74C0.06,13.05,0,24,0,24s0.06,10.95,1.48,16.26c0.78,2.93,2.49,5.41,5.42,6.19 C12.21,47.87,34,48,34,48s21.79-0.13,27.1-1.55c2.93-0.78,4.64-3.26,5.42-6.19C67.94,34.95,68,24,68,24S67.94,13.05,66.52,7.74z" fill="#212121" fill-opacity="0.6"></path><path d="M 45,24 27,14 27,34" fill="#fff"></path></svg></span>
                </div>';
            }
            return $html;
        }
        public static function build_element_link($html, $element, $section_id, $element_id, $additional = '') {
            if( ! empty($element['link']) ) {
                $html .= '<div data-link="'.br_get_value_from_array($element, 'link').'" class="br_tutorial_link" style="background-image:url('.br_get_value_from_array($element, 'img').');">
                    <span class="br-link-icon" aria-label="open">
                        <svg viewBox="0 0 32 32" width="100%" fill="none" stroke="currentcolor" stroke-linecap="round" stroke-linejoin="round" stroke-width="3" stroke-opacity="0.6">
                            <path d="M18 8 C18 8 24 2 27 5 30 8 29 12 24 16 19 20 16 21 14 17 M14 24 C14 24 8 30 5 27 2 24 3 20 8 16 13 12 16 11 18 15" ></path>
                        </svg>
                    </span>
                </div>';
            }
            return $html;
        }
        public static function build_element_html($html, $element, $section_id, $element_id, $additional = '') {
            if( ! empty($element['html']) ) {
                $html .= '<div class="br_tutorial_html" style="background-image:url('.br_get_value_from_array($element, 'img').');">
                    <span class="br-question-icon" aria-label="open">
                        <svg viewBox="0 0 85 85" xmlns="http://www.w3.org/2000/svg">
                            <path d="m42.37985,0.7408c-22.935,-0.015 -41.855,18.864 -41.865,41.774c-0.009,23.403 18.722,42.228 42.013,42.225c23.185,-0.003 41.988,-18.82 41.985,-42.013c-0.002,-23.233 -18.806,-41.97 -42.133,-41.986zm1.037,69.837c-3.299,0.027 -5.461,-2.08 -5.474,-5.332c-0.014,-3.298 2.089,-5.447 5.347,-5.464c3.22,-0.017 5.461,2.198 5.462,5.396c0.001,3.159 -2.188,5.375 -5.335,5.4zm12.91,-32.644c-1.209,2.626 -3.042,4.78 -4.971,6.863c-1.687,1.822 -2.979,3.816 -3.573,6.273c-0.584,2.42 -3.066,3.882 -5.458,3.37c-2.205,-0.472 -3.502,-2.64 -3.185,-5.167c0.463,-3.685 2.492,-6.495 4.892,-9.143c2.326,-2.567 3.984,-5.44 3.5,-9.089c-0.124,-0.936 -0.336,-1.906 -0.739,-2.749c-1.062,-2.216 -3.772,-2.551 -5.337,-0.646c-0.645,0.785 -1.099,1.762 -1.484,2.714c-0.667,1.65 -1.924,2.258 -3.578,2.284c-1.199,0.019 -2.399,0.026 -3.598,-0.001c-2.296,-0.052 -3.059,-1.019 -2.647,-3.311c1.273,-7.108 6.19,-11.073 15.502,-11.072c1.893,0.015 5.314,0.775 8.059,3.398c3.987,3.812 5.081,10.924 2.617,16.276z" fill="#212121" fill-opacity="0.6" clip-rule="evenodd" fill-rule="evenodd"/>
                        </svg>
                    </span>
                    <div class="br_element_html_popup_content" style="display: none!important;">'.$element['html'].'</div>
                </div>';
            }
            return $html;
        }
        public static function required_code() {
            self::javascript();
            self::css();
        }
        public static function javascript() {
            ?>
<script>
jQuery(document).ready(function(){
    function show_simple_popup(content, width, height) {
        if( typeof(width) == 'undefined' || width == 0 ) {
            width = jQuery(window).width() - 80;
        }
        if( typeof(height) == 'undefined' || height == 0 ) {
            height = jQuery(window).height() - 80;
        }
        var $content = jQuery(content);
        var style = 'width:'+width+'px;height:'+height+'px;margin-left:-'+(width/2)+'px;margin-top:-'+(height/2)+'px;';
        var html = '<div class="br_tutorial_popup"><div style="'+style+'" class="br_tutorial_popup_content"></div><span class="dashicons dashicons-no"></span></div>';
        jQuery('.br_tutorial_popup').remove();
        jQuery('body').append(jQuery(html));
        jQuery('.br_tutorial_popup_content').append($content);
    }
    jQuery(document).on('click', '.br_tutorial_popup_content', function(e) {
        e.stopPropagation();
    });
    jQuery(document).on('click', '.br_tutorial_popup', function(e) {
        jQuery('.br_tutorial_popup').remove();
    });
    jQuery(document).on('click', '.br_tutorial_element_youtube', function() {
        var video  = jQuery(this).find('.br_tutorial_youtube').data('video');
        var width  = jQuery(window).width() - 80;
        var height = jQuery(window).height() - 80;
        var width  = parseInt(Math.min(width, (height/9*16)));
        var height = parseInt(Math.min(height, (width/16*9)));
        var content = '<iframe width="'+width+'" height="'+height+'" src="https://www.youtube.com/embed/'+video+'?rel=0&amp;showinfo=0&amp;autoplay=1" frameborder="0" allowfullscreen="" allow="autoplay"></iframe>';
        show_simple_popup(content, width, height);
    });
    jQuery(document).on('click', '.br_tutorial_element_link', function() {
        window.open(jQuery(this).find('.br_tutorial_link').data('link'),'_blank');
    });
    jQuery(document).on('click', '.br_tutorial_element_html', function() {
        var content = '<div style="padding:10px;max-width:100%;">'+jQuery(this).find('.br_element_html_popup_content').html()+'</div>';
        show_simple_popup(content);
    });
});
</script>
            <?php
        }
        public static function css() {
            ?>
<style>
.br_tutorial_popup {
    position: fixed;
    z-index: 999999999999;
    top: 0;
    bottom: 0;
    left: 0;
    right: 0;
    background-color: rgba(0,0,0,0.5);
}
.br_tutorial_popup .dashicons {
    position: absolute;
    top: 0px;
    right: 0px;
    font-size: 50px;
    line-height: 1em;
    color: white;
    display: block;
    width: 50px;
    height: 50px;
    cursor: pointer;
}
.br_tutorial_popup .dashicons:hover {
    color: #cc3333;
}
.br_tutorial_popup .br_tutorial_popup_content {
    position: absolute;
    top: 50%;
    left: 50%;
    background-color: rgb(255, 255, 255);
    animation-duration: 0.2s;
    animation-name: br_tutorial_popup_content;
    overflow: auto;
}
.br_tutorial_popup .br_tutorial_popup_content iframe {
    display: block;
}
@keyframes br_tutorial_popup_content {
  0% {
    top: -50%;
  }
  100% {
    top: 50%;
  }
}


.br_framework_settings .br_tutorial_section + .br_tutorial_section {
    margin-top: 30px;
}
.br_framework_settings .br_tutorial_section > h2 {
    box-sizing: border-box;
    text-align: left;
    padding: 20px 0 !important;
    color: #515861;
    font-size: 1.75rem;
    line-height: 1em;
    margin: 5px 0;
}
.br_tutorial_section_elements {
    display: flex;
    justify-content: left;
    flex-wrap: wrap;
}
.br_tutorial_section_elements .br_tutorial_element {
    flex-basis: 32%;
    margin: 0 0 20px 1.5%;
    flex-grow: 1;
    max-width: 32%;
    position: relative;
    display: flex;
    flex-direction: column;
    box-shadow: 0 2px 6px 0 rgba(12,13,14,.15) !important;
    border-radius: 10px;
    overflow: hidden;
    border: 0 none;
    cursor: pointer;
}
.br_tutorial_section_elements .br_tutorial_element:nth-child(3n+1) {
    margin-left: 0;
}
.br_tutorial_section_elements .br_tutorial_element > h3 {
    display: block;
    margin: 0;
    padding: 10px 20px;
    background-color: #515861;
    font-size: 16px;
    line-height: 1em;
    color: white;
    position: absolute;
    bottom: 0;
    left: 0;
    border-top-right-radius: 10px;
}


@media screen and (max-width: 1200px) {
    .br_tutorial_section_elements .br_tutorial_element {
        flex-basis: 49%;
        margin: 0 0 20px 2%;
        flex-grow: 1;
        max-width: 49%;
    }
    .br_tutorial_section_elements .br_tutorial_element:nth-child(3n+1) {
        margin-left: 2%;
    }
    .br_tutorial_section_elements .br_tutorial_element:nth-child(2n+1) {
        margin-left: 0;
    }
}
@media screen and (max-width: 768px) {
    .br_tutorial_section_elements .br_tutorial_element {
        flex-basis: 94%;
        margin: 0 3% 20px 3%;
        flex-grow: 1;
        max-width: 94%;
    }
    .br_framework_settings .br_tutorial_section > h2 {
        font-size: 1.25rem;
        margin-left: 3%;
    }
    .br_tutorial_section_elements .br_tutorial_element:nth-child(3n+1) {
        margin-left: 3%;
    }
    .br_tutorial_section_elements .br_tutorial_element:nth-child(2n+1) {
        margin-left: 3%;
    }
}


.br_tutorial_section_elements .br_tutorial_element .br_tutorial_youtube {
    background-position: center left;
    background-size: cover;
    width: 100%;
    height: 0;
    padding-top: 55%;
    position: relative;
}
.br_tutorial_section_elements .br_tutorial_element .ytp-large-play-button,
.br_tutorial_section_elements .br_tutorial_element .br-link-icon,
.br_tutorial_section_elements .br_tutorial_element .br-question-icon {
    position: absolute;
    left: 50%;
    top: 50%;
    width: 68px;
    height: 68px;
    margin-left: -34px;
    margin-top: -34px;
    -moz-transition: opacity .25s cubic-bezier(0,0,.2,1);
    -webkit-transition: opacity .25s cubic-bezier(0,0,.2,1);
    transition: opacity .25s cubic-bezier(0,0,.2,1);
    z-index: 63;
}
.br_tutorial_section_elements .br_tutorial_element_youtube:hover .ytp-large-play-button-bg,
.br_tutorial_section_elements .br_tutorial_element_html:hover .br-question-icon path{
    -moz-transition: fill .1s cubic-bezier(0,0,.2,1),fill-opacity .1s cubic-bezier(0,0,.2,1);
    -webkit-transition: fill .1s cubic-bezier(0,0,.2,1),fill-opacity .1s cubic-bezier(0,0,.2,1);
    transition: fill .1s cubic-bezier(0,0,.2,1),fill-opacity .1s cubic-bezier(0,0,.2,1);
    fill: red;
    fill-opacity: 1;
}
.br_tutorial_section_elements .br_tutorial_element_link:hover .br-link-icon path {
    color: red;
    stroke-opacity: 1;
}
.br_tutorial_section_elements .br_tutorial_element .br_tutorial_link,
.br_tutorial_section_elements .br_tutorial_element .br_tutorial_html {
    background-position: center left;
    background-size: contain;
    background-repeat: no-repeat;
    width: 100%;
    height: 0;
    padding-top: 55%;
    position: relative;
}
</style>
            <?php
        }
    }
    new BAPF_tutorial_tab();
    function berocket_tutorial_tab($data, $additional = '') {
        return BAPF_tutorial_tab::build($data, $additional);
    }
}