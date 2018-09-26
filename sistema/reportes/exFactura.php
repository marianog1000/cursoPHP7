<?php

// activamos el almacenamiento en el buffer
ob_start();
session_start();


if (!isset($_SESSION["nombre"])){
  echo "Debe Ingresar al sistema correctamente para visualizar el reporte.";  
}else{

if ($_SESSION['ventas']==1){

	require('Factura.php');

	$logo = "logo.jpg";
	$ext_logo = "jpg";
	$empresa = "Soluciones Innovadoras Perú S.A.C";
	$documento = "20477157772";
	$direccion = "Rosario, Italia 351";
	$telefono = "4245874";
	$mail = "soluciones.innovadoras@gmail.com";


	//Obtenemos los datos de la cabecera de la venta actual.
	require_once "../modelos/Venta.php";
	$venta = new Venta();
	$rta = $venta->ventacabecera($_GET['id']);
	$regv = $rta->fetch_object();

	$pdf = new PDF_Invoice('P','mm','A4');
	$pdf->AddPage();

	$pdf->addSociete(utf8_decode($empresa), $documento. "\n". 
			utf8_decode("Dirección: ") .utf8_decode($direccion)."\n".
			utf8_decode("Teléfono: ") .$telefono."\n".
			"Email: " .$mail,$logo,$ext_logo);
	$pdf->fact_dev("$regv->tipo_comprobante ", "$regv->serie_comprobante - $regv->num_comprobante");
	$pdf->temporaire("");
	$pdf->addDate($regv->fecha);

	$pdf->addClientAdresse(utf8_decode($regv->cliente), "Domicilio: ".utf8_decode($regv->direccion),$regv->tipo_documento .": ".$regv->num_documento, "Email: ".$regv->mail, utf8_decode("Teléfono: "). $regv->telefono);

	// establecemos las columnas que va a tener la sección donde mostramos los detalles de la venta
	$cols =array(
			"CODIGO"=>23,
			"DESCRIPCION"=>78,
			"CANTIDAD"=>22,
			"P.U."=>25,
			"DTO"=>20,
			"SUBTOTAL"=>22);
	$pdf->addCols($cols);
	$cols=array(
			"CODIGO"=>"L",
			"DESCRIPCION"=>"L",
			"CANTIDAD"=>"C",
			"P.U."=>"R",
			"DTO"=>"R",
			"SUBTOTAL"=>"C"	);
	$pdf->addLineFormat($cols);

	// ubicación desde donde empezamos a mostrar los detalles.
	$y = 89;
	$rtad = $venta->ventadetalle($_GET["id"]);

	while($reg = $rtad->fetch_object()){
		$line = array(

			"CODIGO"=>"$reg->codigo",
			"DESCRIPCION"=>utf8_decode("$reg->articulo"),
			"CANTIDAD"=>"$reg->cantidad",
			"P.U."=>"$reg->precio_venta",
			"DTO"=>"$reg->descuento",
			"SUBTOTAL"=>"$reg->subtotal"
		);
		$size = $pdf->addLine($y, $line);
		$y += $size +2;
	}

	// convertimos el total en Letras
	require_once "Letras.php";
	$v = new EnLetras();
	$con_letra= strtoupper($v->ValorEnLetras($regv->total_venta, "PESOS"));
	$pdf->addCadreTVAs("---". $con_letra);


	$pdf->addTVAs($regv->impuesto, $regv->total_venta,"$");
	$pdf->addCadreEurosFrancs("IGV"."$regv->impuesto %");
	$pdf->Output('Reporte de Venta','I');








}else{
  require 'No tiene permiso para visualizar el reporte.';
}


}
ob_end_flush();
// liberamos el almacenamiento en el buffer
?>