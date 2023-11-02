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
    <html lang="es">

    <head>
        <?php include 'head.php'; ?>
        <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.3/css/jquery.dataTables.css">
        <style>
            th {
                width: 100px;
            }
        </style>
        <script type="text/javascript" charset="utf8" src="js/dataTables.min.js">
        </script>
    </head>
    <?php include 'nav2.php'; ?>

    <body>
        <section class="home-section">
            <?php include 'nav.php'; ?>
            <h4 id="datos"></h4>
            <div class="container-fluid rounded">
                <div class="row">
                    <!--MENSAJES DE ELIMINAR EN CADA TABLA-->

                    <div class="col-lg-10 col-md-12 col-sm-12 px-1 mt-1">
                        <div class="">


                            <div class="p-3">
                                <nav>
                                    <div class="nav nav-tabs" id="nav-tab" role="tablist">
                                        <button class="nav-link active alert-warning" id="nav-home-tab" data-toggle="tab" data-target="#nav-home" type="button" role="tab" aria-controls="nav-home" aria-selected="true">Fechas programadas <i class="fa-solid fa-clipboard-list"></i></i></button>
                                        <button class="nav-link alert-success" id="nav-profile-tab" data-toggle="tab" data-target="#nav-profile" type="button" role="tab" aria-controls="nav-profile" aria-selected="false">Fechas DIAN <i class="fa-solid fa-business-time"></i></button>
                                    </div>
                                    <div class="tab-content" id="nav-tabContent">
                                    <?php include './dashboard/alertDeleteDateProgramada.php'; ?>
                                    <?php include './dashboard/alertDeleteDateDian.php'; ?>
                                        <div class="tab-pane fade show active" id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab"><?php include './views/listHorasProgramadas.php'; ?></div>
                                        <div class="tab-pane fade" id="nav-profile" role="tabpanel" aria-labelledby="nav-profile-tab"><?php include './views/listHorasDian.php'; ?></div>

                                    </div>
                                    <br>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-2 col-md-12 col-sm-12 px-1 mt-1">
                        <div class="row p-1">
                            <?php include './contadores/digitos.php'; ?>
                            <?php include './contadores/totalAsociados.php'; ?>
                            <?php include './contadores/certificadosEnviados.php'; ?>
                            <?php include './contadores/certificadosPendientes.php'; ?>
                        </div>
                    </div>
                </div>
            </div>


            <br><br>
            <?php include 'footer.php';
            ?>
        </section>
      

    </body>



<?php else :
?>
    <script LANGUAGE="javascript">
        location.href = "index.php";
    </script>
<?php endif;
?>
<script>
    $(document).ready(function() {
        $('#horasDian').DataTable();
    });
    $(document).ready(function() {
        $('#horasProgramadas').DataTable();
    });
</script>



    </html>