<?php
/**
 * HTMl for the User Grades screen.
 *
 * @since 1.2.0
 *
 * @var int $gradebook Current gradebook
 * @var LD_GB_UserGrade $user_grade User grade object.
 * @var bool $is_weighted If grades are weighted or not.
 * @var WP_User $user Currently being viewed user.
 * @var array $grade_statuses
 * @var array $grade_status_options
 */

defined( 'ABSPATH' ) || die();

function ld_gb_grade_icon( $type ) {

	switch ( $type ) {

		case 'quiz':

			$icon = 'dashicons-editor-help';
			break;

		case 'assignment':

			$icon = 'dashicons-media-text';
			break;

		case 'lesson':

			$icon = 'dashicons-welcome-write-blog';
			break;

		case 'topic':

			$icon = 'dashicons-portfolio';
			break;

		case 'manual':

			$icon = 'dashicons-edit';
			break;

		default:

			$icon = '';
	}

	return $icon;
}

?>

<?php if ( isset( $_GET['return'] ) ) : ?>
    <p>
        <a href="<?php echo $_GET['referrer']; ?>" class="button ld-gb-button ld-gb-return-button">
			<?php
			switch ( $_GET['return'] ) {
				case 'gradebook':
					_e( 'Return to the Gradebook', 'learndash-gradebook' );
					break;

				case 'gradebook-edit':
					_e( 'Return to the Gradebook Editing', 'learndash-gradebook' );
					break;
			}
			?>
        </a>
    </p>
<?php endif; ?>

<div id="ld-gb-gradebook">

	<?php if ( isset( $_GET['ld_gb_back_to_gradebook'] ) ) : ?>
        <div class="notice notice-info ld-gb-notice inline">
            <p>
				<?php
				printf(
					__( 'To go back to the Gradebook %sclick here%s.', 'learndash-gradebook' ),
					'<a href="' . admin_url( 'admin.php?page=learndash-gradebook' ) . '">',
					'</a>'
				);
				?>
            </p>
        </div>
	<?php endif; ?>

    <h3>
		<?php
		printf(
			__( 'Grade for %s', 'learndash-gradebook' ),
			$user->display_name
		)
		?><span class="user-grade-separator">:</span>&nbsp;<span class="user-grade ld-gb-grade"
              style="background: <?php $user_grade->display_user_grade_color(); ?>;"><?php $user_grade->display_user_grade(); ?></span>
    </h3>

	<?php if ( $user_grade->get_components() ) : ?>
		<?php foreach ( $user_grade->get_components() as $component ) : ?>

            <div id="ld-gb-component-<?php echo $component['id']; ?>" class="ld-gb-gradebook-component">
                <div class="ld-gb-gradebook-component-header">
                    <div class="ld-gb-gradebook-component-name">
						<?php echo $component['name']; ?>
                    </div>

                    <div
                            class="ld-gb-gradebook-component-overall-grade <?php echo $component['overridden'] ? 'overridden' : ''; ?>"
                            data-ld-gb-component-grade="<?php echo $component['averaged_score']; ?>"
                            data-user-id="<?php echo $user->ID; ?>"
							data-component-id="<?php echo $component['id']; ?>">
							
						<?php if ( ! get_option( 'ld_gb_disable_component_override', false ) ) : ?>

							<a href="#" class="ld-gb-gradebook-component-edit-open" data-ld-gb-component-edit><?php
								if ( $component['overridden'] ) {

									_e( 'Modify', 'learndash-gradebook' );

								} else {

									_e( 'Override', 'learndash-gradebook' );
								}
								?></a>

							<span class="ld-gb-grade-overidden-icon dashicons dashicons-info"></span>

						<?php endif; ?>

							<?php echo LD_GB_UserGrade::display_grade_html( $component['averaged_score'] ); ?>

						<?php if ( ! get_option( 'ld_gb_disable_component_override', false ) ) : ?>

							<?php if ( $is_weighted ) : ?>
								<span class="ld-gb-gradebook-component-weight">
									<?php
									printf(
										__( 'Weight: %s', 'learndash-gradebook' ),
										$component['weight'] . '%'
									);
									?>
								</span>
							<?php endif; ?>

							<div class="ld-gb-gradebook-component-edit">

								<a href="#" class="ld-gb-gradebook-component-edit-close" data-ld-gb-component-cancel
								aria-label="<?php _e( 'Cancel', 'learndash-gradebook' ); ?>">
									<span class="dashicons dashicons-no"></span>
								</a>

								<?php
								ld_gb_do_field_number( array(
									'no_init' => true,
									'name'    => 'ld-gb-component-override',
									'id'      => false,
									'min'     => 0,
									'value'   => $component['averaged_score'],
									'postfix' => '%',
								) );
								?>

								<div class="ld-gb-gradebook-component-edit-actions">
									<button type="button" class="button" data-ld-gb-component-submit>
										<?php _e( 'Submit', 'learndash-gradebook' ); ?>
									</button>

									<a href="#" class="ld-gb-cancel" data-ld-gb-component-delete
									><?php _e( 'Remove', 'learndash-gradebook' ); ?></a>
								</div>
							</div>

						<?php endif; ?>
                    </div>
				</div>
				
				<?php if ( ! get_option( 'ld_gb_disable_component_override', false ) ) : ?>

					<div class="ld-gb-gradebook-component-overridden-notice notice notice-warning ld-gb-notice inline"
						<?php echo $component['overridden'] ? '' : 'style="display: none;"'; ?>>
						<p>
							<?php _e( 'Grade is being overridden.', 'learndash-gradebook' ); ?>
						</p>
					</div>

				<?php endif; ?>

                <table class="ld-gb-gradebook-component-grades">
                    <thead>
                    <tr>
                        <th colspan="2">
							<?php _e( 'Name', 'learndash-gradebook' ); ?>
                        </th>

                        <th class="ld-gb-gradebook-component-grade-score">
							<?php _e( 'Score', 'learndash-gradebook' ); ?>
                        </th>
                    </tr>
                    </thead>

                    <tbody>
					<?php
					$no_grades = ! $component['grades'];

					// Append manual template
					$component['grades'][] = array(
						'name'  => '',
						'score' => '',
						'type'  => 'manual',
					);
					?>
					<?php foreach ( $component['grades'] as $i => $grade ) : ?>
						<?php $is_template = $i === count( $component['grades'] ) - 1; ?>
                        <tr class="ld-gb-gradebook-component-grade-display <?php echo $i % 2 === 1 ? 'odd' : 'even'; ?>"
							<?php echo $is_template ? 'data-template' : ''; ?>>
                            <td class="ld-gb-gradebook-component-grade-name">

                                    <span class="ld-gb-gradebook-component-grade-icon"
                                          title="<?php echo ld_gb_get_grade_type_name( $grade['type'] ); ?>">
										<span class="dashicons <?php echo ld_gb_grade_icon( $grade['type'] ); ?>">
										</span>
									</span>

                                <span class="ld-gb-gradebook-component-grade-name-content">
										<?php if ( isset( $grade['post_id'] ) && $grade['post_id'] ) : ?>
                                            <a href="<?php echo add_query_arg( 'ld_gb_back_to_user', $user->ID, get_edit_post_link( $grade['post_id'] ) ); ?>">
												<?php echo $grade['name']; ?>
											</a>
										<?php else: ?>
											<?php echo $grade['name']; ?>
										<?php endif; ?>
									</span>
                                &nbsp;
							</td>

							<td class="ld-gb-gradebook-component-grade-actions">

								<?php if ( $grade['status'] !== 'pending' &&  
									( $grade['type'] !== 'assignment' || get_post_type( $grade['post_id'] ) == 'sfwd-assignment' ) ) : ?>

									<div class="ld-gb-gradebook-component-grade-actions-container">
										<a href="#" class="ld-gb-gradebook-component-grade-edit" data-edit-grade>
											<?php _e( 'Edit', 'learndash-gradebook' ); ?>
										</a>

										<?php if ( $grade['type'] == 'manual' ) : ?>
											&nbsp;
											<a href="#" class="ld-gb-gradebook-component-grade-remove ld-gb-cancel"
											data-remove-manual-grade>
												<?php _e( 'Remove', 'learndash-gradebook' ); ?>
											</a>
										<?php endif; ?>
									</div>

								<?php endif; ?>
								
							</td>

                            <td class="ld-gb-gradebook-component-grade-score">
									<span class="ld-gb-gradebook-component-grade-score-content"
                                          data-value-id="grade-score">
										<?php echo $grade['score_display']; ?>
									</span>
                            </td>
						</tr>
						
						<?php if ( $grade['status'] !== 'pending' &&  
							( $grade['type'] !== 'assignment' || get_post_type( $grade['post_id'] ) == 'sfwd-assignment' ) ) : ?>

							<tr class="ld-gb-gradebook-component-grade-editform"
								<?php echo $is_template ? 'data-template' : ''; ?>>
								<td colspan="3">

									<input type="hidden" name="grade-gradebook" value="<?php echo $gradebook; ?>"/>
									<input type="hidden" name="grade-type" value="<?php echo $grade['type']; ?>"/>
									<input type="hidden" name="grade-component"
										value="<?php echo $component['id']; ?>"/>
									<input type="hidden" name="grade-user_id" value="<?php echo $user->ID; ?>"/>

									<div class="ld-gb-gradebook-component-grade-message">
										<div class="notice inline notice-error">
											<p>
												<?php _e( 'Name and Score are required', 'learndash_gradebok' ); ?>
											</p>
										</div>
										
										<?php if ( ! get_option( 'ld_gb_disable_manual_grades', false ) ) : ?>

											<div class="notice inline notice-success">
												<p>
													<?php _e( 'Manual Grade successfully added', 'learndash_gradebok' ); ?>
												</p>
											</div>

										<?php endif; ?>
									</div>

									<?php if ( $grade['type'] !== 'manual' ) : ?>

										<input type="hidden" name="grade-post_id"
											value="<?php echo $grade['post_id']; ?>"/>
										<input type="hidden" name="grade-score"
											value="<?php echo $grade['original_score']; ?>"/>

									<?php elseif ( ! get_option( 'ld_gb_disable_manual_grades', false ) ) : ?>

										<input type="hidden" name="grade-new"
											value="<?php echo $is_template ? '1' : '0'; ?>"/>
										<input type="hidden" name="grade-previous_name"
											value="<?php echo $grade['name']; ?>"/>

										<label>
											<?php _e( 'Name', 'learndash-gradebook' ); ?><br/>
											<?php
											ld_gb_do_field_text( array(
												'no_init' => true,
												'name'    => 'grade-name',
												'value'   => $grade['name'],
											) );
											?>
										</label>

										<label>
											<?php _e( 'Score', 'learndash-gradebook' ); ?><br/>
											<input type="number" name="grade-score" min="0" max="100"
												value="<?php echo esc_attr( $grade['original_score'] ); ?>" data-default="<?php echo apply_filters( 'learndash_gradebook_manual_grade_default_score', '' ); ?>"/>
											<br/>
										</label>

									<?php endif; ?>

									<?php if ( $grade['status'] !== 'pending' ) : ?>

										<label>
											<?php _e( 'Status', 'learndash-gradebook' ); ?><br/>
											<?php
											ld_gb_do_field_select( array(
												'no_init'         => true,
												'name'            => 'grade-status',
												'select2_disable' => true,
												'option_none'     => __( 'No Special Status', 'learndash-gradebook' ),
												'options'         => $grade_status_options,
												'value'           => $grade['status'],
												'default_option'  => array(
													'' => __( '- No Special Status -', 'learndash-gradebook' ),
												),
												'no_options'      => array(
													'' => __( '- No Statuses -', 'learndash-gradebook' ),
												),
											) );
											?>
										</label>

									<?php endif; ?>

									<div class="ld-gb-gradebook-component-grade-editform-actions">
										<button type="button" data-submit-edit-grade
												class="button ld-gb-gradebook-component-grade-submit">
											<?php
											if ( $is_template ) {

												_e( 'Add', 'learndash-gradebook' );

											} else {

												_e( 'Change', 'learndash-gradebook' );
											}
											?>
										</button>

										<a href="#" class="ld-gb-cancel ld-gb-gradebook-component-grade-cancel"
											<?php echo $is_template ? 'data-cancel-add-manual-grade' : 'data-cancel-edit-grade'; ?>>
											<?php
											if ( $is_template ) {

												_e( 'Cancel', 'learndash-gradebook' );

											} else {

												_e( 'Close', 'learndash-gradebook' );
											}
											?>
										</a>
									</div>
								</td>
							</tr>

						<?php endif; ?>

					<?php endforeach; ?>

                    <tr class="ld-gb-gradebook-component-no-grades" data-no-grades
						<?php echo $no_grades ? '' : 'style="display: none;"'; ?>>
                        <td colspan="3">
							<?php _e( 'No Grades Yet', 'learndash-gradebook' ); ?>
                        </td>
                    </tr>

                    </tbody>
				</table>
				
				<?php if ( ! get_option( 'ld_gb_disable_manual_grades', false ) ) : ?>

					<button type="button" class="button ld-gb-gradebook-component-grade-add" data-add-manual-grade>
						<?php _e( 'Add Manual Grade', 'learndash-gradebook' ); ?>
					</button>

				<?php endif; ?>
            </div>
		<?php endforeach; ?>
	<?php else : ?>
		<?php _e( 'No Components setup.', 'learndash-gradebook' ); ?>
	<?php endif; ?>

</div>
