<?php require_once 'core/dbConfig.php'; ?>
<?php require_once 'core/models.php'; ?>
<?php 
// Redirect to login if user is not logged in
if (!isset($_SESSION['username'])) {
	header("Location: login.php");
	exit();
}

logAction($pdo, 'READ', 'Dashboard', null, $_SESSION['username'], "Viewed Library Dashboard");
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Biblioteca — Your Library</title>
	<link rel="stylesheet" href="styles.css">
</head>
<body>

	<!-- Library Header -->
	<div class="library-header">
		<h1>Biblioteca</h1>
		<p>A Curated Collection of Authors, Genres & Books</p>
		<div class="header-nav">
			<span class="user-greeting">Logged in as: <strong><?php echo htmlspecialchars($_SESSION['username']); ?></strong></span>
			<a href="search.php">Search</a>
			<a href="activitylogs.php">Activity Logs</a>
			<a href="core/handleForms.php?logoutAUser=1">Logout</a>
		</div>
	</div>

	<div class="main-container">

		<?php if (isset($_SESSION['message'])) { ?>
			<div class="auth-message" style="margin-bottom: 20px;"><?php echo htmlspecialchars($_SESSION['message']); ?></div>
		<?php } unset($_SESSION['message']); ?>

		<!-- ===================== ADD AUTHOR FORM ===================== -->
		<div class="catalog-card">
			<h2>Register a New Author</h2>
			<form action="core/handleForms.php" method="POST">
				<div class="form-row">
					<div class="form-group">
						<label for="firstName">First Name</label>
						<input type="text" name="firstName" id="firstName" required>
					</div>
					<div class="form-group">
						<label for="lastName">Last Name</label>
						<input type="text" name="lastName" id="lastName" required>
					</div>

				</div>
				<div class="form-row" style="margin-top: 12px;">
					<div class="form-group">
						<label for="publisher">Publisher</label>
						<input type="text" name="publisher" id="publisher">
					</div>
					<div class="form-group">
						<label for="specialization">Specialization</label>
						<input type="text" name="specialization" id="specialization">
					</div>
					<div class="form-group" style="flex: 0;">
						<label>&nbsp;</label>
						<button type="submit" name="insertAuthorBtn" class="btn btn-primary">Add Author</button>
					</div>
				</div>
			</form>
		</div>

		<!-- ===================== ADD GENRE FORM ===================== -->
		<div class="catalog-card">
			<h2>Register a New Genre</h2>
			<form action="core/handleForms.php" method="POST">
				<div class="form-row">
					<div class="form-group">
						<label for="genreName">Genre Name</label>
						<input type="text" name="genreName" id="genreName" required>
					</div>
					<div class="form-group">
						<label for="genreDescription">Description</label>
						<input type="text" name="description" id="genreDescription">
					</div>
					<div class="form-group" style="flex: 0;">
						<label>&nbsp;</label>
						<button type="submit" name="insertGenreBtn" class="btn btn-primary">Add Genre</button>
					</div>
				</div>
			</form>
		</div>

		<hr class="divider">

		<!-- ===================== AUTHORS SHELF ===================== -->
		<h2 class="section-title">Authors Shelf</h2>

		<?php $getAllAuthors = getAllAuthors($pdo); ?>

		<?php if (count($getAllAuthors) > 0) { ?>
		<div class="shelf-grid">
			<?php foreach ($getAllAuthors as $row) { ?>
			<div class="shelf-card">
				<div class="shelf-card-header">
					<h3><?php echo htmlspecialchars($row['first_name']) . ' ' . htmlspecialchars($row['last_name']); ?></h3>
					<div class="subtitle"><?php echo htmlspecialchars($row['specialization']); ?></div>
				</div>
				<div class="shelf-card-body">
					<p><strong>Publisher:</strong> <?php echo htmlspecialchars($row['publisher']); ?></p>
					<p><strong>Rating:</strong> 
						<span class="rating">
							<?php 
								$r = intval($row['rating']);
								for ($i = 0; $i < $r; $i++) { echo "★"; }
								for ($i = $r; $i < 5; $i++) { echo "☆"; }
							?>
						</span>
					</p>
					<p><strong>Added:</strong> <?php echo $row['date_added']; ?></p>
					<p><strong>Added by:</strong> <?php echo $row['added_by'] ? htmlspecialchars($row['added_by']) : 'N/A'; ?></p>
					<p><strong>Last updated by:</strong> <?php echo $row['last_updated_by'] ? htmlspecialchars($row['last_updated_by']) : 'N/A'; ?></p>
					<p><strong>Last updated:</strong> <?php echo $row['last_updated']; ?></p>
				</div>
				<div class="shelf-card-actions">
					<a href="viewbooks.php?author_id=<?php echo $row['author_id']; ?>" class="action-view">View Books</a>
					<a href="editauthor.php?author_id=<?php echo $row['author_id']; ?>" class="action-edit">Edit</a>
					<a href="deleteauthor.php?author_id=<?php echo $row['author_id']; ?>" class="action-delete">Delete</a>
				</div>
			</div>
			<?php } ?>
		</div>
		<?php } else { ?>
		<div class="empty-state">
			No authors registered yet. Add your first author above!
		</div>
		<?php } ?>

		<hr class="divider">

		<!-- ===================== GENRES SHELF ===================== -->
		<h2 class="section-title">Genres Shelf</h2>

		<?php $getAllGenres = getAllGenres($pdo); ?>

		<?php if (count($getAllGenres) > 0) { ?>
		<div class="shelf-grid">
			<?php foreach ($getAllGenres as $row) { ?>
			<div class="shelf-card">
				<div class="shelf-card-header" style="background: linear-gradient(135deg, var(--green-dark), var(--green-accent));">
					<h3><?php echo htmlspecialchars($row['genre_name']); ?></h3>
				</div>
				<div class="shelf-card-body">
					<p><strong>Description:</strong> <?php echo htmlspecialchars($row['description']); ?></p>
					<p><strong>Added:</strong> <?php echo $row['date_added']; ?></p>
					<p><strong>Added by:</strong> <?php echo $row['added_by'] ? htmlspecialchars($row['added_by']) : 'N/A'; ?></p>
					<p><strong>Last updated by:</strong> <?php echo $row['last_updated_by'] ? htmlspecialchars($row['last_updated_by']) : 'N/A'; ?></p>
					<p><strong>Last updated:</strong> <?php echo $row['last_updated']; ?></p>
				</div>
				<div class="shelf-card-actions">
					<a href="viewgenre.php?genre_id=<?php echo $row['genre_id']; ?>" class="action-view">View Books</a>
					<a href="editgenre.php?genre_id=<?php echo $row['genre_id']; ?>" class="action-edit">Edit</a>
					<a href="deletegenre.php?genre_id=<?php echo $row['genre_id']; ?>" class="action-delete">Delete</a>
				</div>
			</div>
			<?php } ?>
		</div>
		<?php } else { ?>
		<div class="empty-state">
			No genres registered yet. Add your first genre above!
		</div>
		<?php } ?>

	</div>

</body>
</html>