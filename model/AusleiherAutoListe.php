<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of AusleiherAutoListe
 *
 * @author Teilnehmer
 */
class AusleiherAutoListe {

    public static function getAll() {
        
        $pdo = DbConnect::connect();
        $sql = "SELECT CONCAT(her.name, ' ', a.name, ' ', a.kennzeichen) Fahrzeug, CONCAT(mit.vorname, ' ', mit.nachname) Ausleiher, aus.von Von, aus.bis Bis
                FROM auto a, hersteller her, mitarbeiter mit, ausleihe aus
                WHERE aus.auto_id=a.id AND a.hersteller_id=her.id AND aus.mitarbeiter_id=mit.id;";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
//        echo '<pre>';
//        print_r($rows);
//        echo '</pre>';
//        die();
//        echo 'Start';


        return $rows;
    }

}
