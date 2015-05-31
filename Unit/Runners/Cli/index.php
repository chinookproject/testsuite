<?php

ini_set('display_errors', '1');
error_reporting(E_ALL | E_STRICT);

//require_once ( realpath(dirname(__DIR__ . '/../../../../../../../../../')) . '/autoload.php' );
require_once ( __DIR__ . '/CliRunner.php' );
use Chinook\TestSuite\Unit\CliRunner;

$now = microtime(true);
$run = new CliRunner ( );
$now = microtime(true) - $now;
echo "This test was run in $now seconds";

?>