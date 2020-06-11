<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       google.com
 * @since      1.0.0
 *
 * @package    Sfwd_Lms_Course_Migration
 * @subpackage Sfwd_Lms_Course_Migration/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Sfwd_Lms_Course_Migration
 * @subpackage Sfwd_Lms_Course_Migration/admin
 * @author     Faizaan Gagan <fzngagan@gmail.com>
 */
class Sfwd_Lms_Course_Migration_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version     = $version;

		add_action( 'admin_menu', array( $this, 'addMenuPages' ) );

		// add_action( 'admin_menu', array( $this, 'addSubmenuPage' ) );

		add_action( 'rest_api_init', array( $this, 'registerRestRoutes' ) );

		add_action( 'wp_ajax_ldcm_fetch_course_data', array( $this, 'sendCourseData' ) );

		add_action( 'wp_ajax_ldcm_fetch_post_data', array( $this, 'sendPostData' ) );

		add_action( 'current_screen', array( $this, 'saveWhitelist' ) );

		add_action( 'admin_init', array( $this, 'deactivateIfLdUnactive' ) );

		add_action( 'wp_ajax_send_data_to_client', array( $this, 'sendDataToClient' ) );
		
		// add_action('admin_init',array($this,'checkfunc'));
		// add_action('init', function(){
		// 	$response = wp_remote_get('https://meta.discourse.org/latest.json');
		// 	print_r($response);
		// 	die();
		// });
	}

	public function sendDataToClient() {
	

		// $args     = array(
		// 	'body' => json_encode($_POST['json']),
		// );

		$response = wp_remote_post($_POST['url'], array(
			'headers'     => array('Content-Type' => 'application/json; charset=utf-8'),
			'body'        => stripslashes($_POST['json']),
			'method'      => 'POST',
			'data_format' => 'body',
		));
		// // $response = wp_remote_post( $_POST['url'], $args );
		echo '{success:"true"}';
			// echo "hello";
		echo json_encode($response);
		wp_die();
		
	}
	public function checkfunc() {
		$lessons = learndash_get_lesson_list( 11 );
		ldp( $lessons );
		die();
	}

	public function ldNotActiveNotice() {
		?>
	<div class="notice notice-error is-dismissible">
		<p><?php _e( 'LearnDash Plugin is not active! Please install and/or activate LearnDash to use Learndash Course Migration Plugin', 'sample-text-domain' ); ?></p>
	</div>
			<?php
	}
	public function deactivateIfLdUnactive() {
		if ( ! class_exists( 'SFWD_LMS' ) ) {
			add_action( 'admin_notices', array( $this, 'ldNotActiveNotice' ) );
			deactivate_plugins( 'sfwd-lms-course-migration/sfwd-lms-course-migration.php' );

		}
	}

	public function saveWhitelist( $screen ) {

		if ( wp_verify_nonce( $_POST['ldcm_nonce'], 'ldcm_whitelist_form' ) && $screen->id == 'course-migration_page_sfwd-lms-course-migration-admin-whitelist' && isset( $_POST['ldcm_save_whitelist'] ) ) {
			if ( isset( $_POST['ldcm_security_key'] ) ) {

				// $whitelist = array();

				// foreach ( $_POST['ldcm_whitelist'] as $url ) {
				// 	if ( ! empty( $url ) ) {

				// 		$escapedUrl   = esc_url_raw( $url );
				// 		$validatedUrl = wp_http_validate_url( $escapedUrl );
				// 		if ( $validatedUrl ) {
				// 			$whitelist[] = ( trailingslashit( $validatedUrl ) );
				// 		}
				// 	}
				// }
					update_option( 'ldcm_security_key', $_POST['ldcm_security_key'] );
				// update_option( 'ldcm_whitelist', $whitelist );

			} else {
				update_option( 'ldcm_whitelist', array() );
			}
		}
	}
	public function removeEmptyElements( $element ) {
		if ( element == ' ' ) {
			return false;
		}

	}
	public function sendCourseData() {
		// die if nonce verification fails
		check_ajax_referer( 'ldcm_ajax_security' );

		$courseId = sanitize_text_field( $_POST['course_id'] );
		$courseId = intval( $courseId );
		$lessons  = learndash_get_lesson_list( $courseId );

		// $lessonTitles = wp_list_pluck($lessons,'post_title','ID');
		echo '<br/>';
		echo "<div class='form-check-input'>";
		echo "<input type='hidden' name='selected_data[] 'value='$courseId' />";
		echo '</div>';

		foreach ( $lessons as $lesson ) {
			echo '<br/>';
			echo "<div id='step-{$lesson->ID}' class='form-check-input'>";
			echo "<input type='checkbox' name='selected_data[]' value={$lesson->ID} /><label>{$lesson->post_title}</label>";
			echo "<img class='ldcm-loader ldcm-loader-{$lesson->ID}'/>";
			echo '</div>';
			$topics = learndash_get_topic_list( $lesson->ID );

			foreach ( $topics as $topic ) {
				echo '<br/>';
				echo "<div id='step-{$topic->ID}' class='form-check-input'>";
				echo "<input type='checkbox'  style='margin-left:5em;'  name='selected_data[]' value={$topic->ID} /> <label>{$topic->post_title}</label>";
				echo "<img class='ldcm-loader ldcm-loader-{$topic->ID}'/>";
				echo '</div>';
			}
		}
		wp_die();
	}


	public function sendPostData() {
		check_ajax_referer( 'ldcm_ajax_security', $_POST['_nonce'] );

		$postId   = sanitize_text_field( $_POST['post_id'] );
		$postId   = intval( $postId );
		$post     = (array) get_post( $postId );
		$postMeta = (array) get_post_meta( $postId );

		$response = array(
			'post'      => $post,
			'post_meta' => $postMeta,
		);
		wp_send_json( $response );

	}

	public function registerRestRoutes() {
		register_rest_route(
			'ldcm',
			'/migratecourses',
			array(
				'methods'  => 'POST',
				'callback' => array( $this, 'saveData' ),
			)
		);

		// register_rest_route(
		// 	'ldcm',
		// 	'/whitelist',
		// 	array(
		// 		'methods'  => 'GET',
		// 		'callback' => array( $this, 'checkWhitelist' ),
		// 	)
		// );
	}
	public function removeWWW( $url ) {
		return preg_replace( '#^www\.(.+\.)#i', '$1', $url );
	}

	public function checkWhitelist( $urlToCheck ) {
		$whitelist = get_option( 'ldcm_whitelist', array() );

		foreach ( $whitelist as $url ) {
			if ( 0 === strcmp( $urlToCheck, $url . 'wp-admin/admin.php?page=sfwd-lms-course-migration-admin' ) ) {
				return true;
			}
		}

		return false;
	}

	public function saveData( $data ) {

		// if ( ! $this->checkWhitelist( $_SERVER['HTTP_REFERER'] ) ) {
		// 	return new WP_REST_Response( array( 'postdata' => 'forbidden ' ), 401 );

		// }

		$postParams = $data->get_params();
		
		// this security variable contains a MD5 hashed key which should be checked against the local key
		$security = $postParams['ldcm_security'];

		$securityOption = get_option( 'ldcm_security_key' );

		if ( $security !== md5( $securityOption ) ) {
				return new WP_REST_Response( array( 'postdata' => 'forbidden ' ), 401 );

		}

		$postdata = $postParams['postdata']['post'];
		$postmeta = $postParams['postdata']['post_meta'];

		// to avoid conflicting ids
		// WordPress will create a new post
		unset( $postdata['ID'] );

		$newPostId = wp_insert_post( $postdata );

		foreach ( $postmeta as $key => $value ) {
			update_post_meta( $newPostId, $key, $value );
		}
		/**
		 * {"ID":11,"post_author":"1","post_date":"2018-12-31 12:08:50","post_date_gmt":"2018-12-31 17:08:50","post_content":"","post_title":"Florida 4 Hour Basic Driver Improvement","post_excerpt":"","post_status":"publish","comment_status":"closed","ping_status":"closed","post_password":"","post_name":"florida-4-hour-bdi","to_ping":"","pinged":"","post_modified":"2019-05-03 10:35:33","post_modified_gmt":"2019-05-03 14:35:33","post_content_filtered":"","post_parent":0,"guid":"http:\/\/localhost\/billy.com\/?post_type=sfwd-courses&#038;p=11","menu_order":0,"post_type":"sfwd-courses","post_mime_type":"","comment_count":"0","filter":"raw"}
		 */

		// Create the response object

		$response = new WP_REST_Response( array( 'createdPost' => $newPostId ) );

		return $response;
	}




	public function addMenuPages() {

		//do not show the plugin settings to non-admin users
		if ( ! current_user_can( 'administrator' ) ) {
			return;
		}

		add_menu_page( 'Course Migration', 'Course Migration', 'manage_options', 'sfwd-lms-course-migration-admin', array( $this, 'showMenuPage' ), 'dashicons-update' );

		add_submenu_page( 'sfwd-lms-course-migration-admin', 'Security Key', 'Security Key', 'manage_options', 'sfwd-lms-course-migration-admin-whitelist', array( $this, 'showSubmenuPage' ) );

	}



	public function showMenuPage() {
		$args    = array(
			'post_type' => 'sfwd-courses',
			'status'    => 'publish',
			'posts_per_page' => -1
		);
		$courses = get_posts( $args );
		// ldp(get_current_screen());
		// die();
		include LDCM_BASE_PATH . 'admin/partials/sfwd-lms-course-migration-admin-display.php';
	}

	public function showSubmenuPage() {
		include LDCM_BASE_PATH . 'admin/partials/sfwd-lms-course-migration-admin-whitelist.php';

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Sfwd_Lms_Course_Migration_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Sfwd_Lms_Course_Migration_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/sfwd-lms-course-migration-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Sfwd_Lms_Course_Migration_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Sfwd_Lms_Course_Migration_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/sfwd-lms-course-migration-admin.js', array( 'jquery' ), $this->version, false );
		// wp_localize_script( $this->plugin_name, 'admindata', array( 'adm' ) );

		$url = array(
			'siteurl'  => site_url(),
			'endpoint' => '/wp-json/ldcm/migratecourses',
		);
		wp_localize_script( $this->plugin_name, 'ldcm_urls', $url );
		$icons = array(
			'loading'  => LDCM_BASE_URL . 'admin/assets/Spinner-1s-200px.svg',
			'complete' => LDCM_BASE_URL . 'admin/assets/icons8-checkmark.svg',
			'error'    => LDCM_BASE_URL . 'admin/assets/cancel.svg',
		);
		wp_localize_script( $this->plugin_name, 'ldcm_icons', $icons );

		$ajaxNonce = array(
			'nonce' => wp_create_nonce( 'ldcm_ajax_security' ),
		);
		wp_localize_script( $this->plugin_name, 'ajaxNonce', $ajaxNonce );

		$sc1    = 'toplevel_page_sfwd-lms-course-migration-admin';
		$sc2    = 'course-migration_page_sfwd-lms-course-migration-admin-whitelist';
		$screen = get_current_screen()->id;
		if ( $sc1 == $screen || $sc2 == $screen ) {

			wp_enqueue_style( 'ldcm-bs-css', LDCM_BASE_URL . 'admin/css/bootstrap.min.css' );
			wp_enqueue_script( 'ldcm-bs-popper', LDCM_BASE_URL . 'admin/js/popper.min.js', array( 'jquery' ) );
			wp_enqueue_script( 'ldcm-bs-js', LDCM_BASE_URL . 'admin/js/bootstrap.min.js', array( 'jquery' ) );
			wp_enqueue_script( 'MD5', LDCM_BASE_URL . 'admin/js/MD5.js' );

		}

	}

}
