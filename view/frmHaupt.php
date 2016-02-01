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
                    <li><a class="menuItem" href='#' id="menuHerstellerAnzeigen"><span>Hersteller Anzeigen</span></a></li>
                    <li><a class="menuItem" href='#' id="menuAutosAnzeigen"><span>Autos Anzeigen</span></a></li>
                    <li><a class="menuItem" href='#' id="menuAusleihe"><span>Ausleihen</span></a></li>
                    <li class='last'><a class="menuItem" href='#' id="menuHerstellerNeuAnlegen"><span>Hersteller neu Anlegen</span></a></li>
                    <li class='last'><a class="menuItem" href='#' id="menuAutoNeuAnlegen"><span>Auto neu Anlegen</span></a></li>
                    <li class='last'><a class="menuItem" href='#' id="menuAusleiheNeuAnlegen"><span>Ausleihe neu Anlegen</span></a></li>
                </ul>
            </li>
            <li class='has-sub'><a href='#' id="menuProjekte"><span>Projekte</span></a>
                <ul>
                    <li><a class="menuItem" href='#' id="menuProjekteAnzeigen"><span>Anzeigen</span></a></li>
                    <li class='last'><a class="menuItem" href='#' id="menuProjektNeuAnlegen"><span>neu Anlegen</span></a></li>
                </ul>
            </li>
            <li class='has-sub'><a href='#' id="menuMitarbeiterToProjekt"><span>Projekt Mitarbeiter</span></a>
                <ul>
                    <li><a class="menuItem" href='#' id="menuProjektMitarbeiterAnzeigen"><span>Anzeigen</span></a></li>
                    <li class='last'><a class="menuItem" href='#' id="menuProjektMitarbeiterNeuAnlegen"><span>neu Anlegen</span></a></li>
                </ul>
            </li>
            <li class='last'><a href='#' id="menuKontakt"><span>Kontakt</span></a></li>
        </ul>
    </div>
</div>
<div id="content">
</div>
<?php

include './view/footer.php';
?>