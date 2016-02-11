<?php

/**
 * Description of AutoController
 *
 * @author Teilnehmer
 */
class AutoController {

    public static function doAction($action, &$view, $id) {
        switch ($action) {

            /*
             * Showlist führt Methode getAll in Klasse Auto aus
             * und liefert ein array mit objekten zurück.
             * dieses wird umgewandelt in ein Array, welches dann 
             * in der listeAuto zu einem HTML Statement in den
             * #content div geladen wird.
             * 
             */

            case 'showList':
                $out = Auto::getAll();
                $out = self::transform($out);
                break;

            /*
             *  $out Objekt; $id Integer
             *  Aus der Klasse Auto wird das passende Auto anhand der übegebenen ID geladen.
             *  Dafür wird die $id an dessen Funktion getById übegeben
             *  das übegebene Object wird in die $out reingeschrieben
             *  $out wird mit der eigenen Funktion transformUpdate bearbeitet
             *  Das heißt html gerechtes Bearbeitungsformular wird mit den Object Daten gefüllt
             *  
             */

            case 'showUpdate':
                $out = Auto::getById($id);
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
             * $out wird als "neues" Auto angelegt mit den Daten aus den Array $daten befüllt
             * Mit Fremd IDs z.B. 'auto_id=1' werden Inhalte z.B. 'Auto::name=Opel' aus ihren entsprechenden Klassen mit der Funktion getById geladen
             * $out wird an die Funktion update von Auto geschickt und in die Datenbak geschrieben
             * $out wird mit Auto Funktion getAll überschieben, sprich mit allen Autos
             * $out wird wird mit der Funktion transform html gerecht überarbeitet
             * So dass man nun die Listen allen Autos sieht.
             */

            case 'update' :
                $autoFiltered = filter_input(INPUT_POST, 'Auto', FILTER_SANITIZE_MAGIC_QUOTES);
                $hersteller_idFiltered = filter_input(INPUT_POST, 'hersteller_id', FILTER_SANITIZE_MAGIC_QUOTES);
                $kennzeichenFiltered = filter_input(INPUT_POST, 'kennzeichen', FILTER_SANITIZE_MAGIC_QUOTES);
                $updateautoidFiltered = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT);

                $out = new Auto($autoFiltered, Hersteller::getById($hersteller_idFiltered), $kennzeichenFiltered, $updateautoidFiltered);
                $out = Auto::update($out);
                $out = Auto::getAll();
                $out = self::transform($out);
                break;

            /*
             * die Daten aus den Inputfeldern werden mit sanitize bearbeitet um Cross-Site Scripting zu unterbinden
             * in $out wird ein neues Objekt von Auto als Objekt zugewiesen
             * in Datenbank gespeichert
             * Später wird die Liste der Objekte der Klasse ausgegeben
             */

            case 'insert' :
                $autoFiltered = filter_input(INPUT_POST, 'Auto', FILTER_SANITIZE_MAGIC_QUOTES);
                $hersteller_idFiltered = filter_input(INPUT_POST, 'hersteller_id', FILTER_SANITIZE_MAGIC_QUOTES);
                $kennzeichenFiltered = filter_input(INPUT_POST, 'kennzeichen', FILTER_SANITIZE_MAGIC_QUOTES);

                $out = new Auto($autoFiltered, Hersteller::getById($hersteller_idFiltered), $kennzeichenFiltered, NULL);
                $out = Auto::insert($out);
                $out = Auto::getAll();
                $out = self::transform($out);
                break;

            /* Übergabe des Primary Keys (über POST('id')
             * danach methodenaufruf (löschen) in der jeweiligen Klasse,
             * und seite neu laden bzw. liste anzeigen.
             */

            case 'delete' :
                $deleteautoidFiltered = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT);

                $out = $deleteautoidFiltered;
                $out = Auto::delete($out);
                $out = Auto::getAll();
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
        foreach ($out as $auto) {
            $returnOut[$i]['herstellerName'] = $auto->getHersteller()->getName();
            $returnOut[$i]['autoName'] = $auto->getName();
            $returnOut[$i]['autoKennzeichen'] = $auto->getKennzeichen();
            $returnOut[$i]['bearbeiten'] = HTML::buildButton('bearbeiten', $auto->getId(), 'bearbeitenAuto', 'bearbeiten');
            $returnOut[$i]['loeschen'] = HTML::buildButton('löschen', $auto->getId(), 'loeschenAuto', 'loeschen');
            $i++;
        }
        return $returnOut;
    }

    private static function transformUpdate($out = NULL) {
        $returnOut = [];
        $linkeSpalte = [];
        $rechteSpalte = [];

        for ($i = 0; $i < count(Auto::getNames()); $i++) {
            array_push($linkeSpalte, Auto::getNames()[$i]);
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
        // hersteller $options ertellen
        $options = [];
        $herst = Hersteller::getAll();

        foreach ($herst as $hersteller) {
            $option = [];
            $option['value'] = $hersteller->getId();
            $option['label'] = $hersteller->getName();
            $options[$hersteller->getId()] = $option;
            if ($out !== NULL) {
                if ($out->getHersteller()->getId() == $hersteller->getId()) {
                    $options[$hersteller->getId()]['selected'] = TRUE;
                }
            }
        }

        if ($out !== NULL) {
            array_push($rechteSpalte, HTML::buildDropDown('herstellerName', '1', $options, NULL, 'hersteller'));
            array_push($rechteSpalte, HTML::buildInput('text', 'autoName', $dbWerte['name'], NULL, 'autoName'));
            array_push($rechteSpalte, HTML::buildInput('text', 'kennzeichen', $dbWerte['kennzeichen'], NULL, 'kennzeichen'));
            array_push($rechteSpalte, HTML::buildButton('OK', 'ok', 'updateAuto', 'OK'));
        } else {
            array_push($rechteSpalte, HTML::buildDropDown('herstellerName', '1', $options, NULL, 'hersteller'));
            array_push($rechteSpalte, HTML::buildInput('text', 'autoName', '', NUll, 'autoName', NULL, 'Fahrzeugmodell'));
            array_push($rechteSpalte, HTML::buildInput('text', 'kennzeichen', '', NULL, 'kennzeichen', NULL, 'Kennzeichen'));
            array_push($rechteSpalte, HTML::buildButton('OK', 'ok', 'insertAuto', 'OK'));
        }
        $returnOut = HTML::buildFormularTable($linkeSpalte, $rechteSpalte);
        return $returnOut;
    }

}
