<?php require_once 'core/dbConfig.php'; ?>
<?php require_once 'core/models.php'; ?>
<?php 
// Redirect to login if user is not logged in
if (!isset($_SESSION['username'])) {
	header("Location: login.php");
	exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Biblioteca — Edit Author</title>
	<link rel="stylesheet" href="styles.css">
</head>
<body>

	<!-- Library Header -->
	<div class="library-header">
		<h1>Biblioteca</h1>
		<p>A Curated Collection of Authors, Genres & Books</p>
		<div class="header-nav">
			<span class="user-greeting">Logged in as: <strong><?php echo htmlspecialchars($_SESSION['username']); ?></strong></span>
			<a href="core/handleForms.php?logoutAUser=1">Logout</a>
		</div>
	</div>

	<div class="main-container">
		<a href="index.php" class="back-link">← Back to Library</a>

		<?php $getAuthorByID = getAuthorByID($pdo, $_GET['author_id']); ?>

		<div class="page-card">
			<h1>Edit Author</h1>
			<form action="core/handleForms.php?author_id=<?php echo $_GET['author_id']; ?>" method="POST">
				<div class="form-group" style="margin-bottom: 12px;">
					<label for="firstName">First Name</label>
					<input type="text" name="firstName" id="firstName" value="<?php echo htmlspecialchars($getAuthorByID['first_name']); ?>">
				</div>
				<div class="form-group" style="margin-bottom: 12px;">
					<label for="lastName">Last Name</label>
					<input type="text" name="lastName" id="lastName" value="<?php echo htmlspecialchars($getAuthorByID['last_name']); ?>">
				</div>
				<div class="form-group" style="margin-bottom: 12px;">
					<label for="publisher">Publisher</label>
					<input type="text" name="publisher" id="publisher" value="<?php echo htmlspecialchars($getAuthorByID['publisher']); ?>">
				</div>
				<div class="form-group" style="margin-bottom: 18px;">
					<label for="specialization">Specialization</label>
					<input type="text" name="specialization" id="specialization" value="<?php echo htmlspecialchars($getAuthorByID['specialization']); ?>">
				</div>
				<button type="submit" name="editAuthorBtn" class="btn btn-gold">Update Author</button>
			</form>
		</div>
	</div>

</body>
</html>
