<?php
namespace Chinook\TestSuite\Unit;

class UnitTestCase
{
    protected $currentTestCase;
    protected $methodChain = array ( );
    protected $totalAssertions = 0;
    protected $assertObject;

    public function setUp ( ) { }
    public function tearDown ( ) { }
    public function setUpBeforeClass ( ) { }
    public function tearDownAfterClass ( ) { }

    public function __call ( $name, $arguments )
    {
        if ( $name === 'and' )
        {
            $this->addMethodChain ( 'and' );
            return $this;
        }

        return null;
    }

    protected function prepareAssertion ( )
    {
        $callers = debug_backtrace ( );
        $testResult = new TestResult ( );
        $testResult->testCase = basename($callers[1]['file'], '.php');
        $testResult->testMethod = $callers[2]['function'];
        $this->currentTestCase = $testResult->testCase;

        TestResultCollection::addResult ( $testResult->testCase, $testResult );

        $this->totalAssertions++;
    }

    protected function addResult ( $message, $result = false )
    {
        $testResult = TestResultCollection::getResult ( $this->currentTestCase, $this->totalAssertions-1 );
        $testResult->Message = $message;
        $testResult->Result = $result;
    }

    protected function addMethodChain ( $method )
    {
        $result = TestResultCollection::getResult ( $this->currentTestCase, $this->totalAssertions-1 );
        $result->MethodChain[] = $method;
    }

    public function assert ( $object )
    {
        $this->prepareAssertion ( );

        $this->assertObject = $object;
        return $this;
    }

    public function should ( )
    {
        $this->addMethodChain ( 'should' );
        return $this;
    }

    /**
     * Compares the first object with the second object
     */
    public function be ( $mixed )
    {

        if ( func_num_args ( ) > 0 )
        {
            if ( $this->assertObject !== $mixed )
            {
                if ( is_array ( $mixed ) && is_array ( $this->assertObject ) )
                {
                    $this->addResult ( 'Expected asserted object to have the exact same values in the array as was given, but there are differences', false );
                }
                else
                {
                    $this->addResult ( 'Expected asserted object to be ('.gettype($mixed).')[' . $mixed . '], but ('.gettype($this->assertObject).')[' . $this->assertObject . '] was given', false );
                }
            }
        }

        $this->addMethodChain ( "be($mixed)" );
        return $this;
    }

    public function notBe ( $mixed )
    {
        if ( func_num_args ( ) > 0 )
        {
            if ( $this->assertObject === $mixed )
            {
                $this->addResult ( 'Asserted object is ('.gettype($this->assertObject).')[' . $this->assertObject . '], expected not to be ('.gettype($mixed).')[' . $mixed . ']', false );
            }
        }

        $this->addMethodChain ( "notBe($mixed)" );
        return $this;
    }

    public function beAString ( )
    {
        if ( !is_string ( $this->assertObject ) )
        {
            $this->addResult ( 'Expected asserted object to be of type (string), but ('.gettype($this->assertObject).')[' . $this->assertObject . '] was given', false );
        }

        $this->addMethodChain ( 'beAString()' );
        return $this;
    }

    public function beAnObject ( )
    {
        if ( !is_object ( $this->assertObject ) )
        {
            $this->addResult ( 'Expected asserted object to be of type (object), but ('.gettype($this->assertObject).')[' . $this->assertObject . '] was given', false );
        }
        $this->addMethodChain ( 'beAnObject()' );
        return $this;
    }

    public function beAnArray ( )
    {
        if ( !is_array ( $this->assertObject ) )
        {
            $this->addResult ( 'Expected asserted object to be of type (array), but ('.gettype($this->assertObject).')[' . $this->assertObject . '] was given', false );
        }

        $this->addMethodChain ( 'beAnArray()' );
        return $this;
    }

    public function beAFloat ( )
    {
        if ( !is_float ( $this->assertObject ) )
        {
            $this->addResult ( 'Expected asserted object to be of type (float), but ('.gettype($this->assertObject).')[' . $this->assertObject . '] was given', false );
        }

        $this->addMethodChain ( 'beAFloat()' );
        return $this;
    }

    public function beAnInt ( )
    {
        if ( !is_int ( $this->assertObject ) )
        {
            $this->addResult ( 'Expected asserted object to be of type (int), but ('.gettype($this->assertObject).')[' . $this->assertObject . '] was given', false );
        }

        $this->addMethodChain ( 'beAnInt()' );
        return $this;
    }

    public function beTrue ( )
    {
        if ( $this->assertObject !== true )
        {
            $this->addResult ( 'Expected asserted object to be TRUE, but ('.gettype($this->assertObject).')[' . $this->assertObject . '] was given', false );
        }

        $this->addMethodChain ( 'beTrue()' );
        return $this;
    }

    public function beFalse ( )
    {
        if ( $this->assertObject !== false )
        {
            $this->addResult ( 'Expected asserted object to be FALSE, but ('.gettype($this->assertObject).')[' . $this->assertObject . '] was given', false );
        }

        $this->addMethodChain ( 'beFalse()' );
        return $this;
    }

    public function NotBeNull ( )
    {
        if ( $this->assertObject === null )
        {
            $this->addResult ( 'Expected asserted object not to be NULL, but ('.gettype($this->assertObject).')[' . $this->assertObject . '] was given', false );
        }

        $this->addMethodChain ( 'notBeNull()' );
        return $this;
    }

    public function beNull ( )
    {
        if ( $this->assertObject !== null )
        {
            $this->addResult ( 'Expected asserted object to be NULL, but ('.gettype($this->assertObject).')[' . print_r($this->assertObject, true) . '] was given', false );
        }

        $this->addMethodChain ( 'beNull()' );
        return $this;
    }

    public function beEmpty ( )
    {
        if ( $this->assertObject !== '' || !is_string ( $this->assertObject ) )
        {
            $this->addResult ( 'Expected asserted object to be an EMPTY string, but ('.gettype($this->assertObject).')[' . $this->assertObject . '] was given', false );
        }

        $this->addMethodChain ( 'beEmpty()' );
        return $this;
    }

    public function notBeEmpty ( )
    {
        if ( $this->assertObject === '' || !is_string ( $this->assertObject ) )
        {
            $this->addResult ( 'Expected asserted object not to be an EMPTY string, but ('.gettype($this->assertObject).')[' . $this->assertObject . '] was given', false );
        }

        $this->addMethodChain ( 'notBeEmpty()' );
        return $this;
    }

    public function HaveLength ( $length )
    {
        if ( strlen ( $this->assertObject ) !== $length )
        {
            $this->addResult ( 'Expected asserted object to have a length of ['.$length.'], but found a length of ['. strlen($this->assertObject) . ']', false );
        }

        $this->addMethodChain ( 'haveLength()' );
        return $this;
    }

    /**
     * A case insensitive string compare
     */
    public function beEquivalentTo ( $string )
    {
        if ( strcasecmp ( $this->assertObject, $string ) )
        {
            $this->addResult ( 'Expected asserted object to be [' . $string . '], but ('.gettype($this->assertObject).')[' . $this->assertObject . '] was given', false );
        }

        $this->addMethodChain ( "beEquivalentTo($string)" );
        return $this;
    }

    public function endWith ( $string )
    {
        if ( !preg_match ( '~'.preg_quote($string).'$~', $this->assertObject ) )
        {
            $this->addResult ( 'Expected asserted object to end with string [' . $string . '], but couldn\'t find match in given string: ('.gettype($this->assertObject).')[' . $this->assertObject . ']', false );
        }

        $this->addMethodChain ( "endWith($string)" );
        return $this;
    }

    public function endWithEquivalent ( $string )
    {
        if ( !preg_match ( '~'.preg_quote($string).'$~i', $this->assertObject ) )
        {
            $this->addResult ( 'Expected asserted object to end with string [' . $string . '], but couldn\'t find match in given string: ('.gettype($this->assertObject).')[' . $this->assertObject . ']', false );
        }

        $this->addMethodChain ( "endWithEquivalent($string)" );
        return $this;
    }

    /**
     * String, Array
     */
    public function contain ( $mixed )
    {
        if ( is_array ( $mixed ) && is_array ( $this->assertObject ) )
        {
            if ( !array_intersect ( $this->assertObject, $mixed ) )
            {
                $this->addResult ( 'Expected asserted object to contain values that exist in given array, but couldn\'t find any matching values', false );
            }
        }
        else if ( is_string ( $mixed ) && is_string ( $this->assertObject ) )
        {
            if ( strpos ( $this->assertObject, $mixed ) === false )
            {
                $this->addResult ( 'Expected asserted object to contain string [' . $mixed . '], but couldn\'t find a match in given string: [' . $this->assertObject . ']', false );
            }
        }
        else
        {
            $this->addResult ( 'Expected asserted object to contain what given object contains, but a type mismatch was found. AssertObject: ('.gettype($this->assertObject).'), given: ('.gettype($mixed).')', false );
        }

        $this->addMethodChain ( "contain($mixed)" );
        return $this;
    }

    /**
     * String, Array
     */
    public function notContain ( $mixed )
    {
        if ( is_array ( $mixed ) && is_array ( $this->assertObject ) )
        {
            if ( array_intersect ( $this->assertObject, $mixed ) )
            {
                $this->addResult ( 'Expected asserted object not to contain values that exist in given array, but found a match', false );
            }
        }
        else if ( is_string ( $mixed ) && is_string ( $this->assertObject ) )
        {
            if ( strpos ( $this->assertObject, $mixed ) !== false )
            {
                $this->addResult ( 'Expected asserted object not to contain string [' . $mixed . '], but found a match in given string: [' . $this->assertObject . ']', false );
            }
        }
        else
        {
            $this->addResult ( 'Expected asserted object to contain what given object contains, but a type mismatch was found. AssertObject: ('.gettype($this->assertObject).'), given: ('.gettype($mixed).')', false );
        }

        $this->addMethodChain ( "notContain($mixed)" );
        return $this;
    }

    public function containEquivalentOf ( $mixed )
    {
        if ( is_array ( $mixed ) && is_array ( $this->assertObject ) )
        {
            if ( !array_intersect ( array_map ( 'strtolower', $this->assertObject ), array_map ( 'strtolower', $mixed ) ) )
            {
                $this->addResult ( 'Expected asserted object not to contain values that exist in given array, but found a match', false );
            }
        }
        else if ( is_string ( $mixed ) && is_string ( $this->assertObject ) )
        {
            if ( stripos ( $this->assertObject, $mixed ) === false )
            {
                $this->addResult ( 'Expected asserted object to contain string [' . $mixed . '], but couldn\'t find a match in given string: [' . $this->assertObject . ']', false );
            }
        }
        else
        {
            $this->addResult ( 'Expected asserted object to contain what given object contains, but a type mismatch was found. AssertObject: ('.gettype($this->assertObject).'), given: ('.gettype($mixed).')', false );
        }

        $this->addMethodChain ( "containEquivalentOf($mixed)" );
        return $this;
    }

    public function notContainEquivalentOf ( $mixed )
    {
        if ( is_array ( $mixed ) && is_array ( $this->assertObject ) )
        {
            if ( array_intersect ( array_map ( 'strtolower', $this->assertObject ), array_map ( 'strtolower', $mixed ) ) )
            {
                $this->addResult ( 'Expected asserted object not to contain values that exist in given array, but found a match', false );
            }
        }
        else if ( is_string ( $mixed ) && is_string ( $this->assertObject ) )
        {
            if ( stripos ( $this->assertObject, $mixed ) !== false )
            {
                $this->addResult ( 'Expected asserted object not to contain string [' . $mixed . '], but found match in given string: ('.gettype($this->assertObject).')[' . $this->assertObject . ']', false );
            }
        }
        else
        {
            $this->addResult ( 'Expected asserted object to contain what given object contains, but a type mismatch was found. AssertObject: ('.gettype($this->assertObject).'), given: ('.gettype($mixed).')', false );
        }

        $this->addMethodChain ( "notContainEquivalentOf($mixed)" );
        return $this;
    }

    public function startWith ( $string )
    {
        if ( !preg_match ( '~^'.preg_quote($string).'~', $this->assertObject ) )
        {
            $this->addResult ( 'Expected asserted object to start with string [' . $string . '], but couldn\'t find match in given string: [' . $this->assertObject . ']', false );
        }

        $this->addMethodChain ( "startWith($string)" );
        return $this;
    }

    public function startWithEquivalent ( $string )
    {
        if ( !preg_match ( '~^'.preg_quote($string).'~i', $this->assertObject ) )
        {
            $this->addResult ( 'Expected asserted object to start with string [' . $string . '], but couldn\'t find match in given string: [' . $this->assertObject . ']', false );
        }

        $this->addMethodChain ( "startWithEquivalent($string)" );
        return $this;
    }

    public function beGreaterOrEqualTo ( $number )
    {
        if ( $this->assertObject < $number )
        {
            $this->addResult ( 'Expected asserted object to be greater than or equal to ['.$number.'], but found ('.gettype($this->assertObject).')[' . $this->assertObject . ']', false );
        }

        $this->addMethodChain ( "beGreaterOrEqualTo($number)" );
        return $this;
    }

    public function beGreaterThan ( $number )
    {
        if ( $this->assertObject <= $number )
        {
            $this->addResult ( 'Expected asserted object to be greater than ['.$number.'], but found ('.gettype($this->assertObject).')[' . $this->assertObject . ']', false );
        }

        $this->addMethodChain ( "beGreaterThan($number)" );
        return $this;
    }

    public function beLessOrEqualTo ( $number )
    {
        if ( $this->assertObject > $number )
        {
            $this->addResult ( 'Expected asserted object to be less than or equal to ['.$number.'], but found ('.gettype($this->assertObject).')[' . $this->assertObject . ']', false );
        }

        $this->addMethodChain ( "beLessOrEqualTo($number)" );
        return $this;
    }

    public function beLessThan ( $number )
    {
        if ( $this->assertObject >= $number )
        {
            $this->addResult ( 'Expected asserted object to be less than ['.$number.'], but found ('.gettype($this->assertObject).')[' . $this->assertObject . ']', false );
        }

        $this->addMethodChain ( "beLessThan($number)" );
        return $this;
    }

    public function bePositive ( )
    {
        if ( $this->assertObject <= 0 )
        {
            $this->addResult ( 'Expected asserted object to be a positive number, but ('.gettype($this->assertObject).')[' . $this->assertObject . '] was given', false );
        }

        $this->addMethodChain ( 'bePositive()' );
        return $this;
    }

    public function beNegative ( )
    {
        if ( $this->assertObject >= 0 )
        {
            $this->addResult ( 'Expected asserted object to be a negative number, but ('.gettype($this->assertObject).')[' . $this->assertObject . '] was given', false );
        }

        $this->addMethodChain ( 'beNegative()' );
        return $this;
    }

    public function beInRange ( $min, $max )
    {
        if ( $this->assertObject < $min || $this->assertObject > $max )
        {
            $this->addResult ( 'Expected asserted object to be in the range of min: '.$min.' and max: '.$max.', but ('.gettype($this->assertObject).')[' . $this->assertObject . '] was given' );
        }

        $this->addMethodChain ( 'beInRange()' );
        return $this;
    }



    public function beAfter ( $datetime )
    {
        if ( gettype ( $datetime ) === 'object' && gettype ( $this->assertObject ) === 'object' )
        {
            if ( $this->assertObject->getTimestamp ( ) <= $datetime->getTimestamp ( ) )
            {
                $this->addResult ( 'Expected asserted object to be after date ['.$datetime->format('Y-m-d H:i').'], but ('.gettype($this->assertObject).')[' . $this->assertObject->format('Y-m-d H:i') . '] was given' );
            }
        }
        else
        {
            $this->addResult ( 'Expected asserted object and given object to be of type DateTime, but a mismatch was found. AssetObject: ('.gettype($this->assertObject).') -> ('.gettype($datetime).')' );
        }

        $this->addMethodChain ( 'beAfter()' );
        return $this;
    }

    public function beBefore ( $datetime )
    {
        if ( gettype ( $datetime ) === 'object' && gettype ( $this->assertObject ) === 'object' )
        {
            if ( $this->assertObject->getTimestamp ( ) >= $datetime->getTimestamp ( ) )
            {
                $this->addResult ( 'Expected asserted object to be before date ['.$datetime->format('Y-m-d H:i').'], but ('.gettype($this->assertObject).')[' . $this->assertObject->format('Y-m-d H:i') . '] was given' );
            }
        }
        else
        {
            $this->addResult ( 'Expected asserted object and given object to be of type DateTime, but a mismatch was found. AssetObject: ('.gettype($this->assertObject).') -> ('.gettype($datetime).')' );
        }

        $this->addMethodChain ( 'beBefore()' );
        return $this;
    }

    public function beOnOrAfter ( $datetime )
    {
        if ( gettype ( $datetime ) === 'object' && gettype ( $this->assertObject ) === 'object' )
        {
            if ( $this->assertObject->getTimestamp ( ) < $datetime->getTimestamp ( ) )
            {
                $this->addResult ( 'Expected asserted object to be on or after date ['.$datetime->format('Y-m-d H:i').'], but ('.gettype($this->assertObject).')[' . $this->assertObject->format('Y-m-d H:i') . '] was given' );
            }
        }
        else
        {
            $this->addResult ( 'Expected asserted object and given object to be of type DateTime, but a mismatch was found. AssetObject: ('.gettype($this->assertObject).') -> ('.gettype($datetime).')' );
        }

        $this->addMethodChain ( 'beOnOrAfter()' );
        return $this;
    }

    public function beOnOrBefore ( $datetime )
    {
        if ( gettype ( $datetime ) === 'object' && gettype ( $this->assertObject ) === 'object' )
        {
            if ( $this->assertObject->getTimestamp ( ) > $datetime->getTimestamp ( ) )
            {
                $this->addResult ( 'Expected asserted object to be on or before date ['.$datetime->format('Y-m-d H:i').'], but ('.gettype($this->assertObject).')[' . $this->assertObject->format('Y-m-d H:i') . '] was given' );
            }
        }
        else
        {
            $this->addResult ( 'Expected asserted object and given object to be of type DateTime, but a mismatch was found. AssetObject: ('.gettype($this->assertObject).') -> ('.gettype($datetime).')' );
        }

        $this->addMethodChain ( 'beOnOrBefore()' );
        return $this;
    }

//    public function HaveDay ( )
//    { }
//    
//    public function HaveMonth ( )
//    { }
//    
//    public function HaveYear ( )
//    { }
//    
//    public function HaveHour ( )
//    { }
//    
//    public function HaveMinute ( )
//    { }
//    
//    public function HaveSecond ( )
//    { }


    public function notContainNull ( )
    {
        if ( is_array ( $this->assertObject ) )
        {
            if ( in_array ( null, $this->assertObject ) )
            {
                $this->addResult ( 'Expected asserted object not to contain NULL values in array, but found a match' );
            }
        }
        else
        {
            $this->addResult ( 'Expected asserted object to be of type (array), but ('.gettype($this->assertObject).') was found' );
        }

        $this->addMethodChain ( 'notContainNull()' );
        return $this;
    }

    private $exceptionMessage;
    public function shouldThrow ( $func )
    {
        $hasThrown = false;
        try
        {
            $func ( );
        }
        catch(\Exception $e)
        {
            $this->exceptionMessage = $e->getMessage();
            $hasThrown = true;
        }

        if ( !$hasThrown )
        {
            $this->addResult ( 'Expected to throw an exception, but nothing happend' );
        }

        $this->addMethodChain ( 'shouldThrow()' );
        return $this;
    }

    public function withMessage ( $message )
    {
        $messagePattern = str_replace ( '*', '(.*?)', $message );
        if ( !preg_match ( '~^' . $messagePattern . '$~', $this->exceptionMessage ) )
        {
            $this->addResult ( 'Expected exception to be thrown with message: "' . $message . '", but "' . $this->exceptionMessage . '" was thrown instead' );
        }

        $this->addMethodChain ( "withMessage($message)" );
        return $this;
    }

    public function shouldNotThrow ( $func )
    {
        try
        {
            $func ( );
        }
        catch(\Exception $e)
        {
            $this->addResult ( 'Expected not to throw an exception, but exception was thrown with message: ' . $e->getMessage() );
        }

        $this->addMethodChain ( 'shouldNotThrow()' );
        return $this;
    }
}

?>