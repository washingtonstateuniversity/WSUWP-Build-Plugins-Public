=== Gutenberg ===
Contributors: matveb, joen, karmatosed
Requires at least: 5.1.0
Tested up to: 5.2
Stable tag: 6.4.0
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
*   Add a [new](https://github.com/WordPress/gutenberg/pull/17402) [Social links](https://github.com/WordPress/gutenberg/pull/16897) [block](https://github.com/WordPress/gutenberg/pull/17380).
*   Support [border radius changes](https://github.com/WordPress/gutenberg/pull/17253) in the Button block.
*   Support [adding a caption to the Gallery block](https://github.com/WordPress/gutenberg/pull/17101).
*   Support [local autosaves](https://github.com/WordPress/gutenberg/pull/16490).
### Enhancements
*   [Disable the click-through](https://github.com/WordPress/gutenberg/pull/17239) behavior in desktop.
*   Update the [labels width](https://github.com/WordPress/gutenberg/pull/14478) to fit their content.
*   Avoid displaying console warnings when [blocks are upgraded using deprecated versions](https://github.com/WordPress/gutenberg/pull/16862).
*   Reduce the [padding around the in-between block inserter](https://github.com/WordPress/gutenberg/pull/17136).
*   Improve the design of [the](https://github.com/WordPress/gutenberg/pull/17315) [block movers](https://github.com/WordPress/gutenberg/pull/17216).
*   Align the [Gallery block image](https://github.com/WordPress/gutenberg/pull/17316) [controls](https://github.com/WordPress/gutenberg/pull/17374) with the block movers design.
*   [Remove child blocks](https://github.com/WordPress/gutenberg/pull/17128) from the block manager.
*   [Remove duplicated "Enable" label](https://github.com/WordPress/gutenberg/pull/17375) from the options panel.
*   Use [sentence case](https://github.com/WordPress/gutenberg/pull/17336) for all tooltips.
*   [Remove the forced gray scale](https://github.com/WordPress/gutenberg/pull/17415) from the category icons.
*   Move the [alignment controls to toolbar of the Heading](https://github.com/WordPress/gutenberg/pull/17419) block.
*   Use the [featured image frame](https://github.com/WordPress/gutenberg/pull/17410) in the Media modal.
### Bug Fixes
*   Update the [Post Schedule label](https://github.com/WordPress/gutenberg/pull/15757) to correctly reflect the date and time display settings.
*   Clean up the [block toolbar position](https://github.com/WordPress/gutenberg/pull/17197) for wide full blocks.
*   Fix the [cropped focus indicator](https://github.com/WordPress/gutenberg/pull/17215) in the block inserter.
*   Browser incompatibilities: 
    *   [Fallback to setTimeout in RichText](https://github.com/WordPress/gutenberg/pull/17213) if no requestIdleCallback is not supported.
    *   [Block toolbar fixes](https://github.com/WordPress/gutenberg/pull/17214) for IE11.
    *   Fix [Backspace usage in RichText](https://github.com/WordPress/gutenberg/pull/17256) for IE11.
*   Prevent clicking the [next/previous month in the Post Schedule](https://github.com/WordPress/gutenberg/pull/17201) popover from closing it.
*   Prevent the [private posts from triggering the unsaved changes](https://github.com/WordPress/gutenberg/pull/17210) [warnings](https://github.com/WordPress/gutenberg/pull/17257) after saving.
*   Fix the usage of the [useReducedMotion hook in Node.js](https://github.com/WordPress/gutenberg/pull/17165) context.
*   A11y: 
    *   Use [darker form field borders](https://github.com/WordPress/gutenberg/pull/17218).
    *   Fix the [modal escape key propagation](https://github.com/WordPress/gutenberg/pull/17297).
    *   [Move focus back from the Modal to the More Menu](https://github.com/WordPress/gutenberg/pull/16964) when it was used to open the Modal.
*   [Trim leading and trailing whitespaces](https://github.com/WordPress/gutenberg/pull/17320) when inserting links.
*   Prevent using the paragraph block when [pasting unformatted text into RichText](https://github.com/WordPress/gutenberg/pull/17140).
*   Fix styling of [classic block's block controls](https://github.com/WordPress/gutenberg/pull/17323).
*   Fix the [showing/hiding logic of the **Group** menu item](https://github.com/WordPress/gutenberg/pull/17353) in the block settings menu.
*   Fix [invalid HTML nesting](https://github.com/WordPress/gutenberg/pull/17342) of buttons.
*   Fix [React warning when using withFocusReturn](https://github.com/WordPress/gutenberg/pull/17354) Higher-order component.
*   Fix [lengthy content cuts](https://github.com/WordPress/gutenberg/pull/17365) in the Cover block.
*   Disable [multi-selection when resizing](https://github.com/WordPress/gutenberg/pull/17359).
*   Fix the [permalink UI in RTL](https://github.com/WordPress/gutenberg/pull/13919) languages.
*   Fix multiple issues related to the [reusable blocks](https://github.com/WordPress/gutenberg/pull/14367) editing/previewing UI.
*   Remove filter that [unsets auto-draft titles](https://github.com/WordPress/gutenberg/pull/17317).
*   Fix the [Move to trash](https://github.com/WordPress/gutenberg/pull/17427) button redirection.
*   Prevent [undo/redo history cleaning on autosaves](https://github.com/WordPress/gutenberg/pull/17420).
*   Add i18n support for title [Content Blocks string](https://github.com/WordPress/gutenberg/pull/17435).
*   Add missing extra [classnames to the Column block](https://github.com/WordPress/gutenberg/pull/17422).
*   Fix  JavaScript error triggered when using a [multi-line RichText](https://github.com/WordPress/gutenberg/pull/17447).
*   Fix [RichText](https://github.com/WordPress/gutenberg/pull/17451) [focus](https://github.com/WordPress/gutenberg/pull/17450) related issues.
*   Fix [undo levels](https://github.com/WordPress/gutenberg/pull/17259) [inconsistencies](https://github.com/WordPress/gutenberg/pull/17452).
*   Fix [multiple post meta fields edits](https://github.com/WordPress/gutenberg/pull/17455).
*   Fix [selecting custom colors](https://github.com/WordPress/gutenberg/pull/17381) in RTL languages.
### Experiments
*   Add [one-click search and install blocks](https://github.com/WordPress/gutenberg/pull/17431) from the block directory to the inserter.
*   Refactor the [Navigation block](https://github.com/WordPress/gutenberg/pull/16796) [to](https://github.com/WordPress/gutenberg/pull/17343) [be](https://github.com/WordPress/gutenberg/pull/17328) a dynamic block.
*   Add a [block navigator to the Navigation](https://github.com/WordPress/gutenberg/pull/17265) [block](https://github.com/WordPress/gutenberg/pull/17446).
*   Only show the [customizer block based widgets](https://github.com/WordPress/gutenberg/pull/16956) if the experimental widget screen is enabled.
### APIs
*   Add a [disableDropZone prop for MediaPlaceholder](https://github.com/WordPress/gutenberg/pull/17077) component.
*   Add [post autosave locking](https://github.com/WordPress/gutenberg/pull/16249).
*   [PluginPrePublishPanel](https://github.com/WordPress/gutenberg/pull/16378) and [PluginPostPublishPanel](https://github.com/WordPress/gutenberg/pull/16383) support icon prop and inherits from registerPlugin.
*   Allow [disabling the Post Status](https://github.com/WordPress/gutenberg/pull/17117) settings panel.
*   Restore the [keepPlaceholderOnFocus](https://github.com/WordPress/gutenberg/pull/17439) [RichText](https://github.com/WordPress/gutenberg/pull/17445) prop.
### Various
*   Upgrade [React and React DOM](https://github.com/WordPress/gutenberg/pull/16982) to 16.9.0.
*   Add [TypeScript JSDoc linting](https://github.com/WordPress/gutenberg/pull/17014) to the @wordpress/url package.
*   Run [npm audit](https://github.com/WordPress/gutenberg/pull/17192) to fix the reported vulnerabilities.
*   Switch the local environment to an [environment based on the](https://github.com/WordPress/gutenberg/pull/17004) [Core setup](https://github.com/WordPress/gutenberg/pull/17296).
*   Set a constant namespace for [module sourcemaps](https://github.com/WordPress/gutenberg/pull/17024).
*   [Refactor the loading animation](https://github.com/WordPress/gutenberg/pull/17106) to rely on the Animate component.
*   Code improvements to [block PHP files](https://github.com/WordPress/gutenberg/pull/17288).
*   Enable the [duplicate style property](https://github.com/WordPress/gutenberg/pull/17287) linting rule.
*   Update [Husky & Lint-staged](https://github.com/WordPress/gutenberg/pull/17310) to the latest versions.
*   Restore the usage of the [latest npm version](https://github.com/WordPress/gutenberg/pull/17171) in CI.
*   Add [ESLint as peer dependency to eslint-plugin](https://github.com/WordPress/gutenberg/pull/17417).
*   [Conditionally include the block styles](https://github.com/WordPress/gutenberg/pull/17429) functionality to avoid conflicts with Core.
*   Add missing [deprecated setFocusedElement prop](https://github.com/WordPress/gutenberg/pull/17421) to the RichText component.
*   Support [generating assets in PHP format](https://github.com/WordPress/gutenberg/pull/17298) in the webpack dependency extraction plugin.
### Documentation
*   Update the [reviews and merging documentation](https://github.com/WordPress/gutenberg/pull/16915).
*   Fix [type docs](https://github.com/WordPress/gutenberg/pull/17206) for the Notices package.
*   Add a link to the [fixtures tests document](https://github.com/WordPress/gutenberg/pull/17283) in the Testing Overview.
*   Adds documentation for the [onClose prop of MediaUpload](https://github.com/WordPress/gutenberg/pull/17403).
*   Tweaks and typos: [1](https://github.com/WordPress/gutenberg/pull/17097), [2](https://github.com/WordPress/gutenberg/pull/17285), [3](https://github.com/WordPress/gutenberg/pull/17292), [4](https://github.com/WordPress/gutenberg/pull/17286), [5](https://github.com/WordPress/gutenberg/pull/17304), [6](https://github.com/WordPress/gutenberg/pull/17349), [7](https://github.com/WordPress/gutenberg/pull/17377), [8](https://github.com/WordPress/gutenberg/pull/17436).


