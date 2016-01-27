<?php

include './view/header.php';
?>
<script type="text/javascript" src="./js/clickListener.js"></script>
<div id='menuleft'>
    <div id='cssmenu'>
        <ul>
            <li class='active'><a href='' id="menuHome"><span>Home</span></a></li>
            <li class='has-sub'><a href='#' id="menuMitarbeiter"><span>Mitarbeiter</span></a>
                <ul>
                    <li><a class="menuItem" view="Mitarbeiter" href='#' id="menuMitarbeiterAnzeige"><span>Anzeigen</span></a></li>
                    <li class='last'><a class="menuItem" id="menuMitarbeiterNeuAnlegen" href='#'><span>neu Anlegen</span></a></li>
                </ul>
            </li>
            <li class='has-sub'><a href='#' id="menuAbteilungen"><span>Abteilungen</span></a>
                <ul>
                    <li><a class="menuItem" href='#' id="menuAbteilungAnzeigen"><span>Anzeigen</span></a></li>
                    <li class='last'><a class="menuItem" href='#' id="menuAbteilungNeuAnlegen"><span>neu Anlegen</span></a></li>
                </ul>
            </li>
            <li class='has-sub'><a href='#' id="menuFuhrpark"><span>Fuhrpark</span></a>
                <ul>
                    <li><a class="menuItem" href='#' id="menuFuhrparkAnzeigen"><span>Anzeigen</span></a></li>
                    <li><a class="menuItem" href='#' id="menuFuhrparkAusleihe"><span>Ausleihen</span></a></li>
                    <li class='last'><a class="menuItem" href='#' id="menuFuhrparkNeuAnlegen"><span>neu Anlegen</span></a></li>
                </ul>
            </li>
            <li class='has-sub'><a href='#' id="menuProjekte"><span>Projekte</span></a>
                <ul>
                    <li><a class="menuItem" href='#' id="menuProjekteAnzeigen"><span>Anzeigen</span></a></li>
                    <li class='last'><a class="menuItem" href='#' id="menuProjekteNeuAnlegen"><span>neu Anlegen</span></a></li>
                </ul>
            </li>
            <li class='has-sub'><a href='#' id="menuMitarbeiterToProjekt"><span>Mitarbeiter zu Projekt</span></a>
                <ul>
                    <li><a class="menuItem" href='#' id="menuMitarbeiterToProjektAnzeigen"><span>Anzeigen</span></a></li>
                    <li class='last'><a class="menuItem" href='#' id="menuMitarbeiterToProjektNeuAnlegen"><span>neu Anlegen</span></a></li>
                </ul>
            </li>
            <li class='last'><a href='#' id="menuKontakt"><span>Kontakt</span></a></li>
        </ul>
    </div>
</div>

<div id="content">
</div>
<!--<button type="button" id="btnEingabe" name="btnEingabe" value="Eingabe">Eingabe</button>-->
<?php

include './view/footer.php';
?>