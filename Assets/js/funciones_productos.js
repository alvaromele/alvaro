document.write(`<script src="${base_url}/Assets/js/plugins/JsBarcode.all.min.js"></script>`);
let tablaProductos;
let rowTable = "";
$(document).on("focusin", function (e) {
	if ($(e.target).closest(".tox-dialog").length) {
		e.stopImmediatePropagation();
	}
});
tablaProductos = $("#tablaProductos").dataTable({
	aProcessing: true,
	aServerSide: true,
	language: {
		url: "//cdn.datatables.net/plug-ins/1.10.20/i18n/Spanish.json",
	},
	ajax: {
		url: " " + base_url + "/Productos/getProductos",
		dataSrc: "",
	},
	columns: [
		{data: "idproducto"},
		{data: "codigo"},
		{data: "nombre"},
		{data: "unidad"},
		{data: "precio"},
		{data: "iva"},
		{data: "status"},
		{data: "options"},
	],
	columnDefs: [
		{className: "textcenter", targets: [3]},
		{className: "textright", targets: [4]},
		{className: "textcenter", targets: [5]},
	],
	dom: "Blfrtip",
	buttons: [
		{
			extend: "copyHtml5",
			text: "<i class='far fa-copy'></i> Copiar",
			titleAttr: "Copiar",
			className: "btn btn-secondary",
			exportOptions: {
				columns: [0, 1, 2, 3, 4, 5],
			},
		},
		{
			extend: "excelHtml5",
			text: "<i class='fas fa-file-excel'></i> Excel",
			titleAttr: "Esportar a Excel",
			className: "btn btn-success",
			exportOptions: {
				columns: [0, 1, 2, 3, 4, 5],
			},
		},
		{
			extend: "pdfHtml5",
			text: "<i class='fas fa-file-pdf'></i> PDF",
			titleAttr: "Esportar a PDF",
			className: "btn btn-danger",
			exportOptions: {
				columns: [0, 1, 2, 3, 4, 5],
			},
		},
		{
			extend: "csvHtml5",
			text: "<i class='fas fa-file-csv'></i> CSV",
			titleAttr: "Esportar a CSV",
			className: "btn btn-info",
			exportOptions: {
				columns: [0, 1, 2, 3, 4, 5],
			},
		},
	],
	resonsieve: "true",
	bDestroy: true,
	iDisplayLength: 10,
	order: [[2, "asc"]],
});
window.addEventListener(
	"load",
	function () {
		if (document.querySelector("#formProductos")) {
			let formProductos = document.querySelector("#formProductos");
			formProductos.onsubmit = function (e) {
				e.preventDefault();
				let strNombre = document.querySelector("#txtNombre").value;
				let intCodigo = document.querySelector("#txtCodigo").value;
				let intPrecio = document.querySelector("#txtPrecio").value;
				let intIva = document.querySelector("#txtIva").value;
				let intStatus = document.querySelector("#listStatus").value;
				let intUnidad = document.querySelector("#listUnidades").value;
				let intCategoria = document.querySelector("#listCategoria").value;
				if (strNombre == "" || intCodigo == "" || intPrecio == "") {
					swal("Atención", "Todos los campos son obligatorios.", "error");
					return false;
				}
				if (intCodigo.length < 4) {
					swal("Atención", "El código debe ser mayor que 4 dígitos.", "error");
					return false;
				}
				divLoading.style.display = "flex";
				tinyMCE.triggerSave();

				let url = base_url + "/Productos/setProducto";
				let formData = new FormData(formProductos);

				fetch(url, {method: "POST", body: formData})
					.then((respuesta) => respuesta.json())
					.then((resp) => {
						if (resp.status) {
							swal("", resp.msg, "success");
							document.querySelector("#idProducto").value = resp.idproducto;
							document.querySelector("#containerGallery").classList.remove("notblock");

							if (rowTable == "") {
								tablaProductos.api().ajax.reload();
							} else {
								htmlStatus =
									intStatus == 1
										? '<span class="badge badge-success">Activo</span>'
										: '<span class="badge badge-danger">Inactivo</span>';
								rowTable.cells[1].textContent = intCodigo;
								rowTable.cells[2].textContent = strNombre;
								rowTable.cells[4].textContent = smony + intPrecio;
								rowTable.cells[5].textContent = intIva;
								rowTable.cells[6].innerHTML = htmlStatus;
								rowTable = "";
							}
						} else {
							swal("Error", resp.msg, "error");
						}
					});
				divLoading.style.display = "none";
				return false;
			};
		}

		if (document.querySelector(".btnAddImage")) {
			let btnAddImage = document.querySelector(".btnAddImage");
			btnAddImage.onclick = function (e) {
				let key = Date.now();
				let newElement = document.createElement("div");
				newElement.id = "div" + key;
				newElement.innerHTML = `
					<div class="prevImage"></div>
					<input type="file" name="foto" id="img${key}" class="inputUploadfile">
					<label for="img${key}" class="btnUploadfile"><i class="fas fa-upload "></i></label>
					<button class="btnDeleteImage notblock" type="button" onclick="fntDelItem('#div${key}')"><i class="fas fa-trash-alt"></i></button>`;
				document.querySelector("#containerImages").appendChild(newElement);
				document.querySelector("#div" + key + " .btnUploadfile").click();
				fntInputFile();
			};
		}

		fntInputFile();
		fntCategorias();
		fntUnidades();
	},
	false
);

if (document.querySelector("#txtCodigo")) {
	let inputCodigo = document.querySelector("#txtCodigo");
	inputCodigo.onkeyup = function () {
		if (inputCodigo.value.length >= 4) {
			document.querySelector("#divBarCode").classList.remove("notblock");
			fntBarcode();
		} else {
			document.querySelector("#divBarCode").classList.add("notblock");
		}
	};
}

tinymce.init({
	selector: "#txtDescripcion",
	width: "100%",
	height: 400,
	statubar: true,
	plugins: [
		"advlist autolink link image lists charmap print preview hr anchor pagebreak",
		"searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking",
		"save table contextmenu directionality emoticons template paste textcolor",
	],
	toolbar:
		"insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image | print preview media fullpage | forecolor backcolor emoticons",
});

function fntInputFile() {
	let inputUploadfile = document.querySelectorAll(".inputUploadfile");
	inputUploadfile.forEach(function (inputUploadfile) {
		inputUploadfile.addEventListener("change", function () {
			let idProducto = document.querySelector("#idProducto").value;
			let parentId = this.parentNode.getAttribute("id");
			let idFile = this.getAttribute("id");
			let uploadFoto = document.querySelector("#" + idFile).value;
			let fileimg = document.querySelector("#" + idFile).files;
			let prevImg = document.querySelector("#" + parentId + " .prevImage");
			let nav = window.URL || window.webkitURL;
			if (uploadFoto != "") {
				let type = fileimg[0].type;
				let name = fileimg[0].name;
				if (type != "image/jpeg" && type != "image/jpg" && type != "image/png") {
					prevImg.innerHTML = "Archivo no válido";
					uploadFoto.value = "";
					return false;
				} else {
					let objeto_url = nav.createObjectURL(this.files[0]);
					prevImg.innerHTML = `<img class="loading" src="${base_url}/Assets/images/loading.svg" >`;

					let url = base_url + "/Productos/setImage";
					let formData = new FormData();
					formData.append("idproducto", idProducto);
					formData.append("foto", this.files[0]);
					fetch(url, {method: "POST", body: formData})
						.then((respuesta) => respuesta.json())
						.then((resp) => {
							if (resp.status) {
								prevImg.innerHTML = `<img src="${objeto_url}">`;
								document
									.querySelector("#" + parentId + " .btnDeleteImage")
									.setAttribute("imgname", resp.imgname);
								document.querySelector("#" + parentId + " .btnUploadfile").classList.add("notblock");
								document.querySelector("#" + parentId + " .btnDeleteImage").classList.remove("notblock");
							} else {
								swal("Error", resp.msg, "error");
							}
						});
				}
			}
		});
	});
}

function fntDelItem(element) {
	let nameImg = document.querySelector(element + " .btnDeleteImage").getAttribute("imgname");
	let idProducto = document.querySelector("#idProducto").value;

	let url = base_url + "/Productos/delFile";
	let formData = new FormData();
	formData.append("idproducto", idProducto);
	formData.append("file", nameImg);
	fetch(url, {method: "POST", body: formData})
		.then((respuesta) => respuesta.json())
		.then((resp) => {
			if (resp.status) {
				let itemRemove = document.querySelector(element);
				itemRemove.parentNode.removeChild(itemRemove);
			} else {
				swal("", resp.msg, "error");
			}
		});
}

function fntViewInfo(idProducto) {
	let url = base_url + "/Productos/getProducto/" + idProducto;

	fetch(url, {method: "GET"})
		.then((respuesta) => respuesta.json())
		.then((resp) => {
			if (resp.status) {
				let htmlImage = "";
				let objProducto = resp.data;
				let estadoProducto =
					objProducto.status == 1
						? '<span class="badge badge-success">Activo</span>'
						: '<span class="badge badge-danger">Inactivo</span>';

				document.querySelector("#celCodigo").innerHTML = objProducto.codigo;
				document.querySelector("#celNombre").innerHTML = objProducto.nombre;
				document.querySelector("#celPrecio").innerHTML = objProducto.precio;
				document.querySelector("#celStock").innerHTML = objProducto.iva;
				document.querySelector("#celCategoria").innerHTML = objProducto.categoria;
				document.querySelector("#celUnidad").innerHTML = objProducto.unidad;
				document.querySelector("#celStatus").innerHTML = estadoProducto;
				document.querySelector("#celDescripcion").innerHTML = objProducto.descripcion;

				if (objProducto.images.length > 0) {
					let objProductos = objProducto.images;
					for (let p = 0; p < objProductos.length; p++) {
						htmlImage += `<img src="${objProductos[p].url_image}"></img>`;
					}
				}
				document.querySelector("#celFotos").innerHTML = htmlImage;
				$("#modalViewProducto").modal("show");
			} else {
				swal("Error", resp.msg, "error");
			}
		});
}

function fntEditInfo(element, idProducto) {
	rowTable = element.parentNode.parentNode.parentNode;
	document.querySelector("#titleModal").innerHTML = "Actualizar Producto";
	document.querySelector(".modal-header").classList.replace("headerRegister", "headerUpdate");
	document.querySelector("#btnActionForm").classList.replace("btn-primary", "btn-info");
	document.querySelector("#btnText").innerHTML = "Actualizar";

	let url = base_url + "/Productos/getProducto/" + idProducto;
	fetch(url, {method: "GET"})
		.then((respuesta) => respuesta.json())
		.then((resp) => {
			if (resp.status) {
				let htmlImage = "";
				let objProducto = resp.data;
				document.querySelector("#idProducto").value = objProducto.idproducto;
				document.querySelector("#txtNombre").value = objProducto.nombre;
				document.querySelector("#txtDescripcion").value = objProducto.descripcion;
				document.querySelector("#txtCodigo").value = objProducto.codigo;
				document.querySelector("#txtPrecio").value = objProducto.precio;
				document.querySelector("#txtIva").value = objProducto.iva;
				document.querySelector("#listUnidades").value = objProducto.idunidad;
				document.querySelector("#listCategoria").value = objProducto.idcategoria;
				document.querySelector("#listStatus").value = objProducto.status;
				tinymce.activeEditor.setContent(objProducto.descripcion);
				$("#listCategoria").selectpicker("render");
				$("#listUnidades").selectpicker("render");
				$("#listStatus").selectpicker("render");
				fntBarcode();

				if (objProducto.images.length > 0) {
					let objProductos = objProducto.images;
					for (let p = 0; p < objProductos.length; p++) {
						let key = Date.now() + p;
						htmlImage += `<div id="div${key}">
                            <div class="prevImage">
                            <img src="${objProductos[p].url_image}"></img>
                            </div>
                            <button type="button" class="btnDeleteImage" onclick="fntDelItem('#div${key}')" imgname="${objProductos[p].img}">
                            <i class="fas fa-trash-alt"></i></button></div>`;
					}
				}
				document.querySelector("#containerImages").innerHTML = htmlImage;
				document.querySelector("#divBarCode").classList.remove("notblock");
				document.querySelector("#containerGallery").classList.remove("notblock");
				$("#modalFormProductos").modal("show");
			} else {
				swal("Error", resp.msg, "error");
			}
		});
}

function fntDelInfo(idProducto) {
	swal(
		{
			title: "Eliminar Producto",
			text: "¿Realmente quiere eliminar el producto?",
			type: "warning",
			showCancelButton: true,
			confirmButtonText: "Si, eliminar!",
			cancelButtonText: "No, cancelar!",
			closeOnConfirm: false,
			closeOnCancel: true,
		},
		function (isConfirm) {
			if (isConfirm) {
				let url = base_url + "/Productos/delProducto";
				let data = new FormData();
				data.append("idProducto", idProducto);
				fetch(url, {method: "POST", body: data})
					.then((respuesta) => respuesta.json())
					.then((resp) => {
						if (resp.status) {
							swal("Eliminar!", resp.msg, "success");
							tablaProductos.api().ajax.reload();
						} else {
							swal("Atención!", resp.msg, "error");
						}
					});
			}
		}
	);
}

function funcActivarProducto(idprod) {
	let url = base_url + "/productos/activarProducto";
	let data = new FormData();
	data.append("idproducto", idprod);
	fetch(url, {method: "POST", body: data})
		.then((resp) => resp.json())
		.then((resp) => {
			if (resp.status) {
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
							window.location.href = base_url + "/Productos/productos";
						}
					}
				);
			} else {
				swal("Atención!", resp.msg, "error");
			}
		});
}

function fntCategorias() {
	if (document.querySelector("#listCategoria")) {
		let ajaxUrl = base_url + "/Categorias/getSelectCategorias";
		let request = window.XMLHttpRequest ? new XMLHttpRequest() : new ActiveXObject("Microsoft.XMLHTTP");
		request.open("GET", ajaxUrl, true);
		request.send();
		request.onreadystatechange = function () {
			if (request.readyState == 4 && request.status == 200) {
				document.querySelector("#listCategoria").innerHTML = request.responseText;
				$("#listCategoria").selectpicker("render");
			}
		};
	}
}

function fntUnidades() {
	if (document.querySelector("#listUnidades")) {
		let ajaxUrl = base_url + "/Unidades/getSelectUnidades";
		let request = window.XMLHttpRequest ? new XMLHttpRequest() : new ActiveXObject("Microsoft.XMLHTTP");
		request.open("GET", ajaxUrl, true);
		request.send();
		request.onreadystatechange = function () {
			if (request.readyState == 4 && request.status == 200) {
				document.querySelector("#listUnidades").innerHTML = request.responseText;
				$("#listUnidades").selectpicker("render");
			}
		};
	}
}

function fntBarcode() {
	let codigo = document.querySelector("#txtCodigo").value;
	JsBarcode("#barcode", codigo);
}

function fntPrintBarcode(area) {
	let elemntArea = document.querySelector(area);
	let vprint = window.open(" ", "popimpr", "height=400,width=600");
	vprint.document.write(elemntArea.innerHTML);
	vprint.document.close();
	vprint.print();
	vprint.close();
}

function openModal() {
	rowTable = "";
	document.querySelector("#idProducto").value = "";
	document.querySelector(".modal-header").classList.replace("headerUpdate", "headerRegister");
	document.querySelector("#btnActionForm").classList.replace("btn-info", "btn-primary");
	document.querySelector("#btnText").innerHTML = "Guardar";
	document.querySelector("#titleModal").innerHTML = "Nuevo Producto";
	document.querySelector("#formProductos").reset();
	document.querySelector("#divBarCode").classList.add("notblock");
	document.querySelector("#containerGallery").classList.add("notblock");
	document.querySelector("#containerImages").innerHTML = "";
	$("#modalFormProductos").modal("show");
}
