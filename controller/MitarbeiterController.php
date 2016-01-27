<?php

/**
 * Description of MitarbeiterController
 *
 * @author Teilnehmer
 */
class MitarbeiterController {

    public static function doAction($action, &$view, $id) {
        switch ($action) {
            case 'showList':

                $out = Mitarbeiter::getAll();
                $out = self::transform($out);
                break;

            case 'showUpdate':
                $out = Mitarbeiter::getById($id);
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
        foreach ($out as $mitarbeiter) {
            $returnOut[$i]['vorname'] = $mitarbeiter->getVorname();
            $returnOut[$i]['nachname'] = $mitarbeiter->getNachname();
            $returnOut[$i]['bearbeiten'] = HTML::buildButton('bearbeiten', $mitarbeiter->getId());
            $returnOut[$i]['loeschen'] = HTML::buildButton('löschen', $mitarbeiter->getId());
            $i++;
        }
        return $returnOut;
    }

    private static function transformUpdate($out) {
        $returnOut = [];
        $linkeSpalte = [];
        for ($i = 0; $i < count(Mitarbeiter::getNames()); $i++) {
            array_push($linkeSpalte, Mitarbeiter::getNames()[$i]);
        }
        array_push($linkeSpalte, HTML::buildInput('hidden', 'id', $out->getId()));
        $dbWerte = json_decode(json_encode($out), true);
        // überführe $dbWerte in rechte Spalte
        // dropdownMenü $abteilungoptions erstellen
        $abteilungoptions = [];
        $abteilungen = Abteilung::getAll();
        foreach ($abteilungen as $abteilung) {
            $option = [];
            $option['value'] = $abteilung->getId();
            if ($out->getAbteilung()->getId() == count($abteilungoptions) + 1) {
                $option['selected'] = TRUE;
            }
            $option['label'] = $abteilung->getName();
            array_push($abteilungoptions, $option);
        }

        // vorgesetzter $options erstellen
        $vorgesetzteroptions = [];
        $vorgesetzte = Mitarbeiter::getAll();
        $option = [];
        $option['value'] = '';
        $option['label'] = '';
        array_push($vorgesetzteroptions, $option);
        foreach ($vorgesetzte as $vorgesetzter) {
            $option = [];
            $option['value'] = $vorgesetzter->getId();
            if ($out->getVorgesetzter() !== NULL) {
                if ($out->getVorgesetzter()->getId() == count($vorgesetzteroptions)) {
                    $option['selected'] = TRUE;
                }
            }
            $option['label'] = $vorgesetzter->getVorname() . ' ' . $vorgesetzter->getNachname();
            array_push($vorgesetzteroptions, $option);
        }

        // radio $options erstellen
        $radioOptions = [];
        $radioOption = [];
        $radioOption['label'] = 'weibl.';
        if ($out->getGeschlecht() === 'w') {
            $radioOption['checked'] = TRUE;
        }
        $radioOption['value'] = 'w';
        array_push($radioOptions, $radioOption);

        $radioOption = [];
        $radioOption['label'] = 'männl.';
        if ($out->getGeschlecht() === 'm') {
            $radioOption['checked'] = TRUE;
        }
        $radioOption['value'] = 'm';
        array_push($radioOptions, $radioOption);

        $rechteSpalte = [];
        array_push($rechteSpalte, HTML::buildInput('text', 'vorname', $dbWerte['vorname']));
        array_push($rechteSpalte, HTML::buildInput('text', 'nachname', $dbWerte['nachname']));
        array_push($rechteSpalte, HTML::buildRadio('geschlecht', $radioOptions, FALSE));
        array_push($rechteSpalte, HTML::buildInput('text', 'geburtsdatum', HTML::mysqlToGerman($dbWerte['geburtsdatum'])));
        array_push($rechteSpalte, HTML::buildDropDown('abteilung', '1', $abteilungoptions));
        array_push($rechteSpalte, HTML::buildInput('text', 'stundenlohn', $dbWerte['stundenlohn']));
        array_push($rechteSpalte, HTML::buildDropDown('vorgesetzter', '1', $vorgesetzteroptions));
        array_push($rechteSpalte, HTML::buildButton('OK', 'ok', '', 'OK'));

        $returnOut = HTML::buildFormularTable($linkeSpalte, $rechteSpalte);
        return $returnOut;
    }

}
