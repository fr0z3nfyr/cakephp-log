<?php
App::uses('LogBehavior', 'Log.Model/Behavior');

/**
 * LogBehavior Test Case
 *
 */
class LogBehaviorTest extends CakeTestCase {

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->Log = new LogBehavior();
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
