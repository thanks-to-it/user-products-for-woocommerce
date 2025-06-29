<?php
/**
 * User Products for WooCommerce - Emails Section Settings
 *
 * @version 1.5.0
 * @since   1.5.0
 *
 * @author  Algoritmika Ltd
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Alg_WC_User_Products_Settings_Emails' ) ) :

class Alg_WC_User_Products_Settings_Emails extends Alg_WC_User_Products_Settings_Section {

	/**
	 * Constructor.
	 *
	 * @version 1.5.0
	 * @since   1.5.0
	 */
	function __construct() {
		$this->id   = 'emails';
		$this->desc = __( 'Emails', 'user-products-for-woocommerce' );
		parent::__construct();
	}

	/**
	 * get_placeholders_desc.
	 *
	 * @version 1.5.0
	 * @since   1.5.0
	 */
	function get_placeholders_desc() {
		return sprintf(
			/* Translators: %s: Placeholder list. */
			__( 'Placeholders: %s.', 'user-products-for-woocommerce' ),
			implode( ', ', array(
				'<code>%product_title%</code>',
				'<code>%user_id%</code>',
			) )
		);
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
				'title'       => __( 'Emails', 'user-products-for-woocommerce' ),
				'type'        => 'title',
				'desc'        => __( 'Send an email when a new product is added.', 'user-products-for-woocommerce' ),
				'id'          => 'alg_wc_user_products_emails_options',
			),
			array(
				'title'       => __( 'Emails', 'user-products-for-woocommerce' ),
				'desc'        => '<strong>' . __( 'Enable', 'user-products-for-woocommerce' ) . '</strong>',
				'id'          => 'alg_wc_user_products_emails_enabled',
				'default'     => 'no',
				'type'        => 'checkbox',
			),
			array(
				'title'       => __( 'Send to', 'user-products-for-woocommerce' ),
				'id'          => 'alg_wc_user_products_emails_to',
				'default'     => '',
				'placeholder' => get_option( 'admin_email' ),
				'type'        => 'text',
			),
			array(
				'title'       => __( 'Subject', 'user-products-for-woocommerce' ),
				'desc'        => $this->get_placeholders_desc(),
				'id'          => 'alg_wc_user_products_emails_subject',
				'default'     => __( '"%product_title%" product added', 'user-products-for-woocommerce' ),
				'type'        => 'text',
				'css'         => 'width:100%;',
			),
			array(
				'title'       => __( 'Message', 'user-products-for-woocommerce' ),
				'desc'        => $this->get_placeholders_desc(),
				'id'          => 'alg_wc_user_products_emails_message',
				'default'     => (
					'<p>' . __( 'Product: "%product_title%"', 'user-products-for-woocommerce' ) . '</p>' . PHP_EOL .
					'<p>' . __( 'User ID: %user_id%', 'user-products-for-woocommerce' ) . '</p>' // phpcs:ignore WordPress.WP.I18n.MissingTranslatorsComment
				),
				'type'        => 'textarea',
				'css'         => 'width:100%;height:150px;',
			),
			array(
				'type'        => 'sectionend',
				'id'          => 'alg_wc_user_products_emails_options',
			),
		);
	}

}

endif;

return new Alg_WC_User_Products_Settings_Emails();
