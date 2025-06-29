<?php
/**
 * User Products for WooCommerce - Fields Section Settings
 *
 * @version 2.0.0
 * @since   1.5.0
 *
 * @author  Algoritmika Ltd
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Alg_WC_User_Products_Settings_Fields' ) ) :

class Alg_WC_User_Products_Settings_Fields extends Alg_WC_User_Products_Settings_Section {

	/**
	 * Constructor.
	 *
	 * @version 1.5.0
	 * @since   1.5.0
	 */
	function __construct() {
		$this->id   = 'fields';
		$this->desc = __( 'Fields', 'user-products-for-woocommerce' );
		parent::__construct();
	}

	/**
	 * get_settings.
	 *
	 * @version 2.0.0
	 * @since   1.5.0
	 *
	 * @todo    (feature) customizable field titles
	 */
	function get_settings() {
		$fields = array(
			'desc'          => __( 'Description', 'user-products-for-woocommerce' ),
			'short_desc'    => __( 'Short Description', 'user-products-for-woocommerce' ),
			'image'         => __( 'Image', 'user-products-for-woocommerce' ),
			'regular_price' => __( 'Regular Price', 'user-products-for-woocommerce' ),
			'sale_price'    => __( 'Sale Price', 'user-products-for-woocommerce' ),
			'external_url'  => __( 'Product URL (for "External/Affiliate" product type only)', 'user-products-for-woocommerce' ),
			'cats'          => __( 'Categories', 'user-products-for-woocommerce' ),
			'tags'          => __( 'Tags', 'user-products-for-woocommerce' ),
		);
		$fields_enabled_options  = array();
		$fields_required_options = array();
		$i                       = 0;
		$total_fields            = count( $fields );
		foreach ( $fields as $field_id => $field_desc ) {
			$i++;
			$checkboxgroup = '';
			if ( 1 === $i ) {
				$checkboxgroup = 'start';
			} elseif ( $total_fields === $i ) {
				$checkboxgroup = 'end';
			}
			$fields_enabled_options[] = array(
				'title'             => ( 1 === $i ? __( 'Additional fields', 'user-products-for-woocommerce' ) : '' ),
				'desc'              => $field_desc,
				'id'                => "alg_wc_user_products_fields_enabled[{$field_id}]",
				'default'           => 'no',
				'type'              => 'checkbox',
				'checkboxgroup'     => $checkboxgroup,
			);
			$fields_required_options[] = array(
				'title'             => ( 1 === $i ? __( 'Is required', 'user-products-for-woocommerce' ) : '' ),
				'desc'              => $field_desc,
				'id'                => "alg_wc_user_products_fields_required[{$field_id}]",
				'default'           => 'no',
				'type'              => 'checkbox',
				'checkboxgroup'     => $checkboxgroup,
			);
		}
		$settings = array_merge(
			array( array(
				'title'    => __( 'Fields Options', 'user-products-for-woocommerce' ),
				'type'     => 'title',
				'desc'     => __( '<em>Title</em> field is always enabled and required.', 'user-products-for-woocommerce' ),
				'id'       => 'alg_wc_user_products_fields_options',
			) ),
			$fields_enabled_options,
			$fields_required_options,
			array( array(
				'type'     => 'sectionend',
				'id'       => 'alg_wc_user_products_fields_options',
			) )
		);
		return $settings;
	}

}

endif;

return new Alg_WC_User_Products_Settings_Fields();
