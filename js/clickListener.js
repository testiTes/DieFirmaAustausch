$(document).ready(function () {
    $('button').off('click');
    $('button').click(function () {
        var inputs = document.getElementsByTagName('input');
        var drops = document.getElementsByTagName('select');

        if (this.value == 'bearbeiten') {
            var aTableEdit = getMenuOptions(this.className);
        }

        if (this.className == "updateHersteller") {
            var id = inputs['id'].value;
            var hersteller = inputs['name'].value;

            $.post("index.php",
                    {
                        ajax: "true",
                        action: "update",
                        area: "Hersteller",
                        view: "listeHersteller",
                        uHersteller: hersteller,
                        uheid: id
                    },
            function (data, status) {
                $('#content').html(data);
            });
        }

        if (this.className == "updateAuto") {
            var id = inputs['id'].value;
            var hersteller_id = drops['hersteller'].value;
            var auto = inputs['autoName'].value;
            var kennzeichen = inputs['kennzeichen'].value;

            $.post("index.php",
                    {
                        ajax: "true",
                        action: "update",
                        area: "Auto",
                        view: "listeAuto",
                        auto: auto,
                        hersteller_id: hersteller_id,
                        kennzeichen: kennzeichen,
                        uauid: id
                    },
            function (data, status) {
                $('#content').html(data);
            });
        }

        if (this.className == "insertHersteller") {
            var hersteller = inputs['name'].value;

            $.post("index.php",
                    {
                        ajax: "true",
                        action: "insert",
                        area: "Hersteller",
                        view: "listeHersteller",
                        hersteller: hersteller
                    },
            function (data, status) {
                $('#content').html(data);
            });
        }

        if (this.className == "insertAuto") {
            var hersteller_id = drops['hersteller'].value;
            var auto = inputs['autoName'].value;
            var kennzeichen = inputs['kennzeichen'].value;

            $.post("index.php",
                    {
                        ajax: "true",
                        action: "insert",
                        area: "Auto",
                        view: "listeAuto",
                        auto: auto,
                        hersteller_id: hersteller_id,
                        kennzeichen: kennzeichen
                    },
            function (data, status) {
                $('#content').html(data);
            });
        }

        if (this.className == "updateProjektMitarbeiter") {
            var id = inputs['id'].value;
            var projekt = drops['projekt'].value;
            var mitarbeiter = drops['mitarbeiter'].value;
            var vonDate = inputs['vonTag'].value + ' ' + inputs['vonZeit'].value;
            var bisDate = inputs['bisTag'].value + ' ' + inputs['bisZeit'].value;

            $.post("index.php",
                    {
                        ajax: "true",
                        action: "update",
                        area: "ProjektMitarbeiter",
                        view: "listeProjektMitarbeiter",
                        projekt: projekt,
                        mitarbeiter: mitarbeiter,
                        von: vonDate,
                        bis: bisDate,
                        upmid: id
                    },
            function (data, status) {
                $('#content').html(data);
            });
        }

        if (this.className == 'loeschenProjektMitarbeiter' && this.value == 'loeschen') {
            alert(this.className);
            alert(this.id);
            alert(this.value);
            var lpmid = this.id;
            $.post("index.php",
                    {
                        ajax: "true",
                        action: "delete",
                        area: "ProjektMitarbeiter",
                        view: "listeProjektMitarbeiter",
                        lpmid: lpmid
                    },
            function (data, status) {
                $('#content').html(data);
            });
        }

        if (this.className == "insertProjektMitarbeiter") {
            var projekt = drops['projekt'].value;
            var mitarbeiter = drops['mitarbeiter'].value;
            var vonDate = inputs['vonTag'].value + ' ' + inputs['vonZeit'].value;
            var bisDate = inputs['bisTag'].value + ' ' + inputs['bisZeit'].value;

            $.post("index.php",
                    {
                        ajax: "true",
                        action: "insert",
                        area: "ProjektMitarbeiter",
                        view: "listeProjektMitarbeiter",
                        projekt: projekt,
                        mitarbeiter: mitarbeiter,
                        von: vonDate,
                        bis: bisDate
                    },
            function (data, status) {
                $('#content').html(data);
            });
        }

        if (this.id == "ha") {
            var action = inputs['action'].value;
            var area = inputs['area'].value;
            var view = inputs['view'].value;
            var id = inputs['id'].value;
            $.post("index.php",
                    {
                        ajax: "true",
                        action: action,
                        area: area,
                        view: view,
                        id: id
                    },
            function (data, status) {
                $('#content').html(data);
            });
        }

        $.post("index.php",
                {
                    ajax: "true",
                    action: aTableEdit[0],
                    area: aTableEdit[1],
                    view: aTableEdit[2],
                    id: this.id
                },
        function (data, status) {
            $('#content').html(data);
        });
    });

    $('a.menuItem').off('click');
    $('a.menuItem').click(function () {
        var aMenuOptions = getMenuOptions(this.id);
        $.post("index.php",
                {
                    ajax: "true",
                    action: aMenuOptions[0],
                    area: aMenuOptions[1],
                    view: aMenuOptions[2]
                },
        function (data, status) {
            $('#content').html(data);
        });
    });

    function getMenuOptions(id) {
        var options = [];
        switch (id) {

            //Show

            case 'menuMitarbeiterAnzeige' :
                options = ['showList', 'Mitarbeiter', 'listeMitarbeiter'];
                break;
            case 'menuAbteilungAnzeigen' :
                options = ['showList', 'Abteilung', 'listeAbteilung'];
                break;
            case 'menuHerstellerAnzeigen' :
                options = ['showList', 'Hersteller', 'listeHersteller'];
                break;
            case 'menuAutosAnzeigen' :
                options = ['showList', 'Auto', 'listeAuto'];
                break;
            case 'menuAusleihe' :
                options = ['showList', 'Ausleihe', 'listeAusleihe'];
                break;
            case 'menuProjekteAnzeigen' :
                options = ['showList', 'Projekt', 'listeProjekt'];
                break;
            case 'menuProjektMitarbeiterAnzeigen' :
                options = ['showList', 'ProjektMitarbeiter', 'listeProjektMitarbeiter'];
                break;

            //Insert

            case 'menuMitarbeiterNeuAnlegen' :
                options = ['showInsert', 'Mitarbeiter', 'formularMitarbeiter'];
                break;
            case 'menuAbteilungNeuAnlegen' :
                options = ['showInsert', 'Abteilung', 'formularAbteilung'];
                break;
            case 'menuHerstellerNeuAnlegen' :
                options = ['showInsert', 'Hersteller', 'formularHersteller'];
                break;
            case 'menuAutoNeuAnlegen' :
                options = ['showInsert', 'Auto', 'formularAuto'];
                break;
            case 'menuAusleiheNeuAnlegen' :
                options = ['showInsert', 'Ausleihe', 'formularAusleihe'];
                break;
            case 'menuProjektNeuAnlegen' :
                options = ['showInsert', 'Projekt', 'formularProjekt'];
                break;
            case 'menuProjektMitarbeiterNeuAnlegen' :
                options = ['showInsert', 'ProjektMitarbeiter', 'formularProjektMitarbeiter'];
                break;
                
            //Update

            case 'bearbeitenMitarbeiter' :
                options = ['showUpdate', 'Mitarbeiter', 'formularMitarbeiter'];
                break;
            case 'bearbeitenAbteilung' :
                options = ['showUpdate', 'Abteilung', 'formularAbteilung'];
                break;
            case 'bearbeitenHersteller' :
                options = ['showUpdate', 'Hersteller', 'formularHersteller'];
                break;
            case 'bearbeitenAuto' :
                options = ['showUpdate', 'Auto', 'formularAuto'];
                break;
            case 'bearbeitenAusleihe' :
                options = ['showUpdate', 'Ausleihe', 'formularAusleihe'];
                break;
            case 'bearbeitenProjekt' :
                options = ['showUpdate', 'Projekt', 'formularProjekt'];
                break;
            case 'bearbeitenProjektMitarbeiter' :
                options = ['showUpdate', 'ProjektMitarbeiter', 'formularProjektMitarbeiter'];
                break;                
                
            //Default also Standard

            default:
                options = ['standard', 'standard', 'standard'];
                break;
        }

        return options;
    }

    $('#cssmenu > ul > li > a').off('click');
    $('#cssmenu > ul > li > a').click(function () {
//        alert(this.id);
        $('#cssmenu li').removeClass('active');
        $(this).closest('li').addClass('active');
        var checkElement = $(this).next();
        if ((checkElement.is('ul')) && (checkElement.is(':visible'))) {
//            alert(this.id);
            $(this).closest('li').removeClass('active');
            checkElement.slideUp('normal');
        }
        if ((checkElement.is('ul')) && (!checkElement.is(':visible'))) {
//            alert(this.id);
            $('#cssmenu ul ul:visible').slideUp('normal');
            checkElement.slideDown('normal');
        }
        if ($(this).closest('li').find('ul').children().length === 0) {
            return true;
        } else {
            return false;
        }
    });
});