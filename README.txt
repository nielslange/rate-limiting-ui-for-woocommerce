=== Rate limiting UI for WooCommerce ===

Contributors: 		nielslange, pauloarromba, riaanknoetze
Tags: 				woocommerce, rate limiting, checkout, store api, security
Stable tag: 		1.4
Tested up to: 		7.1
Requires PHP: 		7.4
Requires at least: 	6.1
License: 			GPL v2 or later
License URI: 		https://www.gnu.org/licenses/gpl-2.0.html

Set the WooCommerce Store API rate limit for checkout in the WooCommerce settings: requests, time window and proxy support.

== Description ==

WooCommerce can limit how often the Store API checkout endpoint accepts orders, but only through code or a fixed on/off setting. Rate Limiting UI for WooCommerce adds a settings screen under WooCommerce » Settings » Advanced » Rate Limiting. There you set how many checkout requests are allowed per time window and turn on proxy support for stores behind a CDN or load balancer.

The limit applies only to placing an order through the Checkout block, so browsing and the cart are not affected. When the plugin's rate limiting is off, WooCommerce's own "Rate limit Checkout" setting keeps working.

== Installation ==

1. Upload `rate-limiting-ui-for-woocommerce` to the `/wp-content/plugins/` directory.
2. Activate the plugin through the `Plugins` menu in WordPress.
3. Go to `WooCommerce » Settings » Advanced » Rate Limiting` to configure the plugin.

== Screenshots ==

1. Control the rate limiting settings for WooCommerce via UI.

== Contribute ==

Contributions are more than welcome. Simply head over to [Github](https://github.com/nielslange/rate-limiting-ui-for-woocommerce) and open an issue or a pull request.

== Changelog ==

= 1.4 (2026.11.21) =

-   Shorten the plugin's short description to the 150 characters WordPress.org supports and add a full description
-   Add plugin tags

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

