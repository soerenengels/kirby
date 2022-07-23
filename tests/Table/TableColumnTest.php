<?php

namespace Kirby\Table;

use Kirby\Blueprint\Prop\Label;

/**
 * @covers \Kirby\Table\TableColumn
 */
class TableColumnTest extends TestCase
{
	/**
	 * @covers ::__construct
	 */
	public function testConstruct()
	{
		$column = new TableColumn(
			id: 'test'
		);

		$this->assertSame('test', $column->id);
		$this->assertInstanceOf(Label::class, $column->label);
		$this->assertSame('Test', $column->label->value);
		$this->assertFalse($column->mobile);
		$this->assertNull($column->align);
		$this->assertNull($column->field);
		$this->assertNull($column->value);
		$this->assertNull($column->width);
	}
}