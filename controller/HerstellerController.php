<?php

/**
 * Description of HerstellerController
 *
 * @author Teilnehmer
 */
class HerstellerController {

    public static function doAction($action, &$view, $id) {
        switch ($action) {

            /*
             * Showlist führt Methode getAll in Klasse Hersteller aus
             * und liefert ein array mit objekten zurück.
             * dieses wird umgewandelt in ein Array, welches dann 
             * in der listeHersteller zu einem HTML Statement in den
             * #content div geladen wird.
             * 
             */

            case 'showList':
                $out = Hersteller::getAll();
                $out = self::transform($out);
                break;

            /*
             *  $out Objekt; $id Integer
             *  Aus der Klasse Hersteller wird der passende Hersteller anhand der übegebenen ID geladen.
             *  Dafür wird die $id an dessen Funktion getById übegeben
             *  das übegebene Object wird in die $out reingeschrieben
             *  $out wird mit der eigenen Funktion transformUpdate bearbeitet
             *  Das heißt html gerechtes Bearbeitungsformular wird mit den Object Daten gefüllt
             *  
             */

            case 'showUpdate':
                $out = Hersteller::getById($id);
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
             * $daten wird an die Json Function json_decode übergeben und in eine PHP-Variable kovertiert
             * $out wird als "neue" Hersteller angelegt mit den Daten aus den Array $daten befüllt
             * $out wird an die Funktion update von Hersteller geschickt und in die Datenbak geschrieben
             * $out wird mit Hersteller Funktion getAll überschieben, sprich mit allen Abteilungen
             * $out wird wird mit der Funktion transform html gerecht überarbeitet
             * So dass man nun die Liste allen Hersteller sieht.
             */

            case 'update' :
                $herstellerFiltered = filter_input(INPUT_POST, 'Hersteller', FILTER_SANITIZE_MAGIC_QUOTES);
                $updateherstelleridFiltered = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT);

                $out = new Hersteller($herstellerFiltered, $updateherstelleridFiltered);
                $out = Hersteller::update($out);
                $out = Hersteller::getAll();
                $out = self::transform($out);
                break;

            /*
             * die Daten aus den Inputfeldern werden mit sanitize bearbeitet um Cross-Site Scripting zu unterbinden
             * in $out wird ein neues Objekt von Hersteller als Objekt zugewiesen
             * in Datenbank gespeichert
             * Später wird die Liste der Objekte der Klasse ausgegeben
             */

            case 'insert' :
                $herstellerFiltered = filter_input(INPUT_POST, 'hersteller', FILTER_SANITIZE_MAGIC_QUOTES);

                $out = new Hersteller($herstellerFiltered, NULL);
                $out = Hersteller::insert($out);
                $out = Hersteller::getAll();
                $out = self::transform($out);
                break;

            /* Übergabe des Primary Keys (über POST('id')
             * danach methodenaufruf (löschen) in der jeweiligen Klasse,
             * und seite neu laden bzw. liste anzeigen.
             */

            case 'delete' :
                $deleteherstelleridFiltered = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT);

                $out = $deleteherstelleridFiltered;
                $out = Hersteller::delete($out);
                $out = Hersteller::getAll();
                $out = self::transform($out);
                break;

            default:
                break;
        }
        return $out;
    }

    private static function transform($out) {
        $herst; // hersteller array
        $i = 0;
        foreach ($out as $hersteller) {
            $herst[$i]['hersteller'] = $hersteller->getName();
            $herst[$i]['bearbeiten'] = HTML::buildButton('bearbeiten', $hersteller->getId(), 'bearbeitenHersteller', 'bearbeiten');
            $herst[$i]['loeschen'] = HTML::buildButton('löschen', $hersteller->getId(), 'loeschenHersteller', 'loeschen');
            $i++;
        }
        return $herst;
    }

    private static function transformUpdate($out = NULL) {
        $returnOut = [];
        $linkeSpalte = [];
        $rechteSpalte = [];

        for ($i = 0; $i < count(Hersteller::getNames()); $i++) {
            array_push($linkeSpalte, Hersteller::getNames()[$i]);
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
            $rechteSpalte[0] = HTML::buildInput('text', 'hersteller', $dbWerte['name'], NULL, 'name');
            array_push($rechteSpalte, HTML::buildButton('OK', 'ok', 'updateHersteller', 'OK'));
        } else {
            $rechteSpalte[0] = HTML::buildInput('text', 'hersteller', '', NULL, 'name', NULL, 'Hersteller');
            array_push($rechteSpalte, HTML::buildButton('OK', 'ok', 'insertHersteller', 'OK'));
        }
        $returnOut = HTML::buildFormularTable($linkeSpalte, $rechteSpalte);
        return $returnOut;
    }

}
