<?php
require_once("Libraries/Core/Mysql.php");
trait TProveedor
{
	private $con;
	private $intIdProveedor;
	private $strNombre;
	private $strCorreo;
	private $strCorreo1;
	private $strDireccion;
	private $intTelefono;
	private $intTelefono1;
	private $strPassword;
	private $strContacto;
	private $strToken;
	private $intRolId;
	private $intEstado;
	private $strFecha;
	private $token;

	public function getProveedoresT()
	{
		$this->con = new Mysql();
		$sql = "SELECT *
					FROM proveedores
					WHERE estado != 2  ORDER BY nombre ASC ";
		$request = $this->con->select_all($sql);
		return $request;
	}

	public function insertProveedorT(string $nombre, string $correo, string $correo1, string $direccion, int $telefono, int $telefono1, string $password, string $contacto, int $rolid)
	{
		$this->con = new Mysql();

		$this->strNombre = $nombre;
		$this->strCorreo = $correo;
		$this->strCorreo1 = $correo1;
		$this->strDireccion = $direccion;
		$this->intTelefono = $telefono;
		$this->intTelefono1 = $telefono1;
		$this->strPassword = $password;
		$this->strContacto = $contacto;
		$this->intRolId = $rolid;

		$return = 0;
		$sql = "SELECT * FROM persona WHERE 
				email_user = '{$this->strCorreo}' ";
		$request = $this->con->select_all($sql);

		if (empty($request)) {
			$query_insert  = "INSERT INTO persona(nombres,correo,correo1,direccion,telefono,telefono1,password,contacto,rolid) 
							  VALUES(?,?,?,?,?,?,?,?,?)";
			$arrData = array(
				$this->strNombre,
				$this->strCorreo,
				$this->strCorreo1,
				$this->strDireccion,
				$this->intTelefono,
				$this->intTelefono1,
				$this->strPassword,
				$this->strContacto,
				$this->intRolId
			);
			$request_insert = $this->con->insert($query_insert, $arrData);
			$return = $request_insert;
		} else {
			$return = "exist";
		}
		return $return;
	}

	public function insertPedido(string $idtransaccionpaypal = NULL, string $datospaypal = NULL, int $personaid, float $costo_envio, string $monto, int $tipopagoid, string $direccionenvio, string $status)
	{
		$this->con = new Mysql();
		$query_insert  = "INSERT INTO pedido(idtransaccionpaypal,datospaypal,personaid,costo_envio,monto,tipopagoid,direccion_envio,status) 
							  VALUES(?,?,?,?,?,?,?,?)";
		$arrData = array(
			$idtransaccionpaypal,
			$datospaypal,
			$personaid,
			$costo_envio,
			$monto,
			$tipopagoid,
			$direccionenvio,
			$status
		);
		$request_insert = $this->con->insert($query_insert, $arrData);
		$return = $request_insert;
		return $return;
	}

	public function insertDetalle(int $idpedido, int $productoid, float $precio, int $cantidad)
	{
		$this->con = new Mysql();
		$query_insert  = "INSERT INTO detalle_pedido(pedidoid,productoid,precio,cantidad) 
							  VALUES(?,?,?,?)";
		$arrData = array(
			$idpedido,
			$productoid,
			$precio,
			$cantidad
		);
		$request_insert = $this->con->insert($query_insert, $arrData);
		$return = $request_insert;
		return $return;
	}

	public function insertDetalleTemp(array $pedido)
	{
		$this->intIdProveedor = $pedido['idcliente'];
		$this->intIdTransaccion = $pedido['idtransaccion'];
		$productos = $pedido['productos'];

		$this->con = new Mysql();
		$sql = "SELECT * FROM detalle_temp WHERE 
					transaccionid = '{$this->intIdTransaccion}' AND 
					personaid = $this->intIdProveedor";
		$request = $this->con->select_all($sql);

		if (empty($request)) {
			foreach ($productos as $producto) {
				$query_insert  = "INSERT INTO detalle_temp(personaid,productoid,precio,cantidad,transaccionid) 
								  VALUES(?,?,?,?,?)";
				$arrData = array(
					$this->intIdProveedor,
					$producto['idproducto'],
					$producto['precio'],
					$producto['cantidad'],
					$this->intIdTransaccion
				);
				$request_insert = $this->con->insert($query_insert, $arrData);
			}
		} else {
			$sqlDel = "DELETE FROM detalle_temp WHERE 
				transaccionid = '{$this->intIdTransaccion}' AND 
				personaid = $this->intIdProveedor";
			$request = $this->con->delete($sqlDel);
			foreach ($productos as $producto) {
				$query_insert  = "INSERT INTO detalle_temp(personaid,productoid,precio,cantidad,transaccionid) 
								  VALUES(?,?,?,?,?)";
				$arrData = array(
					$this->intIdProveedor,
					$producto['idproducto'],
					$producto['precio'],
					$producto['cantidad'],
					$this->intIdTransaccion
				);
				$request_insert = $this->con->insert($query_insert, $arrData);
			}
		}
	}

	public function getPedido(int $idpedido)
	{
		$this->con = new Mysql();
		$request = array();
		$sql = "SELECT p.idpedido,
							p.referenciacobro,
							p.idtransaccionpaypal,
							p.personaid,
							p.fecha,
							p.costo_envio,
							p.monto,
							p.tipopagoid,
							t.tipopago,
							p.direccion_envio,
							p.status
					FROM pedido as p
					INNER JOIN tipopago t
					ON p.tipopagoid = t.idtipopago
					WHERE p.idpedido =  $idpedido";
		$requestPedido = $this->con->select($sql);
		if (count($requestPedido) > 0) {
			$sql_detalle = "SELECT p.idproducto,
											p.nombre as producto,
											d.precio,
											d.cantidad
									FROM detalle_pedido d
									INNER JOIN producto p
									ON d.productoid = p.idproducto
									WHERE d.pedidoid = $idpedido
									";
			$requestProductos = $this->con->select_all($sql_detalle);
			$request = array(
				'orden' => $requestPedido,
				'detalle' => $requestProductos
			);
		}
		return $request;
	}

	public function setSuscripcion(string $nombre, string $email)
	{
		$this->con = new Mysql();
		$sql = 	"SELECT * FROM suscripciones WHERE email = '{$email}'";
		$request = $this->con->select_all($sql);
		if (empty($request)) {
			$query_insert  = "INSERT INTO suscripciones(nombre,email) 
							  VALUES(?,?)";
			$arrData = array($nombre, $email);
			$request_insert = $this->con->insert($query_insert, $arrData);
			$return = $request_insert;
		} else {
			$return = false;
		}
		return $return;
	}

	public function setContacto(string $nombre, string $email, string $mensaje, string $ip, string $dispositivo, string $useragent)
	{
		$this->con = new Mysql();
		$nombre  	 = $nombre != "" ? $nombre : "";
		$email 		 = $email != "" ? $email : "";
		$mensaje	 = $mensaje != "" ? $mensaje : "";
		$ip 		 = $ip != "" ? $ip : "";
		$dispositivo = $dispositivo != "" ? $dispositivo : "";
		$useragent 	 = $useragent != "" ? $useragent : "";
		$query_insert  = "INSERT INTO contacto(nombre,email,mensaje,ip,dispositivo,useragent) 
						  VALUES(?,?,?,?,?,?)";
		$arrData = array($nombre, $email, $mensaje, $ip, $dispositivo, $useragent);
		$request_insert = $this->con->insert($query_insert, $arrData);
		return $request_insert;
	}
}
