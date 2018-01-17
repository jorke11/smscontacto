$(function() {
    cargaTabla("jerarquias", 5);

    $("#nuevo").click(function() {
        $(".frmgerencia").limpiarCampos();
    })

    $("#creaGerencia").click(function() {
        var valida = $(".frmgerencia").valida();
        if (valida == 0) {
            var datos = $("#formgerencia").serialize();
            var res = crud(datos, 'jerarquias/gestion', 'JSON');
            res.success(function(data) {
                if (data.id != '') {
                    $("#borrar").addClass("hidden");
                    $(".alertok").removeClass("hidden").html("<b>Operación Realizada</b>")
                } else {
                    $(".alertok").addClass("hidden");
                }
                $(".frmgerencia").limpiarCampos();
                $("#formgerencia #idpadre").attr("disabled", true);
                recargar();
            })
        } else {
            alert("Campos obligatorios!");
        }
    });

    $("#borrar").click(function() {

        var obj = {};

        if (confirm("Esta seguro de borrar el Registro!")) {
            obj.id = $("#formgerencia #id").val();
            obj.tabla = "jerarquias";
            var res = crud(obj, 'jerarquias/borrar', 'HTML');
            res.success(function(data) {
                $(".frmgerencia").limpiarCampos();
                if (data > 0) {
                    $("#borrar").addClass("hidden");
                    $(".alertok").removeClass("hidden").html("<b>Operación Realizada</b>")
                }else{
                    $(".alertok").addClass("hidden");
                }
                recargar();
            })

        }

    })
    $("#tipo").change(function() {
        var id = $(this).val();
        switch (id) {
            case '2':
                {
                    var res = crud(null, 'jerarquias/cargaGerencias', 'JSON'), texto = '';
                    res.success(function(data) {
                        $.each(data, function(i, val) {
                            texto += '<option value="' + val.codigo + '">' + val.nombre + '</option>';
                        })

                        $("#idpadre").attr("disabled", false);
                        $("#idpadre").html(texto);
                       
                        $("#formgerencia #cupo").attr("disabled", true);
                    })

                    break;
                }
            case "1":
                {
                    $("#idpadre").empty();
                    $("#idpadre").attr("disabled", true);
                    $("#formgerencia #cupo").attr("disabled", false);
                    break;
                }
            default:
                $("#idpadre").attr("disabled", true);
        }
    })

    $("#codigo").blur(function() {
        var texto = $(this).val(), obj = {};
        obj.buscar = texto;
        if (texto != '') {
            var res = crud(obj, 'jerarquias/validaCodigo', 'JSON');
            res.success(function(data) {
                if (data.respuesta != true) {
                    $("#creaGerencia").attr("disabled", true);
                    alert("Codigo ya existe");
                    $("#codigo").empty();
                } else {
                    $("#creaGerencia").attr("disabled", false);
                }
            })
        }
    });
})

function gestion(id) {
    var obj = {};
    obj.id = id;
    var res = crud(obj, 'jerarquias/obtieneJearaquiasId', 'JSON');
    res.success(function(data) {
        if (data.tipo == '2') {
            $("#formgerencia #cupo").attr("disabled", true);
        } else {
            $("#formgerencia #cupo").attr("disabled", false);
        }
        $("#formgerencia #idpadre").attr("disabled", true);

        $("#borrar").removeClass("hidden");
        $(".alertok").addClass("hidden");
        $(".frmgerencia").cargarCampos(data);
    })
}


