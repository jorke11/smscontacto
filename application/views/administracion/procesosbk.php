<script src="<?php echo base_url() ?>public/js/sistema/procesos.js"></script>
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-6">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Ejecución de procesos</h3>
                </div>
                <div class="panel-body">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-lg-2">
                                <button id="procesoftp" class="btn btn-success">FTP</button>
                            </div>    
                            <div class="col-lg-8">
                                <div class="alert alert-success alertftp hidden"></div>
                            </div>    
                            <div class="col-lg-2 cargando hidden">
                                <img src="<?php echo base_url()?>imagenes/loading.gif" width="70%">
                            </div>    
                        </div>    
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>