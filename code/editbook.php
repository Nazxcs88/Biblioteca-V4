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
	<title>Biblioteca — Edit Book</title>
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
			<h1>Edit Book</h1>
			<form action="core/handleForms.php?book_id=<?php echo $_GET['book_id']; ?>&author_id=<?php echo $_GET['author_id']; ?>" method="POST">
				<div class="form-group" style="margin-bottom: 12px;">
					<label for="title">Book Title</label>
					<input type="text" name="title" id="title" value="<?php echo htmlspecialchars($getBookByID['title']); ?>">
				</div>
				<div class="form-group" style="margin-bottom: 12px;">
					<label for="edition">Edition</label>
					<input type="text" name="edition" id="edition" value="<?php echo htmlspecialchars($getBookByID['edition']); ?>">
				</div>
				<div class="form-group" style="margin-bottom: 12px;">
					<label for="datePublished">Date Published</label>
					<input type="date" name="datePublished" id="datePublished" value="<?php echo $getBookByID['date_published']; ?>">
				</div>
				<div class="form-group" style="margin-bottom: 12px;">
					<label for="description">Description</label>
					<textarea name="description" id="description" rows="3"><?php echo htmlspecialchars($getBookByID['description']); ?></textarea>
				</div>
				<div class="form-group" style="margin-bottom: 18px;">
					<label for="bookRating">Rating (1-5)</label>
					<input type="number" name="bookRating" id="bookRating" min="1" max="5" value="<?php echo $getBookByID['book_rating']; ?>">
				</div>
				<button type="submit" name="editBookBtn" class="btn btn-gold">Update Book</button>
			</form>
		</div>
	</div>

</body>
</html>
