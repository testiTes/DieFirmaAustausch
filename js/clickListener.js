$(document).ready(function () {
    $('button').off('click');
    $('button').click(function () {
        var inputs = document.getElementsByTagName('input');
        var drops = document.getElementsByTagName('select');
        if (this.value === 'bearbeiten') {
            var aTableEdit = getMenuOptions(this.className);
        }

// Ausleihe insert/update/delete
// update
        if (this.className === 'updateAusleihe') {
            var id = inputs['id'].value;
            var fahrzeug = drops['fahrzeug'].value;
            var mitarbeiter = drops['mitarbeiter'].value;
            var vonDate = inputs['vonTag'].value + ' ' + inputs['vonZeit'].value;
            var bisDate = inputs['bisTag'].value + ' ' + inputs['bisZeit'].value;
            $.post("index.php",
                    {
                        ajax: "true",
                        action: "update",
                        area: "Ausleihe",
                        view: "listeAusleihe",
                        fahrzeug: fahrzeug,
                        mitarbeiter: mitarbeiter,
                        von: vonDate,
                        bis: bisDate,
                        uausid: id
                    },
            function (data, status) {
                $('#content').html(data);
            });
        }
// insert
        if (this.className === 'insertAusleihe') {
            var fahrzeug = drops['fahrzeug'].value;
            var mitarbeiter = drops['mitarbeiter'].value;
            var vonDate = inputs['vonTag'].value + ' ' + inputs['vonZeit'].value;
            var bisDate = inputs['bisTag'].value + ' ' + inputs['bisZeit'].value;
            $.post("index.php",
                    {
                        ajax: "true",
                        action: "insert",
                        area: "Ausleihe",
                        view: "listeAusleihe",
                        fahrzeug: fahrzeug,
                        mitarbeiter: mitarbeiter,
                        von: vonDate,
                        bis: bisDate
                    },
            function (data, status) {
                $('#content').html(data);
            });
        }
// delete
        if (this.className === 'loeschenAusleihe' && this.value === 'loeschen') {
            var lausid = this.id;
            $.post("index.php",
                    {
                        ajax: "true",
                        action: "delete",
                        area: "Ausleihe",
                        view: "listeAusleihe",
                        lausid: lausid
                    },
            function (data, status) {
                $('#content').html(data);
            });
        }

// Projekt insert/update/delete
// update
        if (this.className === 'updateProjekt') {
            var id = inputs['id'].value;
            var projekt = inputs['projekt'].value;
            $.post("index.php",
                    {
                        ajax: "true",
                        action: "update",
                        area: "Projekt",
                        view: "listeProjekt",
                        Projekt: projekt,
                        uprid: id
                    },
            function (data, status) {
                $('#content').html(data);
            });
        }
// insert
        if (this.className === 'insertProjekt') {
            var projekt = inputs['projekt'].value;
            $.post("index.php",
                    {
                        ajax: "true",
                        action: "insert",
                        area: "Projekt",
                        view: "listeProjekt",
                        projekt: projekt
                    },
            function (data, status) {
                $('#content').html(data);
            });
        }
// delete
        if (this.className === 'loeschenProjekt' && this.value === 'loeschen') {
            var id = this.id;
            $.post("index.php",
                    {
                        ajax: "true",
                        action: "delete",
                        area: "Projekt",
                        view: "listeProjekt",
                        lprid: id
                    },
            function (data, status) {
                $('#content').html(data);
            });
        }

// Mitarbeiter insert/update/delete
// update
        if (this.className === 'updateMitarbeiter') {
            var id = inputs['id'].value;
            var vorname = inputs['vorname'].value;
            var nachname = inputs['nachname'].value;
            var geschlecht = $('input[type="radio"]:checked').val();
            var geburtsdatum = inputs['geburtsdatum'].value;
            var abteilung_id = drops['abteilung'].value;
            var stundenlohn = inputs['stundenlohn'].value;
            var vorgesetzter_id = drops['vorgesetzter'].value;
            alert(vorgesetzter_id);
            $.post("index.php",
                    {
                        ajax: "true",
                        action: "update",
                        area: "Mitarbeiter",
                        view: "listeMitarbeiter",
                        vorname: vorname,
                        nachname: nachname,
                        geschlecht: geschlecht,
                        geburtsdatum: geburtsdatum,
                        abteilung_id: abteilung_id,
                        stundenlohn: stundenlohn,
                        vorgesetzter_id: vorgesetzter_id,
                        umaid: id
                    },
            function (data, status) {
                $('#content').html(data);
            });
        }
// insert
        if (this.className === 'insertMitarbeiter') {
            var vorname = inputs['vorname'].value;
            var nachname = inputs['nachname'].value;
            var geschlecht = $('input[type="radio"]:checked').val();
            var geburtsdatum = inputs['geburtsdatum'].value;
            var abteilung_id = drops['abteilung'].value;
            var stundenlohn = inputs['stundenlohn'].value;
            var vorgesetzter_id = drops['vorgesetzter'].value;
            $.post("index.php",
                    {
                        ajax: "true",
                        action: "insert",
                        area: "Mitarbeiter",
                        view: "listeMitarbeiter",
                        vorname: vorname,
                        nachname: nachname,
                        geschlecht: geschlecht,
                        geburtsdatum: geburtsdatum,
                        abteilung_id: abteilung_id,
                        stundenlohn: stundenlohn,
                        vorgesetzter_id: vorgesetzter_id
                    },
            function (data, status) {
                $('#content').html(data);
            });
        }
// delete
        if (this.className === 'loeschenMitarbeiter' && this.value === 'loeschen') {
            var lmaid = this.id;
            $.post("index.php",
                    {
                        ajax: "true",
                        action: "delete",
                        area: "Mitarbeiter",
                        view: "listeMitarbeiter",
                        lmaid: lmaid
                    },
            function (data, status) {
                $('#content').html(data);
            });
        }

// Abteilung insert/update/delete
// update
        if (this.className === 'updateAbteilung') {
            var id = inputs['id'].value;
            var abteilung = inputs['abteilung'].value;
            $.post("index.php",
                    {
                        ajax: "true",
                        action: "update",
                        area: "Abteilung",
                        view: "listeAbteilung",
                        Abteilung: abteilung,
                        uabid: id
                    },
            function (data, status) {
                $('#content').html(data);
            });
        }
// insert
        if (this.className === 'insertAbteilung') {
            var abteilung = inputs['abteilung'].value;
            $.post("index.php",
                    {
                        ajax: "true",
                        action: "insert",
                        area: "Abteilung",
                        view: "listeAbteilung",
                        abteilung: abteilung
                    },
            function (data, status) {
                $('#content').html(data);
            });
        }
// delete
        if (this.className === 'loeschenAbteilung' && this.value === 'loeschen') {
            var id = this.id;
            $.post("index.php",
                    {
                        ajax: "true",
                        action: "delete",
                        area: "Abteilung",
                        view: "listeAbteilung",
                        labid: id
                    },
            function (data, status) {
                $('#content').html(data);
            });
        }

// Hersteller insert/update/delete
// update
        if (this.className === 'updateHersteller') {
            var id = inputs['id'].value;
            var hersteller = inputs['name'].value;
            $.post("index.php",
                    {
                        ajax: "true",
                        action: "update",
                        area: "Hersteller",
                        view: "listeHersteller",
                        Hersteller: hersteller,
                        uheid: id
                    },
            function (data, status) {
                $('#content').html(data);
            });
        }
// insert
        if (this.className === 'insertHersteller') {
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
// delete
        if (this.className === 'loeschenHersteller' && this.value === 'loeschen') {
            var id = this.id;
            $.post("index.php",
                    {
                        ajax: "true",
                        action: "delete",
                        area: "Hersteller",
                        view: "listeHersteller",
                        lheid: id
                    },
            function (data, status) {
                $('#content').html(data);
            });
        }

// Auto insert/update/delete
// update
        if (this.className === 'updateAuto') {
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
                        Auto: auto,
                        hersteller_id: hersteller_id,
                        kennzeichen: kennzeichen,
                        uauid: id
                    },
            function (data, status) {
                $('#content').html(data);
            });
        }
// insert
        if (this.className === 'insertAuto') {
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
// delete
        if (this.className === 'loeschenAuto' && this.value === 'loeschen') {
            var id = this.id;
            $.post("index.php",
                    {
                        ajax: "true",
                        action: "delete",
                        area: "Auto",
                        view: "listeAuto",
                        lauid: id
                    },
            function (data, status) {
                $('#content').html(data);
            });
        }

// ProjektMitarbeiter insert/update/delete
// update
        if (this.className === 'updateProjektMitarbeiter') {
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
                        Projekt: projekt,
                        mitarbeiter: mitarbeiter,
                        von: vonDate,
                        bis: bisDate,
                        upmid: id
                    },
            function (data, status) {
                $('#content').html(data);
            });
        }
// insert
        if (this.className === 'insertProjektMitarbeiter') {
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
// delete
        if (this.className === 'loeschenProjektMitarbeiter' && this.value === 'loeschen') {
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

// für test.html

        if (this.id === 'test') {
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

// aufruf der unterschiedlichen formulare und views

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

            // Show

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

                // Insert

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

                // Update

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

                // Default 

            default:
                options = ['standard', 'standard', 'standard'];
                break;
        }

        return options;
    }

    $('#cssmenu > ul > li > a').off('click');
    $('#cssmenu > ul > li > a').click(function () {
        $('#cssmenu li').removeClass('active');
        $(this).closest('li').addClass('active');
        var checkElement = $(this).next();
        if ((checkElement.is('ul')) && (checkElement.is(':visible'))) {
            $(this).closest('li').removeClass('active');
            checkElement.slideUp('normal');
        }
        if ((checkElement.is('ul')) && (!checkElement.is(':visible'))) {
            $('#cssmenu ul ul:visible').slideUp('normal');
            checkElement.slideDown('normal');
        }
        if ($(this).closest('li').find('ul').children().length === 0) {
            return true;
        } else {
            return false;
        }
    });

    // datepicker für geburtsdatum und vonTag und bisTag

    $('#geburtsdatum,#vonTag,#bisTag').datepicker({
        inline: true,
        showOtherMonths: true,
        closeText: 'schließen',
        prevText: '&#x3c;zurück',
        nextText: 'Vor&#x3e;',
        currentText: 'heute',
        monthNames: ['Januar', 'Februar', 'März', 'April', 'Mai', 'Juni',
            'Juli', 'August', 'September', 'Oktober', 'November', 'Dezember'],
        monthNamesShort: ['Jan', 'Feb', 'Mär', 'Apr', 'Mai', 'Jun',
            'Jul', 'Aug', 'Sep', 'Okt', 'Nov', 'Dez'],
        dayNames: ['Sonntag', 'Montag', 'Dienstag', 'Mittwoch', 'Donnerstag', 'Freitag', 'Samstag'],
        dayNamesShort: ['So', 'Mo', 'Di', 'Mi', 'Do', 'Fr', 'Sa'],
        dayNamesMin: ['So', 'Mo', 'Di', 'Mi', 'Do', 'Fr', 'Sa'],
        weekHeader: 'Wo',
        firstDay: new Date(),
        dateFormat: "dd.mm.yy",
        isRTL: false,
        showMonthAfterYear: false,
        yearSuffix: '',
        showAnim: "slideDown",
        showWeek: true,
        onClose: function (selectedDate) {
            if (this.id !== 'bisTag') {
                $('#bisTag').datepicker("option", "minDate", selectedDate);
            } else {
                $('#vonTag').datepicker("option", "maxDate", selectedDate);
            }
        }
    });

    // timepicker
    $('#vonZeit,#bisZeit').timepicker({
        disableTextInput: false,
        timeFormat: 'H:i'
    });

});