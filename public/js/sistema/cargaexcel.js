
$(":file").change(function() {
    var archivo;
    var string = '';
    archivo = $("#archivo")[0].files[0];
    if (archivo.type == "application/vnd.ms-excel") {
        string = "<b>Tamaño Aproximado: " + humanFileSize(archivo.size);
        $("#informacion").removeClass("hidden").html(string);
    } else {
        alert("Formato del archivo no valido");
        $("#archivo").val("");
    }

});
$("#subir").click(function() {
    $(".alertok").addClass("hidden");
    $(".loading").addClass("hidden");

    var archivo = $("#archivo").val();
    if (archivo != '') {
        var formData = new FormData($("#frmcargaexcel")[0]);
        $.ajax({
            url: 'cargaexcel/subeArchivo',
            type: "POST",
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            dataType: "JSON",
            beforeSend: function() {
                $(".loading").removeClass("hidden");
                $("#subir").attr("disabled", true);
            },
            success: function(data) {
                grabaDatos(data.idarchivo);
            },
            complete: function() {

            }
        })
    } else {
        alert("No hay archivos seleccionados");
    }
});

function grabaDatos(id) {
    var string = {},texto='';
    
    string.idarchivo = id
    $.ajax({
        url: 'cargaexcel/grabaDatos',
        type: 'POST',
        dataType: 'JSON',
        data: string,
        success: function(data) {
            $(".loading").addClass("hidden");
            texto = "Registros procesados: " + data.insertados + "<br><b>Para que los contactos sean cargando es necesario Cerrar Sesión</p>";
            $(".alertok").removeClass("hidden").html(texto);
            $("#subir").attr("disabled", false);
            $("#archivo").empty();
        }
    })

}