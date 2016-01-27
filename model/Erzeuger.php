<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Erzeuger
 *
 * @author Teilnehmer
 */
class Erzeuger {
    public static function erzeuger(){
        $h1 = new Hersteller('Opel',1);
        $h2 = new Hersteller('Ford',2);
        $h3 = new Hersteller('Hyundai',3);
        
        $a1 = new Auto('Vectra', $h1, 'B-AU213', 1);
        $a2 = new Auto('Sigma', $h1, 'B-CD213', 2);
        $a3 = new Auto('Escort', $h2, 'B-QU23', 3);
        $a4 = new Auto('GT', $h2, 'B-TT13', 4);
        $a5 = new Auto('Tucson', $h3, 'B-TF14', 5);
        $a6 = new Auto('Santa Fe', $h3, 'B-SF130', 6);
        
        $p1 = new Projekt('Band Website', 1);
        $p2 = new Projekt('Arzt Homepage', 2);
        $p3 = new Projekt('Verleih App', 3);
        
        
        $abt1= new Abteilung ('IT',1);
        $abt2= new Abteilung ('Buchhaltung',2);
        $abt3= new Abteilung ('Rechtsabteilung',3);
        
        $mit1 = new Mitarbeiter('Petra', 'Petroleum', 'w', '1993-03-04', $abt2, 9, NULL, 1);
        $mit2 = new Mitarbeiter('Anton', 'Bolika', 'm', '1993-06-07', $abt3, 11, NULL, 2);
        $mit3 = new Mitarbeiter('Master', 'Bubu', 'm', '1995-01-07', $abt1, 8, NULL, 3);
        $mit4 = new Mitarbeiter('Nerf', 'Gun', 'm', '1987-01-07', $abt1, 5, $mit3, 4);
        $mit5 = new Mitarbeiter('Rüdiger', 'Richtig', 'm', '1985-01-07', $abt3, 5, $mit2, 5);
        $mit6 = new Mitarbeiter('Gudrun', 'Gerstig', 'w', '1984-01-10', $abt2, 5, $mit1, 6);
        
        
        $projMit1 = new ProjektMitarbeiter($p1, $mit1, '2016-01-02 08:00', '2016-01-02 17:00', 1);
        $projMit2 = new ProjektMitarbeiter($p2, $mit2, '2016-01-02 08:00', '2016-01-04 15:00', 2);
        $projMit3 = new ProjektMitarbeiter($p2, $mit3, '2016-01-02 08:05:00', '2016-01-03 16:23:00', 3);
        
        $aus1 = new Ausleihe($a1, $mit1, '2016-01-02 08:00:00', '2016-01-03 08:01:00', 1);
        $aus2 = new Ausleihe($a2, $mit2, '2016-01-15 08:00:00', '2016-01-16 16:00:00', 2);
        $aus3 = new Ausleihe($a3, $mit3, '2016-01-20 08:03:00', '2016-01-21 17:00:00', 3);
                
    }
}
