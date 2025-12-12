<?php
/**
 * ZILI User Products for WooCommerce - Shortcodes Class
 *
 * @version 2.0.1
 * @since   1.2.0
 *
 * @author  Algoritmika Ltd
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Alg_WC_User_Products_Shortcodes' ) ) :

class Alg_WC_User_Products_Shortcodes {

	/**
	 * Constructor.
	 *
	 * @version 1.2.0
	 * @since   1.2.0
	 *
	 * @todo    (dev) maybe add `alg_` prefix to all shortcodes?
	 */
	function __construct() {
		add_shortcode( 'wc_user_products_list', array( $this, 'wc_user_products_list' ) );
	}

	/**
	 * wc_user_products_list.
	 *
	 * @version 2.0.1
	 * @since   1.2.0
	 *
	 * @todo    (v2.0.0) escape output?
	 * @todo    (fix) recheck all `explode()` for empty strings!
	 * @todo    (feature) `function`: optional function params
	 * @todo    (feature) `column_titles`: add "do not use default titles" option
	 * @todo    (feature) `columns`: add more columns, e.g., "Price", etc.
	 * @todo    (dev) `columns`: `product_nr` to `nr`?
	 * @todo    (dev) `columns`: replace `status` with `status_label` in the default value?
	 * @todo    (dev) rename to `[wc_user_products_list_all]`?
	 * @todo    (dev) use `wc_get_products()`?
	 * @todo    (feature) add `column_functions` param - to format the result, e.g., for `meta=_price` we could use `wc_price`?
	 */
	function wc_user_products_list( $atts, $content = '' ) {

		// Default atts
		$default_atts = array(
			'user_id'        => 0,
			'columns'        => 'thumbnail,status,title,actions',
			'column_titles'  => '',
			'column_styles'  => '',
			'column_classes' => '',
			'column_sep'     => ',',
			'row_styles'     => '',
			'table_class'    => 'shop_table shop_table_responsive my_account_orders',
			'table_style'    => '',
			'thumbnail_size' => 'post-thumbnail',
			'actions'        => '%edit% %delete%',
			'template'       => '%products_table%',
		);
		$atts = shortcode_atts( $default_atts, $atts, 'wc_user_products_list' );

		// Thumbnail size
		$thumbnail_size_parts = array_map( 'trim', explode( ',', $atts['thumbnail_size'] ) );
		$thumbnail_size       = (
			2 == count( $thumbnail_size_parts ) ?
			$thumbnail_size_parts :
			$atts['thumbnail_size']
		);

		// User ID
		$user_id = ( 0 == $atts['user_id'] ? get_current_user_id() : $atts['user_id'] );
		if ( 0 == $user_id ) {
			return false;
		}

		// Execute actions
		$output = '';
		if ( isset( $_GET['alg_wc_delete_product'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
			$product_id     = intval( $_GET['alg_wc_delete_product'] ); // phpcs:ignore WordPress.Security.NonceVerification.Recommended
			$post_author_id = get_post_field( 'post_author', $product_id );
			if ( $user_id != $post_author_id ) {
				$output .= '<p>' .
					__( 'Wrong user ID!', 'zili-user-products-for-woocommerce' ) .
				'</p>';
			} else {
				wp_delete_post( $product_id, true );
			}
		}
		if ( isset( $_GET['alg_wc_edit_product'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
			$product_id     = intval( $_GET['alg_wc_edit_product'] ); // phpcs:ignore WordPress.Security.NonceVerification.Recommended
			$post_author_id = get_post_field( 'post_author', $product_id );
			if ( $user_id != $post_author_id ) {
				$output .= '<p>' .
					__( 'Wrong user ID!', 'zili-user-products-for-woocommerce' ) .
				'</p>';
			} else {
				$output .= do_shortcode(
					'[wc_user_products_add_new product_id="' . $product_id . '"]'
				);
			}
		}

		// Products
		$products   = array();
		$offset     = 0;
		$block_size = 1024;
		while ( true ) {
			$args = array(
				'post_type'      => 'product',
				'post_status'    => 'any',
				'posts_per_page' => $block_size,
				'offset'         => $offset,
				'orderby'        => 'date',
				'order'          => 'DESC',
				'author'         => $user_id,
				'fields'         => 'ids',
			);
			$loop = new WP_Query( $args );
			if ( ! $loop->have_posts() ) {
				break;
			}
			foreach ( $loop->posts as $post_id ) {
				$products[ $post_id ] = array(
					'title'  => get_the_title( $post_id ),
					'status' => get_post_status( $post_id ),
				);
			}
			$offset += $block_size;
		}

		// Final products list table
		if ( 0 != count( $products ) ) {

			$table_data = array();
			$columns    = array_map( 'trim', explode( $atts['column_sep'], $atts['columns'] ) );

			// Header
			$header = array();
			$default_column_titles = array(
				'product_nr'        => '#',
				'thumbnail'         => '',
				'thumbnail_no_link' => '',
				'status'            => __( 'Status', 'zili-user-products-for-woocommerce' ),
				'status_label'      => __( 'Status', 'zili-user-products-for-woocommerce' ),
				'title'             => __( 'Title', 'zili-user-products-for-woocommerce' ),
				'title_no_link'     => __( 'Title', 'zili-user-products-for-woocommerce' ),
				'actions'           => __( 'Actions', 'zili-user-products-for-woocommerce' ),
				'categories'        => __( 'Categories', 'zili-user-products-for-woocommerce' ),
				'tags'              => __( 'Tags', 'zili-user-products-for-woocommerce' ),
			);
			$column_titles = (
				'' !== $atts['column_titles'] ?
				array_map( 'trim', explode( $atts['column_sep'], $atts['column_titles'] ) ) :
				array()
			);
			foreach ( $columns as $i => $column ) {
				if ( isset( $column_titles[ $i ] ) ) {
					$header[]     = $column_titles[ $i ];
				} else {
					$column_parts = array_map( 'trim', explode( '=', $column ) );
					$column       = $column_parts[0];
					$column_param = ( isset( $column_parts[1] ) ? $column_parts[1] : false );
					$header[]     = (
						false !== $column_param ?
						"<code>{$column_param}</code>" :
						(
							isset( $default_column_titles[ $column ] ) ?
							$default_column_titles[ $column ] :
							$column
						)
					);
				}
			}
			$table_data[] = $header;

			// Product rows
			$total_products = 0;
			foreach ( $products as $_product_id => $_product_data ) {
				$total_products++;
				$row = array();
				foreach ( $columns as $column ) {
					$column_parts = array_map( 'trim', explode( '=', $column ) );
					$column       = $column_parts[0];
					$column_param = ( isset( $column_parts[1] ) ? $column_parts[1] : false );
					switch ( $column ) {

						case 'product_nr':
							$row[] = $total_products;
							break;

						case 'thumbnail':
							$row[] = (
								'' != ( $thumbnail = get_the_post_thumbnail( $_product_id, $thumbnail_size ) ) ?
								'<a target="_blank" href="' . get_the_permalink( $_product_id ) . '">' .
									$thumbnail .
								'</a>' :
								''
							);
							break;

						case 'thumbnail_no_link':
							$row[] = get_the_post_thumbnail( $_product_id, $thumbnail_size );
							break;

						case 'status':
							$row[] = '<code>'. $_product_data['status'] . '</code>';
							break;

						case 'status_label':
							$status_object = get_post_status_object( $_product_data['status'] );
							$row[] = $status_object->label;
							break;

						case 'title':
							$row[] = '<a target="_blank" href="' . get_the_permalink( $_product_id ) . '">' .
								$_product_data['title'] .
							'</a>';
							break;

						case 'title_no_link':
							$row[] = $_product_data['title'];
							break;

						case 'actions':
							$edit_url   = add_query_arg(
								'alg_wc_edit_product',
								$_product_id,
								remove_query_arg(
									array(
										'alg_wc_edit_product_image_delete',
										'alg_wc_delete_product',
									)
								)
							);
							$delete_url = add_query_arg(
								'alg_wc_delete_product',
								$_product_id,
								remove_query_arg(
									array(
										'alg_wc_edit_product_image_delete',
										'alg_wc_edit_product',
									)
								)
							);
							$view_url   = get_the_permalink( $_product_id );
							$actions    = array(
								'%edit%'   => (
									'<a' .
										' class="button"' .
										' href="' . $edit_url . '"' .
									'>' .
										__( 'Edit', 'zili-user-products-for-woocommerce' ) .
									'</a>'
								),
								'%delete%' => (
									'<a' .
										' class="button"' .
										' href="' . $delete_url . '"' .
										' onclick="return confirm(\'' .
											__( 'Are you sure?', 'zili-user-products-for-woocommerce' ) .
										'\')"' .
									'>' .
										__( 'Delete', 'zili-user-products-for-woocommerce' ) .
									'</a>'
								),
								'%view%'   => (
									'<a' .
										' class="button"' .
										' target="_blank"' .
										' href="' . $view_url . '"' .
									'>' .
										__( 'View', 'zili-user-products-for-woocommerce' ) .
									'</a>'
								),
							);
							$row[] = str_replace(
								array_keys( $actions ),
								$actions,
								$atts['actions']
							);
							break;

						case 'categories':
							$row[] = wc_get_product_category_list( $_product_id ) ;
							break;

						case 'tags':
							$row[] = wc_get_product_tag_list( $_product_id );
							break;

						case 'meta':
							$row[] = (
								false !== $column_param ?
								get_post_meta( $_product_id, $column_param, true ) :
								''
							);
							break;

						case 'function':
							$row[] = (
								(
									false !== $column_param &&
									( $product = wc_get_product( $_product_id ) ) &&
									is_callable( array( $product, $column_param ) )
								) ?
								$product->$column_param() :
								''
							);
							break;

						case 'taxonomy':
							$row[] = (
								false !== $column_param ?
								get_the_term_list( $_product_id, $column_param, '', ', ', '' ) :
								''
							);
							break;

					}
				}
				$table_data[] = $row;
			}

			$column_styles  = array_map(
				'trim',
				explode( $atts['column_sep'], $atts['column_styles'] )
			);
			$column_classes = array_map(
				'trim',
				explode( $atts['column_sep'], $atts['column_classes'] )
			);
			$products_table = alg_wc_user_products()->core->get_table_html(
				$table_data, array(
					'table_class'    => $atts['table_class'],
					'table_style'    => $atts['table_style'],
					'row_styles'     => $atts['row_styles'],
					'column_styles'  => $column_styles,
					'column_classes' => $column_classes,
				)
			);

			$output .= str_replace(
				array( '%products_table%', '%total_products%' ),
				array( $products_table, $total_products ),
				$atts['template']
			);

		} else {
			$output .= '<p><em>' .
				__( 'You have no products yet.', 'zili-user-products-for-woocommerce' ) .
			'</em></p>';
		}

		return $output;
	}

}

endif;

return new Alg_WC_User_Products_Shortcodes();
