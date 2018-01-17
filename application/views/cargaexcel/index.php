<script src="<?php echo base_url() ?>public/js/sistema/cargaexcel.js"></script>
<div class="row">
    <div class="col-lg-12 col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title"><b>Cargar Excel</b></h3>
            </div>
            <div class="panel-body">
                <form id="frmcargaexcel" name="frmcargaexcel" enctype="multipart/form-data">
                    <div class="row">
                        <div class="alert alert-success alertok hidden">Operación Realizada</div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12 col-md-12"><b>Solo se pueden cargar Archivos excel</b>
                            <a href="<?php echo base_url()?>template/templatecontactos.xls">Descargar Template</a>
                            
                        </div>
                    </div>
                    <div class="espacio10"></div>
                    <div class="row">
                        <div class="col-lg-3 col-md-3">
                            Seleccione el archivo
                        </div>
                        <div class="col-lg-3 col-md-3">
                            <input type="file" name="archivo" id="archivo">
                        </div>
                    </div>
                    <div class="espacio10"></div>
                    <div class="row">
                        <div class="col-lg-9 col-md-3">
                            <p id="informacion" class="hidden"></p>
                        </div>
                    </div>
                    <div class="espacio10"></div>
                    <div class="row loading hidden">
                        <div class="col-lg-9 col-md-3">
                            <img src="<?php echo base_url()?>imagenes/loading.gif" width="5%">
                        </div>
                    </div>
                    <div class="espacio10"></div>

                    <div class="row">
                        <div class="col-lg-3 col-md-3">
                            <button type="button" name="subir" id="subir" class="btn btn-primary">Subir</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>