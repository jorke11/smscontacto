var process = null;
$(function () {
    $("#btnEjecutar").click(function () {
        var elem = $(this);
        elem.attr("disabled", true);
        $.ajax({
            url: 'carteramanual/setprocesss',
            method: 'get',
            dataType: 'json',
            async: true,
            success: function (data) {

            }
        })

        process = setInterval(getData, 4000);
    })
});

function getData() {

    $.ajax({
        url: 'carteramanual/getprocesss',
        method: 'get',
        dataType: 'json',
        async: true,
        success: function (data) {
            if (data.status == true) {
                $("#btnEjecutar").attr("disabled", false);
                clearInterval(process);
            }
        }
    })
}
