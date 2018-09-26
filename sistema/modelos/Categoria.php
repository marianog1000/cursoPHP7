<?php 
//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion.php";

Class Categoria
{
	//Implementamos nuestro constructor
	public function __construct()
	{

	}

	//Implementamos un método para insertar registros
	public function insertar($nombre,$descripcion)
	{
		$sql="INSERT INTO categoria (nombre,descripcion,condicion)
		VALUES ('$nombre','$descripcion','1')";
		return ejecutarConsulta($sql);
	}

	//Implementamos un método para editar registros
	public function editar($idCategoria,$nombre,$descripcion)
	{
		$sql="UPDATE categoria SET nombre='$nombre',descripcion='$descripcion' WHERE idCategoria='$idCategoria'";
		return ejecutarConsulta($sql);
	}

	//Implementamos un método para desactivar categorías
	public function desactivar($idCategoria)
	{
		$sql="UPDATE categoria SET condicion='0' WHERE idCategoria='$idCategoria'";
		return ejecutarConsulta($sql);
	}

	//Implementamos un método para activar categorías
	public function activar($idCategoria)
	{
		$sql="UPDATE categoria SET condicion='1' WHERE idCategoria='$idCategoria'";
		return ejecutarConsulta($sql);
	}

	//Implementar un método para mostrar los datos de un registro a modificar
	public function mostrar($idCategoria)
	{
		$sql="SELECT * FROM categoria WHERE idCategoria='$idCategoria'";
		return ejecutarConsultaSimpleFila($sql);
	}

	//Implementar un método para listar los registros
	public function listar()
	{
		$sql="SELECT idCategoria, nombre, descripcion, condicion FROM categoria";
		return ejecutarConsulta($sql);
	}

	//Implementar un método para listar los registros y mostrar en el select
	public function select()
	{
		$sql="SELECT idCategoria, nombre, descripcion, condicion FROM categoria where condicion=1";
		return ejecutarConsulta($sql);
	}	


}

?>