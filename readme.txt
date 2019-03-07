=== Rundiz PostOrder ===
Contributors: okvee
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=9HQE4GVV4KTZE
Tags: posts, order, sort, re-arrange, re arrange, rearrange, re_arrange, sortable, sort posts, order posts
Requires at least: 4.6.0
Tested up to: 5.1
Stable tag: 1.0.1
Requires PHP: 5.5
License: MIT
License URI: https://opensource.org/licenses/MIT

Re-order posts to what you want.

== Description ==
If you want to customize the post order to the other than date, id, name. For example: You want to re-arrange it to display as what you want in your agency or company website.<br>
This plugin allow you to re-arrange the post order as you wish.

Re-arrange or re-order the posts but not interfere with sticky posts. Make your web sites different.

You can re-order the single post (move up/down) or multiple posts (sortable items). Move up or down action is very helpful when you have many posts and many pages to display and you want the old post to list up to the top.

You can also disable custom post order in some category or all everywhere by adding `rd_postorder_is_working` and `rd_postorder_admin_is_working` filters and its value is boolean.
OR!!
You can disable custom post order in front page, categories in the settings menu. That's very easy.

It's clean!<br>
My plugins are always restore everything to its default value and cleanup. I love clean db and don't let my plugins left junk in your db too.

It's completely free!<br>
It's not the "pay for premium feature" or freemium. It's free and no ADs. However, if you like it please donate to help me buy some food.

= System requirement =
PHP 5.5 or higher<br>
WordPress 4.6.0 or higher

== Installation ==
1. Upload "rd-postorder" folder to the "/wp-content/plugins/" directory.
2. Activate the plugin through the "Plugins" menu in WordPress.
3. Done.

== Frequently Asked Questions ==
= Is multisite support? =
Yes, of course.

= Support for re-order the pages or custom post type? =
No, it doesn't support for now.

= Does it gonna be mess if I uninstall this plugin? =
No, on uninstallation or delete the plugin, it will be reset the *menu_order* to zero which is WordPress default value for post content.

= How to disable custom order in some category? =
Create your plugin and conditions whatever you want such as `is_category(['your','category','id','or','name'])` and then add this code `add_filter('rd_postorder_is_working', '__return_false');` to disable custom order in the categories you choose.
If you want to enable, just remove the filter or change from `__return_false` to `__return_true`.
Please note that to hook into this filter in the theme some times it might not work due to `pre_get_posts` limitation on the template. See more at https://codex.wordpress.org/Plugin_API/Action_Reference/pre_get_posts .
For anyone who use this plugin v 0.8 or newer, there is a settings page that you can check front page or categories to disable custom order. To do this, go to Settings > Rundiz PostOrder menu.

= How to disable custom order in admin list post page? =
Same as disable custom order in the front-end. Add this filter hook into your theme or plugin. `add_filter('rd_postorder_admin_is_working', '__return_false');`
Please note that to hook into this filter in the theme some times it might not work due to `pre_get_posts` limitation on the template. See more at https://codex.wordpress.org/Plugin_API/Action_Reference/pre_get_posts .

== Screenshots ==
1. Front end re-order with sticky post.
2. Admin re-order page.
3. Re-ordering action.

== Changelog ==
= 1.0.1 =
2019-03-07

* Update WPListTable class to work with WP 5.1+.

= 1.0 =
2019-01-23

* Fix PHP required version.
* Update required at least (WordPress).
* Moved previous version change log to changelog.md file.