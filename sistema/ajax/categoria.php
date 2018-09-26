<?php 

require_once "../modelos/Categoria.php";

$categoria = new Categoria();

$idCategoria = isset($_POST["idCategoria"])?limpiarCadena($_POST["idCategoria"]):"";
$nombre = isset($_POST["nombre"])?limpiarCadena($_POST["nombre"]):"";
$descripcion = isset($_POST["descripcion"])?limpiarCadena($_POST["descripcion"]):"";

switch ($_GET["op"]) {
	case 'guardaryeditar':
		if(empty($idCategoria)){
			$rsta=$categoria->insertar($nombre,$descripcion);
			echo $rsta?"Categoria Registrada": "Categoria No se pudo registrar";
		}else{
			$rsta=$categoria->editar($idCategoria,$nombre,$descripcion);
			echo $rsta?"Categoria Actualizada": "Categoria No se pudo Actualizar";
		}
		break;
	case 'desactivar':
		$rsta=$categoria->desactivar($idCategoria);
		echo $rsta?"Categoria Desactivada": "Categoria No se pudo desactivar";
		break;
	case 'activar':
		$rsta=$categoria->activar($idCategoria);
		echo $rsta?"Categoria Activada": "Categoria No se pudo Activar";
		break;
	case 'mostrar':
		$rsta=$categoria->mostrar($idCategoria);
		// codificar el resultado utilizando json
		echo json_encode($rsta);
		break;
	case 'listar':
		$rspta=$categoria->listar();

		$data = Array();

 		while($reg=$rspta->fetch_object()){
			$data[]=array(
				"0"=>($reg->condicion)?
					'<button class="btn btn-warning" onclick="mostrar('.$reg->idCategoria.')"><i class="fa fa-pencil"></i></button>'.
                	' <button class="btn btn-danger" onclick="desactivar('.$reg->idCategoria.')"><i class="fa fa-close"></i></button>':
					'<button class="btn btn-warning" onclick="mostrar('.$reg->idCategoria.')"><i class="fa fa-pencil"></i></button>'.
                	' <button class="btn btn-primary" onclick="activar('.$reg->idCategoria.')"><i class="fa fa-check"></i></button>',
				"1"=>$reg->nombre,
				"2"=>$reg->descripcion,
				"3"=>($reg->condicion)?'<span class="label bg-green">Activado</span>':'<span class="label bg-red">Desactivado</span>'
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