<?php // phpcs:ignore WordPress.NamingConventions

/**
 * This file belongs to the YIT Plugin Framework.
 *
 * This source file is subject to the GNU GENERAL PUBLIC LICENSE (GPL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.gnu.org/licenses/gpl-3.0.txt
 *
 * @package yith-woocommerce-question-and-answer
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! class_exists( 'YITH_WooCommerce_Question_Answer' ) ) {

	/**
	 * YITH_WooCommerce_Question_Answer
	 *
	 * @class   YITH_WooCommerce_Question_Answer
	 * @package Yithemes
	 * @since   1.0.0
	 * @author  Your Inspiration Themes
	 */
	class YITH_WooCommerce_Question_Answer {

		/**
		 * How much questions to show on first time entering a product page
		 *
		 * @var int
		 */
		public $questions_to_show = 0;

		/**
		 * Questions and answers can be created only on backend
		 *
		 * @var bool
		 */
		public $faq_mode = false;

		/**
		 * Single instance of the class
		 *
		 * @var instnace $instance;
		 * @since 1.0.0
		 */
		protected static $instance;

		/**
		 * Returns single instance of the class
		 *
		 * @since 1.0.0
		 */
		public static function get_instance() {
			if ( is_null( self::$instance ) ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		/**
		 * Constructor
		 *
		 * Initialize plugin and registers actions and filters to be used
		 *
		 * @since  1.0
		 * @author Lorenzo Giuffrida
		 */
		protected function __construct() {

			$this->init_plugin_settings();

			/**
			 * Including the GDRP
			 */
			add_action( 'plugins_loaded', array( $this, 'load_privacy' ), 20 );

			/**
			 * Add a tab to WooCommerce products tabs
			 */
			add_filter( 'woocommerce_product_tabs', array( $this, 'show_question_answer_tab' ), 20 );

			/**
			 * Do some stuff on plugin init
			 */
			add_action( 'init', array( $this, 'on_plugin_init' ) );

			/** Add styles and scripts */
			add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_styles_scripts' ) );
			add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_styles_scripts' ) );

			// Add to admin_init function.
			add_filter( 'manage_edit-question_answer_columns', array( $this, 'add_custom_columns_title' ) );

			// Add to admin_init function.
			add_action(
				'manage_question_answer_posts_custom_column',
				array(
					$this,
					'add_custom_columns_content',
				),
				10,
				2
			);

			/**
			 * Add metabox to question and answer post type
			 */
			add_action( 'add_meta_boxes', array( $this, 'add_plugin_metabox' ) );

			/**
			 * Save data from question and answer post type metabox
			 */
			add_action( 'save_post', array( $this, 'save_plugin_metabox' ), 1, 2 );

			add_filter( 'wp_insert_post_data', array( $this, 'before_insert_discussion' ), 99, 2 );

			add_action( 'wp_ajax_submit_answer', array( $this, 'submit_answer_callback' ) );

			add_action( 'admin_head-post-new.php', array( $this, 'limit_products_creation' ) );
			add_action( 'admin_head-edit.php', array( $this, 'limit_products_creation' ) );
			add_action( 'admin_menu', array( $this, 'remove_add_product_link' ) );

			/*
			 * Avoid "View Post" link when a Q&A custom post type is saved
			 */
			add_filter( 'post_updated_messages', array( $this, 'avoid_view_post_link' ) );

			/* === Show Plugin Information === */
			add_filter( 'plugin_action_links_' . plugin_basename( YITH_YWQA_DIR . '/' . basename( YITH_YWQA_FILE ) ), array( $this, 'action_links' ) );
			add_filter( 'yith_show_plugin_row_meta', array( $this, 'plugin_row_meta' ), 10, 5 );
		}

		/**
		 * Avoid "View Post" link when a Q&A custom post type is saved
		 *
		 * @param  mixed $messages messages.
		 * @return messages
		 */
		public function avoid_view_post_link( $messages ) {
			$messages['post'][1] = __( 'Content updated.', 'yith-woocommerce-questions-and-answers' );
			$messages['post'][4] = __( 'Content updated.', 'yith-woocommerce-questions-and-answers' );
			$messages['post'][6] = __( 'Content published.', 'yith-woocommerce-questions-and-answers' );
			$messages['post'][7] = __( 'Content saved.', 'yith-woocommerce-questions-and-answers' );
			$messages['post'][8] = __( 'Content submitted.', 'yith-woocommerce-questions-and-answers' );

			return $messages;
		}

		/**
		 * Init plugin settings
		 */
		public function init_plugin_settings() {
			$this->questions_to_show = get_option( 'ywqa_questions_to_show', 0 );
			$this->faq_mode          = ( 'yes' === get_option( 'ywqa_faq_mode', 'no' ) ) ? true : false;
		}

		/**
		 * Including the GDRP
		 */
		public function load_privacy() {

			if ( class_exists( 'YITH_Privacy_Plugin_Abstract' ) ) {
				require_once YITH_YWQA_LIB_DIR . 'class.yith-woocommerce-question-answer-privacy.php';
			}

		}

		/**
		 * Limit_products_creation
		 *
		 * @return void
		 */
		public function limit_products_creation() {
			global $post_type;

			if ( YWQA_CUSTOM_POST_TYPE_NAME !== $post_type ) {
				return;
			}
		}

		/**
		 * Remove_add_product_link
		 *
		 * @return void
		 */
		public function remove_add_product_link() {
			global $post_type;

			if ( YWQA_CUSTOM_POST_TYPE_NAME !== $post_type ) {
				return;
			}

			echo '<style>.add-new-h2{ display: none; }</style>';
		}

		/**
		 * Submit_answer_callback
		 *
		 * @return void
		 */
		public function submit_answer_callback() {
		// @codingStandardsIgnoreStart
			$args = array(
				'content'    => $_POST['answer_content'],
				'author_id'  => get_current_user_id(),
				'product_id' => $_POST['product_id'],
				'parent_id'  => $_POST['question_id'],
			);
		// @codingStandardsIgnoreEnd
			$answer         = new YWQA_Answer( $args );
			$answer->status = 'publish';
			$result         = $answer->save();
			if ( ! $result ) {
				wp_send_json(
					array(
						'code' => - 1,
					)
				);
			}

			wp_send_json(
				array(
					'code' => 1,
				)
			);
		}

		/**
		 * Before_insert_discussion
		 *
		 * Update the title for the custom post type, trimming the discussion content
		 *
		 * @param  mixed $data data.
		 * @param  mixed $postarr postarr.
		 * @author Lorenzo Giuffrida
		 * @since  1.0.0
		 * @return data
		 */
		public function before_insert_discussion( $data, $postarr ) {
			if ( YWQA_CUSTOM_POST_TYPE_NAME === $data['post_type'] ) {

				if ( isset( $postarr['select_product'] ) ) {
					$data['post_parent'] = $postarr['select_product'];
				}

				/*
				 * Update the title for the custom post type, trimming the discussion content
				 */
				$data['post_title'] = ywqa_strip_trim_text( $data['post_content'] );
			}

			return $data;
		}

		/**
		 * Add_plugin_metabox
		 *
		 * Add the Events Meta Boxes.
		 *
		 * @return void
		 */
		public function add_plugin_metabox() {
			add_meta_box(
				'ywqa_metabox',
				'Questions & Answers',
				array(
					$this,
					'display_plugin_metabox',
				),
				'question_answer',
				'normal',
				'default'
			);
		}
		/**
		 * Display_plugin_metabox
		 *
		 * @return void
		 */
		public function display_plugin_metabox() {
			// Display different metabox content when it's a new question or answer.

			if ( isset( $_GET['post'] ) ) {//phpcs:ignore WordPress.Security.NonceVerification
				$discussion = $this->get_discussion( sanitize_text_field( wp_unslash( $_GET['post'] ) ) );//phpcs:ignore WordPress.Security.NonceVerification
				if ( $discussion instanceof YWQA_Question ) {
					?>
					<div id="question-content-div">
						<label><?php esc_html_e( 'Product: ', 'yith-woocommerce-questions-and-answers' ); ?></label>
						<a target="_blank"
						href="<?php echo esc_attr( get_permalink( $discussion->product_id ) ); ?>"><?php echo wp_kses( wc_get_product( $discussion->product_id )->get_title(), 'post' ); ?></a>
						<input type="hidden" id="product_id" name="product_id"
						value="<?php echo wp_kses( $discussion->product_id, 'post' ); ?>">
						<input type="hidden" id="discussion_type" name="discussion_type" value="edit-question">
						<textarea id="respond-to-question" name="respond-to-question" placeholder="Write an answer"
						rows="5"></textarea>
						<input id="submit-answer" class="button button-primary button-large" type="submit"
						value="Respond">
					</div>
					<?php

				} elseif ( $discussion instanceof YWQA_Answer ) {
					$question = $discussion->get_question();
					?>
					<input type="hidden" id="discussion_type" name="discussion_type" value="edit-answer">
					<fieldset>
						<label><?php esc_html_e( 'Product: ', 'yith-woocommerce-questions-and-answers' ); ?></label>
						<a target="_blank"
						href="<?php echo esc_attr( get_permalink( $discussion->product_id ) ); ?>"><?php echo wp_kses( wc_get_product( $discussion->product_id )->get_title(), 'post' ); ?></a>
					</fieldset>
					<fieldset>
						<label><?php esc_html_e( 'Question: ', 'yith-woocommerce-questions-and-answers' ); ?></label>
						<span><?php echo wp_kses( $question->content, 'post' ); ?></span>
					</fieldset>
					<?php
				}
			} else {
				// it's a new question, let it choose the product to be related to.
				global $wpdb;
				// @codingStandardsIgnoreStart
				//Direct call to db is discouraged.
				$products = $wpdb->get_results(
					"select ID, post_title
				from {$wpdb->prefix}posts
				where post_type = 'product'
				order by post_title"
				);
				// @codingStandardsIgnoreEnd

				?>
				<input type="hidden" id="discussion_type" name="discussion_type" value="new-question">
				<table class="form-table">
					<tbody>
					<tr valign="top" class="titledesc">
						<th scope="row">
							<label for="product"><?php esc_html_e( 'Select product', 'yith-woocommerce-questions-and-answers' ); ?></label>
						</th>
						<td class="forminp yith-choosen">
							<select id="select_product" name="select_product" class="chosen-select"
									style="width: 80%" placeholder="Select product">
								<option value="-1"></option>
								<?php

								foreach ( $products as $product ) {
									?>
									<option
										value="<?php echo wp_kses( $product->ID, 'post' ); ?>"><?php echo wp_kses( $product->post_title, 'post' ); ?></option>
									<?php
								}
								?>
							</select>
						</td>
					</tr>
					</tbody>
				</table>
				<?php
				wp_enqueue_script( 'ajax-chosen' );

				$inline_js = '$(".chosen-select").chosen();';

				wc_enqueue_js( $inline_js );
			}
		}

		/**
		 * Save_plugin_metabox
		 *
		 * Save the Metabox Data
		 *
		 * @param  mixed $post_id post_id.
		 * @param  mixed $post post.
		 * @return void
		 */
		public function save_plugin_metabox( $post_id, $post ) {

			if ( YWQA_CUSTOM_POST_TYPE_NAME !== $post->post_type ) {
				return;
			}

			// verify this is not an auto save routine.
			if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
				return;
			}

			/**
			 * Update the discussion inserted
			 */
			if ( isset( $_POST['select_product'] ) ) {//phpcs:ignore WordPress.Security.NonceVerification
				update_post_meta( $post_id, YWQA_METAKEY_PRODUCT_ID, sanitize_text_field( wp_unslash( $_POST['select_product'] ) ) );//phpcs:ignore WordPress.Security.NonceVerification
				update_post_meta( $post_id, YWQA_METAKEY_DISCUSSION_TYPE, 'question' );
			}
		}

		/**
		 * Add_custom_columns_title
		 *
		 * Add custom columns to custom post type table
		 *
		 * @param  mixed $defaults defaults.
		 * @return array new columns
		 */
		public function add_custom_columns_title( $defaults ) {

			$columns = array_slice( $defaults, 0, 1 );

			$columns['image_type'] = '';

			return apply_filters( 'yith_questions_answers_custom_column_title', array_merge( $columns, array_slice( $defaults, 1 ) ) );
		}

		/**
		 * Add_custom_columns_content
		 *
		 * Show content for custom columns.
		 *
		 * @param  mixed $column_name column_name.
		 * @param  mixed $post_ID post_ID.
		 * @return void
		 */
		public function add_custom_columns_content( $column_name, $post_ID ) {

			switch ( $column_name ) {
				case 'image_type':
					$discussion = $this->get_discussion( $post_ID );
					if ( $discussion instanceof YWQA_Question ) {
						echo '<span class="dashicons dashicons-admin-comments"></span>';
					} elseif ( $discussion instanceof YWQA_Answer ) {
						echo '<span class="dashicons dashicons-admin-page"></span>';
					}
					break;

				default:
					do_action( 'yith_questions_answers_custom_column_content', $column_name, $post_ID );
			}

		}
		/**
		 * Get_discussion
		 *
		 * Retrieve the instance of the correct object based on the content type of
		 * the post.
		 *
		 * @param  mixed $post_id post_id.
		 * @return null|YWQA_Answer|YWQA_Question
		 */
		public function get_discussion( $post_id ) {

			$discussion_type = get_post_meta( $post_id, YWQA_METAKEY_DISCUSSION_TYPE, true );

			if ( 'question' === $discussion_type ) {
				return new YWQA_Question( $post_id );
			} elseif ( 'answer' === $discussion_type ) {
				return new YWQA_Answer( $post_id );
			}

			return null;
		}

		/**
		 *  Execute all the operation need when the plugin init
		 */
		public function on_plugin_init() {

			$this->init_post_type();

			if ( $this->is_new_question() ) {
				return;
			}

			if ( $this->is_new_answer() ) {
				return;
			}
		}

		/**
		 * Register the custom post type
		 */
		public function init_post_type() {

			// Set UI labels for Custom Post Type.
			$labels = array(
				'name'               => _x( 'Questions & Answers', 'Post Type General Name', 'yith-woocommerce-questions-and-answers' ),
				'singular_name'      => _x( 'Question', 'Post Type Singular Name', 'yith-woocommerce-questions-and-answers' ),
				'menu_name'          => __( 'Questions & Answers', 'yith-woocommerce-questions-and-answers' ),
				'parent_item_colon'  => __( 'Parent discussion', 'yith-woocommerce-questions-and-answers' ),
				'all_items'          => __( 'All discussion', 'yith-woocommerce-questions-and-answers' ),
				'view_item'          => __( 'View discussions', 'yith-woocommerce-questions-and-answers' ),
				'add_new_item'       => __( 'Add new question', 'yith-woocommerce-questions-and-answers' ),
				'add_new'            => __( 'Add new', 'yith-woocommerce-questions-and-answers' ),
				'edit_item'          => __( 'Edit discussion', 'yith-woocommerce-questions-and-answers' ),
				'update_item'        => __( 'Update discussion', 'yith-woocommerce-questions-and-answers' ),
				'search_items'       => __( 'Search discussion', 'yith-woocommerce-questions-and-answers' ),
				'not_found'          => __( 'Not found', 'yith-woocommerce-questions-and-answers' ),
				'not_found_in_trash' => __( 'Not found in the bin', 'yith-woocommerce-questions-and-answers' ),
			);

			// Set other options for Custom Post Type.

			$args = array(
				'label'               => __( 'Questions & Answers', 'yith-woocommerce-questions-and-answers' ),
				'description'         => __( 'YITH Questions and Answers', 'yith-woocommerce-questions-and-answers' ),
				'labels'              => $labels,
				// Features this CPT supports in Post Editor.
				'supports'            => array(
					// 'title',
					'editor',
					// 'author',
				),
				'hierarchical'        => false,
				'public'              => false,
				'show_ui'             => true,
				'show_in_menu'        => true,
				'show_in_nav_menus'   => false,
				'show_in_admin_bar'   => false,
				'menu_position'       => 9,
				'can_export'          => false,
				'has_archive'         => false,
				'exclude_from_search' => true,
				'menu_icon'           => 'dashicons-clipboard',
				'query_var'           => false,
			);

			// Registering your Custom Post Type.
			register_post_type( YWQA_CUSTOM_POST_TYPE_NAME, $args );
		}

		/**
		 * Check if there is a new question or answer from the user
		 *
		 * @return bool it's a new question
		 */
		public function is_new_question() {

			if ( ! isset( $_POST['add_new_question'] ) ) {
				return false;
			}

			if ( ! isset( $_POST['ywqa_product_id'] ) ) {
				return false;
			}

			if ( ! isset( $_POST['ywqa_ask_question_text'] ) || empty( $_POST['ywqa_ask_question_text'] ) ) {
				return false;
			}

			if (
				! isset( $_POST['ask_question'] )
				|| ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['ask_question'] ) ), 'ask_question_' . sanitize_text_field( wp_unslash( $_POST['ywqa_product_id'] ) ) )
			) {

				esc_html_e( 'Please retry submitting your question or answer.', 'yith-woocommerce-questions-and-answers' );
				exit;
			}

			$product_id = intval( $_POST['ywqa_product_id'] );
			if ( ! $product_id ) {
				esc_html_e( 'No product ID selected, the question will not be created.', 'yith-woocommerce-questions-and-answers' );
				exit;
			}

			$args = array(
				'content'    => sanitize_text_field( wp_unslash( $_POST['ywqa_ask_question_text'] ) ),
				'author_id'  => get_current_user_id(),
				'product_id' => $product_id,
				'parent_id'  => $product_id,
			);

			$this->create_question( $args );
		}

		/**
		 * Create a new question
		 *
		 * @param argsc $args array Parameters used to create the question.
		 *
		 * @return mixed|void|YWQA_Question
		 * @author Lorenzo Giuffrida
		 * @since  1.0.0
		 */
		public function create_question( $args ) {

			$question = new YWQA_Question( $args );
			$question = apply_filters( 'yith_questions_answers_before_new_question', $question );
			$question->save();
			do_action( 'yith_questions_answers_after_new_question', $question );

			return $question;
		}

		/**
		 * Check if there is a new answer
		 *
		 * @return bool it's a new answer
		 */
		public function is_new_answer() {
			if ( ! isset( $_POST['add_new_answer'] ) ) {
				return false;
			}

			if ( ! isset( $_POST['ywqa_product_id'] ) ) {
				return false;
			}

			if ( ! isset( $_POST['ywqa_question_id'] ) ) {
				return false;
			}

			if ( ! isset( $_POST['ywqa_send_answer_text'] ) || empty( $_POST['ywqa_send_answer_text'] ) ) {
				return false;
			}

			if (
				! isset( $_POST['send_answer'] )
				|| ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['send_answer'] ) ), 'submit_answer_' . sanitize_text_field( wp_unslash( $_POST['ywqa_question_id'] ) ) )
			) {

				esc_html_e( 'Please retry submitting your question or answer.', 'yith-woocommerce-questions-and-answers' );
				exit;
			}

			$args = array(
				'content'    => sanitize_text_field( wp_unslash( $_POST['ywqa_send_answer_text'] ) ),
				'author_id'  => get_current_user_id(),
				'product_id' => sanitize_text_field( wp_unslash( $_POST['ywqa_product_id'] ) ),
				'parent_id'  => sanitize_text_field( wp_unslash( $_POST['ywqa_question_id'] ) ),
			);

			$this->create_answer( $args );
		}

		/**
		 * Create_answer
		 *
		 * @param  mixed $args args.
		 * @return answer
		 */
		public function create_answer( $args ) {
			$answer = new YWQA_Answer( $args );
			$answer = apply_filters( 'yith_questions_answers_before_new_answer', $answer );
			$answer->save();
			do_action( 'yith_questions_answers_after_new_answer', $answer );

			return $answer;
		}

		/**
		 * Add frontend style
		 *
		 * @since  1.0
		 * @author Lorenzo Giuffrida
		 */
		public function enqueue_styles_scripts() {

			// register and enqueue ajax calls related script file.
			wp_enqueue_script( 'ywqa-frontend', YITH_YWQA_URL . 'assets/js/ywqa-frontend.js', array( 'jquery' ), 1.0, false, true );

			wp_enqueue_style( 'ywqa-frontend', YITH_YWQA_ASSETS_URL . '/css/ywqa-frontend.css', array(), 1.0 );

			wp_localize_script(
				'ywqa-frontend',
				'ywqa_frontend',
				array(
					'reply_to_question' => isset( $_GET['reply-to-question'] ) ? sanitize_text_field( wp_unslash( $_GET['reply-to-question'] ) ) : null, //phpcs:ignore WordPress.Security.NonceVerification

				)
			);

		}

		/**
		 * Admin_enqueue_styles_scripts
		 * Enqueue scripts on administration comment page
		 *
		 * @param  mixed $hook hook.
		 * @return void
		 */
		public function admin_enqueue_styles_scripts( $hook ) {
			global $post_type;
			if ( YWQA_CUSTOM_POST_TYPE_NAME !== $post_type ) {
				return;
			}

			/**
			 * Add styles
			 */
			wp_enqueue_style( 'ywqa-backend', YITH_YWQA_ASSETS_URL . '/css/ywqa-backend.css', array(), 1.0 );

			/**
			 * Add scripts
			 */
			wp_register_script(
				'ywqa-backend',
				YITH_YWQA_URL . 'assets/js/ywqa-backend.js',
				array(
					'jquery',
					'jquery-blockui',
				),
				1.0,
				false,
				true
			);

			wp_localize_script(
				'ywqa-backend',
				'ywqa',
				array(
					'empty_answer'   => __( 'You need to write something!', 'yith-woocommerce-questions-and-answers' ),
					'answer_success' => __( 'Answer correctly sent.', 'yith-woocommerce-questions-and-answers' ),
					'answer_error'   => __( 'An error occurred, your answer has not been added.', 'yith-woocommerce-questions-and-answers' ),
					'loader'         => apply_filters( 'yith_question_answer_loader_gif', YITH_YWQA_ASSETS_URL . '/images/loading.gif' ),
					'ajax_url'       => admin_url( 'admin-ajax.php' ),
				)
			);

			wp_enqueue_script( 'ywqa-backend' );
		}

		/**
		 * Show_question_answer_tab
		 * Add a tab for question & answer
		 *
		 * @param  mixed $tabs tabs.
		 * @return tabs
		 */
		public function show_question_answer_tab( $tabs ) {
			global $product;

			$tab_title = __( 'Questions & Answers', 'yith-woocommerce-questions-and-answers' );

			$product_id = yit_get_prop( $product, 'id' );
			if ( isset( $product_id ) ) {
				$count = $this->get_questions_count( $product_id );

				if ( $count ) {
					$tab_title .= sprintf( ' (%d)', $count );
				}
			}

			if ( ! isset( $tabs['questions'] ) ) {
				$tabs['questions'] = array(
					'title'    => $tab_title,
					'priority' => 99,
					'callback' => array( $this, 'show_question_answer_template' ),
				);
			}

			return $tabs;
		}

		/**
		 * Show the question or answer template file
		 */
		public function show_question_answer_template() {

			if ( isset( $_GET['reply-to-question'] ) ) { //phpcs:ignore WordPress.Security.NonceVerification
				$question = new YWQA_Question( sanitize_text_field( wp_unslash( $_GET['reply-to-question'] ) ) ); //phpcs:ignore WordPress.Security.NonceVerification
				wc_get_template( 'ywqa-answers-template.php', array( 'question' => $question ), '', YITH_YWQA_TEMPLATE_DIR );
			} elseif ( isset( $_GET['show-all-questions'] ) ) {//phpcs:ignore WordPress.Security.NonceVerification
				wc_get_template(
					'ywqa-questions-template.php',
					array(
						'max_items'     => - 1,
						'only_answered' => 0,
					),
					'',
					YITH_YWQA_TEMPLATE_DIR
				);
			} else {
				wc_get_template(
					'ywqa-questions-template.php',
					array(
						'max_items'     => $this->questions_to_show,
						'only_answered' => 1,
					),
					'',
					YITH_YWQA_TEMPLATE_DIR
				);
			}
		}

		/**
		 * Get_questions_count
		 *
		 * @param  mixed $product_id product_id.
		 * @return items
		 */
		public function get_questions_count( $product_id ) {
			global $wpdb;

			$query = $wpdb->prepare(
				"select count(que.ID)
				from {$wpdb->prefix}posts as que left join {$wpdb->prefix}posts as pro
				on que.post_parent = pro.ID
				where que.post_status = 'publish'
				and que.post_type = %s
				and pro.post_type = 'product'
				and pro.ID = %d",
				YWQA_CUSTOM_POST_TYPE_NAME,
				$product_id
			);

			$items = $wpdb->get_row( $query, ARRAY_N ); //phpcs:ignore --Direct db call is discouraged.

			return $items[0];
		}

		/**
		 * Get_questions
		 * Retrieve the number of questions for the product
		 *
		 * @param  mixed $product_id the product id requested.
		 * @param  mixed $items items.
		 * @param  mixed $only_answered only_answered.
		 * @return questions
		 */
		public function get_questions( $product_id, $items = 'auto', $only_answered = false ) {
			global $wpdb;

			if ( 'auto' === $items ) {
				$items = $this->questions_to_show;
			}

			$query_limit = '';
			if ( $items > 0 ) {
				$query_limit = sprintf( ' limit 0,%d ', $items );
			}

			$order_by_query = ' order by que.post_date DESC ';

			$answered_query = '';
			if ( $only_answered ) {
				$answered_query = " and que.ID in (select distinct(post_parent) from {$wpdb->prefix}posts) ";
			}

			$query = $wpdb->prepare(
				"select que.ID
				from {$wpdb->prefix}posts as que left join {$wpdb->prefix}posts as pro
				on que.post_parent = pro.ID
				where que.post_status = 'publish'
				and que.post_type = %s
				and pro.post_type = 'product'
				and pro.ID = %d" . $answered_query . $order_by_query . $query_limit, //phpcs:ignore --placeholders not works.
				YWQA_CUSTOM_POST_TYPE_NAME,
				$product_id
			);

			$post_ids = $wpdb->get_results( $query, ARRAY_A ); //phpcs:ignore --Direct db call is discouraged.

			$questions = array();

			foreach ( $post_ids as $item ) {
				$questions[] = new YWQA_Question( $item['ID'] );
			}

			return $questions;
		}

		/**
		 * Get_item
		 * Retrieve the item from the id
		 *
		 * @param  mixed $item_id id of item to be retrieved.
		 * @return question
		 */
		public function get_item( $item_id ) {

			$question = new YWQA_Question( $item_id );

			return $question;
		}

		/**
		 * Show_questions
		 * Show the reviews for a specific product
		 *
		 * @param  mixed $product_id product id for whose should be shown the reviews.
		 * @param  mixed $items items.
		 * @param  mixed $only_answered only_answered.
		 * @return count
		 */
		public function show_questions( $product_id, $items = 'auto', $only_answered = false ) {

			$questions = $this->get_questions( $product_id, $items, $only_answered );

			foreach ( $questions as $question ) {

				$this->show_question( $question );
			}

			return count( $questions );
		}

		/**
		 * Show_question
		 *
		 * @param  mixed $question question.
		 * @param  mixed $classes classes.
		 * @return void
		 */
		public function show_question( $question, $classes = '' ) {

			wc_get_template(
				'ywqa-question-template.php',
				array(
					'question' => $question,
					'classes'  => $classes,
				),
				'',
				YITH_YWQA_TEMPLATE_DIR
			);
		}

		/**
		 * Show_answers
		 * Show the reviews for a specific product
		 *
		 * @param  mixed $question question.
		 * @return void
		 */
		public function show_answers( $question ) {

			foreach ( $question->get_answers() as $answer ) {

				$this->show_answer( $answer );
			}
		}

		/**
		 * Show_answer
		 * Call the question template file and show the content
		 *
		 * @param  mixed $answer answer.
		 * @param  mixed $classes classes.
		 * @return void
		 */
		public function show_answer( $answer, $classes = '' ) {

			wc_get_template(
				'ywqa-answer-template.php',
				array(
					'answer'  => $answer,
					'classes' => $classes,
				),
				'',
				YITH_YWQA_TEMPLATE_DIR
			);
		}

		/**
		 * Action_links
		 *
		 * @param  mixed $links links.
		 * @return links
		 * @since    1.2.3
		 * @author   Carlos Rodríguez <carlos.rodriguez@youirinspiration.it>
		 */
		public function action_links( $links ) {
			$links = yith_add_action_links( $links, 'yith_woocommerce_question_answer_panel', false );
			return $links;
		}
		/**
		 * Plugin Row Meta
		 *
		 * @param  mixed $new_row_meta_args new_row_meta_args.
		 * @param  mixed $plugin_meta plugin_meta.
		 * @param  mixed $plugin_file plugin_file.
		 * @param  mixed $plugin_data plugin_data.
		 * @param  mixed $status status.
		 * @param  mixed $init_file init_file.
		 * @return new_row_meta_args
		 * @since    1.2.3
		 * @author   Carlos Rodríguez <carlos.rodriguez@youirinspiration.it>
		 */
		public function plugin_row_meta( $new_row_meta_args, $plugin_meta, $plugin_file, $plugin_data, $status, $init_file = 'YITH_YWQA_FREE_INIT' ) {
			if ( defined( $init_file ) && constant( $init_file ) === $plugin_file ) {
				$new_row_meta_args['slug'] = 'yith-woocommerce-questions-and-answers';
			}

			return $new_row_meta_args;
		}
	}
}
