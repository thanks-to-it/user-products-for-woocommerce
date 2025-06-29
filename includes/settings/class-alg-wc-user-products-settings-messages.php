<?php
/**
 * User Products for WooCommerce - Messages Section Settings
 *
 * @version 1.5.0
 * @since   1.5.0
 *
 * @author  Algoritmika Ltd
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Alg_WC_User_Products_Settings_Messages' ) ) :

class Alg_WC_User_Products_Settings_Messages extends Alg_WC_User_Products_Settings_Section {

	/**
	 * Constructor.
	 *
	 * @version 1.5.0
	 * @since   1.5.0
	 */
	function __construct() {
		$this->id   = 'messages';
		$this->desc = __( 'Messages', 'user-products-for-woocommerce' );
		parent::__construct();
	}

	/**
	 * get_settings.
	 *
	 * @version 1.5.0
	 * @since   1.5.0
	 */
	function get_settings() {
		return array(
			array(
				'title'    => __( 'Messages', 'user-products-for-woocommerce' ),
				'type'     => 'title',
				'desc'     => sprintf(
					/* Translators: %s: Placeholder name. */
					__( 'Replaced value: %s.', 'user-products-for-woocommerce' ),
					'<code>%product_title%</code>'
				),
				'id'       => 'alg_wc_user_products_messages_options',
			),
			array(
				'title'    => __( 'Product successfully added', 'user-products-for-woocommerce' ),
				'id'       => 'alg_wc_user_products_message_product_successfully_added',
				'default'  => __( '"%product_title%" successfully added!', 'user-products-for-woocommerce' ),
				'type'     => 'text',
				'css'      => 'width:100%;',
			),
			array(
				'title'    => __( 'Product successfully edited', 'user-products-for-woocommerce' ),
				'id'       => 'alg_wc_user_products_message_product_successfully_edited',
				'default'  => __( '"%product_title%" successfully edited!', 'user-products-for-woocommerce' ),
				'type'     => 'text',
				'css'      => 'width:100%;',
			),
			array(
				'type'     => 'sectionend',
				'id'       => 'alg_wc_user_products_messages_options',
			),
		);
	}

}

endif;

return new Alg_WC_User_Products_Settings_Messages();
