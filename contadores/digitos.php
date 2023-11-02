<div class="col col-lg-12 col-md-6 col-sm-12">
    <div class="card border-warning mb-3 shadow rounded bg-transparent" style="max-width: 100%;">
        <div class="card-header text-center bg-warning text-white">Cantidad de PDF  </div>
        <div class="card-body text-warning text-center">
            <?php
            // Consulta MySQL para contar la cantidad de datos
            $directorio = './PDFS'; // Reemplaza 'ruta_de_la_carpeta' con la ruta a la carpeta que deseas contar.
            $archivos = scandir($directorio);
            
            // La función scandir retorna dos elementos que no son archivos o carpetas (.) y (..). Los excluimos.
            $archivos = array_diff($archivos, array('.', '..'));
            
            $cantidadArchivos = count($archivos);
            // Mostrar la cantidad de datos
            echo "<h1 class='card-title'><i class='fa fa-file-pdf'></i> ".$cantidadArchivos . "</h1>";
            ?>
         </div>
    </div>
</div>