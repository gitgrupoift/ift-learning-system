<?php 

namespace SynologyPS\Api;

class ApiException extends \Exception {
    
    public function __construct($message = null,
                                $code = null){
        
        if ( empty($message) ) {
            switch ($code) {
                    
                case 101:
                    $message = __( 'Invalid parameter', 'wp-synology-services' );
                    break;
                case 102:
                    $message = __( 'The requested API does not exist', 'wp-synology-services' );
                    break;
                case 103:
                    $message = __( 'The requested method does not exist', 'wp-synology-services' );
                    break;
                case 104:
                    $message = __( 'The requested version does not support the functionality', 'wp-synology-services' );
                    break;
                case 105:
                    $message = __( 'The logged in session does not have permission', 'wp-synology-services' );
                    break;
                case 106:
                    $message = __( 'Session timeout', 'wp-synology-services' );
                    break;
                case 107:
                    $message = __( 'Session interrupted by duplicate login', 'wp-synology-services' );
                    break;
                case 400:
                    $message = __( 'No such account or incorrect password', 'wp-synology-services' );
                    break;
                case 401:
                    $message = __( 'Guest account disabled', 'wp-synology-services' );
                    break;
                case 402:
                    $message = __( 'Account disabled', 'wp-synology-services' );
                    break;
                case 403:
                    $message = __( 'Wrong password', 'wp-synology-services' );
                    break;
                case 404:
                    $message = __( 'Permission denied', 'wp-synology-services' );
                    break;
                default:
                    $message = __( 'Unknown', 'wp-synology-services' );
                    break;
            }
        }
        
        parent::__construct($message, $code);
        
    }
    
}
