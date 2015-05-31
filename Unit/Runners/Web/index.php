<?php

ini_set('display_errors', '1');
error_reporting(E_ALL | E_STRICT);

//require_once ( realpath(dirname(__DIR__ . '/../../../../../../../../../')) . '/autoload.php' );
require_once ( __DIR__ . '/WebRunner.php' );
use Chinook\TestSuite\Unit\WebRunner;

class Error
{
    public $errorNumber;
    public $errorString;
    public $errorFile;
    public $errorLine;
    public $errorMessage;
}

function CustomErrorHandler ( $errno, $errstr, $errfile, $errline )
{
    if (! (error_reporting() & $errno) )
    {
        // This error code is not included in error_reporting
        return null;
    }

    switch ($errno) {
        case E_USER_ERROR:
            $error = new Error();
            $error->errorNumber = $errno;
            $error->errorString = $errstr;
            $error->errorFile = $errfile;
            $error->errorLine = $errfile;
            $error->errorMessage = "<strong>ERROR</strong> $errno :
        Fatal error on line $errline in file $errfile <br />
        $errstr";
            WebRunner::$errors[] = $error;
            break;

        case E_USER_WARNING:
            $error = new Error();
            $error->errorNumber = $errno;
            $error->errorString = $errstr;
            $error->errorFile = $errfile;
            $error->errorLine = $errfile;
            $error->errorMessage = "<strong>Warning</strong> $errno :
        Fatal error on line $errline in file $errfile <br />
        $errstr";
            WebRunner::$errors[] = $error;
            break;

        case E_USER_NOTICE:
            $error = new Error();
            $error->errorNumber = $errno;
            $error->errorString = $errstr;
            $error->errorFile = $errfile;
            $error->errorLine = $errfile;
            $error->errorMessage = "<strong>Notice</strong> $errno :
        Fatal error on line $errline in file $errfile <br />
        $errstr";
            WebRunner::$errors[] = $error;
            break;

        default:
            $error = new Error();
            $error->errorNumber = $errno;
            $error->errorString = $errstr;
            $error->errorFile = $errfile;
            $error->errorLine = $errfile;
            $error->errorMessage = "<strong>Error</strong> $errno :
        Fatal error on line $errline in file $errfile <br />
        $errstr";
            WebRunner::$errors[] = $error;
            break;
    }

    /* Don't execute PHP internal error handler */
    return true;
}

set_error_handler("CustomErrorHandler");

$now = microtime(true);
$run = new WebRunner ( );
$now = microtime(true) - $now;
echo "This test was run in $now seconds";

?>