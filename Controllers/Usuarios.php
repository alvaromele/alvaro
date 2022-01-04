<?php

class Usuarios extends Controllers
{
	public function __construct()
	{
		parent::__construct();
		session_start();
		if (empty($_SESSION['login'])) {
			header('Location: ' . base_url() . '/login');
			die();
		}
		getPermisos(MUSUARIOS);
	}

	public function Usuarios()
	{
		if (empty($_SESSION['permisosMod']['r'])) {
			header("Location:" . base_url() . '/dashboard');
		}
		$data['page_etiqueta'] = "Usuarios";
		$data['page_titulo'] = "Usuarios";
		$data['page_name'] = "usuarios";
		$data['page_funciones_js'] = "funciones_usuarios.js";
		$this->views->getVista($this, "usuarios", $data);
	}
	//Metodo para mostrar los eliminados
	public function eliminados($estado = 0)
	{
		if ($_SESSION['permisosMod']['d']) {
			$arrData = $this->model->selectUsuariosBorrados();

			$data['page_tag'] = "Usuarios";
			$data['page_title'] = "Usuarios Inactivos";
			$data['page_name'] = "usuarios";
			$data['usuarios'] = $arrData;
			$data['page_funciones_js'] = "funciones_usuarios.js";
			$this->views->getVista($this, "eliminados", $data);
		}
	}

	public function modificarUsuario()
	{

		// dep($_POST);
		// die();
		if ($_POST) {
			if (empty($_POST['identificacion']) || empty($_POST['nombre']) || empty($_POST['apellido']) ||  empty($_POST['email']) || empty($_POST['listaRolid']) || empty($_POST['listaEstado'])) {
				$arrResponse = array("status" => false, "msg" => 'Datos muy incorrectos.');
			} else {
				$idUsuario = intval($_POST['idUsuario']);
				$strIdentificacion = limpiaCadena($_POST['identificacion']);
				$strNombre = ucwords(limpiaCadena($_POST['nombre']));
				$strApellido = ucwords(limpiaCadena($_POST['apellido']));
				// $intTelefono = intval(limpiaCadena($_POST['telefono']));
				$strEmail = strtolower(limpiaCadena($_POST['email']));
				$intTipoId = intval(limpiaCadena($_POST['listaRolid']));
				$intStatus = intval(limpiaCadena($_POST['listaEstado']));
				$request_user = "";
				if ($idUsuario == 0) {
					//NUEVO USUARIO
					$option = 1;
					// $strPassword =  empty($_POST['txtPassword']) ? hash("SHA256", generaPasword()) : hash("SHA256", $_POST['txtPassword']);
					$strPassword =  empty($_POST['txtPassword']) ? hash("SHA256", generaPasword()) : $_POST['txtPassword'];

					if ($_SESSION['permisosMod']['w']) {
						$request_user = $this->model->insertUsuario(
							$strIdentificacion,
							$strNombre,
							$strApellido,
							// $intTelefono,
							$strEmail,
							$strPassword,
							$intTipoId,
							$intStatus
						);
					}
				} else {
					// MODIFICAR USUARIO
					$option = 2;
					// $strPassword =  empty($_POST['txtPassword']) ? "" : hash("SHA256", $_POST['txtPassword']);
					$strPassword =  empty($_POST['txtPassword']) ? "" : $_POST['txtPassword'];
					if ($_SESSION['permisosMod']['u']) {
						$request_user = $this->model->updateUsuario(
							$idUsuario,
							$strIdentificacion,
							$strNombre,
							$strApellido,
							// $intTelefono,
							$strEmail,
							$strPassword,
							$intTipoId,
							$intStatus
						);
					}
				}

				if ($request_user > 0) {
					if ($option == 1) {
						$arrResponse = array('status' => true, 'msg' => 'Datos guardados correctamente.');
					} else {
						$arrResponse = array('status' => true, 'msg' => 'Datos Actualizados correctamente.');
					}
				} else if ($request_user == 'exist') {
					$arrResponse = array('status' => false, 'msg' => '¡Atención! el email o la identificación ya existe, ingrese otro.');
				} else {
					$arrResponse = array("status" => false, "msg" => 'No es posible almacenar los datos.');
				}
			}
			echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
		}
		die();
	}

	public function obtienerUsuarios()
	{
		if ($_SESSION['permisosMod']['r']) {

			$arrData = $this->model->selectUsuarios();
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
					$btnView = '<button class="btn btn-info btn-sm btnViewUsuario" onClick="funcVerInfo(' . $arrData[$i]['idusuario'] . ')" title="Ver usuario"><i class="far fa-eye"></i></button>';
				}
				if ($_SESSION['permisosMod']['u']) {
					if (($_SESSION['idUser'] == 1 and $_SESSION['userData']['idrol'] == 1) ||
						($_SESSION['userData']['idrol'] == 1 and $arrData[$i]['idrol'] != 1)
					) {
						$btnEdit = '<button class="btn btn-primary  btn-sm btnEditUsuario" onClick="funcEditarInfo(this,' . $arrData[$i]['idusuario'] . ')" title="Editar usuario"><i class="fas fa-pencil-alt"></i></button>';
					} else {
						$btnEdit = '<button class="btn btn-secondary btn-sm" disabled ><i class="fas fa-pencil-alt"></i></button>';
					}
				}
				if ($_SESSION['permisosMod']['d']) {
					if (($_SESSION['idUser'] == 1 and $_SESSION['userData']['idrol'] == 1) ||
						($_SESSION['userData']['idrol'] == 1 and $arrData[$i]['idrol'] != 1) and
						($_SESSION['userData']['idpersona'] != $arrData[$i]['idusuario'])
					) {
						$btnDelete = '<button class="btn btn-danger btn-sm btnDelUsuario" onClick="funcBorrarInfo(this,' . $arrData[$i]['idusuario'] . ')" title="Eliminar usuario"><i class="far fa-trash-alt"></i></button>';
					} else {
						$btnDelete = '<button class="btn btn-secondary btn-sm" disabled ><i class="far fa-trash-alt"></i></button>';
					}
				}
				$arrData[$i]['opciones'] = '<div class="text-center">' . $btnView . ' ' . $btnEdit . ' ' . $btnDelete . '</div>';
			}
			echo json_encode($arrData, JSON_UNESCAPED_UNICODE);
		}
		die();
	}

	public function obtenerUsuario($idusuario)
	{
		if ($_SESSION['permisosMod']['r']) {
			$idusuario = intval($idusuario);
			if ($idusuario > 0) {
				$arrData = $this->model->selectUsuario($idusuario);
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

	public function delUsuario()
	{
		if ($_POST) {
			if ($_SESSION['permisosMod']['d']) {

				$intIdpersona = intval($_POST['idusuario']);
				$requestDelete = $this->model->deleteUsuario($intIdpersona);
				if ($requestDelete) {
					$arrResponse = array('status' => true, 'msg' => 'Se ha eliminado el usuario');
				} else {
					$arrResponse = array('status' => false, 'msg' => 'Error al eliminar el usuario.');
				}
				echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
			}
		}
		die();
	}

	public function activarUsuario()
	{
		if ($_POST) {
			if ($_SESSION['permisosMod']['d']) {
				$intIdusuario = intval($_POST['idusuario']);
				$requestActivar = $this->model->activarUsuario($intIdusuario);
				if ($requestActivar) {
					$arrResponse = array('status' => true, 'msg' => 'Se ha activado el usuario');
				} else {
					$arrResponse = array('status' => false, 'msg' => 'Error al eliminar el usuario.');
				}
				echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
			}
		}
		die();
	}

	public function perfil()
	{
		$data['page_tag'] = "Perfil";
		$data['page_title'] = "Perfil de usuario";
		$data['page_name'] = "perfil";
		$data['page_functions_js'] = "functions_usuarios.js";
		$this->views->getVista($this, "perfil", $data);
	}

	public function putPerfil()
	{
		if ($_POST) {
			if (empty($_POST['txtIdentificacion']) || empty($_POST['txtNombre']) || empty($_POST['txtApellido']) || empty($_POST['txtTelefono'])) {
				$arrResponse = array("status" => false, "msg" => 'Datos incorrectos.');
			} else {
				$idUsuario = $_SESSION['idUser'];
				$strIdentificacion = limpiaCadena($_POST['txtIdentificacion']);
				$strNombre = limpiaCadena($_POST['txtNombre']);
				$strApellido = limpiaCadena($_POST['txtApellido']);
				$intTelefono = intval(limpiaCadena($_POST['txtTelefono']));
				$strPassword = "";
				if (!empty($_POST['txtPassword'])) {
					$strPassword = hash("SHA256", $_POST['txtPassword']);
				}
				$request_user = $this->model->updatePerfil(
					$idUsuario,
					$strIdentificacion,
					$strNombre,
					$strApellido,
					$intTelefono,
					$strPassword
				);
				if ($request_user) {
					sessionUser($_SESSION['idUser']);
					$arrResponse = array('status' => true, 'msg' => 'Datos Actualizados correctamente.');
				} else {
					$arrResponse = array("status" => false, "msg" => 'No es posible actualizar los datos.');
				}
			}
			echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
		}
		die();
	}

	public function putDFical()
	{
		if ($_POST) {
			if (empty($_POST['txtNit']) || empty($_POST['txtNombreFiscal']) || empty($_POST['txtDirFiscal'])) {
				$arrResponse = array("status" => false, "msg" => 'Datos incorrectos.');
			} else {
				$idUsuario = $_SESSION['idUser'];
				$strNit = limpiaCadena($_POST['txtNit']);
				$strNomFiscal = limpiaCadena($_POST['txtNombreFiscal']);
				$strDirFiscal = limpiaCadena($_POST['txtDirFiscal']);
				$request_datafiscal = $this->model->updateDataFiscal(
					$idUsuario,
					$strNit,
					$strNomFiscal,
					$strDirFiscal
				);
				if ($request_datafiscal) {
					sessionUser($_SESSION['idUser']);
					$arrResponse = array('status' => true, 'msg' => 'Datos Actualizados correctamente.');
				} else {
					$arrResponse = array("status" => false, "msg" => 'No es posible actualizar los datos.');
				}
			}
			echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
		}
		die();
	}
}
