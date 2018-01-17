<script src="<?php echo base_url() ?>public/js/sistema/poblaciones.js"></script>
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
                                        <img src="<?php echo base_url() ?>imagenes/loading.gif" class="img-responsive">
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
                            <h3 class="panel-title">Crear Filtro nuevo</h3>
                        </div>
                        <div class="panel-body">
                            <div class="row contenidos">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <form id="frmPoblaciones" name="frmPoblaciones">
                                            <label for="calve">Población</label>
                                            <div class="contenido">    
                                                <table class="table table-hover">

                                                    <!--<tr>
                                                        <td><input type="checkbox" id="todas" name="con" value="con">CON POBLACIÓN</td>
                                                    </tr>
                                                    <tr>
                                                        <td><input type="checkbox" id="sin" name="sin" value="sin">SIN POBLACIÓN</td>
                                                    </tr>-->

                                                    <?php
                                                    if (count($poblacion) > 0 && $poblacion != FALSE) {
                                                        foreach ($poblacion as $value) {
                                                            ?>
                                                            <tr>
                                                                <td><input type="checkbox" name="poblaciones[]" class="poblaciones" value="<?php echo $value["poblacion"] ?>"> 
                                                                    <?php echo ucwords($value["poblacion"]) ?></td>
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
                                        </form>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <form id="frmGrupos" name="frmGrupos">
                                        <label for="calve">Grupos</label>
                                        <div class="contenido">    
                                            <table class="table table-hover" id="tablagrupos">
                                                <?php
                                                if (count($grupos) > 0 && $grupos != FALSE) {
                                                    foreach ($grupos as $value) {
                                                        ?>
                                                        <tr>
                                                            <td><input type="checkbox" name="grupos[]" class="grupos" value="<?php echo $value["ciclo"] ?>"> 
                                                                <?php echo ucwords($value["nombre"]) ?></td>
                                                        </tr>
                                                        <?php
                                                    }
                                                } else {
                                                    ?>
                                                    <tr>
                                                        <td>No tienes Grupos asociados</td>
                                                    </tr>
                                                    <?php
                                                }
                                                ?>
                                            </table>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <div class="espacio10"></div>
                            <div class="row">
                                <div class="col-lg-3">
                                    <button class="btn btn-primary" id="filtroPoblacion" name="filtroPoblacion" type="button">Aplicar Filtro</button>
                                </div>
                                <div class="col-lg-9">
                                    <b><span id="totalfiltros"></span></b>
                                </div>
                            </div>

                        </div>

                    </div>

                </div>
                <div class="col-lg-6">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title">Crear mensaje nuevo</h3>
                        </div>
                        <div class="panel-body">
                            <form id="fmrContactos" name="fmrContactos">
                                <div class="row contenidos">
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="calve">Destinatarios</label>
                                            <textarea class="form-control" id="destinatarios" name="destinarios" rows="7"></textarea>
                                        </div>
                                        <div class="alert alert-danger hidden" id="mensajedestino"></div>
                                    </div>
                                    <div class="col-lg-6">
                                        <label for="calve">Contactos</label>
                                        <div class="contenido">    
                                            <table class="table table-hover" id="tablacontactos">
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
                                        <button class="btn btn-primary" id="enviarPoblaciones" name="enviarPoblaciones" type="button">Enviar</button>
                                    </div>
                                </div>
                            </form>
                        </div>

                    </div>
                </div>
            </div>

        </div>
    </div>
</div>