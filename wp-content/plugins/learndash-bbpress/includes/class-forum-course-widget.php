<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit();
}

class LearnDash_Forum_Course_Widget extends WP_Widget {
    /**
     * Init the class
     */
    public function __construct() {
        $widget_args = array(
            'classname' => 'ld-forum-course-widget',
            'description' => __( 'Display courses links that belong to a particular forum. This widget will be displayed only on bbpress pages.', 'learndash-bbpress' )
        );
        $control_args = array();

        parent::__construct( 'ld_forum_course', __( 'Forum Course', 'learndash-bbpress' ), $widget_args, $control_args );
    }

    /**
     * Output widget form on admin page
     * @param  array  $instance Widget instance values
     * @return void
     */
    public function form( $instance ) {
        ?>

        <p>
            <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title', 'learndash-bbpress' ); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $instance['title'] ); ?>" />
        </p>

        <?php
    }

    /**
     * Update course instance values
     * @param  array  $new_instance Widget instance values
     * @param  array  $instance     Existing/old instance values
     * @return array                New sanitized values
     */
    public function update( $new_instance, $instance ) {
        $instance['title'] = sanitize_text_field( $new_instance['title'] );

        return $instance;
    }

    /**
     * Output widget HTML
     * @param  array  $args     Widget args
     * @param  array  $instance Widget inputs
     * @return void
     */
    public function widget( $args, $instance ) {
        if ( ! is_bbpress() ) {
            return;
        }

        $forum_id = bbp_get_forum_id();
        $forum_associated_courses = get_post_meta( $forum_id, '_ld_associated_courses', true );

        $widget_content = '<div class="ld-course-forum-links-wrapper">';
        $widget_content .= '<h2 class="widget-title">' . $instance['title'] . '</h2>';
        $widget_content .= '<ul>';

        foreach ( $forum_associated_courses as $course_id ) {
            $course_title = get_the_title( $course_id );
            $permalink = get_the_permalink( $course_id );

            $widget_content .= '<li><a href="' . $permalink . '">'. $course_title . '</a></li>';
        }

        $widget_content .= '</ul>';
        $widget_content .= "</div>";
        
        echo $args['before_widget'];
        if ( ! empty( $title ) ) { 
            echo $args['before_title'] . $title . $args['after_title'];
        } 
        
        echo $widget_content;
        echo $args['after_widget'];
    }
}

add_action( 'widgets_init', function() {
    register_widget( 'LearnDash_Forum_Course_Widget' );
} );