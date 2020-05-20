# Force Admin Color Scheme

A plugin for WordPress that forces a single admin color scheme for all users of the site.

This plugin is available in the WordPress Plugin Directory: https://wordpress.org/plugins/force-admin-color-scheme/


## Installation

1. Install via the built-in WordPress plugin installer. Or install the plugin code inside the plugins directory for your site (typically `/wp-content/plugins/`).
2. Activate the plugin through the 'Plugins' admin menu in WordPress
3. As an admin, edit your own profile (Users -> Your Profile) and choose the Admin Color Scheme you want to apply to all users by setting the color scheme for yourself.
4. Check the "Force this admin color scheme on all users?" checkbox and then save the update to your profile.
5. Optional: Use the `c2c_force_admin_color_scheme` filter in custom code to programmatically set the forced admin color scheme with greater control.
6. Optional: Define the `C2C_FORCE_ADMIN_COLOR_SCHEME` constant somewhere (such as `wp-config.php`) if you'd prefer to configure the color that way. Configuring the color in this manner takes precedence over the color as configured via an admin's profile. Also, if the constant is used, the plugin prevents the setting of admin color schemes entirely from within user profiles, including by admins.


## Additional Documentation

See [readme.txt](https://github.com/coffee2code/force-admin-color-scheme/blob/master/readme.txt) for additional usage information. See [CHANGELOG.md](CHANGELOG.md) for the list of changes for each release.


## Support

Commercial support and custom development are not presently available. You can raise an [issue](https://github.com/coffee2code/force-admin-color-scheme/issues) on GitHub or post in the [plugin's support forum on WordPress.org](https://wordpress.org/support/plugin/force-admin-color-scheme/).

If the plugin has been of benefit to you, how about [submitting a review](https://wordpress.org/support/plugin/force-admin-color-scheme/reviews/) for it in the WordPress Plugin Directory or considerating a [donation](https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=6ARCFJ9TX3522)?


## License

This plugin is free software; you can redistribute it and/or modify it under the terms of the [GNU General Public License](https://www.gnu.org/licenses/gpl-2.0.html) as published by the Free Software Foundation; either version 2 of the License, or (at your option) any later version.