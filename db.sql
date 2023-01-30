CREATE DATABASE Blog;

CREATE TABLE user (
    id INT NOT NULL AUTO_INCREMENT,
    firstName VARCHAR(255),
    lastName VARCHAR(255),
    username VARCHAR(255) UNIQUE,
    email VARCHAR(255) UNIQUE,
    passwordHash VARCHAR(255),
    registeredAt DATE,
    profile VARCHAR(255),
    PRIMARY KEY (id)
);

CREATE TABLE post (
    id INT NOT NULL AUTO_INCREMENT,
    authorId INT NOT NULL,
    title VARCHAR(255),
    createdAt DATE,
    updatedAt DATE,
    content TEXT,
    PRIMARY KEY (id),
    FOREIGN KEY (authorId) REFERENCES user(id)

);


CREATE TABLE reactions (
    id INT NOT NULL AUTO_INCREMENT,
    postId INT NOT NULL,
    reactorId INT NOT NULL,
    comments TEXT,
    subscribers VARCHAR(255),
    PRIMARY KEY (id),
    FOREIGN KEY (reactorId) REFERENCES user(id),
    FOREIGN KEY (postId) REFERENCES post(id)


);

CREATE TABLE postviews (
    id INT NOT NULL AUTO_INCREMENT,
    postId INT NOT NULL,
    views INT,
    PRIMARY KEY (id),
    FOREIGN KEY (postId) REFERENCES post(id)


);