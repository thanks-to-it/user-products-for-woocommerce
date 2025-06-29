=== User Products for WooCommerce ===
Contributors: algoritmika, thankstoit, anbinder, karzin
Tags: woocommerce, user products, ecommerce
Requires at least: 5.0
Tested up to: 6.8
Stable tag: 2.0.0
License: GNU General Public License v3.0
License URI: http://www.gnu.org/licenses/gpl-3.0.html

Let users add new WooCommerce products from frontend.

== Description ==

**User Products for WooCommerce** plugin lets your users add new WooCommerce products from frontend.

### ✅ Main Features ###

* Add product upload form to the **frontend** with the `[wc_user_products_add_new]` shortcode.
* Set **additional** and **required form fields**.
* Limit the form to selected **user roles** only.
* Set default **product status**, e.g., "pending review" or "published".
* Customize user **messages**.
* Send an **email** when a new product is added.
* And more...

### 🚀 Add User Products for WooCommerce ###

Empower your WooCommerce community with "User Products for WooCommerce", enabling users to easily submit and manage products from the frontend, fostering a dynamic and collaborative online store experience.

The "User Products for WooCommerce" plugin is designed to facilitate an interactive and engaged user community by enabling users to add/upload new products directly from the frontend of your WooCommerce store.

On the frontend, users are greeted with a straightforward process for adding new products, where you can easily integrate a product upload form on the frontend from any page.

### 🚀 Allow Users to Upload Products From Store Frontend ###

Enable users to add products directly from the frontend of your WooCommerce store using the `[wc_user_products_add_new]` shortcode.

This feature simplifies the process of product submission, allowing users to contribute new items through a user-friendly form, enhancing community interaction and product variety on your site.

### 🚀 Customize Field Options for Product Submission ###

While the Title field is always visible, you have the ability to allow users to upload more product details in different fields:

* Description
* Short Description
* Image
* Regular Price
* Sale Price
* Product URL (for "External/Affiliate" product type only)
* Categories
* Tags
* Custom Fields
* Custom Taxonomies

### 🚀 Show Messages for Product Uploads ###

Enhance user experience with customizable confirmation messages for successful product submissions and edits.

This feature allows administrators to set tailored messages that appear when a user successfully adds or updates a product, providing clear and personalized feedback.

### 🚀 Control Optional/Required Fields Selection ###

Specify which fields in the product submission form are optional and which are required, with the exception of the title field, which is always mandatory.

This feature grants administrators the flexibility to tailor the product submission process according to the store's specific needs and products content guidelines.

### 🚀 Customizable General Options for Product Submission ###

Control general aspects of the product submission process, such as the number of decimal places in prices, visibility of the form to certain user roles, product type (simple or external/affiliate), and default product status (draft, pending review, etc.).

These options provide flexibility in managing how new products are added and also who can add them.

### 🚀 Custom Taxonomies for Detailed Product Categorization ###

Incorporate custom taxonomies for product submissions, allowing users to categorize their products more specifically.

This feature is essential for stores with diverse product ranges, enabling more precise classification and easier navigation for customers.

### 🚀 Custom Fields for Additional Product Information ###

Implement custom fields in the product submission form for extra information that might be specific to your store's requirements.

These fields can be enabled or required as needed, providing additional flexibility in the type of information collected from users during product submission.

### 🚀 Show Uploaded Products in User's "My Account" Tab ###

Expand the functionality of the user's "My Account" page by integrating a "Products" tab, allowing users to easily view and manage their submitted products directly from their account dashboard.

### 🗘 Feedback ###

* We are open to your suggestions and feedback. Thank you for using or trying out one of our plugins!
* Head to the plugin [GitHub Repository](https://github.com/thanks-to-it/user-products-for-woocommerce) to find out how you can pitch in.

### ℹ More ###

* The plugin is **"High-Performance Order Storage (HPOS)"** compatible.

== Installation ==

1. Upload the entire plugin folder to the `/wp-content/plugins/` directory.
2. Activate the plugin through the "Plugins" menu in WordPress.
3. Start by visiting plugin settings at "WooCommerce > Settings > User Products".

== Changelog ==

= 2.0.0 - 29/06/2025 =
* Dev - Security - Output escaped.
* Dev - Security - Input sanitized.
* Dev - Custom Fields - Total custom fields - Moved to the free plugin version.
* Dev - Taxonomies - Total custom taxonomies - Moved to the free plugin version.
* Dev - Fields - Image - Moved to the free plugin version.
* Dev - Code refactoring.
* Dev - Coding standards improved.
* Tested up to: 6.8.
* WC tested up to: 9.9.

= 1.5.0 - 04/08/2024 =
* Dev - "Emails" options added.
* Dev - "High-Performance Order Storage (HPOS)" compatibility.
* Dev - PHP v8.2 compatibility - "Creation of dynamic property is deprecated" notice fixed.
* Dev - Admin settings split into separate sections.
* Tested up to: 6.6.
* WC tested up to: 9.1.
* WooCommerce added to the "Requires Plugins" (plugin header).

= 1.4.1 - 19/06/2023 =
* Tested up to: 6.2.
* WC tested up to: 7.8.

= 1.4.0 - 22/12/2022 =
* Dev - Code refactoring.
* Tested up to: 6.1.
* WC tested up to: 7.2.
* Readme.txt updated.
* Deploy script added.

= 1.3.2 - 07/05/2021 =
* Fix - Shortcodes - `[wc_user_products_list]` - `columns` - `product_nr` - Default title (`#`) added.
* Dev - Shortcodes - `[wc_user_products_list]` - `columns` - `function=x` column added.
* Localization - Romanian `ro_RO` translations (by Florin) added.

= 1.3.1 - 27/04/2021 =
* Fix - Custom Fields - "Undefined index ..." PHP notice fixed.

= 1.3.0 - 27/04/2021 =
* Fix - Fields Options - Image - Wrong "Image is required!" message on "Edit" fixed.
* Fix - Fields Options - Product URL - "Required" mark fixed.
* Dev - "Custom Fields Options" added.
* Dev - Products tab - "Tab content" option added.
* Dev - Shortcodes - `[wc_user_products_list]` - Product link added to the product title and thumbnail.
* Dev - Shortcodes - `[wc_user_products_list]` - `user_id` attribute added (defaults to current user ID).
* Dev - Shortcodes - `[wc_user_products_list]` - `columns` attribute added (defaults to `thumbnail,status,title,actions`).
* Dev - Shortcodes - `[wc_user_products_list]` - `columns` - `categories` and `tags` columns added.
* Dev - Shortcodes - `[wc_user_products_list]` - `columns` - `meta=x` column added.
* Dev - Shortcodes - `[wc_user_products_list]` - `columns` - `taxonomy=x` column added.
* Dev - Shortcodes - `[wc_user_products_list]` - `columns` - `status_label` column added.
* Dev - Shortcodes - `[wc_user_products_list]` - `columns` - `product_nr` column added.
* Dev - Shortcodes - `[wc_user_products_list]` - `thumbnail_size` attribute added (now defaults to `post-thumbnail` (was `25,25`)).
* Dev - Shortcodes - `[wc_user_products_list]` - `column_titles` attribute added.
* Dev - Shortcodes - `[wc_user_products_list]` - `column_styles` attribute added.
* Dev - Shortcodes - `[wc_user_products_list]` - `column_classes` attribute added.
* Dev - Shortcodes - `[wc_user_products_list]` - `column_sep` attribute added (applied to `columns`, `column_titles`, `column_styles` and `column_classes` attributes).
* Dev - Shortcodes - `[wc_user_products_list]` - `table_class` attribute added.
* Dev - Shortcodes - `[wc_user_products_list]` - `table_style` attribute added.
* Dev - Shortcodes - `[wc_user_products_list]` - `row_styles` attribute added.
* Dev - Shortcodes - `[wc_user_products_list]` - `actions` attribute added (defaults to `%edit% %delete%`; additional available placeholder: `%view%`).
* Dev - Shortcodes - `[wc_user_products_list]` - `template` attribute added (defaults to `%products_table%`; additional available placeholder: `%total_products%`).
* Dev - Calling `flush_rewrite_rules()` on every settings save now.
* Dev - Admin settings descriptions updated.
* Dev - Code refactoring.

= 1.2.0 - 16/04/2021 =
* Dev - Shortcodes - `[wc_user_products_list]` shortcode added.
* Dev - Plugin is initialized on `plugins_loaded` now.
* Dev - Localization - `load_plugin_textdomain()` function moved to the `init` hook.
* Dev - Admin settings descriptions updated.
* Dev - Code refactoring.
* Tested up to: 5.7.
* WC tested up to: 5.2.

= 1.1.0 - 18/03/2020 =
* Dev - Code refactoring.
* Dev - Admin settings descriptions updated.
* Author updated.
* Author URI updated.
* Contributors updated.
* Tags updated.
* Requires at least: 5.0.
* Tested up to: 5.3.
* WC tested up to: 4.0.

= 1.0.0 - 19/06/2018 =
* Initial Release.

== Upgrade Notice ==

= 1.0.0 =
This is the first release of the plugin.
