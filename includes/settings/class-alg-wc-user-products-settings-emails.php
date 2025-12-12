<?php
/**
 * ZILI User Products for WooCommerce - Emails Section Settings
 *
 * @version 2.0.1
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
	 * @version 2.0.1
	 * @since   1.5.0
	 */
	function __construct() {
		$this->id   = 'emails';
		$this->desc = __( 'Emails', 'zili-user-products-for-woocommerce' );
		parent::__construct();
	}

	/**
	 * get_placeholders_desc.
	 *
	 * @version 2.0.1
	 * @since   1.5.0
	 */
	function get_placeholders_desc() {
		return sprintf(
			/* Translators: %s: Placeholder list. */
			__( 'Placeholders: %s.', 'zili-user-products-for-woocommerce' ),
			implode( ', ', array(
				'<code>%product_title%</code>',
				'<code>%user_id%</code>',
			) )
		);
	}

	/**
	 * get_settings.
	 *
	 * @version 2.0.1
	 * @since   1.5.0
	 */
	function get_settings() {
		return array(
			array(
				'title'       => __( 'Emails', 'zili-user-products-for-woocommerce' ),
				'type'        => 'title',
				'desc'        => __( 'Send an email when a new product is added.', 'zili-user-products-for-woocommerce' ),
				'id'          => 'alg_wc_user_products_emails_options',
			),
			array(
				'title'       => __( 'Emails', 'zili-user-products-for-woocommerce' ),
				'desc'        => '<strong>' . __( 'Enable', 'zili-user-products-for-woocommerce' ) . '</strong>',
				'id'          => 'alg_wc_user_products_emails_enabled',
				'default'     => 'no',
				'type'        => 'checkbox',
			),
			array(
				'title'       => __( 'Send to', 'zili-user-products-for-woocommerce' ),
				'id'          => 'alg_wc_user_products_emails_to',
				'default'     => '',
				'placeholder' => get_option( 'admin_email' ),
				'type'        => 'text',
			),
			array(
				'title'       => __( 'Subject', 'zili-user-products-for-woocommerce' ),
				'desc'        => $this->get_placeholders_desc(),
				'id'          => 'alg_wc_user_products_emails_subject',
				'default'     => __( '"%product_title%" product added', 'zili-user-products-for-woocommerce' ),
				'type'        => 'text',
				'css'         => 'width:100%;',
			),
			array(
				'title'       => __( 'Message', 'zili-user-products-for-woocommerce' ),
				'desc'        => $this->get_placeholders_desc(),
				'id'          => 'alg_wc_user_products_emails_message',
				'default'     => (
					'<p>' . __( 'Product: "%product_title%"', 'zili-user-products-for-woocommerce' ) . '</p>' . PHP_EOL .
					'<p>' . __( 'User ID: %user_id%', 'zili-user-products-for-woocommerce' ) . '</p>' // phpcs:ignore WordPress.WP.I18n.MissingTranslatorsComment
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
