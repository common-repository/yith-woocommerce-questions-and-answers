<?php // phpcs:ignore WordPress.NamingConventions

/**
 * This file belongs to the YIT Framework.
 *
 * This source file is subject to the GNU GENERAL PUBLIC LICENSE (GPL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.gnu.org/licenses/gpl-3.0.txt
 *
 * @package yith-woocommerce-question-and-answer\lib
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit( 'Direct access forbidden.' );
}

if ( ! class_exists( 'YWQA_Question' ) ) {
	/**
	 * YWQA_Question
	 *
	 * @class   class.ywqa-question.php
	 * @package    Yithemes
	 * @since      Version 1.0.0
	 * @author     Your Inspiration Themes
	 */
	class YWQA_Question extends YWQA_Discussion {

		/**
		 * Initialize a question object
		 *
		 * @param int|array $args the question id or an array for initializing the object.
		 */
		public function __construct( $args = null ) {
			parent::__construct( $args );

			$this->type = 'question';
		}

		/**
		 * Has_answers
		 *
		 * @return items
		 */
		public function has_answers() {
			global $wpdb;

			$query = $wpdb->prepare(
				"select count(ID)
				from {$wpdb->prefix}posts
				where post_status = 'publish' and post_type = %s and post_parent = %s",
				YWQA_CUSTOM_POST_TYPE_NAME,
				$this->ID
			);

			$items = $wpdb->get_row( $query, ARRAY_N ); //phpcs:ignore --Direct db call is discouraged

			return $items[0];
		}

		/**
		 * Get_answers
		 *
		 * @param  mixed $count count.
		 * @return answers
		 */
		public function get_answers( $count = - 1 ) {

			$query_limit = '';
			if ( $count > 0 ) {
				$query_limit = 'limit 0,' . $count;
			}

			global $wpdb;

			$query = $wpdb->prepare(
				"select ID, post_author, post_date, post_content, post_title, post_parent
				from {$wpdb->prefix}posts
				where post_status = 'publish' and post_type = %s and post_parent = %s order by post_date DESC " . $query_limit, //phpcs:ignore --Not works with placeholder.
				YWQA_CUSTOM_POST_TYPE_NAME,
				$this->ID
			);

			$items = $wpdb->get_results( $query, ARRAY_A ); //phpcs:ignore --Direct db call is discouraged

			$answers = array();

			foreach ( $items as $item ) {
				$params = array(
					'content'    => $item['post_content'],
					'author_id'  => $item['post_author'],
					'product_id' => get_post_meta( $item['ID'], YWQA_METAKEY_PRODUCT_ID, true ),
					'ID'         => $item['ID'],
					'parent_id'  => $item['post_parent'],
					'date'       => $item['post_date'],
				);

				$answers[] = new YWQA_Answer( $params );
			}

			return $answers;
		}
	}
}
