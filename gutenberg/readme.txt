=== Gutenberg ===
Contributors: matveb, joen, karmatosed
Requires at least: 4.9
Tested up to: 4.9.6
Stable tag: 2.9.2
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

* Redesign the inserter with collapsible panels.
* Add support for Child Blocks. These create a relationship between blocks and updates the inserter to show blocks based on context.
* Implement a new block hover and select approach to improve nested block selection and clarity.
* Allow expanding selection on consecutive Meta+A presses.
* Add shared blocks to the blocks autocompleter.
* Iterate on behaviour of the “between blocks inserter”.
* Multiple longstanding fixes to the UrlInput box by using Popover component instead of custom positioning.
* Allow themes to opt-in to the visual styles provided by core blocks.
* Allow custom colors in block icons.
* When focused on a parent with InnerBlocks set, show available child blocks clearly at the top. Blocks with children are marked visually in the root inserter.
* Add publish panels support for plugins.
* Scroll the inserter menu to the relevant position when opening a panel.
* Expand matching categories when searching the block library.
* Introduce a dedicated autosaves endpoint for handling autosave behavior. Improves general handling of revisions through REST API saves.
* Show autosave notice when autosave exists.
* Send all fields when transmitting an autosave, fixing missing titles.
* Allow clicking the block’s input fields.
* Move “Saved” blocks to the bottom and show distinct icon on the panel name.
* Improve max-upload size error message.
* Normalize unicode in raw handling.
* Allow inserting a link with no text selected.
* Center the background of the cover image block.
* Make the Classic block toolbar sticky while scrolling.
* Make Button component styles independent from Core Styles.
* Use new "theme" style mechanism to restore Quote styles on the front.
* Make various greys less dull when used with opacity.
* Set the correct min-width for the ChromePicker popover.
* Unify all the media blocks placeholders under a unique component.
* Address focus style regression in menu and improve display of keyboard shortcut.
* Refactor popover to clarify computations and address multiple cases of overflow issues.
* Avoid URL redirect for published post autosave.
* Refactor URL redirect as BrowserURL component and ensure redirect for new posts.
* Avoid superfluous changes in RichText.
* Improve performance for PublishPanel and extensions.
* Unselect blocks when opening the document settings.
* Add text alignment options to verse block toolbar.
* Make sure the Title element uses the same max-width as blocks.
* Improve title component so it works with and without editor styles.
* Properly associate the spacer height input label.
* Further visual polish to new inserter design.
* Simplify inserter accessibility.
* Fix block icon alignment in block inspector.
* Fix issue with excerpt textarea height overflow.
* Fix typo in withRehydration function call.
* Fix Post Formats UI not showing.
* Fix regressions with Button component after PostCSS.
* Fix issue with applied formats being lost.
* Fix unset background-color on sidebar headings.
* Fix case where inbetweenserter would linger if you clicked to insert and then clicked away.
* Fix issue with rich text toolbar being gone in captions.
* Fix padding and outline style for expander panel.
* Fix CodeEditor component not loading when WordPress is installed in a subfolder.
* Fix regression with sticky toolbar border.
* Fix some intermittent E2E test failures.
* Fix “no results” message within inserter.
* Fix visual issue with normal buttons with icons.
* Fix issue where the sidebar would remain open on mobile when the page loaded if it was opened before.
* Fix fileName not being respected when the image is uploaded via drag & drop.
* Fix regression in spacer block.
* Fix getInserterItems cache.
* Fix intermittent adding-blocks E2E test failure.
* Fix issue with shared block preview being rendered hidden.
* Fix alignment issue with hover label on wide and full-wide.
* Fix Windows-unfriendly theme.scss loader rule.
* Fix issue where pasting would fail in IE11.
* Fix issue with editing paragraph blocks in shared blocks.
* Address small code style fixes on the core-data module.
* Add support for getEntityRecords selectors/resolvers to the core data module to avoid duplication across the different entities.
* Remove drag handle from block breadcrumb.
* Improve Child Blocks code footprint.
* Address package issues with npm audit fix.
* Use Core’s TinyMCE version to avoid conflicts.
* Scope the rule adding a white background to the html element.
* Remove onFocus from core blocks’ RichText usage.
* Remove post type capabilities from the user object.
* Remove the dependency on the editor module code from blocks tests.
* Expose preview_link through the REST API and use within client.
* Only load Gutenberg Polyfill in editor pages.
* Refine code statement of image classes.
* Add support for the default_page_template_title filter in page-attributes meta-box.
* Add additional condition to “Available templates” meta-box logic.
* Ensure the wp-editor script is also enqueued soon using the enqueue_block_assets hook.
* Cleanup shared block tests.
* Provide cross-browser node containment checking.
* Add unit tests for core-blocks/more/edit.js components.
* Add documentation about ServerSideRender.
* Auto-generate human-readable version of Gutenberg block grammar.
* Doc Block cleanup for rich-text component.
* Address multiple typos in code comments.
* Skip test files when generating build folders for packages.
* Fail E2E tests when uncaught page error occurs.
* Extract new blob package out of utils module.
* Update the wait function name to discourage its use in E2E tests.
* Introduce new module with deprecation utility.
* Introduce insertBlock() utility for E2E tests.
* Move data module to the package maintained by Lerna.
* Avoid using spread for objects to work with all node 8x versions.
* Add lint rule to check that memize() is used.
* Add global guard against ZWSP in E2E content retrieval.
* Add building/watching support to Gutenberg packages.
* Add further explanation for why .normalize() is optional.
* Add new webpack plugin to handle library default export.
* Reload the page after webpack watch compile.
* Publish numerous WP packages updates from repository.
* Drop deprecations slated for 3.0 removal.
* Always publish main and module distributions in packages.
* Upgrade mousetrap to 1.6.2.
* Update all WordPress packages to the latest version.
* Bump WordPress requirements to 4.9.6.
