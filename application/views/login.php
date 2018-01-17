<html>
    <head>
        <title>Login SMS contacto</title>
        <meta charset="utf-8">
        <script src="<?php echo base_url() ?>librerias/jquery/jquery-1.11.0.min.js"></script>

        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
        <link rel="shortcut icon" href="<?php echo base_url() ?>imagenes/logonatura.png" />

        <link href="<?php echo base_url() ?>librerias/css/bootstrap/less/bootstrap.css" rel="stylesheet">
        <!--<link href="<?php echo base_url() ?>librerias/css/bootstrap/less/bootstrap.less" rel="stylesheet/less" type="text/css">-->
        <!--<script src="<?php // echo base_url()?>librerias/css/bootstrap/js/less.js"></script>-->
        <script src="<?php echo base_url() ?>librerias/css/bootstrap/dist/js/bootstrap.js"></script>
        <script src="<?php echo base_url() ?>public/js/propias/funciones.js"></script>
        <script src="<?php echo base_url() ?>public/js/sistema/login.js"></script>
        <link href="<?php echo base_url() ?>public/css/segmentosmenu.css" rel="stylesheet">
        <style>
            .espacio{
                width:100%;
                height: 1px;
            }
            .espacio10{
                width:100%;
                height: 10px;
            }
            .espacio15{
                width:100%;
                height: 15px;
            }
            .titulo{
                height: 85px;
                background-color: #F5F6F7;
            }
            .titulo2{
                height: 150px;
                background-color: #F5F6F7;
            }
            .fondo{
                background-color: #F5F6F7;
            }
            .rojo{
                background-color: red;
            }
            .verde{
                background-color: green;
            }
            .letra{
                font-size: 30px;
                color: #f7b629;
                font-weight: 500;
                line-height: 26px;
                font-weight: bold;
            }

            .col-center{
                float: none;margin: 0 auto;
            }
            .fondorosa{
                background-color: #F5F6F7;
            }
            .fondoinput{
                -webkit-box-shadow:inset 2px 2px 2px #5c5c5c;
                -moz-box-shadow:inset 2px 2px 2px #5c5c5c;
                box-shadow:inset 2px 2px 2px #5c5c5c;
            }
            .morado{
                background-color:#f7b629;
            }
            h2{
                font-size: 30px;
                color: #a00081;
                font-weight: 500;
                line-height: 26px;
                font-weight: bold
            }
            .gradiente{
                background: #936e93; /* Old browsers */
                background: -moz-linear-gradient(top,  #936e93 1%, #d6d6d6 61%, #ffffff 100%); /* FF3.6+ */
                background: -webkit-gradient(linear, left top, left bottom, color-stop(1%,#936e93), color-stop(61%,#d6d6d6), color-stop(100%,#ffffff)); /* Chrome,Safari4+ */
                background: -webkit-linear-gradient(top,  #936e93 1%,#d6d6d6 61%,#ffffff 100%); /* Chrome10+,Safari5.1+ */
                background: -o-linear-gradient(top,  #936e93 1%,#d6d6d6 61%,#ffffff 100%); /* Opera 11.10+ */
                background: -ms-linear-gradient(top,  #936e93 1%,#d6d6d6 61%,#ffffff 100%); /* IE10+ */
                background: linear-gradient(to bottom,  #936e93 1%,#d6d6d6 61%,#ffffff 100%); /* W3C */
                filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#936e93', endColorstr='#ffffff',GradientType=0 ); /* IE6-9 */
            }
            .gradiente2{
                position: relative;
                z-index: 10;
                background: #f8f8f8;
                background-image: url("<?php echo base_url() ?>imagenes/background.jpg") ;
            }

        </style>
    </head>
    <body>
        <div class="container-fluid">
            <div class="row titulo">
                <div class="col-lg-4 col-md-4  col-xs-9 col-lg-offset-2">
                    <img src="<?php echo base_url() ?>imagenes/logo.png" width="20%">
                </div>
            </div>

            <div class="espacio"></div>

            <div class="row gradiente2">
                <div class="col-lg-8 col-md-8 col-sm-8 col-xs-10 col-center fondorosa">
                    <div class="row ">
                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 fondo letra hidden-xs">
                            <div class="espacio15"></div>
                            <h1 class="text-center">Acceso al sistema</h1>
                        </div>
                        <div class="col-lg-4 visible-xs letra">
                            <div class="espacio15"></div>
                            <h3 class="text-center">Acceso al sistema</h3>
                        </div>
                        <div class="col-lg-8 col-md-8 col-sm-8 col-xs-12 fondo">
                            <div class="espacio10"></div>
                            <form role="form" action="<?php echo base_url() ?>login/valida" METHOD='POST'>

                                <div class="row">
                                    <div class="col-lg-4 col-md-7 col-sm-7 col-sm-12 col-xs-12">
                                        <div class="form-group">
                                            <label for="usuario">Usuario</label>
                                            <input type="text" class="form-control login fondoinput" name="usuario" id="usuario" placeholder="Ingresa tu usuario">
                                        </div>

                                        <div class="form-group">
                                            <label for="calve">Clave</label>
                                            <input type="password" class="form-control login fondoinput" id="clave" name="clave" placeholder="Password">
                                        </div>

                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox"> Recordarme
                                            </label>
                                        </div>
                                        <button type="submit" id="ingresar" class="btn btn-primary morado"><b>Ingresar</b></button>

                                    </div>
                                </div>
                                <div class="espacio10"></div>
                                <?php
                                $error = $this->session->flashdata('error');
                                ?>

                                <div class="row">
                                    <div class="col-lg-4 col-md-7 col-sm-7 col-sm-12 col-xs-12">
                                        <div class="alert alert-danger <?php echo (!empty($error)) ? '' : 'hidden' ?>">
                                            <b><?php echo $error ?></b>
                                        </div>
                                    </div>
                                </div>
                            </form>

                        </div>
                    </div>
                </div>
            </div>

            <div class="row titulo2">
                <hr>
            </div>
        </div>

    </body>
</html>

