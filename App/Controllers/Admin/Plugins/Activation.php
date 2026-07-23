<?php
/**
 * Activate the plugin action.
 * 
 * @package rundiz-postorder
 */


namespace RundizPostOrder\App\Controllers\Admin\Plugins;


if (!defined('ABSPATH')) {
    exit();
}


if (!class_exists('\\RundizPostOrder\\App\\Controllers\\Admin\\Plugins\\Activation')) {
    /**
     * Plugin activation and new site activation hooks class.
     */
    class Activation implements \RundizPostOrder\App\Controllers\ControllerInterface
    {


        use \RundizPostOrder\App\AppTrait;


        /**
         * Activate the plugin by admin on WP plugin page.
         * 
         * @link https://developer.wordpress.org/reference/functions/register_activation_hook/ The function `register_activation_hook()` reference.
         * @link https://developer.wordpress.org/reference/hooks/activate_plugin/ The reference about what will be pass to callback of function `register_activation_hook()`.
         * @global \wpdb $wpdb WordPress DB class.
         * @param bool $network_wide Whether to enable the plugin for all sites in the network or just the current site. Multisite only. Default false.
         */
        public function activate($network_wide)
        {
            global $wpdb;

            \RundizPostOrder\App\Libraries\Debug::writeLog('Debug: RundizPostOrder activate() method was called.');

            if (is_multisite() && $network_wide) {
                // This site is multisite and network activate. Add/update options, create/alter tables on all sites.
                $blog_ids = get_sites(['fields' => 'ids', 'number' => 0]);
                $original_blog_id = get_current_blog_id();
                if ($blog_ids) {
                    foreach ($blog_ids as $blog_id) {
                        switch_to_blog($blog_id);
                        $this->doActivateAction();
                    }
                }
                switch_to_blog($original_blog_id);
                unset($blog_id, $blog_ids, $original_blog_id);
            } else {
                $this->doActivateAction();
            }
        }// activate


        /**
         * Do the activate plugin action. 
         * 
         * - Add order number into `posts` table.<br>
         * - Add option related to this plugin (if not exists).
         * 
         * @global \wpdb $wpdb WordPress DB class.
         */
        private function doActivateAction()
        {
            global $wpdb;

            // it is not supported manual order per category.
            // if doing that, the single post will still load previous & next post from home posts listing.
            // example code to add order number in `table_relationships` table.
            /*
            $results = $wpdb->get_results(
                'SELECT ' . 
                    '`' . $wpdb->term_relationships . '`.`object_id`, ' . 
                    '`' . $wpdb->term_relationships . '`.`term_taxonomy_id`, ' . 
                    '`' . $wpdb->term_taxonomy . '`.`term_taxonomy_id`, ' . 
                    '`' . $wpdb->term_taxonomy . '`.`term_id`, ' . 
                    '`' . $wpdb->term_taxonomy . '`.`taxonomy`, ' . 
                    '`' . $wpdb->posts . '`.`ID`, ' . 
                    '`' . $wpdb->posts . '`.`post_date`, ' . 
                    '`' . $wpdb->posts . '`.`post_name`, ' . 
                    '`' . $wpdb->posts . '`.`post_status`' . 
                    ' FROM `' . $wpdb->term_relationships . '`' . 
                    ' LEFT JOIN `' . $wpdb->term_taxonomy . '` ON `' . $wpdb->term_relationships . '`.`term_taxonomy_id` = `' . $wpdb->term_taxonomy . '`.`term_taxonomy_id`' . 
                    ' LEFT JOIN `' . $wpdb->posts . '` ON `' . $wpdb->term_relationships . '`.`object_id` = `' . $wpdb->posts . '`.`ID`' . 
                    ' WHERE `' . $wpdb->term_taxonomy . '`.`taxonomy` = \'category\'' . 
                    ' AND `' . $wpdb->posts . '`.`post_status` IN(\'' . implode('\', \'', $this->allowed_order_post_status) . '\')' . 
                    ' ORDER BY `' . $wpdb->posts . '`.`post_date` ASC',// get the oldest for number 1st to display at last page.
                OBJECT
            );
            if (is_array($results)) {
                $i_count = [];
                foreach ($results as $row) {
                    if (isset($i_count[$row->term_taxonomy_id])) {
                        $i_count[$row->term_taxonomy_id] ++;
                    } else {
                        $i_count[$row->term_taxonomy_id] = 1;
                    }
                    $wpdb->update(
                        $wpdb->term_relationships, 
                        ['term_order' => $i_count[$row->term_taxonomy_id]], 
                        ['object_id' => $row->object_id, 'term_taxonomy_id' => $row->term_taxonomy_id],
                        ['%d'],
                        ['%d', '%d']
                    );
                }// endforeach;
                unset($i_count, $row);
            }
            unset($results);
             */
            // the example code above will not be use as explained.

            // add order number into `posts` table.
            \RundizPostOrder\App\Libraries\Input::static_setPaged();
            $PostOrderM = new \RundizPostOrder\App\Models\PostOrder();
            $PostOrderM->resetAllPostsOrder(true);
            unset($PostOrderM);

            // add option related to this plugin (if not exists).
            $plugin_option = get_option($this->main_option_name);
            if (false === $plugin_option) {
                // not exists, add new.
                add_option($this->main_option_name, []);
            }
            unset($plugin_option);
            // finished activate the plugin.
        }// doActivateAction


        /**
         * {@inheritDoc}
         */
        public function registerHooks()
        {
            // register activate hook
            register_activation_hook(RUNDIZPOSTORDER_FILE, [$this, 'activate']);
        }// registerHooks


    }// Activation
}
