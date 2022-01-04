let tablaUsuarios;
let tablaUserEliminados;
let rowTable = "";
let divLoading = document.querySelector("#divLoading");

document.addEventListener(
	"DOMContentLoaded",
	function () {
		tablaUsuarios = $("#tablaUsuarios").dataTable({
			aProcessing: true,
			aServerSide: true,
			language: {
				url: "//cdn.datatables.net/plug-ins/1.10.20/i18n/Spanish.json",
			},
			ajax: {
				url: " " + base_url + "/Usuarios/obtienerUsuarios",
				dataSrc: "",
			},
			columns: [
				{data: "idusuario"},
				{data: "identificacion"},
				{data: "nombres"},
				{data: "apellidos"},
				{data: "email_user"},
				{data: "nombrerol"},
				{data: "estado"},
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
		// ******CREAR O ACTUALIZAR UN USUARIO*****
		if (document.querySelector("#formUsuario")) {
			let formUsuario = document.querySelector("#formUsuario");
			formUsuario.onsubmit = function (e) {
				e.preventDefault();
				let strIdentificacion = document.querySelector("#identificacion").value;
				let strNombre = document.querySelector("#nombre").value;
				let strApellido = document.querySelector("#apellido").value;
				let strEmail = document.querySelector("#email").value;
				// let  intTelefono = document.querySelector('telefono').value;
				let intTipousuario = document.querySelector("#listaRolid").value;
				let strPassword = document.querySelector("#password").value;
				let strPassword1 = document.querySelector("#password1").value;
				let intStatus = document.querySelector("#listaEstado").value;

				if (
					strIdentificacion == "" ||
					strApellido == "" ||
					strNombre == "" ||
					strEmail == "" ||
					intTipousuario == ""
				) {
					swal("Atención", "Todos los campos son obligatorios.", "error");
					return false;
				}
				if (strPassword != "" || strPassword1 != "") {
					if (strPassword != strPassword1) {
						swal("Atención", "Las contraseñas no son iguales.", "info");
						return false;
					}
					if (strPassword.length < 5) {
						swal("Atención", "La contraseña debe tener un mínimo de 5 caracteres.", "info");
						return false;
					}
				}

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
		// ******ACTUALIZAR  PERFIL*****
		if (document.querySelector("#formPerfil")) {
			let formPerfil = document.querySelector("#formPerfil");
			formPerfil.onsubmit = function (e) {
				e.preventDefault();
				let strIdentificacion = document.querySelector("#Identificacion").value;
				let strNombre = document.querySelector("#Nombre").value;
				let strApellido = document.querySelector("#Apellido").value;
				let intTelefono = document.querySelector("#Telefono").value;
				let strPassword = document.querySelector("#Password").value;
				let strPasswordConfirm = document.querySelector("#PasswordConfirm").value;

				if (strIdentificacion == "" || strApellido == "" || strNombre == "" || intTelefono == "") {
					swal("Atención", "Todos los campos son obligatorios.", "error");
					return false;
				}
				if (strPassword != "" || strPasswordConfirm != "") {
					if (strPassword != strPasswordConfirm) {
						swal("Atención", "Las contraseñas no son iguales.", "info");
						return false;
					}
					if (strPassword.length < 5) {
						swal("Atención", "La contraseña debe tener un mínimo de 5 caracteres.", "info");
						return false;
					}
				}

				let elementsValid = document.getElementsByClassName("valid");
				for (let i = 0; i < elementsValid.length; i++) {
					if (elementsValid[i].classList.contains("is-invalid")) {
						swal("Atención", "Por favor verifique los campos en rojo.", "error");
						return false;
					}
				}
				divLoading.style.display = "flex";
				let ajaxUrl = base_url + "/Usuarios/putPerfil";
				let formData = new FormData(formPerfil);

				fetch(ajaxUrl, {method: "POST", body: formData})
					.then((respuesta) => respuesta.json())
					.then((resp) => {
						if (resp.status) {
							$("#modalFormPerfil").modal("hide");
							swal(
								{
									title: "",
									text: resp.msg,
									type: "success",
									confirmButtonText: "Aceptar",
									closeOnConfirm: false,
								},
								function (isConfirm) {
									if (isConfirm) {
										location.reload();
									}
								}
							);
						} else {
							swal("Error", resp.msg, "error");
						}
						divLoading.style.display = "none";
						return false;
					});
			};
		}

		// ******ACTUALIZAR DATOS FICALES DESDE EL PERFIL*****
		if (document.querySelector("#formDataFiscal")) {
			let formDataFiscal = document.querySelector("#formDataFiscal");
			formDataFiscal.onsubmit = function (e) {
				e.preventDefault();
				let strNit = document.querySelector("#Nit").value;
				let strNombreFiscal = document.querySelector("#NombreFiscal").value;
				let strDirFiscal = document.querySelector("#DirFiscal").value;

				// if(strNit == '' || strNombreFiscal == '' || strDirFiscal == '')
				// {
				//     swal("Atención", "Todos los campos son obligatorios." , "error");
				//     return false;
				// }

				divLoading.style.display = "flex";
				let ajaxUrl = base_url + "/Usuarios/putDFiscal";
				let formData = new FormData(formDataFiscal);

				fetch(ajaxUrl, {method: "POST", body: formData})
					.then((respuesta) => respuesta.json())
					.then((resp) => {
						if (resp.status) {
							$("#modalFormPerfil").modal("hide");
							swal(
								{
									title: "",
									text: resp.msg,
									type: "success",
									confirmButtonText: "Aceptar",
									closeOnConfirm: false,
								},
								function (isConfirm) {
									if (isConfirm) {
										location.reload();
									}
								}
							);
						} else {
							swal("Error", resp.msg, "error");
						}
						divLoading.style.display = "none";
						return false;
					});
			};
		}
	},
	false
);

window.addEventListener(
	"load",
	function () {
		fntRolesUsuario();
	},
	false
);

function fntRolesUsuario() {
	if (document.querySelector("#listaRolid")) {
		let ajaxUrl = base_url + "/Roles/obtRolesSelect";
		fetch(ajaxUrl, {method: "GET"})
			.then((respuesta) => respuesta.text())
			.then((resp) => {
				document.querySelector("#listaRolid").innerHTML = resp;
				$("#listaRolid").selectpicker("render");
			});
	}
}

function funcVerInfo(idpersona) {
	let ajaxUrl = base_url + "/Usuarios/obtenerUsuario/" + idpersona;
	fetch(ajaxUrl, {method: "POST"})
		.then((respuesta) => respuesta.json())
		.then((resp) => {
			if (resp.status) {
				let estadoUsuario =
					resp.data.estado == 1
						? '<span class="badge badge-success">Activo</span>'
						: '<span class="badge badge-danger">Inactivo</span>';

				document.querySelector("#celIdentificacion").innerHTML = resp.data.identificacion;
				document.querySelector("#celNombre").innerHTML = resp.data.nombres;
				document.querySelector("#celApellido").innerHTML = resp.data.apellidos;
				document.querySelector("#celTelefono").innerHTML = resp.data.telefono;
				document.querySelector("#celEmail").innerHTML = resp.data.email_user;
				document.querySelector("#celTipoUsuario").innerHTML = resp.data.nombrerol;
				document.querySelector("#celEstado").innerHTML = estadoUsuario;
				document.querySelector("#celFechaRegistro").innerHTML = resp.data.fechaRegistro;
				$("#modalViewUser").modal("show");
			} else {
				swal("Error", resp.msg, "error");
			}
		});
}

function funcEditarInfo(element, iduser) {
	const correo = document.getElementById("email");
	console.log(correo);
	rowTable = element.parentNode.parentNode.parentNode;
	console.log(rowTable);
	document.querySelector("#titleModal").innerHTML = "Actualizar Usuario";
	document.querySelector(".modal-header").classList.replace("registrar", "actualizar");
	document.querySelector("#btnActionForm").classList.replace("btn-primary", "btn-info");
	document.querySelector("#btnText").innerHTML = "Actualizar";
	document.getElementById("email").setAttribute("readOnly", "true");

	let url = base_url + "/Usuarios/obtenerUsuario/" + iduser;
	fetch(url, {method: "GET"})
		.then((respuesta) => respuesta.json())
		.then((resp) => {
			console.log(resp);
			if (resp.status) {
				document.querySelector("#idUsuario").value = resp.data.idusuario;
				document.querySelector("#identificacion").value = resp.data.identificacion;
				document.querySelector("#nombre").value = resp.data.nombres;
				document.querySelector("#apellido").value = resp.data.apellidos;
				document.querySelector("#email").value = resp.data.email_user;
				document.querySelector("#listaRolid").value = resp.data.idrol;
				$("#listaRolid").selectpicker("render");
				$("#listEstado").selectpicker("render");
			}
		});
	$("#modalUsuario").modal("show");
}

function funcBorrarInfo(element, iduser) {
	rowTable = element.parentNode.parentNode.parentNode;
	//  console.log(rowTable.cells[1].textContent) ;
	let nombre = rowTable.cells[1].textContent;
	let apellido = rowTable.cells[2].textContent;
	swal(
		{
			title: "¿Quieres eliminar el Usuario?",
			text: nombre + " " + apellido,
			type: "warning",
			showCancelButton: true,
			confirmButtonText: "Si, eliminar!",
			cancelButtonText: "No, cancelar!",
			closeOnConfirm: false,
			closeOnCancel: true,
		},
		function (isConfirm) {
			if (isConfirm) {
				let url = base_url + "/Usuarios/delUsuario";
				let data = new FormData();
				data.append("idusuario", iduser);
				fetch(url, {method: "POST", body: data})
					.then((resp) => resp.json())
					.then((resp) => {
						if (resp.status) {
							swal("Eliminar!", resp.msg, "success");
							tablaUsuarios.api().ajax.reload();
						} else {
							swal("Atención!", resp.msg, "error");
						}
					});
			}
		}
	);
}

function funcActivarUsuario(iduser) {
	let url = base_url + "/Usuarios/activarUsuario";
	let data = new FormData();
	data.append("idusuario", iduser);
	fetch(url, {method: "POST", body: data})
		.then((resp) => resp.json())
		.then((resp) => {
			if (resp.status) {
				// sleep(2);
				// tablaUserEliminados.ajax.reload();
				// swal("Activar!", resp.msg, "success");
				// window.location.href = base_url + "/Usuarios/usuarios";
				swal(
					{
						title: "Activar!",
						text: resp.msg,
						type: "success",
						showCancelButton: false,
						confirmButtonText: "Aceptar",
						closeOnConfirm: false,
						closeOnCancel: true,
					},
					function (isConfirm) {
						if (isConfirm) {
							window.location.href = base_url + "/Usuarios/usuarios";
						}
					}
				);
			} else {
				swal("Atención!", resp.msg, "error");
			}
		});
}

function abrirModal() {
	document.querySelector("#idUsuario").value = "";
	document.querySelector(".modal-header").classList.replace("headerUpdate", "headerRegister");
	document.querySelector("#btnActionForm").classList.replace("btn-info", "btn-primary");
	document.querySelector("#btnText").innerHTML = "Guardar";
	document.querySelector("#titleModal").innerHTML = "Nuevo Usuario";
	document.getElementById("email").removeAttribute("readOnly");
	document.querySelector("#formUsuario").reset();
	$("#modalUsuario").modal("show");
}

function openModalPerfil() {
	$("#modalFormPerfil").modal("show");
}
