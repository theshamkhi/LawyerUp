CREATE TABLE User (
    UserID INT AUTO_INCREMENT PRIMARY KEY,
    Name VARCHAR(50) NOT NULL,
    Username VARCHAR(50) UNIQUE NOT NULL,
    Email VARCHAR(100) UNIQUE NOT NULL,
    Password VARCHAR(100) NOT NULL,
    Role ENUM('Client', 'Lawyer') NOT NULL
);

CREATE TABLE Lawyer (
    LawyerID INT PRIMARY KEY,
    Specialization VARCHAR(100),
    PhotoURL VARCHAR(255),
    Rating INT CHECK (Rating >= 0 AND Rating <= 5),
    ExpYears INT CHECK (ExpYears >= 0),
    Bio TEXT,
    PhoneNumber VARCHAR(100),
    FOREIGN KEY (LawyerID) REFERENCES User(UserID) ON DELETE CASCADE
);

CREATE TABLE Reservation (
    ReservationID INT AUTO_INCREMENT PRIMARY KEY,
    ClientID INT NOT NULL,
    LawyerID INT NOT NULL,
    ReservationDate DATETIME NOT NULL,
    Status ENUM('Pending', 'Confirmed', 'Cancelled', 'Completed') NOT NULL,
    FOREIGN KEY (ClientID) REFERENCES User(UserID) ON DELETE CASCADE,
    FOREIGN KEY (LawyerID) REFERENCES Lawyer(LawyerID) ON DELETE CASCADE
);

CREATE TABLE Availability (
    AvailabilityID INT AUTO_INCREMENT PRIMARY KEY,
    LawyerID INT NOT NULL,
    FromTime DATETIME NOT NULL,
    ToTime DATETIME NOT NULL,
    Status ENUM('Available', 'Booked', 'Inactive') NOT NULL,
    FOREIGN KEY (LawyerID) REFERENCES Lawyer(LawyerID) ON DELETE CASCADE
);
