<?php
/**
 * Plugin Name: Rate limiting UI for WooCommerce
 * Plugin URI: https://github.com/nielslange/rate-limiting-ui-for-woocommerce
 * Description: Allows merchants to easily enable and configure the rate limiting settings for WooCommerce.
 * Version: 1.0
 * Author: Paulo Arromba, Niels Lange
 *
 * Requires at least: 6.1.1
 * Requires PHP: 7.4
 * WC requires at least: 7.2
 * WC tested up to: 7.2
 *
 * License: GPLv2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: rate-limiting-ui-for-woocommerce
 *
 * @package Rate_Limiting_UI_for_WooCommerce
 */

/**
 * Declare compatibility with custom order tables for WooCommerce.
 *
 * @return void
 */
function declare_compatibility() {
	if ( class_exists( '\Automattic\WooCommerce\Utilities\FeaturesUtil' ) ) {
		\Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility( 'custom_order_tables', __FILE__, true );
	}
}
add_action( 'before_woocommerce_init', 'declare_compatibility' );

/**
 * Add settings link on plugin page
 *
 * @param array $links The original array with customizer links.
 *
 * @return array The updated array with customizer links.
 */
function smntcs_google_webmasadd_plugin_action_links( array $links ) {
	$admin_url     = admin_url( 'admin.php?page=wc-settings&tab=advanced&section=rate_limiting' );
	$settings_link = sprintf( '<a href="%s">' . __( 'Settings', 'rate-limiting-ui-for-woocommerce' ) . '</a>', $admin_url );
	array_unshift( $links, $settings_link );

	return $links;
}

add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), 'smntcs_google_webmasadd_plugin_action_links' );

/**
 * Add a new settings section tab to the WooCommerce advanced settings tabs array.
 *
 * @param array $sections The original array with the WooCommerce settings tabs.
 *
 * @return array $sections The updated array with our settings tab added.
 */
function add_wc_advanced_settings_tab( $sections ) {
	$sections['rate_limiting'] = __( 'Rate Limiting', 'rate-limiting-ui-for-woocommerce' );

	return $sections;
}

add_filter( 'woocommerce_get_sections_advanced', 'add_wc_advanced_settings_tab', 20 );

/**
 * Add the settings section to the WooCommerce settings tab array on the advanced tab.
 *
 * @param array $settings The settings array to add our section to.
 *
 * @return array $settings The settings array with our section added.
 */
function add_wc_rate_limiting_settings( $settings ) {
	global $current_section;

	if ( 'rate_limiting' !== $current_section ) {
		return $settings;
	}

	$rate_limiting_settings = array(
		array(
			'name' => __( 'Rate Limiting', 'rate-limiting-ui-for-woocommerce' ),
			'type' => 'title',
			'desc' => '',
			'id'   => 'rate_limiting_settings',
		),
		array(
			'name' => __( 'Enable', 'rate-limiting-ui-for-woocommerce' ),
			'id'   => 'enabled',
			'type' => 'checkbox',
			'desc' => __( 'Enable the Rate Limiting feature.', 'rate-limiting-ui-for-woocommerce' ),
		),
		array(
			'name'    => __( 'Seconds', 'rate-limiting-ui-for-woocommerce' ),
			'id'      => 'seconds',
			'type'    => 'number',
			'css'     => 'width:60px;',
			'default' => '10',
			'desc'    => __( 'Time in seconds before rate limits are reset.', 'rate-limiting-ui-for-woocommerce' ),
		),
		array(
			'name'    => __( 'Limit', 'rate-limiting-ui-for-woocommerce' ),
			'id'      => 'limit',
			'type'    => 'number',
			'css'     => 'width:60px;',
			'default' => '25',
			'desc'    => __( 'Amount of max requests allowed for the defined timeframe.', 'rate-limiting-ui-for-woocommerce' ),

		),
		array(
			'name' => __( 'Enable Basic Proxy support', 'rate-limiting-ui-for-woocommerce' ),
			'id'   => 'proxy_support',
			'type' => 'checkbox',
			'desc' => __( 'Enable this only if your store is running behing a reverse proxy, cache system, etc.', 'rate-limiting-ui-for-woocommerce' ),
		),
		array(
			'type' => 'sectionend',
			'id'   => 'rate_limiting_settings',
		),
	);

	return $rate_limiting_settings;
}
add_filter( 'woocommerce_get_settings_advanced', 'add_wc_rate_limiting_settings' );

/**
 * Save the settings.
 *
 * @return void
 */
function save_wc_rate_limiting_settings() {
	global $current_section;

	if ( 'rate_limiting' !== $current_section ) {
		return;
	}

	if ( ! isset( $_POST['_wpnonce'] ) || ! wp_verify_nonce( $_POST['_wpnonce'], 'woocommerce-settings' ) ) {
		die( __( 'Could not verify request.', 'rate-limiting-ui-for-woocommerce' ) );
	}

	$enabled = isset( $_POST['enabled'] ) ? 'yes' : 'no';
	update_option( 'rate_limiting_enabled', $enabled );

	if ( isset( $_POST['seconds'] ) ) {
		$seconds = intval( $_POST['seconds'] );
		update_option( 'rate_limiting_seconds', $seconds );
	}

	if ( isset( $_POST['limit'] ) ) {
		$limit = intval( $_POST['limit'] );
		update_option( 'rate_limiting_limit', $limit );
	}

	$proxy_support = isset( $_POST['proxy_support'] ) ? 'yes' : 'no';
	update_option( 'rate_limiting_proxy_support', $proxy_support );
}
add_action( 'woocommerce_update_options_advanced', 'save_wc_rate_limiting_settings' );

/**
 * Add the rate limiting settings to the WooCommerce REST API settings.
 *
 * @return void
 */
add_filter(
	'woocommerce_store_api_rate_limit_options',
	function () {
		return array(
			'enabled'       => get_option( 'rate_limiting_enabled', true ),
			'proxy_support' => get_option( 'rate_limiting_proxy_support', false ),
			'limit'         => get_option( 'rate_limiting_limit', 25 ),
			'seconds'       => get_option( 'rate_limiting_seconds', 10 ),
		);
	}
);
