<?php
/**
 * Installs the plugin.
 *
 * @since 1.2.0
 */

defined( 'ABSPATH' ) || die;

/**
 * Class LD_GB_Install
 *
 * Installs the plugin.
 *
 * @since 1.2.0
 */
class LD_GB_Install {

	/**
	 * Loads the install functions.
	 *
	 * @since 1.2.0
	 */
	static function install() {

		update_option( 'learndash_gradebook_db_version', '1.0.0' );

		self::setup_capabilities();
	}

	/**
	 * Sets up custom capabilities
	 *
	 * @since 1.2.0
	 * @access private
	 */
	public static function setup_capabilities() {

		foreach ( ld_gb_get_capabilities() as $role_ID => $capabilities ) {

			$role = get_role( $role_ID );

			if ( ! $role ) {
				continue;
			}

			foreach ( $capabilities as $capability ) {

				if ( ! $role->has_cap( $capability ) ) {

					$role->add_cap( $capability );
				}
			}
		}
	}
}