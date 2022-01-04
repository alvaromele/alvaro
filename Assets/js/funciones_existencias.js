let tablaExistencias;
let rowTable = "";
$(document).on("focusin", function (e) {
	if ($(e.target).closest(".tox-dialog").length) {
		e.stopImmediatePropagation();
	}
});
tablaExistencias = $("#tablaExistencias").dataTable({
	aProcessing: true,
	aServerSide: true,
	language: {
		url: "//cdn.datatables.net/plug-ins/1.10.20/i18n/Spanish.json",
	},
	ajax: {
		url: " " + base_url + "/Existencias/getExistencias",
		dataSrc: "",
	},
	columns: [
		{data: "id"},
		// {data: "idproducto"},
		{data: "codproducto"},
		{data: "producto"},
		{data: "unidad"},
		{data: "cantidad"},
		{data: "total"},
		// {data: "status"},
		{data: "opciones"},
	],
	columnDefs: [
		{className: "textcenter", targets: [0]},
		{className: "textcenter", targets: [1]},
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
});
