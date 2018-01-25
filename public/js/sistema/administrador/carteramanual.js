var process = null;
$(function () {
    $("#btnEjecutar").click(function () {
        var elem = $(this);
        elem.attr("disabled", true);
        $("#alert-process").removeClass("alert-success").html("Iniciando ....");
        $.ajax({
            url: 'carteramanual/setprocess',
            method: 'get',
            dataType: 'json',
            async: true,
            success: function (data) {
                if (data.status == true) {
                    process = setInterval(getData, 4000);
                } else {
                    elem.attr("disabled", false);
                    $("#alert-process").addClass("alert-danger").html(data.msg);
                }
            }
        })


    })
});

function getData() {
    $("#alert-process").removeClass("alert-success").removeClass("alert-danger")

    $.ajax({
        url: 'carteramanual/getprocess',
        method: 'get',
        dataType: 'json',
        async: true,
        success: function (data) {

            $("#alert-process").html("Cantidad : " + data.quantity);

            if (data.status == true) {
                $("#alert-process").addClass("alert-success").html("Cargado: 100%");
                $("#btnEjecutar").attr("disabled", false);
                clearInterval(process);
            }
        }
    })
}
