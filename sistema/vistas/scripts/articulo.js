
var tabla;

// función que se ejecuta al inicio
function init()
{
	mostrarForm(false);
	listar();

	$("#formulario").on("submit",function(e){

		guardaryeditar(e);
	})

	//cargamos los items al select categoria
	$.post("../ajax/articulo.php?op=selectCategoria",function(r){
		$("#idCategoria").html(r);
		$("#idCategoria").selectpicker('refresh');
	});

	$("#imagenmuestra").hide();



}

function limpiar()
{
	$("#codigo").val("");
	$("#nombre").val("");
	$("#descripcion").val("");
	$("#stock").val("");
	$("#imagenmuestra").attr("src","");
	$("#imagenactual").val("");
	$("#print").hide();
	$("#idarticulo").val("");
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
		$("#btnagregar").hide();
	}else{
		$("#listadoregistros").show();
		$("#formularioregistros").hide();
		$("#btnagregar").show();
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
				url:'../ajax/articulo.php?op=listar',
				type:"get",
				dataType:"json",
				error:function(e){
					console.log(e.responseText) // lo muestra en chrome
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
		url:"../ajax/articulo.php?op=guardaryeditar",
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

function mostrar(idarticulo)
{
	$.post("../ajax/articulo.php?op=mostrar",{idarticulo:idarticulo}, function(data,status){
		data = JSON.parse(data);
		mostrarForm(true);


		$("#idCategoria").val(data.idCategoria);
		$("#codigo").val(data.codigo);
		$("#nombre").val(data.nombre);
		$("#stock").val(data.stock);
		$("#descripcion").val(data.descripcion);
		$("#imagenmuestra").show();
		$("#imagenmuestra").attr("src","../files/articulos/"+data.imagen);
		$("#imagenactual").val(data.imagen);
		$("#idarticulo").val(data.idarticulo);
		generarBarcode();
	})
}

function desactivar(idarticulo)
{
	bootbox.confirm("Está seguro de desactivar el Articulo??",function(result){
		if (result){
			$.post("../ajax/articulo.php?op=desactivar",{idarticulo:idarticulo},function(e){
				bootbox.alert(e);
				tabla.ajax.reload();
			});
		}
	})
}

function activar(idarticulo)
{
	bootbox.confirm("Está seguro de Activar el Articulo??",function(result){
		if (result){
			$.post("../ajax/articulo.php?op=activar",{idarticulo:idarticulo},function(e){
				bootbox.alert(e);
				tabla.ajax.reload();
			});
		}
	})
}

function generarBarcode()
{
	codigo = $("#codigo").val();
	JsBarcode("#barcode",codigo);
	$("#print").show();
}

function imprimir()
{
	$("#print").printArea();



}
init();
