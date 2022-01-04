var tablaRoles;
var divLoading = document.querySelector("#divLoading"); //Variable para poner una figura mientras se actualiza
document.addEventListener("DOMContentLoaded", function () {
	tablaRoles = $("#tablaRoles").DataTable({
		aProcessing: true,
		aServerSide: true,
		language: {
			url: "http://cdn.datatables.net/plug-ins/1.10.20/i18n/Spanish.json",
		},
		lengthMenu: [
			[5, 10, 20, -1],
			[5, 10, 20, "Todos"],
		],
		iDisplayLength: 5,
		ajax: {
			url: " " + base_url + "/Roles/obtRoles",
			dataSrc: "",
		},
		columns: [
			{data: "idrol"},
			{data: "nombrerol"},
			{data: "descripcion"},
			{data: "status"},
			{data: "opciones"},
		],
	});

	// NUEVO ROL
	var formRol = document.querySelector("#formRol");
	formRol.onsubmit = function (e) {
		e.preventDefault();

		var idRol = document.querySelector("#idRol").value;
		var nombre = document.querySelector("#nombre").value;
		var descripcion = document.querySelector("#descripcion").value;
		var estado = document.querySelector("#listaEstado").value;
		if (nombre == "" || estado == "") {
			swal("Atención", "Todos los campos son obligatorios.", "error");
			return false;
		}
		divLoading.style.display = "flex";
		var ajaxUrl = base_url + "/Roles/modificarRol";
		var formData = new FormData(formRol);

		fetch(ajaxUrl, {method: "POST", body: formData})
			.then((respuesta) => respuesta.json())
			.then((respuesta) => {
				// console.log(respuesta);
				if (respuesta.status) {
					$("#modalRol").modal("hide");
					formRol.reset();
					swal("Roles de usuario", respuesta.msg, "success");
					tablaRoles.ajax.reload();
				} else {
					swal("Error", respuesta.msg, "error");
				}
			});
		divLoading.style.display = "none";
		return false;
	};
});

// $('#tablaRoles').DataTable();

window.addEventListener(
	"load",
	function () {
		/*fntEditRol();
    fntDelRol();
    fntPermisos();*/
	},
	false
);

function funcEditar(idrol) {
	document.querySelector("#titleModal").innerHTML = "Actualizar Rol";
	document.querySelector(".modal-header").classList.replace("registrar", "actualizar");
	document.querySelector("#btnActionForm").classList.replace("btn-primary", "btn-info");
	document.querySelector("#btnText").innerHTML = "Actualizar";

	var idrol = idrol;
	var ajaxUrl = base_url + "/Roles/obtRol/" + idrol;
	fetch(ajaxUrl, {method: "GET"})
		.then((respuesta) => respuesta.json())
		.then((respuesta) => {
			if (respuesta.status) {
				document.querySelector("#idRol").value = respuesta.data.idrol;
				document.querySelector("#nombre").value = respuesta.data.nombrerol;
				document.querySelector("#descripcion").value = respuesta.data.descripcion;

				if (respuesta.data.status == 1) {
					var optionSelect = '<option value="1" selected class="notBlock">Activo</option>';
				} else {
					var optionSelect = '<option value="2" selected class="notBlock">Inactivo</option>';
				}

				var htmlSelect = `${optionSelect}
                                  <option value="1">Activo</option>
                                  <option value="2">Inactivo</option>
                                `;
				document.querySelector("#listaEstado").innerHTML = htmlSelect;
				$("#modalRol").modal("show");
			} else {
				swal("Error", respuesta.msg, "error");
			}
		});
}

function funcBorrar(idrol) {
	swal(
		{
			title: "Eliminar Rol",
			text: "¿Realmente quiere eliminar el Rol?",
			type: "warning",
			showCancelButton: true,
			confirmButtonText: "Si, eliminar!",
			cancelButtonText: "No, cancelar!",
			closeOnConfirm: false,
			closeOnCancel: true,
		},
		function (isConfirm) {
			if (isConfirm) {
				var url = base_url + "/Roles/borrarRol/" + idrol;
				fetch(url, {method: "POST"})
					.then((respuesta) => respuesta.json())
					.then((respuesta) => {
						if (respuesta.status) {
							swal("Eliminar!", respuesta.msg, "success");
							tablaRoles.ajax.reload(function () {});
						} else {
							swal("Atención!", respuesta.msg, "error");
						}
					});
			}
		}
	);
}

function funcPermisos(idrol) {
	var ajaxUrl = base_url + "/Permisos/getPermisosRol/" + idrol;
	fetch(ajaxUrl, {method: "GET"})
		.then((respuesta) => respuesta.text())
		.then((respuesta) => {
			document.querySelector("#contentAjax").innerHTML = respuesta;
			$(".modalPermisos").modal("show");
			document.querySelector("#formPermisos").addEventListener("submit", fntSavePermisos, false);
		});
}

function fntSavePermisos(evnet) {
	evnet.preventDefault();

	var ajaxUrl = base_url + "/Permisos/guardaPermisos";
	var formElement = document.querySelector("#formPermisos");
	var formData = new FormData(formElement);
	fetch(ajaxUrl, {method: "POST", body: formData})
		.then((respuesta) => respuesta.json())
		.then((respuesta) => {
			// var respuesta = JSON.parse(request.responseText);
			if (respuesta.status) {
				swal("Permisos de usuario", respuesta.msg, "success");
			} else {
				swal("Error", respuesta.msg, "error");
			}
		});
}
function abrirModal() {
	document.querySelector("#idRol").value = "";
	document.querySelector(".modal-header").classList.replace("actualizar", "registrar");
	document.querySelector("#btnActionForm").classList.replace("btn-info", "btn-primary");
	document.querySelector("#btnText").innerHTML = "Guardar";
	document.querySelector("#titleModal").innerHTML = "Nuevo Rol";
	document.querySelector("#formRol").reset();
	$("#modalRol").modal("show");
}
