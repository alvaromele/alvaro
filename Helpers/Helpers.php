<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'Libraries/phpmailer/Exception.php';
require 'Libraries/phpmailer/PHPMailer.php';
require 'Libraries/phpmailer/SMTP.php';

//Retorla la url del proyecto
function base_url()
{
    return BASE_URL;
}
//Retorla la url de Assets
function media()
{
    return BASE_URL . "/Assets";
}
function cabecera($data = "")
{
    $view_header = "Views/Plantillas/cabecera.php";
    require_once($view_header);
}
function pie($data = "")
{
    $view_footer = "Views/Plantillas/pie.php";
    require_once($view_footer);
}
function headerTienda($data = "")
{
    $view_header = "Views/Plantillas/header_tienda.php";
    require_once($view_header);
}
function footerTienda($data = "")
{
    $view_footer = "Views/Plantillas/footer_tienda.php";
    require_once($view_footer);
}

//Muestra información formateada
function dep($data)
{
    $format  = print_r('<pre>');
    $format .= print_r($data);
    $format .= print_r('</pre>');
    return $format;
}
function mostrarModal(string $nameModal, $data)
{
    $view_modal = "Views/Plantillas/Modales/{$nameModal}.php";
    require_once $view_modal;
}
function getFile(string $url, $data)
{
    ob_start();
    require_once("Views/{$url}.php");
    $file = ob_get_clean();
    return $file;
}
//Envio de correos
function sendEmail($data, $template)
{
    if (ENVIRONMENT == 1) {
        $asunto = $data['asunto'];
        $emailDestino = $data['email'];
        $empresa = NOMBRE_REMITENTE;
        $remitente = EMAIL_REMITENTE;
        $emailCopia = !empty($data['emailCopia']) ? $data['emailCopia'] : "";
        //ENVIO DE CORREO
        $de = "MIME-Version: 1.0\r\n";
        $de .= "Content-type: text/html; charset=UTF-8\r\n";
        $de .= "From: {$empresa} <{$remitente}>\r\n";
        $de .= "Bcc: $emailCopia\r\n";
        ob_start();
        require_once("Views/Plantillas/Email/" . $template . ".php");
        $mensaje = ob_get_clean();
        $send = mail($emailDestino, $asunto, $mensaje, $de);
        return $send;
    } else {
        //Create an instance; passing `true` enables exceptions
        $mail = new PHPMailer(true);
        ob_start();
        require_once("Views/Plantillas/Email/" . $template . ".php");
        $mensaje = ob_get_clean();

        try {
            //Server settings
            $mail->SMTPDebug = 0;                      //Enable verbose debug output
            $mail->isSMTP();                                            //Send using SMTP
            $mail->Host       = 'smtp.gmail.com';                     //Set the SMTP server to send through
            $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
            $mail->Username   = correo;          //SMTP username
            $mail->Password   = contraseña;                               //SMTP password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
            $mail->Port       = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

            //Recipients
            $mail->setFrom(correo, 'Servidor Local');
            $mail->addAddress($data['email']);     //Add a recipient
            if (!empty($data['emailCopia'])) {
                $mail->addBCC($data['emailCopia']);
            }
            $mail->CharSet = 'UTF-8';
            //Content
            $mail->isHTML(true);                                  //Set email format to HTML
            $mail->Subject = $data['asunto'];
            $mail->Body    = $mensaje;

            $mail->send();
            return true;
        } catch (Exception $e) {
            return false;
        }
    }
}

function sendMailLocal($data, $template)
{
    //Create an instance; passing `true` enables exceptions
    $mail = new PHPMailer(true);
    ob_start();
    require_once("Views/Plantillas/Email/" . $template . ".php");
    $mensaje = ob_get_clean();

    try {
        //Server settings
        $mail->SMTPDebug = 1;                      //Enable verbose debug output
        $mail->isSMTP();                                            //Send using SMTP
        $mail->Host       = 'smtp.gmail.com';                     //Set the SMTP server to send through
        $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
        $mail->Username   = correo;                     //SMTP username
        $mail->Password   = contraseña;                               //SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
        $mail->Port       = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

        //Recipients
        $mail->setFrom(correo, 'Servidor Local');
        $mail->addAddress($data['email']);     //Add a recipient
        if (!empty($data['emailCopia'])) {
            $mail->addBCC($data['emailCopia']);
        }

        //Content
        $mail->isHTML(true);                                  //Set email format to HTML
        $mail->Subject = $data['asunto'];
        $mail->Body    = $mensaje;

        $mail->send();
        echo 'Mensaje enviado';
    } catch (Exception $e) {
        echo "Error en el envío del mensaje: {$mail->ErrorInfo}";
    }
}

function getPermisos(int $idmodulo)
{
    require_once("Models/PermisosModel.php");
    $objPermisos = new PermisosModel();
    if (!empty($_SESSION['userData'])) {
        $idrol = $_SESSION['userData']['idrol'];
        $arrPermisos = $objPermisos->permisosModulo($idrol);
        $permisos = '';
        $permisosMod = '';
        if (count($arrPermisos) > 0) {
            $permisos = $arrPermisos;
            $permisosMod = isset($arrPermisos[$idmodulo]) ? $arrPermisos[$idmodulo] : "";
        }
        $_SESSION['permisos'] = $permisos;
        $_SESSION['permisosMod'] = $permisosMod;
    }
}

function sessionUser(int $idpersona)
{
    require_once("Models/LoginModel.php");
    $objLogin = new LoginModel();
    $request = $objLogin->sessionLogin($idpersona);
    return $request;
}

function uploadImage(array $data, string $name)
{
    $url_temp = $data['tmp_name'];
    $destino    = 'Assets/images/uploads/' . $name;
    $move = move_uploaded_file($url_temp, $destino);
    return $move;
}

function deleteFile(string $name)
{
    unlink('Assets/images/uploads/' . $name);
}

//Elimina exceso de espacios entre palabras
function limpiaCadena($strCadena)
{
    $string = preg_replace(['/\s+/', '/^\s|\s$/'], [' ', ''], $strCadena);
    $string = trim($string); //Elimina espacios en blanco al inicio y al final
    $string = stripslashes($string); // Elimina las \ invertidas
    $string = str_ireplace("<script>", "", $string);
    $string = str_ireplace("</script>", "", $string);
    $string = str_ireplace("<script src>", "", $string);
    $string = str_ireplace("<script type=>", "", $string);
    $string = str_ireplace("SELECT * FROM", "", $string);
    $string = str_ireplace("DELETE FROM", "", $string);
    $string = str_ireplace("INSERT INTO", "", $string);
    $string = str_ireplace("SELECT COUNT(*) FROM", "", $string);
    $string = str_ireplace("DROP TABLE", "", $string);
    $string = str_ireplace("OR '1'='1", "", $string);
    $string = str_ireplace('OR "1"="1"', "", $string);
    $string = str_ireplace('OR ´1´=´1´', "", $string);
    $string = str_ireplace("is NULL; --", "", $string);
    $string = str_ireplace("is NULL; --", "", $string);
    $string = str_ireplace("LIKE '", "", $string);
    $string = str_ireplace('LIKE "', "", $string);
    $string = str_ireplace("LIKE ´", "", $string);
    $string = str_ireplace("OR 'a'='a", "", $string);
    $string = str_ireplace('OR "a"="a', "", $string);
    $string = str_ireplace("OR ´a´=´a", "", $string);
    $string = str_ireplace("OR ´a´=´a", "", $string);
    $string = str_ireplace("--", "", $string);
    $string = str_ireplace("^", "", $string);
    $string = str_ireplace("[", "", $string);
    $string = str_ireplace("]", "", $string);
    $string = str_ireplace("==", "", $string);
    return $string;
}

function clear_cadena(string $cadena)
{
    //Reemplazamos la A y a
    $cadena = str_replace(
        array('Á', 'À', 'Â', 'Ä', 'á', 'à', 'ä', 'â', 'ª'),
        array('A', 'A', 'A', 'A', 'a', 'a', 'a', 'a', 'a'),
        $cadena
    );

    //Reemplazamos la E y e
    $cadena = str_replace(
        array('É', 'È', 'Ê', 'Ë', 'é', 'è', 'ë', 'ê'),
        array('E', 'E', 'E', 'E', 'e', 'e', 'e', 'e'),
        $cadena
    );

    //Reemplazamos la I y i
    $cadena = str_replace(
        array('Í', 'Ì', 'Ï', 'Î', 'í', 'ì', 'ï', 'î'),
        array('I', 'I', 'I', 'I', 'i', 'i', 'i', 'i'),
        $cadena
    );

    //Reemplazamos la O y o
    $cadena = str_replace(
        array('Ó', 'Ò', 'Ö', 'Ô', 'ó', 'ò', 'ö', 'ô'),
        array('O', 'O', 'O', 'O', 'o', 'o', 'o', 'o'),
        $cadena
    );

    //Reemplazamos la U y u
    $cadena = str_replace(
        array('Ú', 'Ù', 'Û', 'Ü', 'ú', 'ù', 'ü', 'û'),
        array('U', 'U', 'U', 'U', 'u', 'u', 'u', 'u'),
        $cadena
    );

    //Reemplazamos la N, n, C y c
    $cadena = str_replace(
        array('Ñ', 'ñ', 'Ç', 'ç', ',', '.', ';', ':'),
        array('N', 'n', 'C', 'c', '', '', '', ''),
        $cadena
    );
    return $cadena;
}
//Genera una contraseña de 10 caracteres
function generaPasword($length = 10)
{
    $pass = "";
    $longitudPass = $length;
    $cadena = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz1234567890";
    $longitudCadena = strlen($cadena);

    for ($i = 1; $i <= $longitudPass; $i++) {
        $pos = rand(0, $longitudCadena - 1);
        $pass .= substr($cadena, $pos, 1);
    }
    return $pass;
}
//Genera un token
function token()
{
    $r1 = bin2hex(random_bytes(10));
    $r2 = bin2hex(random_bytes(10));
    $r3 = bin2hex(random_bytes(10));
    $r4 = bin2hex(random_bytes(10));
    $token = $r1 . '-' . $r2 . '-' . $r3 . '-' . $r4;
    return $token;
}
//Formato para valores monetarios
function formatMoney($cantidad)
{
    $cantidad = number_format($cantidad, 2, SPD, SPM);
    return $cantidad;
}

function getTokenPaypal()
{
    $payLogin = curl_init(URLPAYPAL . "/v1/oauth2/token");
    curl_setopt($payLogin, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($payLogin, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($payLogin, CURLOPT_USERPWD, IDCLIENTE . ":" . SECRET);
    curl_setopt($payLogin, CURLOPT_POSTFIELDS, "grant_type=client_credentials");
    $result = curl_exec($payLogin);
    $err = curl_error($payLogin);
    curl_close($payLogin);
    if ($err) {
        $request = "CURL Error #:" . $err;
    } else {
        $objData = json_decode($result);
        $request =  $objData->access_token;
    }
    return $request;
}

function CurlConnectionGet(string $ruta, string $contentType = null, string $token)
{
    $content_type = $contentType != null ? $contentType : "application/x-www-form-urlencoded";
    if ($token != null) {
        $arrHeader = array(
            'Content-Type:' . $content_type,
            'Authorization: Bearer ' . $token
        );
    } else {
        $arrHeader = array('Content-Type:' . $content_type);
    }
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $ruta);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $arrHeader);
    $result = curl_exec($ch);
    $err = curl_error($ch);
    curl_close($ch);
    if ($err) {
        $request = "CURL Error #:" . $err;
    } else {
        $request = json_decode($result);
    }
    return $request;
}

function CurlConnectionPost(string $ruta, string $contentType = null, string $token)
{
    $content_type = $contentType != null ? $contentType : "application/x-www-form-urlencoded";
    if ($token != null) {
        $arrHeader = array(
            'Content-Type:' . $content_type,
            'Authorization: Bearer ' . $token
        );
    } else {
        $arrHeader = array('Content-Type:' . $content_type);
    }
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $ruta);
    curl_setopt($ch, CURLOPT_POST, TRUE);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $arrHeader);
    $result = curl_exec($ch);
    $err = curl_error($ch);
    curl_close($ch);
    if ($err) {
        $request = "CURL Error #:" . $err;
    } else {
        $request = json_decode($result);
    }
    return $request;
}



function getCatFooter()
{
    require_once("Models/CategoriasModel.php");
    $objCategoria = new CategoriasModel();
    $request = $objCategoria->getCategoriasFooter();
    return $request;
}

function getInfoPage(int $idpagina)
{
    require_once("Libraries/Core/Mysql.php");
    $con = new Mysql();
    $sql = "SELECT * FROM post WHERE idpost = $idpagina";
    $request = $con->select($sql);
    return $request;
}

function getPageRout(string $ruta)
{
    require_once("Libraries/Core/Mysql.php");
    $con = new Mysql();
    $sql = "SELECT * FROM post WHERE ruta = '$ruta' AND status != 0 ";
    $request = $con->select($sql);
    if (!empty($request)) {
        $request['portada'] = $request['portada'] != "" ? media() . "/images/uploads/" . $request['portada'] : "";
    }
    return $request;
}

function viewPage(int $idpagina)
{
    require_once("Libraries/Core/Mysql.php");
    $con = new Mysql();
    $sql = "SELECT * FROM post WHERE idpost = $idpagina ";
    $request = $con->select($sql);
    if (($request['status'] == 2 and isset($_SESSION['permisosMod']) and $_SESSION['permisosMod']['u'] == true) or $request['status'] == 1) {
        return true;
    } else {
        return false;
    }
}

// Formato para valores en moneda
function formatoMoneda($cantidad)
{
    $cantidad = number_format($cantidad, 2, SPD, SPM);
    return $cantidad;
}

function cMoneda($num)
{

    $moneda = 'Pesos';
    $nEntero = floor($num);
    $nDecimal = round(($num - $nEntero) * 100);


    $texto = cNumero($nEntero);

    //Se agrega la moneda


    if ($nEntero == 1) {

        $texto = $texto . ' Peso';
    } else {
        if (($nEntero % 1000000) == 0) {
            $texto = $texto . ' de';
        }
        $texto = $texto . ' Pesos';
    }
    $texto = $texto;


    // Agregar los centavos

    if ($nDecimal <> 0) {

        $a = cNumero($nDecimal);
        $texto = $texto . ' con ' . $a;

        if ($nDecimal == 1) {

            $texto = $texto . ' Centavo M/cte.';
        } else {
            $texto = $texto . ' Centavos M/cte.';
        }
    }

    return $texto;
}


function cNumero($num)

{
    $texto = '';

    $cUnidades = array("", "Un", "Dos", "Tres", "Cuatro", "Cinco", "Seis", "Siete", "Ocho", "Nueve", "Diez", "Once", "Doce", "Trece", "Catorce", "Quince", "Dieciseis", "Diecisite", "Dieciocho", "Diecinueve", "Veinte", "Veintiuno", "Veintidós", "Veintitrés", "Veitnicuatro", "Veinticinco", "Veintiseis", "Veintiseisete", "Veintiocho", "Veintinueve");
    $cDecenas = array("", "Diez", "Veinte", "Treinta", "Cuarenta", "Cincuenta", "Sesenta", "Setenta", "Ochenta", "Noventa", "Cien");
    $cCentenas = array("", "Ciento", "Doscientos", "Trescientos", "Cuatrocientos", "Quinientos", "Seiscientos", "Setecientos", "Ochocientos", "Novecientos");


    $nMillones = intdiv($num, 1000000);
    $nMiles = intdiv($num, 1000) % 1000;
    $nCentenas = intdiv($num, 100) % 100;
    $nDecenas = intdiv($num, 10) % 10;
    $nUnidades = $num % 10;


    //****************** Evaluación de Millones ************************

    if ($nMillones == 1) {

        $a = ($num % 1000000 <> 0) ? " " . cNumero($num % 1000000) : " ";
        $texto = 'Un millón' . $a;
        return $texto;
    } elseif ($nMillones >= 2 && $nMillones <= 999) {
        $texto = cNumero(intdiv($num, 1000000)) . ' Millones';
        $a = ($num % 1000000 <> 0) ? " " . cNumero($num % 1000000) : " ";
        $texto = $texto . $a;

        return $texto;



        //****************** Evaluación de Miles ************************

    } elseif ($nMiles == 1) {

        $a = ($num % 1000 <> 0) ? " " . cNumero($num % 1000) : " ";
        $texto = 'Mil' . $a;
        return $texto;
    } elseif ($nMiles >= 2 && $nMiles <= 999) {
        $texto = cNumero(intdiv($num, 1000)) . ' Mil';
        $a = ($num % 1000 <> 0) ? " " . cNumero($num % 1000) : " ";
        $texto = $texto . $a;

        return $texto;
    }


    //****************** Evaluación desde 0 hasta 999 *****************


    if ($num == 100) {
        $texto = $cDecenas[10];
        return $texto;
    } elseif ($num == 0) {
        $texto = "Cero";
        return $texto;
    }

    if ($nCentenas <> 0) {
        $texto = $cCentenas[$nCentenas];
    }

    if ($nDecenas <> 0) {

        if ($nDecenas == 1 || $nDecenas == 2) {

            if ($nCentenas <> 0) {
                $texto = $texto . ' ';
            }

            $texto = $texto . $cUnidades[$num % 100];
            $cNumero = $texto;
            return $texto;
        } else {

            if ($nCentenas <> 0) {
                $texto = $texto . ' ';
            }
            $texto = $texto . $cDecenas[$nDecenas];
        }
    }

    if ($nUnidades <> 0) {

        if ($nDecenas <> 0) {

            $texto = $texto . ' y ';
        } elseif ($nCentenas <> 0) {
            $texto = $texto . ' ';
        }
        $texto = $texto . $cUnidades[$nUnidades];
    }
    return $texto;
}

function fechaHoy()
{
    $mes = array(
        "", "Enero",
        "Febrero",
        "Marzo",
        "Abril",
        "Mayo",
        "Junio",
        "Julio",
        "Agosto",
        "Septiembre",
        "Octubre",
        "Noviembre",
        "Diciembre"
    );
    return date('d') . " de " . $mes[date('n')] . " de " . date('Y');
}

function Meses()
{
    $meses = array(
        "Enero",
        "Febrero",
        "Marzo",
        "Abril",
        "Mayo",
        "Junio",
        "Julio",
        "Agosto",
        "Septiembre",
        "Octubre",
        "Noviembre",
        "Diciembre"
    );
    return $meses;
}
