<?php

/**
 * Description of AbteilungController
 *
 * @author Teilnehmer
 */
class AbteilungController {

    public static function doAction($action, &$view, $id) {
        switch ($action) {
            
            case 'showList':
                $out = Abteilung::getAll();
                $out = self::transform($out);
                break;

            case 'showUpdate':
                $out = Abteilung::getById($id);
                $out = self::transformUpdate($out);
                break;

            case 'showInsert':
                $out = self::transformUpdate();
                break;

            case 'update':
                $out = new Abteilung($_POST['Abteilung'], $_POST['uabid']);
                $out = Abteilung::update($out);
                $out = Abteilung::getAll();
                $out = self::transform($out);
                break;

            case 'insert':
                $out = new Abteilung($_POST['abteilung'], NULL);
                $out = Abteilung::insert($out);
                $out = Abteilung::getAll();
                $out = self::transform($out);
                break;

            case 'delete':
                $out = $_POST['labid'];
                $out = Abteilung::delete($out);
                $out = Abteilung::getAll();
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
        foreach ($out as $abteilung) {
            $returnOut[$i]['abteilungName'] = $abteilung->getName();
            $returnOut[$i]['bearbeiten'] = HTML::buildButton('bearbeiten', $abteilung->getId(), 'bearbeitenAbteilung', 'bearbeiten');
            $returnOut[$i]['loeschen'] = HTML::buildButton('löschen', $abteilung->getId(), 'loeschenAbteilung', 'loeschen');
            $i++;
        }
        return $returnOut;
    }

    private static function transformUpdate($out = NULL) {
        $returnOut = [];
        $linkeSpalte = [];
        $rechteSpalte = [];

        for ($i = 0; $i < count(Abteilung::getNames()); $i++) {
            array_push($linkeSpalte, Abteilung::getNames()[$i]);
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
            array_push($rechteSpalte, HTML::buildInput('text', 'name', $dbWerte['name'], NULL, 'abteilung'));
            array_push($rechteSpalte, HTML::buildButton('OK', 'ok', 'updateAbteilung', 'OK'));
        } else {
            array_push($rechteSpalte, HTML::buildInput('text', 'name', '', NULL, 'abteilung'));
            array_push($rechteSpalte, HTML::buildButton('OK', 'ok', 'insertAbteilung', 'OK'));
        }
        $returnOut = HTML::buildFormularTable($linkeSpalte, $rechteSpalte);
        return $returnOut;
    }

}
