<?php
include "conexion.php";

//iniciamos la sesion
session_start(); ?>
<?php if (isset($_SESSION['loggedin'])) : ?>
    <?php
    $filtro = htmlspecialchars($_SESSION["username"]);
    $query = mysqli_query($con, "SELECT nombre FROM users WHERE username like '%$filtro%'");
    while ($userLog = mysqli_fetch_array($query)) {
        $pacient = $userLog['nombre'];
    }
    ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php include('head.php');
    ?>
</head>
<?php include('nav2.php'); ?>

<body>
    <section class="home-section">
        <?php include('nav.php');
        ?>
        <div class="container mt-">
<style>
    .card{
      margin-bottom: 50px;
    }
</style>
            <div class="card shadow p-3 mt-n1 "">

                <div class="">
                    <!--ACTUALIZAR DATOS USUARIO-->
                    <?php
                    $usaurio = htmlspecialchars($_SESSION["username"]);
                    if (isset($_POST['actualizarSmtp'])) {
                        $host = mysqli_real_escape_string($con, (strip_tags($_POST["host"], ENT_QUOTES))); //Escanpando caracteres 
                        $email = mysqli_real_escape_string($con, (strip_tags($_POST["email"], ENT_QUOTES))); //Escanpando caracteres 
                        $password = mysqli_real_escape_string($con, (strip_tags($_POST["password"], ENT_QUOTES))); //Escanpando caracteres 
                        $port = mysqli_real_escape_string($con, (strip_tags($_POST["port"], ENT_QUOTES))); //Escanpando caracteres 
                        $nameBody = mysqli_real_escape_string($con, (strip_tags($_POST["nameBody"], ENT_QUOTES))); //Escanpando caracteres 
                        $Subject = mysqli_real_escape_string($con, (strip_tags($_POST["Subject"], ENT_QUOTES))); //Escanpando caracteres 
                        $body = mysqli_real_escape_string($con, (strip_tags($_POST["body"], ENT_QUOTES))); //Escanpando caracteres 
                        $nameFile = mysqli_real_escape_string($con, (strip_tags($_POST["nameFile"], ENT_QUOTES))); //Escanpando caracteres 
                        //carga de la imagen 1
                        $nombreimg1 = $_FILES['imagen']['name']; //obtiene el nombre
                        $archivo1 = $_FILES['imagen']['tmp_name']; //contiene el archivo
                        $ruta1 = "images/imagenesSubidas";
                        $ruta1 = $ruta1 . "/" . $nombreimg1; ///images/nombre.jpg
                        //carga de la imagen del logo
                        $logoName = $_FILES['logo']['name']; //obtiene el nombre
                        $logoFile = $_FILES['logo']['tmp_name']; //contiene el archivo
                        $ruta2 = "images/imagenesSubidas";
                        $ruta2 = $ruta2 . "/" . $logoName; ///images/nombre.jpg
                        if (!empty($_FILES['imagen']['name']) || !empty($_FILES['logo']['name'])) {
                            move_uploaded_file($archivo1, $ruta1);
                            move_uploaded_file($logoFile, $ruta2);
                            $update = mysqli_query($con, "UPDATE smtpConfig SET host='$host',
                               email='$email', password='$password', port='$port', nameBody='$nameBody',
                               Subject='$Subject',body='$body',nameFile='$nameFile',urlpicture='$ruta1',logoEncabezado='$ruta2'  WHERE id='1'");
                            if ($update) {
                                echo '<div class="alert alert-success alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>Los datos se han actualizado y guardado con éxito.</div>';
                            } else {
                                echo '<div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>Error, no se pudo guardar los datos.</div>';
                            }
                        } else { // en este else se quita de la consulta el campo de la imagen para que conserve la imagen sino se cambia
                            $update = mysqli_query($con, "UPDATE smtpConfig SET host='$host',
                        email='$email', password='$password', port='$port', nameBody='$nameBody',
                        Subject='$Subject',body='$body',nameFile='$nameFile' WHERE id='1'");
                            if ($update) {
                                echo '<div class="alert alert-success alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>Los datos se han actualizado y guardado con éxito.</div>';
                            } else {
                                echo '<div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>Error, no se pudo guardar los datos.</div>';
                            }
                        }
                    }
                    ?>



                    <div class="container">
                        <h2>Actualiza las credenciales del SMTP</h2>
                        <p>Tenga en cuenta que toda la información solicitada es obligatoria</p>
                        <div class="col-lg-12 col-md-12 col-sm-12 px-2 mt-1">
                            <div class="row">

                                <div class="col-lg-12 col-md-8 col-sm-12 px-2 mt-1">

                                    <!--Actualizar datos usuario-->
                                    <?php
                                    $usaurio = htmlspecialchars($_SESSION["username"]);
                                    $query = mysqli_query($con, "SELECT * FROM smtpConfig WHERE id='1'");
                                    while ($smtpConfig = mysqli_fetch_array($query)) {
                                        $host = $smtpConfig['host'];
                                        $email = $smtpConfig['email'];
                                        $password = $smtpConfig['password'];
                                        $port = $smtpConfig['port'];
                                        $nameBody = $smtpConfig['nameBody'];
                                        $Subject = $smtpConfig['Subject'];
                                        $body = $smtpConfig['body'];
                                        $nameFile = $smtpConfig['nameFile'];
                                        $foto1 = $smtpConfig['urlpicture'];
                                        $logo = $smtpConfig['logoEncabezado'];
                                    }
                                    ?>
                                    <form action="" method="POST" class="was-validated" enctype="multipart/form-data">
                                        <div class="form-group">
                                            <label>Host</label>
                                            <input type="text" name="host" class="form-control" value="<?php echo $host; ?>" require="true">
                                        </div>
                                        <div class="form-group">
                                            <label>Correo electrónico</label>
                                            <input type="email" name="email" class="form-control" value="<?php echo $email; ?>" require="true">
                                        </div>
                                        <div class="form-group">
                                            <label>Contraseña</label>
                                            <input type="password" name="password" class="form-control" value="<?php echo $password; ?>" required>
                                        </div>
                                        <div class="form-group">
                                            <label>Puerto</label>
                                            <input type="number" name="port" class="form-control" value="<?php echo $port; ?>" required>
                                        </div>
                                        <div class="form-group">
                                            <label>Nombre de la empresa que envia el correo</label>
                                            <input type="text" name="nameBody" class="form-control" value="<?php echo $nameBody; ?>" required>
                                        </div>
                                        <div class="form-group">
                                            <label>Asunto del correo</label>
                                            <input type="text" name="Subject" class="form-control" value="<?php echo $Subject; ?>" required>
                                        </div>
                                        <div class="form-group">
                                            <label>Cuerpo del correo</label>
                                            <input type="text" name="body" rows="3" cols="3" class="form-control" value="<?php echo $body; ?>">
                                        </div>
                                        <div class="form-group">
                                            <label>Nombre para el archivo que se enviará</label>
                                            <input type="text" name="nameFile" rows="3" cols="3" class="form-control" value="<?php echo $nameFile; ?>">
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-6 col-md-12 col-sm-12 px-2 mt-1">
                                                <label>Imágen para el cuerpo del correo</label><br>
                                                <div class="input-group mb-3">

                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text" id="basic-addon1"><img src="<?php echo $foto1; ?>" width="100px" alt="picture">
                                                        </span>
                                                    </div>
                                                    <input type="file" name="imagen" class="btn  w-80" style="background-color: #123960; color:#ffffff;" value="<?php echo $foto1; ?>" />
                                                </div>
                                            </div>
                                            <div class="col-lg-12 col-md-12 col-sm-12 px-2 mt-1">
                                                <label>Logo para el encabezado del PDF</label><br>
                                                <div class="input-group mb-3">

                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text" id="basic-addon1"><img src="<?php echo $logo; ?>" width="100px" alt="picture">
                                                        </span>
                                                    </div>
                                                    <input type="file" name="logo" class="btn  w-80" style="background-color: #123960; color:#ffffff;" value="<?php echo $logo; ?>" />
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <input type="submit" class="btn btn-outline-success" value="Actualizar SMTP" name="actualizarSmtp">
                                            <a class="btn btn-outline-danger" href="main.php">Cancelar</a>
                                        </div>
                                      
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <footer class="footer">
            <?php include('footer.php'); ?>
        </footer>
    </section>
    <?php else :
?>
    <script LANGUAGE="javascript">
        location.href = "index.php";
    </script>
<?php endif;
?>
</body>

</html>