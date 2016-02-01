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
        foreach ($out as $ausleihe) {
            $returnOut[$i]['herstellerName'] = $ausleihe->getAuto()->getHersteller()->getName();
            $returnOut[$i]['modell'] = $ausleihe->getAuto()->getName();
            $returnOut[$i]['kennzeichen'] = $ausleihe->getAuto()->getKennzeichen();
            $returnOut[$i]['nachname'] = $ausleihe->getMitarbeiter()->getVorname();
            $returnOut[$i]['vorname'] = $ausleihe->getMitarbeiter()->getNachname();
            $returnOut[$i]['von'] = HTML::dateTimeToDateAndTime($ausleihe->getVon());
            $returnOut[$i]['bis'] = HTML::dateTimeToDateAndTime($ausleihe->getBis());
            $returnOut[$i]['bearbeiten'] = HTML::buildButton('bearbeiten', $ausleihe->getId(), 'bearbeitenAusleihe', 'bearbeiten');
            $returnOut[$i]['loeschen'] = HTML::buildButton('löschen', $ausleihe->getId(), NULL, 'loeschen');
            $i++;
        }
        return $returnOut;
    }

    private static function transformUpdate($out = NULL) {
        $returnOut = [];
        $linkeSpalte = [];
        $rechteSpalte = [];
        $options = [];
        $autoOptions = [];

        for ($i = 0; $i < count(Ausleihe::getNames()); $i++) {
            array_push($linkeSpalte, Ausleihe::getNames()[$i]);
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
        // dropdownMenü $options erstellen
        $herst = Hersteller::getAll();
        $options = [];
        $options[0] = ['value' => 0, 'label' => ''];
        $hatirgendwas = FALSE;
        foreach ($herst as $o) {
            $options[$o->getId()] = ['value' => $o->getId(), 'label' => $o->getName()];
            if ($out !== NULL) {
                if ($o->getId() === $out->getId()) {
                    $options[$o->getId()]['selected'] = TRUE;
                    $hatirgendwas = TRUE;
                }
            }
        }
        if ($hatirgendwas == FALSE) {
            $options[0]['selected'] = TRUE;
        }

        // auto $options erstellen
        $auto = Auto::getAll();
        $options2 = [];
        $options2[0] = ['value' => 0, 'label' => ''];
        $hatirgendwas2 = FALSE;
        foreach ($auto as $o) {
            $options2[$o->getId()] = ['value' => $o->getId(), 'label' => $o->getName()];
            if ($out !== NULL) {
                if ($o->getId() == $out->getAuto()->getId()) {
                    $options2[$o->getId()]['selected'] = TRUE;
                    $hatirgendwas2 = TRUE;
                }
            }
        }
        if ($hatirgendwas2 == FALSE) {
            $options2[0]['selected'] = TRUE;
        }
        if ($out !== NULL) {
            array_push($rechteSpalte, HTML::buildDropDown('hersteller', '1', $options));
            array_push($rechteSpalte, HTML::buildDropDown('auto', '1', $options2));
            array_push($rechteSpalte, HTML::buildInput('text', 'kennzeichen', $dbWerte['auto']['kennzeichen']));
            array_push($rechteSpalte, HTML::buildInput('text', 'vorname', $dbWerte['mitarbeiter']['vorname']));
            array_push($rechteSpalte, HTML::buildInput('text', 'nachname', $dbWerte['mitarbeiter']['nachname']));
            array_push($rechteSpalte, HTML::buildInput('text', 'von', HTML::dateTimeToDateAndTime($dbWerte['von'])));
            array_push($rechteSpalte, HTML::buildInput('text', 'bis', HTML::dateTimeToDateAndTime($dbWerte['bis'])));
            array_push($rechteSpalte, HTML::buildButton('OK', 'ok', NULL, 'OK'));
        } else {
            array_push($rechteSpalte, HTML::buildDropDown('hersteller', '1', $options));
            array_push($rechteSpalte, HTML::buildDropDown('auto', '1', $options2));
            array_push($rechteSpalte, HTML::buildInput('text', 'kennzeichen', ''));
            array_push($rechteSpalte, HTML::buildInput('text', 'vorname', ''));
            array_push($rechteSpalte, HTML::buildInput('text', 'nachname', ''));
            array_push($rechteSpalte, HTML::buildInput('text', 'von', ''));
            array_push($rechteSpalte, HTML::buildInput('text', 'bis', ''));
            array_push($rechteSpalte, HTML::buildButton('OK', 'ok', NULL, 'OK'));
        }
        $returnOut = HTML::buildFormularTable($linkeSpalte, $rechteSpalte);
        return $returnOut;
    }

}
