<?php

class Roles extends Controllers
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
		getPermisos(MUSUARIOS);
	}

	public function Roles()
	{
		$data['page_etiqueta'] = "Roles - Usuario";
		$data["page_nombre"] = "rol_usuario";
		$data['page_titulo'] = "Roles Usuario ";
		$data['page_funciones_js'] = "funciones_roles.js";

		$this->views->getVista($this, "roles", $data);
	}

	public function obtRoles()
	{

		if ($_SESSION['permisosMod']['r']) {
			$btnView = '';
			$btnEdit = '';
			$btnDelete = '';
			$arrData = $this->model->selecRoles();

			for ($i = 0; $i < count($arrData); $i++) {

				if ($arrData[$i]['status'] == 1) {
					$arrData[$i]['status'] = '<span class="badge badge-success">Activo</span>';
				} else {
					$arrData[$i]['status'] = '<span class="badge badge-danger">Inactivo</span>';
				}

				if ($_SESSION['permisosMod']['u']) {
					$btnView = '<button class="btn btn-secondary btn-sm btnPermisosRol" onClick="funcPermisos(' . $arrData[$i]['idrol'] . ')" title="Permisos"><i class="fas fa-key"></i></button>';
					$btnEdit = '<button class="btn btn-primary btn-sm btnEditRol" onClick="funcEditar(' . $arrData[$i]['idrol'] . ')" title="Editar"><i class="fas fa-pencil-alt"></i></button>';
				}
				if ($_SESSION['permisosMod']['d']) {
					$btnDelete = '<button class="btn btn-danger btn-sm btnDelRol" onClick="funcBorrar(' . $arrData[$i]['idrol'] . ')" title="Eliminar"><i class="far fa-trash-alt"></i></button>
					</div>';
				}
				$arrData[$i]['opciones'] = '<div class="text-center">' . $btnView . ' ' . $btnEdit . ' ' . $btnDelete . '</div>';
			}
			echo json_encode($arrData, JSON_UNESCAPED_UNICODE);
		}
		die();
	}

	// Funcion para rellenar el select Roles
	public function obtRolesSelect()
	{
		$htmlOptions = "";
		$arrData = $this->model->selecRoles();
		if (count($arrData) > 0) {
			for ($i = 0; $i < count($arrData); $i++) {
				if ($arrData[$i]['status'] == 1) {
					$htmlOptions .= '<option value="' . $arrData[$i]['idrol'] . '">' . $arrData[$i]['nombrerol'] . '</option>';
				}
			}
		}
		echo $htmlOptions;
		die();
	}

	public function obtRol($idrol)
	{
		// if($_SESSION['permisosMod']['r']){
		$intIdrol = intval(limpiaCadena($idrol));
		if ($intIdrol > 0) {
			$arrData = $this->model->selecRol($intIdrol);
			if (empty($arrData)) {
				$arrResponse = array('status' => false, 'msg' => 'Datos no encontrados.');
			} else {
				$arrResponse = array('status' => true, 'data' => $arrData);
			}
			echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
			// }
		}
		die();
	}
	//Inserta y Guarda un rol

	public function modificarRol()
	{
		//dep($_POST);

		$idrol = intval($_POST['idRol']);
		$nombre =  limpiaCadena($_POST['nombre']);
		$descipcion = limpiaCadena($_POST['descripcion']);
		$estado = intval($_POST['listaEstado']);
		$request_rol = "";

		if ($idrol == 0) {
			//Crear
			// if($_SESSION['permisosMod']['w']){
			$request_rol = $this->model->nuevoRol($nombre, $descipcion, $estado);
			$option = 1;
			// }
		} else {
			//Actualizar
			// if($_SESSION['permisosMod']['u']){
			$request_rol = $this->model->actualizarRol($idrol, $nombre, $descipcion, $estado);
			$option = 2;
			// }
		}

		if ($request_rol > 0) {
			if ($option == 1) {
				$arrResponse = array('status' => true, 'msg' => 'Datos guardados correctamente.');
			} else {
				$arrResponse = array('status' => true, 'msg' => 'Datos Actualizados correctamente.');
			}
		} else if ($request_rol == 'exist') {

			$arrResponse = array('status' => false, 'msg' => '¡Atención! El Rol ya existe.');
		} else {
			$arrResponse = array("status" => false, "msg" => 'No es posible almacenar los datos.');
		}
		echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
		die();
	}



	public function borrarRol($idrol)
	{
		$intIdrol = intval(limpiaCadena($idrol));
		$respuestaBorrar = $this->model->borrarRol($intIdrol);
		if ($respuestaBorrar == 'ok') {
			$arrRespuesta = array('status' => true, 'msg' => 'Se ha eliminado el Rol.');
		} else if ($respuestaBorrar == 'existe') {
			$arrRespuesta = array('status' => false, 'msg' => 'No es posible eliminar un Rol asociado a usuarios.');
		} else {
			$arrRespuesta = array('status' => false, 'msg' => 'Error al eliminar el Rol.');
		}
		echo json_encode($arrRespuesta, JSON_UNESCAPED_UNICODE);
		die();
	}
}
