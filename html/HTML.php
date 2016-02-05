<?php

/**
 * Description of HTML
 *
 * @author rolfhaeckel
 */
class HTML {

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

    public static function buildButton($label, $id = 'NULL', $class = 'NULL', $value = 'NULL') {
        return '<button type="button" id="' . $id . '" class="' . $class . '" value="' . $value . '">' . $label . '</button>';
    }

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

    // datum aus datenbank wird in deutsches format(tag/monat/jahr) ausgegeben
    public static function mysqlToGerman($date) {
        return implode('.', array_reverse(explode('-', $date)));
    }

    // datum wird wieder in datenbank format(jahr/monat/tag) ausgegeben
    public static function germanToMysql($date) {
        return implode('-', array_reverse(explode('.', $date)));
    }

    public static function dateTimeToDateAndTime($date) {
        $datum = array_reverse(explode(' ', $date));
        $datum[1] = implode('.', array_reverse(explode('-', $datum[1])));
        return $datum[1] . ' ' . $datum[0];
    }

    public static function dateAndTimeToDateTime($date) {
        $datum = array_reverse(explode(' ', $date));
        $datum[1] = implode('-', array_reverse(explode('.', $datum[1])));
        return $datum[1] . ' ' . $datum[0];
    }

    // gibt nur das Datum aus dem DateTime Feld aus
    public static function extractDateFromDateTime($date) {
        $datum = array_reverse(explode(' ', $date));
        $datum[1] = implode('.', array_reverse(explode('-', $datum[1])));
        return $datum[1];
    }

    // gibt nur die Zeit aus dem DateTime Feld aus
    public static function extractTimeFromDateTime($date) {
        $datum = array_reverse(explode(' ', $date));
        $datum[1] = implode('.', array_reverse(explode('-', $datum[1])));
        return $datum[0];
    }

}

?>