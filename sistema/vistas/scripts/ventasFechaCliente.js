
var tabla;

// función que se ejecuta al inicio
function init()
{
	listar();

	$.post("../ajax/venta.php?op=selectVendedor",function(r){
		$("#idCliente").html(r);
		$("#idCliente").selectpicker('refresh');
	});
}

function listar()
{

	var fecha_inicio = $("#fecha_inicio").val();
	var fecha_fin = $("#fecha_fin").val();
	var idCliente = $("#idCliente").val();

	tabla=$('#tbllistado').dataTable(
	{
		"aProcessing":true, // activamos el procesamiento del datatables
		"aServerSide":true,// paginación y filtrado realizados por el servidor
		dom: 'Bfrtip', // definimos los elementos del control de tabla
		buttons:[
				'copyHtml5',
				'excelHtml5',
				'csvHtml5',
				'pdf'
			],
		"ajax":
			{
				url:'../ajax/consultas.php?op=ventasFecha',
				data:{fecha_inicio: fecha_inicio, fecha_fin: fecha_fin, idCliente:idCliente},
				type:"get",
				dataType:"json",
				error:function(e){
					console.log(e.responseText); // lo muestra en chrome
				}
			},
		"bDestroy":true,
		"iDisplayLength":5, // paginacion
		"order":[[ 0, "desc" ]] // ordenar (columna, orden)

	}).DataTable();
}

init();