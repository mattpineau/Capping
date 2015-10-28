--Authors: Matt Pineau--
--Date: 10/26/15--
--Project: Capping (Database)--
--Professor: Dr. Rivas--

--Drop all tables if they already exist--
DROP TABLE IF EXISTS CoreRequirements;
DROP TABLE IF EXISTS CountsToward;
DROP TABLE IF EXISTS CoursesTaken;
DROP TABLE IF EXISTS TransfersTo;
DROP TABLE IF EXISTS MaristCourses;
DROP TABLE IF EXISTS InstitutionCourses;
DROP TABLE IF EXISTS MajorSelections;
DROP TABLE IF EXISTS MinorSelections;
DROP TABLE IF EXISTS MinorRequirements;
DROP TABLE IF EXISTS MaristMinors;
DROP TABLE IF EXISTS ConcentrationRequirements;
DROP TABLE IF EXISTS Requirements;
DROP TABLE IF EXISTS MajorConcentrations;
DROP TABLE IF EXISTS MaristMajors;
DROP TABLE IF EXISTS TransferReports;
DROP TABLE IF EXISTS Admins;
DROP TABLE IF EXISTS Students;
DROP TABLE IF EXISTS Institutions;
DROP TABLE IF EXISTS Addresses;
DROP TABLE IF EXISTS Users;


--Drop all types if they already exist--
DROP TYPE IF EXISTS typeOfDegree;


--Create any necessary enumerations (types)--
CREATE TYPE typeOfDegree as ENUM ('BA', 'BS');


--Create Tables--

CREATE TABLE Users(
    userId         SERIAL    NOT NULL,
    userFirstName  TEXT      NOT NULL,
    userLastName   TEXT      NOT NULL,
    email          TEXT      NOT NULL,
    password       TEXT      NOT NULL,
    createdOn      TIMESTAMP WITHOUT TIME ZONE    NOT NULL    DEFAULT (now() at time zone 'utc'),
    lastUpdated    TIMESTAMP WITHOUT TIME ZONE    NOT NULL    DEFAULT (now() at time zone 'utc'),
    PRIMARY KEY    (userId)
);

CREATE TABLE Addresses(
    addressId      SERIAL    NOT NULL,
    addressStreet  TEXT      NOT NULL,
    addressCity    TEXT      NOT NULL,
    addressZip     TEXT      NOT NULL,
    addressState   TEXT      NOT NULL,
    createdOn      TIMESTAMP WITHOUT TIME ZONE    NOT NULL    DEFAULT (now() at time zone 'utc'),
    lastUpdated    TIMESTAMP WITHOUT TIME ZONE    NOT NULL    DEFAULT (now() at time zone 'utc'),
    PRIMARY KEY(addressId)
);
CREATE TABLE Institutions(
    institutionId         SERIAL     NOT NULL,
    institutionAddress    INTEGER    NOT NULL    REFERENCES Addresses (addressId),
    institutionName       TEXT       NOT NULL,
    createdOn      TIMESTAMP WITHOUT TIME ZONE    NOT NULL    DEFAULT (now() at time zone 'utc'),
    lastUpdated    TIMESTAMP WITHOUT TIME ZONE    NOT NULL    DEFAULT (now() at time zone 'utc'),
    PRIMARY KEY(institutionId)
);

CREATE TABLE Students(
    studentId               INTEGER    NOT NULL    REFERENCES Users (userId),
    studentInstitutionId    INTEGER    NOT NULL    REFERENCES Institutions (institutionId),
    homeAddressId           INTEGER    REFERENCES Addresses (addressId),
    studentPhone            TEXT,
    dateOfBirth             DATE       NOT NULL,
    createdOn               TIMESTAMP WITHOUT TIME ZONE    NOT NULL    DEFAULT (now() at time zone 'utc'),
    lastUpdated             TIMESTAMP WITHOUT TIME ZONE    NOT NULL    DEFAULT (now() at time zone 'utc'),
    PRIMARY KEY(studentId)
);

CREATE TABLE Admins(
    adminId        INTEGER                        NOT NULL    REFERENCES Users (userId),
    createdOn      TIMESTAMP WITHOUT TIME ZONE    NOT NULL    DEFAULT (now() at time zone 'utc'),
    lastUpdated    TIMESTAMP WITHOUT TIME ZONE    NOT NULL    DEFAULT (now() at time zone 'utc'),
    PRIMARY KEY(adminId)
);


CREATE TABLE TransferReports(
    transferReportId    SERIAL    NOT NULL,
    studentId           INTEGER   NOT NULL    REFERENCES Students (studentId),
    reportName          TEXT      NOT NULL,
    createdOn           TIMESTAMP WITHOUT TIME ZONE    NOT NULL    DEFAULT (now() at time zone 'utc'),
    lastUpdated         TIMESTAMP WITHOUT TIME ZONE    NOT NULL    DEFAULT (now() at time zone 'utc'),
    PRIMARY KEY(transferReportId)
);

CREATE TABLE MaristMajors(
    majorId        SERIAL          NOT NULL,
    majorName      TEXT            NOT NULL,
    school         TEXT            NOT NULL,
    BAorBS         typeOfDegree    NOT NULL,
    createdOn      TIMESTAMP WITHOUT TIME ZONE    NOT NULL    DEFAULT (now() at time zone 'utc'),
    lastUpdated    TIMESTAMP WITHOUT TIME ZONE    NOT NULL    DEFAULT (now() at time zone 'utc'),
    PRIMARY KEY(majorId)
);

CREATE TABLE MajorConcentrations(
    concentrationId      SERIAL    NOT NULL,
    majorId              INTEGER   NOT NULL    REFERENCES MaristMajors (majorId),
    concentrationName    TEXT      NOT NULL,
    createdOn      TIMESTAMP WITHOUT TIME ZONE    NOT NULL    DEFAULT (now() at time zone 'utc'),
    lastUpdated    TIMESTAMP WITHOUT TIME ZONE    NOT NULL    DEFAULT (now() at time zone 'utc'),
    PRIMARY KEY(concentrationId)
);

CREATE TABLE Requirements(
    requirementId         SERIAL     NOT NULL,
    requirementName       TEXT       NOT NULL,
    numCreditsRequired    INTEGER    NOT NULL,
    createdOn      TIMESTAMP WITHOUT TIME ZONE    NOT NULL    DEFAULT (now() at time zone 'utc'),
    lastUpdated    TIMESTAMP WITHOUT TIME ZONE    NOT NULL    DEFAULT (now() at time zone 'utc'),
    PRIMARY KEY(requirementId)
);

CREATE TABLE ConcentrationRequirements(
    concentrationRequirementId    INTEGER    NOT NULL    REFERENCES Requirements (requirementId),
    concentrationId               INTEGER    NOT NULL    REFERENCES MajorConcentrations (concentrationId),
    createdOn       TIMESTAMP WITHOUT TIME ZONE    NOT NULL    DEFAULT (now() at time zone 'utc'),
    lastUpdated     TIMESTAMP WITHOUT TIME ZONE    NOT NULL    DEFAULT (now() at time zone 'utc'),
    PRIMARY KEY(concentrationRequirementId)
);
CREATE TABLE MaristMinors(
    minorId    SERIAL    NOT NULL,
    minorName  TEXT      NOT NULL,
    createdOn      TIMESTAMP WITHOUT TIME ZONE    NOT NULL    DEFAULT (now() at time zone 'utc'),
    lastUpdated    TIMESTAMP WITHOUT TIME ZONE    NOT NULL    DEFAULT (now() at time zone 'utc'),
    PRIMARY KEY(minorId)
);

CREATE TABLE MinorRequirements(
    minorRequirementId    INTEGER    NOT NULL    REFERENCES Requirements (requirementId),
    minorID               INTEGER    NOT NULL    REFERENCES MaristMinors (minorId),
    createdOn      TIMESTAMP WITHOUT TIME ZONE    NOT NULL    DEFAULT (now() at time zone 'utc'),
    lastUpdated    TIMESTAMP WITHOUT TIME ZONE    NOT NULL    DEFAULT (now() at time zone 'utc'),
    PRIMARY KEY(minorRequirementId)
);

CREATE TABLE MajorSelections(
    majorId            INTEGER    NOT NULL    REFERENCES MaristMajors (majorId),
    transferReportId   INTEGER    NOT NULL    REFERENCES TransferReports (transferReportId),
    createdOn      TIMESTAMP WITHOUT TIME ZONE    NOT NULL    DEFAULT (now() at time zone 'utc'),
    lastUpdated    TIMESTAMP WITHOUT TIME ZONE    NOT NULL    DEFAULT (now() at time zone 'utc'),
    PRIMARY KEY(majorId, transferReportId)
);

CREATE TABLE MinorSelections(
    minorID             INTEGER    NOT NULL    REFERENCES MaristMinors (minorId),
    transferReportId    INTEGER    NOT NULL    REFERENCES TransferReports (transferReportId),
    createdOn      TIMESTAMP WITHOUT TIME ZONE    NOT NULL    DEFAULT (now() at time zone 'utc'),
    lastUpdated    TIMESTAMP WITHOUT TIME ZONE    NOT NULL    DEFAULT (now() at time zone 'utc'),
    PRIMARY KEY(minorId, transferReportId)
);

CREATE TABLE InstitutionCourses(
    courseId         SERIAL     NOT NULL,
    institutionId    INTEGER    NOT NULL    REFERENCES Institutions (institutionId),
    courseTerm       TEXT       NOT NULL,
    courseTitle      TEXT       NOT NULL,
    courseNum        TEXT       NOT NULL,
    createdOn      TIMESTAMP WITHOUT TIME ZONE    NOT NULL    DEFAULT (now() at time zone 'utc'),
    lastUpdated    TIMESTAMP WITHOUT TIME ZONE    NOT NULL    DEFAULT (now() at time zone 'utc'),
    PRIMARY KEY(courseId)
);

CREATE TABLE MaristCourses(
    maristCourseId         SERIAL     NOT NULL,
    maristCourseTitle      TEXT       NOT NULL,
    maristCourseNum        TEXT       NOT NULL,
    maristCourseSubject    TEXT       NOT NULL,
    numCredits             INTEGER    DEFAULT 3,
    createdOn      TIMESTAMP WITHOUT TIME ZONE    NOT NULL    DEFAULT (now() at time zone 'utc'),
    lastUpdated    TIMESTAMP WITHOUT TIME ZONE    NOT NULL    DEFAULT (now() at time zone 'utc'),
    PRIMARY KEY(maristCourseId)
);

CREATE TABLE TransfersTo(
    courseId          INTEGER    NOT NULL    REFERENCES InstitutionCourses (courseId),
    maristCourseId    INTEGER    NOT NULL    REFERENCES MaristCourses (maristCourseId),
    createdOn      TIMESTAMP WITHOUT TIME ZONE    NOT NULL    DEFAULT (now() at time zone 'utc'),
    lastUpdated    TIMESTAMP WITHOUT TIME ZONE    NOT NULL    DEFAULT (now() at time zone 'utc'),
    PRIMARY KEY(courseId, maristCourseId)
);

CREATE TABLE CoursesTaken(
    transferReportId    INTEGER    NOT NULL    REFERENCES TransferReports (transferReportId),
    courseId            INTEGER    NOT NULL    REFERENCES InstitutionCourses (courseId),
    createdOn      TIMESTAMP WITHOUT TIME ZONE    NOT NULL    DEFAULT (now() at time zone 'utc'),
    lastUpdated    TIMESTAMP WITHOUT TIME ZONE    NOT NULL    DEFAULT (now() at time zone 'utc'),
    PRIMARY KEY(transferReportId, courseId)
);

CREATE TABLE CountsToward(
    maristCourseId    INTEGER    NOT NULL    REFERENCES MaristCourses (maristCourseId),
    requirementId     INTEGER    NOT NULL    REFERENCES Requirements (requirementId),
    createdOn      TIMESTAMP WITHOUT TIME ZONE    NOT NULL    DEFAULT (now() at time zone 'utc'),
    lastUpdated    TIMESTAMP WITHOUT TIME ZONE    NOT NULL    DEFAULT (now() at time zone 'utc'),
    PRIMARY KEY(maristCourseId, requirementId)
);

CREATE TABLE CoreRequirements(
    coreRequirementId    INTEGER    NOT NULL    REFERENCES Requirements (requirementId),
    createdOn      TIMESTAMP WITHOUT TIME ZONE    NOT NULL    DEFAULT (now() at time zone 'utc'),
    lastUpdated    TIMESTAMP WITHOUT TIME ZONE    NOT NULL    DEFAULT (now() at time zone 'utc'),
    PRIMARY KEY(coreRequirementId)
);


-- Insert test data --

INSERT INTO Users (userFirstName, userLastName, email, password)
VALUES ('Steve', 'Harvey', 'steve.harvey@familyfeud.com', 'nakedGrandma1'),
       ('Jerry', 'Springer', 'jerry.springer@baggage.com', 'imAHorriblePerson');

INSERT INTO Addresses (addressStreet, addressCity, addressZip, addressState)
VALUES ('24 Main Street', 'Orlando', '32801', 'Florida'),
       ('53 Pendell Road', 'Poughkeepsie', '12601', 'New York');

INSERT INTO Institutions (institutionAddress, institutionName)
VALUES (2, 'Dutchess Community College');

INSERT INTO Students (studentId, studentInstitutionId, homeAddressId, studentPhone, dateOfBirth)
VALUES (1, 1, 1, '5555550147', '12 Jul 1996');

INSERT INTO Admins (adminId)
VALUES (2);

INSERT INTO TransferReports (studentId, reportName)
VALUES (1, 'myFirstReport');

INSERT INTO MaristMajors (majorName, school, BAorBS)
VALUES ('Computer Science', 'School of Computer Science and Mathematics', 'BS');

INSERT INTO MajorConcentrations (majorId, concentrationName)
VALUES (1, 'Software Development');

INSERT INTO Requirements (requirementName, numCreditsRequired)
VALUES ('Philosophy', 3), --Core
       ('Ethics', 3), --Core
       ('College Writing', 6), --Core
       ('Literature', 6), --Core
       ('Fine Arts', 3), --Core
       ('Social Science', 6), --Core
       ('Philosophy and Religious Studies', 3), --Core
       ('History', 6), --Core
       ('Natural Science', 6), --Core
       ('Cultural Diversity', 3), --Core
       ('Introduction to Programming', 4), --CompSci(SoftDev)
       ('Software Development 1', 4), --CompSci(SoftDev)
       ('Software Development 2', 4), --CompSci(SoftDev)
       ('Software Systems and Analysis', 4), --CompSci(SoftDev)
       ('Data Communications and Networks', 4), --CompSci(SoftDev)
       ('Database Management', 4), --CompSci(SoftDev)
       ('Internetworking', 4), --CompSci(SoftDev)
       ('System Design', 4), --CompSci(SoftDev)
       ('Computer Organization and Architecture', 4), --CompSci(SoftDev)
       ('Algorithm Analysis and Design', 4), --CompSci(SoftDev)
       ('System Elective Option', 4), --CompSci(SoftDev)
       ('Language Elective Option', 3), --CompSci(SoftDev)
       ('Third Required Elective', 3), --CompSci(SoftDev)
       ('Project 1', 3), --CompSci(SoftDev)
       ('Project 2', 1), --CompSci(SoftDev)
       ('Introduction to Business and Management', 3), --CompSci(SoftDev)
       ('Introduction to Statistics', 3), --CompSci(SoftDev)
       ('Calculus 1', 4), --CompSci(SoftDev)
       ('Discrete Mathematics', 4), --CompSci(SoftDev)
       ('Calculus 1', 4), --Math Minor
       ('Calculus 2', 4), --Math Minor
       ('Calculus 3', 4), --Math Minor
       ('Introduction to Mathematics Reasoning', 3), --Math Minor
       ('Linear Algebra or Discrete Mathematics', 3); --Math Minor