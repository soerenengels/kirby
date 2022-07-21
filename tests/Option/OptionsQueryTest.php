<?php

namespace Kirby\Option;

use Kirby\Cms\App;
use Kirby\Cms\Page;
use Kirby\Field\TestCase;

class MyPage extends Page
{
	public function myArray(): array
	{
		return [['name' => 'foo'], ['name' => 'bar']];
	}

	public function myOptions(): Options
	{
		return Options::factory(['foo', 'bar']);
	}
}

/**
 * @covers \Kirby\Option\OptionsQuery
 */
class OptionsQueryTest extends TestCase
{
	/**
	 * @covers ::__construct
	 */
	public function testConstruct()
	{
		$options = new OptionsQuery('site.children', '{{ page.slug }}');
		$this->assertSame('site.children', $options->query);
		$this->assertSame('{{ page.slug }}', $options->text);
		$this->assertNull($options->value);
	}

	/**
	 * @covers ::factory
	 */
	public function testFactory()
	{
		$options = OptionsQuery::factory([
			'query' => 'site.children',
			'text'  => '{{ page.slug }}'
		]);

		$this->assertSame('site.children', $options->query);
		$this->assertSame('{{ page.slug }}', $options->text);
		$this->assertNull($options->value);
	}

	/**
	 * @covers ::resolve
	 */
	public function testResolveForArray()
	{
		$model   = new MyPage(['slug' => 'a']);
		$options = (new OptionsQuery(
			query: 'page.myArray',
			value: '{{ arrayItem.name }}',
		))->render($model);

		$this->assertSame('foo', $options[0]['value']);
		$this->assertSame('bar', $options[1]['value']);

		// since we didn't define a text query template,
		// the default `{{ arrayItem.value }}` is used
		// but our array doesn't have a a value key
		$this->assertSame('', $options[0]['text']);

		// with shorter alias
		$options = (new OptionsQuery(
			query: 'page.myArray',
			value: '{{ item.name }}',
		))->render($model);

		$this->assertSame('foo', $options[0]['value']);
		$this->assertSame('bar', $options[1]['value']);
	}

	/**
	 * @covers ::resolve
	 */
	public function testResolveForStructure()
	{
		$model = new Page([
			'slug' => 'a',
			'content' => [
				'foo' => '
-
  name: foo
-
  name: bar
				'
			]
		]);

		$options = (new OptionsQuery(
			query: 'page.foo.toStructure',
			value: '{{ structureItem.name }}',
		))->render($model);

		$this->assertSame('foo', $options[0]['value']);
		$this->assertSame('bar', $options[1]['value']);

		// with shorter alias
		$options = (new OptionsQuery(
			query: 'page.foo.toStructure',
			value: '{{ item.name }}',
		))->render($model);

		$this->assertSame('foo', $options[0]['value']);
		$this->assertSame('bar', $options[1]['value']);
	}

	/**
	 * @covers ::resolve
	 */
	public function testResolveForBlock()
	{
		$model = new Page([
			'slug' => 'a',
			'content' => [
				'foo' => '[
					{ "type":"image", "content": { "headline": "foo" } },
					{ "type":"test", "content": { "headline": "bar" } }
				]'
			]
		]);

		$options = (new OptionsQuery(
			query: 'page.foo.toBlocks',
			text: '{{ block.type }}',
			value: '{{ block.headline }}',
		))->render($model);

		$this->assertSame('image', $options[0]['text']);
		$this->assertSame('foo', $options[0]['value']);
		$this->assertSame('test', $options[1]['text']);
		$this->assertSame('bar', $options[1]['value']);
	}

	/**
	 * @covers ::resolve
	 */
	public function testResolveForPages()
	{
		$app = new App([
			'site' => [
				'children' => [
					['slug' => 'a'],
					['slug' => 'b'],
					['slug' => 'c'],
				]
			]
		]);

		$options = new OptionsQuery(
			query: 'site.children',
			text: '{{ page.slug }}'
		);
		$options = $options->render($app->site());


		$this->assertSame('a', $options[0]['text']);
		$this->assertSame('a', $options[0]['value']);
		$this->assertSame('b', $options[1]['text']);
		$this->assertSame('c', $options[2]['text']);
	}

	/**
	 * @covers ::resolve
	 */
	public function testResolveForFile()
	{
		$app = new App([
			'site' => [
				'files' => [
					['filename' => 'a.jpg'],
					['filename' => 'b.pdf']
				]
			]
		]);

		$options = new OptionsQuery(
			query: 'site.files',
		);
		$options = $options->render($app->site());


		$this->assertSame('a.jpg', $options[0]['text']);
		$this->assertSame('b.pdf', $options[1]['text']);
	}

	/**
	 * @covers ::resolve
	 */
	public function testResolveForUser()
	{
		$app = new App([
			'users' => [
				['email' => 'homer@simpson.com', 'name' => 'homer']
			]
		]);

		$options = new OptionsQuery(
			query: 'kirby.users',
		);
		$options = $options->render($app->site());


		$this->assertSame('homer@simpson.com', $options[0]['value']);
		$this->assertSame('homer', $options[0]['text']);
	}

	/**
	 * @covers ::resolve
	 */
	public function testResolveForOptions()
	{
		$model   = new MyPage(['slug' => 'a']);
		$options = new OptionsQuery('page.myOptions');
		$options = $options->render($model);

		$this->assertSame('foo', $options[0]['text']);
		$this->assertSame('foo', $options[0]['value']);
		$this->assertSame('bar', $options[1]['text']);
		$this->assertSame('bar', $options[1]['value']);
	}

	/**
	 * @covers ::resolve
	 */
	public function testResolveInvalid()
	{
		$app = new App([
			'site' => [
				'content' => [
					'foo' => 'a'
				]
			]
		]);

		$this->expectException('Kirby\Exception\InvalidArgumentException');
		$this->expectExceptionMessage('Invalid query result data: Kirby\Cms\Field');

		$options = (new OptionsQuery('site.foo'))->render($app->site());
	}
}