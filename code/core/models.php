<?php 

// ===================== USER FUNCTIONS =====================

// Insert a new user into the database (registration)
function insertNewUser($pdo, $username, $password) {

	$checkUserSql = "SELECT * FROM user_passwords WHERE username = ?";
	$checkUserSqlStmt = $pdo->prepare($checkUserSql);
	$checkUserSqlStmt->execute([$username]);

	if ($checkUserSqlStmt->rowCount() == 0) {

		$sql = "INSERT INTO user_passwords (username, password) VALUES(?,?)";
		$stmt = $pdo->prepare($sql);
		$executeQuery = $stmt->execute([$username, $password]);

		if ($executeQuery) {
			$_SESSION['message'] = "User successfully registered";
			return true;
		}

		else {
			$_SESSION['message'] = "An error occured from the query";
		}

	}
	else {
		$_SESSION['message'] = "User already exists";
	}

	
}

// Login a user by checking username and password
function loginUser($pdo, $username, $password) {
	$sql = "SELECT * FROM user_passwords WHERE username = ?";
	$stmt = $pdo->prepare($sql);
	$stmt->execute([$username]); 

	if ($stmt->rowCount() == 1) {
		$userInfoRow = $stmt->fetch();
		$usernameFromDB = $userInfoRow['username']; 
		$passwordFromDB = $userInfoRow['password'];

		if (password_verify($password, $passwordFromDB)) {
			$_SESSION['username'] = $usernameFromDB;
			$_SESSION['message'] = "Login successful!";
			return true;
		}

		else {
			$_SESSION['message'] = "Username/password invalid";
		}
	}

	
	if ($stmt->rowCount() == 0) {
		$_SESSION['message'] = "Username/password invalid";
	}

}

// Fetch all users from the database
function getAllUsers($pdo) {
	$sql = "SELECT * FROM user_passwords";
	$stmt = $pdo->prepare($sql);
	$executeQuery = $stmt->execute();

	if ($executeQuery) {
		return $stmt->fetchAll();
	}

}

// Fetch a single user by their ID
function getUserByID($pdo, $user_id) {
	$sql = "SELECT * FROM user_passwords WHERE user_id = ?";
	$stmt = $pdo->prepare($sql);
	$executeQuery = $stmt->execute([$user_id]);
	if ($executeQuery) {
		return $stmt->fetch();
	}
}


// ===================== ACTIVITY LOG FUNCTIONS =====================

// Insert a new activity log record into the database
function logAction($pdo, $operation, $entity, $entity_id, $username, $description) {
	$sql = "INSERT INTO activity_logs (operation, entity, entity_id, username, description) VALUES (?, ?, ?, ?, ?)";
	$stmt = $pdo->prepare($sql);
	$stmt->execute([$operation, $entity, $entity_id, $username, $description]);
}

// Fetch all activity logs from the database
function getAllActivityLogs($pdo) {
	$sql = "SELECT * FROM activity_logs ORDER BY timestamp DESC";
	$stmt = $pdo->prepare($sql);
	$stmt->execute();
	return $stmt->fetchAll();
}



// ===================== AUTHOR FUNCTIONS =====================

// Insert a new author into the database
function insertAuthor($pdo, $first_name, $last_name, $publisher, $specialization, $added_by) {

	$sql = "INSERT INTO authors (first_name, last_name, publisher, specialization, added_by, last_updated_by) VALUES(?,?,?,?,?,?)";

	$stmt = $pdo->prepare($sql);
	$executeQuery = $stmt->execute([$first_name, $last_name, $publisher, $specialization, $added_by, $added_by]);

	if ($executeQuery) {
		$author_id = $pdo->lastInsertId();
		logAction($pdo, 'CREATE', 'Author', $author_id, $added_by, "Inserted new author: $first_name $last_name");
		return true;
	}
}

// Update an existing author record
function updateAuthor($pdo, $first_name, $last_name, $publisher, $specialization, $author_id, $updated_by) {

	$sql = "UPDATE authors
				SET first_name = ?,
					last_name = ?,
					publisher = ?,
					specialization = ?,
					last_updated_by = ?
				WHERE author_id = ?
			";
	$stmt = $pdo->prepare($sql);
	$executeQuery = $stmt->execute([$first_name, $last_name, $publisher, $specialization, $updated_by, $author_id]);
	
	if ($executeQuery) {
		logAction($pdo, 'UPDATE', 'Author', $author_id, $updated_by, "Updated author details for $first_name $last_name");
		return true;
	}

}

// Delete an author and all their associated books
function deleteAuthor($pdo, $author_id, $deleted_by) {
	// First get author details for logging
	$author = getAuthorByID($pdo, $author_id);
	$author_name = $author ? $author['first_name'] . ' ' . $author['last_name'] : 'Unknown';

	$deleteAuthorBooks = "DELETE FROM books WHERE author_id = ?";
	$deleteStmt = $pdo->prepare($deleteAuthorBooks);
	$executeDeleteQuery = $deleteStmt->execute([$author_id]);

	if ($executeDeleteQuery) {
		$sql = "DELETE FROM authors WHERE author_id = ?";
		$stmt = $pdo->prepare($sql);
		$executeQuery = $stmt->execute([$author_id]);

		if ($executeQuery) {
			logAction($pdo, 'DELETE', 'Author', $author_id, $deleted_by, "Deleted author: $author_name");
			return true;
		}

	}
	
}

// Fetch all authors with their average book rating
function getAllAuthors($pdo) {
	$sql = "SELECT authors.*, 
				ROUND(AVG(books.book_rating)) AS rating
			FROM authors
			LEFT JOIN books ON authors.author_id = books.author_id
			GROUP BY authors.author_id";
	$stmt = $pdo->prepare($sql);
	$executeQuery = $stmt->execute();

	if ($executeQuery) {
		return $stmt->fetchAll();
	}
}

// Fetch a single author by their ID with average book rating
function getAuthorByID($pdo, $author_id) {
	$sql = "SELECT authors.*, 
				ROUND(AVG(books.book_rating)) AS rating
			FROM authors
			LEFT JOIN books ON authors.author_id = books.author_id
			WHERE authors.author_id = ?
			GROUP BY authors.author_id";
	$stmt = $pdo->prepare($sql);
	$executeQuery = $stmt->execute([$author_id]);

	if ($executeQuery) {
		return $stmt->fetch();
	}
}


// ===================== GENRE FUNCTIONS =====================

// Insert a new genre into the database
function insertGenre($pdo, $genre_name, $description, $added_by) {

	$sql = "INSERT INTO genres (genre_name, description, added_by, last_updated_by) VALUES(?,?,?,?)";

	$stmt = $pdo->prepare($sql);
	$executeQuery = $stmt->execute([$genre_name, $description, $added_by, $added_by]);

	if ($executeQuery) {
		$genre_id = $pdo->lastInsertId();
		logAction($pdo, 'CREATE', 'Genre', $genre_id, $added_by, "Inserted new genre: $genre_name");
		return true;
	}
}

// Update an existing genre record
function updateGenre($pdo, $genre_name, $description, $genre_id, $updated_by) {

	$sql = "UPDATE genres
				SET genre_name = ?,
					description = ?,
					last_updated_by = ?
				WHERE genre_id = ?
			";
	$stmt = $pdo->prepare($sql);
	$executeQuery = $stmt->execute([$genre_name, $description, $updated_by, $genre_id]);
	
	if ($executeQuery) {
		logAction($pdo, 'UPDATE', 'Genre', $genre_id, $updated_by, "Updated genre details for: $genre_name");
		return true;
	}

}

// Delete a genre and all its associated books
function deleteGenre($pdo, $genre_id, $deleted_by) {
	$genre = getGenreByID($pdo, $genre_id);
	$genre_name = $genre ? $genre['genre_name'] : 'Unknown';

	$deleteGenreBooks = "DELETE FROM books WHERE genre_id = ?";
	$deleteStmt = $pdo->prepare($deleteGenreBooks);
	$executeDeleteQuery = $deleteStmt->execute([$genre_id]);

	if ($executeDeleteQuery) {
		$sql = "DELETE FROM genres WHERE genre_id = ?";
		$stmt = $pdo->prepare($sql);
		$executeQuery = $stmt->execute([$genre_id]);

		if ($executeQuery) {
			logAction($pdo, 'DELETE', 'Genre', $genre_id, $deleted_by, "Deleted genre: $genre_name");
			return true;
		}

	}
	
}

// Fetch all genres from the database
function getAllGenres($pdo) {
	$sql = "SELECT * FROM genres";
	$stmt = $pdo->prepare($sql);
	$executeQuery = $stmt->execute();

	if ($executeQuery) {
		return $stmt->fetchAll();
	}
}

// Fetch a single genre by its ID
function getGenreByID($pdo, $genre_id) {
	$sql = "SELECT * FROM genres WHERE genre_id = ?";
	$stmt = $pdo->prepare($sql);
	$executeQuery = $stmt->execute([$genre_id]);

	if ($executeQuery) {
		return $stmt->fetch();
	}
}


// ===================== BOOK FUNCTIONS =====================

// Fetch all books by a specific author using JOIN
function getBooksByAuthor($pdo, $author_id) {
	
	$sql = "SELECT 
				books.book_id AS book_id,
				books.title AS title,
				books.edition AS edition,
				books.date_published AS date_published,
				books.description AS description,
				books.book_rating AS book_rating,
				books.date_added AS date_added,
				books.added_by AS added_by,
				books.last_updated_by AS last_updated_by,
				books.last_updated AS last_updated,
				CONCAT(authors.first_name,' ',authors.last_name) AS author_name,
				genres.genre_name AS genre_name
			FROM books
			JOIN authors ON books.author_id = authors.author_id
			LEFT JOIN genres ON books.genre_id = genres.genre_id
			WHERE books.author_id = ? 
			ORDER BY books.title;
			";

	$stmt = $pdo->prepare($sql);
	$executeQuery = $stmt->execute([$author_id]);
	if ($executeQuery) {
		return $stmt->fetchAll();
	}
}

// Fetch all books by a specific genre using JOIN
function getBooksByGenre($pdo, $genre_id) {
	
	$sql = "SELECT 
				books.book_id AS book_id,
				books.title AS title,
				books.edition AS edition,
				books.date_published AS date_published,
				books.description AS description,
				books.book_rating AS book_rating,
				books.date_added AS date_added,
				books.added_by AS added_by,
				books.last_updated_by AS last_updated_by,
				books.last_updated AS last_updated,
				CONCAT(authors.first_name,' ',authors.last_name) AS author_name,
				genres.genre_name AS genre_name
			FROM books
			JOIN genres ON books.genre_id = genres.genre_id
			LEFT JOIN authors ON books.author_id = authors.author_id
			WHERE books.genre_id = ? 
			ORDER BY books.title;
			";

	$stmt = $pdo->prepare($sql);
	$executeQuery = $stmt->execute([$genre_id]);
	if ($executeQuery) {
		return $stmt->fetchAll();
	}
}

// Insert a new book into the database
function insertBook($pdo, $title, $edition, $date_published, $description, $book_rating, $author_id, $genre_id, $added_by) {
	$sql = "INSERT INTO books (title, edition, date_published, description, book_rating, author_id, genre_id, added_by, last_updated_by) VALUES (?,?,?,?,?,?,?,?,?)";
	$stmt = $pdo->prepare($sql);
	$executeQuery = $stmt->execute([$title, $edition, $date_published, $description, $book_rating, $author_id, $genre_id, $added_by, $added_by]);
	if ($executeQuery) {
		$book_id = $pdo->lastInsertId();
		logAction($pdo, 'CREATE', 'Book', $book_id, $added_by, "Inserted new book: $title");
		return true;
	}

}

// Fetch a single book by its ID using JOIN
function getBookByID($pdo, $book_id) {
	$sql = "SELECT 
				books.book_id AS book_id,
				books.title AS title,
				books.edition AS edition,
				books.date_published AS date_published,
				books.description AS description,
				books.book_rating AS book_rating,
				books.date_added AS date_added,
				books.added_by AS added_by,
				books.last_updated_by AS last_updated_by,
				books.last_updated AS last_updated,
				books.author_id AS author_id,
				books.genre_id AS genre_id,
				CONCAT(authors.first_name,' ',authors.last_name) AS author_name,
				genres.genre_name AS genre_name
			FROM books
			LEFT JOIN authors ON books.author_id = authors.author_id
			LEFT JOIN genres ON books.genre_id = genres.genre_id
			WHERE books.book_id = ?";

	$stmt = $pdo->prepare($sql);
	$executeQuery = $stmt->execute([$book_id]);
	if ($executeQuery) {
		return $stmt->fetch();
	}
}

// Update an existing book record
function updateBook($pdo, $title, $edition, $date_published, $description, $book_rating, $book_id, $updated_by) {
	$sql = "UPDATE books
			SET title = ?,
				edition = ?,
				date_published = ?,
				description = ?,
				book_rating = ?,
				last_updated_by = ?
			WHERE book_id = ?
			";
	$stmt = $pdo->prepare($sql);
	$executeQuery = $stmt->execute([$title, $edition, $date_published, $description, $book_rating, $updated_by, $book_id]);

	if ($executeQuery) {
		logAction($pdo, 'UPDATE', 'Book', $book_id, $updated_by, "Updated book details for: $title");
		return true;
	}
}

// Delete a single book by its ID
function deleteBook($pdo, $book_id, $deleted_by) {
	$book = getBookByID($pdo, $book_id);
	$title = $book ? $book['title'] : 'Unknown';

	$sql = "DELETE FROM books WHERE book_id = ?";
	$stmt = $pdo->prepare($sql);
	$executeQuery = $stmt->execute([$book_id]);
	if ($executeQuery) {
		logAction($pdo, 'DELETE', 'Book', $book_id, $deleted_by, "Deleted book: $title");
		return true;
	}
}


// ===================== SEARCH FUNCTIONS =====================

// Search for books, authors, and genres based on a search query
function searchBooks($pdo, $searchQuery, $username = null) {
	if ($username) {
		logAction($pdo, 'READ', 'Search', null, $username, "Searched for: $searchQuery");
	}

	$sql = "SELECT 
				books.book_id AS book_id,
				books.title AS title,
				books.edition AS edition,
				books.date_published AS date_published,
				books.description AS description,
				books.book_rating AS book_rating,
				books.date_added AS date_added,
				books.added_by AS added_by,
				books.last_updated_by AS last_updated_by,
				books.last_updated AS last_updated,
				books.author_id AS author_id,
				books.genre_id AS genre_id,
				CONCAT(authors.first_name,' ',authors.last_name) AS author_name,
				genres.genre_name AS genre_name
			FROM books
			LEFT JOIN authors ON books.author_id = authors.author_id
			LEFT JOIN genres ON books.genre_id = genres.genre_id
			WHERE books.title LIKE ? 
			   OR authors.first_name LIKE ? 
			   OR authors.last_name LIKE ? 
			   OR CONCAT(authors.first_name, ' ', authors.last_name) LIKE ?
			   OR genres.genre_name LIKE ?
			ORDER BY books.title";

	$stmt = $pdo->prepare($sql);
	$likeQuery = '%' . $searchQuery . '%';
	$executeQuery = $stmt->execute([$likeQuery, $likeQuery, $likeQuery, $likeQuery, $likeQuery]);
	if ($executeQuery) {
		return $stmt->fetchAll();
	}
}


?>
