<script src="<?php echo base_url() ?>public/js/sistema/simple.js"></script>

<style>
    .contenido{
        max-height: 150px; 
        overflow: auto;
        border: 1px solid #ccc;
        border-radius: 5px;
    }
    .contenidomodal{
        max-height: 150px; 
        overflow: auto;
        border: 1px solid #ccc;
        border-radius: 5px;
    }
    .contenido2{
        height: 100px; 
        overflow: auto;
        border: 1px solid #ccc;
        border-radius: 5px;
    }
</style>
<div class="row">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title">Envios Simplificados</h3>
        </div>
        <div class="panel-body">
            <div class="row">
                <form id="frmsimple" name="frmsimple">
                    <div class="col-lg-7">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h3 class="panel-title">Crear mensaje nuevo</h3>
                            </div>
                            <div class="panel-body">
                                <div class="row">
                                    <div class="alert mensajealerta hidden"></div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-4">
                                        <div class="form-group">
                                            <label for="calve">Destinatarios</label>
                                            <textarea class="form-control frmenviosimple" id="destinarios" rows="7" name="destinarios"></textarea>
                                        </div>
                                        <div class="alert alert-danger hidden" id="mensajedestino"></div>
                                    </div>
                                    <div class="col-lg-8">
                                        <label for="calve">Contactos</label>
                                        <div class="contenido">    
                                            <table class="table table-hover">
                                                <?php
                                                $contactos = $this->session->userdata("contactos");
                                                if (count($contactos) > 0 && $contactos != FALSE) {
                                                    ?>
                                                    <tr>
                                                        <td><input type="checkbox" id="seleccionartodos">&nbsp;<b>Seleccionar Todos</b>
                                                        </td>
                                                    </tr>
                                                    <?php
                                                    foreach ($contactos as $value) {
                                                        ?>
                                                        <tr>
                                                            <td>
                                                                <input type="checkbox" name="contactos[]" class="infocontactos" nombre="<?php echo $value["nombre"] ?>" value="<?php echo $value["celular"] ?>"> <?php echo $value["nombre"] . " " . $value["celular"] ?>
                                                            </td>
                                                        </tr>
                                                        <?php
                                                    }
                                                } else {
                                                    ?>
                                                    <tr>
                                                        <td>No tienes usuarios Asociados</td>
                                                    </tr>    
                                                    <?php
                                                }
                                                ?>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <div class="espacio10"></div>
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            <label for="calve">Mensaje</label>
                                            <textarea class="form-control frmenviosimple" id="mensaje" name="mensaje" ></textarea>
                                        </div>
                                        <div class="alert alert-danger hidden" id="mensajealerta"></div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-12">
                                        <p id="carateres">0 de 160 caracteres<br>
                                            0 palabras partes
                                        </p>

                                    </div>

                                </div>
                                <div class="espacio10"></div>
                                <div class="row">
                                    <div class="col-lg-3">Fecha de Envio</div>
                                    <div class="col-lg-6">
                                        <div class="input-group">
                                            <input type="text" class="form-control fechas frmenviosimple" id="fechaenvio" name="fechaenvio" placeholder="dd-md-YYYY">
                                            <span class="input-group-btn">
                                                <button class="btn btn-default" type="button">
                                                    <span class="glyphicon glyphicon-calendar"></span>
                                                </button>
                                            </span>
                                        </div>
                                    </div>

                                </div>

                                <div class="espacio10"></div>
                                <div class="row">
                                    <div class="col-lg-3 col-lg-offset-9">
                                        <button class="btn btn-primary" id="enviarSimple" name="enviarSimple" type="button">Enviar</button>
                                    </div>
                                </div>

                            </div>
                        </div>


                    </div>
                    <div class="col-lg-5">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h3 class="panel-title">Ayuda</h3>
                            </div>
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <ul>
                                            <li><p>Es posible realizar envíos sencillos de SMS, unitario o por contactos.</p></li>

                                            <li><p>Envío unitario: inserte los números en el campo Destinatarios, 
                                                    uno bajo el otro. 
                                                    No incluya espacios o cualquier carácter no numérico.</p></li>

                                            <li><p>En caso de enviar. Finalice haciendo click en Enviar.</p></li>
                                        </ul>
                                    </div>

                                </div>
                            </div>
                        </div>


                    </div>

                </form>
            </div>
        </div>
    </div>
</div>




<div class="modal fade modalconfirmacion">
    <div class="modal-dialog">
        <div class="modal-content ">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title">Confirmación de Envio</h4>
            </div>
            <div class="modal-body">
                <div class="container-fluid infomodal">
                    <div class="row">
                        <div class="col-lg-1 destino hidden"><label>Destino</label></div>
                        <div class="col-lg-3 destino hidden">
                            <div class="contenido2">
                                <table id="listadestino" class="table table-condensed table-hover table-bordered"></table>
                            </div>
                        </div>
                        <div class="col-lg-2 contacto hidden"><label>Contactos</label></div>
                        <div class="col-lg-5 contacto hidden">
                            <div class="contenido2">
                                <table id="listacontactos" class="table table-condensed table-hover table-striped table-bordered"></table>
                            </div>
                        </div>
                    </div>
                    <div class="espacio"></div>
                    <div class="row">
                        <div class="col-lg-6">
                            <p id="totalcontactos"></p>
                        </div>
                    </div>
                    <div class="espacio"></div>
                    <div class="row">
                        <div class="col-lg-6"><p id="totalmensaje"></p></div>
                    </div>
                    <div class="espacio"></div>
                    <div class="row">
                        <div class="col-lg-12"><p id="txtmensaje"></p></div>
                    </div>
                    <div class="espacio"></div>
                    <div class="row">
                        <div class="col-lg-6"><p id="programacion"></p></div>
                    </div>
                    <div class="espacio"></div>
                    <div class="row">
                        <div class="col-lg-6"><p id="cupoactual"></p></div>
                    </div>
                    <div class="espacio"></div>
                    <div class="row">
                        <div class="col-lg-6"><p id="codigo"></p></div>
                    </div>


                </div>
                <div class="container-fluid hidden cargando">
                    <div class="row">
                        <div class="col-lg-6 col-center">
                            <img src="<?php echo base_url() ?>imagenes/cargando.gif">
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" id="btnconfirmacion">Confirmar</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->