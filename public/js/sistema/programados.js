var tabla;
$(function () {

    $('.fechas').datetimepicker({
        lang: 'es',
        format: 'Y-m-d H:i:s',
        timepicker: false,
    });

    $("#btnreporte").click(function () {
        mensaje("alertamensaje", 'hidden');

        var idbase = $("#frmcancelados #idbase").val(), inicio, final = '', html = '';
        inicio = $("#frmcancelados #inicio").val();
        final = $("#frmcancelados #final").val();
        var datos = $(".inputProgramados");
        tablaProgramados(idbase, inicio, final)

    });

    $("#btncancelar").click(function () {
        var idbase = $("#idbase").val(), inicio, final = '', html = '';
        inicio = $("#inicio").val();

        var rowCount = $('table#tblprogramados tr:last').index() + 1;

        if (rowCount > 0) {
            var datos = {};
            datos.idbase = idbase;
            datos.inicio = inicio;
            var res = crud(datos, 'programados/datosConfirmacion');
            res.success(function (data) {
                $("#tablaresumen tbody").empty();
                $("#modalconfirmacion").modal("show");
                $.each(data, function (i, val) {
                    html += "<tr>";
                    html += '<td><input type="checkbox" name="bases[]" value="' + val.id + '" checked classs="inputcancelados"></td>';
                    html += "<td>" + val.id + "</td>";
                    html += "<td>" + val.fecha + "</td>";
                    html += "<td>" + val.registros + "</td>";
                    html += "<td>" + val.errores + "</td>";
                    html += "</tr>";
                })
                $("#tablaresumen tbody").html(html);
            })
        } else {
            alert("No existen registros para cancelar!");
        }
    });

    $("#frmconfirmacion #confirmacion").click(function () {
		
        var cont = 0, datos, men = '';

        $('input[name="bases[]"]').each(function () {
            
            if ($(this).is(":checked")) {
                cont++;
            }
        })
		
		console.log("contador: "+cont);
		

        if (cont > 0) {
            datos = $("#frmconfirmacion").serialize();
            var res = crud(datos, 'programados/cancelar');
            res.success(function (data) {
                if (data.total > 0) {
                    $("#modalconfirmacion").modal("hide");
                    men = "Total de Registros cancelados : " + data.total;
                    mensaje("alertamensaje", null, men);
                    actualizaCupo();
                }
            })
        } else {
            alert("Es necesario seleccionar un registro!");
        }

    })

});



function tablaProgramados(idbase, inicio, final) {
    if (idbase != '' || inicio != '') {
        var obj = {};
        obj.data = {};
        obj.data.idbase = idbase;
        obj.data.inicio = inicio;
        obj.data.final = final;

        tabla = $("#tblprogramados").DataTable({
            sDom:
                    "R<'row'<'col-lg-3 col-md-6'l><'col-lg-4 col-right col-md-6'f>r>" +
                    "<'row'<'col-lg-12 col-md-12't>>" +
                    "<'row'<'col-lg-4 col-md-4'i><'col-lg-8 col-md-8'p>>",
            ajax: {
                url: "programados/cargaTabla",
                type: "POST",
                dataType: "JSON",
                data: obj
            },
            destroy: true,
            processing: true,
            serverSide: true,
//            aaSorting: [[0, "asc"]],
//        "language":{
//            "url":ruta+'librerias/internacional/spanish.json'
//        },
            aoColumnDefs: [
//                {
//                    targets: [0],
//                    visible: false,
//                    searchable: false
//                },
//                {
//                    aTargets: [1, 2, 3],
//                    "mRender": function (data, type, full) {
//                        return '<a href="#" onclick="gestion(' + full[0] + ')">' + data + '</a>';
//                    }
//                }
            ]

        });

    } else {
        alert("Debes Ingresa Datos para realizar la Busqueda");
    }
}