<?php
class Productos extends Controllers
{
	public function __construct()
	{
		parent::__construct();
		session_start();
		if (empty($_SESSION['login'])) {
			header('Location: ' . base_url() . '/login');
			die();
		}
		getPermisos(MPRODUCTOS);
	}

	public function Productos()
	{
		if (empty($_SESSION['permisosMod']['r'])) {
			header("Location:" . base_url() . '/dashboard');
		}
		$numMax = $this->model->codigo();
		if ($numMax['codigo'] == null) {
			$numMax['codigo'] = CPRODUCTOS;
		}

		$data['page_etiqueta'] = "Productos";
		$data['page_title'] = "PRODUCTOS";
		$data['page_name'] = "productos";
		$data['codigo'] = $numMax;
		$data['page_funciones_js'] = "funciones_productos.js";
		$this->views->getVista($this, "productos", $data);
	}

	//Metodo para mostrar los eliminados
	public function eliminados($estado = 0)
	{
		if ($_SESSION['permisosMod']['d']) {
			$arrData = $this->model->selectProductosBorrados();

			$data['page_tag'] = "Productos";
			$data['page_title'] = "Productos Inactivos";
			$data['page_name'] = "productos";
			$data['productos'] = $arrData;
			$data['page_funciones_js'] = "funciones_productos.js";
			$this->views->getVista($this, "eliminados", $data);
		}
	}

	public function getProductos()
	{
		if ($_SESSION['permisosMod']['r']) {
			$arrData = $this->model->selectProductos();
			for ($i = 0; $i < count($arrData); $i++) {
				$btnView = '';
				$btnEdit = '';
				$btnDelete = '';

				if ($arrData[$i]['status'] == 1) {
					$arrData[$i]['status'] = '<span class="badge badge-success">Activo</span>';
				} else {
					$arrData[$i]['status'] = '<span class="badge badge-danger">Inactivo</span>';
				}

				$arrData[$i]['precio'] = SMONEY . ' ' . formatMoney($arrData[$i]['precio']);
				if ($_SESSION['permisosMod']['r']) {
					$btnView = '<button class="btn btn-info btn-sm" onClick="fntViewInfo(' . $arrData[$i]['idproducto'] . ')" title="Ver producto"><i class="far fa-eye"></i></button>';
				}
				if ($_SESSION['permisosMod']['u']) {
					$btnEdit = '<button class="btn btn-primary  btn-sm" onClick="fntEditInfo(this,' . $arrData[$i]['idproducto'] . ')" title="Editar producto"><i class="fas fa-pencil-alt"></i></button>';
				}
				if ($_SESSION['permisosMod']['d']) {
					$btnDelete = '<button class="btn btn-danger btn-sm" onClick="fntDelInfo(' . $arrData[$i]['idproducto'] . ')" title="Eliminar producto"><i class="far fa-trash-alt"></i></button>';
				}
				$arrData[$i]['options'] = '<div class="text-center">' . $btnView . ' ' . $btnEdit . ' ' . $btnDelete . '</div>';
			}
			echo json_encode($arrData, JSON_UNESCAPED_UNICODE);
		}
		die();
	}

	public function setProducto()
	{
		if ($_POST) {
			if (empty($_POST['txtNombre']) || empty($_POST['txtCodigo']) || empty($_POST['listCategoria']) || empty($_POST['listUnidades']) || empty($_POST['txtPrecio']) || empty($_POST['listStatus'])) {
				$arrResponse = array("status" => false, "msg" => 'Datos incorrectos.');
			} else {

				$idProducto = intval($_POST['idProducto']);
				$strNombre = limpiaCadena($_POST['txtNombre']);
				$strDescripcion = limpiaCadena($_POST['txtDescripcion']);
				$strCodigo = limpiaCadena($_POST['txtCodigo']);
				$intCategoriaId = intval($_POST['listCategoria']);
				$intUnidadId = intval($_POST['listUnidades']);
				$strPrecio = limpiaCadena($_POST['txtPrecio']);
				$intIva = limpiaCadena($_POST['txtIva']);
				$intStatus = intval($_POST['listStatus']);
				$request_producto = "";

				$ruta = strtolower(clear_cadena($strNombre));
				$ruta = str_replace(" ", "-", $ruta);

				if ($idProducto == 0) {
					$option = 1;
					if ($_SESSION['permisosMod']['w']) {
						$request_producto = $this->model->insertProducto(
							$strNombre,
							$strDescripcion,
							$strCodigo,
							$intCategoriaId,
							$intUnidadId,
							$strPrecio,
							$intIva,
							$ruta,
							$intStatus
						);
					}
				} else {
					$option = 2;
					if ($_SESSION['permisosMod']['u']) {
						$request_producto = $this->model->updateProducto(
							$idProducto,
							$strNombre,
							$strDescripcion,
							$strCodigo,
							$intCategoriaId,
							$intUnidadId,
							$strPrecio,
							$intIva,
							$ruta,
							$intStatus
						);
					}
				}
				if ($request_producto > 0) {
					if ($option == 1) {
						$guarda_existencia = $this->model->insertarExistencia($strCodigo, $idProducto);

						$arrResponse = array('status' => true, 'idproducto' => $request_producto, 'msg' => 'Datos guardados correctamente.');
					} else {
						$arrResponse = array('status' => true, 'idproducto' => $idProducto, 'msg' => 'Datos Actualizados correctamente.');
					}
				} else if ($request_producto == 'exist') {
					$arrResponse = array('status' => false, 'msg' => '¡Atención! ya existe un producto con el Código Ingresado.');
				} else {
					$arrResponse = array("status" => false, "msg" => 'No es posible almacenar los datos.');
				}
			}
			echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
		}
		die();
	}
	public function getProducto($idproducto)
	{
		if ($_SESSION['permisosMod']['r']) {
			$idproducto = intval($idproducto);
			if ($idproducto > 0) {
				$arrData = $this->model->selectProducto($idproducto);
				if (empty($arrData)) {
					$arrResponse = array('status' => false, 'msg' => 'Datos no encontrados.');
				} else {
					$arrImg = $this->model->selectImages($idproducto);
					if (count($arrImg) > 0) {
						for ($i = 0; $i < count($arrImg); $i++) {
							$arrImg[$i]['url_image'] = media() . '/images/uploads/' . $arrImg[$i]['img'];
						}
					}
					$arrData['images'] = $arrImg;
					$arrResponse = array('status' => true, 'data' => $arrData);
				}
				echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
			}
		}
		die();
	}

	public function setImage()
	{
		if ($_POST) {
			if (empty($_POST['idproducto'])) {
				$arrResponse = array('status' => false, 'msg' => 'Error de dato.');
			} else {
				$idProducto = intval($_POST['idproducto']);
				$foto      = $_FILES['foto'];
				$imgNombre = 'pro_' . md5(date('d-m-Y H:i:s')) . '.jpg';
				$request_image = $this->model->insertImage($idProducto, $imgNombre);
				if ($request_image) {
					$uploadImage = uploadImage($foto, $imgNombre);
					$arrResponse = array('status' => true, 'imgname' => $imgNombre, 'msg' => 'Archivo cargado.');
				} else {
					$arrResponse = array('status' => false, 'msg' => 'Error de carga.');
				}
			}
			echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
		}
		die();
	}

	public function delFile()
	{
		if ($_POST) {
			if (empty($_POST['idproducto']) || empty($_POST['file'])) {
				$arrResponse = array("status" => false, "msg" => 'Datos incorrectos.');
			} else {
				//Eliminar de la DB
				$idProducto = intval($_POST['idproducto']);
				$imgNombre  = limpiaCadena($_POST['file']);
				$request_image = $this->model->deleteImage($idProducto, $imgNombre);

				if ($request_image) {
					$deleteFile =  deleteFile($imgNombre);
					$arrResponse = array('status' => true, 'msg' => 'Archivo eliminado');
				} else {
					$arrResponse = array('status' => false, 'msg' => 'Error al eliminar');
				}
			}
			echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
		}
		die();
	}

	public function delProducto()
	{
		// dep($_POST);
		// die();
		if ($_POST) {
			if ($_SESSION['permisosMod']['d']) {
				$intIdproducto = intval($_POST['idProducto']);
				$requestDelete = $this->model->deleteProducto($intIdproducto);
				if ($requestDelete) {
					$arrResponse = array('status' => true, 'msg' => 'Se ha eliminado el producto');
				} else {
					$arrResponse = array('status' => false, 'msg' => 'Error al eliminar el producto.');
				}
				echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
			}
		}
		die();
	}


	public function activarProducto()
	{
		if ($_POST) {
			if ($_SESSION['permisosMod']['d']) {
				$intIdproducto = intval($_POST['idproducto']);
				$requestDelete = $this->model->activarProducto($intIdproducto);
				if ($requestDelete) {
					$arrResponse = array('status' => true, 'msg' => 'Se ha activado el producto');
				} else {
					$arrResponse = array('status' => false, 'msg' => 'Error al activar el producto.');
				}
				echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
			}
		}
		die();
	}
}
