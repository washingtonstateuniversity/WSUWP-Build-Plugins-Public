=== Gutenberg ===
Contributors: matveb, joen, karmatosed
Requires at least: 4.9
Tested up to: 4.9.4
Stable tag: 2.8.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

A new editing experience for WordPress is in the works, with the goal of making it easier than ever to make your words, pictures, and layout look just right. This is the beta plugin for the project.

== Description ==

Gutenberg is more than an editor. While the editor is the focus right now, the project will ultimately impact the entire publishing experience including customization (the next focus area).

<a href="https://wordpress.org/gutenberg">Discover more about the project</a>.

= Editing focus =

> The editor will create a new page- and post-building experience that makes writing rich posts effortless, and has “blocks” to make it easy what today might take shortcodes, custom HTML, or “mystery meat” embed discovery. — Matt Mullenweg

One thing that sets WordPress apart from other systems is that it allows you to create as rich a post layout as you can imagine -- but only if you know HTML and CSS and build your own custom theme. By thinking of the editor as a tool to let you write rich posts and create beautiful layouts, we can transform WordPress into something users _love_ WordPress, as opposed something they pick it because it's what everyone else uses.

Gutenberg looks at the editor as more than a content field, revisiting a layout that has been largely unchanged for almost a decade.This allows us to holistically design a modern editing experience and build a foundation for things to come.

Here's why we're looking at the whole editing screen, as opposed to just the content field:

1. The block unifies multiple interfaces. If we add that on top of the existing interface, it would _add_ complexity, as opposed to remove it.
2. By revisiting the interface, we can modernize the writing, editing, and publishing experience, with usability and simplicity in mind, benefitting both new and casual users.
3. When singular block interface takes center stage, it demonstrates a clear path forward for developers to create premium blocks, superior to both shortcodes and widgets.
4. Considering the whole interface lays a solid foundation for the next focus, full site customization.
5. Looking at the full editor screen also gives us the opportunity to drastically modernize the foundation, and take steps towards a more fluid and JavaScript powered future that fully leverages the WordPress REST API.

= Blocks =

Blocks are the unifying evolution of what is now covered, in different ways, by shortcodes, embeds, widgets, post formats, custom post types, theme options, meta-boxes, and other formatting elements. They embrace the breadth of functionality WordPress is capable of, with the clarity of a consistent user experience.

Imagine a custom “employee” block that a client can drag to an About page to automatically display a picture, name, and bio. A whole universe of plugins that all extend WordPress in the same way. Simplified menus and widgets. Users who can instantly understand and use WordPress  -- and 90% of plugins. This will allow you to easily compose beautiful posts like <a href="http://moc.co/sandbox/example-post/">this example</a>.

Check out the <a href="https://github.com/WordPress/gutenberg/blob/master/docs/faq.md">FAQ</a> for answers to the most common questions about the project.

= Compatibility =

Posts are backwards compatible, and shortcodes will still work. We are continuously exploring how highly-tailored metaboxes can be accommodated, and are looking at solutions ranging from a plugin to disable Gutenberg to automatically detecting whether to load Gutenberg or not. While we want to make sure the new editing experience from writing to publishing is user-friendly, we’re committed to finding  a good solution for highly-tailored existing sites.

= The stages of Gutenberg =

Gutenberg has three planned stages. The first, aimed for inclusion in WordPress 5.0, focuses on the post editing experience and the implementation of blocks. This initial phase focuses on a content-first approach. The use of blocks, as detailed above, allows you to focus on how your content will look without the distraction of other configuration options. This ultimately will help all users present their content in a way that is engaging, direct, and visual.

These foundational elements will pave the way for stages two and three, planned for the next year, to go beyond the post into page templates and ultimately, full site customization.

Gutenberg is a big change, and there will be ways to ensure that existing functionality (like shortcodes and meta-boxes) continue to work while allowing developers the time and paths to transition effectively. Ultimately, it will open new opportunities for plugin and theme developers to better serve users through a more engaging and visual experience that takes advantage of a toolset supported by core.

= Contributors =

Gutenberg is built by many contributors and volunteers. Please see the full list in <a href="https://github.com/WordPress/gutenberg/blob/master/CONTRIBUTORS.md">CONTRIBUTORS.md</a>.

== Frequently Asked Questions ==

= How can I send feedback or get help with a bug? =

We'd love to hear your bug reports, feature suggestions and any other feedback! Please head over to <a href="https://github.com/WordPress/gutenberg/issues">the GitHub issues page</a> to search for existing issues or open a new one. While we'll try to triage issues reported here on the plugin forum, you'll get a faster response (and reduce duplication of effort) by keeping everything centralized in the GitHub repository.

= How can I contribute? =

We’re calling this editor project "Gutenberg" because it's a big undertaking. We are working on it every day in GitHub, and we'd love your help building it.You’re also welcome to give feedback, the easiest is to join us in <a href="https://make.wordpress.org/chat/">our Slack channel</a>, `#core-editor`.

See also <a href="https://github.com/WordPress/gutenberg/blob/master/CONTRIBUTING.md">CONTRIBUTING.md</a>.

= Where can I read more about Gutenberg? =

- <a href="http://matiasventura.com/post/gutenberg-or-the-ship-of-theseus/">Gutenberg, or the Ship of Theseus</a>, with examples of what Gutenberg might do in the future
- <a href="https://make.wordpress.org/core/2017/01/17/editor-technical-overview/">Editor Technical Overview</a>
- <a href="http://gutenberg-devdoc.surge.sh/reference/design-principles/">Design Principles and block design best practices</a>
- <a href="https://github.com/Automattic/wp-post-grammar">WP Post Grammar Parser</a>
- <a href="https://make.wordpress.org/core/tag/gutenberg/">Development updates on make.wordpress.org</a>
- <a href="http://gutenberg-devdoc.surge.sh/">Documentation: Creating Blocks, Reference, and Guidelines</a>
- <a href="https://github.com/WordPress/gutenberg/blob/master/docs/faq.md">Additional frequently asked questions</a>


== Changelog ==

= Latest =

* Add support for pinning plugin items in the main editor header. This is an important part of the editor Plugin API seeking to both grant plugins high visibility while offering users a consistent and flexible UI that can scale better.
* Add shortcut tooltips for main toolbar.
* Add remaining RichText shortcuts for formatting toolbar. Display them in tooltips.
* Display the block toolbar and controls below the block on mobile.
* Add automatic handling of focus for RichText component.
* New reusable component: FontSizePicker. Example use in paragraph block.
* Query for all authors with an unbounded per_page=-1 request. Makes sure no users appear missing.
* Make the editor canvas friendly towards colored backgrounds. Improves support of nested structures over backgrounds as well.
* Remove block alignment from paragraph block with deprecation handling.
* Ensure contributors can create tags and manage categories.
* Exclude private blocks from the slash autocompleter.
* Close the post publish panel only when the post becomes dirty.
* Add toggle to set fixed widths in Table block.
* Surface and style the resizing tool in Table block.
* Iterate on table block front-end styles.
* Transform into the correct embed block based on URL patterns.
* Allow resetting the permalink by saving it as empty.
* Add text alignment options to Subhead block.
* Move Heading block alignment options from the inspector to the toolbar.
* Writing Flow: consider tabbable edge if no adjacent tabbable.
* Implement Button as assigning ref via forwardRef.
* Avoid adding terms when tabbing away from the tag selector field.
* Add a max-height to Table of Contents menu.
* Make any iframe embed responsive in the editor.
* Update PostExcerpt component to use TextareaControl.
* Show block remove button on empty paragraph blocks.
* Introduce wp:action-publish and update corresponding UI to reference it. Use wp:action-publish to determine whether to display publish UI.
* Use wp:action-assign-author to indicate if user can assign authors. Fixes issues with author selector not appearing under certain circumstances.
* Permit unbounded per_page=-1 requests for Pages and Shared Blocks. Removes limit on how many items are retrieved.
* Permit unbounded per_page=-1 requests for Categories and Tags.
* Improve written descriptions of core blocks.
* Show caption and description settings in featured image modal.
* Make sidebar toggle button open the block inspector if a block is selected.
* Avoid setting font-style when using dropcap.
* Preserve image ID in raw handling.
* Add a data store to manage the block/categories registration.
* Avoid change in RichText when possible. It prevents unnecessary history records.
* Stop unnecessary re-renders caused by withColors. Also solve memoize problems.
* Move components from the blocks to the editor module.
* Move editorMediaUpload to the editor module.
* Move the editor settings into the editor’s store.
* Change subhead block to subheading.
* Add cache to getUserQueryResults and avoid authors rerender on every key press.
* Rename isPrivate → supports.inserter in Block API.
* Fix multi selection with arrows + shift.
* Fix caretRangeFromPoint for Firefox.
* Fix a PHP Notice in REST API responses.
* Fix broken example in withAPIData README.
* Fix issue with UrlInput autofocus when used in a custom block.
* Fix issue with high contrast indicator in Edge.
* Fix and update block fixture regeneration.
* Fix gallery width to match width of other elements.
* Fix Fragment render error on empty children.
* Fix ServerSideRender bug with Columns block.
* Fix block icon alignment.
* Fix absent editor styles in Classic block.
* Fix state variable name in core-data.
* Fix generating admin schemes styles.
* Fix captions on resized images.
* Fix case where link modal would hide on rerender.
* Fix paste with selection/caret at start or end.
* Fix appender height to match paragraph block.
* Use core-blocks prefix for class names.
* Prevent classname override﻿ when passing className as argument.
* Remove document outline from the sidebar.
* Framework work to support React Native mobile app explorations:
* Refactor the Code block .
* Refactor “More” block.
* Extract edit to their own file (part 1, part 2).
* Use targetSchema of JSON Hyper Schema to communicate sticky action.
* Tweak targetSchema Response for sticky posts.
* Load additional REST API files if controller is defined.
* Restore the wp-blocks stylesheet for backwards compatibility concerns.
* Add documentation for title and modalClass props in MediaUpload.
* Copy edits to theme extensibility.
* Add a lint rule for enforcing ellipsis use.
* Use a postcss plugin to generate the admin-schemes styles.
* Move date module to packages maintained by Lerna. Move element to packages maintained by Lerna.
* Extract dom package and make it maintained with Lerna.
* Move blocks raw handling tests to test/integration folder.
* Remove skipped tests which fail, enable those that pass.
* Add missing unit test for received entity records.
* Update Notice README.
* Update wordcount package to prevent crash.
* Update dom-react to 2.2.1.
* Update React to 16.3.2.
* Update packages to pass npm audit.
* Upgrade rememo dependency to 3.0.0.
* Updates the minimum required version of npm to version 6.0.0 or greater.
* Update testing-overview document.
* Update package-lock.json for fsevents﻿.
* Avoid tail-ing the PHP 5.2/3 build logs.
* Remove Docker compose deprecated parallel option.
* Remove fsevents from optionalDependencies.
* Remove %s from Lerna publish message.
* Deprecate isExtraSmall utility function.
* Add npm update to build script.
* Make lerna a dependency rather than a devDependency.
* Support adding a human-readable deprecation hint.
* Drop features slated for 2.9 removal.
* Introduce the common build folder to be used by all modules.
