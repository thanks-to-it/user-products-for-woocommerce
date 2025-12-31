<?php
/**
 * ZILI User Products for WooCommerce - General Section Settings
 *
 * @version 2.0.2
 * @since   1.0.0
 *
 * @author  Algoritmika Ltd
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Alg_WC_User_Products_Settings_General' ) ) :

class Alg_WC_User_Products_Settings_General extends Alg_WC_User_Products_Settings_Section {

	/**
	 * Constructor.
	 *
	 * @version 2.0.1
	 * @since   1.0.0
	 */
	function __construct() {
		$this->id   = '';
		$this->desc = __( 'General', 'zili-user-products-for-woocommerce' );
		parent::__construct();
	}

	/**
	 * get_settings.
	 *
	 * @version 2.0.2
	 * @since   1.0.0
	 *
	 * @todo    (feature) `alg_wc_user_products_product_type` - Variable products
	 */
	function get_settings() {
		return array(
			array(
				'title'             => __( 'Options', 'zili-user-products-for-woocommerce' ),
				'type'              => 'title',
				'id'                => 'alg_wc_user_products_general_options',
				'desc'              => (
					__( 'Let users add new WooCommerce products from frontend.', 'zili-user-products-for-woocommerce' ) . ' ' .
					sprintf(
						/* Translators: %s: Shortcode name. */
						__( 'Use %s shortcode to add product upload form to frontend.', 'zili-user-products-for-woocommerce' ),
						'<code>[zili_wc_user_products_add_new]</code>'
					)
				),
			),
			array(
				'title'             => __( 'Price step (number of decimals)', 'zili-user-products-for-woocommerce' ),
				'desc_tip'          => __( 'Used for price fields only.', 'zili-user-products-for-woocommerce' ),
				'id'                => 'alg_wc_user_products_price_step',
				'default'           => get_option( 'woocommerce_price_num_decimals', 2 ),
				'type'              => 'number',
				'custom_attributes' => array( 'min' => 0 ),
			),
			array(
				'title'             => __( 'User visibility', 'zili-user-products-for-woocommerce' ),
				'desc_tip'          => (
					__( 'If you want for product add form to be visible to selected user roles only, set roles here.', 'zili-user-products-for-woocommerce' ) . ' ' .
					__( 'Ignored if empty.', 'zili-user-products-for-woocommerce' )
				),
				'id'                => 'alg_wc_user_products_user_visibility',
				'default'           => array(),
				'type'              => 'multiselect',
				'class'             => 'chosen_select',
				'options'           => $this->get_user_roles_options(),
			),
			array(
				'title'             => __( 'Product type', 'zili-user-products-for-woocommerce' ),
				'id'                => 'alg_wc_user_products_product_type',
				'default'           => 'simple',
				'type'              => 'select',
				'class'             => 'wc-enhanced-select',
				'options'           => array(
					'simple'   => __( 'Simple product', 'zili-user-products-for-woocommerce' ),
					'external' => __( 'External/Affiliate product', 'zili-user-products-for-woocommerce' ),
				),
			),
			array(
				'title'             => __( 'Product status', 'zili-user-products-for-woocommerce' ),
				'id'                => 'alg_wc_user_products_status',
				'default'           => 'draft',
				'type'              => 'select',
				'class'             => 'wc-enhanced-select',
				'options'           => get_post_statuses(),
			),
			array(
				'title'             => __( 'Require unique title', 'zili-user-products-for-woocommerce' ),
				'desc'              => __( 'Enable', 'zili-user-products-for-woocommerce' ),
				'id'                => 'alg_wc_user_products_require_unique_title',
				'default'           => 'no',
				'type'              => 'checkbox',
			),
			array(
				'title'             => __( 'Products tab', 'zili-user-products-for-woocommerce' ),
				'desc_tip'          => __( 'Adds "Products" tab to user\'s "My account" page.', 'zili-user-products-for-woocommerce' ),
				'desc'              => __( 'Add', 'zili-user-products-for-woocommerce' ),
				'id'                => 'alg_wc_user_products_add_to_my_account',
				'default'           => 'yes',
				'type'              => 'checkbox',
			),
			array(
				'desc'              => (
					__( 'Tab content', 'zili-user-products-for-woocommerce' ) . '<br>' .
					sprintf(
						/* Translators: %s: Shortcode name. */
						__( 'You should use %s shortcode here.', 'zili-user-products-for-woocommerce' ),
						'<code>[zili_wc_user_products_list]</code>'
					)
				),
				'id'                => 'alg_wc_user_products_my_account_tab_content',
				'default'           => '[zili_wc_user_products_list]',
				'type'              => 'textarea',
				'css'               => 'width:100%;height:100px;',
			),
			array(
				'type'              => 'sectionend',
				'id'                => 'alg_wc_user_products_general_options',
			),
		);
	}

}

endif;

return new Alg_WC_User_Products_Settings_General();
