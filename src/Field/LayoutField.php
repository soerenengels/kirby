<?php

namespace Kirby\Field;

use Kirby\Cms\ModelWithContent;
use Kirby\Field\Prop\Layouts;
use Kirby\Field\Prop\LayoutSettings;

/**
 * Layout field
 *
 * @package   Kirby Field
 * @author    Bastian Allgeier <bastian@getkirby.com>
 * @link      https://getkirby.com
 * @copyright Bastian Allgeier
 * @license   https://opensource.org/licenses/MIT
 */
class LayoutField extends BlocksField
{
	public const TYPE = 'layout';

	public function __construct(
		public string $id,
		public Layouts|null $layouts = null,
		public LayoutSettings|null $settings = null,
		...$args
	) {
		parent::__construct($id, ...$args);
	}

	public function render(ModelWithContent $model): array
	{
		return parent::render($model) + [
			'layouts'  => $this->layouts?->render($model),
			'settings' => $this->settings?->render($model),
		];
	}
}