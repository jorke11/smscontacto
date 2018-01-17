
<script src="<?php echo base_url() ?>public/js/sistema/informemes.js"></script>
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Enviados por Mes</h3>
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-lg-2">Seleccione Mes</div>
                        <div class="col-lg-2">
                            <div class="input-group">
                                <input type="text" class="form-control prueba" id="meses" name="meses" placeholder="dd-md-YYYY">
                                <span class="input-group-btn">
                                    <button class="btn btn-default" type="button">
                                        <span class="glyphicon glyphicon-calendar"></span>
                                    </button>
                                </span>
                            </div>
                        </div>
                        <div class="col-lg-2">
                            <button id="buscarmes" class="btn btn-primary" type="button">Buscar</button>
                        </div>
                    </div>
                    <div class="espacio10"></div>
                    <div class="row">
                        <div class="col-lg-12">
                            <table class="table table-bordered table-hover" id="tablames">
                                <thead>
                                    <tr>
                                        <td>Fecha de Envio</td>
                                        <td>Cantidad</td>
                                        <td>Exito</td>
                                    </tr>
                                </thead>
<!--                                <tbody><tbody>-->
                            </table>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>