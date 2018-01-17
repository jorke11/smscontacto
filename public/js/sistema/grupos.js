$(function() {
    cargaTabla("grupos",4);
    $("#registrar").click(function() {
        var datos = $("#formgrupos").serialize();

        var res = crud(datos, 'grupos/gestion', 'JSON');
        res.success(function(data) {
            recargar();
        })
    });

    $("#add").click(function() {
        $('#listacontactos option:selected').appendTo($("#gruponuevo"));
    })
    
    $("#delete").click(function(){
        $('#gruponuevo option:selected').appendTo($("#listacontactos"));
    })
})

function gestion(id) {
    var obj = {};
    obj.id = id;
    var res = crud(obj, 'grupos/obtieneGruposId', 'JSON');
    res.success(function(data) {
        $("#formgrupos .frmgrupos").cargarCampos(data);
    })
}


