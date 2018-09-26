<?php 
//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion.php";

Class Ingreso
{
	//Implementamos nuestro constructor
	public function __construct()
	{

	}

	//Implementamos un método para insertar registros
	public function insertar($idProveedor, $idUsuario, $tipo_comprobante, $serie_comprobante, $num_comprobante, $fecha_hora, $impuesto, $total_compra, $idArticulo, $cantidad, $precio_compra, $precio_venta )
	{

		$sql="INSERT INTO ingreso (idproveedor, idusuario, tipo_comprobante, serie_comprobante, num_comprobante, fecha_hora, impuesto, total_compra, estado)
		VALUES ('$idProveedor', '$idUsuario', '$tipo_comprobante', '$serie_comprobante', '$num_comprobante', '$fecha_hora', '$impuesto','$total_compra','Aceptado')";
		$idIngresonew = ejecutarConsulta_retornarID($sql);
		$num_elementos=0;
		$sw=true;

		while($num_elementos<count($idArticulo)){
			$sql_detalle = "INSERT INTO detalle_ingreso(idIngreso, idArticulo, cantidad, precio_compra, precio_venta) VALUES('$idIngresonew','$idArticulo[$num_elementos]', '$cantidad[$num_elementos]','$precio_compra[$num_elementos]','$precio_venta[$num_elementos]' )";
			ejecutarConsulta($sql_detalle) or $sw=false;
			$num_elementos = $num_elementos+1;
		}

		return $sw;
	}



	//Implementamos un método para anular 
	public function anular($idIngreso)
	{
		$sql="UPDATE ingreso SET estado = 'Anulado' WHERE idingreso='$idIngreso'";
		return ejecutarConsulta($sql);
	}

	//Implementar un método para mostrar los datos de un registro a modificar
	public function mostrar($idIngreso)
	{
		$sql="SELECT i.idIngreso, DATE(i.fecha_hora) as fecha_i, idproveedor, p.nombre as proveedor, u.idUsuario, u.nombre as usuario, i.tipo_comprobante, i.serie_comprobante, i.num_comprobante, i.total_compra, i.impuesto, i.estado FROM ingreso i 
		INNER JOIN persona p ON i.idproveedor = p.idPersona 
		INNER JOIN usuario u ON i.idusuario = u.idUsuario 
		where i.idIngreso = '$idIngreso'";

		return ejecutarConsultaSimpleFila($sql);
	}

	public function listarDetalle($idIngreso)
	{
		$sql = "SELECT di.idingreso, di.idarticulo, a.nombre, di.cantidad, di.precio_compra, di.precio_venta FROM detalle_ingreso di inner join articulo a on 
		 di.idarticulo = a.idarticulo where di.idingreso ='$idIngreso'";
		return ejecutarConsulta($sql);


	}

	//Implementar un método para listar los registros
	public function listar()
	{
		$sql="SELECT i.idIngreso, DATE(i.fecha_hora) as fecha_i, idproveedor, p.nombre as proveedor, u.idUsuario, u.nombre as usuario, i.tipo_comprobante, i.serie_comprobante, i.num_comprobante, i.total_compra, i.impuesto, i.estado FROM ingreso i 
		INNER JOIN persona p ON i.idproveedor = p.idPersona 
		INNER JOIN usuario u ON i.idusuario = u.idUsuario 
		ORDER BY i.idIngreso desc";
		return ejecutarConsulta($sql);
	}
}

?>