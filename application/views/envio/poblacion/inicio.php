<ul class="nav nav-tabs" role="tablist" id="myTab">
    <li role="presentation" class="principal active"><a href="#enviopoblacion"role="tab" data-toggle="tab">Envio Poblacion</a></li>
    <li role="presentation" class="limpiaTab hidden"><a href="#confirmacion" aria-controls="profile" role="tab" data-toggle="tab">Confirmación</a></li>
</ul>

<div class="tab-content">
    <div role="tabpanel" class="tab-pane active" id="enviopoblacion">
        <div class="container-fluid">
            <?php
            $this->load->view("envio/poblacion/poblaciones");
            ?>
        </div>
    </div>
    <div role="tabpanel" class="tab-pane" id="confirmacion">
        <div class="container-fluid">
            <?php
            $this->load->view("envio/poblacion/confirmacion");
            ?>
        </div>
    </div>
</div>