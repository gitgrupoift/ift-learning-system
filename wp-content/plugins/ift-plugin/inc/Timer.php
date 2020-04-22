<?php

namespace IFT;

class Timer {
    
    public function __construct() {
        
        add_action('wp_enqueue_scripts', array($this, 'timer_js'));
        add_action('wp_footer', array($this, 'timer_init'));
        
    }
    
    public function timer_js() {
        
        if(is_singular('sfwd-lessons') || is_singular('sfwd-topic') || is_singular('sfwd-quiz')) {
            
            wp_register_script('ift-timer', IFT_ASSETS . 'ift-timer.js');
            wp_enqueue_script('ift-timer');
            
        }
    }
    
    public function timer_init() {
        
        if(is_singular('sfwd-lessons') || is_singular('sfwd-topic') || is_singular('sfwd-quiz')) {
            
        ?>
            <script type="text/javascript">

                TimeMe.initialize({
                currentPageName: "<?php get_the_title(); ?>",
                idleTimeoutInSeconds: 120
                });

                var timeSpentOnPage = TimeMe.getTimeOnCurrentPageInSeconds();

            </script>

        <?php
            
        }  
    }
    
}