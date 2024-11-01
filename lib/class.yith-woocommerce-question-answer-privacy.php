<?php // phpcs:ignore WordPress.NamingConventions
/**
 * YITH_WooCommerce_Question_And_Answer_Privacy class
 *
 * @package yith-woocommerce-question-and-answer\lib
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Implements features of PREMIUM version of YWQA plugin
 *
 * @class   YITH_WooCommerce_Question_And_Answer_Privacy
 * @package Yithemes
 * @since   1.2.1
 * @author  Your Inspiration Themes
 */
if ( ! class_exists( 'YITH_WooCommerce_Question_And_Answer_Privacy' ) ) {

	/**
	 * YITH_WooCommerce_Question_And_Answer_Privacy
	 */
	class YITH_WooCommerce_Question_And_Answer_Privacy extends YITH_Privacy_Plugin_Abstract {


		/**
		 * Init - hook into events.
		 */
		public function __construct() {
			/**
			 * GDRP privacy policy content
			 */
			parent::__construct( _x( 'YITH Question and Answers for WooCommerce', 'Privacy Policy Content', 'yith-woocommerce-advanced-reviews' ) );

			/**
			 * GDRP WordPress hook to add exporters
			 */
			add_filter( 'wp_privacy_personal_data_exporters', array( $this, 'yith_wc_register_question_and_answer_customer_data_exporter' ) );

			/**
			 * GDRP WordPress hook to add erasers
			 */
			add_filter( 'wp_privacy_personal_data_erasers', array( $this, 'yith_wc_register_question_and_answer_customer_data_eraser' ) );

		}
		/**
		 * Get_privacy_message
		 * Add privacy policy content for the privacy policy page.
		 *
		 * @param  mixed $section section.
		 * @since 1.3.3
		 * @return ob_get_clean
		 */
		public function get_privacy_message( $section ) {

			$privacy_content_path = YITH_YWQA_VIEWS_PATH . '/privacy/html-policy-content-' . $section . '.php';

			if ( file_exists( $privacy_content_path ) ) {

				ob_start();

				include $privacy_content_path;

				return ob_get_clean();

			}

			return '';

		}

		/**
		 * Registers the personal data exporter for yith question and answer.
		 *
		 * @since 1.1.1
		 *
		 * @param array $exporters An array of personal data exporters.
		 * @return array $exporters An array of personal data exporters.
		 */
		public function yith_wc_register_question_and_answer_customer_data_exporter( $exporters ) {
			$exporters['yith-advanced-reviews-questions-and-answers'] = array(
				'exporter_friendly_name' => _x( 'Yith Question and Answer', 'GDPR privacy', 'yith-woocommerce-questions-and-answers' ),
				'callback'               => array( $this, 'yith_wc_question_and_answer_customer_data_exporter' ),
			);

			return $exporters;
		}

		/**
		 * Finds and exports personal data associated with an email address from the posts table.
		 *
		 * @since 1.1.1
		 *
		 * @param string $email_address The comment author email address.
		 * @param int    $page Comment page.
		 * @return array $return An array of personal data.
		 */
		public function yith_wc_question_and_answer_customer_data_exporter( $email_address, $page = 1 ) {
			$user = get_user_by( 'email', $email_address ); // Check if user has an ID in the DB to load stored personal data.

			$customer = new WC_Customer( $user->ID );

			if ( ! $customer ) {
				return array();
			}

			$posts_per_page = 500;
			$page           = (int) $page;

			$args = array(
				'post_type'      => YWQA_CUSTOM_POST_TYPE_NAME,
				'paged'          => $page,
				'posts_per_page' => $posts_per_page,
				'author'         => $user->ID,
			);

			$question_and_answers = get_posts( $args );

			$data_to_export = array();

			foreach ( $question_and_answers as $question_and_answer ) {

				$type = get_post_meta( $question_and_answer->ID, YWQA_METAKEY_DISCUSSION_TYPE, true );

				$question_or_answer = ( 'question' === $type ? new YWQA_Question( $question_and_answer->ID ) : new YWQA_Answer( $question_and_answer->ID ) );

				$comment_data_to_export = array();

				$comment_data_to_export[] = array(

					'name'  => 'Type',
					'value' => $type,
				);

				$comment_data_to_export[] = array(

					'name'  => 'Author',
					'value' => $question_or_answer->get_author_name(),
				);

				$comment_data_to_export[] = array(
					'name'  => 'Date',
					'value' => $question_and_answer->post_date,
				);

				$comment_data_to_export[] = array(
					'name'  => 'Title',
					'value' => $question_and_answer->post_title,
				);

				$comment_data_to_export[] = array(
					'name'  => 'Content',
					'value' => $question_and_answer->post_content,
				);

				$comment_data_to_export[] = array(
					'name'  => 'Author URL',
					'value' => $question_and_answer->guid,
				);

				$data_to_export[] = array(
					'group_id'    => 'yith_wc_question_and_answer',
					'group_label' => _x( 'Question and answer', 'GDPR privacy', 'yith-woocommerce-questions-and-answers' ),
					'item_id'     => 'yith_quiestion_and_answer-' . $question_and_answer->ID,
					'data'        => $comment_data_to_export,
				);

			}

			$done = count( $question_and_answers ) < $posts_per_page;

			return array(
				'data' => $data_to_export,
				'done' => $done,
			);

		}

		/**
		 * Registers the personal data eraser for yith question and answer.
		 *
		 * @since 1.1.1
		 *
		 * @param  array $erasers An array of personal data erasers.
		 * @return array $erasers An array of personal data erasers.
		 */
		public function yith_wc_register_question_and_answer_customer_data_eraser( $erasers ) {
			$erasers['yith-advanced-reviews-questions-and-answers'] = array(
				'eraser_friendly_name' => _x( 'Yith Advanced Reviews', 'GDPR privacy', 'yith-woocommerce-questions-and-answers' ),
				'callback'             => array( $this, 'yith_wc_question_and_answer_customer_data_eraser' ),
			);

			return $erasers;
		}

		/**
		 * Erases personal data associated with an email address from the posts table.
		 *
		 * @since 1.1.1
		 *
		 * @param  string $email_address The comment author email address.
		 * @param  int    $page Comment page.
		 * @return array
		 */
		public function yith_wc_question_and_answer_customer_data_eraser( $email_address, $page = 1 ) {
			$user = get_user_by( 'email', $email_address ); // Check if user has an ID in the DB to load stored personal data.

			$customer = new WC_Customer( $user->ID );

			if ( ! $customer ) {
				return array();
			}

			$posts_per_page = 500;
			$page           = (int) $page;
			$items_removed  = false;
			$items_retained = false;

			$args = array(
				'post_type'      => YWQA_CUSTOM_POST_TYPE_NAME,
				'paged'          => $page,
				'posts_per_page' => $posts_per_page,
				'author'         => $user->ID,
			);

			$question_and_answers = get_posts( $args );

			$anon_author = _x( 'Anonymous', 'GDPR privacy', 'yith-woocommerce-questions-and-answers' );
			$messages    = array();

			global $wpdb;

			foreach ( $question_and_answers as $question_and_answer ) {

				/**
				 * Filters whether to anonymize the question or answer.
				 *
				 * @since 1.1.1
				 *
				 * @param bool|string                    Whether to apply the quesntion and answer anonymization (bool).
				 *                                       Custom prevention message (string). Default true.
				 * @param YITH_WooCommerce_Advanced_Reviews_Premium $question_and_answer             YITH_WooCommerce_Advanced_Reviews_Premium object.
				 */
				$anon_message = apply_filters( 'yith_wc_anonymize_quenstion_and_answer', true, $question_and_answer );

				if ( true !== $anon_message ) {
					if ( $anon_message && is_string( $anon_message ) ) {
						$messages[] = esc_html( $anon_message );
					} else {
						/* translators: %d: Comment ID */
						$messages[] = sprintf( esc_html__( 'Review %d contains personal data but could not be anonymized.' ), $question_and_answer->ID );
					}

					$items_retained = true;

					continue;
				}

				$guid = wp_privacy_anonymize_data( 'url', $question_and_answer->guid );

				// Update post.
                // @codingStandardsIgnoreStart
				$result = $wpdb->query( 
					$wpdb->prepare(
						"
                    UPDATE $wpdb->posts
                    SET guid = %s, post_author = '0'
                    WHERE ID = %d
                    ",
						$guid,
						$question_and_answer->ID
					)
				);
                // @codingStandardsIgnoreEnd
				// DirectDatabaseQuery

				if ( $result ) {

					$items_removed = true;

				} else {

					$items_retained = true;
				}
			}

			$done = count( $question_and_answers ) < $posts_per_page;

			return array(
				'items_removed'  => $items_removed,
				'items_retained' => $items_retained,
				'messages'       => $messages,
				'done'           => $done,
			);

		}

	}

}

new YITH_WooCommerce_Question_And_Answer_Privacy();
