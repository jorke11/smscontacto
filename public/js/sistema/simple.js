$(function () {
    var iderror = 0;
    $('.fechas').datetimepicker({
        lang: 'es',
        format: 'Y-m-d H:i:s',
    });

    $("#seleccionartodos").click(function () {
        if ($(this).is(":checked", true) == true) {
            $(".infocontactos").prop("checked", true);
        } else {
            $(".infocontactos").prop("checked", false);
        }
    })

    $("#mensaje").cuentaPalabras("#frmsimple #carateres");


    $("#enviarSimple").click(function () {
        var txtmensaje='';
        var val = crud(null, 'inicio/validaSession');
        val.success(function (data) {
            if (data.validado == 'ok') {
                var texto = '', textodes = '', cont = 0, validado = {}, fechaenvio = '';
                var mensaje = $("#mensaje").val(), errores = 0, destinos = {}, numeros = 0, contdestino = 0;

                if ($("#frmsimple #destinarios").val() != '') {
                    destinos = $("#frmsimple #destinarios").validaTextarea();
                    validado = (destinos.ok == true) ? 'Destinatario validados' : destinos;
                    if (destinos.ok == true) {
                        $("#mensajedestino").addClass("hidden");
                    } else {
                        $("#mensajedestino").removeClass("hidden").text(destinos.ok);
                    }
                } else {
                    $("#mensajedestino-ok").addClass("hidden")
                    $("#mensajedestino").addClass("hidden");
                }

                if (mensaje == '') {
                    $("#mensajealerta").removeClass("hidden").text("Mensaje vacio");
                } else {
                    $("#mensajealerta").addClass("hidden");
                }

                if (destinos.ok == true || destinos.ok == undefined) {
                    $("#mensajedestino").addClass('hidden');
                    if (mensaje != '') {
                        $("#mensajealerta").addClass("hidden");
                        $(".infocontactos").each(function () {
                            if ($(this).is(":checked")) {

                                texto += "<tr><td>" + $(this).attr("nombre") + " " + $(this).val() + "</td></tr>";
                                ++cont;
                            }
                        });
                        if (cont != 0 || destinos.ok == true) {
                            if (texto != '') {
                                $(".contacto").removeClass("hidden");
                            }
                            if (destinos.numeros != undefined) {
                                numeros = destinos.numeros.split(",");
                                $.each(numeros, function (i, val) {
                                    textodes += "<tr><td>" + val + "</td></tr>";
                                })
                                $(".destino").removeClass("hidden");
                            }

                            $("#listacontactos").html(texto);
                            $("#listadestino").html(textodes);

                            destinos.cantidad = Math.ceil(parseInt($("#mensaje").val().length) / 160);
                            fechaenvio = ($("#fechaenvio").val() == '') ? 'Envio inmediato' : $("#fechaenvio").val();
                            contdestino = (destinos.valido == undefined) ? contdestino : destinos.valido;
                            var totalmensaje = destinos.cantidad * (cont + contdestino);
                            var rex = /(<([^>]+)>)/ig;
                            
                            
                            var res = crud(null, 'inicio/consultaSaldo');
                            res.success(function (data) {

                                if (data.cupo >= totalmensaje) {
                                    $(".modalconfirmacion").modal("show");
                                    $("#totalcontactos").html("<strong>Total de contactos: </strong>&nbsp;" + (cont + contdestino));
                                    $("#totalmensaje").html("<strong>Total de Mensajes: </strong>&nbsp;" + totalmensaje);
                                    $("#txtmensaje").html("<b>Mensaje: </b>" + $("#mensaje").val());
                                    $("#programacion").html("<b>Programación: </b>" + fechaenvio);
                                    $("#cupoactual").html("<b>Cupo: </b>" + data.cupo + " SMS Disponibles");
                                } else {
                                    alert("El usuario no cuenta con cupo suficiente!, por favor verifique");
                                }
                            })



                        } else {
                            $("#mensajealerta").removeClass("hidden").text("Es necesario tener algun contacto");
                            return false;
                        }


                    }
                }
            } else {
                if (confirm("No se ha podido cargar la pagina, Por tiempo de inactividad")) {
                    location.href = "inicio/cerrarSession";
                }
            }
        })
    });


    $("#btnconfirmacion").click(function () {
        var btn = $(this), texto = '', link = '';
        btn.attr("disabled", true);
        var sms = $("#frmsimple #destinarios").validaTextarea(), datos;
        datos = $("#frmsimple").serialize();
        $("#mensajealerta").html("Destinatarios Validos");
        var res = crud(null, 'inicio/consultaSaldo', 'JSON');
        res.success(function (data) {
            if (data.cupo > 0) {
                $.ajax({
                    url: "enviosimple/insertaMensaje",
                    type: 'POST',
                    data: datos,
                    dataType: 'JSON',
                    beforeSend: function () {
                        $(".infomodal").addClass("hidden");
                        $(".cargando").removeClass("hidden");
                        $("#btnconfirmacion").attr("disabled", true);

                    }, success: function (data) {
                        btn.attr("disabled", false);
                        $(".modalconfirmacion").modal("hide");
                        $(".infomodal").removeClass("hidden");
                        $(".cargando").addClass("hidden");
                        $("#frmsimple #mensaje").val("");
                        $("#frmsimple #destinarios").val("");

                        $(".contenedorfull #cupo").html("<b>Cupo: " + data.cupoactual + ' SMS</b><a href="inicio/cerrarSession">Salir</a>');
                        $("#btnconfirmacion").attr("disabled", false);
                        $("#mensajerespuestaerror").addClass("hidden");

                        $("#frmsimple .infocontactos").prop("checked", false);

                        if (data.registros != 0 && data.errores == 0) {
                            mensaje("mensajealerta", true, "<b>Enviados con exito: " + data.registros + " SMS, Codigo:" + data.idbase + "</b>");
                            $(".descargaerrores").addClass("hidden");
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
                        }
                    }
                })
            } else {
                alert("El usuario no cuenta con suficiente cupo!, por favor verifique");
            }
        })



    })
    
})

function exportar(idbase) {
    window.open('enviosimple/peticionErrores/' + idbase);
}