<?php
if ( ! defined( 'ABSPATH' ) ) exit;

/**
* Base Course Class
*
* Class used for instantiating course object
*
* @version 1.0
* @author codeBOX
* @project lifterLMS
*/
class LLMS_Quiz {

	/**
	* ID
	* @access public
	* @var int
	*/
	public $id;

	/**
	* Post Object
	* @access public
	* @var array
	*/
	public $post;

	/**
	* Constructor
	*
	* initializes the quiz object based on post data
	*/
	public function __construct( $quiz ) {

		if ( is_numeric( $quiz ) ) {

			$this->id   = absint( $quiz );
			$this->post = get_post( $this->id );

		}

		elseif ( $quiz instanceof LLMS_Quiz ) {

			$this->id   = absint( $quiz->id );
			$this->post = $quiz;

		}

		elseif ( isset( $quiz->ID ) ) {

			$this->id   = absint( $quiz->ID );
			$this->post = $quiz;

		}

	}

	/**
	* __isset function
	*
	* checks if metadata exists
	*
	* @param string $item
	*/
	public function __isset( $item ) {

		return metadata_exists( 'post', $this->id, '_' . $item );

	}

	/**
	* __get function
	*
	* initializes the quiz object based on post data
	*
	* @param string $item
	* @return string $value
	*/
	public function __get( $item ) {

		$value = get_post_meta( $this->id, '_' . $item, true );

		return $value;
	}

	/**
	 * Get Allowed Attempts
	 *
	 * @return string
	 */
	public function get_total_allowed_attempts() {

		return $this->llms_allowed_attempts;

	}

	/**
	 * Get Passing Percent
	 *
	 * @return string
	 */
	public function get_passing_percent() {

		return $this->llms_passing_percent;

	}

	/**
	 * returns the total points possible
	 * @return [int] [description]
	 */
	public function get_total_possible_points() {
		$questions = $this->get_questions();

		$points = 0;

		if ( ! empty( $questions ) ) {
			foreach ( $questions as $key => $value ) {
				$points += $value['points'];
			}
		}
		return ( $points != 0 ? $points : 0 );
	}

	public function get_point_weight() {
		return ( 100 / $this->get_total_possible_points() );
	}

	public function get_grade($points) {
		return $points * $this->get_point_weight();
	}

	public function get_user_grade( $user_id ) {
		$grade = 0;
		$quiz = get_user_meta( $user_id, 'llms_quiz_data', true );

		foreach ( $quiz as $key => $value ) {
			if ( $value['id'] == $this->id ) {
				$grade = $value['grade'];
			}
		}
		return $grade;
	}

	public function is_passing_score( $user_id ) {
		return $this->get_passing_percent() < $this->get_user_grade( $user_id );
	}

	public function get_total_attempts_by_user($user_id) {
		global $wpdb;

		$table_name = $wpdb->prefix . 'lifterlms_user_postmeta';

		$result = $wpdb->get_col( $wpdb->prepare(
			'SELECT meta_value FROM '.$table_name.' WHERE user_id = %s AND post_id = %d AND meta_key = "_attempts" ORDER BY updated_date DESC', $user_id, $this->id ) );

		return $result;
	}

	public function get_remaining_attempts_by_user($user_id) {
		$attempts_allowed = $this->get_total_allowed_attempts();
		$attempts = $this->get_total_attempts_by_user($user_id);

		//attempts aren't really unlimited but they get 1000 tries.
		if ( empty($attempts) ) {
			$attempts = 0;
		}
		else {
			foreach($attempts as $key => $value) {
				$attempts = $value;
			}
		}

		$total_attempts_remaining = ($attempts_allowed - $attempts);

		return $total_attempts_remaining;
	}

	public function get_questions() {
		return $this->llms_questions;

	}

	
	public function get_question_key ($question_id) {
		foreach ($this->get_questions() as $key => $value) {
			if ($key == $quiz_id) {
				$question_key = $key;
			}
		}
		return $question_key;
	}

}