
var tabla;

// función que se ejecuta al inicio
function init()
{
	mostrarForm(false);
	listar();

	$("#formulario").on("submit",function(e){

		guardaryeditar(e);
	});

	$.post("../ajax/venta.php?op=selectVendedor",function(r){
		$("#idCliente").html(r);
		$("#idCliente").selectpicker('refresh');
	});


}

function limpiar()
{
	$("#idVendedor").val("");
	$("#vendedor").val("");
	$("#serie_comprobante").val("");
	$("#num_comprobante").val("");
	$("#fecha_hora").val("");
	$("#impuesto").val("");

	$("#total_venta").val("");
	$(".filas").remove();
	$("#total").html("0");

	var now = new Date();
	var day = ("0" + now.getDate()).slice(-2);
	var month = ("0" + (now.getMonth() +1 )).slice(-2);
	var today = now.getFullYear() +"-"+(month)+"-"+(day);
	$("#fecha_hora").val(today);


	$("#tipo_comprobante").val("Boleta");
	$("#tipo_comprobante").selectpicker("refresh");
}

// mostrar formulario
function mostrarForm(flag)
{
	limpiar();
	if (flag) // si quiero mostrar el formulario
	{
		$("#listadoregistros").hide();
		$("#formularioregistros").show();
		//$("#btnGuardar").prop("disabled",false);
		$("#btnagregar").hide();
		listarArticulos();


		$("#btnGuardar").hide();
		$("#btnCancelar").show();
		detalles=0;
		$("#btnAgregarArt").show();
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
				url:'../ajax/venta.php?op=listar',
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


function listarArticulos()
{
	tabla=$('#tblarticulos').dataTable(
	{
		"aProcessing":true, // activamos el procesamiento del datatables
		"aServerSide":true,// paginación y filtrado realizados por el servidor
		dom: 'Bfrtip', // definimos los elementos del control de tabla
		buttons:[

			],
		"ajax":
			{
				url:'../ajax/venta.php?op=listarArticulos',
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
	//$("#btnGuardar").prop("disabled",true);
	var formData = new FormData($("#formulario")[0]);

	$.ajax({
		url:"../ajax/venta.php?op=guardaryeditar",
		type:"POST",
		data:formData,
		contentType:false,
		processData:false,

		success: function(datos){
			bootbox.alert(datos);
			mostrarForm(false);
			listar();
		}
		
	});
	limpiar();
}

function mostrar(idVenta)
{
	$.post("../ajax/venta.php?op=mostrar",{idVenta:idVenta}, function(data,status){
		data = JSON.parse(data);
		mostrarForm(true);


		$("#idVendedor").val(data.idVendedor);
		$("#idVendedor").selectpicker('refresh');
		$("#tipo_comprobante").val(data.tipo_comprobante);		
		$("#tipo_comprobante").selectpicker('refresh');
		$("#serie_comprobante").val(data.serie_comprobante);
		$("#num_comprobante").val(data.num_comprobante);
		$("#fecha_hora").val(data.fecha_hora);
		$("#impuesto").val(data.impuesto);
		$("#idVenta").val(data.idVenta);

		$("#btnGuardar").hide();
		$("#btnCancelar").show();
		$("#btnAgregarArt").hide();		
	});

	$.post("../ajax/venta.php?op=listarDetalle&id="+idVenta, function(r){
		$("#detalles").html(r);
	});

}

function anular(idVenta)
{
	bootbox.confirm("Está seguro de Anular la Venta??",function(result){
		if (result){
			$.post("../ajax/venta.php?op=anular",{idVenta:idVenta},function(e){
				bootbox.alert(e);
				tabla.ajax.reload();
			});
		}
	})
}

// Declaración de variables necesarias para trabajar con las compras y sus detalles
var impuesto = 18;
var cont=0;
var detalles=0;

$("#btnGuardar").hide();
$("#tipo_comprobante").change(marcarImpuesto);

function marcarImpuesto(){
	var tipo_comprobante = $("#tipo_comprobante option:selected").text();
	if(tipo_comprobante=='Factura'){
		$("#impuesto").val(impuesto);


	}else{
		$("#impuesto").val("0");
	}
}


function agregarDetalle(idArticulo,articulo)
{
	var cantidad=1;
	var precio_venta=1;
	var descuento = 0;

	if(idArticulo!=""){
		var subtotal = (cantidad * precio_venta) - descuento;
		var fila='<tr class="filas" id="fila'+cont+'">'+
			'<td><button class="btn btn-danger" onclick="eliminarDetalle('+ cont +')">X</button></td>'+
			'<td><input type="hidden" name="idArticulo[]" value="'+idArticulo+'">'+articulo+'</input></td>'+
			'<td><input type="number" name="cantidad[]" id="cantidad[]" value="'+cantidad+'"></input></td>'+
			'<td><input type="number" name="precio_venta[]" id="precio_venta[]" value="'+precio_venta+'"></td>'+
			'<td><input type="number" name="descuento[]" value="'+descuento+'"></td>'+
			'<td><span name="subtotal" id="subtotal" '+cont+'>'+subtotal+'</span></td>'+
			'<td><button type="button" onclick="modificarSubtotales()" class="btn btn-info">'+
				'<i class="fa fa-refresh"></i></button></td>';
			'</tr>';
			cont++;
			detalles++;
			$('#detalles').append(fila);
			modificarSubtotales();

	}else{
		alert("Error al ingresar el detalle, revisar los datos del artículo.");


	}
}

function modificarSubtotales()
{
 	var cant = document.getElementsByName("cantidad[]");
 	var prec = document.getElementsByName("precio_venta[]");
 	var desc = document.getElementsByName("descuento[]");
 	var sub = document.getElementsByName("subtotal");

 	for(var i=0;i<cant.length;i++){
 		var inpC = cant[i];
 		var inpP=prec[i];
 		var inpS = sub[i];
 		var inpD =  desc[i];

 		inpS.value = (inpC.value * inpP.value) - inpD.value;
 		document.getElementsByName("subtotal")[i].innerHTML = inpS.value;
 	}
 	calcularTotales();
}


function calcularTotales()
{

	var sub = document.getElementsByName("subtotal");
	var total=0.0;

	for (var i = 0; i < sub.length; i++) {
		total +=document.getElementsByName("subtotal")[i].value;
	}
	$("#total").html("S/. " + total);
	$("#total_venta").val(total);
	evaluar();
}


function evaluar()
{
	if (detalles>0)
		$("#btnGuardar").show();
	else{
		$("#btnGuardar").hide();
		cont=0;
	}
}


function eliminarDetalle(indice)
{
	$("#fila" + indice).remove();
	calcularTotales();
	detalles=detalles-1;
}


init();
