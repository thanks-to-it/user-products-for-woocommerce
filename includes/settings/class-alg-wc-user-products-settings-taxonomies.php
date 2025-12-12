<?php
/**
 * ZILI User Products for WooCommerce - Taxonomies Section Settings
 *
 * @version 2.0.1
 * @since   1.5.0
 *
 * @author  Algoritmika Ltd
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Alg_WC_User_Products_Settings_Taxonomies' ) ) :

class Alg_WC_User_Products_Settings_Taxonomies extends Alg_WC_User_Products_Settings_Section {

	/**
	 * Constructor.
	 *
	 * @version 2.0.1
	 * @since   1.5.0
	 */
	function __construct() {
		$this->id   = 'taxonomies';
		$this->desc = __( 'Taxonomies', 'zili-user-products-for-woocommerce' );
		parent::__construct();
	}

	/**
	 * get_settings.
	 *
	 * @version 2.0.1
	 * @since   1.5.0
	 */
	function get_settings() {
		$settings = array(
			array(
				'title' => __( 'Taxonomies Options', 'zili-user-products-for-woocommerce' ),
				'type'  => 'title',
				'id'    => 'alg_wc_user_products_taxonomies_options',
			),
			array(
				'title'             => __( 'Total custom taxonomies', 'zili-user-products-for-woocommerce' ),
				'id'                => 'alg_wc_user_products_custom_taxonomies_total',
				'default'           => 1,
				'type'              => 'number',
				'desc_tip'          => __( 'New settings fields will be displayed if you change this option and save changes.', 'zili-user-products-for-woocommerce' ),
				'custom_attributes' => array( 'min' => 0 ),
			),
		);
		for ( $i = 1; $i <= get_option( 'alg_wc_user_products_custom_taxonomies_total', 1 ); $i++ ) {
			$settings = array_merge( $settings, array(
				array(
					'title'   => __( 'Custom taxonomy', 'zili-user-products-for-woocommerce' ) . ' #' . $i,
					'desc'    => __( 'Enabled', 'zili-user-products-for-woocommerce' ),
					'id'      => "alg_wc_user_products_custom_taxonomy_enabled[{$i}]",
					'default' => 'no',
					'type'    => 'checkbox',
				),
				array(
					'desc'    => __( 'Required', 'zili-user-products-for-woocommerce' ),
					'id'      => "alg_wc_user_products_custom_taxonomy_required[{$i}]",
					'default' => 'no',
					'type'    => 'checkbox',
				),
				array(
					'desc'    => __( 'ID', 'zili-user-products-for-woocommerce' ),
					'id'      => "alg_wc_user_products_custom_taxonomy_id[{$i}]",
					'default' => '',
					'type'    => 'text',
				),
				array(
					'desc'    => __( 'Title', 'zili-user-products-for-woocommerce' ),
					'id'      => "alg_wc_user_products_custom_taxonomy_title[{$i}]",
					'default' => '',
					'type'    => 'text',
				),
			) );
		}
		$settings = array_merge( $settings, array(
			array(
				'type' => 'sectionend',
				'id'   => 'alg_wc_user_products_taxonomies_options',
			),
		) );
		return $settings;
	}

}

endif;

return new Alg_WC_User_Products_Settings_Taxonomies();
