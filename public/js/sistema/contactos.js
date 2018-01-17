$(function() {
    cargaTablaPag('contactos', 4);
//    cargaTabla("contactos", 4);
    
    $("#frmcontacto #idsector").empty();
    $("#registrar").click(function() {
        var valido = $(".contactos").valida();
        if (valido == 0) {
            var datos = $("#frmcontacto").serialize();
            var res = crud(datos, 'contactos/gestion', 'JSON');
            res.success(function(data) {
                if (data.id) {
                    $("#frmcontacto .contactos").limpiarCampos();
                    $(".alertaerror").addClass("hidden");
                    $(".alertacontacto").removeClass("hidden").html("Operación Realizada");
                    cargaTablaPag('contactos', 4);
                } else if (data.error) {
                    $(".alertaerror").removeClass("hidden").html(data.error);
                } else {
                    $(".alertacontacto").addClass("hidden");
                }
                
            })
        } else {
            $(".alertaerror").removeClass("hidden").html("Campos obligatorios");
        }
    })

    $("#nuevo").click(function() {
        $("#frmcontacto .contactos").limpiarCampos();
    })

    $("#borrar").click(function() {

        var obj = {};
        if (confirm("Esta seguro de borrar el Registro!")) {
            obj.id = $("#frmcontacto #id").val();
            var res = crud(obj, 'contactos/borrar', 'HTML');
            res.success(function(data) {
                if (data > 0) {
                    $("#frmcontacto .contactos").limpiarCampos();
                    $("#borrar").addClass("hidden");
                    $(".alertacontacto").removeClass("hidden").html("<b>Operación Realizada</b>")
                } else {
                    $(".alertacontacto").addClass("hidden");
                }
                cargaTablaPag('contactos', 4);
            })

        }
    })

    $("#idgerencia").blur(function() {
        var id = $(this).val(), obj = {}, html = '';
        obj.codigo = id;
        var res = crud(obj, 'contactos/buscaSector', 'JSON');
        res.success(function(data) {
            $.each(data, function(i, val) {
                html += '<option value="' + val.codigo + '">' + val.nombre + '</option>';
            })
            $("#idsector").html(html);
        })
    })


})


function gestion(id) {
    var obj = {};
    obj.id = id;
    var res = crud(obj, 'contactos/obtieneContactoId', 'JSON');
    res.success(function(data) {
        $(".alertacontacto").addClass("hidden");
        $("#frmcontacto .contactos").cargarCampos(data);
        $("#borrar").removeClass("hidden");
    })
}