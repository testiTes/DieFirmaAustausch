<?php
/**
 * erstellt ein Array für DropDown-Menu:
 * @example 
 * 
 * $objectArr[1]['value'] = 1;
 * $objectArr[1]['label'] = 'angezeigte Auswahl beim Dropdown-Menu';
 *   falls Auswahl vorausgewählt ist
 * $objectArray[1]['selected'] = ' selected'
 * mit 1 als PK
 */



class Option {
    
    /**
     * 
     * @param string $className Klassen mit getId(),getName() bzw. getDropName() und getAll()
     * @param int $selected PK der Vorauswahl
     * @param boolean $zeroOption, wenn ein Leerfeld an oberster Stelle ausgeben werden soll
     * @return Array (siehe oben)
     * @throws Exception
     */
    public static function buildOptions($className, $selected = NULL, $zeroOption = NULL) {
     $objectArr = []; // für Rückgabe
     // check: hat class $className die Methoden getId() und getName()
     // (!in_array( prüft ob alle Methoden in get_class_methods($classname) = getId vorhanden ist)
     if (!in_array('getId', get_class_methods($className))) {
         throw new Exception('Methode getId() fehlt in Parameter zu Option::buildOptions $classname: mit Wert ' . $className);
     }
  
     if (!in_array('getName', get_class_methods($className)) && !in_array('getDropName', get_class_methods($className))) {
         throw new Exception('Methode getName()bzw. getDropName fehlt in Parameter zu Option::buildOptions $classname: mit Wert' . $className);
     }
     if($selected !== NULL) {
         //check: $selected ist Zahl, mache zu int
         if (is_numeric($selected)) {
             $selected= (int)$selected;
         } else {
             throw new Exception('$selected enthält keine ganze Zahl(PK): '. $selected);
         }
     }
     //falls Auswahl keine Pflicht ist
     if ($zeroOption) {
         $objectArr[0]=['value' => 0, 'label' => ''];
     }
     
     $objects = $className::getAll();
     foreach ($objects as $o) {
         if (in_array('getDropName', get_class_methods($className))) {
             $objectArr[$o->getId()] = ['value' => $o->getId(), 'label' => $o->getDropName()];
         } else {
         $objectArr[$o->getId()] = ['value' => $o->getId(), 'label' => $o->getName()];
         }
         //// alternativ
         // $objectArr[$o->getId()]['value']=$o->getId(); // zeile 54 hat dieselbe Funktion 
         // $objectArr[$o->getId()]['label']=$o->getName(); // wie zeile 57 und 58 zusammen
         if ($selected === $o->getId()) {
             $objectArr[$o->getId()]['selected'] = ' selected';
         }
     }
     return $objectArr;
  }
}