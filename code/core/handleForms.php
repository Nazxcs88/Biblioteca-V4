<?php 

require_once 'dbConfig.php'; 
require_once 'models.php';
require_once 'validate.php';

// ===================== USER HANDLERS =====================

// Handle user registration
if (isset($_POST['registerUserBtn'])) {

	// Sanitize the username input to prevent XSS attacks
	$username = sanitizeInput($_POST['username']);
	$password = $_POST['password'];
	$confirm_password = $_POST['confirm_password'];

	if (!empty($username) && !empty($password) && !empty($confirm_password)) {

		// Check if both passwords match before proceeding
		if ($password == $confirm_password) {

			// Validate password strength using validatePassword()
			if (validatePassword($password)) {

				$hashedPassword = password_hash($password, PASSWORD_DEFAULT);
				$insertQuery = insertNewUser($pdo, $username, $hashedPassword);

				if ($insertQuery) {
					header("Location: ../login.php");
				}
				else {
					header("Location: ../register.php");
				}
			}

			else {
				// Password did not meet strength requirements
				$_SESSION['message'] = "Password should be more than 8 characters and should contain both uppercase, lowercase, and numbers";
				header("Location: ../register.php");
			}
		}

		else {
			$_SESSION['message'] = "Please check if both passwords are equal!";
			header("Location: ../register.php");
		}

	}

	else {
		$_SESSION['message'] = "Please make sure the input fields are not empty for registration!";
		header("Location: ../register.php");
	}

}

// Handle user login
if (isset($_POST['loginUserBtn'])) {

	// Sanitize the username input to prevent XSS attacks
	$username = sanitizeInput($_POST['username']);
	$password = $_POST['password'];

	if (!empty($username) && !empty($password)) {

		$loginQuery = loginUser($pdo, $username, $password);
	
		if ($loginQuery) {
			header("Location: ../index.php");
		}
		else {
			header("Location: ../login.php");
		}

	}

	else {
		$_SESSION['message'] = "Please make sure the input fields are not empty for the login!";
		header("Location: ../login.php");
	}
 
}

// Handle user logout
if (isset($_GET['logoutAUser'])) {
	unset($_SESSION['username']);
	header('Location: ../login.php');
}


// ===================== AUTHOR HANDLERS =====================

// Handle inserting a new author
if (isset($_POST['insertAuthorBtn'])) {

	// Sanitize all author input fields to prevent XSS attacks
	$firstName = sanitizeInput($_POST['firstName']);
	$lastName = sanitizeInput($_POST['lastName']);
	$publisher = sanitizeInput($_POST['publisher']);
	$specialization = sanitizeInput($_POST['specialization']);

	$query = insertAuthor($pdo, $firstName, 
		$lastName, $publisher, $specialization, $_SESSION['username']);

	if ($query) {
		header("Location: ../index.php");
	}
	else {
		echo "Insertion failed";
	}

}

// Handle updating an existing author
if (isset($_POST['editAuthorBtn'])) {
	// Sanitize all author input fields to prevent XSS attacks
	$firstName = sanitizeInput($_POST['firstName']);
	$lastName = sanitizeInput($_POST['lastName']);
	$publisher = sanitizeInput($_POST['publisher']);
	$specialization = sanitizeInput($_POST['specialization']);

	$query = updateAuthor($pdo, $firstName, $lastName, 
		$publisher, $specialization, $_GET['author_id'], $_SESSION['username']);

	if ($query) {
		header("Location: ../index.php");
	}

	else {
		echo "Edit failed";
	}

}

// Handle deleting an author
if (isset($_POST['deleteAuthorBtn'])) {
	$query = deleteAuthor($pdo, $_GET['author_id'], $_SESSION['username']);

	if ($query) {
		header("Location: ../index.php");
	}

	else {
		echo "Deletion failed";
	}
}


// ===================== GENRE HANDLERS =====================

// Handle inserting a new genre
if (isset($_POST['insertGenreBtn'])) {

	// Sanitize all genre input fields to prevent XSS attacks
	$genreName = sanitizeInput($_POST['genreName']);
	$description = sanitizeInput($_POST['description']);

	$query = insertGenre($pdo, $genreName, $description, $_SESSION['username']);

	if ($query) {
		header("Location: ../index.php");
	}
	else {
		echo "Insertion failed";
	}

}

// Handle updating an existing genre
if (isset($_POST['editGenreBtn'])) {
	// Sanitize all genre input fields to prevent XSS attacks
	$genreName = sanitizeInput($_POST['genreName']);
	$description = sanitizeInput($_POST['description']);

	$query = updateGenre($pdo, $genreName, $description, $_GET['genre_id'], $_SESSION['username']);

	if ($query) {
		header("Location: ../index.php");
	}
	else {
		echo "Edit failed";
	}

}

// Handle deleting a genre
if (isset($_POST['deleteGenreBtn'])) {
	$query = deleteGenre($pdo, $_GET['genre_id'], $_SESSION['username']);

	if ($query) {
		header("Location: ../index.php");
	}
	else {
		echo "Deletion failed";
	}
}


// ===================== BOOK HANDLERS =====================

// Handle inserting a new book
if (isset($_POST['insertNewBookBtn'])) {
	// Sanitize all book input fields to prevent XSS attacks
	$title = sanitizeInput($_POST['title']);
	$edition = sanitizeInput($_POST['edition']);
	$datePublished = sanitizeInput($_POST['datePublished']);
	$description = sanitizeInput($_POST['description']);
	$bookRating = sanitizeInput($_POST['bookRating']);

	$query = insertBook($pdo, $title, $edition, $datePublished, $description, $bookRating, $_GET['author_id'], $_POST['genreId'], $_SESSION['username']);

	if ($query) {
		header("Location: ../viewbooks.php?author_id=" .$_GET['author_id']);
	}
	else {
		echo "Insertion failed";
	}
}

// Handle updating an existing book
if (isset($_POST['editBookBtn'])) {
	// Sanitize all book input fields to prevent XSS attacks
	$title = sanitizeInput($_POST['title']);
	$edition = sanitizeInput($_POST['edition']);
	$datePublished = sanitizeInput($_POST['datePublished']);
	$description = sanitizeInput($_POST['description']);
	$bookRating = sanitizeInput($_POST['bookRating']);

	$query = updateBook($pdo, $title, $edition, $datePublished, $description, $bookRating, $_GET['book_id'], $_SESSION['username']);

	if ($query) {
		header("Location: ../viewbooks.php?author_id=" .$_GET['author_id']);
	}
	else {
		echo "Update failed";
	}

}

// Handle deleting a book
if (isset($_POST['deleteBookBtn'])) {
	$query = deleteBook($pdo, $_GET['book_id'], $_SESSION['username']);

	if ($query) {
		header("Location: ../viewbooks.php?author_id=" .$_GET['author_id']);
	}
	else {
		echo "Deletion failed";
	}
}



?>
