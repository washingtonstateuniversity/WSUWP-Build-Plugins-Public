=== Gutenberg ===
Contributors: matveb, joen, karmatosed
Requires at least: 4.9.6
Tested up to: 4.9.6
Stable tag: 3.1.1
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

* Add block styles variations to the Block API.
* Add support for Inline Images and Inline Blocks API.
* Convert Columns to a set of parent and child blocks, including a wrapper element and more reliable front-end presentation.
* Allow registering new block categories.
* Add support for locking Inner Block areas.
* Add File Block for uploading and listing documents, with drag and drop support.
* Introduce Modal component to expand the extensibility suite of UI components.
* Redesign block transformation menu.
* Improve style display of region focus areas.
* Prevent blocks from being draggable if a template lock exists.
* Parse superfluous classes as custom classes preventing a block being considered invalid for such cases.
* Support “Autoplay” and “Loop” in Audio Block “Playback Controls”.
* Always show “new gallery item” below the gallery.
* When dragging images to create a gallery, immediately show the images while uploading is happening.
* Optimize withSelect to avoid generating merge props on equal props.
* Remove the “scroll shadow” at the bottom of the inserter library.
* Remove the bottom border on the last collapsible panel.
* Remove wrapping div from paragraph block (in the editor) for performance audit.
* Add Image Block ‘Link to’ setting.
* Allow margins to collapse & refactor block toolbar.
* Keep NUX tips open when the user clicks outside.
* Add initialTabName prop to Tab Panel component.
* Add higher order component to constrain Tab keyboard navigation.
* Display server error message on media upload when one exists.
* Improve “add block” text in NUX onboarding.
* Improve experience of using image resize handles — placing them at the middle of the edges instead of the corners.
* Update color of the Shared panel icon to be the same as all other icons.
* Verify if block icon background and foreground colors are readable. Warn in the console otherwise.
* Address various design details on Plugin API icon treatment in header and popover.
* Include all image sizes on the media upload object when they exist.
* Move the delete block action to the ellipsis menu for the block. Introduce separator in the menu.
* Make the inserter results panel focusable and improve accessibility.
* Improve publish panel accessibility and add new publish landmark region.
* Open preview to previewLink if not autosaveable.
* Make sure autocompleted values make it into the block’s saved content.
* Avoid setAttributes on end-of-paragraph seeking to resolve unnecessary performance degradations.
* Avoid re-render and subsequent action dispatch by adopting module constant.
* Avoid focusing link in new NUX tooltip
* Avoid showing hover effect if the ancestor of a block is multi-selected.
* Schedule render by store update via setState. Fixes condition where appender would insert two copies of a block.
* Inner Blocks refactor:
* * Update deprecated componentWillReceiveProps to equivalent componentDidUpdate.
* * Avoid deep equality check on flat allowedBlocks prop shape.
* * Avoid handling unexpected case where UPDATE_BLOCK_LIST_SETTINGS is not passed an id.
* * Avoid creating new references for blockListSettings when settings not set, but the id never existed in state anyways.
* * Avoid switch fallthrough on case where previous updateIsRequired condition would be false, which could have introduced future maintainability issues if additional case statements were added.
* * Add test to verify state reference is not changed when no update is needed.
* * Consistently name allowedBlocks (previously also referred to as supportedBlocks).
* Consider horizontal handled by stopPropagation in RichText. Fixes edge case with inline boundaries at the end of lines﻿. With further improvements﻿.
* Ensure ellipsis icon button is visible when block settings menu is open.
* Simplify RichText to have a single function for setting content vs. the current updateContent and setContent, by removing updateContent.
* Optimize RichText by removing the creation of undo levels at split and merge steps.
* Simplify the RichText component’s getContent function to remove a call to TinyMCE’s isEmpty function, which incurs a DOM walk to determine emptiness.
* Optimize the RichText component to avoid needing to keep a focusPosition state.
* Reenable pointer events on insertion point hover for Firefox.
* Introduce colors slugs in color palette definitions to ensure localization.
* Respect inner blocks locking when displaying default block appender.
* Use color styles on the editor even if the classes were not set.
* Move “opinionated” Gutenberg block styles to theme.scss.
* Don’t allow negative values in image dimensions.
* Fix IE11 formatting toolbar visibility.
* Fix issues with gallery block in IE11.
* Fix import statement for InnerBlocks.
* Fix broken links in documentation.
* Fix text wrapping issues in Firefox.
* Fix showing the permalink edit box on the title element.
* Fix focus logic error in Tips and tidy up docs.
* Fix instance of keycode package import.
* Fix case where an explicit string value assigned as an attribute would be wrongly interpreted as false when assigned as a boolean attribute type in the parser.
* Fix the data module docs by moving them to the root level of the handbook.
* Fix specificity issue with button group selector.
* Fix CSS property serialization.
* Fix left / right alignments of blocks.
* Fix CSS vendor-prefixed property serialization.
* Fix arrows navigation in the block more options menu.
* Let ⌘A’s select all blocks again.
* Check for forwardedRef in withGlobalEvents.
* Address issues with left / right align improvements in RTL.
* Different approach for fixing sibling inserter in Firefox.
* Correctly handle case where ‘post-thumbnails’ is array of post types.
* Remove blocks/index.native as the default is compatible with React Native app.
* Allow editor color palette to be empty.
* Support setup with single array argument in Color Palette registration.
* Only save metaboxes when it’s not an autosave.
* Force the display of hidden meta boxes.
* Implement core style of including revisions data on Post response.
* Remove post type ‘viewable’ compatibility shim.
* Remove unused block-transformations component.
* Use withSafeTimeout in NUX tips﻿ to handle cases where plugins modify the $post global.
* Update HOCs to use createHigherOrderComponent.
* Deprecate property source in Block API.
* Documentation: fix rich-text markdown source.
* Tweak release docs and improve release build script.
* Add focusOnMount change to deprecations.
* Add e2e test for sidebar behaviours on mobile and desktop.
* Add e2e test for PluginPostStatusInfo.
* Add snapshot update script.
* Update import from @wordpress/deprecated.
* Extract “keycodes” into its own package and rework the Readme file.
* Add shortcode package instead of global.
* Add package: @wordpress/babel-plugin-import-jsx-pragma.
* Update nested templates to new columns format.
* Generate the manifest dynamically to include the data module docs in the handbook.
* Expose the grammar parser to the mobile app.
* Drop the .js extension from @wordpress/element’s package.json entry-point so when used in the mobile RN app the correct module (index.native.js﻿) can be resolved by Metro.
* Add packages Readme files to the handbook.
* Add link in documentation to supported browsers.
* Add initial document on copy guidelines.
* Add missing documentation for InnerBlocks props.
* Regenerate package-lock.json to address unintentional changes.
* Use cross-env for plugin build scripts to address issues on Windows machines.
* Invert JSX pragma application condition.
* Ignore non-JS file events in packages.
* Drop deprecations slated for 3.2 removal.
* Publish multiple new versions of packages.
