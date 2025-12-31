<?php
/**
 * ZILI User Products for WooCommerce - My Account Class
 *
 * @version 2.0.2
 * @since   1.0.0
 *
 * @author  Algoritmika Ltd
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Alg_WC_User_Products_My_Account' ) ) :

class Alg_WC_User_Products_My_Account {

	/**
	 * Constructor.
	 *
	 * @version 2.0.0
	 * @since   1.0.0
	 */
	function __construct() {

		// Flush rewrite rules
		register_activation_hook(
			ALG_WC_USER_PRODUCTS_FILE,
			array( $this, 'add_my_products_endpoint_flush_rewrite_rules' )
		);
		register_deactivation_hook(
			ALG_WC_USER_PRODUCTS_FILE,
			array( $this, 'add_my_products_endpoint_flush_rewrite_rules' )
		);
		add_action(
			'alg_wc_user_products_after_save_settings',
			array( $this, 'add_my_products_endpoint_flush_rewrite_rules' )
		);

		// Query var
		add_filter(
			'query_vars',
			array( $this, 'add_my_products_endpoint_query_var' ),
			0
		);

		// Endpoint
		add_action(
			'init',
			array( $this, 'add_my_products_endpoint' )
		);

		// Tab
		add_filter(
			'woocommerce_account_menu_items',
			array( $this, 'add_my_products_tab_my_account_page' )
		);

		// Content
		add_action(
			'woocommerce_account_alg-wc-my-products_endpoint',
			array( $this, 'add_my_products_content_my_account_page' )
		);

		// Title
		add_filter(
			'the_title',
			array( $this, 'change_my_products_endpoint_title' )
		);

	}

	/**
	 * Flush rewrite rules on plugin activation.
	 *
	 * @version 1.3.0
	 * @since   1.0.0
	 *
	 * @todo    (dev) `alg_wc_user_products_after_save_settings`: call only `flush_rewrite_rules()`?
	 * @todo    (dev) `alg_wc_user_products_after_save_settings`: run always, i.e., even if the plugin, or "Products tab" option are disabled?
	 */
	function add_my_products_endpoint_flush_rewrite_rules() {
		$this->add_my_products_endpoint();
		flush_rewrite_rules();
	}

	/**
	 * Add new query var.
	 *
	 * @version 1.1.0
	 * @since   1.0.0
	 *
	 * @param   array $vars
	 * @return  array
	 */
	function add_my_products_endpoint_query_var( $vars ) {
		$vars[] = 'alg-wc-my-products';
		return $vars;
	}

	/**
	 * Register new endpoint to use inside My Account page.
	 *
	 * @version 1.1.0
	 * @since   1.0.0
	 *
	 * @see     https://developer.wordpress.org/reference/functions/add_rewrite_endpoint/
	 */
	function add_my_products_endpoint() {
		add_rewrite_endpoint( 'alg-wc-my-products', EP_ROOT | EP_PAGES );
	}

	/*
	 * Change endpoint title.
	 *
	 * @version 2.0.1
	 * @since   1.0.0
	 *
	 * @param   string $title
	 * @return  string
	 *
	 * @see     https://github.com/woocommerce/woocommerce/wiki/2.6-Tabbed-My-Account-page
	 *
	 * @todo    (feature) customizable page title
	 */
	function change_my_products_endpoint_title( $title ) {
		global $wp_query;
		if (
			isset( $wp_query->query_vars['alg-wc-my-products'] ) && // is endpoint
			! is_admin() &&
			is_main_query() &&
			in_the_loop() &&
			is_account_page()
		) {
			$title = __( 'Products', 'zili-user-products-for-woocommerce' );
			remove_filter( 'the_title', array( $this, 'change_my_products_endpoint_title' ) );
		}
		return $title;
	}

	/**
	 * Custom help to add new items into an array after a selected item.
	 *
	 * @version 1.0.0
	 * @since   1.0.0
	 *
	 * @param   array $items
	 * @param   array $new_items
	 * @param   string $after
	 * @return  array
	 *
	 * @see     https://github.com/woocommerce/woocommerce/wiki/2.6-Tabbed-My-Account-page
	 */
	function insert_after_helper( $items, $new_items, $after ) {
		// Search for the item position and +1 since is after the selected item key.
		$position = array_search( $after, array_keys( $items ) ) + 1;
		// Insert the new item.
		$array = array_slice( $items, 0, $position, true );
		$array += $new_items;
		$array += array_slice( $items, $position, count( $items ) - $position, true );
		return $array;
	}

	/**
	 * add_my_products_tab_my_account_page.
	 *
	 * @version 2.0.1
	 * @since   1.0.0
	 *
	 * @todo    (feature) customizable tab title
	 * @todo    (dev) check if any user's products exist (i.e., do not add tab at all)
	 */
	function add_my_products_tab_my_account_page( $items ) {
		$new_items = array(
			'alg-wc-my-products' => __( 'Products', 'zili-user-products-for-woocommerce' ),
		);
		return $this->insert_after_helper( $items, $new_items, 'orders' );
	}

	/**
	 * add_my_products_content_my_account_page.
	 *
	 * @version 2.0.2
	 * @since   1.0.0
	 */
	function add_my_products_content_my_account_page() {
		$content = get_option(
			'alg_wc_user_products_my_account_tab_content',
			'[zili_wc_user_products_list]'
		);
		$content = str_replace(
			'[wc_user_products_list]',
			'[zili_wc_user_products_list]',
			$content
		);
		echo do_shortcode( $content );
	}

}

endif;

return new Alg_WC_User_Products_My_Account();
