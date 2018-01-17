$(function() {
    
    cargaTabla("blacklist", 4);

    $("#nuevo").click(function() {
        $(".frmblacklist").limpiarCampos();
    })

    $("#creaBlacklist").click(function() {
        var datos = $("#formblacklist").serialize();
        
        var res = crud(datos, 'blacklist/gestion', 'JSON');
        res.success(function(data){
            recargar();
        })
    });
})

function gestion(id) {
    var obj = {};
    obj.id = id;
    var res = crud(obj, 'blacklist/obtieneBlacklistId', 'JSON');
    res.success(function(data) {
        $("#formblacklist .frmblacklist").cargarCampos(data);
    })
}


