=== User Switching ===

Contributors: johnbillion  
Tags: users, profiles, user switching, fast user switching, multisite, buddypress, bbpress, become, user management, developer  
Requires at least: 3.7  
Tested up to: 4.9  
Stable tag: 1.3.1  
License: GPL v2 or later  

Instant switching between user accounts in WordPress.

== Description ==

This plugin allows you to quickly swap between user accounts in WordPress at the click of a button. You'll be instantly logged out and logged in as your desired user. This is handy for test environments where you regularly log out and in between different accounts, or for administrators who need to switch between multiple accounts.

= Features =

 * Switch user: Instantly switch to any user account from the *Users* screen.
 * Switch back: Instantly switch back to your originating account.
 * Switch off: Log out of your account but retain the ability to instantly switch back in again.
 * It's completely secure (see the *Security* section below).
 * Compatible with WordPress, WordPress Multisite, BuddyPress and bbPress.

= Security =

 * Only users with the ability to edit other users can switch user accounts. By default this is only Administrators on single site installs, and Super Admins on Multisite installs.
 * Passwords are not (and cannot be) revealed.
 * Uses the cookie authentication system in WordPress when remembering the account(s) you've switched from and when switching back.
 * Implements the nonce security system in WordPress, meaning only those who intend to switch users can switch.
 * Full support for administration over SSL (if applicable).

= Usage =

 1. Visit the *Users* menu in WordPress and you'll see a *Switch To* link in the list of action links for each user.
 2. Click this and you will immediately switch into that user account.
 3. You can switch back to your originating account via the *Switch back* link on each dashboard screen or in your profile menu in the WordPress toolbar.

See the [FAQ](https://wordpress.org/plugins/user-switching/faq/) for information about the *Switch Off* feature.

= Privacy Statement =

User Switching makes use of browser cookies in order to allow users to switch to another account. Its cookies operate using the same mechanism as the authentication cookies in WordPress core, therefore they contain the user's `user_login` field in plain text which should be treated as potentially personally identifiable information. The name of the cookies are `wordpress_user_sw_{hash}`, `wordpress_user_sw_secure_{hash}`, and `wordpress_user_sw_olduser_{hash}`, where `{hash}` is an identifier unique to the installation of WordPress.

User Switching does not send data to any third party, nor does it include any third party resources, nor will it ever do so.

== Screenshots ==

1. The *Switch To* link on the Users screen
2. The *Switch To* link on a user's profile

== Frequently Asked Questions ==

= What does "Switch off" mean? =

Switching off logs you out of your account but retains your user ID in an authentication cookie so you can switch straight back without having to log in again manually. It's akin to switching to no user, and being able to switch back.

The *Switch Off* link can be found in your profile menu in the WordPress toolbar. Once you've switched off you'll see a *Switch back* link in the footer of your site.

= Does this plugin work with WordPress Multisite? =

Yes, and you'll also be able to switch users from the Users screen in Network Admin.

= Does this plugin work with BuddyPress? =

Yes, and you'll also be able to switch users from member profile screens and the member listing screen.

= Does this plugin work with bbPress? =

Yes, and you'll also be able to switch users from member profile screens.

= Does this plugin work if my site is using a two-factor authentication plugin? =

Yes, mostly.

One exception I'm aware of is [Duo Security](https://wordpress.org/plugins/duo-wordpress/). If you're using this plugin, you should install the [User Switching for Duo Security](https://github.com/johnbillion/user-switching-duo-security) add-on plugin which will prevent the two-factor authentication prompt from appearing when you switch between users.

= What capability does a user need in order to switch accounts? =

A user needs the `edit_users` capability in order to switch user accounts. By default only Administrators have this capability, and with Multisite enabled only Super Admins have this capability.

= Can regular admins on Multisite installs switch accounts? =

No. This can be enabled though by installing the [User Switching for Regular Admins](https://github.com/johnbillion/user-switching-for-regular-admins) plugin.

= Can I switch users directly from the admin toolbar? =

Yes, there's a third party add-on plugin for this: [Admin Bar User Switching](https://wordpress.org/plugins/admin-bar-user-switching/).

= Are any plugin actions called when a user switches account? =

Yes. When a user switches to another account, the `switch_to_user` hook is called with the new and old user IDs passed as parameters.

When a user switches back to their original account, the `switch_back_user` hook is called with the new (original) and old user IDs passed as parameters. Note that the old user ID can be boolean false if the user is switching back after they've been switched off.

When a user switches off, the `switch_off_user` hook is called with the old user ID as a parameter.

== Changelog ==

For User Switching's changelog, please see [the Releases page on GitHub](https://github.com/johnbillion/user-switching/releases).
