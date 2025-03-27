-- Use the existing LMS database 
USE lms_sawinee_srisakul;

-- Create Users Table if it does not exist
CREATE TABLE IF NOT EXISTS Users (
    MemberID INT AUTO_INCREMENT PRIMARY KEY,
    MemberType ENUM('member', 'admin') DEFAULT 'member' NOT NULL,
    FirstName VARCHAR(20),
    LastName VARCHAR(20),
    EmailAddress VARCHAR(50),
    PasswordHash VARCHAR(80) NOT NULL
);

-- Create Books Table if it does not exist
CREATE TABLE IF NOT EXISTS Books (
    BookID INT AUTO_INCREMENT PRIMARY KEY,
    BookTitle VARCHAR(30),
    Author VARCHAR(30),
    Publisher VARCHAR(30),
    Language ENUM('english', 'french', 'german', 'mandarin', 'japanese', 'russian', 'other') DEFAULT 'english' NOT NULL,
    Category ENUM('fiction', 'nonfiction', 'reference') DEFAULT 'fiction' NOT NULL,
    CoverImagePath VARCHAR(255)
);

-- Create BookStatus Table with corrected foreign key references
CREATE TABLE IF NOT EXISTS BookStatus (
    StatusID INT AUTO_INCREMENT PRIMARY KEY,
    BookID INT,
    MemberID INT,
    Status VARCHAR(50),
    AppliedDate DATETIME DEFAULT NOW(),
    BorrowedDate DATETIME,
    ReturnDueDate DATETIME AS (DATE_ADD(BorrowedDate, INTERVAL 21 DAY)),
    ReturnedDate DATE, 
    FOREIGN KEY (BookID) REFERENCES Books(BookID),
    FOREIGN KEY (MemberID) REFERENCES Users(MemberID) -- Reference Users table instead of Members
);

-- Insert sample data into Users table
INSERT INTO Users (MemberType, FirstName, LastName, EmailAddress, PasswordHash)
VALUES 
('member', 'sawinee', 'srisakul', 'sawinee.ss4@gmail.com', '$2y$10$v2/kvljxvoRDA300aZIFieF46GjCI4ie20VI2Y6rH0bW.WgKE.rDa'),
('admin', 'sawineeadmin', 'srisakul', 'sawinee.ss1@gmail.com', '$2y$10$v2/kvljxvoRDA300aZIFieF46GjCI4ie20VI2Y6rH0bW.WgKE.rDa'),
('admin', 'sawineeadmintwo', 'testtwo', 'sawinee.ss2@gmail.com', '$2y$10$v2/kvljxvoRDA300aZIFieF46GjCI4ie20VI2Y6rH0bW.WgKE.rDa');

-- Insert sample data into Books table
INSERT INTO Books (BookTitle, Author, Publisher, Language, Category, CoverImagePath)
VALUES 
('great expectations', 'charles dickens', 'macmillan collectors library', 'english', 'fiction', 'images-cover/book_1.png'),
('an inconvenient truth', 'al gore', 'penguin books', 'english', 'nonfiction', 'images-cover/book_2.png'),
('oxford dictionary', 'oxford press', 'oxford press', 'english', 'reference', 'images-cover/book_3.png'),
('anna karenina', 'leo tolstoy', 'star publishing', 'russian', 'fiction', 'images-cover/book_4.png'),
('the tale of genji', 'murasaki shikibu', 'kinokuniya', 'japanese', 'fiction', 'images-cover/book_5.png');

-- Insert sample data into BookStatus table
-- Insert sample data into BookStatus table with all books set as available
INSERT INTO BookStatus (BookID, MemberID, Status, BorrowedDate, ReturnedDate)
VALUES 
    (1, 2, 'available', NULL, NULL),  -- "Book 1" available
    (2, 2, 'available', NULL, NULL),  -- "Book 2" available
    (3, 2, 'available', NULL, NULL),  -- "Book 3" available
    (4, 2, 'available', NULL, NULL),  -- "Book 4" available
    (5, 2, 'available', NULL, NULL);  -- "Book 5" available