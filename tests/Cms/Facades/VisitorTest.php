<?php

namespace Kirby\Cms;

class VisitorTest extends TestCase
{
	protected $app;

	public function setUp(): void
	{
		$this->app = new App([
			'roots' => [
				'index' => '/dev/null'
			]
		]);
	}

	public function testInstance()
	{
		$this->assertSame($this->app->visitor(), Visitor::instance());
	}
}
