=== IBM Watson Assistant ===
Contributors: cognitiveclass
Tags:  chat, chatbot, chat bot, artificial intelligence, support, faq
Requires at least: 4.7
Tested up to: 5.2.2
Stable tag: 0.8.20
License: Apache v2.0
License URI: http://www.apache.org/licenses/LICENSE-2.0

This plugin allows you to easily add chatbots powered by IBM Watson Assistant to your website.

== Description ==

Add this plugin to your site and let IBM Watson help you better support your customers. In a few minutes, you can train Watson to answer frequently asked questions, provide useful information and help them navigate your website. And if they want to talk to a human, the plugin can connect them to a real operator via telephone.

This plugin uses IBM Watson Assistant (formerly Watson Conversation) on the IBM Cloud. You can use it with an IBM Cloud Lite account free of charge and without a need to provide a credit card. If you take [this free course](https://cocl.us/build-a-chatbot) you may get a special offer of US$1200 of IBM Cloud usage to deploy chatbots for much higher usage. If you build web sites and chatbots as a business you may qualify for [a special ISV program](https://cocl.us/CB0103EN_WATR_WPP).

Currently supported features:

* Take advantage of [Rich responses](https://console.bluemix.net/docs/services/conversation/dialog-overview.html#multimedia) to add images, pauses, and clickable responses to your chatbot
* Use user's account data such as name in chatbot dialog
* Easy VOIP calling powered by Twilio for users to contact a real person if they wish
* Simple plugin setup to get your Watson Assistant chatbot available to users as soon as possible
* Control usage of the Watson Assistant service directly from the plugin settings page
* Choose the pages and posts you want the visitors to see the chat bot on
* Customize the appearance of the chat box to your preference

== Installation ==

= Requirements =

This plugin requires the [WordPress REST API Plugin](https://en-ca.wordpress.org/plugins/rest-api/) to be installed. If you have WordPress 4.7 or later, this is installed by default.

= Installing the Plugin =

1. Log in to your site’s Dashboard.
1. Click on the `Plugins` tab in the left panel, then click the `Add New` button.
1. Search for “Watson Assistant” and the latest version will appear at the top of the list of results.
1. Install the plugin by clicking the `Install Now` link.
1. When installation finishes, click `Activate Plugin`.

This plugin can also be installed manually.

**Note:**
If your WordPress site is hosted by WordPress (with a URL like `websitename.wordpress.com`), you need a paid plan to install plugins. If your WordPress is hosted separately, you should have no issue.

= Building Your Chatbot =

1. Learn how to set up your Watson Assistant chatbot with [this quick free course](https://cocl.us/build-a-chatbot).

1. [Sign up for a free IBM Cloud Lite account.](https://cocl.us/bluemix-registration)

1. You can see [the Watson Assistant documentation](https://cocl.us/watson-conversation-help) for more information.

Once you've created your workspace using the course or the link above, you must connect it to your Wordpress site.

= Setting up the Plugin =

1.  From the Deploy tab of your workspace, you must obtain your username and password credentials in addition to the Workspace URL of your new workspace.

1. Enter these on the "Main Setup" tab of your settings page. Once you click "Save Changes", the plugin will verify if the credentials are valid and notify you of whether or not the configuration was successful. 

1. (Optional) By default, the chatbot shows up on all pages of your website. In the Behaviour tab of your settings page, you can choose which pages to show the chat bot on. You can also show the chat box inline within posts and pages using the shortcode `[watson-chat-box]`

**Note:**
If you have a server-side caching plugin installed such as WP Super Cache, you may need to clear your cache after changing settings or deactivating the plugin. Otherwise, your action may not take effect.

== Frequently Asked Questions ==

= What is the best place to learn how to create a chatbot? =

Check out [this free course](https://cocl.us/build-a-chatbot) to learn how to build your own chatbot.

= Why should I use this? =

Watson Assistant, when used with this plugin, allows you to build and deploy a fully customized chat bot with little technical knowledge. It can talk to your website's visitors about whatever you choose, from helping navigate the website and providing support with common questions, to just having a casual conversation on a topic of interest.

= Do I need to know how to code? =

Nope. This plugin allows you to easily deploy chatbots that you create using the Watson Assistant service on IBM Cloud. [This free course](https://cocl.us/build-a-chatbot) will guide you through this intuitive process – no prior technical knowledge necessary.

= How do I see my chatbot's conversations with users? =

On the same page where you build your chatbot in IBM Cloud, you can click on the Improve tab to view and analyze past conversations with users.

== Screenshots ==
1. An example of your chatbot greeting a website visitor.

== Changelog ==

= 0.8.20 =
* Fixed bug with layout on mobile Safari

= 0.8.19 =
* Updated compatibility with IBM Watson Assistant after API changing

= 0.8.18 =
* Fixed bug with initiate a conversation

= 0.8.17 =
* Added private pages to the list of pages on which the Chat Box may be displayed.

= 0.8.16 =
* Fixed conflict with translate plugin
* Upgraded instruction for updating Assistant credentials
* Fixed out of screen input field on mobile (android)

= 0.8.15 =
* Fixed bug with layout in Safari desktop

= 0.8.14 =
* Fixed out of screen input field on mobile

= 0.8.13 =
* Fix misspelling
* Automatically make a plain URL clickable in the chatbot
* Associate a face or logo to the chatbot
* Fix out of screen input field on mobile Chrome
* Check curl availability

= 0.8.12 =
* Refactored cross-tab session synchronization

= 0.8.11 =
* Fixed usage notification duplicate e-mails
* Refactored plug-in usage summary notification mail template
* Added warning to Watson Assistant API v1 usage

= 0.8.10 =
* Fixed compatibility issue with MySQL versions lower than 5.6

= 0.8.9 =
* Fixed compatibility issue with PHP versions lower than 5.4
* Enhanced logging
* Improved plug-in stability

= 0.8.8 =
* Fixed compatibility issue with PHP versions lower than 5.4
* Fixed role authorization issue
* Added log events
* Improved "Send test notification e-mail" feature
* Refactored plug-in usage summary notification mail template

= 0.8.7 =
* Fixed bug with Advanced page

= 0.8.6 =
* Improved "Having Issues?" tab UI
* Fixed chatbot initial message issue

= 0.8.5 =
* Ensure conversation is shared between tabs
* Improved error handling
* Improved plug-in stability
* Fixed MariaDB compatibility
* Refactored "Having Issues?" tab, added "Download full log file" link, added "Copy messages to clipboard" button

= 0.8.4 =
* Added Chat History Collection feature
* Added E-mail Notification (ChatBot invocation summary) feature
* Added Mail Settings section

= 0.8.3 =
* Added context variable option with Plug-in version
* Switched to User/Password credentials

= 0.8.2 =
* Fixed dialog context integration

= 0.8.1 =
* Made Watson Assistant API version 2 default

= 0.8.0 =
* Updated instructions on plugin configuration

= 0.7.9 =
* Added support for Watson Assistant API v2

= 0.7.8 =
* Fixed issue with Watson Dialog Options integration

= 0.7.7 =
* Fixed issue with chatbot not responding in certain Wordpress environments
* Fixed minimization setting for small devices
* Added debug information to make addressing user's issues faster and easier
* Fixed a bug with voice calling not always loading the required scripts

= 0.7.6 =
* Fixed issue with API key authentication on some Wordpress installations
* Fixed “Please fill in your Watson Assistant Workspace Credentials” link

= 0.7.5 =
* Added support for new Watson response types (Images, Options and Pauses)

= 0.7.4 =
* Fixed issue with new users entering workspace URL with username/password credentials
* Fixed "Enabled" setting bug

= 0.7.3 =
* Fixed compatibility issue with PHP versions lower than 5.6

= 0.7.2 =
* Added option for new type of credentials, used by services in Sydney
* Fixed small styling issues

= 0.7.1 =
* Fixed critical bug caused by missing files

= 0.7.0 =
* Reorganized and moved settings to top-level menu
* Fixed chat box minimize bug
* Fixed issue with chat box disappearing sometimes

= 0.6.10 =
* Added option for message delay with "typing" ellipses animation

= 0.6.9 =
* Fixed chat box preview in Appearance settings page
* Fixed issue with old credentials migration in some installations

= 0.6.8 =
* Fixed Context Variables bug
* Moved Clear Messages button to header
* Added timezone context variable

= 0.6.7 =
* Added example values to Context Variables page
* Added settings for FAB icon and text size

= 0.6.6 =
* New Context Variables Feature for using user account data in chat bot dialog
* Added full-screen specific font size
* Fixed chat box rendering bug

= 0.6.5 =
* Small UI improvements

= 0.6.4 =
* Fixed full screen caching issue
* Fixed issue with rendering lists in messages
* Added PHP compatibility check upon activation
* Small CSS improvements
* Fixed transient checks
* Decreased lower font size limit

= 0.6.3 =
* Added setting to control message displayed after API limit overage
* Added setting to temporarily disable chatbot without having to deactivate plugin
* Fixed compatibility issues with PHP 5.3

= 0.6.2 =
* Rebrand from Watson Conversation to Watson Assistant

= 0.6.1 =
* More detailed debug info for credential validation failure

= 0.6.0 =
* Added chat box shortcode feature
* Added more text customization to Appearance tab
* Added CSS caching to reduce server load
* Fixed bug causing some websites to have issues sending messages

= 0.5.10 =
* Added tooltips to settings
* Changed appearance of muliple-message responses
* Improved full screen customization

= 0.5.9 =
* Fixed appearance of send button for certain websites

= 0.5.8 =
* Fixed styling issues for some devices

= 0.5.7 =
* Improved spacing of chat box button
* Added more customization for chat box minimized state
* Added option for Send Message button
* Fixed appearance of multiple-message responses from chatbot

= 0.5.6 =
* Fixed issue with chat button remaining clickable when invisible

= 0.5.5 =
* Fixed browser caching issue preventing chatbox from appearing initially after updates

= 0.5.4 =
* Modified Wordpress hooks

= 0.5.3 =
* Fixed bug with credentials validation

= 0.5.2 =
* Added Wordpress hooks for sending and receiving messages
* Added extra debug information for credential validation failure
* Added Chat Button customization

= 0.5.1 =
* Fixed bug with Advanced page showing on wrong tab

= 0.5.0 =
* Added Preset Response Options feature
* Fixed issue where typing in message box caused media in previous messages to reload

= 0.4.2 =
* Added compatiblity with Internet Explorer
* Fixed chat box rendering for some Wordpress installations
* Fixed visual bug with long words in messages

= 0.4.1 =
* Fixed issue with voice call settings validation

= 0.4.0 =
* Added settings tab to help introduce plugin to new users
* Made some settings more intuitive
* Settings on all tabs are submitted together now

= 0.3.3 =
* Fixed bug with setting to start chat box minimized

= 0.3.2 =
* Fixed bug in Voice Call UI customization

= 0.3.1 =
* Removed font size cap, fixed font size issues for full screen

= 0.3.0 =
* Added voice calling feature using Twilio
* Improved compatibility with older PHP versions
* Added setting for full-screen UI on non-mobile devices

= 0.2.3 =
* Fixes bug causing links from chatbot to be same color as background.

= 0.2.2 =
* Improves backwards compatibility with older PHP versions
* Improves iOS support.

= 0.2.1 =
* Fixes bug where settings changes do not take effect.

= 0.2.0 =
* New UI for mobile devices.
* Added ability to clear messages.
* Fixed several small bugs.

= 0.1.4 =
* Fixed critical bug causing chat box to stick to cursor on some browsers, on some pages.

= 0.1.3 =
* Fixed some UI issues with the chat box being hidden and not staying minimized across pages.
* Adjusted `Show on Home Page` option to `Show on Front Page` instead.

= 0.1.2 =
* Changed UI to use floating action button for minimizing.

= 0.1.1 =
* Added setting allowing admin to specify API base URL.

== Upgrade Notice ==

= 0.4.2 =
This version adds compatiblity with Internet Explorer and fixes chat box rendering issues for some Wordpress installations.

= 0.2.1 =
This verison fixes a bug from 0.2.0 where settings changes do not take effect.

= 0.1.3 =
This version fixes some issues with UI and the Show on Home Page setting.

= 0.1.2 =
This version fixes issues with the UI on mobile devices by adding a floating action button.

= 0.1.1 =
This version adds support for custom API base URLs.
