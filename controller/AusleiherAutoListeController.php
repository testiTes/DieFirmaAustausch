<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of AusleiherAutoListeController
 *
 * @author Teilnehmer
 */
class AusleiherAutoListeController {
    public static function doAction($action, $view, $id) {
        switch ($action) {
            case 'showList':
                $out = AusleiherAutoListe::getAll();
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

