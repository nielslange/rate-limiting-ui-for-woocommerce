<?php
/**
 * Plugin Name:             Rate limiting UI for WooCommerce
 * Plugin URI:              https://github.com/nielslange/rate-limiting-ui-for-woocommerce
 * Description:             Enhance the security and performance of your WooCommerce store with the Rate Limiting UI for WooCommerce plugin, specifically designed for managing traffic to the Checkout block. This plugin provides a user-friendly interface within WooCommerce settings, allowing store owners to easily configure rate limits to prevent abuse and ensure smooth transaction processes. Ideal for stores experiencing high volumes of traffic, it ensures that your checkout experience remains efficient and secure.
 * Version:                 1.3
 * Author:                  Paulo Arromba, Niels Lange, Riaan Knoetze
 * Requires at least:       6.1
 * Requires PHP:            7.4
 * WC requires at least:    7.2
 * WC tested up to:         11.3
 * License:                 GPL v2 or later
 * License URI:             https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:             rate-limiting-ui-for-woocommerce
 *
 * @package Rate_Limiting_UI_for_WooCommerce
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

/**
 * Rate Limiting UI for WooCommerce main class.
 */
class RateLimitingUIForWooCommerce {
	/**
	 * Variable for the enabled setting.
	 *
	 * @var bool
	 */
	private $enabled;

	/**
	 * Variable for the seconds setting.
	 *
	 * @var int
	 */
	private $seconds;

	/**
	 * Variable for the limit setting.
	 *
	 * @var int
	 */
	private $limit;

	/**
	 * Variable for the proxy support setting.
	 *
	 * @var bool
	 */
	private $proxy;

	/**
	 * Rate Limiting UI for WooCommerce constructor.
	 *
	 * @return void
	 */
	public function __construct() {
		$this->enabled = 'yes' === get_option( 'rate_limiting_enabled', 'yes' );
		$this->seconds = (int) get_option( 'rate_limiting_seconds', 10 );
		$this->limit   = (int) get_option( 'rate_limiting_limit', 25 );
		$this->proxy   = 'yes' === get_option( 'rate_limiting_proxy_support', 'no' );

		add_action( 'before_woocommerce_init', array( $this, 'declare_compatibility' ) );
		add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), array( $this, 'add_plugin_action_links' ) );
		add_filter( 'woocommerce_get_sections_advanced', array( $this, 'add_wc_advanced_settings_tab' ), 20 );
		add_filter( 'woocommerce_get_settings_advanced', array( $this, 'add_wc_rate_limiting_settings' ) );
		add_action( 'woocommerce_update_options_advanced', array( $this, 'save_wc_rate_limiting_settings' ) );
		add_filter( 'rest_authentication_errors', array( $this, 'apply_checkout_rate_limiting' ), 9 );
	}

	/**
	 * Declare compatibility with custom order tables for WooCommerce.
	 *
	 * @return void
	 */
	public function declare_compatibility() {
		if ( class_exists( '\Automattic\WooCommerce\Utilities\FeaturesUtil' ) ) {
			\Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility( 'custom_order_tables', __FILE__, true );
		}
	}

	/**
	 * Add settings link on plugin page
	 *
	 * @param array $links The original array with customizer links.
	 * @return array The updated array with customizer links.
	 */
	public function add_plugin_action_links( array $links ) {
		$admin_url     = admin_url( 'admin.php?page=wc-settings&tab=advanced&section=rate_limiting' );
		$settings_link = sprintf( '<a href="%s">' . __( 'Settings', 'rate-limiting-ui-for-woocommerce' ) . '</a>', $admin_url );
		array_unshift( $links, $settings_link );

		return $links;
	}

	/**
	 * Add a new settings section tab to the WooCommerce advanced settings tabs array.
	 *
	 * @param array $sections The original array with the WooCommerce settings tabs.
	 * @return array $sections The updated array with our settings tab added.
	 */
	public function add_wc_advanced_settings_tab( $sections ) {
		$sections['rate_limiting'] = __( 'Rate Limiting', 'rate-limiting-ui-for-woocommerce' );

		return $sections;
	}

	/**
	 * Add the settings section to the WooCommerce settings tab array on the advanced tab.
	 *
	 * @param array $settings The settings array to add our section to.
	 *
	 * @return array $settings The settings array with our section added.
	 */
	public function add_wc_rate_limiting_settings( $settings ) {
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
				'id'   => 'rate_limiting_enabled',
				'type' => 'checkbox',
				'desc' => __( 'Enable the Rate Limiting feature.', 'rate-limiting-ui-for-woocommerce' ),
			),
			array(
				'name'    => __( 'Seconds', 'rate-limiting-ui-for-woocommerce' ),
				'id'      => 'rate_limiting_seconds',
				'type'    => 'number',
				'css'     => 'width:60px;',
				'default' => '10',
				'desc'    => __( 'Time in seconds before rate limits are reset.', 'rate-limiting-ui-for-woocommerce' ),
			),
			array(
				'name'    => __( 'Limit', 'rate-limiting-ui-for-woocommerce' ),
				'id'      => 'rate_limiting_limit',
				'type'    => 'number',
				'css'     => 'width:60px;',
				'default' => '25',
				'desc'    => __( 'Amount of max requests allowed for the defined timeframe.', 'rate-limiting-ui-for-woocommerce' ),
			),
			array(
				'name' => __( 'Enable Basic Proxy support', 'rate-limiting-ui-for-woocommerce' ),
				'id'   => 'rate_limiting_proxy_support',
				'type' => 'checkbox',
				'desc' => __( 'Enable this only if your store is running behind a reverse proxy, cache system, etc.', 'rate-limiting-ui-for-woocommerce' ),
			),
			array(
				'type' => 'sectionend',
				'id'   => 'rate_limiting_settings',
			),
		);

		return $rate_limiting_settings;
	}

	/**
	 * Save the settings.
	 *
	 * @return void
	 */
	public function save_wc_rate_limiting_settings() {
		global $current_section;

		if ( 'rate_limiting' !== $current_section ) {
			return;
		}

		if ( ! isset( $_POST['_wpnonce'] ) || ! wp_verify_nonce( $_POST['_wpnonce'], 'woocommerce-settings' ) ) {
			die( __( 'Could not verify request.', 'rate-limiting-ui-for-woocommerce' ) );
		}

		$enabled = isset( $_POST['rate_limiting_enabled'] ) ? 'yes' : 'no';
		update_option( 'rate_limiting_enabled', $enabled );

		$seconds = isset( $_POST['rate_limiting_seconds'] ) && '' !== $_POST['rate_limiting_seconds'] ? intval( $_POST['rate_limiting_seconds'] ) : 10;
		update_option( 'rate_limiting_seconds', $seconds );

		$limit = isset( $_POST['rate_limiting_limit'] ) && '' !== $_POST['rate_limiting_limit'] ? intval( $_POST['rate_limiting_limit'] ) : 25;
		update_option( 'rate_limiting_limit', $limit );

		$proxy_support = isset( $_POST['rate_limiting_proxy_support'] ) ? 'yes' : 'no';
		update_option( 'rate_limiting_proxy_support', $proxy_support );
	}

	/**
	 * Apply rate limiting to the checkout post request.
	 *
	 * Mirrors the checks WooCommerce uses for its own "Rate limit Checkout"
	 * feature, so only placing an order is limited.
	 *
	 * @param mixed $result The result of the REST authentication.
	 * @return mixed The result of the REST authentication.
	 */
	public function apply_checkout_rate_limiting( $result ) {
		if (
			! empty( $GLOBALS['wp']->query_vars['rest_route'] )
			&& preg_match( '#/wc/store(?:/v\d+)?/checkout#i', $GLOBALS['wp']->query_vars['rest_route'] )
			&& $this->is_only_post_request()
		) {
			add_filter( 'woocommerce_store_api_rate_limit_options', array( $this, 'add_rate_limiting_settings_to_rest_api' ) );
		}
		return $result;
	}

	/**
	 * Whether the request is a POST request that is not overridden to another method.
	 *
	 * @return bool
	 */
	private function is_only_post_request() {
		if ( ! isset( $_SERVER['REQUEST_METHOD'] ) || 'POST' !== $_SERVER['REQUEST_METHOD'] ) {
			return false;
		}

		if ( isset( $_SERVER['HTTP_X_HTTP_METHOD_OVERRIDE'] ) ) {
			$method_override = strtoupper( sanitize_text_field( wp_unslash( $_SERVER['HTTP_X_HTTP_METHOD_OVERRIDE'] ) ) );
			if ( '' !== $method_override && 'POST' !== $method_override ) {
				return false;
			}
		}

		return true;
	}

	/**
	 * Add the rate limiting settings to the WooCommerce REST API settings.
	 *
	 * When the plugin's rate limiting is disabled, the options are passed through
	 * unchanged, so WooCommerce's own "Rate limit Checkout" feature keeps working.
	 *
	 * @param array $options The rate limiting options.
	 * @return array
	 */
	public function add_rate_limiting_settings_to_rest_api( $options ) {
		if ( ! $this->enabled ) {
			return $options;
		}

		return array(
			'enabled'       => $this->enabled,
			'proxy_support' => $this->proxy,
			'limit'         => $this->limit,
			'seconds'       => $this->seconds,
		);
	}
}

new RateLimitingUIForWooCommerce();
