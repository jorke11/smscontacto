$(function() {
    tablaReporte2("tablames");

    $('.fechas').datetimepicker({
        lang: 'es',
        format: 'Y-m-d H:i:s',
    });

    $('.prueba').datetimepicker({
        lang: 'es',
        format: 'Y-m-d H:i:s',
        timepicker: false,
    });
    $("#opcionfecha").click(function() {
        $(".reportes").addClass("hidden");
        $(".filtrofechas").removeClass("hidden");
    });
    $("#opcionmes").click(function() {
        $(".reportes").addClass("hidden");
        $(".filtromes").removeClass("hidden");
    });
    $("#opcionoperador").click(function() {
        $(".reportes").addClass("hidden");
        $(".filtrooperador").removeClass("hidden");
    });

    $("#buscarmes").click(function() {
        var datos = {};
        var texto = '';

        if ($("#meses").val() != '') {
            datos.mes = $("#meses").val();
            $("#tablames tbody").empty();
            var res = crud(datos, 'informes/tablames', "JSON");
            res.success(function(data) {
                $.each(data["data"], function(i, val) {
                    texto += "<tr>";
                    $.each(val, function(j, valor) {
                        texto += "<td>" + valor.trim() + "</td>";
                    })
                    texto += "</tr>";
                });
                $("#tablames tbody").html(texto);
            });
        } else {
            alert("Por favor seleccione un fecha!");
        }
    });

})