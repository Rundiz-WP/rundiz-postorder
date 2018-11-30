<?php
/**
 * Uninstallation the plugin.
 * 
 * @package rundiz-postorder
 * @link https://developer.wordpress.org/plugins/the-basics/uninstall-methods/ Reference 1.
 * @link https://developer.wordpress.org/reference/functions/register_uninstall_hook/ Reference 2.
 */


if (!defined('WP_UNINSTALL_PLUGIN')) {
    die;
}

// write the log for easy debug.
\RdPostOrder\App\Libraries\Debug::writeLog('RundizPostOrder uninstall.php file was called.');

// require main plugin file to use its autoload.
require 'rd-postorder.php';

// due to it is not working to uninstall (delete) the plugin via multisite enabled. 
// (some time is working and some time is not working. I don't know why.) 
// wordpress did not call to registered uninstall hook. 
// we have to directly call it here.
\RdPostOrder\App\Controllers\Admin\Uninstall::uninstallAction();