=== Multi-Column Taxonomy List ===
Contributors: mmuro
Tags: categories, category, tags, tag, taxonomy, shortcode 
Requires at least: 3.0
Tested up to: 3.2.1
Stable tag: 1.3

List your categories, tags, or custom taxonomies into multiple, customizable, columns. 

== Description ==

*Multi-Column Taxonomy List* is a plugin that allows you to list your categories, tags, or custom taxonomies into multiple columns.

This plugin is great to use on custom archives index pages to display all categories, tags, and custom taxonomies on a single page. You can also turn the list into links to the RSS feeds for your taxonomies.

== Installation ==

1. Upload `multi-column-taxonomy-list` to the `/wp-content/plugins/` directory.
1. Activate the plugin through the 'Plugins' menu in WordPress.
1. Create a new page and add the shortcode `[mctl]` to the content.

Alternatively, you can add `<?php multi_column_tax_list(); ?>` anywhere in your theme.

See the Usage section for more examples and a list of parameters.

== Frequently Asked Questions ==

= Does this work with custom taxonomies I have created? =

Yes! To use your custom taxonomy that you have registered with `register_taxonomy`, simply add the `taxonomy=custom_taxonomy_name` attribute.

= I'm not seeing any output with my custom taxonomy =

If you are having problems seeing any output with your custom taxonomy, it's usually one of two things:

1. You set `public => false` when you registered the taxonomy.  It needs to be true: `public => true`
1. There are no posts assigned to that taxonomy.

= How do I customize the CSS? =

If you want to customize the appearance of the lists using your own CSS, here's how to do it:

1. Add this code to your theme's `functions.php` file: `add_filter( 'mctl_css', '__return_false' );`
1. Copy everything from `css/multi-column-taxonomy-list.css` into your theme's `style.css`
1. Change the CSS properties in your theme's `style.css` as needed

== Usage ==

= Shortcode attributes =
You can implement a new list by adding the shortcode `[mctl]` to the content of a page.  By default, it will output the categories into three columns with a heading of Categories.  You can customize the output using attributes in the following format: `[mctl taxonomy='post_tag' title='Tags']`

* **taxonomy**: The taxonomy to retrieve terms from
	- Valid values: `category`, `post_tag`, `custom_taxonomy_name`
	- Multiple values not allowed
	- Default: `category`
* **title**: Sets the title of the list
	- Valid values: any string
	- Default: `Categories`
* **title_container**: HTML element to wrap the title in
	- Valid values: any HTML element
	- Recommended values: `h1`, `h2`, `h3`, `h4`, `h5`, `h6`, `p`
	- Default: `h3`
* **columns**: Sets the number of columns to use
	- Valid values: any integer
	- Default: `3`
* **orderby**: Sort terms by name, unique ID, slug, or the count of posts in that term
	- Valid values: `name`, `id`, `slug`, `count`
	- Default: `name`
* **order**: Sort order for the terms (either ascending or descending)
	- Valid values: `ASC`, `DESC`
	- Default: `ASC`
* **show_count**: Toggles the display of the current count of posts in each term. `0` is false/off and `1` is true/on
	- Valid values: `0`, `1`
	- Default: `0`
* **exclude**: Exclude one or more terms from the results.
	- Valid values: unique IDs, separated by commas. (ex: `exclude='12,13,22'`)
	- Default: no default
* **parent**: Get direct children of this term
	- Valid values: any integer
	- Multiple values not allowed
	- Default: no default
* **rss**: Turns the list into links to RSS feed of term. `0` is false/off and `1` is true/on
	- Valid values: `0`, `1`
	- Default: `0`
* **rss_image**: Use a custom image RSS image icon when `rss` is on.
	- Valid values: full URL to image
	- Default: `/wp-includes/images/rss.png`
* **number**: Max number of terms to display
	- Valid values: any integer
	- Default: display all terms
* **like**: Return terms that begin with this value
	- Valid values: any string
	- Default: no default

= Template tag =
Alternatively, you can implement a new list by adding the the template tag `<?php multi_column_tax_list(); ?>` anywhere in the code of your theme. Just like the shortcode, by default it will output the categories into three columns with a heading of Categories.  You can customize the output using a string of parameters in the following format: `<?php multi_column_tax_list( 'taxonomy=post_tag&title=Tags' ); ?>`

Refer to the above attributes for customizations.

== Screenshots ==

1. List of categories and tags added to a Page via shortcode
2. List of category RSS feeds

== Changelog ==

**Version 1.3**

* Add new shortcode attribute: like. Display items that begin with a certain value.

**Version 1.2**

* Fix bug causing extra closing `ul` to output
* Add new shortcode attribute: number

**Version 1.1**

* Add new shortcode attributes: parent, rss, and rss_image

**Version 1.0**

* Plugin launch!

== Upgrade Notice ==

= 1.3 =
Added a new shortcode to create lists that only begin with a certain value.

= 1.2 =
Fix bug causing extra closing `ul` to be output. Added a new shortcode to limit the number of terms being output.

= 1.1 =
Added ability to show only items from a particular parent. Also, turn the list into links to the RSS feeds.