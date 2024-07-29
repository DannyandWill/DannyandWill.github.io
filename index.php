<?php
$alert = '';
session_start();

if (!empty($_SESSION['active'])) {
    header('location: Proyecto/');
} else {
    if (!empty($_POST)) {
        if (empty($_POST['usuario']) || empty($_POST['clave'])) {
            $alert = '<div class="alert alert-danger" role="alert">
                Ingrese su usuario y su clave
                </div>';
        } else {
            require_once "conexion.php";
            
            $user = mysqli_real_escape_string($conexion, $_POST['usuario']); // Email o nombre ingresado
            $clave = mysqli_real_escape_string($conexion, $_POST['clave']); // Contraseña ingresada

            // Preparar la consulta
            $stmt = $conexion->prepare("SELECT u.id, u.nombre, u.email, u.contrasena, r.id AS rol_id, r.nombre AS rol 
                                        FROM usuario u 
                                        INNER JOIN rol r ON u.rol_id = r.id 
                                        WHERE (u.nombre = ? OR u.email = ?)");
            $stmt->bind_param("ss", $user, $user);
            $stmt->execute();
            $result = $stmt->get_result();

            // Verificar si se encontraron resultados
            if ($result->num_rows > 0) {
                // Procesar los resultados
                $row = $result->fetch_assoc();

                // Verificar la contraseña
                if (password_verify($clave, $row['contrasena'])) {
                    $_SESSION['active'] = true;
                    $_SESSION['id'] = $row['id'];
                    $_SESSION['nombre'] = $row['nombre'];
                    $_SESSION['email'] = $row['email'];
                    $_SESSION['rol_id'] = $row['rol_id'];
                    $_SESSION['rol'] = $row['rol'];

                    header('location: Proyecto/');
                } else {
                    $alert = '<div class="alert alert-danger" role="alert">
                        Usuario o Contraseña Incorrecta
                        </div>';
                    session_destroy();
                }
            } else {
                $alert = '<div class="alert alert-danger" role="alert">
                    Usuario o Contraseña Incorrecta
                    </div>';
                session_destroy();
            }

            // Cerrar la consulta y la conexión
            $stmt->close();
            mysqli_close($conexion);
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>Login</title>

    <!-- Bootstrap CSS CDN -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="css/styles.css" rel="stylesheet">
</head>

<body class="bg-gradient-primary">
    <div class="container">
        <!-- Outer Row -->
        <div class="row justify-content-center">
            <div class="col-xl-10 col-lg-12 col-md-9">
                <div class="card o-hidden border-0 shadow-lg my-5">
                    <div class="card-body p-0">
                        <!-- Nested Row within Card Body -->
                        <div class="row">
                            <div class="col-lg-6 d-none d-lg-block bg-login-image">
                                <img src="Proyecto/img/logo.jpg" class="img-thumbnail">
                            </div>
                            <div class="col-lg-6">
                                <div class="p-5">
                                    <div class="text-center">
                                        <h1 class="h4 text-gray-900 mb-4">Iniciar Sesión</h1>
                                    </div>
                                    <form class="user" method="POST">
                                        <?php echo isset($alert) ? $alert : ""; ?>
                                        <div class="form-group">
                                            <label for="usuario">Usuario o Email</label>
                                            <input type="text" class="form-control" placeholder="admin o admin@example.com" name="usuario" id="usuario">
                                        </div>
                                        <div class="form-group">
                                            <label for="clave">Contraseña</label>
                                            <input type="password" class="form-control" placeholder="admin" name="clave" id="clave">
                                        </div>
                                        <input type="submit" value="Iniciar" class="btn btn-primary">
                                        <hr>
                                    </form>
                                    <hr>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap core JavaScript -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <!-- Custom scripts for all pages -->
    <script src="js/scripts.js"></script>
</body>

</html>
