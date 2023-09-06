<?php
declare(strict_types=1);

namespace n0nag0n\Extension;

use Base;
use DB;

class Database_Extension extends Extension_Base implements \Tracy\IBarPanel {

	/** @var \DB\SQL */
	protected $database_connection = null;

	/**
	 * Construct
	 *
	 * @param string $database_variable_name The Hive variable name for the database method
	 * @param \DB\SQL $database_object The database object you want to inject
	 */
	public function __construct(Base $f3, string $database_variable_name = 'DB', \DB\SQL $database_object = null) {
		$this->database_connection = $database_object !== null ? $database_object : $f3->get($database_variable_name);
	}

	/**
	 * Gets the panel
	 *
	 * @return string
	 */
	public function getPanel() {
		$html = <<<EOT
<div class="tracy-database-panel">
	<div class="tracy-database-heading">
		<h1>DB Queries</h1> 
	</div>
	<div class="tracy-database-controls">
		<div class="tracy-database-controls-header tracy-inner" style="max-height: 400px;">
			<table class="tracy-sortable">
				<thead>
					<tr>
						<th>Time</th>
						<th>SQL</th>
					</tr>
				</thead>
				<tbody>
EOT;
		if(!empty($this->database_connection->log())) {
			$split_values = preg_split("/\(([0-9\.]+)ms\)\s/", $this->database_connection->log(), -1, PREG_SPLIT_DELIM_CAPTURE);
			$query_results = [];
			$last_query_time = 0.0;
			foreach($split_values as $value) {
				if(empty($value)) {
					continue;
				}
				if(is_numeric($value) === true) {
					$last_query_time = (float) $value;
				} else {
					$query_results[] = [
						'time' => $last_query_time,
						'sql'  => $value
					];
				}
			}
			foreach($query_results as $query_result) {
				$sql 		 = $this->handleLongStrings(($query_result['sql'] ?? ''));
				$html       .= <<<EOT
						<tr>
							<td>{$query_result['time']}ms</td>
							<td>{$sql}</td>
						</tr>
EOT;
			}
		}
		$html .= <<<EOT
				</tbody>
			</table>
		</div>
	</div>
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
		$total_time       = 0;
		$long_query_count = 0;
		$query_count      = 0;
		if(!empty($this->database_connection->log())) {
			$split_values = preg_split("/\(([0-9\.]+)ms\)\s/", $this->database_connection->log(), -1, PREG_SPLIT_DELIM_CAPTURE);
			$query_results = [];
			$last_query_time = 0.0;
			foreach($split_values as $value) {
				if(empty($value)) {
					continue;
				}
				if(is_numeric($value) === true) {
					$last_query_time = (float) $value;
				} else {
					$query_results[] = [
						'time' => $last_query_time,
						'sql'  => $value
					];
				}
			}
			foreach($query_results as $query_result) {
				$total_time += $query_result['time'];
				if($query_result['time'] > 500) {
					++$long_query_count;
				}
				++$query_count;
			}
		}
		$long_query_html = '';
		if($long_query_count > 0) {
			$long_query_html = '<span class="text-danger fw-bold">'.$long_query_count.' long queries!</span>';
		}
		return <<<EOT
<span title="Database Queries">
	<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="darkTurquoise" class="bi bi-server" viewBox="0 0 16 16">
		<path d="M1.333 2.667C1.333 1.194 4.318 0 8 0s6.667 1.194 6.667 2.667V4c0 1.473-2.985 2.667-6.667 2.667S1.333 5.473 1.333 4V2.667z"/>
		<path d="M1.333 6.334v3C1.333 10.805 4.318 12 8 12s6.667-1.194 6.667-2.667V6.334a6.51 6.51 0 0 1-1.458.79C11.81 7.684 9.967 8 8 8c-1.966 0-3.809-.317-5.208-.876a6.508 6.508 0 0 1-1.458-.79z"/>
		<path d="M14.667 11.668a6.51 6.51 0 0 1-1.458.789c-1.4.56-3.242.876-5.21.876-1.966 0-3.809-.316-5.208-.876a6.51 6.51 0 0 1-1.458-.79v1.666C1.333 14.806 4.318 16 8 16s6.667-1.194 6.667-2.667v-1.665z"/>
	</svg>
	<span class="tracy-label">{$total_time}ms / {$query_count} {$long_query_html}</span>
</span>
EOT;
	}

}
