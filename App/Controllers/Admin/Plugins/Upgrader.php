<?php
/**
 * Upgrade or update the plugin action.
 * 
 * @package rundiz-postorder
 * @since 1.1.3
 * @license http://opensource.org/licenses/MIT MIT
 */


namespace RundizPostOrder\App\Controllers\Admin\Plugins;


if (!defined('ABSPATH')) {
    exit();
}


if (!class_exists('\\RundizPostOrder\\App\\Controllers\\Admin\\Plugins\\Upgrader')) {
    /**
     * Plugin upgrader class.
     * 
     * @since 1.1.3
     */
    class Upgrader implements \RundizPostOrder\App\Controllers\ControllerInterface
    {


        /**
         * {@inheritDoc}
         * 
         * @since 1.1.3
         */
        public function registerHooks()
        {
            // On update/upgrade plugin completed, set transient and let `detectPluginUpdate()` work.
            add_action('upgrader_process_complete', [$this, 'updateProcessComplete'], 10, 2);
        }// registerHooks


        /**
         * After update plugin completed.
         * 
         * This method will be called while running the current version of this plugin, not the new one that just updated.
         * For example: You are running 1.0 and just updated to 2.0. The 2.0 version will not working here yet but 1.0 is working.
         * So, any code here will not work as the new version. Please be aware!
         * 
         * This method will add the transient to be able to detect updated and run the manual update in `detectPluginUpdate()` method.
         * 
         * @since 1.1.3 Moved from `Controllers\Admin\Plugins\Activation->updatePlugin()`.
         * @link https://developer.wordpress.org/reference/hooks/upgrader_process_complete/ Reference.
         * @link https://developer.wordpress.org/reference/classes/wp_upgrader/ Reference.
         * @param \WP_Upgrader $upgrader The `\WP_Upgrader` class.
         * @param array $hook_extra Array of bulk item update data.
         */
        public function updateProcessComplete(\WP_Upgrader $upgrader, array $hook_extra)
        {
            if (is_array($hook_extra) && array_key_exists('action', $hook_extra) && array_key_exists('type', $hook_extra) && array_key_exists('plugins', $hook_extra)) {
                if ('update' === $hook_extra['action'] && 'plugin' === $hook_extra['type'] && is_array($hook_extra['plugins']) && !empty($hook_extra['plugins'])) {
                    $this_plugin = plugin_basename(RUNDIZPOSTORDER_FILE);
                    foreach ($hook_extra['plugins'] as $key => $plugin) {
                        if ($this_plugin === $plugin) {
                            // if this plugin is in the updated plugins.
                            $this_plugin_updated = true;
                            break;
                        }
                    }// endforeach;
                    unset($key, $plugin, $this_plugin);

                    if (isset($this_plugin_updated) && true === $this_plugin_updated) {
                        \RundizPostOrder\App\Libraries\Debug::writeLog('Debug: RundizPostOrder updatePlugin() method was called.');

                        global $wpdb;
                        // do the update plugin task.
                        // leave this for the future use, if not then this code inside next update cannot working.
                    }// endif; $this_plugin_updated
                }// endif update plugin and plugins not empty.
            }// endif; $hook_extra
        }// updateProcessComplete


    }// Upgrader
}
