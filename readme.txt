=== Force Admin Color Scheme ===
Contributors: coffee2code
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=6ARCFJ9TX3522
Tags: admin colors, color scheme, admin, staging, coffee2code
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
Requires at least: 3.8
Tested up to: 4.0
Stable tag: 1.0

Force a single admin color scheme for all users of the site.

== Description ==

Though usually an individually configurable aspect of WordPress, there are times when forcing a single admin color schemes upon all users of a site can be warranted, such as:

* Provide a unique backend color scheme for multiple sites used by the same set of users to reinforce the difference between the sites.
* Clearly denote backend differences between a production and staging/test instance of a site. Especially given in this situation with the same plugins active and often the same data present, it can be easy to get mixed up about what site you're actually on.
* Force a site branding appropriate color scheme.

Additionally, the plugin removes the "Admin Color Scheme" profile setting from users who don't have the capability to set the admin color scheme globally since being able to set its value gives them the false impression that it may actually apply.

Links: [Plugin Homepage](http://coffee2code.com/wp-plugins/force-admin-color-scheme/) | [Plugin Directory Page](https://wordpress.org/plugins/force-admin-color-scheme/) | [Author Homepage](http://coffee2code.com)


== Installation ==

1. Unzip `force-admin-color-scheme.zip` inside the plugins directory for your site (typically `/wp-content/plugins/`). Or install via the built-in WordPress plugin installer)
2. Activate the plugin through the 'Plugins' admin menu in WordPress
3. As an admin, edit your own profile (Users -> Your Profile) and choose the Admin Color Scheme you want to apply to all users by setting the color scheme for yourself.
4. Check the "Force this admin color scheme" checkbox and then save the update to your profile.

== Frequently Asked Questions ==

= Why isn't everyone seeing the same admin color scheme after activating this plugin? =

Have you followed all of the installation instructions? You must configure the forced admin color scheme by setting the color scheme for yourself while also checking the "Force this admin color scheme?" checkbox.

= If I forced an admin color scheme, then go back and select a new color scheme without checking the "Force this admin color scheme?" checkbox, what color scheme will I and others see? =

Everyone, including you, will continue to see the color scheme that had been last saved with the "Force this admin color scheme?" checkbox checked.

= How do I resume letting users pick their own color schemes? =

Deactivate the plugin.

= Does this plugin include unit tests? =

Yes.


== Changelog ==

= 1.0 (2014-09-26) =
* Initial public release


== Upgrade Notice ==

= 1.0 =
Initial public release.
