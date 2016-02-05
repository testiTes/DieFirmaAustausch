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

            case 'update' :
                $out = new Ausleihe(Auto::getById($_POST['fahrzeug']), Mitarbeiter::getById($_POST['mitarbeiter']), HTML::dateAndTimeToDateTime($_POST['von']), HTML::dateAndTimeToDateTime($_POST['bis']), $_POST['uausid']);
                $out = Ausleihe::update($out);
                $out = Ausleihe::getAll();
                $out = self::transform($out);
                break;

            case 'insert' :
                $out = new Ausleihe(Auto::getById($_POST['fahrzeug']), Mitarbeiter::getById($_POST['mitarbeiter']), HTML::dateAndTimeToDateTime($_POST['von']), HTML::dateAndTimeToDateTime($_POST['bis']), NULL);
                $out = Ausleihe::insert($out);
                $out = Ausleihe::getAll();
                $out = self::transform($out);
                break;

            case 'delete' :
                $out = $_POST['lausid'];
                $out = Ausleihe::delete($out);
                $out = Ausleihe::getAll();
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
        foreach ($out as $ausleihe) {
            $returnOut[$i]['herstellerName'] = $ausleihe->getAuto()->getHersteller()->getName();
            $returnOut[$i]['modell'] = $ausleihe->getAuto()->getName();
            $returnOut[$i]['kennzeichen'] = $ausleihe->getAuto()->getKennzeichen();
            $returnOut[$i]['nachname'] = $ausleihe->getMitarbeiter()->getVorname();
            $returnOut[$i]['vorname'] = $ausleihe->getMitarbeiter()->getNachname();
            $returnOut[$i]['von'] = HTML::dateTimeToDateAndTime($ausleihe->getVon());
            $returnOut[$i]['bis'] = HTML::dateTimeToDateAndTime($ausleihe->getBis());
            $returnOut[$i]['bearbeiten'] = HTML::buildButton('bearbeiten', $ausleihe->getId(), 'bearbeitenAusleihe', 'bearbeiten');
            $returnOut[$i]['loeschen'] = HTML::buildButton('löschen', $ausleihe->getId(), 'loeschenAusleihe', 'loeschen');
            $i++;
        }
        return $returnOut;
    }

    private static function transformUpdate($out = NULL) {
        $returnOut = [];
        $linkeSpalte = [];
        $rechteSpalte = [];

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
        // auto $options erstellen
        $auto = Auto::getAll();
        $options = [];
        $options[0] = ['value' => 0, 'label' => ''];
        $hatirgendwas = FALSE;
        foreach ($auto as $o) {
            $options[$o->getId()] = ['value' => $o->getId(), 'label' => $o->getHersteller()->getName() . ' ' . $o->getName() . ' ' . $o->getKennzeichen()];
            if ($out !== NULL) {
                if ($o->getId() == $out->getAuto()->getId()) {
                    $options[$o->getId()]['selected'] = TRUE;
                    $hatirgendwas = TRUE;
                }
            }
        }
        if ($hatirgendwas == FALSE) {
            $options[0]['selected'] = TRUE;
        }

        // mitarbeiter $options erstellen

        $mitarbeiter = Mitarbeiter::getAll();
        $options2 = [];
        // zum abwählen
        $options2[0] = ['value' => 0, 'label' => ''];
        $hatMitarbeiter = FALSE;
        foreach ($mitarbeiter as $o) {
            $options2[$o->getId()] = ['value' => $o->getId(), 'label' => $o->getVorname() . ' ' . $o->getNachname()];
            if ($out !== NULL) {
                if ($o->getId() === $out->getMitarbeiter()->getId()) {
                    $options2[$o->getId()]['selected'] = TRUE;
                    $hatMitarbeiter = TRUE;
                }
            }
        }
        if ($hatMitarbeiter == FALSE) {
            $options2[0]['selected'] = TRUE;
        }


        if ($out !== NULL) {
            array_push($rechteSpalte, HTML::buildDropDown('fahrzeug', '1', $options, NULL, 'fahrzeug'));
            array_push($rechteSpalte, HTML::buildDropDown('mitarbeiter', '1', $options2, NULL, 'mitarbeiter'));
            array_push($rechteSpalte, HTML::buildInput('text', 'vonTag', HTML::extractDateFromDateTime($dbWerte['von'])));
            array_push($rechteSpalte, HTML::buildInput('text', 'vonZeit', HTML::extractTimeFromDateTime($dbWerte['von'])));
            array_push($rechteSpalte, HTML::buildInput('text', 'bisTag', HTML::extractDateFromDateTime($dbWerte['bis'])));
            array_push($rechteSpalte, HTML::buildInput('text', 'bisZeit', HTML::extractTimeFromDateTime($dbWerte['bis'])));
            array_push($rechteSpalte, HTML::buildButton('OK', 'ok', 'updateAusleihe', 'OK'));
        } else {
            array_push($rechteSpalte, HTML::buildDropDown('fahrzeug', '1', $options, NULL, 'fahrzeug'));
            array_push($rechteSpalte, HTML::buildDropDown('mitarbeiter', '1', $options2, NULL, 'mitarbeiter'));
            array_push($rechteSpalte, HTML::buildInput('text', 'vonTag', '', NULL, 'vonTag'));
            array_push($rechteSpalte, HTML::buildInput('text', 'vonZeit', '', NULL, 'vonZeit'));
            array_push($rechteSpalte, HTML::buildInput('text', 'bisTag', '', NULL, 'bisTag'));
            array_push($rechteSpalte, HTML::buildInput('text', 'bisZeit', '', NULL, 'bisZeit'));
            array_push($rechteSpalte, HTML::buildButton('OK', 'ok', 'insertAusleihe', 'OK'));
        }
        $returnOut = HTML::buildFormularTable($linkeSpalte, $rechteSpalte);
        return $returnOut;
    }

}
