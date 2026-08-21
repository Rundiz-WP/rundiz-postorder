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
         * @link https://developer.wordpress.org/reference/hooks/plugin_action_links/ Reference.
         * @link https://developer.wordpress.org/reference/hooks/network_admin_plugin_action_links_plugin_file/ Reference.
         * @staticvar string $plugin The plugin file name.
         * @param array $actions An array of plugin action links.
         * @param string $plugin_file Path to the plugin file relative to the plugins directory.
         * @param array $plugin_data An array of plugin data. See `get_plugin_data()` and the `'plugin_row_meta'` filter for the list of possible values.
         * @param string $context The plugin context. By default this can include `'all'`, `'active'`, `'inactive'`, `'recently_activated'`, `'upgrade'`, `'mustuse'`, `'dropins'`, and `'search'`.
         * @return array Return modified links
         */
        public function actionLinks(array $actions, $plugin_file, array $plugin_data, $context = 'all')
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
            add_filter('plugin_action_links', [$this, 'actionLinks'], 10, 4);
            add_filter('network_admin_plugin_action_links_' . plugin_basename(RUNDIZPOSTORDER_FILE), [$this, 'actionLinks'], 10, 4);
            // add filter to row meta. (in plugin page below description). for example: By xxx | Visit plugin site | xxxafter
            add_filter('plugin_row_meta', [$this, 'rowMeta'], 10, 4);
        }// registerHooks


        /**
         * Add links to row meta that is in Plugins page under plugin description. For example: xxxbefore | By xxx | Visit plugin site | xxxafter
         * 
         * @link https://developer.wordpress.org/reference/hooks/plugin_row_meta/ Reference.
         * @staticvar string $plugin The plugin file name.
         * @param array $plugin_meta An array of the plugin's metadata, including the version, author, author URI, and plugin URI.
         * @param string $plugin_file Path to the plugin file relative to the plugins directory.
         * @param array $plugin_data An array of plugin data.
         * @param string $status Status filter currently applied to the plugin list. Possible values are: `'all'`, `'active'`, `'inactive'`, `'recently_activated'`, `'upgrade'`, `'mustuse'`, `'dropins'`, `'search'`, `'paused'`, `'auto-update-enabled'`, `'auto-update-disabled'`.
         * @return array Return modified links.
         */
        public function rowMeta(array $plugin_meta, $plugin_file, array $plugin_data, $status = 'all')
        {
            static $plugin;

            if (!isset($plugin)) {
                $plugin = plugin_basename(RUNDIZPOSTORDER_FILE);
            }

            if ($plugin === $plugin_file) {
                $after_link = [];
                $after_link[] = '<a href="https://rundiz.com/en/donate" target="donate">' . __('Donate', 'rundiz-postorder') . '</a>';
                $plugin_meta = array_merge($plugin_meta, $after_link);
                unset($after_link);
            }

            return $plugin_meta;
        }// rowMeta


    }// Plugins
}
