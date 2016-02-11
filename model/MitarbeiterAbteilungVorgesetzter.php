<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of MitarbeiterAbteilungVorgesetzter
 *
 * @author Teilnehmer
 */
class MitarbeiterAbteilungVorgesetzter {
    public static function getAll() {
        
        $pdo = DbConnect::connect();
        $sql = "SELECT mit.vorname vorname, mit.nachname nachname, abt.name abteilung, CONCAT(vor.vorname, ' ', vor.nachname) vorgesetzter
                FROM abteilung abt
                LEFT JOIN mitarbeiter mit ON mit.abteilung_id=abt.id
                LEFT JOIN mitarbeiter vor ON mit.vorgesetzter_id=vor.id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);        
        return $rows;
    }
}
