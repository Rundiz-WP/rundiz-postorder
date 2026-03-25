<?php
/**
 * Plugin Name: Rundiz PostOrder
 * Plugin URI: https://rundiz.com/?p=319
 * Description: Re-order posts to what you want.
 * Version: 1.0.10
 * Requires at least: 4.7.0
 * Requires PHP: 5.5
 * Author: Vee Winch
 * Author URI: https://rundiz.com
 * License: MIT
 * License URI: https://opensource.org/licenses/MIT
 * Text Domain: rundiz-postorder
 * Domain Path: /App/languages/
 *
 * @package rundiz-postorder
 */


// Define this plugin main file path.
if (!defined('RUNDIZPOSTORDER_FILE')) {
    define('RUNDIZPOSTORDER_FILE', __FILE__);
}

if (!defined('RUNDIZPOSTORDER_VERSION')) {
    $rundiz_postorder_pluginData = (function_exists('get_file_data') ? get_file_data(__FILE__, ['Version' => 'Version']) : null);
    $rundiz_postorder_pluginVersion = (isset($rundiz_postorder_pluginData['Version']) ? $rundiz_postorder_pluginData['Version'] : date('Ym'));
    unset($rundiz_postorder_pluginData);
    define('RUNDIZPOSTORDER_VERSION', $rundiz_postorder_pluginVersion);
    unset($rundiz_postorder_pluginVersion);
}


// Plugin's autoload.
require __DIR__ . DIRECTORY_SEPARATOR . 'autoload.php';


// Run this wp plugin.
$rundiz_postorder_App = new \RundizPostOrder\App\App();
$rundiz_postorder_App->run();
unset($rundiz_postorder_App);


// That's it. Everything is load and works inside the main App class.