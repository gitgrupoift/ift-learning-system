<?php
/**
 * Bootstrapper for the plugin.
 *
 * Makes sure everything is good to go for loading the plugin, and then loads it.
 *
 * @since 1.2.0
 */

defined( 'ABSPATH' ) || die;

/**
 * Class LearnDash_Gradebook_Bootstrapper
 *
 * Bootstrapper for the plugin.
 *
 * Makes sure everything is good to go for loading the plugin, and then loads it.
 *
 * @since 1.2.0
 */
class LearnDash_Gradebook_Bootstrapper {

	/**
	 * Notices to show if cannot load.
	 *
	 * @since 1.2.0
	 * @access private
	 *
	 * @var array
	 */
	private $notices = array();

	/**
	 * LearnDash_Gradebook_Bootstrapper constructor.
	 *
	 * @since 1.2.0
	 */
	function __construct() {

		add_action( 'plugins_loaded', array( $this, 'maybe_load' ), 1 );
	}

	/**
	 * Maybe loads the plugin.
	 *
	 * @since 1.2.0
	 * @access private
	 */
	function maybe_load() {

		$php_version = phpversion();
		$wp_version  = get_bloginfo( 'version' );

		// Minimum PHP version
		if ( version_compare( $php_version, '5.3.0' ) === - 1 ) {

			$this->notices[] = sprintf(
				__( 'Minimum PHP version of 5.3.0 required. Current version is %s. Please contact your system administrator to upgrade PHP to its latest version.', 'learndash-gradebook' ),
				$php_version
			);
		}

		// Minimum WordPress version
		if ( version_compare( $wp_version, '4.8.0' ) === - 1 ) {

			$this->notices[] = sprintf(
				__( 'Minimum WordPress version of 4.8.0 required. Current version is %s. Please contact your system administrator to upgrade WordPress to its latest version.', 'learndash-gradebook' ),
				$wp_version
			);
		}

		// LearnDash Activated
		if ( ! defined( 'LEARNDASH_VERSION' ) ) {

			$this->notices[] = __( 'LearnDash LMS must be installed and activated.', 'learndash-gradebook' );
		}
		
		// LearndDash at version
		if ( defined( 'LEARNDASH_VERSION' ) ) {
			
			// Pad in a Patch version if necessary
			$ld_version = ( substr_count( LEARNDASH_VERSION, '.' ) == 1 ) ? LEARNDASH_VERSION . '.0' : LEARNDASH_VERSION;
			
			if ( version_compare( $ld_version, '2.3.0' ) === -1 ) {

				$this->notices[] = sprintf(
					__( 'LearnDash LMS must be at least version 2.3.0. Current version is %s.', 'learndash-gradebook' ),
					$ld_version
				);
				
			}
			
		}

		// Don't load and show errors if incompatible environment.
		if ( ! empty( $this->notices ) ) {

			add_action( 'admin_notices', array( $this, 'notices' ) );

			return;
		}

		$this->load();
	}

	/**
	 * Loads the plugin.
	 *
	 * @since 1.2.0
	 * @access private
	 */
	private function load() {

		LearnDash_Gradebook();
	}

	/**
	 * Shows notices on failure to load.
	 *
	 * @since 1.2.0
	 * @access private
	 */
	function notices() {
		?>
        <div class="notice error">
            <p>
				<?php
				printf(
					__( '%sLearnDash - Gradebook%s could not load because of the following errors:', 'learndash-gradebook' ),
					'<strong>',
					'</strong>'
				);
				?>
            </p>

            <ul>
				<?php foreach ( $this->notices as $notice ) : ?>
                    <li>
						&bull;&nbsp;<?php echo $notice; ?>
                    </li>
				<?php endforeach; ?>
            </ul>
        </div>
		<?php
	}
}