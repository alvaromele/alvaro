<?php
class Categorias extends Controllers
{

	public function __construct()
	{
		parent::__construct();
		session_start();
		//session_regenerate_id(true);
		if (empty($_SESSION['login'])) {
			header('Location: ' . base_url() . '/login');
			die();
		}
		getPermisos(MCATEGORIAS);
	}

	public function Categorias()
	{
		if (empty($_SESSION['permisosMod']['r'])) {
			header("Location:" . base_url() . '/dashboard');
		}
		$data['page_etiqueta'] = "Categorias";
		$data['page_title'] = "CATEGORIAS";
		$data['page_name'] = "categorias";
		$data['page_funciones_js'] = "funciones_categorias.js";
		$this->views->getVista($this, "categorias", $data);
	}

	//Metodo para mostrar los eliminados
	public function eliminados($estado = 0)
	{
		if ($_SESSION['permisosMod']['d']) {
			$arrData = $this->model->selectCategoriasBorradas();

			$data['page_etiqueta'] = "Categorias";
			$data['page_title'] = "Categorias Inactivas";
			$data['page_name'] = "categorias";
			$data['categorias'] = $arrData;
			$data['page_funciones_js'] = "funciones_categorias.js";
			$this->views->getVista($this, "eliminados", $data);
		}
	}

	public function setCategoria()
	{
		if ($_POST) {
			if (empty($_POST['txtNombre']) || empty($_POST['listStatus'])) {
				$arrResponse = array("status" => false, "msg" => 'Datos incorrectos.');
			} else {

				$intIdcategoria = intval($_POST['idCategoria']);
				$strCategoria =  limpiaCadena($_POST['txtNombre']);
				$strDescipcion = limpiaCadena($_POST['txtDescripcion']);
				$intStatus = intval($_POST['listStatus']);

				$ruta = strtolower(clear_cadena($strCategoria));
				$ruta = str_replace(" ", "-", $ruta);

				$foto   	 	= $_FILES['foto'];
				$nombre_foto 	= $foto['name'];
				$type 		 	= $foto['type'];
				$url_temp    	= $foto['tmp_name'];
				$imgPortada 	= 'portada_categoria.png';
				$request_cateria = "";
				if ($nombre_foto != '') {
					$imgPortada = 'img_' . md5(date('d-m-Y H:i:s')) . '.jpg';
				}

				if ($intIdcategoria == 0) {
					//Crear
					if ($_SESSION['permisosMod']['w']) {
						$request_cateria = $this->model->inserCategoria($strCategoria, $strDescipcion, $imgPortada, $ruta, $intStatus);
						$option = 1;
					}
				} else {
					//Actualizar
					if ($_SESSION['permisosMod']['u']) {
						if ($nombre_foto == '') {
							if ($_POST['foto_actual'] != 'portada_categoria.png' && $_POST['foto_remove'] == 0) {
								$imgPortada = $_POST['foto_actual'];
							}
						}
						$request_cateria = $this->model->updateCategoria($intIdcategoria, $strCategoria, $strDescipcion, $imgPortada, $ruta, $intStatus);
						$option = 2;
					}
				}
				if ($request_cateria > 0) {
					if ($option == 1) {
						$arrResponse = array('status' => true, 'msg' => 'Datos guardados correctamente.');
						if ($nombre_foto != '') {
							uploadImage($foto, $imgPortada);
						}
					} else {
						$arrResponse = array('status' => true, 'msg' => 'Datos Actualizados correctamente.');
						if ($nombre_foto != '') {
							uploadImage($foto, $imgPortada);
						}

						if (($nombre_foto == '' && $_POST['foto_remove'] == 1 && $_POST['foto_actual'] != 'portada_categoria.png')
							|| ($nombre_foto != '' && $_POST['foto_actual'] != 'portada_categoria.png')
						) {
							deleteFile($_POST['foto_actual']);
						}
					}
				} else if ($request_cateria == 'exist') {
					$arrResponse = array('status' => false, 'msg' => '¡Atención! La categoría ya existe.');
				} else {
					$arrResponse = array("status" => false, "msg" => 'No es posible almacenar los datos.');
				}
			}
			echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
		}
		die();
	}

	public function getCategorias()
	{
		if ($_SESSION['permisosMod']['r']) {
			$arrData = $this->model->selectCategorias();
			for ($i = 0; $i < count($arrData); $i++) {
				$btnView = '';
				$btnEdit = '';
				$btnDelete = '';

				if ($arrData[$i]['status'] == 1) {
					$arrData[$i]['status'] = '<span class="badge badge-success">Activo</span>';
				} else {
					$arrData[$i]['status'] = '<span class="badge badge-danger">Inactivo</span>';
				}

				if ($_SESSION['permisosMod']['r']) {
					$btnView = '<button class="btn btn-info btn-sm" onClick="funcVerInfo(' . $arrData[$i]['idcategoria'] . ')" title="Ver categoría"><i class="far fa-eye"></i></button>';
				}
				if ($_SESSION['permisosMod']['u']) {
					$btnEdit = '<button class="btn btn-primary  btn-sm" onClick="funcEditarInfo(this,' . $arrData[$i]['idcategoria'] . ')" title="Editar categoría"><i class="fas fa-pencil-alt"></i></button>';
				}
				if ($_SESSION['permisosMod']['d']) {
					$btnDelete = '<button class="btn btn-danger btn-sm" onClick="funcBorrarInfo(' . $arrData[$i]['idcategoria'] . ')" title="Eliminar categoría"><i class="far fa-trash-alt"></i></button>';
				}
				$arrData[$i]['options'] = '<div class="text-center">' . $btnView . ' ' . $btnEdit . ' ' . $btnDelete . '</div>';
			}
			echo json_encode($arrData, JSON_UNESCAPED_UNICODE);
		}
		die();
	}

	public function getCategoria($idcategoria)
	{
		if ($_SESSION['permisosMod']['r']) {
			$intIdcategoria = intval($idcategoria);
			if ($intIdcategoria > 0) {
				$arrData = $this->model->selectCategoria($intIdcategoria);
				if (empty($arrData)) {
					$arrResponse = array('status' => false, 'msg' => 'Datos no encontrados.');
				} else {
					$arrData['url_portada'] = media() . '/images/uploads/' . $arrData['portada'];
					$arrResponse = array('status' => true, 'data' => $arrData);
				}
				echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
			}
		}
		die();
	}

	public function delCategoria()
	{
		if ($_POST) {
			if ($_SESSION['permisosMod']['d']) {
				$intIdcategoria = intval($_POST['idCategoria']);
				$requestDelete = $this->model->deleteCategoria($intIdcategoria);
				if ($requestDelete == 'ok') {
					$arrResponse = array('status' => true, 'msg' => 'Se ha eliminado la categoría');
				} else if ($requestDelete == 'exist') {
					$arrResponse = array('status' => false, 'msg' => 'No es posible eliminar una categoría con productos asociados.');
				} else {
					$arrResponse = array('status' => false, 'msg' => 'Error al eliminar la categoría.');
				}
				echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
			}
		}
		die();
	}

	public function activarCategoria()
	{
		// dep($_POST);
		// die();
		if ($_POST) {
			if ($_SESSION['permisosMod']['d']) {
				$intIdcat = intval($_POST['idcategoria']);
				$requestActivar = $this->model->activarCategoria($intIdcat);
				if ($requestActivar) {
					$arrResponse = array('status' => true, 'msg' => 'Se ha activado la categoria');
				} else {
					$arrResponse = array('status' => false, 'msg' => 'Error al activar la categoria.');
				}
				echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
			}
		}
		die();
	}

	public function getSelectCategorias()
	{
		$htmlOptions = "";
		$arrData = $this->model->selectCategorias();
		if (count($arrData) > 0) {
			for ($i = 0; $i < count($arrData); $i++) {
				if ($arrData[$i]['status'] == 1) {
					$htmlOptions .= '<option value="' . $arrData[$i]['idcategoria'] . '">' . $arrData[$i]['nombre'] . '</option>';
				}
			}
		}
		echo $htmlOptions;
		die();
	}
}
