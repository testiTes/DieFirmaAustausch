<?php

/**
 * Description of HerstellerController
 *
 * @author Teilnehmer
 */
class HerstellerController {

    public static function doAction($action, &$view, $id) {
        switch ($action) {
            case 'showList':

                $out = Hersteller::getAll();
                $out = self::transform($out);
                break;

            case 'showUpdate':
                $out = Hersteller::getById($id);
                $out = self::transformUpdate($out);
                break;

            default:
                break;
        }
        return $out;
    }

    private static function transform($out) {
        $herst; // hersteller array
        $i = 0;
        foreach ($out as $hersteller) {
            $herst[$i]['hersteller'] = $hersteller->getName();
            $herst[$i]['bearbeiten'] = HTML::buildButton('bearbeiten', $hersteller->getId());
            $herst[$i]['loeschen'] = HTML::buildButton('löschen', $hersteller->getId());
            $i++;
        }
        return $herst;
    }

    private static function transformUpdate($out) {
        $returnOut = [];

        $linkeSpalte = Hersteller::getNames();
        array_push($linkeSpalte, HTML::buildInput('hidden', 'id', $out->getId()));
        $dbWerte = json_decode(json_encode($out), true);
        
        // überführe $dbWerte in rechte Spalte
        $rechteSpalte[0] = HTML::buildInput('text', 'hersteller', $dbWerte['name']);
        array_push($rechteSpalte, HTML::buildButton('OK', 'ok', NULL, 'OK'));
        $returnOut = HTML::buildFormularTable($linkeSpalte, $rechteSpalte);

        return $returnOut;
    }

}
