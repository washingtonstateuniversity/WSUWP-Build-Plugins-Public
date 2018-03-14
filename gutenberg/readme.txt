=== Gutenberg ===
Contributors: matveb, joen, karmatosed
Requires at least: 4.9
Tested up to: 4.9.4
Stable tag: 2.3.0
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

* Show the full block inserter (+ button) to the left of empty paragraphs and the default appender. Quick block options (based on compound frequency and recency) remain on the right.
* Insert default block as provisional — this reduces the proliferation of empty blocks as the editor removes these provisional blocks when unfocusing.
* When pressing enter from post title, insert initial block as provisional.
* Fade out the side inserter when typing on the newly created block.
* Group common block definition on inserters. Use 'frecency' to sort items on top of it.
* Improve the visual focus style for inbetween inserter.
* Move isTyping behaviour to a separate component.
* Inserting a block should only shift focus to a text field, otherwise focusing the block's "focus stop".
* Example: Inserting an image should focus the top-level placeholder.
* Pressing backspace or enter from the block's focus stop should respectively delete or insert a subsequent paragraph block.
* Example: Pressing enter or delete on an image placeholder.
* Pressing down arrow from a non-text-field should proceed with a tab transition as expected.
* Multi-selection at the last text field in a block now accounts for non-contenteditable text fields.
* Better internal identification of text fields for writing flow transitions. Previously, if a block contained a checkbox, radio, or other non-text input tags, they would be erroneously included in the writing flow sequence.
* Inserting paragraph block (quote, etc; those with text fields) via autocomplete should move focus to the cursor.
* Shift-arrow from a text field engages multi-selection, but not if there are other text fields in the intended direction in the same block.
* Cancel isTyping state when focusing non text field.
* Improve reliability of the block click-to-clear behavior.
* When clicking below the editor move focus to last text field — this includes creating a new provisional block if last block is not text. This is equivalent to the default block appender spanning the entire viewport height of the editable canvas.
* Introduce same undo buffering for general text to the post title (and other post properties).
* Allow breaking out of List block upon Enter on last empty line.
* Address conflicts between WritingFlow's selection transitioning and nested blocks by moving selection to the block's focus handler.
* Improve reusable block creation flow by focusing the title field and allowing the user to name their block immediately.
* Avoid calling callbacks on DropZone component if a file is dropped on another dropzone.
* Improve settings UI on mobile devices.
* Allow text to wrap within Button block.
* Restrict Popover focusOnMount to keyboard interaction. This seeks to improve the experience of interacting with popovers and popover menus based on usability and accessibility concerns.
* Optimize the behavior of subscribe to avoid calling a listener except in the case that state has in-fact changed.
* Move the behaviors to transition focus to a newly selected block from the WritingFlow component to the BlockListBlock component.
* Extract scroll preservation from ﻿BlockList as non-visual component
* Add Upload button to audio and video blocks.
* Refactor image uploads and added auto-filled captions using the image metadata.
* Limit CSS rules for lists to the visual editor area.
* Add aria-label to the post title.
* Add new distinctive icon for Cover Image block.
* Refactor PostTitle for easier select, deselect.
* Use ifViewportMatches HoC to render BlockMobileToolbar as appropriate.
* Introduce a new reusable Disabled component which intends to manage all field disabling automatically.
* Expand editor canvas as flex region improving deselect behaviour.
* Bump minimum font-size to 13px.
* Allow emojis to be displayed in permalink visual component.
* Respect HTML when readding paragraph-tags.
* Allow undefined return from withSelect mapSelectToProps.
* Update datepicker styling to inherit font-family/colour scheme.
* Move QueryControls and expose them for general use under components module.
* Address design issues with block dialog warnings on blocks that are too tall.
* Preserve "More" order during block conversion.
* Add capabilities handling for reusable blocks mapping to default roles.
* Add a "Write your story" filter.
* Return focus from Toolbar to selection when escape is pressed.
* Improve keyboard interaction on inserter tab panels.
* Simplify state management for the sidebar to make it easier to maintain.
* Update cover image markup and CSS.
* Fix sent parameter in onChange function of CheckboxControl.
* Fix errors in some localized strings.
* Fix problem with arrow navigation within Block Menu buttons.
* Fix issue with demo page being marked immediately as unsaved (and the subsequent autosave).
* Fix issue with a Reusable Blocks being keyboard navigable even when it is not supposed to be edited.
* Fix focusable matching elements with "contenteditable=false".
* Fix issue with empty paragraphs appearing after images when the images are inside an anchor.
* Fix issue when a block cannot be removed after being transformed into another block type.
* Fix issue with updating the author on published posts.
* Fix edgecases in Windows high contrast mode.
* Fix regression with inserter tabs colors.
* Fix handling of HTML captions on gallery and image.
* Fix heading subscript regression.
* Resolve a performance regression caused by a bailout condition in our chosen shallow-equality library.
* Handle calculateFrequency﻿ edge case on upgrading.
* Use lodash includes in NavigableMenu to address IE11 issue.
* Refer to reusable blocks as 'Shared Blocks'.
* Add domain argument to localization functions. Allow setting locale data by domain.
* Update localization functions to absorb errors from Jed.
* Make UrlInput a controlled component.
* Decode HTML entities in placeholders.
* Only allow whitespace around URL when attempting to transform pasted Embeds.
* Make preferences reducer deterministic.
* Remove max-width for meta boxes area inputs.
* Default to content-box box-sizing for the metabox area.
* Adjust inside padding of meta boxes to better accommodate plugins.
* Remove !important clauses from button styles.
* Keep update button enabled when there are metaboxes present.
* Clarify some inner workings of Block API functionality with comments.
* Rewrite data document as a walkthrough of wordpress/data.
* Revert the eslint --fix Git precommit hook.
* Extract shared eslint config.
* Add tests for the BlockSwitcher component.
* Add test cases for REQUEST_POST_UPDATE_FAILURE effect.
* Fail the build via ESLint error when deprecations marked for removal in a given version change are not removed.
* Update redux-optmist to 1.0.0.
* Update package-lock.json.
* Remove Deprecated Features planned for this release and start documenting them in a deprecated document.
