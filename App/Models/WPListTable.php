<?php
/**
 * This file has been copied from wp-admin/includes/class-wp-list-table.php
 * 
 * phpcs:disable
 *
 * Administration API: WP_List_Table class
 *
 * @package WordPress
 * @subpackage List_Table
 * @since 3.1.0
 */


namespace RundizPostOrder\App\Models;


if (!class_exists(__NAMESPACE__ . '\WPListTable', false)) {
    global $wp_version;

    if (preg_match('/^(\d+\.\d+)/', $wp_version, $matches)) {
        $major_minor = $matches[1]; // grab only number.number. for example: 7.1
    } else {
        $major_minor = $wp_version;
    }

    // Use class alias to extends differently based on WordPress version.
    // If you use WP 7.1 list table class on WP 7.0, the design on small screen will be completely borken because the core CSS of WP 7.0 is different with 7.1.
    // The same goes for WP 7.0 list table class on WP 7.1.
    if (version_compare($major_minor, '7.1', '>=')) {
        class_alias(__NAMESPACE__ . '\WPListTable\WPListTable71', __NAMESPACE__ . '\WPListTable');
    } else {
        class_alias(__NAMESPACE__ . '\WPListTable\WPListTableMax70', __NAMESPACE__ . '\WPListTable');
    }
}

if (true === false) {
    /**
     * IDE-only stub — never actually loaded at runtime.
     * class_alias() above handles the real resolution.
     */
    class WPListTable extends \WP_List_Table {}
}
