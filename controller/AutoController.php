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

            case 'showInsert':
                $out = self::transformUpdate();
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
            $returnOut[$i]['loeschen'] = HTML::buildButton('löschen', $auto->getId(), NULL, 'loeschen');
            $i++;
        }
        return $returnOut;
    }

    private static function transformUpdate($out = NULL) {
        $returnOut = [];
        $linkeSpalte = [];
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
        $rechteSpalte = [];
        // überführe $dbWerte in rechte Spalte
        // hersteller $options ertellen
        $options = [];
        $herst = Hersteller::getAll();


        foreach ($herst as $hersteller) {
            $option = [];
            $option['value'] = $hersteller->getId();
            // @todo wenn Hersteller gelöscht wurde funkioniert Vergleich nicht

            $option['label'] = $hersteller->getName();
            $options[$hersteller->getId()] = $option;
            if ($out !== NULL) {
                if ($out->getHersteller()->getId() == $hersteller->getId()) {
                    $options[$hersteller->getId()]['selected'] = TRUE;
                }
            }
        }
        if ($out !== NULL) {
            array_push($rechteSpalte, HTML::buildDropDown('herstellerName', '1', $options));
            array_push($rechteSpalte, HTML::buildInput('text', 'name', $dbWerte['name']));
            array_push($rechteSpalte, HTML::buildInput('text', 'kennzeichen', $dbWerte['kennzeichen']));
            array_push($rechteSpalte, HTML::buildButton('OK', 'ok', NULL, 'OK'));
        } else {
            array_push($rechteSpalte, HTML::buildDropDown('herstellerName', '1', $options));
            array_push($rechteSpalte, HTML::buildInput('text', 'name', ''));
            array_push($rechteSpalte, HTML::buildInput('text', 'kennzeichen', ''));
            array_push($rechteSpalte, HTML::buildButton('OK', 'ok', NULL, 'OK'));
        }
        $returnOut = HTML::buildFormularTable($linkeSpalte, $rechteSpalte);
        return $returnOut;
    }

}
