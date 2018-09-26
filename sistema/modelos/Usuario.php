<?php 
//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion.php";

Class Usuario
{
	//Implementamos nuestro constructor
	public function __construct()
	{

	}

	//Implementamos un método para insertar registros
	public function insertar($nombre,$tipo_documento,$num_documento,$direccion,$telefono,$mail,$cargo,$login,$clave,$imagen,$permisos)
	{
		$sql="INSERT INTO usuario (nombre,tipo_documento,num_documento,direccion,telefono,mail,cargo,login,clave,imagen,condicion)
		VALUES ('$nombre','$tipo_documento','$num_documento','$direccion','$telefono','$mail','$cargo','$login','$clave','$imagen','1')";
		$idusuarionew = ejecutarConsulta_retornarID($sql);
		$num_elementos=0;
		$sw=true;

		while($num_elementos<count($permisos)){
			$sql_detalle = "INSERT INTO usuario_permiso(idUsuario, idPermiso) VALUES('$idusuarionew','$permisos[$num_elementos]')";
			ejecutarConsulta($sql_detalle) or $sw=false;
			$num_elementos = $num_elementos+1;
		}

		return $sw;
	}

	//Implementamos un método para editar registros
	public function editar($idUsuario,$nombre,$tipo_documento,$num_documento,$direccion,$telefono,$mail,$cargo,$login,$clave,$imagen,$permisos)
	{
		$sql="UPDATE usuario SET nombre='$nombre',tipo_documento='$tipo_documento',num_documento='$num_documento',direccion='$direccion',telefono='$telefono',mail='$mail',cargo='$cargo',login='$login',clave='$clave',imagen='$imagen' WHERE idUsuario='$idUsuario'";
		ejecutarConsulta($sql);

		$sql_del = "DELETE FROM usuario_permiso where idUsuario = '$idUsuario'";
		ejecutarConsulta($sql_del);

		$num_elementos=0;
		$sw=true;

		while($num_elementos<count($permisos)){
			$sql_detalle = "INSERT INTO usuario_permiso(idUsuario, idPermiso) VALUES('$idUsuario','$permisos[$num_elementos]')";
			ejecutarConsulta($sql_detalle) or $sw=false;
			$num_elementos = $num_elementos+1;
		}
		return $sw;
	}

	//Implementamos un método para desactivar categorías
	public function desactivar($idUsuario)
	{
		$sql="UPDATE usuario SET condicion='0' WHERE idUsuario='$idUsuario'";
		return ejecutarConsulta($sql);
	}

	//Implementamos un método para activar categorías
	public function activar($idUsuario)
	{
		$sql="UPDATE usuario SET condicion='1' WHERE idUsuario='$idUsuario'";
		return ejecutarConsulta($sql);
	}

	//Implementar un método para mostrar los datos de un registro a modificar
	public function mostrar($idUsuario)
	{
		$sql="SELECT * FROM usuario WHERE idUsuario='$idUsuario'";
		return ejecutarConsultaSimpleFila($sql);
	}

	//Implementar un método para listar los registros
	public function listar()
	{
		$sql="SELECT * FROM usuario";
		return ejecutarConsulta($sql);
	}

	public function listarMarcados($idUsuario){
		$sql ="SELECT * FROM usuario_permiso WHERE idUsuario = '$idUsuario'";
		return ejecutarConsulta($sql);
	}


	public function verificar($login, $clave){
		$sql = "SELECT idUsuario, nombre, tipo_documento, num_documento, telefono, mail, cargo, imagen, login FROM usuario where login ='$login' and clave='$clave' and condicion='1' ";
		return ejecutarConsulta($sql);


	}

}

?>