var ruta;
$(function () {
    ruta = $("#ruta").val();
    actualizaCupo();
})

function humanFileSize(size) {
    var i = Math.floor(Math.log(size) / Math.log(1024));
    return (size / Math.pow(1024, i)).toFixed(2) * 1 + ' ' + ['B', 'kB', 'MB', 'GB', 'TB'][i];
}

function limitar_teclado() {
    /* validamos que solo permita números */
    $('.numeros').validCampoFranz('0123456789');
    $('.numerosfecha').validCampoFranz('0123456789:-');
    /* validamos que solo permita letras */
    $('.letras').validCampoFranz(' abcdefghijklmnñopqrstuvwxyzáéiou');
    /* validamos que solo permita letras */
    $('.letrasycaracteres').validCampoFranz(' abcdefghijklmnñopqrstuvwxyzáéioóuú./&-_0123456789()');
}
function crud(datos, controlador, tipo) {
    tipo = tipo || 'json';
    return $.ajax({
        url: controlador,
        type: 'POST',
        data: datos,
        dataType: tipo
    });
}


function cargaPagina(controlador) {
    $.ajax({
        url: controlador,
        type: 'POST',
        beforeSend: function () {
            $(".modalcarga").modal("show");
        },
        success: function (data) {
            actualizaCupo();
            $("#container-tabs").empty();
            $("#container-tabs").append(data);

            $(".modalcarga").modal("hide");
        }, error: function () {
            if (confirm("No se ha podido cargar la pagina, contactar con soporte")) {
                location.href = "inicio";
            }
        }
    });
    return false;
}
function log(string) {
    console.log(string);
    return false;
}

function actualizaCupo(id) {
    id = id || ".contenedorfull #cupo";
    var res = crud(null, 'inicio/consultaSaldo', 'JSON');
    res.success(function (data) {
        $(id).empty();
        $(id).html("<b>Cupo: " + data.cupo + ' SMS</b><a href="inicio/cerrarSession">Salir</a>');
        $(".cupo2").html("<b>Cupo: " + data.cupo + ' SMS</b><a href="inicio/cerrarSession">Salir</a>');
    });
}


function AddColumnas(columnas) {
    var arreglo = new Array();
    for (var i = 0; i < columnas; ++i) {
        arreglo[i] = i;
    }
    return arreglo
}

var table;
function cargaTabla2(controlador, columnas) {
    var target = AddColumnas(columnas);

    table = $("#table" + controlador).DataTable({
        "sDom": 'T<"clear"><"H">t<"F"ip>',
        "sAjaxSource": controlador + "/cargaTabla",
        "columnDefs": [
            {
                "targets": [0],
                "visible": false,
                "searchable": false
            },
            {
                "targets": target,
                "mRender": function (data, type, full) {
                    return '<a href="#" onclick="gestion(' + full[0] + ')">' + data + '</a>';
                }
            }
        ],
        "language": {
            "url": "//cdn.datatables.net/plug-ins/be7019ee387/i18n/Spanish.json"
        },
    });

}


var tabla = '';
function cargaTabla(id, columnas) {
    var arreglo = CrearString(columnas);
    // Setup - add a text input to each footer cell
    $('#' + id + ' tfoot th').each(function () {
        var title = $('#' + id + ' thead th').eq($(this).index()).text();
        $(this).html('<input type="text"  placeholder="' + title + '" />');
    });

    tabla = $("#table" + id).DataTable({
        "sDom":
                "R<'row'<'col-lg-3 col-md-6'l><'col-lg-4 col-right col-md-6'f>r>" +
                "<'row'<'col-lg-12 col-md-12't>>" +
                "<'row'<'col-lg-4 col-md-4'i><'col-lg-8 col-md-8'p>>",
        "ajax": ruta + id + "/cargaTabla",
        "aaSorting": [[0, "asc"]],
//        "language":{
//            "url":ruta+'librerias/internacional/spanish.json'
//        },
        "aoColumnDefs": [
            {
                "targets": [0],
                "visible": false,
                "searchable": false
            },
            {
                "aTargets": arreglo,
                "mRender": function (data, type, full) {
                    return '<a href="#" onclick="gestion(' + full[0] + ')">' + data + '</a>';
                }
            }
        ]

    });

}


var table;
function crearTabla(id, columnas, metodo) {
    var arreglo = CrearString(columnas);
    metodo = metodo || 'gestion';
    table = $("#tabla" + id).DataTable({
        "sAjaxSource": id + "/cargaTabla",
        "destroy": true,
        "language": {
            "url": '//cdn.datatables.net/plug-ins/be7019ee387/i18n/Spanish.json'
        },
        "aoColumnDefs": [
            {
                "targets": [0],
                "visible": false,
                "searchable": false
            },
            {
                "aTargets": arreglo,
                "mRender": function (data, type, full) {
                    return '<a href="#" onclick="' + metodo + '(' + full[0] + ')">' + data + '</a>';
                }
            }
        ]
    });
}


var oTable2;
function cargaTablaPag(id, columnas) {
    var arreglo = CrearString(columnas);
    oTable2 = $("#table" + id).dataTable({
        "sDom":
                "R<'row'<'col-lg-3 col-md-6'l><'col-lg-6 col-md-0'><'col-lg-3 col-md-6'f>r>" +
                "<'row'<'col-lg-12 col-md-12't>>" +
                "<'row'<'col-lg-5 col-md-3'i><'col-lg-6 col-md-6 text-center'p><'col-lg-3 col-md-3'>>",
        "bProcessing": true,
        "bServerSide": true,
        "sServerMethod": "GET",
        "sAjaxSource": id + '/cargaTabla',
        "iDisplayLength": 10,
        "aaSorting": [[1, 'desc']],
        "aoColumnDefs": [
            {
                "targets": [0],
                "visible": false,
                "searchable": false
            },
            {
                "aTargets": arreglo,
                "mRender": function (data, type, full) {
                    return '<a href="#" onclick="gestion(' + full[0] + ')">' + data + '</a>';
                }
            }
        ], "destroy": true

    });
}

var tabla;
function loadData(id, columnas) {
    var arreglo = CrearString(columnas);
    tabla = $("#table" + id).dataTable({
        "sDom":
                "R<'row'<'col-lg-3 col-md-6'l><'col-lg-6 col-md-0'><'col-lg-3 col-md-6'f>r>" +
                "<'row'<'col-lg-12 col-md-12't>>" +
                "<'row'<'col-lg-5 col-md-3'i><'col-lg-6 col-md-6 text-center'p><'col-lg-3 col-md-3'>>",
        ajax: {
            url: id + '/cargaTabla',
            type: 'POST',
            dataType: 'JSON'
        },
        "bProcessing": true,
        "bServerSide": true,
        "iDisplayLength": 10,
        "aaSorting": [[2, 'DESC']],
        "aoColumnDefs": [
            {
                "targets": [0],
                "visible": false,
                "searchable": false
            },
            {
                "aTargets": arreglo,
                "mRender": function (data, type, full) {
                    return '<a href="#" onclick="gestion(' + full[0] + ')">' + data + '</a>';
                }
            }
        ], "destroy": true

    });
}

function CrearString(tam) {
    var string = '';
    var arreglo = new Array();
    for (var i = 0; i < tam; i++) {
//        string += (string == '') ? '' : ',';
//        string += i;
        arreglo[i] = i;
    }

//    return string;
    return arreglo;
}

function tablaReporte2(controlador) {
    var texto = '';
    $("#" + controlador + "tbody").empty();
    $.ajax({
        url: 'informes/' + controlador,
        type: 'POST',
        dataType: "JSON",
        success: function (data) {
            if (data["data"] != "") {
                $.each(data["data"], function (i, val) {
                    texto += "<tr>";
                    $.each(val, function (j, valor) {
                        texto += "<td>" + valor + "</td>";
                    })
                    texto += "</tr>";
                });
                $("#" + controlador).append(texto);
            } else {
                $("#" + controlador).append("<tr><td>No se encontraron registros</td></td>");
            }
        }
    })
}

function tablaReporte(controlador) {
    table = $("#" + controlador).DataTable({
        "sAjaxSource": "informes/" + controlador,
        "columnDefs": [
            {
                "targets": [0],
                "visible": false,
                "searchable": false
            },
            {
                "targets": [1, 2, 3],
                "mRender": function (data, type, full) {
                    return '<a href="#" onclick="gestion(' + full[0] + ')">' + data + '</a>';
                }
            }
        ],
        "language": {
            "url": "//cdn.datatables.net/plug-ins/be7019ee387/i18n/Spanish.json"
        },
        "destroy": true,
    });
}

function recargar() {
    tabla.ajax.reload();
}

function arrayFilter(datos) {

    var arreglo = new Array();
    var cont = 0;
    $.each(datos, function (i, val) {
        if (val != '' && val != undefined) {
            arreglo[cont] = val;
            ++cont;
        }
    })
    return arreglo;
}

function fecha() {
    var elem = $(this);
    var d = new Date();
    var fecha = '';
    mes = (mes <= 9) ? '0' + mes : mes;
    return d.getDate() + "/" + mes + "/" + d.getFullYear() + " " + d.getHours() + ':' + d.getMinutes();

}

function mensaje(clase, activo, mensaje) {
    activo = activo || true;
    mensaje = mensaje || '<h4>Operación Realizada</h4>';
    if (activo == true) {
        $("." + clase).removeClass("hidden").removeClass("alert-danger").removeClass("alert-warning").addClass("alert-success").html(mensaje);
    } else if (activo == 'hidden') {
        $("." + clase).addClass("hidden");
    } else if (activo == 'error') {
        $("." + clase).removeClass("hidden").removeClass("alert-success").removeClass("alert-warning").addClass("alert-danger").html(mensaje);
    } else if (activo == 'war') {
        $("." + clase).removeClass("hidden").removeClass("alert-success").removeClass("alert-danger").addClass("alert-warning").html(mensaje);
    }

}

var tick;
function stop() {
    clearTimeout(tick);
}
function simple_reloj() {
    var ut = new Date();
    var h, m, s;
    var time = " ";

    var meses = new Array("Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre");
    var diasSemana = new Array("Domingo", "Lunes", "Martes", "Miércoles", "Jueves", "Viernes", "Sábado");
    var fecha = diasSemana[ut.getDay()] + " " + ut.getDate() + " de " + meses[ut.getMonth()] + " de " + ut.getFullYear();
    h = ut.getHours();
    m = ut.getMinutes();
    s = ut.getSeconds();
    if (s <= 9)
        s = "0" + s;
    if (m <= 9)
        m = "0" + m;
    if (h <= 9)
        h = "0" + h;
    time += h + ":" + m + ":" + s;
    $("#reloj").html(fecha + " " + time);
    tick = setTimeout("simple_reloj()", 1000);
}

function removeNewlines(str) {
//remove line breaks from str
    str = str.replace(/\s{2,}/g, ' ');
    str = str.replace(/\t/g, ' ');
    str = str.toString().trim().replace(/(\r\n|\n|\r)/g, "");
    return str;
}

var normalize = (function () {
    var from = "ÃÀÁÄÂÈÉËÊÌÍÏÎÒÓÖÔÙÚÜÛãàáäâèéëêìíïîòóöôùúüûÑñÇç",
            to = "AAAAAEEEEIIIIOOOOUUUUaaaaaeeeeiiiioooouuuunncc",
            mapping = {};

    for (var i = 0, j = from.length; i < j; i++)
        mapping[ from.charAt(i) ] = to.charAt(i);

    return function (str) {
        var ret = [];
        for (var i = 0, j = str.length; i < j; i++) {
            var c = str.charAt(i);
            if (mapping.hasOwnProperty(str.charAt(i)))
                ret.push(mapping[ c ]);
            else
                ret.push(c);
        }
        return ret.join('');
    }

})();