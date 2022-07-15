<?php
declare(strict_types=1);

namespace n0nag0n\Extension;

class Session_Extension extends Extension_Base implements \Tracy\IBarPanel {

	/**
	 * Gets the panel
	 *
	 * @return string
	 */
	public function getPanel() {
		$session_data  = $_SESSION;
		ksort($session_data, SORT_NATURAL);
		$table_tr_html = '';
		foreach($session_data as $key => $value) {
			$table_tr_html .= '<tr><td>'.$key.'</td><td>'.$this->handleLongStrings($value).'</td></tr>'."\n";
		}
		$html = <<<EOT
			<h1>SESSION Data</h1> 
			<div class="tracy-inner" style="max-height: 400px;">
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
			<span title="Session Data">
				<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="tan" class="bi bi-archive-fill" viewBox="0 0 16 16">
					<path d="M12.643 15C13.979 15 15 13.845 15 12.5V5H1v7.5C1 13.845 2.021 15 3.357 15h9.286zM5.5 7h5a.5.5 0 0 1 0 1h-5a.5.5 0 0 1 0-1zM.8 1a.8.8 0 0 0-.8.8V3a.8.8 0 0 0 .8.8h14.4A.8.8 0 0 0 16 3V1.8a.8.8 0 0 0-.8-.8H.8z"/>
				</svg>
				<span class="tracy-label">Session</span>
			</span>
			EOT;
	}

}
