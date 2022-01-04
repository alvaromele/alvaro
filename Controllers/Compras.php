<?php
// // require_once("Models/TCategoria.php");
require_once("Models/TaProducto.php");
require_once("Models/TProveedor.php");


class Compras extends Controllers
{
    use TaProducto;
    use TProveedor;

    public function __construct()
    {
        parent::__construct();
        session_start();
    }
    public function Compras()
    {
        // Validar Permisos
        if (empty($_SESSION['permisosMod']['r'])) {
            header("Location:" . base_url() . '/Dashboard');
        }

        // $numMax = $this->model->codigo();	

        // if($numMax['codigo'] == null){
        // 	$numMax['codigo'] = CCUENTA;
        // }
        // $empresas = $this->model->empresas();

        $data['page_tag'] = "Compras";
        $data['page_title'] = "Listado Compras ";
        $data["page_name"] = "cuentas";
        $data['page_functions_js'] = "functions_compras.js";
        // $datos['codigo'] = $numMax;
        // $datos['empresas'] = $empresas;

        $this->views->getView($this, "compras", $data);
    }

    // Función para mostrar los datos de las cuentas en DataTables 
    public function getCompras()
    {
        if ($_SESSION['permisosMod']['r']) {
            $arrDatos = $this->model->selectCompras();

            // dep($arrDatos);
            // exit;

            for ($i = 0; $i < count($arrDatos); $i++) {
                $btnVer = '';
                $btnEditar = '';
                $btnBorrar = '';

                $arrDatos[$i]['total'] = SMONEY . formatoMoneda($arrDatos[$i]['total']);
                // $arrDatos[$i]['reteica'] = SMONEDA.formatoMoneda($arrDatos[$i]['reteica']);
                // $arrDatos[$i]['retefuente'] = SMONEDA.formatoMoneda($arrDatos[$i]['retefuente']);
                // $arrDatos[$i]['total'] = SMONEDA.formatoMoneda($arrDatos[$i]['total']);


                //Validar los permisos

                if ($_SESSION['permisosMod']['w']) {

                    $btnVer .= '<a title="Ver Compra" href="' . base_url() . '/Compras/compra/' . $arrDatos[$i]['idcompra'] . '" target= "_blank" class="btn btn-info btn-sm"><i class="fas fa-eye fa-1x"></i></a>

                    <button class="btn btn-danger btn-sm" onClick="funcVerPdf(' . $arrDatos[$i]['idcompra'] . ')" title="Generar PDF"><i class="fas fa-file-pdf"></i></button>';

                    $btnEdit = '<button  class="btn btn-primary btn-sm btnEditUsuario" onClick="funcVerInfo(' . $arrDatos[$i]['idcompra'] . ')" title="Editar compra"><i class="fas fa-pencil-alt fa-1x"></i></button>';
                }

                // Poner los botones en Data Tables
                $arrDatos[$i]['opciones'] = '<div class="text-center">' . $btnVer . ' ' . $btnEdit . ' </div>';
                // $arrDatos[$i]['opciones'] = '<div class="text-center">' . $btnVer . ' </div>';
            }
            //Convertir $arrDatos a formato JSON
            echo json_encode($arrDatos, JSON_UNESCAPED_UNICODE);
        }
        die();
    }

    public function pedido()
    {
        if (empty($_SESSION['permisosMod']['r'])) {
            header("Location:" . base_url() . '/dashboard');
        }
        $numMax = $this->model->codigo();
        if ($numMax['codigo'] == null) {
            $numMax['codigo'] = CCUENTA;
        }
        // dep($this->selectProductos());
        // exit;
        $data['page_tag'] = "Pedidos";
        $data['page_title'] = "PEDIDO";
        $data['derecha'] = "Listado Compras";
        $data['page_name'] = "compras";
        $data['productos'] = $this->getProductosT();
        $data['proveedores'] = $this->getProveedoresT();
        $data['codigo'] = $numMax;
        $data['page_functions_js'] = "functions_compras.js";
        $this->views->getView($this, "pedido", $data);
    }

    public function addOrden()
    {
        if ($_POST) {
            // dep($_POST);
            // die();
            $arrCompra = array();
            $idProducto = $_POST['id'];
            $cantidad = $_POST['cant'];
            if (is_numeric($idProducto) and is_numeric($cantidad)) {
                $arrInfoProducto = $this->getProductoIDT($idProducto);
                // dep($arrInfoProducto);
                // exit;
                if (!empty($arrInfoProducto)) {
                    $arrProducto = array(
                        'idProducto' => $idProducto,
                        'producto' => $arrInfoProducto['nombre'],
                        'unidad' => $arrInfoProducto['unidad'],
                        'cantidad' => $cantidad,
                        'precio' => $arrInfoProducto['precio'],
                        'iva' => $arrInfoProducto['iva']
                    );
                    // dep($arrProducto);
                    // exit;
                    if (isset($_SESSION['arrCompra'])) {
                        $on = true;
                        $arrCompra = $_SESSION['arrCompra'];

                        for ($pr = 0; $pr < count($arrCompra); $pr++) {
                            if ($arrCompra[$pr]['idProducto'] == $idProducto) {
                                $arrCompra[$pr]['cantidad'] += $cantidad;
                                $on = false;
                            }
                        }
                        if ($on) {
                            array_push($arrCompra, $arrProducto);
                        }
                        $_SESSION['arrCompra'] = $arrCompra;
                    } else {
                        array_push($arrCompra, $arrProducto);
                        $_SESSION['arrCompra'] = $arrCompra;
                    }
                    // dep($_SESSION['arrCompra']);
                    // exit;
                    $htmlCompra = getFile('Compras/pedido', $_SESSION['arrCompra']);
                    $arrResponse = array(
                        "status" => true,
                        'msg' => '¡Se agrego al pedido!',
                        'htmlCompra' => $htmlCompra
                    );
                } else {
                    $arrResponse = array("status" => false, "msg" => 'El producto no existe.');
                }
            } else {
                $arrResponse = array("status" => false, "msg" => 'Dato incorrecto');
            }

            echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
        }
        die();
    }

    public function updCompra()
    {
        if ($_POST) {
            // dep($_POST);
            // die();
            $arrCompra = array();
            $precio = 0;
            $totalProducto = 0;
            $subtotal = 0;
            $iva = 0;
            $total = 0;

            $idproducto = $_POST['id'];
            $cantidad = intval($_POST['cantidad']);
            if (is_numeric($idproducto) and $cantidad > 0) {
                $arrCompra = $_SESSION['arrCompra'];
                // dep($_SESSION['arrCompra']);
                // exit;
                for ($p = 0; $p < count($arrCompra); $p++) {
                    if ($arrCompra[$p]['idProducto'] == $idproducto) {
                        $arrCompra[$p]['cantidad'] = $cantidad;
                        $precio =  $arrCompra[$p]['precio'] * (1 + ($arrCompra[$p]['iva'] / 100));
                        $totalProducto = $precio * $arrCompra[$p]['cantidad'];
                        break;
                    }
                }
                $_SESSION['arrCompra'] = $arrCompra;
                foreach ($_SESSION['arrCompra'] as $pro) {
                    $subtotal += $pro['cantidad'] * $pro['precio'];
                    $total += $pro['cantidad'] * $pro['precio'] * (1 + $pro['iva'] / 100);
                    $iva =  $total - $subtotal;
                }
                // dep($_SESSION['arrCompra']);
                // exit;

                $arrResponse = array(
                    "status" => true,
                    "msg" => '¡Producto eliminado!',
                    "totalProducto" => SMONEY . formatMoney($totalProducto),
                    "subTotal" => SMONEY . formatMoney($subtotal),
                    "iva" => SMONEY . formatMoney($iva),
                    "total" => SMONEY . formatMoney($total)

                );
            } else {
                $arrResponse = array("status" => false, "msg" => 'Dato incorrecto.');
            }
            // dep($arrResponse);
            // exit;
            echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
        }
        die();
    }
    public function delItemCompra()
    {
        if ($_POST) {
            // dep($_POST);
            // die();
            $arrCompra = array();
            $precio = 0;
            $totalProducto = 0;
            $subtotal = 0;
            $iva = 0;
            $total = 0;

            $idproducto = $_POST['id'];

            if (is_numeric($idproducto)) {
                $arrCompra = $_SESSION['arrCompra'];
                for ($pr = 0; $pr < count($arrCompra); $pr++) {
                    if ($arrCompra[$pr]['idProducto'] == $idproducto) {
                        unset($arrCompra[$pr]);
                    }
                }
                sort($arrCompra);
                $_SESSION['arrCompra'] = $arrCompra;

                foreach ($_SESSION['arrCompra'] as $pro) {
                    $subtotal += $pro['cantidad'] * $pro['precio'];
                    $total += $pro['cantidad'] * $pro['precio'] * (1 + $pro['iva'] / 100);
                    $iva =  $total - $subtotal;
                }

                $arrResponse = array(
                    "status" => true,
                    "msg" => '¡Producto eliminado!',
                    "subTotal" => SMONEY . formatMoney($subtotal),
                    "iva" => SMONEY . formatMoney($iva),
                    "total" => SMONEY . formatMoney($total)

                );
                // dep($arrCompra);
                // exit;
            } else {
                $arrResponse = array("status" => false, "msg" => 'Dato incorrecto.');
            }
            echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
        }
        die();
    }
}
