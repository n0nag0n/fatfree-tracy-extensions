<?php
declare(strict_types=1);

namespace n0nag0n\Extension;

abstract class Extension_Base {

	/** @var int The width of the <pre> object when a value renders */
	protected $value_width = 300;

	/**
	 * Sets the value with for the <pre> tags
	 *
	 * @param integer $value_width the width of the <pre> tag
	 * @return void
	 */
	public function setValueWidth(int $value_width): void {
		$this->value_width = $value_width;
	}

	/**
	 * This will make it so the string or whatever is it can be clicked to show the data if it's too big to show
	 *
	 * @param mixed $value value
	 * @return string
	 */
	protected function handleLongStrings($value): string {
		if(is_array($value) === true || is_object($value) === true) {
			$value = print_r($value, true);
		}

		$value = is_bool($value) || is_int($value) ? var_export($value, true) : (string) $value;

		if(strlen($value) > 60) {
			$uniq_id = uniqid('');
			$value = $this->ellipsis($value, 60).' <a href="#tracy-request-panel-'.$uniq_id.'" class="tracy-toggle tracy-collapsed">more</a><pre id="tracy-request-panel-'.$uniq_id.'" class="tracy-collapsed" style="max-width: '.$this->value_width.'px; overflow: auto;"><code>'.$value.'</code></pre>';
		}

		return $value;
	}

	/**
	 * Limits a string to so many characters depending on the limit
	 *
	 * @param string  $text            text
	 * @param integer $character_limit character limit
	 * @return string
	 */
	protected function ellipsis(string $text, int $character_limit = 30): string {
		return mb_strlen($text) > $character_limit ? mb_substr($text, 0, $character_limit).'...' : $text;
	}

}
