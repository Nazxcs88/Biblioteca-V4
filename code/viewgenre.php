<?php require_once 'core/dbConfig.php'; ?>
<?php require_once 'core/models.php'; ?>
<?php 
// Redirect to login if user is not logged in
if (!isset($_SESSION['username'])) {
	header("Location: login.php");
	exit();
}

if (isset($_GET['genre_id'])) {
	logAction($pdo, 'READ', 'Genre Books', $_GET['genre_id'], $_SESSION['username'], "Viewed books for Genre ID: " . $_GET['genre_id']);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Biblioteca — Genre Collection</title>
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

		<?php $getGenreByID = getGenreByID($pdo, $_GET['genre_id']); ?>

		<h2 class="section-title">
			Genre: <?php echo htmlspecialchars($getGenreByID['genre_name']); ?>
		</h2>

		<p style="color: var(--ink-light); margin-bottom: 25px; font-size: 1.05em;">
			<?php echo htmlspecialchars($getGenreByID['description']); ?>
		</p>

		<!-- ===================== BOOKS TABLE ===================== -->
		<?php $getBooksByGenre = getBooksByGenre($pdo, $_GET['genre_id']); ?>

		<?php if (count($getBooksByGenre) > 0) { ?>
		<table class="books-table">
			<thead>
				<tr>
					<th>ID</th>
					<th>Title</th>
					<th>Edition</th>
					<th>Author</th>
					<th>Published</th>
					<th>Rating</th>
					<th>Date Added</th>
					<th>Added By</th>
					<th>Last Updated By</th>
					<th>Last Updated</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ($getBooksByGenre as $row) { ?>
				<tr>
					<td><?php echo $row['book_id']; ?></td>
					<td><strong><?php echo htmlspecialchars($row['title']); ?></strong></td>
					<td><?php echo htmlspecialchars($row['edition']); ?></td>
					<td><?php echo htmlspecialchars($row['author_name']); ?></td>
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
				</tr>
				<?php } ?>
			</tbody>
		</table>
		<?php } else { ?>
		<div class="empty-state">
			No books in this genre yet.
		</div>
		<?php } ?>

	</div>

</body>
</html>
