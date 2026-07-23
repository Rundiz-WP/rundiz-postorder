<?php
/**
 * Uninstall or delete the plugin.
 * 
 * @package rundiz-postorder
 */


namespace RundizPostOrder\App\Controllers\Admin\Plugins;


if (!defined('ABSPATH')) {
    exit();
}


if (!class_exists('\\RundizPostOrder\\App\\Controllers\\Admin\\Plugins\\Uninstallation')) {
    /**
     * The controller that will be working on uninstall (delete) the plugin.
     */
    class Uninstallation implements \RundizPostOrder\App\Controllers\ControllerInterface
    {


        use \RundizPostOrder\App\AppTrait;


        /**
         * Cleanup old uninstall hook value.
         * 
         * Since v. 1.1.0, the main plugin file name had changed from rd-postorder.php to match its folder name rundiz-postorder.php.  
         * So, the value in `register_uninstall_hook()` will remain unchanged. Clean it up as it is no need anymore (using new value instead).
         * 
         * @since 1.1.3
         */
        public function cleanupOldUninstallHook()
        {
            $uninstallable_plugins = get_option('uninstall_plugins');
            if (is_array($uninstallable_plugins) && isset($uninstallable_plugins['rundiz-postorder/rd-postorder.php'])) {
                unset($uninstallable_plugins['rundiz-postorder/rd-postorder.php']);
                update_option('uninstall_plugins', $uninstallable_plugins);
            }
            unset($uninstallable_plugins);
        }// cleanupOldUninstallHook


        /**
         * Do the uninstallation action
         * 
         * - Reset all values to its default.<br>
         * - Remove option related to this plugin.
         */
        private function doUninstallAction()
        {
            // reset data in the table matched as in activate process.
            // see App\Controllers\Admin\Activate.php `activate()` method.

            // reset order number in `posts` table.
            $PostOrder = new \RundizPostOrder\App\Models\PostOrder();
            $PostOrder->setMenuOrderToOriginal();
            unset($PostOrder);

            // delete post meta that this plugin use to store its original value.
            delete_post_meta_by_key('_rd-postorder-original-menu-order');// delete on old version. @todo[rundiz] delete this line on next version 1.2+
            delete_post_meta_by_key(\RundizPostOrder\App\Models\PostOrder::POST_META_ORIG_MENUORDER_NAME);

            // remove option related to this plugin.
            delete_option($this->main_option_name);
        }// doUninstallAction


        /**
         * {@inheritDoc}
         */
        public function registerHooks()
        {
            // register uninstall hook. MUST be static method or function.
            register_uninstall_hook(RUNDIZPOSTORDER_FILE, ['\\RundizPostOrder\\App\\Controllers\\Admin\\Plugins\\Uninstallation', 'uninstall']);
        }// registerHooks


        /**
         * Uninstall or delete the plugin.
         * 
         * @global \wpdb $wpdb
         */
        public static function uninstall()
        {
            global $wpdb;
            $ThisClass = new self();

            \RundizPostOrder\App\Libraries\Debug::writeLog('Debug: RundizPostOrder uninstall() method was called.');

            if (is_multisite()) {
                // this is multi site, delete options in all sites.
                $blog_ids = get_sites(['fields' => 'ids', 'number' => 0]);
                $original_blog_id = get_current_blog_id();
                if ($blog_ids) {
                    foreach ($blog_ids as $blog_id) {
                        switch_to_blog($blog_id);
                        $ThisClass->cleanupOldUninstallHook();
                        $ThisClass->doUninstallAction();
                    }
                }
                switch_to_blog($original_blog_id);
                unset($blog_id, $blog_ids, $original_blog_id);
            } else {
                $ThisClass->cleanupOldUninstallHook();
                $ThisClass->doUninstallAction();
            }
        }// uninstall


    }// Uninstallation
}
