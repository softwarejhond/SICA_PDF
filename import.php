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
  

include('conexion.php');
require_once('vendor/php-excel-reader/excel_reader2.php');
require_once('vendor/SpreadsheetReader.php');
include('eliminar.php');
if (isset($_POST["import"])) {

    $allowedFileType = ['application/vnd.ms-excel', 'text/xls', 'text/xlsx', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'];

    if (in_array($_FILES["file"]["type"], $allowedFileType)) {

        $targetPath = 'subidas/' . $_FILES['file']['name'];
        move_uploaded_file($_FILES['file']['tmp_name'], $targetPath);

        $Reader = new SpreadsheetReader($targetPath);

        $sheetCount = count($Reader->sheets());
        for ($i = 0; $i < $sheetCount; $i++) {

            $Reader->ChangeSheet($i);

            foreach ($Reader as $Row) {

               

                $dni = "";
                if (isset($Row[0])) {
                    $dni = mysqli_real_escape_string($con, $Row[0]);
                }

                $nombre = "";
                if (isset($Row[1])) {
                    $nombre = mysqli_real_escape_string($con, $Row[1]);
                }

                $email = "";
                if (isset($Row[2])) {
                    $email = mysqli_real_escape_string($con, $Row[2]);
                }
                $estado = "";
                if (isset($Row[3])) {
                    $estado = mysqli_real_escape_string($con, $Row[3]);
                }
                $fechaEnvio = "";
              
                if (isset($Row[4])) {
                    $fechaEnvio = mysqli_real_escape_string($con, $Row[4]);
                }
                if (!empty($dni) || !empty($nombre) || !empty($aportes) || !empty($numero) || !empty($aportesLetra) || !empty($email)) {
                    $query = "INSERT INTO asociados(DNI,nombre,email,estado,fechaEnvio) values('" . $dni . "','" . $nombre . "','" . $email . "','" . $estado . "','" . $fechaEnvio . "')";
                    $resultados = mysqli_query($con, $query);
                    if (!empty($resultados)) {
                        $type = "success";
                        $message = '<div class="alert alert-success" role="alert">
                        Excel importado correctamente
                      </div>';
                    } else {
                        $type = "error";
                        $message = '<div class="alert alert-danger" role="alert">
                        Hubo un problema al importar los productos, verifica que los datos del excel esten correctos o que la base de datos este vacia inicialmente
                      </div>';
                    }
                }
            }
        }
    } else {
        $type = "error";
        $message = '<div class="alert alert-warning" role="alert">
        El archivo enviado es invalido. Por favor vuelva a intentarlo  </div
        ';
    }
}
if (isset($_POST["delete"])) {
    $sql = mysqli_query($con, 'TRUNCATE TABLE `asociados`');
    $type = "error";
    $message = '<div class="alert alert-danger" role="alert">
                     Datos eliminados correctamente, ya puedes cargar los datos nuevamente </div>';
}
?>
<!doctype html>
<html lang="es">

<head>
    <?php include 'head.php'; ?>
</head>
</head>
<?php include 'nav2.php'; ?>

<body>
    <section class="home-section">
        <?php include 'nav.php'; ?>
        <h4 id="datos"></h4>
        <div class="container-fluid rounded">
            <div class="container">
                <div class="col-lg-12 col-md-12 col-sm-12 px-1 mt-1">
                        
              <div class="card shadow p-3 mt-5 "">

                        <div class="p-3">
                            <div class="col-lg-12 col-md-12 col-sm-12 ">
                                <div class="card text-center">
                                    <img src="" class="w-100">
                                    <div class="card-header" style="background-color:#123960; color:#ffffff">
                                    <i class='fas fa-file-import''></i> IMPORTACIÓN MASIVA DE EMPLEADOS  <i class='fas fa-file-import''></i>
                                    </div>
                                    <div class="text-center"><img src="./images/import.png" width="50%" alt="" class="p-3"></div>
                                    <div class="card-body">
                                        <!-- Contenido -->
                                        <div id="response" class="<?php if (!empty($type)) {
                                                                        echo $type . " display-block";
                                                                    } ?>">
                                            <?php if (!empty($message)) {
                                                echo $message;
                                            } ?></div>
                                        <div class="outer-container">
                                            <form action="" method="post" name="frmExcelImport" id="frmExcelImport" enctype="multipart/form-data">
                                                <div>
                                                    <label><i class="fas fa-file-excel alert-success "></i> Elija Archivo Excel</label>
                                                    <input type="file" name="file" id="file" accept=".xls,.xlsx" class="btn-lg btn-warning">
                                                    <button type="submit" id="submit" name="import" class="btn-success btn-lg"><i class="fas fa-file-import"></i> Importar </button>
                                                    <button type="submit" id="submit" name="delete" class="btn-danger btn-lg"><i class="fas fa-trash-alt"></i> Vaciar </button>
                                                </div>

                                            </form>

                                        </div>
                                    </div>
                                </div>
                                <!-- Fin Contenido -->

                            </div>
                        </div>


                        <!-- Fin row -->
                    </div>

                </div>
            </div>
        </div>
        <footer class="footer">
            <?php include('footer.php'); ?>
        </footer>
    </section>
</body>
<script>
    $(document).ready(function() {
        $('#myTable').DataTable();
    });
</script>
<?php else :
?>
    <script LANGUAGE="javascript">
        location.href = "index.php";
    </script>
<?php endif;
?>
</html>