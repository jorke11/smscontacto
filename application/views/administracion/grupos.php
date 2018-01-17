<script src="<?php echo base_url() ?>public/js/sistema/usuarios.js"></script>
<div class="row">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title">Creacion de Grupos</h3>
        </div>
        <div class="panel-body">
            <form name="formusuario" id="formusuario">
                <div class="hidden">
                    <input type="hidden" name="id" id="id" class="frmusuario">
                </div>
                <div class="row">
                    <div class="col-lg-2">Nombre</div>
                    <div class="col-lg-4">
                        <input type="text" name="usuario" id="usuario" class="form-control frmusuario" placeholder="Usuario">
                    </div>

                </div>
                <div class="espacio10"></div>
                <div class="row">
                    <div class="col-lg-2">Descripción</div>
                    <div class="col-lg-6">
                        <textarea class="form-control" name="descripcion"></textarea>
                    </div>
                </div>
                <div class="espacio10"></div>
                <div class="row">
                    <div class="col-lg-3">Seleccionar contactos para el grupo:</div>

                </div>
                <div class="espacio10"></div>
                <div class="row">
                    <div class="col-lg-10 col-lg-offset-1">
                        <div class="row">
                            <div class="col-lg-4">
                                <select class="form-control" multiple></select>
                            </div>
                            <div class="col-lg-1">
                                <button>Add</button><button>quit</button>
                            </div>
                            <div class="col-lg-4">
                                <select class="form-control" multiple></select>
                            </div>
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