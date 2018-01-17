<?php
foreach ($menu as $i => $value) {
    if ($i != "permisos") {
        ?>

        <div class="panel panel-default">

            <div class="panel-heading">
                <h4 class="panel-title">
                    <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#<?php echo $i ?>">
                        <?php echo ucwords($i) ?>
                    </a>
                </h4>
            </div>
            <div id="<?php echo $i ?>" class="panel-collapse collapse">
                <div class="panel-body">
                    <ul class="nav nav-pills nav-stacked">
                        <?php
                        foreach ($value as $j => $val) {
                            ?>
                            <li><a href="#" data-toggle="tab" <?php echo $val ?>><?php echo ucwords($j) ?></a></li>
                            <?php
                        }
                        ?>
                    </ul>
                    </li>
                </div>
            </div>
        </div>
        <?php
    } else {
        $this->session->set_userdata(array("permisos" => $value));
    }
}
?>
