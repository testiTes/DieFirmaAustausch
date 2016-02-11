<?php

/**
 * Description of ProjektController
 *
 * @author Teilnehmer
 */
class ProjektController {

    public static function doAction($action, &$view, $id) {
        switch ($action) {

            /*
             * Showlist führt Methode getAll in Klasse Projekt aus
             * und liefert ein array mit objekten zurück.
             * dieses wird umgewandelt in ein Array, welches dann 
             * in der listeProjekt zu einem HTML Statement in den
             * #content div geladen wird.
             * 
             */

            case 'showList':
                $out = Projekt::getAll();
                $out = self::transform($out);
                break;

            /*
             *  $out Objekt; $id Integer
             *  Aus der Klasse Projekt wird das passende Projekt anhand der übegebenen ID geladen.
             *  Dafür wird die $id an dessen Funktion getById übegeben
             *  das übegebene Object wird in die $out reingeschrieben
             *  $out wird mit der eigenen Funktion transformUpdate bearbeitet
             *  Das heißt html gerechtes Bearbeitungsformular wird mit den Object Daten gefüllt
             *  
             */

            case 'showUpdate':
                $out = Projekt::getById($id);
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
             * $out wird als "neues" Projekt angelegt mit den Daten aus den Array $daten befüllt
             * $out wird an die Funktion update von Projekt geschickt und in die Datenbak geschrieben
             * $out wird mit Projekt Funktion getAll überschieben, sprich mit allen Projekten
             * $out wird wird mit der Funktion transform html gerecht überarbeitet
             * So dass man nun die Liste mit allen Projekten sieht.
             */

            case 'update' :
                $ProjektFiltered = filter_input(INPUT_POST, 'Projekt', FILTER_SANITIZE_MAGIC_QUOTES);
                $updateprojektidFiltered = filter_input(INPUT_POST, 'updateprojektid', FILTER_SANITIZE_NUMBER_INT);

                $out = new Projekt($ProjektFiltered, $updateprojektidFiltered);
                $out = Projekt::update($out);
                $out = Projekt::getAll();
                $out = self::transform($out);
                break;

            /*
             * die Daten aus den Inputfeldern werden mit sanitize bearbeitet um Cross-Site Scripting zu unterbinden
             * in $out wird ein neues Objekt von Projekt als Objekt zugewiesen
             * in Datenbank gespeichert
             * Später wird die Liste der Objekte der Klasse ausgegeben
             */

            case 'insert' :
                $projektFiltered = filter_input(INPUT_POST, 'projekt', FILTER_SANITIZE_MAGIC_QUOTES);

                $out = new Projekt($projektFiltered, NULL);
                $out = Projekt::insert($out);
                $out = Projekt::getAll();
                $out = self::transform($out);
                break;

            /* Übergabe des Primary Keys (über POST('id')
             * danach methodenaufruf (löschen) in der jeweiligen Klasse,
             * und seite neu laden bzw. liste anzeigen.
             */

            case 'delete' :
                $deleteprojektidFiltered = filter_input(INPUT_POST, 'deleteprojektid', FILTER_SANITIZE_NUMBER_INT);

                $out = $deleteprojektidFiltered;
                $out = Projekt::delete($out);
                $out = Projekt::getAll();
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
        foreach ($out as $projekt) {
            $returnOut[$i]['projektName'] = $projekt->getName();
            $returnOut[$i]['bearbeiten'] = HTML::buildButton('bearbeiten', $projekt->getId(), 'bearbeitenProjekt', 'bearbeiten');
            $returnOut[$i]['loeschen'] = HTML::buildButton('löschen', $projekt->getId(), 'loeschenProjekt', 'loeschen');
            $i++;
        }
        return $returnOut;
    }

    private static function transformUpdate($out = NULL) {
        $returnOut = [];
        $linkeSpalte = [];
        $rechteSpalte = [];

        for ($i = 0; $i < count(Projekt::getNames()); $i++) {
            array_push($linkeSpalte, Projekt::getNames()[$i]);
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
            array_push($rechteSpalte, HTML::buildInput('text', 'projekt', $dbWerte['name'], NULL, 'projekt'));
            array_push($rechteSpalte, HTML::buildButton('OK', 'ok', 'updateProjekt', 'OK'));
        } else {
            array_push($rechteSpalte, HTML::buildInput('text', 'projekt', '', NULL, 'projekt', NULL, 'Projekt'));
            array_push($rechteSpalte, HTML::buildButton('OK', 'ok', 'insertProjekt', 'OK'));
        }
        $returnOut = HTML::buildFormularTable($linkeSpalte, $rechteSpalte);
        return $returnOut;
    }

}
