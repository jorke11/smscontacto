<script src="<?php base_url() ?>public/js/sistema/programados.js"></script>
<div class="container-fluid">
    <form id="frmcancelados">
        <div class="row">
            <div class="col-lg-1"># Base</div>
            <div class="col-lg-2">
                <input type="text" id="idbase" name="idbase" class="form-control inputProgramados" placeholder="# Base">
            </div>
            <div class="col-lg-3 col-md-3 col-sm-3">
                Envios programados Hasta
            </div>
            <div class="col-lg-4 col-md-4 col-sm-4">
                <div class="input-group">
                    <input type="text" class="form-control fechas inputProgramados" id="inicio" name="inicio" placeholder="dd-md-YYYY">
                    <span class="input-group-btn">
                        <button class="btn btn-default" type="button">
                            <span class="glyphicon glyphicon-calendar"></span>
                        </button>
                    </span>
                </div>
            </div>
            <!--        <div class="col-lg-2 col-md-2 col-sm-2">
                        Fecha Final
                    </div>
                    <div class="col-lg-2 col-md-3 col-sm-3">
                        <div class="input-group">
                            <input type="text" class="form-control fechas inputProgramados" id="final" name="final" placeholder="dd-md-YYYY">
            
                            <span class="input-group-btn">
                                <button class="btn btn-default" type="button">
                                    <span class="glyphicon glyphicon-calendar"></span>
                                </button>
                            </span>
            
            
                        </div> /input-group 
                    </div>-->
            <div class="col-lg-1 col-md-2 col-sm-2">
                <button id="btnreporte" type="button" class="btn btn-primary" type="buton">Buscar</button>
            </div>
        </div>

    </form>
    <div class="espacio10"></div>
    <div class="row">
        <div class="col-lg-11 col-center">

            <div class="panel panel-default">
                <div class="panel-heading">
                    <div class="row">
                        <div class="col-lg-6"><h3 class="panel-title">Contenido Programados</h3></div>
                        <div class="col-lg-3 col-right"><button id="btncancelar" class="btn btn-danger" type="buton">Cancelar Envio</button></div>
                    </div>

                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="alert alertamensaje hidden"></div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <table class="table table-bordered table-condensed" id="tblprogramados">
                                <thead>
                                    <tr align="center">
                                        <!--<td>ID</td>-->
                                        <td># Base</td>
                                        <th>Numero</th>
                                        <th>Mensaje</th>
                                        <th>Nota</th>
                                        <th>Fecha envio</th>
                                    </tr>
                                </thead>
                                <tbody align="center"></tbody>
                            </table>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalconfirmacion">
    <form id="frmconfirmacion">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Datos para Cancelar</h4>
                </div>
                <div class="modal-body">
                    <div class="container-fluid">

                        <div class="row">
                            <div class="col-lg-12 col-md-12">
                                <table class="table table-condensed table-bordered" id="tablaresumen">
                                    <thead>
                                        <tr>
                                            <th></th>
                                            <th># Base</th>
                                            <th>Fecha Cargue</th>
                                            <th>Registros</th>
                                            <th>Errores</th>
                                        </tr>
                                    </thead>
                                    <tbody align="center">

                                    </tbody>
                                </table>
                            </div>
                        </div>

                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                    <button type="button" class="btn btn-primary" id="confirmacion">Confirmar</button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </form>
</div><!-- /.modal -->
