let tablaProveedores;
let rowTable = "";
let divLoading = document.querySelector("#divLoading");

document.addEventListener(
	"DOMContentLoaded",
	function () {
		tablaProveedores = $("#tablaProveedores").dataTable({
			aProcessing: true,
			aServerSide: true,
			language: {
				url: "//cdn.datatables.net/plug-ins/1.10.20/i18n/Spanish.json",
			},
			ajax: {
				url: " " + base_url + "/Proveedores/obtienerUsuarios",
				dataSrc: "",
			},
			columns: [
				{data: "idusuario"},
				{data: "identificacion"},
				{data: "nombrefiscal"},
				// {data: "apellidos"},
				{data: "email_user"},
				{data: "telefono"},
				// {data: "estado"},
				{data: "opciones"},
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
			iDisplayLength: 5,
			order: [[0, "desc"]],
		});
	},
	false
);

// ******CREAR O ACTUALIZAR UN USUARIO*****
if (document.querySelector("#formUsuario")) {
	let formUsuario = document.querySelector("#formUsuario");
	formUsuario.onsubmit = function (e) {
		e.preventDefault();
		let strIdentificacion = document.querySelector("#txtIdentificacion").value;
		let strNombre = document.querySelector("#txtNombre").value;
		let strApellido = document.querySelector("#txtDireccion").value;
		let strEmail = document.querySelector("#txtEmail").value;
		let strTelefono = document.querySelector("#txtTelefono").value;

		// let strPassword = document.querySelector("#password").value;
		// let strPassword1 = document.querySelector("#password1").value;

		if (
			strIdentificacion == "" ||
			strNombre == "" ||
			strNombre == "" ||
			strEmail == "" ||
			strTelefono == ""
		) {
			swal("Atención", "Todos los campos son obligatorios.", "error");
			return false;
		}
		// if (strPassword != "" || strPassword1 != "") {
		// 	if (strPassword != strPassword1) {
		// 		swal("Atención", "Las contraseñas no son iguales.", "info");
		// 		return false;
		// 	}
		// 	if (strPassword.length < 5) {
		// 		swal("Atención", "La contraseña debe tener un mínimo de 5 caracteres.", "info");
		// 		return false;
		// 	}
		// }

		let elementsValid = document.getElementsByClassName("valid");
		for (let i = 0; i < elementsValid.length; i++) {
			if (elementsValid[i].classList.contains("is-invalid")) {
				swal("Atención", "Por favor verifique los campos en rojo.", "error");
				return false;
			}
		}
		let ajaxUrl = base_url + "/Usuarios/modificarUsuario";
		let formData = new FormData(formUsuario);
		divLoading.style.display = "flex";
		fetch(ajaxUrl, {method: "POST", body: formData})
			.then((respuesta) => respuesta.json())
			.then((resp) => {
				if (resp.status) {
					if (rowTable == "") {
						tablaUsuarios.api().ajax.reload();
					} else {
						htmlStatus =
							intStatus == 1
								? '<span class="badge badge-success">Activo</span>'
								: '<span class="badge badge-danger">Inactivo</span>';
						rowTable.cells[1].textContent = strNombre;
						rowTable.cells[2].textContent = strApellido;
						rowTable.cells[3].textContent = strEmail;
						// rowTable.cells[4].textContent = intTelefono;
						rowTable.cells[5].textContent = document.querySelector("#listaRolid").selectedOptions[0].text;
						rowTable.cells[6].innerHTML = htmlStatus;
						rowTable = "";
					}
					$("#modalUsuario").modal("hide");
					formUsuario.reset();
					swal("Usuarios", resp.msg, "success");
				} else {
					swal("Error", resp.msg, "error");
				}
			});
		divLoading.style.display = "none";
		return false;
	};
}

function funcEditarInfo(element, idpersona) {
	rowTable = element.parentNode.parentNode.parentNode;
	console.log(rowTable);
	document.querySelector("#titleModal").innerHTML = "Actualizar Proveedor";
	document.querySelector(".modal-header").classList.replace("registrar", "actualizar");
	document.querySelector("#btnActionForm").classList.replace("btn-primary", "btn-info");
	document.querySelector("#btnText").innerHTML = "Actualizar";
	let ajaxUrl = base_url + "/Proveedores/getUsuario/" + idpersona;
	fetch(ajaxUrl, {method: "GET"})
		.then((respuesta) => respuesta.json())
		.then((objData) => {
			console.log(objData);
			if (objData.status) {
				document.querySelector("#idUsuario").value = objData.data.idusuario;
				document.querySelector("#txtIdentificacion").value = objData.data.identificacion;
				document.querySelector("#txtNombre").value = objData.data.nombrefiscal;
				document.querySelector("#txtDireccion").value = objData.data.direccionfiscal;
				document.querySelector("#txtTelefono").value = objData.data.telefono;
				document.querySelector("#txtEmail").value = objData.data.email_user;
				document.querySelector("#txtNombreCont").value = objData.data.contacto;
				document.querySelector("#txtEmailCont").value = objData.data.emailcontacto;
				document.querySelector("#txtTelefonoCont").value = objData.data.telcontacto;
			}
		});
	$("#modalProveedor").modal("show");
}

function funcVerInfo(idpersona) {
	let ajaxUrl = base_url + "/Proveedores/obtenerUsuario/" + idpersona;
	fetch(ajaxUrl, {method: "POST"})
		.then((respuesta) => respuesta.json())
		.then((resp) => {
			if (resp.status) {
				let estadoUsuario =
					resp.data.estado == 1
						? '<span class="badge badge-success">Activo</span>'
						: '<span class="badge badge-danger">Inactivo</span>';

				document.querySelector("#celIdentificacion").innerHTML = resp.data.identificacion;
				document.querySelector("#celNombre").innerHTML = resp.data.nombrefiscal;
				document.querySelector("#celEmail").innerHTML = resp.data.email_user;
				document.querySelector("#celTelefono").innerHTML = resp.data.telefono;
				document.querySelector("#celDireccion").innerHTML = resp.data.direccionfiscal;

				document.querySelector("#celContacto").innerHTML = resp.data.contacto;
				document.querySelector("#celEmailContacto").innerHTML = resp.data.emailcontacto;
				document.querySelector("#celTelefonoContacto").innerHTML = resp.data.telcontacto;

				document.querySelector("#celEstado").innerHTML = estadoUsuario;
				document.querySelector("#celFechaRegistro").innerHTML = resp.data.fechaRegistro;
				$("#modalViewUser").modal("show");
			} else {
				swal("Error", resp.msg, "error");
			}
		});
}

function abrirModal() {
	document.querySelector("#idUsuario").value = "";
	document.querySelector(".modal-header").classList.replace("actualizar", "registrar");
	document.querySelector("#btnActionForm").classList.replace("btn-info", "btn-primary");
	document.querySelector("#btnText").innerHTML = "Guardar";
	document.querySelector("#titleModal").innerHTML = "Nuevo Proveedor";
	document.querySelector("#formUsuario").reset();
	$("#modalProveedor").modal("show");
}
