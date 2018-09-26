<?php 
//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion.php";

Class Articulo
{
	//Implementamos nuestro constructor
	public function __construct()
	{

	}

	//Implementamos un método para insertar registros
	public function insertar($idCategoria,$codigo,$nombre,$stock,$descripcion,$imagen)
	{
		$sql="INSERT INTO Articulo (idCategoria,codigo,nombre,stock,descripcion,imagen,condicion)
		VALUES ('$idCategoria','$codigo','$nombre','$stock','$descripcion','','1')";
		return ejecutarConsulta($sql);
	}

	//Implementamos un método para editar registros
	public function editar($idarticulo,$idCategoria,$codigo,$nombre,$stock,$descripcion,$imagen)
	{
		$sql="UPDATE Articulo SET idCategoria=$idCategoria, codigo=$codigo, nombre='$nombre',stock=$stock, descripcion='$descripcion', imagen='$imagen' WHERE idarticulo='$idarticulo'";
		return ejecutarConsulta($sql);
	}

	//Implementamos un método para desactivar categorías
	public function desactivar($idarticulo)
	{
		$sql="UPDATE Articulo SET condicion='0' WHERE idarticulo='$idarticulo'";
		return ejecutarConsulta($sql);
	}

	//Implementamos un método para activar categorías
	public function activar($idarticulo)
	{
		$sql="UPDATE Articulo SET condicion='1' WHERE idarticulo='$idarticulo'";
		return ejecutarConsulta($sql);
	}

	//Implementar un método para mostrar los datos de un registro a modificar
	public function mostrar($idarticulo)
	{
		$sql="SELECT * FROM Articulo WHERE idarticulo='$idarticulo'";
		return ejecutarConsultaSimpleFila($sql);
	}

	//Implementar un método para listar los registros
	public function listar()
	{
		$sql="SELECT a.idarticulo, a.idCategoria, c.nombre as Categoria, a.codigo, a.nombre, a.stock, a.descripcion, a.imagen, a.condicion FROM Articulo a inner join Categoria c ON a.idCategoria = c.idCategoria";
		return ejecutarConsulta($sql);
	}


	//Implementar un método para listar los registros activos
	public function listarActivos()
	{
		$sql="SELECT a.idarticulo, a.idCategoria, c.nombre as Categoria, a.codigo, a.nombre, a.stock, a.descripcion, a.imagen, a.condicion FROM Articulo a inner join Categoria c ON a.idCategoria = c.idCategoria WHERE a.condicion = '1'";
		return ejecutarConsulta($sql);
	}


	public function listarActivosVenta()
	{
		$sql="SELECT a.idarticulo, a.idCategoria, c.nombre as Categoria, a.codigo, a.nombre, a.stock, (SELECT precio_venta FROM detalle_ingreso WHERE idarticulo = a.idarticulo order by idDetalle_ingreso desc limit 0,1) as precio_venta, a.descripcion, a.imagen, a.condicion FROM Articulo a inner join Categoria c ON a.idCategoria = c.idCategoria WHERE a.condicion = '1'";
		return ejecutarConsulta($sql);
	}
}

?>