<?php

/**
 * Description of AusleiheController
 *
 * @author Teilnehmer
 */
class AusleiheController {

    public static function doAction($action, &$view, $id) {
        switch ($action) {
            case 'showList':

                $out = Ausleihe::getAll();
                $out = self::transform($out);
                break;

            case 'showUpdate':
                $out = Ausleihe::getById($id);
                $out = self::transformUpdate($out);
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
            $returnOut[$i]['bearbeiten'] = HTML::buildButton('bearbeiten', $ausleihe->getId());
            $returnOut[$i]['loeschen'] = HTML::buildButton('löschen', $ausleihe->getId());
            $i++;
        }
        return $returnOut;
    }

    private static function transformUpdate($out) {
        $returnOut = [];
        $linkeSpalte = [];
        $rechteSpalte = [];
        $options = [];
        $autoOptions = [];

        for ($i = 0; $i < count(Ausleihe::getNames()); $i++) {
            array_push($linkeSpalte, Ausleihe::getNames()[$i]);
        }
        array_push($linkeSpalte, HTML::buildInput('hidden', 'id', $out->getId()));

        $dbWerte = json_decode(json_encode($out), true);
        // überführe $dbWerte in rechte Spalte
        // dropdownMenü $options erstellen
        $herst = Hersteller::getAll();
        // @todo wenn Hersteller gelöscht wurde funktioniert Vergleich nicht mehr
        foreach ($herst as $hersteller) {
            $option = [];
            $option['value'] = $hersteller->getId();
            if ($out->getAuto()->getHersteller()->getId() == count($options) + 1) {
                $option['selected'] = TRUE;
            }
            $option['label'] = $hersteller->getName();
            array_push($options, $option);
        }

        // auto $options erstellen
        $auto = Auto::getAll();
        foreach ($auto as $modell) {
            $option = [];
            $option['value'] = $modell->getId();
            if ($out->getAuto()->getId() == count($autoOptions) + 1) {
                $option['selected'] = TRUE;
            }
            $option['label'] = $modell->getName();
            array_push($autoOptions, $option);
        }

        array_push($rechteSpalte, HTML::buildDropDown('hersteller', '1', $options));
        array_push($rechteSpalte, HTML::buildDropDown('auto', '1', $autoOptions));
        array_push($rechteSpalte, HTML::buildInput('text', 'kennzeichen', $dbWerte['auto']['kennzeichen']));
        array_push($rechteSpalte, HTML::buildInput('text', 'vorname', $dbWerte['mitarbeiter']['vorname']));
        array_push($rechteSpalte, HTML::buildInput('text', 'nachname', $dbWerte['mitarbeiter']['nachname']));
        array_push($rechteSpalte, HTML::buildInput('text', 'von', $dbWerte['von']));
        array_push($rechteSpalte, HTML::buildInput('text', 'bis', $dbWerte['bis']));
        array_push($rechteSpalte, HTML::buildButton('OK', 'ok', NULL, 'OK'));
        $returnOut = HTML::buildFormularTable($linkeSpalte, $rechteSpalte);
        return $returnOut;
    }

}
