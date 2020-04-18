<?php

namespace IFT\Tools;

class Cloner {
    
    private static $instance;
    
    public $types_forbidden = array(
                'sfwd-courses',
                'sfwd-quiz'
            );

	public static function get_instance() {
		if ( ! isset( self::$instance ) ) {
			self::$instance = new self;
		}
			return self::$instance;
    }
    
    public function __construct() {
        
        $this->types_forbidden = $types_forbidden;
        
        add_action('admin_action_clone_as_draft', array($this, 'clone_as_draft'));
        add_filter('post_row_actions', array($this, 'clone_action_link'), 10, 2);

        
    }
    
    /**
     * Clona um conteúdo e gera um novo rascunho, com mesmo título seguido da data do dia.
     *
     * @since 1.3.0
     * @param   void
     */
    public function clone_as_draft() {
        
          global $wpdb;
          if (! ( isset( $_GET['post']) || isset( $_POST['post'])  || ( isset($_REQUEST['action']) && 'clone_as_draft' == $_REQUEST['action'] ) ) ) {
            wp_die('Não há conteúdo a clonar!');
          }

          if ( !isset( $_GET['duplicate_nonce'] ) || !wp_verify_nonce( $_GET['duplicate_nonce'], basename( __FILE__ ) ) )
            return;

          $post_id = (isset($_GET['post']) ? absint( $_GET['post'] ) : absint( $_POST['post'] ) );
    
          $post = get_post( $post_id );

          $current_user = wp_get_current_user();
          $new_post_author = $current_user->ID;

          if (isset( $post ) && $post != null) {

            $args = array(
              'comment_status' => $post->comment_status,
              'ping_status'    => $post->ping_status,
              'post_author'    => $new_post_author,
              'post_content'   => $post->post_content,
              'post_excerpt'   => $post->post_excerpt,
              'post_name'      => $post->post_name,
              'post_parent'    => $post->post_parent,
              'post_password'  => $post->post_password,
              'post_status'    => 'draft',
              'post_title'     => $post->post_title . ' - Clone de ' . date('d-m-Y'),
              'post_type'      => $post->post_type,
              'to_ping'        => $post->to_ping,
              'menu_order'     => $post->menu_order
            );

            $new_post_id = wp_insert_post( $args );


            $taxonomies = get_object_taxonomies($post->post_type); 
            foreach ($taxonomies as $taxonomy) {
              $post_terms = wp_get_object_terms($post_id, $taxonomy, array('fields' => 'slugs'));
              wp_set_object_terms($new_post_id, $post_terms, $taxonomy, false);
            }

            $post_meta_infos = $wpdb->get_results("SELECT meta_key, meta_value FROM $wpdb->postmeta WHERE post_id=$post_id");
            if (count($post_meta_infos)!=0) {
              $sql_query = "INSERT INTO $wpdb->postmeta (post_id, meta_key, meta_value) ";
              foreach ($post_meta_infos as $meta_info) {
                $meta_key = $meta_info->meta_key;
                if( $meta_key == '_wp_old_slug' ) continue;
                $meta_value = addslashes($meta_info->meta_value);
                $sql_query_sel[]= "SELECT $new_post_id, '$meta_key', '$meta_value'";
              }
              $sql_query.= implode(" UNION ALL ", $sql_query_sel);
              $wpdb->query($sql_query);
            }

            wp_redirect( admin_url( 'post.php?action=edit&post=' . $new_post_id ) );
            exit;
          } else {
            wp_die('A clonagem falhou, pois não há como encontrar o original: ' . $post_id);
          }
        
    }
    
    /**
     * Cria o link para acionamento do mecanismo de clonagem na área de edição do Wordpress, excepto para formações ou questionários.
     *
     *
     * @since   1.3.0
     * @param   $actions    string      Nome da ação a desempenhar.
     * @param   $post       string      Post a ser criado pela ação - o clone neste caso.
     */
    public function clone_action_link($actions, $post) {
        
        if (current_user_can('edit_posts')) {
            
            $actions['duplicate'] = '<a href="' . wp_nonce_url('admin.php?action=clone_as_draft&post=' . $post->ID, basename(__FILE__), 'duplicate_nonce' ) . '" title="Clonar" rel="permalink">Clonar</a>';

        }
        
        if ( get_post_type() === 'sfwd-courses' ) {
            unset($actions['duplicate']);
        }
        
        if ( get_post_type() === 'sfwd-quiz' ) {
            unset($actions['duplicate']);
        }
        
        return $actions;
        
    }

}