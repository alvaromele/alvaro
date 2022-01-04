<?php

class Arbol extends Controllers
{

  public function __construct()
  {
    parent::__construct();
  }

  public function arbol()
  {
    $data = $this->model->seleccionarPaises();
    // $datos['page_id'] = 3;
    // $datos['page_etiqueta'] = "Unidades";
    // $datos["page_nombre"] = "unidades";
    // $datos['page_titulo'] = "Unidades";
    // $data['page_funciones_js'] = "funciones_arbol.js";

    // $this->views->getView($this, "categorias", $data);
    $this->views->getView($this, "arbol", $data);
  }
  // public function obtenerPaises()
  // {

  //   $data = $this->model->seleccionarPaises();
  //   // echo(count($data));

  //   // for ($i = 0; $i < count($data); $i++){
  //   //   dep($data[$i]['name']);
  //   // }
  //   $i = 0;
  //   while ($i < count($data)) {
  //     //  dep($data[$i]['id']);
  //     $data1['id'] = $data[$i]['id'];
  //     $data1['nombre'] = $data[$i]['name'];
  //     $data1['texto'] = $data[$i]['name'];
  //     $data1['padreid'] = $data[$i]['padreid'];
  //     $data2[] =  $data1;
  //     $i++;
  //   }
  //   foreach ($data as $key => &$value) {
  //     $output[$value["id"]] = &$value;
  //   }
  //   foreach ($data as $key => &$value) {
  //     if ($value["padreid"] && isset($output[$value["padreid"]])) {
  //       $output[$value["padreid"]]["nodes"][] = &$value;
  //     }
  //   }
  //   foreach ($data as $key => &$value) {
  //     if ($value["padreid"] && isset($output[$value["padreid"]])) {
  //       unset($data[$key]);
  //     }
  //   }
  //   //  dep($data);

  //   // echo json_encode($data);

  //   //Convertir $arrDatos a formato JSON
  //   echo json_encode($data, JSON_UNESCAPED_UNICODE);

  //   // die();
  // }
}
