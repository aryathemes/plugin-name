<?php
/**
 * Plugin Name: Plugin Name
 * Plugin URI: https://example.com/plugin-name
 * Description: Description of the plugin.
 * Author: Your Name
 * Author URI: https://example.com
 * Version: 1.0.0
 * License: GNU General Public License v2 or later
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain: plugin-name
 * Domain Path: /languages
 *
 * @package   PluginName
 * @author    Your Name
 * @copyright 2019 Your Name or Company Name
 * @license   GPL-2.0-or-later
 */

/* PHP namespace autoloader */
require_once( dirname( __FILE__ ) . '/vendor/autoload.php' );

\PluginName\Loader::init( plugin_basename( __FILE__ ) );
