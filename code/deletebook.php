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
	<title>Biblioteca — Delete Book</title>
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
		<a href="viewbooks.php?author_id=<?php echo $_GET['author_id']; ?>" class="back-link">← Back to Bookshelf</a>

		<?php $getBookByID = getBookByID($pdo, $_GET['book_id']); ?>

		<div class="page-card">
			<h1>Delete Book</h1>
			<p style="color: var(--red-accent); margin-bottom: 20px; font-weight: 600;">
				Are you sure you want to delete this book?
			</p>

			<h2><strong>Title:</strong> <?php echo htmlspecialchars($getBookByID['title']); ?></h2>
			<h2><strong>Edition:</strong> <?php echo htmlspecialchars($getBookByID['edition']); ?></h2>
			<h2><strong>Author:</strong> <?php echo htmlspecialchars($getBookByID['author_name']); ?></h2>
			<h2><strong>Genre:</strong> <?php echo htmlspecialchars($getBookByID['genre_name']); ?></h2>
			<h2><strong>Published:</strong> <?php echo $getBookByID['date_published']; ?></h2>
			<h2><strong>Rating:</strong> <?php echo $getBookByID['book_rating']; ?>/5</h2>
			<h2><strong>Date Added:</strong> <?php echo $getBookByID['date_added']; ?></h2>

			<div style="margin-top: 25px; display: flex; gap: 12px;">
				<form action="core/handleForms.php?book_id=<?php echo $_GET['book_id']; ?>&author_id=<?php echo $_GET['author_id']; ?>" method="POST">
					<button type="submit" name="deleteBookBtn" class="btn btn-danger">Confirm Delete</button>
				</form>
				<a href="viewbooks.php?author_id=<?php echo $_GET['author_id']; ?>" class="btn btn-gold" style="display: inline-block; text-align: center;">Cancel</a>
			</div>
		</div>
	</div>

</body>
</html>
