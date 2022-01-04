$('.login-content [data-toggle="flip"]').click(function () {
	$(".login-box").toggleClass("flipped");
	return false;
});

var divLoading = document.querySelector("#divLoading");
document.addEventListener(
	"DOMContentLoaded",
	function () {
		//*********CONFIRMA USUARIO Y CONTRASEÑA **********/
		if (document.querySelector("#formLogin")) {
			let formLogin = document.querySelector("#formLogin");
			formLogin.onsubmit = function (e) {
				e.preventDefault();

				let strEmail = document.querySelector("#txtEmail").value;
				let strPassword = document.querySelector("#txtPassword").value;

				if (strEmail == "" || strPassword == "") {
					swal("Por favor", "Escribe usuario y contraseñaa.", "error");
					return false;
				} else {
					divLoading.style.display = "flex";
					var ajaxUrl = base_url + "/Login/loginUser";
					var formData = new FormData(formLogin);
					fetch(ajaxUrl, {method: "POST", body: formData})
						.then((respuesta) => respuesta.json())
						.then((objData) => {
							// console.log(objData)
							if (objData) {
								if (objData.status) {
									//window.location = base_url+'/dashboard';
									swal("Compras & Inventario", "BIENVENIDO", "success");
									window.location.reload(false);
								} else {
									swal("Atención", objData.msg, "error");
									document.querySelector("#txtPassword").value = "";
								}
							} else {
								swal("Atención", "Error en el proceso", "error");
							}
						});
				}
				divLoading.style.display = "none";
				return false;
			};
		}
		//*********CONFIRMA EL OLVIDO DE LA CONTRASEÑA **********/
		if (document.querySelector("#formRecetPass")) {
			let formLogin = document.querySelector("#formRecetPass");
			formLogin.onsubmit = function (e) {
				e.preventDefault();

				let strEmail = document.querySelector("#txtEmailReset").value;

				if (strEmail == "") {
					swal("Por favor", "Escribe tu correo electrónico.", "error");
					return false;
				} else {
					divLoading.style.display = "flex";

					var ajaxUrl = base_url + "/Login/resetPass";
					var formData = new FormData(formRecetPass);

					fetch(ajaxUrl, {method: "POST", body: formData})
						.then((respuesta) => respuesta.json())
						.then((objData) => {
							// console.log(objData)
							if (objData) {
								if (objData.status) {
									swal(
										{
											title: "",
											text: objData.msg,
											type: "success",
											confirmButtonText: "Aceptar",
											closeOnConfirm: false,
										},
										function (isConfirm) {
											if (isConfirm) {
												window.location = base_url;
											}
										}
									);
								} else {
									swal("Atención", objData.msg, "error");
									return false;
								}
							} else {
								swal("Atención", "Error en el proceso", "error");
							}
						});
					divLoading.display = "none";
				}
			};
		}

		//*********CAMBIA CONTRASEÑA AL SER OLVIDADA**********/
		if (document.querySelector("#formCambiarPass")) {
			let formCambiarPass = document.querySelector("#formCambiarPass");
			formCambiarPass.onsubmit = function (e) {
				e.preventDefault();

				let strPassword = document.querySelector("#txtPassword").value;
				let strPasswordConfirm = document.querySelector("#txtPasswordConfirm").value;
				let idUsuario = document.querySelector("#idUsuario").value;

				if (strPassword == "" || strPasswordConfirm == "") {
					swal("Por favor", "Escribe la nueva contraseña.", "error");
					return false;
				} else {
					if (strPassword.length < 5) {
						swal("Atención", "La contraseña debe tener un mínimo de 5 caracteres.", "info");
						return false;
					}
					if (strPassword != strPasswordConfirm) {
						swal("Atención", "Las contraseñas no son iguales.", "error");
						return false;
					}
					divLoading.style.display = "flex";
					var ajaxUrl = base_url + "/Login/setPassword";
					var formData = new FormData(formCambiarPass);

					fetch(ajaxUrl, {method: "POST", body: formData})
						.then((respuesta) => respuesta.json())
						.then((objData) => {
							// console.log(objData)
							if (objData) {
								if (objData.status) {
									swal(
										{
											title: "",
											text: objData.msg,
											type: "success",
											confirmButtonText: "Iniciar sessión",
											closeOnConfirm: false,
										},
										function (isConfirm) {
											if (isConfirm) {
												window.location = base_url + "/login";
											}
										}
									);
								} else {
									swal("Atención", objData.msg, "error");
								}
							} else {
								swal("Atención", "Error en el proceso", "error");
							}
						});
					divLoading.display = "none";
				}
			};
		}
	},
	false
);
