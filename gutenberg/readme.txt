=== Gutenberg ===
Contributors: matveb, joen, karmatosed
Requires at least: 5.1.0
Tested up to: 5.2
Stable tag: 6.2.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

The block editor was introduced in core WordPress with version 5.0. This beta plugin allows you to test bleeding-edge features around editing and customization projects before they land in future WordPress releases.

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

### Features

-   A11y: Support [Navigation and Edit modes](https://github.com/WordPress/gutenberg/pull/16500) to ease navigating between blocks.
-   [Support text alignments](https://github.com/WordPress/gutenberg/pull/16111) in Table block columns.
-   Support changing the [separator block color](https://github.com/WordPress/gutenberg/pull/16784).

### Enhancements

-   Improvements to the BlockPreview component:
	-   Support [previewing a multiple blocks](https://github.com/WordPress/gutenberg/pull/16033) (a template).
	-   [Unify BlockPreview and BlockPreviewContent](https://github.com/WordPress/gutenberg/pull/16801) into a unique component.
	-   Hide [block appenders](https://github.com/WordPress/gutenberg/pull/16887).
	-   [Expose the component](https://github.com/WordPress/gutenberg/pull/16834) in the block-editor module.
	-   [Scale the preview content](https://github.com/WordPress/gutenberg/pull/16873) according to the width of the preview container.
-   Improvements to the Modal component design:
	-   Increase the [padding of the Modal component](https://github.com/WordPress/gutenberg/pull/16690).
	-   Correct the [position of the close button](https://github.com/WordPress/gutenberg/pull/16883).
-   Use classnames instead of inline styles for text alignments in:
	-   [Verse block](https://github.com/WordPress/gutenberg/pull/16777).
	-   [Quote block](https://github.com/WordPress/gutenberg/pull/16779).
	-   [Paragraph block](https://github.com/WordPress/gutenberg/pull/16794).
-   Add a [purple color option](https://github.com/WordPress/gutenberg/pull/16833) to the default color palette.
-   A11y: Visible [focus and active styles for Windows high contrast mode](https://github.com/WordPress/gutenberg/pull/16554).
-   Improve the design of the [inline image controls](https://github.com/WordPress/gutenberg/pull/16793) in the Gallery block.
-   I18n: Align the [Read more string](https://github.com/WordPress/gutenberg/pull/16865) with WordPress Core.
-   Removes the word-break :break-all CSS rule from the [table cells](https://github.com/WordPress/gutenberg/pull/16741).
-   Update the [Notice dismiss button](https://github.com/WordPress/gutenberg/pull/16926) to match other Gutenberg UI (color and icon).
-   Modifies the shortcut hierarchy in the [keyboard shortcuts modal](https://github.com/WordPress/gutenberg/pull/16724).
-   Remove [edit gallery toolbar button](https://github.com/WordPress/gutenberg/pull/16778).
-   Add the possibility to [disable document settings panels registered by plugins](https://github.com/WordPress/gutenberg/pull/16900).
-   [ESLint plugin: Enable `wp` global by default](https://github.com/WordPress/gutenberg/pull/16904) in the `recommended` config.

### Experiments

-   Add a settings page to the plugin to [enable/disable experimental features](https://github.com/WordPress/gutenberg/pull/16626).
-   Add [padding when interacting with](https://github.com/WordPress/gutenberg/pull/14961)  [nested blocks](https://github.com/WordPress/gutenberg/pull/16820) to ease parent block selections.
-   Widgets Screen:
	-   Prevent the [block toolbar from overlapping](https://github.com/WordPress/gutenberg/pull/16765) the widget area header.
	-   Add the [BlockEditorKeyboardShortcuts](https://github.com/WordPress/gutenberg/pull/16972) component.
	-   Fixed [block paddings](https://github.com/WordPress/gutenberg/pull/16944).

### New APIs

-   Support [Entities](https://github.com/WordPress/gutenberg/pull/16823)  [Local Edits](https://github.com/WordPress/gutenberg/pull/16867) in the Core Data Module.
-   Support [autosaving entities](https://github.com/WordPress/gutenberg/pull/16903) in the Core Data Module.
-   Add support for [disabled dropdown items](https://github.com/WordPress/gutenberg/pull/15976) in SelectControl.
-   Add [onFocusOutside](https://github.com/WordPress/gutenberg/pull/14851) prop as a replacement to Popover onClickOutside.
-   [Stop using unstable props on DropdownMenu.](https://github.com/WordPress/gutenberg/pull/15968)

### Bug Fixes

-   [Prevent tooltips from appearing](https://github.com/WordPress/gutenberg/pull/16800) on mouse down.
-   Avoid passing event object to [save button onSave prop](https://github.com/WordPress/gutenberg/pull/16770).
-   Prevent [image captions loss](https://github.com/WordPress/gutenberg/pull/15004) when editing a Gallery block.
-   Rerender [FormtTokenField](https://github.com/WordPress/gutenberg/pull/14819) component when the suggestions prop changes.
-   Handle scalar [return types values in useSelect](https://github.com/WordPress/gutenberg/pull/16669).
-   Fix [php notice](https://github.com/WordPress/gutenberg/pull/16189) that can be triggered while using the Search block.
-   Fix the [Resolve Block Modal](https://github.com/WordPress/gutenberg/pull/15581) columns sizes.
-   Fix [duplicate content when pasting](https://github.com/WordPress/gutenberg/pull/16857) text into newly focused RichText.
-   Fix [Table block cell selection](https://github.com/WordPress/gutenberg/pull/16653) when clicking on the edge of the cells.
-   Prevent the [CSS reset](https://github.com/WordPress/gutenberg/pull/16856) from applying to the meta boxes.
-   Fix [misaligned Block toolbars](https://github.com/WordPress/gutenberg/pull/16858) on floated blocks.
-   Fix the [Notice component](https://github.com/WordPress/gutenberg/pull/16861) close button alignment and [height](https://github.com/WordPress/gutenberg/pull/16891).
-   [Link to the full size images](https://github.com/WordPress/gutenberg/pull/16011) in the Gallery block.
-   Avoid leaking CSS transforms when [disabling block animations](https://github.com/WordPress/gutenberg/pull/16893).
-   A11y: Avoid focusing the PostTitle component when [switching between code and visual editor](https://github.com/WordPress/gutenberg/pull/16874).
-   A11y: Add a [confirmation step to enable the Custom Fields](https://github.com/WordPress/gutenberg/pull/15688)  [option](https://github.com/WordPress/gutenberg/pull/16918).
-   [Disable block insertion buttons](https://github.com/WordPress/gutenberg/pull/15024) and [prevent moving blocks](https://github.com/WordPress/gutenberg/pull/14924) depending on the contextual restrictions (template locking and default block availability).
-   Fix [Block manager not honoring the allowed_block_types](https://github.com/WordPress/gutenberg/pull/16586) hook.
-   [Keep the Image block alt and caption attributes](https://github.com/WordPress/gutenberg/pull/16051) while uploading a new image.
-   Don't render [drop zone below the default block appender](https://github.com/WordPress/gutenberg/pull/16119).
-   Prevent horizontal [arrow navigation errors](https://github.com/WordPress/gutenberg/pull/16846).
-   Fix [shifting menu items on DropdownMenu](https://github.com/WordPress/gutenberg/pull/16871).
-   Make API Fetch [refresh nonces as soon as they expired](https://github.com/WordPress/gutenberg/pull/16683).

### Various
-   Github actions:
	-   [Automatically assign issues](https://github.com/WordPress/gutenberg/pull/16700) to PR authors.
	-   Automatically assign the [First-time Contributor label](https://github.com/WordPress/gutenberg/pull/16762).
-   Avoid [unguarded getRangeAt usage](https://github.com/WordPress/gutenberg/pull/16212) and add eslint rule.
-   Make the [e2e transforms tests](https://github.com/WordPress/gutenberg/pull/16739) more stable.
-   [ESLint no-unused-vars-before-return rule](https://github.com/WordPress/gutenberg/pull/16799): Exempt destructuring only if to multiple properties.
-   Output an [informational message for deprecations](https://github.com/WordPress/gutenberg/pull/16774) when no version provided.
-   Refactor [registry selectors](https://github.com/WordPress/gutenberg/pull/16692) to allow calling them from other regular selectors.
-   Bail early in the [deactivatePlugin e2e test utility](https://github.com/WordPress/gutenberg/pull/16816) if plugin is already inactive.
-   Fix the [CheckboxControl](https://github.com/WordPress/gutenberg/pull/16551)  [styles](https://github.com/WordPress/gutenberg/pull/16863) in a WordPress agnostic context.
-   Move the [auto-draft status and default title handling](https://github.com/WordPress/gutenberg/pull/16814) to the server.
-   Code quality tweaks to the [Table block e2e tests](https://github.com/WordPress/gutenberg/pull/16872).
-   [Fix JSDocs errors](https://github.com/WordPress/gutenberg/pull/16870) across the entire repository.
-   [Upgrade Lerna](https://github.com/WordPress/gutenberg/pull/16919) to the latest version (3.16.4).
-   [Upgrade](https://github.com/WordPress/gutenberg/pull/16875)  [Puppeteer](https://github.com/WordPress/gutenberg/pull/16937) to the latest version (1.19.0).
-   [Upgrade ESLint](https://github.com/WordPress/gutenberg/pull/16921) to the latest version (6.1.0).
-   Run [npm audit fix](https://github.com/WordPress/gutenberg/pull/16963) to fix dependency vulnerabilities.
-   Audit and fix all [missing or obsolete package dependencies](https://github.com/WordPress/gutenberg/pull/16969).
-   Fix issue with [jest caching of block.json](https://github.com/WordPress/gutenberg/pull/16899) files.
-   Add [eslint-plugin-jsdoc lint rule](https://github.com/WordPress/gutenberg/pull/16869) for better JSDoc linting.
-   Fix [intermittent RichText e2e test failures](https://github.com/WordPress/gutenberg/pull/16952).
-   [Replace the react-click-outside dependency usage](https://github.com/WordPress/gutenberg/pull/16878) with our own Higher-order component withFocusOutside.
-   Improve the [usage of eslint-disable directives](https://github.com/WordPress/gutenberg/pull/16941).
-   Migrate the [Github Actions](https://github.com/WordPress/gutenberg/pull/16981) to the new YAML syntax.

### Documentation

-   Enhance the components Design Documentation and guidelines:
	-   [DateTime](https://github.com/WordPress/gutenberg/pull/16757) component.
	-   [Spinner](https://github.com/WordPress/gutenberg/pull/16760) component.
	-   [ClipboardButton](https://github.com/WordPress/gutenberg/pull/16758) component.
-   Add section about adding [new dependencies to WordPress](https://github.com/WordPress/gutenberg/pull/16876)  [packages](https://github.com/WordPress/gutenberg/pull/16923).
-   Add [Figma ressources](https://github.com/WordPress/gutenberg/pull/16892) to the Design documentation.
-   Document [URL inputs reusable components](https://github.com/WordPress/gutenberg/pull/16566).
-   Typos and tweaks: [1](https://github.com/WordPress/gutenberg/pull/16852), [2](https://github.com/WordPress/gutenberg/pull/16832), [3](https://github.com/WordPress/gutenberg/pull/16908).

### Mobile

-   Refactor [BlockToolbar out of](https://github.com/WordPress/gutenberg/pull/16677)  [BlockList](https://github.com/WordPress/gutenberg/pull/16906).    
-   Fix [toolbar bottom inset for iPhone X](https://github.com/WordPress/gutenberg/pull/16961) devices.

