=== Multiple Post Passwords ===
Contributors: andreasmuench
Donate link: https://www.andreasmuench.de/wordpress/
Tags: password, protected, page, post, multiple, security
Requires at least: 4.7.0
Tested up to: 5.7
Requires PHP: 5.6
Stable tag: trunk
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Set multiple passwords for your protected pages so you can give them to different users.

== Description ==

This is a simple Plugin that let´s you set multiple passwords for your password protected posts and pages.

On posts/pages with password protection it will show an extra Metabox with a field to input additional passwords, one in each line.

Note that if you just changed a post/page to password protection you have to save once so that the extra field appears.

= Expire passwords =

You can also make passwords expire after x hours when being used. You can find the settings under Settings -> Multiple Post Passwords.

Note that the actual deletion of the passwords is triggered by a cronjob which is run every 30 minutes. So even if you set your expiry time to very short, it may still take 30 minutes until the password really expires.

Also note that the expiration only works for the additional passwords, not for the standard WordPress page/post password.

= Using lots of passwords on one page =

If you are using lots of passwords on one page and the password check takes a long time, you should activate the alternative password check in the settings to speed up the password check.

== Installation ==

1. Upload the zip to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Edit the post or page you want to protect with multiple passwords
4. When you set a password and save once, the field to add multiple passwords will appear in the top right corner
5. Set as many passwords as you wish, one in a line

== Frequently Asked Questions ==

= I cannot see where I can enter the additional passwords =

First set one password (as you normally would in WordPress), then save once and you´ll see the extra field where you can add the passwords.

In Gutenberg Editor you have to save and then completely reload the admin page.

We decided to not show the extra metabox if there is no password set so it does not clutter the interface.

= Where are the plugin settings =

You can find the settings under Settings -> Multiple Post Passwords.

= Does this support custom post types =

Yes, this was implemented in Version 1.0.2

= What about feature X =

Just go to the support forums and kindly ask for it, then we´ll see what we can do. Thank you!

== Screenshots ==

1. The meta box to add additional passwords in classic editor

2. The additional passwords meta box in gutenberg editor

3. Plugin settings screen


== Changelog ==

= 1.1.0 =
* feature: add ability to expire used passwords
* feature: implement alternative password checking method -> quicker for alot of passwords

= 1.0.3 =
* improvement: implement cache to speed things up for many passwords in one post/page

= 1.0.2 =
* feature: add support for custom post types

= 1.0.1 =
* remove wordpress brand from url domains
* fix post sanitization

= 1.0.0 =
* Initial release

