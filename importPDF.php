<?php
include "conexion.php";

//iniciamos la sesion
session_start(); ?>
<?php if (isset($_SESSION['loggedin'])) : ?>

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

                        <div class=" p-3">
                            <div class="col-lg-12 col-md-12 col-sm-12 ">
                                <div class="card text-center">
                                    <img src="" class="w-100">
                                    <div class="card-header" style="background-color:#123960; color:#ffffff">
                                        <i class='fas fa-file-pdf''></i> IMPORTACIÓN MASIVA DE PDF  <i class=' fas fa-file-pdf''></i>
                                    </div>
                                    <div class="text-center"><img src="./images/import.png" width="50%" alt="" class="p-3"></div>
                                    <div class="card-body">
                                        <?php
                                        if (isset($_POST['upload'])) {
                                            if (!empty($_FILES['file']['name'][0])) {
                                                $allowedExtensions = ["pdf"];
                                                $targetDirectory = "PDFS/";

                                                foreach ($_FILES['file']['name'] as $key => $fileName) {
                                                    $temp = explode(".", $fileName);
                                                    $extension = end($temp);
                                                    $uploadedFiles++;

                                                    if (in_array($extension, $allowedExtensions)) {
                                                        $targetFile = $targetDirectory . basename($fileName);

                                                        if (move_uploaded_file($_FILES['file']['tmp_name'][$key], $targetFile)) {
                                                            // La carga se realizó con éxito
                                                            echo '<div class="alert alert-success" role="alert">
                                                        Carga exitosa de los PDF ' . $fileName . '
                                                      </div> ';
                                                        } else {
                                                            echo '<div class="alert alert-danger" role="alert">
                                                        Error al cargar el archivo:  ' . $fileName . '<br>"
                                                      </div> ';
                                                        }
                                                    } else {
                                                        echo '<div class="alert alert-warning" role="alert">
                                                    Solo se permiten archivos PDF:' . $fileName . '<br>"
                                                  </div> ';
                                                    }
                                                }
                                            } else {
                                                echo '<div class="alert alert-info" role="alert">
                                           Ningun archivo seleccionado para cargar
                                          </div> ';
                                            }
                                        }
                                        ?>

                                        <!-- Contenido -->
                                        <div id="response" class="<?php if (!empty($type)) {
                                                                        echo $type . " display-block";
                                                                    } ?>">
                                            <?php if (!empty($message)) {
                                                echo $message;
                                            } ?></div>
                                        
                   <?php
                   if (isset($_POST['eliminar'])) {
                       $directorio = './PDFS/'; // Reemplaza con la ruta de tu carpeta
                   
                       // Escanea el directorio y obtén la lista de archivos
                       $archivos = scandir($directorio);
                   
                       if ($archivos !== false) {
                           // Recorre la lista de archivos
                           foreach ($archivos as $archivo) {
                               // Excluye "." y ".." que son directorios especiales
                               if ($archivo != "." && $archivo != "..") {
                                   $archivo_completo = $directorio . $archivo;
                                   
                                   // Verifica si es un archivo (no un directorio)
                                   if (is_file($archivo_completo)) {
                                       // Intenta eliminar el archivo
                                       if (unlink($archivo_completo)) {
                                           echo 'Archivo eliminado: ' . $archivo_completo . '<br>';
                                       } else {
                                           echo 'No se pudo eliminar el archivo: ' . $archivo_completo . '<br>';
                                       }
                                   }
                               }
                           }
                           echo '<div class="alert alert-info" role="alert">Todos los archivos fueron eliminados</div> ';
                       } else {
                           echo 'No se pudo escanear el directorio.';
                       }
                   }
                   ?>
                   
                                    
                                        <div class="outer-container">
                                            <form id="upload-form" action="" method="post" enctype="multipart/form-data">
                                                <input type="file" name="file[]" id="file" multiple accept=".pdf">
                                                <input type="submit" name="upload" value="Subir PDFs" class="btn-success btn-lg">
                                            </form>
                                            
                                            <br>
                                            <form method="post">
                                                <input type="submit" name="eliminar" value="Borrar todos los archivos actuales" class="btn-danger btn-lg">
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