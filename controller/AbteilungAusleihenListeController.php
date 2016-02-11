<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of AbteilungAusleihenListeController
 *
 * @author Teilnehmer
 */
class AbteilungAusleihenListeController {
    public static function doAction($action, $view, $id) {
        switch ($action) {
            case 'showList':
                $out = AbteilungAusleihenListe::getAll();
//                echo 'mitte';
//                echo '<pre>';
//                print_r($out);
//                echo '</pre>';
                break;
            default:
                break;
        }
        return $out;
    }
}
