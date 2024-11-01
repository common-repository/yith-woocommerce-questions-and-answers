<?php  // phpcs:ignore WordPress.NamingConventions

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
	exit; // Exit if accessed directly.
}

if ( ! class_exists( 'YWQA_Plugin_FW_Loader' ) ) {

	/**
	 * Implements features related to an invoice document
	 *
	 * @class YWQA_Plugin_FW_Loader
	 * @package Yithemes
	 * @since   1.0.0
	 * @author  Your Inspiration Themes
	 */
	class YWQA_Plugin_FW_Loader {

		/**
		 * Panel
		 *
		 * @var $panel Panel Object
		 */
		protected $panel;

		/**
		 * Premium
		 *
		 * @var $premium string Premium tab template file name
		 */
		protected $premium = 'premium.php';

		/**
		 * Premium_landing_url
		 *
		 * @var string Premium version landing link
		 */
		protected $premium_landing_url = '//yithemes.com/themes/plugins/yith-woocommerce-questions-and-answers/';

		/**
		 * Official_documentation
		 *
		 * @var string Plugin official documentation
		 */
		protected $official_documentation = 'https://docs.yithemes.com/yith-woocommerce-questions-and-answers/';

		/**
		 * Panel_page
		 *
		 * @var string Plugin panel page
		 */
		protected $panel_page = 'yith_woocommerce_question_answer_panel';

		/**
		 * Single instance of the class
		 *
		 * @var instance instance
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
		 * __construct
		 *
		 * @return void
		 */
		public function __construct() {
			/**
			 * Register actions and filters to be used for creating an entry on YIT Plugin menu
			 */
			add_action( 'admin_init', array( $this, 'register_pointer' ) );

			add_action( 'plugins_loaded', array( $this, 'plugin_fw_loader' ), 15 );

			// Add stylesheets and scripts files.
			add_action( 'admin_menu', array( $this, 'register_panel' ), 5 );

			// Show plugin premium tab.
			add_action( 'yith_question_answer_premium', array( $this, 'premium_tab' ) );

			/**
			 * Register plugin to licence/update system.
			 */
			$this->licence_activation();
		}


		/**
		 * Load YIT core plugin
		 *
		 * @since  1.0
		 * @access public
		 * @return void
		 * @author Andrea Grillo <andrea.grillo@yithemes.com>
		 */
		public function plugin_fw_loader() {
			if ( ! defined( 'YIT_CORE_PLUGIN' ) ) {
				global $plugin_fw_data;
				if ( ! empty( $plugin_fw_data ) ) {
					$plugin_fw_file = array_shift( $plugin_fw_data );
					require_once $plugin_fw_file;
				}
			}
		}

		/**
		 * Add a panel under YITH Plugins tab
		 *
		 * @return   void
		 * @since    1.0
		 * @author   Andrea Grillo <andrea.grillo@yithemes.com>
		 * @use     /Yit_Plugin_Panel class
		 * @see      plugin-fw/lib/yit-plugin-panel.php
		 */
		public function register_panel() {

			if ( ! empty( $this->panel ) ) {
				return;
			}

			$admin_tabs['general'] = esc_html__( 'General', 'yith-woocommerce-questions-and-answers' );

			if ( ! defined( 'YITH_YWQA_PREMIUM' ) ) {
				$admin_tabs['premium'] = esc_html__( 'Premium Version', 'yith-woocommerce-questions-and-answers' );
			}

			$args = array(
				'create_menu_page' => true,
				'parent_slug'      => '',
				'plugin_slug'      => YITH_YWQA_SLUG,
				'page_title'       => 'Questions & Answers',
				'menu_title'       => 'Questions & Answers',
				'capability'       => 'manage_options',
				'parent'           => '',
				'class'            => yith_set_wrapper_class(),
				'parent_page'      => 'yit_plugin_panel',
				'page'             => $this->panel_page,
				'admin-tabs'       => $admin_tabs,
				'options-path'     => YITH_YWQA_DIR . '/plugin-options',
			);

			/* === Fixed: not updated theme  === */
			if ( ! class_exists( 'YIT_Plugin_Panel_WooCommerce' ) ) {

				require_once 'plugin-fw/lib/yit-plugin-panel-wc.php';
			}

			$this->panel = new YIT_Plugin_Panel_WooCommerce( $args );
		}

		/**
		 * Premium Tab Template
		 *
		 * Load the premium tab template on admin page
		 *
		 * @since    1.0
		 * @author   Andrea Grillo <andrea.grillo@yithemes.com>
		 * @return void
		 */
		public function premium_tab() {
			$premium_tab_template = YITH_YWQA_TEMPLATE_DIR . '/admin/' . $this->premium;
			if ( file_exists( $premium_tab_template ) ) {
				include_once $premium_tab_template;
			}
		}

		/**
		 * Register_pointer
		 *
		 * @return void
		 */
		public function register_pointer() {
			if ( ! class_exists( 'YIT_Pointers' ) ) {
				include_once 'plugin-fw/lib/yit-pointers.php';
			}

			$premium_message = defined( 'YITH_YWQA_PREMIUM' )
				? ''
				: esc_html__( 'YITH WooCommerce Questions and Answers is available in an outstanding PREMIUM version with many new options, discover it now.', 'yith-woocommerce-questions-and-answers' ) .
				' <a href="' . $this->get_premium_landing_uri() . '">' . esc_html__( 'Premium version', 'yith-woocommerce-questions-and-answers' ) . '</a>';

			$args[] = array(
				'screen_id'  => 'plugins',
				'pointer_id' => 'yith_woocommerce_question_answer',
				'target'     => '#toplevel_page_yit_plugin_panel',
				'content'    => sprintf(
					'<h3> %s </h3> <p> %s </p>',
					esc_html__( 'YITH WooCommerce Questions and Answers', 'yith-woocommerce-questions-and-answers' ),
					esc_html__( 'In YIT Plugins tab you can find YITH WooCommerce Questions & Answers options.<br> From this menu you can access all settings of YITH plugins activated.', 'yith-woocommerce-questions-and-answers' ) . '<br>' . $premium_message
				),
				'position'   => array(
					'edge'  => 'left',
					'align' => 'center',
				),
				'init'       => defined( 'YITH_YWQA_PREMIUM' ) ? YITH_YWQA_INIT : YITH_YWQA_FREE_INIT,
			);

			YIT_Pointers()->register( $args );
		}

		/**
		 * Get the premium landing uri
		 *
		 * @since   1.0.0
		 * @author  Andrea Grillo <andrea.grillo@yithemes.com>
		 * @return  string The premium landing link
		 */
		public function get_premium_landing_uri() {
			return $this->premium_landing_url;
		}

		// region    ****    licence related methods ****.

		/**
		 * Add actions to manage licence activation and updates
		 */
		public function licence_activation() {
			if ( ! defined( 'YITH_YWQA_PREMIUM' ) ) {
				return;
			}

			add_action( 'wp_loaded', array( $this, 'register_plugin_for_activation' ), 99 );
			add_action( 'admin_init', array( $this, 'register_plugin_for_updates' ) );
		}

		/**
		 * Register plugins for activation tab
		 *
		 * @return void
		 * @since    2.0.0
		 * @author   Andrea Grillo <andrea.grillo@yithemes.com>
		 */
		public function register_plugin_for_activation() {
			if ( ! class_exists( 'YIT_Plugin_Licence' ) ) {
				require_once YITH_YWQA_DIR . '/plugin-fw/licence/lib/yit-licence.php';
				require_once YITH_YWQA_DIR . '/plugin-fw/licence/lib/yit-plugin-licence.php';
			}
			YIT_Plugin_Licence()->register( YITH_YWQA_INIT, YITH_YWQA_SECRET_KEY, YITH_YWQA_SLUG );
		}

		/**
		 * Register plugins for update tab
		 *
		 * @return void
		 * @since    2.0.0
		 * @author   Andrea Grillo <andrea.grillo@yithemes.com>
		 */
		public function register_plugin_for_updates() {
			if ( ! class_exists( 'YIT_Upgrade' ) ) {
				require_once 'plugin-fw/lib/yit-upgrade.php';
			}
			YIT_Upgrade()->register( YITH_YWQA_SLUG, YITH_YWQA_INIT );
		}
		// endregion.
	}
}
