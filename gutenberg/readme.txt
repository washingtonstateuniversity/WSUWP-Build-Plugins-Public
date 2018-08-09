=== Gutenberg ===
Contributors: matveb, joen, karmatosed
Requires at least: 4.9.6
Tested up to: 4.9
Stable tag: 3.3.0
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

* Add the Inline Blocks API.
* Rename Shared Blocks to Reusable Blocks.
* Add a Modal component.
* Add a REST API Search controller.
* Add a warning in the classic editor when attempting to edit a post that contains blocks.
* Add ability for themes to configure font sizes.
* Add RTL CSS to all packages.
* Add an edit button to embed blocks.
* Remove all wp.api usage from the editor package.
* Add error handling for file block drag-and-drop.
* Add registerBlockStyleVariation, for registering block style variations.
* Add a border between panels in the block sidebar.
* Add a editor.PostFeaturedImage.imageSize filter for the Featured Image.
* Create a video block when dropping a video on an insertion point.
* Expose a custom class name hook for mobile.
* Add a React Native entrypoint for mobile.
* Only disable wpautop on the main classic editor instance.
* Retain the id attribute when converting heading tags to heading blocks.
* Retain target="_blank" on links in converted paragraphs.
* Improve the handling of imported shortcode blocks.
* Replace the File block’s filename editor with a RichText.
* Tweak the block warning style.
* Add a max-height to the table of contents.
* Remove the inset shadow from the table of contents.
* Fix the tag placeholder text for long translations.
* Fix the table of contents sometimes causing JavaScript errors.
* Fix the link suggestion dropdown not allowing the first suggestion to be selected by keyboard.
* Make tooltips persist when hovering them.
* Add missing aria-labels to the audio and video block UIs.
* Add an icon and accessibility text to links that open in a new tab.
* Fixed shared blocks adding unnecessary rewrite rules.
* Fix a regression in the colour picker width.
* Fix the colour picker focus border being off-centre.
* Combine ColorPalettes into a single panel for Button and Paragraph blocks.
* Fix the ColorIndicator style import.
* Fix auto-linking a URL pasted on top of another URL.
* Add persistent store support to the data module.
* Fix the Latest Comments block using admin imports.
* Fix a warning when adding an image block.
* Fix the classic block toolbar alignment.
* Fix a warning in the block menu.
* Change all blocks to use supports: align, instead of the align attribute.
* Improve the ContrastChecker logic for large font sizes.
* Update the is-shallow-equal package to use ES5 code.
* Deprecate getMimeTypesArray, mediaUpload, and preloadImage.
* Deprecate wideAlign in favour of alignWide.
* Document Node version switching in the testing documentation.
* Document examples of the registerBlockType hook.
* Document an example of the block transforms property.
* Document Gutenberg’s camelCase coding style.
* Improved all of the package descriptions.
* Update coding standards to allow double quoted strings to avoid escaping single quotes.
* Standardise the package descriptions and titles.
* Extract the editor package.
* Isolate and reset e2e tests every run.
* Improve test configuration and mocking strategy.
* Fix test coverage configuration.
* Fix the block icons e2e tests.
* Bump the Puppeteer version.
* Use simpler jest.fn() mocks for api-fetch calls in unit tests.
