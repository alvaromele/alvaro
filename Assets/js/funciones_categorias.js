let tableCategorias;
let rowTable = "";
let divLoading = document.querySelector("#divLoading");
document.addEventListener(
	"DOMContentLoaded",
	function () {
		tableCategorias = $("#tableCategorias").dataTable({
			aProcessing: true,
			aServerSide: true,
			language: {
				url: "//cdn.datatables.net/plug-ins/1.10.20/i18n/Spanish.json",
			},
			ajax: {
				url: " " + base_url + "/Categorias/getCategorias",
				dataSrc: "",
			},
			columns: [
				{data: "idcategoria"},
				{data: "nombre"},
				{data: "descripcion"},
				{data: "status"},
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

		//NUEVA CATEGORIA
		if (document.querySelector("#formCategoria")) {
			let formCategoria = document.querySelector("#formCategoria");
			formCategoria.onsubmit = function (e) {
				e.preventDefault();
				let strNombre = document.querySelector("#txtNombre").value;
				let strDescripcion = document.querySelector("#txtDescripcion").value;
				let intStatus = document.querySelector("#listStatus").value;
				if (strNombre == "" || intStatus == "") {
					swal("Atención", "Todos los campos son obligatorios.", "error");
					return false;
				}
				divLoading.style.display = "flex";

				let ajaxUrl = base_url + "/Categorias/setCategoria";
				let formData = new FormData(formCategoria);

				fetch(ajaxUrl, {method: "POST", body: formData})
					.then((resp) => resp.json())
					.then((resp) => {
						if (resp.status) {
							if (rowTable == "") {
								tableCategorias.api().ajax.reload();
							} else {
								htmlStatus =
									intStatus == 1
										? '<span class="badge badge-success">Activo</span>'
										: '<span class="badge badge-danger">Inactivo</span>';
								rowTable.cells[1].textContent = strNombre;
								rowTable.cells[2].textContent = strDescripcion;
								rowTable.cells[3].innerHTML = htmlStatus;
								rowTable = "";
							}

							$("#modalFormCategorias").modal("hide");
							formCategoria.reset();
							swal("Categoria", resp.msg, "success");
							removePhoto();
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

function funcVerInfo(idcategoria) {
	let ajaxUrl = base_url + "/Categorias/getCategoria/" + idcategoria;

	fetch(ajaxUrl, {method: "GET"})
		.then((resp) => resp.json())
		.then((resp) => {
			if (resp.status) {
				let estado =
					resp.data.status == 1
						? '<span class="badge badge-success">Activo</span>'
						: '<span class="badge badge-danger">Inactivo</span>';
				document.querySelector("#celId").innerHTML = resp.data.idcategoria;
				document.querySelector("#celNombre").innerHTML = resp.data.nombre;
				document.querySelector("#celDescripcion").innerHTML = resp.data.descripcion;
				document.querySelector("#celEstado").innerHTML = estado;
				document.querySelector("#imgCategoria").innerHTML = '<img src="' + resp.data.url_portada + '"></img>';
				$("#modalViewCategoria").modal("show");
			} else {
				swal("Error", resp.msg, "error");
			}
		});
}

function funcEditarInfo(element, idcategoria) {
	rowTable = element.parentNode.parentNode.parentNode;
	document.querySelector("#titleModal").innerHTML = "Actualizar Categoría";
	document.querySelector(".modal-header").classList.replace("headerRegister", "headerUpdate");
	document.querySelector("#btnActionForm").classList.replace("btn-primary", "btn-info");
	document.querySelector("#btnText").innerHTML = "Actualizar";

	let ajaxUrl = base_url + "/Categorias/getCategoria/" + idcategoria;

	fetch(ajaxUrl, {method: "GET"})
		.then((resp) => resp.json())
		.then((resp) => {
			if (resp.status) {
				document.querySelector("#idCategoria").value = resp.data.idcategoria;
				document.querySelector("#txtNombre").value = resp.data.nombre;
				document.querySelector("#txtDescripcion").value = resp.data.descripcion;
				document.querySelector("#foto_actual").value = resp.data.portada;
				document.querySelector("#foto_remove").value = 0;

				if (resp.data.status == 1) {
					document.querySelector("#listStatus").value = 1;
				} else {
					document.querySelector("#listStatus").value = 2;
				}
				$("#listStatus").selectpicker("render");

				if (document.querySelector("#img")) {
					document.querySelector("#img").src = resp.data.url_portada;
				} else {
					document.querySelector(".prevPhoto div").innerHTML =
						"<img id='img' src=" + resp.data.url_portada + ">";
				}

				if (resp.data.portada == "portada_categoria.png") {
					document.querySelector(".delPhoto").classList.add("notBlock");
				} else {
					document.querySelector(".delPhoto").classList.remove("notBlock");
				}

				$("#modalFormCategorias").modal("show");
			} else {
				swal("Error", resp.msg, "error");
			}
		});
}

function funcBorrarInfo(idcat) {
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
				let ajaxUrl = base_url + "/Categorias/delCategoria";
				let data = new FormData();
				data.append("idCategoria", idcat);
				fetch(ajaxUrl, {method: "POST", body: data})
					.then((resp) => resp.json())
					.then((resp) => {
						if (resp.status) {
							swal("Eliminar!", resp.msg, "success");
							tableCategorias.api().ajax.reload();
						} else {
							swal("Atención!", resp.msg, "error");
						}
					});
			}
		}
	);
}

function funcActivarCategoria(idcat) {
	let ajaxUrl = base_url + "/Categorias/activarCategoria/";
	let data = new FormData();
	data.append("idcategoria", idcat);
	fetch(ajaxUrl, {method: "POST", body: data})
		.then((resp) => resp.json())
		.then((resp) => {
			if (resp.status) {
				swal("Activar!", resp.msg, "success");
				tableCategorias.api().ajax.reload();
			} else {
				swal("Atención!", resp.msg, "error");
			}
		});
}

function removePhoto() {
	document.querySelector("#foto").value = "";
	document.querySelector(".delPhoto").classList.add("notBlock");
	if (document.querySelector("#img")) {
		document.querySelector("#img").remove();
	}
}

function openModal() {
	rowTable = "";
	document.querySelector("#idCategoria").value = "";
	document.querySelector(".modal-header").classList.replace("headerUpdate", "headerRegister");
	document.querySelector("#btnActionForm").classList.replace("btn-info", "btn-primary");
	document.querySelector("#btnText").innerHTML = "Guardar";
	document.querySelector("#titleModal").innerHTML = "Nueva Categoría";
	document.querySelector("#formCategoria").reset();
	$("#modalFormCategorias").modal("show");
	removePhoto();
}
