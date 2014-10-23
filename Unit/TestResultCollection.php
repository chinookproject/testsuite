<?php
namespace Chinook\TestSuite\Unit;

class TestResultCollection
{
    private static $results = array ( );

    public static function addResult ( $testCaseName, TestResult $result )
    {
        self::$results[$testCaseName][] = $result;
    }

    public static function removeResult ( $testCaseName, $index )
    {
        unset ( self::$results[$testCaseName][$index] );
    }

    public static function &getResult ( $testCaseName, $index )
    {
        return self::$results[$testCaseName][$index];
    }

    public static function getResults ( )
    {
        return self::$results;
    }
}

?>