=== Tribe Common ===


== Changelog ==

= [4.8.3] 2018-12-19 =

* Tweak - Refreshing the Welcome page for The Events Calendar and Event Tickets [117795]
* Fix - Prevent admin tooltips to that full page width on Blocks Editor [118883]
* Fix - Datepicker code will now use the correct datetime format [117428]

= [4.8.2] 2018-12-13 =

* Feature - Add new action `tribe_editor_register_blocks` used to register Event blocks via `common`
* Fix - Make sure assets are injected before is too late
* Fix - Fix an issue where feature detection of async-process support would fire too many requests [118876]
* Fix - Interface and Abstracts for REST base structures are now PHP 5.2 compatible
* Fix - Ensure admin CSS is enqueued any time a notice is displayed atop an admin page [119452]
* Fix - Prevent to trigger error when using `array_combine` with empty arrays
* Fix - Compatiblity with classic editor plugin [119426]
* Tweak - Add functions to remove inner blocks [119426]

= [4.8.1] 2018-12-05 =

* Fix - speed up and improve robustness of the asynchronous process feature detection code [118934]
* Language - 0 new strings added, 0 updated, 1 fuzzied, and 0 obsoleted

= [4.8.0.1] 2018-11-30 =

* Fix - Added safety measure to reduce risk of a fatal error when examining list of network-activated plugins [115826]
* Fix - Corrected a usage of array syntax within the PUE client, in order to ensure compatibility with PHP 5.2.4 (our thanks to @megabit81 for promptly flagging this issue!) [119073]
* Language - 0 new strings added, 3 updated, 1 fuzzied, and 0 obsoleted

= [4.8] 2018-11-29 =

* Add - Added `tribe_cache_expiration` filter that allows plugins to use persistent caching based on cache key [117158]
* Fix - The invalid license key notice won't be displayed for Products with empty license keys [115562]
* Language - 9 new strings added, 7 updated, 1 fuzzied, and 0 obsoleted

= [4.7.23.1] 2018-11-21 =

* Fixed - Use of the `wp_doing_cron` function that would break compatibility with sites not on WordPress version 4.8 or later [118627]

= [4.7.23] 2018-11-13 =

* Add - Added `Tribe__Admin__Notice__Marketing` class for bespoke marketing admin notices [114903]
* Add - Added `TRIBE_HIDE_MARKETING_NOTICES` constant that, if defined to `true` in your site's `wp-config.php` file, will hide all marketing admin notices [114903]
* Fix - Fixed the setting-up of strings in the Tribe Bar datepicker to ensure they're translatable into languages other than English [115286]
* Language - 1 new strings added, 22 updated, 1 fuzzied, and 0 obsoleted

= [4.7.22] 2018-10-22 =

* Fix - Update `Tribe__Admin__Help_Page::is_current_page()` to return true when viewing the help page from the network settings [109563]
* Language - 3 new strings added, 35 updated, 3 fuzzied, and 1 obsoleted

= [4.7.21] 2018-10-03 =

* Fix - Only load Customizer CSS when loading main stylesheets or widget stylesheets of PRO [112127]
* Fix - Restore functionality of admin notices that display when a license key is invalid (thanks to @tyrann0us on GitHub for submitting the fix!) [113660]
* Fix - Update our mascot terminology to the preferred verbiage [114426]
* Fix - Handle the upload of images with more complex URLs [114201]
* Tweak - Added the `tribe_global_id_valid_types` action to allow new EA origins [114652]
* Tweak - Added the `tribe_global_id_type_origins` action to allow new EA origins [114652]

= [4.7.20] 2018-09-12 =
* Add - Added is_string_or_empty, is_image_or_empty, is_url_or_empty variations for REST API validation of values that are allowed to be set as empty [108834]
* Add - Introduce folder lookup for `Tribe__Template` to allow usage on Themes [112478]
* Fix - When option to avoid creating duplicate Organizers/Venues is enabled, we now exclude trash and autodraft posts when looking up potential duplicates [113882]
* Fix - Allow settings to restrict to only one country [106974]
* Tweak - Removed filters: `tribe_template_base_path`
* Tweak - Added new filters: `tribe_template_before_include`, `tribe_template_after_include`, `tribe_template_html`, `tribe_template_path_list`, `tribe_template_public_path`, `tribe_template_public_namespace`, `tribe_template_plugin_path`

= [4.7.19] 2018-08-22 =
* Fix - Add the following datepicker formats to the validation script: YYYY.MM.DD, MM.DD.YYYY, DD.MM.YYYY [102815]
* Add - Added the `Tribe__Process__Queue::delete_all_queues` method [111856]
* Tweak - updated some foundation code for the Tickets REST API [108021]
* Tweak - Event Aggregator Add-On text due to the removal of Facebook Imports [111729]

= [4.7.18] 2018-08-01 =
* Fix - Add `target="_blank"` to repository links in the Help Page [107974]
* Fix - Change 3rd parameter to be relative path to plugin language files instead of the mofile for load_plugin_textdomain(), thanks to jmortell [63144]
* Tweak - Deprecate the usage of old asset loading methods [40267]

= [4.7.17] 2018-07-10 =
* Add - Method to sanitize a multidimensional array [106000]
* Add - New is_not_null and is_null methods for Tribe__Validator__Base [109482]
* Tweak - Added new filter `tribe_plugins_get_list` to give an opportunity to modify the list of tribe plugins [69581]

= [4.7.16] 2018-06-20 =
* Fix - Fixed a PHP warning related to the RSS feed in the Help page [108398]
* Tweak - Add notices related to PHP minimum versions [107852]

= [4.7.15] 2018-06-04 =
* Add - Method to parse the Global ID string [104379]
* Add - Load tribe-common script to prevent undefined function errors with tribe-dropdowns [107610]

= [4.7.14] 2018-05-29 =

* Fix - Adjust the `Tribe__PUE__Checker` $stats creation regarding WordPress multisite installs [84231]
* Fix - Hide any errors generated by servers that don't support `set_time_limit()` [64183]

= [4.7.13] 2018-05-16 =

* Fix - Prevent PHP 5.2 error on new Queuing Proccess `T_PAAMAYIM_NEKUDOTAYIM` [106696]
* Fix - Modify some language and typos

= [4.7.12] 2018-05-09 =

* Fix - Updated datatables.js to its most recent version to prevent conflicts [102465]
* Tweak - Added the `Tribe__Process__Queue` class to handle background processing operations
* Tweak - Changed 'forums' for 'help desk' in the Help content [104561]
* Tweak - Updated datatables.js to most recent version, to prevent conflicts [102465]
* Tweak - Add `tribe_set_time_limit()` wrapper function to prevent errors from `set_time_limit()` [64183]
* Tweak - Changed 'forums' to 'help desk' throughout the content in the "Help" tab [104561]
* Language - 3 new strings added, 84 updated, 3 fuzzied, and 3 obsoleted

= [4.7.11] 2018-04-18 =

* Fix - Restore "type" attribute to some inline `<script>` tags to ensure proper character encoding in Customizer-generated CSS [103167]
* Tweak - Allow to register the same ID of a post if has multiple types for JSON-LD `<script>` tag [94989]
* Tweak - Added the `a5hleyrich/wp-background-processing` package and the asynchronous process handling base [102323]
* Tweak - Added the `Tribe__Process__Post_Thumbnail_Setter` class to handle post thumbnail download and creation in an asynchronous manner [102323]
* Tweak - Deprecated the `Tribe__Main::doing_ajax()` method and moved it to the `Tribe__Context::doing_ajax()` method [102323]
* Tweak - Modified the `select2` implementation to work with the `maximumSelectionSize` argument via data attribute. [103577]
* Tweak - Add new filters: `tribe_countries` and `tribe_us_states` to allow easier extensibility on the names used for each country [79880]
* Fix - Updated Timezones::abbr() with additional support for timezone strings not covered by PHP date format "T" [102705]

= [4.7.10] 2018-03-28 =

* Tweak - Adjusted app shop text in relation to Modern Tribe's ticketing solutions [101655]
* Tweak - Added wrapper function around use of `tribe_events_get_the_excerpt` for safety [95034]

= [4.7.9] 2018-03-12 =

* Tweak - Added the a `tribe_currency_cost` filtering for Currency control for Prices and Costs

= [4.7.8] 2018-03-06 =

* Feature - Added new `tribe_get_global_query_object()` template tag for accessing the $wp_query global without triggering errors if other software has directly manipulated the global [100199]
* Fix - Remove unnecessary timezone-abbreviation caching approach to improve accuracy of timezone abbreviations and better reflect DST changes [97344]
* Fix - Make sure JSON strings are always a single line of text [99089]

= [4.7.7.1] 2018-02-16 =

* Fix - Rollback changes introduced in version 4.7.7 to allow month view to render correctly.

= [4.7.7] 2018-02-14 =

* Fix - Fixed the behavior of the `tribe_format_currency` function not to overwrite explicit parameters [96777]
* Fix - Modified timezone handling in relation to events, in order to avoid DST changes upon conversion to UTC [69784]
* Tweak - Improved the performance of dropdown and recurrent events by using caching on objects (our thanks to Gilles in the forums for flagging this problem) [81993]
* Tweak - Reduced the risk of conflicts when lodash and underscore are used on the same site [92205]
* Tweak - Added the `tribe_transient_notice` and `tribe_transient_notice_remove` functions to easily create and remove fire-and-forget admin notices
* Language - 0 new strings added, 0 updated, 1 fuzzied, and 0 obsoleted

= [4.7.6] 2018-01-23 =

* Fix - Make sure to apply `$settings` to each section with the initial values in the customizer [96821]
* Tweak - Include permalink structure into the report for support [68687]
* Tweak - Added `not_empty()` validation method to the `Tribe__Validate` class for more options while validating date formats [94725]
* Tweak - Update label on report for support to avoid confusions [68687]
* Tweak - Deprecated the unused $timezone parameter in the `tribe_get_start_date()` and `tribe_get_end_date()` template tags [73400]

= [4.7.5] 2018-01-10 =

* Fix - Added safety check to avoid errors surrounding the use of count() (our thanks to daftdog for highlighting this issue) [95527]
* Fix - Improved file logger to gracefully handle further file system restrictions (our thanks to Richard Palmer for highlighting further issues here) [96747]

= [4.7.4] 2017-12-18 =

* Fix - Fixed Event Cost field causing an error if it did not contain any numeric characters [95400]
* Fix - Fixed the color of the license key validation messages [91890]
* Fix - Added a safety check to avoid errors in the theme customizer when the search parameter is empty (props @afragen)
* Language - 1 new strings added, 5 updated, 1 fuzzied, and 0 obsoleted

= [4.7.3] 2017-12-07 =

* Tweak - Tweaked Tribe Datepicker to prevent conflicts with third-party styles [94161]

= [4.7.2] 2017-11-21 =

* Feature - Added Template class which adds a few layers of filtering to any template file included
* Tweak - Included `tribe_callback_return` for static returns for Hooks
* Language - 0 new strings added, 0 updated, 1 fuzzied, and 0 obsoleted

= [4.7.1] 2017-11-16 =

* Fix - Added support for translatable placeholder text when dropdown selectors are waiting on results being returned via ajax [84926]
* Fix - Implemented an additional file permissions check within default error logger (our thanks to Oscar for highlighting this) [73551]
* Tweak - Added new `tribe_is_site_using_24_hour_time()` function to easily check if the site is using a 24-hour time format [78621]
* Tweak - Ensure the "Debug Mode" helper text in the Events Settings screen displays all of the time (it previously would vanish with certain permalinks settings) [92315]
* Tweak - Allow for non-Latin characters to be used as the Events URL slug and the Single Event URL slug (thanks @daviddweb for originally reporting this) [61880]
* Tweak - Removed restrictions imposed on taxonomy queries by Tribe__Ajax__Dropdown (our thanks to Ian in the forums for flagging this issue) [91762]
* Tweak - Fixed the definition of Tribe__Rewrite::get_bases() to address some PHP strict notices its previous definition triggered [91828]
* Language - 0 new strings added, 16 updated, 1 fuzzied, and 0 obsoleted

= [4.7] 2017-11-09 =

* Feature - Included a new Validation.js for Forms and Fields
* Feature - Included a Camelcase Utils for JavaScript
* Tweak - Added Groups functionality for Tribe Assets class
* Tweak - Improve Dependency.js with better Documentation
* Tweak - Timepicker.js is now part of Common instead of The Events Calendar
* Language - 0 new strings added, 23 updated, 1 fuzzied, and 0 obsoleted

= [4.6.3] 2017-11-02 =

* Fix - Added some more specification to our jquery-ui-datepicker CSS to limit conflicts with other plugins and themes [90577]
* Fix - Fixed compatibility issue with Internet Explorer 10 & 11 when selecting a venue from the dropdown (thanks (@acumenconsulting for reporting this) [72924]
* Fix - Improved process for sharing JSON data in the admin environment so that it also works within the theme customizer screen [72127]
* Tweak - Obfuscated the API key for the google_maps_js_api_key field in the "System Information" screen [89795]
* Tweak - Updated the list of countries used in the country dropdown [75769]
* Tweak - Added additional timezone handling facilities [78233]
* Language - 7 new strings added, 292 updated, 18 fuzzied, and 3 obsoleted

= [4.6.2] 2017-10-18 =

* Fix - Restored functionality to the "currency position" options in Events Settings, and in the per-event cost settings (props @schola and many others!) [89918]
* Fix - Added safety checks to reduce the potential for errors stemming from our logging facilities (shout out to Brandon Stiner and Russell Todd for highlighting some remaining issues here) [90436, 90544]
* Fix - Added checks to avoid the generation of warnings when rendering the customizer CSS template (props: @aristath) [91070]
* Fix - Added safety checks to the Tribe__Post_Transient class to avoid errors when an array is expected but not available [91258]
* Tweak - Improved strategy for filtering of JSON LD data (our thanks to Mathew in the forums for flagging this issue) [89801]
* Tweak - Added new tribe_is_wpml_active() function for unified method of checking (as its name implies) if WPML is active [82286]
* Tweak - Removed call to deprecated screen_icon() function [90985]

= [4.6.1] 2017-10-04 =

* Fix - Fixed issues with the jQuery Timepicker vendor script conflicting with other plugins' similar scripts (props: @hcny et al.) [74644]
* Fix - Added support within Tribe__Assets for when someone filters plugins_url() (Thank you @boonebgorges for the pull request!) [89228]
* Fix - Improved performance of retrieving the country and US States lists [68472]
* Tweak - Limited the loading of several Tribe Common scripts and stylesheets to only load where needed within the wp-admin (props: @traildamage ) [75031]
* Tweak - Removed explicit width styles from app shop "buy now" buttons to better accommodate longer language strings (thanks @abrain on GitHub for submitting this fix!) [88868]
* Tweak - Implemented a re-initializing of Select2 inputs on use of a browser's "Back" button to prevent some UI bugs, e.g. with such inputs' placeholder attributes not being populated (props @uwefunk!) [74553]
* Language - Improvement to composition of various strings, to aid translatability (props: @ramiy) [88982]
* Language - 3 new strings added, 331 updated, 1 fuzzied, and 2 obsoleted

= [4.6] 2017-09-25 =

* Feature - Add support for create, update, and delete REST endpoints
* Language - 1 new strings added, 24 updated, 1 fuzzied, and 0 obsoleted

= [4.5.13] 2017-09-20 =

* Feature - Remove 'France, Metropolitan' option from country list to prevent issues with Google Maps API (thanks @varesanodotfr for pointing this out) [78023]
* Fix - Prevents breakages resulting from deprecated filter hooks
* Tweak - Added an id attribute to dropdowns generated by the Fields API [spotfix]
* Fix - Prevents resetting selected Datatables rows when changing pages (thanks @templesinai for reporting) [88437]

= [4.5.12] 2017-09-06 =

* Fix - Added check to see if log directory is readable before listing logs within it (thank you @rodrigochallengeday-org and @richmondmom for reporting this) [86091]
* Tweak - Datatables Head and Foot checkboxes will not select all items, only the current page [77395]
* Tweak - Added method into Date Utils class to allow us to easily convert all datepicker formats into the default one [77819]
* Tweak - Added a filter to customize the list of states in the USA that are available to drop-downs when creating or editing venues.
* Language - 3 new strings added, 46 updated, 1 fuzzied, and 4 obsoleted

= [4.5.11] 2017-08-24 =

* Fix - Ensure valid license keys save as expected [84966]
* Tweak - Removing WP Plugin API result adjustments

= [4.5.10.1] 2017-08-16 =

* Fix - Fixed issue with JS/CSS files not loading when WordPress URL is HTTPS but Site URL is not (our thanks to @carcal1 for first reporting this) [85017]

= [4.5.10] 2017-08-09 =

* Fix - Added support to tribe_asset() for non-default plugin directions/usage from within the mu-plugin directory (our thanks to @squirrelandnnuts for reporting this) [82809]
* Fix - Made JSON LD permalinks overridable by all post types, so they can be filtered [76411]
* Tweak - Improve integration with the plugins API/add new plugins screen (our thanks to David Sharpe for highlighting this) [82223]
* Tweak - Improve the Select2 search experience (props to @fabianmarz) [84496]
* Language - 0 new strings added, 312 updated, 1 fuzzied, and 0 obsoleted

= [4.5.9] 2017-07-26 =

* Fix - Avoid accidental overwrite of options when settings are saved in a multisite context [79728]
* Fix - Provide a well sorted list of countries even when this list is translated (our thanks to Johannes in the forums for highlighting this) [69550]
* Tweak - Cleanup logic responsible for handling the default country option and remove confusing translation calls (our thanks to Oliver for flagging this!) [72113]
* Tweak - Added period "." separator to datepicker formats [65282]
* Tweak - Avoid noise relating to PUE checks during WP CLI requests

= [4.5.8] 2017-07-13 =

* Fix - Fixes to the plugin upgrade notice parser including support for environments where the data stream wrapper is unavailable [69486]
* Fix - Ensure the multichoice settings configured to allow no selection work as expected [73183]
* Fix - Enqueue expired notice and CSS on every admin page [81714]
* Tweak - Add helper to retrieve anonymous objects using the class name, hook and callback priority [74938]
* Tweak - Allow dependency.js to handle radio buttons. ensure that they are linked correctly. [82510]
* Fix - Allow passing multiple localize-scripts to tribe-assets. Don't output a localized scrip more than once. [81644]

= [4.5.7] 2017-06-28 =

* Fix - Made the App Shop and help pages work on Windows. [77975]
* Fix - Resolved issue where the Meta Chunker attempted to inappropriately chunk meta for post post_types [80857]
* Fix - Avoid notices during plugin update and installation checks [80492]
* Fix - Ensure an empty dateTimeSeparator option value doesn't break tribe_get_start_date output anymore. [65286]
* Tweak - Improve navigation to elements inside admin pages (don't let the admin toolbar obscure things) [41829]
* Tweak - Textual corrections (with thanks to @garrett-eclipse) [77196]

= [4.5.6] 2017-06-22 =

* Fix - Resolved issue where the Meta Chunker attempted to inappropriately chunk meta for post post_types [80857]
* Language - 0 new strings added, 0 updated, 1 fuzzied, and 0 obsoleted [tribe-common]

