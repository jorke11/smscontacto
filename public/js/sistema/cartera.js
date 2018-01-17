var ord = '';

$(function () {

    $("#mensaje").cuentaPalabras("#frmcartera #carateres");

    var res = crud(null, 'cartera/datosTabla');
    res.success(function (data) {
        tabla(data);
    })

    $("#enviar").click(function () {
        var men = '';
        var datos = $("#frmcartera").serialize();

        $.ajax({
            url: 'cartera/envioCartera',
            type: 'POST',
            data: datos,
            dataType: 'JSON',
            beforeSend: function () {
                $(".modalcarga").modal("show");
            },
            success: function (data) {
                if (data.registros != 0 && data.errores == 0) {
                    $(".modalcarga").modal("hide");
                    men = "Operación realizada. " + data.registros + " SMS cargados, Codigo: " + data.idbase;
                    mensaje("alertamensaje", null, men);
                    $("#frmcartera #mensaje").val("");
                    $(".grupocartera").prop("checked", false);
                    $(".contenedorfull #cupo").html("<b>Cupo: " + data.cupoactual + ' SMS</b><a href="inicio/cerrarSession">Salir</a>');
                } else {
                    mensaje("alertamensaje", "error");
                }
            }
        });


//        var res2 = crud(datos, 'cartera/envioCartera');
//        res2.success(function (data) {
//            if (data.registros != 0 && data.errores == 0) {
//                men = "Operación realizada. " + data.registros + " SMS cargados, Codigo: " + data.idbase;
//                mensaje("alertamensaje", null, men);
//                $("#frmcartera #mensaje").val("");
//                $(".grupocartera").prop("checked", false);
//                $(".contenedorfull #cupo").html("<b>Cupo: " + data.cupoactual + ' SMS</b><a href="inicio/cerrarSession">Salir</a>');
//            } else {
//                mensaje("alertamensaje", "error");
//            }
//        })
    })

})

function tabla(data) {
    var html = '';
    $("#tablacartera tbody").empty();
    html += "<tr>";
    html += '<td colspan="3" ><b>SELECCIONAR TODOS</b></td>';
    html += '<td><input type="checkbox" id="todos" onclick=seltodos(this)></td>';
    html += "</tr>";
    $.each(data, function (i, val) {
        html += "<tr>";
        html += '"<td><a href="#" onclick=check(' + val.id + ')>' + val.diamora + "</a></td>";
        html += '"<td><a href="#" onclick=check(' + val.id + ')>' + val.nombre + "</a></td>";
        html += '"<td><a href="#" onclick=check(' + val.id + ')>' + val.celular + "</a></td>";
        html += '<td><input type="checkbox" id="' + val.id + '" value="' + val.celular + '" name="cartera[]" class="grupocartera"></td>';
        html += "</tr>";
    })

    $("#tablacartera tbody").html(html);
}

function seltodos(id) {

    if ($(id).is(":checked")) {
        $(".grupocartera").prop("checked", true);
    } else {
        $(".grupocartera").prop("checked", false);
    }
}

function ordenar(order) {

    if (ord == order) {
        order = order + ' DESC ';
        ord = '';
    } else {
        ord = order;
    }
    var obj = {};
    obj.order = order;
    var res = crud(obj, 'cartera/datosTabla');
    res.success(function (data) {
        tabla(data);
    })
}

function check(id) {

    if ($("#" + id).is(":checked")) {
        $("#" + id).prop("checked", true);
    } else {
        $("#" + id).prop("checked", true);
    }

}
