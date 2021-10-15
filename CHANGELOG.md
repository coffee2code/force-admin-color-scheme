# Changelog

## 2.0.3 _(2021-10-14)_
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

## 2.0.2 _(2021-04-11)_
* Change: Note compatibility through WP 5.7+
* Change: Update copyright date (2021)

## 2.0.1 _(2020-09-03)_
* Change: Restructure unit test file structure
    * New: Create new subdirectory `phpunit/` to house all files related to unit testing
    * Change: Move `bin/` to `phpunit/bin/`
    * Change: Move `tests/bootstrap.php` to `phpunit/`
    * Change: Move `tests/` to `phpunit/tests/`
    * Change: Rename `phpunit.xml` to `phpunit.xml.dist` per best practices
* Change: Note compatibility through WP 5.5+
* Change: Tweak function documentation
* Change: Add FAQ entry regarding continued appearance of admin color scheme picker when the color scheme can't be changed (due to being set via filter or constant)
* New: Add a few more possible TODO items

## 2.0 _(2020-05-22)_

### Hightlights:

This feature release adds support for programmatically customizing forced admin color scheme via a filter and/or constant, adds stylish inline notices, adds validation for color schemes, expands unit test coverage, renames a number of functions, adds TODO.md, updates compatibility through WP 5.4+, and a few more minor changes.

### Details:

* New: Add filter `c2c_force_admin_color_scheme` to set or override admin color scheme
    * New: Add `get_filtered_color_scheme()` to get the filtered admin color scheme
* New: Add support for constant to set admin color scheme, `C2C_FORCE_ADMIN_COLOR_SCHEME`
    * New: Add `is_constant_set()` to determine if constant was used to set the forced admin color scheme
    * New: Add `get_color_scheme_via_constant()` to get the admin color scheme specified via the constant
    * New: Show message to users who can force an admin color scheme when the forced admin color scheme was set via the constant
* New: Verify validity of admin color scheme on save and on retrieval
    * New: Add `is_valid_color_scheme()` to check if a given admin color scheme is valid
    * Change: Modify `get_forced_color_scheme()` to not return an invalid admin color scheme
    * Change: Modify `set_forced_color_scheme()` to not save an invalid admin color scheme
* New: Add TODO.md and move existing TODO list from top of main plugin file into it (and add to it)
* Change: Output currently forced admin color scheme beneath the color scheme picker for admins via a notice similar to an admin notice
* Change: Ensure admin color scheme name is lowercased before validation, saving, and comparisons
* Change: Rename `get_forced_admin_color()` to `get_forced_color_scheme()`
* Change: Rename `set_forced_admin_color()` to `set_forced_color_scheme()`
* Change: Rename `force_admin_color()` to `force_color_scheme()`
* Change: Rename `hide_admin_color_input()` to `hide_admin_color_scheme_picker()`
* Change: Tweak attribute spacing for `label` tag
* Change: Use HTTPS for link to WP SVN repository in bin script for configuring unit tests
* Change: Note compatibility through WP 5.4+
* Change: Update links to coffee2code.com to be HTTPS
* Unit tests:
    * New: Add tests for `add_checkbox()`, `force_admin_color()`, `hide_admin_color_scheme_picker()`, `output_css()`, `register_css()`, `save_setting()`, `set_forced_color_scheme()`
    * Change: Remove commented out code
* New: Add screenshots for messages indicating use of constant or filter

## 1.2.1 _(2020-01-02)_
* Change: Note compatibility through WP 5.3+
* Change: Include additional usage steps in the "Installation" section of README.md
* Change: Tweak wording of one of the use-cases for the plugin
* Change: Update copyright date (2020)
* New: Add link to CHANGELOG.md in README.md
* Fix: Use full path to CHANGELOG.md in the Changelog section of readme.txt

## 1.2 _(2019-02-09)_
* Change: Initialize plugin on `plugins_loaded` action instead of on load
* New: Add CHANGELOG.md and move all but most recent changelog entries into it
* Change: Note compatibility through WP 5.1+
* Change: Add README.md link to plugin's page in Plugin Directory
* Change: Update unit test install script and bootstrap to use latest WP unit test repo
* Change: Update copyright date (2019)
* Change: Update License URI to be HTTPS
* Change: Update installation instruction to prefer built-in installer over .zip file
* Change: Split paragraph in README.md's "Support" section into two

## 1.1.1. _(2017-12-22)_
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

## 1.1 _(2016-03-09)_
* New: Add `get_setting_name()` as a getter for plugin's setting name and use it everywhere internally instead of referencing private class variable.
* New: Add `set_forced_admin_color()` as a setter for forced admin color. Deletes setting if value is falsey.
* New: Delete plugin setting on uninstall.
* New: Add unit tests.
* Change: Reimplement how the color picker is hidden from non-administrative users.
    * Rewrite `hide_admin_color_input()`.
    * Remove `restore_wp_admin_css_colors()`.
    * Remove private static variable `$_wp_admin_css_colors`.
* Change: When the checkbox is submitted unchecked, delete the forced admin color value.
* Change: When a forced admin color is set, have the checkbox checked.
* Change: Hook 'admin_init' rather than 'init' for initialization.
* Change: Escape use of setting name in markup attributes as an extra precaution.
* Change: Allow class to be defined even when loaded outside the admin.
* Change: Add left padding to input label so the input aligns with color picker colors.
* Change: Remove extra help text associated with checkbox as it was no longer necessary.
* Change: Add support for language packs:
    * Change textdomain from 'c2c-facs' to 'force-admin-color-scheme'.
    * Don't load plugin translations from file.
    * Remove 'Domain Path' from plugin header.
* Change: Add inline docs for class variable.
* Change: Minor code and inline documentation reformatting (spacing).
* New: Create empty index.php to prevent files from being listed if web server has enabled directory listings.
* Change: Drop support for versions of WP older than 4.1.
* Change: Note compatibility through WP 4.4+.
* Change: Update copyright date (2016).

## 1.0 _(2014-09-26)_
* Initial release
