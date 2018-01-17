$(function () {

    $("#opcionfecha").click(function () {
        $(".reportes").addClass("hidden");
        $(".filtrofechas").removeClass("hidden");
    });
    $("#opcionmes").click(function () {
        $(".reportes").addClass("hidden");
        $(".filtromes").removeClass("hidden");
    });

    $("#opcionoperador").click(function () {
        $(".reportes").addClass("hidden");
        $(".filtrooperador").removeClass("hidden");
    });

    $('.fechas').datetimepicker({
        lang: 'es',
        format: 'Y-m-d H:i:s',
    });

    $('.prueba').datetimepicker({
        lang: 'es',
        format: 'Y-m-d H:i:s',
        timepicker: false,
    });

    $("#buscarmes").click(function () {
        var datos = {};
        var texto = '';

        if ($("#meses").val() != '') {
            datos.mes = $("#meses").val();
            $("#tabladinamica tbody").empty();
            var res = crud(datos, 'informes/tablames', "JSON");
            res.success(function (data) {
                $.each(data["data"], function (i, val) {
                    texto += "<tr>";
                    $.each(val, function (j, valor) {
                        texto += "<td>" + valor.trim() + "</td>";
                    })
                    texto += "</tr>";
                });
                $("#tabladinamica tbody").html(texto);
            });
        } else {
            alert("Por favor seleccione un fecha!");
        }
    });

    $("#consolidado").click(function () {
        if ($("#consolidado").is(":checked")) {
            $("#tiporeporte").val("consolidado");
        } else {
            $("#tiporeporte").val("normal");
        }
    })

    $("#generareporte").click(function () {
        var datos = {}, menu, cantidad = 0, texto = '', total = 0;
        menu = $("#menu").val();
        datos.cantidad = $("#cantidad").val();
        datos.finicio = $("#finicial").val();
        datos.ffinal = $("#ffinal").val();
        datos.tiporeporte = ($("#consolidado").is(":checked")) ? 'consolidado' : 'detallado';
        if (menu != 0) {
            switch (menu) {
                case "1":
                {
                    $("#operadores").addClass("hidden");
                    $("#tablames tbody").empty();
                    $(".reporteconsumo").addClass("hidden");
                    $(".reportemes").removeClass("hidden");



                    var res = crud(datos, 'informes/tabladia', "JSON");
                    res.success(function (data) {
                        if (data["data"] != '') {
                            $.each(data["data"], function (i, val) {
                                texto += "<tr>";

                                $.each(val, function (j, valor) {
                                    if (datos.tiporeporte == 'consolidado') {
                                        if (j == 4) {
                                            total += parseInt(valor);
                                        }
                                    } else {
                                        if (j == 6) {
                                            total += parseInt(valor);
                                        }
                                    }
                                    texto += "<td>" + valor.trim() + "</td>";
                                })
                                texto += "</tr>";
                            });

                            $("#tablames tbody").html(texto);

                            if (datos.tiporeporte == 'consolidado') {
                                $(".consolidomes").addClass("hidden");
                                $("#tablames tbody").append("<tr><td colspan=4><b>Total Registros</b></td><td>" + total + "</td></tr>");
                            } else {
                                $("#tablames tbody").append("<tr><td colspan=6><b>Total Registros</b></td><td>" + total + "</td></tr>");
                                $(".consolidomes").removeClass("hidden");

                            }

                        } else {
                            texto = 'No se encontraron registros';
                            $("#tablames tbody").html(texto);
                        }
                    });

                    break;
                }
                case "2":
                {

                    if (datos.tiporeporte == 'consolidado') {
                        $(".consolidomes").addClass("hidden");
                    } else {
                        $(".consolidomes").removeClass("hidden");
                    }

                    $("#tablames tbody").empty();
                    $(".reporteconsumo").addClass("hidden");
                    $(".operadores").addClass("hidden");
                    $(".reportemes").removeClass("hidden");
                    var res = crud(datos, 'informes/tablames', "JSON");
                    res.success(function (data) {
                        if (data["data"] != '') {
                            $.each(data["data"], function (i, val) {
                                texto += "<tr>";


                                $.each(val, function (j, valor) {
                                    if (datos.tiporeporte == 'consolidado') {
                                        if (j == 4) {
                                            total += parseInt(valor);
                                        }
                                    } else {
                                        if (j == 6) {
                                            total += parseInt(valor);
                                        }
                                    }

                                    texto += "<td>" + valor.trim() + "</td>";
                                })
                                texto += "</tr>";
                            });

                            $("#tablames tbody").html(texto);

                            if (datos.tiporeporte == 'consolidado') {
                                $(".consolidomes").addClass("hidden");
                                $("#tablames tbody").append("<tr><td colspan=4><b>Total Registros</b></td><td>" + total + "</td></tr>");
                            } else {
                                $("#tablames tbody").append("<tr><td colspan=6><b>Total Registros</b></td><td>" + total + "</td></tr>");
                                $(".consolidomes").removeClass("hidden");

                            }
                        } else {
                            texto = "No se encontraron registros";
                            $("#tablames tbody").html(texto);
                        }

                    });

                    break;
                }
                case "3":
                {
                    $(".operadores").removeClass("hidden");
                    $(".reportemes").addClass("hidden");
                    $(".reporteconsumo").addClass("hidden");

                    $("#tablaoperadores tbody").empty();
                    var res = crud(datos, 'informes/tablaoperador', "JSON");
                    res.success(function (data) {
                        $.each(data["data"], function (i, val) {
                            texto += "<tr>";
                            $.each(val, function (j, valor) {
                                texto += "<td>" + valor.trim() + "</td>";
                            })
                            texto += "</tr>";
                        });
                        $("#tablaoperadores tbody").html(texto);
                    });
                    break;
                }
                case "4":
                {
                    $("#operadores").addClass("hidden");
                    $(".reportemes").addClass("hidden");
                    $(".operadores").addClass("hidden");
                    $(".reporteconsumo").removeClass("hidden");
                    $("#tablaconsumo tbody").empty();
                    var res = crud(datos, 'informes/tablaconsumo', "JSON");
                    res.success(function (data) {
                        if (data["data"] != '') {
                            $.each(data["data"], function (i, val) {
                                texto += "<tr>";
                                $.each(val, function (j, valor) {
                                    texto += "<td>" + valor.trim() + "</td>";
                                })
                                texto += "</tr>";
                            });
                        } else {
                            texto = 'No se encontraron registros';
                        }
                        $("#tablaconsumo tbody").html(texto);
                    });

                    break;
                }
            }
        } else {
            alert("Seleccione un Tipo de reporte");
        }
    })

    $("#menu").change(function () {
        if ($(this).val() == 1) {
            $("#ffinal").attr("disabled", true);
        } else {
            $("#ffinal").attr("disabled", false);
        }

    });


    $("#exportar").click(function () {
        if ($("#tablames >tbody >tr").length != 0) {
            var menu = $("#menu").val();
            window.open('informes/peticionExcel/' + menu);
        }

    })
})
