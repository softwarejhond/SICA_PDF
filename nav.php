<?php
include "conexion.php";

//iniciamos la sesion
session_start();

?>

<?php if (isset($_SESSION['loggedin'])) : ?>
  <?php
  $admin = htmlspecialchars($_SESSION["username"]);
  //consulta para verificar si el usuario logueado es administrador o no
  $query = mysqli_query($con, "SELECT * FROM users WHERE username like '%$admin%'");
  $activo = "block";
  while ($userLog = mysqli_fetch_array($query)) {
    $rol = $userLog['dependencia'];
    $nombre = $userLog['nombre'];
    $fechaCreacion = $userLog['fechaCreacionUser'];
  }
  ?>

  <nav class="navbar navbar-expand-lg navbar-dark sticky-top nav-wrapper " style="background: #123960;">
    <a class="navbar-brand" href="main.php"><img src="images/sicaLogo.png" width="80px"></a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav mr-auto">
        <li class="nav-item active">
          <a class="nav-link" href="main.php"><i class="fa fa-home"></i> Inicio <span class="sr-only">(current)</span></a>
        </li>

        <li class="nav-item">
          <a class="nav-link" href="perfil.php"><i class="fa fa-user"></i> Perfil</a>
        </li>

        <?php
        //Condicion de php para validar si el usuario es administrador o no y asi mostrar los botones
        if ($rol === "Ingeniero") {
          echo '
          <li class="nav-item">
          <a class="nav-link" href="company.php"><i class="fa fa-building"></i> Institución</a>
        </li>
            <li class="nav-item">
                <a class="nav-link" href="updateSMTP.php" ><i class="fa-solid fa-screwdriver-wrench"></i> SMTP </a>
            </li>';
        }
        ?>
      </ul>
      <style>
        .alert-fondo {
          background-color: #123960;
          color: #ffffff;
          transition: transform .2s;
          /* Animation */
        }

        .alert-fondo:hover {
          color: #ffffff;
          transform: scale(1.2);
        }

        .nav-wrapper {
          display: flex;
          align-items: center;
          margin: auto;
          width: 90%;
          border-radius: 0 0 15px 15px;
          padding: 0 25px;
          z-index: 2;
          background: #123960;


        }
        .nombre{
          font-size: 12px;
          text-align: right;
          vertical-align: middle;
          padding-top: 5px;;
        }
        .btn-salir{
          margin-right: 20px;
        }
       
      </style>

      <div class="dropdown btn-salir" ">
            <button class=" dropdown-toggle p-1 alert alert-fondo " type=" button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <label class="nombre">
              <?php echo $nombre; ?>
              <br>
              <?php echo $rol." - ".$fechaCreacion ?>
          </label>
        <img src="vista.php?id='<?php echo '9' ?>'" alt='<?php echo $nombre ?>' class="rounded-circle p-1" title="<?php echo $nombre ?>" width="45 px" height="45px" data-container="body" data-toggle="popover" data-placement="bottom" data-content="Bottom popover" />
        <?php
        $usaurio = htmlspecialchars($_SESSION["username"]);
        $query = mysqli_query($con, "SELECT nombre FROM users WHERE username like '%$usaurio%'");
        while ($userLog = mysqli_fetch_array($query)) {
          $nombre = $userLog['nombre'];
        }
        ?>
        <div class="spinner-grow text-success spinner-grow-sm" role="status">
          <span class="sr-only">Loading...</span>
        </div>
        </button>
        <div class="dropdown-menu dropdown-menu-right dropdown-menu-lg-left" aria-labelledby="dropdownMenuButton">
          <a class="dropdown-item" href="logout.php"><i class="fa-solid fa-power-off"></i> Cerrar</a>
        </div>
      </div>
    </div>


  </nav>
<?php else :
?>
  <script LANGUAGE="javascript">
    location.href = "index.php";
  </script>
<?php endif;
?>