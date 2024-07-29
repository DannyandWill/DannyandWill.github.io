<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agregar Usuario</title>
</head>
<body>
    <div class="container">
        <h2>Agregar Usuario</h2>
        <form action="procesar_usuario.php" method="post">
            <div class="form-group">
                <label for="nombre">Nombre:</label>
                <input type="text" name="nombre" id="nombre" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" name="email" id="email" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="contrasena">Contraseña:</label>
                <input type="password" name="contrasena" id="contrasena" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="rol_id">Rol:</label>
                <select name="rol_id" id="rol_id" class="form-control">
                    <option value="1">Administrador</option>
                    <option value="2">Cliente</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Agregar Usuario</button>
        </form>
    </div>
</body>
</html>
