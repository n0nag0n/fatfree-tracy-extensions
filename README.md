Fat-Free Tracy Extensions
------

This is a set of extensions to make working with Fat-Free a little richer.

- F3 - Analyze all hive variables.
- Database - Analyze all queries that have run on the page
- Request - Analyze all `$_SERVER` variables and examine all global payloads (`$_GET`, `$_POST`, `$_FILES`)
- Session - Analyze all `$_SESSION` variables

Installation
-------
Run `composer require n0nag0n/fatfree-tracy-extensions --dev` and you're on your way!

Configuration
-------
There is very little configuration you need to do to get this started. You will need to initiate the Tracy debugger prior to using this [https://tracy.nette.org/en/guide](https://tracy.nette.org/en/guide):

```php
<?php

use Tracy\Debugger;
use n0nag0n\Tracy_Extension_Loader;

// bootstrap code
$f3 = Base::instance();

Debugger::enable();
// You may need to specify your environment with Debugger::enable(Debugger::DEVELOPMENT)

// Database query profiler (not required)
// Create DB connection
// Variant 1
$f3->set('DB', new DB\SQL('mysql:host=localhost;port=3306;dbname=database', 'username', 'password'));
// OR variant 2
$f3->set('AnyVariableName', new DB\SQL('mysql:host=localhost;port=3306;dbname=database', 'username', 'password'));
// OR variant 3
$my_db_connection = new DB\SQL('mysql:host=localhost;port=3306;dbname=database', 'username', 'password');

$extension_options = [
	// Variant 2: Specify the name of the variable F3 with database connection
	'database_variable_name' => 'AnyVariableName', //It will mean: $f3->get('AnyVariableName')
	// OR variant 3: pass the database connection object
	'database_object' => $my_db_connection
];
new Tracy_Extension_Loader($f3, $extension_options);

// If you have no database connection or your connection is written in $f3->set('DB') (variant 1),
// you don't need to create $extension_options because the name 'DB' is used by default, simple:
// new Tracy_Extension_Loader($f3);

// more code

$f3->run();
