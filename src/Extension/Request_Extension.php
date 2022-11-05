<?php
declare(strict_types=1);

namespace n0nag0n\Extension;

use Base;
use Text;

class Request_Extension extends Extension_Base implements \Tracy\IBarPanel {

	/**
	 * Gets the panel
	 *
	 * @return string
	 */
	public function getPanel() {
		$f3 = Base::instance();
		$request_data  = $_SERVER;
		ksort($request_data, SORT_NATURAL);
		$table_tr_html = '';
		foreach($request_data as $key => $value) {
			$table_tr_html .= '<tr><td>'.$key.'</td><td>'.$this->handleLongStrings($value).'</td></tr>'."\n";
		}
		$request_data['HTTP_X_FORWARDED_FOR'] = $request_data['HTTP_X_FORWARDED_FOR'] ?? '';
		$get_html = $this->handleLongStrings($_GET);
		$post_html = $this->handleLongStrings($_POST);
		$files_html = $this->handleLongStrings($_FILES);
		$request_data['REQUEST_URI'] = $this->handleLongStrings($request_data['REQUEST_URI']);
		$request_data['HTTP_USER_AGENT'] = $this->handleLongStrings($request_data['HTTP_USER_AGENT']);
		$request_data['USER'] = !empty($request_data['USER']) ? $request_data['USER'] : '';
		$view_files = $this->handleLongStrings(array_filter(get_included_files(), function($value) use ($f3) { return strpos($value, $f3->UI) !== false; }));
		$html = <<<EOT
			<h1>Request</h1> 
			<div class="tracy-inner" style="max-height: 400px;">
				<table>
					<tbody>
						<tr><td colspan="2" style="background-color: #EEE;"><b>Common</b></td></tr>
						<tr><td>Included View Files</td><td>{$view_files}</td></tr>
						<tr><td>REQUEST_METHOD</td><td>{$request_data['REQUEST_METHOD']}</td></tr>
						<tr><td>REQUEST_URI</td><td>{$request_data['REQUEST_URI']}</td></tr>
						<tr><td>REMOTE_ADDR</td><td>{$request_data['REMOTE_ADDR']}</td></tr>
						<tr><td>HTTP_X_FORWARDED_FOR</td><td>{$request_data['HTTP_X_FORWARDED_FOR']}</td></tr>
						<tr><td>HTTP_HOST</td><td>{$request_data['HTTP_HOST']}</td></tr>
						<tr><td>PHP_SELF</td><td>{$request_data['PHP_SELF']}</td></tr>
						<tr><td>USER</td><td>{$request_data['USER']}</td></tr>
						<tr><td colspan="2" style="background-color: #EEE;"><b>Payload</b></td></tr>
						<tr><td>GET</td><td>{$get_html}</td></tr>
						<tr><td>POST</td><td>{$post_html}</td></tr>
						<tr><td>FILES</td><td>{$files_html}</td></tr>
						<tr><td colspan="2" style="background-color: #EEE;"><b>All</b></td></tr>
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
		$uri = explode('?', $_SERVER['REQUEST_URI'])[0];
		return <<<EOT
			<span title="Request">
				<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="green" class="bi bi-caret-right-square-fill" viewBox="0 0 16 16">
					<path d="M0 2a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V2zm5.5 10a.5.5 0 0 0 .832.374l4.5-4a.5.5 0 0 0 0-.748l-4.5-4A.5.5 0 0 0 5.5 4v8z"/>
				</svg>
				<span class="tracy-label">{$uri}</span>
			</span>
			EOT;
	}

}
