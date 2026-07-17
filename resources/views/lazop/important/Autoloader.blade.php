<?php

class Autoloader{
  
  /**
     * Autoloade Class in SDK.
     * PS: only load SDK class
     * @param string $class class name
     * @return void
     */

    public static function autoload($class) {

        $name = $class;
        if(false !== strpos($name,'\\')){
          $name = strstr($class, '\\', true);
        }

        $filename = asset('resources/views/lazop/'.$name.'.php');

            include $filename;
            return;
    }
    
}

spl_autoload_register('Autoloader::autoload');
?>