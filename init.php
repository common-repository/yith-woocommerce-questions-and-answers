<?php
/**
 * Plugin Name: YITH WooCommerce Questions and Answers
 * Plugin URI: http://yithemes.com
 * Description: <code><strong>YITH WooCommerce Questions And Answers</strong></code> offers a rapid way to manage dynamic discussions about the products of your shop. <a href="https://yithemes.com/" target="_blank">Get more plugins for your e-commerce shop on <strong>YITH</strong></a>.
 * Author: YITH
 * Text Domain: yith-woocommerce-questions-and-answers
 * Version: 1.5.0
 * WC requires at least: 5.3
 * WC tested up to: 5.8
 * Author URI: http://yithemes.com/
 *
 * @package yith-woocommerce-question-and-answer\
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

if ( ! function_exists( 'is_plugin_active' ) ) {
	require_once ABSPATH . 'wp-admin/includes/plugin.php';
}

/**
 * Yith_ywqa_install_woocommerce_admin_notice
 *
 * @return void
 */
function yith_ywqa_install_woocommerce_admin_notice() {
	?>
	<div class="error">
		<p><?php esc_html_e( 'YITH WooCommerce Questions & Answers is enabled but not effective. It requires WooCommerce in order to work.', 'yit' ); ?></p>
	</div>
	<?php
}

/**
 * Yith_ywqa_install_free_admin_notice
 *
 * @return void
 */
function yith_ywqa_install_free_admin_notice() {
	?>
	<div class="error">
		<p><?php esc_html_e( 'You can\'t activate the free version of YITH WooCommerce Questions & Answers while you are using the premium one.', 'yit' ); ?></p>
	</div>
	<?php
}

if ( ! function_exists( 'yith_plugin_registration_hook' ) ) {
	require_once 'plugin-fw/yit-plugin-registration-hook.php';
}
register_activation_hook( __FILE__, 'yith_plugin_registration_hook' );

// region    ****    Define constants.

if ( ! defined( 'YITH_YWQA_FREE_INIT' ) ) {
	define( 'YITH_YWQA_FREE_INIT', plugin_basename( __FILE__ ) );
}

if ( ! defined( 'YITH_YWQA_VERSION' ) ) {
	define( 'YITH_YWQA_VERSION', '1.5.0' );
}

if ( ! defined( 'YITH_YWQA_FILE' ) ) {
	define( 'YITH_YWQA_FILE', __FILE__ );
}

if ( ! defined( 'YITH_YWQA_DIR' ) ) {
	define( 'YITH_YWQA_DIR', plugin_dir_path( __FILE__ ) );
}

if ( ! defined( 'YITH_YWQA_URL' ) ) {
	define( 'YITH_YWQA_URL', plugins_url( '/', __FILE__ ) );
}

if ( ! defined( 'YITH_YWQA_ASSETS_URL' ) ) {
	define( 'YITH_YWQA_ASSETS_URL', YITH_YWQA_URL . 'assets' );
}

if ( ! defined( 'YITH_YWQA_TEMPLATE_PATH' ) ) {
	define( 'YITH_YWQA_TEMPLATE_PATH', YITH_YWQA_DIR . 'templates' );
}

if ( ! defined( 'YITH_YWQA_TEMPLATE_DIR' ) ) {
	define( 'YITH_YWQA_TEMPLATE_DIR', YITH_YWQA_DIR . '/templates/' );
}

if ( ! defined( 'YITH_YWQA_ASSETS_IMAGES_URL' ) ) {
	define( 'YITH_YWQA_ASSETS_IMAGES_URL', YITH_YWQA_ASSETS_URL . '/images/' );
}

defined( 'YITH_YWQA_LIB_DIR' ) || define( 'YITH_YWQA_LIB_DIR', YITH_YWQA_DIR . 'lib/' );
defined( 'YITH_YWQA_VIEWS_PATH' ) || define( 'YITH_YWQA_VIEWS_PATH', YITH_YWQA_DIR . 'views/' );
! defined( 'YITH_YWQA_SLUG' ) && define( 'YITH_YWQA_SLUG', 'yith-woocommerce-questions-and-answers' );

// endregion.

/* Plugin Framework Version Check */
if ( ! function_exists( 'yit_maybe_plugin_fw_loader' ) && file_exists( YITH_YWQA_DIR . 'plugin-fw/init.php' ) ) {
	require_once YITH_YWQA_DIR . 'plugin-fw/init.php';
}
yit_maybe_plugin_fw_loader( YITH_YWQA_DIR );

/**
 * Yith_ywqa_init
 *
 * @return void
 */
function yith_ywqa_init() {

	/**
	 * Load text domain and start plugin
	 */
	load_plugin_textdomain( 'yith-woocommerce-questions-and-answers', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );

	require_once YITH_YWQA_DIR . 'class.yith-woocommerce-question-answer.php';
	require_once YITH_YWQA_DIR . 'lib/class.ywqa-plugin-fw-loader.php';
	require_once YITH_YWQA_DIR . 'lib/class.ywqa-discussion.php';
	require_once YITH_YWQA_DIR . 'lib/class.ywqa-question.php';
	require_once YITH_YWQA_DIR . 'lib/class.ywqa-answer.php';
	require_once YITH_YWQA_DIR . 'functions.php';

	YWQA_Plugin_FW_Loader::get_instance();

	global $YWQA; // phpcs:ignore WordPress.NamingConventions
	$YWQA = YITH_WooCommerce_Question_Answer::get_instance(); // phpcs:ignore WordPress.NamingConventions
}

add_action( 'yith_ywqa_init', 'yith_ywqa_init' );


/**
 * Yith_ywqa_install
 *
 * @return void
 */
function yith_ywqa_install() {

	if ( ! function_exists( 'WC' ) ) {
		add_action( 'admin_notices', 'yith_ywqa_install_woocommerce_admin_notice' );
	} elseif ( defined( 'YITH_YWQA_PREMIUM' ) ) {
		add_action( 'admin_notices', 'yith_ywqa_install_free_admin_notice' );
		deactivate_plugins( plugin_basename( __FILE__ ) );
	} else {
		do_action( 'yith_ywqa_init' );
	}
}

add_action( 'plugins_loaded', 'yith_ywqa_install', 11 );
