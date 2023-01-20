<?php
/**
 * Widget
 */

if ( ! class_exists( 'BeRocket_Widget' ) ) {

    class BeRocket_Widget extends WP_Widget {
        public static $defaults = array(
            'title' => '',
        );

        public function __construct( $name, $normal_name, $description = '' ) {
            parent::__construct( "berocket_" . $name . "_widget", $normal_name,
                array( "description" => $description )
            );
        }

        /**
         * WordPress widget
         */
        public function widget( $args, $instance ) {
            $instance = wp_parse_args( (array) $instance, self::$defaults );
            $options  = $args['plugin_class']::get_option();

            set_query_var( 'title', apply_filters( 'ce_widget_title', $instance[ 'title' ] ) );
            set_query_var( 'args', $args );

            echo $args[ 'before_widget' ];
            $args['plugin_class']::br_get_template_part( apply_filters( 'berocket_widget_template', 'widget', $args['plugin_class']::info ) );
            echo $args[ 'after_widget' ];
        }

        /**
         * Update widget settings
         */
        public function update( $new_instance, $old_instance ) {
            $instance            = $old_instance;
            $instance[ 'title' ] = strip_tags( $new_instance[ 'title' ] );

            return $instance;
        }

        /**
         * Widget settings form
         */
        public function form( $instance ) {
            $instance = wp_parse_args( (array) $instance, self::$defaults );
            $title    = strip_tags( $instance[ 'title' ] );
            ?>
            <p><label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label> <input
                    class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>"
                    name="<?php echo $this->get_field_name( 'title' ); ?>" type="text"
                    value="<?php echo esc_attr( $title ); ?>"/></p>
            <?php
        }
    }
}
