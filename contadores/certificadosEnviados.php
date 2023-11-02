<div class="col col-lg-12 col-md-6 col-sm-12">
    <div class="card border-success mb-3 shadow rounded bg-transparent" style="max-width: 100%;">
        <div class="card-header text-center bg-success text-white">Certificados enviados</div>
        <div class="card-body text-success text-center">
            <?php
            // Consulta MySQL para contar la cantidad de datos
            $sql = "SELECT COUNT(*) AS cantidad FROM asociados WHERE estado='ENVIADO'";
            $resultado = mysqli_query($con, $sql);

            // Obtener el resultado de la consulta
            $fila = mysqli_fetch_assoc($resultado);
            $cantidad = $fila['cantidad'];

            // Mostrar la cantidad de datos
            echo "<h1 class='card-title'><i class='fa-solid fa-paper-plane'></i> ".$cantidad."</h1>";
            ?>
         </div>
    </div>
</div>