=== Gutenberg ===
Contributors: matveb, joen, karmatosed
Requires at least: 4.9
Tested up to: 4.9.4
Stable tag: 2.4.0
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

* Add support for sharing nested blocks.
* Introduce a declarative approach to handling display of sidebar content to the Plugin API with PluginSidebar component and portals.
* Introduce menu item and related components to handle entry point for editor plugin operations, further extending capabilities and available tools in the Plugin API.
* Add block template validation and ability to reset a template.
* Add new abstracted data querying interface that provides better handling of declarative data needs and side effects. Introduces registerResolvers enhanced by withSelect.
* Add predefined sets of font sizes and corresponding UI controls.
* Improve block margin implementation in order to simplify work needed for nesting blocks.
* Don't show insertion point between blocks when a template is locked.
* Update shared block UI to better indicate that a block is reusable.
* Add support for transforms prioritization to the block API.
* Improve initial focus allocation within content structure popover for accessibility.
* Add visibile text to gallery "add item" button for accessibility.
* Update post taxonomies wp.apiRequest to not depend on ajax specific implementation.
* Some visual refinements to the main block library inserter.
* Include custom classes in the block markup within the editor, matching the final render on the front-end.
* Improve display of block transformation options.
* Fine-tune the pre- and post-publish flows depending on post status and user role.
* Improve the accessibility of the MenuItemsToggle buttons and add a speak message for screen reader users to confirm when they switch editor mode.
* Improve the accessibility of RichText elements by providing textbox roles and aria-multiline attributes.
* Improve the accessibility of inserter items by providing aria-label attributes.
* Clear selected block on canvas focus only if it is selected.
* Avoid styling meta-boxes inputs to look like Gutenberg UI.
* Use "perfect fourth" rule of typographic scale for heading display.
* Inherit color styling on meta-boxes area.
* Increase width of meta-boxes area.
* Default to content-box box-sizing for the meta-box area.
* Improve handling of transformations (backticks for Code and dashes for Separator) when pressing enter.
* Expose combineReducer helper in data module.
* Make it possible to override the default class name generation logic.
* Remove edit-post styles from editor components.
* Ignore mid-word underscores when pasting markdown text.
* Add label element to the post title.
* Improve block mover labels for speech recognition software.
* Correct onChange handler in SelectControl component to support multi-value changes.
* Make MediaUpload component extensible.
* Improve display of color palette items (like white) by adding a subtle transparent inset shadow.
* Ignore "Disable visual editor" setting to address case where Classic block would not load for the user.
* Improve display of sidebar heights on mobile.
* Update blockSelection reducer to clear selection when removing the selected block.
* Show "no title" placeholder on the mobile sidebar when post title is empty.
* Address case where cancelling edits on a shared block not discarding unsaved changes that have been made to that block.
* Introduce new MenuGroup and MenuItem components and refactor for clarity.
* Improve the block inserter UI on mobile by displaying the post title in a header above the search bar. Extends as optional support for all popovers.
* Refactor media fetching to use the core data module. Shields from REST API specific nomenclature.
* Add a label and a role to the block appender to make it discoverable by text readers.
* Use up and down arrow icons for the meta boxes panels.
* Hide reusable block indicator from the inserter preview.
* Fix issue with embed placeholder breaking on reload.
* Fix error when collapsing categories panel.
* Fix case where inserting a block after removal inserts it at the top of the post.
* Fix issue with Button block text wrap.
* Fix bug with meta-boxes data collection that occasionally prevented them from showing.
* Fix meta-box configuration persistence to be per postType.
* Fix issue with multiple previews in Firefox by unsetting popup window upon close.
* Fix scroll bleed when displaying modal UI on mobile.
* Fix z-index issue with admin bar quick links and content structure tooltip.
* Fix image href attribute matcher to not interfere with anchors inside the caption.
* Fix help text position on toggle control and range control.
* Fix centering of small videos.
* Fix timezone conflicts when setting global moment default timezone.
* Fix issue with getDocumentTitle and undefined titles.
* Fix missing rerender of plugin area upon registration or unregistration.
* Remove title from Table of Contents and warn user if theme doesn't support titles.
* Prevent potential fatal error when registering shared block post type if a specific core user role has been removed.
* Avoid collecting meta-box information on non-Gutenberg screens.
* Update contrast checker to respect recent changes on Notice component.
* Rename isProvisionalBlock action property to selectPrevious in removeBlock and removeBlocks functions.
* Address issue with heartbeat dependency (only use when available).
* Allow calling functions passed as props in the Fill.
* Improve style handling and specificity of dashicon SVGs.
* Unify "citation" translatable strings for quotes and pullquotes.
* Clean up nomenclature inconsistencies in blocks and components modules.
* Correct documentation example for withDispatch.
* Update documentation on extending the editor via PluginSidebar and PluginMoreMenuItem.
* Dynamically pick JS/CSS build files for plugin ZIP generation.
* Copy improvements to documentation.
* Attempt to avoid cases where hosts block certain HTTP verbs on wp-api.js requests. This is part of similar issues being exposed by Gutenberg being the first Core WordPress feature that makes significant use of the REST API.
* Add a shim to override wp.apiRequest, allowing HTTP/1.0 emulation.
* Update react-autosize-textarea package.
* Update @wordpress/hooks to v1.1.6.
* Use CustomTemplatedPathPlugin which was extracted and updated for Webpack 4.
* Use wordpress/es6 ESLint config.
* Add Gutenberg Hub to the resources.
* Properly detect NVM path on macOS using homebrew.
* Remove Cypress for E2E testing in favor of Puppeteer. Refactor all existing tests and integrations.
* Remove deprecations slated for 2.5.0 removal.
