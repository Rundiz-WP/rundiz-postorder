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
         * Run the WP plugin app.
         */
        public function run()
        {
            // Initialize the loader class.
            $this->Loader = new \RundizPostOrder\App\Libraries\Loader();
            $this->Loader->autoRegisterControllers();
        }// run


    }// App
}
