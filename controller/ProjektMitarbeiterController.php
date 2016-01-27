<?php

/**
 * Description of ProjektMitarbeiterController
 *
 * @author Teilnehmer
 */
class ProjektMitarbeiterController {

    public static function doAction($action, &$view, $id) {
        switch ($action) {
            case 'showList':

                $out = ProjektMitarbeiter::getAll();
                $out = self::transform($out);
                break;

            case 'showUpdate':
                $out = ProjektMitarbeiter::getById($id);
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
        foreach ($out as $projektmitarbeiter) {
            $returnOut[$i]['projektName'] = $projektmitarbeiter->getProjekt()->getName();
            $returnOut[$i]['mitarbeiterVorname'] = $projektmitarbeiter->getMitarbeiter()->getVorname();
            $returnOut[$i]['mitarbeiterNachname'] = $projektmitarbeiter->getMitarbeiter()->getNachname();
            $returnOut[$i]['projektVon'] = HTML::dateTimeToDateAndTime($projektmitarbeiter->getVon());
            $returnOut[$i]['projektBis'] = HTML::dateTimeToDateAndTime($projektmitarbeiter->getBis());
            $returnOut[$i]['bearbeiten'] = HTML::buildButton('bearbeiten', $projektmitarbeiter->getId());
            $returnOut[$i]['loeschen'] = HTML::buildButton('löschen', $projektmitarbeiter->getId());
            $i++;
        }
        return $returnOut;
    }

    private static function transformUpdate($out) {
        $returnOut = [];
        $linkeSpalte = [];
        for ($i = 0; $i < count(ProjektMitarbeiter::getNames()); $i++) {
            array_push($linkeSpalte, ProjektMitarbeiter::getNames()[$i]);
        }
        array_push($linkeSpalte, HTML::buildInput('hidden', 'id', $out->getId()));
        $dbWerte = json_decode(json_encode($out), true);
        // überführe $dbWerte in rechte Spalte
        // dropdownMenü $abteilungoptions erstellen
        $projektoptions = [];
        $projekte = Projekt::getAll();
        foreach ($projekte as $projekt) {
            $option = [];
            $option['value'] = $projekt->getId();
            if ($out->getProjekt()->getId() == count($projektoptions) + 1) {
                $option['selected'] = TRUE;
            }
            $option['label'] = $projekt->getName();
            array_push($projektoptions, $option);
        }

        $rechteSpalte = [];
        array_push($rechteSpalte, HTML::buildDropDown('projekt', '1', $projektoptions));
        array_push($rechteSpalte, HTML::buildInput('text', 'vorname', $dbWerte['mitarbeiter']['vorname']));
        array_push($rechteSpalte, HTML::buildInput('text', 'nachname', $dbWerte['mitarbeiter']['nachname']));
        array_push($rechteSpalte, HTML::buildInput('text', 'projektVon', HTML::dateTimeToDateAndTime($dbWerte['von'])));
        array_push($rechteSpalte, HTML::buildInput('text', 'projektBis', HTML::dateTimeToDateAndTime($dbWerte['bis'])));
        array_push($rechteSpalte, HTML::buildButton('OK', 'ok', '', 'OK'));

        $returnOut = HTML::buildFormularTable($linkeSpalte, $rechteSpalte);
        return $returnOut;
    }

}
