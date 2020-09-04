<?php
/**
 * Welcome page content.
 *
 * @since 1.2.0
 *
 * @var array $active_tab
 */

defined( 'ABSPATH' ) || die();
?>

<div class="wrap about-wrap">

    <h1>
		<?php
		printf(
		/* translators: %s is the current plugin version. */
			__( 'Welcome to LearnDash - Gradebook %s', 'learndash-gradebook' ),
			LEARNDASH_GRADEBOOK_VERSION
		);
		?>
    </h1>

    <p class="about-text">
		<?php _e( 'Thank you for updating to the latest version! LearnDash - Gradebook 1.2.0 introduces the ability to have Multiple Gradebooks, among many other features.', 'learndash-gradebook' ); ?>
    </p>
    <h2 class="nav-tab-wrapper wp-clearfix">
        <a href="<?php echo admin_url( 'index.php?page=learndash-gradebook-welcome&tab=whats-new' ); ?>"
           class="nav-tab <?php echo $active_tab === 'whats-new' ? 'nav-tab-active' : ''; ?>">
			<?php _e( 'What\'s New', 'learndash-gradebook' ); ?>
        </a>
        <a href="<?php echo admin_url( 'index.php?page=learndash-gradebook-welcome&tab=credits' ); ?>"
           class="nav-tab <?php echo $active_tab === 'credits' ? 'nav-tab-active' : ''; ?>">
			<?php _e( 'Credits', 'learndash-gradebook' ); ?>
        </a>
    </h2>

	<?php if ( $active_tab === 'whats-new' ) : ?>
        <div class="feature-section one-col">
            <div class="col">
                <h2>
					<?php _e( 'Enjoy the power and flexibility of having Multiple Gradebooks.', 'learndash-gradebook' ); ?>
                </h2>

                <p>
					<?php
					printf(
					/* translators: First %s is Course, second %s is Quizzes. Then the remainder %s are for wrapping a link to the Manage Gradebooks page */
                        __( 'Ever wanted to have a Gradebook for a specific %s? Or maybe a Gradebook for all of your %s just because? Well, now you can! You can create as many custom Gradebooks as your heart desires. Head on over to the %sManage Gradebooks%s section and get started now!', 'learndash-gradebook' ),
                        LearnDash_Custom_Label::get_label( 'course' ),
                        LearnDash_Custom_Label::get_label( 'quizzes' ),
						'<a href="' . admin_url( 'edit.php?post_type=gradebook' ) . '">',
						'</a>'
					); ?>
                </p>

                <p>
					<?php _e( 'If you\'re upgrading from an old version of LearnDash - Gradebook, you will see a Gradebook already created for you that contains all of your previous settings.', 'learndash-gradebook' ); ?>
                </p>
            </div>
        </div>

        <div class="changelog">
            <h2><?php _e( 'What Else?', 'learndash-gradebook' ); ?></h2>

            <div class="under-the-hood two-col">
                <div class="col">
                    <h3>
						<?php _e( 'Premium Support from Your Website', 'learndash-gradebook' ); ?>
                    </h3>

                    <p>
						<?php _e( 'It\'s now easier than ever to get in touch with our support team. There is a new Support Sidebar that shows up on all of the Gradebook Setting\'s pages. Once you activate your license with the website, you will be able to send support messages directly from the sidebar.', 'learndash-gradebook' ); ?>
                    </p>
                </div>

                <div class="col">
                    <h3>
                        <?php 
                            /* translators: First %s is Assignments */
                            printf( __( 'Integrate more tightly with LearnDash LMS %s.', 'learndash-gradebook' ), LearnDash_Custom_Label::get_label( 'assignments' ) ); 
                        ?>
                    </h3>

                    <p>
                        <?php 
                            /* translators: First %s is Assignment, second %s is Assignment, third %s is Assignments, fourth %s is Lesson, fifth %s is Topic, and sixth %s is Assignment */
                            printf( __( 'Previously, you had to add %s points into a new Gradebook field on each %s. Now you can simply use the native "Points" system for %s! Simply enable points from the %s or %s you desire, and then each %s will have a place to enter points, which will automatically average into a 0-100% scale.', 'learndash-gradebook' ), LearnDash_Custom_Label::get_label( 'assignment' ), LearnDash_Custom_Label::get_label( 'assignment' ), LearnDash_Custom_Label::get_label( 'assignments' ), LearnDash_Custom_Label::get_label( 'lesson' ), LearnDash_Custom_Label::get_label( 'topic' ), LearnDash_Custom_Label::get_label( 'assignment' ) );
                        ?>
                    </p>
                </div>
            </div>
        </div>
	<?php endif; ?>

	<?php if ( $active_tab === 'credits' ) : ?>
        <div class="about-wrap-content">
            <p class="about-description">
				<?php _e( 'LearnDash - Gradebook is created by the team at Real Big Plugins.', 'learndash-gradebook' ); ?>
            </p>

            <ul class="wp-people-group">
                <li class="wp-person">
		            <?php echo get_avatar( 'eric@realbigmarketing.com', 96, '', '', array( 'class' => 'gravatar' ) ); ?>
                    Eric Defore
                    <span class="title">
                        <?php _e( 'Gradebook Lead Developer', 'learndash-gradebook' ); ?>
                    </span>
                </li>
                <li class="wp-person">
					<?php echo get_avatar( 'joel@realbigmarketing.com', 96, '', '', array( 'class' => 'gravatar' ) ); ?>
                    Joel Worsham
                    <span class="title">
                        <?php _e( 'Gradebook Contributor', 'learndash-gradebook' ); ?>
                    </span>
                </li>
                <li class="wp-person">
		            <?php echo get_avatar( 'steve@realbigmarketing.com', 96, '', '', array( 'class' => 'gravatar' ) ); ?>
                    Steve Bennett
                    <span class="title">
                        <?php _e( 'Gradebook Contributor', 'learndash-gradebook' ); ?>
                    </span>
                </li>
            </ul>

            <p class="description">
                <?php _e( 'A very special thanks to Grant Aldrich for helping push this release, as well as keen insight from a professional in the academic field.', 'learndash-gradebook' ); ?>
            </p>

            <h3 class="wp-people-group"><?php _e( 'External Libraries', 'learndash-gradebook' ); ?></h3>

            <p class="wp-credits-list">
                <a href="https://github.com/realbig/rbm-field-helpers">RBM Field Helpers</a>, <a href="https://github.com/realbigplugins/rbp-support">RBP Support</a>, <a href="https://github.com/DubFriend/jquery.repeater">jQuery Repeater</a>, and <a href="http://trentrichardson.com/examples/timepicker">jQuery Timepicker Addon</a>
            </p>
        </div>
	<?php endif; ?>

    <hr/>

    <div class="return-to-dashboard">
        <a href="<?php echo admin_url( 'admin.php?page=learndash-gradebook' ); ?>">
			<?php _e( 'Go to Gradebooks page &rarr;', 'learndash-gradebook' ); ?>
        </a>
    </div>
</div>