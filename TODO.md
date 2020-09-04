# TODO

The following list comprises ideas, suggestions, and known issues, all of which are in consideration for possible implementation in future releases.

***This is not a roadmap or a task list.*** Just because something is listed does not necessarily mean it will ever actually get implemented. Some might be bad ideas. Some might be impractical. Some might either not benefit enough users to justify the effort or might negatively impact too many existing users. Or I may not have the time to devote to the task.

* Record and report (to other admins) the name (and possibly datetime) of the user who forced the admin color scheme.
* Add custom capability for being able to restrict users who can set a forced admin color scheme.
* Disable rather than hide admin color picker for non-admins?
* Add support for colors based on environment type
* ...which implies a different (and arguably better) UI that doesn't overload existing color scheme picker. Perhaps:
  * Add a dropdown for setting admin color scheme (takes precedence)
  * Add a dropdown for each environment type (only if a global color scheme isn't configured)
  * Default should be "User selected", and for environment type "Forced color" (w/o a "User selected" option)
  * Perhaps hide environment type forced color fields initially, requiring clicking of "Force admin
  * Add filter and constant for forced environment type colors

Feel free to make your own suggestions or champion for something already on the list (via the [plugin's support forum on WordPress.org](https://wordpress.org/support/plugin/force-admin-color-scheme/) or on [GitHub](https://github.com/coffee2code/force-admin-color-scheme/) as an issue or PR).
