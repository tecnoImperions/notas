<?php
require_once 'config.php';

$mensaje = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario = trim($_POST['usuario']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    if ($usuario && $email && $password) {
        $password_hash = password_hash($password, PASSWORD_BCRYPT);

        // Insertar usuario en Supabase
        $resultado = supabaseInsert('usuarios', [
            'usuario' => $usuario,
            'email' => $email,
            'password' => $password_hash,
            'role' => 'usuario',
            'verificado' => true
        ]);

        if ($resultado['httpCode'] == 201) {
            $mensaje = "Usuario registrado correctamente en Supabase ✅";
        } else {
            $mensaje = "Error al registrar: " . json_encode($resultado['response']);
        }
    } else {
        $mensaje = "Completa todos los campos.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Registro Supabase Test</title>
</head>
<body>
<h2>Registro de Usuario</h2>
<?php if ($mensaje) echo "<p>$mensaje</p>"; ?>
<form method="POST">
    <input type="text" name="usuario" placeholder="Usuario" required><br><br>
    <input type="email" name="email" placeholder="Email" required><br><br>
    <input type="password" name="password" placeholder="Contraseña" required><br><br>
    <button type="submit">Registrar</button>
</form>
</body>
</html>
