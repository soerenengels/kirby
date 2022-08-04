<?php

namespace Kirby\Field;

use Kirby\Attribute\IconAttribute;
use Kirby\Attribute\LabelAttribute;
use Kirby\Blueprint\Blueprints;
use Kirby\Option\Option;
use Kirby\Option\Options;

/**
 * Blueprints field
 *
 * @package   Kirby Field
 * @author    Bastian Allgeier <bastian@getkirby.com>
 * @link      https://getkirby.com
 * @copyright Bastian Allgeier
 * @license   https://opensource.org/licenses/MIT
 */
class BlueprintsField extends SelectField
{
	public const TYPE = 'select';

	public function __construct(
		public string $id,
		public Blueprints|null $blueprints = null,
		...$args
	) {
		parent::__construct($id, ...$args);
	}

	public function blueprints(): Options
	{
		$options = new Options;

		foreach ($this->blueprints ?? [] as $blueprint) {
			$option = new Option(
				text: $blueprint->label,
				value: $blueprint->id,
			);

			$options->__set($option->value, $option);
		}

		return $options;
	}

	public function defaults(): void
	{
		$this->icon    ??= new IconAttribute('template');
		$this->label   ??= new LabelAttribute(['*' => 'template']);
		$this->options ??= $this->blueprints();

		parent::defaults();
	}
}
