<?php
/**
 * The plugin bootstrap file
 *
 * @link              https://hashcodeab.se
 * @since             1.0.0
 * @package           Deepl_Wpml
 *
 * @wordpress-plugin
 * Plugin Name:       DeepL WPML
 * Plugin URI:        https://github.com/dhanukanuwan/wpml-deepl
 * Description:       Introduce DeepL API translations to ACF fields in the WPML translations editor
 * Version:           1.0.0
 * Author:            Dhanuka Gunarathna
 * Author URI:        https://hashcodeab.se/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       deepl-wpml
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'DEEPL_WPML_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-deepl-wpml-activator.php
 */
function activate_deepl_wpml() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-deepl-wpml-activator.php';
	Deepl_Wpml_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-deepl-wpml-deactivator.php
 */
function deactivate_deepl_wpml() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-deepl-wpml-deactivator.php';
	Deepl_Wpml_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_deepl_wpml' );
register_deactivation_hook( __FILE__, 'deactivate_deepl_wpml' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-deepl-wpml.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_deepl_wpml() {

	$plugin = new Deepl_Wpml();
	$plugin->run();
}
run_deepl_wpml();
