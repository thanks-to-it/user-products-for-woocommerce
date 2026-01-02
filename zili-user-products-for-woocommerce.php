<?php
/*
Plugin Name: ZILI User Products for WooCommerce
Plugin URI: https://wordpress.org/plugins/zili-user-products-for-woocommerce/
Description: Let users add new WooCommerce products from frontend.
Version: 2.0.2
Author: Algoritmika Ltd
Author URI: https://algoritmika.com
Requires at least: 5.0
Text Domain: zili-user-products-for-woocommerce
Domain Path: /langs
WC tested up to: 10.4
Requires Plugins: woocommerce
License: GNU General Public License v3.0
License URI: http://www.gnu.org/licenses/gpl-3.0.html
*/

defined( 'ABSPATH' ) || exit;

if ( 'zili-user-products-for-woocommerce.php' === basename( __FILE__ ) ) {
	/**
	 * Check if Pro plugin version is activated.
	 *
	 * @version 1.5.0
	 * @since   1.4.0
	 */
	$plugin = 'user-products-for-woocommerce-pro/user-products-for-woocommerce-pro.php';
	if (
		in_array( $plugin, (array) get_option( 'active_plugins', array() ), true ) ||
		(
			is_multisite() &&
			array_key_exists( $plugin, (array) get_site_option( 'active_sitewide_plugins', array() ) )
		)
	) {
		defined( 'ALG_WC_USER_PRODUCTS_FILE_FREE' ) || define( 'ALG_WC_USER_PRODUCTS_FILE_FREE', __FILE__ );
		return;
	}
}

defined( 'ALG_WC_USER_PRODUCTS_VERSION' ) || define( 'ALG_WC_USER_PRODUCTS_VERSION', '2.0.2' );

defined( 'ALG_WC_USER_PRODUCTS_FILE' ) || define( 'ALG_WC_USER_PRODUCTS_FILE', __FILE__ );

require_once plugin_dir_path( __FILE__ ) . 'includes/class-alg-wc-user-products.php';

if ( ! function_exists( 'alg_wc_user_products' ) ) {
	/**
	 * Returns the main instance of Alg_WC_User_Products to prevent the need to use globals.
	 *
	 * @version 1.1.0
	 * @since   1.0.0
	 */
	function alg_wc_user_products() {
		return Alg_WC_User_Products::instance();
	}
}

add_action( 'plugins_loaded', 'alg_wc_user_products' );
