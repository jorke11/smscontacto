
<script src="<?php echo base_url() ?>public/js/sistema/informes.js"></script>
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <div class="row">
                        <div class="col-lg-3">
                            <select id="menu" class="form-control" name="menu">
                                <option value="0">Seleccione reporte</option>
                                <option value="1">Dia</option>
                                <option value="2">Rango Fechas</option>
                                <!--<option value="3">Operador</option>-->
                                <!--<option value="4">Consumo por Usuarios</option>-->
                            </select>
                        </div>
                        <div class="col-lg-2">
                            Consolidado&nbsp;&nbsp;<input type="checkbox" id="consolidado" name="consolidado">
                        </div>
                        <div class="col-lg-3 ">
                            <div class="row col-right">
                                <div class="col-lg-6">
                                    Cantidad de registros
                                </div>
                                <div class="col-lg-4">
                                    <input type="text" name="cantidad" id="cantidad" value="10">
                                </div>
                            </div>

                        </div>


                    </div>
                </div>
                <div class="panel-body">
                    <div class="container-fluid">
                        <div class="row reportefehas">
                            <div class="col-lg-2">Fecha inicial</div>
                            <div class="col-lg-3">

                                <div class="input-group">
                                    <input type="text" class="form-control fechas" id="finicial" name="finicial" placeholder="dd-md-YYYY">
                                    <span class="input-group-btn">
                                        <button class="btn btn-default" type="button">
                                            <span class="glyphicon glyphicon-calendar"></span>
                                        </button>
                                    </span>
                                </div>

                            </div>
                            <div class="col-lg-2">Fecha Final</div>
                            <div class="col-lg-3">
                                <div class="input-group">
                                    <input type="text" class="form-control fechas" id="ffinal" name="ffinal" placeholder="dd-md-YYYY">
                                    <span class="input-group-btn">
                                        <button class="btn btn-default" type="button">
                                            <span class="glyphicon glyphicon-calendar"></span>
                                        </button>
                                    </span>
                                </div>
                            </div>
                            <div class="col-lg-2">
                                <button id="generareporte" type="button" class="btn btn-success" name="generareporte">Generar</button>
                            </div>
                            <div class="col-lg-1">
                                <a href="#" id="exportar">Exportar</a>
                            </div>
                        </div>
                        <div class="espacio15"></div>

                        <div class="row operadores hidden">
                            <div class="col-lg-12">
                                <table class="table table-bordered table-hover" id="tablaoperadores">
                                    <thead>
                                        <tr>
                                            <td>Operador</td>
                                            <td>Fecha</td>
                                            <td>Enviados</td>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                            </div>
                        </div>

                        <div class="row reportemes hidden">
                            <div class="col-lg-12">
                                <table class="table table-bordered table-hover" id="tablames">
                                    <thead>
                                        <tr>
                                            <td class="consolidomes">Numero</td>
                                            <td class="consolidomes">Mensaje</td>
                                            <td>Operador</td>
                                            <td>Usuario</td>
                                            <td>Fecha</td>
                                            <td>Codigo</td>
                                            <td>Cantidad</td>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                            </div>
                        </div>
                        <div class="row reporteconsumo hidden">
                            <div class="col-lg-12">
                                <table class="table table-bordered table-hover" id="tablaconsumo">
                                    <thead>
                                        <tr>
                                            <td>Operador</td>
                                            <td>Fecha</td>
                                            <td>Usuario</td>
                                            <td>Cantidad</td>
                                            <td>Cupo</td>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                            </div>
                        </div>

                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
