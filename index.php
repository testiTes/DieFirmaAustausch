<?php
define('DEV','TRUE');
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

if ($ajax == 'false') {
    include './view/frmHaupt.php';
}
?>
