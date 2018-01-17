var infoarchivos = {};
$(function () {

    $('.fechas').datetimepicker({
        lang: 'es',
        format: 'Y-m-d H:i:s',
    });
    $("#conarchivo").click(function () {
        $("#contenidoarchivo").removeClass("hidden");
        $(".contenidos").addClass("hidden");
        $("#enviarAvanzado").attr("disabled", true);
    });
    $("#sinarchivo").click(function () {
        if (infoarchivos.idbase != undefined) {
            if (confirm("Se perdera la informacion del Archivo excel Subido, Desea continuar?")) {
                infoarchivos = {};
            } else {
                $("#conarchivo").attr("checked", true);
            }
        }

        $("#contenidoarchivo").addClass("hidden");
        $(".contenidos").removeClass("hidden");
        $("#enviarAvanzado").attr("disabled", false);
    })

    $("#mensajeavanzado").cuentaPalabras("#fmravanzado #pavanzado");
    $("#subir").click(function () {

    })

    $("#enviarAvanzado").click(function () {
        $(this).attr("disabled", true);
        var val = crud(null, 'inicio/validaSession');
        val.success(function (data) {
            if (data.validado == 'ok') {
                var destinos = {}, texto = '', cont = 0, fechaenvio = '', contdestino = 0, cantidadgrupos = 0;
                var textodes = '', error = 0;
                var mensaje = $("#mensajeavanzado").val();
                fechaenvio = ($("#fechaenvio").val() == '') ? 'Envio inmediato' : $("#fechaenvio").val();
                var res = crud(null, 'enviosimple/ultimoid', 'JSON');
                res.success(function (data) {
                    data.max = (data.max == null) ? 1 : data.max;
                    $("#codigo").html("<b>Codigo Verificacion: </b>" + data.max);
                })

                if (mensaje == '' && $("#conarchivo").val() != 'on') {
                    error++;
                    alert("asd");
                    $("#mensajealerta").removeClass("hidden").text("Mensaje vacio");
                } else {
                    $("#mensajealerta").addClass("hidden");
                }

                if (infoarchivos.idbase != undefined) {
                    if (error == 0) {
                        var val = crud(null, 'inicio/consultaSaldo', 'JSON');
                        val.success(function (data) {

                            if (parseInt(data.cupo) >= parseInt(infoarchivos.insertados)) {
                                $(".modalconfirmacion2").modal("show");
                                $(".cargando").addClass("hidden");
                                $(".infomodal").removeClass("hidden");
                                $("#btnconfirmacion2").attr("disabled", false);
                                $("#totalcontactos").html("<b>Total de contactos: </b>&nbsp;" + infoarchivos.insertados);
                                $("#totalmensaje").html("<b>Total de Mensajes: </b>&nbsp;" + infoarchivos.insertados);
                                $("#txtmensaje").html("<b>Mensaje: </b>" + mensaje);
                                $("#programacion").html("<b>Programación: </b>" + fechaenvio);
                                $("#cupoactual").html("<b>Cupo: </b>" + data.cupo + " SMS Disponibles");
                                actualizaCupo();
                            } else {
                                alert("El usuario no cuenta con cupo suficiente!, por favor verifique");
                            }
                        });
                    } else {
                        $("#enviarAvanzado").attr("disabled", false);
                        $("#mensajealerta").removeClass("hidden").html("<b>Mensaje vacio</b>");
                    }

                } else {

                    if ($("#fmravanzado #destinatarios").val() != '') {

                        destinos = $("#fmravanzado #destinatarios").validaTextarea();
                        if (destinos.ok == true) {
                            $("#mensajedestino").addClass("hidden");
                        } else {
                            error++;
                            $("#mensajedestino").removeClass("hidden").text(destinos.ok);
                        }
                    } else {
                        $("#mensajedestino").addClass("hidden");
                    }


                    if (destinos.numeros != undefined) {
                        var numeros = destinos.numeros.split(",");
                        $.each(numeros, function (i, val) {
                            textodes += "<tr><td>" + val + "</td></tr>";
                        })
                        $(".destino").removeClass("hidden");
                    } else {
                        $(".destino").addClass("hidden");
                    }

                    $(".grupos").each(function () {
                        if ($(this).is(":checked")) {
                            texto += "<tr><td>" + $(this).attr("nombre") + " </td></tr>";
                            ++cont;
                            cantidadgrupos += parseInt($(this).attr("cantidad"));
                        }
                    });
                    if (cont != 0) {
                        $(".grupos2").removeClass("hidden");
                    } else {
                        $(".grupos2").addClass("hidden");
                    }




                    if (error == 0) {
                        if (cont != 0 || destinos.ok == true) {

                            $("#listagrupos").html(texto);
                            $("#listadestino").html(textodes);
                            destinos.cantidad = Math.ceil(parseInt(mensaje.length) / 160);
                            contdestino = (destinos.valido == undefined) ? contdestino : destinos.valido;
                            var totalmensajes = destinos.cantidad * (cantidadgrupos + contdestino);
                            var res = crud(null, 'inicio/consultaSaldo', 'JSON');
                            res.success(function (data) {
                                if (data.cupo >= totalmensajes) {
                                    $(".modalconfirmacion2").modal("show");
                                    $(".cargando").addClass("hidden");
                                    $(".infomodal").removeClass("hidden");
                                    $("#btnconfirmacion2").attr("disabled", false);
                                    $("#totalcontactos").html("<strong>Total de contactos: </strong>&nbsp;" + (cantidadgrupos + contdestino));
                                    $("#totalmensaje").html("<strong>Total de Mensajes: </strong>&nbsp;" + totalmensajes);
                                    $("#txtmensaje").html("<b>Mensaje: </b>" + mensaje);
                                    $("#programacion").html("<b>Programación: </b>" + fechaenvio);
                                    $("#cupoactual").html("<b>Cupo: </b>" + data.cupo + " SMS Disponibles");
                                    actualizaCupo();
                                } else {
                                    console.log("asd");
                                    alert("El usuario no cuenta con cupo suficiente!, por favor verifique");
                                }
                            });
                        } else {
                            $("#mensajealerta").removeClass("hidden").html("<b>Es necesario algun contacto</b>");
                        }
                    }

                }
            } else {
                if (confirm("No se ha podido cargar la pagina, Por tiempo de inactividad")) {
                    location.href = "inicio/cerrarSession";
                }
            }
        })

    })

    $("#subir").click(function () {
        if ($("#archivo").val() != '') {
            var formData = new FormData($("#form")[0]);
            $.ajax({
                url: 'envioavanzado/insertarMensajeExcel',
                type: "POST",
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                dataType: "JSON",
                beforeSend: function () {
                    $("#subir").attr("disabled", true);
                    $(".cargadocontacto").removeClass("hidden");
                },
                success: function (data) {
                    grabaDatosEnvio(data);
                }
            })


        } else {
            alert("no ha cargando ningun archivo");
        }
    })

    $("#btnconfirmacion2").click(function () {
        var btn = $(this), texto = '', link = '';
        btn.attr("disabled", true);
        var datos = {}, destinos = '', ruta = '';
        datos.mensaje = $("#fmravanzado #mensajeavanzado").val();
        datos.fechaenvio = $("#fmravanzado #fechaenvio").val();
        if (infoarchivos.idbase != undefined) {
            datos.idbase = infoarchivos.idbase
            ruta = 'envioavanzado/insertaMensajeArchivo';
        } else {
            destinos = $("#fmravanzado #destinatarios").validaTextarea();
            datos.destinos = destinos.numeros;

            datos.grupos = stringGrupo("#fmravanzado .grupos");
            ruta = 'envioavanzado/insertaMensaje';
        }

        $.ajax({
            url: ruta,
            type: 'POST',
            data: datos,
            dataType: 'JSON',
            beforeSend: function () {
                $("#btnconfirmacion2").attr("disabled", true);
                $(".confirmacionavanzado").addClass("hidden");
                $(".cargando").removeClass("hidden");
                $(".infomodal").addClass("hidden");
            }, success: function (data) {
                $(".modalconfirmacion2").modal("hide");
                $("#destinatarios").val("");
                $("#mensajeavanzado").val("");
                btn.attr("disabled", false);

                $("#fmravanzado .grupos").prop("checked", false);

                actualizaCupo();
                if (data.registros != 0 && data.errores == 0) {
                    mensaje("mensajealerta", true, "<b>Enviados con exito: " + data.registros + " SMS, Codigo: " + data.idbase + "</b>");
                } else if (data.errores != 0) {
                    texto = "<b>Enviados con exito: " + data.registros + " SMS, con Errores: " + data.errores + " SMS, Codigo:" + data.idbase + "</b>";
                    link = '<a href = "#" id = "exportar" onclick=exportar("' + data.idbase + '")> Descargar Errores </a>';
                    mensaje("mensajealerta", 'war', texto);
                    $(".mensajealerta").append(link);
                } else {
                    texto = "<b>Errores: " + data.error + " SMS, Codigo: " + data.idbase + "</b>";
                    link = '<a href = "#" id = "exportar" onclick=exportar("' + data.idbase + '")> Descargar Errores </a>';
                    mensaje("mensajealerta", 'error', texto);
                    $(".mensajealerta").append(link);

                    mensaje("mensajealerta", 'error', "<b>Errores: " + data.errores + " SMS, Codigo:" + data.idbase + "</b>");
                }
                $(".mensajesubida").addClass("hidden");
                $("#form")[0].reset();
                $("#enviarAvanzado").attr("disabled", false);
                delete infoarchivos.idarchivo;

            }
        })

    })
})

function stringGrupo(clase) {
    var arreglo = '';
    $(clase).each(function () {
        if ($(this).is(":checked")) {
            arreglo += (arreglo == '') ? '' : ',';
            arreglo += $(this).val();
        }

    })
    return arreglo;
}

function grabaDatosEnvio(data) {
    $.ajax({
        url: 'envioavanzado/grabaDatos',
        type: 'POST',
        dataType: 'JSON',
        data: data,
        success: function (data) {
            $(".cargadocontacto").addClass("hidden");
            $("#subir").attr("disabled", false);
            $("#fmravanzado #enviarAvanzado").attr("disabled", false);
            if (data.idbase != '') {
                callback(data);
            }
            if (data.registros && data.errores == 0) {
                mensaje("mensajesubida", null, "<b>Mensaje listos para procesar: " + data.registros + " SMS</b>");
            } else if (data.registros && data.errores != 0) {
                mensaje("mensajesubida", 'war', "<b>Mensaje listos para procesar: " + data.registros + "SMS, Con Error:" + data.errores + " SMS</b>");
            } else {
                $(".avanzadook").addClass("hidden");
            }
        }
    })
}

function callback(base) {
    infoarchivos.idbase = base.idbase;
    infoarchivos.insertados = base.registros;
}

function exportar(idbase) {
    window.open('envioavanzado/peticionErrores/' + idbase);
}