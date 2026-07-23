<?php
/**
 * Hooks into Plugins page.
 * 
 * @package rundiz-postorder
 * @since 1.1.3 Moved from Controllers/Admin/Plugins/PluginMetaAndLinks.php
 */


namespace RundizPostOrder\App\Controllers\Admin;


if (!defined('ABSPATH')) {
    exit();
}


if (!class_exists('\\RundizPostOrder\\App\\Controllers\\Admin\\Plugins')) {
    /**
     * Plugin class that will work on admin list plugins page.
     * 
     * @since 1.1.3
     */
    class Plugins implements \RundizPostOrder\App\Controllers\ControllerInterface
    {


        /**
         * Add links to plugin actions area. For example: xxxbefore | Activate | Edit | Delete | xxxafter
         * 
         * @staticvar string $plugin The plugin file name.
         * @param array $actions Current plugin actions. (including deactivate, edit).
         * @param string $plugin_file The plugin file for checking.
         * @return array Return modified links
         */
        public function actionLinks(array $actions, $plugin_file)
        {
            static $plugin;

            if (!isset($plugin)) {
                $plugin = plugin_basename(RUNDIZPOSTORDER_FILE);
            }

            if ($plugin === $plugin_file) {
                $link = [];
                if (current_user_can('manage_options') && !is_network_admin()) {
                    $link['settings'] = '<a href="' . esc_url(get_admin_url(null, 'options-general.php?page=' . \RundizPostOrder\App\Controllers\Admin\Settings\Settings::MENU_SLUG)) . '">' . __('Settings', 'rundiz-postorder') . '</a>';
                    $actions = array_merge($link, $actions);
                }
                if (current_user_can('manage_network_plugins') && is_network_admin()) {
                    $link['networksettings'] = '<a href="' . esc_url(network_admin_url('settings.php?page=' . \RundizPostOrder\App\Controllers\Admin\Settings\MultisiteSettings::MENU_SLUG)) . '">' . __('Settings', 'rundiz-postorder') . '</a>';
                    $actions = array_merge($link, $actions);
                }
                //$actions['after_actions'] = '<a href="#" onclick="return false;">'.__('After Actions', 'rd-yte').'</a>';
                unset($link);
            }

            return $actions;
        }// actionLinks


        /**
         * {@inheritDoc}
         * 
         * @since 1.1.3
         */
        public function registerHooks()
        {
            // add filter action links. this will be displayed in actions area of plugin page. for example: xxxbefore | Activate | Edit | Delete | xxxafter
            add_filter('plugin_action_links', [$this, 'actionLinks'], 10, 5);
            add_filter('network_admin_plugin_action_links_' . plugin_basename(RUNDIZPOSTORDER_FILE), [$this, 'actionLinks'], 10, 5);
            // add filter to row meta. (in plugin page below description). for example: By xxx | Visit plugin site | xxxafter
            add_filter('plugin_row_meta', [$this, 'rowMeta'], 10, 2);
        }// registerHooks


        /**
         * Add links to row meta that is in Plugins page under plugin description. For example: xxxbefore | By xxx | Visit plugin site | xxxafter
         * 
         * @staticvar string $plugin The plugin file name.
         * @param array $links Current meta links
         * @param string $file The plugin file name for checking.
         * @return array Return modified links.
         */
        public function rowMeta(array $links, $file)
        {
            static $plugin;

            if (!isset($plugin)) {
                $plugin = plugin_basename(RUNDIZPOSTORDER_FILE);
            }

            if ($plugin === $file) {
                $after_link = [];

                /*$configValues = $this->getOptions();
                if (
                    is_array($configValues) && 
                    array_key_exists('rdsfw_plugin_db_version', $configValues) && 
                    is_scalar($configValues['rdsfw_plugin_db_version']) && 
                    !empty($configValues['rdsfw_plugin_db_version'])
                ) {
                    /* translators: %s The DB version of this plugin. */
                   /* $after_link[] = sprintf(__('DB version %s', 'plugin-template'), $configValues['rdsfw_plugin_db_version']);
                }
                unset($configValues);*/

                 $after_link[] = '<a href="https://rundiz.com/en/donate" target="donate">' . __('Donate', 'rundiz-postorder') . '</a>';
                $links = array_merge($links, $after_link);
                unset($after_link);
            }

            return $links;
        }// rowMeta


    }// Plugins
}
