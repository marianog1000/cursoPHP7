<?php 

if (strlen(session_id())<1)
	session_start();

require_once "../modelos/Ingreso.php";

$ingreso = new Ingreso();

$idIngreso = isset($_POST["idIngreso"])?limpiarCadena($_POST["idIngreso"]):"";
$idProveedor = isset($_POST["idProveedor"])?limpiarCadena($_POST["idProveedor"]):"";

$idUsuario = isset($_SESSION["idUsuario"]);
$tipo_comprobante = isset($_POST["tipo_comprobante"])?limpiarCadena($_POST["tipo_comprobante"]):"";
$serie_comprobante = isset($_POST["serie_comprobante"])?limpiarCadena($_POST["serie_comprobante"]):"";
$num_comprobante = isset($_POST["num_comprobante"])?limpiarCadena($_POST["num_comprobante"]):"";
$fecha_hora = isset($_POST["fecha_hora"])?limpiarCadena($_POST["fecha_hora"]):"";
$impuesto = isset($_POST["impuesto"])?limpiarCadena($_POST["impuesto"]):"";
$total_compra = isset($_POST["total_compra"])?limpiarCadena($_POST["total_compra"]):"";



switch ($_GET["op"]) {
	case 'guardaryeditar':
		if(empty($idIngreso)){
			$rsta=$ingreso->insertar($idProveedor, $idUsuario, $tipo_comprobante, $serie_comprobante, $num_comprobante, $fecha_hora, $impuesto, $total_compra, $_POST['idArticulo'], $_POST['cantidad'], $_POST['precio_compra'], $_POST['precio_venta']);
			echo $rsta?"Ingreso Registrado": "Ingreso No se pudo registrar";
		}
		break;
	case 'anular':
		$rsta=$ingreso->anular($idIngreso);
		echo $rsta?"Ingreso Anulado": "Ingreso No se pudo anular.";
		break;
	case 'mostrar':
		$rsta=$ingreso->mostrar($idIngreso);
		// codificar el resultado utilizando json
		echo json_encode($rsta);
		break;
	case 'listarDetalle':
		$id = $_GET["id"];
		$rpta = $ingreso->listarDetalle($id);

		$total=0;


		echo '<thead style="background-color: #A9D0F5">
              <th>Opciones</th>
              <th>Artículo</th>
              <th>Cantidad</th>
              <th>Precio Compra</th>
              <th>Precio Venta</th>
              <th>Subtotal</th>                              
	         </thead>';

		while ($reg=$rpta->fetch_object()) {
			echo '<tr class="filas">
					<td></td>
					<td>'.$reg->nombre.'</td>
					<td>'.$reg->cantidad.'</td>
					<td>'.$reg->precio_compra.'</td>
					<td>'.$reg->precio_venta.'</td>
					<td>'.$reg->precio_compra*$reg->cantidad.'</td>
				</tr>';
				$total = $total + ($reg->precio_compra*$reg->cantidad); 
		}

	    echo '<tfoot>
		          <th>Total</th>
		          <th></th>
		          <th></th>
		          <th></th>
		          <th></th>
		          <th><h4 id="total">S/'.$total.'</h4><input type="hidden" name="total_compra" id="total_compra"></th>
		     </tfoot>';

		break;


	case 'listar':
		$rspta=$ingreso->listar();

		$data = Array();

 		while($reg=$rspta->fetch_object()){
			$data[]=array(
				"0"=>($reg->estado=='Aceptado')?
					'<button class="btn btn-warning" onclick="mostrar('.$reg->idIngreso.')"><i class="fa fa-eye"></i></button>'.
                	' <button class="btn btn-danger" onclick="anular('.$reg->idIngreso.')"><i class="fa fa-close"></i></button>':
					'<button class="btn btn-warning" onclick="mostrar('.$reg->idIngreso.')"><i class="fa fa-eye"></i></button>',
				"1"=>$reg->fecha_i,
				"2"=>$reg->proveedor,
				"3"=>$reg->usuario,
				"4"=>$reg->tipo_comprobante,
				"5"=>$reg->serie_comprobante.'-'. $reg->num_comprobante,
				"6"=>$reg->total_compra,
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

		case 'selectProveedor':
			require_once "../modelos/Persona.php";
			$persona = new Persona();

			$rta = $persona->listarP();
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