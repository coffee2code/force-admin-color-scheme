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

		// Fool WP into not realizing it is running a -src version so it
		// registers all default admin color schemes.
		$GLOBALS['wp_version'] = str_replace( '-src', '', $GLOBALS['wp_version'] );
		register_admin_color_schemes();
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
		$this->assertEquals( '2.0.3', c2c_ForceAdminColorScheme::version() );
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
		$this->assertEquals( 10, has_filter( 'get_user_option_admin_color', array( 'c2c_ForceAdminColorScheme', 'force_color_scheme'          ) ) );
		$this->assertEquals( 20, has_action( 'admin_color_scheme_picker',   array( 'c2c_ForceAdminColorScheme', 'add_checkbox'                ) ) );
		$this->assertEquals( 10, has_action( 'personal_options_update',     array( 'c2c_ForceAdminColorScheme', 'save_setting'                ) ) );
		$this->assertEquals( 8,  has_action( 'admin_color_scheme_picker',   array( 'c2c_ForceAdminColorScheme', 'hide_admin_color_scheme_picker' ), 8 ) );
		$this->assertEquals( 10, has_action( 'load-profile.php',            array( 'c2c_ForceAdminColorScheme', 'register_css'                ) ) );
	}

	/*
	 * is_constant_set()
	 */

	public function test_is_constant_set_when_constant_is_not_set() {
		$this->assertFalse( c2c_ForceAdminColorScheme::is_constant_set() );
	}

	/*
	 * get_forced_color_scheme()
	 */

	public function test_no_default_forced_admin_color() {
		$this->assertEmpty( c2c_ForceAdminColorScheme::get_forced_color_scheme() );
	}

	public function test_get_forced_color_scheme() {
		update_option( c2c_ForceAdminColorScheme::get_setting_name(), 'ocean' );

		$this->assertEquals( 'ocean', c2c_ForceAdminColorScheme::get_forced_color_scheme() );
	}

	public function test_get_forced_color_scheme_when_an_invalid_color_scheme_is_set() {
		update_option( c2c_ForceAdminColorScheme::get_setting_name(), 'bogus' );

		$this->assertEmpty( c2c_ForceAdminColorScheme::get_forced_color_scheme() );
	}

	/*
	 * filter: c2c_force_admin_color_scheme
	 */

	public function test_filter_c2c_force_admin_color_scheme() {
		add_filter( 'c2c_force_admin_color_scheme', function ( $color ) { return 'midnight'; } );
		c2c_ForceAdminColorScheme::set_forced_color_scheme( 'ocean' );

		$this->assertEquals( 'midnight', c2c_ForceAdminColorScheme::get_forced_color_scheme() );
	}

	public function test_filter_c2c_force_admin_color_scheme_that_returns_mixed_case() {
		add_filter( 'c2c_force_admin_color_scheme', function ( $color ) { return 'Midnight'; } );
		c2c_ForceAdminColorScheme::set_forced_color_scheme( 'ocean' );

		$this->assertEquals( 'midnight', c2c_ForceAdminColorScheme::get_forced_color_scheme() );
	}

	public function test_filter_c2c_force_admin_color_scheme_that_returns_empty_string_and_no_forced_color_scheme() {
		$this->create_user( 'editor' );

		add_filter( 'c2c_force_admin_color_scheme', '__return_empty_string' );

		$this->assertEmpty( c2c_ForceAdminColorScheme::get_forced_color_scheme() );
		$this->assertEquals( 'fresh', get_user_option( 'admin_color' ) );
	}

	public function test_filter_c2c_force_admin_color_scheme_that_returns_empty_string_and_forced_color_scheme() {
		$this->create_user( 'editor' );

		add_filter( 'c2c_force_admin_color_scheme', '__return_empty_string' );
		c2c_ForceAdminColorScheme::set_forced_color_scheme( 'ocean' );

		$this->assertEquals( 'ocean', c2c_ForceAdminColorScheme::get_forced_color_scheme() );
		$this->assertEquals( 'ocean', get_user_option( 'admin_color' ) );
	}

	public function test_filter_c2c_force_admin_color_scheme_that_returns_invalid_color_scheme_and_no_forced_color_scheme() {
		$this->create_user( 'editor' );

		add_filter( 'c2c_force_admin_color_scheme', function ( $color ) { return 'bogus'; } );

		$this->assertEmpty( c2c_ForceAdminColorScheme::get_forced_color_scheme() );
		$this->assertEquals( 'fresh', get_user_option( 'admin_color' ) );
	}

	public function test_filter_c2c_force_admin_color_scheme_that_returns_invalid_color_scheme_and_forced_color_scheme() {
		$this->create_user( 'editor' );

		add_filter( 'c2c_force_admin_color_scheme', function ( $color ) { return 'bogus'; } );
		c2c_ForceAdminColorScheme::set_forced_color_scheme( 'ocean' );

		$this->assertEquals( 'ocean', c2c_ForceAdminColorScheme::get_forced_color_scheme() );
		$this->assertEquals( 'ocean', get_user_option( 'admin_color' ) );
	}

	/*
	 * set_forced_color_scheme()
	 */

	public function test_set_forced_color_scheme_saves_color_to_option() {
		$color = 'coffee';
		$this->create_user( 'editor' );

		$this->assertEquals( 'fresh', get_user_option( 'admin_color' ) );

		$this->assertEquals( $color, c2c_ForceAdminColorScheme::set_forced_color_scheme( $color ) );
		$this->assertEquals( $color, get_option( c2c_ForceAdminColorScheme::get_setting_name() ) );
	}

	public function test_set_forced_color_scheme_unsets_option_if_blank_is_sent() {
		$this->create_user( 'editor' );

		$this->assertEquals( 'fresh', get_user_option( 'admin_color' ) );

		$this->assertEmpty( c2c_ForceAdminColorScheme::set_forced_color_scheme( '' ) );
		$this->assertEmpty( get_option( c2c_ForceAdminColorScheme::get_setting_name() ) );
		$this->assertEquals( 'fresh', get_user_option( 'admin_color' ) );
	}

	public function test_set_forced_color_scheme_saves_color_to_option_when_mixed_case() {
		$color = 'coffee';
		$this->create_user( 'editor' );

		$this->assertEquals( 'fresh', get_user_option( 'admin_color' ) );

		$this->assertEquals( $color, c2c_ForceAdminColorScheme::set_forced_color_scheme( ucfirst( $color ) ) );
		$this->assertEquals( $color, get_option( c2c_ForceAdminColorScheme::get_setting_name() ) );
	}

	public function test_set_forced_color_scheme_does_not_save_invalid_color_to_option() {
		$color = 'bogus';
		$this->create_user( 'editor' );

		$this->assertEquals( 'fresh', get_user_option( 'admin_color' ) );

		$this->assertEmpty( c2c_ForceAdminColorScheme::set_forced_color_scheme( $color ) );
		$this->assertEmpty( get_option( c2c_ForceAdminColorScheme::get_setting_name() ) );
		$this->assertEmpty( c2c_ForceAdminColorScheme::get_forced_color_scheme() );
	}

	public function test_set_forced_color_scheme_does_not_overwrite_existing_forced_color_scheme_with_invalid_color_scheme() {
		$color = 'coffee';
		$this->create_user( 'editor' );

		$this->assertEquals( $color, c2c_ForceAdminColorScheme::set_forced_color_scheme( $color ) );

		$this->assertEmpty( c2c_ForceAdminColorScheme::set_forced_color_scheme( 'bogus' ) );
		$this->assertEquals( $color, get_option( c2c_ForceAdminColorScheme::get_setting_name() ) );
		$this->assertEquals( $color, c2c_ForceAdminColorScheme::get_forced_color_scheme() );
	}

	/*
	 * get_filtered_color_scheme()
	 */

	public function test_get_filtered_color_scheme() {
		$this->assertEmpty( c2c_ForceAdminColorScheme::get_filtered_color_scheme() );
	}

	public function test_get_filtered_color_scheme_with_forced_color_scheme() {
		c2c_ForceAdminColorScheme::set_forced_color_scheme( 'ocean' );

		$this->assertEmpty( c2c_ForceAdminColorScheme::get_filtered_color_scheme() );
	}

	public function test_get_filtered_color_scheme_that_is_filtered() {
		add_filter( 'c2c_force_admin_color_scheme', function ( $color ) { return 'midnight'; } );

		$this->assertEquals( 'midnight', c2c_ForceAdminColorScheme::get_filtered_color_scheme() );
	}

	public function test_get_filtered_color_scheme_that_is_filtered_with_forced_color_scheme_that_is_returned() {
		add_filter( 'c2c_force_admin_color_scheme', function ( $x, $color ) { return $color; }, 10, 2 );
		c2c_ForceAdminColorScheme::set_forced_color_scheme( 'ocean' );

		$this->assertEquals( 'ocean', c2c_ForceAdminColorScheme::get_filtered_color_scheme() );
	}

	public function test_get_filtered_color_scheme_that_is_filtered_but_mixed_case() {
		add_filter( 'c2c_force_admin_color_scheme', function ( $color ) { return 'Midnight'; } );

		$this->assertEquals( 'midnight', c2c_ForceAdminColorScheme::get_filtered_color_scheme() );
	}

	public function test_get_filtered_color_scheme_that_is_filtered_but_empty_string() {
		add_filter( 'c2c_force_admin_color_scheme', '__return_empty_string' );

		$this->assertEmpty( c2c_ForceAdminColorScheme::get_filtered_color_scheme() );
	}

	public function test_get_filtered_color_scheme_that_is_filtered_but_invalid_return_type() {
		add_filter( 'c2c_force_admin_color_scheme', '__return_empty_array' );

		$this->assertEquals( '', c2c_ForceAdminColorScheme::get_filtered_color_scheme() );
	}

	public function test_get_filtered_color_scheme_that_is_filtered_but_bogus_color_scheme() {
		add_filter( 'c2c_force_admin_color_scheme', function ( $color ) { return 'bogus'; } );

		// The function does not validate the returned filtered value.
		$this->assertEquals( 'bogus', c2c_ForceAdminColorScheme::get_filtered_color_scheme() );
	}

	/*
	 * get_color_scheme_via_constant()
	 */

	public function test_get_color_scheme_via_constant() {
		$this->assertEmpty( c2c_ForceAdminColorScheme::get_color_scheme_via_constant() );
	}

	/*
	 * is_valid_color_scheme()
	 */

	public function test_is_valid_color_scheme_with_valid_color_schemes() {
		$this->assertTrue( c2c_ForceAdminColorScheme::is_valid_color_scheme( 'ocean' ) );
		$this->assertTrue( c2c_ForceAdminColorScheme::is_valid_color_scheme( 'coffee' ) );
	}

	public function test_is_valid_color_scheme_with_invalid_color_schemes() {
		$this->assertFalse( c2c_ForceAdminColorScheme::is_valid_color_scheme( 'phony' ) );
		$this->assertFalse( c2c_ForceAdminColorScheme::is_valid_color_scheme( 'fake' ) );
	}

	/*
	 * force_color_scheme()
	 */

	public function test_force_admin_color_returns_passed_in_color_if_forced_color_not_set() {
		$this->assertEquals( 'coffee', c2c_ForceAdminColorScheme::force_color_scheme( 'coffee' ) );
	}

	public function test_force_admin_color_returns_forced_color_if_set() {
		c2c_ForceAdminColorScheme::set_forced_color_scheme( 'sunrise' );

		$this->assertEquals( 'sunrise', c2c_ForceAdminColorScheme::force_color_scheme( 'coffee' ) );
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

		$expected = '<label for="c2c_forced_admin_color"><input name="c2c_forced_admin_color" type="checkbox" id="c2c_forced_admin_color" value="true" /> Force this admin color scheme on all users?</label>';

		$this->expectOutputRegex( '~^' . preg_quote( $expected ) . '$~', c2c_ForceAdminColorScheme::add_checkbox() );
	}

	public function test_add_checkbox_outputs_for_user_with_cap_when_forced_color_set() {
		$this->create_user( 'administrator' );

		c2c_ForceAdminColorScheme::set_forced_color_scheme( 'ocean' );
		$expected = '<label for="c2c_forced_admin_color"><input name="c2c_forced_admin_color" type="checkbox" id="c2c_forced_admin_color" value="true" checked=\'checked\' /> '
			. 'Force this admin color scheme on all users?'
			. '</label>'
			. '<em class="c2c_forced_admin_color notice notice-info">Currently forced admin color scheme: <strong>Ocean</strong></em>';

		$this->expectOutputRegex( '~^' . preg_quote( $expected ) . '$~', c2c_ForceAdminColorScheme::add_checkbox() );
	}

	public function test_add_checkbox_outputs_message_for_user_with_cap_when_filter_is_set_even_when_forced_color_set() {
		$this->create_user( 'administrator' );
		add_filter( 'c2c_force_admin_color_scheme', function ( $color ) { return 'midnight'; } );

		$expected = '<em class="c2c_forced_admin_color notice notice-info">Currently forced admin color scheme (via the filter <strong><code>c2c_force_admin_color_scheme</code></strong>, and thus cannot be changed above): <strong>Midnight</strong></em>';
		c2c_ForceAdminColorScheme::set_forced_color_scheme( 'ocean' );

		$this->expectOutputRegex( '~^' . preg_quote( $expected ) . '$~', c2c_ForceAdminColorScheme::add_checkbox() );
	}

	public function test_add_checkbox_does_not_output_message_for_user_with_cap_when_filter_returns_empty_string() {
		$this->create_user( 'administrator' );

		add_filter( 'c2c_force_admin_color_scheme', '__return_empty_string' );

		$expected = '<label for="c2c_forced_admin_color"><input name="c2c_forced_admin_color" type="checkbox" id="c2c_forced_admin_color" value="true" /> Force this admin color scheme on all users?</label>';

		$this->expectOutputRegex( '~^' . preg_quote( $expected ) . '$~', c2c_ForceAdminColorScheme::add_checkbox() );
	}

	public function test_add_checkbox_outputs_message_for_user_with_cap_when_filter_returns_invalid_color_scheme_and_no_existing_forced_color_set() {
		$this->create_user( 'administrator' );
		add_filter( 'c2c_force_admin_color_scheme', function ( $color ) { return 'bogus'; } );

		$expected = '<label for="c2c_forced_admin_color"><input name="c2c_forced_admin_color" type="checkbox" id="c2c_forced_admin_color" value="true" /> Force this admin color scheme on all users?</label>';

		$this->expectOutputRegex( '~^' . preg_quote( $expected ) . '$~', c2c_ForceAdminColorScheme::add_checkbox() );
	}

	public function test_add_checkbox_outputs_message_for_user_with_cap_when_filter_returns_invalid_color_scheme_and_existing_forced_color_set() {
		$this->create_user( 'administrator' );
		add_filter( 'c2c_force_admin_color_scheme', function ( $color ) { return 'bogus'; } );

		c2c_ForceAdminColorScheme::set_forced_color_scheme( 'ocean' );
		$expected = '<label for="c2c_forced_admin_color"><input name="c2c_forced_admin_color" type="checkbox" id="c2c_forced_admin_color" value="true" checked=\'checked\' /> '
			. 'Force this admin color scheme on all users?'
			. '</label>'
			. '<em class="c2c_forced_admin_color notice notice-info">Currently forced admin color scheme: <strong>Ocean</strong></em>'
			. '<em class="c2c_forced_admin_color notice notice-warning"><strong>Notice:</strong> The filter <strong><code>c2c_force_admin_color_scheme</code></strong> is hooked and returns an invalid color scheme (<strong>bogus</strong>) and is being ignored.</em>';

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
		$this->assertEmpty( c2c_ForceAdminColorScheme::get_forced_color_scheme() );
	}

	public function test_save_setting_does_save_for_user_with_cap() {
		$user_id = $this->create_user( 'administrator' );
		$_POST = array(
			'admin_color'            => 'sunrise',
			'c2c_forced_admin_color' => '1',
		);

		c2c_ForceAdminColorScheme::save_setting( $user_id );

		$this->assertEquals( 'sunrise', get_user_option( 'admin_color' ) );
		$this->assertEquals( 'sunrise', c2c_ForceAdminColorScheme::get_forced_color_scheme() );
	}

	public function test_save_setting_unsets_setting_for_user_with_cap_when_unchecked() {
		$user_id = $this->create_user( 'administrator' );
		$_POST = array(
			'admin_color'            => 'ocean',
			'c2c_forced_admin_color' => '0',
		);

		c2c_ForceAdminColorScheme::set_forced_color_scheme( 'coffee' );
		c2c_ForceAdminColorScheme::save_setting( $user_id );

		// The test hasn't actually saved a new color to the user's option
		// directly, and since the forced color was unset, then the user's
		// original color should be returned.
		$this->assertEquals( 'fresh', get_user_option( 'admin_color' ) );
		$this->assertEmpty( c2c_ForceAdminColorScheme::get_forced_color_scheme() );
	}

	/*
	 * hide_admin_color_scheme_picker()
	 */

	public function test_hide_admin_color_scheme_picker_does_not_hide_from_user_with_cap() {
		$this->create_user( 'administrator' );

		c2c_ForceAdminColorScheme::hide_admin_color_scheme_picker();

		$this->assertEquals( 10, has_action( 'admin_color_scheme_picker', 'admin_color_scheme_picker' ) );
	}

	public function test_hide_admin_color_scheme_picker_does_not_hide_from_user_with_cap_when_color_is_set() {
		$this->create_user( 'administrator' );
		c2c_ForceAdminColorScheme::set_forced_color_scheme( 'coffee' );

		c2c_ForceAdminColorScheme::hide_admin_color_scheme_picker();

		$this->assertEquals( 10, has_action( 'admin_color_scheme_picker', 'admin_color_scheme_picker' ) );
	}

	public function test_hide_admin_color_scheme_picker_does_not_hide_from_user_without_cap_when_color_not_set() {
		$this->create_user( 'editor' );

		c2c_ForceAdminColorScheme::hide_admin_color_scheme_picker();

		$this->assertEquals( 10, has_action( 'admin_color_scheme_picker', 'admin_color_scheme_picker' ) );
	}

	public function test_hide_admin_color_scheme_picker_hides_from_user_without_cap_when_color_is_set() {
		$this->create_user( 'editor' );
		c2c_ForceAdminColorScheme::set_forced_color_scheme( 'coffee' );

		c2c_ForceAdminColorScheme::hide_admin_color_scheme_picker();

		$this->assertFalse( has_action( 'admin_color_scheme_picker', 'admin_color_scheme_picker' ) );
	}

	/*
	 * register_css()
	 */

	public function test_output_css_is_not_hooked_without_call_to_register_css_() {
		$this->assertFalse( has_action( 'admin_head', array( 'c2c_ForceAdminColorScheme', 'output_css' ) ) );
	}

	public function test_register_css() {
		c2c_ForceAdminColorScheme::register_css();

		$this->assertEquals( 10, has_action( 'admin_head', array( 'c2c_ForceAdminColorScheme', 'output_css' ) ) );
	}

	/*
	 * output_css()
	 */

	public function test_output_css_for_user_with_cap() {
		$this->create_user( 'administrator' );
		$expected = 'label[for="c2c_forced_admin_color"], .c2c_forced_admin_color { display: block; padding-left: 15px; margin-top: 10px; }'
			. "\n" . '.wrap .c2c_forced_admin_color.notice { line-height: 2em; margin-left: 15px; margin-top: 15px; margin-bottom: 0; }';

		$this->expectOutputRegex( '~^<style>' . preg_quote( $expected ) . '</style>$~', c2c_ForceAdminColorScheme::output_css() );
	}

	public function test_output_css_for_user_without_cap_and_forced_color_is_set() {
		$this->create_user( 'editor' );
		c2c_ForceAdminColorScheme::set_forced_color_scheme( 'ocean' );
		$expected = '.user-admin-color-wrap { display: none; }'
			. "\n" . '.wrap .c2c_forced_admin_color.notice { line-height: 2em; margin-left: 15px; margin-top: 15px; margin-bottom: 0; }';

		$this->expectOutputRegex( '~^<style>' . preg_quote( $expected ) . '</style>$~', c2c_ForceAdminColorScheme::output_css() );
	}

	public function test_output_css_for_user_without_cap_and_forced_color_is_not_set() {
		$this->create_user( 'editor' );

		$this->expectOutputRegex( '~^$~', c2c_ForceAdminColorScheme::output_css() );
	}

	/*
	 * Options handling
	 */

	public function test_uninstall_deletes_option() {
		$option = c2c_ForceAdminColorScheme::get_setting_name();

		c2c_ForceAdminColorScheme::set_forced_color_scheme( 'ocean' );

		$this->assertEquals( 'ocean', get_option( $option ) );

		c2c_ForceAdminColorScheme::uninstall();

		$this->assertFalse( get_option( $option ) );
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

		c2c_ForceAdminColorScheme::set_forced_color_scheme( 'ocean' );
		define( 'C2C_FORCE_ADMIN_COLOR_SCHEME', 'coffee' );

		$this->assertEquals( 'coffee', c2c_ForceAdminColorScheme::get_forced_color_scheme() );
		$this->assertEquals( 'coffee', get_user_option( 'admin_color' ) );
	}

	public function test_get_color_scheme_via_constant_when_constant_is_set() {
		$this->assertEquals( 'coffee', c2c_ForceAdminColorScheme::get_color_scheme_via_constant() );
	}

	public function test_is_constant_set_when_constant_is_set() {
		$this->assertTrue( c2c_ForceAdminColorScheme::is_constant_set() );
	}

	public function test_add_checkbox_outputs_message_for_user_with_cap_when_constant_is_set_even_when_forced_color_set() {
		$this->create_user( 'administrator' );

		$expected = '<em class="c2c_forced_admin_color notice notice-info">Currently forced admin color scheme (via the constant <strong><code>C2C_FORCE_ADMIN_COLOR_SCHEME</code></strong>, and thus cannot be changed above): <strong>Coffee</strong></em>';
		c2c_ForceAdminColorScheme::set_forced_color_scheme( 'ocean' );

		$this->expectOutputRegex( '~^' . preg_quote( $expected ) . '$~', c2c_ForceAdminColorScheme::add_checkbox() );
	}

	public function test_set_forced_color_scheme_saves_color_to_option_even_if_constant_set() {
		$color = 'sunrise';
		$expected = 'coffee';

		$this->create_user( 'editor' );

		$this->assertEquals( $expected, get_user_option( 'admin_color' ) );

		$this->assertEquals( $color, c2c_ForceAdminColorScheme::set_forced_color_scheme( $color ) );
		$this->assertEquals( $color, get_option( c2c_ForceAdminColorScheme::get_setting_name() ) );
		$this->assertEquals( $expected, get_user_option( 'admin_color' ) );
	}

	public function test_save_setting_does_not_save_for_user_with_cap_if_constant_set() {
		$user_id = $this->create_user( 'administrator' );
		$_POST = array(
			'admin_color'            => 'sunrise',
			'c2c_forced_admin_color' => '1',
		);

		c2c_ForceAdminColorScheme::save_setting( $user_id );

		$this->assertEquals( 'coffee', get_user_option( 'admin_color' ) );
		$this->assertEquals( 'coffee', c2c_ForceAdminColorScheme::get_forced_color_scheme() );
		$this->assertEmpty( get_option( c2c_ForceAdminColorScheme::get_setting_name() ) );
	}

}
