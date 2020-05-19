<?php

defined( 'ABSPATH' ) or die();

class test_ForceAdminColorScheme extends WP_UnitTestCase {

	public static function setUpBeforeClass() {
		// Make all requests as if in the admin, which is the only place the plugin
		// affects.
		define( 'WP_ADMIN', true );

		// Re-initialize plugin now that WP_ADMIN is true.
		c2c_ForceAdminColorScheme::init();

		// Re-fire init handler as admin_init action would've done.
		c2c_ForceAdminColorScheme::do_init();
	}

	public function tearDown() {
		parent::tearDown();
		$this->unset_current_user();
	}


	//
	//
	// HELPER FUNCTIONS
	//
	//


	private function create_user( $role, $set_as_current = true ) {
		$user_id = $this->factory->user->create( array( 'role' => $role ) );
		if ( $set_as_current ) {
			wp_set_current_user( $user_id );
		}
		return $user_id;
	}

	// helper function, unsets current user globally. Taken from post.php test.
	private function unset_current_user() {
		global $current_user, $user_ID;

		$current_user = $user_ID = null;
	}


	//
	//
	// FUNCTIONS FOR HOOKING ACTIONS/FILTERS
	//
	//




	//
	//
	// TESTS
	//
	//


	public function test_class_exists() {
		$this->assertTrue( class_exists( 'c2c_ForceAdminColorScheme' ) );
	}

	public function test_version() {
		$this->assertEquals( '1.2.1', c2c_ForceAdminColorScheme::version() );
	}

	public function test_setting_name_does_not_change() {
		$this->assertEquals( 'c2c_forced_admin_color', c2c_ForceAdminColorScheme::get_setting_name() );
	}

	public function test_hooks_plugins_loaded() {
		$this->assertEquals( 10, has_action( 'plugins_loaded', array( 'c2c_ForceAdminColorScheme', 'init' ) ) );
	}

	public function test_hooks_admin_init() {
		$this->assertEquals( 10, has_filter( 'admin_init', array( 'c2c_ForceAdminColorScheme', 'do_init' ) ) );
	}

	public function test_registers_hooks() {
		$this->assertEquals( 10, has_filter( 'get_user_option_admin_color', array( 'c2c_ForceAdminColorScheme', 'force_admin_color'           ) ) );
		$this->assertEquals( 20, has_action( 'admin_color_scheme_picker',   array( 'c2c_ForceAdminColorScheme', 'add_checkbox'                ) ) );
		$this->assertEquals( 10, has_action( 'personal_options_update',     array( 'c2c_ForceAdminColorScheme', 'save_setting'                ) ) );
		$this->assertEquals( 8,  has_action( 'admin_color_scheme_picker',   array( 'c2c_ForceAdminColorScheme', 'hide_admin_color_input'      ), 8 ) );
		$this->assertEquals( 10, has_action( 'load-profile.php',            array( 'c2c_ForceAdminColorScheme', 'register_css'                ) ) );
	}

	public function test_no_default_forced_admin_color() {
		$this->assertFalse( c2c_ForceAdminColorScheme::get_forced_admin_color() );
	}

	public function test_get_forced_admin_color() {
		c2c_ForceAdminColorScheme::set_forced_admin_color( 'ocean' );

		$this->assertEquals( 'ocean', c2c_ForceAdminColorScheme::get_forced_admin_color() );
	}

	public function test_set_forced_admin_color_sets_setting_value() {
		c2c_ForceAdminColorScheme::set_forced_admin_color( 'ocean' );

		$this->assertEquals( 'ocean', get_option( c2c_ForceAdminColorScheme::get_setting_name() ) );
	}

	public function test_user_color_scheme_is_the_forced_color_scheme() {
		$this->create_user( 'editor' );

		$this->assertEquals( 'fresh', get_user_option( 'admin_color' ) );

		c2c_ForceAdminColorScheme::set_forced_admin_color( 'ocean' );

		$this->assertEquals( 'ocean', get_user_option( 'admin_color' ) );
	}

	public function test_setting_forced_color_scheme_to_empty_string_deletes_option() {
		$this->test_user_color_scheme_is_the_forced_color_scheme();

		c2c_ForceAdminColorScheme::set_forced_admin_color( '' );

		$this->assertEquals( 'fresh', get_user_option( 'admin_color' ) );
		$this->assertFalse( get_option( c2c_ForceAdminColorScheme::get_setting_name() ) );
	}

	public function test_uninstall_deletes_option() {
		$option = c2c_ForceAdminColorScheme::get_setting_name();

		c2c_ForceAdminColorScheme::set_forced_admin_color( 'ocean' );

		$this->assertEquals( 'ocean', get_option( $option ) );

		c2c_ForceAdminColorScheme::uninstall();

		$this->assertFalse( get_option( $option ) );
	}

	/*
	 * add_checkbox()
	 */

	public function test_add_checkbox_outputs_nothing_for_user_without_cap() {
		$this->create_user( 'editor' );

		$this->expectOutputRegex( '/^$/', c2c_ForceAdminColorScheme::add_checkbox() );
	}

	public function test_add_checkbox_outputs_for_user_with_cap_when_forced_color_not_set() {
		$this->create_user( 'administrator' );

		$expected = '<label for="c2c_forced_admin_color"><input name="c2c_forced_admin_color" type="checkbox" id="c2c_forced_admin_color" value="true" /> Force this admin color scheme on all users? </label>';

		$this->expectOutputRegex( '~^' . preg_quote( $expected ) . '$~', c2c_ForceAdminColorScheme::add_checkbox() );
	}

	public function test_add_checkbox_outputs_for_user_with_cap_when_forced_color_set() {
		$this->create_user( 'administrator' );

		c2c_ForceAdminColorScheme::set_forced_admin_color( 'ocean' );
		$expected = '<label for="c2c_forced_admin_color"><input name="c2c_forced_admin_color" type="checkbox" id="c2c_forced_admin_color" value="true" checked=\'checked\' /> '
			. 'Force this admin color scheme on all users? '
			. '<em>Currently forced admin color: <strong>Ocean</strong></em>'
			. '</label>';

		$this->expectOutputRegex( '~^' . preg_quote( $expected ) . '$~', c2c_ForceAdminColorScheme::add_checkbox() );
	}

	/*
	 * save_setting()
	 */

	public function test_save_setting_does_not_save_for_user_without_cap() {
		$user_id = $this->create_user( 'editor' );
		$_POST = array(
			'admin_color'            => 'sunrise',
			'c2c_forced_admin_color' => '1',
		);

		c2c_ForceAdminColorScheme::save_setting( $user_id );

		$this->assertEquals( 'fresh', get_user_option( 'admin_color' ) );
		$this->assertEmpty( c2c_ForceAdminColorScheme::get_forced_admin_color() );

	}

	public function test_save_setting_does_save_for_user_with_cap() {
		$user_id = $this->create_user( 'administrator' );
		$_POST = array(
			'admin_color'            => 'sunrise',
			'c2c_forced_admin_color' => '1',
		);

		c2c_ForceAdminColorScheme::save_setting( $user_id );

		$this->assertEquals( 'sunrise', get_user_option( 'admin_color' ) );
		$this->assertEquals( 'sunrise', c2c_ForceAdminColorScheme::get_forced_admin_color() );

	}

	public function test_save_setting_unsets_setting_for_user_with_cap_when_unchecked() {
		$user_id = $this->create_user( 'administrator' );
		$_POST = array(
			'admin_color'            => 'ocean',
			'c2c_forced_admin_color' => '0',
		);

		c2c_ForceAdminColorScheme::set_forced_admin_color( 'coffee' );
		c2c_ForceAdminColorScheme::save_setting( $user_id );

		// The test hasn't actually saved a new color to the user's option
		// directly, and since the forced color was unset, then the user's
		// original color should be returned.
		$this->assertEquals( 'fresh', get_user_option( 'admin_color' ) );
		$this->assertFalse( c2c_ForceAdminColorScheme::get_forced_admin_color() );

	}

	/*
	 * constant: C2C_FORCE_ADMIN_COLOR_SCHEME
	 *
	 * Note: Due to the nature of constants, once defined they will thereafter
	 * be set, so this should be one of the final tests.
	 */

	public function test_constant_supercedes_setting() {
		$this->create_user( 'editor' );

		$this->assertEquals( 'fresh', get_user_option( 'admin_color' ) );

		c2c_ForceAdminColorScheme::set_forced_admin_color( 'ocean' );
		define( 'C2C_FORCE_ADMIN_COLOR_SCHEME', 'coffee' );

		$this->assertEquals( 'coffee', c2c_ForceAdminColorScheme::get_forced_admin_color() );
		$this->assertEquals( 'coffee', get_user_option( 'admin_color' ) );
	}

}
