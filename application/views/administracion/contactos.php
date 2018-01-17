<script src="<?php echo base_url() ?>public/js/sistema/contactos.js"></script>
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-6 col-md-6 col-sm-6 col-center">
            <div class="alert alert-success alertacontacto hidden"></div>
            <div class="alert alert-danger alertaerror hidden"></div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-10 col-md-10 col-sm-10 col-center">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Creación de contactos</h3>
                </div>
                <div class="panel-body">
                    <div class="container-fluid">
                        <form id="frmcontacto" name="frmcontacto">
                            <div class="hidden">
                                <input type="text" class="form-control contactos" name="id" id="id">
                            </div>

                            <div class="row">
                                <div class="col-lg-6 col-md-6 col-sm-6">
                                    <div class="row">
                                        <div class="col-lg-2 col-md-2 col-sm-2">Nombre</div>
                                        <div class="col-lg-8 col-md-8 col-sm-7"><input type="text" class="form-control contactos" name="nombre" placeholder="Digita el nombre" obligatorio="alfa"></div>
                                    </div>
                                    <div class="espacio10"></div>
                                    <div class="row">
                                        <div class="col-lg-2 col-md-2 col-sm-2">Numero</div>
                                        <div class="col-lg-8 col-md-8 col-sm-8"><input class="form-control contactos" type="text" id="celular" name="celular" placeholder="Digita el numero" obligatorio="numero"></div>
                                    </div>
                                    <div class="espacio10"></div>
                                    <div class="row">
                                        <div class="col-lg-2 col-md-2 col-sm-2">Grupo</div>
                                        <div class="col-lg-8 col-md-8 col-sm-8">
                                            <select name="ciclo" id="ciclo" class="form-control contactos" obligatorio="numero">
                                                <option value='0'>Seleccione</option>    
                                                <?php
                                                foreach ($grupos as $value) {
                                                    ?>
                                                    <option value='<?php echo $value["id"] ?>'><?php echo ucwords($value["nombre"]) ?></option>    
                                                    <?php
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>    
                                <div class="col-lg-6 col-md-6 col-sm-6">
                                    <div class="row">
                                        <div class="row">
                                            <div class="col-lg-2 col-md-2 col-sm-2">Gerencias</div>
                                            <div class="col-lg-8 col-md-8 col-sm-8">
                                                <select class="form-control contactos" name="idgerencia" id="idgerencia" obligatorio="numero">
                                                    <option value="0">Seleccione</option>
                                                    <?php
                                                    foreach ($gerencias as $value) {
                                                        ?>
                                                        <option value="<?php echo $value["codigo"] ?>"><?php echo $value["nombre"] ?></option>
                                                        <?php
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="espacio10"></div>
                                        <div class="row">
                                            <div class="col-lg-2 col-md-2 col-sm-2">Sectores</div>
                                            <div class="col-lg-8 col-md-8 col-sm-8">
                                                <select class="form-control contactos" name="idsector" id="idsector" obligatorio="alfa">
                                                    <option value="0">Seleccione</option>
                                                    <?php
                                                    foreach ($sectores as $value) {
                                                        ?>
                                                        <option value='<?php echo $value["codigo"] ?>'><?php echo ucwords($value["nombre"]) ?></option>    
                                                        <?php
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>    
                                </div>    
                            </div>    

                            <div class="espacio10"></div>
                            <div class="row">
                                <div class="col-lg-8 col-md-8 col-sm-8">
                                    <button type="button" id="registrar" class="btn btn-success">Registrar</button>
                                    <button type="button" id="nuevo" class="btn btn-default">Nuevo</button>
                                    <button type="button" id="borrar" class="btn btn-danger">Borrar</button>
                                </div>

                            </div>
                        </form>
                    </div>

                </div>

            </div>

        </div>
    </div>
    <div class="espacio10"></div>
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-center">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Listado de Contactos</h3>
                </div>
                <div class="panel-body">
                    <table class="table table-bordered table-condensed table-hover" id="tablecontactos">
                        <thead>
                            <tr>
                                <th>Id</th>
                                <th>Nombre</th>
                                <th>Celular</th>
                                <th>Ciclo</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>


                    </table>
                </div>
            </div>
        </div>
    </div>
</div>


