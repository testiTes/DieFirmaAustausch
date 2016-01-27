<script type="text/javascript" src="./js/clickListener.js"></script>
<?php
echo HTML::buildListTable(['Hersteller', 'Modell', 'Kennzeichen', 'Vorname', 'Nachname', 'Ausleihe Von', 'Ausleihe Bis', 'Bearbeiten', 'Löschen'], $out);
?>