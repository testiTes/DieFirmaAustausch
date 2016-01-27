<script type="text/javascript" src="./js/clickListener.js"></script>

<?php
//echo '<pre>';
//print_r($out);
//echo '</pre>';
echo HTML::buildListTable(['Abteilung', 'Bearbeiten', 'Löschen'], $out);
?>