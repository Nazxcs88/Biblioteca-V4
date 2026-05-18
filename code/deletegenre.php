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
	<title>Biblioteca — Delete Genre</title>
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
			<h1>Delete Genre</h1>
			<p style="color: var(--red-accent); margin-bottom: 20px; font-weight: 600;">
				Are you sure you want to delete this genre? All books in this genre will also be removed.
			</p>

			<h2><strong>Genre:</strong> <?php echo htmlspecialchars($getGenreByID['genre_name']); ?></h2>
			<h2><strong>Description:</strong> <?php echo htmlspecialchars($getGenreByID['description']); ?></h2>
			<h2><strong>Date Added:</strong> <?php echo $getGenreByID['date_added']; ?></h2>

			<div style="margin-top: 25px; display: flex; gap: 12px;">
				<form action="core/handleForms.php?genre_id=<?php echo $_GET['genre_id']; ?>" method="POST">
					<button type="submit" name="deleteGenreBtn" class="btn btn-danger">Confirm Delete</button>
				</form>
				<a href="index.php" class="btn btn-gold" style="display: inline-block; text-align: center;">Cancel</a>
			</div>
		</div>
	</div>

</body>
</html>
