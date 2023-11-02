<?php
require('./fpdf/fpdf/fpdf.php');
require('./fpdf/fpdf_protection.php');
//Import PHPMailer classes into the global namespace
//These must be at the top of your script, not inside a function
require 'vendor/autoload.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';
require 'PHPMailer/src/Exception.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

// Initialize the session
session_start();
// Establecer tiempo de vida de la sesión en segundos
$inactividad = 86400;
// Comprobar si $_SESSION["timeout"] está establecida
if (isset($_SESSION["timeout"])) {
    // Calcular el tiempo de vida de la sesión (TTL = Time To Live)
    $sessionTTL = time() - $_SESSION["timeout"];
    if ($sessionTTL > $inactividad) {
        header("location: main.php");
        exit;
    }
}
// El siguiente key se crea cuando se inicia sesión
$_SESSION["timeout"] = time();
// Include config file
require_once "conexion.php";
if (isset($_GET['nik'])) {
    $nik = $_GET['nik'];
}

$meses = array(
    'enero', 'febrero', 'marzo', 'abril', 'mayo', 'junio',
    'julio', 'agosto', 'septiembre', 'octubre', 'noviembre', 'diciembre'
);

$fecha_actual = new DateTime();
$dia = $fecha_actual->format('d');
$mes = $meses[(int)$fecha_actual->format('m') - 1]; // Restamos 1 porque los índices de array comienzan en 0
$anio = $fecha_actual->format('Y');
$fecha_formateada = "$dia de $mes de $anio";

//-------------------------------------------------------------------------------------
//EL COMANDO RIGHT EQUIVALE A QUE SE SEÑALAN LOS DOS ULTIMOS VALORES DE LA CEDULA 
$queryAsociados = mysqli_query($con, "SELECT * FROM asociados WHERE  DNI=$nik");
$querySMTP = mysqli_query($con, "SELECT * FROM smtpConfig WHERE id=1");
while ($smtpConfig = mysqli_fetch_array($querySMTP)) {
    $host = $smtpConfig['host'];
    $emailSmtp = $smtpConfig['email'];
    $password = $smtpConfig['password'];
    $port = $smtpConfig['port'];
    $nameBody = $smtpConfig['nameBody'];
    $Subject = $smtpConfig['Subject'];
    $body = $smtpConfig['body'];
    $nameFile = $smtpConfig['nameFile'];
    $urlpicture = $smtpConfig['urlpicture'];
    $logo = $smtpConfig['logoEncabezado'];
    
}
while ($infoAsociados = mysqli_fetch_array($queryAsociados)) {
    $DNI = $infoAsociados['DNI'];
    $nombre = $infoAsociados['nombre'];
    $email = $infoAsociados['email'];
    $nombreFormateado = str_replace(' ', '_', $nombre);
    //Create an instance; passing `true` enables exceptions
    $mail = new PHPMailer(true);

    try {
        //Server settings

        $mail->isSMTP();                                            //Send using SMTP
        $mail->Host       = $host;                     //Set the SMTP server to send through
        $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
        $mail->Username   = $emailSmtp;                     //SMTP username
        $mail->Password   = $password;                               //SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
        $mail->Port       = $port;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

        //Recipients
        $mail->setFrom($emailSmtp, $nameBody);
        $mail->addAddress($email);     //Add a recipient
        $mail->addAddress($emailSmtp); 

        //Attachments
        $mail->addAttachment('./PDFS/' . $DNI .'_'.$nombre. '.pdf');         //Add attachments

        //Content
        $mail->isHTML(true);                                  //Set email format to HTML
        $mail->Subject = $Subject;
        $mail->Body    = $body.'<br><br>Este correo se ha generado de manera automatica por el sistema SICA<br> <b>Sistema desarrollado por la Agencia de Desarrollo Eagle Software <br><img src="cid:cuerpo" width="400px"></b>';
        // Ruta de la imagen en el sistema de archivos
        $imagenPath = $urlpicture;

        // Agregar la imagen al correo como recurso embebido
        $mail->addEmbeddedImage($imagenPath, 'cuerpo');


        $mail->send();
        //aqui a continuacion actualizamos el estado de los datos 
        $estado = "ENVIADO"; //Escanpando caracteres 
        $dataTime = date("Y-m-d H:i:s");
        $update = mysqli_query($con, "UPDATE asociados SET estado='$estado', fechaEnvio='$dataTime' WHERE DNI='$DNI'");
    } catch (Exception $e) {
        echo '
        
                                <div class="jumbotron alert-danger text-center">
                                <h1 class="display-4"><b><i class="fa-solid fa-triangle-exclamation"></i></b></h1><br>
                                <h1 class="display-4"><b>¡NO SE HAN PODIDO ENVIAR!</b></h1>
                                <p class="lead">SICA no ha podido enviar el correo a esta dirección <br> ERROR: ' . $email . '</p>
                                <hr class="my-4">
                                <a class="btn btn-lg" href="main.php" role="button" style="color: #ffffff; background: #123960;">Regresar</a>
                             </div>';
    }
}
echo '  <div class="jumbotron alert-success text-center">
                            <h1 class="display-4"><b><i class="fa-solid fa-circle-check"></i></b></h1><br>
        <h1 class="display-4"><b>¡EL REPORTE HAN SIDO ENVIADO CON ÉXITO!</b></h1>
        <p class="lead text-center"><b>El estado y fecha de envio se han actualizado con éxito.'.$nik.'</b></p>
        <hr class="my-4">

        <a href="main.php?"id="send" name="enviar" title="Enviar certificado al correo" class="btn btn-outline-success btn-lg"><span class="fa fa-home" aria-hidden="true"></span> REGRESAR AL INICIO</a>
                        
      </div>
      ';
?>