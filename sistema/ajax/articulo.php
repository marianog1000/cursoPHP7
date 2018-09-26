<?php 

require_once "../modelos/Articulo.php";

$articulo = new Articulo();

$idarticulo = isset($_POST["idarticulo"])?limpiarCadena($_POST["idarticulo"]):"";
$idCategoria = isset($_POST["idCategoria"])?limpiarCadena($_POST["idCategoria"]):"";
$codigo = isset($_POST["codigo"])?limpiarCadena($_POST["codigo"]):"";
$nombre = isset($_POST["nombre"])?limpiarCadena($_POST["nombre"]):"";
$stock = isset($_POST["stock"])?limpiarCadena($_POST["stock"]):"";
$descripcion = isset($_POST["descripcion"])?limpiarCadena($_POST["descripcion"]):"";
$imagen = isset($_POST["imagen"])?limpiarCadena($_POST["imagen"]):"";

switch ($_GET["op"]) {
	case 'guardaryeditar':

		if (!file_exists($_FILES['imagen']['tmp_name']) || !is_uploaded_file($_FILES['imagen']['tmp_name']))
		{
			$imagen=$_POST["imagenactual"];
		}
		else 
		{
			$ext = explode(".", $_FILES["imagen"]["name"]);
			if ($_FILES['imagen']['type'] == "image/jpg" || $_FILES['imagen']['type'] == "image/jpeg" || $_FILES['imagen']['type'] == "image/png")
			{
				$imagen = round(microtime(true)) . '.' . end($ext);
				move_uploaded_file($_FILES["imagen"]["tmp_name"], "../files/articulos/" . $imagen);
			}
		}

		if(empty($idarticulo)){
			$rsta=$articulo->insertar($idCategoria,$codigo,$nombre,$stock,$descripcion,$imagen);
			echo $rsta?"Articulo Registrado": "Articulo No se pudo registrar";
		}else{
			$rsta=$articulo->editar($idarticulo,$idCategoria,$codigo,$nombre,$stock,$descripcion,$imagen);
			echo $rsta?"Articulo Actualizado": "Articulo No se pudo Actualizar";
		}
		break;
	case 'desactivar':
		$rsta=$articulo->desactivar($idarticulo);
		echo $rsta?"Articulo Desactivada": "Articulo No se pudo desactivar";
		break;
	case 'activar':
		$rsta=$articulo->activar($idarticulo);
		echo $rsta?"Articulo Activado": "Articulo No se pudo Activar";
		break;
	case 'mostrar':
		$rsta=$articulo->mostrar($idarticulo);
		// codificar el resultado utilizando json
		echo json_encode($rsta);
		break;
	case 'listar':
		$rspta=$articulo->listar();

		$data = Array();

 		while($reg=$rspta->fetch_object()){
			$data[]=array(
				"0"=>($reg->condicion)?
					'<button class="btn btn-warning" onclick="mostrar('.$reg->idarticulo.')"><i class="fa fa-pencil"></i></button>'.
                	' <button class="btn btn-danger" onclick="desactivar('.$reg->idarticulo.')"><i class="fa fa-close"></i></button>':
					'<button class="btn btn-warning" onclick="mostrar('.$reg->idarticulo.')"><i class="fa fa-pencil"></i></button>'.
                	' <button class="btn btn-primary" onclick="activar('.$reg->idarticulo.')"><i class="fa fa-check"></i></button>',
				"1"=>$reg->nombre,
				"2"=>$reg->Categoria,
				"3"=>$reg->codigo,
				"4"=>$reg->stock,
				"5"=>"<img src='../files/articulos/".$reg->imagen."' height='50px' width='50px'>",
				"6"=>($reg->condicion)?'<span class="label bg-green">Activado</span>':'<span class="label bg-red">Desactivado</span>'
			);
		}
		$results = array(
				"sEcho"=>1, // información para DataTables
				"iTotalRecords"=>count($data), // enviamos el total de registros al datatable.
				"iTotalDisplayRecords"=>count($data), // enviamos el total de registros a visualizar
				"aaData"=>$data);
		echo json_encode($results);
		break;


		case "selectCategoria":
			require_once "../modelos/Categoria.php";
			$categoria = new Categoria();
			$rspta = $categoria->select();
			while ( $reg = $rspta->fetch_object()) {
				echo '<option value='.$reg->idCategoria.'>'.$reg->nombre.'</option>';
			}
		break;
}

 ?>