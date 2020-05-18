=== Force Admin Color Scheme ===
Contributors: coffee2code
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=6ARCFJ9TX3522
Tags: admin colors, color scheme, admin, staging, coffee2code
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Requires at least: 4.1
Tested up to: 5.4
Stable tag: 1.2.1

Force a single admin color scheme for all users of the site.

== Description ==

Though it is typically an individually configurable aspect of WordPress, there are times when forcing a single admin color scheme upon all users of a site can be warranted, such as to:

* Provide a unique backend color scheme for multiple sites used by the same set of users to reinforce the difference between the sites.
* Clearly denote backend differences between a production and staging/test instance of a site. Especially given that in this situation with the same plugins active and often the same data present, it can be easy to get mixed up about what site you're actually on.
* Force a site brand-appropriate color scheme.
* Crush the expression of individuality under your iron fist.

Additionally, the plugin removes the "Admin Color Scheme" profile setting from users who don't have the capability to set the admin color scheme globally since being able to set its value gives them the false impression that it may actually apply.

Links: [Plugin Homepage](https://coffee2code.com/wp-plugins/force-admin-color-scheme/) | [Plugin Directory Page](https://wordpress.org/plugins/force-admin-color-scheme/) | [GitHub](https://github.com/coffee2code/force-admin-color-scheme/) | [Author Homepage](https://coffee2code.com)


== Installation ==

1. Install via the built-in WordPress plugin installer. Or download and unzip `force-admin-color-scheme.zip` inside the plugins directory for your site (typically `wp-content/plugins/`)
2. Activate the plugin through the 'Plugins' admin menu in WordPress
3. As an admin, edit your own profile (Users -> Your Profile) and choose the Admin Color Scheme you want to apply to all users by setting the color scheme for yourself.
4. Check the "Force this admin color scheme on all users?" checkbox and then save the update to your profile.


== Screenshots ==

1. A screenshot of the profile page for an administrative user who has the checkbox to force an admin color scheme on users.


== Frequently Asked Questions ==

= Why isn't everyone seeing the same admin color scheme after activating this plugin? =

Have you followed all of the installation instructions? You must configure the forced admin color scheme by setting the color scheme for yourself while also checking the "Force this admin color scheme?" checkbox.

= How do I resume letting users pick their own color schemes? =

Uncheck the "Force this admin color scheme?" when updating an administrative profile, or deactivate the plugin.

= Does this plugin include unit tests? =

Yes.


== Changelog ==

= 1.2.1 (2020-01-02) =
* Change: Note compatibility through WP 5.3+
* Change: Include additional usage steps in the "Installation" section of README.md
* Change: Tweak wording of one of the use-cases for the plugin
* Change: Update copyright date (2020)
* New: Add link to CHANGELOG.md in README.md
* Fix: Use full path to CHANGELOG.md in the Changelog section of readme.txt

= 1.2 (2019-02-09) =
* Change: Initialize plugin on `plugins_loaded` action instead of on load
* New: Add CHANGELOG.md and move all but most recent changelog entries into it
* Change: Note compatibility through WP 5.1+
* Change: Add README.md link to plugin's page in Plugin Directory
* Change: Update unit test install script and bootstrap to use latest WP unit test repo
* Change: Update copyright date (2019)
* Change: Update License URI to be HTTPS
* Change: Update installation instruction to prefer built-in installer over .zip file
* Change: Split paragraph in README.md's "Support" section into two

== 1.1.1 (2017-12-22) =
* Fix: Add missing underscore to function call; `_()` should have been `__()`
* New: Add README.md
* Change: Update unit test bootstrap
    * Default `WP_TESTS_DIR` to `/tmp/wordpress-tests-lib` rather than erroring out if not defined via environment variable
    * Enable more error output for unit tests
* Change: In unit tests, fire `do_init()` manually instead of triggering 'admin_init' to avoid a PHP warning
* Fix: Fix typo in readme
* Change: Add GitHub link to readme
* Change: Note compatibility through WP 4.9+
* Change: Update copyright date (2018)
* New: Add a list of ideas for future consideration

_Full changelog is available in [CHANGELOG.md](https://github.com/coffee2code/force-admin-color-scheme/blob/master/CHANGELOG.md)._


== Upgrade Notice ==

= 1.2.1 =
Trivial update: noted compatibility through WP 5.3+, made minor documentation tweaks, and updated copyright date (2020)

= 1.2 =
Minor update: tweaked plugin initialization, noted compatibility through WP 5.1+, created CHANGELOG.md to store historical changelog outside of readme.txt, and updated copyright date (2019)

= 1.1.1 =
Trivial update: updated unit test bootstrap; noted compatibility through WP 4.9+; added README.md; added GitHub link to readme; updated copyright date (2018)

= 1.1 =
Recommended update.

= 1.0 =
Initial release.
