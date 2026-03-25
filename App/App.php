<?php
/**
 * The main application file for this plugin.
 * 
 * @package rundiz-postorder
 * @author Vee W.
 * @license http://opensource.org/licenses/MIT MIT
 */


namespace RundizPostOrder\App;

if (!class_exists('\\RundizPostOrder\\App\\App')) {
    /**
     * The main application class for this plugin.<br>
     * This class is the only main class that were called from main plugin file and it will be load any hook actions/filters to work inside the run() method.
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
