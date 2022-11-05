<?php
declare(strict_types=1);

namespace n0nag0n;

use Base;
use Exception;
use n0nag0n\Extension\Database_Extension;
use n0nag0n\Extension\F3_Panel_Extension;
use n0nag0n\Extension\Request_Extension;
use n0nag0n\Extension\Session_Extension;
use Throwable;
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
		Debugger::getBar()->addPanel(new F3_Panel_Extension($f3));
		$database_variable_name = $extension_options['database_variable_name'] ?? 'DB';
		$database_object = $extension_options['database_object'] ?? null;
		if(($database_variable_name && $f3->exists($database_variable_name)) || ($database_object !== null && $database_object instanceof \DB\SQL)) {
			Debugger::getBar()->addPanel(new Database_Extension($f3, $database_variable_name, $database_object));
		}
		Debugger::getBar()->addPanel(new Session_Extension);
		Debugger::getBar()->addPanel(new Request_Extension);

		Debugger::getBlueScreen()->addPanel(function(?Throwable $e) use ($f3) {
			$F3_Panel_Extension = new F3_Panel_Extension($f3);
			$F3_Panel_Extension->setValueWidth(800);
			if($e instanceof Throwable && $e->getMessage()) {
				return [];
			}
			return [
				'tab' => 'F3 Hive',
				'panel' => $F3_Panel_Extension->getPanel(),
				'bottom' => true,
			];
		});
	}
} 
