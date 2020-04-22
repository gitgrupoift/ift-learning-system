<?php
/**
 * The settings of the plugin.
 *
 * @link       http://devinvinson.com
 * @since      1.0.0
 *
 * @package    Wc_Gdpr_Aan
 * @subpackage Wc_Gdpr_Aan/admin
 */
/**
 * Class WordPress_Plugin_Template_Settings
 *
 */
class Ift_Plugin_Settings {
    
	/**
	 * O ID do plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;
	/**
	 * A versão deste plugin.
	 *
	 * @since    1.4.0
	 * @access   private
	 * @var      string    $version    Versão atual do plugin.
	 */
	private $version;
	/**
	 * Inicializa a classe.
	 *
	 * @since    1.4.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    A versão deste plugin.
	 */
	public function __construct( $plugin_name, $version ) {
		$this->plugin_name = $plugin_name;
		$this->version = $version;
	}
    
    /**
	 * Regista o menu principal das definições na aba do IFT Learning.
	 *
	 * @since    1.4.0
	 */
	public function add_plugin_admin_menu() {
        
        add_submenu_page( 'learndash-lms', 'Gestão IFT', 'Gestão IFT', 'manage_options', $this->plugin_name, array($this, 'plugin_page')
	    );
        
    }

    /**
	 * Guarda as opções na base de dados.
	 *
	 * @since    1.4.0
	 */
    public function options_update() {
	
	    register_setting( $this->plugin_name, $this->plugin_name);
	    
	}
    
    /**
	 * Renderização da página principal das opções.
	 *
	 * @since    1.4.0
	 */
	public function plugin_page() {
	    include_once( 'partials/' . 'ift-plugin-admin-display.php' );
	}
    
}