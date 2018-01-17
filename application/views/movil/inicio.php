<script src="<?php echo base_url() ?>public/js/sistema/movil/enviosimple.js"></script>

<form id="frmmoviil" name="frmmoviil">
    <div class="row hidden-lg hidden-md hidden-sm">
        <div class="col-xs-12 col-center">
            <div class="alert hidden mensajemovil" ></div>  
        </div>

    </div>
    <div class="row hidden-lg hidden-md hidden-sm">

        <div class="col-xs-12">

            <div class="panel-group hidde" id="accordion">
                <div class="panel panel-default hidden">
                    <div class="panel-heading">
                        <h4 class="panel-title">
                            <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#enviomanual">
                                Ingreso Manual
                            </a>
                        </h4>
                    </div>
                    <div id="enviomanual" class="panel-collapse collapse">
                        <div class="panel-body">
                            <button type="button" id="inputManual" class="btn btn-default form-control">Ingresar Numeros</button>
                        </div>
                    </div>
                </div>

                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4 class="panel-title">
                            <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#gruposmovil">
                                Seleccionar Grupos
                            </a>
                        </h4>
                    </div>
                    <div id="gruposmovil" class="panel-collapse collapse">
                        <div class="panel-body">
                            <button type="button" id="inputGrupos" class="btn btn-default form-control">Seleccionar Grupos</button>
                        </div>
                    </div>
                </div>


                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4 class="panel-title">
                            <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#contactosmovil">
                                Seleccionar Contactos
                            </a>
                        </h4>
                    </div>
                    <div id="contactosmovil" class="panel-collapse collapse">
                        <div class="panel-body">
                            <button type="button" id="inputContacto" class="btn btn-default form-control">Seleccionar Contactos</button>
                        </div>
                    </div>
                </div>

                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4 class="panel-title">
                            <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#smsmanuales">
                                Mensaje Individual
                            </a>
                        </h4>
                    </div>
                    <div id="smsmanuales" class="panel-collapse collapse">
                        <div class="panel-body">
                            <button type="button" id="btnmanual" class="btn btn-default form-control">Digita los numeros</button>
                        </div>
                    </div>
                </div>

                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4 class="panel-title">
                            <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#smscartera">
                                Cartera
                            </a>
                        </h4>
                    </div>
                    <div id="smscartera" class="panel-collapse collapse">
                        <div class="panel-body">
                            <button type="button" id="btncartera" class="btn btn-default form-control">Seleccciona los numeros</button>
                        </div>
                    </div>
                </div>


                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4 class="panel-title">
                            <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#mensajemovil">
                                Mensaje
                            </a>
                        </h4>
                    </div>
                    <div id="mensajemovil" class="panel-collapse collapse in">
                        <div class="panel-body">

                            <div class="row">
                                <div class="col-xs-12">
                                    <label>Mensajes</label>
                                </div>
                                <div class="espacio"></div>
                                <div class="col-xs-12">
                                    <textarea class="form-control enviomovil" id="txtmensajemovil" name="txtmensajemovil"></textarea>
                                </div>
                                <div class="col-xs-12">
                                    <p id="carateres">0 de 160 caracteres<br>
                                        0 palabras partes
                                    </p>

                                </div>
                            </div>
                            <div class="espacio"></div>
                            <div class="row">
                                <div class="col-xs-12">
                                    <label>Fecha envio</label>
                                </div>
                                <div class="espacio"></div>
                                <div class="col-xs-12">
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
                        </div>
                    </div>
                </div>

            </div>


        </div>

        <div class="col-xs-12">
            <button id="enviamovil" type='button' class="btn btn-primary">Enviar</button>
        </div>
    </div>

</form>






<div class="modal fade" id="modalmanual">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Digite los Numeros</h4>
            </div>
            <div class="modal-body">
                <form id="formManual" name="formManual">
                    <div class="row">
                        <div class="col-xs-10 col-sm-10 col-center">
                            <textarea class="form-control" id="numeromanual" type="number"></textarea>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                <!--<button type="button" class="btn btn-success" id="simanual">Validar</button>-->
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->


<div class="modal fade" id="modalcartera">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Selecciona los Numeros</h4>
            </div>
            <div class="modal-body">
                <form id="formManual" name="formCartera">
                    <div class="row">
                        <div class="col-xs-10 col-sm-10 col-center">
                            <table class="table table-hover" id="tblcartera">
                            </table>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
