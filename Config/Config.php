<?php
const BASE_URL = "http://localhost/alvaro";
//const BASE_URL = "https://abelosh.com/tiendavirtual";

//Zona horaria
date_default_timezone_set('America/Bogota');

//Datos de conexión a Base de Datos
const DB_HOST = "localhost";
const DB_NAME = "db_tiendavirtual";
const DB_USER = "root";
const DB_PASSWORD = "";
const DB_CHARSET = "utf8";

//Para envío de correo
const ENVIRONMENT = 0; // Local: 0, Produccón: 1;

const correo = "groupmegasas@gmail.com";
const contraseña = "Mamega90$";

//Deliminadores decimal y millar Ej. 24,1989.00
const SPD = ",";
const SPM = ".";

//Simbolo de moneda
const SMONEY = "$ ";
const CURRENCY = "USD";
const PORCENT = " %";

//Api PayPal
//SANDBOX PAYPAL
const URLPAYPAL = "https://api-m.sandbox.paypal.com";
const IDCLIENTE = "";
const SECRET = "";
//LIVE PAYPAL
//const URLPAYPAL = "https://api-m.paypal.com";
//const IDCLIENTE = "";
//const SECRET = "";

//Datos envio de correo
const NOMBRE_REMITENTE = "Mega Construcciones SAS";
const EMAIL_REMITENTE = "groupmegasas@gmail.com";
const NOMBRE_EMPESA = "Mega SAS";
const WEB_EMPRESA = "www.megasas.com";

const DESCRIPCION = "Constructora Mega Construcciones SAS.";
const SHAREDHASH = "Alamcen";

//Datos Empresa
const DIRECCION = "Calle 144 # 7-31";
const TELEMPRESA = "+3105538065";
const WHATSAPP = "+3105538065";
const EMAIL_EMPRESA = "groupmegasas@gmail.com";
const EMAIL_PEDIDOS = "groupmegasas@gmail.com";
const EMAIL_SUSCRIPCION = "groupmegasas@gmail.com";
const EMAIL_CONTACTO = "groupmegasas@gmail.com";

const CAT_SLIDER = "1,2,3";
const CAT_BANNER = "4,5,6";
const CAT_FOOTER = "1,2,3,4,5";

//Datos para Encriptar / Desencriptar
const KEY = 'abelosh';
const METHODENCRIPT = "AES-128-ECB";

//Envío
const COSTOENVIO = 5;

//Módulos
const MDASHBOARD = 1;
const MUSUARIOS = 2;
const MCLIENTES = 3;
const MPRODUCTOS = 4;
const MPEDIDOS = 5;
const MCATEGORIAS = 6;
const MSUSCRIPTORES = 7;
const MDCONTACTOS = 8;
const MDPAGINAS = 9;

//Páginas
const PINICIO = 1;
const PTIENDA = 2;
const PCARRITO = 3;
const PNOSOTROS = 4;
const PCONTACTO = 5;
const PPREGUNTAS = 6;
const PTERMINOS = 7;
const PSUCURSALES = 8;
const PERROR = 9;

//Roles
const RADMINISTRADOR = 1;
const RSUPERVISOR = 2;
const RCLIENTES = 3;
const RADMIN = 1;
const RSEGUROS = 2;
const RCOMPRAS = 3;
const RINVENT = 4;

const STATUS = array('Completo', 'Aprobado', 'Cancelado', 'Reembolsado', 'Pendiente', 'Entregado');

//Productos por página
const CANTPORDHOME = 8;
const PROPORPAGINA = 4;
const PROCATEGORIA = 4;
const PROBUSCAR = 4;

//REDES SOCIALES
const FACEBOOK = "https://www.facebook.com/abelosh";
const INSTAGRAM = "https://www.instagram.com/febel24/";

//Codigos para iniciar CONSECUTIVOS
const CCUENTA = 1;
const CORDEN = 37;
const CENTRADA = 1;
const CSALIDA = 1;
const CPRODUCTOS = 2000;
