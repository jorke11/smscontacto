
<html>
    <head>
        <title>SMS contacto</title>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">

        <script src="<?php echo base_url() ?>librerias/jquery/jquery-1.11.0.min.js"></script>
        <script src="<?php echo base_url() ?>public/js/propias/plugins.js"></script>
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
        <link rel="shortcut icon" href="<?php echo base_url() ?>imagenes/logonatura.png" />
        <link href="<?php echo base_url() ?>librerias/css/bootstrap/less/bootstrap.css" rel="stylesheet">
        <!--<link href="<?php echo base_url() ?>librerias/css/bootstrap/less/bootstrap.less" rel="stylesheet/less" type="text/css">-->
        <!--<script src="<?php // echo base_url()                                              ?>librerias/css/bootstrap/js/less.js"></script>-->
        <script src="<?php echo base_url() ?>librerias/css/bootstrap/dist/js/bootstrap.js"></script>

<!--<script src="<?php echo base_url() ?>librerias/datatable/js/jquery.daTatables.js"></script>-->
        <script src="//cdn.datatables.net/1.10.0/js/jquery.dataTables.js"></script>
        <!--<link href="<?php echo base_url() ?>librerias/datatable/css/jquery.daTatables.css" rel="stylesheet">-->
        <link href="//cdn.datatables.net/1.10.0/css/jquery.dataTables.css" rel="stylesheet">

        <script src="<?php echo base_url() ?>public/css/datetimepicker/jquery.datetimepicker.js"></script>
        <link href="<?php echo base_url() ?>public/css/datetimepicker/jquery.datetimepicker.css" rel="stylesheet">

        <script src="<?php echo base_url() ?>public/js/propias/funciones.js"></script>
        <script src="<?php echo base_url() ?>public/js/validCampo.js"></script>


        <script src="<?php echo base_url() ?>public/js/sistema/login.js"></script>
        <script src="<?php echo base_url() ?>public/js/sistema/inicio.js"></script>
        <script type="text/javascript" src="<?php echo base_url() ?>librerias/datatable/extensions/TableTools/js/dataTables.tableTools.js"></script>
        <link href="<?php echo base_url() ?>public/css/segmentosmenu.css" rel="stylesheet">
        <style>
            .contenidocheck{
                max-height: 200px;
                overflow: auto;
                widows: 100%;
            }
        </style>
    </head>

    <body onLoad="simple_reloj();" onUnload="stop();">

        <div class="container-fluid contenedorfull">
            <div class="hidden">
                <input type="text" id="ruta" value="<?php echo base_url() ?>">
            </div>
            <div class="row fondo2 hidden-xs">
                <div class="col-lg-8 col-md-3 col-sm-7"><p><span id="reloj"></span></div>
                <div class="col-lg-4 col-md-4 col-sm-5">
                    Hola <?php echo ucwords($this->session->userdata("usuario")) ?>,
                    <span id="cupo" class="cupo2">Cupo: <?php // echo $this->session->userdata("cupo")          ?> SMS 
                        &nbsp;<a href="inicio/cerrarSession"><b>Salir</b></a></span>
                </div>
            </div>
            <div class="row fondo3 hidden-xs ">
                <div class="col-lg-2 col-right">
                    <img src="<?php echo base_url() ?>imagenes/logo.png" width="35%">
                </div>
            </div>
            <div class="row hidden-lg hidden-md hidden-sm fondo4">
                <div class="col-xs-2">
                    <img src="<?php echo base_url() ?>imagenes/logonatura.png" class="img-responsive">
                </div>
                <div class="col-xs-3">
                    <b><?php echo ucwords($this->session->userdata("usuario")) ?></b>
                </div>
                <div class="col-xs-7 col-right cupo2">

                </div>
            </div>

            <?php $this->load->view("movil/inicio"); ?>

            <div class="row hidden-xs">

                <div class="col-lg-3 col-md-3 col-sm-3" >

                    <div class="panel-group" id="accordion">
                        <?php
                        $this->load->view("menu");
                        ?>
                    </div>
                </div>

                <div class="col-lg-9 col-md-9 col-sm-9">
                    <div id="container-tabs">
                        <div class="row">
                            <div class="col-lg-9 col-center">
                                <!--<img src="<?php echo base_url() . 'imagenes/natura_inicio.jpg' ?>">-->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>







        <div class="modal fade" id="modalcontactos">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h4 class="modal-title">Seleccione los contactos</h4>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 ">
                                <div class="contenidocheck">
                                    <table class="table table-hover">
                                        <?php
                                        $contactos = $this->session->userdata("contactos");
                                        if (count($contactos) > 0) {
                                            foreach ($contactos as $value) {
                                                ?>
                                                <tr>
                                                    <td>
                                                        <input type="checkbox" name="destino[]" class="valorcontacto enviomovil" nombre="<?php echo trim($value["nombre"]); ?>" value="<?php echo $value["celular"] ?>">&nbsp;<?php echo trim($value["nombre"]); ?>
                                                    </td>
                                                </tr>
                                                <?php
                                            }
                                        } else {
                                            ?>
                                            <tr>
                                                <td>No tienes usuarios asociados</td>
                                            </tr>    
                                            <?php
                                        }
                                        ?>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                        <button type="button" class="btn btn-primary" id="sicontactos">Seleccinar</button>
                    </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->

        <div class="modal fade" id="modalgrupos">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h4 class="modal-title">Seleccione los Grupos</h4>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-12">
                                <table class="table table-hover">
                                    <?php
                                    $grupos = $this->session->userdata("grupos");
                                    if (count($grupos) > 0) {
                                        foreach ($grupos as $value) {
                                            ?>
                                            <tr>
                                                <td>
                                                    <input type="checkbox" name="grupos[]" class="valorgrupos enviomovil" cantidad="<?php echo $value["cantidad"] ?>"
                                                           nombre="<?php echo $value["nombre"] ?>" value="<?php echo $value["ciclo"] ?>"> 
                                                    <?php echo ucwords($value["nombre"]) ?>(<?php echo $value["cantidad"] ?>)
                                                </td>
                                            </tr>
                                            <?php
                                        }
                                    } else {
                                        ?>
                                        <tr>
                                            <td>No tienes usuarios asociados</td>
                                        </tr>    
                                        <?php
                                    }
                                    ?>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal" id="cerrar">Cerrar</button>
                        <button type="button" class="btn btn-primary" id="sigrupos">Seleccionar</button>
                    </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->




        <div class="modal fade modalconfirmacion3">
            <div class="modal-dialog">
                <div class="modal-content ">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                        <h4 class="modal-title">Confirmación de Envio</h4>
                    </div>
                    <div class="modal-body">
                        <div class="container-fluid">
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
                                <div class="col-lg-6"><p id="codigo"></p></div>
                            </div>

                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" id="cancelar" data-dismiss="modal">Cancelar</button>
                        <button type="button" class="btn btn-primary" id="btnconfirmacion3">Confirmar</button>
                    </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->


        <div class="modal fade modalcarga">
            <div class="modal-dialog modal-sm">
                <div class="modal-content ">

                    <div class="modal-body col-center">
                        <img src="<?php echo base_url() ?>imagenes/loading.GIF" style="padding-left: 35%">
                        <p class="text-center"><b>Espere por favor..</b></p>
                    </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->


    </body>
</html>

