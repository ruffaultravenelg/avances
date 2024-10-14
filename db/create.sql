-- Use `sqlite3 data.db < create.sql`

CREATE TABLE IF NOT EXISTS AVANCES (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    nom TEXT NOT NULL,
    somme REAL NOT NULL,
    creation_date DATETIME NOT NULL, -- No default cause it doesn't work, use `DATETIME('now', 'localtime')` in code
    admin INTEGER NOT NULL -- creator -> USERS.id
);

CREATE TABLE IF NOT EXISTS USERS (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    nom TEXT NOT NULL,
    pass TEXT NOT NULL
);

CREATE TABLE IF NOT EXISTS REMBOURSEMENTS (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    nom TEXT NOT NULL,
    somme REAL NOT NULL,
    payement_date DATETIME NOT NULL,
    admin INTEGER NOT NULL -- admin -> USERS.id
);

-- Create admin user (meant to be destroyed after first connection)
INSERT INTO USERS (nom, pass) VALUES ('admin', '$2y$10$6vDPzGWCQcj47WiR7GGiGuOB3V8nGQoBr7AUa/RE3I02ZjvXfhHSS'); -- admin with password 'admin'