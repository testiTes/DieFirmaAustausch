<?php

/**
 * Description of AbteilungController
 *
 * @author Teilnehmer
 */
class AbteilungController {

    public static function doAction($action, &$view, $id) {
        switch ($action) {

            /*
             * rufe klasse Abteilung mit der methode getAll auf,
             * 
             * liefert Array zurück
             * 
             * Array
              (
              [1] => Abteilung Object
              (
              [id:Abteilung:private] => 1
              [name:Abteilung:private] => IT
              )

              [2] => Abteilung Object
              (
              [id:Abteilung:private] => 2
              [name:Abteilung:private] => Buchhaltung
              )
              [3] => Abteilung Object
              (
              [id:Abteilung:private] => 3
              [name:Abteilung:private] => Rechtsabteilung 2
              )
              Übergabe des Arrays an Funktion Transform
             * 
             * Transform baut daraus ein HTML gerüst und gibt 
             * dieses an AbteilungListe zurück (an echo).
             */

            case 'showList':
                $out = Abteilung::getAll();
                $out = self::transform($out);
                break;

            /*
             *  $out Objekt; $id Integer
             *  Aus der Klasse Abteilung wird die passende Abteilung anhand der übegebenen ID geladen.
             *  Dafür wird die $id an dessen Funktion getById übegeben
             *  das übegebene Object wird in die $out reingeschrieben
             *  $out wird mit der eigenen Funktion transformUpdate bearbeitet
             *  Das heißt html gerechtes Bearbeitungsformular wird mit den Object Daten gefüllt
             *  
             */

            case 'showUpdate':
                $out = Abteilung::getById($id);
                $out = self::transformUpdate($out);
                break;

            /*
             * Die Methode wird aufgerufen
             * die fertig erstellten daten werden an §out als string übergeben
             */

            case 'showInsert':
                $out = self::transformUpdate();
                break;

            /*
             * $daten = Array der vom User eigegeben Daten; $out = Objekt
             * $daten wird an die Json Function json_decode übergeben und in eine PHP-Variable konvertiert
             * $out wird als "neue" Abteilung angelegt mit den Daten aus den Array $daten befüllt
             * $out wird an die Funktion update von Projekt geschickt und in die Datenbak geschrieben
             * $out wird mit Projekt Funktion getAll überschieben, sprich mit allen Projekten
             * $out wird wird mit der Funktion transform html gerecht überarbeitet
             * So dass man nun die Liste mit allen Projekten sieht.
             */

            case 'update':
                $abteilungFiltered = filter_input(INPUT_POST, 'Abteilung', FILTER_SANITIZE_MAGIC_QUOTES);
                $updateabteilungidFiltered = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT);

                $out = new Abteilung($abteilungFiltered, $updateabteilungidFiltered);
                $out = Abteilung::update($out);
                $out = Abteilung::getAll();
                $out = self::transform($out);
                break;

            /*
             * die Daten aus den Inputfeldern werden mit sanitize bearbeitet um Cross-Site Scripting zu unterbinden
             * in $out wird ein neues Objekt von Abteilung als Objekt zugewiesen
             * in Datenbank gespeichert
             * Später wird die Liste der Objekte der Klasse ausgegeben
             */

            case 'insert':
                $abteilungFiltered = filter_input(INPUT_POST, 'abteilung', FILTER_SANITIZE_MAGIC_QUOTES);

                $out = new Abteilung($abteilungFiltered, NULL);
                $out = Abteilung::insert($out);
                $out = Abteilung::getAll();
                $out = self::transform($out);
                break;

            /*
             * Übergabe des Primary Keys (über POST('id')
             * 
             * Array
              (
              [ajax] => true
              [action] => delete
              [area] => Abteilung
              [view] => listeAbteilung
              [id] => 13
              )
             * 
             * , danach methodenaufruf (löschen) in der jeweiligen Klasse,
             * und seite neu laden bzw. liste anzeigen.
             * 
             * 
             */

            case 'delete':
                $deleteabteilungidFiltered = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_MAGIC_QUOTES);

                $out = $deleteabteilungidFiltered;
                $out = Abteilung::delete($out);
                $out = Abteilung::getAll();
                $out = self::transform($out);
                break;

            default:
                break;
        }
        return $out;
    }

    private static function transform($out) {
        $returnOut;
        $i = 0;
        foreach ($out as $abteilung) {
            $returnOut[$i]['abteilungName'] = $abteilung->getName();
            $returnOut[$i]['bearbeiten'] = HTML::buildButton('bearbeiten', $abteilung->getId(), 'bearbeitenAbteilung', 'bearbeiten');
            $returnOut[$i]['loeschen'] = HTML::buildButton('löschen', $abteilung->getId(), 'loeschenAbteilung', 'loeschen');
            $i++;
        }
        return $returnOut;
    }

    private static function transformUpdate($out = NULL) {
        $returnOut = [];
        $linkeSpalte = [];
        $rechteSpalte = [];

        for ($i = 0; $i < count(Abteilung::getNames()); $i++) {
            array_push($linkeSpalte, Abteilung::getNames()[$i]);
        }

        if ($out !== NULL) {
            array_push($linkeSpalte, HTML::buildInput('hidden', 'id', $out->getId()));
        } else {
            array_push($linkeSpalte, '');
        }

        if ($out !== NULL) {
            $dbWerte = json_decode(json_encode($out), true);
        }

        // überführe $dbWerte in rechte Spalte
        if ($out !== NULL) {
            array_push($rechteSpalte, HTML::buildInput('text', 'name', $dbWerte['name'], NULL, 'abteilung'));
            array_push($rechteSpalte, HTML::buildButton('OK', 'ok', 'updateAbteilung', 'OK'));
        } else {
            array_push($rechteSpalte, HTML::buildInput('text', 'name', '', NULL, 'abteilung', NULL, 'Abteilung'));
            array_push($rechteSpalte, HTML::buildButton('OK', 'ok', 'insertAbteilung', 'OK'));
        }
        $returnOut = HTML::buildFormularTable($linkeSpalte, $rechteSpalte);
        return $returnOut;
    }

}
