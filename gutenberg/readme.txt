=== Gutenberg ===
Contributors: matveb, joen, karmatosed
Requires at least: 5.0.0
Tested up to: 5.1
Stable tag: 5.6.1
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

Check out the <a href="https://wordpress.org/gutenberg/handbook/reference/faq/">FAQ</a> for answers to the most common questions about the project.

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
- <a href="https://wordpress.org/gutenberg/handbook/reference/design-principles/">Design Principles and block design best practices</a>
- <a href="https://github.com/Automattic/wp-post-grammar">WP Post Grammar Parser</a>
- <a href="https://make.wordpress.org/core/tag/gutenberg/">Development updates on make.wordpress.org</a>
- <a href="https://wordpress.org/gutenberg/handbook/">Documentation: Creating Blocks, Reference, and Guidelines</a>
- <a href="https://wordpress.org/gutenberg/handbook/reference/faq/">Additional frequently asked questions</a>


== Changelog ==

## Features

*   Support setting a [width to the column block](https://github.com/WordPress/gutenberg/pull/15499).
*   Support showing [Post Content or excerpt in the Latest Posts](https://github.com/WordPress/gutenberg/pull/14627) [block](https://github.com/WordPress/gutenberg/pull/15453).
*   Support [headers and footers in the Table block](https://github.com/WordPress/gutenberg/pull/15409).

## Enhancement

*   Improve the UX of the Group block by using the [individual block appender](https://github.com/WordPress/gutenberg/pull/14943).
*   Support [updating images using Drag & Drop](https://github.com/WordPress/gutenberg/pull/14983).
*   Clarify the name of the [inline code format](https://github.com/WordPress/gutenberg/pull/15199).
*   Add a usability [warning when audio/video autoplay](https://github.com/WordPress/gutenberg/pull/15575) is applied.
*   Replace the [Page Break block icon](https://github.com/WordPress/gutenberg/pull/15627) with Material version.

## Bug Fixes

*   A11y:
    *   Fix [focal point picker input labels](https://github.com/WordPress/gutenberg/pull/15255).
    *   Add role to the [copy all content menu item](https://github.com/WordPress/gutenberg/pull/15383).
    *   Add [focus style to the document outline](https://github.com/WordPress/gutenberg/pull/15479) panel.
    *   Update the [save indicator contrast](https://github.com/WordPress/gutenberg/pull/15514) to pass AA.
    *   Fix The [tabbing order in the Gallery Block](https://github.com/WordPress/gutenberg/pull/15540).
    *   Improve the [contrast of the button focus styles](https://github.com/WordPress/gutenberg/pull/15544).
    *   Fixed [focus state of pressed AM/PM buttons](https://github.com/WordPress/gutenberg/pull/15582).
    *   Fix the [URLInput aria properties](https://github.com/WordPress/gutenberg/pull/15564).
    *   Avoid showing [pre-publish panel buttons](https://github.com/WordPress/gutenberg/pull/15460) as links.
    *   Fix the [focus state of the links shown as buttons](https://github.com/WordPress/gutenberg/pull/15601).
*   Fix [RTL keyboard interactions](https://github.com/WordPress/gutenberg/pull/15496).
*   Fix [updates to RichText formats not being reflected in the UI](https://github.com/WordPress/gutenberg/pull/15573) synchronously.
*   Fix extra line breaks added when [pasting from Google Docs](https://github.com/WordPress/gutenberg/pull/15557).
*   Fix [copy, paste JavaScript errors](https://github.com/WordPress/gutenberg/pull/14712) in paragraph blocks with locking enabled.
*   Fix [format buttons incorrectly toggled](https://github.com/WordPress/gutenberg/pull/15466) on RichText blur.
*   Preserve the [caret’s horizontal position](https://github.com/WordPress/gutenberg/pull/15624) when navigating blocks.
*   [Case-insensitive search](https://github.com/WordPress/gutenberg/pull/14786) for existing categories.
*   Prevent the [Code block from rendering embeds](https://github.com/WordPress/gutenberg/pull/13996) or shortcodes.
*   Fix the [SandBox component usage outside the WP-admin](https://github.com/WordPress/gutenberg/pull/15415) context.
*   Fix the  [Cover block’s deprecated version](https://github.com/WordPress/gutenberg/pull/15449) attributes.
*   Fix the [webpack dependency plugin](https://github.com/WordPress/gutenberg/pull/15430): filename function handling.
*   Support [watching block.json changes](https://github.com/WordPress/gutenberg/pull/15455) in our build tool.
*   Fix [ServerSideRender remaining in loading state](https://github.com/WordPress/gutenberg/pull/15412) when nothing is rendered.
*   Fix the [server-side registered blocks](https://github.com/WordPress/gutenberg/pull/15414) in the widgets screen.
*   Show the [block movers in the widgets](https://github.com/WordPress/gutenberg/pull/15076) screen.
*   Decode the [HTML entities in the post author selector](https://github.com/WordPress/gutenberg/pull/15090).
*   Fix [inconsistent heading sizes](https://github.com/WordPress/gutenberg/pull/15393) between the classic and the heading blocks.
*   Add [check for author in post data before meta boxes save](https://github.com/WordPress/gutenberg/pull/15375) request submission.
*   [Allow blocks drag & drop](https://github.com/WordPress/gutenberg/pull/14521) if locking is set to "insert".
*   Fix [Youtube embed styles](https://github.com/WordPress/gutenberg/pull/14748) when used multiple times in a post.
*   [Proxy the code/block-editor replaceBlock](https://github.com/WordPress/gutenberg/pull/15528) action in the core/editor package.
*   Use [block-editor instead of editor in cover block](https://github.com/WordPress/gutenberg/pull/15547).
*   Fix [block showing an error in Safari](https://github.com/WordPress/gutenberg/pull/15576) when focused.
*   Fix small visual error in the [active state of the formatting buttons](https://github.com/WordPress/gutenberg/pull/15592).
*   Fix the pinned [plugins buttons styles](https://github.com/WordPress/gutenberg/pull/15609) when toggled.
*   Set caret position correctly when [merging blocks using the Delete key](https://github.com/WordPress/gutenberg/pull/15599).

## Documentation

*   Clarify [best practices pertaining to Color Palette](https://github.com/WordPress/gutenberg/pull/15006) values.
*   Use [Block Editor instead of Gutenberg](https://github.com/WordPress/gutenberg/pull/15411) when appropriate in the Handbook.
*   [Omit docblocks with private tag](https://github.com/WordPress/gutenberg/pull/15173) in the API Docs generation.
*   Update docs for [selectors & actions moved to block-editor](https://github.com/WordPress/gutenberg/pull/15424).
*   Update i18n docs to use [make-json command](https://github.com/WordPress/gutenberg/pull/15303) from wp-cli.
*   Consolidate [doc generation tools](https://github.com/WordPress/gutenberg/pull/15421).
*   Fix [next/previous links issue](https://github.com/WordPress/gutenberg/pull/15456).
*   Webpack dependency plugin: Document [unsupported multiple instances](https://github.com/WordPress/gutenberg/pull/15451).
*   Add a [DevHub manifest file](https://github.com/WordPress/gutenberg/pull/15254) to allow synchronizing the documentation to the DevHub.
*   Typos and tweaks: [1](https://github.com/WordPress/gutenberg/pull/15251), [2](https://github.com/WordPress/gutenberg/pull/15204), [3](https://github.com/WordPress/gutenberg/pull/15260), [4](https://github.com/WordPress/gutenberg/pull/15262), [5](https://github.com/WordPress/gutenberg/pull/15170), [6](https://github.com/WordPress/gutenberg/pull/15386), [7](https://github.com/WordPress/gutenberg/pull/15423), [8](https://github.com/WordPress/gutenberg/pull/15448), [9](https://github.com/WordPress/gutenberg/pull/15454), [10](https://github.com/WordPress/gutenberg/pull/15494), [11](https://github.com/WordPress/gutenberg/pull/15506), [12](https://github.com/WordPress/gutenberg/pull/15527), [13](https://github.com/WordPress/gutenberg/pull/15508), [14](https://github.com/WordPress/gutenberg/pull/15505), [15](https://github.com/WordPress/gutenberg/pull/15612).

## Various

*   Support and use the [shorthand Fragment](https://github.com/WordPress/gutenberg/pull/15120) [syntax](https://github.com/WordPress/gutenberg/pull/15261).
*   Update to [Babel 7.4 and core-js 3](https://github.com/WordPress/gutenberg/pull/15139).
*   Upgrade [simple-html-tokenizer](https://github.com/WordPress/gutenberg/pull/15246) dependency.
*   Pass individual files as arguments from watch to [build script](https://github.com/WordPress/gutenberg/pull/15219).
*   Automate the [scripts dependencies](https://github.com/WordPress/gutenberg/pull/15124) generation.
*   [Clean DropZone](https://github.com/WordPress/gutenberg/pull/15224) component’s unused state.
*   remove [\_\_unstablePositionedAtSelection](https://github.com/WordPress/gutenberg/pull/15035) component.
*   Refactor core/edit-post [INIT effect to use action-generators](https://github.com/WordPress/gutenberg/pull/14740) and controls.
*   Improve [eslint disable comments](https://github.com/WordPress/gutenberg/pull/15384).
*   Fix NaN [warning when initializing the FocalPointPicker](https://github.com/WordPress/gutenberg/pull/15400) component.
*   [Export React.memo](https://github.com/WordPress/gutenberg/pull/15385) in the @wordpress/element package.
*   Improve the [specificity of the custom colors styles](https://github.com/WordPress/gutenberg/pull/15167).
*   [Preload the autosaves endpoint](https://github.com/WordPress/gutenberg/pull/15067) to avoid request when loading the editor.
*   A11y: Add support for [Axe verification in e2e tests](https://github.com/WordPress/gutenberg/pull/15018).
*   Refactor the [File block to use the block.json](https://github.com/WordPress/gutenberg/pull/14862) syntax.
*   Remove [redundant duplicated reducers](https://github.com/WordPress/gutenberg/pull/15142).
*   Add [integration tests for blocks with deprecations](https://github.com/WordPress/gutenberg/pull/15268).
*   Allow [non-production env in wp-scripts build](https://github.com/WordPress/gutenberg/pull/15480).
*   Use [React Hooks in the BlockListBlock component](https://github.com/WordPress/gutenberg/pull/14985).
*   Add an [e2e test for custom taxonomies](https://github.com/WordPress/gutenberg/pull/15151).
*   Fix [intermittent failures on block transforms](https://github.com/WordPress/gutenberg/pull/15485) tests.
*   Remove [componentWillReceiveProps usage from ColorPicker](https://github.com/WordPress/gutenberg/pull/11772).
*   Add an [experimental](https://github.com/WordPress/gutenberg/pull/15563) [CPT to be used in the block based widgets screen](https://github.com/WordPress/gutenberg/pull/15014).
*   Split [JavaScript CI tasks to individual jobs](https://github.com/WordPress/gutenberg/pull/15229).
*   Remove [unnecessary aria-label from the block inserter](https://github.com/WordPress/gutenberg/pull/15382) list items.

## Mobile

*   Add a first version of the [video block](https://github.com/WordPress/gutenberg/pull/14912).
*   Fix the [Auto-scroll behavior on List](https://github.com/WordPress/gutenberg/pull/15048) block.
*   Fix the [list handling on Android](https://github.com/WordPress/gutenberg/pull/15168).
*   Improve [accessibility of the missing block](https://github.com/WordPress/gutenberg/pull/15457).
