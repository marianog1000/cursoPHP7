<?php 
//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion.php";

Class Venta
{
	//Implementamos nuestro constructor
	public function __construct()
	{

	}

	//Implementamos un método para insertar registros
	public function insertar($idCliente, $idUsuario, $tipo_comprobante, $serie_comprobante, $num_comprobante, $fecha_hora, $impuesto, $total_venta, $idArticulo, $cantidad, $precio_venta, $descuento )
	{

		$sql="INSERT INTO venta (idCliente, idusuario, tipo_comprobante, serie_comprobante, num_comprobante, fecha_hora, impuesto, total_venta, estado)
		VALUES ('$idCliente', '$idUsuario', '$tipo_comprobante', '$serie_comprobante', '$num_comprobante', '$fecha_hora', '$impuesto','$total_venta','Aceptado')";
		$idVentanew = ejecutarConsulta_retornarID($sql);
		$num_elementos=0;
		$sw=true;

		while($num_elementos<count($idArticulo)){
			$sql_detalle = "INSERT INTO detalle_venta(idventa, idArticulo, cantidad, precio_venta, descuento) VALUES('$idVentanew','$idArticulo[$num_elementos]', '$cantidad[$num_elementos]','$precio_venta[$num_elementos]','$descuento[$num_elementos]' )";
			ejecutarConsulta($sql_detalle) or $sw=false;
			$num_elementos = $num_elementos+1;
		}

		return $sw;
	}



	//Implementamos un método para anular 
	public function anular($idVenta)
	{
		$sql="UPDATE venta SET estado = 'Anulado' WHERE idventa='$idVenta'";
		return ejecutarConsulta($sql);
	}

	//Implementar un método para mostrar los datos de un registro a modificar
	public function mostrar($idVenta)
	{
		$sql="SELECT v.idVenta, DATE(v.fecha_hora) as fecha_v, v.idCliente, p.nombre as cliente, u.idUsuario, u.nombre as usuario, v.tipo_comprobante, v.serie_comprobante, v.num_comprobante, v.total_venta, v.estado FROM venta v
		INNER JOIN persona p ON v.idCliente = p.idPersona 
		INNER JOIN usuario u ON v.idusuario = u.idUsuario 
		where v.idventa = '$idVenta'";

		return ejecutarConsultaSimpleFila($sql);
	}

	public function listarDetalle($idVenta)
	{
		$sql = "SELECT dv.idventa, dv.idarticulo, a.nombre, dv.cantidad, dv.precio_venta, dv.descuento, (dv.cantidad*dv.precio_venta-dv.descuento) as subtotal FROM detalle_venta dv inner join articulo a on 
		 dv.idarticulo = a.idarticulo where dv.idventa ='$idVenta'";
		return ejecutarConsulta($sql);


	}

	//Implementar un método para listar los registros
	public function listar()
	{
		$sql="SELECT v.idventa, DATE(v.fecha_hora) as fecha_v, v.idCliente, p.nombre as cliente, u.idUsuario, u.nombre as usuario, v.tipo_comprobante, v.serie_comprobante, v.num_comprobante, v.total_venta, v.estado FROM venta v
		INNER JOIN persona p ON v.idcliente = p.idPersona 
		INNER JOIN usuario u ON v.idusuario = u.idUsuario 
		ORDER BY v.idVenta desc";
		return ejecutarConsulta($sql);
	}


	public function ventacabecera($idVenta)
	{
		$sql = "SELECT v.idventa, v.idcliente, p.nombre as cliente, p.direccion, p.tipo_documento, p.num_documento, p.mail, p.telefono, v.idUsuario, u.nombre as usuario, v.tipo_comprobante, v.serie_comprobante, v.num_comprobante, date(v.fecha_hora) as fecha, v.impuesto, v.total_venta FROM venta v inner join persona p on v.idCliente = p.idpersona INNER join usuario u on v.idusuario = u.idusuario where v.idVenta = '$idVenta'";
		return ejecutarConsulta($sql);
	}

	public function ventadetalle($idVenta)
	{
		$sql = "SELECT a.nombre as articulo, a.codigo, d.cantidad, d.precio_venta, d.descuento, (d.cantidad*d.precio_venta-d.descuento) as subtotal FROM detalle_venta d INNER JOIN articulo a ON d.idarticulo = a.idarticulo WHERE d.idventa = '$idVenta' ";
		return ejecutarConsulta($sql);



	}
}

?>