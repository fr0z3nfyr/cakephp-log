<?php
App::uses('Log', 'Log.Model');

/**
 * Log Test Case
 *
 */
class LogTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'plugin.log.log'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->Log = ClassRegistry::init('Log.Log');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->Log);

		parent::tearDown();
	}

}
