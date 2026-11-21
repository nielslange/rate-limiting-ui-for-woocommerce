# Rate limiting UI for WooCommerce

![Support Level](https://img.shields.io/badge/support-active-green.svg)
![Build Status](https://github.com/nielslange/rate-limiting-ui-for-woocommerce/actions/workflows/test.yml/badge.svg)
![GPLv2 License](https://img.shields.io/github/license/nielslange/rate-limiting-ui-for-woocommerce.svg)
![Compatible to WordPress version](https://img.shields.io/wordpress/plugin/tested/rate-limiting-ui-for-woocommerce.svg)
![Compatible to PHP version](https://img.shields.io/wordpress/plugin/required-php/rate-limiting-ui-for-woocommerce.svg)
![Downloads](https://img.shields.io/wordpress/plugin/dt/rate-limiting-ui-for-woocommerce.svg)
![Plugin Version](https://img.shields.io/wordpress/plugin/v/rate-limiting-ui-for-woocommerce.svg)
![Tag Version](https://img.shields.io/github/tag/nielslange/rate-limiting-ui-for-woocommerce.svg)

Set the WooCommerce Store API rate limit for checkout in the WooCommerce settings: requests, time window and proxy support.

WooCommerce can limit how often the Store API checkout endpoint accepts orders, but only through code or a fixed on/off setting. Rate Limiting UI for WooCommerce adds a settings screen under WooCommerce » Settings » Advanced » Rate Limiting. There you set how many checkout requests are allowed per time window and turn on proxy support for stores behind a CDN or load balancer.

The limit applies only to placing an order through the Checkout block, so browsing and the cart are not affected. When the plugin's rate limiting is off, WooCommerce's own "Rate limit Checkout" setting keeps working.

## Installation

1. Upload `rate-limiting-ui-for-woocommerce` to the `/wp-content/plugins/` directory.
2. Activate the plugin through the `Plugins` menu in WordPress.
3. Go to `WooCommerce » Settings » Advanced » Rate Limiting` to configure the plugin.

## Plugin page

You can find the plugin on <https://wordpress.org/plugins/rate-limiting-ui-for-woocommerce/>.

## Screenshot

![rate-limiting-ui-for-woocommerce](https://github.com/nielslange/rate-limiting-ui-for-woocommerce/assets/3323310/66b43c55-3366-4f45-8b30-894c255598de)

## Changelog

### 1.4 (2026.11.21)

-   Shorten the plugin's short description to the 150 characters WordPress.org supports and add a full description
-   Add plugin tags

### 1.3 (2026.11.21)

-   Fix a fatal error in the WooCommerce Store API with current WooCommerce versions
-   Apply rate limits to checkout requests only
-   Keep WooCommerce's own "Rate limit Checkout" setting working while the plugin's rate limiting is off
-   Test against WordPress 7.1 and WooCommerce 11.3

### 1.2 (2024.10.02)

-   Test against WordPress 6.6

### 1.1 (2024.04.07)

-   Test against WordPress 6.5

### 1.0 (2023.12.30)

-   Initial release
