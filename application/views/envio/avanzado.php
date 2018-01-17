<script src="<?php echo base_url() ?>public/js/sistema/avanzado.js"></script>
<style>
    .contenido{
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
            <h3 class="panel-title">Envios Grupos</h3>
        </div>
        <div class="panel-body">

            <div class="row">
                <div class="col-lg-6">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title">Cargar archivo</h3>
                        </div>
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-lg-3">
                                    Con archivo
                                </div>
                                <div class="col-lg-3">
                                    Si &nbsp;<input type="radio" name="conarchivo" id="conarchivo">&nbsp;&nbsp;&nbsp;&nbsp;
                                    No &nbsp;<input type="radio" name="conarchivo" id="sinarchivo" checked>
                                </div>

                            </div>
                            <div class="espacio10"></div>
                            <div class="row">
                                <div class="col-lg-4">
                                    <a href="<?php echo base_url() ?>template/templatecarga.xls">Exportar Template</a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="panel panel-default hidden" id="contenidoarchivo">
                        <div class="panel-heading">
                            <h3 class="panel-title">Subir Archivo</h3>
                        </div>
                        <div class="panel-body">
                            <form enctype="multipart/form-data" class="form" id="form">
                                <div class="row">
                                    <div class="col-lg-2">
                                        Archivo
                                    </div>
                                    <div class="col-lg-10">
                                        <input type="file" name="archivo" id="archivo">
                                    </div>
                                </div>
                                <div class="espacio10"></div>
                                <div class="row">
                                    <div class="col-lg-3">
                                        <button class="btn btn-success" type='button' id="subir">Subir</button>
                                    </div>
                                    <div class="col-lg-2 cargadocontacto hidden" >
                                        <img src="<?php echo base_url()?>imagenes/loading.gif" class="img-responsive">
                                    </div>
                                </div>
                                <div class="espacio10"></div>
                                <div class="row">
                                    <div class="alert mensajesubida hidden"></div>
                                </div>

                            </form>
                        </div>
                    </div>


                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title">Crear mensaje nuevo</h3>
                        </div>
                        <div class="panel-body">
                            <form id="fmravanzado" name="fmravanzado">
                                <div class="row">
                                    <div class="alert mensajealerta hidden"></div>
                                </div>
                                <div class="row contenidos">
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="calve">Destinatarios</label>
                                            <textarea class="form-control" id="destinatarios" name="destinarios" rows="7"></textarea>
                                        </div>
                                        <div class="alert alert-danger hidden" id="mensajedestino"></div>
                                    </div>
                                    <div class="col-lg-6">
                                        <label for="calve">Grupos</label>
                                        <div class="contenido">    
                                            <table class="table table-hover">
                                                <?php
                                                $grupos = $this->session->userdata("grupos");
                                                if (count($grupos) > 0 && $grupos != FALSE) {
                                                    foreach ($grupos as $value) {
                                                        ?>
                                                        <tr>
                                                            <td><input type="checkbox" name="grupos[]" nombre="<?php echo $value["nombre"] ?>" cantidad="<?php echo $value["cantidad"] ?>" class="grupos" value="<?php echo $value["ciclo"] ?>"> 
                                                                <?php echo ucwords($value["nombre"]) ?>(<?php echo $value["cantidad"] ?>)</td>
                                                        </tr>
                                                        <?php
                                                    }
                                                } else {
                                                    ?>
                                                    <tr>
                                                        <td>No tienes Usuarios asociados</td>
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
                                            <textarea class="form-control" id="mensajeavanzado" name="mensaje"></textarea>
                                        </div>
                                        <div class="alert alert-danger hidden" id="mensajealerta"></div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-12">
                                        <p id="pavanzado">0 de 160 caracteres<br>
                                            0 palabras partes
                                        </p>

                                    </div>
                                </div>
                                <div class="espacio10"></div>
                                <div class="row">
                                    <div class="col-lg-3">Fecha envío</div>
                                    <div class="col-lg-6">
                                        <div class="input-group">
                                            <input type="text" class="form-control fechas" id="fechaenvio" name="fechaenvio" placeholder="dd-md-YYYY">
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
                                        <button class="btn btn-primary" id="enviarAvanzado" name="enviarAvanzado" type="button">Enviar</button>
                                    </div>
                                </div>
                            </form>
                        </div>

                    </div>





                </div>
                <div class="col-lg-6">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title">Ayuda</h3>
                        </div>
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-lg-12">
                                    <p class="text-justify">El envío avanzado permite:</p>
                                    <p class="text-justify">- Envío por carga de archivo: a través de archivo XLS, haga click en escoger Archivo. 
                                        Si el archivo contiene el texto del mensaje, seleccione este.</p>

                                    <p class="text-justify">- Envío unitario: para contactos digitados manualmente.</p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-12">- El formato recomendado para la carga:</div>
                            </div>
                            <div class="espacio10"></div>
                            <div class="row">
                                <div class="col-lg-12">
                                    <img src="<?php echo base_url() ?>imagenes/carga.png">
                                </div>
                            </div>
                        </div>
                    </div>


                </div>
            </div>

        </div>
    </div>
</div>


<div class="modal fade modalconfirmacion2">
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
                        <div class="col-lg-2 grupos2 hidden"><label>Grupos</label></div>
                        <div class="col-lg-5 grupos2 hidden">
                            <div class="contenido2">
                                <table id="listagrupos" class="table table-condensed table-hover table-striped table-bordered"></table>
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
                <div class="container-fluid cargando hidden">
                    <div class="row">
                        <div class="col-lg-6 col-center">
                            <img src="<?php echo base_url() ?>imagenes/cargando.gif">
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" id="btnconfirmacion2">Confirmar</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->