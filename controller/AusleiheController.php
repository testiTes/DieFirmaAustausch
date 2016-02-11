<?php

/**
 * Description of AusleiheController
 *
 * @author Teilnehmer
 */
class AusleiheController {

    public static function doAction($action, &$view, $id) {
        switch ($action) {

            /*
             * Showlist führt Methode getAll in Klasse Ausleihe aus
             * und liefert ein array mit objekten zurück.
             * dieses wird umgewandelt in ein Array, welches dann 
             * in der listeAusleihe zu einem HTML Statement in den
             * #content div geladen wird.
             * 
             */

            case 'showList':
                $out = Ausleihe::getAll();
                $out = self::transform($out);
                break;

            /*
             *  $out Objekt; $id Integer
             *  Aus der Klasse Ausleihe wird die passende Ausleihe anhand der übegebenen ID geladen.
             *  Dafür wird die $id an dessen Funktion getById übegeben
             *  das übegebene Object wird in die $out reingeschrieben
             *  $out wird mit der eigenen Funktion transformUpdate bearbeitet
             *  Das heißt html gerechtes Bearbeitungsformular wird mit den Object Daten gefüllt
             *  
             */

            case 'showUpdate':
                $out = Ausleihe::getById($id);
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
             * $out wird als "neue" Ausleihe angelegt mit den Daten aus den Array $daten befüllt
             * Mit Fremd IDs z.B. 'auto_id=1' werden Inhalte z.B. 'Auto::name=Opel' aus ihren entsprechenden Klassen mit der Funktion getById geladen
             * $out wird an die Funktion update von Ausleihe geschickt und in die Datenbak geschrieben
             * $out wird mit Ausleihe Funktion getAll überschieben, sprich mit allen Ausleihen
             * $out wird wird mit der Funktion transform html gerecht überarbeitet
             * So dass man nun die Liste allen Ausleihen sieht.
             */

            case 'update' :
                $fahrzeugFiltered = filter_input(INPUT_POST, 'fahrzeug', FILTER_SANITIZE_MAGIC_QUOTES);
                $mitarbeiterFiltered = filter_input(INPUT_POST, 'mitarbeiter', FILTER_SANITIZE_MAGIC_QUOTES);
                $vonFiltered = filter_input(INPUT_POST, 'von', FILTER_SANITIZE_MAGIC_QUOTES);
                $bisFiltered = filter_input(INPUT_POST, 'bis', FILTER_SANITIZE_MAGIC_QUOTES);
                $updateausleiheidFiltered = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT);

                $out = new Ausleihe(Auto::getById($fahrzeugFiltered), Mitarbeiter::getById($mitarbeiterFiltered), HTML::dateAndTimeToDateTime($vonFiltered), HTML::dateAndTimeToDateTime($bisFiltered), $updateausleiheidFiltered);
                $out = Ausleihe::update($out);
                $out = Ausleihe::getAll();
                $out = self::transform($out);
                break;

            /*
             * die Daten aus den Inputfeldern werden mit sanitize bearbeitet um Cross-Site Scripting zu unterbinden
             * in $out wird ein neues Objekt von Ausleihe als Objekt zugewiesen
             * in Datenbank gespeichert
             * Später wird die Liste der Objekte der Klasse ausgegeben
             */

            case 'insert' :
                $fahrzeugFiltered = filter_input(INPUT_POST, 'fahrzeug', FILTER_SANITIZE_MAGIC_QUOTES);
                $mitarbeiterFiltered = filter_input(INPUT_POST, 'mitarbeiter', FILTER_SANITIZE_MAGIC_QUOTES);
                $vonFiltered = filter_input(INPUT_POST, 'von', FILTER_SANITIZE_MAGIC_QUOTES);
                $bisFiltered = filter_input(INPUT_POST, 'bis', FILTER_SANITIZE_MAGIC_QUOTES);

                $out = new Ausleihe(Auto::getById($fahrzeugFiltered), Mitarbeiter::getById($mitarbeiterFiltered), HTML::dateAndTimeToDateTime($vonFiltered), HTML::dateAndTimeToDateTime($bisFiltered), NULL);
                $out = Ausleihe::insert($out);
                $out = Ausleihe::getAll();
                $out = self::transform($out);
                break;

            /*
             * Übergabe des Primary Keys (über POST('id')
             * danach methodenaufruf (löschen) in der jeweiligen Klasse,
             * und seite neu laden bzw. liste anzeigen.
             */

            case 'delete' :
                $deleteausleiheidFiltered = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT);

                $out = $deleteausleiheidFiltered;
                $out = Ausleihe::delete($out);
                $out = Ausleihe::getAll();
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
        foreach ($out as $ausleihe) {
            $returnOut[$i]['herstellerName'] = $ausleihe->getAuto()->getHersteller()->getName();
            $returnOut[$i]['modell'] = $ausleihe->getAuto()->getName();
            $returnOut[$i]['kennzeichen'] = $ausleihe->getAuto()->getKennzeichen();
            $returnOut[$i]['nachname'] = $ausleihe->getMitarbeiter()->getVorname();
            $returnOut[$i]['vorname'] = $ausleihe->getMitarbeiter()->getNachname();
            $returnOut[$i]['von'] = HTML::dateTimeToDateAndTime($ausleihe->getVon());
            $returnOut[$i]['bis'] = HTML::dateTimeToDateAndTime($ausleihe->getBis());
            $returnOut[$i]['bearbeiten'] = HTML::buildButton('bearbeiten', $ausleihe->getId(), 'bearbeitenAusleihe', 'bearbeiten');
            $returnOut[$i]['loeschen'] = HTML::buildButton('löschen', $ausleihe->getId(), 'loeschenAusleihe', 'loeschen');
            $i++;
        }
        return $returnOut;
    }

    private static function transformUpdate($out = NULL) {
        $returnOut = [];
        $linkeSpalte = [];
        $rechteSpalte = [];

        for ($i = 0; $i < count(Ausleihe::getNames()); $i++) {
            array_push($linkeSpalte, Ausleihe::getNames()[$i]);
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
        // auto $options erstellen
//        $auto = Auto::getAll();
//        $options = [];
//        $options[0] = ['value' => 0, 'label' => ''];
//        $hatirgendwas = FALSE;
//        foreach ($auto as $o) {
//            $options[$o->getId()] = ['value' => $o->getId(), 'label' => $o->getHersteller()->getName() . ' ' . $o->getName() . ' ' . $o->getKennzeichen()];
//            if ($out !== NULL) {
//                if ($o->getId() == $out->getAuto()->getId()) {
//                    $options[$o->getId()]['selected'] = TRUE;
//                    $hatirgendwas = TRUE;
//                }
//            }
//        }
//        if ($hatirgendwas == FALSE) {
//            $options[0]['selected'] = TRUE;
//        }
//        
        // mitarbeiter $options erstellen
//        $mitarbeiter = Mitarbeiter::getAll();
//        $options2 = [];
//        // zum abwählen
//        $options2[0] = ['value' => 0, 'label' => ''];
//        $hatMitarbeiter = FALSE;
//        foreach ($mitarbeiter as $o) {
//            $options2[$o->getId()] = ['value' => $o->getId(), 'label' => $o->getVorname() . ' ' . $o->getNachname()];
//            if ($out !== NULL) {
//                if ($o->getId() === $out->getMitarbeiter()->getId()) {
//                    $options2[$o->getId()]['selected'] = TRUE;
//                    $hatMitarbeiter = TRUE;
//                }
//            }
//        }
//        if ($hatMitarbeiter == FALSE) {
//            $options2[0]['selected'] = TRUE;
//        }

        $selected = NULL;
        if ($out !== NULL) {
            if ($out->getAuto() !== NULL) {
                $selected = $out->getAuto()->getId(); // Foreign Key
            }
        }
        $options = Option::buildOptions('Auto', $selected);

        $selected = NULL;
        if ($out !== NULL) {
            if ($out->getName() !== NULL) {
                $selected = $out->getName()->getId(); // Foreign Key
            }
        }
        $options2 = Option::buildOptions('Mitarbeiter', $selected);

        if ($out !== NULL) {
            array_push($rechteSpalte, HTML::buildDropDown('fahrzeug', '1', $options, NULL, 'fahrzeug'));
            array_push($rechteSpalte, HTML::buildDropDown('mitarbeiter', '1', $options2, NULL, 'mitarbeiter'));
            array_push($rechteSpalte, HTML::buildInput('text', 'vonTag', HTML::extractDateFromDateTime($dbWerte['von']), NULL, 'vonTag'));
            array_push($rechteSpalte, HTML::buildInput('text', 'vonZeit', HTML::extractTimeFromDateTime($dbWerte['von'])));
            array_push($rechteSpalte, HTML::buildInput('text', 'bisTag', HTML::extractDateFromDateTime($dbWerte['bis']), NULL, 'bisTag'));
            array_push($rechteSpalte, HTML::buildInput('text', 'bisZeit', HTML::extractTimeFromDateTime($dbWerte['bis'])));
            array_push($rechteSpalte, HTML::buildButton('OK', 'ok', 'updateAusleihe', 'OK'));
        } else {
            array_push($rechteSpalte, HTML::buildDropDown('fahrzeug', '1', $options, NULL, 'fahrzeug'));
            array_push($rechteSpalte, HTML::buildDropDown('mitarbeiter', '1', $options2, NULL, 'mitarbeiter'));
            array_push($rechteSpalte, HTML::buildInput('text', 'vonTag', '', NULL, 'vonTag', NULL, 'TT:MM:JJJJ'));
            array_push($rechteSpalte, HTML::buildInput('text', 'vonZeit', '', NULL, 'vonZeit', NULL, 'HH:mm'));
            array_push($rechteSpalte, HTML::buildInput('text', 'bisTag', '', NULL, 'bisTag', NULL, 'TT:MM:JJJJ'));
            array_push($rechteSpalte, HTML::buildInput('text', 'bisZeit', '', NULL, 'bisZeit', NULL, 'HH:mm'));
            array_push($rechteSpalte, HTML::buildButton('OK', 'ok', 'insertAusleihe', 'OK'));
        }
        $returnOut = HTML::buildFormularTable($linkeSpalte, $rechteSpalte);
        return $returnOut;
    }

}
