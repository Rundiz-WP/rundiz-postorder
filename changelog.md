# Change log

## Version 1.0.x

### 1.0.5
2024-12-11

* Update reset/restart numbering all posts order.
    Use default WordPress front page order including sticky at the top, and all other post statuses.

### 1.0.4
2024-07-01

* Update priority for hook `pre_get_posts` to be lower (higher number) to let other plugins hook work with this plugin either.

### 1.0.3
2022-02-01

* Removed no need check requirement, already checked on WP core.
* Remove donation link.
* Fix activate/uninstall process.
* Add network settings (multisite).
* Move PHP files into sub folders. Each sub folder represent admin menu.
* Update JS of re-order page to class that supported in newer web browser.
* Add view link (to front page) in re-order posts page.
* Move ajax actions to its controller.
* Move admin help tab contents to views file.
* Use `wp_send_json` instead of `echo` and `wp_die` instead of `exit`.
* Fix call to hook `wp_insert_post`.
* Update hook new post class to always update scheduled posts number.
* Make Polylang supported (on selected language and list posts).
* Fix alter post on front pages main query only.
* Update translation.

### 1.0.2
2021-12-14

* Update WPListTable class based on WordPress 5.8.2.

### 1.0.1
2019-03-07

* Update WPListTable class to work with WP 5.1+.

### 1.0.0
2019-01-23

* Fix PHP required version.
* Update required at least (WordPress).
* Moved previous version change log to changelog.md file.

## Version 0.x

### 0.9.1
2018-12-08

* Update Font Awesome to 4.7.0.
* Add requires PHP version.
* Add translation template file (.POT).
* Add translators help message.
* Fix bug disable order is not working on category page.

### 0.9
2018-01-04

* Add some hook for future use. This cannot work if it is just in the next update, it must stay in current update and then next update will work.

### 0.8
2017-03-25

* Add settings to disable custom order in front page.
* Add settings to disable custom order for selected categories.

### 0.7
2016-11-13

* Fix current page input that was not work.

### 0.6.1
2106-10-23

* Add debugger class to debug uninstallation.

### 0.6
2016-10-23

* Tested with multisite enabled and it works!
* Fix uninstall for multisite enabled.

### 0.5
2016-10-22

* Add support for filters `rd_postorder_is_working` and `rd_postorder_admin_is_working`

### 0.4
2016-10-19

* Change from buttons actions to bulk actions
* Add manually change order numbers
* Move help text to help screen

### 0.3
2016-10-13

* Fix uninstall error
* Fix single quote in the input array but this maybe the cause of sort items wrong number.

### 0.2
2016-10-13

* Fix ajax replace list table
* Fix PHP notices

### 0.1
2016-10-11

* The beginning.