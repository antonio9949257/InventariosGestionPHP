<?php session_start(); ?>
<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Login</title>
	<link href="./adi_bootstrap/css/bootstrap.min.css" rel="stylesheet">
	<style>
		body {
			background-color: #212529; /* Fondo oscuro */
			color: #f8f9fa; /* Texto claro */
			display: flex;
			justify-content: center;
			align-items: center;
			min-height: 100vh;
			margin: 0;
		}
		.login-card {
			background: #343a40; /* Fondo de la tarjeta un poco más claro */
			border: none;
			border-radius: 15px;
			box-shadow: 0 10px 25px rgba(0, 0, 0, 0.5);
			padding: 40px;
			width: 100%;
			max-width: 400px;
		}
		.login-card h2 {
			color: #f8f9fa; /* Texto claro para el título */
			text-align: center;
			margin-bottom: 30px;
			font-weight: 700;
		}
		.form-control {
			background-color: #495057; /* Fondo de input más oscuro */
			border: 1px solid #6c757d;
			color: #f8f9fa; /* Texto de input claro */
		}
		.form-control:focus {
			background-color: #495057;
			border-color: #80bdff; /* Azul de foco de Bootstrap */
			box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
			color: #f8f9fa;
		}
        .form-control::placeholder { /* Estilo para el texto de placeholder */
            color: #adb5bd;
        }
		.btn-primary {
			background-color: #0d6efd; /* Azul primario de Bootstrap */
			border-color: #0d6efd;
			font-weight: 600;
		}
		.btn-primary:hover {
			background-color: #0b5ed7;
			border-color: #0a58ca;
		}
	</style>
</head>
<body>
	<div class="login-card">
		<h2>FORMULARIO DE LOGIN</h2>
		<?php
		if (isset($_SESSION['error_message'])) {
			echo '<div class="alert alert-danger text-center" role="alert">' . $_SESSION['error_message'] . '</div>';
			unset($_SESSION['error_message']); 
		}
		?>
		<form action="Validar.php" method="POST">
			<div class="mb-3">
				<input type="text" class="form-control" placeholder="Usuario" name="usuario" required>
			</div>
			<div class="mb-3">
				<input type="password" class="form-control" placeholder="Contraseña" name="clave" required>
			</div>
			<div class="d-grid">
				<input type="submit" class="btn btn-primary" value="Ingresar">
			</div>
		</form>
	</div>

	<script src="./adi_bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>