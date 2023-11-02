<?php
// Initialize the session
session_start();
// Establecer tiempo de vida de la sesión en segundos
$inactividad = 86400;
// Comprobar si $_SESSION["timeout"] está establecida
if (isset($_SESSION["timeout"])) {
    // Calcular el tiempo de vida de la sesión (TTL = Time To Live)
    $sessionTTL = time() - $_SESSION["timeout"];
    if ($sessionTTL > $inactividad) {
        header("location: main.php");
        exit;
    }
}
// El siguiente key se crea cuando se inicia sesión
$_SESSION["timeout"] = time();
// Include config file
require_once "conexion.php";

// Define variables and initialize with empty values
$username = $password = "";
$username_err = $password_err = "";

// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Check if username is empty
    if (empty(trim($_POST["username"]))) {
        $username_err = "Por favor ingrese su usuario.";
    } else {
        $username = trim($_POST["username"]);
    }

    // Check if password is empty
    if (empty(trim($_POST["password"]))) {
        $password_err = "Por favor ingrese su contraseña.";
    } else {
        $password = trim($_POST["password"]);
    }

    // Validate credentials
    if (empty($username_err) && empty($password_err)) {
        // Prepare a select statement
        $sql = "SELECT id, username, password FROM users WHERE username = ?";

        if ($stmt = mysqli_prepare($con, $sql)) {
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $param_username);

            // Set parameters
            $param_username = $username;

            // Attempt to execute the prepared statement
            if (mysqli_stmt_execute($stmt)) {
                // Store result
                mysqli_stmt_store_result($stmt);

                // Check if username exists, if yes then verify password
                if (mysqli_stmt_num_rows($stmt) == 1) {
                    // Bind result variables
                    mysqli_stmt_bind_result($stmt, $id, $username, $hashed_password);
                    if (mysqli_stmt_fetch($stmt)) {
                        if (password_verify($password, $hashed_password)) {
                            // Password is correct, so start a new session
                            session_start();

                            // Store data in session variables
                            $_SESSION["loggedin"] = true;
                            $_SESSION["id"] = $id;
                            $_SESSION["username"] = $username;

                            // Redirect user to welcome page
                            header("location: main.php");
                        } else {
                            // Display an error message if password is not valid
                            echo '
                            <div class="toastNotificacion" style="position: absolute; top: 0; right: 0;" data-delay="4000">
                                <div class="toast-header ">
                                    <strong class="mr-auto"><i class="fa fa-bell" aria-hidden="true"
                                            style=color:green></i> Notificación</strong>
                                  
                                    <button type="button" class="ml-2 mb-1 close" data-dismiss="toast"
                                        aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="toastNotificacion-body alert-danger">
                                    <h1 class="p-3"><img src="images/favicon.png" width="20px"> <b>Contraseña incorrecta</b></h1>
                               
                                </div>
                            </div>
                       ';}
                    }
                } else {
                    // Display an error message if username doesn't exist
                    echo '
                    <div class="toastNotificacion" style="position: absolute; top: 0; right: 0;" data-delay="4000">
                        <div class="toast-header ">
                            <strong class="mr-auto"><i class="fa fa-bell" aria-hidden="true"
                                    style=color:green></i> Notificación</strong>
                          
                            <button type="button" class="ml-2 mb-1 close" data-dismiss="toast"
                                aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="toastNotificacion-body alert-warning">
                       
                            <h1 class="p-3">  <img src="images/favicon.png" width="20px"> <b>Usuario no existe</b></h1>
                       
                        </div>
                    </div>
               ';} 
            } else {
                echo '
                <div class="toastNotificacion" style="position: absolute; top: 0; right: 0;" data-delay="4000">
                    <div class="toast-header ">
                        <strong class="mr-auto"><i class="fa fa-bell" aria-hidden="true"
                                style=color:green></i> Notificación</strong>
                      
                        <button type="button" class="ml-2 mb-1 close" data-dismiss="toast"
                            aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="toastN"otificacion-body alert-info">
                        <h1 class="p-3"><img src="images/favicon.png" width="20px"> <b>Algo salio mal, intenta de nuevo</b></h1>
                   
                    </div>
                </div>
           ';
            }
        }

        // Close statement
        mysqli_stmt_close($stmt);
    }

    // Close connection
    mysqli_close($con);
}
?>
<!DOCTYPE html>
<html lang="en">
<?php
include 'head.php';
?>

<body>
    <div id="wrapper">
        <div id="left">
            <div id="signin">
                <div class="logo">
                    <div id="loader">
                        <img src="images/sicaLogo.png" alt="logo" width="100%" style='text-aling:center;'>
                        <div class="loader-wrapper"></div>
                    </div>
                </div>
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                    <div>
                        <label style="color:#fff">Usuario ID:</label>
                        <div class="input-group-prepend">
                            <div class="input-group-text form-control-lg"><i class="fa fa-user" style="cursor:pointer ;"></i></div>

                            <input type="number" name="username" placeholder="1234567890" class="text-input text-center form-control-lg" />
                        </div>
                    </div>
                    <div>
                        <label style="color:#fff">Contraseña:</label>
                        <div class="input-group-prepend">
                            <div class="input-group-text form-control-lg" id="viewPassword"><i class="fa fa-eye" style="cursor:pointer ;"></i></div>

                            <input type="password" name="password" class="text-input text-center form-control-lg w-100" id="password" placeholder="Contraseña">
                        </div>

                    </div>
                    <button type="submit" class="primary-btn btn-lg"><b>Iniciar sesión</b></button>
                </form>
                <a href="register_admin.php">Registrar</a>
            </div>
            <footer id="main-footer">
                <p class="text-center login__forgot"> <?php
                                                        $queryCompany = mysqli_query($con, "SELECT nombre FROM company");
                                                        while ($empresaLog = mysqli_fetch_array($queryCompany)) {
                                                            echo '<label  class="card-text">' . $empresaLog['nombre'] . '</label>';
                                                        }
                                                        ?></p><!--se otorga el copy a la empresa-->
                <p class="text-center login__forgot">SIGC &copy; Copyright <?php echo date("Y"); ?></p>
                <a href="https://agenciaeaglesoftware.com/" target="_blank" class="login__forgot">Made by Agencia de
                    Desarrollo
                    Eagle Software</a>
            </footer>
        </div>
        <div id="right">
            <div id="showcase">
                <div class="showcase-content">

                </div>
            </div>
        </div>
    </div>
</body>
<style>
    @import url('https://fonts.googleapis.com/css?family=Roboto:300');
    
    body {
        background-size: cover;
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' version='1.1' xmlns:xlink='http://www.w3.org/1999/xlink' xmlns:svgjs='http://svgjs.dev/svgjs' width='1440' height='560' preserveAspectRatio='none' viewBox='0 0 1440 560'%3e%3cg mask='url(%26quot%3b%23SvgjsMask1001%26quot%3b)' fill='none'%3e%3cpath d='M1512 560L0 560 L0 300.36Q38.31 266.67%2c 72 304.97Q132.65 293.62%2c 144 354.27Q170.65 308.92%2c 216 335.57Q252.41 251.98%2c 336 288.38Q381.7 262.08%2c 408 307.78Q498.82 278.6%2c 528 369.41Q567.93 289.35%2c 648 329.28Q720.7 281.98%2c 768 354.68Q811.03 325.71%2c 840 368.74Q846.56 303.3%2c 912 309.86Q938.21 264.07%2c 984 290.28Q1035.14 269.42%2c 1056 320.57Q1121.58 266.15%2c 1176 331.73Q1254.62 290.35%2c 1296 368.97Q1325.89 326.86%2c 1368 356.75Q1379.09 295.84%2c 1440 306.93Q1465.32 260.25%2c 1512 285.56z' fill='%23182f5d'%3e%3c/path%3e%3cpath d='M1464 560L0 560 L0 367.65Q37.35 333%2c 72 370.34Q117.91 344.25%2c 144 390.15Q196.2 370.35%2c 216 422.55Q267.1 353.65%2c 336 404.76Q386.09 382.85%2c 408 432.93Q428.76 333.69%2c 528 354.45Q571.61 326.06%2c 600 369.66Q673.94 323.61%2c 720 397.55Q780.45 386%2c 792 446.46Q798.07 380.53%2c 864 386.6Q885.48 336.08%2c 936 357.55Q1025.82 327.37%2c 1056 417.18Q1070.47 359.65%2c 1128 374.13Q1155.54 329.67%2c 1200 357.21Q1289.08 326.28%2c 1320 415.36Q1365.24 388.6%2c 1392 433.84Q1397.16 367%2c 1464 372.16z' fill='%2325467d'%3e%3c/path%3e%3cpath d='M1512 560L0 560 L0 477.57Q51.46 457.03%2c 72 508.5Q92.13 408.63%2c 192 428.77Q233.98 398.75%2c 264 440.73Q332.43 437.16%2c 336 505.59Q378.44 428.02%2c 456 470.46Q521.61 416.06%2c 576 481.67Q620.9 454.58%2c 648 499.48Q651.42 430.9%2c 720 434.32Q787.96 382.28%2c 840 450.25Q893.66 431.91%2c 912 485.58Q917.17 418.75%2c 984 423.91Q1025.66 393.58%2c 1056 435.24Q1105.19 412.43%2c 1128 461.62Q1177.08 438.7%2c 1200 487.78Q1217.62 433.4%2c 1272 451.02Q1343.1 402.12%2c 1392 473.21Q1474.37 435.58%2c 1512 517.94z' fill='%23356cb1'%3e%3c/path%3e%3cpath d='M1560 560L0 560 L0 525.73Q37.38 491.11%2c 72 528.48Q156.31 492.79%2c 192 577.09Q217.27 530.36%2c 264 555.63Q315.75 487.38%2c 384 539.12Q432.74 467.86%2c 504 516.6Q592.89 485.49%2c 624 574.38Q661.83 492.21%2c 744 530.05Q817.64 483.69%2c 864 557.34Q932.29 505.63%2c 984 573.93Q985.43 503.36%2c 1056 504.78Q1086.71 463.49%2c 1128 494.2Q1224.37 470.57%2c 1248 566.94Q1315.37 514.31%2c 1368 581.67Q1382.35 524.01%2c 1440 538.36Q1479.02 457.38%2c 1560 496.4z' fill='white'%3e%3c/path%3e%3c/g%3e%3cdefs%3e%3cmask id='SvgjsMask1001'%3e%3crect width='1440' height='560' fill='white'%3e%3c/rect%3e%3c/mask%3e%3c/defs%3e%3c/svg%3e");
    }

    h1,
    h2,
    h3,
    h4,
    h5,
    h6 {
        font-weight: 300;
    }

    #wrapper {
        display: flex;
        flex-direction: row;
    }

    #left {
        display: flex;
        flex-direction: column;
        flex: 1;
        align-items: center;
        justify-content: center;
        height: 100vh;
        background-image: linear-gradient(rgba(2, 52, 81), rgba(2, 52, 81, 0.8));
        box-shadow: 0 50px 70px -20px rgba(0, 0, 0, 0.85);
        background-size: cover;
    }


    #right {
        flex: 1;
    }

    /* Sign In */
    #signin {
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        width: 80%;
        padding-bottom: 1rem;
    }

    #signin form {
        width: 80%;
        padding-bottom: 3rem;
    }

    #signin .logo {
        margin-bottom: 8vh;
    }

    #signin .logo img {
        width: 300px;
    }

    #signin label {
        font-size: 0.9rem;
        line-height: 2rem;
        font-weight: 500;
    }

    #signin .text-input {
        margin-bottom: 1.3rem;
        width: 100%;
        border-radius: 2px;
        background: #181818;
        border: 1px solid #555;
        color: #ccc;
        padding: 0.5rem 1rem;
        line-height: 1.3rem;
    }

    #signin .primary-btn {
        width: 100%;
    }

    #signin .secondary-btn,
    .or,
    .links {
        width: 60%;
    }

    #signin .links a {
        display: block;
        color: #fff;
        text-decoration: none;
        margin-bottom: 1rem;
        text-align: center;
        font-size: 0.9rem;
    }

    #signin .or {
        display: flex;
        flex-direction: row;
        margin-bottom: 1.2rem;
        align-items: center;
    }


    #signin .or span {
        color: #fff;
        padding: 0 0.8rem;
    }

    /* Showcase */
    #showcase {
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh;
        text-align: center;
    }



    #showcase .showcase-text {
        font-size: 3rem;
        width: 100%;
        color: #F9DA5A;
        margin-bottom: 1.5rem;
    }

    #showcase .secondary-btn {
        width: 60%;
        margin: auto;
    }

    /* Footer */
    #main-footer {
        color: #fff;
        text-align: center;
        font-size: 0.8rem;
        max-width: 80%;
        padding-top: 5rem;
    }

    #main-footer a {
        color: #fff;
        text-decoration: underline;
    }

    /* Button */
    .primary-btn {
        padding: 0.7rem 1rem;
    height: 4rem;
    display: block;
    border: 0;
    border-radius: 40px;
    font-weight: 500;
    background: #F3AC46;
    color: #ffffff;
    text-decoration: none;
    cursor: pointer;
    text-align: center;
    transition: all 0.5s;
    }

    .primary-btn:hover {
        background-color: #23f207;
    transition: all 0.5s;
    font-size: 24px;
    border-radius: 0px;
    color: #181818;
    }

    .secondary-btn {
        padding: 0.7rem 1rem;
        height: 2.7rem;
        display: block;
        border: 1px solid #F9DA5A;
        border-radius: 2px;
        font-weight: 500;
        background: none;
        color: #F9DA5A;
        text-decoration: none;
        cursor: pointer;
        text-align: center;
        transition: all 0.5s;
    }

    .secondary-btn:hover {
        border-color: #F9DA5A;
        color: #F9DA5A;
    }

    /* Media Queries */
    @media (min-width: 1200px) {
        #left {
            flex: 4;
        }

        #right {
            flex: 6;
        }
    }

    @media (max-width: 768px) {
        body {
            overflow: auto;
        }

        #right {
            display: none;
        }

        #left {
            justify-content: start;
        }

        #signin .logo {
            margin-bottom: 2vh;
        }

        #signin .text-input {
            margin-bottom: 0.7rem;
        }

        #main-footer {
            padding-top: 1rem;
        }
    }

    @import url('https://fonts.googleapis.com/css?family=Roboto+Condensed:400,600,700,200');

    .wrapper::before {
        position: absolute;
        width: 100%;
        height: 600px;
        background: linear-gradient(40deg, #00BCD4, #2196F3);
        content: '';
        transform-origin: top left;
        transform: skewY(-12deg);
        z-index: -1;
        top: 0;
        bottom: 0;
        left: 0;
        right: 0
    }

    .wrapper #loader {
        width: 100vw;
        height: 100vh;
        display: flex;
        justify-content: center;
        align-items: center;
        flex-direction: column;
    }

    .wrapper #loader #logo {
        font-family: 'Roboto Condensed', sans-serif;
        color: #FFF;
        font-size: 3rem;
        font-weight: 800;
        text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
    }

    .loader-wrapper {
        height: 20px;
        width: 200px;
    }

    .loader-wrapper::before {
        content: '';
        width: 20px;
        height: 20px;
        background-color: #fff;
        border-radius: 50%;
        display: block;
        position: relative;
        left: 85%;
        box-shadow: 30px 0px 0px 0px black;
        animation: loading 2s cubic-bezier(1, 0.03, 0.19, 1.46) infinite;
        -webkit-animation: loading 2000ms cubic-bezier(1, 0.03, 0.19, 1.46) infinite;
    }

    @keyframes loading {
        0% {
            left: 85%;
        }

        50% {
            left: 0%;
        }

        100% {
            left: 85%;
        }
    }
</style>
<script>
    //funcion para imagenes aleatorias
    $(document).ready(function() {
        var classCycle = ['imageCycle1', 'imageCycle2'];

        var randomNumber = Math.floor(Math.random() * classCycle.length);
        var classToAdd = classCycle[randomNumber];

        $('body').addClass(classToAdd);

    });
</script>
<script>
    //view pass
    let password = document.getElementById('password');
    let viewPassword = document.getElementById('viewPassword');
    let click = false;

    viewPassword.addEventListener('click', (e) => {
        if (!click) {
            password.type = 'text'
            click = true
        } else if (click) {
            password.type = 'password'
            click = false
        }
    })
</script>

<script>
    $(document).ready(function() {
        $(".toastNotificacion").toast('show');
    });
</script>
</html>