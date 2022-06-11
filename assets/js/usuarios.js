$(document).ready(function () {
	usuarios();
});

function usuarios(){
	$.ajax({
		url: "assets/php/consulta_usuarios.php",
		type: "POST",
		success: function(data){
			$(".usuarios_caja").html(data);
		}
	});
}


function nuevo_usuario(){
	$.ajax({
		url: "assets/php/nuevo_usuario.php",
		type: "POST",
		success: function(data){
			Swal.fire({
				html: data,
				allowOutsideClick: false,
				showConfirmButton: false
			});
			select_estilo();
			guardar_usuario();
		}
	});
}

function nuevo_rol(){
	$.ajax({
		url: "assets/php/nuevo_rol.php",
		type: "POST",
		success: function(data){
			Swal.fire({
				html: data,
				allowOutsideClick: false,
				showConfirmButton: false
			});

			$(".checklist").perfectScrollbar();
			guardar_rol();
		}
	});
}


function guardar_rol(){
	$("#form").submit(function(e){
		e.preventDefault();
		var selected = [];
		$('.checklist input:checked').each(function() {
			selected.push($(this).val());
		});

		$.ajax({
			url: "assets/php/agregar_rol.php",
			type: "POST",
			data: {
				autorizaciones: selected,
				nombre: $("#nombre").val()
			},
			success: function(data){
				if(data == 1){
					md.showNotification("top", "right", "Rol creado correctamente.");
					Swal.close();
				}else{
					md.showNotification("top", "right", data);
				}
			}
		});
		
	})
}

function guardar_usuario(){
	$("#form").submit(function(e){
		e.preventDefault();
		$.ajax({
			url: "assets/php/agregar_usuario.php",
			type: "POST",
			data: {
				nombre: $("#nombre").val(),
				alias: $("#alias").val(),
				email: $("#email").val(),
				rol: $("#rol").val()
			},
			success: function(data){
				if(data == 1){
					md.showNotification("top", "right", "Usuario creado correctamente.");
					usuarios();
					Swal.close();
				}else{
					md.showNotification("top", "right", data);
				}
			}
		});
		
	})
}