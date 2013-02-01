<?php

class MemobarComponentTest extends CakeTestCase
{

	public function setUp()
	{
		parent::setUp();

		App::uses('Controller', 'Controller');
		App::uses('ComponentCollection', 'Controller');
		App::uses('MemobarComponent', 'DebugMemo.Controller/Component');

		$collection = new ComponentCollection();
		$this->MemobarComponent = new MemobarComponent($collection);
	}

	public function tearDown()
	{
		parent::tearDown();
	}

	public function testGetAsset()
	{
		$expected = $this->MemobarComponent->css;
		$result = $this->MemobarComponent->getAsset('css');
		$this->assertEquals($expected, $result);

		$expected = $this->MemobarComponent->javascript;
		$result = $this->MemobarComponent->getAsset('javascript');
		$this->assertEquals($expected, $result);

		$expected = $this->MemobarComponent->javascript;
		$result = $this->MemobarComponent->getAsset('js');
		$this->assertEquals($expected, $result);

		Configure::write('DebugMemo.javascript.jquery', '/js/jquery');

		$expected = $this->MemobarComponent->javascript;
		$expected['jquery'] = '/js/jquery';
		$result = $this->MemobarComponent->getAsset('javascript');
		$this->assertEquals($expected, $result);

		Configure::write('DebugMemo.javascript.jquery', null);

		$expected = $this->MemobarComponent->javascript;
		unset($expected['jquery']);
		$result = $this->MemobarComponent->getAsset('javascript');
		$this->assertEquals($expected, $result);

		Configure::write('DebugMemo.css.0', 'style.css');
		$expected = $this->MemobarComponent->css;
		$expected[] = 'style.css';
		$result = $this->MemobarComponent->getAsset('css');
		$this->assertEquals($expected, $result);
	}

}
