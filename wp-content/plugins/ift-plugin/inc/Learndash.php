<?php

namespace IFT;

use IFT\Learndash\GroupDates;
/*
Timer nos tópicos - funções e hooks a utilizar:
- learndash_get_topic_list()
- learndash_forced_lesson_time()

*/

class Learndash {

    private static $instance;

	public static function get_instance() {
        
		if ( ! isset( self::$instance ) ) {
			self::$instance = new self;
		}
			return self::$instance;
        
    }
    
    public function __construct() {
            
        add_action( 'get_header', array( $this, 'enable_comments' ) );
        add_filter('learndash_content_tabs', array($this, 'add_tabs'));
        
        add_shortcode( 'ld-hours-completed', array($this, 'learndash_course_completed_hours'));
        add_shortcode( 'ld-courses-and-hours', array($this, 'learndash_user_course_enrollment_and_hours'));
        
        $this->require();
        
    }
    
    public function require() {
        
        //new GroupDates();
        
    }
    /**
     * Habilita comentários que, por defeito, não estão presentes no modo foco do Learndash.
     *
     * @param   void
     */
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
     * Exibe as horas concluídas equivalentes no cursos, pelo utilizador atual.
     *
     * @param   void
     */
    public function learndash_course_completed_hours() {
        
        if( is_singular('ld-notification')) {
            $course_id  = get_post_meta( get_the_ID(), '_ld_notifications_course_id', true );            
        } elseif( is_singular('sfwd-courses')) {
            $course_id = get_the_ID();           
        }
    
        $completed_hours = get_post_meta( $course_id, 'course_points', true ) . __(' Horas Concluídas', 'ift-plugin');
        
        return $completed_hours;
    }
    
    /**
     * Exibe todos os cursos com as horas equivalentes concluídas, por utilizador.
     *
     * @param   void
     */
    public function learndash_user_course_enrollment_and_hours() {
        
        $user_id = get_current_user_id();
        $user_courses = ld_get_mycourses( $user_id );
        
        $item_li = '<ul class="user-course-list">' . PHP_EOL;      
        
        foreach ( $user_courses as $course_item ) {
            
            $is_completed = learndash_course_completed( $user_id, $course_item );           
            $item_hours = get_post_meta( $course_item, 'course_points', true );
            
            $item_li .= '<li class="user-course-list-item">' . get_the_title($course_item);
            
            if ( $item_hours == null ) {                
                 $item_li .= ' | <strong>sem atribuição de horas.</strong>';
                
            } else {                
                $item_li .= ' | <span class="ld-courses-hours">' . $item_hours . '  Horas</span>';
                
                if ($is_completed == true) {
                    $item_li .= ' | <span class="ld-course-finished">Concluído</span>';
                    
                } else {
                    $item_li .= ' | <span class="ld-course-ongoing">Em Curso</span>';
                }
                
            }
            
            $item_li .= '</li>';
                        
        }
        
        
        $item_li .= '</ul>';
        
        return $item_li;
        
    }
    
    public function learndash_user_course_hours() {
        
        $user_id = get_current_user_id();
        $user_courses = ld_get_mycourses( $user_id );
        
        $item_li = '<ul class="user-course-list">' . PHP_EOL;      
        
        foreach ( $user_courses as $course_item ) {
            
            $is_completed = learndash_course_completed( $user_id, $course_item );           
            $item_hours = get_post_meta( $course_item, 'course_points', true );
            
            $item_li .= '<li class="user-course-list-item">' . get_the_title($course_item);
            
            if ( $item_hours == null ) {                
                 $item_li .= ' | <strong>sem atribuição de horas.</strong>';
                
            } else {                
                $item_li .= ' | <span class="ld-courses-hours">' . $item_hours . '  Horas</span>';
                
                if ($is_completed == true) {
                    $item_li .= ' | <strong><span class="ld-course-finished">Concluído</span></strong>';
                    
                } else {
                    $item_li .= ' | <span class="ld-course-ongoing">Em Curso</span>';
                }
                
            }
            
            $item_li .= '</li>';
                        
        }
        
        
        $item_li .= '</ul>';
        
        return $item_li;
        
    }
    
    /**
     * Tabs adicionais às páginas das formações.
     *
     * @since 1.3.0
     * @param   $tabs   string  Novas tabs a serem adicionadas
     */
    public function add_tabs($tabs) {
        
        $tabs['description'] = array(
            'id'      => 'description',
			'icon'    => 'ld-icon-assignment',
			'label'   => __( 'Descrição', 'ift-plugin' ),
			'content' => self::description_tab(),
		);
        
        return $tabs;
        
    }
    
    /**
     * Cria e organiza a nova tab Descrição para os cursos.
     *
     * @since 1.3.0
     * @param   void
     */
    public static function description_tab() {
        
        $content = '<header class="description-tab-header">Carga Horária da Formação | ' . get_field('carga_horaria') . ' horas</header>';
        $content .= '<hr>';
        // Objetivos
        $content .= '<h3 class="tab-intertitle">Objetivos da Formação</h3>';
        $content .= get_field('objetivo_geral') . get_field('objetivos_especificos');
        $content .= '<hr>';
        // Destinatários, Modalidade e Organização
        $content .= '<h3 class="tab-intertitle">Características</h3><ul class="description-list">';
        $content .= '<li><span>DESTINATÁRIOS</span>' . get_field('destinatarios') . '</li>';
        $content .= '<li><span>MODALIDADE DA FORMAÇÃO</span>' . get_field('modalidade_da_formacao') . '</li>';
        $content .= '<li><span>ORGANIZAÇÃO DA FORMAÇÃO</span>' . get_field('organizacao_da_formacao') . '</li></ul>';
        // Restante dos textos
        $content .= '<hr>';
        $content .= '<h3 class="tab-intertitle">Metodologia de Avaliação</h3>';
        $content .= get_field('metodologia_de_avaliacao');
        $content .= '<hr>';
        $content .= '<h3 class="tab-intertitle">Programa do Curso</h3>';
        $content .= get_field('programa_do_curso');
        $content .= '<hr>';
        $content .= '<h3 class="tab-intertitle">Recursos Pedagógicos</h3>';
        $content .= get_field('recursos_pedagogicos');
        
        
        return $content;
        
    }
    
    public function get_topic_lesson_timer() {
        
        $time = learndash_forced_lesson_time();
        $hours = floor($time / 3600);
        $minutes = floor(($time / 60) % 60);
        echo $hours . ':' . $minutes;
        /*if(is_singular('swfd-topic')) {
            $lesson_id = learndash_get_lesson_id();
            $timer = learndash_forced_lesson_time($lesson_id);
            
            print_r($timer);
        }*/
    }

}