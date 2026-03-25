<?php
/**
 * Debugging.
 * 
 * @package rundiz-postorder
 * @author Vee W.
 * @license http://opensource.org/licenses/MIT MIT
 */


namespace RundizPostOrder\App\Libraries;


if (!class_exists('\\RundizPostOrder\\App\\Libraries\\Debug')) {
    /**
     * Debug class.
     */
    class Debug
    {


        /**
         * Write debug log.
         * 
         * @param mixed $message Log message.
         */
        public static function writeLog($message)
        {
            if (
                (defined('WP_DEBUG') && WP_DEBUG === true) || 
                (defined('WP_DEBUG_LOG') && WP_DEBUG_LOG === true)
            ) {
                if (is_array($message) || is_object($message)) {
                    error_log(print_r($message, true));// phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_error_log, WordPress.PHP.DevelopmentFunctions.error_log_print_r
                } else {
                    error_log($message);// phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_error_log
                }
            }
        }// writeLog


    }// Debug
}
