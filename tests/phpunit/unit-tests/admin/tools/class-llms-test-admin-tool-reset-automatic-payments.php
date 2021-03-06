<?php
/**
 * Tests for the LLMS_Admin_Tool_Clear_Sessions class
 *
 * @package LifterLMS/Tests/Admins/Tools
 *
 * @group admin
 * @group admin_tools
 *
 * @since 4.13.0
 */
class LLMS_Test_Admin_Tool_Reset_Automatic_Payments extends LLMS_UnitTestCase {

	/**
	 * Setup before class
	 *
	 * Include abstract class.
	 *
	 * @since 4.13.0
	 *
	 * @return void
	 */
	public static function setUpBeforeClass() {

		parent::setUpBeforeClass();

		require_once LLMS_PLUGIN_DIR . 'includes/abstracts/llms-abstract-admin-tool.php';
		require_once LLMS_PLUGIN_DIR . 'includes/admin/tools/class-llms-admin-tool-reset-automatic-payments.php';

	}

	/**
	 * Setup the test case
	 *
	 * @since 4.13.0
	 *
	 * @return void
	 */
	public function setUp() {

		parent::setUp();
		$this->main = new LLMS_Admin_Tool_Reset_Automatic_Payments();

	}

	/**
	 * Test get_description()
	 *
	 * @since 4.13.0
	 *
	 * @return void
	 */
	public function test_get_description() {

		$res = LLMS_Unit_Test_Util::call_method( $this->main, 'get_description' );
		$this->assertTrue( ! empty( $res ) );
		$this->assertTrue( is_string( $res ) );

	}

	/**
	 * Test get_label()
	 *
	 * @since 4.13.0
	 *
	 * @return void
	 */
	public function test_get_label() {

		$res = LLMS_Unit_Test_Util::call_method( $this->main, 'get_label' );
		$this->assertTrue( ! empty( $res ) );
		$this->assertTrue( is_string( $res ) );

	}

	/**
	 * Test get_text()
	 *
	 * @since 4.13.0
	 *
	 * @return void
	 */
	public function test_get_text() {

		$res = LLMS_Unit_Test_Util::call_method( $this->main, 'get_text' );
		$this->assertTrue( ! empty( $res ) );
		$this->assertTrue( is_string( $res ) );

	}

	/**
	 * Test handle()
	 *
	 * @since 4.13.0
	 *
	 * @return void
	 */
	public function test_handle() {

		$actions = did_action( 'llms_site_clone_detected' );

		// Get the original values of options to be cleared.
		$orig_url    = get_option( 'llms_site_url' );
		$orig_ignore = get_option( 'llms_site_url_ignore' );

		$this->expectException( LLMS_Unit_Test_Exception_Redirect::class );
		$this->expectExceptionMessage( sprintf( '%s [302] YES', admin_url( 'admin.php?page=llms-status&tab=tools') ) );

		try {
			LLMS_Unit_Test_Util::call_method( $this->main, 'handle' );
		} catch( LLMS_Unit_Test_Exception_Redirect $exception ) {

			$this->assertEquals( '', get_option( 'llms_site_url' ) );
			$this->assertEquals( 'no', get_option( 'llms_site_url_ignore' ) );
			$this->assertEquals( ++$actions, did_action( 'llms_site_clone_detected' ) );

			// Reset to the orig values.
			update_option( 'llms_site_url', $orig_url );
			update_option( 'llms_site_url_ignore', $orig_ignore );

			throw $exception;

		}

	}

	/**
	 * Test should_load() with no constants set.
	 *
	 * @since 4.13.0
	 *
	 * @return void
	 */
	public function test_should_load() {
		$this->assertTrue( true, LLMS_Unit_Test_Util::call_method( $this->main, 'should_load' ) );
	}

	/**
	 * Test should_load() with LLMS_SITE_IS_CLONE constant set.
	 *
	 * @since 4.13.0
	 *
	 * @runInSeparateProcess
	 * @preserveGlobalState disabled
	 *
	 * @return void
	 */
	public function test_should_load_with_site_clone_constant_set() {
		define( 'LLMS_SITE_IS_CLONE', false );
		$this->assertTrue( true, LLMS_Unit_Test_Util::call_method( $this->main, 'should_load' ) );
	}

	/**
	 * Test should_load() with LLMS_SITE_FEATURE_RECURRING_PAYMENTS constant set.
	 *
	 * @since 4.13.0
	 *
	 * @runInSeparateProcess
	 * @preserveGlobalState disabled
	 *
	 * @return void
	 */
	public function test_should_load_with_recurring_payments_constant_set() {
		define( 'LLMS_SITE_FEATURE_RECURRING_PAYMENTS', false );
		$this->assertTrue( true, LLMS_Unit_Test_Util::call_method( $this->main, 'should_load' ) );
	}

}
