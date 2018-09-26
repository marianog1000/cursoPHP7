<?php

session_start(); 

require_once "../modelos/Usuario.php";

$usuario = new Usuario();

$idUsuario = isset($_POST["idUsuario"])?limpiarCadena($_POST["idUsuario"]):"";
$nombre = isset($_POST["nombre"])?limpiarCadena($_POST["nombre"]):"";
$tipo_documento = isset($_POST["tipo_documento"])?limpiarCadena($_POST["tipo_documento"]):"";
$num_documento = isset($_POST["num_documento"])?limpiarCadena($_POST["num_documento"]):"";
$direccion = isset($_POST["direccion"])?limpiarCadena($_POST["direccion"]):"";
$telefono = isset($_POST["telefono"])?limpiarCadena($_POST["telefono"]):"";
$mail = isset($_POST["mail"])?limpiarCadena($_POST["mail"]):"";
$cargo = isset($_POST["cargo"])?limpiarCadena($_POST["cargo"]):"";
$login = isset($_POST["login"])?limpiarCadena($_POST["login"]):"";
$clave = isset($_POST["clave"])?limpiarCadena($_POST["clave"]):"";
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
				move_uploaded_file($_FILES["imagen"]["tmp_name"], "../files/usuarios/" . $imagen);
			}
		}

		// HASH SHA256 en la contraseña
		$clavehash = hash("SHA256",$clave);

		if(empty($idUsuario)){
			$rsta=$usuario->insertar($nombre,$tipo_documento,$num_documento,$direccion,$telefono,$mail,$cargo,$login,$clavehash,$imagen,$_POST['permiso']);
			echo $rsta?"Usuario Registrado": "Usuario No se pudieron registrar todos los datos del Usuario";
		}else{
			$rsta=$usuario->editar($idUsuario,$nombre,$tipo_documento,$num_documento,$direccion,$telefono,$mail,$cargo,$login,$clavehash,$imagen,$_POST['permiso']);
			echo $rsta?"Usuario Actualizado": "Usuario No se pudo Actualizar";
		}
		break;
	case 'desactivar':
		$rsta=$usuario->desactivar($idUsuario);
		echo $rsta?"Usuario Desactivada": "Usuario No se pudo desactivar";
		break;
	case 'activar':
		$rsta=$usuario->activar($idUsuario);
		echo $rsta?"Usuario Activado": "Usuario No se pudo Activar";
		break;
	case 'mostrar':
		$rsta=$usuario->mostrar($idUsuario);
		// codificar el resultado utilizando json
		echo json_encode($rsta);
		break;
	case 'listar':
		$rsta=$usuario->listar();

		$data = Array();

 		while($reg=$rsta->fetch_object()){
			$data[]=array(
				"0"=>($reg->condicion)?
					'<button class="btn btn-warning" onclick="mostrar('.$reg->idUsuario.')"><i class="fa fa-pencil"></i></button>'.
                	' <button class="btn btn-danger" onclick="desactivar('.$reg->idUsuario.')"><i class="fa fa-close"></i></button>':
					'<button class="btn btn-warning" onclick="mostrar('.$reg->idUsuario.')"><i class="fa fa-pencil"></i></button>'.
                	' <button class="btn btn-primary" onclick="activar('.$reg->idUsuario.')"><i class="fa fa-check"></i></button>',
				"1"=>$reg->nombre,
				"2"=>$reg->tipo_documento,
				"3"=>$reg->num_documento,
				"4"=>$reg->telefono,
				"5"=>$reg->mail,
				"6"=>$reg->login,
				"7"=>"<img src='../files/usuarios/".$reg->imagen."' height='50px' width='50px'>",
				"8"=>($reg->condicion)?'<span class="label bg-green">Activado</span>':'<span class="label bg-red">Desactivado</span>'
			);
		}
		$results = array(
				"sEcho"=>1, // información para DataTables
				"iTotalRecords"=>count($data), // enviamos el total de registros al datatable.
				"iTotalDisplayRecords"=>count($data), // enviamos el total de registros a visualizar
				"aaData"=>$data);
		echo json_encode($results);
		break;

	case 'permisos':
		// obtener todos los permisos de la tabla permisos
		require_once "../modelos/Permiso.php";
		$permiso = new Permiso();
		$rsta = $permiso->listar();

		// obtener los permisos asignados al usuario
		$id = $_GET['id'];
		$marcados = $usuario->listarMarcados($id);

		$valores = array();
		while($per=$marcados->fetch_object()){
			array_push($valores,$per->idpermiso);
		}

		while($reg= $rsta->fetch_object()){
			$sw = in_array($reg->idPermiso, $valores)?'checked':'';
			echo '<li> <input type="checkbox" '.$sw.' name="permiso[]" value="' . $reg->idPermiso .'">'. $reg->nombre .'</li>';
		}
	break;

	case 'verificar':
		$logina = $_POST['logina'];
		$clavea = $_POST['clavea'];


		$clavehash = hash("SHA256",$clavea);
		
	
		$rpta= $usuario->verificar($logina,$clavehash);

		$fetch = $rpta->fetch_object();
		if(isset($fetch)){
			$_SESSION['idUsuario']=$fetch->idUsuario;
			$_SESSION['nombre']=$fetch->nombre;
			$_SESSION['imagen']=$fetch->imagen;
			$_SESSION['login']=$fetch->login;

			$marcados = $usuario->listarMarcados($fetch->idUsuario);
			$valores = array();

			while($per=$marcados->fetch_object()){
				array_push($valores,$per->idpermiso);		
			}

			in_array(1,$valores)?$_SESSION['escritorio']=1:$_SESSION['escritorio']=0;
			in_array(2,$valores)?$_SESSION['almacen']=1:$_SESSION['almacen']=0;
			in_array(3,$valores)?$_SESSION['compras']=1:$_SESSION['compras']=0;
			in_array(4,$valores)?$_SESSION['ventas']=1:$_SESSION['ventas']=0;
			in_array(5,$valores)?$_SESSION['acceso']=1:$_SESSION['acceso']=0;
			in_array(6,$valores)?$_SESSION['consultac']=1:$_SESSION['consultac']=0;
			in_array(7,$valores)?$_SESSION['consultav']=1:$_SESSION['consultav']=0;

		}
		echo json_encode($fetch);
	break;
	case 'salir':

		// limpiamos las variables de sesión
		session_unset();

		//destruímos la sesión
		session_destroy();

		header('Location: ../index.php');
	break;
}
?>