<?php
/**
 * PHPUnit bootstrap file
 *
 * @package Force_Admin_Color_Scheme
 */

define( 'FORCE_ADMIN_COLOR_SCHEME_PLUGIN_FILE', dirname( __FILE__, 3 ) . '/force-admin-color-scheme.php' );

ini_set( 'display_errors', 'on' );
error_reporting( E_ALL );

// Backward compatibility (PHPUnit < 6).
$phpunit_backcompat = array(
	'\PHPUnit\Framework\TestCase' => 'PHPUnit_Framework_TestCase',
);
foreach ( $phpunit_backcompat as $new => $old ) {
	if ( ! class_exists( $new ) && class_exists( $old ) ) {
		class_alias( $old, $new );
	}
}

$_tests_dir = getenv( 'WP_TESTS_DIR' );
if ( ! $_tests_dir ) {
	$_tests_dir = '/tmp/wordpress-tests-lib';
}

// Give access to tests_add_filter() function.
require_once $_tests_dir . '/tests/phpunit/includes/functions.php';

/**
 * Manually load the plugin being tested.
 */
function _manually_load_plugin() {
	require FORCE_ADMIN_COLOR_SCHEME_PLUGIN_FILE;
}
tests_add_filter( 'muplugins_loaded', '_manually_load_plugin' );

// Start up the WP testing environment.
require $_tests_dir . '/tests/phpunit/includes/bootstrap.php';
