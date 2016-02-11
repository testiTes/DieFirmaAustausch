<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of AbteilungAusleihenListe
 *
 * @author Teilnehmer
 */
class AbteilungAusleihenListe {
    public static function getAll() {
        
        $pdo = DbConnect::connect();
        $sql = "SELECT abt.name Abteilung, CONCAT(mit.vorname, ' ', mit.nachname) Ausleiher, aus.von Von, aus.bis Bis
                FROM abteilung abt, mitarbeiter mit, ausleihe aus
                WHERE mit.abteilung_id=abt.id AND aus.mitarbeiter_id=mit.id;";
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
