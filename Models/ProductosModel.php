<?php

class ProductosModel extends Mysql
{
	private $intIdProducto;
	private $strNombre;
	private $strDescripcion;
	private $intCodigo;
	private $intCategoriaId;
	private $intUnidadId;
	private $intPrecio;
	private $intIva;
	private $intStatus;
	private $strRuta;
	private $strImagen;

	public function __construct()
	{
		parent::__construct();
	}

	public function codigo()
	{
		$sql = "SELECT MAX(codigo) as codigo  FROM productos";
		$request = $this->select($sql);
		return $request;
	}

	public function selectProductos()
	{
		$sql = "SELECT p.idproducto,
							p.codigo,
							p.nombre,
							p.descripcion,
							p.categoriaid,
							p.unidadid,
							c.nombre as categoria,
							c.idcategoria,
							u.descripcion as unidad,
							u.idunidad,
							p.precio,
							p.iva,
							p.status 
					FROM productos p 
					INNER JOIN categorias c
					ON p.categoriaid = c.idcategoria
					INNER JOIN unidades u
					ON p.unidadid = u.idunidad
					WHERE p.status != 0  ORDER BY p.nombre ASC";
		$request = $this->select_all($sql);
		return $request;
	}

	public function insertProducto(string $nombre, string $descripcion, int $codigo, int $categoriaid, int $unidadid, string $precio, int $Iva, string $ruta, int $status)
	{
		$this->strNombre = $nombre;
		$this->strDescripcion = $descripcion;
		$this->intCodigo = $codigo;
		$this->intCategoriaId = $categoriaid;
		$this->intUnidadId = $unidadid;
		$this->strPrecio = $precio;
		$this->intIva = $Iva;
		$this->strRuta = $ruta;
		$this->intStatus = $status;
		$return = 0;
		$sql = "SELECT * FROM productos WHERE codigo = '{$this->intCodigo}'";
		$request = $this->select_all($sql);
		if (empty($request)) {
			$query_insert  = "INSERT INTO productos(unidadid,categoriaid,
														codigo,
														nombre,
														descripcion,
														precio,
														iva,
														ruta,
														status) 
								  VALUES(?,?,?,?,?,?,?,?,?)";
			$arrData = array(
				$this->intUnidadId,
				$this->intCategoriaId,
				$this->intCodigo,
				$this->strNombre,
				$this->strDescripcion,
				$this->strPrecio,
				$this->intIva,
				$this->strRuta,
				$this->intStatus
			);
			$request_insert = $this->insert($query_insert, $arrData);
			$return = $request_insert;
		} else {
			$return = "exist";
		}
		return $return;
	}
	public function insertarExistencia(int $codigo, int $idproducto)
	{

		$this->intCodigo = $codigo;
		$this->intCategoriaId = $idproducto;
		$this->strPrecio = 0;
		$this->intCantidad = 0;


		$query_insert  = "INSERT INTO existencias(codproducto,idproducto,cantidad,total) 
								  VALUES(?,?,?,?)";
		$arrData = array(

			$this->intCodigo,
			$this->intCategoriaId,
			$this->intCantidad,
			$this->strPrecio,
		);
		$request_insert = $this->insert($query_insert, $arrData);
	}

	public function updateProducto(int $idproducto, string $nombre, string $descripcion, int $codigo, int $categoriaid, int $unidadid, string $precio, int $Iva, string $ruta, int $status)
	{
		$this->intIdProducto = $idproducto;
		$this->strNombre = $nombre;
		$this->strDescripcion = $descripcion;
		$this->intCodigo = $codigo;
		$this->intCategoriaId = $categoriaid;
		$this->intUnidadId = $unidadid;
		$this->strPrecio = $precio;
		$this->intIva = $Iva;
		$this->strRuta = $ruta;
		$this->intStatus = $status;
		$return = 0;
		$sql = "SELECT * FROM productos WHERE codigo = '{$this->intCodigo}' AND idproducto != $this->intIdProducto ";
		$request = $this->select_all($sql);
		if (empty($request)) {
			$sql = "UPDATE productos 
						SET unidadid=?,categoriaid=?,
							codigo=?,
							nombre=?,
							descripcion=?,
							precio=?,
							iva=?,
							ruta=?,
							status=? 
						WHERE idproducto = $this->intIdProducto ";
			$arrData = array(
				$this->intUnidadId,
				$this->intCategoriaId,
				$this->intCodigo,
				$this->strNombre,
				$this->strDescripcion,
				$this->strPrecio,
				$this->intIva,
				$this->strRuta,
				$this->intStatus
			);

			$request = $this->update($sql, $arrData);
			$return = $request;
		} else {
			$return = "exist";
		}
		return $return;
	}

	public function selectProducto(int $idproducto)
	{
		$this->intIdProducto = $idproducto;
		$sql = "SELECT p.*,
						c.nombre as categoria,
						c.idcategoria,
						u.descripcion as unidad,
						u.idunidad		
					FROM productos p
					INNER JOIN categorias c
					ON p.categoriaid = c.idcategoria
					INNER JOIN unidades u
					ON p.unidadid = u.idunidad
					WHERE idproducto = $this->intIdProducto";
		$request = $this->select($sql);
		return $request;
	}

	public function insertImage(int $idproducto, string $imagen)
	{
		$this->intIdProducto = $idproducto;
		$this->strImagen = $imagen;
		$query_insert  = "INSERT INTO imagen(productoid,img) VALUES(?,?)";
		$arrData = array(
			$this->intIdProducto,
			$this->strImagen
		);
		$request_insert = $this->insert($query_insert, $arrData);
		return $request_insert;
	}

	public function selectImages(int $idproducto)
	{
		$this->intIdProducto = $idproducto;
		$sql = "SELECT productoid,img
					FROM imagen
					WHERE productoid = $this->intIdProducto";
		$request = $this->select_all($sql);
		return $request;
	}

	public function deleteImage(int $idproducto, string $imagen)
	{
		$this->intIdProducto = $idproducto;
		$this->strImagen = $imagen;
		$query  = "DELETE FROM imagen 
						WHERE productoid = $this->intIdProducto 
						AND img = '{$this->strImagen}'";
		$request_delete = $this->delete($query);
		return $request_delete;
	}

	public function deleteProducto(int $idproducto)
	{
		$this->intIdProducto = $idproducto;
		$sql = "UPDATE productos SET status = ? WHERE idproducto = $this->intIdProducto ";
		$arrData = array(2);
		$request = $this->update($sql, $arrData);
		return $request;
	}
	public function selectProductosBorrados()
	{
		$sql = "SELECT p.idproducto,
							p.codigo,
							p.nombre,
							p.descripcion,
							p.categoriaid,
							p.unidadid,
							c.nombre as categoria,
							c.idcategoria,
							u.descripcion as unidad,
							u.idunidad,
							p.precio,
							p.iva,
							p.status 
					FROM productos p 
					INNER JOIN categorias c
					ON p.categoriaid = c.idcategoria
					INNER JOIN unidades u
					ON p.unidadid = u.idunidad
					WHERE p.status = 2 ";
		$request = $this->select_all($sql);
		return $request;
	}

	public function activarProducto(int $idproducto)
	{
		$this->intIdProducto = $idproducto;
		$sql = "UPDATE productos SET status = ? WHERE idproducto = $this->intIdProducto ";
		$arrData = array(1);
		$request = $this->update($sql, $arrData);
		return $request;
	}
}
