<?php

/**
 * Description of ProjektController
 *
 * @author Teilnehmer
 */
class ProjektController {

    public static function doAction($action, &$view, $id) {
        switch ($action) {

            case 'showList':
                $out = Projekt::getAll();
                $out = self::transform($out);
                break;

            case 'showUpdate':
                $out = Projekt::getById($id);
                $out = self::transformUpdate($out);
                break;

            case 'showInsert':
                $out = self::transformUpdate();
                break;

            case 'update' :
                $out = new Projekt($_POST['Projekt'], $_POST['uprid']);
                $out = Projekt::update($out);
                $out = Projekt::getAll();
                $out = self::transform($out);
                break;

            case 'insert' :
                $out = new Projekt($_POST['projekt'], NULL);
                $out = Projekt::insert($out);
                $out = Projekt::getAll();
                $out = self::transform($out);
                break;

            case 'delete' :
                $out = $_POST['lprid'];
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
            array_push($rechteSpalte, HTML::buildInput('text', 'projekt', '', NULL, 'projekt'));
            array_push($rechteSpalte, HTML::buildButton('OK', 'ok', 'insertProjekt', 'OK'));
        }
        $returnOut = HTML::buildFormularTable($linkeSpalte, $rechteSpalte);
        return $returnOut;
    }

}
