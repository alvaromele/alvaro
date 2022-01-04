<?php

class ExistenciasModel extends Mysql
{
    private $idexit;
    private $codproducto;
    private $idProducto;
    private $total;
    private $cantidad;

    public function __construct()
    {
        parent::__construct();
    }

    public function getExistencias()
    {
        $sql = "SELECT e.*, p.nombre as producto,u.unidad as unidad
                FROM existencias e 
                INNER JOIN productos p 
                ON e.idproducto = p.idproducto
                INNER JOIN unidades u
                ON p.unidadid = u.idunidad
                WHERE e.cantidad > 0 ORDER BY p.nombre DESC ";
        $respuesta = $this->select_all($sql);
        return $respuesta;
    }
}
