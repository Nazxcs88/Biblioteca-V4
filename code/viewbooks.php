<?php require_once 'core/dbConfig.php'; ?>
<?php require_once 'core/models.php'; ?>
<?php 
// Redirect to login if user is not logged in
if (!isset($_SESSION['username'])) {
	header("Location: login.php");
	exit();
}

if (isset($_GET['author_id'])) {
	logAction($pdo, 'READ', 'Author Books', $_GET['author_id'], $_SESSION['username'], "Viewed books for Author ID: " . $_GET['author_id']);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Biblioteca — Author's Books</title>
	<link rel="stylesheet" href="styles.css">
</head>
<body>

	<!-- Library Header -->
	<div class="library-header">
		<h1>Biblioteca</h1>
		<p>A Curated Collection of Authors, Genres & Books</p>
		<div class="header-nav">
			<span class="user-greeting">Logged in as: <strong><?php echo htmlspecialchars($_SESSION['username']); ?></strong></span>
			<a href="index.php">← Return to Library</a>
			<a href="core/handleForms.php?logoutAUser=1">Logout</a>
		</div>
	</div>

	<div class="main-container">

		<?php $getAuthorByID = getAuthorByID($pdo, $_GET['author_id']); ?>

		<h2 class="section-title">
			<?php echo htmlspecialchars($getAuthorByID['first_name']) . ' ' . htmlspecialchars($getAuthorByID['last_name']); ?>'s Bookshelf
		</h2>

		<!-- ===================== ADD BOOK FORM ===================== -->
		<div class="catalog-card">
			<h2>Add a New Book</h2>
			<form action="core/handleForms.php?author_id=<?php echo $_GET['author_id']; ?>" method="POST">
				<div class="form-row">
					<div class="form-group">
						<label for="title">Book Title</label>
						<input type="text" name="title" id="title" required>
					</div>
					<div class="form-group">
						<label for="edition">Edition</label>
						<input type="text" name="edition" id="edition">
					</div>
					<div class="form-group">
						<label for="datePublished">Date Published</label>
						<input type="date" name="datePublished" id="datePublished">
					</div>
				</div>
				<div class="form-row" style="margin-top: 12px;">
					<div class="form-group">
						<label for="description">Description</label>
						<input type="text" name="description" id="description">
					</div>
					<div class="form-group">
						<label for="bookRating">Rating (1-5)</label>
						<input type="number" name="bookRating" id="bookRating" min="1" max="5">
					</div>
					<div class="form-group">
						<label for="genreId">Genre</label>
						<select name="genreId" id="genreId">
							<option value="">-- Select Genre --</option>
							<?php $getAllGenres = getAllGenres($pdo); ?>
							<?php foreach ($getAllGenres as $genre) { ?>
							<option value="<?php echo $genre['genre_id']; ?>">
								<?php echo $genre['genre_name']; ?>
							</option>
							<?php } ?>
						</select>
					</div>
					<div class="form-group" style="flex: 0;">
						<label>&nbsp;</label>
						<button type="submit" name="insertNewBookBtn" class="btn btn-primary">Add Book</button>
					</div>
				</div>
			</form>
		</div>

		<!-- ===================== BOOKS TABLE ===================== -->
		<?php $getBooksByAuthor = getBooksByAuthor($pdo, $_GET['author_id']); ?>

		<?php if (count($getBooksByAuthor) > 0) { ?>
		<table class="books-table">
			<thead>
				<tr>
					<th>ID</th>
					<th>Title</th>
					<th>Edition</th>
					<th>Genre</th>
					<th>Published</th>
					<th>Rating</th>
					<th>Date Added</th>
					<th>Added By</th>
					<th>Last Updated By</th>
					<th>Last Updated</th>
					<th>Action</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ($getBooksByAuthor as $row) { ?>
				<tr>
					<td><?php echo $row['book_id']; ?></td>
					<td><strong><?php echo htmlspecialchars($row['title']); ?></strong></td>
					<td><?php echo htmlspecialchars($row['edition']); ?></td>
					<td><?php echo htmlspecialchars($row['genre_name']); ?></td>
					<td><?php echo $row['date_published']; ?></td>
					<td>
						<span class="rating">
							<?php 
								$r = intval($row['book_rating']);
								for ($i = 0; $i < $r; $i++) { echo "★"; }
								for ($i = $r; $i < 5; $i++) { echo "☆"; }
							?>
						</span>
					</td>
					<td><?php echo $row['date_added']; ?></td>
					<td><?php echo $row['added_by'] ? htmlspecialchars($row['added_by']) : 'N/A'; ?></td>
					<td><?php echo $row['last_updated_by'] ? htmlspecialchars($row['last_updated_by']) : 'N/A'; ?></td>
					<td><?php echo $row['last_updated']; ?></td>
					<td class="table-actions">
						<a href="editbook.php?book_id=<?php echo $row['book_id']; ?>&author_id=<?php echo $_GET['author_id']; ?>" class="action-edit">Edit</a>
						<a href="deletebook.php?book_id=<?php echo $row['book_id']; ?>&author_id=<?php echo $_GET['author_id']; ?>" class="action-delete">Delete</a>
					</td>
				</tr>
				<?php } ?>
			</tbody>
		</table>
		<?php } else { ?>
		<div class="empty-state">
			No books yet. Add the first book to this author's shelf!
		</div>
		<?php } ?>

	</div>

</body>
</html>
