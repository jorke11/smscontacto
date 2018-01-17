$(function () {

    $("#frmmoviil #txtmensajemovil").cuentaPalabras("#frmmoviil #carateres");
    $("#btnmanual").click(function () {
        $("#modalmanual").modal("show");
    })


    $("#btncartera").click(function () {
        var html = '';
        $.ajax({
            url: 'cartera/datosCartera',
            type: 'POST',
            dataType: 'JSON',
            beforeSend: function () {
                $(".modalcarga").modal("show");
            },
            success: function (data) {
                if (data.length > 0) {
                    $.each(data, function (i, val) {
                        html += "<tr><td>"
                        html += "<input type='checkbox'  id='car_" + i + "' name='cartera[]' nombre='" + val.nombre + "' class='valorcartera enviomovil' value='" + val.celular + "'>&nbsp;";
                        html += '<a href="#" onclick=seleccion("car_' + i + '")>' + val.diamora + ' - ' + val.nombre + " - " + val.celular;
                        html += "</td></tr>";
                    })
                } else {
                    html += "<tr><td>No tienes Usuario para esta Opción</td></tr>";
                }

                $(".modalcarga").modal("hide");
                $("#tblcartera").html(html);
            }
        })


        $("#modalcartera").modal("show");
    });
    $("#inputContacto").click(function () {
        $("#modalcontactos").modal("show");
    });
    $("#inputGrupos").click(function () {
        $("#modalgrupos").modal("show");
    })

    $("#sigrupos").click(function () {
        $("#modalgrupos").modal("hide");
    })
    $("#sicontactos").click(function () {
        $("#modalcontactos").modal("hide");
    });
    $("#enviamovil").click(function () {

        var val = crud(null, 'inicio/validaSession');
        val.success(function (data) {
            if (!data.session) {
                var texto = '', cantcontactos = 0, cont2 = 0, grupos = '', cantidadgrupos = 0, txtmensaje = '';
                var cantcartera = 0, cartera = '';
                var destinos = {}, fechaenvio = '';
                txtmensaje = $("#txtmensajemovil").val();
                $(".valorcontacto").each(function () {
                    if ($(this).is(":checked")) {
                        ++cantcontactos;
                    }
                });
                $(".valorgrupos").each(function () {
                    if ($(this).is(":checked")) {
                        grupos += "<tr><td>" + $(this).attr("nombre") + " " + $(this).val() + "</td></tr>";
                        cantidadgrupos += parseInt($(this).attr("cantidad"));
                    }
                });

                $(".valorcartera").each(function () {
                    if ($(this).is(":checked")) {
                        cartera += "<tr><td>" + $(this).attr("nombre") + " " + $(this).val() + "</td></tr>";
                        cantcartera++;
                    }
                });



                destinos = $("#formManual #numeromanual").validaTextarea("entero");
                validado = (destinos.ok == true) ? 'Destinatario validados' : destinos;
                if (destinos.ok == true || $("#formManual #numeromanual").val() == '') {
                    if (txtmensaje != '') {
                        if (cantcontactos != 0 || cantidadgrupos != 0 || destinos.ok == true || cantcartera != 0) {
                            mensaje("mensajemovil", 'hidden');
                            var val = crud(null, 'inicio/consultaSaldo'), cantidadmsj = 0, cant = 0, man = 0, total = 0;
                            val.success(function (data) {
                                man = (destinos.valido == undefined) ? 0 : destinos.valido;
                                if (data.cupo >= cantidadmsj) {
                                    $(".modalconfirmacion3").modal("show");
                                    total = (cantidadgrupos + cantcontactos + man + cantcartera);
                                    cantidadsms = (Math.ceil(parseInt(txtmensaje.length) / 160) * total);
                                    fechaenvio = ($("#fechaenvio").val() == '') ? 'Envio inmediato' : $("#fechaenvio").val();
                                    $("#totalcontactos").html("<strong>Total de contactos: </strong>&nbsp;" + total);
                                    $("#totalmensaje").html("<strong>Total de Mensajes: </strong>&nbsp;" + cantidadsms);
                                    $("#txtmensaje").html("<b>Mensaje: </b>" + txtmensaje);
                                    $("#programacion").html("<b>Programación: </b>" + fechaenvio);
                                } else {
                                    alert("No cuentas con cupo suficiente!");
                                }
                            });
                        } else {
                            mensaje("mensajemovil", 'error', '<b>No se han seleccionado contactos</b>');
                        }
                    } else {
                        mensaje("mensajemovil", 'error', '<b>Mensaje vacio</b>');
                    }
                } else {
                    mensaje("mensajemovil", 'error', '<b>' + destinos.ok + '</b>');
                }

            } else {
                if (confirm("No se ha podido cargar la pagina, Por tiempo de inactividad")) {
                    location.href = "inicio/cerrarSession";
                }
            }
        })

    });
    $("#cancelar").click(function () {
        $("#btnconfirmacion3").attr("disabled", false);
    })


    $("#btnconfirmacion3").click(function () {
        var btn = $(this), texto = '', cantidadgrupos = 0, cantcontactos = 0, cantcartera = 0;
        var total = 0;
        btn.attr("disabled", true);
        var txtmensaje = $("#txtmensajemovil").val();

        var ingresos = $("#formManual #numeromanual").validaTextarea();
        var datos = {}, numeros = '', grupos = '', cartera = '', remi = 0;

        $(".valorcontacto").each(function () {
            if ($(this).is(":checked")) {
                numeros += (numeros == '') ? '' : ',';
                numeros += $(this).val();
                cantcontactos++;
            }
        });

        $(".valorgrupos").each(function () {
            if ($(this).is(":checked")) {
                grupos += (grupos == '') ? '' : ',';
                grupos += $(this).val();
                cantidadgrupos += parseInt($(this).attr("cantidad"));
            }
        });

        $(".valorcartera").each(function () {
            if ($(this).is(":checked")) {
                cartera += (cartera == '') ? '' : ',';
                cartera += $(this).val();
                ++cantcartera;
            }
        });


        if (ingresos == 'no hay remitentes') {
            ingresos.valido = 0;
            remi = ingresos.valido;
        } else {
            remi = ingresos.valido;
        }

        remi = (remi == undefined) ? 0 : remi;

        total = cantcontactos + cantidadgrupos + remi + cantcartera;


        datos.destino = numeros;
        datos.grupos = grupos;
        datos.cartera = cartera;
        datos.manuales = ingresos.numeros;
        datos.mensaje = $("#txtmensajemovil").val();
        datos.fechaenvio = ($("#fechaenvio").val() == '') ? '' : $("#fechaenvio").val();
        datos.cantmensajes = (Math.ceil(txtmensaje.length / 160) * total);

        $.ajax({
            type: 'POST',
            url: "inicio/envioMovil",
            data: datos,
            dataType: 'JSON',
            beforeSend: function () {
                $(".modalcarga").modal("show");
            },
            success: function (data) {

                $(".modalcarga").modal("hide");
                $(".modalconfirmacion3").modal("hide");
                actualizaCupo(".contenedorfull .cupo2");
                $("#txtmensajemovil").val("");
                $("#numeromanual").val("");
                $(".valorcontacto").prop("checked", false);
                $(".valorgrupos").prop("checked", false);
                btn.attr("disabled", false);
                if (data.registros != 0 && data.errores == 0) {
                    actualizaCupo(".contenedorfull .cupo2");
                    mensaje("mensajemovil", true, "<b>Enviados con exito: " + data.registros + " SMS, Codigo:" + data.idbase + "</b>");
                } else if (data.sesion) {
                    if (confirm("No se ha podido cargar la pagina, contactar con soporte")) {
                        location.href = "inicio/cerrarSession";
                    }
                } else if (data.error) {
                    texto = "<b>Errores: " + data.error + " Consulte con soporte</b>";
                    mensaje("mensajemovil", 'error', texto);
                } else {
                    texto = "<b>Errores: " + data.error + " </b>";
                    mensaje("mensajemovil", 'error', texto);
                }
            },
            error: function () {
                alert("Problemas con el proceso");
            }
        })
    })
})

function seleccion(id) {
    if ($("#" + id).is(":checked")) {
        $("#" + id).attr("checked", false);
    } else {
        $("#" + id).attr("checked", true);

    }
}