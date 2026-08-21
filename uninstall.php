<?php
/**
 * Uninstall script for this plugin.
 *
 * WordPress runs this file when the user deletes the plugin from the admin UI.
 * If this file exists, any callback registered via `register_uninstall_hook()` is skipped.
 *
 * @package rundiz-postorder
 * @link https://developer.wordpress.org/plugins/the-basics/uninstall-methods/ Reference 1.
 * @link https://developer.wordpress.org/reference/functions/register_uninstall_hook/ Reference 2.
 */


// Bail if WordPress did not invoke this file as an uninstall.
if (!defined('WP_UNINSTALL_PLUGIN')) {
    exit(1);
}

// require main plugin file to use its autoload.
require 'rundiz-postorder.php';

// write the log for easy debug.
\RundizPostOrder\App\Libraries\Debug::writeLog('Debug: RundizPostOrder uninstall.php file was called.');

\RundizPostOrder\App\Controllers\Admin\Plugins\Uninstallation::uninstall();
