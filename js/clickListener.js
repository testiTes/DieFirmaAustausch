$(document).ready(function () {
    $("button").click(function () {
//        alert(this.value);
//        alert(this.id);

        $.post("index.php",
                {
                    ajax: "true",
                    btnViewLoader: this.value,
                },
                function (data, status) {
//                    alert(data);
                    $('#content').html(data);
                });
    });


    $('a.menuItem').click(function () {
//        alert(this.id);
        $.post("index.php",
                {
                    ajax: "true",
                    menuViewLoader: this.id
                },
        function (data, status) {
//                    alert(data);
            $('#content').html(data);
        });
    });


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
        if ($(this).closest('li').find('ul').children().length == 0) {
            return true;
        } else {
            return false;
        }
    });
});