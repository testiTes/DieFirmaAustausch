<?php
class MitarbeiterAbteilungVorgesetzterController {

    public static function doAction($action, &$view, $id) {
        switch ($action) {
            
            case 'showList':
                $out = MitarbeiterAbteilungVorgesetzter::getAll();
                break;
            
            default:
                break;
        }
        return $out;
    }

}
