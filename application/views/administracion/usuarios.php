<?php
$permisos = (isset($_SESSION["permisos"]["editarcupo"])) ? $_SESSION["permisos"]["editarcupo"] : '';
?>

<script src="<?php echo base_url() ?>public/js/sistema/usuarios.js"></script>
<input type="hidden" id="permiso" value="<?php echo $permisos ?>">
<div class="row">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title">Creacion de Usuarios</h3>
        </div>
        <div class="panel-body">
            <form name="formusuario" id="formusuario">
                <div class="hidden">
                    <input type="hidden" name="id" id="id" class="inputusuario">
                </div>
                <div class="row">
                    <div class="col-lg-1 col-md-1 col-sm-1">Usuario</div>
                    <div class="col-lg-2 col-md-2 col-sm-2">
                        <input type="text" name="usuario" id="usuario" 
                               class="form-control inputusuario 
                               <?php echo ($permisos != 'editar') ? 'permisos' : ''; ?>" placeholder="Usuario" obligatorio='alfa'>
                    </div>
                    <div class="col-lg-1 col-md-1 col-sm-1">Clave</div>
                    <div class="col-lg-2 col-md-2 col-sm-2">
                        <input type="password" name="clave" id="clave" class="form-control inputusuario 
                               <?php echo ($permisos != 'editar') ? 'permisos' : ''; ?>" placeholder="********" obligatorio='alfa'>
                    </div>
                    <div class="col-lg-1 col-md-1 col-sm-1">Confirmación</div>
                    <div class="col-lg-2 col-md-2 col-sm-2">
                        <input type="password" name="confirmacion" id="confirmacion" class="form-control inputusuario 
                               <?php echo ($permisos != 'editar') ? 'permisos' : ''; ?>" placeholder="********" obligatorio='alfa'>
                    </div>

                    <div class="col-lg-2 col-md-2 col-sm-2"><input type="checkbox"  id="ver" obligatorio='alfa' disabled> Ver Clave </div>

                </div>
                <div class="espacio10 gerencias"></div>
                <div class="row gerencias">
                    <div class="col-lg-1 col-md-1 col-sm-1">Tipo usuario</div>
                    <div class="col-lg-2 col-md-2 col-sm-2">
                        <select name="idperfil" id="idperfil" class="form-control inputusuario <?php echo ($permisos != 'editar') ? 'permisos' : ''; ?>"
                                obligatorio='numero'>
                            <option value="0">Seleccione</option>
                            <?php
                            foreach ($perfiles as $value) {
                                ?>
                                <option value="<?php echo $value["id"] ?>"><?php echo ucwords($value["perfil"]) ?></option>
                                <?php
                            }
                            ?>
                        </select>
                    </div>
                    <div class="col-lg-1 col-md-1 col-sm-1">Gerencia</div>
                    <div class="col-lg-2 col-md-2 col-sm-2">
                        <select name="idjerarquia" class="form-control inputusuario <?php echo ($permisos != 'editar') ? 'permisos' : ''; ?>" id="idjerarquia"
                                obligatorio='numero'>
                            <option value="0">Seleccione</option>
                            <?php
                            foreach ($gerencias as $value) {
                                ?>
                                <option value="<?php echo $value["codigo"] ?>"><?php echo $value["nombre"] ?></option>
                                <?php
                            }
                            ?>
                        </select>
                    </div>
                    <div class="col-lg-1 col-md-1 col-sm-1">Sector</div>
                    <div class="col-lg-2 col-md-2 col-sm-2">
                        <select name="idsector" class="form-control inputusuario <?php echo ($permisos != 'editar') ? 'permisos' : ''; ?>" id="idsector">
                            <option value="0">Seleccione</option>
                        </select>
                    </div>

                </div>
                <div class="espacio10"></div>
                <div class="row">
                    <div class="col-lg-1 col-md-1 col-sm-1">Cupo Disponible</div>
                    <div class="col-lg-2 col-md-2 col-sm-2">
                        <input type="text" name="cupodisponible" id="cupodisponible" class="form-control inputusuario <?php echo ($permisos != 'editar') ? 'permisos' : ''; ?>" value="0" readonly>
                    </div>
                    <div class="col-lg-1 col-md-1 col-sm-1">Cupo Inicial</div>
                    <div class="col-lg-2 col-md-2 col-sm-2">
                        <input type="text" name="cupo" id="cupo" class="form-control inputusuario <?php echo ($permisos != 'editar') ? 'permisos' : ''; ?>" value="0">
                    </div>
                    <div class="col-lg-1 col-md-1 col-sm-1">Cupo Actual</div>
                    <div class="col-lg-2 col-md-2 col-sm-2">
                        <input type="text" name="cupoactual" id="cupoactual" class="form-control inputusuario <?php echo ($permisos != 'editar') ? 'permisos' : ''; ?>" value="0" disabled bloqueado='ok'>
                    </div>

                </div>

                <div class="espacio10"></div>
                <div class="row">

                    <div class="col-lg-1 col-md-1 col-sm-1">Agregar o Quitar cupo</div>
                    <div class="col-lg-2 col-md-2 col-sm-2">
                        <div class="row">
                            <div class="col-lg-4 col-md-4 col-sm-4">
                                <select name="simbolo" id="simbolo" class="inputusuario">
                                    <option value="1">+</option>
                                    <option value="2">-</option>
                                </select>
                            </div>
                            <div class="col-lg-8 col-md-8 col-sm-8">
                                <input type="text" name="adicion" id="adicion" class="form-control inputusuario" value="0">
                            </div>
                        </div>


                    </div>
                    <div class="col-lg-1 col-md-1 col-sm-1">Centro Costo</div>
                    <div class="col-lg-2 col-md-2 col-sm-2">
                        <select name="idcentrocosto" class="form-control inputusuario <?php echo ($permisos != 'editar') ? 'permisos' : ''; ?>" id="idcentrocosto">

                            <option value="0">Seleccione</option>
                            <?php
                            foreach ($sucursales as $value) {
                                ?>
                                <option value="<?php echo $value["id"] ?>"><?php echo ucwords($value["codigo"]) ?></option>
                                <?php
                            }
                            ?>
                        </select>
                    </div>
                    <div class="col-lg-1 col-md-1 col-sm-1">Activado</div>
                    <div class="col-lg-2 col-md-2 col-sm-2">
                        <input type="checkbox" name="estado" id="estado" class="form-control inputusuario <?php echo ($permisos != 'editar') ? 'permisos' : ''; ?>" checked estado="activo">
                    </div>
                </div>

                <div class="espacio10"></div>
                <div class="row">
                    <div class="col-lg-1 col-md-1 col-sm-1">
                        <button id="registrar" name="registrar" type="button" class="btn btn-primary">Registrar</button>
                    </div>
                    <div class="col-lg-1 col-md-1 col-sm-1">
                        <button id="nuevo" name="nuevo" type="button" class="btn btn-success" >Nuevo</button>
                    </div>
                    <div class="col-lg-1 col-md-1 col-sm-1">
                        <button id="borrar" name="borrar" type="button" class="btn btn-danger hidden <?php echo ($permisos != 'editar') ? 'permisos' : ''; ?>" <?php echo ($permisos != 'editar') ? 'permisos' : ''; ?> <?php echo ($permisos != 'editar') ? 'style="display:hidden"' : ''; ?>>Borrar</button>
                    </div>

                </div>

            </form>
        </div>

    </div>
</div>
<div class="espacio"></div>
<div class="row">
    <div class="panel panel-default">
        <div class="panel-heading">
            <div class="row">
                <div class="col-lg-3 col-md-3 col-sm-3"><h3 class="panel-title">Listado Usuarios</h3></div>
                <div class="col-lg-8 col-md-8 col-sm-8">
                    <p id="informacioncupo"></p>
                </div>
            </div>

        </div>
        <div class="panel-body">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12">
                    <div class="alert mensajealerta hidden"></div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12">
                    <table class="table table-bordered table-condensed table-hover" id="tableusuarios">
                        <thead>
                            <tr>
                                <th>Id</th>
                                <th>Usuario</th>
                                <th>Perfil</th>
                                <th>Gerencia</th>
                                <th>Sector</th>
                                <th>Centro costo</th>
                                <th>Cupo Ger</th>
                                <th>Cupo Disp</th>
                                <th>Cupo Ini</th>
                                <th>Adicion</th>
                                <th>Enviados</th>
                                <th>Saldo</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-2 col-md-2 col-sm-2">
                    <button id="importar">Importar</button>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-4">
                    <p id="informacionsubida"></p>
                </div>
            </div>


        </div>
    </div>
</div>

<div class="modal fade subearchivo">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title">Pantilla para Usuarios</h4>
            </div>
            <div class="modal-body">
                <div class="container-fluid">
                    <form name="form" id="formsubir" name="formsubir" enctype="multipart/form-data">
                        <div class="row ocultaarchivo">
                            <div class="col-lg-4"><input type="file" name="fileusuarios" id="fileusuarios"></div>
                        </div>
                        <div class="row ocultaarchivo">
                            <div class="col-lg-12"><p id="informacion"></p></div>
                        </div>
                        <div class="row carga hidden">
                            <div class="col-lg-4 col-center">
                                <img src="<?php echo base_url() ?>imagenes/loading.gif">
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                    <button type="button" class="btn btn-primary" id="btnsubir">Subir</button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
</div><!-- /.modal -->

<div class="modal fade modalplanes">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title">Creacion de planes</h4>
            </div>
            <div class="modal-body">
                <div class="container-fluid">
                    <form name="formplanes" id="formplanes">
                        <div class="row">
                            <div class="col-lg-3">Nombre Plan</div>
                            <div class="col-lg-4">
                                <input type="text" name="nombre" id="nombre" class="form-control" placeholder="Nombre plan">
                            </div>
                        </div>
                        <div class="espacio10"></div>
                        <div class="row">
                            <div class="col-lg-3">Cantidad</div>
                            <div class="col-lg-4">
                                <input type="text" name="cantidad" id="cantidad" class="form-control" placeholder="Cantidad">
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                    <button type="button" class="btn btn-primary" id="btncrearplan">Crear</button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
</div><!-- /.modal -->