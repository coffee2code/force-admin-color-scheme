<?php
/**
 * Plugin Name: Force Admin Color Scheme
 * Version:     1.0
 * Plugin URI:  http://coffee2code.com/wp-plugins/force-admin-color-scheme/
 * Author:      Scott Reilly
 * Author URI:  http://coffee2code.com/
 * Text Domain: force-admin-color-scheme
 * Domain Path: /lang/
 * License:     GPLv2 or later
 * License URI: http://www.gnu.org/licenses/gpl-2.0.html
 * Description: Force a single admin color scheme for all users of the site.
 *
 * Compatible with WordPress 3.8 through 4.0+.
 *
 * =>> Read the accompanying readme.txt file for instructions and documentation.
 * =>> Also, visit the plugin's homepage for additional information and updates.
 * =>> Or visit: https://wordpress.org/plugins/force-admin-color-scheme/
 *
 * @package Force_Admin_Color_Scheme
 * @author  Scott Reilly
 * @version 1.0
 */

/*
 * Note: there are numerous ways to implement the backend functionality for
 * this plugin. I chose the most simple to use and implement. Arguably, a
 * dedicated settings page might be clearer in conforming to user expectations.
 * It may come to that; we'll see.
*/

/*
	Copyright (c) 2014 by Scott Reilly (aka coffee2code)

	This program is free software; you can redistribute it and/or
	modify it under the terms of the GNU General Public License
	as published by the Free Software Foundation; either version 2
	of the License, or (at your option) any later version.

	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.

	You should have received a copy of the GNU General Public License
	along with this program; if not, write to the Free Software
	Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
*/

defined( 'ABSPATH' ) or die();

if ( is_admin() && ! class_exists( 'c2c_ForceAdminColorScheme' ) ) :

class c2c_ForceAdminColorScheme {

	private static $setting = 'c2c_forced_admin_color';

	private static $_wp_admin_css_colors;

	/**
	 * Returns version of the plugin.
	 *
	 * @since 1.0
	 */
	public static function version() {
		return '1.0';
	}

	/**
	 * Hooks actions and filters.
	 *
	 * @since 1.0
	 */
	public static function init() {
		add_action( 'init', array( __CLASS__, 'do_init' ) );
	}

	/**
	 * Performs initializations on the 'init' action.
	 *
	 * @since 1.0
	 */
	public static function do_init() {
		/*
		 * Load textdomain
		 */
		load_plugin_textdomain( 'force-admin-color-scheme', false, basename( dirname( __FILE__ ) ) . DIRECTORY_SEPARATOR . 'lang' );

		/*
		 * Register hooks
		 */

		// Override the user's admin color scheme
		add_filter( 'get_user_option_admin_color', array( __CLASS__, 'force_admin_color'      ) );

		// Add checked for setting the forced admin color scheme
		add_action( 'admin_color_scheme_picker',   array( __CLASS__, 'add_checkbox'           ), 20 );

		// Save the checkbox value for forcing admin color scheme
		add_action( 'personal_options_update',     array( __CLASS__, 'save_setting'           ) );

		// Hide the Admin Color Scheme field from users who can't set a forced color scheme
		add_action( 'load-profile.php',            array( __CLASS__, 'hide_admin_color_input' ) );

		// Restore global $_wp_admin_css_colors
		add_action( 'personal_options',            array( __CLASS__, 'restore_wp_admin_css_colors' ) );

		/* Note: not bothering with preventing users from being able to save a value for admin_color
		   since it's not really a big deal if they do. */
	}

	/**
	 * Returns the forced admin color scheme.
	 *
	 * @since 1.0
	 *
	 * @return string
	 */
	public static function get_forced_admin_color() {
		return get_option( self::$setting );
	}

	/**
	 * Overrides the user's admin color scheme with the forced admin color
	 * scheme, if set.
	 *
	 * @since 1.0
	 *
	 * @param  string $admin_color_scheme The admin color scheme.
	 * @return string
	 */
	public static function force_admin_color( $admin_color_scheme ) {
		// If a forced admin color has been configured, use it.
		if ( $forced = self::get_forced_admin_color() ) {
			$admin_color_scheme = $forced;
		}

		return $admin_color_scheme;
	}

	/**
	 * Outputs the checkbox for forcing the admin color scheme.
	 *
	 * @since 1.0
	 */
	public static function add_checkbox() {
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		$forced_admin_color = self::get_forced_admin_color();

		printf(
			'<label for="%s"><input name="%s" type="checkbox" id="%s" value="true" /> %s %s</label>',
			self::$setting,
			self::$setting,
			self::$setting,
			__( 'Force this admin color scheme?', 'c2c-facs' ),
			(
				( $c = self::get_forced_admin_color() ) ?
					' <em>' . sprintf( _( '(Currently forced admin color: %s)', 'c2c-facs' ), '<strong>' . ucfirst( $c ) . '</strong>' ) . '</em>' :
					''
			)
		);

		echo '<span class="description" style="display: block; margin-left: 24px;">';
		_e( 'If checked when you update your profile, the chosen admin color scheme will apply to all users.', 'c2c-facs' );
		echo '</span>';
	}

	/**
	 * Saves the admin user's admin color scheme as the forced admin color
	 * scheme if the checkbox is checked.
	 *
	 * @since 1.0
	 *
	 * @param  $user_id The user ID.
	 */
	public static function save_setting( $user_id ) {
		if ( current_user_can( 'manage_options' ) && isset( $_POST[ self::$setting ] ) && $_POST[ self::$setting ] ) {
			update_option( self::$setting, $_POST['admin_color'] );
		}
	}

	/**
	 * Hides the Admin Color Scheme input and label when appropriate.
	 *
	 * The input is hidden for users who do not have the capability to set the
	 * forced admin color scheme *and* when an adnmin color scheme hasn't been
	 * set yet.
	 *
	 * This works by HACKILY unsetting the global $_wp_admin_css_colors array.
	 * This ensures the field is never output; otherwise CSS and/or JS would
	 * need to be used to hide the field after the fact.
	 */
	public static function hide_admin_color_input() {
		if ( ! current_user_can( 'manage_options' ) && ! self::get_forced_admin_color() ) {
			self::$_wp_admin_css_colors = $GLOBALS[ '_wp_admin_css_colors' ];
			$GLOBALS[ '_wp_admin_css_colors' ] = array();
		}
	}

	/**
	 * Restores the global _wp_admin_css_colors value.
	 *
	 * Since $_wp_admin_css_colors is blanked to prevent the admin color scheme
	 * input row from appearing, restore its value as early as possible so as
	 * to play well with others.
	 *
	 * @since 1.0
	 *
	 * @param WP_User $profileuser The current WP_User object.
	 */
	public static function restore_wp_admin_css_colors( $profileuser ) {
		if ( self::$_wp_admin_css_colors ) {
			$GLOBALS['_wp_admin_css_colors'] = self::$_wp_admin_css_colors;
		}
	}

} // end c2c_ForceAdminColorScheme

c2c_ForceAdminColorScheme::init();

endif; // end if !class_exists()
