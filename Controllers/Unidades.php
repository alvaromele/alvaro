<?php
class Unidades extends Controllers
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

	public function Unidades()
	{
		if (empty($_SESSION['permisosMod']['r'])) {
			header("Location:" . base_url() . '/dashboard');
		}
		$data['page_etiqueta'] = "Unidades";
		$data['page_title'] = "UNIDADES";
		$data['page_name'] = "unidades";
		$data['page_funciones_js'] = "funciones_unidades.js";
		$this->views->getVista($this, "unidades", $data);
	}

	//Metodo para mostrar los eliminados
	public function eliminadas($estado = 0)
	{
		if ($_SESSION['permisosMod']['d']) {
			$arrData = $this->model->selectUnidadesBorradas();

			$data['page_etiqueta'] = "Unidades";
			$data['page_title'] = "Unidades Inactivas";
			$data['page_name'] = "unidades";
			$data['unidades'] = $arrData;
			$data['page_funciones_js'] = "funciones_unidades.js";
			$this->views->getVista($this, "eliminadas", $data);
		}
	}


	public function setUnidad()
	{
		if ($_POST) {
			if (empty($_POST['unidad']) || empty($_POST['descripcion'])) {
				$arrResponse = array("status" => false, "msg" => 'Datos incorrectos.');
			} else {

				$intIdunidad = intval($_POST['idUnidad']);
				$strunidad =  limpiaCadena($_POST['unidad']);
				$strDescipcion = limpiaCadena($_POST['descripcion']);

				if ($intIdunidad == 0) {
					//Crear
					if ($_SESSION['permisosMod']['w']) {
						$request_cateria = $this->model->insertarunidad($strunidad, $strDescipcion);
						$option = 1;
					}
				} else {
					//Actualizar
					if ($_SESSION['permisosMod']['u']) {
						$request_cateria = $this->model->actualizarunidad($intIdunidad, $strunidad, $strDescipcion);
						$option = 2;
					}
				}
				if ($request_cateria > 0) {
					if ($option == 1) {
						$arrResponse = array('status' => true, 'msg' => 'Datos guardados correctamente.');
					} else {
						$arrResponse = array('status' => true, 'msg' => 'Datos Actualizados correctamente.');
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

	public function getUnidades()
	{
		if ($_SESSION['permisosMod']['r']) {
			$arrData = $this->model->selectUnidades();
			for ($i = 0; $i < count($arrData); $i++) {
				$btnView = '';
				$btnEdit = '';
				$btnDelete = '';

				if ($arrData[$i]['estado'] == 1) {
					$arrData[$i]['estado'] = '<span class="badge badge-success">Activo</span>';
				} else {
					$arrData[$i]['estado'] = '<span class="badge badge-danger">Inactivo</span>';
				}

				if ($_SESSION['permisosMod']['r']) {
					$btnView = '<button class="btn btn-info btn-sm" onClick="funcVerInfo(' . $arrData[$i]['idunidad'] . ')" title="Ver categoría"><i class="far fa-eye"></i></button>';
				}
				if ($_SESSION['permisosMod']['u']) {
					$btnEdit = '<button class="btn btn-primary  btn-sm" onClick="funcEditarInfo(this,' . $arrData[$i]['idunidad'] . ')" title="Editar categoría"><i class="fas fa-pencil-alt"></i></button>';
				}
				if ($_SESSION['permisosMod']['d']) {
					$btnDelete = '<button class="btn btn-danger btn-sm" onClick="funcBorrarInfo(' . $arrData[$i]['idunidad'] . ')" title="Eliminar categoría"><i class="far fa-trash-alt"></i></button>';
				}
				$arrData[$i]['options'] = '<div class="text-center">' . $btnView . ' ' . $btnEdit . ' ' . $btnDelete . '</div>';
			}
			echo json_encode($arrData, JSON_UNESCAPED_UNICODE);
		}
		die();
	}

	public function getUnidad($idund)
	{
		if ($_SESSION['permisosMod']['r']) {
			$intIdunidad = intval($idund);
			if ($intIdunidad > 0) {
				$arrData = $this->model->selectUnidad($intIdunidad);
				if (empty($arrData)) {
					$arrResponse = array('status' => false, 'msg' => 'Datos no encontrados.');
				} else {
					$arrResponse = array('status' => true, 'data' => $arrData);
				}
				echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
			}
		}
		die();
	}

	public function delunidad()
	{
		// dep($_POST);
		// die();
		if ($_POST) {
			if ($_SESSION['permisosMod']['d']) {
				$intIdunidad = intval($_POST['idund']);
				$requestDelete = $this->model->deleteunidad($intIdunidad);
				if ($requestDelete == 'ok') {
					$arrResponse = array('status' => true, 'msg' => 'Se ha eliminado la unidad');
				} else if ($requestDelete == 'exist') {
					$arrResponse = array('status' => false, 'msg' => 'No es posible eliminar la unidad porque esta asociada a productos.');
				} else {
					$arrResponse = array('status' => false, 'msg' => 'Error al eliminar la unidad.');
				}
				echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
			}
		}
		die();
	}

	public function activarUnidad()
	{
		// dep($_POST);
		// die();
		if ($_POST) {
			if ($_SESSION['permisosMod']['d']) {
				$intIdunidad = intval($_POST['idunidad']);
				$requestActivar = $this->model->activarUnidad($intIdunidad);
				if ($requestActivar) {
					$arrResponse = array('status' => true, 'msg' => 'Se ha activado la unidad');
				} else {
					$arrResponse = array('status' => false, 'msg' => 'Error al activar la unidad.');
				}
				echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
			}
		}
		die();
	}

	public function getSelectUnidades()
	{
		$htmlOptions = "";
		$arrData = $this->model->selectUnidades();
		if (count($arrData) > 0) {
			for ($i = 0; $i < count($arrData); $i++) {
				if ($arrData[$i]['estado'] == 1) {
					$htmlOptions .= '<option value="' . $arrData[$i]['idunidad'] . '">' . $arrData[$i]['descripcion'] . '</option>';
				}
			}
		}
		echo $htmlOptions;
		die();
	}
}
