<?php

namespace IFT;

class Ads {

    private static $instance;

	public static function get_instance() {
		if ( ! isset( self::$instance ) ) {
			self::$instance = new self;
		}
			return self::$instance;
    }
    
    public function __construct() {
        
        add_action('wp_head', array($this, 'ads_head'));
    }

    public function ads_head() {
        ?>
        <script data-ad-client="ca-pub-4640138021036262" async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
        <?php        
    }
}