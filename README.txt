=== Rate limiting UI for WooCommerce ===

Contributors: 		nielslange, pauloarromba, riaanknoetze
Tags: 				Plugins
Stable tag: 		1.3
Tested up to: 		7.1
Requires PHP: 		7.4
Requires at least: 	6.1
License: 			GPL v2 or later
License URI: 		https://www.gnu.org/licenses/gpl-2.0.html

Enhance the security and performance of your WooCommerce store with the Rate Limiting UI for WooCommerce plugin, specifically designed for managing traffic to the Checkout block. This plugin provides a user-friendly interface within WooCommerce settings, allowing store owners to easily configure rate limits to prevent abuse and ensure smooth transaction processes. Ideal for stores experiencing high volumes of traffic, it ensures that your checkout experience remains efficient and secure.

== Installation ==

1. Upload `rate-limiting-ui-for-woocommerce` to the `/wp-content/plugins/` directory.
2. Activate the plugin through the `Plugins` menu in WordPress.
3. Go to `WooCommerce » Settings » Advanced » Rate Limiting` to configure the plugin.

== Screenshots ==

1. Control the rate limiting settings for WooCommerce via UI.

== Contribute ==

Contributions are more than welcome. Simply head over to [Github](https://github.com/nielslange/rate-limiting-ui-for-woocommerce) and open an issue or a pull request.

== Changelog ==

= 1.3 (2026.11.21) =

-   Fix a fatal error in the WooCommerce Store API with current WooCommerce versions
-   Apply rate limits to checkout requests only
-   Keep WooCommerce's own "Rate limit Checkout" setting working while the plugin's rate limiting is off
-   Test against WordPress 7.1 and WooCommerce 11.3

= 1.2 (2024.10.02) =

-   Test against WordPress 6.6

= 1.1 (2024.04.07) =

-   Test against WordPress 6.5

= 1.0 (2023.12.30) =

- Initial release

