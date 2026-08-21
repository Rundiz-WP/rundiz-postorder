<?php
/**
 * Main app class. Extend this class if you want to use any method of this class.
 * 
 * @package rundiz-postorder
 * @author Vee W.
 * @license http://opensource.org/licenses/MIT MIT
 */


namespace RundizPostOrder\App;


if (!defined('ABSPATH')) {
    exit();
}


if (!class_exists('\\RundizPostOrder\\App\\App')) {
    /**
     * Plugin application main entry class.
     */
    class App
    {


        use AppTrait;


        /**
         * Load text domain. (Language files)
         * 
         * @since 1.1.4
         * @link https://make.wordpress.org/core/2025/03/12/i18n-improvements-6-8/ The load text domain function is not need if requires WP 6.8+
         * @link https://core.trac.wordpress.org/ticket/64249 Follow-up bug fix that auto load translation file not working on multi-site enabled.
         */
        public function loadLanguage()
        {
            load_plugin_textdomain('rundiz-postorder', false, dirname(plugin_basename(RUNDIZPOSTORDER_FILE)) . '/App/languages/');
        }// loadLanguage


        /**
         * Run the WP plugin app.
         */
        public function run()
        {
            add_action('init', function () {
                // @link https://codex.wordpress.org/Function_Reference/load_plugin_textdomain Reference.
                // load language of this plugin.
                $this->loadLanguage();
            });

            // Initialize the loader class.
            $this->Loader = new \RundizPostOrder\App\Libraries\Loader();
            $this->Loader->autoRegisterControllers();
        }// run


    }// App
}
