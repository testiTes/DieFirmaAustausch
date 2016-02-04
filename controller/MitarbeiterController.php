<?php

/**
 * Description of MitarbeiterController
 *
 * @author Teilnehmer
 */
class MitarbeiterController {

    public static function doAction($action, &$view, $id) {
        switch ($action) {

            case 'showList':
                $out = Mitarbeiter::getAll();
                $out = self::transform($out);
                break;

            case 'showUpdate':
                $out = Mitarbeiter::getById($id);
                $out = self::transformUpdate($out);
                break;

            case 'showInsert':
                $out = self::transformUpdate();
                break;

            case 'update':
                $vorgesetzter = ($_POST['vorgesetzter_id']) ? Mitarbeiter::getById($_POST['vorgesetzter_id']) : NULL;
                $out = new Mitarbeiter($_POST['vorname'], $_POST['nachname'], $_POST['geschlecht'], HTML::germanToMysql($_POST['geburtsdatum']), Abteilung::getById($_POST['abteilung_id']), $_POST['stundenlohn'], $vorgesetzter, $_POST['umaid']);
                $out = Mitarbeiter::update($out);
                $out = Mitarbeiter::getAll();
                $out = self::transform($out);
                break;

            case 'insert':
                $vorgesetzter = ($_POST['vorgesetzter_id']) ? Mitarbeiter::getById($_POST['vorgesetzter_id']) : NULL;
                $out = new Mitarbeiter($_POST['vorname'], $_POST['nachname'], $_POST['geschlecht'], HTML::germanToMysql($_POST['geburtsdatum']), Abteilung::getById($_POST['abteilung_id']), $_POST['stundenlohn'], $vorgesetzter, NULL);
                $out = Mitarbeiter::insert($out);
                $out = Mitarbeiter::getAll();
                $out = self::transform($out);
                break;

            case 'delete':
                $out = $_POST['lmaid'];
                $out = Mitarbeiter::delete($out);
                $out = Mitarbeiter::getAll();
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
        foreach ($out as $mitarbeiter) {
            $returnOut[$i]['vorname'] = $mitarbeiter->getVorname();
            $returnOut[$i]['nachname'] = $mitarbeiter->getNachname();
            $returnOut[$i]['bearbeiten'] = HTML::buildButton('bearbeiten', $mitarbeiter->getId(), 'bearbeitenMitarbeiter', 'bearbeiten');
            $returnOut[$i]['loeschen'] = HTML::buildButton('löschen', $mitarbeiter->getId(), 'loeschenMitarbeiter', 'loeschen');
            $i++;
        }
        return $returnOut;
    }

    private static function transformUpdate($out = NULL) {
        $returnOut = [];
        $linkeSpalte = [];
        for ($i = 0; $i < count(Mitarbeiter::getNames()); $i++) {
            array_push($linkeSpalte, Mitarbeiter::getNames()[$i]);
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
        //options für die abteilungen   
        $abt = Abteilung::getAll();
        $options = [];

        // zum abwählen
        $options[0] = ['value' => 0, 'label' => ''];
        $hatAbteilung = FALSE;
        foreach ($abt as $o) {
            $options[$o->getId()] = ['value' => $o->getId(), 'label' => $o->getName()];
            if ($out !== NULL) {
                if ($o->getId() === $out->getAbteilung()->getId()) {
                    $options[$o->getId()]['selected'] = TRUE;
                    $hatAbteilung = TRUE;
                }
            }
        }
        if ($hatAbteilung == FALSE) {
            $options[0]['selected'] = TRUE;
        }

        // options für die vorgesetzten
        $vorgesetzte = Mitarbeiter::getAll();

        $options2 = [];

        // zum abwählen
        $options2[0] = ['value' => 0, 'label' => ''];
        $hatVorgesetzte = FALSE;
        foreach ($vorgesetzte as $o) {
            $options2[$o->getId()] = ['value' => $o->getId(), 'label' => $o->getVorname() . ' ' . $o->getNachname()];
            if ($out !== NULL) {
                if ($o->getVorgesetzter() !== NULL) {
                    if ($o->getId() === $out->getId()) {
                        $options2[$o->getVorgesetzter()->getId()]['selected'] = TRUE;
                        $hatVorgesetzte = TRUE;
                    }
                } else {
                    $options2[0]['selected'] = TRUE;
                }
            }
        }
        if ($hatVorgesetzte == FALSE) {
            $options2[0]['selected'] = TRUE;
        }

        // radio $options erstellen
        $radioOptions = [];
        $radioOption = [];

        if ($out !== Null) {
            $radioOption['label'] = 'weibl.';
            if ($out->getGeschlecht() === 'w') {
                $radioOption['checked'] = TRUE;
            }
            $radioOption['value'] = 'w';
            array_push($radioOptions, $radioOption);

            $radioOption = [];
            $radioOption['label'] = 'männl.';
            if ($out->getGeschlecht() === 'm') {
                $radioOption['checked'] = TRUE;
            }
            $radioOption['value'] = 'm';
            array_push($radioOptions, $radioOption);
        } else {
            $radioOption['label'] = 'weibl.';
            $radioOption['checked'] = TRUE;
            $radioOption['value'] = 'w';
            array_push($radioOptions, $radioOption);
            $radioOption['label'] = 'männl.';
            $radioOption['checked'] = NULL;
            $radioOption['value'] = 'm';
            array_push($radioOptions, $radioOption);
        }

        $rechteSpalte = [];
        if ($out !== NULL) {
            array_push($rechteSpalte, HTML::buildInput('text', 'vorname', $dbWerte['vorname'], NULL, 'vorname'));
            array_push($rechteSpalte, HTML::buildInput('text', 'nachname', $dbWerte['nachname'], NULL, 'nachname'));
            array_push($rechteSpalte, HTML::buildRadio('geschlecht', $radioOptions, FALSE));
            array_push($rechteSpalte, HTML::buildInput('text', 'geburtsdatum', HTML::mysqlToGerman($dbWerte['geburtsdatum']), NULL, 'geburtsdatum'));
            array_push($rechteSpalte, HTML::buildDropDown('abteilung', '1', $options, NULL, 'abteilung'));
            array_push($rechteSpalte, HTML::buildInput('text', 'stundenlohn', $dbWerte['stundenlohn'], NULL, 'stundenlohn'));
            array_push($rechteSpalte, HTML::buildDropDown('vorgesetzter', '1', $options2, NULL, 'vorgesetzter'));
            array_push($rechteSpalte, HTML::buildButton('OK', 'ok', 'updateMitarbeiter', 'OK'));
        } else {
            array_push($rechteSpalte, HTML::buildInput('text', 'vorname', '', NULL, 'vorname'));
            array_push($rechteSpalte, HTML::buildInput('text', 'nachname', '', NULL, 'nachname'));
            array_push($rechteSpalte, HTML::buildRadio('geschlecht', $radioOptions, FALSE));
            array_push($rechteSpalte, HTML::buildInput('text', 'geburtsdatum', '', NULL, 'geburtsdatum'));
            array_push($rechteSpalte, HTML::buildDropDown('abteilung', '1', $options, NULL, 'abteilung'));
            array_push($rechteSpalte, HTML::buildInput('text', 'stundenlohn', '', NULL, 'stundenlohn'));
            array_push($rechteSpalte, HTML::buildDropDown('vorgesetzter', '1', $options2, NULL, 'vorgesetzter'));
            array_push($rechteSpalte, HTML::buildButton('OK', 'ok', 'insertMitarbeiter', 'OK'));
        }
        $returnOut = HTML::buildFormularTable($linkeSpalte, $rechteSpalte);
        return $returnOut;
    }

}
