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

            }
        })

        process = setInterval(getData, 4000);
    })
});

function getData() {
    $("#alert-process").removeClass("alert-success")
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
