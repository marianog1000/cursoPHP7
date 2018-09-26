<?php

// activamos el almacenamiento en el buffer
ob_start();
session_start();


if (!isset($_SESSION["nombre"])){
  header("Location: login.html");  
}else{

require 'header.php';


if ($_SESSION['escritorio']==1){
	require_once "../modelos/Consultas.php";
	$consulta = new Consultas();
	$respuestac = $consulta->totalcomprahoy();
	$regc = $respuestac->fetch_object();
	$totalc = $regc->total_compra;

	$respuestav = $consulta->totalventahoy();
	$regv = $respuestav->fetch_object();
	$totalv = $regv->total_venta;

	$compras10 = $consulta->compraultimos_10dias();
	$fecha_c='';
	$totales_c='';
	while($regfechac= $compras10->fetch_object()){
		$fecha_c=$fecha_c.' "'.$regfechac->fecha .'",';
		$totales_c=$totales_c.$regfechac->total.',';
	}
	$fecha_c = substr($fecha_c, 0,-1);
	$totales_c = substr($totales_c, 0,-1);


	$ventas12 = $consulta->ventasultimos_12meses();
	$fecha_v='';
	$totales_v='';
	while($regfechav= $ventas12->fetch_object()){
		$fecha_v=$fecha_v.' "'.$regfechav->fecha .'",';
		$totales_v=$totales_v.$regfechav->total.',';
	}
	$fecha_v = substr($fecha_v, 0,-1);
	$totales_v = substr($totales_v, 0,-1);

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
                          <h1 class="box-title">Escritorio </h1>
                        <div class="box-tools pull-right">
                        </div>
                    </div>
                    <!-- /.box-header -->
                    <!-- centro -->
                    <div class="panel-body"  id="listadoregistros">
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                        	<div class="small-box bg-aqua">
                        		<div class="inner">
                        			<h4 style="font-size: 17px;">
                        				<strong>$ <?php echo $totalc; ?></strong>
                        			</h4>
                        			<p>Compras</p>
                        		</div>
                        		<div class="icon">
                        			<i class="ion ion-bag"></i>
                        		</div>
                        		<a href="ingreso.php" class="small-box-footer">Compras<i class="fa fa-arrow-circle-right"></i></a>
                        	</div>                        	
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                        	<div class="small-box bg-green">
                        		<div class="inner">
                        			<h4 style="font-size: 17px;">
                        				<strong>$ <?php echo $totalv; ?></strong>
                        			</h4>
                        			<p>Ventas</p>
                        		</div>
                        		<div class="icon">
                        			<i class="ion ion-bag"></i>
                        		</div>
                        		<a href="venta.php" class="small-box-footer">Ventas<i class="fa fa-arrow-circle-right"></i></a>
                        	</div>                        	
                        </div>
                    </div>
                    <div class="panel-body " style="height: 400px;">
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                        	<div class="box box-primary">
                        		<div class="box-header with-border">
                        			Compras en los últimos 10 días
                        		</div>
                        		<div class="box-body">
                        			<canvas id="compras" width="400" height="300"></canvas>
                        		</div>
                        	</div>
                       	</div>
                       	    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                        	<div class="box box-primary">
                        		<div class="box-header with-border">
                        			Ventas en los últimos 12 Meses
                        		</div>
                        		<div class="box-body">
                        			<canvas id="ventas" width="400" height="300"></canvas>
                        		</div>
                        	</div>
                    	</div>                     
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
<script type="text/javascript" src="../public/js/Chart.min.js"></script>
<script type="text/javascript" src="../public/js/Chart.bundle.min.js"></script>

<script type="text/javascript">
var ctx = document.getElementById("compras").getContext('2d');
var compras = new Chart(ctx, {
    type: 'bar',
    data: {
        labels: [ <?php echo $fecha_c; ?>],
        datasets: [{
            label: '# Compras $ de los últimos 10 días',
            data: [<?php echo $totales_c; ?>],
            backgroundColor: [
                'rgba(54, 162, 235, 0.2)',
                'rgba(255, 206, 86, 0.2)',
                'rgba(75, 192, 192, 0.2)',
                'rgba(153, 102, 255, 0.2)',
                'rgba(255, 159, 64, 0.2)',
                'rgba(255, 99, 132, 0.2)',
                'rgba(54, 162, 235, 0.2)',
                'rgba(255, 206, 86, 0.2)',
                'rgba(75, 192, 192, 0.2)'
            ],
            borderColor: [
                'rgba(255,99,132,1)',
                'rgba(54, 162, 235, 1)',
                'rgba(255, 206, 86, 1)',
                'rgba(75, 192, 192, 1)',
                'rgba(153, 102, 255, 1)',
                'rgba(255, 159, 64, 1)',
                'rgba(255,99,132,1)',
                'rgba(54, 162, 235, 1)',
                'rgba(255, 206, 86, 1)',
                'rgba(75, 192, 192, 1)'
             ],
            borderWidth: 1
        }]
    },
    options: {
        scales: {
            yAxes: [{
                ticks: {
                    beginAtZero:true
                }
            }]
        }
    }
});




var ctx = document.getElementById("ventas").getContext('2d');
var ventas = new Chart(ctx, {
    type: 'bar',
    data: {
        labels: [ <?php echo $fecha_v; ?>],
        datasets: [{
            label: '# Ventas $ de los últimos 12 Meses',
            data: [<?php echo $totales_v; ?>],
            backgroundColor: [
                'rgba(54, 162, 235, 0.2)',
                'rgba(255, 206, 86, 0.2)',
                'rgba(75, 192, 192, 0.2)',
                'rgba(153, 102, 255, 0.2)',
                'rgba(255, 159, 64, 0.2)',
                'rgba(255, 99, 132, 0.2)',
                'rgba(54, 162, 235, 0.2)',
                'rgba(255, 206, 86, 0.2)',
                'rgba(75, 192, 192, 0.2)',
                'rgba(255, 159, 64, 0.2)',
                'rgba(255, 99, 132, 0.2)'
                
            ],
            borderColor: [
                'rgba(255,99,132,1)',
                'rgba(54, 162, 235, 1)',
                'rgba(255, 206, 86, 1)',
                'rgba(75, 192, 192, 1)',
                'rgba(153, 102, 255, 1)',
                'rgba(255, 159, 64, 1)',
                'rgba(255,99,132,1)',
                'rgba(54, 162, 235, 1)',
                'rgba(255, 206, 86, 1)',
                'rgba(75, 192, 192, 1)',
                'rgba(153, 102, 255, 1)',
                'rgba(255, 159, 64, 1)'
             ],
            borderWidth: 1
        }]
    },
    options: {
        scales: {
            yAxes: [{
                ticks: {
                    beginAtZero:true
                }
            }]
        }
    }
});

</script>


<?php
}
ob_end_flush();
// liberamos el almacenamiento en el buffer
?>