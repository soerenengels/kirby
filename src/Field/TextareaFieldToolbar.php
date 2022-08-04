<?php

namespace Kirby\Field;

use Kirby\Foundation\Bools;

/**
 * Toolbar Button Setup
 *
 * @package   Kirby Field
 * @author    Bastian Allgeier <bastian@getkirby.com>
 * @link      https://getkirby.com
 * @copyright Bastian Allgeier
 * @license   https://opensource.org/licenses/MIT
 */
class TextareaFieldToolbar extends Bools
{
	public function __construct(
		public bool $bold = true,
		public bool $code = true,
		public bool $email = true,
		public bool $file = true,
		public bool $h1 = true,
		public bool $h2 = true,
		public bool $h3 = true,
		public bool $h4 = true,
		public bool $h5 = true,
		public bool $h6 = true,
		public bool $italic = true,
		public bool $link = true,
		public bool $ol = true,
		public bool $ul = true,
	) {
	}
}