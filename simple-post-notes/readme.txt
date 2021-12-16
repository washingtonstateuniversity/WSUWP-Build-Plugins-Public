=== Simple Post Notes ===
Contributors: bracketspace, Kubitomakita
Tags: post, page, custom post type, cpt, note, notes, informations, info
Requires at least: 3.6
Tested up to: 5.8
Stable tag: 1.7.5

Adds simple notes to post, page and custom post type edit screen.

== Description ==

= Features =

* Simple note section on the post edit screen
* Sortable note column in posts table
* Bulk / Quick edit support
* Shortcode which will display the note on the front end
* Ability to change the "Note" title and add a help text for editors

= Our other plugins = 

* [Notification - notification system for WordPress](https://bracketspace.com/notification/)
* [Advanced Cron Manager](https://wordpress.org/plugins/advanced-cron-manager/)
* [Easy Watermark](https://wordpress.org/plugins/easy-watermark/)

= Custom development =

BracketSpace - the company behind this plugin provides [custom WordPress plugin development services](https://bracketspace.com/custom-development/). We can create any custom plugin for you.

*Cover photo [designed by Freepik](http://www.freepik.com)*

== Frequently Asked Questions ==

= Is this plugin compatible with Gutenberg? =

Yes, it is!

= Custom post type are supported? =

Yes! Enable them on the plugin settings screen.

= Can I display my note on the front-end? =

Yes. Use the `[spnote]` shortcode. You can also pass optional parameter with post ID: `[spnote id="123"]` to display note from other post. By default it's grabbing current post.

= Can I disable display of admin column? =

Yes, by a simple filter.

Use:
`add_filter( 'spn/columns-display', '__return_false' );`
To disable SPN column for all post types

Or use
`add_filter( 'spn/columns-display/POST_TYPE_SLUG', '__return_false' );`
To disable SPN column only for specific post type

= Can you create a plugin for me? =

Yes! We're offering a [custom plugin development](https://bracketspace.com/custom-development/) services. Feel free to contact us to find out how we can help you.

== Screenshots ==

1. Post Note area
2. Post Note in Posts table
3. Settings

== Changelog ==

= 1.7.5 =
* [Fixed] PHP warning.

= 1.7.4 =
* [Fixed] Note column sorting.

= 1.7.3 =
* [Changed] The shortcode with note is now wrapped with a div.

= 1.7.2 =
* [Fixed] Bug with duplicated post notes for hierachical post types
* [Changed] Plugin author branding

= 1.7.1 =
* [Fixed] `Using $this when not in object context` error, thanks to Gero

= 1.7 =
* [Added] Ability to change the "Note" title and add a help text for editors

= 1.6 =
* [Added] Support for quick and bulk edit
* [Added] Shortcode [sponote]

= 1.5 =
* [Fixed] Bug with duplicated post notes for custom post type
* [Changed] Translation. Now it's managed via repository

= 1.4 =
* Added German translation thanks to Michael Köther

= 1.3 =
* Note column in the posts table is now sortable

= 1.2.2 =
* Added Spanish translation thanks to Alfonso Frachelle

= 1.2.1 =
* Added filter to prevent displaying post note column

= 1.1.0 =
* Added Note to admin column

= 1.0.0 =
* Release

== Upgrade Notice ==

= 1.0.0 =
Release
