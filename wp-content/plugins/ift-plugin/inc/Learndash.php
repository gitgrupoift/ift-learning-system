<?php

namespace IFT;

class Learndash {

    private static $instance;

	public static function get_instance() {
        
		if ( ! isset( self::$instance ) ) {
			self::$instance = new self;
		}
			return self::$instance;
        
    }
    
    public function __construct() {
        
        if ( true === self::dependants_exist() ) {
            
            add_action( 'get_header', array( $this, 'enable_comments' ) );
            add_action( 'wp_head', array( $this, 'find_last_known_learndash_page' ) );
            //add_shortcode( 'ift-learndash-resume', array( $this, 'learndash_resume' ) );
            //add_shortcode( 'ift_learndash_resume', array( $this, 'learndash_resume' ) );
			//add_shortcode( 'ift_course_resume', array( $this, 'ift_course_resume' ) );
            
        }
        
    }
    
    // Habilita comentários que, por defeito, não estão presente no modo foco do Learndash
    function enable_comments() {
        
        remove_filter( 'comments_array', 'learndash_remove_comments', 1, 2 );
        remove_filter('comments_open', 'learndash_comments_open', 10, 2);
        
    }
    
	public static function dependants_exist() {
        
		global $learndash_post_types;
		if ( ! isset( $learndash_post_types ) ) {
			return 'Plugin: LearnDash';
		}

		return true;
        
	}
    
	/**
	 * Adiciona registo da última página de acesso pelo utilizador, dentre as atinentes às formações.
	 *
	 * @static
     * @since 1.1.0
     * 
	 */
	public static function find_last_known_learndash_page() {

		$user = wp_get_current_user();

		if ( is_user_logged_in() ) {

			global $post;
			if ( ! is_object( $post ) ) {
				return;
			}
            
            /**
             * FILTER >> last_known_learndash_post_types.
             *
             * Limita ou expande os tipos de conteúdo relacionados ao mecanismo de retorno do formando ao seu ponto de paragem anterior.
             *
             * @since 1.1.0
             *
             * @param   array   $post_type      Custom Post Types a ser filtrados. 
             *
             */
			$learn_dash_post_types = apply_filters(
				'last_known_learndash_post_types',
				array(
					'sfwd-courses',
					'sfwd-lessons',
					'sfwd-topic',
					'sfwd-quiz',
					'sfwd-certificates',
					'sfwd-assignment',
				)
			);

			$step_id        = $post->ID;
			$step_course_id = learndash_get_course_id( $step_id );

			if ( empty( $step_course_id ) ) {
				$step_course_id = 0;
			}

			if ( is_singular( $learn_dash_post_types ) ) {
				update_user_meta( $user->ID, 'learndash_last_known_page', $step_id . ',' . $step_course_id );
				if ( 'sfwd-courses' !== $post->post_type ) {
					update_user_meta( $user->ID, 'learndash_last_known_course_' . $step_course_id, $step_id );
				}
			}

		}
	}
    
    
    /**
     * Cria o mecanismo que permite identificar e processar as visitas do utilizador.
     * @static
     * @since 1.1.0
     *
	 * @param  array   $atts   {
     *      Relacionados ao shortcode desta funcionalidade.
     * }
	 *
	 * @return string
	 */
	public static function ift_course_resume( $atts ) {
		$atts = shortcode_atts( array(
			'course_id' => '',
		), $atts, 'ift_course_resume' );

		if ( is_user_logged_in() ) {
			if ( ! empty( $atts['course_id'] ) ) {
				$user           = wp_get_current_user();
				$step_course_id = $atts['course_id'];
				$course         = get_post( $step_course_id );

				if ( isset( $course ) && 'sfwd-courses' === $course->post_type ) {
					$last_know_step = get_user_meta( $user->ID, 'learndash_last_known_course_' . $step_course_id, true );


					if ( empty( $last_know_step ) ) {

						return '';
					}

					if ( absint( $last_know_step ) ) {
						$step_id = $last_know_step;
					} else {
						return '';
					}


					$last_know_post_object = get_post( $step_id );


					if ( null !== $last_know_post_object ) {

						$post_type        = $last_know_post_object->post_type; 
						$label            = get_post_type_object( $post_type );
						$title            = $last_know_post_object->post_title;
						$resume_link_text = __( 'RESUME', 'uncanny-learndash-toolkit' );

						$link_text = self::get_settings_value( 'learn-dash-resume-button-text', $this );
						$show_name = self::get_settings_value( 'learn-dash-resume-show-name', $this );

						if ( strlen( trim( $link_text ) ) ) {
							$resume_link_text = $link_text;
						}

						$resume_link_text = apply_filters( 'learndash_resume_link_text', $resume_link_text );

						$css_classes = apply_filters( 'learndash_resume_css_classes', 'learndash-resume-button' );

						ob_start();

						if ( function_exists( 'learndash_get_step_permalink' ) ) {
							$permalink = learndash_get_step_permalink( $step_id, $step_course_id );
						} else {
							$permalink = get_permalink( $step_id );
						}

						printf(
							'<a href="%s" title="%s" class="%s"><input type="submit" value="%s" class=""></a>',
							$permalink,
							esc_attr(
								sprintf(
									esc_html_x( 'Resume %s: %s', 'LMS shortcode Resume link title "Resume post_type_name: Post_title ', 'uncanny-learndash-toolkit' ),
									$label->labels->singular_name,
									$title
								)
							),
							esc_attr( $css_classes ),
							esc_attr( $resume_link_text )
						);
                                                
                                                if($show_name === 'on'){
                                                    printf(
                                                            '<div class="resume-item-name">%s</div>',
                                                            $title							
                                                    );
                                                }

						$resume_link = ob_get_contents();
						ob_end_clean();

						return $resume_link;
					}
				}
			}
		}

		return '';
	}

    
    /**
	 * SHORTCODE > [uo-learndash-resume] pode ser utilizado em qualquer página do Learndash ou sistema de e-learning para regressar ao ponto de paragem do utilizador atual.
	 *
     * @since 1.1.0
	 * @static
	 * @return string
	 */
	public static function learndash_resume() {

		$user = wp_get_current_user();

		if ( is_user_logged_in() ) {

			$last_know_step = get_user_meta( $user->ID, 'learndash_last_known_page', true );

			if ( empty( $last_know_step ) ) {

				return '';
			}

			$step_course_id = 0;

			if ( false !== strpos( $last_know_step, ',' ) ) {
				$last_know_step = explode( ',', $last_know_step );
				$step_id        = $last_know_step[0];
				$step_course_id = $last_know_step[1];
			} else {

				if ( absint( $last_know_step ) ) {
					$step_id = $last_know_step;
				} else {
					return '';
				}

			}

			$last_know_post_object = get_post( $step_id );

			if ( null !== $last_know_post_object ) {

				$post_type        = $last_know_post_object->post_type;
				$label            = get_post_type_object( $post_type );
				$title            = $last_know_post_object->post_title;
				$resume_link_text = 'RESUME';

				$link_text = self::get_settings_value( 'learn-dash-resume-button-text', $this );
                                $show_name = self::get_settings_value( 'learn-dash-resume-show-name', $this );

				if ( strlen( trim( $link_text ) ) ) {
					$resume_link_text = $link_text;
				}
                
                /**
                 * FILTER >> learndash_resume_link_text.
                 *
                 * Filtra o texto do link de regresso ao ponto de paragem.
                 *
                 * @since 1.1.0
                 *
                 * @param array $args {
                 *     Descrição em texto ou inserção no link.
                 * }
                 */
				$resume_link_text = apply_filters( 'learndash_resume_link_text', $resume_link_text );
                
                /**
                 * FILTER > learndash_resume_css_classes.
                 *
                 * Filtra o CSS do link de regresso ao ponto de paragem.
                 *
                 * @since 1.1.0
                 *
                 * @param type  $var Description.
                 * @param array $args {
                 *     Short description about this hash.
                 *
                 *     @type type $var Description.
                 *     @type type $var Description.
                 * }
                 * @param type  $var Description.
                 */
				$css_classes = apply_filters( 'learndash_resume_css_classes', 'learndash-resume-button' );

				ob_start();

				if ( function_exists( 'learndash_get_step_permalink' ) ) {
					$permalink = learndash_get_step_permalink( $step_id, $step_course_id );
				} else {
					$permalink = get_permalink( $step_id );
				}

				printf(
					'<a href="%s" title="%s" class="%s"><input type="submit" value="%s" class=""></a>',
					$permalink,
					esc_attr(
						sprintf(
							esc_html_x( 'Retornar %s: %s', 'LMS shortcode Resume link title "Resume post_type_name: Post_title ', 'ift-plugin' ),
							$label->labels->singular_name,
							$title
						)
					),
					esc_attr( $css_classes ),
					esc_attr( $resume_link_text )
				);
                                
                                if($show_name === 'on'){
                                    printf(
                                            '<div class="resume-item-name">%s</div>',
                                            $title							
                                    );
                                }
				$resume_link = ob_get_contents();
				ob_end_clean();

				return $resume_link;
			}

		}

		return '';
	}

    
}