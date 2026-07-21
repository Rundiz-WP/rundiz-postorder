<?php
/**
 * Loader class. This class will load anything for example: views, template, configuration file.
 * 
 * @package rundiz-postorder
 * @author Vee W.
 * @license http://opensource.org/licenses/MIT MIT
 */


namespace RundizPostOrder\App\Libraries;


if (!defined('ABSPATH')) {
    exit();
}


if (!class_exists('\\RundizPostOrder\\App\\Libraries\\Loader')) {
    /**
     * Loader class for load template, view file, config file, etc.
     */
    class Loader
    {


        /**
         * Automatic look into those controllers and register to the main App class to make it works.<br>
         * The controllers that will be register must implement RundizPostOrder\App\Controllers\ControllerInterface to have registerHooks() method in it, otherwise it will be skipped.
         */
        public function autoRegisterControllers()
        {
            $this_plugin_dir = dirname(RUNDIZPOSTORDER_FILE);
            $file_list = $this->getClassFileList($this_plugin_dir . DIRECTORY_SEPARATOR . 'App' . DIRECTORY_SEPARATOR . 'Controllers');

            if (is_array($file_list)) {
	            foreach ($file_list as $file) {
	                $this_file_classname = '\\RundizPostOrder' . str_replace([$this_plugin_dir, '.php', '/'], ['', '', '\\'], $file);
	                if (class_exists($this_file_classname)) {
	                    $TestClass = new \ReflectionClass($this_file_classname);
	                    if (
	                        !$TestClass->isAbstract() && 
	                        !$TestClass->isTrait() && 
	                        $TestClass->implementsInterface('\\RundizPostOrder\\App\\Controllers\\ControllerInterface')
	                    ) {
	                        $ControllerClass = new $this_file_classname();
	                        if (method_exists($ControllerClass, 'setLoader')) {
	                            $ControllerClass->setLoader($this);
	                        }
	                        $ControllerClass->registerHooks();
	                        unset($ControllerClass);
	                    }
	                    unset($TestClass);
	                }
	                unset($this_file_classname);
	            }// endforeach;
                unset($file);
            }

            unset($file_list, $this_plugin_dir);
        }// autoRegisterControllers


        /**
         * Get file list that may contain class in specific path.
         * 
         * @param string $path The full path without trailing slash.
         * @return array Return indexed array of file list.
         */
        protected function getClassFileList($path)
        {
            $Di = new \RecursiveDirectoryIterator($path, \RecursiveDirectoryIterator::SKIP_DOTS);
            $It = new \RecursiveIteratorIterator($Di);
            unset($Di);

            $file_list = [];
            foreach ($It as $file) {
                $file_list[] = $file;
            }// endforeach;
            unset($file, $It);
            natsort($file_list);

            return $file_list;
        }// getClassFileList


        /**
         * Get load view contents by return, not display it.
         * 
         * @since 1.0.3
         * @see `loadView()` method for more details.
         * @param string $view_name Views file name without extension.
         * @param array $data Array data for send its key as variable into view.
         * @param string $require_once Use include or include_once? If true, use include_once.
         * @return string
         */
        public function getLoadView($view_name, array $data = [], $require_once = false)
        {
            ob_start();
            $this->loadView($view_name, $data, $require_once);
            $output = ob_get_contents();
            ob_end_clean();
            return $output;
        }// getLoadView


        /**
         * Load views.
         * 
         * @param string $view_name View file name, refer from app/Views folder.
         * @param array $data For send data variable to view.
         * @param bool $require_once Set to `true` to use `include_once`, `false` to use `include`. Default is `false`.
         * @return bool Return `true` if success loading.
         * @throws \Exception Throws the error if views file was not found.
         */
        public function loadView($view_name, array $data = [], $require_once = false)
        {
            $view_dir = dirname(__DIR__) . '/Views/';
            $templateFile = $view_dir . $view_name . '.php';
            unset($view_dir);

            if ('' !== $view_name && file_exists($templateFile) && is_file($templateFile)) {
                // if views file was found.
                if (is_array($data)) {
                    extract($data, EXTR_PREFIX_SAME, 'dupvar_');// phpcs:ignore WordPress.PHP.DontExtract.extract_extract
                }

                if (true === $require_once) {
                    include_once $templateFile;
                } else {
                    include $templateFile;
                }

                unset($templateFile);
                return true;
            } else {
                // if views file was not found.
                // Throw the exception to notice the developers. Without translation.
                throw new \Exception(
                    esc_html(
                        sprintf(
                            'The views file was not found (%s).', 
                            str_replace(['\\', '/'], '/', $templateFile)
                        )
                    )
                );
            }
        }// loadView


    }// Loader
}
