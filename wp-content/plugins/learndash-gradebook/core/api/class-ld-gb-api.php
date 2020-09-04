<?php
/**
 * API functionality.
 *
 * @since 1.2.0
 */

defined( 'ABSPATH' ) || die;

/**
 * Class LD_GB_API
 *
 * API functionality.
 *
 * @since 1.2.0
 */
class LD_GB_API {

	/**
	 * LD_GB_API constructor.
	 *
	 * @since 1.2.0
	 */
	function __construct() {

		add_filter( 'ld_gb_admin_script_data', array( $this, 'script_data' ) );
		add_filter( 'rest_user_query', array( $this, 'remove_has_published_posts' ), 100, 2 );
	}

	/**
	 * Gets the active group ID for the Attendance, if one at all.
	 *
	 * @since 1.2.0
	 * @access private
	 *
	 * @param array $data
	 *
	 * @return array
	 */
	function script_data( $data ) {

		$data['nonce'] = wp_create_nonce( 'wp_rest' );

		return $data;
	}

	/**
	 * Removes `has_published_posts` from the query args so even users who have not published content are returned by
	 * the request.
	 *
	 * Silly WordPress...
	 *
	 * @see https://developer.wordpress.org/reference/classes/wp_user_query/
	 *
	 * @param array $prepared_args Array of arguments for WP_User_Query.
	 * @param WP_REST_Request $request The current request.
	 *
	 * @return array
	 */
	function remove_has_published_posts( $prepared_args, $request ) {

		if ( $request['has_published_posts'] === 'false' ) {
			unset( $prepared_args['has_published_posts'] );
		}

		return $prepared_args;
	}
}