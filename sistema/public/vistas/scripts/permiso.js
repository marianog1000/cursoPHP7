
var tabla;

// función que se ejecuta al inicio
function init()
{
	mostrarForm(false);
	listar();
}

// mostrar formulario
function mostrarForm(flag)
{
	if (flag) // si quiero mostrar el formulario
	{
		$("#listadoregistros").hide();
		$("#formularioregistros").show();
		$("#btnGuardar").prop("disabled",false);
		$("#btnAgregar").hide();
	}else{
		$("#listadoregistros").show();
		$("#formularioregistros").hide();
		$("#btnAgregar").hide();
	}

}

function listar()
{
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
				url:'../ajax/permiso.php?op=listar',
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