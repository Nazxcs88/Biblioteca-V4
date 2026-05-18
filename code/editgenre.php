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
	<title>Biblioteca — Edit Genre</title>
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

		<?php $getGenreByID = getGenreByID($pdo, $_GET['genre_id']); ?>

		<div class="page-card">
			<h1>Edit Genre</h1>
			<form action="core/handleForms.php?genre_id=<?php echo $_GET['genre_id']; ?>" method="POST">
				<div class="form-group" style="margin-bottom: 12px;">
					<label for="genreName">Genre Name</label>
					<input type="text" name="genreName" id="genreName" value="<?php echo htmlspecialchars($getGenreByID['genre_name']); ?>">
				</div>
				<div class="form-group" style="margin-bottom: 18px;">
					<label for="description">Description</label>
					<textarea name="description" id="description" rows="3"><?php echo htmlspecialchars($getGenreByID['description']); ?></textarea>
				</div>
				<button type="submit" name="editGenreBtn" class="btn btn-gold">Update Genre</button>
			</form>
		</div>
	</div>

</body>
</html>
