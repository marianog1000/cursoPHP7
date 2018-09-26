<?php

// activamos el almacenamiento en el buffer
ob_start();
session_start();

if (!isset($_SESSION["nombre"])){
  header("Location: login.html");  
}else{

require 'header.php';

if ($_SESSION['ventas']==1){


?>

<!--Contenido-->
      <!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper">
        
        <!-- Main content -->
        <section class="content">
            <div class="row">
              <div class="col-md-12">
                  <div class="box">
                    <div class="box-header with-border">
                          <h1 class="box-title">Cliente<button class="btn btn-success" onclick="mostrarForm(true)"><i class="fa fa-plus-circle"></i> Agregar</button></h1>
                        <div class="box-tools pull-right">
                        </div>
                    </div>
                    <!-- /.box-header -->
                    <!-- centro -->
                    <div class="panel-body table-responsive"  id="listadoregistros">
                        <table id="tbllistado" class="table table-striped table-bordered table-condensed table-hover">
                          <thead>
                            <th>Opciones</th>
                            <th>Nombre</th>
                            <th>Tipo Documento</th>
                            <th>Nro. Documento</th>
                            <th>Teléfono</th>
                            <th>Email</th>
                          </thead>
                          <tbody>
                            
                          </tbody>
                          <tfoot>
                            <th>Opciones</th>
                            <th>Nombre</th>
                            <th>Tipo Documento</th>
                            <th>Nro. Documento</th>
                            <th>Teléfono</th>
                            <th>Email</th>
                          </tfoot>
                        </table>
                    </div>
                    <div class="panel-body " style="height: 400px;" id="formularioregistros">
                      <form name="formulario" id="formulario" method="POST">
                        <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                          <label>Nombre:</label>
                          <input type="hidden" name="idPersona" id="idPersona">
                          <input type="hidden" name="tipo_persona" id="tipo_persona" value="Cliente" >
                          <input type="text" class="form-control" name="nombre" id="nombre" maxlength="100" placeholder="Nombre del Cliente" required>
                        </div>
                        <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                          <label>Tipo Documento:</label>
                          <select class="form-control select-picker" name="tipo_documento" id="tipo_documento" required>
                            <option value="DNI">DNI</option>
                            <option value="CUIT">CUIT</option>
                          </select>
                        </div>

                        <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                          <label>Número Documento:</label>
                          <input type="text" class="form-control" name="num_documento" id="num_documento" maxlength="20" placeholder="Documento">
                        </div>
                          
                        <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                          <label>Dirección:</label>
                          <input type="text" class="form-control" name="direccion" id="direccion" maxlength="70" placeholder="Dirección">
                        </div>

                        <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                          <label>Teléfono:</label>
                          <input type="text" class="form-control" name="telefono" id="telefono" maxlength="20" placeholder="Teléfono">
                        </div>

                        <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                          <label>Email:</label>
                          <input type="text" class="form-control" name="mail" id="mail" maxlength="50" placeholder="Email">
                        </div>

                        
                        <div class="form-group col-lg-12 col-mg-12 col-sm-12 col-xs-12">
                          <button class="btn btn-primary" type="submit" id="btnGuardar"><i class="fa fa-save"></i>Guardar</button>
                          <button class="btn btn-danger" onclick="cancelarForm()" type="button" ><i class="fa fa-arrow-circle-left"></i>Cancelar</button>
                        </div>
                      </form>
                        
                    </div>
                    <!--Fin centro -->
                  </div><!-- /.box -->
              </div><!-- /.col -->
          </div><!-- /.row -->
      </section><!-- /.content -->

    </div><!-- /.content-wrapper -->
  <!--Fin-Contenido-->

<?php

}else{
  require 'noAcceso.php';
}

require 'footer.php';

?>
<script type="text/javascript" src="scripts/cliente.js"></script>


<?php
}
ob_end_flush();
// liberamos el almacenamiento en el buffer
?>