<?php

include './config.php';
include './model/Autoloader.php';

// benötigte Klassen nachladen
spl_autoload_register(function ($class) {
    Autoloader::load($class);
});

$action = isset($_POST['action']) ? $_POST['action'] : 'standard';
$area = isset($_POST['area']) ? $_POST['area'] : 'standard';
$view = isset($_POST['view']) ? $_POST['view'] : 'standard';
$id = isset($_POST['id']) ? $_POST['id'] : '0';

try {
    $out = BaseController::load($action, $view, $area, $id);
} catch (Exception $exc) {
    $view = 'error';

    //Fehler in Datei schreiben
    file_put_contents('logger' . DIRECTORY_SEPARATOR . 'phplogger.txt', date(DateTime::ATOM) . "\n" . $exc->getTraceAsString(), FILE_APPEND);
}

include 'view' . DIRECTORY_SEPARATOR . $view . '.php';


$ajax = isset($_POST['ajax']) ? $_POST['ajax'] : 'false';
//$btnViewLoader = isset($_POST['btnViewLoader']) ? $_POST['btnViewLoader'] : '';
$menuViewLoader = isset($_POST['menuViewLoader']) ? $_POST['menuViewLoader'] : '';

//if ($ajax == 'false') {
//    include './view/frmHaupt.php';
//}
//
//if ($menuViewLoader == 'menuHome') {
//    include './view/frmHaupt.php';
//} elseif ($menuViewLoader == 'menuMitarbeiterAnzeige') {
//    include './view/listeMitarbeiter.php';
//} elseif ($menuViewLoader == 'menuMitarbeiterNeuAnlegen') {
//    include './view/formularMitarbeiter.php';
//} elseif ($menuViewLoader == 'menuAbteilungAnzeigen') {
//    
//} elseif ($menuViewLoader == 'menuAbteilungNeuAnlegen') {
//    
//} elseif ($menuViewLoader == 'menuFuhrparkAnzeigen') {
//    
//} elseif ($menuViewLoader == 'menuFuhrparkAusleihen') {
//    
//} elseif ($menuViewLoader == 'menuFuhrparkNeuAnlegen') {
//    
//} elseif ($menuViewLoader == 'menuProjekteAnzeigen') {
//    
//} elseif ($menuViewLoader == 'menuProjekteNeuAnlegen') {
//    
//} elseif ($menuViewLoader == 'menuMitarbeiterToProjektAnzeigen') {
//    
//} elseif ($menuViewLoader == 'menuMitarbeiterToProjektNeuAnlegen') {
//    
//} elseif ($menuViewLoader == 'menuKontakt') {
//    
//}
?>
