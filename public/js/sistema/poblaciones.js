var infoarchivos = {};
$(function () {

    cargaDatos();

	

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

    $("#mensajeavanzado").cuentaPalabras("#fmrContactos #pavanzado");




    $("#filtroPoblacion").click(function () {
        var datos = $("#frmPoblaciones,#frmGrupos").serialize(), html = "";
        var res = crud(datos, 'poblaciones/getContactos');
        res.success(function (data) {
            $("#totalfiltros").html("Resultado: "+ data.registros+ " Registros encontrados");
            $("#tablacontactos").empty(html);
            tablaContacto(data);
        })
    })

    $("#enviarPoblaciones").click(function () {
        var val = crud(null, 'inicio/validaSession');
        val.success(function (data) {
            if (data.validado == 'ok') {
                var destinos = {}, texto = '', cont = 0, fechaenvio = '', contdestino = 0, cantidadgrupos = 0;
                var textodes = '', error = 0;
                var txtmensaje = $("#mensajeavanzado").val();
                fechaenvio = ($("#fechaenvio").val() == '') ? 'Envio inmediato' : $("#fechaenvio").val();

                if (txtmensaje == '') {
                    error++;
                    $("#mensajealerta").removeClass("hidden").text("Mensaje vacio");
                } else {
                    $("#mensajealerta").addClass("hidden");
                }


                if (infoarchivos.idbase != undefined) {
                    if (error == 0) {
                        var val = crud(null, 'inicio/consultaSaldo');
                        val.success(function (data) {
                            if (data.cupo >= infoarchivos.insertados) {
                                $(".modalconfirmacion2").modal("show");
                                $(".cargando").addClass("hidden");
                                $(".infomodal").removeClass("hidden");
                                $("#btnconfirmacion2").attr("disabled", false);
                                $("#totalcontactos").html("<b>Total de contactos: </b>&nbsp;" + infoarchivos.insertados);
                                $("#totalmensaje").html("<b>Total de Mensajes: </b>&nbsp;" + infoarchivos.insertados);
                                $("#txtmensaje").html("<b>Mensaje: </b>" + txtmensaje);
                                $("#programacion").html("<b>Programación: </b>" + fechaenvio);
                                $("#cupoactual").html("<b>Cupo: </b>" + data.cupo + " SMS Disponibles");
                                actualizaCupo();
                            } else {
                                alert("El usuario no cuenta con cupo suficiente!, por favor verifique");
                            }
                        });
                    } else {
                        $("#mensajealerta").removeClass("hidden").html("<b>Problemas con el mensaje o no hay numeros de contactos</b>");
                    }

                } else {

                    if ($("#fmrContactos #destinatarios").val() != '') {
                        destinos = $("#fmrContactos #destinatarios").validaTextarea();
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


                    $("#fmrContactos .infocontactos").each(function () {
                        if ($(this).is(":checked")) {
                            texto += "<tr><td>" + $(this).attr("nombre") + " " + $(this).val() + "</td></tr>";
                            ++cont;
                        }
                    });

                    if (error == 0) {
                        if (cont != 0 || destinos.ok == true) {
                            texto = (texto == '') ? "<tr><td>Sin grupos Seleccionados</td></tr>" : texto;
                            textodes = (textodes == '') ? "<tr><td>Sin Contactos Seleccionados</td></tr>" : textodes;

                            $("#frmconfirmacion #listacontactos").html(texto);
                            $("#frmconfirmacion #listamanual").html(textodes);

                            destinos.cantidad = Math.ceil(parseInt(txtmensaje.length) / 160);
                            contdestino = (destinos.valido == undefined) ? contdestino : destinos.valido;
                            var totalmensajes = destinos.cantidad * (cont + contdestino);
                            var res = crud(null, 'inicio/consultaSaldo');
                            res.success(function (data) {
                                if (data.cupo >= totalmensajes) {
                                    $(".limpiaTab").removeClass("hidden");
                                    $('#myTab a[href="#confirmacion"]').tab('show');
                                    mensaje("alertamensaje", 'hidden');
                                    $("#frmconfirmacion #confirmarenvio").attr("disabled", false);
                                    $("#frmconfirmacion #totalcontactos").html("<strong>Total de contactos: </strong>&nbsp;" + (cont + contdestino));
                                    $("#frmconfirmacion #totalmensaje").html("<strong>Total de Mensajes: </strong>&nbsp;" + totalmensajes);
                                    $("#frmconfirmacion #txtmensaje").html("<b>Mensaje: </b>" + txtmensaje);
                                    $("#frmconfirmacion #programacion").html("<b>Programación: </b>" + fechaenvio);
                                    $("#frmconfirmacion #cupoactual").html("<b>Cupo: </b>" + data.cupo + " SMS Disponibles");
                                    actualizaCupo();
                                } else {
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
            $("#mensajedestino").addClass("hidden");
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

    $(".principal").click(function () {
        $(".limpiaTab").addClass("hidden");
    })

    $(".limpiaTab").click(function () {
        mensaje("alertamensaje", 'hidden');
        $("#listacontactos").empty();
        $("#listamanual").empty();

        $("#frmconfirmacion #totalcontactos").html("<strong>Total de contactos: </strong>&nbsp;" + 0);
        $("#frmconfirmacion #totalmensaje").html("<strong>Total de Mensajes: </strong>&nbsp;" + 0);
        $("#frmconfirmacion #txtmensaje").html("<b>Mensaje: </b>");
        $("#frmconfirmacion #programacion").html("<b>Programación: </b>");
        $("#frmconfirmacion #cupoactual").html("<b>Cupo: </b>");

    })

    $("#confirmarenvio").click(function () {
        var btn = $(this), texto = '', link = '';
        $("#frmconfirmacion #confirmarenvio").attr("disabled", true);
        var datos = {}, destinos = '', ruta = '';
        datos.mensaje = $("#fmrContactos #mensajeavanzado").val();
        datos.fechaenvio = $("#fmrContactos #fechaenvio").val();

        if (infoarchivos.idbase != undefined) {
            datos.idbase = infoarchivos.idbase
            ruta = 'envioavanzado/insertaMensajeArchivo';
        } else {
//            destinos = $("#fmrContactos #destinatarios").validaTextarea();
//            datos.destinos = destinos.numeros;
//
//            datos.grupos = stringGrupo("#fmrContactos .grupos");
            ruta = 'poblaciones/insertaMensaje';
        }

        $.ajax({
            url: ruta,
            type: 'POST',
            data: $("#fmrContactos,#frmGrupos").serialize(),
            dataType: 'JSON',
            beforeSend: function () {
                $(".alertamensaje").removeClass("hidden").html('<table style="widtd:100%"><tr align="center"><td><img src="./imagenes/loading2.gif" style="width:25%"></td></tr><table>');
            }, success: function (data) {

                $("#fmrContactos #destinatarios").val("");
                $("#fmrContactos .infocontactos").prop("checked", false);
                $("#fmrContactos #mensajeavanzado").val("");
                $("#fmrContactos .grupos").prop("checked", false);

                actualizaCupo();
                if (data.registros != 0 && data.errores == 0) {
                    mensaje("alertamensaje", true, "<b>Enviados con exito: " + data.registros + " SMS, Codigo: " + data.idbase + "</b>");
                    
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
            }
        })

    })
	
	
})


function cargaDatos() {

    $.ajax({
        url: 'poblaciones/cargaDatos',
        type: 'POST',
        dataType: 'json',
        beforeSend: function () {
            $("#tablacontactos").html('<tr align="center"><td><img src="./imagenes/loading2.gif" style="width:25%"></td></tr>');
        },
        success: function (data) {
            $("#tablacontactos").empty();
            tablaContacto(data);
        }
    })
}

function tablaContacto(data) {
    var html = '';



    if (data.contactos.length > 0) {
        html = '<tr><td><input type="checkbox" id="todoscontactos" onclick=marcarContactos("todoscontactos")>';
        html += 'SELECCIONAR TODAS</td></tr>';
        $("#tablacontactos").append(html);
        $.each(data.contactos, function (i, val) {
            html = '<tr><td><input type="checkbox" name="contactos[]" class="infocontactos" checked="true"';
            html += 'nombre="' + val.nombre + '" value="' + val.celular + '" id="contactos_' + i + '"> ';
            html += '<a href="#" onclick=marcar("contactos_' + i + '")>' + val.nombre + ' ' + val.celular + '</a></td></tr>'
            $("#tablacontactos").append(html);
        })
    } else {
        html = '<tr><td>No se encontro ningun contacto asociado</td></tr>"';
        $("#tablacontactos").append(html);
    }
}



function marcar(id) {
    if ($("#" + id).is(":checked")) {
        $("#" + id).prop("checked", false);
    } else {
        $("#" + id).prop("checked", true);
    }

}

function marcarContactos(id) {
    if ($("#" + id).is(":checked")) {
        $(".infocontactos").prop("checked", true);
    } else {
        $(".infocontactos").prop("checked", false);
        
    }

}

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
            $("#fmrContactos #enviarPoblaciones").attr("disabled", false);
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