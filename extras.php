<?php
/* 
 * für die options für die dropdown menüs
 * hier einige vorlagen später auch noch in die HTML umzusetzen
 */

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
?>
<!-- für den clicklistener -->
<script>

    $("button").click(Mitarbeiter);
    function Mitarbeiter() {
        $.post("index.php",
                {
                    ajax: "true",
                    action: "showList",
                    area: "Auto",
                    view: "listeAuto",
                    id: this.id
                },
        function (data, status) {
            $('#content').html(data);
            $("button").click(Mitarbeiter2);
        });
    }
    function Mitarbeiter2() {
        $.post("index.php",
                {
                    ajax: "true",
                    action: "showList",
                    area: "Mitarbeiter",
                    view: "listeMitarbeiter",
                    id: this.id
                },
        function (data, status) {
            $('#content').html(data);
            $("button").click(Mitarbeiter);
        });
    }

    $("button").click(function () {
        alert(this.id);
//        alert(this.value);
        $.post("index.php",
                {
                    ajax: "true",
//                    action: "showList",
                    action: "showInsert",
                    area: "ProjektMitarbeiter",
//                    view: "listeAuto",
                    view: "formularProjektMitarbeiter",
                    id: this.id
                },
        function (data, status) {
            $('#content').html(data);
        });
    });
</script>