$(function() {
    cargaTabla("centrocostos", 3);

    $("#registrar").click(function() {

        var valido = $(".centrocosto").valida();
        if (valido == 0) {
            var datos = $("#frmcentrocosto").serialize();
            var res = crud(datos, 'centrocostos/gestion', 'JSON');
            res.success(function(data) {
                if (data.id != '') {
                    $(".alerterror").addClass("hidden");
                    $("#frmcentrocosto .centrocosto").limpiarCampos();
                    $("#borrar").addClass("hidden");
                    $(".alertok").removeClass("hidden").html("<b>Operación Realizada</b>")
                } else {
                    $(".alertok").addClass("hidden");
                }

                recargar();
            })
        }else{
            $(".alerterror").removeClass("hidden").html("Campos vacios!");
        }
    })

    $("#nuevo").click(function() {
        $("#frmcentrocosto .centrocosto").limpiarCampos();
    })

    $("#borrar").click(function() {

        var obj = {};
        obj.id = $("#frmcentrocosto #id").val();
        if (obj.id != '') {
            if (confirm("Esta seguro de borrar el Registro!")) {

                var res = crud(obj, 'centrocostos/borrar', 'HTML');
                res.success(function(data) {
                    if (data == 'ok') {
                        $("#frmcentrocosto .centrocosto").limpiarCampos();
                        $("#borrar").addClass("hidden");
                        $(".alertok").removeClass("hidden").html("<b>Operación Realizada</b>")
                    } else {
                        $(".alertok").addClass("hidden");
                    }
                    recargar();
                })
            }
        } else {
            alert("Seleccione un regisgtro!");
        }
    })


})


function gestion(id) {
    var obj = {};
    obj.id = id;
    var res = crud(obj, 'centrocostos/obtieneCentroId', 'JSON');
    res.success(function(data) {
        $(".alerterror").addClass("hidden");
        $(".alertok").addClass("hidden");
        $("#frmcentrocosto .centrocosto").cargarCampos(data);
    })
}