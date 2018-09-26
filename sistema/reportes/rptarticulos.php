<?php

// activamos el almacenamiento en el buffer
ob_start();
session_start();


if (!isset($_SESSION["nombre"])){
  echo "Debe Ingresar al sistema correctamente para visualizar el reporte.";  
}else{

if ($_SESSION['almacen']==1){

	require('PDF_MC_Table.php');
	$pdf = new PDF_MC_Table();

	$pdf->AddPage();
	// inicio margen superior
	$y_axis_initial = 25;
	$pdf->SetFont('Arial','B',12);
	$pdf->Cell(40,6,'',0,0,'C');
	$pdf->Cell(100,6,'LISTA DE ARTICULOS',1,0,'C');
	$pdf->Ln(10);

	$pdf->SetFillColor(232,232,232);
	$pdf->SetFont('Arial','B',10);
	$pdf->Cell(58,6,'Nombre',1,0,'C',1);
	$pdf->Cell(50,6,utf8_decode('Categoría'),1,0,'C',1);
	$pdf->Cell(30,6,utf8_decode('Código'),1,0,'C',1);
	$pdf->Cell(12,6,utf8_decode('Stock'),1,0,'C',1);
	$pdf->Cell(35,6,utf8_decode('Descripción'),1,0,'C',1);
	$pdf->Ln(10);


	require_once "../modelos/Articulo.php";
	$articulo = new Articulo();
	$rta = $articulo->listar();

	$pdf->SetWidths(array(58,50,32,12,35));

	while($reg = $rta->fetch_object()){
		$nombre=$reg->nombre;
		$categoria=$reg->Categoria;
		$codigo=$reg->codigo;
		$stock=$reg->stock;
		$descripcion = $reg->descripcion;

		$pdf->SetFont('Arial','',10);
		$pdf->Row(array(utf8_decode($nombre), utf8_decode($categoria), $codigo, $stock, utf8_decode($descripcion)));
	}
	// mostramos el pdf
	$pdf->Output();









}else{
  require 'No tiene permiso para visualizar el reporte.';
}


}
ob_end_flush();
// liberamos el almacenamiento en el buffer
?>