<script src="<?php echo base_url() ?>public/js/sistema/cartera.js"></script>
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-6 col-center">
            <h3 class="text-center">Grupo Cartera</h3>
        </div>
        <div class="espacio"></div>
        <div class="row">
            <form name="frmcartera" id="frmcartera" method="POST">

                <div class="col-lg-6">
                    <div class="row">
                        <div class="alert alertamensaje hidden">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-10">
                            <textarea class="form-control" name="mensaje" id="mensaje"></textarea>
                        </div>

                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <p id="carateres">0 de 160 caracteres<br>
                                0 palabras partes
                            </p>
                        </div>
                    </div>
                    <div class="espacio10"></div>
                    <div class="row">
                        <div class="col-lg-10 col-center">
                            <button class="btn btn-success" id="enviar" type="button">Enviar</button>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <table class="table table-condensed table-bordered" id="tablacartera">
                        <thead>
                            <tr>
                                <th><a href="#" onclick="ordenar(1);
                                        return false;">Dias Mora</a></th>
                                <th><a href="#" onclick="ordenar(2);
                                        return false;">Nombre consultora</a></th>
                                <th><a href="#" onclick="ordenar(3);
                                        return false;">Celular</a></th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </form>
        </div>
    </div>