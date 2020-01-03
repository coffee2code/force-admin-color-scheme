# Changelog

## _(in-progress)_
* Change: Note compatibility through WP 5.3+
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
