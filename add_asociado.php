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
    <?php include('head.php'); ?>
</head>
<?php include('nav2.php'); ?>

<body>
    <section class="home-section">
        <?php include('nav.php'); ?>
        <div class="container-fluid rounded">
            <div class="container">

                <div class="col-lg-12 col-md-12 col-sm-12 px-2 mt-1">
                  
              <div class="card shadow p-3 mt-5 "">

                        <?php

                        if (isset($_POST['addAsociado'])) {
                            $dni = $_POST["dni"]; //Escanpando caracteres 
                            $nombre = $_POST["nombre"]; //Escanpando caracteres 
                            $email = $_POST["email"]; //Escanpando caracteres 
                            $insert = mysqli_query($con, "INSERT INTO asociados(DNI,nombre,email,estado) VALUES ('$dni','$nombre','$email','NO ENVIADO')");
                            if ($insert) {
                                echo '
                                    <div class="alert alert-success text-center" role="alert">
                                   <b>Trabajador registrado con éxito</b>
                                  </div>
                       ';
                            } else {
                                echo '
                                    <div class="alert alert-danger text-center" role="alert">
                                   <b> Trabajador no pudo ser registrado, intenta nuevamente</b>
                                   </div>
                       ';
                            }
                        }
                        ?>
                        <div class="container">
                            <br>
                            <h2>Añadir nuevo trabajador</h2>
                            <p>Complete este formulario para registrar el trabajador <br>
                                <b style="color:red">Todos los datos con * son obligatorios</b>
                            </p>

                            <form method="post" class="was-validated" enctype="multipart/form-data">

                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12 px-2 mt-1">


                                        <div class="form-group">
                                            <label style="color:#000">Cédula *</label>
                                            <input type="number" name="dni" class="form-control " placeholder="DNI" required="true">
                                            <span class="help-block alert-danger"><?php echo $username_err; ?></span>
                                        </div>
                                        <div class="form-group">
                                            <label style="color:#000">Nombre completo *</label>
                                            <input type="text" name="nombre" class="form-control "  placeholder="Nombre completo" required="true">
                                        </div>
                                      
                                        <div class="form-group">
                                            <label style="color:#000">Coreeo electrónico *</label>
                                            <input type="email" name="email" class="form-control "  placeholder="Coreeo electrónico" required="true">
                                        </div>

                                        <div class="form-group">
                                            <input type="submit" name="addAsociado" class="btn btn-outline-success" value="Registrar asociado">
                                            <input type="reset" class="btn btn-outline-danger" value="Cancelar">
                                        </div>
                                    </div>
                                </div>
                            </form>
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
<script>
    $(document).ready(function() {
        $(".toastPaciente").toast('show');
    });
</script>


</html>