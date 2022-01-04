<?php

class UnidadesModel extends Mysql
{
	public $intIdUnidad;
	public $strUnidad;
	public $strDescripcion;
	public $intStatus;
	public $strPortada;
	public $strRuta;

	public function __construct()
	{
		parent::__construct();
	}

	public function insertarUnidad(string $unidad, string $descripcion)
	{

		$return = 0;
		$this->strUnidad = strtoupper($unidad);
		$this->strDescripcion = $descripcion;


		$sql = "SELECT * FROM unidades WHERE unidad = '{$this->strUnidad}' ";
		$request = $this->select_all($sql);

		if (empty($request)) {
			$query_insert  = "INSERT INTO unidades(unidad,descripcion,estado) VALUES(?,?,?)";
			$arrData = array(
				$this->strUnidad,
				$this->strDescripcion,
				$this->intStatus
			);
			$request_insert = $this->insert($query_insert, $arrData);
			$return = $request_insert;
		} else {
			$return = "exist";
		}
		return $return;
	}

	public function selectUnidades()
	{
		$sql = "SELECT * FROM unidades 
					WHERE estado != 2 ";
		$request = $this->select_all($sql);
		return $request;
	}


	public function selectUnidadesBorradas()
	{
		$sql = "SELECT * FROM unidades 
					WHERE estado = 2 ";
		$request = $this->select_all($sql);
		return $request;
	}
	public function selectUnidad(int $idunidad)
	{
		$this->intIdUnidad = $idunidad;
		$sql = "SELECT * FROM unidades
					WHERE idunidad = $this->intIdUnidad";
		$request = $this->select($sql);
		return $request;
	}

	public function actualizarUnidad(int $idunidad, string $unidad, string $descripcion)
	{
		$this->intIdUnidad = $idunidad;
		$this->strUnidad = strtoupper($unidad);
		$this->strDescripcion = $descripcion;


		$sql = "SELECT * FROM unidades WHERE unidad = '{$this->strUnidad}' AND idunidad != $this->intIdUnidad";
		$request = $this->select($sql);

		if (empty($request)) {
			$sql = "UPDATE unidades SET unidad = ?, descripcion = ?
					WHERE idunidad = $this->intIdUnidad ";
			$arrData = array(
				$this->strUnidad,
				$this->strDescripcion,
			);
			$request = $this->update($sql, $arrData);
		} else {
			$request = "exist";
		}
		return $request;
	}

	public function deleteUnidad(int $idunidad)
	{
		$this->intIdUnidad = $idunidad;
		$sql = "SELECT * FROM productos WHERE unidadid = $this->intIdUnidad";
		$request = $this->select_all($sql);
		if (empty($request)) {
			$sql = "UPDATE unidades SET estado = ? WHERE idunidad = $this->intIdUnidad ";
			$arrData = array(2);
			$request = $this->update($sql, $arrData);
			if ($request) {
				$request = 'ok';
			} else {
				$request = 'error';
			}
		} else {
			$request = 'exist';
		}
		return $request;
	}

	public function activarUnidad(int $idunidad)
	{
		$this->intIdUnidad = $idunidad;
		$sql = "UPDATE unidades SET estado = ? WHERE idunidad = $this->intIdUnidad ";
		$arrData = array(1);
		$request = $this->update($sql, $arrData);
		if ($request) {
			$request = 'ok';
		} else {
			$request = 'error';
		}

		$request = 'exist';

		return $request;
	}

	public function getCategoriasFooter()
	{
		$sql = "SELECT idcategoria, unidad, descripcion, portada, ruta
					FROM unidades WHERE  estado = 1 AND idcategoria IN (" . CAT_FOOTER . ")";
		$request = $this->select_all($sql);
		if (count($request) > 0) {
			for ($c = 0; $c < count($request); $c++) {
				$request[$c]['portada'] = BASE_URL . '/Assets/images/uploads/' . $request[$c]['portada'];
			}
		}
		return $request;
	}
}
