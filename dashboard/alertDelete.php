<?php
$nik = mysqli_real_escape_string($con, (strip_tags($_GET["nikDelete"], ENT_QUOTES)));
if (isset($_GET['aksi']) == 'delete') {
    // escaping, additionally removing everything that could be (html/javascript-) code
    $nikDelete = mysqli_real_escape_string($con, (strip_tags($_GET["nikDelete"], ENT_QUOTES)));
    $cek = mysqli_query($con, "SELECT * FROM asociados WHERE DNI='$nikDelete'");
  
    if (mysqli_num_rows($cek) == 0) {
       
        echo '    <div class="toastDelete" style="position: absolute; top: 0; right: 0; z-index: 289;" data-delay="4000">
        <div class="toast-header ">
            <strong class="mr-auto"><i class="fa fa-bell" aria-hidden="true"
                    style=color:green></i> Notificación</strong>
          
            <button type="button" class="ml-2 mb-1 close" data-dismiss="toast"
                aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="toast-body alert-info">
            <h5> <b>Este trabajador no se encuentra en nuestra base de datos</b></h5>
       
        </div>
    </div>
    ';
    } else {
        $delete = mysqli_query($con, "DELETE FROM asociados WHERE DNI='$nik'");
        if ($delete===TRUE) {

            echo '
            <div class="toastDelete" style="position: absolute; top: 0; right: 0; z-index: 289;" data-delay="4000">
                <div class="toast-header ">
                    <strong class="mr-auto"><i class="fa fa-bell" aria-hidden="true"
                            style=color:green></i> Notificación</strong>
                  
                    <button type="button" class="ml-2 mb-1 close" data-dismiss="toast"
                        aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="toast-body alert-success">
                    <h5> <b>Trabajador eliminado correctamente</b></h5>
               
                </div>
            </div>
       ';
       header("location:main.php");
        } else {
            echo '
        <div class="toastPatient" style="position: absolute; top: 0; right: 0; z-index: 289;" data-delay="4000">
            <div class="toast-header  ">
                <strong class="mr-auto"><i class="fa fa-exclamation-triangle" aria-hidden="true" style="color:red"></i> Notificación</strong>
              
                <button type="button" class="ml-2 mb-1 close" data-dismiss="toast"
                    aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="toast-body alert-danger">
                <h5> <b>Hubo problemas al eliminar el trabajador</b></h5>
           
            </div>
        </div>
   ';}
    }
}
?>
<script>
    $(document).ready(function() {
    
        $(".toastDelete").toast('show');
 
    });
</script>