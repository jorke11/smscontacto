<script src="<?php echo base_url() ?>public/js/sistema/blacklist.js"></script>
<div class="row">
    <div class="col-lg-5 col-lg-offset-3 col-center">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">Creacion BlackList</h3>
            </div>
            <div class="panel-body">
                <form name="formblacklist" id="formblacklist">
                    <div class="hidden">
                        <input type="hidden" name="id" id="id" class="frmblacklist">
                    </div>
                    <div class="row">
                        <div class="col-lg-2">Numero</div>
                        <div class="col-lg-4">
                            <input type="text" name="numero" id="numero" class="form-control frmblacklist" placeholder="Numero">
                        </div>
                        <div class="col-lg-2">Activar</div>
                        <div class="col-lg-4">
                            <input type="checkbox" name="estado" id="estado" class="frmblacklist">
                        </div>
                    </div>
                    <div class="espacio10"></div>
                    <div class="row">
                        <div class="col-lg-2">Motivo</div>
                        <div class="col-lg-10">
                            <textarea name="motivo" id="motivo" class="form-control frmblacklist"></textarea>
                        </div>
                    </div>

                    <div class="espacio10"></div>
                    <div class="row">
                        <div class="col-lg-3">
                            <button id="creaBlacklist" name="creaGerencia" type="button" class="btn btn-primary">Registrar</button>
                        </div>
                        <div class="col-lg-3">
                            <button id="nuevo" name="nuevo" type="button" class="btn btn-success">Nuevo</button>
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
                <h3 class="panel-title">Listado de Numeros Bloqueados</h3>
            </div>
            <div class="panel-body">
                <table class="table table-bordered table-condensed table-hover" id="tableblacklist">
                    <thead>
                        <tr>
                            <th>Id</th>
                            <th>Nnumero</th>
                            <th>Motivio</th>
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

