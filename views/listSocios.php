<div class="card text-center">
    <div class="card-header" style="background-color:#123960; color:#ffffff">
        <i class="fa fa-users"></i> LISTA DE EMPLEADOS REGISTRADOS <i class="fa fa-users"></i>
    </div>
    <br>
    <div class="container">
    
        <div class="alert" style="background-color: #123960;color:#ffffff">
           
            <a href="../generarTodPdfA.php" class="btn btn-success p-3" tittle="Enviar todos los reportees"><i class="fa-solid fa-paper-plane" style="font-size: 24px;"></i> ENVIAR TODOS LOS REPORTES</a>
        </div>
    </div>
    <div class="card-body">
        <?php include './dashboard/alertDelete.php'; ?>
        <table id="myTable" class=" table table-hover table-bordered table-lg table-responsive">
            <thead class="thead-dark">
                <style>
                    .columna {
                        width: 30px;
                    }

                    .numeral {
                        width: 10px;
                    }

                    td {
                        width: 30px;
                    }
                </style>
                <tr>
                    <th class="w-10">#</th>
                    <th class="columna">DNI</th>
                    <th class="columna">Nombre</th>
                    <th class="columna">E-Mail</th>
                    <th class="w-30">Estado</th>
                    <th class="columna">Fecha Envio</th>
                    <th class="columna"> <i class="fa fa-eye"></i></th>
                    <th class="columna"> <i class="fa fa-file-pdf-o"></i></th>
                    <th class="columna"><i class="fa-solid fa-paper-plane"></i></th>
                    <th class="columna"><i class="fa fa-trash"></i> </th>
                </tr>
            </thead>
            <tbody>
                <?php

                $buscar = $_POST["buscador"];
                $usaurio = htmlspecialchars($_SESSION["identificacion"]);
                if ($filter) {
                    $sql = mysqli_query($con, "SELECT * FROM asociados WHERE DNI like '%$buscar%' AND estado='NO ENVIADO' ORDER BY nombre ASC");
                } else {
                    $sql = mysqli_query($con, "SELECT * FROM asociados WHERE DNI like '%$buscar%' AND estado='NO ENVIADO'ORDER BY nombre ASC");
                }
                if (mysqli_num_rows($sql) == 0) {
                    echo '<tr><td colspan="8">No hay datos.</td></tr>';
                } else {
                    $no = 1;
                    $estados;
                    while ($row = mysqli_fetch_assoc($sql)) {
                        $DNI= $row['DNI'];
                        $nombre= $row['nombre'];
                        $nombreFormateado = str_replace(' ', '_', $nombre);
                        if ($row['estado'] == "NO ENVIADO") {
                            $estados =  '<label class="btn btn-outline-danger btn-sm w-100" data-toggle="tooltip" data-placement="top" title="Certificado: ' . $row['estado'] . '" style="font-size:10px"><i class="fa-regular fa-thumbs-down"></i> NO ENVIADO</label>';
                        }
                        if ($row['estado'] == "ENVIADO") {
                            $estados =  '<label class="btn btn-outline-success btn-sm w-100" data-toggle="tooltip" data-placement="top" title="Certificado: ' . $row['estado'] . '" style="font-size:10px"><i class="fa-regular fa-thumbs-up"></i> ENVIADO</label>';
                        }
                        echo ' 

						  <tr style="font-size:12px">
						    <td>' . $no . '</td>
                            <td>' . $row['DNI'] . '</td>
                            <td>' . $row['nombre'] . '</td>
                            <td>' . $row['email'] . '</td>
                            <td class="w-30">' . $estados . '</td>
                            <td>' . $row['fechaEnvio'] . '</td>
                           <td><a href="../PDFS/'.$DNI.'_'.$nombre.'.pdf" title="Ver certificado" class="btn btn-outline-success btn"><span class="fa fa-eye" aria-hidden="true"></span></a></td>
                           <td><a href="../PDFS/'.$DNI.'_'.$nombre.'.pdf" download="'.$DNI.'_'.$nombre.'.pdf"  title="Descargar certificado" class="btn btn-outline-info btn"><span class="fa-solid fa-cloud-arrow-down" aria-hidden="true"></span></a></td>
                           <td><a href="../individualEmail.php?nik='.$row['DNI'].'"id="send" target="_blank" title="Enviar certificado al correo" class="btn btn-outline-warning btn"><span class="fa fa-envelope" aria-hidden="true"></span></a></td>
                         
                           <td><a href="../main.php?aksi=delete&nikDelete='.$row['DNI'].'" title="Eliminar trabajador" onclick="return confirm(\'Esta seguro de borrar a ' . $DNI.' '.$nombre.'?\')" class="btn btn-outline-danger btn"><span class="fa fa-trash" aria-hidden="true"></span></a></td>
                          
                           </tr>


						';
                        $no++;
                    }
                }
                ?>
            </tbody>
        </table>

    </div>
    <div class="card-footer " style="background-color:#123960; color:#ffffff">
        <i class="fas fa-clock"></i>
        <?php
        $DateAndTime = date('m-d-Y h:i:s a', time());
        echo "Actualizado $DateAndTime.";
        ?>
    </div>

</div>
<script>
    $(document).ready(function() {
        $(".toastPatient").toast('show');
    });
</script>




</script>