let tablaUnidades;
let rowTable = "";
let divLoading = document.querySelector("#divLoading");
document.addEventListener(
	"DOMContentLoaded",
	function () {
		tablaUnidades = $("#tablaUnidades").dataTable({
			aProcessing: true,
			aServerSide: true,
			language: {
				url: "//cdn.datatables.net/plug-ins/1.10.20/i18n/Spanish.json",
			},
			ajax: {
				url: " " + base_url + "/Unidades/getUnidades",
				dataSrc: "",
			},
			columns: [
				{data: "idunidad"},
				{data: "unidad"},
				{data: "descripcion"},
				{data: "estado"},
				{data: "options"},
			],
			dom: "Blfrtip",
			buttons: [
				{
					extend: "copyHtml5",
					text: "<i class='far fa-copy'></i> Copiar",
					titleAttr: "Copiar",
					className: "btn btn-secondary",
				},
				{
					extend: "excelHtml5",
					text: "<i class='fas fa-file-excel'></i> Excel",
					titleAttr: "Esportar a Excel",
					className: "btn btn-success",
				},
				{
					extend: "pdfHtml5",
					text: "<i class='fas fa-file-pdf'></i> PDF",
					titleAttr: "Esportar a PDF",
					className: "btn btn-danger",
				},
				{
					extend: "csvHtml5",
					text: "<i class='fas fa-file-csv'></i> CSV",
					titleAttr: "Esportar a CSV",
					className: "btn btn-info",
				},
			],
			resonsieve: "true",
			bDestroy: true,
			iDisplayLength: 10,
			order: [[0, "desc"]],
		});

		if (document.querySelector("#foto")) {
			let foto = document.querySelector("#foto");
			foto.onchange = function (e) {
				let uploadFoto = document.querySelector("#foto").value;
				let fileimg = document.querySelector("#foto").files;
				let nav = window.URL || window.webkitURL;
				let contactAlert = document.querySelector("#form_alert");
				if (uploadFoto != "") {
					let type = fileimg[0].type;
					let name = fileimg[0].name;
					if (type != "image/jpeg" && type != "image/jpg" && type != "image/png") {
						contactAlert.innerHTML = '<p class="errorArchivo">El archivo no es válido.</p>';
						if (document.querySelector("#img")) {
							document.querySelector("#img").remove();
						}
						document.querySelector(".delPhoto").classList.add("notBlock");
						foto.value = "";
						return false;
					} else {
						contactAlert.innerHTML = "";
						if (document.querySelector("#img")) {
							document.querySelector("#img").remove();
						}
						document.querySelector(".delPhoto").classList.remove("notBlock");
						let objeto_url = nav.createObjectURL(this.files[0]);
						document.querySelector(".prevPhoto div").innerHTML = "<img id='img' src=" + objeto_url + ">";
					}
				} else {
					alert("No selecciono foto");
					if (document.querySelector("#img")) {
						document.querySelector("#img").remove();
					}
				}
			};
		}

		if (document.querySelector(".delPhoto")) {
			let delPhoto = document.querySelector(".delPhoto");
			delPhoto.onclick = function (e) {
				document.querySelector("#foto_remove").value = 1;
				removePhoto();
			};
		}

		//NUEVA Unidad
		if (document.querySelector("#formUnidad")) {
			let formUnidad = document.querySelector("#formUnidad");
			formUnidad.onsubmit = function (e) {
				e.preventDefault();
				let strNombre = document.querySelector("#unidad").value;
				let strDescripcion = document.querySelector("#descripcion").value;

				if (strNombre == "" || strDescripcion == "") {
					swal("Atención", "Todos los campos son obligatorios.", "error");
					return false;
				}
				divLoading.style.display = "flex";

				let url = base_url + "/Unidades/setUnidad";
				let formData = new FormData(formUnidad);

				fetch(url, {method: "POST", body: formData})
					.then((resp) => resp.json())
					.then((resp) => {
						if (resp.status) {
							if (rowTable == "") {
								tablaUnidades.api().ajax.reload();
							} else {
								rowTable.cells[1].textContent = strNombre;
								rowTable.cells[2].textContent = strDescripcion;

								rowTable = "";
							}

							$("#modalFormUnidades").modal("hide");
							formUnidad.reset();
							swal("Unidad", resp.msg, "success");
						} else {
							swal("Error", resp.msg, "error");
						}
					});
				divLoading.style.display = "none";
				return false;
			};
		}
	},
	false
);

function funcVerInfo(idund) {
	let url = base_url + "/Unidades/getUnidad/" + idund;

	fetch(url, {method: "GET"})
		.then((resp) => resp.json())
		.then((resp) => {
			if (resp.status) {
				let estado =
					resp.data.estado == 1
						? '<span class="badge badge-success">Activo</span>'
						: '<span class="badge badge-danger">Inactivo</span>';

				document.querySelector("#celId").innerHTML = resp.data.idunidad;
				document.querySelector("#celNombre").innerHTML = resp.data.unidad;
				document.querySelector("#celDescripcion").innerHTML = resp.data.descripcion;
				document.querySelector("#celEstado").innerHTML = estado;
				// document.querySelector("#imgUnidad").innerHTML = '<img src="' + resp.data.url_portada + '"></img>';
				$("#modalVerDatos").modal("show");
			} else {
				swal("Error", resp.msg, "error");
			}
		});
}

function funcEditarInfo(element, idund) {
	rowTable = element.parentNode.parentNode.parentNode;
	document.querySelector("#titleModal").innerHTML = "Actualizar Categoría";
	document.querySelector(".modal-header").classList.replace("headerRegister", "headerUpdate");
	document.querySelector("#btnActionForm").classList.replace("btn-primary", "btn-info");
	document.querySelector("#btnText").innerHTML = "Actualizar";

	let url = base_url + "/Unidades/getUnidad/" + idund;

	fetch(url, {method: "GET"})
		.then((resp) => resp.json())
		.then((resp) => {
			if (resp.status) {
				document.querySelector("#idUnidad").value = resp.data.idunidad;
				document.querySelector("#unidad").value = resp.data.unidad;
				document.querySelector("#descripcion").value = resp.data.descripcion;

				// if (resp.data.status == 1) {
				// 	document.querySelector("#listStatus").value = 1;
				// } else {
				// 	document.querySelector("#listStatus").value = 2;
				// }
				// $("#listStatus").selectpicker("render");

				$("#modalFormUnidades").modal("show");
			} else {
				swal("Error", resp.msg, "error");
			}
		});
}

function funcBorrarInfo(idund) {
	swal(
		{
			title: "Eliminar Categoría",
			text: "¿Realmente quiere eliminar al categoría?",
			type: "warning",
			showCancelButton: true,
			confirmButtonText: "Si, eliminar!",
			cancelButtonText: "No, cancelar!",
			closeOnConfirm: false,
			closeOnCancel: true,
		},
		function (isConfirm) {
			if (isConfirm) {
				let url = base_url + "/Unidades/delUnidad";
				let data = new FormData();
				data.append("idund", idund);
				fetch(url, {method: "POST", body: data})
					.then((resp) => resp.json())
					.then((resp) => {
						if (resp.status) {
							swal("Eliminar!", resp.msg, "success");
							tablaUnidades.api().ajax.reload();
						} else {
							swal("Atención!", resp.msg, "error");
						}
					});
			}
		}
	);
}

// function funcActivarUnidad(idund) {
// 	let ajaxUrl = base_url + "/Unidades/activarUnidad/";
// 	let data = new FormData();
// 	data.append("idunidad", idund);
// 	fetch(ajaxUrl, {method: "POST", body: data})
// 		.then((resp) => resp.json())
// 		.then((resp) => {
// 			if (resp.status) {
// 				swal("Activar!", resp.msg, "success");
// 				tableCategorias.api().ajax.reload();
// 			} else {
// 				swal("Atención!", resp.msg, "error");
// 			}
// 		});
// }
function funcActivarUnidad(idund) {
	let ajaxUrl = base_url + "/Unidades/activarUnidad/";
	let data = new FormData();
	data.append("idunidad", idund);
	fetch(ajaxUrl, {method: "POST", body: data})
		.then((resp) => resp.json())
		.then((resp) => {
			if (resp.status) {
				// swal("Activar!", resp.msg, "success");
				// tableCategorias.api().ajax.reload();
				swal(
					{
						title: "Activar!",
						text: resp.msg,
						type: "success",
						showCancelButton: false,
						confirmButtonText: "Aceptar",
						// cancelButtonText: "No, cancelar!",
						closeOnConfirm: false,
						closeOnCancel: true,
					},
					function (isConfirm) {
						if (isConfirm) {
							window.location.href = base_url + "/Unidades/unidades";
						}
					}
				);
			} else {
				swal("Atención!", resp.msg, "error");
			}
		});
}

function openModal() {
	rowTable = "";
	document.querySelector("#idUnidad").value = "";
	document.querySelector(".modal-header").classList.replace("headerUpdate", "headerRegister");
	document.querySelector("#btnActionForm").classList.replace("btn-info", "btn-primary");
	document.querySelector("#btnText").innerHTML = "Guardar";
	document.querySelector("#titleModal").innerHTML = "Nueva Categoría";
	document.querySelector("#formUnidad").reset();
	$("#modalFormUnidades").modal("show");
	// removePhoto();
}
