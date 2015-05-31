
require_once ( __DIR__ . DIRECTORY_SEPARATOR . 'Fixture.php' );
require_once ( __DIR__ . DIRECTORY_SEPARATOR . '../Unit/TestResult.php' );

require_once ( '{mock_file}' );

class MockedObject {mock_class}
{
    protected $fixtures = array ( );
    protected $testIndex = 0;
    protected $currentTestCase;
    protected $testResults = array ( );
    
    // Mock
    protected $methodName;
    protected $args;
    
    public function __destruct ( )
    {        
        foreach ( $this->fixtures as $key => $fixture )
        {
            $called = 0;
            $called = $fixture->called;
            
            if ( ($fixture->maximumCallCount !== null && $fixture->called > $fixture->maximumCallCount) )
            {
                $message = 'Expected '.$fixture->maximumCallCount.' call(s) to be made, but '.$called.' calls were made.';
                $this->testResults[$key]->message = $message;
                $this->testResults[$key]->result = false;
            }
            else if ( $fixture->minimumCallCount !== null && $fixture->called < $fixture->minimumCallCount )
            {
                $message = 'Expected '.$fixture->minimumCallCount.' call(s) to be made, but '.$called.' calls were made.';
                $this->testResults[$key]->Message = $message;
                $this->testResults[$key]->Result = false;
            }
            else if ( $fixture->minimumCallCount === null && $fixture->maximumCallCount === null )
            {
                // dont create a message, because there is nothing to assert
                unset ( $this->testResults[$key] );
            }
            else
            {
                // nothing
            }
        }
        
        foreach ( $this->testResults as $result )
        {
            TestResultCollection::addResult ( $result->testCase, $result );
        }
    }
    
    protected function resolveMatchingMethod ( $methodName, $args )
    {
        $matchingFixture = null;
        
        foreach ( $this->fixtures as $fixture )
        {
            if ( $fixture->method !== $methodName )
                continue;
            
            if ( empty ( $fixture->paramMatches ) || array_intersect ( $fixture->paramMatches, $args ) )
            {
                $matchingFixture = $fixture; 
                break;
            }
        }
        
        return $matchingFixture;
    }
    
    protected function &getLastFixture ( )
    {
        $lastIndex = count ( $this->fixtures ) -1;
        return $this->fixtures[$lastIndex];
    }
    
    protected function addResult ( $index, $message, $result )
    {
        $this->testResults[$index]->message = $message;
        $this->testResults[$index]->result = $result;
    }
    
    protected function addMethodChain ( $method )
    {
        $this->testResults[$this->testIndex-1]->methodChain[] = $method;
    }
    
    public function __call ( $methodName, $args )
    {        
        $matchingFixture = $this->resolveMatchingMethod ( $methodName, $args );
        
        if ( $matchingFixture !== null )
        {
            $this->methodName = $methodName;
            $this->args = $args;
            
            $matchingFixture->called++;
            return $matchingFixture->returns;
        }
    }
    
    protected function prepareAssertion ( )
    {
        $testResult = new Chinook\TestSuite\Unit\TestResult ( );
        
        $callers = debug_backtrace ( );
        
        // no assertions
        $testResult->testCase = basename($callers[0]['file'], '.php');
        $testResult->testMethod = $callers[1]['function'];
        
        // with assertions
        if ( strstr ( $testResult->testCase, 'Mock' ) )
        {
            $testResult->testCase = basename($callers[1]['file'], '.php');
            $testResult->testMethod = $callers[2]['function'];
        }
        
        $this->currentTestCase = $testResult->testCase;
        $this->testIndex++;
        
        $this->testResults[] = $testResult;
    }
    
    public function aCallTo ( $methodName, array $paramMatches = array() )
    {
        $this->prepareAssertion ( );
        
        $fixture = new Fixture();
        $fixture->method = $methodName;
        $fixture->paramMatches = $paramMatches;
        
        $this->fixtures[] = $fixture;
        
        $this->addMethodChain ( "aCallTo('$methodName')" );
        
        return $this;
    }
    
    public function returns ( $returns )
    {
        $fixture = $this->getLastFixture ( );
        $fixture->returns = $returns;
        
        $this->addMethodChain ( "returns('".print_r($returns,  true)."')" );
        
        return $this;
    }
    
    public function expectCallCount ( $amount )
    {
        $fixture = $this->getLastFixture ( );
        $fixture->minimumCallCount = $amount;
        $fixture->maximumCallCount = $amount;
        
        $this->addMethodChain ( "expectedCallCount($amount)" );
        
        return $this;
    }
    
    public function expectMinimumCallCount ( $amount )
    {
        $fixture = $this->getLastFixture ( );
        $fixture->minimumCallCount = $amount;
        
        $this->addMethodChain ( 'expectedMinimumCallCount' );
        
        return $this;
    }
    
    public function expectMaximumCallCount ( $amount )
    {
        $fixture = $this->getLastFixture ( );
        $fixture->maximumCallCount = $amount;
        
        $this->addMethodChain ( 'expectedMaximumCallCount' );
        
        return $this;
    }
    
    public function expectNever ( )
    {
        $fixture = $this->getLastFixture ( );
        $fixture->minimumCallCount = 0;
        $fixture->maximumCallCount = 0;
        
        $this->addMethodChain ( 'expectedNever' );
        
        return $this;
    }
    
    public function expectOnce ( )
    {
        $fixture = $this->getLastFixture ( );
        $fixture->minimumCallCount = 1;
        $fixture->maximumCallCount = 1;
        
        $this->addMethodChain ( 'expectedOnce' );
        
        return $this;
    }
}

