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
        <?php include 'nav.php'; ?>
        <h4 id="datos"></h4>
        <div class="container-fluid rounded">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 px-1 mt-1">
                    <div class="">
                        <?php //muy importante
                        include "txtBanner.php";
                        ?>

                        <div class="p-3">
                            <style>
                                .loader {
                                    border: 4px solid #f3f3f3;
                                    border-top: 4px solid #123960;
                                    border-radius: 50%;
                                    width: 50px;
                                    height: 50px;
                                    animation: spin 2s linear infinite;
                                    position: fixed;
                                    top: 50%;
                                    left: 50%;
                                    transform: translate(-50%, -50%);
                                    z-index: 9999;
                                }

                                @keyframes spin {
                                    0% {
                                        transform: rotate(0deg);
                                    }

                                    100% {
                                        transform: rotate(360deg);
                                    }
                                }
                            </style>
                            <div id="loader" class="loader container"></div>
                            <div id="content" style="display: none;"></div>
                            <script>
                                document.addEventListener('DOMContentLoaded', function() {
                                    // Muestra el spinner
                                    document.getElementById('loader').style.display = 'block';

                                    // Realiza una solicitud AJAX a tu archivo PHP que carga datos desde la base de datos
                                    fetch('sendFilesPdf.php?'<?php echo $nik;?>'')
                                        .then(response => response.text())
                                        .then(data => {
                                            // Oculta el spinner y muestra los datos cargados
                                            document.getElementById('loader').style.display = 'none';
                                            document.getElementById('content').style.display = 'block';
                                            document.getElementById('content').innerHTML = data;
                                        })
                                        .catch(error => {
                                            // En caso de error, oculta el spinner y muestra un mensaje de error
                                            document.getElementById('loader').style.display = 'none';
                                            console.error('Error al cargar los datos:', error);
                                        });
                                });
                            </script>

                            <br>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <br><br>
        <?php include 'footer.php'; ?>
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