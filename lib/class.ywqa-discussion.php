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

if ( ! class_exists( 'YWQA_Discussion' ) ) {
	/**
	 * YWQA_Discussion
	 *
	 * @class      class.ywqa-discussion.php
	 * @package    Yithemes
	 * @since      Version 1.0.0
	 * @author     Your Inspiration Themes
	 */
	class YWQA_Discussion {

		/**
		 * ID
		 *
		 * @var int Id of the question or answer
		 */
		public $ID;

		/**
		 * Content
		 *
		 * @var string
		 */
		public $content = '';

		/**
		 * Author_id
		 *
		 * @var int the user who submitted the question
		 */
		public $author_id = 0;

		/**
		 * Date
		 *
		 * @var mixed the date when the question was submitted
		 */
		public $date;

		/**
		 * Product_id
		 *
		 * @var int the product id related to current question
		 */
		public $product_id = 0;

		/**
		 * Parent_id
		 *
		 * @var int id of the parent element(it's a product for the question element, it's a question element for the answer element)
		 */
		public $parent_id = 0;

		/**
		 * Type
		 *
		 * @var string discussion type, can be  "question", "answer"
		 */
		public $type = '';

		/**
		 * Status
		 *
		 * @var string status of the post
		 */
		public $status = 'publish';

		/**
		 * __construct
		 *
		 * Create a new item
		 *
		 * @param null $args args.
		 * @return void
		 */
		public function __construct( $args = null ) {

			if ( is_numeric( $args ) ) {
				$args = $this->get_array( $args );
			}

			if ( $args ) {
				foreach ( $args as $key => $value ) {
					$this->{$key} = $value;
				}
			}
		}

		/**
		 * Retrieve the discussion author name
		 */
		public function get_author_name() {
			if ( ! isset( $this->author_id ) ) {
				return '';
			}

			$user_info = get_userdata( $this->author_id );
			if ( $user_info ) {
				return $user_info->display_name;
			}

			return __( 'Anonymous', 'yith-woocommerce-questions-and-answers' );
		}

		/**
		 * Retrieve discussion attribute from id
		 *
		 * @param mixed $post_id discussion id.
		 *
		 * @return array|null
		 */
		private function get_array( $post_id ) {
			$post = get_post( $post_id );

			if ( ! isset( $post ) ) {
				return null;
			}

			return array(
				'date'       => $post->post_date,
				'author_id'  => $post->post_author,
				'content'    => $post->post_content,
				'product_id' => get_post_meta( $post->ID, YWQA_METAKEY_PRODUCT_ID, true ),
				'ID'         => $post->ID,
				'parent_id'  => $post->post_parent,
			);
		}

		/**
		 * Save the current question
		 */
		public function save() {

			$new_id = 0;

			// Create post object.
			$args = array(
				'post_date'    => isset( $this->date ) ? $this->date : current_time( 'mysql', 0 ),
				'post_author'  => $this->author_id,
				'post_title'   => ywqa_strip_trim_text( $this->content ),
				'post_content' => $this->content,
				'post_status'  => $this->status,
				'post_type'    => YWQA_CUSTOM_POST_TYPE_NAME,
				'post_parent'  => $this->parent_id,
			);

			if ( ! isset( $this->ID ) ) {
				// Insert the post into the database.
				$this->ID = wp_insert_post( $args );
			} else {
				$args['ID'] = $this->ID;
				wp_update_post( $args );
			}

			update_post_meta( $this->ID, YWQA_METAKEY_PRODUCT_ID, $this->product_id );
			update_post_meta( $this->ID, YWQA_METAKEY_DISCUSSION_TYPE, $this->type );

			do_action( 'ywqa_after_discussion_save', $this->ID );

			return $this->ID;
		}
	}
}
