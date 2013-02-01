<?php

class DebugMemoTest extends CakeTestCase
{

	public $fixtures = array(
		'plugin.debug_memo.debug_memo',
	);

	public function setUp()
	{
		parent::setUp();
	}

	public function testUrlString()
	{
		App::uses('DebugMemo', 'DebugMemo.Model');

		$args = array(
			'plugin' => 'p',
			'controller' => 'c',
			'action' => 'a',
		);

		$result = DebugMemo::urlString($args);
		$expected = 'p/c/a';
		$this->assertEquals($expected, $result);

		$args = array(
			'controller' => 'c',
			'action' => 'a',
		);
		$result = DebugMemo::urlString($args);
		$expected = 'c/a';
		$this->assertEquals($expected, $result);
	}

	public function testReadMemo()
	{
		$DebugMemo = ClassRegistry::init('DebugMemo.DebugMemo');

		$memoSample1 = 'This is a memorandum.';

		$data1 = array(
			'DebugMemo' => array(
				'controller' => 'c',
				'action' => 'a',
				'memo' => $memoSample1,
			),
		);

		$DebugMemo->update($data1);

		$result = DebugMemo::readMemo($data1['DebugMemo']);
		$expected = $memoSample1;
		$this->assertEquals($expected, $result);

		$memoSample2 = 'This is another memorandum.';

		$data2 = array(
			'DebugMemo' => array(
				'plugin' => 'p',
				'controller' => 'c',
				'action' => 'a',
				'memo' => $memoSample2,
			),
		);

		$DebugMemo->update($data2);

		$result = DebugMemo::readMemo($data2['DebugMemo']);
		$expected = $memoSample2;
		$this->assertEquals($expected, $result);

		$result = DebugMemo::readMemo($data1['DebugMemo']);
		$expected = $memoSample1;
		$this->assertEquals($expected, $result);
	}

}
