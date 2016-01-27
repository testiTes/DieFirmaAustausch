<?php

class Autoloader {
   public static function load($class) {
       $fileName=$class . '.php';
       // in Array die möglichen Pfade für Klassen eintragen
       $pathArr = ['model','controller','interface','html'];
       $classFound=FALSE;
       foreach($pathArr as $path) {
           if(file_exists($path . DIRECTORY_SEPARATOR . $fileName)) {
               include $path . DIRECTORY_SEPARATOR . $fileName;
               $classFound=TRUE;
               break;
           }
       }
       if ($classFound === FALSE) {
           throw new Exception("class $class not found in Autoloader.");
       }
       
   }
}
