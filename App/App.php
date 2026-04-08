<?php
/**
 * The main application file for this plugin.
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


        use \RundizPostOrder\App\AppTrait;


        /**
         * Run the main application class (plugin).
         */
        public function run()
        {
            // Initialize the loader class.
            $this->Loader = new \RundizPostOrder\App\Libraries\Loader();
            $this->Loader->autoRegisterControllers();
        }// run


    }// App
}
