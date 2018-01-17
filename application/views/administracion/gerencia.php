<script src="<?php echo base_url() ?>public/js/sistema/gerencias.js"></script>
<div class="row">
    <div class="col-lg-8 col-lg-offset-2">
        <div class="alert alert-success alertok hidden"></div>
    </div>
</div>
<div class="row">
    <div class="col-lg-10 col-lg-offset-1">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">Creacion de Jerarquias</h3>
            </div>
            <div class="panel-body">
                <form name="formgerencia" id="formgerencia">
                    <div class="hidden">
                        <input type="hidden" name="id" id="id" class="frmgerencia">
                    </div>
                    <div class="row">
                        <div class="col-lg-1 col-md-1 col-sm-1">Nombre</div>
                        <div class="col-lg-3 col-md-3 col-sm-3">
                            <input type="text" name="nombre" id="nombre" class="form-control frmgerencia" placeholder="Nombre" obligatorio="alfa">
                        </div>
                        <div class="col-lg-1 col-md-1 col-sm-1">Codigo</div>
                        <div class="col-lg-2 col-md-2 col-sm-2">
                            <input type="text" name="codigo" id="codigo" class="form-control frmgerencia" placeholder="Codigo" obligatorio="alfa">
                        </div>
                        <div class="col-lg-1 col-md-1 col-sm-1">Perfil</div>
                        <div class="col-lg-3 col-md-3 col-sm-3">
                            <select id="tipo" name="tipo" class="form-control frmgerencia" obligatorio="numero">
                                <option value="0">Seleccione</option>
                                <?php
                                foreach ($perfiles as $value) {
                                    ?>
                                    <option value="<?php echo $value["id"] ?>"><?php echo ucwords($value["perfil"]) ?></option>    
                                    <?php
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="espacio10"></div>
                    <div class="row">

                        <div class="col-lg-1 col-md-1 col-sm-1">Gerencias</div>
                        <div class="col-lg-3 col-md-3 col-sm-3">
                            <select id="idpadre" name="idpadre" class="form-control frmgerencia" disabled>
                                <option value="0">Seleccione</option>
                                <?php
                                foreach ($gerencias as $value) {
                                    ?>
                                    <option value="<?php echo $value["codigo"] ?>"><?php echo ucwords($value["nombre"]) ?></option>    
                                    <?php
                                }
                                ?>
                            </select>
                        </div>
                        <div class="col-lg-1 col-md-1 col-sm-1">Cupo</div>
                        <div class="col-lg-2 col-md-2 col-sm-2">
                            <input type="text" name="cupo" id="cupo" class="form-control frmgerencia" value="0" estado="disabled" disabled>
                        </div>
                        <div class="col-lg-1 col-md-1 col-sm-1">Activo</div>
                        <div class="col-lg-2 col-md-2 col-sm-2">
                            <input type="checkbox" name="estado" id="estado" class="frmgerencia" estado="activo" checked>
                        </div>

                    </div>

                    <div class="espacio10"></div>
                    <div class="espacio10"></div>
                    <div class="row">
                        <div class="col-lg-2 col-md-2 col-sm-2">
                            <button id="creaGerencia" name="creaGerencia" type="button" class="btn btn-primary">Registrar</button>
                        </div>
                        <div class="col-lg-2 col-md-2 col-sm-2">
                            <button id="nuevo" name="nuevo" type="button" class="btn btn-success">Nuevo</button>
                        </div>
                        <div class="col-lg-2 col-md-2 col-sm-2">
                            <button id="borrar" name="borrar" type="button" class="btn btn-danger">Borrar</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<div class="espacio10"></div>
<div class="row">
    <div class="col-lg-8 col-lg-offset-2">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">Listado</h3>
            </div>
            <div class="panel-body">
                <table class="table table-bordered table-condensed table-hover" id="tablejerarquias">
                    <thead>
                        <tr>
                            <th>Id</th>
                            <th>Gerencia</th>
                            <th>Codigo</th>
                            <th>Tipo</th>
                            <th>Cupo</th>
                            <th>Estado</th>
                            
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>


                </table>
            </div>
        </div>
    </div>
</div>

