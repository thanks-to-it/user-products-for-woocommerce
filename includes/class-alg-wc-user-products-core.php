<?php
/**
 * User Products for WooCommerce - Core Class
 *
 * @version 2.0.0
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
