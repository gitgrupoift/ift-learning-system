<?php
/**
 * Plugin Name:       IFT Learning Tools
 * Plugin URI:        https://ti.ift.pt/
 * Description:       Plugin de definições e melhorias do sistema do IFT Learning. Conteúdo proprietário.
 * Version:           1.0.0
 * Author:            Grupo IFT
 * Author URI:        https://grupoift.pt/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       ift-plugin
 * Domain Path:       /languages
 */

use IFT\Learndash;
use IFT\Users;
use IFT\Rest;
use IFT\Optimize;
use IFT\Security;
use IFT\Bbpress;
use IFT\Backend;
use IFT\Customizer;

// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}

require __DIR__ .'/vendor/autoload.php';

new Learndash();
new Users();
new Security();
new Rest();
new Bbpress();
new Optimize();


add_action('after_setup_theme', 'override_theme', 999);

/**
 * Redirect non-admins to the homepage after logging into the site.
 *
 * @since 	1.0
 */
function student_login_redirect( $redirect_to, $request, $user  ) {
	return ( is_array( $user->roles ) && in_array( 'administrator', $user->roles ) ) ? admin_url() : site_url('/perfil');
}
add_filter( 'login_redirect', 'student_login_redirect', 10, 3 );



add_filter( 'wp_new_user_notification_email', 'welcome_email_template', 10, 3 );

function welcome_email_template( $wp_new_user_notification_email, $user, $blogname ) {
    $key = get_password_reset_key( $user );
    
    $message = sprintf(__('Seja bem-vindo ao IFT Learning.')) . "\r\n\r\n";
    $message .= 'Clique na ligação seguinte para definir a sua palavra-passe:' . "\r\n\r\n";
    $message .= network_site_url("wp-login.php?action=rp&key=$key&login=" . rawurlencode($user->user_login), 'login') . "\r\n\r\n";
    $message .= "Após fazê-lo, poderá sempre aceder diretamente à plataforma de aprendizagem online com as suas credenciais: e-mail e password." . "\r\n\r\n";
    $message .= "Com os melhores cumprimentos," . "\r\n";
    $message .= "Equipa IFT Learning" . "\r\n";
    $wp_new_user_notification_email['message'] = $message;

    $wp_new_user_notification_email['subject'] = "IFT Learning - Confirmação de Acesso";
    
    $wp_new_user_notification_email['headers'] = 'From: IFT Learning <geral@grupoift.pt>';

    return $wp_new_user_notification_email;
}

function remove_jquery_migrate( $scripts ) {
   if ( ! is_admin() && isset( $scripts->registered['jquery'] ) ) {
        $script = $scripts->registered['jquery'];
   if ( $script->deps ) { 
// Check whether the script has any dependencies

        $script->deps = array_diff( $script->deps, array( 'jquery-migrate' ) );
 }
 }
 }
add_action( 'wp_default_scripts', 'remove_jquery_migrate' );
