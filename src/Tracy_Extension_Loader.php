<?php
declare(strict_types=1);

namespace n0nag0n;

use Base;
use Exception;
use n0nag0n\Extension\Database_Extension;
use n0nag0n\Extension\F3_Extension;
use n0nag0n\Extension\Request_Extension;
use n0nag0n\Extension\Session_Extension;
use Tracy\Debugger;

class Tracy_Extension_Loader {

	public function __construct(Base $f3, array $extension_options = []) {
		if(Debugger::isEnabled() === false) {
			throw new Exception('You need to enable Tracy\Debugger before using this extension!');
		}

		$f3->set('ONERROR', [Debugger::class, 'errorHandler']);

		$this->loadExtensions($f3, $extension_options);
	}

	protected function loadExtensions(Base $f3, array $extension_options = []): void {
		Debugger::getBar()->addPanel(new F3_Extension($f3));
		Debugger::getBar()->addPanel(new Database_Extension(($extension_options['database_variable_name'] ?? 'DB')));
		Debugger::getBar()->addPanel(new Request_Extension);
		Debugger::getBar()->addPanel(new Session_Extension);
	}
} 