<?php 
require_once 'core/dbConfig.php'; 
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Biblioteca — Register</title>
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
			<h1>Create Account</h1>
			<p class="auth-subtitle">Register to start cataloging</p>

			<?php if (isset($_SESSION['message'])) { ?>
				<div class="auth-message"><?php echo htmlspecialchars($_SESSION['message']); ?></div>
			<?php } unset($_SESSION['message']); ?>

				<form action="core/handleForms.php" method="POST">
				<div class="form-group" style="margin-bottom: 15px;">
					<label for="username">Username</label>
					<input type="text" name="username" id="username" required>
				</div>
				<div class="form-group" style="margin-bottom: 15px;">
					<label for="password">Password</label>
					<input type="password" name="password" id="password" required>
				</div>
				<div class="form-group" style="margin-bottom: 20px;">
					<label for="confirm_password">Confirm Password</label>
					<input type="password" name="confirm_password" id="confirm_password" required>
				</div>
				<button type="submit" name="registerUserBtn" class="btn btn-primary" style="width: 100%;">Register</button>
			</form>

			<p class="auth-footer">Already have an account? <a href="login.php">Login here</a></p>
		</div>
	</div>

</body>
</html>
