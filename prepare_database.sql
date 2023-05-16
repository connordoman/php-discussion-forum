-- COSC 360 Project
-- Prepared by: Connor Doman

-- This script will create the database and tables for the project

-- Create the database
CREATE DATABASE IF NOT EXISTS stackking360;

-- Use the database
USE stackking360;

-- Create the tables
CREATE TABLE IF NOT EXISTS users (
    uid INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(255) NOT NULL,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    firstName VARCHAR(255) NOT NULL,
    lastName VARCHAR(255) NOT NULL,
    joinDate DATETIME NOT NULL,
    lastLogin DATETIME NOT NULL,
    appeal INT,
    profilePic VARCHAR(255)
);

CREATE TABLE IF NOT EXISTS posts (
    pid INT PRIMARY KEY AUTO_INCREMENT,
    uid INT NOT NULL,
    content TEXT NOT NULL,
    postDate DATETIME NOT NULL,
    appeal INT
);

CREATE TABLE IF NOT EXISTS questions (
    qid INT PRIMARY KEY AUTO_INCREMENT,
    title VARCHAR(255) NOT NULL,
    pid INT NOT NULL,
    top INT,
    FOREIGN KEY (pid) REFERENCES posts(pid),
    FOREIGN KEY (top) REFERENCES posts(pid)
);

CREATE TABLE IF NOT EXISTS comment (
    mid INT PRIMARY KEY AUTO_INCREMENT,
    pid INT NOT NULL,
    uid INT NOT NULL,
    message TEXT NOT NULL,
    postDate DATETIME NOT NULL,
    appeal INT,
    FOREIGN KEY (pid) REFERENCES posts(pid),
    FOREIGN KEY (uid) REFERENCES users(uid)
);

CREATE TABLE IF NOT EXISTS replies (
    rid INT PRIMARY KEY AUTO_INCREMENT,
    qid INT NOT NULL,
    pid INT NOT NULL,
    FOREIGN KEY (qid) REFERENCES questions(qid),
    FOREIGN KEY (pid) REFERENCES posts(pid)
);

CREATE TABLE IF NOT EXISTS tags (
    tid INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL
);

CREATE TABLE IF NOT EXISTS categories (
    cid INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL
);

CREATE TABLE IF NOT EXISTS question_tags (
    qid INT NOT NULL,
    tid INT NOT NULL,
    PRIMARY KEY (qid, tid),
    FOREIGN KEY (qid) REFERENCES questions(qid),
    FOREIGN KEY (tid) REFERENCES tags(tid)
);

CREATE TABLE IF NOT EXISTS question_categories (
    qid INT NOT NULL,
    cid INT NOT NULL,
    PRIMARY KEY (qid, cid),
    FOREIGN KEY (qid) REFERENCES questions(qid),
    FOREIGN KEY (cid) REFERENCES categories(cid)
);

CREATE TABLE IF NOT EXISTS moderators (
    modid INT PRIMARY KEY AUTO_INCREMENT,
    uid INT NOT NULL,
    FOREIGN KEY (uid) REFERENCES users(uid)
);

CREATE TABLE IF NOT EXISTS administrators (
    adminid INT PRIMARY KEY AUTO_INCREMENT,
    uid INT NOT NULL,
    FOREIGN KEY (uid) REFERENCES users(uid)
);

CREATE TABLE IF NOT EXISTS banned_posts (
    pid INT PRIMARY KEY,
    FOREIGN KEY (pid) REFERENCES posts(pid)
);

CREATE TABLE IF NOT EXISTS banned_users (
    uid INT PRIMARY KEY,
    FOREIGN KEY (uid) REFERENCES users(uid)
);