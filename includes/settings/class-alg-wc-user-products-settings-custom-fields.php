<?php
/**
 * User Products for WooCommerce - Custom Fields Section Settings
 *
 * @version 2.0.0
 * @since   1.5.0
 *
 * @author  Algoritmika Ltd
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Alg_WC_User_Products_Settings_Custom_Fields' ) ) :

class Alg_WC_User_Products_Settings_Custom_Fields extends Alg_WC_User_Products_Settings_Section {

	/**
	 * Constructor.
	 *
	 * @version 1.5.0
	 * @since   1.5.0
	 */
	function __construct() {
		$this->id   = 'custom_fields';
		$this->desc = __( 'Custom Fields', 'user-products-for-woocommerce' );
		parent::__construct();
	}

	/**
	 * get_settings.
	 *
	 * @version 2.0.0
	 * @since   1.5.0
	 *
	 * @todo    (dev) add "Field type" option (e.g., `text`, `number`, etc.)
	 */
	function get_settings() {
		$settings = array(
			array(
				'title'    => __( 'Custom Fields Options', 'user-products-for-woocommerce' ),
				'type'     => 'title',
				'id'       => 'alg_wc_user_products_custom_fields_options',
			),
			array(
				'title'    => __( 'Total custom fields', 'user-products-for-woocommerce' ),
				'id'       => 'alg_wc_user_products_custom_fields_total',
				'default'  => 1,
				'type'     => 'number',
				'desc_tip' => __( 'New settings fields will be displayed if you change this option and save changes.', 'user-products-for-woocommerce' ),
			),
		);
		for ( $i = 1; $i <= get_option( 'alg_wc_user_products_custom_fields_total', 1 ); $i++ ) {
			$settings = array_merge( $settings, array(
				array(
					'title'    => __( 'Custom field', 'user-products-for-woocommerce' ) . ' #' . $i,
					'desc'     => __( 'Enabled', 'user-products-for-woocommerce' ),
					'id'       => "alg_wc_user_products_custom_field_enabled[{$i}]",
					'default'  => 'no',
					'type'     => 'checkbox',
				),
				array(
					'desc'     => __( 'Required', 'user-products-for-woocommerce' ),
					'id'       => "alg_wc_user_products_custom_field_required[{$i}]",
					'default'  => 'no',
					'type'     => 'checkbox',
				),
				array(
					'desc'     => __( 'Meta key', 'user-products-for-woocommerce' ),
					'id'       => "alg_wc_user_products_custom_field_meta_key[{$i}]",
					'default'  => '',
					'type'     => 'text',
				),
				array(
					'desc'     => __( 'Title', 'user-products-for-woocommerce' ),
					'id'       => "alg_wc_user_products_custom_field_title[{$i}]",
					'default'  => '',
					'type'     => 'text',
				),
			) );
		}
		$settings = array_merge( $settings, array(
			array(
				'type'     => 'sectionend',
				'id'       => 'alg_wc_user_products_custom_fields_options',
			),
		) );
		return $settings;
	}

}

endif;

return new Alg_WC_User_Products_Settings_Custom_Fields();
