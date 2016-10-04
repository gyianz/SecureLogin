CREATE TABLE IF NOT EXISTS members (
	id INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
	username VARCHAR(30) NOT NULL,
	password VARCHAR(128) NOT NULL,
	rank INTEGER NOT NULL
);


CREATE TABLE IF NOT EXISTS login_attempts (
	id INTEGER NOT NULL,
	time VARCHAR(30) NOT NULL
);

CREATE TABLE IF NOT EXISTS session (
	username VARCHAR (30) NOT NULL PRIMARY KEY,
	expiry VARCHAR(30) NOT NULL,
	logged_in BOOLEAN NOT NULL,
	session_id VARCHAR(128) NOT NULL
);