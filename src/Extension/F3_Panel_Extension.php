<?php
declare(strict_types=1);

namespace n0nag0n\Extension;

use Base;

class F3_Panel_Extension extends Extension_Base implements \Tracy\IBarPanel {

	/**
	 * Construct
	 *
	 * @param Base $f3 the $f3 variable
	 */
	public function __construct(Base $f3) {
		$this->f3 = $f3;
	}

	/**
	 * Gets the panel
	 *
	 * @return string
	 */
	public function getPanel() {
		$f3_data = (array) $this->f3->hive();
		ksort($f3_data, SORT_NATURAL);
		$table_tr_html = '';
		foreach($f3_data as $key => $value) {
			$table_tr_html .= '<tr><td>'.$key.'</td><td>'.$this->handleLongStrings($value).'</td></tr>'."\n";
		}
		$html = <<<EOT
			<h1>F3 Data</h1> 
			<div class="tracy-inner" style="max-height: 400px; overflow: auto;">
				<table>
					<tbody>
						{$table_tr_html}
					</tbody>
				</table>
			</div>
			EOT;
		return $html;
	}

	/**
	 * Gets the tab
	 *
	 * @return string
	 */
	public function getTab() {
		return <<<EOT
			<span title="F3 Hive">
				<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="indigo" class="bi bi-file-zip-fill" viewBox="0 0 16 16">
					<path d="M8.5 9.438V8.5h-1v.938a1 1 0 0 1-.03.243l-.4 1.598.93.62.93-.62-.4-1.598a1 1 0 0 1-.03-.243z"/>
					<path d="M4 0h8a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V2a2 2 0 0 1 2-2zm2.5 8.5v.938l-.4 1.599a1 1 0 0 0 .416 1.074l.93.62a1 1 0 0 0 1.109 0l.93-.62a1 1 0 0 0 .415-1.074l-.4-1.599V8.5a1 1 0 0 0-1-1h-1a1 1 0 0 0-1 1zm1-5.5h-1v1h1v1h-1v1h1v1H9V6H8V5h1V4H8V3h1V2H8V1H6.5v1h1v1z"/>
				</svg>
				<span class="tracy-label">F3</span>
			</span>
			EOT;
	}

}
