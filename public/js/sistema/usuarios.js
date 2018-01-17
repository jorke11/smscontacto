$(function () {
    limitar_teclado();
    cargaTabla("usuarios", 11);

    $(".permisos").attr("disabled", true);
    $("#formusuario #idsector").attr("disabled", true);

    muestraCupo();

    $("#registrar").click(function () {
        var clave = $("#clave").val(), confir = $("#confirmacion").val(), permiso = $("#permiso").val(), res, obj = {};

        if (permiso == 'editar') {

            var cupo = $("#formusuario #cupo");

            if (confirm("Realmente desea realizar los cambios para este usuario!")) {
                var valido = $(".inputusuario").valida();

                if (valido == 0) {
                    if (clave === confir) {
                        var datos = $("#formusuario").serialize();
                        res = crud(datos, 'usuarios/gestionUsuarios', 'JSON');

                        res.success(function (data) {
                            if (data.id) {
                                $(".inputusuario").limpiarCampos();
                                mensaje("mensajealerta");
                                recargar();
                                muestraCupo();
                            } else if (data.error) {
                                mensaje("mensajealerta", 'error', "<b>" + data.error + "</b>");
                            } else {
                                mensaje("mensajealerta", 'error', "<b>" + data.respuesta + "</b>");
                            }

                        });

                    } else {
                        alert("Las claves no coinciden");
                    }
                } else {
                    alert("Campos Vacios!");
                }
            }

        } else {
            obj.id = $("#formusuario #id").val();
            obj.adicion = $("#formusuario #adicion").val();
            obj.simbolo = $("#formusuario #simbolo").val();
            res = crud(obj, 'usuarios/adicionaCupo', 'JSON');
            res.success(function (data) {
                if (data.id) {
                    mensaje("mensajealerta");
                    $(".inputusuario").limpiarCampos();
                    $(".permisos").attr("disabled", true);
                    
                } else if (data.error) {
                    mensaje("mensajealerta", 'error', "<b>" + data.error + "</b>");
                } else {
                    mensaje("mensajealerta", 'hidden');
                }
                recargar();
                muestraCupo();
            })
        }
    })

    $("#borrar").click(function () {
        var obj = {};
        if (confirm("Esta seguro de borrar el Usuario!")) {
            obj.id = $("#formusuario #id").val();
            obj.tabla = "usuarios";
            var res = crud(obj, 'usuarios/borrar', 'HTML');
            res.success(function (data) {
                if (data > 0) {
                    $(".inputusuario").limpiarCampos();
                    $("#borrar").addClass("hidden");
                    mensaje("mensajealerta");
                } else {
                    mensaje("mensajealerta", 'error');
                    $(".alertok").addClass("hidden");
                }
                recargar();
            })

        }
    });

    $("#formusuario #idperfil").change(function () {
        if ($(this).val() != '2') {
            $("#formusuario #cupo").attr("disabled", true);
        } else {
            $("#formusuario #cupo").attr("disabled", false);
        }
        $("#formusuario #idsector").attr("disabled", true);
    })

    $("#idjerarquia").change(function () {

        var obj = {}, texto = '', perfil = 0, cupo = 0;
        obj.id = $(this).val();
        var res = crud(obj, 'usuarios/cargaSectores', 'JSON');
        res.success(function (data) {
            perfil = $("#formusuario #idperfil").val();
            $("#formusuario #idsector").attr("disabled", false);
            $("#formusuario #idsector").empty();
            cupo = (data["datos"].saldogerencia.cupodisponible <= 0) ? 0 : data["datos"].saldogerencia.cupodisponible;
            $("#formusuario #cupodisponible").val(cupo);
            $("#formusuario #idsector").cargarSelect(data["sectores"]);
        })
    })

    $(":file").change(function () {
        var archivo;
        var string = '';
        archivo = $("#fileusuarios")[0].files[0];
        if (archivo.type == "application/vnd.ms-excel") {
            string = "<b>Tamaño Aproximado: " + humanFileSize(archivo.size);
            $("#informacion").removeClass("hidden").html(string);
        } else {
            alert("Formato del archivo no valido");
            $("#fileusuarios").val("");
        }

    });

    $("#importar").click(function () {
        $(".subearchivo").modal("show");
    })

    $("#btnsubir").click(function () {
        var file = $("#formsubir #fileusuarios").val();
        if (file != '') {
            var formData = new FormData($("#formsubir")[0]), texto = '';
            $.ajax({
                url: 'usuarios/subeArchivo',
                type: "POST",
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                dataType: "JSON",
                beforeSend: function () {
                    $(".carga").removeClass("hidden");
                    $(".ocultaarchivo").addClass("hidden");
                    $("#btnsubir").attr("disabled", true);
                },
                success: function (data) {
                    $(".carga").addClass("hidden");
                    $(".ocultaarchivo").removeClass("hidden");
                    $("#btnsubir").attr("disabled", false);
                    texto = "<b>Insertados: " + data.insertados + " Actualizados: " + data.actualizados + "</b>";
                    $("#informacionsubida").html(texto);
                    $(".subearchivo").modal("hide");
                    recargar();
                }
            })
        } else {
            alert("No haz cargado un archivo!");
        }
    });


    $("#btnplan").click(function () {
        $(".modalplanes").modal("show");
    })

    $("#btncrearplan").click(function () {
        var datos = $("#formplanes").serialize(), texto = '';

        var res = crud(datos, 'usuarios/crearplan', 'JSON');

        res.success(function (data) {

            var plan = crud(null, 'usuarios/cargaplan', 'JSON');
            plan.success(function (data) {
                $.each(data, function (i, val) {
                    texto += '<option value="' + val.id + '">' + val.cantidad + '</option>';
                })
                $("#idplan").html(texto);

            })

            $(".alertok").removeClass("hidden").html("Operación realizada!");
            $(".modalplanes").modal("hide");
        })
    })

    $("#formusuario #cupo").blur(function () {
        var cupo = 0;
        cupo = $(" #formusuario #cupo");
        if (parseInt(cupo.val()) > parseInt($("#formusuario #cupogerencia").val())) {
            alert("No tiene el cupo suficiente!");
            cupo.val("");
            cupo.focus();
        }
    }
    );

    $("#nuevo").click(function () {
        $(".inputusuario").limpiarCampos();
    })

    $("#ver").click(function () {
        if ($(this).is(":checked")) {
            var id = $("#formusuario #id").val(), obj = {};
            obj.id = id;
            var res = crud(obj, 'usuarios/Clave', 'JSON');
            res.success(function (data) {
                $("#clave").attr("type", 'text').val(data.cont);
                $("#confirmacion").attr("type", 'text').val(data.cont);
            })
        } else {
            $("#clave").attr("type", 'password');
            $("#confirmacion").attr("type", 'password');
        }

    });
})

function muestraCupo() {
    var cupo = 0;

    var res = crud(null, 'usuarios/muestraCupo', 'JSON');
    res.success(function (data) {
        cupo = (data.saldogerencia.cupodisponible <= 0) ? 0 : data.saldogerencia.cupodisponible;
        $("#informacioncupo").html("<b>Cupo Gerencia</b>: " + data["saldogerencia"].cupogerencia + " <b>Disponible: </b>" + cupo + " <b>Gerencia</b>: " + data.saldogerencia.gerencia);
    })
}

function gestion(id) {
    var objeto = {};
    objeto.id = id;
    var res = crud(objeto, 'usuarios/obtieneUsuarioId', 'JSON');
    $("#borrar").removeClass("hidden");
    res.success(function (data) {
        $("#ver").attr("disabled", false);
        $("#formusuario #idsector").cargarSelect(data["sectores"].sectores);
        $("#formusuario #idsector").attr("disabled", false);
        $("#formusuario #cupo").attr("disabled", true);
        $(".inputusuario").cargarCampos(data["usuario"]);
        mensaje("mensajealerta", 'hidden');
    })
}
