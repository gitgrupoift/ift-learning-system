<?php 

namespace IFT\Learndash;

class Gradebook {
    
	public $api;
	public $field_helpers;
	public $support;
	public $posttypes;
	public $upgrade;
	public $welcome_page;
	public $admin_pages;
	public $gradebook_page;
	public $shortcodes;
	public $dashboard_widgets;
	public $settings_page;
	public $usergrades_page;
    public $notices;
    public $quickstart;

    protected function __clone() {
        
    }

    public static function instance() {

		static $instance = null;

		if ( $instance === null ) {
		  $instance = new LearnDash_Gradebook();
		}

		return $instance;
    }

    public function __construct() {
        
        $this->dependencies();
        $this->setup_helpers();
        $this->setup_support();
        
        add_action( 'init', array( $this, 'register_assets' ) );
        
    }
    
    private function dependencies() {}
    
    private function setup_helpers() {}
    
    private function setup_support() {}
    
    function register_assets() {}
    
    public function maybe_enqueue_assets() {}
    
    function admin_enqueue_assets() {}
    
    function setup_roles() {}
    
    
}