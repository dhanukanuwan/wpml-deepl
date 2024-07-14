<?php
/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://hashcodeab.se
 * @since      1.0.0
 *
 * @package    Deepl_Wpml
 * @subpackage Deepl_Wpml/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * @package    Deepl_Wpml
 * @subpackage Deepl_Wpml/admin
 * @author     Dhanuka Gunarathna <dhanuka@hashcodeab.se>
 */
class Deepl_Wpml_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string $plugin_name       The name of this plugin.
	 * @param      string $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version     = $version;
	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 * @param string $hook_suffix .
	 */
	public function deepl_wpml_enqueue_scripts( $hook_suffix ) {

		if ( 'wpml_page_tm/menu/translations-queue' === $hook_suffix ) {
			wp_enqueue_script( 'wpml-deepl', plugin_dir_url( __FILE__ ) . 'js/wpml-deepl.js', array( 'jquery' ), filemtime( plugin_dir_path( __FILE__ ) . 'js/wpml-deepl.js' ), true );

			$deepl_data = array(
				'ajaxurl'    => admin_url( 'admin-ajax.php' ),
				'version'    => $this->version,
				'root_id'    => $this->plugin_name,
				'rest_root'  => esc_url_raw( rest_url() ),
				'rest_nonce' => wp_create_nonce( 'wp_rest' ),
			);

			wp_set_script_translations( $this->plugin_name, $this->plugin_name );
			wp_localize_script( 'wpml-deepl', 'deepl_ajax_data', $deepl_data );
		}
	}

	/**
	 * DeepL translations endpoint.
	 *
	 * @since    1.0.0
	 */
	public function deepl_wpml_get_deepl_translations_endpoint() {
		register_rest_route(
			'deeplwpml/v1',
			'/getdeepltranslations',
			array(
				array(
					'methods'             => WP_REST_Server::READABLE,
					'callback'            => array( $this, 'deepl_wpml_get_deepl_translations_endpoint_callback' ),
					'permission_callback' => array( $this, 'deepl_wpml_rest_api_user_permissions' ),
				),
			)
		);
	}

	/**
	 * DeepL translations endpoint callback.
	 *
	 * @param    array $request request array.
	 * @since    1.0.0
	 */
	public function deepl_wpml_get_deepl_translations_endpoint_callback( $request ) {

		$orig_text   = $request->get_param( 'orig_text' );
		$target_lang = $request->get_param( 'target_lang' );

		$data    = array();
		$success = false;
		$message = '';

		$deepl_api_key = get_field( 'deepl_api_key', 'option' );

		if ( ! empty( $deepl_api_key ) ) {

			require_once plugin_dir_path( __DIR__ ) . 'admin/includes/deepl/vendor/autoload.php';

			$translator = new \DeepL\Translator( $deepl_api_key );

			$result = $translator->translateText( $orig_text, null, $target_lang );

			if ( ! empty( $result ) && isset( $result->text ) ) {
				$success = true;
			}

			$data = array(
				'result' => $result,
			);

		}

		$response = rest_ensure_response(
			array(
				'data'    => $data,
				'success' => $success,
				'message' => $message,
			)
		);

		return $response;
	}

	/**
	 * Register plugin settings page using ACF.
	 *
	 * @since    1.0.0
	 */
	public function deepl_wpml_register_settings_page() {

		if ( ! function_exists( 'acf_add_options_page' ) ) {
			return;
		}

		$option_page = acf_add_options_page(
			array(
				'page_title' => __( 'Deepl WPML Settings', 'deepl-wpml' ),
				'menu_title' => __( 'Deepl WPML Settings', 'deepl-wpml' ),
				'menu_slug'  => 'deepl-wpml-settings',
				'capability' => 'edit_posts',
				'redirect'   => false,
			)
		);
	}

	/**
	 * ACF field groups.
	 *
	 * @since 1.0.0
	 */
	public function deepl_wpml_acf_field_groups() {

		require_once plugin_dir_path( __DIR__ ) . 'admin/includes/acf/deepl-settings.php';
	}

	/**
	 * Check user permissions.
	 *
	 * @param    array $request request array.
	 * @since    1.0.0
	 */
	public function deepl_wpml_rest_api_user_permissions( $request ) { //phpcs:ignore
		return current_user_can( 'edit_posts' );
	}
}
