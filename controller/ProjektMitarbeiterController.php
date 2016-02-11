<?php

/**
 * Description of ProjektMitarbeiterController
 *
 * @author Teilnehmer
 */
class ProjektMitarbeiterController {

    public static function doAction($action, &$view, $id) {
        switch ($action) {

            /*
             * Showlist führt Methode getAll in Klasse ProjektMitarbeiter aus
             * und liefert ein array mit objekten zurück.
             * dieses wird umgewandelt in ein Array, welches dann 
             * in der listeProjektMitarbetier zu einem HTML Statement in den
             * #content div geladen wird.
             * 
             */

            case 'showList':
                $out = ProjektMitarbeiter::getAll();
                $out = self::transform($out);
                break;

            /*
             *  $out Objekt; $id Integer
             *  Aus der Klasse ProjektMitarbeiter wird der passende ProjektMitarbeiter anhand der übegebenen ID geladen.
             *  Dafür wird die $id an dessen Funktion getById übegeben
             *  das übegebene Object wird in die $out reingeschrieben
             *  $out wird mit der eigenen Funktion transformUpdate bearbeitet
             *  Das heißt html gerechtes Bearbeitungsformular wird mit den Object Daten gefüllt
             *  
             */

            case 'showUpdate':
                $out = ProjektMitarbeiter::getById($id);
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
             * $out wird als "neuer" ProjektMitarbeiter angelegt mit den Daten aus den Array $daten befüllt
             * $out wird an die Funktion update von ProjektMitarbeiter geschickt und in die Datenbak geschrieben
             * $out wird mit ProjektMitarbeiter Funktion getAll überschieben, sprich mit allen ProjektMitarbeitern
             * $out wird wird mit der Funktion transform html gerecht überarbeitet
             * So dass man nun die Liste allen ProjektMitarbeiter sieht.
             */

            case 'update' :
                $ProjektFiltered = filter_input(INPUT_POST, 'Projekt', FILTER_SANITIZE_MAGIC_QUOTES);
                $mitarbeiterFiltered = filter_input(INPUT_POST, 'mitarbeiter', FILTER_SANITIZE_MAGIC_QUOTES);
                $vonFiltered = filter_input(INPUT_POST, 'von', FILTER_SANITIZE_MAGIC_QUOTES);
                $bisFiltered = filter_input(INPUT_POST, 'bis', FILTER_SANITIZE_MAGIC_QUOTES);
                $updatemitarbeiterprojektidFiltered = filter_input(INPUT_POST, 'updatemitarbeiterprojektid', FILTER_SANITIZE_NUMBER_INT);

                $out = new ProjektMitarbeiter(Projekt::getById($ProjektFiltered), Mitarbeiter::getById($_POST['mitarbeiter']), HTML::dateAndTimeToDateTime($vonFiltered), HTML::dateAndTimeToDateTime($bisFiltered), $updatemitarbeiterprojektidFiltered);
                $out = ProjektMitarbeiter::update($out);
                $out = ProjektMitarbeiter::getAll();
                $out = self::transform($out);
                break;

            /*
             * die Daten aus den Inputfeldern werden mit sanitize bearbeitet um Cross-Site Scripting zu unterbinden
             * in $out wird ein neues Objekt von ProjektMitarbeiter als Objekt zugewiesen
             * in Datenbank gespeichert
             * Später wird die Liste der Objekte der Klasse ausgegeben
             */

            case 'insert' :
                $projektFiltered = filter_input(INPUT_POST, 'projekt', FILTER_SANITIZE_MAGIC_QUOTES);
                $mitarbeiterFiltered = filter_input(INPUT_POST, 'mitarbeiter', FILTER_SANITIZE_MAGIC_QUOTES);
                $vonFiltered = filter_input(INPUT_POST, 'von', FILTER_SANITIZE_MAGIC_QUOTES);
                $bisFiltered = filter_input(INPUT_POST, 'bis', FILTER_SANITIZE_MAGIC_QUOTES);

                $out = new ProjektMitarbeiter(Projekt::getById($projektFiltered), Mitarbeiter::getById($mitarbeiterFiltered), HTML::dateAndTimeToDateTime($vonFiltered), HTML::dateAndTimeToDateTime($bisFiltered), NULL);
                $out = ProjektMitarbeiter::insert($out);
                $out = ProjektMitarbeiter::getAll();
                $out = self::transform($out);
                break;

            /*
             * Übergabe des Primary Keys (über POST('id')
             * danach methodenaufruf (löschen) in der jeweiligen Klasse,
             * und seite neu laden bzw. liste anzeigen.
             */

            case 'delete' :
                $deleteprojektmitarbeiteridFiltered = filter_input(INPUT_POST, 'deleteprojektmitarbeiterid', FILTER_SANITIZE_NUMBER_INT);

                $out = $deleteprojektmitarbeiteridFiltered;
                $out = ProjektMitarbeiter::delete($out);
                $out = ProjektMitarbeiter::getAll();
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
        foreach ($out as $projektmitarbeiter) {
            $returnOut[$i]['projektName'] = $projektmitarbeiter->getProjekt()->getName();
            $returnOut[$i]['mitarbeiterVorname'] = $projektmitarbeiter->getMitarbeiter()->getVorname();
            $returnOut[$i]['mitarbeiterNachname'] = $projektmitarbeiter->getMitarbeiter()->getNachname();
            $returnOut[$i]['projektVon'] = HTML::extractDateFromDateTime($projektmitarbeiter->getVon());
            $returnOut[$i]['projektBis'] = HTML::extractDateFromDateTime($projektmitarbeiter->getBis());
            $returnOut[$i]['bearbeiten'] = HTML::buildButton('bearbeiten', $projektmitarbeiter->getId(), 'bearbeitenProjektMitarbeiter', 'bearbeiten');
            $returnOut[$i]['loeschen'] = HTML::buildButton('löschen', $projektmitarbeiter->getId(), 'loeschenProjektMitarbeiter', 'loeschen');
            $i++;
        }
        return $returnOut;
    }

    private static function transformUpdate($out = NULL) {
        $returnOut = [];
        $linkeSpalte = [];
        $rechteSpalte = [];

        for ($i = 0; $i < count(ProjektMitarbeiter::getNames()); $i++) {
            array_push($linkeSpalte, ProjektMitarbeiter::getNames()[$i]);
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

        $selected = NULL;
        if ($out !== NULL) {
            if ($out->getProjekt() !== NULL) {
                $selected = $out->getProjekt()->getId(); // Foreign Key
            }
        }
        $options = Option::buildOptions('Projekt', $selected);

        $selected = NULL;
        if ($out !== NULL) {
            if ($out->getName() !== NULL) {
                $selected = $out->getName()->getId(); // Foreign Key
            }
        }
        $options2 = Option::buildOptions('Mitarbeiter', $selected);

        if ($out !== NULL) {
            array_push($rechteSpalte, HTML::buildDropDown('projekt', '1', $options, NULL, 'projekt'));
            array_push($rechteSpalte, HTML::buildDropDown('mitarbeiter', '1', $options2, NULL, 'mitarbeiter'));
            array_push($rechteSpalte, HTML::buildInput('text', 'vonTag', HTML::extractDateFromDateTime($dbWerte['von']), NULL, 'vonTag'));
            array_push($rechteSpalte, HTML::buildInput('text', 'vonZeit', HTML::extractTimeFromDateTime($dbWerte['von'])));
            array_push($rechteSpalte, HTML::buildInput('text', 'bisTag', HTML::extractDateFromDateTime($dbWerte['bis']), NULL, 'bisTag'));
            array_push($rechteSpalte, HTML::buildInput('text', 'bisZeit', HTML::extractTimeFromDateTime($dbWerte['bis'])));
            array_push($rechteSpalte, HTML::buildButton('OK', 'ok', 'updateProjektMitarbeiter', 'OK'));
        } else {
            array_push($rechteSpalte, HTML::buildDropDown('projekt', '1', $options, NULL, 'projekt'));
            array_push($rechteSpalte, HTML::buildDropDown('mitarbeiter', '1', $options2, NULL, 'mitarbeiter'));
            array_push($rechteSpalte, HTML::buildInput('text', 'vonTag', '', NULL, 'vonTag', NULL, 'TT:MM:JJJJ'));
            array_push($rechteSpalte, HTML::buildInput('text', 'vonZeit', '', NULL, 'vonZeit', NULL, 'HH:mm'));
            array_push($rechteSpalte, HTML::buildInput('text', 'bisTag', '', NULL, 'bisTag', NULL, 'TT:MM:JJJJ'));
            array_push($rechteSpalte, HTML::buildInput('text', 'bisZeit', '', NULL, 'bisZeit', NULL, 'HH:mm'));
            array_push($rechteSpalte, HTML::buildButton('OK', 'ok', 'insertProjektMitarbeiter', 'OK'));
        }
        $returnOut = HTML::buildFormularTable($linkeSpalte, $rechteSpalte);
        return $returnOut;
    }

}
