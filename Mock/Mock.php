<?php
namespace Chinook\TestSuite\Mock;

require_once ( __DIR__ . DIRECTORY_SEPARATOR . 'Fixture.php' );
require_once ( __DIR__ . DIRECTORY_SEPARATOR . '../Unit/TestResult.php' );

class Mock
{
    private static $mockCount = 0;
    
    public static function create ( $className, array $constructParams = array ( ) )
    {
        self::$mockCount++;
        $mockClass = 'MockedObject_' . self::$mockCount;
        
        $reflector = new \ReflectionClass ( $className );
        $classFile = $reflector->getFileName ( );

        if ( !$reflector->isInterface() )
            $interfaces = $reflector->getInterfaceNames ( );
        else
            $interfaces = array ( $className );

        $methods = $reflector->getMethods(\ReflectionMethod::IS_PUBLIC);

        if ( empty ( $constructParams ) && $reflector->hasMethod('__construct') )
        {
            $constructParamCount = $reflector->getMethod('__construct')->getParameters();
            foreach ( $constructParamCount as $param )
            {
                $paramValue = null;
                if($param->isArray())
                {
                    $type = 'array';
                }
                else
                {
                    $type = ($param->getClass() ? $param->getClass()->name : '' );
                    if ( $type == 'stdClass' )
                        $paramValue = new \stdClass();
                    else
                        $paramValue = null;
                }
                
                $paramStrings[] = $type . ' $'.$param->name;
                
                $constructParams[] = $paramValue;
            }
        }

        $methodStrings = '';
        foreach ( $methods as $method )
        {
            $parameters = $method->getParameters();
            $methodStrings .= self::createMethodString($method, $parameters);
        }

        $mockContent = file_get_contents ( __DIR__ . DIRECTORY_SEPARATOR . 'MockedObject.php' );
            
        $implements = ( empty($interfaces) ? '' : 'implements ' . implode(', ', $interfaces) );

        $mockContent = str_replace ( '{mock_file}', $classFile, $mockContent );
        $mockContent = str_replace ( '{mock_class}', $implements, $mockContent );
        $mockContent = str_replace ( 'MockedObject', $mockClass, $mockContent );
        $mockContent = preg_replace ( '~MockedObject(.*?){~s', '$0 '.$methodStrings, $mockContent );

        eval ( $mockContent );
        
        $reflectionMock = new \ReflectionClass($mockClass); 
        $myClassInstance = $reflectionMock->newInstanceArgs($constructParams);
        
        return $myClassInstance;
    }
    
    private static function createMethodString ( $method, $params )
    {        
        $paramStrings = array ( );
        foreach ( $params as $param )
        {
            if($param->isArray())
            {
                $type = 'array';
            }
            else
            {
                $type = ($param->getClass() ? $param->getClass()->name : '' );
            }
            
            $paramStrings[] = $type . ' $'.$param->name;
        }
        
        $method = 'public function '.$method->name.' ( '.implode(', ', $paramStrings).' ) { return $this->__call(\''.$method->name.'\', array() ); }  ';
        return $method;
    }
}

?>