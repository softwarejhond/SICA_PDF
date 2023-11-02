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
                  
                    <div class="col-lg-10 col-md-12 col-sm-12 px-1 mt-1">
                        <div class="">

                            <!--MENSAJES DE ELIMINAR EN CADA TABLA-->
                            <?php include './dashboard/alertHouseDelete.php'; ?>

                            <div class="p-3">
                                <nav>
                                    <div class="nav nav-tabs" id="nav-tab" role="tablist">
                                        <button class="nav-link active alert-danger" id="nav-home-tab" data-toggle="tab" data-target="#nav-home" type="button" role="tab" aria-controls="nav-home" aria-selected="true">Certificados no enviados <i class="fa-regular fa-thumbs-down"></i></button>
                                        <button class="nav-link alert-success" id="nav-profile-tab" data-toggle="tab" data-target="#nav-profile" type="button" role="tab" aria-controls="nav-profile" aria-selected="false">Certificados enviados <i class="fa-regular fa-thumbs-up"></i></button>
                                        <button class="nav-link" id="nav-contact-tab" data-toggle="tab" data-target="#nav-contact" type="button" role="tab" aria-controls="nav-contact" aria-selected="false">Exportar <i class="fa-solid fa-file-excel"></i></button>
                                    </div>
                                    <div class="tab-content" id="nav-tabContent">
                                        <div class="tab-pane fade show active" id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab"><?php include './views/listSocios.php'; ?></div>
                                        <div class="tab-pane fade" id="nav-profile" role="tabpanel" aria-labelledby="nav-profile-tab"><?php include './views/listSociosEnviados.php'; ?></div>
                                        <div class="tab-pane fade" id="nav-contact" role="tabpanel" aria-labelledby="nav-contact-tab"><?php include './views/ListAllSocios.php'; ?></div>

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
        <!-- MENSAJE DE BIENVENIDA TOAST-->

    </body>

    <!-- Codigo para exportar tablas a excel -->
    <script type="text/javascript">
        var tableToExcel = (function() {
            var uri = 'data:application/vnd.ms-excel;base64,',
                template =
                '<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40"><head><!--[if gte mso 9]><xml><x:ExcelWorkbook><x:ExcelWorksheets><x:ExcelWorksheet><x:Name>{worksheet}</x:Name><x:WorksheetOptions><x:DisplayGridlines/></x:WorksheetOptions></x:ExcelWorksheet></x:ExcelWorksheets></x:ExcelWorkbook></xml><![endif]--></head><body><table>{table}</table></body></html>',
                base64 = function(s) {
                    return window.btoa(unescape(encodeURIComponent(s)))
                },
                format = function(s, c) {
                    return s.replace(/{(\w+)}/g, function(m, p) {
                        return c[p];
                    })
                }
            return function(table, name) {
                if (!table.nodeType) table = document.getElementById(table)
                var ctx = {
                    worksheet: name || 'Worksheet',
                    table: table.innerHTML
                }
                window.location.href = uri + base64(format(template, ctx))
            }
        })()
    </script>

<?php else :
?>
    <script LANGUAGE="javascript">
        location.href = "index.php";
    </script>
<?php endif;
?>
<script>
    $(document).ready(function() {
        $('#myTable').DataTable();
    });
    $(document).ready(function() {
        $('#myTables').DataTable();
    });
</script>

<script>
    $(document).ready(function() {
        $('table.display').DataTable();
    });
</script>

    </html>