<?php
namespace Chinook\TestSuite\Unit;

ini_set('display_errors', '1');
error_reporting(E_ALL | E_STRICT);

class WebRunner extends UnitTestInvoker
{
    public $cases;
    public static $errors = array ( );
    
    public function __construct ( )
    {
        parent::__construct ( );
        
        $this->cases = $this->getAllTestCases ( );
        
        if ( $_SERVER['REQUEST_METHOD'] == 'POST' )
        {
            $this->createSelectedTests ( );
            $this->executeTestMethods ( );
        }
        else
        {
            $this->addTestCases ( $this->cases );
            $this->executeTestMethods ( );
        }
        
        $this->testCasseResults = TestResultCollection::getResults ( );
        
        require_once('WebUnitTestLoggerViews/result.html');
    }
    
    public function CreateSelectedTests ( )
    {
        if ( isset ( $_POST['testmethods'] ) )
        {
            foreach ( $_POST['testmethods'] as $testCase => $testMethods )
            {
                $this->createTestCase ( $testCase, $testMethods );
            }
        }
        else
        {
            foreach ( $_POST['testcases'] as $testCase )
            {
                $this->createTestCase ( $testCase, null );
            }
        }
    }
        
    public function getSucceededResultCount ( array $testCaseResults )
    {
        $successCount = 0;
        foreach ( $testCaseResults as $result )
        {
            if ( $result->Result === true )
            {
                $successCount++;
            }
        }
        
        return $successCount;
    }
}

?>