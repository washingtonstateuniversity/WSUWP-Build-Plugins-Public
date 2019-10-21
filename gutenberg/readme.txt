=== Gutenberg ===
Contributors: matveb, joen, karmatosed
Requires at least: 5.1.0
Tested up to: 5.2
Stable tag: 6.6.0
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

*   Add [gradient backgrounds support](https://github.com/WordPress/gutenberg/pull/17169) to the Button block.

### Bug Fixes

*   i18n : Include the plural version of the “[remove block](https://github.com/WordPress/gutenberg/pull/17665)” string.
*   Update dropdown menu items to match [hover](https://github.com/WordPress/gutenberg/pull/17621) [style](https://github.com/WordPress/gutenberg/pull/17581) in other places.
*   Smoothly [reposition Popovers on scroll](https://github.com/WordPress/gutenberg/pull/17699).
*   Fix [margin styles for Gallery](https://github.com/WordPress/gutenberg/pull/17694) and Social links blocks.
*   Fix [popovers hidden on mobile](https://github.com/WordPress/gutenberg/pull/17696).
*   Ensure [sidebar plugins do not get auto-closed](https://github.com/WordPress/gutenberg/pull/17712) when opened on small screens.
*   Fix the design of the [Checkbox component in IE11](https://github.com/WordPress/gutenberg/pull/17714).
*   Add [has-text-color](https://github.com/WordPress/gutenberg/pull/17742) classname to heading block.
*   Prevent [figure margin reset CSS](https://github.com/WordPress/gutenberg/pull/17737) from being included in the frontend.
*   Fix the [scaling of the pinned plugins menu icons](https://github.com/WordPress/gutenberg/pull/17752).
*   Fix [Heading and paragraph colors](https://github.com/WordPress/gutenberg/pull/17728) not applied inside the cover block.
*   [Close Nux tips](https://github.com/WordPress/gutenberg/pull/17663) when clicking outside the tip.
*   Fix [meta attribute source](https://github.com/WordPress/gutenberg/pull/17820) for post types other than post.
*   Fix ”[Open in New Tab](https://github.com/WordPress/gutenberg/pull/17794)” not being persisted.
*   Fix [redo](https://github.com/WordPress/gutenberg/pull/17827) [behavior](https://github.com/WordPress/gutenberg/pull/17861) and expand test coverage.
*   I18n: Fix missing translation for the [“All content copied” string](https://github.com/WordPress/gutenberg/pull/17828).
*   Fix the [block preview padding](https://github.com/WordPress/gutenberg/pull/17807) in themes with custom backgrounds.
*   Fix [merging list blocks](https://github.com/WordPress/gutenberg/pull/17845) with indented list items.
*   Fix [inline image controls](https://github.com/WordPress/gutenberg/pull/17750) display condition.
*   Fix clicking the [redirect element](https://github.com/WordPress/gutenberg/pull/17798) focuses the inserted paragraph.
*   Fix [editing meta attributes](https://github.com/WordPress/gutenberg/pull/17850) with multiple set to true.
*   Add [No Preview Available](https://github.com/WordPress/gutenberg/pull/17848) text to the inserter preview panel.
*   [Prevent block controls from disappearing](https://github.com/WordPress/gutenberg/pull/17876) when switching the List block type.
*   Avoid [trailing space](https://github.com/WordPress/gutenberg/pull/17842) at the end of a translatable string.
*   Fix [left aligned nested blocks](https://github.com/WordPress/gutenberg/pull/17804).
*   Fix the top margin of the [RadioControl help text](https://github.com/WordPress/gutenberg/pull/17677).
*   Fix [invalid HTML](https://github.com/WordPress/gutenberg/pull/17754) used in the Featured Image panel.
*   Make sure that all [edits after saving](https://github.com/WordPress/gutenberg/pull/17888) are considered persistent by default.
*   Ensure that [sidebar is closed on the first visit](https://github.com/WordPress/gutenberg/pull/17902) on small screens.
*   Update the [columns block example](https://github.com/WordPress/gutenberg/pull/17904) to avoid overlapping issues.
*   Remove unnecessary default styles for [H2 heading inside Cover blocks](https://github.com/WordPress/gutenberg/pull/17815).
*   Fix [Media & Text block alignment](https://github.com/WordPress/gutenberg/pull/10812) in IE11.
*   Remove [unnecessary padding](https://github.com/WordPress/gutenberg/pull/17907https://github.com/WordPress/gutenberg/pull/17907) in the Columns block.
*   Fix the [Columns block height](https://github.com/WordPress/gutenberg/pull/17901) in IE11.
*   Correctly update [RichText value after undo](https://github.com/WordPress/gutenberg/pull/17840).
*   Prevent the [snackbar link components](https://github.com/WordPress/gutenberg/pull/17887) from hiding on focus.
*   Fix [block toolbar position](https://github.com/WordPress/gutenberg/pull/17894) in IE11.
*   Retry [uploading images](https://github.com/WordPress/gutenberg/pull/17858) on failures.

### Performance

*   Avoid [continuously reset browser selection](https://github.com/WordPress/gutenberg/pull/17869) (improve typing performance in iOS).

### Enhancements

*   Polish [FontSize Picker design](https://github.com/WordPress/gutenberg/pull/17647).
*   Use body color for the [post publish panel](https://github.com/WordPress/gutenberg/pull/17731).
*   Limit the width and height of the [pinnable plugins icons](https://github.com/WordPress/gutenberg/pull/17722).
*   Add a [max width to the Search block](https://github.com/WordPress/gutenberg/pull/17648) input.

### Experiments

*   Menu Navigation block:
*   Implement initial state containing [top level pages](https://github.com/WordPress/gutenberg/pull/17637).
*   Fix [menu alignment](https://github.com/WordPress/gutenberg/pull/17630).
*   Fix the [classname](https://github.com/WordPress/gutenberg/pull/17853) in frontend.
*   Block Directory
*   Change the [relative time string](https://github.com/WordPress/gutenberg/pull/17535).
*   Widgets Screen
*   Fix the [styling of the inspector panel](https://github.com/WordPress/gutenberg/pull/17880).

### Documentation

*   Fix [@wordpress/data-controls examples](https://github.com/WordPress/gutenberg/pull/17773).
*   Typos and tweaks: [1](https://github.com/WordPress/gutenberg/pull/17821), [2](https://github.com/WordPress/gutenberg/pull/17909).

### Various

*   Introduce the [@wordpress/env](https://github.com/WordPress/gutenberg/pull/17668) package, A zero-config, self-contained local WordPress environment for development and testing.
*   Add [Storybook](https://github.com/WordPress/gutenberg/pull/17475) [to](https://github.com/WordPress/gutenberg/pull/17762) develop and showcase UI components:
*   [Add](https://github.com/WordPress/gutenberg/pull/17910) [ButtonGroup](https://github.com/WordPress/gutenberg/pull/17884) component.
*   Add [ScrollLock](https://github.com/WordPress/gutenberg/pull/17886) component.
*   Add [Animate](https://github.com/WordPress/gutenberg/pull/17890https://github.com/WordPress/gutenberg/pull/17890) component.
*   Add [Icon and IconButton](https://github.com/WordPress/gutenberg/pull/17868) components.
*   Add [ClipboardButton](https://github.com/WordPress/gutenberg/pull/17913) component.
*   Add [ColorIndicator](https://github.com/WordPress/gutenberg/pull/17924) component.
*   [Remove RichText](https://github.com/WordPress/gutenberg/pull/17607) [wrapper](https://github.com/WordPress/gutenberg/pull/17713) and use Popover for the inline toolbar.
*   Improve the way the [lock file](https://github.com/WordPress/gutenberg/pull/17705) handles local dependencies.
*   Refactor [ColorPalette](https://github.com/WordPress/gutenberg/pull/17154) by extracting its design.
*   Improve [E2E test reliability](https://github.com/WordPress/gutenberg/pull/17679) by consuming synchronous data and bailing on save failure.
*   Replace the [isDismissable prop with isDismissible](https://github.com/WordPress/gutenberg/pull/17689) in the Modal component.
*   Add eslint-plugin-jest to the default @wordpress/scripts [linting config](https://github.com/WordPress/gutenberg/pull/17744).
*   Update @wordpress/scripts to use the [latest version of webpack](https://github.com/WordPress/gutenberg/pull/17753) for build and start commands.
*   Cleanup [Dashicon component](https://github.com/WordPress/gutenberg/pull/17741).
*   Update the [Excerpt help link](https://github.com/WordPress/gutenberg/pull/17753).
*   [Release tool](https://github.com/WordPress/gutenberg/pull/17717): fix wrong package.json used when bumping the stable released version.
*   Fix several [typos](https://github.com/WordPress/gutenberg/pull/17666) in [code](https://github.com/WordPress/gutenberg/pull/17800) and [files](https://github.com/WordPress/gutenberg/pull/17782).
*   Update [E2E tests](https://github.com/WordPress/gutenberg/pull/17859) to accommodate WP 5.3 Beta 3 changes.
*   Define the “[sideEffects](https://github.com/WordPress/gutenberg/pull/17862)” property for @wordpress packages.
*   Add [nested embed e2e test](https://github.com/WordPress/gutenberg/pull/15909).
*   I18N: Always return the [translation file](https://github.com/WordPress/gutenberg/pull/17900) prefixed with `gutenberg-`.
*   Use [wp.org CDN for images](https://github.com/WordPress/gutenberg/pull/17935) used in block preview.

