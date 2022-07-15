Fat-Free Tracy Extensions
------

This is a set of extensions to make working with Fat-Free a little richer.

- F3 - Analyze all hive variables.
- Database - Analyze all queries that have run on the page
- Request - Analyze all `$_SERVER` variables and examine all global payloads (`$_GET`, `$_POST`, `$_FILES`)
- Session - Analyze all `$_SESSION_` variables

Installation
-------
Run `composer require n0nag0n/fatfree-tracy-devtools --dev` and you're on your way!

Configuration
-------
There is very little configuration you need to do to get this started. You will need to initiate the Tracy debugger prior to using this [https://tracy.nette.org/en/guide](https://tracy.nette.org/en/guide):

```php
<?php

use Tracy\Debugger;
use n0nag0n\Tracy_Extension_Loader

// bootstrap code
$f3 = Base::instance();

Debugger::enable();
// You may need to specify your environment with Debugger::enable(Debugger::DEVELOPMENT)

// not required
$extension_options = [
	// this is the name of the hive variable that's holding your
	// database connection.
	// Ex: $f3->DB, you would put 'DB' (default)
	//     $f3->database, you would put 'database'
	'database_variable_name' => 'DB'
];
new Tracy_Extension_Loader($f3, $extension_options);

// more code

$f3->run();