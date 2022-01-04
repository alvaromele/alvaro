<?php

class ComprasModel extends Mysql
{
    private $IdCompra;
    private $Numero;
    private $Total;
    private $TotalIva;
    private $idUsuario;
    private $idProveedor;
    private $Fecha;
    private $Estado;

    public function __construct()
    {
        parent::__construct();
    }

    public function codigo()
    {
        $sql = "SELECT MAX(numcompra) as codigo  FROM compras";
        $request = $this->select($sql);
        return $request;
    }

    public function selectCompras()
    {
        $sql = "SELECT c.*, p.nombre as proveedor, p.idprov as idProv,per.nombres as usuario,
                DATE_FORMAT(c.fecha_compra,'%d/%m/%Y') as fecha
                FROM compras c 
                INNER JOIN proveedores p 
                ON c.proveedorid = p.idprov
                INNER JOIN persona per 
                ON per.idpersona = c.usuarioid
                WHERE c.estado != 0 ORDER BY c.numcompra DESC ";
        $respuesta = $this->select_all($sql);
        return $respuesta;
    }
}
