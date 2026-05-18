CREATE TABLE user_passwords (
	user_id INT AUTO_INCREMENT PRIMARY KEY,
	username VARCHAR(50),
	password VARCHAR(255),
	date_added TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE activity_logs (
    log_id INT AUTO_INCREMENT PRIMARY KEY,
    operation VARCHAR(50),
    entity VARCHAR(50),
    entity_id INT,
    username VARCHAR(50),
    description TEXT,
    timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE genres (
    genre_id INT AUTO_INCREMENT PRIMARY KEY,
    genre_name VARCHAR(50),
    description TEXT,
    added_by VARCHAR(50),
    last_updated_by VARCHAR(50),
    last_updated TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    date_added TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE authors (
    author_id INT AUTO_INCREMENT PRIMARY KEY,
    first_name VARCHAR(50),
    last_name VARCHAR(50),
    publisher VARCHAR(50),
    specialization TEXT,
    added_by VARCHAR(50),
    last_updated_by VARCHAR(50),
    last_updated TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    date_added TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE books (
    book_id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(50),
    edition VARCHAR(50),
    date_published DATE,
    description TEXT,
    book_rating TINYINT,
    date_added TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    added_by VARCHAR(50),
    last_updated_by VARCHAR(50),
    last_updated TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    author_id INT,
    genre_id INT,
    FOREIGN KEY (author_id) REFERENCES authors(author_id),
    FOREIGN KEY (genre_id) REFERENCES genres(genre_id)
);


-- Sample Genres
INSERT INTO genres (genre_name, description) VALUES
('Fantasy', 'Tales of magic, mythical creatures, and otherworldly settings'),
('Sci-Fi', 'Stories exploring futuristic science, technology, and space'),
('Horror', 'Stories designed to frighten, unsettle, and disturb'),
('LitRPG', 'Fiction blending RPG game mechanics with fantasy storytelling');


-- Sample Authors
INSERT INTO authors (first_name, last_name, publisher, specialization) VALUES
('Brandon', 'Sanderson', 'Tor Books', 'Epic Fantasy'),
('J.K.', 'Rowling', 'Bloomsbury', 'Fantasy'),
('Pierce', 'Brown', 'Del Rey Books', 'Sci-Fi'),
('Andy', 'Weir', 'Crown Publishing', 'Hard Sci-Fi'),
('Stephen', 'King', 'Doubleday', 'Horror and Thriller'),
('Matt', 'Dinniman', 'Self-Published', 'LitRPG'),
('', 'Zogarth', 'Self-Published', 'LitRPG');


-- Sample Books
INSERT INTO books (title, edition, date_published, description, book_rating, author_id, genre_id) VALUES
('The Way of Kings', '1st Edition', '2010-08-31', 'The first book of the Stormlight Archive epic', 5, 1, 1),
('Words of Radiance', '1st Edition', '2014-03-04', 'The second book of the Stormlight Archive', 5, 1, 1),
('The Final Empire', '1st Edition', '2006-07-17', 'A crew of thieves attempts to overthrow a godlike ruler', 5, 1, 1),
('Harry Potter and the Sorcerer\'s Stone', '1st Edition', '1997-06-26', 'A young boy discovers he is a wizard', 5, 2, 1),
('Harry Potter and the Chamber of Secrets', '1st Edition', '1998-07-02', 'Harry returns to Hogwarts and faces a hidden danger', 4, 2, 1),
('Harry Potter and the Prisoner of Azkaban', '1st Edition', '1999-07-08', 'A dangerous prisoner escapes from the wizarding prison', 5, 2, 1),
('Red Rising', '1st Edition', '2014-01-28', 'A miner infiltrates the ruling class to ignite a revolution', 5, 3, 2),
('Golden Son', '1st Edition', '2015-01-06', 'Darrow continues his war against the Society from within', 5, 3, 2),
('Morning Star', '1st Edition', '2016-02-09', 'The revolution reaches its explosive conclusion', 5, 3, 2),
('The Martian', '1st Edition', '2011-09-27', 'An astronaut is stranded alone on Mars and must survive', 5, 4, 2),
('Project Hail Mary', '1st Edition', '2021-05-04', 'A lone astronaut must save the Earth from an extinction threat', 5, 4, 2),
('The Shining', '1st Edition', '1977-01-28', 'A family encounters supernatural evil at the Overlook Hotel', 5, 5, 3),
('It', '1st Edition', '1986-09-15', 'A shape-shifting monster terrorizes the town of Derry', 4, 5, 3),
('The Stand', '1st Edition', '1978-10-03', 'A post-apocalyptic struggle between good and evil', 5, 5, 3),
('Dungeon Crawler Carl', '1st Edition', '2020-04-12', 'A man and his cat battle through a deadly dungeon game', 5, 6, 4),
('The Dungeon Anarchist\'s Cookbook', '1st Edition', '2021-05-10', 'Carl dives deeper into the dungeon with explosive results', 5, 6, 4),
('The Primal Hunter', '1st Edition', '2021-01-15', 'Earth is turned into a game and one man must rise above', 4, 7, 4),
('The Primal Hunter 2', '2nd Book', '2021-06-20', 'Jake continues to grow stronger in the new world order', 4, 7, 4);