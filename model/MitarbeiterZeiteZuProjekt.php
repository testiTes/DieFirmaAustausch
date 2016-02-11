<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of MitarbeiterZeiteZuProjekt
 *
 * @author Teilnehmer
 */
class MitarbeiterZeiteZuProjekt {
    public static function getAll() {
        
        $pdo = DbConnect::connect();
        $sql = "SELECT projekt.name Projektname, CONCAT(mitarbeiter.vorname,' ', mitarbeiter.nachname) Mitarbeiter, HOUR(TIMEDIFF(projektmitarbeiter.von, projektmitarbeiter.bis)) Stunden 
                FROM projekt, projektmitarbeiter, mitarbeiter 
                WHERE projektmitarbeiter.projekt_id=projekt.id AND mitarbeiter.id = projektmitarbeiter.mitarbeiter_id;";
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
