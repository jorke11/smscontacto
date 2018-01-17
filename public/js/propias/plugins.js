jQuery.fn.cuentaPalabras = function(id) {
    var cont = 0;
    var espacios;
    var palabras = '';
    var sms = 1;
    this.each(function() {
        var elem = $(this);
        elem.keyup(function(e) {
            
            espacios = elem.val().split(" ");
            palabras = espacios.length;
            sms = Math.ceil(parseInt(elem.val().length) / 160);
            $(id).html(elem.val().length + " de 160 caracteres <br>" + palabras + " palabras | " + sms + " parte(s)");
        })

    });
}

jQuery.fn.cargarSelect = function(data) {
    var html = '';
    this.each(function() {
        var elem = $(this);
        $.each(data, function(i, val) {
            html += "<option value='" + val.valor + "'>" + val.texto + "</option>";
        })
        elem.html(html);
    });
}



jQuery.fn.cargarCampos = function(data) {

    this.each(function() {
        var elem = $(this);
        $.each(data, function(i, val) {

            if (elem.attr("name") == i) {
                if (elem.attr('type') == 'checkbox') {
                    (val == 1) ? elem.prop('checked', true) : elem.prop('checked', false);
                } else if (elem.get(0).tagName == 'IMG') {
                    elem.attr("src", val);
                } else if (elem.attr("type") == 'file') {
                    elem.attr("disabled", true);
                } else {
                    elem.val(val);
                }
            }
        })

    })

}



jQuery.fn.limpiarCampos = function() {
    var id;
    this.each(function() {
        var elem = $(this);
        $(elem).attr("disabled", false);
        if (elem.get(0).tagName == 'SELECT') {
            elem.val("0");
            elem.removeClass("ok").removeClass("error");
        } else if (elem.get(0).tagName == 'IMG') {
            elem.attr("src", '');
            elem.attr("alt", 'foto');
        } else if (elem.attr("type") == "checkbox") {
            if (elem.attr("estado") == 'activo') {
                elem.attr("checked", true);
            } else {
                elem.attr("checked", false);
            }

        } else if (elem.attr("estado") == 'disabled') {
            $(elem).val(0);
            $(elem).attr("disabled", true);
        } else {
            if (elem.hasClass("select2-offscreen")) {
                id = elem.attr("id");
                $("#" + id).select2('data', {id: 0, text: 'Seleccione una Opcion...'});
            } else if (elem.hasClass("fechahora")) {
                elem.fechaActual();
            } else {

                if (elem.attr("bloqueado") == 'ok') {
                    elem.val("");
                    $(elem).attr("disabled", true);
                } else {
                    elem.val("");
                    elem.removeClass("ok").removeClass("error");
                }

            }

        }
    });
    return this;
}

jQuery.fn.validaTextarea = function() {
    var sms = {};
    this.each(function() {
        var elem = $(this);
        var cont = 0;
        var ok = 0;
        var num = '';
        var numeros = elem.val();
        numeros = numeros.split(/\n/g);
        if (numeros.length > 0 && numeros != '') {
            for (var i = 0; i < numeros.length; i++) {
                if (numeros[i] != '') {
                    if (numeros[i].length != 10 || isNaN(numeros[i])) {
                        ++cont;
                    } else {
                        ++ok;
                        num += (num == '') ? '' : ',';
                        num += numeros[i];
                    }
                }
            }

            sms.ok = (cont == 0) ? true : "El numero presenta errores";
            sms.valido = ok;
            sms.numeros = num;
        } else {
            sms = "no hay remitentes";
        }

    });
    return sms;
}


jQuery.fn.fechaActual = function() {
    this.each(function() {
        var elem = $(this);
        var d = new Date();
        var fecha = '';
        var mes = d.getMonth() + 1;
        mes = (mes <= 9) ? '0' + mes : mes;
        fecha = d.getDate() + "/" + mes + "/" + d.getFullYear() + " " + d.getHours() + ':' + d.getMinutes();
        elem.val(fecha);
    })
    return this;
}

jQuery.fn.toggleAttr = function(attr) {
    return this.each(function() {
        var self = $(this);
        log(self.attr(attr));
        if (self.attr(attr) == 'checked')
            self.attr('checked', false);
        else
            self.attr(attr, true);
    });
};
jQuery.fn.valida = function() {
    var cont = 0;
    this.each(function() {
        var elem = $(this);
        if (elem.attr("obligatorio") == 'alfanumerico') {

            if (elem.val().match(/^[0-9]+$/)) {
                ++cont;
                elem.addClass("error");
            } else {
                elem.removeClass("error");
                if (elem.hasClass("error2")) {
                    elem.removeClass("error2");
                } else {
                    elem.removeClass("error");
                }
            }
            if (elem.val() == '') {
                ++cont;
                elem.addClass("error");
            } else {
                elem.removeClass("error");
            }

        } else if (elem.attr("obligatorio") == 'numero') {

            if (elem.val() == '' || elem.val() == 0) {

                if (elem.hasClass("select2-offscreen")) {
                    elem.addClass("error2");
                } else {
                    elem.addClass("error");
                }
                ++cont;
            } else {
                if (elem.val() != null) {
                    if (!elem.val().match(/^[0-9]+$/)) {
                        ++cont;
                        elem.addClass("error");
                    } else {
                        elem.removeClass("error");
                        if (elem.hasClass("error2")) {
                            elem.removeClass("error2");
                        } else {
                            elem.removeClass("error");
                        }

                    }
                } else {
                    ++cont;
                    elem.addClass("error");
                }

            }
        } else if (elem.attr("obligatorio") == 'alfa') {
            if (elem.val() == '' || elem.val() == null) {
                ++cont;
                elem.addClass("error");
            } else {
                elem.removeClass("error");
            }
        }

    })
    return cont;
}