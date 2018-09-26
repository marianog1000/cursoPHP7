<?php 
//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion.php";

Class Consultas
{
	//Implementamos nuestro constructor
	public function __construct()
	{

	}

	public function comprasFecha($fecha_inicio, $fecha_fin)
	{
		$sql = "SELECT DATE(i.fecha_hora) as fecha, u.nombre as usuario, p.nombre as proveedor, i.tipo_comprobante, i.serie_comprobante, i.num_comprobante, i.total_compra, i.impuesto, i.estado from ingreso i INNER JOIN persona p ON i.idproveedor = p.idpersona INNER JOIN usuario u ON i.idusuario = u.idusuario WHERE DATE(i.fecha_hora)>= '$fecha_inicio' and date(i.fecha_hora)<'$fecha_fin'";
		return ejecutarConsulta($sql);
	}

	public function ventasFechaCliente($fecha_inicio, $fecha_fin, $idCliente)
	{

		$sql = "SELECT DATE(v.fecha_hora) as fecha, u.nombre as usuario, p.nombre as cliente, v.tipo_comprobante, v.serie_comprobante, v.num_comprobante, v.total_venta, v.impuesto, v.estado from venta v INNER JOIN persona p ON v.idcliente = p.idpersona INNER JOIN usuario u ON v.idusuario = u.idusuario WHERE DATE(v.fecha_hora)>= '$fecha_inicio' and date(v.fecha_hora)<='$fecha_fin' and v.idcliente='$idCliente'";
		return ejecutarConsulta($sql);
	}

	public function totalcomprahoy()
	{
		$sql= "SELECT IFNULL(sum(total_compra),0) as total_compra from ingreso where date(fecha_hora) like CURDATE()";
		return ejecutarConsulta($sql);
	}

	public function totalventahoy()
	{
		$sql= "SELECT IFNULL(sum(total_venta),0) as total_venta from venta where date(fecha_hora) like CURDATE()";
		return ejecutarConsulta($sql);
	}

	public function compraultimos_10dias()
	{
		$sql= "SELECT CONCAT(DAY(fecha_hora), '-', MONTH(fecha_hora)) as fecha, SUM(total_compra) as total from ingreso group by fecha_hora order by fecha_hora desc limit 0,10";
		return ejecutarConsulta($sql);
	}

	public function ventasultimos_12meses()
	{
		$sql= "SELECT date_format(fecha_hora,'%M') as fecha, SUM(total_venta) as total from venta 
		group by month(fecha_hora) order by fecha_hora desc limit 0,12";
		return ejecutarConsulta($sql);
	}
}

?>