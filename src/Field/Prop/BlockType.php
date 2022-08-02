<?php

namespace Kirby\Field\Prop;

use Kirby\Blueprint\Autoload;
use Kirby\Blueprint\Prop\Icon;
use Kirby\Blueprint\Prop\Label;
use Kirby\Blueprint\Prop\Text;
use Kirby\Drawer\Drawer;
use Kirby\Drawer\DrawerTabs;
use Kirby\Field\Fields;
use Kirby\Foundation\Node;

/**
 * Block type
 *
 * @package   Kirby Field
 * @author    Bastian Allgeier <bastian@getkirby.com>
 * @link      https://getkirby.com
 * @copyright Bastian Allgeier
 * @license   https://opensource.org/licenses/MIT
 */
class BlockType extends Node
{
	public function __construct(
		public string $id,
		public bool $disabled = false,
		public bool $editable = true,
		public Icon|null $icon = null,
		public Label|null $label = null,
		public Text|null $name = null,
		public string|null $preview = null,
		public DrawerTabs|null $tabs = null,
		public bool $translate = true,
		public bool $unset = false,
		public bool $wysiwyg = false,
		...$args
	) {
		parent::__construct($id, ...$args);
	}

	public function defaults(): void
	{
		$this->label ??= Label::fallback($this->id);
	}

	public function drawer(): Drawer
	{
		return new Drawer(
			id: $this->id,
			icon: $this->icon,
			label: $this->label,
			tabs: $this->tabs
		);
	}

	public function fields(): ?Fields
	{
		return $this->tabs->fields();
	}

	public static function load(string|array $props): static
	{
		return Autoload::block($props);
	}

	public static function polyfill(array $props): array
	{
		return Drawer::polyfill($props);
	}
}