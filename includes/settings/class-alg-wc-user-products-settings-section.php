<?php
/**
 * ZILI User Products for WooCommerce - Section Settings
 *
 * @version 2.0.1
 * @since   1.0.0
 *
 * @author  Algoritmika Ltd
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Alg_WC_User_Products_Settings_Section' ) ) :

class Alg_WC_User_Products_Settings_Section {

	/**
	 * id.
	 *
	 * @version 1.5.0
	 * @since   1.5.0
	 */
	public $id;

	/**
	 * desc.
	 *
	 * @version 1.5.0
	 * @since   1.5.0
	 */
	public $desc;

	/**
	 * Constructor.
	 *
	 * @version 1.1.0
	 * @since   1.0.0
	 */
	function __construct() {
		add_filter(
			'woocommerce_get_sections_alg_wc_user_products',
			array( $this, 'settings_section' )
		);
		add_filter(
			'woocommerce_get_settings_alg_wc_user_products_' . $this->id,
			array( $this, 'get_settings' ),
			PHP_INT_MAX
		);
	}

	/**
	 * settings_section.
	 *
	 * @version 1.0.0
	 * @since   1.0.0
	 */
	function settings_section( $sections ) {
		$sections[ $this->id ] = $this->desc;
		return $sections;
	}

	/**
	 * get_user_roles_options.
	 *
	 * @version 2.0.1
	 * @since   1.0.0
	 */
	function get_user_roles_options() {
		global $wp_roles;
		$all_roles = apply_filters(
			'editable_roles', // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound
			(
				isset( $wp_roles ) && is_object( $wp_roles ) ?
				$wp_roles->roles :
				array()
			)
		);
		return array_merge(
			array( 'guest' => __( 'Guest', 'zili-user-products-for-woocommerce' ) ),
			wp_list_pluck( $all_roles, 'name' )
		);
	}

}

endif;
