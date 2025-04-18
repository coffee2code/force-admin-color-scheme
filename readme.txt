=== Force Admin Color Scheme ===
Contributors: coffee2code
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=6ARCFJ9TX3522
Tags: admin colors, color scheme, admin, staging, coffee2code
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Requires at least: 4.1
Tested up to: 6.8
Stable tag: 2.1

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

1. Install via the built-in WordPress plugin installer. Or install the plugin code inside the plugins directory for your site (typically `/wp-content/plugins/`).
2. Activate the plugin through the 'Plugins' admin menu in WordPress
3. As an admin, edit your own profile (Users -> Your Profile) and choose the Admin Color Scheme you want to apply to all users by setting the color scheme for yourself.
4. Check the "Force this admin color scheme on all users?" checkbox and then save the update to your profile.
5. Optional: Use the `c2c_force_admin_color_scheme` filter in custom code to programmatically set the forced admin color scheme with greater control.
6. Optional: Define the `C2C_FORCE_ADMIN_COLOR_SCHEME` constant somewhere (such as `wp-config.php`) if you'd prefer to configure the color that way. Configuring the color in this manner takes precedence over the color as configured via an admin's profile. Also, if the constant is used, the plugin prevents the setting of admin color schemes entirely from within user profiles, including by admins.


== Screenshots ==

1. The profile page for an administrative user who has the checkbox to force an admin color scheme on users, though one hasn't been forced yet.
2. The profile page for an administrative user who has the checkbox to force an admin color scheme on users, with a color scheme having been forced.
3. The profile page for an administrative user when the forced admin color scheme is configured via the filter.
4. The profile page for an administrative user when the forced admin color scheme is configured via the constant.
5. The profile page for an administrative user when the forced admin color scheme is incorrectly configured via the constant. A similar warning also appears if the color scheme is incorrectly configured via the filter as well.


== Frequently Asked Questions ==

= Why isn't everyone seeing the same admin color scheme after activating this plugin? =

Have you followed all of the installation instructions? You must configure the forced admin color scheme by setting the color scheme for yourself while also checking the "Force this admin color scheme?" checkbox.

= How do I resume letting users pick their own color schemes? =

You can simply deactivate the plugin. Though bear in mind that if you later reactivate the plugin, the forced color scheme at the time the plugin was deactivated (if there was one) may be reinstated (depending on if the constant, hooked filter, and/or plugin setting are still present).

To keep the plugin active, you can pursue the following steps (whichever may apply to you):
* If the forced color scheme is being set via the constant or code making use of the filter hook, then the code relating to either of those cases must be commented out or disabled.
* If the "Force this admin color scheme?" setting was used, then update an administrative profile to uncheck the checkbox and save.

= Can I force different admin color schemes based on the user? =

Yes, but only via custom coding by making use of the `c2c_force_admin_color_scheme` filter. See the documentation for the filter for an example.

= What happens if a custom admin color scheme was forced, but later the custom admin color scheme is no longer available (e.g. I deactivated the plugin providing the custom admin color scheme)? =

The plugin will recognize that the chosen admin color scheme is no longer valid and will act as if one isn't set. In such a case, users would then see their individually chosen admin color schemes. If the custom admin color scheme becomes available again (before a new existing color scheme is selected as the new scheme to be forced), then the plugin will reinstate it as the forced admin color scheme.

= Why is the admin color scheme picker still functional, or even still present at all, when the color scheme is set via the filter or constant and thus cannot be changed via the picker? =

Just to be clear, if an admin color scheme is being forced, then non-admininistrative users won't see the admin color scheme picker at all.

The plugin does not disable the admin color scheme picker for administrative users even if a value is directly configured in code (via the constant or the filter) so that the admin color schemes can still be seen and previewed in case an admin user wants to evaluate alternatives.

= Does this plugin have unit tests? =

Yes. The tests are not packaged in the release .zip file or included in plugins.svn.wordpress.org, but can be found in the [plugin's GitHub repository](https://github.com/coffee2code/force-admin-color-scheme/).


== Hooks ==

The plugin is further customizable via one filter. Typically, code making use of filters should ideally be put into a mu-plugin or site-specific plugin (which is beyond the scope of this readme to explain).

**c2c_force_admin_color_scheme (filter)**

The 'c2c_force_admin_color_scheme' filter allows you to set or override the forced admin color scheme. Use of the constant (``) takes priority over the filtered value, but the filtered value takes priority over the value set via the admin.

Arguments:

* $color (string): The name of the admin color scheme. If an empty string is returned, then the plugin will behave as if no forced admin color scheme has been defined.

Example:

`
/**
 * Sets a forced admin color scheme based on user. Admins get one color scheme, whereas everyone else gets another.
 *
 * @param string $color The current forced admin color scheme. Empty string indicates no forced admin color scheme.
 * @return string
 */
function my_c2c_force_admin_color_scheme( $color ) {
    return current_user_can( 'manage_options' ) ? 'sunrise' : 'coffee';
}
add_filter( 'c2c_force_admin_color_scheme', 'my_c2c_force_admin_color_scheme' );
`


== Changelog ==

= 2.1 (2025-02-15) =
Highlights:

This minor release fixes a bug that prevented a forced color scheme from being set or unset if one was being forced via the constant, adds clarifying help text, prevents markup from containing unintended markup, removes unit tests from release packaging, updates compatibility through WP 6.8+, and a few more minor changes.

Details:

* Fix: Allow saving a forced admin color scheme even if constant is set (the constant will still take precedence unless invalid)
* New: Add help text under the checkbox, if checked, to clarify that unchecking it will unset the forced color scheme
* New: Add `get_color_scheme_via_setting()` to get the forced admin color scheme saved as a plugin setting
* Change: Prevent translations from containing unintended markup
* Hardening: Sanitize submitted color scheme name
* Change: Prevent unwarranted PHPCS complaint
* Change: Add FAQ entry regarding allowing users to choose admin colors schemes again
* Change: Change word used in output string
* Change: Add missing inline comment for translators
* Change: Note compatibility through WP 6.8+
* Change: Note compatibility through PHP 8.3+
* Change: Update copyright date (2025)
* New: Add `.gitignore` file
* Change: Remove development and testing-related files from release packaging
* Change: Tweak formatting in `README.md`
* Change: Fix some typos in inline documentation
* Unit tests:
    * Hardening: Prevent direct web access to `bootstrap.php`
    * Allow tests to run against current versions of WordPress
    * New: Add `composer.json` for PHPUnit Polyfill dependency
    * Change: Explicitly define return type for overridden methods
    * Change: In bootstrap, store path to plugin directory in a constant
    * Change: Prevent PHP warnings due to missing core-related generated files
* Change: Add more potential TODO items and reformat some existing entries

= 2.0.3 (2021-10-14) =
* Change: Use 'translators' instead of 'translator' as prefix for translator comments
* Change: Note compatibility through WP 5.8+
* Change: Tweak installation instruction
* Unit tests:
    * Change: Restructure unit test directories
        * Change: Move `phpunit/` into `tests/`
        * Change: Move `phpunit/bin` into `tests/`
    * Change: Remove 'test-' prefix from unit test file
    * Change: In bootstrap, store path to plugin file constant
    * Change: In bootstrap, add backcompat for PHPUnit pre-v6.0

= 2.0.2 (2021-04-11) =
* Change: Note compatibility through WP 5.7+
* Change: Update copyright date (2021)

_Full changelog is available in [CHANGELOG.md](https://github.com/coffee2code/force-admin-color-scheme/blob/master/CHANGELOG.md)._


== Upgrade Notice ==

= 2.1 =
Minor release: fixed bug where use of constant prevented admin UI color changes from being saved, added clarifying help text, prevented translations from containing unintended markup, noted compatibility through WP 6.8+, removed unit tests from release packaging, and updated copyright date (2025)

= 2.0.3 =
Trivial update: noted compatibility through WP 5.8+ and minor reorganization and tweaks to unit tests

= 2.0.2 =
Trivial update: noted compatibility through WP 5.7+ and updated copyright date (2021)

= 2.0.1 =
Trivial update: Restructured unit test file structure, tweaked documentation, and noted compatibility through WP 5.5+.

= 2.0 =
Feature update: added support for filter and constant, added stylish inline notices, added validation for color schemes, expanded unit test coverage, renamed a number of functions, added TODO.md file, updated a few URLs to be HTTPS, noted compatibility through WP 5.4+, and more.

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
