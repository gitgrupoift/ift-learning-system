<?php
/**
 * Plugin Name:       IFT Learning Tools
 * Plugin URI:        https://ti.ift.pt/
 * Description:       Plugin de definições e melhorias do sistema do IFT Learning. Conteúdo proprietário. Ferramentas, integrações com APIs e aplicações externas e melhorias de funcionalidades.
 * Version:           1.3.0
 * Author:            Grupo IFT
 * Author URI:        https://grupoift.pt/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       ift-plugin
 * Domain Path:       /languages
 */

use IFT\Learndash;
use IFT\Users;
use IFT\Config;
use IFT\Rest;
use IFT\Optimize;
use IFT\Security;
use IFT\Bbpress;
use IFT\Backend;
use IFT\Customizer;
use IFT\Ads;
use IFT\Talk;
use IFT\Zoom;
use IFT\Settings;
use IFT\Woocommerce\Woocommerce;
use IFT\Tools\Tools;

// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}

require __DIR__ .'/vendor/autoload.php';

new Learndash();
new Users();
new Security();
new Rest();
new Config();
new Bbpress();
new Optimize();
new Ads();
new Backend();
new Talk();
new Zoom();
new Woocommerce();
new Settings();
new Tools();




add_action('after_setup_theme', 'override_theme', 999);

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





