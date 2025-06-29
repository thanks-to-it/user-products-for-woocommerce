<?php
/**
 * User Products for WooCommerce - Shortcode Class
 *
 * @version 2.0.0
 * @since   1.0.0
 *
 * @author  Algoritmika Ltd
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Alg_WC_User_Products_Shortcode' ) ) :

class Alg_WC_User_Products_Shortcode {

	/**
	 * the_atts.
	 *
	 * @version 1.5.0
	 * @since   1.5.0
	 */
	public $the_atts;

	/**
	 * the_product.
	 *
	 * @version 1.5.0
	 * @since   1.5.0
	 */
	public $the_product;

	/**
	 * Constructor.
	 *
	 * @version 2.0.0
	 * @since   1.0.0
	 *
	 * @todo    (dev) recheck if shortcode atts can be used instead of `get_option()`?
	 * @todo    (dev) code refactoring: recheck `if ( '' === $this->the_atts[ 'custom_taxonomy_' . $i . '_id' ] ) { ... }`
	 * @todo    (dev) code refactoring: recheck `if ( '' === $this->the_atts[ 'custom_field_' . $i . '_meta_key' ] ) { ... }`
	 * @todo    (dev) load all options at once (i.e., no `get_option()` calls outside of the constructor)
	 * @todo    (dev) `'product_id' => ( isset( $_GET['alg_wc_edit_product'] ) ? $_GET['alg_wc_edit_product'] : 0 ),`
	 * @todo    (dev) refill image on not validated (or after successful addition)
	 * @todo    (feature) more messages options
	 * @todo    (feature) more styling options
	 * @todo    (dev) code refactoring?
	 */
	function __construct() {

		$fields_options = array();
		$fields_options['enabled']  = get_option( 'alg_wc_user_products_fields_enabled',  array() );
		$fields_options['required'] = get_option( 'alg_wc_user_products_fields_required', array() );

		$this->the_atts = array(
			'product_id'              => 0,
			'post_status'             => get_option( 'alg_wc_user_products_status', 'draft' ),
			'visibility'              => get_option( 'alg_wc_user_products_user_visibility', array() ),

			'desc_enabled'            => ( $fields_options['enabled']['desc']           ?? 'no' ),
			'short_desc_enabled'      => ( $fields_options['enabled']['short_desc']     ?? 'no' ),
			'regular_price_enabled'   => ( $fields_options['enabled']['regular_price']  ?? 'no' ),
			'sale_price_enabled'      => ( $fields_options['enabled']['sale_price']     ?? 'no' ),
			'external_url_enabled'    => ( $fields_options['enabled']['external_url']   ?? 'no' ),
			'cats_enabled'            => ( $fields_options['enabled']['cats']           ?? 'no' ),
			'tags_enabled'            => ( $fields_options['enabled']['tags']           ?? 'no' ),
			'image_enabled'           => ( $fields_options['enabled']['image']          ?? 'no' ),

			'desc_required'           => ( $fields_options['required']['desc']          ?? 'no' ),
			'short_desc_required'     => ( $fields_options['required']['short_desc']    ?? 'no' ),
			'regular_price_required'  => ( $fields_options['required']['regular_price'] ?? 'no' ),
			'sale_price_required'     => ( $fields_options['required']['sale_price']    ?? 'no' ),
			'external_url_required'   => ( $fields_options['required']['external_url']  ?? 'no' ),
			'cats_required'           => ( $fields_options['required']['cats']          ?? 'no' ),
			'tags_required'           => ( $fields_options['required']['tags']          ?? 'no' ),
			'image_required'          => ( $fields_options['required']['image']         ?? 'no' ),
		);

		if ( 'external' !== get_option( 'alg_wc_user_products_product_type', 'simple' ) ) {
			$this->the_atts['external_url_enabled']  = 'no';
			$this->the_atts['external_url_required'] = 'no';
		}

		// Custom taxonomies
		$this->the_atts['custom_taxonomies_total'] = get_option(
			'alg_wc_user_products_custom_taxonomies_total',
			1
		);
		$custom_taxonomies['enabled']  = get_option( 'alg_wc_user_products_custom_taxonomy_enabled', array() );
		$custom_taxonomies['required'] = get_option( 'alg_wc_user_products_custom_taxonomy_required', array() );
		$custom_taxonomies['id']       = get_option( 'alg_wc_user_products_custom_taxonomy_id', array() );
		$custom_taxonomies['title']    = get_option( 'alg_wc_user_products_custom_taxonomy_title', array() );
		for ( $i = 1; $i <= $this->the_atts['custom_taxonomies_total']; $i++ ) {
			$this->the_atts[ 'custom_taxonomy_' . $i . '_enabled' ]  = ( $custom_taxonomies['enabled'][ $i ]  ?? 'no' );
			$this->the_atts[ 'custom_taxonomy_' . $i . '_required' ] = ( $custom_taxonomies['required'][ $i ] ?? 'no' );
			$this->the_atts[ 'custom_taxonomy_' . $i . '_id' ]       = ( $custom_taxonomies['id'][ $i ]       ?? '' );
			$this->the_atts[ 'custom_taxonomy_' . $i . '_title' ]    = ( $custom_taxonomies['title'][ $i ]    ?? '' );
			if ( '' === $this->the_atts[ 'custom_taxonomy_' . $i . '_id' ] ) {
				$this->the_atts[ 'custom_taxonomy_' . $i . '_enabled' ] = 'no';
			}
		}

		// Custom fields
		$this->the_atts['custom_fields_total'] = get_option(
			'alg_wc_user_products_custom_fields_total',
			1
		);
		$custom_fields['enabled']  = get_option( 'alg_wc_user_products_custom_field_enabled',  array() );
		$custom_fields['required'] = get_option( 'alg_wc_user_products_custom_field_required', array() );
		$custom_fields['meta_key'] = get_option( 'alg_wc_user_products_custom_field_meta_key', array() ); // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_key
		$custom_fields['title']    = get_option( 'alg_wc_user_products_custom_field_title',    array() );
		for ( $i = 1; $i <= $this->the_atts['custom_fields_total']; $i++ ) {
			$this->the_atts[ 'custom_field_' . $i . '_enabled' ]  = ( $custom_fields['enabled'][ $i ]  ?? 'no' );
			$this->the_atts[ 'custom_field_' . $i . '_required' ] = ( $custom_fields['required'][ $i ] ?? 'no' );
			$this->the_atts[ 'custom_field_' . $i . '_meta_key' ] = ( $custom_fields['meta_key'][ $i ] ?? '' );
			$this->the_atts[ 'custom_field_' . $i . '_title' ]    = ( $custom_fields['title'][ $i ]    ?? '' );
			if ( '' === $this->the_atts[ 'custom_field_' . $i . '_meta_key' ] ) {
				$this->the_atts[ 'custom_field_' . $i . '_enabled' ] = 'no';
			}
		}

		// Adding the shortcode
		add_shortcode( 'wc_user_products_add_new', array( $this, 'wc_user_products_add_new' ) );
	}

	/**
	 * wc_add_new_product.
	 *
	 * @version 2.0.0
	 * @since   1.0.0
	 *
	 * @todo    (v2.0.0) use `new WC_Product()`
	 * @todo    (v2.0.0) replace `move_uploaded_file()`
	 * @todo    (feature) Image gallery // `<input type="file" multiple>` or separate `<input type="file">` for each file // `update_post_meta( $product_id, '_product_image_gallery', implode( ',', $attach_ids ) );`
	 */
	function wc_add_new_product( $args, $shortcode_atts ) {

		$product_post = array(
			'post_title'    => $args['title'],
			'post_content'  => $args['desc'],
			'post_excerpt'  => $args['short_desc'],
			'post_type'     => 'product',
			'post_status'   => 'draft',
		);

		if ( 0 == $shortcode_atts['product_id'] ) {
			$product_id = wp_insert_post( $product_post );
		} else {
			$product_id = $shortcode_atts['product_id'];
			wp_update_post( array_merge( array( 'ID' => $product_id ), $product_post ) );
		}

		// Insert the post into the database
		if ( 0 != $product_id ) {

			wp_set_object_terms(
				$product_id,
				get_option( 'alg_wc_user_products_product_type', 'simple' ),
				'product_type'
			);
			wp_set_object_terms(
				$product_id,
				$args['cats'],
				'product_cat'
			);
			wp_set_object_terms(
				$product_id,
				$args['tags'],
				'product_tag'
			);

			for ( $i = 1; $i <= $this->the_atts['custom_taxonomies_total']; $i++ ) {
				if (
					'yes' === $this->the_atts[ 'custom_taxonomy_' . $i . '_enabled' ] &&
					'' != $this->the_atts[ 'custom_taxonomy_' . $i . '_id' ]
				) {
					wp_set_object_terms(
						$product_id,
						$args[ 'custom_taxonomy_' . $i ],
						$this->the_atts[ 'custom_taxonomy_' . $i . '_id' ]
					);
				}
			}

			for ( $i = 1; $i <= $this->the_atts['custom_fields_total']; $i++ ) {
				if (
					'yes' === $this->the_atts[ 'custom_field_' . $i . '_enabled' ] &&
					'' != $this->the_atts[ 'custom_field_' . $i . '_meta_key' ]
				) {
					update_post_meta(
						$product_id,
						$this->the_atts[ 'custom_field_' . $i . '_meta_key' ],
						$args[ 'custom_field_' . $i ]
					);
				}
			}

			update_post_meta( $product_id, '_regular_price', $args['regular_price'] );
			update_post_meta( $product_id, '_sale_price',    $args['sale_price'] );
			update_post_meta( $product_id, '_price',         ( '' == $args['sale_price'] ? $args['regular_price'] : $args['sale_price'] ) );
			update_post_meta( $product_id, '_visibility',    'visible' );
			update_post_meta( $product_id, '_stock_status',  'instock' );

			if ( 'external' === get_option( 'alg_wc_user_products_product_type', 'simple' ) ) {
				update_post_meta( $product_id, '_product_url', $args['external_url'] );
			}

			// Image
			if ( '' != $args['image'] && '' != $args['image']['tmp_name'] ) {
				$upload_dir  = wp_upload_dir();
				$filename    = $args['image']['name'];
				$file        = (
					wp_mkdir_p( $upload_dir['path'] ) ?
					$upload_dir['path'] :
					$upload_dir['basedir']
				);
				$file       .= '/' . $filename;

				move_uploaded_file( $args['image']['tmp_name'], $file );

				$wp_filetype = wp_check_filetype( $filename, null );
				$attachment  = array(
					'post_mime_type' => $wp_filetype['type'],
					'post_title'     => sanitize_file_name( $filename ),
					'post_content'   => '',
					'post_status'    => 'inherit'
				);
				$attach_id   = wp_insert_attachment( $attachment, $file, $product_id );
				require_once ABSPATH . 'wp-admin/includes/image.php';
				$attach_data = wp_generate_attachment_metadata( $attach_id, $file );
				wp_update_attachment_metadata( $attach_id, $attach_data );

				set_post_thumbnail( $product_id, $attach_id );
			}

			wp_update_post(
				array(
					'ID'          => $product_id,
					'post_status' => $shortcode_atts['post_status'],
				)
			);
		}

		return $product_id;
	}

	/**
	 * validate_args.
	 *
	 * @version 2.0.0
	 * @since   1.0.0
	 */
	function validate_args( $args, $shortcode_atts ) {
		$errors = '';
		if ( '' == $args['title'] ) {
			$errors .= '<li>' .
				__( 'Title is required!', 'user-products-for-woocommerce' ) .
			'</li>';
		}

		if (
			'yes' === get_option( 'alg_wc_user_products_require_unique_title', 'no' ) &&
			0 == $shortcode_atts['product_id']
		) {
			if ( ! function_exists( 'post_exists' ) ) {
				require_once ABSPATH . 'wp-admin/includes/post.php';
			}
			if ( post_exists( $args['title'] ) ) {
				$errors .= '<li>' .
					__( 'Product exists!', 'user-products-for-woocommerce' ) .
				'</li>';
			}
		}

		$fields = array(
			'desc'          => __( 'Description', 'user-products-for-woocommerce' ),
			'short_desc'    => __( 'Short Description', 'user-products-for-woocommerce' ),
			'image'         => __( 'Image', 'user-products-for-woocommerce' ),
			'regular_price' => __( 'Regular Price', 'user-products-for-woocommerce' ),
			'sale_price'    => __( 'Sale Price', 'user-products-for-woocommerce' ),
			'external_url'  => __( 'Product URL', 'user-products-for-woocommerce' ),
			'cats'          => __( 'Categories', 'user-products-for-woocommerce' ),
			'tags'          => __( 'Tags', 'user-products-for-woocommerce' ),
		);
		for ( $i = 1; $i <= $this->the_atts['custom_taxonomies_total']; $i++ ) {
			$fields[ 'custom_taxonomy_' . $i ] = $this->the_atts[ 'custom_taxonomy_' . $i . '_title' ];
		}
		for ( $i = 1; $i <= $this->the_atts['custom_fields_total']; $i++ ) {
			$fields[ 'custom_field_' . $i ] = $this->the_atts[ 'custom_field_' . $i . '_title' ];
		}
		foreach ( $fields as $field_id => $field_desc ) {
			if (
				'yes' === $shortcode_atts[ $field_id . '_enabled' ] &&
				'yes' === $shortcode_atts[ $field_id . '_required' ]
			) {
				$is_missing = false;
				if (
					'image' === $field_id &&
					(
						'' == $args[ $field_id ] ||
						! isset( $args[ $field_id ]['tmp_name'] ) ||
						'' == $args[ $field_id ]['tmp_name']
					)
				) {
					if ( empty( $args['_image_uploaded'] ) ) {
						$is_missing = true;
					}
				} elseif ( empty( $args[ $field_id ] ) ) {
					$is_missing = true;
				}
				if ( $is_missing ) {
					$errors .= '<li>' . sprintf(
						/* Translators: %s: Field title. */
						__( '%s is required!', 'user-products-for-woocommerce' ),
						$field_desc
					) . '</li>';
				}
			}
		}

		if ( $args['sale_price'] > $args['regular_price'] ) {
			$errors .= '<li>' .
				__( 'Sale price must be less than the regular price!', 'user-products-for-woocommerce' ) .
			'</li>';
		}
		return ( '' === $errors ? true : $errors );
	}

	/**
	 * maybe_add_taxonomy_field.
	 *
	 * @version 2.0.0
	 * @since   1.0.0
	 *
	 * @todo    (dev) params as an array
	 */
	function maybe_add_taxonomy_field( $atts, $args, $option_id, $taxonomy_id, $title, $input_style, $required_mark_html_template, $table_data ) {
		if ( 'yes' === $atts[ $option_id . '_enabled' ] ) {
			$product_taxonomies = get_terms( array(
				'taxonomy'   => $taxonomy_id,
				'orderby'    => 'name',
				'hide_empty' => false,
			) );
			if ( is_wp_error( $product_taxonomies ) ) {
				return $table_data;
			}
			$required_html = (
				'yes' === $atts[ $option_id . '_required' ] ?
				' required' :
				''
			);
			$required_mark_html = (
				'yes' === $atts[ $option_id . '_required' ] ?
				$required_mark_html_template :
				''
			);
			$current_product_taxonomies = (
				0 != $atts['product_id'] ?
				get_the_terms( $atts['product_id'], $taxonomy_id ) :
				$args[ $option_id ]
			);
			$product_taxonomies_as_select_options = '';
			foreach ( $product_taxonomies as $product_taxonomy ) {
				$selected = '';
				if ( ! empty( $current_product_taxonomies ) ) {
					foreach ( $current_product_taxonomies as $current_product_taxonomy ) {
						if ( is_object( $current_product_taxonomy ) ) {
							$current_product_taxonomy = $current_product_taxonomy->slug;
						}
						$selected .= selected(
							$current_product_taxonomy,
							$product_taxonomy->slug,
							false
						);
					}
				}
				$product_taxonomies_as_select_options .= (
					'<option value="' . $product_taxonomy->slug . '" ' . $selected . '>' .
						$product_taxonomy->name .
					'</option>'
				);
			}
			$table_data[] = array(
				'<label for="alg_wc_add_new_product_' . $option_id . '">' .
					$title . $required_mark_html .
				'</label>',
				'<select' .
					$required_html .
					' multiple' .
					' style="' . $input_style . '"' .
					' id="alg_wc_add_new_product_' . $option_id . '"' .
					' name="alg_wc_add_new_product_' . $option_id . '[]"' .
				'>' .
					$product_taxonomies_as_select_options .
				'</select>'
			);
		}
		return $table_data;
	}

	/**
	 * maybe_send_email.
	 *
	 * @version 1.5.0
	 * @since   1.5.0
	 *
	 * @todo    (dev) add more placeholders, e.g., `%product_url%`
	 */
	function maybe_send_email( $args ) {

		// Check if enabled
		if ( 'yes' !== get_option( 'alg_wc_user_products_emails_enabled', 'no' ) ) {
			return;
		}

		// Options
		$to      = get_option( 'alg_wc_user_products_emails_to', '' );
		$subject = get_option(
			'alg_wc_user_products_emails_subject',
			__( '"%product_title%" product added', 'user-products-for-woocommerce' )
		);
		$message = get_option(
			'alg_wc_user_products_emails_message',
			(
				'<p>' . __( 'Product: "%product_title%"', 'user-products-for-woocommerce' ) . '</p>' . PHP_EOL .
				'<p>' . __( 'User ID: %user_id%', 'user-products-for-woocommerce' ) . '</p>' // phpcs:ignore WordPress.WP.I18n.MissingTranslatorsComment
			)
		);

		// Placeholders
		$placeholders = array(
			'%product_title%' => $args['title'],
			'%user_id%'       => get_current_user_id(),
		);

		// Prepare params
		$to      = ( '' !== $to ? $to : get_option( 'admin_email' ) );
		$subject = str_replace( array_keys( $placeholders ), $placeholders, $subject );
		$message = (
			$this->get_wc_email_part( 'header', $subject ) .
				str_replace( array_keys( $placeholders ), $placeholders, $message ) .
			$this->process_email_footer_placeholders( $this->get_wc_email_part( 'footer' ) )
		);

		// Send email
		wc_mail( $to, $subject, $message );

	}

	/**
	 * get_wc_email_part.
	 *
	 * @version 1.5.0
	 * @since   1.5.0
	 */
	function get_wc_email_part( $part, $email_heading = '' ) {
		ob_start();
		switch ( $part ) {
			case 'header':
				wc_get_template(
					'emails/email-header.php',
					array( 'email_heading' => $email_heading )
				);
				break;
			case 'footer':
				wc_get_template(
					'emails/email-footer.php'
				);
				break;
		}
		return ob_get_clean();
	}

	/**
	 * process_email_footer_placeholders.
	 *
	 * @version 1.5.0
	 * @since   1.5.0
	 */
	function process_email_footer_placeholders( $string ) {
		$domain = wp_parse_url( home_url(), PHP_URL_HOST );

		return str_replace(
			array(
				'{site_title}',
				'{site_address}',
				'{site_url}',
				'{woocommerce}',
				'{WooCommerce}',
			),
			array(
				wp_specialchars_decode( get_option( 'blogname' ), ENT_QUOTES ),
				$domain,
				$domain,
				'<a href="https://woocommerce.com">WooCommerce</a>',
				'<a href="https://woocommerce.com">WooCommerce</a>',
			),
			$string
		);
	}

	/**
	 * wc_user_products_add_new.
	 *
	 * @version 2.0.0
	 * @since   1.0.0
	 *
	 * @todo    (v2.0.0) escape output?
	 * @todo    (dev) re-check: `$atts` and `$this->the_atts`
	 * @todo    (dev) multipart only if image...
	 */
	function wc_user_products_add_new( $atts ) {

		$atts = shortcode_atts( $this->the_atts, $atts, 'wc_user_products_add_new' );

		if ( ! empty( $atts['visibility'] ) ) {
			$current_user      = wp_get_current_user();
			$current_user_role = (
				(
					isset( $current_user->roles ) &&
					is_array( $current_user->roles ) &&
					! empty( $current_user->roles )
				) ?
				reset( $current_user->roles ) :
				'guest'
			);
			$current_user_role = (
				'' != $current_user_role ?
				$current_user_role :
				'guest'
			);
			if ( ! in_array( $current_user_role, $atts['visibility'] ) ) {
				return '';
			}
		}

		$header_html       = '';
		$notice_html       = '';
		$input_fields_html = '';
		$footer_html       = '';

		// phpcs:disable WordPress.Security.NonceVerification.Recommended
		$args = array(
			'title'           => (
				isset( $_REQUEST['alg_wc_add_new_product_title'] ) ?
				sanitize_text_field( wp_unslash( $_REQUEST['alg_wc_add_new_product_title'] ) ) :
				''
			),
			'desc'            => (
				isset( $_REQUEST['alg_wc_add_new_product_desc'] ) ?
				sanitize_text_field( wp_unslash( $_REQUEST['alg_wc_add_new_product_desc'] ) ) :
				''
			),
			'short_desc'      => (
				isset( $_REQUEST['alg_wc_add_new_product_short_desc'] ) ?
				sanitize_text_field( wp_unslash( $_REQUEST['alg_wc_add_new_product_short_desc'] ) ) :
				''
			),
			'regular_price'   => (
				isset( $_REQUEST['alg_wc_add_new_product_regular_price'] ) ?
				sanitize_text_field( wp_unslash( $_REQUEST['alg_wc_add_new_product_regular_price'] ) ) :
				''
			),
			'sale_price'      => (
				isset( $_REQUEST['alg_wc_add_new_product_sale_price'] ) ?
				sanitize_text_field( wp_unslash( $_REQUEST['alg_wc_add_new_product_sale_price'] ) ) :
				''
			),
			'external_url'    => (
				isset( $_REQUEST['alg_wc_add_new_product_external_url'] ) ?
				sanitize_text_field( wp_unslash( $_REQUEST['alg_wc_add_new_product_external_url'] ) ) :
				''
			),
			'cats'            => (
				isset( $_REQUEST['alg_wc_add_new_product_cats'] ) ?
				array_map(
					'sanitize_text_field',
					wp_unslash( $_REQUEST['alg_wc_add_new_product_cats'] )
				) :
				array()
			),
			'tags'            => (
				isset( $_REQUEST['alg_wc_add_new_product_tags'] ) ?
				array_map(
					'sanitize_text_field',
					wp_unslash( $_REQUEST['alg_wc_add_new_product_tags'] )
				) :
				array()
			),
			'image'           => (
				isset( $_FILES['alg_wc_add_new_product_image'] ) ? // phpcs:ignore WordPress.Security.NonceVerification.Missing
				array_map(
					'sanitize_text_field',
					$_FILES['alg_wc_add_new_product_image'] // phpcs:ignore WordPress.Security.NonceVerification.Missing
				) :
				''
			),
			'_image_uploaded' => (
				isset( $_REQUEST['_alg_wc_add_new_product_image_uploaded'] ) ?
				sanitize_text_field( wp_unslash( $_REQUEST['_alg_wc_add_new_product_image_uploaded'] ) ) :
				false
			),
		);
		for ( $i = 1; $i <= $this->the_atts['custom_taxonomies_total']; $i++ ) {
			$param_id = 'alg_wc_add_new_product_' . 'custom_taxonomy_' . $i;
			$args[ 'custom_taxonomy_' . $i ] = (
				isset( $_REQUEST[ $param_id ] ) ?
				array_map(
					'sanitize_text_field',
					wp_unslash( $_REQUEST[ $param_id ] )
				) :
				array()
			);
		}
		for ( $i = 1; $i <= $this->the_atts['custom_fields_total']; $i++ ) {
			$param_id = 'alg_wc_add_new_product_' . 'custom_field_' . $i;
			$args[ 'custom_field_' . $i ] = (
				isset( $_REQUEST[ $param_id ] ) ?
				sanitize_text_field( wp_unslash( $_REQUEST[ $param_id ] ) ) :
				''
			);
		}
		// phpcs:enable WordPress.Security.NonceVerification.Recommended

		if ( isset( $_REQUEST['alg_wc_add_new_product'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
			if ( true === ( $validate_args = $this->validate_args( $args, $atts ) ) ) {
				$result = $this->wc_add_new_product( $args, $atts );
				if ( 0 == $result ) {
					// Error
					$notice_html .= '<div class="woocommerce">' .
						'<ul class="woocommerce-error">' .
							'<li>' .
								__( 'Error!', 'user-products-for-woocommerce' ) .
							'</li>' .
						'</ul>' .
					'</div>';
				} else {
					// Success
					if ( 0 == $atts['product_id'] ) {
						$notice_html .= '<div class="woocommerce">' .
							'<div class="woocommerce-message">' .
								str_replace(
									'%product_title%',
									$args['title'],
									get_option(
										'alg_wc_user_products_message_product_successfully_added',
										__( '"%product_title%" successfully added!', 'user-products-for-woocommerce' )
									)
								) .
							'</div>' .
						'</div>';
						// Email
						$this->maybe_send_email( $args );
					} else {
						$notice_html .= '<div class="woocommerce">' .
							'<div class="woocommerce-message">' .
								str_replace(
									'%product_title%',
									$args['title'],
									get_option(
										'alg_wc_user_products_message_product_successfully_edited',
										__( '"%product_title%" successfully edited!', 'user-products-for-woocommerce' )
									)
								) .
							'</div>' .
						'</div>';
					}
				}
			} else {
				$notice_html .= '<div class="woocommerce">' .
					'<ul class="woocommerce-error">' . $validate_args . '</ul>' .
				'</div>';
			}
		}

		if ( isset( $_GET['alg_wc_edit_product_image_delete'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
			$product_id     = intval( $_GET['alg_wc_edit_product_image_delete'] ); // phpcs:ignore WordPress.Security.NonceVerification.Recommended
			$post_author_id = get_post_field( 'post_author', $product_id );
			$user_id        = get_current_user_id();
			if ( $user_id != $post_author_id ) {
				echo '<p>' .
					esc_html__( 'Wrong user ID!', 'user-products-for-woocommerce' ) .
				'</p>';
			} else {
				$image_id = get_post_thumbnail_id( $product_id );
				wp_delete_post( $image_id, true );
			}
		}

		if ( 0 != $atts['product_id'] ) {
			$this->the_product = wc_get_product( $atts['product_id'] );
		}

		$header_html .= '<h3>';
		$header_html .= (
			0 == $atts['product_id'] ?
			__( 'Add New Product', 'user-products-for-woocommerce' ) :
			__( 'Edit Product', 'user-products-for-woocommerce' )
		);
		$header_html .= '</h3>';
		$header_html .= '<form' .
			' method="post"' .
			' action="' . remove_query_arg(
				array(
					'alg_wc_edit_product_image_delete',
					'alg_wc_delete_product',
				)
			) .
			'" enctype="multipart/form-data"' .
		'>';

		$required_mark_html_template = '&nbsp;<abbr' .
			' class="required"' .
			' title="' . __( 'required', 'user-products-for-woocommerce' ) . '"' .
		'>*</abbr>';

		$price_step = sprintf(
			"%f",
			( 1 / pow(
				10,
				get_option(
					'alg_wc_user_products_price_step',
					get_option( 'woocommerce_price_num_decimals', 2 )
				)
			) )
		);

		$table_data = array();
		$input_style = 'width:100%;';
		$table_data[] = array(
			'<label for="alg_wc_add_new_product_title">' .
				__( 'Title', 'user-products-for-woocommerce' ) .
				$required_mark_html_template .
			'</label>',
			'<input' .
				' required' .
				' type="text"' .
				' style="' . $input_style . '"' .
				' id="alg_wc_add_new_product_title"' .
				' name="alg_wc_add_new_product_title"' .
				' value="' . (
					0 != $atts['product_id'] ?
					$this->the_product->get_title() :
					$args['title']
				) . '"' .
			'>'
		);
		if ( 'yes' === $atts['desc_enabled'] ) {
			$required_html = (
				'yes' === $atts['desc_required'] ?
				' required' :
				''
			);
			$required_mark_html = (
				'yes' === $atts['desc_required'] ?
				$required_mark_html_template :
				''
			);
			$table_data[] = array(
				'<label for="alg_wc_add_new_product_desc">' .
					__( 'Description', 'user-products-for-woocommerce' ) .
					$required_mark_html .
				'</label>',
				'<textarea' .
					$required_html .
					' style="' . $input_style . '"' .
					' id="alg_wc_add_new_product_desc"' .
					' name="alg_wc_add_new_product_desc"' .
				'>' .
					(
						0 != $atts['product_id'] ?
						get_post_field( 'post_content', $atts['product_id'] ) :
						$args['desc']
					) .
				'</textarea>'
			);
		}
		if ( 'yes' === $atts['short_desc_enabled'] ) {
			$required_html = (
				'yes' === $atts['short_desc_required'] ?
				' required' :
				''
			);
			$required_mark_html = (
				'yes' === $atts['short_desc_required'] ?
				$required_mark_html_template :
				''
			);
			$table_data[] = array(
				'<label for="alg_wc_add_new_product_short_desc">' .
					__( 'Short Description', 'user-products-for-woocommerce' ) .
					$required_mark_html .
				'</label>',
				'<textarea' .
					$required_html .
					' style="' . $input_style . '"' .
					' id="alg_wc_add_new_product_short_desc"' .
					' name="alg_wc_add_new_product_short_desc"' .
				'>' .
					(
						0 != $atts['product_id'] ?
						get_post_field( 'post_excerpt', $atts['product_id'] ) :
						$args['short_desc']
					) .
				'</textarea>'
			);
		}
		if ( 'yes' === $atts['image_enabled'] ) {
			$required_html = (
				'yes' === $atts['image_required'] ?
				' required' :
				''
			);
			$required_mark_html = (
				'yes' === $atts['image_required'] ?
				$required_mark_html_template :
				''
			);
			$new_image_field = '<input' .
				$required_html .
				' type="file"' .
				' id="alg_wc_add_new_product_image"' .
				' name="alg_wc_add_new_product_image"' .
				' accept="image/*"' .
			'>';
			if ( 0 != $atts['product_id'] ) {
				$the_field = (
					'' == get_post_thumbnail_id( $atts['product_id'] ) ?
					$new_image_field :
					(
						'<a' .
							' href="' . add_query_arg(
								'alg_wc_edit_product_image_delete',
								$atts['product_id']
							) . '"' .
							' onclick="return confirm(\'' .
								__( 'Are you sure?', 'user-products-for-woocommerce' ) .
							'\')"' .
						'>' .
							__( 'Delete', 'user-products-for-woocommerce' ) .
						'</a>' .
						'<br>' .
						get_the_post_thumbnail(
							$atts['product_id'],
							array( 50, 50 ),
							array( 'class' => 'alignleft' )
						) .
						'<input' .
							' type="hidden"' .
							' name="_alg_wc_add_new_product_image_uploaded"' .
							' value="1"' .
						'>'
					)
				);
			} else {
				$the_field = $new_image_field;
			}
			$table_data[] = array(
				'<label for="alg_wc_add_new_product_image">' .
					__( 'Image', 'user-products-for-woocommerce' ) .
					$required_mark_html .
				'</label>',
				$the_field
			);
		}
		if ( 'yes' === $atts['regular_price_enabled'] ) {
			$required_html = (
				'yes' === $atts['regular_price_required'] ?
				' required' :
				''
			);
			$required_mark_html = (
				'yes' === $atts['regular_price_required'] ?
				$required_mark_html_template :
				''
			);
			$table_data[] = array(
				'<label for="alg_wc_add_new_product_regular_price">' .
					__( 'Regular Price', 'user-products-for-woocommerce' ) .
					$required_mark_html .
				'</label>',
				'<input' .
					$required_html .
					' type="number"' .
					' min="0"' .
					' step="' . $price_step . '"' .
					' id="alg_wc_add_new_product_regular_price"' .
					' name="alg_wc_add_new_product_regular_price"' .
					' value="' . (
						0 != $atts['product_id'] ?
						get_post_meta( $atts['product_id'], '_regular_price', true ) :
						$args['regular_price']
					) . '"' .
				'>'
			);
		}
		if ( 'yes' === $atts['sale_price_enabled'] ) {
			$required_html = (
				'yes' === $atts['sale_price_required'] ?
				' required' :
				''
			);
			$required_mark_html = (
				'yes' === $atts['sale_price_required'] ?
				$required_mark_html_template :
				''
			);
			$table_data[] = array(
				'<label for="alg_wc_add_new_product_sale_price">' .
					__( 'Sale Price', 'user-products-for-woocommerce' ) .
					$required_mark_html .
				'</label>',
				'<input' .
					$required_html .
					' type="number"' .
					' min="0"' .
					' step="' . $price_step . '"' .
					' id="alg_wc_add_new_product_sale_price"' .
					' name="alg_wc_add_new_product_sale_price"' .
					' value="' . (
						0 != $atts['product_id'] ?
						get_post_meta( $atts['product_id'], '_sale_price', true ) :
						$args['sale_price']
					) . '"' .
				'>'
			);
		}
		if ( 'yes' === $atts['external_url_enabled'] ) {
			$required_html = (
				'yes' === $atts['external_url_required'] ?
				' required' :
				''
			);
			$required_mark_html = (
				'yes' === $atts['external_url_required'] ?
				$required_mark_html_template :
				''
			);
			$table_data[] = array(
				'<label for="alg_wc_add_new_product_external_url">' .
					__( 'Product URL', 'user-products-for-woocommerce' ) .
					$required_mark_html .
				'</label>',
				'<input' .
					$required_html .
					' style="' . $input_style . '"' .
					' type="url"' .
					' id="alg_wc_add_new_product_external_url"' .
					' name="alg_wc_add_new_product_external_url"' .
					' value="' . (
						0 != $atts['product_id'] ?
						get_post_meta( $atts['product_id'], '_product_url', true ) :
						$args['external_url']
					) . '"' .
				'>',
			);
		}
		$table_data = $this->maybe_add_taxonomy_field(
			$atts,
			$args,
			'cats',
			'product_cat',
			__( 'Categories', 'user-products-for-woocommerce' ),
			$input_style,
			$required_mark_html_template,
			$table_data
		);
		$table_data = $this->maybe_add_taxonomy_field(
			$atts,
			$args,
			'tags',
			'product_tag',
			__( 'Tags', 'user-products-for-woocommerce' ),
			$input_style,
			$required_mark_html_template,
			$table_data
		);
		for ( $i = 1; $i <= $this->the_atts['custom_taxonomies_total']; $i++ ) {
			$table_data = $this->maybe_add_taxonomy_field(
				$atts,
				$args,
				'custom_taxonomy_' . $i,
				$atts[ 'custom_taxonomy_' . $i . '_id' ],
				$atts[ 'custom_taxonomy_' . $i . '_title' ],
				$input_style,
				$required_mark_html_template,
				$table_data
			);
		}
		for ( $i = 1; $i <= $this->the_atts['custom_fields_total']; $i++ ) {
			if (
				'yes' === $atts[ 'custom_field_' . $i . '_enabled' ] &&
				'' !== $atts[ 'custom_field_' . $i . '_meta_key' ]
			) {
				$required_html      = (
					'yes' === $atts[ 'custom_field_' . $i . '_required' ] ?
					' required' :
					''
				);
				$required_mark_html = (
					'yes' === $atts[ 'custom_field_' . $i . '_required' ] ?
					$required_mark_html_template :
					''
				);
				$meta_key           = $atts[ 'custom_field_' . $i . '_meta_key' ];
				$title              = $atts[ 'custom_field_' . $i . '_title' ];
				$id                 = 'alg_wc_add_new_product_' . 'custom_field_' . $i;
				$value              = (
					0 != $atts['product_id'] ?
					get_post_meta( $atts['product_id'], $meta_key, true ) :
					$args[ 'custom_field_' . $i ]
				);
				$table_data[]       = array(
					'<label for="' . $id . '">' .
						$title .
						$required_mark_html .
					'</label>',
					'<input' .
						$required_html .
						' style="' . $input_style . '"' .
						' type="text"' .
						' id="' . $id . '"' .
						' name="' . $id . '"' .
						' value="' . $value . '"' .
					'>',
				);
			}
		}

		$input_fields_html .= alg_wc_user_products()->core->get_table_html(
			$table_data,
			array(
				'table_class'        => 'widefat',
				'table_heading_type' => 'vertical',
			)
		);

		$footer_html .= '<input' .
			' type="submit"' .
			' class="button"' .
			' name="alg_wc_add_new_product"' .
			' value="' . (
				0 == $atts['product_id'] ?
				__( 'Add', 'user-products-for-woocommerce' ) :
				__( 'Edit', 'user-products-for-woocommerce' )
			) . '"' .
		'>';
		$footer_html .= '</form>';

		return $notice_html . $header_html . $input_fields_html . $footer_html;
	}

}

endif;

return new Alg_WC_User_Products_Shortcode();
