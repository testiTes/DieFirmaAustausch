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

            case 'update' :
                $out = new Auto($_POST['Auto'], Hersteller::getById($_POST['hersteller_id']), $_POST['kennzeichen'], $_POST['uauid']);
                $out = Auto::update($out);
                $out = Auto::getAll();
                $out = self::transform($out);
                break;

            case 'insert' :
                $out = new Auto($_POST['auto'], Hersteller::getById($_POST['hersteller_id']), $_POST['kennzeichen'], NULL);
                $out = Auto::insert($out);
                $out = Auto::getAll();
                $out = self::transform($out);
                break;

            case 'delete' :
                $out = $_POST['lauid'];
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
            array_push($rechteSpalte, HTML::buildInput('text', 'autoName', '', NUll, 'autoName'));
            array_push($rechteSpalte, HTML::buildInput('text', 'kennzeichen', '', NULL, 'kennzeichen'));
            array_push($rechteSpalte, HTML::buildButton('OK', 'ok', 'insertAuto', 'OK'));
        }
        $returnOut = HTML::buildFormularTable($linkeSpalte, $rechteSpalte);
        return $returnOut;
    }

}
