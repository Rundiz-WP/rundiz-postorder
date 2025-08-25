<?php
/**
 * Plugin Name: Rundiz PostOrder
 * Plugin URI: https://rundiz.com/?p=319
 * Description: Re-order posts to what you want.
 * Version: 1.0.9
 * Requires at least: 4.7.0
 * Requires PHP: 5.5
 * Author: Vee Winch
 * Author URI: https://rundiz.com
 * License: MIT
 * License URI: http://opensource.org/licenses/MIT
 * Text Domain: rd-postorder
 * Domain Path: /App/languages/
 *
 * @package rundiz-postorder
 */


// Define this plugin main file path.
if (!defined('RDPOSTORDER_FILE')) {
    define('RDPOSTORDER_FILE', __FILE__);
}

if (!defined('RDPOSTORDER_VERSION')) {
    $pluginData = (function_exists('get_file_data') ? get_file_data(__FILE__, ['Version' => 'Version']) : null);
    $pluginVersion = (isset($pluginData['Version']) ? $pluginData['Version'] : date('Ym'));
    unset($pluginData);
    define('RDPOSTORDER_VERSION', $pluginVersion);
    unset($pluginVersion);
}


// Plugin's autoload.
require __DIR__ . DIRECTORY_SEPARATOR . 'autoload.php';


// Run this wp plugin.
$App = new \RdPostOrder\App\App();
$App->run();
unset($App);


// That's it. Everything is load and works inside the main App class.