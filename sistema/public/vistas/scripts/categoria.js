
var tabla;

// función que se ejecuta al inicio
function init()
{
	mostrarForm(false);
	listar();

	$("#formulario").on("submit",function(e){

		guardaryeditar(e);
	})
}

function limpiar()
{
	$("#idCategoria").val("");
	$("#nombre").val("");
	$("#descripcion").val("");
}

// mostrar formulario
function mostrarForm(flag)
{
	limpiar();
	if (flag) // si quiero mostrar el formulario
	{
		$("#listadoregistros").hide();
		$("#formularioregistros").show();
		$("#btnGuardar").prop("disabled",false);
	}else{
		$("#listadoregistros").show();
		$("#formularioregistros").hide();
	}

}

function cancelarForm()
{
	limpiar();
	mostrarForm(false);
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
				url:'../ajax/categoria.php?op=listar',
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

function guardaryeditar(e)
{
	e.preventDefault();// no se activará la acción predeterminada del evento
	$("#btnGuardar").prop("disabled",true);
	var formData = new FormData($("#formulario")[0]);

	$.ajax({
		url:"../ajax/categoria.php?op=guardaryeditar",
		type:"POST",
		data:formData,
		contentType:false,
		processData:false,

		success: function(datos){
			bootbox.alert(datos);
			mostrarForm(false);
			tabla.ajax.reload();
		}
		
	});
	limpiar();
}

function mostrar(idCategoria)
{
	$.post("../ajax/categoria.php?op=mostrar",{idCategoria:idCategoria}, function(data,status){
		data = JSON.parse(data);
		mostrarForm(true);

		$("#nombre").val(data.nombre);
		$("#descripcion").val(data.descripcion);
		$("#idCategoria").val(data.idCategoria);

	})
}

function desactivar(idCategoria)
{
	bootbox.confirm("Está seguro de desactivar la Categoría??",function(result){
		if (result){
			$.post("../ajax/categoria.php?op=desactivar",{idCategoria:idCategoria},function(e){
				bootbox.alert(e);
				tabla.ajax.reload();
			});
		}
	})
}

function activar(idCategoria)
{
	bootbox.confirm("Está seguro de Activar la Categoría??",function(result){
		if (result){
			$.post("../ajax/categoria.php?op=activar",{idCategoria:idCategoria},function(e){
				bootbox.alert(e);
				tabla.ajax.reload();
			});
		}
	})
}


init();