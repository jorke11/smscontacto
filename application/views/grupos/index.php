<script src="<?php echo base_url() ?>public/js/sistema/grupos.js"></script>

<style>
    .div1{
        width: 40%;
        border:1px solid #ccc;
        max-height: 100px;
        float:left;
        overflow: auto;
    }
    .divcentro{
        padding-left: 3px;
        padding-right: 2px;
        width: 15%;
        height: 50px;
        float:left;
    }
</style>
<div class="row">
    <div class="col-lg-8 col-lg-offset-2">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">Creacion de Grupos</h3>
            </div>
            <div class="panel-body">
                <form name="formgrupos" id="formgrupos">
                    <div class="hidden">
                        <input type="hidden" name="id" id="id" class="frmgrupos">
                    </div>
                    <div class="row">
                        <div class="col-lg-2">Nombre</div>
                        <div class="col-lg-4">
                            <input type="text" name="nombre" id="nombre" class="form-control frmgrupos" placeholder="Nombre Grupo">
                        </div>

                    </div>
                    <div class="espacio10"></div>
                    <div class="row">
                        <div class="col-lg-2">Descripción</div>
                        <div class="col-lg-6">
                            <textarea class="form-control frmgrupos" name="descripcion"></textarea>
                        </div>
                    </div>
                    <div class="espacio10"></div>
                    <div class="row">
                        <div class="col-lg-2">Estado</div>
                        <div class="col-lg-6">
                            <input type="checkbox" id="estado" name="estado" class="frmgrupos">
                        </div>
                    </div>
                    <div class="espacio10"></div>
                    <div class="espacio10"></div>
                    <div class="row">
                        <div class="col-lg-5">Seleccionar contactos para el grupo:</div>

                    </div>
                    <div class="espacio10"></div>
                    <div class="row ">
                        <div class="col-lg-12 col-center">
                            <div class="div1">
                                <select class="form-control" id="listacontactos" multiple>
                                    <?php
                                    $contactos = $this->session->userdata("contactos");
                                    foreach ($contactos as $value) {
                                        ?>
                                        <option value="<?php echo $value["celular"].",".$value["nombre"] ?>"><?php echo $value["nombre"] ?></option>
                                        <?php
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="divcentro">
                                <button type="button" class="btn btn-default btn-sm" id="delete">
                                    <span class="glyphicon  glyphicon glyphicon-chevron-left"></span>
                                </button>
                                <button type="button" class="btn btn-default btn-sm" id="add">
                                    <span class="glyphicon  glyphicon glyphicon-chevron-right"></span>
                                </button>
                            </div>
                            <div class="div1">
                                <select class="form-control" id="gruponuevo" name="gruponuevo[]" multiple>

                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="espacio10"></div>
                    <div class="row">
                        <div class="col-lg-1">
                            <button id="registrar" name="registrar" type="button" class="btn btn-primary">Guardar</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

    </div>
</div>
<div class="espacio"></div>
<div class="row">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title">Listado De Grupos</h3>
        </div>
        <div class="panel-body">

            <div class="alert alert-success alertok hidden"></div>

            <table class="table table-bordered table-condensed table-hover" id="tablegrupos">
                <thead>
                    <tr>
                        <th>Id</th>
                        <th>Creado en</th>
                        <th>Nombre</th>
                        <th>Estado</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>


            </table>
        </div>
    </div>
</div>