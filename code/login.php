<?php 
require_once 'core/dbConfig.php'; 
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Biblioteca — Login</title>
	<link rel="stylesheet" href="styles.css">
</head>
<body>

	<!-- Library Header -->
	<div class="library-header">
		<h1>Biblioteca</h1>
		<p>A Curated Collection of Authors, Genres & Books</p>
	</div>

	<div class="main-container">
		<div class="auth-card">
			<h1>Welcome Back</h1>
			<p class="auth-subtitle">Sign in to manage your library</p>

			<?php if (isset($_SESSION['message'])) { ?>
				<div class="auth-message"><?php echo htmlspecialchars($_SESSION['message']); ?></div>
			<?php } unset($_SESSION['message']); ?>

			<form action="core/handleForms.php" method="POST">
				<div class="form-group" style="margin-bottom: 15px;">
					<label for="username">Username</label>
					<input type="text" name="username" id="username" required>
				</div>
				<div class="form-group" style="margin-bottom: 20px;">
					<label for="password">Password</label>
					<input type="password" name="password" id="password" required>
				</div>
				<button type="submit" name="loginUserBtn" class="btn btn-primary" style="width: 100%;">Login</button>
			</form>

			<p class="auth-footer">Don't have an account? <a href="register.php">Register here</a></p>
		</div>
	</div>

</body>
</html>
