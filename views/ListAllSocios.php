<div class="card text-center">
    <div class="card-header" style="background-color:#123960; color:#ffffff">
        <i class="bi bi-people-fill"></i> LISTA DE EMPLEADOS PARA EXPORTAR EN EXCEL <i class="bi bi-people-fill"></i>
    </div>
    <div class="card-body">
        <a href="#" class="btn btn-success btn-lg" onclick="exportarTabla()" title="Exportar base de datos a Excel"><i class="fa fa-file-excel"></i> Exportar a Excel</a>

        <table id="ExportTable" class=" table table-sm table-hover table-bordered table-lg table-responsive">
            <thead class="thead-dark">
                <tr>

                    <th class="w-10">Id</th>
                    <th class="w-10">DNI</th>
                    <th class="w-10">Nombre</th>
                    <th class="w-50">Email</th>
                    <th class="w-50">Estado</th>
                    <th class="w-50">Fecha Envio</th>
                  
                </tr>
            </thead>
            <tbody>
                <?php

                $buscar = $_POST["buscador"];
                $usaurio = htmlspecialchars($_SESSION["codigo"]);
                if ($filter) {
                    //INNER JOING DEMASIADO IMPORTANTE PARA TRAER EL NOMBRE DEL MUNICIPIO
                    $sql = mysqli_query($con, "SELECT * FROM asociados ORDER BY nombre ASC"); 
                } else {
                    $sql = mysqli_query($con, "SELECT * FROM asociados ORDER BY nombre ASC"); 
                }
                if (mysqli_num_rows($sql) == 0) {
                    echo '<tr><td colspan="8">No hay datos.</td></tr>';
                } else {
                    $no = 1;
                    $activo = "";
             
                    while ($row = mysqli_fetch_assoc($sql)) {
                        //condicion para cambiar el color en la tabla segun el valor 
               
                        echo '

						  <tr style="font-size:12px">
                            <td>' . $row['id'] . '</td>
                            <td>' . $row['DNI'] . '</td>
                            <td>' . $row['nombre'] . '</td>
                            <td>' . $row['email'] . '</td>
                            <td>' . $row['estado'] . '</td>
                            <td>' . $row['fechaEnvio'] . '</td>
                      
                     
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
    $(function() {
        $('[data-toggle="tooltip"]').tooltip()
    })
</script>
<!-- Codigo para exportar tablas a excel -->
<script>
        function exportarTabla() {
            var tabla = document.getElementById('ExportTable');
            var archivoExcel = XLSX.utils.table_to_book(tabla, { sheet: "Lista-Empleados" });
            XLSX.writeFile(archivoExcel, "Lista-Empleados.xlsx");
        }
    </script>