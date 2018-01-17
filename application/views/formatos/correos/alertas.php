<html>
    <body>
        <p><strong>RESUMEN DE CARGA: <?php echo date("Y-m-d") ?></strong></p>
        <table border="1">
            <thead>
                <tr>
                    <td><b>No</b></td>
                    <td><b>Nombre</b></td>
                    <td><b>Fecha</b></td>
                    <td><b>Registros Cargados</b></td>
                    <td><b>Registro Procesados</b></td>
                    <td><b>Descripcion</b></td>
                </tr>
            </thead>
            <tbody>
                <?php
                if (count($archivos) > 0) {
                    foreach ($archivos as $i => $value) {
                        ?>
                        <tr>
                            <td><?php echo ($i + 1) ?></td>
                            <td><?php echo $value["nombre"] ?></td>
                            <td><?php echo $value["fecha"] ?></td>
                            <td><?php echo (isset($value["registros"])) ? $value["registros"] : ''; ?></td>
                            <td><?php echo (isset($value["procesado"])) ? $value["procesado"] : ''; ?></td>
                            <td><?php echo (isset($value["descripcion"])) ? $value["descripcion"] : ''; ?></td>
                        </tr>
                        <?php
                    }
                } else {
                    ?>
                    <tr>
                        <td colspan="5"><b><?php echo (isset($mensajeerror)) ? $mensajeerror : ''; ?></b></td>
                    </tr>
                    <?php
                }
                ?>
            </tbody>
        </table>
    </body>
</html>