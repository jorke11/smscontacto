$(function() {
    var texto = '';
    $("#procesoftp").click(function() {
        $.ajax({
            type: 'POST',
            url: "cargaexcel/cargaFtp",
            dataType: 'JSON',
            beforeSend: function() {
                $(".cargando").removeClass("hidden");
            },
            success: function(data) {
                if (data.insertados) {
                    $(".cargando").addClass("hidden");
                    texto = "Registros procesados: " + data.insertados + "<br><b>Para que los contactos sean cargando es necesario Cerrar Sesión</p>";
                    $(".alertftp").removeClass("hidden").html(texto);
                } else {
                    $(".alertftp").addClass("hidden");
                }

            },error:function(){
                alert("Problemas al leer el archivo, por favor revise y vuelva a intentarlo");
                $(".cargando").addClass("hidden");
            }
        });


    });
});