let timeOut = null;

function initListFilter(inputId, listSelector, textElement) {

    const input = document.getElementById(inputId);
    const items = document.querySelectorAll(listSelector);

    $(input).keyup(function (e) {


        if (timeOut) {
            clearTimeout(timeOut);
        }


        timeOut = setTimeout(function () {

            $.each(items, function (i, v) {

                const text = $(v).find(textElement).text();
                const regex = new RegExp($(input).val(), "gi");
                const matches = text.match(regex) || [];

                if (matches.length > 0) {
                    $(v).slideDown(100);
                } else {
                    $(v).slideUp(100);
                }

            })
        }, 500);
    })

}