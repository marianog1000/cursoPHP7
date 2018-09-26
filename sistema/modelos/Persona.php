<?php 
//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion.php";

Class Persona
{
	//Implementamos nuestro constructor
	public function __construct()
	{

	}

	//Implementamos un método para insertar registros
	public function insertar($tipo_persona,$nombre,$tipo_documento,$num_documento,$direccion,$telefono, $mail)
	{
		$sql="INSERT INTO persona (tipo_persona,nombre,tipo_documento,num_documento,direccion,telefono,mail)
		VALUES ('$tipo_persona','$nombre','$tipo_documento','$num_documento','$direccion',
		'$telefono', '$mail')";
		return ejecutarConsulta($sql);
	}

	//Implementamos un método para editar registros
	public function editar($idPersona,$tipo_persona,$nombre,$tipo_documento,$num_documento,$direccion,$telefono, $mail)
	{
		$sql="UPDATE persona SET tipo_persona='$tipo_persona',nombre='$nombre',tipo_documento='$tipo_documento',num_documento='$num_documento',direccion='$direccion',telefono='$telefono',mail='$mail' WHERE idPersona='$idPersona'";

		echo $sql;
		return ejecutarConsulta($sql);
	}

	//Implementamos un método para eliminar Personas
	public function eliminar($idPersona)
	{
		$sql="DELETE FROM persona WHERE idPersona='$idPersona'";
		return ejecutarConsulta($sql);
	}

	//Implementar un método para mostrar los datos de un registro a modificar
	public function mostrar($idPersona)
	{
		$sql="SELECT * FROM persona WHERE idPersona='$idPersona'";
		return ejecutarConsultaSimpleFila($sql);
	}

	//Implementar un método para listar los registros
	public function listarP()
	{
		$sql="SELECT * FROM persona WHERE tipo_persona = 'Proveedor'";
		return ejecutarConsulta($sql);
	}


	//Implementar un método para listar los registros
	public function listarC()
	{
		$sql="SELECT * FROM persona WHERE tipo_persona = 'Cliente'";
		return ejecutarConsulta($sql);
	}	

}

?>