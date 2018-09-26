<?php 

if (strlen(session_id())<1)
	session_start();

require_once "../modelos/Venta.php";

$venta = new Venta();

$idVenta = isset($_POST["idVenta"])?limpiarCadena($_POST["idVenta"]):"";
$idCliente = isset($_POST["idCliente"])?limpiarCadena($_POST["idCliente"]):"";

$idUsuario = isset($_SESSION["idUsuario"]);
$tipo_comprobante = isset($_POST["tipo_comprobante"])?limpiarCadena($_POST["tipo_comprobante"]):"";
$serie_comprobante = isset($_POST["serie_comprobante"])?limpiarCadena($_POST["serie_comprobante"]):"";
$num_comprobante = isset($_POST["num_comprobante"])?limpiarCadena($_POST["num_comprobante"]):"";
$fecha_hora = isset($_POST["fecha_hora"])?limpiarCadena($_POST["fecha_hora"]):"";
$impuesto = isset($_POST["impuesto"])?limpiarCadena($_POST["impuesto"]):"";
$total_venta = isset($_POST["total_venta"])?limpiarCadena($_POST["total_venta"]):"";

switch ($_GET["op"]) {
	case 'guardaryeditar':
		if(empty($idVenta)){
			$rsta=$venta->insertar($idCliente, $idUsuario, $tipo_comprobante, $serie_comprobante, $num_comprobante, $fecha_hora, $impuesto, $total_venta, $_POST['idArticulo'], $_POST['cantidad'], $_POST['precio_venta'], $_POST['descuento']);
			echo $rsta?"Venta Registrada": "Venta No se pudo registrar";
		}
		break;
	case 'anular':
		$rsta=$venta->anular($idVenta);
		echo $rsta?"Venta Anulada": "Venta No se pudo anular.";
		break;
	case 'mostrar':
		$rsta=$venta->mostrar($idVenta);
		// codificar el resultado utilizando json
		echo json_encode($rsta);
		break;
	case 'listarDetalle':
		$id = $_GET["id"];
		$rpta = $venta->listarDetalle($id);

		$total=0;


		echo '<thead style="background-color: #A9D0F5">
              <th>Opciones</th>
              <th>Artículo</th>
              <th>Cantidad</th>
              <th>Precio Venta</th>
              <th>Descuento</th>
              <th>Subtotal</th>                              
	         </thead>';

		while ($reg=$rpta->fetch_object()) {
			echo '<tr class="filas">
					<td></td>
					<td>'.$reg->nombre.'</td>
					<td>'.$reg->cantidad.'</td>
					<td>'.$reg->precio_venta.'</td>
					<td>'.$reg->descuento.'</td>
					<td>'.(($reg->precio_venta*$reg->cantidad)-$reg->descuento).'</td>
				</tr>';
				$total = $total + (($reg->precio_venta*$reg->cantidad)-$reg->descuento); 
		}

	    echo '<tfoot>
		          <th>Total</th>
		          <th></th>
		          <th></th>
		          <th></th>
		          <th></th>
		          <th><h4 id="total">S/'.$total.'</h4><input type="hidden" name="total_venta" id="total_venta"></th>
		     </tfoot>';

		break;


	case 'listar':
		$rspta=$venta->listar();

		$data = Array();

 		while($reg=$rspta->fetch_object()){


 			if ($reg->tipo_comprobante=='Ticket'){
 				$url = '../reportes/exTicket.php?id=';
 			}else{
 				$url = '../reportes/exFactura.php?id='; 				
 			}

			$data[]=array(
				"0"=>(($reg->estado=='Aceptado')?
					'<button class="btn btn-warning" onclick="mostrar('.$reg->idventa.')"><i class="fa fa-eye"></i></button>'.
                	' <button class="btn btn-danger" onclick="anular('.$reg->idventa.')"><i class="fa fa-close"></i></button>':
					'<button class="btn btn-warning" onclick="mostrar('.$reg->idventa.')"><i class="fa fa-eye"></i></button>').
					'<a target="_blank" href="'.$url.$reg->idventa.'"> <button class="btn btn-info"><i class="fa fa-file"></i></button></a>'





				,
				"1"=>$reg->fecha_v,
				"2"=>$reg->cliente,
				"3"=>$reg->usuario,
				"4"=>$reg->tipo_comprobante,
				"5"=>$reg->serie_comprobante.'-'. $reg->num_comprobante,
				"6"=>$reg->total_venta,
				"7"=>($reg->estado=='Aceptado')?'<span class="label bg-green">Activado</span>':'<span class="label bg-red">Anulado</span>'
			);
		}
		$results = array(
				"sEcho"=>1, // información para DataTables
				"iTotalRecords"=>count($data), // enviamos el total de registros al datatable.
				"iTotalDisplayRecords"=>count($data), // enviamos el total de registros a visualizar
				"aaData"=>$data);
		echo json_encode($results);
		break;

		case 'selectVendedor':
			require_once "../modelos/Persona.php";
			$persona = new Persona();

			$rta = $persona->listarC();
			while($reg = $rta->fetch_object()){
				echo '<option value=' . $reg->idPersona . '>'.$reg->nombre.'</option>';

			}
		break;

		case 'listarArticulos':

			require_once "../modelos/Articulo.php";
			$articulo = new Articulo();

			$rspta=$articulo->listarActivos();

			$data = Array();

			while($reg=$rspta->fetch_object()){
			$data[]=array(
				"0"=>'<button class="btn btn-warning" onclick="agregarDetalle('.$reg->idarticulo.',\''.$reg->nombre.'\')"><span class="fa fa-plus"></span></button>',
				"1"=>$reg->nombre,
				"2"=>$reg->Categoria,
				"3"=>$reg->codigo,
				"4"=>$reg->stock,
				"5"=>"<img src='../files/articulos/".$reg->imagen."' height='50px' width='50px'>"
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