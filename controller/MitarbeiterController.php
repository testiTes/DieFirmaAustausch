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
            $returnOut[$i]['bearbeiten'] = HTML::buildButton('bearbeiten', $mitarbeiter->getId());
            $returnOut[$i]['loeschen'] = HTML::buildButton('löschen', $mitarbeiter->getId());
            $i++;
        }
        return $returnOut;
    }

    private static function transformUpdate($out) {
        $returnOut = [];
        $linkeSpalte = [];
        for ($i = 0; $i < count(Mitarbeiter::getNames()); $i++) {
            array_push($linkeSpalte, Mitarbeiter::getNames()[$i]);
        }
        array_push($linkeSpalte, HTML::buildInput('hidden', 'id', $out->getId()));
        $dbWerte = json_decode(json_encode($out), true);
        // überführe $dbWerte in rechte Spalte

        //options für die abteilungen
        $abteilung = $out;
        $abt = Abteilung::getAll();

        $options = [];

        // zum abwählen
        $options[0] = ['value' => 0, 'label' => ''];
        foreach ($abt as $o) {
            $options[$o->getId()] = ['value' => $o->getId(), 'label' => $o->getName()];
            if ($o->getId() === $abteilung->getAbteilung()->getId()) {
                $options[$o->getId()]['selected'] = TRUE;
            }
        }   
   
        // options für die vorgesetzten
        $vorgesetzte = Mitarbeiter::getAll();

        $options2 = [];

        // zum abwählen
        $options2[0] = ['value' => 0, 'label' => ''];
        $hatVorgesetzte = FALSE;
        foreach ($vorgesetzte as $o) {
            $options2[$o->getId()] = ['value' => $o->getId(), 'label' => $o->getVorname() . ' ' . $o->getNachname()];
            if ($o->getVorgesetzter() !== NULL) {
                if ($o->getId() === $out->getId()) {
                    $options2[$o->getVorgesetzter()->getId()]['selected'] = TRUE;
                    $hatVorgesetzte = TRUE;
                }
            }
        }
        if ($hatVorgesetzte == FALSE) {
            $options2[0]['selected'] = TRUE;
        }

        // radio $options erstellen
        $radioOptions = [];
        $radioOption = [];
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

        $rechteSpalte = [];
        array_push($rechteSpalte, HTML::buildInput('text', 'vorname', $dbWerte['vorname']));
        array_push($rechteSpalte, HTML::buildInput('text', 'nachname', $dbWerte['nachname']));
        array_push($rechteSpalte, HTML::buildRadio('geschlecht', $radioOptions, FALSE));
        array_push($rechteSpalte, HTML::buildInput('text', 'geburtsdatum', HTML::mysqlToGerman($dbWerte['geburtsdatum'])));
        array_push($rechteSpalte, HTML::buildDropDown('abteilung', '1', $options));
        array_push($rechteSpalte, HTML::buildInput('text', 'stundenlohn', $dbWerte['stundenlohn']));
        array_push($rechteSpalte, HTML::buildDropDown('vorgesetzter', '1', $options2));
        array_push($rechteSpalte, HTML::buildButton('OK', 'ok', '', 'OK'));

        $returnOut = HTML::buildFormularTable($linkeSpalte, $rechteSpalte);
        return $returnOut;
    }

}
