<?php require_once 'core/dbConfig.php'; ?>
<?php require_once 'core/models.php'; ?>
<?php 
// Redirect to login if user is not logged in
if (!isset($_SESSION['username'])) {
	header("Location: login.php");
	exit();
}

// Initialize search query variable and array to store results
$searchQuery = isset($_GET['q']) ? $_GET['q'] : '';
$searchResults = [];

// If a search query exists, fetch the search results from the database
if (!empty($searchQuery)) {
	$searchResults = searchBooks($pdo, $searchQuery, $_SESSION['username']);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Biblioteca — Search Library</title>
	<link rel="stylesheet" href="styles.css">
</head>
<body>

	<!-- Library Header -->
	<div class="library-header">
		<h1>Biblioteca</h1>
		<p>Search Authors, Genres & Books</p>
		<div class="header-nav">
			<span class="user-greeting">Logged in as: <strong><?php echo htmlspecialchars($_SESSION['username']); ?></strong></span>
			<a href="index.php">← Return to Library</a>
			<a href="activitylogs.php">Activity Logs</a>
			<a href="core/handleForms.php?logoutAUser=1">Logout</a>
		</div>
	</div>

	<div class="main-container">
		
		<!-- ===================== SEARCH FORM ===================== -->
		<div class="catalog-card">
			<h2>Search the Catalog</h2>
			<form action="search.php" method="GET">
				<div class="form-row">
					<div class="form-group" style="flex: 1;">
						<label for="q">Search for Books, Authors, or Genres</label>
						<input type="text" name="q" id="q" value="<?php echo htmlspecialchars($searchQuery); ?>" placeholder="Type keyword..." required>
					</div>
					<div class="form-group" style="flex: 0; align-self: flex-end;">
						<button type="submit" class="btn btn-primary">Search</button>
					</div>
				</div>
			</form>
		</div>

		<!-- ===================== SEARCH RESULTS TABLE ===================== -->
		<?php if (!empty($searchQuery)) { ?>
			<h2 class="section-title">Search Results for "<?php echo htmlspecialchars($searchQuery); ?>"</h2>

			<?php if (count($searchResults) > 0) { ?>
			<table class="books-table">
				<thead>
					<tr>
						<th>ID</th>
						<th>Title</th>
						<th>Edition</th>
						<th>Author</th>
						<th>Genre</th>
						<th>Published</th>
						<th>Rating</th>
						<th>Date Added</th>
						<th>Action</th>
					</tr>
				</thead>
				<tbody>
					<?php foreach ($searchResults as $row) { ?>
					<tr>
						<td><?php echo $row['book_id']; ?></td>
						<td><strong><?php echo htmlspecialchars($row['title']); ?></strong></td>
						<td><?php echo htmlspecialchars($row['edition']); ?></td>
						<td><?php echo htmlspecialchars($row['author_name']); ?></td>
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
						<td class="table-actions">
							<a href="viewbooks.php?author_id=<?php echo $row['author_id']; ?>" class="action-view">View Author's Books</a>
						</td>
					</tr>
					<?php } ?>
				</tbody>
			</table>
			<?php } else { ?>
			<div class="empty-state">
				No results found for your search query. Try another keyword!
			</div>
			<?php } ?>

		<?php } else { ?>
			<div class="empty-state">
				Enter a keyword above to search for books by title, author, or genre.
			</div>
		<?php } ?>

	</div>

</body>
</html>
