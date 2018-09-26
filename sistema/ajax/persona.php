<?php 

require_once "../modelos/Persona.php";

$persona = new Persona();

$idPersona = isset($_POST["idPersona"])?limpiarCadena($_POST["idPersona"]):"";
$tipo_persona = isset($_POST["tipo_persona"])?limpiarCadena($_POST["tipo_persona"]):"";
$nombre = isset($_POST["nombre"])?limpiarCadena($_POST["nombre"]):"";
$tipo_documento = isset($_POST["tipo_documento"])?limpiarCadena($_POST["tipo_documento"]):"";
$num_documento = isset($_POST["num_documento"])?limpiarCadena($_POST["num_documento"]):"";
$direccion = isset($_POST["direccion"])?limpiarCadena($_POST["direccion"]):"";
$telefono = isset($_POST["telefono"])?limpiarCadena($_POST["telefono"]):"";
$mail = isset($_POST["mail"])?limpiarCadena($_POST["mail"]):"";


switch ($_GET["op"]) {
	case 'guardaryeditar':

		if(empty($idPersona)){
			$rsta=$persona->insertar($tipo_persona,$nombre,$tipo_documento,$num_documento,$direccion,$telefono,$mail);
			echo $rsta?"Persona Registrada": "Persona No se pudo registrar";
		}else{
			$rsta=$persona->editar($idPersona,$tipo_persona,$nombre,$tipo_documento,$num_documento,$direccion,$telefono, $mail);
			echo $rsta?"Persona Actualizada": "Persona No se pudo Actualizar";
		}
		break;
	case 'eliminar':
		$rsta=$persona->eliminar($idPersona);
		echo $rsta?"Persona Eliminada": "Persona No se pudo eliminar";
		break;
	case 'mostrar':
		$rsta=$persona->mostrar($idPersona);
		// codificar el resultado utilizando json
		echo json_encode($rsta);
		break;
	case 'listarP':
		$rspta=$persona->listarP();

		$data = Array();

 		while($reg=$rspta->fetch_object()){
			$data[]=array(
				"0"=>'<button class="btn btn-warning" onclick="mostrar('.$reg->idPersona.')"><i class="fa fa-pencil"></i></button>'.
                	' <button class="btn btn-danger" onclick="eliminar('.$reg->idPersona.')"><i class="fa fa-trash"></i></button>',
				"1"=>$reg->nombre,
				"2"=>$reg->tipo_documento,
				"3"=>$reg->num_documento,
				"4"=>$reg->telefono,
				"5"=>$reg->mail
			);
		}
		$results = array(
				"sEcho"=>1, // información para DataTables
				"iTotalRecords"=>count($data), // enviamos el total de registros al datatable.
				"iTotalDisplayRecords"=>count($data), // enviamos el total de registros a visualizar
				"aaData"=>$data);
		echo json_encode($results);
		break;

	case 'listarC':
		$rspta=$persona->listarC();

		$data = Array();

 		while($reg=$rspta->fetch_object()){
			$data[]=array(
				"0"=>'<button class="btn btn-warning" onclick="mostrar('.$reg->idPersona.')"><i class="fa fa-pencil"></i></button>'.
                	' <button class="btn btn-danger" onclick="eliminar('.$reg->idPersona.')"><i class="fa fa-trash"></i></button>',
				"1"=>$reg->nombre,
				"2"=>$reg->tipo_documento,
				"3"=>$reg->num_documento,
				"4"=>$reg->telefono,
				"5"=>$reg->mail
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