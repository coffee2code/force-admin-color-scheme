# TODO

The following list comprises ideas, suggestions, and known issues, all of which are in consideration for possible implementation in future releases.

***This is not a roadmap or a task list.*** Just because something is listed does not necessarily mean it will ever actually get implemented. Some might be bad ideas. Some might be impractical. Some might either not benefit enough users to justify the effort or might negatively impact too many existing users. Or I may not have the time to devote to the task.

* Record and report (to other admins) the name (and possibly datetime) of the user who forced the admin color scheme.
  * Reporting could include via admin notice on next per-admin login
  * Could also be shown on settings field alongside the notice about the currently forced admin color scheme
* Add custom admin notice after saving a new forced admin color scheme
  * In order to specifically call out the fact that the forced admin color scheme was changed or cleared
  * Upon activation, notify admin if a forced color scheme has previously been set and is now being used
* Add custom capability for being able to restrict users who can set a forced admin color scheme.
* Disable rather than hide admin color picker for non-admins?
* Add support for colors based on environment type
  * Maybe as suffixed versions of the main constant, e.g. C2C_FORCE_ADMIN_COLOR_SCHEME_STAGING
  * The main constant becomes the fallback if environment-specific constant isn't set
  * Consider supporting a value of `false` to indicate no color scheme override should be set.
    * e.g. C2C_FORCE_ADMIN_COLOR_SCHEME_STAGING being set to false would essentially disable the plugin for staging
    * (Not sold on the practicality of this; could be used to say PRODUCTION should never be forced, but force it elsewhere according to plugin's usual behavior)
* ...which implies a different (and arguably better) UI that doesn't overload existing color scheme picker. Perhaps:
  * Add a dropdown for setting admin color scheme (takes precedence)
  * Add a dropdown for each environment type (only if a global color scheme isn't configured)
  * Default should be "User selected", and for environment type "Forced color" (w/o a "User selected" option)
  * Perhaps hide environment type forced color fields initially, requiring clicking of "Force admin
  * Add filter and constant for forced environment type colors
* Add support for a constant for a color backup to use if an invalid color scheme was configured.
  * Ideally this would be one of the core color schemes, but that's not necessary and doesn't need to be enforced.
* Add support for constants to define an array of colors
  * Subsequent colors are treated in turn as fallback in case prior color scheme(s) are invalid
* Add ability to restrict admin color schemes a user can choose from to a subset.
  * While a default can be forced on them, allow a subset of admin scheme colors to be available for them to choose and actually have honored.

Feel free to make your own suggestions or champion for something already on the list (via the [plugin's support forum on WordPress.org](https://wordpress.org/support/plugin/force-admin-color-scheme/) or on [GitHub](https://github.com/coffee2code/force-admin-color-scheme/) as an issue or PR).
