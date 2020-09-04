<?php
/**
 * Contains the grade for a given user.
 *
 * @since 1.0.0
 *
 * @package LearnDash_Gradebook
 * @subpackage LearnDash_Gradebook/includes
 */

defined( 'ABSPATH' ) || die();

/**
 * Class LD_GB_UserGrade
 *
 * Contains the grade for a given user's course.
 *
 * @since 1.0.0
 *
 * @package LearnDash_Gradebook
 * @subpackage LearnDash_Gradebook/includes
 */
class LD_GB_UserGrade {

	/**
	 * The Gradebook post.
	 *
	 * @since 1.2.0
	 * @access private
	 *
	 * @var WP_Post
	 */
	private $gradebook;

	/**
	 * The user object for the grade.
	 *
	 * @since 1.0.0
	 * @access private
	 *
	 * @var WP_User
	 */
	private $user;

	/**
	 * Arguments for the grade.
	 *
	 * @since 1.0.0
	 * @access private
	 *
	 * @var array
	 */
	private $args;

	/**
	 * All of the components that go into the grade.
	 *
	 * @since 1.0.0
	 * @access private
	 *
	 * @var array
	 */
	private $components = array();

	/**
	 * Quizzes for this user and course.
	 *
	 * @since 1.0.0
	 * @access private
	 *
	 * @var array
	 */
	private $quizzes = array();

	/**
	 * Assignments for this user and course.
	 *
	 * @since 1.0.0
	 * @access private
	 *
	 * @var array
	 */
	private $assignments = array();

	/**
	 * Manually entered grades for htis user and course.
	 *
	 * @since 1.0.0
	 * @access private
	 *
	 * @var array
	 */
	private $manual_grades = array();

	/**
	 * The overal user grade.
	 *
	 * @since 1.0.0
	 * @access private
	 *
	 * @var int|bool
	 */
	private $user_grade = false;

	/**
	 * Quizzes this user has taken.
	 *
	 * @since 1.0.0
	 * @access private
	 *
	 * @var array|null
	 */
	private $user_quizzes;

	/**
	 * LD_GB_UserGrade constructor.
	 *
	 * @since 1.0.0
	 *
	 * @param int|WP_User|bool $user The user or user ID to get the grade for.
	 * @param int||bool $gradebook The Gradebook ID.
	 * @param array $args Arguments for the object.
	 */
	public function __construct( $user = false, $gradebook = false, $args = array() ) {

		$this->gradebook = get_post( $gradebook );

		if ( ! $this->gradebook ) {
			return;
		}

		$this->args = $this->setup_args( $args );
		$this->user = $this->setup_user( $user );

		if ( $this->user === false || $this->user->ID === 0 ) {

			return;
		}

		$this->components = $this->build_components();
		$this->user_grade = $this->build_final_grade();
	}

	/**
	 * Sets up the default args.
	 *
	 * @since 1.0.0
	 *
	 * @param array $args The supplied args.
	 *
	 * @return array
	 */
	private function setup_args( $args ) {

		return wp_parse_args( $args, array(
			'weight_type'      => ld_gb_get_field( 'gradebook_weighting_enable', $this->gradebook->ID ) ? 'weighted' : 'equal',
			'grade_precision'  => ld_gb_get_option_field( 'grade_precision', 0 ),
			'grade_round_mode' => ld_gb_get_option_field( 'grade_round_mode', 'ceil' ),
		) );
	}

	/**
	 * Sets up the user for the grade.
	 *
	 * @since 1.0.0
	 *
	 * @param int|WP_User|false $_user The user or user ID to get the grade for.
	 *
	 * @return WP_User|false
	 */
	private function setup_user( $_user ) {

		if ( $_user === false ) {

			$user = wp_get_current_user();

		} elseif ( $_user instanceof WP_User ) {

			$user = $_user;

		} elseif ( ! ( $user = get_user_by( 'id', $_user ) ) ) {

			return false;
		}

		/**
		 * Filters the user to get the grade for.
		 *
		 * @since 1.0.0
		 */
		$user = apply_filters( 'ld_gb_user_grade_user', $user, $this->args );

		return $user;
	}

	/**
	 * Gets the various components and retrieves the grades.
	 *
	 * @since 1.0.0
	 *
	 * @return array
	 */
	private function build_components() {

		// Get components
		$components = ld_gb_get_field( 'components', $this->gradebook->ID );

		if ( ! $components ) {
			return array();
		}

		$course                  = ld_gb_get_field( 'course', $this->gradebook->ID );
		$course                  = ( $course == 'all' ) ? false : $course;
		$course_progress         = false;
		$completion_grading_mode = ld_gb_get_field( 'completion_grading_mode', $this->gradebook->ID );

		if ( $course ) {

			$all_progress = get_user_meta( $this->user->ID, '_sfwd-course_progress', true );

			if ( isset( $all_progress[ (int) $course ] ) ) {

				$course_progress = $all_progress[ (int) $course ];
			}
		}

		$component_ordering = wp_parse_args( array_filter( array(
			'orderby' => ld_gb_get_field( 'component_orderby', $this->gradebook->ID ),
			'order'   => ld_gb_get_field( 'component_order', $this->gradebook->ID ),
		) ), array(
			'orderby' => 'title',
			'order'   => 'asc',
		) );

		/**
		 * How the component resources are ordered for the user grade.
		 *
		 * @since 1.3.0
		 *
		 * @param array $component_ordering
		 * @param WP_Post $gradebook
		 */
		$component_ordering = apply_filters( 'ld_gb_user_grade_ordering', $component_ordering, $this->gradebook );

		// If set to grade, remove, because this is custom ordering.
		$orderby_grade = false;
		if ( $component_ordering['orderby'] === 'grade' ) {

			$orderby_grade                 = true;
			$component_ordering['orderby'] = 'title';
		}

		// Component overrides
		$component_overrides = array();
		if ( ! get_option( 'ld_gb_disable_component_override', false ) ) {
			$component_overrides = get_user_meta( $this->user->ID, "ld_gb_component_grades_{$this->gradebook->ID}", true );
		}

		// Grab various grades
		foreach ( $components as &$component ) {

			$grades = array();

			// Quizzes
			$quizzes   = array();
			$quiz_args = array(
				'post_type'   => 'sfwd-quiz',
				'numberposts' => - 1,
				'orderby'     => $component_ordering['orderby'],
				'order'       => $component_ordering['order'],
			);

			if ( $component['quizzes_all'] === '1' ) {

				if ( $course ) {

					$quiz_ids = learndash_course_get_steps_by_type( $course, 'sfwd-quiz' );

					foreach ( $quiz_ids as $quiz_id ) {

						$quizzes[] = get_post( $quiz_id );
					}

				} else {

					$quizzes = get_posts( $quiz_args );
				}

			} elseif ( isset( $component['quizzes'] ) && ! empty( $component['quizzes'] ) ) {

				$quiz_args['post__in'] = $component['quizzes'];

				$quizzes = get_posts( $quiz_args );
			}

			$quiz_grades = array();

			if ( $quizzes ) {

				foreach ( $quizzes as $quiz ) {

					if ( $quiz_grade = $this->get_quiz_grade( $quiz, $completion_grading_mode ) ) {

						$quiz_grades[] = $quiz_grade;
					}
				}
			}

			// Sort by grade if set
			if ( $orderby_grade ) {

				usort(
					$quiz_grades,
					array(
						__CLASS__,
						$component_ordering['order'] === 'asc' ? 'sort_by_score_asc' : 'sort_by_score_desc'
					)
				);
			}

			$grades = array_merge( $grades, $quiz_grades );

			// Assignments
			$assignments = array();

			$assignment_args = array(
				'post_type'   => 'sfwd-assignment',
				'numberposts' => - 1,
				'orderby'     => $component_ordering['orderby'],
				'order'       => $component_ordering['order'],
				'meta_query'  => array(
				),
			);

			// Holds Lessons/Topics that expect an Assignment Upload based on the Component Settings
			$valid_lessons_topics = array();

			if ( $component['assignments_all'] === '1' ) {

				if ( $course ) {

					$assignment_args['meta_key']   = 'course_id';
					$assignment_args['meta_value'] = $course;
				}

				$assignments = get_posts( $assignment_args );

			} elseif ( ( isset( $component['assignment_lessons'] ) && ! empty( $component['assignment_lessons'] ) ) ||
			           ( isset( $component['assignment_topics'] ) && ! empty( $component['assignment_topics'] ) ) ) {

				$component['assignment_lessons'] = ( isset( $component['assignment_lessons'] ) && $component['assignment_lessons'] ) ? $component['assignment_lessons'] : array();
				$component['assignment_topics'] = ( isset( $component['assignment_topics'] ) && $component['assignment_topics'] ) ? $component['assignment_topics'] : array();

				// Find all Lessons and Topics which have Assignment Uploads enabled
				$lessons_topics = get_posts( array(
					'post_type'   => array( 'sfwd-lessons', 'sfwd-topic' ),
					'numberposts' => - 1,
					'post__in'    => array_merge( $component['assignment_lessons'], $component['assignment_topics'] ),
					'orderby'     => $component_ordering['orderby'],
					'order'       => $component_ordering['order'],
				) );

				foreach ( $lessons_topics as $lesson_topic ) {

					// Sanity check, in case a bad value was saved
					if ( ! lesson_hasassignments( $lesson_topic ) ) continue;

					$valid_lessons_topics[] = $lesson_topic->ID;

				}

				// NOTE: LearnDash uses "lesson_id" for both Lessons AND Topics (I know...)
				$assignment_query = array(
					'relation' => 'OR',
					array(
						'key'     => 'lesson_id',
						'value'   => $valid_lessons_topics,
						'compare' => 'IN',
					),
				);

				$assignment_args['meta_query'][] = $assignment_query;

				$assignments = get_posts( $assignment_args );

			} elseif ( isset( $component['assignments'] ) && ! empty( $component['assignments'] ) ) {

				$assignment_args['post__in'] = $component['assignments'];

				$assignments = get_posts( $assignment_args );
			}

			$assignment_grades = array();

			if ( $assignments ) {

				$lesson_topic_assignments_uploaded = array();

				foreach ( $assignments as $assignment ) {

					if ( $assignment_grade = $this->get_assignment_grade( $assignment ) ) {

						$assignment_grades[] = $assignment_grade;

						$lesson_topic_assignments_uploaded[] = get_post_meta( $assignment->ID, 'lesson_id', true );

					}

				}

				// If we're set to Fail until completion, give Students a failing Grade for unsubmitted Assignments
				if ( $completion_grading_mode !== 'completion' ) {

					$lesson_topic_assignments_not_uploaded = array_diff( $valid_lessons_topics, $lesson_topic_assignments_uploaded );

					foreach ( $lesson_topic_assignments_not_uploaded as $lesson_topic_id ) {

						$status = get_user_meta( $this->user->ID, "ld_gb_grade_status_{$this->gradebook->ID}_{$lesson_topic_id}", true );

						$assignment_grades[] = self::modify_grade_by_status( array(
							'type'    => 'assignment',
							'name'    => get_the_title( $lesson_topic_id ),
							'score'   => 0,
							'status'  => $status,
							'post_id' => $lesson_topic_id,
						) );

					}

				}
				
			}

			// Sort by grade if set
			if ( $orderby_grade ) {

				usort(
					$assignment_grades,
					array(
						__CLASS__,
						$component_ordering['order'] === 'asc' ? 'sort_by_score_asc' : 'sort_by_score_desc'
					)
				);
			}

			$grades = array_merge( $grades, $assignment_grades );

			if ( $course ) {

				// Lessons
				$lessons = array();

				$lesson_args = array(
					'post_type'   => 'sfwd-lessons',
					'numberposts' => - 1,
					'orderby'     => $component_ordering['orderby'],
					'order'       => $component_ordering['order'],
				);

				if ( $component['lessons_all'] === '1' ) {

					if ( $course ) {

						$lesson_args['meta_key']   = 'course_id';
						$lesson_args['meta_value'] = $course;
					}

					$lessons = get_posts( $lesson_args );

				} elseif ( isset( $component['lessons'] ) && ! empty( $component['lessons'] ) ) {

					$lesson_args['post__in'] = $component['lessons'];
					$lessons                 = get_posts( $lesson_args );
				}

				$lesson_grades = array();

				if ( $lessons ) {

					foreach ( $lessons as $lesson ) {

						if ( $lesson_grade = $this->get_lesson_grade( $lesson, $course_progress, $completion_grading_mode ) ) {

							$lesson_grades[] = $lesson_grade;
						}
					}
				}

				// Sort by grade if set
				if ( $orderby_grade ) {

					usort(
						$lesson_grades,
						array(
							__CLASS__,
							$component_ordering['order'] === 'asc' ? 'sort_by_score_asc' : 'sort_by_score_desc'
						)
					);
				}

				$grades = array_merge( $grades, $lesson_grades );

				// Topics
				$topics = array();

				$topic_args = array(
					'post_type'   => 'sfwd-topic',
					'numberposts' => - 1,
					'orderby'     => $component_ordering['orderby'],
					'order'       => $component_ordering['order'],
				);

				if ( $component['topics_all'] === '1' ) {

					if ( $course ) {

						$topic_args['meta_key']   = 'course_id';
						$topic_args['meta_value'] = $course;
					}

					$topics = get_posts( $topic_args );

				} elseif ( isset( $component['topics'] ) && ! empty( $component['topics'] ) ) {

					$topic_args['post__in'] = $component['topics'];
					$topics                 = get_posts( $topic_args );
				}

				$topic_grades = array();

				if ( $topics ) {

					foreach ( $topics as $topic ) {

						if ( $topic_grade = $this->get_topic_grade( $topic, $course_progress, $completion_grading_mode ) ) {

							$grades[] = $topic_grade;
						}
					}
				}

				// Sort by grade if set
				if ( $orderby_grade ) {

					usort(
						$topic_grades,
						array(
							__CLASS__,
							$component_ordering['order'] === 'asc' ? 'sort_by_score_asc' : 'sort_by_score_desc'
						)
					);
				}

				$grades = array_merge( $grades, $topic_grades );
			}

			if ( $user_manual_grades = $this->get_manual_grades( $component['id'] ) ) {

				// Sort by grade if set
				if ( $orderby_grade ) {

					usort(
						$user_manual_grades,
						array(
							__CLASS__,
							$component_ordering['order'] === 'asc' ? 'sort_by_score_asc' : 'sort_by_score_desc'
						)
					);
				}

				$grades = array_merge( $grades, $user_manual_grades );
			}

			$component['grades'] = $grades;

			if ( ! isset( $component_overrides[ $component['id'] ] ) ) {

				$component['averaged_score'] = $this::average_grades( $grades );
				$component['overridden']     = false;

			} else {

				$component['averaged_score'] = (int) $component_overrides[ $component['id'] ];
				$component['overridden']     = true;
			}
		}

		/**
		 * Filter the user grade components.
		 *
		 * @since 1.0.0
		 *
		 * @hooked LD_GB_QuickStart->mock_user_grades() 10
		 */
		$components = apply_filters( 'ld_gb_user_grade_components', $components, $this->gradebook->ID, $this->user->ID );

		return $components;
	}

	/**
	 * Gets the quiz grade based on the quiz post.
	 *
	 * @since 1.0.0
	 *
	 * @param WP_Post $quiz The quiz post.
	 * @param string $mode Grading mode for non-completed Quizzes.
	 *
	 * @return array The quiz grade.
	 */
	private function get_quiz_grade( $quiz_post, $mode = 'completion' ) {

		static $quiz_score_type;

		if ( $this->user_quizzes === null ) {

			$this->user_quizzes = get_user_meta( $this->user->ID, '_sfwd-quizzes', true );
		}

		if ( ! $this->user_quizzes ) {
			return array();
		}

		$quizzes = array();
		foreach ( $this->user_quizzes as $quiz ) {

			if ( $quiz['quiz'] == $quiz_post->ID ) {

				$quizzes[] = $quiz;
			}
		}

		// This user has not taken this quiz
		if ( ! $quizzes ) {

			if ( $mode == 'completion' ) {
				return false;
			}
			else { // Fail until completion, report a 0
				
				$status = get_user_meta( $this->user->ID, "ld_gb_grade_status_{$this->gradebook->ID}_{$quiz_post->ID}", true );

				$grade = self::modify_grade_by_status( array(
					'type'    => 'quiz',
					'name'    => get_the_title( $quiz_post->ID ),
					'score'   => 0,
					'status'  => $status,
					'post_id' => $quiz_post->ID,
				) );
				
				return $grade;
				
			}
			
		}

		if ( $quiz_score_type === null ) {

			$quiz_score_type = ld_gb_get_option_field( 'quiz_score_type', 'best' );
		}

		$time  = 0;
		$score = 0;
		$complete_time = false;
		foreach ( $quizzes as $quiz ) {

			if ( ( $quiz_score_type == 'recent' && isset( $quiz['completed'] ) && $quiz['completed'] > $time ) ||
			     ( $quiz_score_type == 'best' && (float) $quiz['percentage'] > $score )
			) {

				$score = (float) $quiz['percentage'];
				$complete_time = $quiz['completed'];

			}
		}

		// Rounding
		$score = ld_gb_round_grade(
			$score,
			$this->args['grade_precision'],
			$this->args['grade_round_mode']
		);

		$status = get_user_meta( $this->user->ID, "ld_gb_grade_status_{$this->gradebook->ID}_{$quiz_post->ID}", true );

		$grade = self::modify_grade_by_status( array(
			'type'    => 'quiz',
			'name'    => get_the_title( $quiz_post->ID ),
			'score'   => $score,
			'status'  => $status,
			'post_id' => $quiz_post->ID,
			'completed' => $complete_time,
		) );

		return $grade;
	}

	/**
	 * Gets the assignment grade based on the assignment post.
	 *
	 * @since 1.0.0
	 *
	 * @param WP_Post $assignment_post Assignment post object.
	 *
	 * return array The Grade
	 */
	private function get_assignment_grade( $assignment_post ) {

		// Make sure it belongs to the current user
		$assignment_user = get_post_meta( $assignment_post->ID, 'user_id', true );

		if ( ! $assignment_user || (int) $assignment_user !== $this->user->ID ) {
			return false;
		}

		$lesson_ID = get_post_meta( $assignment_post->ID, 'lesson_id', true );

		$points_enabled = learndash_get_setting( $lesson_ID, 'lesson_assignment_points_enabled' );

		if ( $points_enabled === 'on' ) {

			$points_amount = (int) learndash_get_setting( $lesson_ID, 'lesson_assignment_points_amount' );
			$points_earned = (int) get_post_meta( $assignment_post->ID, 'points', true );

			if ( $points_amount > 0 && $points_amount >= $points_earned ) {

				$assignment_grade = $points_earned / $points_amount * 100;
			}
		}

		if ( (int) get_post_meta( $assignment_post->ID, 'approval_status', true ) === 1 ) {

			// Default to 100 if not set
			$assignment_grade = isset( $assignment_grade ) ? $assignment_grade : 100;

			$status = get_user_meta( $this->user->ID, "ld_gb_grade_status_{$this->gradebook->ID}_{$assignment_post->ID}", true );

			$score = (float) $assignment_grade;

		}
		else {
			$status = 'pending';
			$score = false;
		}

		// Rounding
		$score = ld_gb_round_grade(
			$score,
			$this->args['grade_precision'],
			$this->args['grade_round_mode']
		);

		$name = get_the_title( $assignment_post->ID );

		if ( get_option( 'ld_gb_assignment_grade_lesson_name', false ) ) {
			$name = get_the_title( $lesson_ID );
		}

		$grade = self::modify_grade_by_status( array(
			'type'    => 'assignment',
			'name'    => $name,
			'score'   => $score,
			'status'  => $status,
			'post_id' => $assignment_post->ID,
		) );

		return $grade;
	}

	/**
	 * Gets the lesson grade based on the assignment post.
	 *
	 * @since 1.2.0
	 *
	 * @param WP_Post $lesson_post Lesson post object.
	 * @param array $course_progress User course progress pertaining to Lesson.
	 * @param string $mode Grading mode for non-completed Lessons.
	 *
	 * return array The Grade
	 */
	private function get_lesson_grade( $lesson_post, $course_progress, $mode = 'completion' ) {

		if ( isset( $course_progress['lessons'][ $lesson_post->ID ] ) &&
		     $course_progress['lessons'][ $lesson_post->ID ] === 1
		) {

			$lesson_grade = 100;

		} else {

			$lesson_grade = $mode === 'completion' ? false : 0;
		}

		$status = get_user_meta( $this->user->ID, "ld_gb_grade_status_{$this->gradebook->ID}_{$lesson_post->ID}", true );

		$score = $lesson_grade;

		$grade = self::modify_grade_by_status( array(
			'type'    => 'lesson',
			'name'    => get_the_title( $lesson_post->ID ),
			'score'   => $score,
			'status'  => $status,
			'post_id' => $lesson_post->ID,
		) );

		return $grade;
	}

	/**
	 * Gets the topic grade based on the assignment post.
	 *
	 * @since 1.2.0
	 *
	 * @param WP_Post $topic_post Topic post object.
	 * @param array $course_progress User course progress pertaining to Topic.
	 * @param string $mode Grading mode for non-completed Topics.
	 *
	 * return array The Grade
	 */
	private function get_topic_grade( $topic_post, $course_progress, $mode = 'completion' ) {

		$topic_grade = $mode === 'completion' ? false : 0;

		if ( isset( $course_progress['topics'] ) ) {

			foreach ( $course_progress['topics'] as $lesson_ID => $topics ) {

				if ( isset( $topics[ $topic_post->ID ] ) && $topics[ $topic_post->ID ] === 1 ) {

					$topic_grade = 100;
				}
			}
		}

		$status = get_user_meta( $this->user->ID, "ld_gb_grade_status_{$this->gradebook->ID}_{$topic_post->ID}", true );

		$score = $topic_grade;

		$grade = self::modify_grade_by_status( array(
			'type'    => 'topic',
			'name'    => get_the_title( $topic_post->ID ),
			'score'   => $score,
			'status'  => $status,
			'post_id' => $topic_post->ID,
		) );

		return $grade;
	}

	/**
	 * Gets all manual grades.
	 *
	 * @since 1.0.0
	 *
	 * @param int $component Component to get manual scores from.
	 *
	 * @return array The grades.
	 */
	private function get_manual_grades( $component ) {

		if ( get_option( 'ld_gb_disable_manual_grades', false ) ) {
			return array();
		}

		if ( ! ( $manual_grades = get_user_meta( $this->user->ID, "ld_gb_manual_grades_{$this->gradebook->ID}_{$component}", true ) ) ) {

			return array();
		}

		$grades = array();
		foreach ( $manual_grades as $grade ) {

			$grade['type'] = 'manual';

			// Rounding
			$grade['score'] = ld_gb_round_grade(
				$grade['score'],
				$this->args['grade_precision'],
				$this->args['grade_round_mode']
			);

			$grades[] = self::modify_grade_by_status( $grade );
		}

		return $grades;
	}

	/**
	 * Used in usort() for sorting component resources by scores ascending.
	 *
	 * @since 1.3.0
	 *
	 * @param int $a
	 * @param int $b
	 *
	 * @return int
	 */
	public static function sort_by_score_asc( $a, $b ) {

		return self::sort_by_score( $a, $b, 'asc' );
	}

	/**
	 * Used in usort() for sorting component resources by scores descending.
	 *
	 * @since 1.3.0
	 *
	 * @param int $a
	 * @param int $b
	 *
	 * @return int
	 */
	public static function sort_by_score_desc( $a, $b ) {

		return self::sort_by_score( $a, $b, 'desc' );
	}

	/**
	 * Used in usort() for sorting component resources by scores.
	 *
	 * @since 1.3.0
	 *
	 * @param int $a
	 * @param int $b
	 *
	 * @return int
	 */
	public static function sort_by_score( $a, $b, $order ) {

		$a = $a['score'];
		$b = $b['score'];

		if ( $a === $b ) {
			return 0;
		}

		if ( $order === 'asc' ) {

			return $a < $b ? - 1 : 1;

		} else {

			return $a > $b ? - 1 : 1;
		}
	}

	/**
	 * Modifies a grade based on its status.
	 *
	 * @since 1.0.1
	 *
	 * @param array $grade
	 */
	public static function modify_grade_by_status( $grade ) {

		static $grade_statuses;

		if ( $grade_statuses === null ) {

			$grade_statuses = ld_gb_get_grade_statuses();
		}

		$original_score = $grade['score'];

		if ( isset( $grade['status'] ) && 
			$grade['status'] && 
			isset( $grade_statuses[ $grade['status'] ] ) ) {

			$score   = $grade_statuses[ $grade['status'] ]['score'];
			$display = $grade_statuses[ $grade['status'] ]['label'];
		}

		$score = isset( $score ) ? $score : $original_score;

		if ( ! isset( $display ) ) {

			$display = $score !== false ? "{$score}%" : '';
		}

		$grade['original_score'] = $original_score;
		$grade['score']          = $score;
		$grade['score_display']  = $display;

		return $grade;
	}

	/**
	 * Averages an array of grades.
	 *
	 * @since 1.0.0
	 *
	 * @param array $grades Grades to be averaged.
	 *
	 * @return int The averaged grade.
	 */
	public static function average_grades( $grades ) {

		// Remove any set to false
		foreach ( $grades as $i => $grade ) {

			if ( $grade['score'] === false ) {

				unset( $grades[ $i ] );
			}
		}

		if ( ! $grades ) {
			return false;
		}

		$scores = wp_list_pluck( $grades, 'score' );

		return self::average_scores( $scores );
	}

	/**
	 * Averages scores.
	 *
	 * @since 1.0.0
	 *
	 * @param array $scores
	 *
	 * @return float
	 */
	public static function average_scores( $scores ) {

		return round( (int) array_sum( $scores ) / count( $scores ) );
	}

	/**
	 * Calculates and returns the final course grade.
	 *
	 * @return int The final grade.
	 */
	private function build_final_grade() {

		switch ( $this->args['weight_type'] ) {
			case 'weighted':

				$final_grade = $this->calculate_weighted_grade();
				break;

			case 'equal':
			default:

				$final_grade = $this->calculate_equal_grade();
				break;
		}

		/**
		 * Filters the final grade.
		 *
		 * @since 1.0.0
		 */
		$final_grade = apply_filters( 'ld_gb_user_grade_final_grade', $final_grade, $this->args['weight_type'] );

		return $final_grade;
	}

	/**
	 * Calculates the weighted final grade.
	 *
	 * @since 1.0.0
	 * @access private
	 *
	 * @return bool|int
	 */
	private function calculate_weighted_grade() {

		// Check that weights equal 100
		if ( array_sum( wp_list_pluck( $this->components, 'weight' ) ) !== 100 ) {
			return false;
		}

		// If no components graded, don't give grade
		if ( count( array_filter( wp_list_pluck( $this->components, 'averaged_score' ) ) ) === 0 ) {

			return false;
		}

		$final_grade = 0;
		foreach ( $this->components as $component ) {

			$score = $component['averaged_score'] !== false ? $component['averaged_score'] : 100;

			$final_grade += $score * ( (int) $component['weight'] / 100 );
		}

		return round( $final_grade );
	}

	/**
	 * Calculates the equally weighted final grade.
	 *
	 * @since 1.0.0
	 * @access private
	 *
	 * @return bool|int
	 */
	private function calculate_equal_grade() {

		$final_grade = 0;
		$components  = $this->components;
		foreach ( $components as $i => $component ) {

			// If no score, this component is incomplete, do not factor into final grade
			if ( $component['averaged_score'] === false ) {

				unset( $components[ $i ] );
				continue;
			}

			$final_grade += $component['averaged_score'];
		}

		if ( count( $components ) <= 0 ) {

			$final_grade = false;

		} else {

			$final_grade = $final_grade / count( $components );
		}

		return $final_grade !== false ? round( $final_grade ) : false;
	}

	/**
	 * Returns the grade as a letter grade.
	 *
	 * @since 1.0.0
	 *
	 * @param int $numeric_grade Value of grade.
	 *
	 * @return string
	 */
	public static function get_letter_grade( $numeric_grade ) {

		/**
		 * Filters the letter grade map.
		 *
		 * @since 1.0.0
		 */
		$letter_scale = apply_filters( 'ld_gb_letter_grade_scale',
			get_option( 'ld_gb_letter_grade_scale', ld_gb_get_default_letter_grade_scale() ) );

		$letter_grade = '';

		foreach ( $letter_scale as $cutoff => $grade ) {

			if ( $numeric_grade >= $cutoff ) {

				$letter_grade = $grade;
				break;
			}
		}

		return $letter_grade;
	}

	/**
	 * Returns the grade color for styling.
	 *
	 * @since 1.0.0
	 *
	 * @param int $numeric_grade Value of grade.
	 *
	 * @return string
	 */
	public static function get_grade_color( $numeric_grade ) {

		if ( $numeric_grade === false ) {

			return '';
		}

		/**
		 * Filters the grade color map.
		 *
		 * This is used as a class for styling the grade output.
		 *
		 * @since 1.0.0
		 */
		$color_scale = apply_filters( 'ld_gb_grade_color_scale',
			get_option( 'ld_gb_grade_color_scale', ld_gb_get_default_grade_color_scale() ) );

		$final_color = '';
		foreach ( $color_scale as $cutoff => $color ) {
			if ( $numeric_grade >= $cutoff ) {
				$final_color = $color;
				break;
			}
		}

		return $final_color;
	}

	/**
	 * Gets the Gradebook post.
	 *
	 * @since 1.2.0
	 *
	 * @return false|WP_Post
	 */
	public function get_gradebook() {

		return $this->gradebook;
	}

	/**
	 * Gets the user.
	 *
	 * @since 1.1.0
	 *
	 * @return false|WP_User
	 */
	public function get_user() {

		return $this->user;
	}

	/**
	 * Gets the weight type.
	 *
	 * @since 1.1.0
	 *
	 * @return string
	 */
	public function get_weight_type() {

		return $this->args['weight_type'];
	}

	/**
	 * Gets components.
	 *
	 * @since 1.0.0
	 *
	 * @param int $ID The Component ID.
	 *
	 * @return array|false Component if found, false if not found.
	 */
	public function get_component( $ID ) {

		foreach ( $this->components as $component ) {

			if ( (int) $component['id'] === (int) $ID ) {

				return $component;
			}
		}

		return false;
	}

	/**
	 * Gets components.
	 *
	 * @since 1.0.0
	 *
	 * @return array
	 */
	public function get_components() {

		return $this->components;
	}

	/**
	 * Gets args.
	 *
	 * @since 1.1.0
	 *
	 * @return array
	 */
	public function get_args() {

		return $this->args;
	}

	/**
	 * Gets the course grade value.
	 *
	 * @since 1.0.0
	 *
	 * @return bool|int
	 */
	public function get_user_grade() {

		return $this->user_grade;
	}

	/**
	 * Displays the final user grade.
	 *
	 * @since 1.0.0
	 */
	public function display_user_grade() {

		self::display_grade( $this->user_grade );
	}

	/**
	 * Displays the final user grade color.
	 *
	 * @since 1.0.0
	 */
	public function display_user_grade_color() {

		self::display_grade_color( $this->user_grade );
	}

	/**
	 * Gets the display grade.
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 *
	 * @param int $score The final score
	 * @param string $format How to display grade
	 */
	public static function get_display_grade( $score, $format = 'letter' ) {

		$format = ld_gb_get_option_field( 'grade_display_mode', $format );

		/**
		 * How to display the grade.
		 *
		 * @since 1.0.0
		 */
		$format = apply_filters( 'ld_gb_display_course_grade_format', $format );

		switch ( $format ) {
			case 'letter':

				if ( $score === false ) {

					$grade_display = '';

				} else {

					$grade_display = self::get_letter_grade( $score );
				}
				break;

			case 'percentage':

				if ( $score === false ) {
					$grade_display = '';

				} else {

					$grade_display = "$score%";
				}
				break;

			default:

				/**
				 * Allows a custom callback to get the display grade.
				 *
				 * @since 1.0.0
				 */
				$grade_display = apply_filters( 'ld_gb_display_course_grade', $score );
				break;
		}

		return $grade_display;
	}

	/**
	 * Displays the grade.
	 *
	 * @since 1.0.0
	 *
	 * @param string $score The final score
	 * @param string|false $format How to display grade
	 */
	public static function display_grade( $score, $format = false ) {

		if ( ! $format ) {

			$format = get_option( 'ld_gb_grade_format', 'letter' );
		}

		echo self::get_display_grade( $score, $format );
	}

	/**
	 * Returns the grade color for styling.
	 *
	 * @since 1.0.0
	 *
	 * @param int $score
	 *
	 * @return string
	 */
	public static function get_display_grade_color( $score ) {

		return self::get_grade_color( $score );
	}

	/**
	 * Displays the grade color for styling.
	 *
	 * @since 1.0.0
	 *
	 * @param int $score
	 */
	public static function display_grade_color( $score ) {

		echo self::get_display_grade_color( $score );
	}

	/**
	 * Returns the grade html for output.
	 *
	 * @since 1.1.0
	 *
	 * @param int $score
	 *
	 * @return string
	 */
	public static function get_display_grade_html( $score ) {

		return '<span class="ld-gb-grade" style="background-color: ' . self::get_grade_color( $score ) . '";>' .
		       self::get_display_grade( $score ) . '</span>';
	}

	/**
	 * Echos the grade html for output.
	 *
	 * @since 1.1.0
	 *
	 * @param int $score
	 *
	 * @return string
	 */
	public static function display_grade_html( $score ) {

		echo self::get_display_grade_html( $score );
	}
}