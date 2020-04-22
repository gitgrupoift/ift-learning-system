<?php 

/**
 * Código a rodar durante a ativação do plugin.
 * Documentado em class-ift-plugin-activator.php
 */
function activate_ift_plugin() {
	require_once IFT_SETTINGS . 'class-ift-plugin-activator.php';
	Ift_Plugin_Activator::activate();
}
/**
 * Código a rodar durante a desativação do plugin.
 * Documentado em class-ift-plugin-deactivator.php
 */
function deactivate_ift_plugin() {
	require_once IFT_SETTINGS . 'class-ift-plugin-deactivator.php';
	Ift_Plugin_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_ift_plugin' );
register_deactivation_hook( __FILE__, 'deactivate_ift_plugin' );

/**
 * Arranque das funções e procedimentos do admin e definições,
 * Hooks, libraries e páginas de opções.
 */
require IFT_SETTINGS . 'class-ift-plugin.php';

function run_ift_plugin() {

	$plugin = new Ift_Plugin();
	$plugin->run();

}
run_ift_plugin();