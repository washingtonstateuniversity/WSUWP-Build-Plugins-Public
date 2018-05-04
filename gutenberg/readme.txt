=== Gutenberg ===
Contributors: matveb, joen, karmatosed
Requires at least: 4.9
Tested up to: 4.9.4
Stable tag: 2.7.0
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

* Add a pasting schema in raw content handling. It simplifies whitelisting and reduces the amount of filters run. Should improve reliability, clarity, markdown conversion, and usage in blocks.
* Add “Spacer” block to create empty areas.
* Add Server Side Render component.
* Expand public InnerBlocks API with support for template configuration and allowedBlocks logic.
* ColorPalette improvements:
	* Implement mechanism to use classes for configured colors instead of inline styles. Use it in Button block as well.
	* Use color name in ColorPalette aria-label for making color selection more accessible.
	* Improve accessibility of PanelColor by announcing currently set color by name.
	* Hide color pickers in paragraph and button if no colors are available.
* Add a format prop to allow HTML string values to be used in RichText component. This should be a useful API addition for plugin developers.
* Improve the make gallery modal and allow it to use the correct mode when editing.
* Improve performance by avoiding creating a new uids prop on each block rerender.
* Make sure createInnerBlockList never updates when passed using context.
* Introduce initial “entities” data model abstraction to automatically build state selectors.
* Hide the movers and the block menu when typing.
* Optimize the shouldComponentUpdate path of withSelect.
* Use support: align API in Columns block, fixes issue with alignment.
* Filter the PostFormat list to those supported by the theme.
* Used fallback styles to compute font size slider initial position.
* Indent serialized block output with tabs as part of Block API.
* Add a RichText.Content component to be used in conjunction with RichText.
* Determine emptiness by value in RichText.
* Call resolver isFulfilled once per argument set in data modules.
* Extend BlockEdit context with name and use it for autocompleters.
* Improve order of block shortcuts within inline inserter.
* Improve terms token feedback and accessibility.
* Introduce theme_supports with formats to REST API index.
* Switch post-author component to use /wp/v2/users/?who=authors. Related #42202.
* Further harden who=authors check by author support for post type.
* Disable link suggestions when value is URL.
* Make CodeEditor component more extensible.
* Allow the new “block remove” button appear on focus.
* Add new “pure” higher order component to wp/element.
* Add Embed Preview support for classic embed providers. This handled legacy embeds.
* Add missing label and focus style to the code editor textarea.
* Introduce editorMediaUpload wrapper and fix issue with images not being attached to a post.
* Used editorMediaUpload in Gallery files transform (images drag&drop).
* Make URL creation mechanism smarter around relative links.
* Add a type attribute to input elements.
* Add missing custom class in latest posts & categories block.
* Add visible label to shared block name input.
* Add ref="noreferrer noopener" for target="_blank" links.
* Add drop cap help text in paragraph block.
* Remove the text alignment from the block inspector in Cover Image.
* Make sure aria-disabled buttons (movers) stay disabled on focus.
* Simplify the BlockBreadcrumb component and its semantics.
* Only display featured image UI when theme supports it﻿.
* Improve display of URL input.
* Improve consistency in how + icon is shown on the inserters.
* Extract block library to separate module.
* Improve handling of admin theme colors.
* Avoid calculating the closest positioned parent by binding the RichText wrapper div.
* Use IconButton on breadcrumbs to increase consistency and accessibility.
* Reset change detection on post update, resolving an issue where changes made while a post is saving are not accurately reflected in change detection.
* Hide inspector controls if no image is selected in Cover Image.
* Minor improvements for the permalink “Copy to clipboard” button.
* Fix scrolling issues with very long and multi-line captions.
* Fix problem with front-end output of LatestsPosts block.
* Fix issue with using zero min value in RangeControl.
* Fix Markdown paste containing HTML.
* Fix permalink linking to preview URL instead of live.
* Fix issue with update button becoming invisible on mobile on already published posts.
* Fix showing/hiding the right block side UI on RTL languages.
* Fix Classic block regression after extraction of the blocks into a separate script.
* Fix issue where when creating a new post would default to the block sidebar if it was opened before.
* Fix issue when pasting content with inline shortcodes would produce a separate block.
* Fix BlockEdit hooks not working properly with context.
* Fix regression with select box.
* Fix translation strings in embed block.
* Fix regression with formatting button hover/focus style.
* Fix arrow navigation in the shared block more options menu.
* Fix orderby typo in latest posts block.
* Fix the clipboard button as IconButton usage.
* Restore hiding drop cap on focus to prevent bugs with contenteditable.
* Restore priority on embed block for raw transforming.
* Remove no longer mandatory use of isSelected in block edit.
* Remove permalink_structure from REST API index as per #42465.
* Remove old solution for focus after deprecation period.
* Refactor withColors HOC to allow configuring the mapping when instantiating the component.
* Refactor PanelColor to avoid the need for the colorName prop.
* Use a “users” reducer combined with a “queries” sub state to map authors to users.
* Make sure block assets are always registered before wp-edit-post script.
* Expose Gutenberg Data Format version in the REST API response.
* Split loading of API actions and filters to its own file.
* Switch to rest_get_server() for compatibility with trunk.
* Pre-load REST API index data to avoid flash of missing data.
* Deprecate event proxying in RichText.
* Avoid duplicate save request in shared block which could cause race conditions.
* Update docs folder structure and make all internal handbook links relative.
* Update theme extensibility documentation to include editor widths.
* Add section about translating the plugin﻿ to the contributing doc.
* Improve documentation and clarity of the Toolbar component.
* Add documentation for undefined attribute source.
* Add isDebounced prop in autocompleter doc.
* Add arrow-spacing rule to eslint config.
* Add arrow-parens rule to eslint config.
* Enforce array as Lodash path argument.
* Upgrade react-datepicker to 1.4.1.
* Upgrade showdown to 1.8.6.
* Drop deprecations slated for 2.8 removal.
* Use the @wordpress/word-count package.
* Use @wordpress/is-shallow-equal for shallow equality.
* Build Tools: Fix the package plugin script.
* Improve the G in Gutenberg ASCII
