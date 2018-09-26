
var tabla;

// función que se ejecuta al inicio
function init()
{
	mostrarForm(false);
	listar();

	$("#formulario").on("submit",function(e){
		guardaryeditar(e);
	})
	$("#imagenmuestra").hide();

	// mostramos los permisos
    $.post("../ajax/usuario.php?op=permisos&id=", function(r){
	 	$("#permisos").html(r);
	});
}

function limpiar()
{
	$("#nombre").val("");
	$("#num_documento").val("");
	$("#direccion").val("");
	$("#telefono").val("");
	$("#mail").val("");
	$("#cargo").val("");
	$("#login").val("");
	$("#clave").val("");
	$("#imagenmuestra").attr("src","");
	$("#imagenactual").val("");
	$("#idUsuario").val("");
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
				url:'../ajax/usuario.php?op=listar',
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
		url:"../ajax/usuario.php?op=guardaryeditar",
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

function mostrar(idUsuario)
{
	$.post("../ajax/usuario.php?op=mostrar",{ idUsuario : idUsuario }, function(data,status){
		data = JSON.parse(data);
		mostrarForm(true);

		$("#nombre").val(data.nombre);
		$("#tipo_documento").val(data.tipo_documento);
		$("#tipo_documento").selectpicker('refresh');
		$("#num_documento").val(data.num_documento);
		$("#direccion").val(data.direccion);
		$("#telefono").val(data.telefono);
		$("#mail").val(data.mail);
		$("#cargo").val(data.cargo);
		$("#login").val(data.login);
		$("#clave").val(data.clave);
		$("#imagenmuestra").show();
		$("#imagenmuestra").attr("src","../files/usuarios/"+data.imagen);
		$("#imagenactual").val(data.imagen);
		$("#idUsuario").val(data.idUsuario);
	});

    $.post("../ajax/usuario.php?op=permisos&id="+idUsuario, function(r){
	 	$("#permisos").html(r);
	});
}

function desactivar(idUsuario)
{
	bootbox.confirm("Está seguro de desactivar el Usuario??",function(result){
		if (result){
			$.post("../ajax/usuario.php?op=desactivar",{idUsuario:idUsuario},function(e){
				bootbox.alert(e);
				tabla.ajax.reload();
			});
		}
	})
}

function activar(idUsuario)
{
	bootbox.confirm("Está seguro de Activar el Usuario??",function(result){
		if (result){
			$.post("../ajax/usuario.php?op=activar",{idUsuario:idUsuario},function(e){
				bootbox.alert(e);
				tabla.ajax.reload();
			});
		}
	})
}

init();