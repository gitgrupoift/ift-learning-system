<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       meuppt.pt
 * @since      1.0.0
 *
 * @package    Ift_Plugin
 * @subpackage Ift_Plugin/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Ift_Plugin
 * @subpackage Ift_Plugin/admin
 * @author     Carlos Artur Curvelo da Matos <geral@meuppt.pt>
 */
class Ift_Plugin_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;
        $this->load_dependencies();

	}
    /**
	 * Classes e dependências PHP de páginas de opções configuração.
	 *
	 * @since    1.4.0
	 */
    private function load_dependencies() {
		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once IFT_ADMIN . 'class-ift-plugin-settings.php';
	}
	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles($hook) {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Ift_Plugin_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Ift_Plugin_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
        if ( $hook != ('ift-learning_page_ift-plugin') ) { // Just triggers if is plugin page
		
			return;
		}

        wp_enqueue_style( 'bootstrap-admin-css', 'https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts($hook) {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Ift_Plugin_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Ift_Plugin_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
        if ( $hook != ('ift-learning_page_ift-plugin') ) { // Just triggers if is plugin page
		
			return;
		}

        wp_enqueue_script( 'popper', 'https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js', null, null, true );
		wp_enqueue_script( 'bootstrap-main', 'https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js', null, null, true );
        wp_enqueue_script( 'font-awesome-5', 'https://use.fontawesome.com/releases/v5.0.9/js/all.js', null, null, true );

	}

}
