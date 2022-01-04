<?php

class UsuariosModel extends Mysql
{
	private $intIdUsuario;
	private $strIdentificacion;
	private $strNombre;
	private $strApellido;
	private $intTelefono;
	private $strEmail;
	private $strPassword;
	private $strNit;
	private $strDirFiscal;
	private $strNomFiscal;
	private $strContacto;
	private $strTelContacto;
	private $strEmailContacto;
	private $strToken;
	private $intRolId;
	private $inFechaCrea;
	private $intEstado;




	public function __construct()
	{
		parent::__construct();
	}

	public function insertUsuario(string $identificacion, string $nombre, string $apellido, int $telefono, string $email, string $password, int $tipoid, int $estado)
	{

		$this->strIdentificacion = $identificacion;
		$this->strNombre = $nombre;
		$this->strApellido = $apellido;
		$this->intTelefono = $telefono;
		$this->strEmail = $email;
		$this->strPassword = $password;
		$this->intTipoId = $tipoid;
		$this->intStatus = $estado;
		$return = 0;

		$sql = "SELECT * FROM usuarios WHERE 
					email_user = '{$this->strEmail}' or identificacion = '{$this->strIdentificacion}' ";
		$request = $this->select_all($sql);

		if (empty($request)) {
			$query_insert  = "INSERT INTO usuarios(identificacion,nombres,apellidos,telefono,email_user,password,rolid,estado) 
								  VALUES(?,?,?,?,?,?,?,?)";
			$arrData = array(
				$this->strIdentificacion,
				$this->strNombre,
				$this->strApellido,
				$this->intTelefono,
				$this->strEmail,
				$this->strPassword,
				$this->intTipoId,
				$this->intStatus
			);
			$request_insert = $this->insert($query_insert, $arrData);
			$return = $request_insert;
		} else {
			$return = "exist";
		}
		return $return;
	}

	public function selectUsuarios()
	{
		$whereAdmin = "";
		if ($_SESSION['idUser'] != 1) {
			$whereAdmin = " and u.idusuario != 1 ";
		}
		$sql = "SELECT u.idusuario,u.identificacion,u.nombres,u.apellidos,u.telefono,u.email_user,u.estado,r.idrol,r.nombrerol as nombrerol
					FROM usuarios u 
					INNER JOIN rol r
					ON u.rolid = r.idrol
					WHERE u.estado != 2 AND rolid != 5 " . $whereAdmin;
		$request = $this->select_all($sql);
		return $request;
	}

	public function selectUsuariosBorrados()
	{
		$whereAdmin = "";
		if ($_SESSION['idUser'] != 1) {
			$whereAdmin = " and u.idusuario != 1 ";
		}
		$sql = "SELECT u.idusuario,u.identificacion,u.nombres,u.apellidos,u.telefono,u.email_user,u.estado,r.idrol,r.nombrerol 
					FROM usuarios u 
					INNER JOIN rol r
					ON u.rolid = r.idrol
					WHERE u.estado = 2 " . $whereAdmin;
		$request = $this->select_all($sql);
		return $request;
	}
	public function selectUsuario(int $idusuario)
	{
		$this->intIdUsuario = $idusuario;
		$sql = "SELECT u.idusuario,u.identificacion,u.nombres,u.apellidos,u.telefono,u.email_user,u.nit,u.nombrefiscal,u.direccionfiscal,r.idrol,r.nombrerol,u.estado, DATE_FORMAT(u.fechacrea, '%d-%m-%Y') as fechaRegistro 
					FROM usuarios u
					INNER JOIN rol r
					ON u.rolid = r.idrol
					WHERE u.idusuario = $this->intIdUsuario";
		$request = $this->select($sql);
		return $request;
	}

	public function updateUsuario(int $idUsuario, string $identificacion, string $nombre, string $apellido,  string $email, string $password, int $tipoid, int $estado)
	{

		$this->intIdUsuario = $idUsuario;
		$this->strIdentificacion = $identificacion;
		$this->strNombre = $nombre;
		$this->strApellido = $apellido;
		// $this->intTelefono = $telefono;
		$this->strEmail = $email;
		$this->strPassword = $password;
		$this->intTipoId = $tipoid;
		$this->intStatus = $estado;

		$sql = "SELECT * FROM usuarios WHERE (email_user = '{$this->strEmail}' AND idusuario != $this->intIdUsuario)
										  OR (identificacion = '{$this->strIdentificacion}' AND idusuario != $this->intIdUsuario) ";
		$request = $this->select_all($sql);

		if (empty($request)) {
			if ($this->strPassword  != "") {
				$sql = "UPDATE usuarios SET identificacion=?, nombres=?, apellidos=?, telefono=?, email_user=?, password=?, rolid=?, estado=? 
							WHERE idusuario = $this->intIdUsuario ";
				$arrData = array(
					$this->strIdentificacion,
					$this->strNombre,
					$this->strApellido,
					$this->intTelefono,
					$this->strEmail,
					$this->strPassword,
					$this->intTipoId,
					$this->intStatus
				);
			} else {
				$sql = "UPDATE usuarios SET identificacion=?, nombres=?, apellidos=?, telefono=?, email_user=?, rolid=?, estado=? 
							WHERE idusuario = $this->intIdUsuario ";
				$arrData = array(
					$this->strIdentificacion,
					$this->strNombre,
					$this->strApellido,
					$this->intTelefono,
					$this->strEmail,
					$this->intTipoId,
					$this->intStatus
				);
			}
			$request = $this->update($sql, $arrData);
		} else {
			$request = "exist";
		}
		return $request;
	}
	public function deleteUsuario(int $intIdusuario)
	{
		$this->intIdUsuario = $intIdusuario;
		$sql = "UPDATE usuarios SET estado = ? WHERE idusuario = $this->intIdUsuario ";
		$arrData = array(2);
		$request = $this->update($sql, $arrData);
		return $request;
	}

	public function activarUsuario(int $intIdusuario)
	{
		$this->intIdUsuario = $intIdusuario;
		$sql = "UPDATE usuarios SET estado = ? WHERE idusuario = $this->intIdUsuario ";
		$arrData = array(1);
		$request = $this->update($sql, $arrData);
		return $request;
	}

	public function updatePerfil(int $idUsuario, string $identificacion, string $nombre, string $apellido, int $telefono, string $password)
	{
		$this->intIdUsuario = $idUsuario;
		$this->strIdentificacion = $identificacion;
		$this->strNombre = $nombre;
		$this->strApellido = $apellido;
		$this->intTelefono = $telefono;
		$this->strPassword = $password;

		if ($this->strPassword != "") {
			$sql = "UPDATE usuarios SET identificacion=?, nombres=?, apellidos=?, telefono=?, password=? 
						WHERE idusuario = $this->intIdUsuario ";
			$arrData = array(
				$this->strIdentificacion,
				$this->strNombre,
				$this->strApellido,
				$this->intTelefono,
				$this->strPassword
			);
		} else {
			$sql = "UPDATE usuarios SET identificacion=?, nombres=?, apellidos=?, telefono=? 
						WHERE idusuario = $this->intIdUsuario ";
			$arrData = array(
				$this->strIdentificacion,
				$this->strNombre,
				$this->strApellido,
				$this->intTelefono
			);
		}
		$request = $this->update($sql, $arrData);
		return $request;
	}

	public function updateDataFiscal(int $idUsuario, string $strNit, string $strNomFiscal, string $strDirFiscal)
	{
		$this->intIdUsuario = $idUsuario;
		$this->strNit = $strNit;
		$this->strNomFiscal = $strNomFiscal;
		$this->strDirFiscal = $strDirFiscal;
		$sql = "UPDATE usuarios SET nit=?, nombrefiscal=?, direccionfiscal=? 
						WHERE idusuario = $this->intIdUsuario ";
		$arrData = array(
			$this->strNit,
			$this->strNomFiscal,
			$this->strDirFiscal
		);
		$request = $this->update($sql, $arrData);
		return $request;
	}
}
