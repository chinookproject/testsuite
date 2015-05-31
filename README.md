Please note
===========
This readme is NOT up to date!

Chinook-TestSuite
=================

Chinook Unit Test &amp; Mocking Framework for PHP.

Create mocks and stubs on the fly with the Mock Framework and Unit test your code with the Unit Test framework. This all comes in one pakcage, ready and easy to be used!

Installation &amp; Configuration
============

1. Download the **"TestSuite"** folder and put it in any directory you like.
2. Go into the **TestSuite** folder and open the file **CFUnitTestConfig.php**.
3. Change the **$TestFolder** path to the folder where your unit tests will reside in.

Note: The **$TestFolder** path will start looking from the parent of the **TestSuite** folder.
So if your TestSuite folder is in the ROOT of your web server, it will start looking from the ROOT
directory. If your TestSuite folder is in a sub folder called "Tests" for example. It will then start looking in
the "Tests" folder.

**Example**
  - ROOT
    - TestSuite
    - UnitTests

Then **$TestFolder** must be "UnitTests"

---

  - ROOT
    - SubFolder1
      - TestSuite
    - UnitTests
      - UnitTest1.php

Then **$TestFolder** must be "../UnitTests"

Creating a Unit Test
====================

A Unit Test class (or Test Case) can have any name and must always extend from **CFUnitTestCase** and therfor 
this class must be included. It is important that the class name of the Unit Test is the same as the file name.

A test method MUST have **Test_** as prefix. All other methods will not be run by the Test Suite.

<pre>
class ExampleTestCase extends CFUnitTestCase
{
    public function Test_A_simple_assertion()
    {
        $this->Assert(5)->Should()->Be(5)->And()->BeGreaterThan(2);
    }
}
</pre>

Additionally a Test Case can also have one of the following methods:

<pre>
public function SetUp() - This is run before each test method is executed
public functin TearDown() - This is run at the end of each test method
public function SetUpBeforeClass() - This is run before any test method is executed
public function TearDownAfterClass() - This is run after all test methods are executed
</pre>

For a more detailed example (including Mocking) check the "Example" folder.

Fluent Assertions
==========

An assertion can be made in a fluent way. The following assertions are supported.

<pre>
// Mixed assertion
$this->assert($string)->Should()->be('something');
$this->assert($array)->Should()->be($someOtherArray);
$this->assert($bool)->Should()->notBe(true);

// Type checking
$this->assert($string)->Should()->beAString();
$this->assert($object)->Should()->beAnObject();
$this->assert($array)->Should()->beAnArray();
$this->assert($int)->Should()->beAnInt();
$this->assert($float)->Should()->beAFloat();
$this->assert($bool)->Should()->beTrue();
$this->assert($bool)->Should()->beFalse();
$this->assert($mixed)->Should()->NotBeNull();
$this->assert($mixed)->Should()->beNull()
$this->assert($string)->Should()->beEmpty();
$this->assert($string)->Should()->NotBeEmpty();

// String specific assertions
$this->assert($string)->should()->haveLength(5);
$this->assert($string)->should()->beEquivalentTo($someString); // Case insensitive compare
$this->assert($string)->should()->startsWith($someString); // Case sensitive compare
$this->assert($string)->should()->startsWithEquivalent($someString); // Case insensitive compare
$this->assert($string)->should()->endWith($someString); // Case sensitive compare
$this->assert($string)->should()->endWithEquivalent($someString); // Case insensitive compare
$this->assert($string)->should()->contain($someText);
$this->assert($string)->should()->notContain($someText);
$this->assert($string)->should()->containEquivalentOf($someString); // Case insensitive compare (also on array values)
$this->assert($string)->should()->notContainEquivalentOf($someString); // Case insensitive compare (also on array values)

// Array specific assertions
$this->assert($array)->should()->contain($someOtherArray); // On intersect = success
$this->assert($array)->should()->notContain($someOtherArray); // When not intersects = success
$this->assert($array)->should()->notContainNull();

// Number assertions
$this->assert($int)->should()->beGreaterOrEqualTo($number);
$this->assert($int)->should()->beGreaterThan($number);
$this->assert($int)->should()->beLessOrEqualTo($number);
$this->assert($int)->should()->beLessThan($number);
$this->assert($int)->should()->bePositive();
$this->assert($int)->should()->beNegative();
$this->assert($int)->should()->beInRange($min, $max); //min=1, max=2 and given=2 will result in success

// Date assertions
$this->assert($datetime)->should()->beAfter($someDatetime);
$this->assert($datetime)->should()->beBefore($someDatetime);
$this->assert($datetime)->should()->beOnOrAfter($someDatetime);
$this->assert($datetime)->should()->beOnOrBefore($someDatetime);

// Throwable assertions
$this->assert()->should()->shouldThrow($func); Give the method that should be executed as an anonymous function to this method.
$this->assert()->should()->shouldThrow($func)->WithMessage('Exact exception message');
$this->assert()->should()->shouldThrow($func)->WithMessage('* psrtial message'); // The asterisk acts as a wild card. Can be used at the beginning, end or both sides of the string
$this->assert()->should()->shouldNotThrow($func);


// Extending assertions with "And()"
$this->assert($string)->should()->beAString()->and()->HaveLength(5);
</pre>


Mocking
=======

When you want to mock an object then you must first include the class **CFMock/CFMock.php**. Now mocking will be
a breeze.

You create a mocked version of an object like this:

<pre>
$mock = new Mock::create( 'DummyClass' );
$mock->aCallTo('SomeMethod')->returns('some value');

// Or even namespaced classes can be loaded:
$mock = new Mock::create( '\Some\Namespace\DummyClass' );
</pre>

Calls can then be made on the **$mock** object.

The mock framework also comes with a few assertions.

<pre>
expectCallCount(2); // Expects that many calls to a certain method
expectMinimumCallCount(2); // Expects at least that many calls to a certain method
expectMaximumCallCount(2); // Expects a maximum of 2 calls to a method, less is fine as well
expectNever(); // A certain method should never be called
expectOnce(); // Only a single call is expected to be made to a certain method
</pre>

A typical setup for a mock test could be this:

<pre>
$mock = new Mock::create( 'DummyClass' );
$mock->aCallTo('SomeMethod')->returns('some value')->expectOnce();

$mock->someMethod('message'); // Will return the string: 'some value'
</pre>

This is obviously not a real world example, but it should illustrate the idea.

Run Unit Tests
==============


## WebRunner ##

When you have created your unit tests then these can easily be run from the browser. Simply browse to **"TestSuit/Unit/Runners/WebWebRunner.php"**

In there you can simply hit the "Run Tests" button or select the tests you want to run.


## Command line ##

You can also run tests through command line:

There are two ways to run tests:

1. TestSuite.phar "Path/to/testFolder/"
2. TestSuite.phar --bootstrap "location/to/your/bootstrap/file.php" "Path/to/testFolder/"

Notice that "--bootstrap" **MUST** come as the first argument. That command could be useful for example to load the "autoload.php" file that comes with composer. So you have access to all your classes in your Unit Tests.

Screenshot
==========

![Example test with selected tests](http://i.imgur.com/5lC8o2f.png)
