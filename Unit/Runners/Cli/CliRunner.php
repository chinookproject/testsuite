<?php
namespace Chinook\TestSuite\Unit;

ini_set('display_errors', '1');
error_reporting(E_ALL | E_STRICT);

require_once ( 'Colors.php' );
require_once ( __DIR__ . '/../../UnitTestCase.php' );
require_once ( __DIR__ . '/../../UnitTestInvoker.php' );

class CliRunner extends UnitTestInvoker
{
    public $cases;
    public static $errors = array ( );
    
    public function __construct ( )
    {
        parent::__construct ( );
        
        $this->cases = $this->getAllTestCases ( );
        
		$this->addTestCases ( $this->cases );
		$this->executeTestMethods ( );
        
        $this->testCaseResults = TestResultCollection::getResults ( );
		
		$previousCase = '';
		$total = 0;
		$succeeded = 0;
		
		foreach ( $this->testCaseResults as $results )
        {
			foreach ( $results as $index => $result )
			{
				if ( $previousCase !== $result->testCase )
				{
					$previousCase = $result->testCase;
					echo "Case: " . $result->testCase . "\r\n";
				}
				
				if ( !$result->result )
				{
					echo "\r\n[Failed] " . $result->testMethod . "\r\n" . implode('->',$result->methodChain) . ' - ' . $result->message . "\r\n\r\n";
				}
			}
		
			$total += count ( $results );
			$tmp = $this->getSucceededResultCount ( $results );
			$succeeded += $tmp;
		}
		
		$failed = $total - $succeeded;
		echo "\r\n$succeeded assertions succeeded - $failed failed\r\n\r\n";
    }
    
    public function getSucceededResultCount ( array $testCaseResults )
    {
        $successCount = 0;
        foreach ( $testCaseResults as $result )
        {
            if ( $result->result === true )
            {
                $successCount++;
            }
        }
        
        return $successCount;
    }
}

?>