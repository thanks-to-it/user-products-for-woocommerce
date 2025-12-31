<?php
/**
 * ZILI User Products for WooCommerce - Core Class
 *
 * @version 2.0.2
 * @since   1.0.0
 *
 * @author  Algoritmika Ltd
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Alg_WC_User_Products_Core' ) ) :

class Alg_WC_User_Products_Core {

	/**
	 * Constructor.
	 *
	 * @version 2.0.0
	 * @since   1.0.0
	 */
	function __construct() {

		// Shortcodes
		require_once plugin_dir_path( __FILE__ ) . 'class-alg-wc-user-products-shortcode-add-new.php';
		require_once plugin_dir_path( __FILE__ ) . 'class-alg-wc-user-products-shortcodes.php';

		// My Account
		if ( 'yes' === get_option( 'alg_wc_user_products_add_to_my_account', 'yes' ) ) {
			require_once plugin_dir_path( __FILE__ ) . 'class-alg-wc-user-products-my-account.php';
		}

	}

	/**
	 * verify_nonce.
	 *
	 * @version 2.0.2
	 * @since   2.0.2
	 *
	 * @todo    (v2.0.2) custom nonce name?
	 */
	function verify_nonce( $action ) {
		if ( ! isset( $_REQUEST['_wpnonce'] ) ) {
			return __( 'Nonce missing!', 'zili-user-products-for-woocommerce' );
		}
		if ( ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_REQUEST['_wpnonce'] ) ), $action ) ) {
			return __( 'Link expired!', 'zili-user-products-for-woocommerce' );
		}
		return true;
	}

	/**
	 * verify_action.
	 *
	 * @version 2.0.2
	 * @since   2.0.2
	 */
	function verify_action( $user_id, $product_id, $action ) {
		if ( true !== ( $verify_nonce_result = $this->verify_nonce( $action ) ) ) {
			return $verify_nonce_result;
		}
		if ( true !== ( $verify_author_result = $this->verify_author( $user_id, $product_id ) ) ) {
			return $verify_author_result;
		}
		return true;
	}

	/**
	 * verify_author.
	 *
	 * @version 2.0.2
	 * @since   2.0.2
	 */
	function verify_author( $user_id, $product_id ) {
		$post_author_id = get_post_field( 'post_author', $product_id );
		if ( $user_id != $post_author_id ) {
			return __( 'Wrong user ID!', 'zili-user-products-for-woocommerce' );
		}
		return true;
	}

	/**
	 * get_wc_message_html.
	 *
	 * @version 2.0.2
	 * @since   2.0.2
	 */
	function get_wc_message_html( $msg, $type = 'message', $is_single = true ) {
		$res = '<div class="woocommerce">';
		if ( 'message' === $type ) {
			$res .= '<div class="woocommerce-message">' .
				$msg .
			'</div>';
		} else { // 'error'
			$res .= '<ul class="woocommerce-error">';
			if ( $is_single ) {
				$res .= '<li>';
			}
			$res .= $msg;
			if ( $is_single ) {
				$res .= '</li>';
			}
			$res .= '</ul>';
		}
		$res .= '</div>';
		return $res;
	}

	/**
	 * get_allowed_html.
	 *
	 * @version 2.0.2
	 * @since   2.0.2
	 *
	 * @todo    (v2.0.2) recheck?
	 */
	function get_allowed_html() {
		$allowed_html = array(
			'form' => array(
				'action'         => true,
				'accept'         => true,
				'accept-charset' => true,
				'enctype'        => true,
				'method'         => true,
				'name'           => true,
				'target'         => true,
				'class'          => true,
			),
			'input' => array(
				'type'     => true,
				'id'       => true,
				'name'     => true,
				'class'    => true,
				'style'    => true,
				'value'    => true,
				'onchange' => true,
				'checked'  => true,
				'required' => true,
				'min'      => true,
				'max'      => true,
				'step'     => true,
				'accept'   => true,
			),
			'select' => array(
				'id'       => true,
				'name'     => true,
				'class'    => true,
				'style'    => true,
				'onchange' => true,
				'multiple' => true,
				'required' => true,
			),
			'option' => array(
				'value'    => true,
				'selected' => true,
			),
			'a' => array(
				'href'    => true,
				'target'  => true,
				'class'   => true,
				'onclick' => true,
			),
		);
		return array_merge(
			wp_kses_allowed_html( 'post' ),
			$allowed_html
		);
	}

	/**
	 * get_table_html.
	 *
	 * @version 1.3.0
	 * @since   1.0.0
	 *
	 * @todo    (dev) move this to `alg-wc-user-products-functions.php` file?
	 */
	function get_table_html( $data, $args = array() ) {

		$defaults = array(
			'table_class'        => '',
			'table_style'        => '',
			'row_styles'         => '',
			'table_heading_type' => 'horizontal',
			'column_classes'     => array(),
			'column_styles'      => array(),
		);
		$args = array_merge( $defaults, $args );

		$html  = '';
		$html .= '<table' .
			( '' == $args['table_class'] ? '' : ' class="' . $args['table_class'] . '"' ) .
			( '' == $args['table_style'] ? '' : ' style="' . $args['table_style'] . '"' ) .
		'>';
		$html .= '<tbody>';
		foreach ( $data as $row_number => $row ) {
			$html .= '<tr' .
				( '' == $args['row_styles'] ? '' : ' style="' . $args['row_styles']  . '"' ) .
			'>';
			foreach ( $row as $column_number => $value ) {
				$th_or_td = (
					(
						( 0 === $row_number && 'horizontal' === $args['table_heading_type'] ) ||
						( 0 === $column_number && 'vertical' === $args['table_heading_type'] )
					) ?
					'th' :
					'td'
				);
				$column_class = (
					! empty( $args['column_classes'][ $column_number ] ) ?
					' class="' . $args['column_classes'][ $column_number ] . '"' :
					''
				);
				$column_style = (
					! empty( $args['column_styles'][ $column_number ] ) ?
					' style="' . $args['column_styles'][ $column_number ] . '"' :
					''
				);
				$html .= '<' . $th_or_td . $column_class . $column_style . '>';
				$html .= $value;
				$html .= '</' . $th_or_td . '>';
			}
			$html .= '</tr>';
		}
		$html .= '</tbody>';
		$html .= '</table>';

		return $html;
	}

}

endif;

return new Alg_WC_User_Products_Core();
