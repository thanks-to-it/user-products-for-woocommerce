<?php
/**
 * ZILI User Products for WooCommerce - Settings
 *
 * @version 2.0.1
 * @since   1.0.0
 *
 * @author  Algoritmika Ltd
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Alg_WC_User_Products_Settings' ) ) :

class Alg_WC_User_Products_Settings extends WC_Settings_Page {

	/**
	 * Constructor.
	 *
	 * @version 2.0.1
	 * @since   1.0.0
	 */
	function __construct() {

		$this->id    = 'alg_wc_user_products';
		$this->label = __( 'ZILI User Products', 'zili-user-products-for-woocommerce' );
		parent::__construct();

		// Sections
		require_once plugin_dir_path( __FILE__ ) . 'class-alg-wc-user-products-settings-section.php';
		require_once plugin_dir_path( __FILE__ ) . 'class-alg-wc-user-products-settings-general.php';
		require_once plugin_dir_path( __FILE__ ) . 'class-alg-wc-user-products-settings-fields.php';
		require_once plugin_dir_path( __FILE__ ) . 'class-alg-wc-user-products-settings-messages.php';
		require_once plugin_dir_path( __FILE__ ) . 'class-alg-wc-user-products-settings-taxonomies.php';
		require_once plugin_dir_path( __FILE__ ) . 'class-alg-wc-user-products-settings-custom-fields.php';
		require_once plugin_dir_path( __FILE__ ) . 'class-alg-wc-user-products-settings-emails.php';

	}

	/**
	 * get_settings.
	 *
	 * @version 2.0.1
	 * @since   1.0.0
	 */
	function get_settings() {
		global $current_section;
		return array_merge(
			apply_filters( 'woocommerce_get_settings_' . $this->id . '_' . $current_section, array() ), // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound
			array(
				array(
					'title'     => __( 'Reset Settings', 'zili-user-products-for-woocommerce' ),
					'type'      => 'title',
					'id'        => $this->id . '_' . $current_section . '_reset_options',
				),
				array(
					'title'     => __( 'Reset section settings', 'zili-user-products-for-woocommerce' ),
					'desc'      => '<strong>' . __( 'Reset', 'zili-user-products-for-woocommerce' ) . '</strong>',
					'desc_tip'  => __( 'Check the box and save changes to reset.', 'zili-user-products-for-woocommerce' ),
					'id'        => $this->id . '_' . $current_section . '_reset',
					'default'   => 'no',
					'type'      => 'checkbox',
				),
				array(
					'type'      => 'sectionend',
					'id'        => $this->id . '_' . $current_section . '_reset_options',
				),
			)
		);
	}

	/**
	 * maybe_reset_settings.
	 *
	 * @version 2.0.0
	 * @since   1.0.0
	 */
	function maybe_reset_settings() {
		global $current_section;
		if ( 'yes' === get_option( $this->id . '_' . $current_section . '_reset', 'no' ) ) {
			foreach ( $this->get_settings() as $value ) {
				if ( isset( $value['id'] ) ) {
					$id = explode( '[', $value['id'] );
					delete_option( $id[0] );
				}
			}
			add_action( 'admin_notices', array( $this, 'admin_notice_settings_reset' ) );
		}
	}

	/**
	 * admin_notice_settings_reset.
	 *
	 * @version 2.0.1
	 * @since   2.0.0
	 */
	function admin_notice_settings_reset() {
		echo '<div class="notice notice-warning is-dismissible"><p><strong>' .
			esc_html__( 'Your settings have been reset.', 'zili-user-products-for-woocommerce' ) .
		'</strong></p></div>';
	}

	/**
	 * Save settings.
	 *
	 * @version 1.3.0
	 * @since   1.0.0
	 */
	function save() {
		parent::save();
		$this->maybe_reset_settings();
		do_action( 'alg_wc_user_products_after_save_settings' );
	}

}

endif;

return new Alg_WC_User_Products_Settings();
