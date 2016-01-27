<?php

/**
 * Description of AutoController
 *
 * @author Teilnehmer
 */
class AutoController {

    public static function doAction($action, &$view, $id) {
        switch ($action) {
            case 'showList':

                $out = Auto::getAll();
                $out = self::transform($out);
                break;

            case 'showUpdate':
                $out = Auto::getById($id);
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
        foreach ($out as $auto) {
            $returnOut[$i]['herstellerName'] = $auto->getHersteller()->getName();
            $returnOut[$i]['autoName'] = $auto->getName();
            $returnOut[$i]['autoKennzeichen'] = $auto->getKennzeichen();
            $returnOut[$i]['bearbeiten'] = HTML::buildButton('bearbeiten', $auto->getId());
            $returnOut[$i]['loeschen'] = HTML::buildButton('löschen', $auto->getId());
            $i++;
        }
        return $returnOut;
    }

    private static function transformUpdate($out) {
        $returnOut = [];
        $linkeSpalte = [];
        $rechteSpalte = [];
        $options = [];

        for ($i = 0; $i < count(Auto::getNames()); $i++) {
            array_push($linkeSpalte, Auto::getNames()[$i]);
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
            if ($out->getHersteller()->getId() == count($options) + 1) {
                $option['selected'] = TRUE;
            }
            $option['label'] = $hersteller->getName();
            array_push($options, $option);
        }
        array_push($rechteSpalte, HTML::buildDropDown('hersteller', '1', $options));
        array_push($rechteSpalte, HTML::buildInput('text', 'name', $dbWerte['name']));
        array_push($rechteSpalte, HTML::buildInput('text', 'kennzeichen', $dbWerte['kennzeichen']));
        array_push($rechteSpalte, HTML::buildButton('OK', 'ok', NULL, 'OK'));
        $returnOut = HTML::buildFormularTable($linkeSpalte, $rechteSpalte);
        return $returnOut;
    }

}
