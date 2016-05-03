=== Picker ===
Contributors: lando1982
Tags: usability, picker, pick, admin, widget, Post, organize, manage
Requires at least: 3.3
Tested up to: 4.5
Stable tag: 1.0.0
License: GPLv3
License URI: http://www.gnu.org/licenses/gpl.html

Picker is a simple and flexible plugin which allow users to choose a specific post inside admin widgets page and display it in their site frontend.

== Description ==

Picker is a simple and flexible plugin which allow users to choose a specific post inside admin widgets page and display it in their site frontend.

If you need to display a specific post, not a generic list of last posts, top ranked posts, category posts, etc.. but only the one that you configure in the backend. Picker plugin makes it possible in a very quick and easy way. It adds a widget on the admin widgets page that you can use to select and show a post on your site's frontend.

Picker plugin is extensible in many of its features, such as, applying filters and action, managing layout template, etc.. moreover, plugin is based on the WordPress Transient API for caching issue.
As mentioned before, the plugin provides a way to override the default layout template. With a few lines of code, you can create your layout template (copying the default one) and completely override the plugin look&feel thanks to a complete access to all widget item data throw Picker classes.

= Widget usage =

You can use Picker plugin like all the other WordPress plugins, dragging the widget to a sidebar you can enable a Picker widget. Initially, the widget is not published on the frontend. In this way you can safely configure widget's data and then publish/display the widget.
Picker widget is composed by the following fields:

* *Publish checkbox*: flag it to publish the widget
* *Widget title*: a widget title, for backend and users only, not shown on frontend
* *Post list*: a lists of the last posts by date
* *Post search*: a search field to provide a post ID or search it if post is not in the above post list
* *Time to publish*: a datetime to set when the widget could be shown on frontend
* *Time to expire*: a datetime to set when the widget should be removed from frontend
* *Alternative URL*: an alternative URL instead the default posts permalink
* *Alternative title*: an alternative title instead the default post_title field
* *Alternative excerpt*: an alternative excerpt instead the default post_excerpt field

For some field there is a jQuery validation to check right formats. Picker plugin checks all numbers, url and datetime fields showing you an alert if data are wrong.

= Customizing layout template =

Picker plugin provide a default widget template that show a linked title (using post title), the featured image of the post and the post excerpt. Alternatively, you can manage widget template overriding default template. Picker plugin has a tool to detect the template path used for widget layout inclusion.
This is the load order of the template files:

* theme / template_path / template_name (default: theme template folder)
* theme / template_name (default: theme root folder)
* default_path / template_name (default: plugin templates folder)

= Plugin filters =

Picker plugin provides many filters to extend default behavior of the plugin core functions. This is a list of the most important available filters:

* *picker_item_title*: allow to modify picker item title
* *picker_item_excerpt*: allow to modify picker item excerpt
* *picker_item_content*: allow to modify picker item content
* *picker_template_path*: allow to modify layout template path

To better understand how you can interact with plugin filters, here are some examples.
If you want, you can modify the default <template_path> (usually "picker/" folder), with a folder in your theme root. In the following example, adding the function to your "functions.php" theme file, we are telling to Picker plugin to search template files inside a "templates/" folder in your theme root.

` function modify_picker_template_path() {
	return 'templates';
}
add_filter( 'picker_template_path', 'modify_picker_template_path' ); `

Also, you can modify the default post title (usually "post_title" field), adding for example a prefix/suffix. In the following example, adding the function to your "functions.php" theme file, we are telling to Picker plugin to call your function before return to template the item post title value.

` function modify_picker_post_title( $value ) {
	return 'my_prefix ' . $value;
}
add_filter( 'picker_item_title', 'modify_picker_post_title' ); `

= Template utility class and functions =

As described in the previous paragraph, you can customize widget template according to you site specs. Inside the template file you can use a `$picker_item` global variable to access the picker class methods:

* *get_post_data()*: get WP_Post object
* *get_permalink()*: get post permalink
* *get_title()*: get post title
* *get_excerpt($max_words = '', $use_content_if_empty = false)*: get post excerpt
* *get_content($max_words = '')*: get post content
* *get_categories($sep = ', ', $before = '', $after = '')*: get post categories as a list of category links
* *get_tags($sep = ', ', $before = '', $after = '')*: get post tags as a list of tag links
* *get_formats($sep = ', ', $before = '', $after = '')*: get post formats as a list of format links
* *has_image()*: tell if post has image
* *get_image($size = 'thumbnail', $attr = array())*: get post featured image (use `thumbnail`, `medium`, `large`, `full` default sizes or your registered new image size)
* *get_image_id()*: get post featured image ID
* using `__get` magic method you can read a custom field (eg: $picker_item->my_custom_field)

In addition to `$picker_item` global variable, you can use custom variables values, such as:

* *get_custom_url()*: the provided alternative post URL
* *get_custom_title()*: the provided alternative post title
* *get_custom_excerpt()*: the provided alternative post excerpt
* *get_widget_sidebar()*: which sidebar contains the the widget
* *get_widget_order()*: the widget order (position)

In the template file are available special variables, for now:

* *$use_cache*: boolean

Very important is the `widget_sidebar` variable. With this value you can display a different widget layout according to which sidebar contains the widget.

= Caching management =

To increase performance and reduce database queries Picker plugin use a persistent caching management. To get a persistent cache without using external plugins Picker plugin uses [WordPress Transient API](http://codex.wordpress.org/Transients_API).
Anytime WordPress display Picker plugin on a site frontend page a calls Picker plugin widget management. First of all, Picker plugin look for a cached object into Transient cache, if found it, gets data, prepares HTML and provides it to frontend.
If data isn't in Transient cache, Picker plugin go on with widget date and status validation, then, after looked up post into database, create Picker item object that contains all the widget post data, and in the end like a cached object prepares HTML and provides it to frontend.
Before concluding by Picker plugin widget management, the Picker item is saved to Transient cache. Only the Picker item is saved to cache, no HTML neither frontend logic are saved to cache. The goal to Picker caching management is only to reduce database usage.
Picker plugin cache has many expiration rules, the most important are:

* widgets are cached 5 minutes if you specify a time to publish or a time to expire
* widgets are cached 1 day if you don't specify a time to publish and a time to expire
* widgets cache expire if you update the widget
* widgets cache expire when one of the following action is triggered:
 * save_post
 * deleted_post
 * publish_future_post
 * switch_theme

= Usage =

1. Go to *WP-Admin -> Appearance -> Widgets*.
2. Drag *Picker* widget to a sidebar.
3. Customize *Picker* widget.

Links: [Author's Site](http://www.andrealandonio.it)

== Installation ==

1. Unzip the downloaded *picker* zip file
2. Upload the *picker* folder and its contents into the *wp-content/plugins/* directory of your WordPress installation
3. Activate *picker* from Plugins page

== Frequently Asked Questions ==

= Works on multisite? =

Yes

= How works with different locales? =

Plugin works with 2 different locale and formats:

* italian locale date format "dd/mm/yy"
* default locale date format "mm/dd/yy"

= Can I use my custom image sizes on widget? =

Yes, in addition to predefined image formats, you can display your registered image sizes

= Works on with custom post types? =

No, for now only standard post types are available

== Screenshots ==

1. Widget admin page (customizing widget)
2. Widget admin page (datetimepicker usage)
3. Frontend widget layout
4. Admin settings page

== Changelog ==

= 1.0.0 - 2015-01-01 =
* First release

== Upgrade Notice ==

= 1.0.0 =
This version requires PHP 5.3.3+
