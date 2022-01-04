<?php

class ArbolModel extends Mysql
{
   public $intIdpais;
   public $strNombre;
   public $intPadre;


   public function __construct()
   {
      parent::__construct();
   }

   //Busca todos los Roles de la BD
   public function seleccionarPaises()
   {

      $sql = "SELECT * FROM paises";
      $respuesta = $this->select_all($sql);
      return $respuesta;
   }
}
