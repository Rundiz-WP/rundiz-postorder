=== Rundiz PostOrder ===
Contributors: okvee
Tags: posts, order, sort, re-arrange, sortable
Tested up to: 7.0
Stable tag: 1.1.1
License: MIT
License URI: https://opensource.org/licenses/MIT
Requires at least: 4.7.0
Requires PHP: 5.5

Re-order posts to what you want.

== Description ==
If you want to customize the post order to the other than date, id, name. For example: You want to re-arrange it to display as what you want in your agency or company website.  
This plugin allow you to re-arrange the post order as you wish.

Re-arrange or re-order the posts but not interfere with sticky posts. Make your web sites different.

You can re-order by one step (move up/down) or multiple steps (sortable items - drag and drop).  
Re-order across the page by drag and drop to the top or bottom and then use move up and down to make it re-order across the page.

You can also disable custom post order in some category or all everywhere by adding `rundiz_postorder_is_working` and `rundiz_postorder_admin_is_working` filters and its value is boolean.
OR!!  
You can do that in the settings menu. That's very easy.

Polylang or multilingual supported.  
In the new version, you can use language switcher to switch and list only posts on selected language and then re-order them.

It's clean!  
My plugins are always restore everything to its default value and cleanup. I love clean db and don't let my plugins left junk in your db too.

This project is maintain by <a href="https://rundiz.com" target="author_site">Rundiz.com</a>. Feel free to rate and comments.  
Please <a href="https://rundiz.com/en/donate" target="donate">donate</a> to support the developer.

Tested up to PHP 8.5.

== Installation ==
1. Upload "rundiz-postorder" folder to the "/wp-content/plugins/" directory.
2. Activate the plugin through the "Plugins" menu in WordPress.
3. Done.

== Frequently Asked Questions ==
= Is multisite support? =
Yes, of course.

= Is multilingual support? =
Yes, it is supported Polylang plugin for list the posts only selected language or all languages. For the other multilingual plugins, it should work too.

= Support for re-order the pages or custom post type? =
No, it doesn't support custom post type.

= Does it gonna be mess if I uninstall this plugin? =
No, on uninstall or delete the plugin, it will be reset the *menu_order* to zero (0) which is WordPress default value for post content.

= How to disable custom order in some category? =
Create your plugin and conditions whatever you want such as `is_category(['your','category','id','or','name'])` and then add this code `add_filter('rundiz_postorder_is_working', '__return_false');` to disable custom order in the categories you choose.
If you want to enable, just remove the filter or change from `__return_false` to `__return_true`.
Please note that to hook into this filter in the theme some times it might not work due to `pre_get_posts` limitation on the template. See more at https://codex.wordpress.org/Plugin_API/Action_Reference/pre_get_posts .
For anyone who use this plugin v 0.8 or newer, there is a settings page that you can check front page or categories to disable custom order. To do this, go to Settings > Rundiz PostOrder menu.

= How to disable custom order in admin list post page? =
Same as disable custom order in the front-end. Add this filter hook into your theme or plugin. `add_filter('rundiz_postorder_admin_is_working', '__return_false');`
Please note that to hook into this filter in the theme some times it might not work due to `pre_get_posts` limitation on the template. See more at https://codex.wordpress.org/Plugin_API/Action_Reference/pre_get_posts .

== Screenshots ==
1. Front end re-order with sticky post.
2. Admin re-order page.
3. Re-ordering action.

== Changelog ==
= 1.1.1 =
2026-04-06

* Update translation with template.

= 1.1.0 =
2026-03-25

* Rename main plugin file, namespace, text domain to match plugin slug. The name `rd-postorder` was used from the beginning but was renamed to `rundiz-postorder` by WordPress.org plugin system.
* Update as per PHPCS's instruction.
* Rename hooks from `rd_postorder_is_working` to be `rundiz_postorder_is_working`.
* Rename hooks from `rd_postorder_admin_is_working` to be `rundiz_postorder_admin_is_working`.
* Rename menu slugs, HTML attributes such as classes, ids, data-* to match plugin slug.

After upgraded to this version, you may need to re-activate this plugin again.

= 1.0.10 =
2025-12-18

* Minor update.

= 1.0.9 =
2025-08-25

* Fix re-order AJAX return incorrect number of table rows.
* Update prevent enter key on post order number field.

= 1.0.8 =
2025-08-24

* Update listing to 50 for easier re-order.
* Update to save the original value of posts `menu_order` and restore on uninstall or use settings page.
* Add reset original, reset zero to settings page on per site, and main network site.

= 1.0.7 =
2025-03-18

* Update load text domain to be inside `init` hook.

= 1.0.6 =
2025-01-13

* Update alter post query and add new setting to disable custom order on admin pages.

== Upgrade Notice ==

= 1.1.0 =

After upgraded to this version, you may need to re-activate this plugin again.
