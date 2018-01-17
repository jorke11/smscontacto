<script src="<?php echo base_url() ?>public/js/sistema/centrocosto.js"></script>
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-6 col-center">
            <div class="alert alert-success alertok hidden"></div>
            <div class="alert alert-danger alerterror hidden"></div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-6 col-center">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Creación de Centro de Costos</h3>
                </div>
                <div class="panel-body">
                    <div class="container-fluid">
                        <form id="frmcentrocosto" name="frmcentrocosto">
                            <div class="hidden">

                                <input type="text" class="form-control centrocosto" name="id" id="id">
                            </div>
                            <div class="row">
                                <div class="col-lg-2">Codigo</div>
                                <div class="col-lg-8">
                                    <input type="text" class="form-control centrocosto" name="codigo" obligatorio='alfa' placeholder="Digita el codigo">
                                </div>
                            </div>
                            <div class="espacio10"></div>
                            <div class="row">
                                <div class="col-lg-2">Nombre</div>
                                <div class="col-lg-8">
                                    <input type="text" class="form-control centrocosto" name="nombre" placeholder="Digita el nombre" obligatorio='alfa'>
                                </div>
                            </div>
                            <div class="espacio10"></div>
                            <div class="row">
                                <div class="col-lg-2">Activo</div>
                                <div class="col-lg-8">
                                    <input type="checkbox" class="form-control centrocosto" name="estado" checked>
                                </div>
                            </div>
                            <div class="espacio10"></div>
                            <div class="row">
                                <div class="col-lg-8">
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
        <div class="col-lg-8 col-lg-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Listado de Centros de costos</h3>
                </div>
                <div class="panel-body">
                    <table class="table table-bordered table-condensed table-hover" id="tablecentrocostos">
                        <thead>
                            <tr>
                                <th>Id</th>
                                <th>Nombre</th>
                                <th>Codigo</th>
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


