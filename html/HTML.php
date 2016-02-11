<?php

/**
 * Description of HTML
 * dient zum Erstellen von form-tags abhängig von übergebenen Parametern
 *
 * @author rolfhaeckel
 */
class HTML {

    /**
     * 
     * @param array $tableHead Spaltenüberschrift einer Tabelle
     * @param array $tableBody 2-dim Array mit Werten (data) für eine Tabelle
     * @return string
     * @throws Exception
     * 
     * erzeugt table: thead mit tr und th, tbody mit tr und td 
     * 
     * @example 
     * $tableHead = ['A','B'];
     * $tableBody = [['1','2'],['3','4'];
     * 
     * buildListTable erstellt HTML-code für
     *  A B
     *  1 2
     *  3 4
     */
    public static function buildListTable(Array $tableHead, Array $tableBody) {
        // falls $tableBody ein Array mit Objekten ist, wird jedes Objekt in ein asso-Array gewandelt
        if (is_object($tableBody[0])) {
            $tableBody = json_decode(json_encode($tableBody), true);
        }
        if (count($tableHead) !== count($tableBody[0])) {
            throw new Exception('Spaltenanzahl von Tablehead stimmt nicht mit Spaltenanzahl Tablebody überein');
        }
        $html = '<table cellspacing="2" cellpadding="2" border="1">' . '<thead>' . '<tr>';
        foreach ($tableHead as $columnName) {
            $html .= '<th>' . $columnName . '</th>';
        }
        $html .= '</tr>' . '</thead>' . '<tbody>';
        foreach ($tableBody as $tableRow) {
            $html .= '<tr>';
            foreach ($tableRow as $tableData) {
                $html .= '<td>' . $tableData . '</td>';
            }
            $html .= '</tr>';
        }
        $html .= '</tbody>';
        return $html;
    }

    /**
     * 
     * @param array $linkeSpalte
     * @param array $rechteSpalte
     * @return string
     * @throws Exception
     * 
     * erzeugt table ohne thead, aber mit tbody, tr und td
     * $linkeSpalte, $rechteSpalte sind Arrays für den Eintrag in die jeweilge Spalte
     * 
     * @example
     * $linkeSpalte = ['Vorname : ', 'Nachname:'];
     * $rechteSpalte = ['Peter', 'Pan'];
     * 
     * buildFormularTable erstellt HTML-code für
     *  Vorname : Peter
     *  Nachname: Pan
     */
    public static function buildFormularTable(Array $linkeSpalte, Array $rechteSpalte) {
        if (count($linkeSpalte) !== count($rechteSpalte)) {
            throw new Exception('Arrays in HTML::buildFormularTable sind nicht gleich groß');
        }
        $html = '<table cellspacing="2" cellpadding="2" border="1"><tbody>' . "\n";
        for ($i = 0; $i < count($linkeSpalte); $i++) {
            $html .= '<tr>' . "\n";
            $html .= '<td>' . $linkeSpalte[$i] . '</td>' . "\n";
            $html .= '<td>' . $rechteSpalte[$i] . '</td>' . "\n";
            $html .= '</tr>' . "\n";
        }
        $html .= '</tbody></table>' . "\n";
        return $html;
    }

    /**
     * erstellt button-tag
     * 
     * @param string $label Ausgabe zum Button (Aufschrift)
     * @param int $id für js
     * @param string $class für js 
     * @param string $value für js
     * @return string
     */
    public static function buildButton($label, $id = 'NULL', $class = 'NULL', $value = 'NULL') {
        return '<button type="button" id="' . $id . '" class="' . $class . '" value="' . $value . '">' . $label . '</button>';
    }

    /**
     * erstellt input-tag
     * 
     * @param string $type text, hidden oder password
     * @param string $name Name der Übergabevariable
     * @param string $value Wert der Vorbelegung
     * @param string $readonly falls Eintrag nicht durch user geändert werden darf
     * @param string $id für js
     * @param string $class für js
     * @param string $placeholder Voreintrag grau in inputfeldern
     */
    public static function buildInput($type, $name, $value, $readonly = NULL, $id = NUll, $class = NULL, $placeholder = NULL) {
        $html = '<input type="';
        $html .= $type;
        $html .= '" name="';
        $html .= $name;
        $html .= '" value="';
        $html .= $value;
        $html .= '"';
        if ($readonly !== NULL) {
            $html .= ' readonly="readonly"';
        }
        if ($id !== NULL) {
            $html .= ' id="' . $id . '"';
        }
        if ($class !== NULL) {
            $html .= ' class="' . $class . '"';
        }
        if ($placeholder !== NULL) {
            $html .= ' placeholder ="' . $placeholder . '"';
        }
        $html .= ' />';
        return $html;
    }

    /**
     * 
     * @param string $name Übergabevariable
     * @param int $size Höhe des DropDownMenus
     * @param array $options 2-dim Array, je Ausgabezeile asso-Keys 'value','label' optional 'selected'
     *        wird gebildet in Option::buildOptions($className, $selected = NULL, $zeroOption = NULL)
     * @param boolean $multiple Mehrfachauswahl noch nicht implementiert
     * @param type $id für js
     * @param type $class für js
     * @return string
     */
    public static function buildDropDown($name, $size, Array $options, $multiple = NULL, $id = NULL, $class = NULL) {

        $html = '<select name="' . $name . '"';
        $html .= ' size="' . $size . '"';
        if ($multiple !== NULL) {
            $html .= ' multiple="multiple"';
        }
        if ($id !== NULL) {
            $html .= ' id="' . $id . '"';
        }
        if ($class !== NULL) {
            $html .= ' class="' . $class . '"';
        }
        $html .= '>' . "\n";

        foreach ($options as $option) {
            $html .= '<option';
            if (isset($option['value'])) {
                $html .= ' value="' . $option['value'] . '"';
            }
            if (isset($option['selected'])) {
                $html .= ' selected';
            }
            $html .= ">\n";
            $html .= $option['label'];
            $html .= '</option>' . "\n";
        }
        $html .= '</select>';
        return $html;
    }

    /**
     * 
     * @param string $groupname
     * @param array $options 2-dim Array, jede option enthält asso-Keys label, value 
     * und optional checked
     * @param boolean $buttonLeft ob button links vom label ist
     * @return string
     */
    public static function buildRadio($groupname, Array $options, $buttonLeft = TRUE) {
        $html = '';
        foreach ($options as $option) {
            if ($buttonLeft === FALSE) {
                $html .= ' ' . $option['label'] . ' ';
            }
            $html .= '<input type="radio" name="' . $groupname . '" value="';
            $html .= $option['value'] . '"';
            if (isset($option['checked'])) {
                $html .= ' checked="checked"';
            }
            $html .= '/>';
            if ($buttonLeft === TRUE) {
                $html .= ' ' . $option['label'] . ' ';
            }
            $html .= "\n";
        }
        return $html;
    }

    // datum aus datenbank(YYYY-MM-DD) wird in deutsches format(DD.MM.YYYY) überführt
    public static function mysqlToGerman($date) {
        return implode('.', array_reverse(explode('-', $date)));
    }

    // datum deutsch (DD.MM.YYYY) wird in datenbank format(YYYY-MM-DD) überführt
    public static function germanToMysql($date) {
        return implode('-', array_reverse(explode('.', $date)));
    }

    // datumzeit db format (YYYY-MM-DD hh:mm:ss) wird in deutsch(DD.MM.YYYY hh:mm:ss) überführt 
    public static function dateTimeToDateAndTime($date) {
        $datum = explode(' ', $date);
        $datum[0] = self::germanToMysql($datum[0]);
        return implode(' ', $datum);
    }

    // datumzeit  deutsch(DD.MM.YYYY hh:mm:ss) wird in db format (YYYY-MM-DD hh:mm:ss) überführt
    public static function dateAndTimeToDateTime($date) {
        $datum = explode(' ', $date);
        $datum[0] = self::mysqlToGerman($datum[0]);
        return implode(' ', $datum);
    }

    // gibt nur das Datum(DD.MM.YYYY) aus dem DateTime Feld(YYYY-MM-DD hh:mm:ss) aus
    public static function extractDateFromDateTime($date) {
        $datum = explode(' ', $date);
        return self::mysqlToGerman($datum[0]);
    }

    // gibt nur die Zeit(hh:mm:ss) aus dem DateTime Feld(YYYY-MM-DD hh:mm:ss) aus
    public static function extractTimeFromDateTime($date) {
        $datum = explode(' ', $date);
        return $datum[1];
    }

}

?>