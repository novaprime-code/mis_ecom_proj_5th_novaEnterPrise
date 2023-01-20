<?php
if ( ! class_exists('BeRocket_framework_settings_fields') ) {
    class BeRocket_framework_settings_fields {
        function __construct() {
            do_action( 'BeRocket_framework_settings_fields_construct' );
            $fields = array(
                'text'          => 'text',
                'number'        => 'number',
                'radio'         => 'radio',
                'checkbox'      => 'checkbox',
                'selectbox'     => 'selectbox',
                'textarea'      => 'textarea',
                'color'         => 'color',
                'image'         => 'image',
                'faimage'       => 'faimage',
                'fontawesome'   => 'fontawesome',
                'fa'            => 'fontawesome',
                'products'      => 'products',
            );
            foreach($fields as $field_hook => $field) {
                add_filter( 'berocket_framework_item_content_'.$field_hook, array( $this, $field ), 10, 8 );
            }
            foreach($fields as $field_hook => $field) {
                add_filter( 'berocket_framework_item_content_'.$field_hook, array( $this, 'admin_disable' ), 100, 8 );
            }
        }

        function text( $html, $field_item, $field_name, $value, $class, $extra ) {
            $html .= '<label>';
            if ( ! empty( $field_item[ 'label_be_for' ] ) ) {
                $html .= '<span class="br_label_be_for">' . $field_item[ 'label_be_for' ] . '</span>';
            }
            $html .= '<input'.( empty($field_item['disabled']) ? '' : ' disabled=disabled').' type="text" name="' . $field_name . '" value="' . htmlentities( $value ) . '"' . $class . $extra . '/>';
            if ( ! empty( $field_item[ 'label_for' ] ) ) {
                $html .= '<span class="br_label_for">' . $field_item[ 'label_for' ] . '</span>';
            }
            $html .= '</label>';

            return $html;
        }

        function number( $html, $field_item, $field_name, $value, $class, $extra ) {
            $html .= '<label>';
            if ( ! empty( $field_item[ 'label_be_for' ] ) ) {
                $html .= '<span class="br_label_be_for">' . $field_item[ 'label_be_for' ] . '</span>';
            }
            $html .= '<input'.( empty($field_item['disabled']) ? '' : ' disabled=disabled').' type="number" name="' . $field_name . '" value="' . $value . '"' . $class . $extra . ( empty( $field_item[ 'min' ] ) ? '' : ' min="' . $field_item[ 'min' ] . '"' ) . ( empty( $field_item[ 'max' ] ) ? '' : ' max="' . $field_item[ 'max' ] . '"' ) . '/>';
            if ( ! empty( $field_item[ 'label_for' ] ) ) {
                $html .= '<span class="br_label_for">' . $field_item[ 'label_for' ] . '</span>';
            }
            $html .= '</label>';

            return $html;
        }

        function radio( $html, $field_item, $field_name, $value, $class, $extra, $option_values, $option_deault_values ) {
            $radio_default = ( isset( $option_values ) ? $option_values : ( ! empty( $field_item[ 'default' ] ) ? $field_item[ 'value' ] : ( ! empty( $option_deault_values ) ? $option_deault_values : '' ) ) );
            $html .= '<label>';
            if ( ! empty( $field_item[ 'label_be_for' ] ) ) {
                $html .= '<span class="br_label_be_for">' . $field_item[ 'label_be_for' ] . '</span>';
            }
            $html .= '<input'.( empty($field_item['disabled']) ? '' : ' disabled=disabled').' type="radio" name="' . $field_name . '" value="' . $field_item[ 'value' ] . '"' . ( $field_item[ 'value' ] == $radio_default ? ' checked="checked" ' : '' ) . $class . $extra . '/>';
            if ( ! empty( $field_item[ 'label_for' ] ) ) {
                $html .= '<span class="br_label_for">' . $field_item[ 'label_for' ] . '</span>';
            }
            $html .= '</label>';

            return $html;
        }

        function checkbox( $html, $field_item, $field_name, $value, $class, $extra, $option_values, $option_deault_values ) {
            $html .= '<label>';
            if ( ! empty( $field_item[ 'label_be_for' ] ) ) {
                $html .= '<span class="br_label_be_for">' . $field_item[ 'label_be_for' ] . '</span>';
            }
            $html .= '<input'.( empty($field_item['disabled']) ? '' : ' disabled=disabled').' type="checkbox" name="' . $field_name . '" value="' . $field_item[ 'value' ] . '"' . ( ( ! empty( $option_values ) ) ? ' checked="checked" ' : '' ) . $class . $extra . '/>';
            if ( ! empty( $field_item[ 'label_for' ] ) ) {
                $html .= '<span class="br_label_for">' . $field_item[ 'label_for' ] . '</span>';
            }
            $html .= '</label>';

            return $html;
        }

        function selectbox( $html, $field_item, $field_name, $value, $class, $extra ) {
            $html .= '<label>';
            if ( ! empty( $field_item[ 'label_be_for' ] ) ) {
                $html .= '<span class="br_label_be_for">' . $field_item[ 'label_be_for' ] . '</span>';
            }
            $html .= '<select'.( empty($field_item['disabled']) ? '' : ' disabled=disabled').' name="' . $field_name . '"' . $class . $extra . '>';
            if ( isset( $field_item[ 'options' ] ) and is_array( $field_item[ 'options' ] ) and count( $field_item[ 'options' ] ) ) {
                foreach ( $field_item[ 'options' ] as $option ) {
                    $html .= '<option value="' . $option[ 'value' ] . '"' . ( ( $value == $option[ 'value' ] ) ? ' selected="selected" ' : '' ) . '>' . $option[ 'text' ] . '</option>';
                }
            } else {
                $html .= "<option>Options data is corrupted!</option>";
            }
            $html .= '</select>';
            if ( ! empty( $field_item[ 'label_for' ] ) ) {
                $html .= '<span class="br_label_for">' . $field_item[ 'label_for' ] . '</span>';
            }
            $html .= '</label>';

            return $html;
        }

        function textarea( $html, $field_item, $field_name, $value, $class, $extra ) {
            if ( ! empty( $field_item[ 'label_be_for' ] ) ) {
                $html .= '<span class="br_label_be_for">' . $field_item[ 'label_be_for' ] . '</span>';
            }
            $html .= '<textarea'.( empty($field_item['disabled']) ? '' : ' disabled=disabled').' name="' . $field_name . '"' . $class . $extra . '>' . htmlentities( $value ) . '</textarea>';
            if ( ! empty( $field_item[ 'label_for' ] ) ) {
                $html .= '<span class="br_label_for">' . $field_item[ 'label_for' ] . '</span>';
            }

            return $html;
        }

        function color( $html, $field_item, $field_name, $value, $class, $extra ) {
            if ( ! empty( $field_item[ 'label_be_for' ] ) ) {
                $html .= '<span class="br_label_be_for">' . $field_item[ 'label_be_for' ] . '</span>';
            }
            if ( empty( $value ) ) {
                $value = $field_item[ 'value' ];
            }
            $html .= br_color_picker( $field_name, $value, ( isset( $field_item[ 'value' ] ) ? $field_item[ 'value' ] : '' ), $field_item );
            if ( ! empty( $field_item[ 'label_for' ] ) ) {
                $html .= '<span class="br_label_for">' . $field_item[ 'label_for' ] . '</span>';
            }

            return $html;
        }

        function image( $html, $field_item, $field_name, $value, $class, $extra ) {
            if ( ! empty( $field_item[ 'label_be_for' ] ) ) {
                $html .= '<span class="br_label_be_for">' . $field_item[ 'label_be_for' ] . '</span>';
            }
            $html .= br_upload_image( $field_name, $value, $field_item );
            if ( ! empty( $field_item[ 'label_for' ] ) ) {
                $html .= '<span class="br_label_for">' . $field_item[ 'label_for' ] . '</span>';
            }

            return $html;
        }

        function faimage( $html, $field_item, $field_name, $value, $class, $extra ) {
            if ( ! empty( $field_item[ 'label_be_for' ] ) ) {
                $html .= '<span class="br_label_be_for">' . $field_item[ 'label_be_for' ] . '</span>';
            }
            $html .= br_fontawesome_image( $field_name, $value, $field_item );
            if ( ! empty( $field_item[ 'label_for' ] ) ) {
                $html .= '<span class="br_label_for">' . $field_item[ 'label_for' ] . '</span>';
            }

            return $html;
        }

        function fontawesome( $html, $field_item, $field_name, $value, $class, $extra ) {
            if ( ! empty( $field_item[ 'label_be_for' ] ) ) {
                $html .= '<span class="br_label_be_for">' . $field_item[ 'label_be_for' ] . '</span>';
            }
            $html .= br_select_fontawesome( $field_name, $value, $field_item );
            if ( ! empty( $field_item[ 'label_for' ] ) ) {
                $html .= '<span class="br_label_for">' . $field_item[ 'label_for' ] . '</span>';
            }

            return $html;
        }

        function products( $html, $field_item, $field_name, $value, $class, $extra ) {
            if ( ! empty( $field_item[ 'label_be_for' ] ) ) {
                $html .= '<span class="br_label_be_for">' . $field_item[ 'label_be_for' ] . '</span>';
            }
            $html .= br_products_selector( $field_name, $value, $field_item );
            if ( ! empty( $field_item[ 'label_for' ] ) ) {
                $html .= '<span class="br_label_for">' . $field_item[ 'label_for' ] . '</span>';
            }

            return $html;
        }
        function admin_disable( $html, $field_item, $field_name ) {
            if( ! empty($field_item['admin_disabled']) ) {
                $admin = ( is_multisite() ? __('MULTISITE ADMIN', 'BeRocket_domain') : __('ADMIN', 'BeRocket_domain') );
                $html .= '<p style="font-weight:900;">'.sprintf(__('Field can be changed only by %s', 'BeRocket_domain'), $admin).'</p>';
            }
            return $html;
        }
    }

    new BeRocket_framework_settings_fields();
}
