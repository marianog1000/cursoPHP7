<?php 

require_once "../modelos/Consultas.php";

$consulta = new Consultas();

switch ($_GET["op"]) {
	case 'compraFecha':

		$fecha_inicio = $_REQUEST['fecha_inicio'];
		$fecha_fin = $_REQUEST['fecha_fin'];

		$rspta=$consulta->comprasFecha($fecha_inicio, $fecha_fin);

		$data = Array();

 		while($reg=$rspta->fetch_object()){

			$data[]=array(
				"0"=>$reg->fecha,
				"1"=>$reg->usuario,
				"2"=>$reg->proveedor,
				"3"=>$reg->tipo_comprobante,
				"4"=>$reg->serie_comprobante .' '. $reg->num_comprobante ,
				"5"=>$reg->total_compra,
				"6"=>$reg->impuesto,
				"7"=>($reg->estado=='Aceptado')?'<span class="label bg-green">Aceptado</span>':
					'<span class="label bg-red">Anulado</span>'
			);
		}
		$results = array(
				"sEcho"=>1, // información para DataTables
				"iTotalRecords"=>count($data), // enviamos el total de registros al datatable.
				"iTotalDisplayRecords"=>count($data), // enviamos el total de registros a visualizar
				"aaData"=>$data);
		echo json_encode($results);
	break;
	case 'ventasFecha':

		$fecha_inicio = $_REQUEST['fecha_inicio'];
		$fecha_fin = $_REQUEST['fecha_fin'];
		$idCliente = $_REQUEST['idCliente'];

		$rspta=$consulta->ventasFechaCliente($fecha_inicio, $fecha_fin,$idCliente);
 
		$data = Array();

 		while($reg=$rspta->fetch_object()){
			$data[]=array(
				"0"=>$reg->fecha,
				"1"=>$reg->usuario,
				"2"=>$reg->cliente,
				"3"=>$reg->tipo_comprobante,
				"4"=>$reg->serie_comprobante .' '. $reg->num_comprobante ,
				"5"=>$reg->total_venta,
				"6"=>$reg->impuesto,
				"7"=>($reg->estado=='Aceptado')?'<span class="label bg-green">Aceptado</span>':'<span class="label bg-red">Anulado</span>'
			);
		}
		$results = array(
				"sEcho"=>1, // información para DataTables
				"iTotalRecords"=>count($data), // enviamos el total de registros al datatable.
				"iTotalDisplayRecords"=>count($data), // enviamos el total de registros a visualizar
				"aaData"=>$data);
		echo json_encode($results);
	break;	
}

 ?>