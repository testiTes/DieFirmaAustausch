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
            $returnOut[$i]['bearbeiten'] = HTML::buildButton('bearbeiten', $projekt->getId());
            $returnOut[$i]['loeschen'] = HTML::buildButton('löschen', $projekt->getId());
            $i++;
        }
        return $returnOut;
    }

    private static function transformUpdate($out) {
        $returnOut = [];
        $linkeSpalte = [];
        $rechteSpalte = [];

        for ($i = 0; $i < count(Projekt::getNames()); $i++) {
            array_push($linkeSpalte, Projekt::getNames()[$i]);
        }
        array_push($linkeSpalte, HTML::buildInput('hidden', 'id', $out->getId()));

        $dbWerte = json_decode(json_encode($out), true);
        // überführe $dbWerte in rechte Spalte

        array_push($rechteSpalte, HTML::buildInput('text', 'name', $dbWerte['name']));
        array_push($rechteSpalte, HTML::buildButton('OK', 'ok', NULL, 'OK'));
        $returnOut = HTML::buildFormularTable($linkeSpalte, $rechteSpalte);
        return $returnOut;
    }

}
